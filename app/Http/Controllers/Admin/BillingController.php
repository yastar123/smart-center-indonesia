<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Student;
use App\Models\Branch;
use App\Models\Package;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BillingController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['siswa', 'cabang'])->where('status', 'lunas');

        if (auth()->user()->hasRole('admin')) {
            $query->where('cabang_id', auth()->user()->admin?->branch_id);
        }

        if ($s = $request->search) {
            $query->where(function ($q) use ($s) {
                $q->where('nomor_invoice', 'like', "%$s%")
                  ->orWhereHas('siswa', fn($sq) => $sq->where('name', 'like', "%$s%"));
            });
        }
        if ($request->periode) {
            $query->where('periode', $request->periode);
        }

        $invoices = $query->latest()->paginate(15)->appends($request->all());

        $now = Carbon::now();

        $stats = [
            'total_piutang' => Invoice::whereNotIn('status', ['lunas'])->sum('total'),
            'menunggu'      => Invoice::where('status', 'belum_bayar')
                                     ->whereNotNull('jatuh_tempo')
                                     ->where('jatuh_tempo', '>=', $now->toDateString())
                                     ->count(),
            'overdue'       => Invoice::where('status', 'belum_bayar')
                                     ->whereNotNull('jatuh_tempo')
                                     ->where('jatuh_tempo', '<', $now->toDateString())
                                     ->count(),
            'pendapatan'    => Invoice::where('status', 'lunas')->sum('total'),
        ];

        $students = Student::orderBy('name')->get();
        $packages = Package::where('status', 'aktif')->orderBy('nama')->get();

        return view('admin.billing.index', compact('invoices', 'stats', 'students', 'packages'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'siswa_id'    => 'required|exists:students,id',
            'deskripsi'   => 'required|string|max:255',
            'total'       => 'required|numeric|min:1000',
            'jatuh_tempo' => 'required|date',
            'catatan'     => 'nullable|string',
        ]);

        $student = Student::findOrFail($data['siswa_id']);

        $year  = date('Y');
        $month = str_pad(date('m'), 2, '0', STR_PAD_LEFT);
        $count = Invoice::whereYear('created_at', $year)->whereMonth('created_at', date('m'))->count() + 1;
        $nomor = "INV-{$year}-{$month}" . str_pad($count, 3, '0', STR_PAD_LEFT);

        Invoice::create([
            'siswa_id'       => $data['siswa_id'],
            'cabang_id'      => $student->branch_id,
            'nomor_invoice'  => $nomor,
            'subtotal'       => $data['total'],
            'diskon'         => 0,
            'pajak'          => 0,
            'total'          => $data['total'],
            'deskripsi'      => $data['deskripsi'],
            'periode'        => date('Y-m'),
            'jatuh_tempo'    => $data['jatuh_tempo'],
            'status'         => 'belum_bayar',
            'catatan'        => $data['catatan'] ?? null,
        ]);

        return redirect()->route('admin.billing.index')
            ->with('success', 'Invoice berhasil diterbitkan.');
    }

    public function show(Invoice $billing)
    {
        $billing->load(['siswa', 'cabang', 'pembayaran']);
        return view('admin.billing.detail', compact('billing'));
    }

    public function update(Request $request, Invoice $billing)
    {
        if (!in_array($billing->status, ['belum_bayar', 'sebagian'])) {
            return back()->with('error', 'Invoice yang sudah lunas tidak dapat diubah.');
        }

        $data = $request->validate([
            'deskripsi'   => 'required|string|max:255',
            'total'       => 'required|numeric|min:0',
            'jatuh_tempo' => 'required|date',
            'catatan'     => 'nullable|string',
        ]);

        $billing->update([
            'deskripsi'   => $data['deskripsi'],
            'subtotal'    => $data['total'],
            'total'       => $data['total'],
            'jatuh_tempo' => $data['jatuh_tempo'],
            'catatan'     => $data['catatan'] ?? null,
        ]);

        return redirect()->route('admin.billing.index')
            ->with('success', 'Invoice berhasil diperbarui.');
    }

    public function destroy(Invoice $billing)
    {
        $billing->delete();
        return redirect()->route('admin.billing.index')
            ->with('success', 'Invoice berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $query = Invoice::with(['siswa', 'cabang']);

        if (auth()->user()->hasRole('admin')) {
            $query->where('cabang_id', auth()->user()->admin?->branch_id);
        }
        if ($s = $request->search) {
            $query->where(function ($q) use ($s) {
                $q->where('nomor_invoice', 'like', "%$s%")
                  ->orWhereHas('siswa', fn($sq) => $sq->where('name', 'like', "%$s%"));
            });
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->periode) {
            $query->where('periode', $request->periode);
        }

        $invoices = $query->latest()->get();

        $filename = 'billing-export-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($invoices) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['No. Invoice', 'Siswa', 'Cabang', 'Deskripsi', 'Total', 'Jatuh Tempo', 'Status', 'Periode', 'Dibuat']);
            foreach ($invoices as $inv) {
                $statusMap = ['belum_bayar' => 'Belum Bayar', 'sebagian' => 'Sebagian', 'lunas' => 'Lunas'];
                fputcsv($out, [
                    $inv->nomor_invoice,
                    $inv->siswa?->name ?? '-',
                    $inv->cabang?->name ?? '-',
                    $inv->deskripsi ?? '-',
                    $inv->total,
                    $inv->jatuh_tempo ?? '-',
                    $statusMap[$inv->status] ?? $inv->status,
                    $inv->periode ?? '-',
                    $inv->created_at?->format('d/m/Y H:i') ?? '-',
                ]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }
}
