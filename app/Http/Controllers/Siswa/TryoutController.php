<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Tryout;
use App\Models\TryoutAttempt;
use App\Models\Student;
use Illuminate\Http\Request;

class TryoutController extends Controller
{
    private function getStudent()
    {
        return Student::where('user_id', auth()->id())->first();
    }

    public function index()
    {
        $student = $this->getStudent();

        $tryouts = Tryout::where('status', 'aktif')
            ->where(function ($q) use ($student) {
                $q->whereNull('cabang_id');
                if ($student) {
                    $q->orWhere('cabang_id', $student->branch_id);
                }
            })
            ->withCount('soal')
            ->orderByDesc('created_at')
            ->get();

        $attempts = collect();
        if ($student) {
            $attempts = TryoutAttempt::where('siswa_id', $student->id)
                ->orderByDesc('created_at')
                ->get()
                ->groupBy('tryout_id');
        }

        return view('siswa.tryouts.index', compact('tryouts', 'attempts', 'student'));
    }

    public function show(Tryout $tryout)
    {
        $student = $this->getStudent();
        if (!$student) {
            return redirect()->route('siswa.dashboard')->with('error', 'Data siswa tidak ditemukan.');
        }

        // Check availability
        if ($tryout->status !== 'aktif') {
            return redirect()->route('siswa.tryout')->with('error', 'Tryout tidak tersedia.');
        }

        $attemptsDone = TryoutAttempt::where('tryout_id', $tryout->id)
            ->where('siswa_id', $student->id)
            ->where('status', 'selesai')
            ->count();

        if ($tryout->maksimal_percobaan && $attemptsDone >= $tryout->maksimal_percobaan) {
            return redirect()->route('siswa.tryout')->with('error', 'Anda telah mencapai batas maksimal percobaan untuk tryout ini.');
        }

        // Check for ongoing attempt
        $activeAttempt = TryoutAttempt::where('tryout_id', $tryout->id)
            ->where('siswa_id', $student->id)
            ->where('status', 'berlangsung')
            ->first();

        if (!$activeAttempt) {
            $activeAttempt = TryoutAttempt::create([
                'tryout_id'   => $tryout->id,
                'siswa_id'    => $student->id,
                'waktu_mulai' => now(),
                'percobaan_ke'=> $attemptsDone + 1,
                'status'      => 'berlangsung',
            ]);
        }

        $soal = $tryout->soal()->orderBy('urutan')->get();
        if ($tryout->is_random) {
            $soal = $soal->shuffle()->values();
        }

        return view('siswa.tryouts.show', compact('tryout', 'soal', 'activeAttempt'));
    }

    public function submit(Request $request, Tryout $tryout)
    {
        $student = $this->getStudent();
        if (!$student) abort(403);

        $attempt = TryoutAttempt::where('tryout_id', $tryout->id)
            ->where('siswa_id', $student->id)
            ->where('status', 'berlangsung')
            ->firstOrFail();

        $jawabanSiswa = $request->input('jawaban', []);
        $soalList     = $tryout->soal()->get();

        $benar = 0; $salah = 0; $tidakDijawab = 0; $totalPoin = 0; $maxPoin = 0;

        foreach ($soalList as $s) {
            $maxPoin += (float) $s->poin;
            $jawab    = $jawabanSiswa[$s->id] ?? null;

            if ($jawab === null || $jawab === '') {
                $tidakDijawab++;
            } else {
                $kunci = $s->kunci_jawaban;
                if ($kunci !== null && $kunci !== '' && (string) $jawab === (string) $kunci) {
                    $benar++;
                    $totalPoin += (float) $s->poin;
                } else {
                    $salah++;
                }
            }
        }

        $totalSoal = $soalList->count();
        $nilai     = $maxPoin > 0 ? ($totalPoin / $maxPoin) * 100 : ($totalSoal > 0 ? ($benar / $totalSoal) * 100 : 0);

        $attempt->update([
            'waktu_selesai'  => now(),
            'nilai'          => round($nilai, 2),
            'jawaban_benar'  => $benar,
            'jawaban_salah'  => $salah,
            'tidak_dijawab'  => $tidakDijawab,
            'status'         => 'selesai',
            'jawaban'        => $jawabanSiswa,
        ]);

        return redirect()->route('siswa.tryout.result', [$tryout->id, $attempt->id]);
    }

    public function result(Tryout $tryout, TryoutAttempt $attempt)
    {
        $student = $this->getStudent();
        if (!$student || $attempt->siswa_id !== $student->id) abort(403);

        $soal = $tryout->soal()->orderBy('urutan')->get();
        return view('siswa.tryouts.result', compact('tryout', 'attempt', 'soal'));
    }
}
