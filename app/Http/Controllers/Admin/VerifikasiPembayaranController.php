<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Branch;
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

        return view('admin.verifikasi-pembayaran.index', compact('payments', 'counts'));
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
}
