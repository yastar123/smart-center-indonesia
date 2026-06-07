<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $q = Announcement::with('pembuat', 'cabang')
                ->when($request->search, fn($q) => $q->where('judul', 'like', "%{$request->search}%"))
                ->when($request->jenis,  fn($q) => $q->where('jenis', $request->jenis))
                ->when($request->status, fn($q) => $q->where('status', $request->status))
                ->orderByDesc('is_pinned')
                ->latest();

            $announcements = $q->paginate(15);
            $stats = [
                'total'  => Announcement::count(),
                'aktif'  => Announcement::where('status', 'aktif')->count(),
                'pinned' => Announcement::where('is_pinned', true)->count(),
            ];
            return response()->json(array_merge($announcements->toArray(), ['stats' => $stats]));
        }

        $branches = Branch::orderBy('name')->get();
        return view('admin.announcements.index', compact('branches'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul'          => 'required|string|max:200',
            'konten'         => 'required|string',
            'jenis'          => 'required|in:info,promo,penting,update',
            'target'         => 'required|in:semua,admin,guru,siswa',
            'tanggal_mulai'  => 'nullable|date',
            'tanggal_selesai'=> 'nullable|date|after_or_equal:tanggal_mulai',
            'is_pinned'      => 'nullable|boolean',
            'status'         => 'required|in:aktif,draft,arsip',
            'cabang_id'      => 'nullable|exists:branches,id',
            'file'           => 'nullable|file|max:10240',
        ]);

        $data['dibuat_oleh'] = auth()->id();
        $data['is_pinned']   = $request->boolean('is_pinned');

        if ($request->hasFile('file')) {
            $data['file'] = $request->file('file')->store('announcements', 'public');
        }

        Announcement::create($data);
        return response()->json(['success' => true, 'message' => 'Pengumuman berhasil ditambahkan!']);
    }

    public function show(Announcement $announcement)
    {
        return response()->json(['success' => true, 'data' => $announcement->load('pembuat', 'cabang')]);
    }

    public function update(Request $request, Announcement $announcement)
    {
        $data = $request->validate([
            'judul'          => 'required|string|max:200',
            'konten'         => 'required|string',
            'jenis'          => 'required|in:info,promo,penting,update',
            'target'         => 'required|in:semua,admin,guru,siswa',
            'tanggal_mulai'  => 'nullable|date',
            'tanggal_selesai'=> 'nullable|date',
            'is_pinned'      => 'nullable|boolean',
            'status'         => 'required|in:aktif,draft,arsip',
            'cabang_id'      => 'nullable|exists:branches,id',
        ]);

        $data['is_pinned'] = $request->boolean('is_pinned');
        $announcement->update($data);
        return response()->json(['success' => true, 'message' => 'Pengumuman berhasil diperbarui!']);
    }

    public function destroy(Announcement $announcement)
    {
        if ($announcement->file) Storage::disk('public')->delete($announcement->file);
        $announcement->delete();
        return response()->json(['success' => true, 'message' => 'Pengumuman berhasil dihapus!']);
    }
}
