<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentLeave;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentLeave::with('student', 'schoolClass.mataPelajaran', 'schoolClass.guru');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $leaves = $query->latest()->paginate(20)->appends($request->all());

        $stats = [
            'total'    => StudentLeave::count(),
            'pending'  => StudentLeave::where('status', 'pending')->count(),
            'approved' => StudentLeave::where('status', 'approved')->count(),
            'rejected' => StudentLeave::where('status', 'rejected')->count(),
        ];

        return view('admin.leave.index', compact('leaves', 'stats'));
    }

    public function approve(Request $request, StudentLeave $leave)
    {
        $leave->update([
            'status'        => 'approved',
            'catatan_admin' => $request->catatan_admin,
        ]);
        return back()->with('success', 'Pengajuan cuti disetujui.');
    }

    public function reject(Request $request, StudentLeave $leave)
    {
        $leave->update([
            'status'        => 'rejected',
            'catatan_admin' => $request->catatan_admin,
        ]);
        return back()->with('success', 'Pengajuan cuti ditolak.');
    }
}
