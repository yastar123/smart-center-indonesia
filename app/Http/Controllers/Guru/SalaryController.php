<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Salary;

class SalaryController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Teacher::where('user_id', auth()->id())->first();
        if (!$teacher) return redirect()->route('dashboard')->with('error', 'Profil guru belum dikonfigurasi.');

        $salaries = Salary::with('cabang')
            ->where('guru_id', $teacher->id)
            ->orderByDesc('tanggal_pembayaran')
            ->paginate(12);

        return view('guru.payments.index', compact('teacher', 'salaries'));
    }
}
