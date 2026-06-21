<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LandingWaNumber;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        // Auto-generate unique email & password
        $baseName  = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $request->name));
        $baseEmail = $baseName . '.' . time() . '@akademibimbel.id';
        $password  = 'password';

        // Create user account
        $user = User::create([
            'name'     => $request->name,
            'email'    => $baseEmail,
            'password' => Hash::make($password),
        ]);
        $user->assignRole('siswa');

        // Build student record
        $student = Student::create([
            'user_id'      => $user->id,
            'name'         => $request->name,
            'phone'        => $request->phone,
            'birth_place'  => $request->birth_place,
            'birth_date'   => $request->birth_date ?: null,
            'gender'       => $request->gender,
            'address'      => $request->address,
            'parent_name'  => $request->parent_name,
            'parent_phone' => $request->parent_phone,
            'school_name'  => $request->school_name,
            'status'       => 'aktif',
            'join_date'    => now()->toDateString(),
        ]);

        // Save student name for the success page and redirect there.
        $request->session()->put('student_name', $request->name);

        return redirect()->route('register.success');
    }
}
