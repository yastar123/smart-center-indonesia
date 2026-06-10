<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Teacher;
use App\Services\ScheduleAgreementService;
use App\Services\ScheduleLockService;
use Illuminate\Http\Request;

class ScheduleAgreementController extends Controller
{
    public function confirm(Request $request, Schedule $schedule)
    {
        $teacher = Teacher::where('user_id', auth()->id())->first();
        if (! $teacher || $schedule->guru_id !== $teacher->id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $result = app(ScheduleAgreementService::class)->guruConfirm($schedule, $data['student_id']);

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function scheduleInfo(Schedule $schedule)
    {
        $lockService = app(ScheduleLockService::class);
        $agreements = $schedule->agreements()->with('student')->get();

        return response()->json([
            'success'       => true,
            'locked'        => $lockService->isScheduleLocked($schedule),
            'attendance_locked' => $lockService->isAttendanceLocked($schedule),
            'agreements'    => $agreements,
            'all_agreed'    => app(ScheduleAgreementService::class)->allStudentsAgreed($schedule),
        ]);
    }
}
