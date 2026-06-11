<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentCoursePayment;
use Illuminate\Http\Request;

class CoursePaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = StudentCoursePayment::with(['student', 'course', 'verifier'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->search, function ($q) use ($request) {
                $q->whereHas('student', fn ($sq) => $sq->where('name', 'like', "%{$request->search}%"));
            })
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'pending'  => StudentCoursePayment::where('status', 'pending')->count(),
            'verified' => StudentCoursePayment::where('status', 'verified')->count(),
            'rejected' => StudentCoursePayment::where('status', 'rejected')->count(),
        ];

        return view('admin.course-payments.index', compact('payments', 'stats'));
    }

    public function verify(StudentCoursePayment $payment)
    {
        $payment->update([
            'status'      => 'verified',
            'verified_by' => auth()->id(),
            'rejected_reason' => null,
        ]);

        // Enroll student in the class for this course
        $student = $payment->student;
        $course = $payment->course;

        if ($student && $course) {
            // Find an active class for this course that matches the student's branch
            $class = \App\Models\SchoolClass::where('mata_pelajaran_id', $course->id)
                ->where('status', 'aktif')
                ->where(function ($query) use ($student) {
                    $query->whereNull('cabang_id')
                          ->orWhere('cabang_id', $student->branch_id);
                })
                ->first();

            if ($class) {
                // Check if student is already enrolled in this class
                $alreadyEnrolled = $class->siswa()->where('student_id', $student->id)->exists();
                
                if (!$alreadyEnrolled) {
                    // Enroll student in the class
                    $class->siswa()->attach($student->id);
                    \Log::info("Student {$student->id} enrolled in class {$class->id} for course {$course->id}");
                } else {
                    \Log::info("Student {$student->id} already enrolled in class {$class->id}");
                }
            } else {
                \Log::warning("No active class found for course {$course->id} for student {$student->id}");
                // Create a new class if none exists
                $newClass = \App\Models\SchoolClass::create([
                    'mata_pelajaran_id' => $course->id,
                    'cabang_id' => $student->branch_id,
                    'nama_kelas' => $course->nama . ' - ' . now()->format('Y'),
                    'status' => 'aktif',
                    'kapasitas' => 50,
                ]);
                $newClass->siswa()->attach($student->id);
                \Log::info("Created new class {$newClass->id} and enrolled student {$student->id}");
            }
        }

        return back()->with('success', 'Pembayaran mata pelajaran berhasil diverifikasi. Siswa telah ditambahkan ke kelas.');
    }

    public function reject(Request $request, StudentCoursePayment $payment)
    {
        $data = $request->validate([
            'rejected_reason' => 'required|string|max:500',
        ]);

        $payment->update([
            'status'          => 'rejected',
            'rejected_reason' => $data['rejected_reason'],
            'verified_by'     => auth()->id(),
        ]);

        return back()->with('success', 'Pembayaran ditolak. Siswa dapat upload ulang.');
    }
}
