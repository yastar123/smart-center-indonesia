<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Invoice;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagihanSiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = SchoolClass::with(['cabang', 'guru', 'mataPelajaran', 'siswa.user'])
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

        $stats = [
            'total'    => $totalCicilan + $totalPostpaid,
            'postpaid' => $totalPostpaid,
            'cicilan'  => $totalCicilan,
            'menunggu' => Invoice::whereIn('status', ['belum_bayar', 'sebagian'])->count(),
        ];

        return view('admin.tagihan-siswa.index', compact('classes', 'branches', 'stats'));
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
