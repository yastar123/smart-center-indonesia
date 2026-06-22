<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Invoice;
use App\Models\Branch;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagihanSiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = SchoolClass::with(['cabang', 'guru', 'mataPelajaran', 'siswa.user', 'siswa.package'])
            ->whereIn('billing_mode', ['postpaid', 'cicilan']);

        if (auth()->user()->hasRole('admin')) {
            $query->where('cabang_id', auth()->user()->admin?->branch_id);
        }

        if ($s = $request->search) {
            $query->where(function ($q) use ($s) {
                $q->where('nama_kelas', 'like', "%$s%")
                  ->orWhereHas('siswa', fn($sq) => $sq->where('name', 'like', "%$s%"));
            });
        }

        if ($request->billing_mode) {
            $query->where('billing_mode', $request->billing_mode);
        }

        if ($request->cabang_id) {
            $query->where('cabang_id', $request->cabang_id);
        }

        $classes = $query->latest()->paginate(20)->appends($request->all());

        $branches = Branch::orderBy('name')->get();

        $totalCicilan  = SchoolClass::where('billing_mode', 'cicilan')->count();
        $totalPostpaid = SchoolClass::where('billing_mode', 'postpaid')->count();

        // Compute payment summary per kelas
        $kelasIds = $classes->pluck('id')->toArray();

        // Fetch invoices that have kelas_id set
        $invoicesWithKelas = Invoice::whereIn('kelas_id', $kelasIds)->with('pembayaran')->get();
        $invoicesByKelas   = $invoicesWithKelas->groupBy('kelas_id');

        // Also fetch null-kelas_id invoices for students in these classes (registration invoices)
        $allStudentIds  = $classes->flatMap(fn($k) => $k->siswa->pluck('id'))->unique()->values()->toArray();
        $studentKelasMap = $classes->flatMap(fn($k) => $k->siswa->map(fn($s) => [
            'student_id' => $s->id, 'kelas_id' => $k->id, 'cabang_id' => $k->cabang_id
        ]))->keyBy('student_id');

        if (!empty($allStudentIds)) {
            $nullKelasInvoices = Invoice::whereNull('kelas_id')
                ->whereIn('siswa_id', $allStudentIds)
                ->with('pembayaran')
                ->get();

            foreach ($nullKelasInvoices as $inv) {
                $entry = $studentKelasMap->get($inv->siswa_id);
                if ($entry && $entry['cabang_id'] == $inv->cabang_id) {
                    $kelasId = $entry['kelas_id'];
                    if (!isset($invoicesByKelas[$kelasId])) {
                        $invoicesByKelas[$kelasId] = collect();
                    }
                    $invoicesByKelas[$kelasId]->push($inv);
                }
            }
        }

        $stats = [
            'total'    => $totalCicilan + $totalPostpaid,
            'postpaid' => $totalPostpaid,
            'cicilan'  => $totalCicilan,
            'menunggu' => Invoice::whereIn('status', ['belum_bayar', 'sebagian'])->count(),
        ];

        return view('admin.tagihan-siswa.index', compact('classes', 'branches', 'stats', 'invoicesByKelas'));
    }

    public function show(Request $request, SchoolClass $kelas)
    {
        $kelas->load(['cabang', 'guru', 'mataPelajaran', 'siswa.user', 'siswa.package']);

        if (auth()->user()->hasRole('admin')) {
            $branchId = auth()->user()->admin?->branch_id;
            if ($branchId && $kelas->cabang_id !== $branchId) {
                abort(403);
            }
        }

        $student = $kelas->siswa->first();

        // Only show invoices that belong to THIS kelas (by kelas_id, fall back to cabang filter)
        $invoices = collect();
        if ($student) {
            $invoices = Invoice::where('siswa_id', $student->id)
                ->where(function ($q) use ($kelas) {
                    $q->where('kelas_id', $kelas->id)
                      ->orWhere(function ($q2) use ($kelas) {
                          $q2->whereNull('kelas_id')->where('cabang_id', $kelas->cabang_id);
                      });
                })
                ->with('pembayaran')
                ->orderByDesc('created_at')
                ->get();
        }

        // Payment summary
        $hargaPaket      = $student?->package?->harga ?? 0;
        $totalDibayar    = $invoices->flatMap->pembayaran->where('status', 'verified')->sum('jumlah');
        $totalTagihan    = $invoices->sum('total');
        $sisaCicilan     = max(0, $totalTagihan - $totalDibayar);
        $jumlahInvoiceLunas = $invoices->where('status', 'lunas')->count();

        return view('admin.tagihan-siswa.show', compact(
            'kelas', 'student', 'invoices',
            'hargaPaket', 'totalDibayar', 'sisaCicilan', 'jumlahInvoiceLunas', 'totalTagihan'
        ));
    }

    public function generateInvoice(Request $request, SchoolClass $kelas)
    {
        $request->validate([
            'deskripsi'   => 'required|string|max:255',
            'total'       => 'required|numeric|min:1000',
            'jatuh_tempo' => 'required|date',
        ]);

        $student = $kelas->siswa()->first();
        if (!$student) {
            return back()->with('error', 'Tidak ada siswa di kelas ini.');
        }

        $year  = date('Y');
        $month = str_pad(date('m'), 2, '0', STR_PAD_LEFT);
        $count = Invoice::whereYear('created_at', $year)->whereMonth('created_at', date('m'))->count() + 1;
        $nomor = 'INV-' . $year . '-' . $month . str_pad($count, 3, '0', STR_PAD_LEFT);

        Invoice::create([
            'siswa_id'      => $student->id,
            'cabang_id'     => $kelas->cabang_id,
            'kelas_id'      => $kelas->id,
            'nomor_invoice' => $nomor,
            'deskripsi'     => $request->deskripsi,
            'subtotal'      => $request->total,
            'diskon'        => 0,
            'pajak'         => 0,
            'total'         => $request->total,
            'status'        => 'belum_bayar',
            'jatuh_tempo'   => $request->jatuh_tempo,
            'periode'       => date('Y-m'),
        ]);

        return back()->with('success', 'Invoice berhasil dibuat.');
    }
}
