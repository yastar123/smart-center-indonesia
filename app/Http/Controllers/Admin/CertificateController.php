<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Student;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        $students     = Student::with('user')->where('status', 'aktif')->get();
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
            'cabang_id'         => 'required|exists:branches,id',
            'jenis'             => 'required|in:kompetensi,kelulusan,prestasi,partisipasi',
            'judul'             => 'required|string|max:200',
            'deskripsi'         => 'nullable|string',
            'tanggal_terbit'    => 'required|date',
            'tanggal_expired'   => 'nullable|date|after:tanggal_terbit',
            'diterbitkan_oleh'  => 'nullable|string|max:100',
        ]);

        $data['nomor_sertifikat'] = 'SCI-' . strtoupper(Str::random(3)) . '-' . date('Ymd') . '-' . rand(100, 999);

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
            'cabang_id'         => 'required|exists:branches,id',
            'jenis'             => 'required|in:kompetensi,kelulusan,prestasi,partisipasi',
            'judul'             => 'required|string|max:200',
            'deskripsi'         => 'nullable|string',
            'tanggal_terbit'    => 'required|date',
            'tanggal_expired'   => 'nullable|date|after:tanggal_terbit',
            'diterbitkan_oleh'  => 'nullable|string|max:100',
        ]);

        $certificate->update($data);

        return response()->json(['success' => true, 'message' => 'Sertifikat berhasil diperbarui.']);
    }

    public function destroy(Certificate $certificate)
    {
        $certificate->delete();

        return response()->json(['success' => true, 'message' => 'Sertifikat berhasil dihapus.']);
    }
}
