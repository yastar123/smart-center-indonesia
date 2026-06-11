<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Certificate;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class SiswaController extends Controller
{
    // Fungsi schedule() dihapus karena halaman jadwal siswa juga dihapus

    public function certificates()
    {
        $student = Student::where('user_id', auth()->id())->first();
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Profil siswa tidak ditemukan.');
        }

        // Ambil semua mata pelajaran yang diambil siswa
        $studentCourses = DB::table('school_classes as sc')
            ->join('courses as c', 'c.id', '=', 'sc.mata_pelajaran_id')
            ->join('class_students as cs', 'cs.class_id', '=', 'sc.id')
            ->where('cs.student_id', $student->id)
            ->whereNull('sc.deleted_at')
            ->select('c.id as course_id', 'c.nama as course_name', 'sc.id as class_id')
            ->distinct()
            ->get();

        // Ambil sertifikat per mata pelajaran
        $certificatesByCourse = Certificate::where('siswa_id', $student->id)
            ->get()
            ->groupBy('mata_pelajaran_id');

        // Siapkan data untuk view
        $courseData = $studentCourses->map(function ($course) use ($certificatesByCourse, $student) {
            $certs = $certificatesByCourse->get($course->course_id, collect());
            $latestCert = $certs->sortByDesc('tanggal_terbit')->first();

            return [
                'course_id' => $course->course_id,
                'course_name' => $course->course_name,
                'class_id' => $course->class_id,
                'has_certificate' => $certs->isNotEmpty(),
                'certificate' => $latestCert,
                'certificate_count' => $certs->count(),
            ];
        });

        $stats = [
            'total_courses' => $studentCourses->count(),
            'certified' => $courseData->where('has_certificate', true)->count(),
            'pending' => $courseData->where('has_certificate', false)->count(),
        ];

        return view('siswa.certificates', compact('courseData', 'stats', 'student'));
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
