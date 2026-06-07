<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Salary;
use App\Models\Teacher;
use App\Models\Branch;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SalaryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $q = Salary::with('guru', 'cabang')
                ->when($request->search,    fn($q) => $q->whereHas('guru', fn($g) => $g->where('name', 'like', "%{$request->search}%")))
                ->when($request->status,    fn($q) => $q->where('status', $request->status))
                ->when($request->cabang_id, fn($q) => $q->where('cabang_id', $request->cabang_id))
                ->when($request->periode,   fn($q) => $q->where('periode', $request->periode))
                ->latest();

            $salaries = $q->paginate(15);
            $stats = [
                'total'   => Salary::count(),
                'dibayar' => Salary::where('status', 'dibayar')->count(),
                'pending' => Salary::where('status', 'pending')->count(),
                'total_nominal' => Salary::where('status', 'dibayar')->sum('total_gaji'),
            ];
            return response()->json(array_merge($salaries->toArray(), ['stats' => $stats]));
        }

        $teachers = Teacher::where('status', 'aktif')->orderBy('name')->get();
        $branches = Branch::orderBy('name')->get();
        return view('admin.salaries.index', compact('teachers', 'branches'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'guru_id'              => 'required|exists:teachers,id',
            'cabang_id'            => 'nullable|exists:branches,id',
            'periode'              => 'required|string|max:20',
            'gaji_pokok'           => 'required|numeric|min:0',
            'jam_mengajar'         => 'nullable|numeric|min:0',
            'tarif_per_jam'        => 'nullable|numeric|min:0',
            'bonus'                => 'nullable|numeric|min:0',
            'potongan'             => 'nullable|numeric|min:0',
            'metode_pembayaran'    => 'nullable|string|max:50',
            'nama_bank'            => 'nullable|string|max:50',
            'nomor_rekening'       => 'nullable|string|max:50',
            'tanggal_pembayaran'   => 'nullable|date',
            'status'               => 'required|in:pending,dibayar,batal',
            'catatan'              => 'nullable|string',
        ]);

        $jamMengajar = (float)($data['jam_mengajar'] ?? 0);
        $tarifPerJam = (float)($data['tarif_per_jam'] ?? 0);
        $data['total_gaji_mengajar'] = $jamMengajar * $tarifPerJam;
        $data['total_gaji'] = (float)$data['gaji_pokok']
            + $data['total_gaji_mengajar']
            + (float)($data['bonus'] ?? 0)
            - (float)($data['potongan'] ?? 0);
        $data['dibayar_oleh'] = auth()->id();

        Salary::create($data);
        return response()->json(['success' => true, 'message' => 'Data gaji berhasil disimpan!']);
    }

    public function show(Salary $salary)
    {
        return response()->json(['success' => true, 'data' => $salary->load('guru', 'cabang')]);
    }

    public function update(Request $request, Salary $salary)
    {
        $data = $request->validate([
            'guru_id'            => 'required|exists:teachers,id',
            'cabang_id'          => 'nullable|exists:branches,id',
            'periode'            => 'required|string|max:20',
            'gaji_pokok'         => 'required|numeric|min:0',
            'jam_mengajar'       => 'nullable|numeric|min:0',
            'tarif_per_jam'      => 'nullable|numeric|min:0',
            'bonus'              => 'nullable|numeric|min:0',
            'potongan'           => 'nullable|numeric|min:0',
            'metode_pembayaran'  => 'nullable|string|max:50',
            'nama_bank'          => 'nullable|string|max:50',
            'nomor_rekening'     => 'nullable|string|max:50',
            'tanggal_pembayaran' => 'nullable|date',
            'status'             => 'required|in:pending,dibayar,batal',
            'catatan'            => 'nullable|string',
        ]);

        $jamMengajar = (float)($data['jam_mengajar'] ?? 0);
        $tarifPerJam = (float)($data['tarif_per_jam'] ?? 0);
        $data['total_gaji_mengajar'] = $jamMengajar * $tarifPerJam;
        $data['total_gaji'] = (float)$data['gaji_pokok']
            + $data['total_gaji_mengajar']
            + (float)($data['bonus'] ?? 0)
            - (float)($data['potongan'] ?? 0);

        $salary->update($data);
        return response()->json(['success' => true, 'message' => 'Data gaji berhasil diperbarui!']);
    }

    public function destroy(Salary $salary)
    {
        $salary->delete();
        return response()->json(['success' => true, 'message' => 'Data gaji berhasil dihapus!']);
    }

    public function printSlip(Salary $salary)
    {
        $salary->load('guru', 'cabang', 'pembayaranOleh');
        $pdf = Pdf::loadView('admin.salaries.slip-pdf', compact('salary'))
            ->setPaper('a4');
        return $pdf->stream('slip-gaji-' . $salary->guru->name . '-' . $salary->periode . '.pdf');
    }
}
