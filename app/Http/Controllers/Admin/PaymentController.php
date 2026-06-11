<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Branch;
use App\Models\StudentCoursePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::where('status', 'aktif')->orderBy('name')->get();
        $branches = Branch::all();

        $invoices = Invoice::with(['siswa', 'cabang'])
            ->when($request->search, fn($q) =>
                $q->whereHas('siswa', fn($sq) =>
                    $sq->where('name', 'like', "%{$request->search}%"))
                  ->orWhere('nomor_invoice', 'like', "%{$request->search}%"))
            ->when($request->status,    fn($q) => $q->where('status', $request->status))
            ->when($request->branch_id, fn($q) => $q->where('cabang_id', $request->branch_id))
            ->latest()
            ->paginate(10)->withQueryString();

        // Course payments
        $coursePayments = StudentCoursePayment::with(['student', 'course', 'verifier'])
            ->when($request->course_status, fn ($q) => $q->where('status', $request->course_status))
            ->when($request->search, function ($q) use ($request) {
                $q->whereHas('student', fn ($sq) => $sq->where('name', 'like', "%{$request->search}%"));
            })
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total_tagihan' => Invoice::sum('total'),
            'lunas'         => Invoice::where('status', 'lunas')->count(),
            'belum_bayar'   => Invoice::where('status', 'belum_bayar')->count(),
            'pendapatan'    => Payment::where('status', 'verified')->sum('jumlah'),
            'course_pending' => StudentCoursePayment::where('status', 'pending')->count(),
            'course_verified' => StudentCoursePayment::where('status', 'verified')->count(),
        ];

        return view('admin.payments.index', compact('invoices', 'students', 'branches', 'stats', 'coursePayments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'siswa_id'    => 'required|exists:students,id',
            'cabang_id'   => 'required|exists:branches,id',
            'subtotal'    => 'required|numeric|min:0',
            'diskon'      => 'nullable|numeric|min:0',
            'pajak'       => 'nullable|numeric|min:0',
            'total'       => 'required|numeric|min:0',
            'deskripsi'   => 'nullable|string',
            'periode'     => 'nullable|string|max:50',
            'jatuh_tempo' => 'nullable|date',
            'catatan'     => 'nullable|string',
        ]);

        $data['nomor_invoice'] = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(5));
        $data['status']        = 'belum_bayar';

        $invoice = Invoice::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Invoice berhasil dibuat',
            'data'    => $invoice,
        ]);
    }

    public function show(Invoice $payment)
    {
        $payment->load(['siswa', 'cabang', 'pembayaran']);
        return response()->json(['success' => true, 'data' => $payment]);
    }

    public function update(Request $request, Invoice $payment)
    {
        $data = $request->validate([
            'subtotal'    => 'required|numeric|min:0',
            'diskon'      => 'nullable|numeric|min:0',
            'pajak'       => 'nullable|numeric|min:0',
            'total'       => 'required|numeric|min:0',
            'deskripsi'   => 'nullable|string',
            'periode'     => 'nullable|string|max:50',
            'jatuh_tempo' => 'nullable|date',
            'status'      => 'required|in:belum_bayar,sebagian,lunas',
            'catatan'     => 'nullable|string',
        ]);

        $payment->update($data);

        return response()->json(['success' => true, 'message' => 'Invoice berhasil diperbarui']);
    }

    public function destroy(Invoice $payment)
    {
        $payment->delete();
        return response()->json(['success' => true, 'message' => 'Invoice berhasil dihapus']);
    }

    public function markPaid(Request $request, Invoice $invoice)
    {
        $data = $request->validate([
            'jumlah'             => 'required|numeric|min:1',
            'metode'             => 'required|in:cash,transfer,qris',
            'tanggal_pembayaran' => 'required|date',
            'catatan'            => 'nullable|string',
        ]);

        $data['invoice_id']        = $invoice->id;
        $data['siswa_id']          = $invoice->siswa_id;
        $data['cabang_id']         = $invoice->cabang_id;
        $data['nomor_pembayaran']  = 'PAY-' . date('Ymd') . '-' . strtoupper(Str::random(5));
        $data['status']            = 'verified';
        $data['disetujui_oleh']    = auth()->id();
        $data['tanggal_disetujui'] = now();

        Payment::create($data);

        $totalPaid = $invoice->pembayaran()->where('status', 'verified')->sum('jumlah');
        if ($totalPaid >= $invoice->total) {
            $invoice->update(['status' => 'lunas']);
        } elseif ($totalPaid > 0) {
            $invoice->update(['status' => 'sebagian']);
        }

        return response()->json(['success' => true, 'message' => 'Pembayaran berhasil dicatat']);
    }

    // Course payment verification methods
    public function verifyCoursePayment(StudentCoursePayment $payment)
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

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Pembayaran mata pelajaran berhasil diverifikasi. Siswa telah ditambahkan ke kelas.']);
        }

        return back()->with('success', 'Pembayaran mata pelajaran berhasil diverifikasi. Siswa telah ditambahkan ke kelas.');
    }

    public function rejectCoursePayment(Request $request, StudentCoursePayment $payment)
    {
        $data = $request->validate([
            'rejected_reason' => 'required|string|max:500',
        ]);

        $payment->update([
            'status'          => 'rejected',
            'rejected_reason' => $data['rejected_reason'],
            'verified_by'     => auth()->id(),
        ]);

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Pembayaran mata pelajaran ditolak. Siswa dapat upload ulang.']);
        }

        return back()->with('success', 'Pembayaran mata pelajaran ditolak. Siswa dapat upload ulang.');
    }
}
