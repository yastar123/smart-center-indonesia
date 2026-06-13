<?php

namespace App\Http\Controllers\Siswa;

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

        $users = $this->getTeacherContacts();

        $messageBaseUrl     = url('siswa/messages');
        $messageRoomsUrl    = url('siswa/messages/rooms');
        $messageCreateRoute = route('siswa.messages.createRoom');
        $allowCreateRoom    = true;

        return view('admin.messages.index', compact(
            'rooms', 'users', 'messageBaseUrl', 'messageRoomsUrl', 'messageCreateRoute', 'allowCreateRoom'
        ));
    }

    private function getTeacherContacts(): \Illuminate\Support\Collection
    {
        $student = Student::where('user_id', auth()->id())->first();

        if ($student) {
            // Primary: teachers of classes this student is enrolled in
            $classIds = DB::table('class_students')
                ->where('student_id', $student->id)
                ->pluck('class_id');

            if ($classIds->isNotEmpty()) {
                $teacherIds = DB::table('school_classes')
                    ->whereIn('id', $classIds)
                    ->whereNotNull('guru_id')
                    ->pluck('guru_id');

                if ($teacherIds->isNotEmpty()) {
                    $userIds = Teacher::whereIn('id', $teacherIds)->pluck('user_id');
                    $users   = User::whereIn('id', $userIds)
                        ->where('id', '!=', auth()->id())
                        ->orderBy('name')
                        ->get();
                    if ($users->isNotEmpty()) return $users;
                }
            }

            // Fallback: all teachers in student's branch
            if ($student->branch_id) {
                $teacherUserIds = Teacher::where('branch_id', $student->branch_id)->pluck('user_id');
                $users          = User::whereIn('id', $teacherUserIds)
                    ->where('id', '!=', auth()->id())
                    ->orderBy('name')
                    ->get();
                if ($users->isNotEmpty()) return $users;
            }
        }

        // Final fallback: all users with guru role
        return User::role('guru')
            ->where('id', '!=', auth()->id())
            ->orderBy('name')
            ->get();
    }
}
