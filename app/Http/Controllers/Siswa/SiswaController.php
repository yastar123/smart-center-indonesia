<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Certificate;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class SiswaController extends Controller
{
    // Fungsi schedule() dihapus karena halaman jadwal siswa juga dihapus

    public function certificates()
    {
        $student = Student::where('user_id', auth()->id())->first();

        // Enrolled classes/courses for this student
        $enrolledClasses = $student
            ? \App\Models\SchoolClass::with(['mataPelajaran', 'guru', 'cabang'])
                ->whereHas('siswa', fn($q) => $q->where('student_id', $student->id))
                ->get()
            : collect();

        // Admin-issued certificates for this student (not student-uploaded)
        $certificates = $student
            ? Certificate::with('cabang')
                ->where('siswa_id', $student->id)
                ->where('nomor_sertifikat', 'not like', 'UPLOAD-%')
                ->latest()
                ->get()
            : collect();

        return view('siswa.certificates', compact('certificates', 'student', 'enrolledClasses'));
    }

    public function downloadCertificate(Certificate $certificate)
    {
        // Only the owner can download
        $student = Student::where('user_id', auth()->id())->first();
        if (!$student || $certificate->siswa_id !== $student->id) {
            abort(403);
        }

        if ($certificate->file_sertifikat && Storage::disk('public')->exists($certificate->file_sertifikat)) {
            return Storage::disk('public')->download($certificate->file_sertifikat);
        }

        // Generate PDF on the fly
        $pdf = Pdf::loadView('siswa.certificate-pdf', compact('certificate', 'student'))
            ->setPaper('a4', 'landscape');
        return $pdf->download('sertifikat-' . $certificate->nomor_sertifikat . '.pdf');
    }

    public function uploadCertificate(Request $request)
    {
        $student = Student::where('user_id', auth()->id())->first();
        if (!$student) abort(403);

        $data = $request->validate([
            'judul'         => 'required|string|max:200',
            'jenis'         => 'required|in:kompetensi,kelulusan,prestasi,partisipasi',
            'tanggal_terbit'=> 'required|date',
            'file'          => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $filePath = $request->file('file')->store('certificates/uploads', 'public');

        Certificate::create([
            'siswa_id'        => $student->id,
            'cabang_id'       => $student->branch_id,
            'diterbitkan_oleh'=> auth()->id(),
            'nomor_sertifikat'=> 'UPLOAD-' . strtoupper(\Str::random(6)) . '-' . date('Y'),
            'jenis'           => $data['jenis'],
            'judul'           => $data['judul'],
            'tanggal_terbit'  => $data['tanggal_terbit'],
            'file_sertifikat' => $filePath,
        ]);

        return response()->json(['success' => true, 'message' => 'Sertifikat berhasil diunggah!']);
    }
}
