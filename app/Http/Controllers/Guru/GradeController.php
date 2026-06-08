<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Teacher::where('user_id', auth()->id())->first();

        if ($request->ajax()) {
            $q = Grade::with('siswa', 'mataPelajaran')
                ->when($teacher, fn($q) => $q->where('guru_id', $teacher?->id))
                ->when($request->siswa_id,          fn($q) => $q->where('siswa_id', $request->siswa_id))
                ->when($request->mata_pelajaran_id,  fn($q) => $q->where('mata_pelajaran_id', $request->mata_pelajaran_id))
                ->when($request->jenis_penilaian,    fn($q) => $q->where('jenis_penilaian', $request->jenis_penilaian))
                ->latest();

            return response()->json($q->paginate(20)->toArray());
        }

        $students = Student::where('status', 'aktif')->orderBy('name')->get();
        $courses  = Course::orderBy('nama')->get();

        return view('guru.grades', compact('students', 'courses', 'teacher'));
    }

    public function store(Request $request)
    {
        $teacher = Teacher::where('user_id', auth()->id())->first();

        $data = $request->validate([
            'siswa_id'          => 'required|exists:students,id',
            'mata_pelajaran_id' => 'required|exists:courses,id',
            'jenis_penilaian'   => 'required|in:tugas,ujian,tryout,harian,uts,uas',
            'nama_penilaian'    => 'required|string|max:150',
            'nilai'             => 'required|numeric|min:0|max:100',
            'nilai_maksimal'    => 'nullable|numeric|min:0',
            'bobot'             => 'nullable|numeric|min:0|max:100',
            'tanggal'           => 'required|date',
            'catatan'           => 'nullable|string',
        ]);

        $data['guru_id'] = $teacher?->id;

        Grade::create($data);
        return response()->json(['success' => true, 'message' => 'Nilai berhasil disimpan!']);
    }

    public function storeBatch(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'jenis'     => 'required|string',
            'grades'    => 'required|array',
        ]);

        $teacher       = Teacher::where('user_id', auth()->id())->first();
        $courseId      = $request->course_id;
        $jenis         = $request->jenis;
        $today         = now()->toDateString();
        $saved         = 0;

        foreach ($request->grades as $siswaId => $nilai) {
            if ($nilai === null || $nilai === '') continue;
            $nilai = max(0, min(100, (float) $nilai));

            Grade::updateOrCreate(
                [
                    'siswa_id'         => $siswaId,
                    'mata_pelajaran_id' => $courseId,
                    'jenis_penilaian'  => $jenis,
                ],
                [
                    'nilai'            => $nilai,
                    'guru_id'          => $teacher?->id,
                    'nama_penilaian'   => ucfirst($jenis),
                    'tanggal'          => $today,
                ]
            );
            $saved++;
        }

        return response()->json([
            'success' => true,
            'message' => "Nilai berhasil disimpan untuk {$saved} siswa!",
            'saved'   => $saved,
        ]);
    }

    public function show(Grade $grade)
    {
        return response()->json(['success' => true, 'data' => $grade->load('siswa', 'mataPelajaran')]);
    }

    public function update(Request $request, Grade $grade)
    {
        $data = $request->validate([
            'siswa_id'          => 'required|exists:students,id',
            'mata_pelajaran_id' => 'required|exists:courses,id',
            'jenis_penilaian'   => 'required|in:tugas,ujian,tryout,harian,uts,uas',
            'nama_penilaian'    => 'required|string|max:150',
            'nilai'             => 'required|numeric|min:0|max:100',
            'nilai_maksimal'    => 'nullable|numeric|min:0',
            'bobot'             => 'nullable|numeric|min:0|max:100',
            'tanggal'           => 'required|date',
            'catatan'           => 'nullable|string',
        ]);

        $grade->update($data);
        return response()->json(['success' => true, 'message' => 'Nilai berhasil diperbarui!']);
    }

    public function destroy(Grade $grade)
    {
        $grade->delete();
        return response()->json(['success' => true, 'message' => 'Nilai berhasil dihapus!']);
    }

    public function rekap(Request $request)
    {
        $teacher = Teacher::where('user_id', auth()->id())->first();

        $rekap = Grade::with('siswa', 'mataPelajaran')
            ->when($teacher, fn($q) => $q->where('guru_id', $teacher?->id))
            ->when($request->siswa_id, fn($q) => $q->where('siswa_id', $request->siswa_id))
            ->select('siswa_id', 'mata_pelajaran_id',
                \DB::raw('AVG(nilai) as rata_rata'),
                \DB::raw('MAX(nilai) as nilai_max'),
                \DB::raw('MIN(nilai) as nilai_min'),
                \DB::raw('COUNT(*) as jumlah_penilaian'))
            ->groupBy('siswa_id', 'mata_pelajaran_id')
            ->with('siswa', 'mataPelajaran')
            ->get();

        return response()->json(['success' => true, 'data' => $rekap]);
    }
}
