<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentLeave;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $student = Student::where('user_id', auth()->id())->first();
        if (!$student) {
            return redirect()->route('siswa.dashboard')->with('error', 'Profil siswa belum lengkap.');
        }

        $query = StudentLeave::where('student_id', $student->id)
            ->with('schoolClass.mataPelajaran');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->whereHas('schoolClass', function ($q) use ($request) {
                $q->where('nama_kelas', 'like', '%' . $request->search . '%');
            });
        }

        $leaves = $query->latest()->paginate(15)->appends($request->all());

        return view('siswa.leave.index', compact('leaves', 'student'));
    }

    public function create()
    {
        $student = Student::where('user_id', auth()->id())->first();
        if (!$student) {
            return redirect()->route('siswa.dashboard')->with('error', 'Profil siswa belum lengkap.');
        }

        $privateClasses = $student->schoolClasses()
            ->with('mataPelajaran', 'guru')
            ->whereIn('jenis', ['private', 'privat'])
            ->get();

        return view('siswa.leave.create', compact('student', 'privateClasses'));
    }

    public function store(Request $request)
    {
        $student = Student::where('user_id', auth()->id())->first();
        if (!$student) {
            return redirect()->route('siswa.dashboard')->with('error', 'Profil siswa belum lengkap.');
        }

        $data = $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'tanggal_mulai'   => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'alasan'          => 'required|string|min:10|max:1000',
        ]);

        // Verify the class belongs to this student and is private
        $class = $student->schoolClasses()
            ->whereIn('jenis', ['private', 'privat'])
            ->where('id', $data['school_class_id'])
            ->first();

        if (!$class) {
            return back()->withErrors(['school_class_id' => 'Kelas tidak ditemukan atau bukan kelas privat.'])->withInput();
        }

        // Check no duplicate pending leave for same class
        $existing = StudentLeave::where('student_id', $student->id)
            ->where('school_class_id', $data['school_class_id'])
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()->withErrors(['school_class_id' => 'Sudah ada pengajuan cuti yang sedang menunggu untuk kelas ini.'])->withInput();
        }

        $data['student_id'] = $student->id;

        StudentLeave::create($data);

        return redirect()->route('siswa.leave.index')
            ->with('success', 'Pengajuan cuti berhasil dikirim. Tunggu konfirmasi admin.');
    }
}
