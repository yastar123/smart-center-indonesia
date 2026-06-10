<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Student;
use App\Models\StudentCoursePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{
    public function index(Request $request)
    {
        $student = Student::where('user_id', auth()->id())->first();
        if (! $student) {
            return redirect()->route('dashboard');
        }

        $courses = Course::whereIn('id', function ($q) use ($student) {
            $q->select('mata_pelajaran_id')->from('school_classes')
                ->whereIn('id', function ($q2) use ($student) {
                    $q2->select('class_id')->from('class_students')->where('student_id', $student->id);
                });
        })->get();

        $fees = DB::table('course_fees')->pluck('amount', 'course_id')->toArray();
        $payments = StudentCoursePayment::where('student_id', $student->id)
            ->orderByDesc('created_at')
            ->get()
            ->unique('course_id')
            ->keyBy('course_id');

        $selectedCourseId = $request->query('course');
        $selectedCourse = $selectedCourseId ? $courses->firstWhere('id', (int) $selectedCourseId) : null;

        return view('siswa.billing.index', compact(
            'courses', 'fees', 'payments', 'selectedCourse', 'student'
        ));
    }

    public function pay(Request $request, Course $course)
    {
        $student = Student::where('user_id', auth()->id())->first();
        if (! $student) {
            return redirect()->route('siswa.attendance');
        }

        $data = $request->validate([
            'proof'   => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'catatan' => 'nullable|string|max:500',
        ]);

        $existing = StudentCoursePayment::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah memiliki pembayaran yang menunggu verifikasi.');
        }

        $path = $data['proof']->store('payments', 'public');
        $amount = DB::table('course_fees')->where('course_id', $course->id)->value('amount') ?? 0;

        StudentCoursePayment::updateOrCreate(
            [
                'student_id' => $student->id,
                'course_id'  => $course->id,
            ],
            [
                'amount'          => $amount,
                'proof'           => $path,
                'catatan'         => $data['catatan'] ?? null,
                'status'          => 'pending',
                'rejected_reason' => null,
                'verified_by'     => null,
            ]
        );

        return redirect()->route('siswa.billing.index', ['course' => $course->id])
            ->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
    }
}
