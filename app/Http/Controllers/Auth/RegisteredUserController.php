<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LandingWaNumber;
use App\Models\StudentRegistration;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function success(): View
    {
        $studentName = session('student_name', 'Siswa');
        $waNumbers   = LandingWaNumber::active()->orderBy('sort_order')->get();

        return view('auth.register-success', compact('waNumbers', 'studentName'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
        ]);

        StudentRegistration::create([
            'no_reg'          => 'REG-' . now()->format('YmdHis') . '-' . rand(100, 999),
            'name'            => $request->name,
            'phone'           => $request->phone,
            'gender'          => $request->gender,
            'education_level' => $request->education_level,
            'birth_place'     => $request->birth_place,
            'birth_date'      => $request->birth_date ?: null,
            'address'         => $request->address,
            'parent_name'     => $request->parent_name,
            'parent_phone'    => $request->parent_phone,
            'program'         => $request->program_belajar,
            'system'          => $request->sistem_belajar,
            'learning_place'  => $request->tempat_belajar,
            'pickup_mode'     => $request->sistem_paket,
            'interests'       => $request->program_minat ?? [],
            'day_preferences' => $request->hari_belajar ?? [],
            'schedule_time'   => $request->jam_belajar,
            'start_date'      => $request->tanggal_mulai ?: null,
            'notes'           => $request->catatan,
            'status'          => 'pending',
        ]);

        $request->session()->put('student_name', $request->name);

        return redirect()->route('register.success');
    }
}
