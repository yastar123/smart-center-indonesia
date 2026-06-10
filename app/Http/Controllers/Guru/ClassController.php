<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Teacher;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $teacher = Teacher::where('user_id', auth()->id())->first();
        $classes = SchoolClass::with('mataPelajaran', 'cabang', 'tahunAkademik', 'siswa')
            ->when($teacher, fn($q) => $q->where('guru_id', $teacher->id))
            ->get();

        return view('guru.classes.index', compact('classes', 'teacher'));
    }

    public function show(SchoolClass $class)
    {
        $class->load('mataPelajaran', 'cabang', 'tahunAkademik', 'guru', 'siswa');
        return view('guru.classes.show', compact('class'));
    }

    public function attendance(SchoolClass $class)
    {
        $class->load('jadwal');
        return view('guru.classes.attendance', compact('class'));
    }
}
