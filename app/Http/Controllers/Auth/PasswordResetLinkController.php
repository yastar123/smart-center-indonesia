<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LandingWaNumber;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        $waNumbers = LandingWaNumber::active()->orderBy('sort_order')->get();
        return view('auth.forgot-password', compact('waNumbers'));
    }

    public function store(Request $request)
    {
        return back()->with('status', 'Silakan hubungi admin melalui WhatsApp untuk reset password Anda.');
    }
}
