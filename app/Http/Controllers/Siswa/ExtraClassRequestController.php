<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\ExtraClassRequest;
use App\Models\Student;
use Illuminate\Http\Request;

class ExtraClassRequestController extends Controller
{
    public function index()
    {
        $student = Student::where('user_id', auth()->id())->firstOrFail();

        $courses = Course::where('status', 'aktif')
            ->where(function ($q) use ($student) {
                $q->whereNull('cabang_id')
                  ->orWhere('cabang_id', $student->branch_id);
            })
            ->orderBy('nama')
            ->get();

        $requests = ExtraClassRequest::with('course')
            ->where('siswa_id', $student->id)
            ->orderByDesc('created_at')
            ->get();

        return view('siswa.extra-class.index', compact('student', 'courses', 'requests'));
    }

    public function store(Request $request)
    {
        $student = Student::where('user_id', auth()->id())->firstOrFail();

        $data = $request->validate([
            'course_id'      => 'required|exists:courses,id',
            'tanggal_rencana'=> 'required|date|after_or_equal:today',
            'jam_mulai'      => 'required|date_format:H:i',
            'jumlah_sesi'    => 'required|integer|min:1|max:20',
            'catatan'        => 'nullable|string|max:500',
        ]);

        ExtraClassRequest::create([
            'siswa_id'       => $student->id,
            'course_id'      => $data['course_id'],
            'tanggal_rencana'=> $data['tanggal_rencana'],
            'jam_mulai'      => $data['jam_mulai'],
            'jumlah_sesi'    => $data['jumlah_sesi'],
            'catatan'        => $data['catatan'] ?? null,
            'status'         => 'pending',
        ]);

        return redirect()->route('siswa.extra-class.index')
            ->with('success', 'Request kelas tambahan berhasil dikirim. Admin akan mengkonfirmasi harga sesegera mungkin.');
    }
}
