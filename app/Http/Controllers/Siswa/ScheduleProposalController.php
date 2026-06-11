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

        // Get all classes the student is enrolled in
        $classIds = SchoolClass::whereHas('siswa', function ($q) use ($student) {
            $q->where('student_id', $student->id);
        })->pluck('id');

        // Get proposals for these classes
        $proposals = ScheduleProposal::whereIn('class_id', $classIds)
            ->with(['kelas', 'approvals'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Stats
        $stats = [
            'total' => $proposals->total(),
            'pending' => ScheduleProposal::whereIn('class_id', $classIds)->where('status', 'pending')->count(),
            'approved' => ScheduleProposal::whereIn('class_id', $classIds)->where('status', 'approved')->count(),
            'rejected' => ScheduleProposal::whereIn('class_id', $classIds)->where('status', 'rejected')->count(),
        ];

        // Classes for the proposal form
        $classes = SchoolClass::whereHas('siswa', function ($q) use ($student) {
            $q->where('student_id', $student->id);
        })
            ->with(['mataPelajaran', 'guru', 'cabang'])
            ->where('status', 'aktif')
            ->orderBy('nama_kelas')
            ->get();

        return view('siswa.schedule-agreements.index', compact('proposals', 'stats', 'classes', 'student'));
    }

    public function store(Request $request)
    {
        $student = Student::where('user_id', auth()->id())->first();
        if (! $student) {
            return response()->json(['success' => false, 'message' => 'Profil siswa belum lengkap.'], 400);
        }

        $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'jenis' => 'required|in:online,offline,private',
            'ruangan' => 'nullable|string|max:255',
            'link_meeting' => 'nullable|url|max:500',
        ]);

        $class = SchoolClass::find($request->class_id);
        
        // Check if student is enrolled in this class
        $isEnrolled = $class->siswa()->where('student_id', $student->id)->exists();
        if (! $isEnrolled) {
            return response()->json(['success' => false, 'message' => 'Anda tidak terdaftar di kelas ini.'], 403);
        }

        $proposal = $this->service->propose($class, 'siswa', $student->id, $request->only([
            'tanggal', 'jam_mulai', 'jam_selesai', 'jenis', 'ruangan', 'link_meeting'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Proposal jadwal berhasil diajukan.',
            'proposal' => $proposal
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
