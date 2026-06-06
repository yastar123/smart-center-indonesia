@extends('layouts.app')
@section('title', 'Jadwal Belajar')
@section('page-title', 'Jadwal Belajar')

@section('content')

@php
use App\Models\Student;
use App\Models\Schedule;
use Carbon\Carbon;

$student = Student::where('user_id', auth()->id())->first();
$today   = today();

$viewMode = request('view', 'week');
$selWeek  = request('week')  ? Carbon::parse(request('week'))  : now();
$selMonth = request('month') ? Carbon::parse(request('month')) : now();

$weekStart = $selWeek->copy()->startOfWeek(Carbon::MONDAY);
$weekEnd   = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);

// Fetch schedules
$schedules = collect();
if ($student) {
    $schedules = Schedule::where('cabang_id', $student->branch_id)
        ->when($viewMode === 'week',
            fn($q) => $q->whereBetween('tanggal', [$weekStart, $weekEnd]),
            fn($q) => $q->whereMonth('tanggal', $selMonth->month)->whereYear('tanggal', $selMonth->year)
        )
        ->with(['kelas', 'guru.user'])
        ->orderBy('tanggal')->orderBy('jam_mulai')
        ->get();
}

// Group schedules by date
$grouped = $schedules->groupBy(fn($s) => $s->tanggal->format('Y-m-d'));

// Days of current week
$weekDays = [];
for ($d = $weekStart->copy(); $d <= $weekEnd; $d->addDay()) {
    $weekDays[] = $d->copy();
}

// Today's schedules
$todaySchedules = $schedules->filter(fn($s) => $s->tanggal->isToday());

// Upcoming this week
$upcomingSchedules = $schedules->filter(
    fn($s) => $s->tanggal->isAfter(now()) && $s->tanggal->diffInDays(now()) <= 7
)->take(5);

$statusColors = [
    'dijadwalkan' => ['#c84ddf','#fdf4ff','#e8b4f5'],
    'berlangsung' => ['#059669','#f0fdf4','#bbf7d0'],
    'selesai'     => ['#94a3b8','#f8fafc','#e2e8f0'],
    'dibatalkan'  => ['#ef4444','#fef2f2','#fecaca'],
];
$jenisIcon = ['online' => 'bi-camera-video-fill', 'offline' => 'bi-building-fill', 'hybrid' => 'bi-laptop'];
@endphp

{{-- HEADER BANNER --}}
<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#2563eb 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.04);border-radius:50%"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div>
            <div style="font-size:11px;opacity:.6;margin-bottom:4px;text-transform:uppercase;letter-spacing:.08em">
                <i class="bi bi-calendar-event me-1"></i>Jadwal Belajar
            </div>
            <h4 style="font-weight:800;margin-bottom:4px;color:white;letter-spacing:-.02em">
                Jadwal Belajar Saya
            </h4>
            <p style="opacity:.65;margin:0;font-size:13px">
                {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }} — Cabang {{ $student?->branch?->name ?? 'Tidak Diketahui' }}
            </p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="text-center">
                <div style="font-size:28px;font-weight:900;color:white">{{ $todaySchedules->count() }}</div>
                <div style="font-size:11px;opacity:.6">Sesi Hari Ini</div>
            </div>
            <div style="width:1px;height:40px;background:rgba(255,255,255,.2)"></div>
            <div class="text-center">
                <div style="font-size:28px;font-weight:900;color:#f6af23">{{ $schedules->count() }}</div>
                <div style="font-size:11px;opacity:.6">{{ $viewMode==='week' ? 'Sesi Minggu Ini' : 'Sesi Bulan Ini' }}</div>
            </div>
        </div>
    </div>
</div>

@if(!$student)
<div class="alert d-flex gap-3 align-items-start mb-4 fade-up" style="border-radius:14px;border:none;background:#fef3c7;color:#78350f">
    <i class="bi bi-exclamation-triangle-fill text-warning mt-1" style="font-size:18px;flex-shrink:0"></i>
    <div>
        <div class="fw-bold mb-1">Profil Siswa Belum Terhubung</div>
        <div style="font-size:13px">Akun Anda belum terhubung ke profil siswa. Hubungi administrator.</div>
    </div>
