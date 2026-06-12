<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $student = Student::where('user_id', auth()->id())->first();
        if (! $student) {
            return redirect()->route('siswa.dashboard')->with('error', 'Profil siswa belum lengkap.');
        }

        // Get class IDs this student belongs to
        $classIds = DB::table('class_students')
            ->where('student_id', $student->id)
            ->pluck('class_id');

        $schedules = Schedule::with(['kelas.mataPelajaran', 'kelas.cabang', 'kelas.guru'])
            ->whereIn('kelas_id', $classIds)
            ->where('status', '!=', 'dibatalkan')
            ->when($request->bulan, fn($q) => $q->whereMonth('tanggal', $request->bulan))
            ->when($request->tahun, fn($q) => $q->whereYear('tanggal', $request->tahun))
            ->orderByDesc('tanggal')
            ->orderBy('jam_mulai')
            ->paginate(15)->withQueryString();

        return view('siswa.schedules.index', compact('schedules', 'student'));
    }
}
