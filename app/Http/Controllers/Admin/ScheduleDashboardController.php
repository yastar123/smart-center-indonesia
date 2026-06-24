<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleDashboardController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();

        $schedules = Schedule::with([
                'kelas.guru', 'kelas.mataPelajaran', 'kelas.cabang', 'kelas.siswa',
                'paket.guru', 'paket.mataPelajaran', 'paket.siswa',
                'guru', 'absensi',
            ])
            ->whereDate('tanggal', $date)
            ->orderBy('jam_mulai')
            ->get()
            ->map(function ($s) {
                $now   = now();
                $start = Carbon::parse($s->tanggal->format('Y-m-d') . ' ' . $s->jam_mulai);
                $end   = Carbon::parse($s->tanggal->format('Y-m-d') . ' ' . $s->jam_selesai);

                if ($s->status === 'selesai') {
                    $displayStatus = 'completed';
                } elseif ($now->between($start, $end)) {
                    $displayStatus = 'ongoing';
                } elseif ($now->lt($start)) {
                    $displayStatus = 'scheduled';
                } else {
                    $displayStatus = 'completed';
                }

                // Student count: prefer kelas (class_students pivot), fall back to package enrollment
                if ($s->kelas) {
                    $studentCount = $s->kelas->siswa->count();
                    $capacity     = $s->kelas->kapasitas ?? $studentCount;
                } elseif ($s->paket) {
                    $studentCount = $s->paket->siswa->count();
                    $capacity     = $studentCount;
                } else {
                    $studentCount = 0;
                    $capacity     = 0;
                }

                // Display info: prefer kelas, fall back to paket + schedule guru
                $className   = $s->kelas->nama_kelas
                    ?? ($s->paket->nama ?? '—');
                $teacherName = $s->kelas->guru->name
                    ?? ($s->guru->name ?? ($s->paket->guru->name ?? '—'));
                $subjectName = $s->kelas->mataPelajaran->nama
                    ?? ($s->paket->mataPelajaran->first()->nama ?? '—');

                return [
                    'id'             => $s->id,
                    'class_name'     => $className,
                    'teacher_name'   => $teacherName,
                    'subject_name'   => $subjectName,
                    'room_name'      => $s->ruangan ?? ($s->jenis === 'online' ? 'Online' : 'Offline'),
                    'jam_mulai'      => $s->jam_mulai,
                    'jam_selesai'    => $s->jam_selesai,
                    'jenis'          => $s->jenis,
                    'status'         => $displayStatus,
                    'topik'          => $s->topik ?? '—',
                    'students_count' => $studentCount . '/' . $capacity,
                    'pertemuan_ke'   => $s->pertemuan_ke,
                    'schedule_id'    => $s->id,
                ];
            });

        $stats = [
            'total'      => $schedules->count(),
            'ongoing'    => $schedules->where('status', 'ongoing')->count(),
            'scheduled'  => $schedules->where('status', 'scheduled')->count(),
            'completed'  => $schedules->where('status', 'completed')->count(),
        ];

        $weekDays = [];
        $weekStart = $date->copy()->startOfWeek(Carbon::MONDAY);
        for ($i = 0; $i < 6; $i++) {
            $d = $weekStart->copy()->addDays($i);
            $weekDays[] = [
                'date'    => $d->format('Y-m-d'),
                'day'     => $d->isoFormat('ddd'),
                'num'     => $d->format('d'),
                'active'  => $d->isSameDay($date),
                'count'   => Schedule::whereDate('tanggal', $d)->count(),
            ];
        }

        $classes  = SchoolClass::with(['guru', 'mataPelajaran'])->where('status', 'aktif')->orderBy('nama_kelas')->get();

        return view('admin.schedule-dashboard.index', compact(
            'schedules', 'stats', 'date', 'weekDays', 'classes'
        ));
    }
}