</div>
@endif

{{-- TODAY'S HIGHLIGHT (only if there are sessions today) --}}
@if($todaySchedules->isNotEmpty())
<div class="dashboard-card mb-4 fade-up" style="border-left:4px solid #f6af23;background:linear-gradient(135deg,var(--card-bg),rgba(246,175,35,.04))">
    <div class="d-flex align-items-center gap-2 mb-3">
        <div style="width:8px;height:8px;border-radius:50%;background:#f6af23;animation:pulse 1.5s infinite"></div>
        <h6 class="fw-bold mb-0" style="font-size:14px;color:#e09000">
            <i class="bi bi-calendar-day me-2"></i>Sesi Hari Ini — {{ today()->locale('id')->isoFormat('dddd, D MMMM') }}
        </h6>
    </div>
    <div class="row g-3">
        @foreach($todaySchedules as $s)
        @php
            $clr = $statusColors[$s->status] ?? $statusColors['dijadwalkan'];
            $now = now();
            $start = Carbon::parse($s->tanggal->format('Y-m-d').' '.$s->jam_mulai);
            $end   = Carbon::parse($s->tanggal->format('Y-m-d').' '.$s->jam_selesai);
            $isOngoing = $now->between($start, $end);
            $isPast    = $now->isAfter($end);
        @endphp
        <div class="col-md-6">
            <div class="p-3 rounded-3"
                 style="background:{{ $isOngoing ? 'linear-gradient(135deg,#059669,#10b981)' : ($isPast ? 'var(--input-bg)' : 'var(--input-bg)') }};border:1.5px solid {{ $isOngoing ? '#10b981' : 'var(--card-border)' }};color:{{ $isOngoing ? 'white' : 'inherit' }}">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="fw-bold" style="font-size:13.5px;color:{{ $isOngoing ? 'white' : 'var(--text-primary)' }}">
                        {{ $s->topik ?? 'Sesi Belajar' }}
                    </div>
                    @if($isOngoing)
                    <span class="badge" style="background:rgba(255,255,255,.25);color:white;font-size:10px;animation:pulse 1s infinite">
                        <i class="bi bi-broadcast me-1"></i>Live
                    </span>
                    @elseif($isPast)
                    <span class="badge" style="background:#e2e8f0;color:#64748b;font-size:10px">Selesai</span>
                    @else
                    <span class="badge" style="background:#fef3c7;color:#92400e;font-size:10px">Akan datang</span>
                    @endif
                </div>
                <div class="d-flex flex-wrap gap-3" style="font-size:12px;color:{{ $isOngoing ? 'rgba(255,255,255,.8)' : 'var(--text-muted)' }}">
                    <span><i class="bi bi-clock me-1"></i>{{ Carbon::parse($s->jam_mulai)->format('H:i') }}–{{ Carbon::parse($s->jam_selesai)->format('H:i') }}</span>
                    <span><i class="bi {{ $jenisIcon[$s->jenis] ?? 'bi-building' }} me-1"></i>{{ ucfirst($s->jenis ?? 'offline') }}</span>
                    @if($s->guru)
                    <span><i class="bi bi-person me-1"></i>{{ $s->guru->user?->name ?? $s->guru->name ?? '—' }}</span>
                    @endif
                </div>
                @if($s->jenis==='online' && $s->link_meeting && $isOngoing)
                <a href="{{ $s->link_meeting }}" target="_blank" class="btn btn-sm mt-2"
                   style="background:rgba(255,255,255,.25);color:white;border:1px solid rgba(255,255,255,.3);border-radius:8px;font-size:12px;font-weight:600">
                    <i class="bi bi-camera-video-fill me-1"></i>Bergabung Sekarang
                </a>
                @elseif($s->jenis==='online' && $s->link_meeting)
                <a href="{{ $s->link_meeting }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2"
                   style="border-radius:8px;font-size:12px">
                    <i class="bi bi-link me-1"></i>Link Meeting
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- VIEW CONTROLS --}}
<div class="dashboard-card mb-4 fade-up" style="padding:12px 16px">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">

        {{-- View Toggle --}}
        <div class="d-flex gap-1 p-1 rounded-3" style="background:var(--input-bg)">
            <a href="?view=week&week={{ $selWeek->format('Y-m-d') }}"
               class="btn btn-sm {{ $viewMode==='week' ? 'btn-primary shadow-sm' : '' }}"
               style="border-radius:8px;font-size:12.5px;font-weight:{{ $viewMode==='week'?'600':'400' }}">
                <i class="bi bi-calendar-week me-1"></i>Mingguan
            </a>
            <a href="?view=month&month={{ $selMonth->format('Y-m-d') }}"
               class="btn btn-sm {{ $viewMode==='month' ? 'btn-primary shadow-sm' : '' }}"
               style="border-radius:8px;font-size:12.5px;font-weight:{{ $viewMode==='month'?'600':'400' }}">
                <i class="bi bi-calendar-month me-1"></i>Bulanan
            </a>
            <a href="?view=list&week={{ $selWeek->format('Y-m-d') }}"
               class="btn btn-sm {{ $viewMode==='list' ? 'btn-primary shadow-sm' : '' }}"
               style="border-radius:8px;font-size:12.5px;font-weight:{{ $viewMode==='list'?'600':'400' }}">
                <i class="bi bi-list-ul me-1"></i>Daftar
            </a>
        </div>

        {{-- Navigation --}}
        @if($viewMode === 'week')
        <div class="d-flex align-items-center gap-2">
            <a href="?view=week&week={{ $weekStart->copy()->subWeek()->format('Y-m-d') }}"
               class="btn btn-sm btn-outline-secondary" style="border-radius:8px;width:34px;height:34px;padding:0;display:flex;align-items:center;justify-content:center">
                <i class="bi bi-chevron-left"></i>
            </a>
            <span class="fw-semibold" style="font-size:13px;min-width:160px;text-align:center">
                {{ $weekStart->locale('id')->isoFormat('D MMM') }} – {{ $weekEnd->locale('id')->isoFormat('D MMM Y') }}
            </span>
            <a href="?view=week&week={{ $weekStart->copy()->addWeek()->format('Y-m-d') }}"
               class="btn btn-sm btn-outline-secondary" style="border-radius:8px;width:34px;height:34px;padding:0;display:flex;align-items:center;justify-content:center">
                <i class="bi bi-chevron-right"></i>
            </a>
            <a href="?view=week&week={{ now()->format('Y-m-d') }}"
               class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:12px;padding:6px 12px">
                Minggu Ini
            </a>
        </div>
        @elseif($viewMode === 'month')
        <div class="d-flex align-items-center gap-2">
            <a href="?view=month&month={{ $selMonth->copy()->subMonth()->format('Y-m-d') }}"
               class="btn btn-sm btn-outline-secondary" style="border-radius:8px;width:34px;height:34px;padding:0;display:flex;align-items:center;justify-content:center">
                <i class="bi bi-chevron-left"></i>
            </a>
            <span class="fw-semibold" style="font-size:13px;min-width:140px;text-align:center">
                {{ $selMonth->locale('id')->isoFormat('MMMM Y') }}
            </span>
            <a href="?view=month&month={{ $selMonth->copy()->addMonth()->format('Y-m-d') }}"
               class="btn btn-sm btn-outline-secondary" style="border-radius:8px;width:34px;height:34px;padding:0;display:flex;align-items:center;justify-content:center">
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>
        @endif

        {{-- Jenis Filter --}}
        <div class="d-flex align-items-center gap-2">
            <span style="font-size:12px;color:var(--text-muted)">Tampilkan:</span>
            <select class="form-select form-select-sm" style="border-radius:8px;width:auto;font-size:12.5px"
                    onchange="filterJenis(this.value)">
                <option value="all">Semua Sesi</option>
                <option value="online">Online</option>
                <option value="offline">Offline</option>
            </select>
        </div>
    </div>
