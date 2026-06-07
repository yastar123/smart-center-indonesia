<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $users = User::where('id', '!=', auth()->id())->get();

        return view('admin.messages.index', compact('rooms', 'users'));
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

        // Mark as read
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
            $msg['jenis']     = in_array($request->file('file')->getMimeType(), ['image/jpeg', 'image/png', 'image/gif']) ? 'gambar' : 'file';
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
            'peserta_id' => 'required|array',
            'cabang_id'  => 'nullable|exists:branches,id',
        ]);

        $peserta = $data['peserta_id'];
        if (!in_array(auth()->id(), $peserta)) {
            $peserta[] = auth()->id();
        }
        $data['peserta_id'] = $peserta;

        $room = ChatRoom::create($data);
        return response()->json(['success' => true, 'message' => 'Room berhasil dibuat!', 'data' => $room]);
    }
}
