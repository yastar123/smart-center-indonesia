<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\ChatRoom;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $rooms = ChatRoom::with(['pesan' => fn($q) => $q->latest()->limit(1)])
            ->where(function ($q) {
                $q->whereJsonContains('peserta_id', auth()->id())
                  ->orWhere('jenis_room', 'broadcast');
            })
            ->orderByDesc('waktu_pesan_terakhir')
            ->get();

        // Contacts: for guru, list their students' user accounts
        $teacher = Teacher::where('user_id', auth()->id())->with('students.user')->first();
        $users = collect();
        if ($teacher) {
            foreach ($teacher->students as $s) {
                if ($s->user) $users->push($s->user);
            }
        }

        $messageBaseUrl = url('guru/messages');
        $messageCreateRoute = route('guru.messages.createRoom');
        $allowCreateRoom = false;
        return view('admin.messages.index', compact('rooms', 'users', 'messageBaseUrl', 'messageCreateRoute', 'allowCreateRoom'));
    }
}
