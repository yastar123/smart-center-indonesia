<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\ScheduleProposal;
use App\Models\SchoolClass;
use App\Models\Teacher;
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
        $teacher = Teacher::where('user_id', auth()->id())->first();
        if (! $teacher) {
            return redirect()->route('guru.dashboard')->with('error', 'Profil guru belum lengkap.');
        }

        $classIds = SchoolClass::where('guru_id', $teacher->id)->pluck('id');

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

        $classes = SchoolClass::where('guru_id', $teacher->id)
            ->with(['mataPelajaran', 'cabang'])
            ->where('status', 'aktif')
            ->orderBy('nama_kelas')
            ->get();

        return view('guru.schedule-agreements.index', compact('proposals', 'stats', 'classes', 'teacher'));
    }

    /** Return available meeting slots for a class (AJAX) */
    public function classMeetings(SchoolClass $class)
    {
        $teacher = Teacher::where('user_id', auth()->id())->first();
        if (! $teacher || $class->guru_id !== $teacher->id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $meetings = $this->service->availableMeetings($class);

        return response()->json(['success' => true, 'meetings' => $meetings, 'jumlah_pertemuan' => $class->jumlah_pertemuan]);
    }

    public function store(Request $request)
    {
        $teacher = Teacher::where('user_id', auth()->id())->first();
        if (! $teacher) {
            return response()->json(['success' => false, 'message' => 'Profil guru belum lengkap.'], 400);
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
        ]);

        $class = SchoolClass::find($request->class_id);
        if ($class->guru_id !== $teacher->id) {
            return response()->json(['success' => false, 'message' => 'Anda tidak mengajar kelas ini.'], 403);
        }

        $result = $this->service->propose($class, 'guru', $teacher->id, $request->only([
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
        $teacher = Teacher::where('user_id', auth()->id())->first();
        if (! $teacher) {
            return response()->json(['success' => false, 'message' => 'Profil guru belum lengkap.'], 400);
        }

        $result = $this->service->respond($proposal, 'guru', $teacher->id, 'approved');
        return response()->json($result);
    }

    public function reject(ScheduleProposal $proposal)
    {
        $teacher = Teacher::where('user_id', auth()->id())->first();
        if (! $teacher) {
            return response()->json(['success' => false, 'message' => 'Profil guru belum lengkap.'], 400);
        }

        $result = $this->service->respond($proposal, 'guru', $teacher->id, 'rejected');
        return response()->json($result);
    }
}
