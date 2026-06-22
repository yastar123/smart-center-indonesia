<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Branch;
use App\Models\StudentCoursePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VerifikasiPembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['invoice.siswa', 'siswa', 'cabang'])
            ->when($request->status ?? 'pending', fn($q, $s) => $q->where('status', $s))
            ->when($request->search, fn($q, $s) =>
                $q->whereHas('siswa', fn($sq) => $sq->where('name', 'like', "%$s%"))
                  ->orWhere('nomor_pembayaran', 'like', "%$s%"))
            ->orderByDesc('created_at');

        if (auth()->user()->hasRole('admin')) {
            $query->where('cabang_id', auth()->user()->admin?->branch_id);
        }

        $payments = $query->paginate(20)->appends($request->all());

        $counts = [
            'pending'  => Payment::where('status', 'pending')->count(),
            'verified' => Payment::where('status', 'verified')->count(),
            'rejected' => Payment::where('status', 'rejected')->count(),
        ];

        // Package payments (StudentCoursePayment) - for separate section
        $pkgQuery = StudentCoursePayment::with(['student', 'course'])
            ->when($request->pkg_status ?? 'pending', fn($q, $s) => $q->where('status', $s))
            ->when($request->search, fn($q, $s) =>
                $q->whereHas('student', fn($sq) => $sq->where('name', 'like', "%$s%")))
            ->orderByDesc('created_at');

        $packagePayments = $pkgQuery->paginate(20, ['*'], 'pkg_page')->appends($request->all());

        $pkgCounts = [
            'pending'  => StudentCoursePayment::where('status', 'pending')->count(),
            'verified' => StudentCoursePayment::where('status', 'verified')->count(),
            'rejected' => StudentCoursePayment::where('status', 'rejected')->count(),
        ];

        return view('admin.verifikasi-pembayaran.index', compact(
            'payments', 'counts', 'packagePayments', 'pkgCounts'
        ));
    }

    public function approve(Request $request, Payment $payment)
    {
        $payment->update([
            'status'           => 'verified',
            'disetujui_oleh'   => auth()->id(),
            'tanggal_disetujui'=> now(),
            'alasan_penolakan' => null,
        ]);

        if ($payment->invoice_id) {
            $invoice = Invoice::find($payment->invoice_id);
            if ($invoice) {
                $totalPaid = Payment::where('invoice_id', $invoice->id)
                    ->where('status', 'verified')
                    ->sum('jumlah');

                if ($totalPaid >= $invoice->total) {
                    $invoice->update(['status' => 'lunas']);
                } elseif ($totalPaid > 0) {
                    $invoice->update(['status' => 'sebagian']);
                }
            }
        }

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function reject(Request $request, Payment $payment)
    {
        $request->validate(['alasan_penolakan' => 'required|string|max:500']);
        $payment->update([
            'status'           => 'rejected',
            'alasan_penolakan' => $request->alasan_penolakan,
        ]);

        return back()->with('success', 'Pembayaran ditolak.');
    }

    public function show(Payment $payment)
    {
        $payment->load(['invoice.siswa', 'siswa', 'cabang', 'approver']);
        return view('admin.verifikasi-pembayaran.show', compact('payment'));
    }

    // --- Package Payment (StudentCoursePayment) ---

    public function approvePackage(Request $request, StudentCoursePayment $packagePayment)
    {
        $packagePayment->update([
            'status'      => 'verified',
            'verified_by' => auth()->id(),
        ]);

        return back()->with('success', 'Pembayaran paket berhasil diverifikasi.');
    }

    public function rejectPackage(Request $request, StudentCoursePayment $packagePayment)
    {
        $request->validate(['alasan_penolakan' => 'required|string|max:500']);
        $packagePayment->update([
            'status'          => 'rejected',
            'rejected_reason' => $request->alasan_penolakan,
        ]);

        return back()->with('success', 'Pembayaran paket ditolak.');
    }
}
