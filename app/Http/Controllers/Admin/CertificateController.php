<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Student;
use App\Models\Branch;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $query = Certificate::with(['siswa.user', 'cabang']);

        if ($s = $request->search) {
            $query->where(function ($q) use ($s) {
                $q->where('judul', 'like', "%$s%")
                  ->orWhere('nomor_sertifikat', 'like', "%$s%");
            });
        }
        if ($request->jenis) {
            $query->where('jenis', $request->jenis);
        }
        if ($request->cabang_id) {
            $query->where('cabang_id', $request->cabang_id);
        }

        $certificates = $query->latest()->paginate(15)->appends($request->all());
        $students     = Student::with(['user', 'branch'])->where('status', 'aktif')->get();
        $branches     = Branch::orderBy('name')->get();

        $stats = [
            'total'      => Certificate::count(),
            'kompetensi' => Certificate::where('jenis', 'kompetensi')->count(),
            'kelulusan'  => Certificate::where('jenis', 'kelulusan')->count(),
            'prestasi'   => Certificate::where('jenis', 'prestasi')->count(),
        ];

        return view('admin.certificates.index', compact('certificates', 'students', 'branches', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'siswa_id'          => 'required|exists:students,id',
            'cabang_id'         => 'nullable|exists:branches,id',
            'course_id'         => 'nullable|exists:courses,id',
            'jenis'             => 'required|in:kompetensi,kelulusan,prestasi,partisipasi',
            'judul'             => 'required|string|max:200',
            'deskripsi'         => 'nullable|string',
            'tanggal_terbit'    => 'required|date',
            'tanggal_expired'   => 'nullable|date|after:tanggal_terbit',
            'diterbitkan_oleh'  => 'nullable|string|max:100',
            'file_sertifikat'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        // Auto-derive cabang_id from student if not provided
        if (empty($data['cabang_id'])) {
            $student = Student::find($data['siswa_id']);
            $data['cabang_id'] = $student?->branch_id ?? null;
        }

        $data['nomor_sertifikat'] = 'SCI-' . strtoupper(Str::random(3)) . '-' . date('Ymd') . '-' . rand(100, 999);

        if ($request->hasFile('file_sertifikat')) {
            $path = $request->file('file_sertifikat')->store('certificates', 'public');
            $data['file_sertifikat'] = $path;
        }

        Certificate::create($data);

        return response()->json(['success' => true, 'message' => 'Sertifikat berhasil diterbitkan.']);
    }

    public function show(Certificate $certificate)
    {
        return response()->json($certificate->load(['siswa.user', 'cabang']));
    }

    public function update(Request $request, Certificate $certificate)
    {
        $data = $request->validate([
            'siswa_id'          => 'required|exists:students,id',
            'cabang_id'         => 'nullable|exists:branches,id',
            'course_id'         => 'nullable|exists:courses,id',
            'jenis'             => 'required|in:kompetensi,kelulusan,prestasi,partisipasi',
            'judul'             => 'required|string|max:200',
            'deskripsi'         => 'nullable|string',
            'tanggal_terbit'    => 'required|date',
            'tanggal_expired'   => 'nullable|date|after:tanggal_terbit',
            'diterbitkan_oleh'  => 'nullable|string|max:100',
            'file_sertifikat'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        // Auto-derive cabang_id from student if not provided
        if (empty($data['cabang_id'])) {
            $student = \App\Models\Student::find($data['siswa_id'] ?? $certificate->siswa_id);
            $data['cabang_id'] = $student?->branch_id ?? $certificate->cabang_id ?? null;
        }

        if ($request->hasFile('file_sertifikat')) {
            if ($certificate->file_sertifikat && Storage::disk('public')->exists($certificate->file_sertifikat)) {
                Storage::disk('public')->delete($certificate->file_sertifikat);
            }
            $data['file_sertifikat'] = $request->file('file_sertifikat')->store('certificates', 'public');
        }

        $certificate->update($data);

        return response()->json(['success' => true, 'message' => 'Sertifikat berhasil diperbarui.']);
    }

    public function destroy(Certificate $certificate)
    {
        // delete file if exists
        if ($certificate->file_sertifikat && Storage::disk('public')->exists($certificate->file_sertifikat)) {
            Storage::disk('public')->delete($certificate->file_sertifikat);
        }
        $certificate->delete();

        return response()->json(['success' => true, 'message' => 'Sertifikat berhasil dihapus.']);
    }

    // Mengembalikan daftar mata pelajaran yang diambil siswa
    public function studentCourses(Student $student)
    {
        $courses = Course::whereIn('id', function ($q) use ($student) {
            $q->select('mata_pelajaran_id')->from('school_classes')
                ->whereIn('id', function ($q2) use ($student) {
                    $q2->select('class_id')->from('class_students')->where('student_id', $student->id);
                })->whereNull('deleted_at');
        })->get();

        return response()->json(['success' => true, 'data' => $courses]);
    }
}