</div>

{{-- ===== WEEK VIEW ===== --}}
@if($viewMode === 'week')
<div class="dashboard-card fade-up">
    @if($schedules->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-calendar-x d-block mb-3" style="font-size:3.5rem;opacity:.2"></i>
        <h6 class="fw-semibold mb-1">Tidak Ada Jadwal</h6>
        <p class="text-muted mb-0" style="font-size:13.5px">Tidak ada jadwal belajar untuk minggu ini.</p>
    </div>
    @else

    {{-- Week Grid --}}
    <div class="row g-3">
        @foreach($weekDays as $day)
        @php
            $dayKey   = $day->format('Y-m-d');
            $daySched = $grouped[$dayKey] ?? collect();
            $isToday  = $day->isToday();
            $isPast   = $day->isPast() && !$isToday;
        @endphp
        <div class="col-sm-6 col-xl-{{ count($weekDays) <= 5 ? (12/count($weekDays)) : 4 }}" style="min-width:0">
            <div class="rounded-3 overflow-hidden" style="border:1.5px solid {{ $isToday ? '#c84ddf' : 'var(--card-border)' }};background:{{ $isToday ? 'linear-gradient(135deg,rgba(200,77,223,.04),rgba(104,17,126,.02))' : 'transparent' }}">

                {{-- Day Header --}}
                <div class="text-center py-2 px-3"
                     style="background:{{ $isToday ? 'linear-gradient(135deg,#c84ddf,#68117e)' : 'var(--input-bg)' }};border-bottom:1px solid {{ $isToday ? '#c84ddf' : 'var(--card-border)' }}">
                    <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:{{ $isToday ? 'rgba(255,255,255,.7)' : 'var(--text-muted)' }}">
                        {{ $day->locale('id')->isoFormat('ddd') }}
                    </div>
                    <div style="font-size:20px;font-weight:900;color:{{ $isToday ? 'white' : ($isPast ? 'var(--text-muted)' : 'var(--text-primary)') }};line-height:1.1">
                        {{ $day->day }}
                    </div>
                    @if($isToday)
                    <div style="font-size:9px;font-weight:700;color:rgba(255,255,255,.7);text-transform:uppercase;letter-spacing:.06em">Hari Ini</div>
                    @endif
                </div>

                {{-- Day Sessions --}}
                <div class="p-2 d-flex flex-column gap-2" style="min-height:80px">
                    @if($daySched->isEmpty())
                    <div class="text-center py-3" style="font-size:11px;color:var(--text-muted)">
                        <i class="bi bi-dash" style="font-size:16px;opacity:.3"></i>
                    </div>
                    @else
                    @foreach($daySched as $s)
                    @php
                        $clr = $statusColors[$s->status] ?? $statusColors['dijadwalkan'];
                        $startTime = Carbon::parse($s->jam_mulai)->format('H:i');
                        $endTime   = Carbon::parse($s->jam_selesai)->format('H:i');
                    @endphp
                    <div class="sched-item rounded-2 p-2"
                         data-jenis="{{ $s->jenis ?? 'offline' }}"
                         style="background:{{ $clr[1] }};border-left:3px solid {{ $clr[0] }};cursor:pointer;transition:.2s"
                         onclick="showScheduleDetail(this)"
                         data-topic="{{ $s->topik ?? 'Sesi Belajar' }}"
                         data-time="{{ $startTime }}–{{ $endTime }}"
                         data-status="{{ $s->status }}"
                         data-jenis-label="{{ ucfirst($s->jenis ?? 'offline') }}"
                         data-room="{{ $s->ruangan ?? ($s->jenis==='online' ? 'Online' : '—') }}"
                         data-guru="{{ $s->guru?->user?->name ?? '—' }}"
                         data-link="{{ $s->link_meeting ?? '' }}"
                         data-catatan="{{ $s->catatan ?? '' }}">
                        <div class="fw-semibold text-truncate" style="font-size:11.5px;color:{{ $clr[0] }}">
                            {{ $s->topik ?? 'Sesi Belajar' }}
                        </div>
                        <div style="font-size:10.5px;color:{{ $clr[0] }};opacity:.75">
                            <i class="bi bi-clock me-1"></i>{{ $startTime }}
                            <i class="bi {{ $jenisIcon[$s->jenis] ?? 'bi-building' }} ms-1 me-1"></i>{{ ucfirst($s->jenis ?? 'offline') }}
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

{{-- ===== MONTH VIEW ===== --}}
@elseif($viewMode === 'month')
<div class="dashboard-card fade-up">
    @php
        $monthStart     = $selMonth->copy()->startOfMonth();
        $monthEnd       = $selMonth->copy()->endOfMonth();
        $calStart       = $monthStart->copy()->startOfWeek(Carbon::MONDAY);
        $calEnd         = $monthEnd->copy()->endOfWeek(Carbon::SUNDAY);
        $dayHeaders     = ['Sen','Sel','Rab','Kam','Jum','Sab','Min'];
    @endphp

    {{-- Day of week header --}}
    <div class="row g-1 mb-1">
        @foreach($dayHeaders as $dh)
        <div class="col" style="text-align:center;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);padding:6px 0">
            {{ $dh }}
        </div>
        @endforeach
    </div>

    {{-- Calendar weeks --}}
    @php $cur = $calStart->copy(); @endphp
    @while($cur <= $calEnd)
    <div class="row g-1 mb-1">
        @for($w = 0; $w < 7; $w++)
        @php
            $dk   = $cur->format('Y-m-d');
            $dSch = $grouped[$dk] ?? collect();
            $inMonth = $cur->month === $selMonth->month;
            $isToday = $cur->isToday();
        @endphp
        <div class="col" style="min-height:80px">
            <div class="rounded-2 h-100"
                 style="background:{{ $isToday ? 'linear-gradient(135deg,rgba(200,77,223,.08),rgba(104,17,126,.04))' : ($inMonth ? 'var(--input-bg)' : 'transparent') }};border:1px solid {{ $isToday ? '#c84ddf' : 'var(--card-border)' }};padding:6px;opacity:{{ $inMonth ? '1' : '.4' }}">
                <div style="font-size:12px;font-weight:{{ $isToday ? '800' : '500' }};color:{{ $isToday ? '#c84ddf' : 'var(--text-muted)' }};margin-bottom:4px">
                    {{ $cur->day }}
                    @if($isToday)<span class="badge ms-1" style="background:#c84ddf;color:white;font-size:8px;padding:2px 5px">Today</span>@endif
                </div>
                @foreach($dSch->take(3) as $s)
                @php $clr = $statusColors[$s->status] ?? $statusColors['dijadwalkan']; @endphp
                <div class="rounded-1 text-truncate mb-1 px-1"
                     style="background:{{ $clr[1] }};border-left:2px solid {{ $clr[0] }};font-size:10px;font-weight:600;color:{{ $clr[0] }};line-height:1.6;cursor:pointer"
                     onclick="showScheduleDetail(this)"
                     data-topic="{{ $s->topik ?? 'Sesi' }}"
                     data-time="{{ Carbon::parse($s->jam_mulai)->format('H:i') }}–{{ Carbon::parse($s->jam_selesai)->format('H:i') }}"
                     data-status="{{ $s->status }}"
                     data-jenis-label="{{ ucfirst($s->jenis ?? 'offline') }}"
                     data-room="{{ $s->ruangan ?? ($s->jenis==='online' ? 'Online' : '—') }}"
                     data-guru="{{ $s->guru?->user?->name ?? '—' }}"
                     data-link="{{ $s->link_meeting ?? '' }}"
                     data-catatan="{{ $s->catatan ?? '' }}">
                    {{ Carbon::parse($s->jam_mulai)->format('H:i') }} {{ $s->topik ?? 'Sesi' }}
                </div>
                @endforeach
                @if($dSch->count() > 3)
                <div style="font-size:10px;color:var(--text-muted)">+{{ $dSch->count()-3 }} lagi</div>
                @endif
            </div>
        </div>
        @php $cur->addDay(); @endphp
        @endfor
    </div>
    @endwhile
</div>

{{-- ===== LIST VIEW ===== --}}
@else
<div class="dashboard-card fade-up">
    @php
        $listSched = $student
            ? Schedule::where('cabang_id', $student->branch_id)
                ->where('tanggal', '>=', today())
                ->with(['kelas', 'guru.user'])
                ->orderBy('tanggal')->orderBy('jam_mulai')
                ->limit(30)
                ->get()
            : collect();
        $listGrouped = $listSched->groupBy(fn($s) => $s->tanggal->format('Y-m-d'));
    @endphp

    <h6 class="fw-bold mb-4" style="font-size:14px">
        <i class="bi bi-list-ul text-primary me-2"></i>Jadwal Mendatang
    </h6>

    @if($listSched->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-calendar-x d-block mb-3" style="font-size:3.5rem;opacity:.2"></i>
        <p class="text-muted mb-0" style="font-size:13.5px">Tidak ada jadwal mendatang.</p>
    </div>
    @else
    <div class="d-flex flex-column gap-4">
        @foreach($listGrouped as $date => $daySched)
        @php
            $dateObj = Carbon::parse($date);
            $isToday = $dateObj->isToday();
            $isTomorrow = $dateObj->isTomorrow();
        @endphp
        <div>
            <div class="d-flex align-items-center gap-2 mb-3">
                <div style="width:10px;height:10px;border-radius:50%;background:{{ $isToday ? '#c84ddf' : '#94a3b8' }};flex-shrink:0"></div>
                <h6 class="fw-bold mb-0" style="font-size:13px;color:{{ $isToday ? '#c84ddf' : 'var(--text-primary)' }}">
                    {{ $isToday ? 'Hari Ini' : ($isTomorrow ? 'Besok' : '') }} — {{ $dateObj->locale('id')->isoFormat('dddd, D MMMM Y') }}
                    <span class="badge ms-2" style="font-size:11px;background:{{ $isToday ? '#c84ddf' : 'var(--card-border)' }};color:{{ $isToday ? 'white' : 'var(--text-muted)' }}">{{ $daySched->count() }} sesi</span>
                </h6>
            </div>
            <div class="d-flex flex-column gap-2 ps-4">
                @foreach($daySched as $s)
                @php
                    $clr = $statusColors[$s->status] ?? $statusColors['dijadwalkan'];
                @endphp
                <div class="d-flex align-items-center gap-3 p-3 rounded-3"
                     style="background:var(--input-bg);border:1.5px solid var(--card-border);transition:.2s;cursor:pointer"
                     onclick="showScheduleDetail(this)"
                     data-topic="{{ $s->topik ?? 'Sesi Belajar' }}"
                     data-time="{{ Carbon::parse($s->jam_mulai)->format('H:i') }}–{{ Carbon::parse($s->jam_selesai)->format('H:i') }}"
                     data-status="{{ $s->status }}"
                     data-jenis-label="{{ ucfirst($s->jenis ?? 'offline') }}"
                     data-room="{{ $s->ruangan ?? ($s->jenis==='online' ? 'Online' : '—') }}"
                     data-guru="{{ $s->guru?->user?->name ?? '—' }}"
                     data-link="{{ $s->link_meeting ?? '' }}"
                     data-catatan="{{ $s->catatan ?? '' }}">

                    {{-- Time Block --}}
                    <div class="text-center flex-shrink-0" style="min-width:52px">
                        <div class="fw-bold" style="font-size:16px;color:{{ $clr[0] }};line-height:1">
                            {{ Carbon::parse($s->jam_mulai)->format('H:i') }}
                        </div>
                        <div style="font-size:10.5px;color:var(--text-muted)">{{ Carbon::parse($s->jam_selesai)->format('H:i') }}</div>
                    </div>

                    <div style="width:3px;height:40px;border-radius:2px;background:{{ $clr[0] }};flex-shrink:0"></div>

                    {{-- Content --}}
                    <div style="flex:1;min-width:0">
                        <div class="fw-bold text-truncate" style="font-size:14px">{{ $s->topik ?? 'Sesi Belajar' }}</div>
                        <div class="d-flex flex-wrap gap-3 mt-1" style="font-size:12px;color:var(--text-muted)">
                            <span><i class="bi {{ $jenisIcon[$s->jenis] ?? 'bi-building' }} me-1"></i>{{ ucfirst($s->jenis ?? 'offline') }}</span>
                            @if($s->guru)
                            <span><i class="bi bi-person me-1"></i>{{ $s->guru->user?->name ?? '—' }}</span>
                            @endif
                            @if($s->kelas)
                            <span><i class="bi bi-diagram-3 me-1"></i>{{ $s->kelas->nama }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Status Badge --}}
                    <div class="flex-shrink-0">
                        <span class="badge" style="background:{{ $clr[1] }};color:{{ $clr[0] }};font-size:11px;padding:5px 10px;border-radius:7px;font-weight:600">
                            {{ ucfirst($s->status) }}
                        </span>
                    </div>

                    @if($s->jenis==='online' && $s->link_meeting)
                    <a href="{{ $s->link_meeting }}" target="_blank" class="btn btn-sm btn-outline-primary flex-shrink-0" style="border-radius:8px;font-size:12px" onclick="event.stopPropagation()">
                        <i class="bi bi-link"></i>
                    </a>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endif

{{-- DETAIL MODAL --}}
<div class="modal fade" id="scheduleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:20px;border:none">
            <div class="modal-header" style="border-bottom:1px solid var(--card-border);border-radius:20px 20px 0 0;background:var(--card-bg)">
                <h6 class="modal-title fw-bold" id="modalTopic" style="font-size:16px">Detail Sesi</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="background:var(--card-bg);border-radius:0 0 20px 20px">
                <div class="d-flex flex-column gap-3" id="modalBody"></div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function showScheduleDetail(el) {
    const d = el.dataset;
    document.getElementById('modalTopic').textContent = d.topic || 'Sesi Belajar';
    const statusColors = { dijadwalkan:'#c84ddf', berlangsung:'#10b981', selesai:'#94a3b8', dibatalkan:'#ef4444' };
    const clr = statusColors[d.status] || '#c84ddf';
    const rows = [
        ['bi-clock',      'Waktu',       d.time],
        ['bi-wifi',       'Jenis',       d.jenisLabel],
        ['bi-building',   'Ruangan',     d.room],
        ['bi-person',     'Pengajar',    d.guru],
        ['bi-circle-fill','Status',      '<span style="color:'+clr+';font-weight:600">'+ucfirst(d.status)+'</span>'],
    ];
    if (d.catatan) rows.push(['bi-chat-left-text','Catatan', d.catatan]);

    let html = rows.map(([icon, label, val]) => `
        <div class="d-flex align-items-start gap-3">
            <div style="width:36px;height:36px;border-radius:10px;background:var(--input-bg);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="bi ${icon}" style="color:#c84ddf;font-size:15px"></i>
            </div>
            <div>
                <div style="font-size:11px;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.06em">${label}</div>
                <div style="font-size:13.5px;font-weight:500;color:var(--text-primary)">${val}</div>
            </div>
        </div>`).join('');

    if (d.link) {
        html += `<a href="${d.link}" target="_blank" class="btn btn-primary w-100 fw-semibold" style="border-radius:12px;margin-top:4px">
            <i class="bi bi-camera-video-fill me-2"></i>Bergabung ke Meeting
        </a>`;
    }
    document.getElementById('modalBody').innerHTML = html;
    new bootstrap.Modal(document.getElementById('scheduleModal')).show();
}

function ucfirst(s) {
    return s ? s.charAt(0).toUpperCase() + s.slice(1) : '—';
}

function filterJenis(val) {
    document.querySelectorAll('.sched-item').forEach(el => {
        el.closest('.col, div').style.display = (val==='all' || el.dataset.jenis===val) ? '' : 'none';
    });
}
</script>
<style>
@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: .6; transform: scale(.85); }
}
.sched-item:hover { transform: translateX(2px); }
</style>
@endpush
