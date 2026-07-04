<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchLandingSetting;
use Illuminate\Http\Request;

class BranchLandingContentController extends Controller
{
    public function index()
    {
        $branches = Branch::orderBy('name')->get();
        return view('admin.landing.cabang-index', compact('branches'));
    }

    public function show(Branch $branch)
    {
        $s = BranchLandingSetting::forBranch($branch->id);
        return view('admin.landing.cabang', compact('branch', 's'));
    }

    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'hero_badge'       => 'nullable|string|max:200',
            'hero_description' => 'nullable|string|max:600',
            'hours_weekday'    => 'nullable|string|max:100',
            'hours_weekend'    => 'nullable|string|max:100',
            'price_homevisi'   => 'nullable|string|max:50',
            'price_online'     => 'nullable|string|max:50',
            'price_offline'    => 'nullable|string|max:50',
            'areas'            => 'nullable|string|max:1000',
            'promo_items'      => 'nullable|array',
            'promo_items.*'    => 'nullable|string|max:200',
            'faq_q'            => 'nullable|array',
            'faq_q.*'          => 'nullable|string|max:300',
            'faq_a'            => 'nullable|array',
            'faq_a.*'          => 'nullable|string|max:1000',
        ]);

        $bid = $branch->id;

        /* ── Simple text keys ── */
        $textKeys = ['hero_badge','hero_description','hours_weekday','hours_weekend',
                     'price_homevisi','price_online','price_offline'];
        foreach ($textKeys as $key) {
            BranchLandingSetting::setVal($bid, $key, $request->input($key, ''));
        }

        /* ── Areas (comma-separated → JSON array) ── */
        $areasRaw = $request->input('areas', '');
        $areas    = array_values(array_filter(array_map('trim', explode(',', $areasRaw))));
        BranchLandingSetting::setVal($bid, 'areas', json_encode($areas));

        /* ── Promo items ── */
        $promoItems = array_values(array_filter(
            array_map('trim', $request->input('promo_items', []))
        ));
        BranchLandingSetting::setVal($bid, 'promo_items', json_encode($promoItems));

        /* ── FAQ ── */
        $questions = $request->input('faq_q', []);
        $answers   = $request->input('faq_a', []);
        $faqs      = [];
        foreach ($questions as $i => $q) {
            $q = trim($q);
            $a = trim($answers[$i] ?? '');
            if ($q && $a) $faqs[] = ['q' => $q, 'a' => $a];
        }
        BranchLandingSetting::setVal($bid, 'faq_items', json_encode($faqs));

        return back()->with('success', 'Konten landing page cabang '.$branch->name.' berhasil disimpan.');
    }
}
