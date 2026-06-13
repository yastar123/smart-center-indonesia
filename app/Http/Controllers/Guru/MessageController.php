<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\ChatRoom;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index()
    {
        $rooms = ChatRoom::with(['pesan' => fn($q) => $q->latest()->limit(1)])
            ->where(function ($q) {
                $q->whereJsonContains('peserta_id', (int) auth()->id())
                  ->orWhere('jenis_room', 'broadcast');
            })
            ->orderByDesc('waktu_pesan_terakhir')
            ->get();

        $users = $this->getStudentContacts();

        $messageBaseUrl     = url('guru/messages');
        $messageRoomsUrl    = url('guru/messages/rooms');
        $messageCreateRoute = route('guru.messages.createRoom');
        $allowCreateRoom    = true;

        return view('admin.messages.index', compact(
            'rooms', 'users', 'messageBaseUrl', 'messageRoomsUrl', 'messageCreateRoute', 'allowCreateRoom'
        ));
    }

    private function getStudentContacts(): \Illuminate\Support\Collection
    {
        $teacher = Teacher::where('user_id', auth()->id())->first();

        if ($teacher) {
            // Primary: students enrolled in teacher's classes via class_students pivot
            $classIds = DB::table('school_classes')
                ->where('guru_id', $teacher->id)
                ->pluck('id');

            if ($classIds->isNotEmpty()) {
                $studentIds = DB::table('class_students')
                    ->whereIn('class_id', $classIds)
                    ->pluck('student_id');

                if ($studentIds->isNotEmpty()) {
                    $userIds = Student::whereIn('id', $studentIds)->pluck('user_id');
                    $users   = User::whereIn('id', $userIds)
                        ->where('id', '!=', auth()->id())
                        ->orderBy('name')
                        ->get();
                    if ($users->isNotEmpty()) return $users;
                }
            }

            // Fallback: all students in teacher's branch
            if ($teacher->branch_id) {
                $siswaIds = Student::where('branch_id', $teacher->branch_id)->pluck('user_id');
                $users    = User::whereIn('id', $siswaIds)
                    ->where('id', '!=', auth()->id())
                    ->orderBy('name')
                    ->get();
                if ($users->isNotEmpty()) return $users;
            }
        }

        // Final fallback: all users with siswa role
        return User::role('siswa')
            ->where('id', '!=', auth()->id())
            ->orderBy('name')
            ->get();
    }
}
