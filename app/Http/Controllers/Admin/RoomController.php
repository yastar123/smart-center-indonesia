<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('branch')->latest()->paginate(20);
        $stats = [
            'total'       => Room::count(),
            'aktif'       => Room::where('status', 'aktif')->count(),
            'maintenance' => Room::where('status', 'maintenance')->count(),
        ];
        return view('admin.rooms.index', compact('rooms', 'stats'));
    }

    public function create()
    {
        return view('admin.rooms.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_ruangan' => 'required|string|max:100',
            'kapasitas'    => 'required|integer|min:1',
            'status'       => 'required|in:aktif,maintenance',
            'keterangan'   => 'nullable|string|max:500',
        ]);

        Room::create($data);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function show(Room $room)
    {
        $room->load('branch');
        return view('admin.rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $data = $request->validate([
            'nama_ruangan' => 'required|string|max:100',
            'kapasitas'    => 'required|integer|min:1',
            'status'       => 'required|in:aktif,maintenance',
            'keterangan'   => 'nullable|string|max:500',
        ]);

        $room->update($data);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('admin.rooms.index')
            ->with('success', 'Ruangan berhasil dihapus.');
    }
}
