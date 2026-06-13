<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index()
    {
        $authUser = auth()->user();

        $rooms = ChatRoom::with(['pesan' => fn($q) => $q->latest()->limit(1)])
            ->where(function ($q) {
                $q->whereJsonContains('peserta_id', (int) auth()->id())
                  ->orWhere('jenis_room', 'broadcast');
            })
            ->orderByDesc('waktu_pesan_terakhir')
            ->get();

        $users = $this->getContactsForAdmin($authUser);

        $messageBaseUrl     = url('admin/messages');
        $messageRoomsUrl    = url('admin/messages/rooms');
        $messageCreateRoute = route('admin.messages.createRoom');
        $allowCreateRoom    = true;

        return view('admin.messages.index', compact(
            'rooms', 'users', 'messageBaseUrl', 'messageRoomsUrl', 'messageCreateRoute', 'allowCreateRoom'
        ));
    }

    private function getContactsForAdmin(User $authUser): \Illuminate\Support\Collection
    {
        $branchId = $authUser->branch_id;

        if ($branchId) {
            // Admin Cabang: show gurus and students in their branch
            $guruIds  = Teacher::where('branch_id', $branchId)->pluck('user_id');
            $siswaIds = Student::where('branch_id', $branchId)->pluck('user_id');
            $userIds  = $guruIds->merge($siswaIds)->unique()->filter();

            if ($userIds->isNotEmpty()) {
                return User::whereIn('id', $userIds)
                    ->where('id', '!=', $authUser->id)
                    ->orderBy('name')
                    ->get();
            }

            // Fallback: role-based + branch_id on User
            return User::role(['guru', 'siswa'])
                ->where('branch_id', $branchId)
                ->where('id', '!=', $authUser->id)
                ->orderBy('name')
                ->get();
        }

        // Admin Pusat: show all admin cabang, gurus, and students
        return User::role(['admin', 'guru', 'siswa'])
            ->where('id', '!=', $authUser->id)
            ->orderBy('name')
            ->get();
    }

    public function getMessages(ChatRoom $room)
    {
        $messages = $room->pesan()
            ->with('pengirim')
            ->latest()
            ->limit(50)
            ->get()
            ->reverse()
            ->values();

        foreach ($messages as $msg) {
            $read = $msg->dibaca_oleh ?? [];
            if (!in_array(auth()->id(), $read)) {
                $read[] = auth()->id();
                $msg->update(['dibaca_oleh' => $read]);
            }
        }

        return response()->json(['success' => true, 'data' => $messages]);
    }

    public function sendMessage(Request $request, ChatRoom $room)
    {
        $data = $request->validate([
            'pesan' => 'nullable|string|max:2000',
            'file'  => 'nullable|file|max:10240',
            'jenis' => 'nullable|in:teks,file,gambar',
        ]);

        $msg = [
            'room_id'     => $room->id,
            'pengirim_id' => auth()->id(),
            'jenis'       => $data['jenis'] ?? 'teks',
            'pesan'       => $data['pesan'] ?? null,
            'dibaca_oleh' => [auth()->id()],
        ];

        if ($request->hasFile('file')) {
            $msg['file_path'] = $request->file('file')->store('chat', 'public');
            $msg['jenis']     = in_array($request->file('file')->getMimeType(), ['image/jpeg', 'image/png', 'image/gif'])
                ? 'gambar' : 'file';
        }

        $message = ChatMessage::create($msg);
        $room->update(['waktu_pesan_terakhir' => now()]);

        return response()->json(['success' => true, 'data' => $message->load('pengirim')]);
    }

    public function createRoom(Request $request)
    {
        $data = $request->validate([
            'nama_room'  => 'required|string|max:100',
            'jenis_room' => 'required|in:personal,grup,broadcast',
            'peserta_id' => 'nullable|array',
            'cabang_id'  => 'nullable|exists:branches,id',
        ]);

        $peserta = array_map('intval', $data['peserta_id'] ?? []);
        if (!in_array((int) auth()->id(), $peserta)) {
            $peserta[] = (int) auth()->id();
        }
        $data['peserta_id'] = $peserta;

        $room = ChatRoom::create($data);
        return response()->json(['success' => true, 'message' => 'Room berhasil dibuat!', 'data' => $room]);
    }

    public function getRooms()
    {
        $rooms = ChatRoom::with(['pesan' => fn($q) => $q->latest()->limit(1)])
            ->where(function ($q) {
                $q->whereJsonContains('peserta_id', (int) auth()->id())
                  ->orWhere('jenis_room', 'broadcast');
            })
            ->orderByDesc('waktu_pesan_terakhir')
            ->get();

        return response()->json(['rooms' => $rooms]);
    }
}
