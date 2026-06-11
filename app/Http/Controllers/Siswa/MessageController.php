<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\ChatRoom;
use App\Models\Student;
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

        // Contacts: for siswa, list their teachers' user accounts
        $student = Student::where('user_id', auth()->id())->with('teachers.user')->first();
        $users = collect();
        if ($student) {
            foreach ($student->teachers as $t) {
                if ($t->user) $users->push($t->user);
            }
        }

        $messageBaseUrl = url('siswa/messages');
        $messageCreateRoute = route('siswa.messages.createRoom');
        $allowCreateRoom = true;
        return view('admin.messages.index', compact('rooms', 'users', 'messageBaseUrl', 'messageCreateRoute', 'allowCreateRoom'));
    }
}
