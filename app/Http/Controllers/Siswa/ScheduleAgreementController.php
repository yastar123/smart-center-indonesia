<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Student;
use App\Services\ScheduleAgreementService;
use Illuminate\Http\Request;

class ScheduleAgreementController extends Controller
{
    public function confirm(Request $request, Schedule $schedule)
    {
        $student = Student::where('user_id', auth()->id())->first();
        if (! $student) {
            return response()->json(['success' => false, 'message' => 'Profil siswa tidak ditemukan.'], 403);
        }

        $enrolled = $schedule->kelas && $schedule->kelas->siswa()->where('students.id', $student->id)->exists();
        if (! $enrolled) {
            return response()->json(['success' => false, 'message' => 'Anda tidak terdaftar di kelas ini.'], 403);
        }

        $result = app(ScheduleAgreementService::class)->siswaConfirm($schedule, $student->id);

        return response()->json($result, $result['success'] ? 200 : 422);
    }
}
