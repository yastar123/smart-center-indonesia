<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tryout;
use App\Models\Question;
use App\Models\Branch;
use App\Models\Course;
use Illuminate\Http\Request;

class TryoutController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $q = Tryout::with('cabang', 'pembuat')
                ->when($request->search,    fn($q) => $q->where('judul', 'like', "%{$request->search}%"))
                ->when($request->kategori,  fn($q) => $q->where('kategori', $request->kategori))
                ->when($request->status,    fn($q) => $q->where('status', $request->status))
                ->latest();

            $tryouts = $q->paginate(12);
            $stats = [
                'total'   => Tryout::count(),
                'aktif'   => Tryout::where('status', 'aktif')->count(),
                'draft'   => Tryout::where('status', 'draft')->count(),
                'peserta' => \App\Models\TryoutAttempt::distinct('siswa_id')->count(),
            ];
            return response()->json(array_merge($tryouts->toArray(), ['stats' => $stats]));
        }

        $branches = Branch::orderBy('name')->get();
        $courses  = Course::orderBy('nama')->get();
        return view('admin.tryouts.index', compact('branches', 'courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul'                    => 'required|string|max:200',
            'deskripsi'                => 'nullable|string',
            'kategori'                 => 'required|string|max:50',
            'durasi_menit'             => 'required|integer|min:5',
            'nilai_kelulusan'          => 'nullable|numeric|min:0|max:100',
            'waktu_mulai'              => 'nullable|date',
            'waktu_selesai'            => 'nullable|date',
            'is_random'                => 'nullable|boolean',
            'tampilkan_hasil_langsung' => 'nullable|boolean',
            'tampilkan_kunci_jawaban'  => 'nullable|boolean',
            'maksimal_percobaan'       => 'nullable|integer|min:1',
            'cabang_id'                => 'nullable|exists:branches,id',
            'status'                   => 'required|in:aktif,draft,selesai',
        ]);

        $data['dibuat_oleh']               = auth()->id();
        $data['is_random']                 = $request->boolean('is_random');
        $data['tampilkan_hasil_langsung']  = $request->boolean('tampilkan_hasil_langsung');
        $data['tampilkan_kunci_jawaban']   = $request->boolean('tampilkan_kunci_jawaban');

        $tryout = Tryout::create($data);
        return response()->json(['success' => true, 'message' => 'Tryout berhasil dibuat!', 'data' => $tryout]);
    }

    public function show(Tryout $tryout)
    {
        return response()->json(['success' => true, 'data' => $tryout->load('cabang', 'soal')]);
    }

    public function update(Request $request, Tryout $tryout)
    {
        $data = $request->validate([
            'judul'                    => 'required|string|max:200',
            'deskripsi'                => 'nullable|string',
            'kategori'                 => 'required|string|max:50',
            'durasi_menit'             => 'required|integer|min:5',
            'nilai_kelulusan'          => 'nullable|numeric|min:0|max:100',
            'waktu_mulai'              => 'nullable|date',
            'waktu_selesai'            => 'nullable|date',
            'is_random'                => 'nullable|boolean',
            'tampilkan_hasil_langsung' => 'nullable|boolean',
            'tampilkan_kunci_jawaban'  => 'nullable|boolean',
            'maksimal_percobaan'       => 'nullable|integer|min:1',
            'cabang_id'                => 'nullable|exists:branches,id',
            'status'                   => 'required|in:aktif,draft,selesai',
        ]);

        $data['is_random']                = $request->boolean('is_random');
        $data['tampilkan_hasil_langsung'] = $request->boolean('tampilkan_hasil_langsung');
        $data['tampilkan_kunci_jawaban']  = $request->boolean('tampilkan_kunci_jawaban');

        $tryout->update($data);
        return response()->json(['success' => true, 'message' => 'Tryout berhasil diperbarui!']);
    }

    public function destroy(Tryout $tryout)
    {
        $tryout->soal()->delete();
        $tryout->delete();
        return response()->json(['success' => true, 'message' => 'Tryout berhasil dihapus!']);
    }

    // ---- SOAL ----
    public function soalIndex(Tryout $tryout)
    {
        $soal = $tryout->soal()->orderBy('urutan')->get();
        return response()->json(['success' => true, 'tryout' => $tryout, 'soal' => $soal]);
    }

    public function soalStore(Request $request, Tryout $tryout)
    {
        $data = $request->validate([
            'teks_pertanyaan'  => 'required|string',
            'jenis'            => 'required|in:pilihan_ganda,benar_salah,isian',
            'pilihan_jawaban'  => 'nullable|array',
            'kunci_jawaban'    => 'nullable|string|max:20',
            'penjelasan'       => 'nullable|string',
            'poin'             => 'nullable|numeric|min:0',
            'tingkat_kesulitan'=> 'nullable|in:mudah,sedang,sulit',
        ]);

        $data['tryout_id'] = $tryout->id;
        $data['urutan']    = $tryout->soal()->max('urutan') + 1;

        $soal = Question::create($data);

        $tryout->update(['total_soal' => $tryout->soal()->count()]);

        return response()->json(['success' => true, 'message' => 'Soal berhasil ditambahkan!', 'data' => $soal]);
    }

    public function soalDestroy(Tryout $tryout, Question $soal)
    {
        $soal->delete();
        $tryout->update(['total_soal' => $tryout->soal()->count()]);
        return response()->json(['success' => true, 'message' => 'Soal berhasil dihapus!']);
    }

    public function results(Tryout $tryout)
    {
        $attempts = $tryout->percobaan()->with('siswa')->orderByDesc('nilai')->get();
        return response()->json(['success' => true, 'data' => $attempts]);
    }
}
