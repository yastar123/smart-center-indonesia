<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\ScheduleProposal;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Services\ScheduleProposalService;
use Illuminate\Http\Request;

class ScheduleProposalController extends Controller
{
    private ScheduleProposalService $service;

    public function __construct(ScheduleProposalService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $student = Student::where('user_id', auth()->id())->first();
        if (! $student) {
            return redirect()->route('siswa.dashboard')->with('error', 'Profil siswa belum lengkap.');
        }

        $classIds = SchoolClass::where('jenis', 'private')
            ->whereHas('siswa', function ($q) use ($student) {
                $q->where('student_id', $student->id);
            })->pluck('id');

        $proposals = ScheduleProposal::whereIn('class_id', $classIds)
            ->with(['kelas', 'approvals'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total'    => $proposals->total(),
            'pending'  => ScheduleProposal::whereIn('class_id', $classIds)->where('status', 'pending')->count(),
            'approved' => ScheduleProposal::whereIn('class_id', $classIds)->where('status', 'approved')->count(),
            'rejected' => ScheduleProposal::whereIn('class_id', $classIds)->where('status', 'rejected')->count(),
        ];

        $classes = SchoolClass::where('jenis', 'private')
            ->whereHas('siswa', function ($q) use ($student) {
                $q->where('student_id', $student->id);
            })
            ->with(['mataPelajaran', 'guru', 'cabang'])
            ->where('status', 'aktif')
            ->orderBy('nama_kelas')
            ->get();

        return view('siswa.schedule-agreements.index', compact('proposals', 'stats', 'classes', 'student'));
    }

    /** Return available meeting slots for a class (AJAX) */
    public function classMeetings(SchoolClass $class)
    {
        $student = Student::where('user_id', auth()->id())->first();
        if (! $student) {
            return response()->json(['success' => false, 'message' => 'Profil siswa tidak ditemukan.'], 403);
        }

        $enrolled = $class->siswa()->where('student_id', $student->id)->exists();
        if (! $enrolled) {
            return response()->json(['success' => false, 'message' => 'Anda tidak terdaftar di kelas ini.'], 403);
        }

        $meetings = $this->service->availableMeetings($class);

        return response()->json(['success' => true, 'meetings' => $meetings, 'jumlah_pertemuan' => $class->jumlah_pertemuan]);
    }

    public function store(Request $request)
    {
        $student = Student::where('user_id', auth()->id())->first();
        if (! $student) {
            return response()->json(['success' => false, 'message' => 'Profil siswa belum lengkap.'], 400);
        }

        $request->validate([
            'class_id'     => 'required|exists:school_classes,id',
            'pertemuan_ke' => 'nullable|integer|min:1',
            'tanggal'      => 'required|date|after_or_equal:today',
            'jam_mulai'    => 'required',
            'jam_selesai'  => 'required|after:jam_mulai',
            'jenis'        => 'required|in:online,offline,private',
            'ruangan'      => 'nullable|string|max:255',
            'link_meeting' => 'nullable|url|max:500',
        ], [
            'tanggal.after_or_equal' => 'Tanggal harus hari ini atau tanggal yang akan datang.',
        ]);

        $class = SchoolClass::find($request->class_id);

        if (! $class || $class->jenis !== 'private') {
            return response()->json(['success' => false, 'message' => 'Pengajuan jadwal hanya tersedia untuk paket privat.'], 403);
        }

        $isEnrolled = $class->siswa()->where('student_id', $student->id)->exists();
        if (! $isEnrolled) {
            return response()->json(['success' => false, 'message' => 'Anda tidak terdaftar di kelas ini.'], 403);
        }

        $result = $this->service->propose($class, 'siswa', $student->id, $request->only([
            'pertemuan_ke', 'tanggal', 'jam_mulai', 'jam_selesai', 'jenis', 'ruangan', 'link_meeting',
        ]));

        if (! $result['success']) {
            return response()->json($result, 422);
        }

        return response()->json([
            'success'  => true,
            'message'  => 'Proposal jadwal berhasil diajukan.',
            'proposal' => $result['proposal'],
        ]);
    }

    public function approve(ScheduleProposal $proposal)
    {
        $student = Student::where('user_id', auth()->id())->first();
        if (! $student) {
            return response()->json(['success' => false, 'message' => 'Profil siswa belum lengkap.'], 400);
        }

        $result = $this->service->respond($proposal, 'siswa', $student->id, 'approved');
        return response()->json($result);
    }

    public function reject(ScheduleProposal $proposal)
    {
        $student = Student::where('user_id', auth()->id())->first();
        if (! $student) {
            return response()->json(['success' => false, 'message' => 'Profil siswa belum lengkap.'], 400);
        }

        $result = $this->service->respond($proposal, 'siswa', $student->id, 'rejected');
        return response()->json($result);
    }
}
