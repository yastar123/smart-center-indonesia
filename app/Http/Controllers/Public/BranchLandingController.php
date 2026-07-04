<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\LandingTestimonial;
use App\Models\LandingSetting;
use App\Models\LandingWaNumber;
use App\Models\Package;
use App\Models\Teacher;

class BranchLandingController extends Controller
{
    public function show(Branch $branch)
    {
        $city = $branch->city ?: $branch->name;

        $teachers = Teacher::where('branch_id', $branch->id)
            ->where('status', 'aktif')
            ->get();

        $packages = Package::where('cabang_id', $branch->id)
            ->where('status', 'aktif')
            ->get();

        $lsAll         = LandingSetting::all()->keyBy('key');
        $ls            = fn(string $k, string $d = '') => $lsAll[$k]->value ?? $d;
        $testimonials  = LandingTestimonial::active()->orderBy('sort_order')->get();
        $waMain        = LandingWaNumber::primaryNumber($ls('footer.wa_number', '628001234567'));

        // Normalise branch phone to digits-only; fall back to main WA
        $branchWa = preg_replace('/[^0-9]/', '', $branch->phone ?? '');
        if (strlen($branchWa) < 8) {
            $branchWa = $waMain;
        } elseif (!str_starts_with($branchWa, '62')) {
            $branchWa = '62' . ltrim($branchWa, '0');
        }

        $tutorGrads = [
            'linear-gradient(160deg,#260632,#c84ddf)',
            'linear-gradient(160deg,#1a3a6b,#2563eb)',
            'linear-gradient(160deg,#064e3b,#10b981)',
            'linear-gradient(160deg,#7c2d12,#f97316)',
            'linear-gradient(160deg,#312e81,#8b5cf6)',
            'linear-gradient(160deg,#881337,#f43f5e)',
        ];

        return view('branch-landing', compact(
            'branch', 'city', 'teachers', 'packages',
            'testimonials', 'waMain', 'branchWa', 'ls', 'tutorGrads'
        ));
    }
}
