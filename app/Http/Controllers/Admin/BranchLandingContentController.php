<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchLandingSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BranchLandingContentController extends Controller
{
    public function index()
    {
        $branches = Branch::orderBy('name')->get();
        return view('admin.landing.cabang-index', compact('branches'));
    }

    /* ── Branch CRUD ─────────────────────────────────────────────── */

    public function storeBranch(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'city'   => 'nullable|string|max:255',
            'address'=> 'nullable|string',
            'phone'  => 'nullable|string|max:50',
            'email'  => 'nullable|email|max:255',
            'status' => 'nullable|in:aktif,nonaktif',
            'photo'  => 'nullable|image|max:4096',
        ]);

        $data = $request->only(['name','city','address','phone','email']);
        $data['status'] = $request->input('status', 'aktif');

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $path = $request->file('photo')->store('landing/cabang/photos', 'public');
            $data['photo'] = Storage::url($path);
        }

        Branch::create($data);

        return back()->with('success', 'Cabang berhasil ditambahkan.');
    }

    public function updateBranch(Request $request, Branch $branch)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'city'   => 'nullable|string|max:255',
            'address'=> 'nullable|string',
            'phone'  => 'nullable|string|max:50',
            'email'  => 'nullable|email|max:255',
            'status' => 'nullable|in:aktif,nonaktif',
            'photo'  => 'nullable|image|max:4096',
        ]);

        $data = $request->only(['name','city','address','phone','email']);
        $data['status'] = $request->input('status', $branch->status);

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $path = $request->file('photo')->store('landing/cabang/photos', 'public');
            $data['photo'] = Storage::url($path);
        }

        $branch->update($data);

        return back()->with('success', 'Cabang ' . $branch->name . ' berhasil diupdate.');
    }

    public function destroyBranch(Branch $branch)
    {
        $name = $branch->name;
        $branch->delete();
        return back()->with('success', 'Cabang ' . $name . ' berhasil dihapus.');
    }

    /* ── Branch Landing Content ──────────────────────────────────── */

    public function show(Branch $branch)
    {
        $s = BranchLandingSetting::forBranch($branch->id);
        return view('admin.landing.cabang', compact('branch', 's'));
    }

    private function uploadImage(Request $request, string $field, int $bid): void
    {
        if ($request->hasFile($field) && $request->file($field)->isValid()) {
            $path = $request->file($field)->store("landing/cabang/{$bid}", 'public');
            BranchLandingSetting::setVal($bid, $field, Storage::url($path));
        }
    }

    public function update(Request $request, Branch $branch)
    {
        $bid = $branch->id;

        /* ── Image uploads (from laptop) ── */
        foreach (['hero_bg', 'metode_img_homevisi', 'metode_img_online', 'metode_img_offline'] as $field) {
            $this->uploadImage($request, $field, $bid);
        }

        /* ── Simple text keys ── */
        foreach ([
            'hero_badge', 'hero_description',
            'hours_weekday', 'hours_weekend',
            'price_homevisi', 'price_online', 'price_offline',
            'cta_eyebrow', 'cta_title', 'cta_desc',
        ] as $key) {
            BranchLandingSetting::setVal($bid, $key, $request->input($key, ''));
        }

        /* ── Areas (comma-separated → JSON) ── */
        $areas = array_values(array_filter(array_map('trim', explode(',', $request->input('areas', '')))));
        BranchLandingSetting::setVal($bid, 'areas', json_encode($areas));

        /* ── Promo items ── */
        $promo = array_values(array_filter(array_map('trim', $request->input('promo_items', []))));
        BranchLandingSetting::setVal($bid, 'promo_items', json_encode($promo));

        /* ── FAQ ── */
        $qs   = $request->input('faq_q', []);
        $as   = $request->input('faq_a', []);
        $faqs = [];
        foreach ($qs as $i => $q) {
            $q = trim($q); $a = trim($as[$i] ?? '');
            if ($q && $a) $faqs[] = ['q' => $q, 'a' => $a];
        }
        BranchLandingSetting::setVal($bid, 'faq_items', json_encode($faqs));

        /* ── Feature cards — Dipercaya Ribuan Keluarga ── */
        $features = [];
        foreach (($request->input('feat_title', [])) as $i => $title) {
            $title = trim($title);
            if ($title) {
                $features[] = [
                    'num'   => trim($request->input('feat_num')[$i] ?? sprintf('%02d', $i + 1)),
                    'icon'  => trim($request->input('feat_icon')[$i] ?? '⭐'),
                    'title' => $title,
                    'desc'  => trim($request->input('feat_desc')[$i] ?? ''),
                ];
            }
        }
        BranchLandingSetting::setVal($bid, 'features', json_encode($features));

        /* ── Subject cards — Program Les & Kursus ── */
        $subjects = [];
        foreach (($request->input('subj_name', [])) as $i => $name) {
            $name = trim($name);
            if ($name) {
                $subjects[] = [
                    'icon'       => trim($request->input('subj_icon')[$i] ?? '📚'),
                    'name'       => $name,
                    'desc'       => trim($request->input('subj_desc')[$i] ?? ''),
                    'badge'      => trim($request->input('subj_badge')[$i] ?? ''),
                    'badge_type' => trim($request->input('subj_badge_type')[$i] ?? 'general'),
                ];
            }
        }
        BranchLandingSetting::setVal($bid, 'subjects', json_encode($subjects));

        return back()->with('success', 'Konten landing page cabang '.$branch->name.' berhasil disimpan.');
    }
}
