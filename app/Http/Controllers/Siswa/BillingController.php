<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Invoice;
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

        // Handle add_course parameter - add to session draft
        if ($request->has('add_course')) {
            $courseId = (int) $request->query('add_course');
            $draftCourses = session()->get('draft_courses', []);
            
            // Check if course already in draft
            if (!in_array($courseId, $draftCourses)) {
                $draftCourses[] = $courseId;
                session()->put('draft_courses', $draftCourses);
            }
            
            return redirect()->route('siswa.billing.index');
        }

        // Handle remove_course parameter - remove from session draft
        if ($request->has('remove_course')) {
            $courseId = (int) $request->query('remove_course');
            $draftCourses = session()->get('draft_courses', []);
            $draftCourses = array_diff($draftCourses, [$courseId]);
            session()->put('draft_courses', array_values($draftCourses));
            
            return redirect()->route('siswa.billing.index');
        }

        // Handle clear_draft parameter - clear all draft courses
        if ($request->has('clear_draft')) {
            session()->forget('draft_courses');
            return redirect()->route('siswa.billing.index');
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

        // Get draft courses from session
        $draftCourseIds = session()->get('draft_courses', []);
        $draftCourses = collect();
        
        foreach ($draftCourseIds as $courseId) {
            $course = $courses->firstWhere('id', $courseId);
            if (!$course) {
                $course = Course::with(['fee', 'cabang'])
                    ->where('id', $courseId)
                    ->where('status', 'aktif')
                    ->where(function ($q) use ($student) {
                        $q->whereNull('cabang_id')
                          ->orWhere('cabang_id', $student->branch_id);
                    })
                    ->first();
            }
            if ($course) {
                $draftCourses->push($course);
                if (!$courses->contains('id', $course->id)) {
                    $courses = $courses->push($course);
                }
            }
        }

        // Also include courses with verified payments that are not yet enrolled
        $verifiedPaymentCourses = StudentCoursePayment::where('student_id', $student->id)
            ->where('status', 'verified')
            ->whereNotIn('course_id', function ($q) use ($student) {
                $q->select('mata_pelajaran_id')->from('school_classes')
                    ->whereIn('id', function ($q2) use ($student) {
                        $q2->select('class_id')->from('class_students')->where('student_id', $student->id);
                    });
            })
            ->with('course')
            ->get()
            ->pluck('course');

        foreach ($verifiedPaymentCourses as $course) {
            if ($course && !$courses->contains('id', $course->id)) {
                $courses = $courses->push($course);
            }
        }

        $invoices = Invoice::where('siswa_id', $student->id)
            ->orderByDesc('created_at')
            ->get();

        return view('siswa.billing.index', compact(
            'courses', 'fees', 'payments', 'draftCourses', 'student', 'invoices'
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

        // Remove from draft courses after successful payment
        $draftCourses = session()->get('draft_courses', []);
        $draftCourses = array_diff($draftCourses, [$course->id]);
        session()->put('draft_courses', array_values($draftCourses));

        return redirect()->route('siswa.billing.index')
            ->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
    }

    public function bulkPay(Request $request)
    {
        $student = Student::where('user_id', auth()->id())->first();
        if (! $student) {
            return redirect()->route('siswa.attendance');
        }

        $data = $request->validate([
            'courses' => 'required|array|min:1',
            'courses.*' => 'integer|exists:courses,id',
            'proof'   => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'catatan' => 'nullable|string|max:500',
        ]);

        $path = $data['proof']->store('payments', 'public');
        $createdCount = 0;

        foreach ($data['courses'] as $courseId) {
            $existing = StudentCoursePayment::where('student_id', $student->id)
                ->where('course_id', $courseId)
                ->where('status', 'pending')
                ->first();

            if (!$existing) {
                $amount = DB::table('course_fees')->where('course_id', $courseId)->value('amount') ?? 0;

                StudentCoursePayment::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'course_id'  => $courseId,
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
                $createdCount++;
            }
        }

        return redirect()->route('siswa.billing.index')
            ->with('success', "Bukti pembayaran berhasil diunggah untuk {$createdCount} mata pelajaran. Menunggu verifikasi admin.");
    }

    public function invoiceDetail(Invoice $invoice)
    {
        $student = Student::where('user_id', auth()->id())->firstOrFail();
        if ((int)$invoice->siswa_id !== (int)$student->id) abort(403);

        $invoice->load(['siswa', 'schoolClass.mataPelajaran', 'schoolClass.cabang', 'pembayaran']);

        $payments = \App\Models\Payment::where('invoice_id', $invoice->id)
            ->orderByDesc('created_at')
            ->get();

        return view('siswa.billing.invoice-detail', compact('invoice', 'payments', 'student'));
    }

    public function invoiceUpload(Request $request, Invoice $invoice)
    {
        $student = Student::where('user_id', auth()->id())->firstOrFail();
        if ((int)$invoice->siswa_id !== (int)$student->id) abort(403);
        if ($invoice->status === 'lunas') {
            return back()->with('error', 'Invoice ini sudah lunas.');
        }

        $request->validate([
            'jumlah'          => 'required|numeric|min:1000',
            'metode'          => 'required|in:transfer,cash,qris,lainnya',
            'nama_bank'       => 'nullable|string|max:100',
            'nomor_rekening'  => 'nullable|string|max:50',
            'bukti_pembayaran'=> 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'catatan'         => 'nullable|string|max:500',
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $buktiPath = $request->file('bukti_pembayaran')->store('payments/bukti', 'public');
        }

        \App\Models\Payment::create([
            'invoice_id'        => $invoice->id,
            'siswa_id'          => $student->id,
            'cabang_id'         => $student->branch_id,
            'nomor_pembayaran'  => 'PAY-' . strtoupper(uniqid()),
            'jumlah'            => $request->jumlah,
            'metode'            => $request->metode,
            'nama_bank'         => $request->nama_bank,
            'nomor_rekening'    => $request->nomor_rekening,
            'bukti_pembayaran'  => $buktiPath,
            'tanggal_pembayaran'=> now()->toDateString(),
            'status'            => 'pending',
            'catatan'           => $request->catatan,
        ]);

        return redirect()->route('siswa.billing.invoice-detail', $invoice)
            ->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
    }
}
