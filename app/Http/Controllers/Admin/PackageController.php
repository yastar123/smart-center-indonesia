<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Branch;
use App\Models\Course;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $q = Package::with('cabang')
                ->when($request->search,    fn($q) => $q->where('nama', 'like', "%{$request->search}%"))
                ->when($request->jenis,     fn($q) => $q->where('jenis', $request->jenis))
                ->when($request->status,    fn($q) => $q->where('status', $request->status))
                ->when($request->cabang_id, fn($q) => $q->where('cabang_id', $request->cabang_id))
                ->latest();

            $packages = $q->paginate(12);
            $stats = [
                'total'   => Package::count(),
                'aktif'   => Package::where('status', 'aktif')->count(),
                'unggulan'=> Package::where('is_unggulan', true)->count(),
                'avg_price'=> Package::avg('harga'),
            ];
            return response()->json(array_merge($packages->toArray(), ['stats' => $stats]));
        }

        $branches = Branch::orderBy('name')->get();
        $courses  = Course::orderBy('nama')->get();
        return view('admin.packages.index', compact('branches', 'courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'             => 'required|string|max:150',
            'deskripsi'        => 'nullable|string',
            'harga'            => 'required|numeric|min:0',
            'durasi_bulan'     => 'required|integer|min:1',
            'jumlah_pertemuan' => 'required|integer|min:1',
            'jenis'            => 'required|string|max:50',
            'cabang_id'        => 'nullable|exists:branches,id',
            'is_unggulan'      => 'nullable|boolean',
            'status'           => 'required|in:aktif,nonaktif',
            'fitur'            => 'nullable|array',
        ]);

        $data['is_unggulan'] = $request->boolean('is_unggulan');
        Package::create($data);
        return response()->json(['success' => true, 'message' => 'Paket berhasil ditambahkan!']);
    }

    public function show(Package $package)
    {
        return response()->json(['success' => true, 'data' => $package->load('cabang')]);
    }

    public function update(Request $request, Package $package)
    {
        $data = $request->validate([
            'nama'             => 'required|string|max:150',
            'deskripsi'        => 'nullable|string',
            'harga'            => 'required|numeric|min:0',
            'durasi_bulan'     => 'required|integer|min:1',
            'jumlah_pertemuan' => 'required|integer|min:1',
            'jenis'            => 'required|string|max:50',
            'cabang_id'        => 'nullable|exists:branches,id',
            'is_unggulan'      => 'nullable|boolean',
            'status'           => 'required|in:aktif,nonaktif',
            'fitur'            => 'nullable|array',
        ]);

        $data['is_unggulan'] = $request->boolean('is_unggulan');
        $package->update($data);
        return response()->json(['success' => true, 'message' => 'Paket berhasil diperbarui!']);
    }

    public function destroy(Package $package)
    {
        $package->delete();
        return response()->json(['success' => true, 'message' => 'Paket berhasil dihapus!']);
    }
}
