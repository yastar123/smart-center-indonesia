<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingFaq;
use App\Models\LandingFeature;
use App\Models\LandingGallery;
use App\Models\LandingHighlight;
use App\Models\LandingJenjang;
use App\Models\LandingProgram;
use App\Models\LandingSetting;
use App\Models\LandingTestimonial;
use App\Models\LandingTicker;
use App\Models\LandingTrust;
use App\Models\LandingWaNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingContentController extends Controller
{
    public function index()
    {
        $settings     = LandingSetting::orderBy('sort_order')->get()->keyBy('key');
        $testimonials = LandingTestimonial::orderBy('sort_order')->get();
        $programs     = LandingProgram::orderBy('sort_order')->get();
        $waNumbers    = LandingWaNumber::orderBy('sort_order')->get();
        $tickers      = LandingTicker::orderBy('sort_order')->get();
        $features     = LandingFeature::orderBy('sort_order')->get();
        $jenjangs     = LandingJenjang::orderBy('sort_order')->get();
        $trusts       = LandingTrust::orderBy('sort_order')->get();
        $highlights   = LandingHighlight::orderBy('sort_order')->get();
        $galleries    = LandingGallery::orderBy('sort_order')->get();
        $faqs         = LandingFaq::orderBy('sort_order')->get();

        return view('admin.landing.index', compact(
            'settings','testimonials','programs','waNumbers','tickers',
            'features','jenjangs','trusts','highlights','galleries','faqs'
        ));
    }

    private function storeImage(Request $request, string $field, string $folder = 'landing'): ?string
    {
        if ($request->hasFile($field)) {
            $path = $request->file($field)->store($folder, 'public');
            return Storage::url($path);
        }
        return null;
    }

    /* ─── Settings ─────────────────────────────────────────────────── */

    public function updateSettings(Request $request)
    {
        $data = $request->validate(['settings' => 'required|array', 'settings.*' => 'nullable|string|max:5000']);

        foreach ($data['settings'] as $key => $value) {
            LandingSetting::updateOrCreate(['key' => $key], ['value' => $value ?? '']);
        }

        foreach ($request->file('setting_files', []) as $key => $file) {
            if ($file) {
                $path = $file->store('landing/settings', 'public');
                LandingSetting::updateOrCreate(['key' => $key], ['value' => Storage::url($path)]);
            }
        }

        return back()->with('success', 'Pengaturan landing page berhasil disimpan.');
    }

    /* ─── Testimonials ─────────────────────────────────────────────── */

    public function storeTestimonial(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:100',
            'role'      => 'required|string|max:150',
            'text'      => 'required|string|max:1000',
            'gradient'  => 'nullable|string|max:200',
            'photo'     => 'nullable|image|max:4096',
            'is_active' => 'boolean',
        ]);
        $data['is_active']  = $request->boolean('is_active', true);
        $data['initial']    = strtoupper(substr($data['name'], 0, 1));
        $data['sort_order'] = LandingTestimonial::max('sort_order') + 1;
        $data['gradient']   = $data['gradient'] ?? 'linear-gradient(135deg,#c84ddf,#68117e)';
        $data['photo']      = $this->storeImage($request, 'photo', 'landing/testimonials');
        LandingTestimonial::create($data);
        return back()->with('success', 'Testimoni berhasil ditambahkan.');
    }

    public function updateTestimonial(Request $request, LandingTestimonial $testimonial)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'role'       => 'required|string|max:150',
            'text'       => 'required|string|max:1000',
            'gradient'   => 'nullable|string|max:200',
            'photo'      => 'nullable|image|max:4096',
            'is_active'  => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['initial']   = strtoupper(substr($data['name'], 0, 1));
        if (array_key_exists('gradient', $data) && $data['gradient'] === null) unset($data['gradient']);
        if ($photo = $this->storeImage($request, 'photo', 'landing/testimonials')) {
            $data['photo'] = $photo;
        } else {
            unset($data['photo']);
        }
        $testimonial->update($data);
        return back()->with('success', 'Testimoni berhasil diperbarui.');
    }

    public function destroyTestimonial(LandingTestimonial $testimonial)
    {
        $testimonial->delete();
        return back()->with('success', 'Testimoni berhasil dihapus.');
    }

    /* ─── Programs ─────────────────────────────────────────────────── */

    public function storeProgram(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:150',
            'description' => 'required|string|max:500',
            'badge_label' => 'required|string|max:80',
            'badge_bg'    => 'nullable|string|max:100',
            'badge_color' => 'nullable|string|max:30',
            'icon_emoji'  => 'nullable|string|max:10',
            'image'       => 'nullable|image|max:4096',
            'is_active'   => 'boolean',
            'is_popular'  => 'boolean',
            'is_new'      => 'boolean',
        ]);
        $data['is_active']  = $request->boolean('is_active', true);
        $data['is_popular'] = $request->boolean('is_popular');
        $data['is_new']     = $request->boolean('is_new');
        $data['sort_order'] = LandingProgram::max('sort_order') + 1;
        $data['badge_bg']   = $data['badge_bg']    ?? 'rgba(200,77,223,.1)';
        $data['badge_color']= $data['badge_color'] ?? '#68117e';
        $data['icon_emoji'] = $data['icon_emoji']  ?? '📖';
        $data['image']      = $this->storeImage($request, 'image', 'landing/programs');
        LandingProgram::create($data);
        return back()->with('success', 'Program berhasil ditambahkan.');
    }

    public function updateProgram(Request $request, LandingProgram $program)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:150',
            'description' => 'required|string|max:500',
            'badge_label' => 'required|string|max:80',
            'badge_bg'    => 'nullable|string|max:100',
            'badge_color' => 'nullable|string|max:30',
            'icon_emoji'  => 'nullable|string|max:10',
            'image'       => 'nullable|image|max:4096',
            'is_active'   => 'boolean',
            'is_popular'  => 'boolean',
            'is_new'      => 'boolean',
            'sort_order'  => 'integer|min:0',
        ]);
        $data['is_active']  = $request->boolean('is_active', true);
        $data['is_popular'] = $request->boolean('is_popular');
        $data['is_new']     = $request->boolean('is_new');
        foreach (['badge_bg','badge_color','icon_emoji'] as $f) {
            if (array_key_exists($f, $data) && $data[$f] === null) unset($data[$f]);
        }
        if ($image = $this->storeImage($request, 'image', 'landing/programs')) {
            $data['image'] = $image;
        } else {
            unset($data['image']);
        }
        $program->update($data);
        return back()->with('success', 'Program berhasil diperbarui.');
    }

    public function destroyProgram(LandingProgram $program)
    {
        $program->delete();
        return back()->with('success', 'Program berhasil dihapus.');
    }

    /* ─── WhatsApp Numbers ─────────────────────────────────────────── */

    public function storeWa(Request $request)
    {
        $data = $request->validate([
            'label'       => 'required|string|max:100',
            'number'      => 'required|string|max:30|regex:/^[0-9]+$/',
            'description' => 'nullable|string|max:255',
            'is_primary'  => 'boolean',
            'is_active'   => 'boolean',
        ]);
        $data['is_primary']  = $request->boolean('is_primary');
        $data['is_active']   = $request->boolean('is_active', true);
        $data['sort_order']  = LandingWaNumber::max('sort_order') + 1;

        if ($data['is_primary']) {
            LandingWaNumber::where('is_primary', true)->update(['is_primary' => false]);
        }

        LandingWaNumber::create($data);
        return back()->with('success', 'Nomor WhatsApp berhasil ditambahkan.');
    }

    public function updateWa(Request $request, LandingWaNumber $wa)
    {
        $data = $request->validate([
            'label'       => 'required|string|max:100',
            'number'      => 'required|string|max:30|regex:/^[0-9]+$/',
            'description' => 'nullable|string|max:255',
            'is_primary'  => 'boolean',
            'is_active'   => 'boolean',
            'sort_order'  => 'integer|min:0',
        ]);
        $data['is_primary'] = $request->boolean('is_primary');
        $data['is_active']  = $request->boolean('is_active', true);

        if ($data['is_primary']) {
            LandingWaNumber::where('is_primary', true)->where('id', '!=', $wa->id)->update(['is_primary' => false]);
        }

        $wa->update($data);
        return back()->with('success', 'Nomor WhatsApp berhasil diperbarui.');
    }

    public function destroyWa(LandingWaNumber $wa)
    {
        $wa->delete();
        return back()->with('success', 'Nomor WhatsApp berhasil dihapus.');
    }

    /* ─── Promo Ticker ─────────────────────────────────────────────── */

    public function storeTicker(Request $request)
    {
        $data = $request->validate([
            'emoji'     => 'nullable|string|max:10',
            'text'      => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);
        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = LandingTicker::max('sort_order') + 1;
        $data['emoji']      = $data['emoji'] ?? '🎉';
        LandingTicker::create($data);
        return back()->with('success', 'Teks promo berhasil ditambahkan.');
    }

    public function updateTicker(Request $request, LandingTicker $ticker)
    {
        $data = $request->validate([
            'emoji'      => 'nullable|string|max:10',
            'text'       => 'required|string|max:255',
            'is_active'  => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        if (array_key_exists('emoji', $data) && $data['emoji'] === null) unset($data['emoji']);
        $ticker->update($data);
        return back()->with('success', 'Teks promo berhasil diperbarui.');
    }

    public function destroyTicker(LandingTicker $ticker)
    {
        $ticker->delete();
        return back()->with('success', 'Teks promo berhasil dihapus.');
    }

    /* ─── Tentang Features ─────────────────────────────────────────── */

    public function storeFeature(Request $request)
    {
        $data = $request->validate([
            'icon'      => 'nullable|string|max:60',
            'label'     => 'required|string|max:150',
            'is_active' => 'boolean',
        ]);
        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = LandingFeature::max('sort_order') + 1;
        $data['icon']       = $data['icon'] ?? 'bi-check-circle-fill';
        LandingFeature::create($data);
        return back()->with('success', 'Fitur berhasil ditambahkan.');
    }

    public function updateFeature(Request $request, LandingFeature $feature)
    {
        $data = $request->validate([
            'icon'       => 'nullable|string|max:60',
            'label'      => 'required|string|max:150',
            'is_active'  => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        if (array_key_exists('icon', $data) && $data['icon'] === null) unset($data['icon']);
        $feature->update($data);
        return back()->with('success', 'Fitur berhasil diperbarui.');
    }

    public function destroyFeature(LandingFeature $feature)
    {
        $feature->delete();
        return back()->with('success', 'Fitur berhasil dihapus.');
    }

    /* ─── Jenjang Pendidikan ───────────────────────────────────────── */

    public function storeJenjang(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:60',
            'label'     => 'required|string|max:150',
            'emoji'     => 'nullable|string|max:10',
            'image'     => 'nullable|image|max:4096',
            'is_active' => 'boolean',
        ]);
        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = LandingJenjang::max('sort_order') + 1;
        $data['emoji']      = $data['emoji'] ?? '📚';
        $data['image']      = $this->storeImage($request, 'image', 'landing/jenjang');
        LandingJenjang::create($data);
        return back()->with('success', 'Jenjang berhasil ditambahkan.');
    }

    public function updateJenjang(Request $request, LandingJenjang $jenjang)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:60',
            'label'      => 'required|string|max:150',
            'emoji'      => 'nullable|string|max:10',
            'image'      => 'nullable|image|max:4096',
            'is_active'  => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        if (array_key_exists('emoji', $data) && $data['emoji'] === null) unset($data['emoji']);
        if ($image = $this->storeImage($request, 'image', 'landing/jenjang')) {
            $data['image'] = $image;
        } else {
            unset($data['image']);
        }
        $jenjang->update($data);
        return back()->with('success', 'Jenjang berhasil diperbarui.');
    }

    public function destroyJenjang(LandingJenjang $jenjang)
    {
        $jenjang->delete();
        return back()->with('success', 'Jenjang berhasil dihapus.');
    }

    /* ─── Cari Guru Trust Badges ───────────────────────────────────── */

    public function storeTrust(Request $request)
    {
        $data = $request->validate([
            'icon'      => 'nullable|string|max:60',
            'text'      => 'required|string|max:150',
            'is_active' => 'boolean',
        ]);
        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = LandingTrust::max('sort_order') + 1;
        $data['icon']       = $data['icon'] ?? 'bi-patch-check-fill';
        LandingTrust::create($data);
        return back()->with('success', 'Badge berhasil ditambahkan.');
    }

    public function updateTrust(Request $request, LandingTrust $trust)
    {
        $data = $request->validate([
            'icon'       => 'nullable|string|max:60',
            'text'       => 'required|string|max:150',
            'is_active'  => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        if (array_key_exists('icon', $data) && $data['icon'] === null) unset($data['icon']);
        $trust->update($data);
        return back()->with('success', 'Badge berhasil diperbarui.');
    }

    public function destroyTrust(LandingTrust $trust)
    {
        $trust->delete();
        return back()->with('success', 'Badge berhasil dihapus.');
    }

    /* ─── Keunggulan SCI Highlights ────────────────────────────────── */

    public function storeHighlight(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:150',
            'description' => 'required|string|max:1000',
            'image'       => 'nullable|image|max:4096',
            'is_active'   => 'boolean',
        ]);
        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = LandingHighlight::max('sort_order') + 1;
        $data['image']      = $this->storeImage($request, 'image', 'landing/highlights');
        LandingHighlight::create($data);
        return back()->with('success', 'Keunggulan berhasil ditambahkan.');
    }

    public function updateHighlight(Request $request, LandingHighlight $highlight)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:150',
            'description' => 'required|string|max:1000',
            'image'       => 'nullable|image|max:4096',
            'is_active'   => 'boolean',
            'sort_order'  => 'integer|min:0',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        if ($image = $this->storeImage($request, 'image', 'landing/highlights')) {
            $data['image'] = $image;
        } else {
            unset($data['image']);
        }
        $highlight->update($data);
        return back()->with('success', 'Keunggulan berhasil diperbarui.');
    }

    public function destroyHighlight(LandingHighlight $highlight)
    {
        $highlight->delete();
        return back()->with('success', 'Keunggulan berhasil dihapus.');
    }

    /* ─── Galeri Kegiatan ──────────────────────────────────────────── */

    public function storeGallery(Request $request)
    {
        $data = $request->validate([
            'alt'       => 'nullable|string|max:150',
            'image'     => 'required|image|max:4096',
            'is_active' => 'boolean',
        ]);
        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = LandingGallery::max('sort_order') + 1;
        $data['image']      = $this->storeImage($request, 'image', 'landing/gallery');
        LandingGallery::create($data);
        return back()->with('success', 'Foto galeri berhasil ditambahkan.');
    }

    public function updateGallery(Request $request, LandingGallery $gallery)
    {
        $data = $request->validate([
            'alt'        => 'nullable|string|max:150',
            'image'      => 'nullable|image|max:4096',
            'is_active'  => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        if ($image = $this->storeImage($request, 'image', 'landing/gallery')) {
            $data['image'] = $image;
        } else {
            unset($data['image']);
        }
        $gallery->update($data);
        return back()->with('success', 'Foto galeri berhasil diperbarui.');
    }

    public function destroyGallery(LandingGallery $gallery)
    {
        $gallery->delete();
        return back()->with('success', 'Foto galeri berhasil dihapus.');
    }

    /* ─── FAQ ──────────────────────────────────────────────────────── */

    public function storeFaq(Request $request)
    {
        $data = $request->validate([
            'question'  => 'required|string|max:255',
            'answer'    => 'required|string|max:2000',
            'is_active' => 'boolean',
        ]);
        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = LandingFaq::max('sort_order') + 1;
        LandingFaq::create($data);
        return back()->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function updateFaq(Request $request, LandingFaq $faq)
    {
        $data = $request->validate([
            'question'   => 'required|string|max:255',
            'answer'     => 'required|string|max:2000',
            'is_active'  => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        $faq->update($data);
        return back()->with('success', 'FAQ berhasil diperbarui.');
    }

    public function destroyFaq(LandingFaq $faq)
    {
        $faq->delete();
        return back()->with('success', 'FAQ berhasil dihapus.');
    }
}
