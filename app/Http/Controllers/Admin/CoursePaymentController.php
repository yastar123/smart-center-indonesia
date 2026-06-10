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

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
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
