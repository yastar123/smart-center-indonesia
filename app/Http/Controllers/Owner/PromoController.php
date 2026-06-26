<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromoController extends Controller
{
    public function index(Request $request)
    {
        $query = Promo::with('cabang')->latest();

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        $promos = $query->paginate(15)->withQueryString();
        return view('owner.promo.index', compact('promos'));
    }

    public function create()
    {
        $branches = Branch::orderBy('name')->get();
        return view('owner.promo.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'            => 'required|string|max:255',
            'tipe'             => 'required|in:diskon,bundle_upgrade,special_price,lainnya',
            'kode_promo'       => 'nullable|string|max:50',
            'deskripsi'        => 'nullable|string',
            'banner'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'tanggal_mulai'    => 'required|date',
            'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_mulai',
            'target'           => 'required|in:semua,paket_intensif,cabang,cicilan',
            'cabang_id'        => 'nullable|exists:branches,id',
            'status'           => 'required|in:draft,aktif',
        ]);

        $bannerPath = null;
        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('promo-banners', 'public');
        }

        Promo::create([
            'kode'             => Promo::generateKode(),
            'judul'            => $request->judul,
            'tipe'             => $request->tipe,
            'kode_promo'       => $request->kode_promo,
            'deskripsi'        => $request->deskripsi,
            'banner_path'      => $bannerPath,
            'tanggal_mulai'    => $request->tanggal_mulai,
            'tanggal_berakhir' => $request->tanggal_berakhir,
            'target'           => $request->target,
            'cabang_id'        => $request->target === 'cabang' ? $request->cabang_id : null,
            'status'           => $request->status,
        ]);

        return redirect()->route('owner.promo.index')
            ->with('success', 'Promo berhasil ' . ($request->status === 'aktif' ? 'dipublikasikan' : 'disimpan sebagai draft') . '.');
    }

    public function show(Promo $promo)
    {
        $branches = Branch::orderBy('name')->get();
        return view('owner.promo.show', compact('promo', 'branches'));
    }

    public function edit(Promo $promo)
    {
        $branches = Branch::orderBy('name')->get();
        return view('owner.promo.edit', compact('promo', 'branches'));
    }

    public function update(Request $request, Promo $promo)
    {
        $request->validate([
            'judul'            => 'required|string|max:255',
            'tipe'             => 'required|in:diskon,bundle_upgrade,special_price,lainnya',
            'kode_promo'       => 'nullable|string|max:50',
            'deskripsi'        => 'nullable|string',
            'banner'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'tanggal_mulai'    => 'required|date',
            'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_mulai',
            'target'           => 'required|in:semua,paket_intensif,cabang,cicilan',
            'cabang_id'        => 'nullable|exists:branches,id',
            'status'           => 'required|in:draft,aktif,berakhir',
        ]);

        $bannerPath = $promo->banner_path;
        if ($request->hasFile('banner')) {
            if ($bannerPath) Storage::disk('public')->delete($bannerPath);
            $bannerPath = $request->file('banner')->store('promo-banners', 'public');
        }

        $promo->update([
            'judul'            => $request->judul,
            'tipe'             => $request->tipe,
            'kode_promo'       => $request->kode_promo,
            'deskripsi'        => $request->deskripsi,
            'banner_path'      => $bannerPath,
            'tanggal_mulai'    => $request->tanggal_mulai,
            'tanggal_berakhir' => $request->tanggal_berakhir,
            'target'           => $request->target,
            'cabang_id'        => $request->target === 'cabang' ? $request->cabang_id : null,
            'status'           => $request->status,
        ]);

        return redirect()->route('owner.promo.index')
            ->with('success', 'Promo berhasil diperbarui.');
    }

    public function destroy(Promo $promo)
    {
        if ($promo->banner_path) Storage::disk('public')->delete($promo->banner_path);
        $promo->delete();
        return back()->with('success', 'Promo dihapus.');
    }
}
