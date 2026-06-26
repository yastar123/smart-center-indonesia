<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Course;
use Illuminate\Http\Request;

class ScheduleListController extends Controller
{
    public function index(Request $request)
    {
        $branchId = auth()->user()?->admin?->branch_id;

        $query = SchoolClass::with([
            'guru',
            'mataPelajaran',
            'siswa',
            'jadwal.paket',
            'cabang',
        ])->withCount('siswa');

        if ($branchId) {
            $query->where('cabang_id', $branchId);
        }

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_kelas', 'like', "%$search%")
                  ->orWhereHas('guru', fn ($g) => $g->where('name', 'like', "%$search%"))
                  ->orWhereHas('mataPelajaran', fn ($m) => $m->where('nama', 'like', "%$search%"));
            });
        }

        if ($statusFilter = $request->status_filter) {
            if ($statusFilter === 'aktif') {
                $query->where('status', '!=', 'draft')
                      ->whereRaw('(SELECT COUNT(*) FROM class_students WHERE class_id = school_classes.id) < COALESCE(kapasitas, 99999)');
            } elseif ($statusFilter === 'penuh') {
                $query->whereRaw('kapasitas > 0')
                      ->whereRaw('(SELECT COUNT(*) FROM class_students WHERE class_id = school_classes.id) >= kapasitas');
            } elseif ($statusFilter === 'draft') {
                $query->where('status', 'draft');
            }
        }

        if ($mapelId = $request->mata_pelajaran_id) {
            $query->where('mata_pelajaran_id', $mapelId);
        }

        $classes = $query->orderBy('nama_kelas')->paginate(15)->withQueryString();

        $dayNames = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];

        $classes->getCollection()->transform(function ($kelas) use ($dayNames) {
            $enrolled = $kelas->siswa_count;
            $capacity = $kelas->kapasitas ?: 0;
            $pct      = $capacity > 0 ? round($enrolled / $capacity * 100) : 0;

            $done  = $kelas->jadwal->where('status', 'selesai')->count();
            $total = $kelas->jumlah_pertemuan ?: $kelas->jadwal->count();

            $firstJadwal = $kelas->jadwal->first();
            $paket       = $firstJadwal?->paket;

            $room = null;
            foreach ($kelas->jadwal as $j) {
                if ($j->ruangan) { $room = $j->ruangan; break; }
                if (!empty($j->link_meeting)) { $room = 'Link Meeting'; break; }
            }
            if (!$room) {
                $room = in_array($kelas->jenis, ['online']) ? 'Link Meeting'
                    : ($kelas->link_zoom ? 'Link Meeting' : '—');
            }

            $days = $kelas->jadwal
                ->map(fn ($j) => optional($j->tanggal)->dayOfWeekIso)
                ->filter()
                ->unique()
                ->sort()
                ->values();
            $daysStr = $days->map(fn ($d) => $dayNames[$d - 1] ?? '')->filter()->join(', ');

            $timeSchedule = $kelas->jadwal->first(fn ($j) => $j->jam_mulai);
            $timeStr = $timeSchedule
                ? substr($timeSchedule->jam_mulai, 0, 5) . ' – ' . substr($timeSchedule->jam_selesai, 0, 5)
                : '—';

            $computedStatus = match (true) {
                $kelas->status === 'draft'              => 'draft',
                $capacity > 0 && $enrolled >= $capacity => 'penuh',
                default                                  => 'aktif',
            };

            $jenisLabel = match ($kelas->jenis) {
                'online'     => 'Online',
                'home_visit' => 'Home Visit',
                default      => 'In-Class',
            };

            $jenisColor = match ($kelas->jenis) {
                'online'     => 'var(--soft-info-bg);color:var(--soft-info-text)',
                'home_visit' => 'var(--soft-warning-bg);color:var(--soft-warning-text)',
                default      => 'var(--soft-primary-bg);color:var(--soft-primary-text)',
            };

            $subjectName = $kelas->mataPelajaran?->nama ?? $kelas->nama_kelas;
            $initials    = strtoupper(mb_substr(preg_replace('/[^A-Za-z]/', '', $subjectName), 0, 2));
            if (strlen($initials) < 2) {
                $initials = strtoupper(mb_substr($kelas->nama_kelas, 0, 2));
            }

            $teacherName     = $kelas->guru?->name ?? '—';
            $tParts          = preg_split('/\s+/', trim($teacherName));
            $teacherInitials = strtoupper(
                ($tParts[0][0] ?? '') . ($tParts[1][0] ?? ($tParts[0][1] ?? ''))
            );

            $code = strtoupper(preg_replace('/[^A-Z0-9]/i', '', mb_substr($kelas->nama_kelas, 0, 6)))
                . '-' . str_pad($kelas->id, 2, '0', STR_PAD_LEFT);

            $kelas->_enrolled         = $enrolled;
            $kelas->_capacity         = $capacity;
            $kelas->_pct              = $pct;
            $kelas->_done             = $done;
            $kelas->_total            = $total;
            $kelas->_paket            = $paket;
            $kelas->_room             = $room;
            $kelas->_days             = $daysStr;
            $kelas->_time             = $timeStr;
            $kelas->_computed_status  = $computedStatus;
            $kelas->_jenis_label      = $jenisLabel;
            $kelas->_jenis_color      = $jenisColor;
            $kelas->_initials         = $initials;
            $kelas->_teacher_initials = $teacherInitials;
            $kelas->_code             = $code;
            $kelas->_teacher_name     = $teacherName;

            return $kelas;
        });

        $allQuery = SchoolClass::withCount('siswa');
        if ($branchId) {
            $allQuery->where('cabang_id', $branchId);
        }
        $allClasses = $allQuery->get();

        $stats = [
            'total'       => $allClasses->count(),
            'aktif'       => $allClasses->filter(fn ($k) => $k->status !== 'draft' && ($k->kapasitas <= 0 || $k->siswa_count < $k->kapasitas))->count(),
            'penuh'       => $allClasses->filter(fn ($k) => $k->kapasitas > 0 && $k->siswa_count >= $k->kapasitas)->count(),
            'total_murid' => $allClasses->sum('siswa_count'),
        ];

        $courses = Course::orderBy('nama')->get();

        return view('admin.schedule-list.index', compact('classes', 'stats', 'courses'));
    }
}
