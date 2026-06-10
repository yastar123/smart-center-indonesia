<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingSetting;
use App\Models\LandingTestimonial;
use App\Models\LandingProgram;
use App\Models\LandingWaNumber;
use Illuminate\Http\Request;

class LandingContentController extends Controller
{
    public function index()
    {
        $settings     = LandingSetting::orderBy('sort_order')->get()->keyBy('key');
        $testimonials = LandingTestimonial::orderBy('sort_order')->get();
        $programs     = LandingProgram::orderBy('sort_order')->get();
        $waNumbers    = LandingWaNumber::orderBy('sort_order')->get();
        return view('admin.landing.index', compact('settings','testimonials','programs','waNumbers'));
    }

    /* ─── Settings ─────────────────────────────────────────────────── */

    public function updateSettings(Request $request)
    {
        $data = $request->validate(['settings' => 'required|array', 'settings.*' => 'nullable|string|max:5000']);
        foreach ($data['settings'] as $key => $value) {
            LandingSetting::where('key', $key)->update(['value' => $value ?? '']);
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
            'is_active' => 'boolean',
        ]);
        $data['is_active']  = $request->boolean('is_active', true);
        $data['initial']    = strtoupper(substr($data['name'], 0, 1));
        $data['sort_order'] = LandingTestimonial::max('sort_order') + 1;
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
            'is_active'  => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['initial']   = strtoupper(substr($data['name'], 0, 1));
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
            'is_active'   => 'boolean',
            'is_popular'  => 'boolean',
            'is_new'      => 'boolean',
        ]);
        $data['is_active']  = $request->boolean('is_active', true);
        $data['is_popular'] = $request->boolean('is_popular');
        $data['is_new']     = $request->boolean('is_new');
        $data['sort_order'] = LandingProgram::max('sort_order') + 1;
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
            'is_active'   => 'boolean',
            'is_popular'  => 'boolean',
            'is_new'      => 'boolean',
            'sort_order'  => 'integer|min:0',
        ]);
        $data['is_active']  = $request->boolean('is_active', true);
        $data['is_popular'] = $request->boolean('is_popular');
        $data['is_new']     = $request->boolean('is_new');
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
}
