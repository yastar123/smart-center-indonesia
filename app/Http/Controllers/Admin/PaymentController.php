<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Branch;
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

        $stats = [
            'total_tagihan' => Invoice::sum('total'),
            'lunas'         => Invoice::where('status', 'lunas')->count(),
            'belum_bayar'   => Invoice::where('status', 'belum_bayar')->count(),
            'pendapatan'    => Payment::where('status', 'verified')->sum('jumlah'),
        ];

        return view('admin.payments.index', compact('invoices', 'students', 'branches', 'stats'));
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
}
