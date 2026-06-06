@extends('layouts.app')
@section('title', 'Portal Guru')
@section('page-title', 'Portal Guru')

@section('content')

@php
use App\Models\Teacher;
use App\Models\Schedule;
use Carbon\Carbon;

$teacher = Teacher::where('user_id', auth()->id())->first();

$todaySchedules = $teacher
    ? Schedule::where('guru_id', $teacher->id)
        ->whereDate('tanggal', today())
        ->orderBy('jam_mulai')
        ->get()
    : collect();

$weekSchedules = $teacher
    ? Schedule::where('guru_id', $teacher->id)
        ->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()])
        ->orderBy('tanggal')->orderBy('jam_mulai')
        ->get()
    : collect();

$monthTotal = $teacher
    ? Schedule::where('guru_id', $teacher->id)
        ->whereMonth('tanggal', now()->month)
        ->whereYear('tanggal', now()->year)
        ->count()
    : 0;
@endphp

{{-- WELCOME BANNER --}}
<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#68117e 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.04);border-radius:50%"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div class="d-flex align-items-center gap-4">
            @if($teacher?->photo)
                <img src="{{ asset('storage/'.$teacher->photo) }}" alt="Foto"
                     style="width:64px;height:64px;border-radius:16px;object-fit:cover;border:2px solid rgba(255,255,255,.2);flex-shrink:0">
            @else
                <div style="width:64px;height:64px;border-radius:16px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:28px;flex-shrink:0">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
            @endif
            <div>
                <div style="font-size:11px;opacity:.6;margin-bottom:4px;text-transform:uppercase;letter-spacing:.08em">
                    Portal Guru · {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                </div>
                <h4 style="font-weight:800;margin-bottom:4px;color:white;letter-spacing:-.02em">
                    Selamat datang, {{ explode(' ', auth()->user()->name)[0] }}! 👋
                </h4>
                <p style="opacity:.65;margin:0;font-size:13px">
                    {{ $teacher?->name ?? auth()->user()->name }}
                    @if($teacher?->branch) · {{ $teacher->branch->name }} @endif
                    @if($teacher?->subjects) · {{ is_array($teacher->subjects) ? implode(', ', $teacher->subjects) : $teacher->subjects }} @endif
                </p>
            </div>
        </div>
        <div style="font-size:64px;opacity:.08;line-height:1;flex-shrink:0">
            <i class="bi bi-person-workspace"></i>
        </div>
    </div>
</div>

{{-- STATS --}}
<div class="row g-3 mb-4">
    <div class="col-4 fade-up">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Hari Ini</div>
                    <div class="stat-value" style="color:#68117e">{{ $todaySchedules->count() }}</div>
                    <div class="stat-label" style="font-size:11px">sesi</div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#68117e,#c84ddf)">
                    <i class="bi bi-calendar-day" style="color:white"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-4 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #68117e">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Minggu Ini</div>
                    <div class="stat-value" style="color:#c84ddf">{{ $weekSchedules->count() }}</div>
                    <div class="stat-label" style="font-size:11px">jadwal</div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#c84ddf,#68117e)">
                    <i class="bi bi-calendar-week" style="color:white"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-4 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Bulan Ini</div>
                    <div class="stat-value" style="color:#059669">{{ $monthTotal }}</div>
                    <div class="stat-label" style="font-size:11px">total</div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#059669,#10b981)">
                    <i class="bi bi-calendar-month" style="color:white"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4" id="jadwal">

    {{-- TODAY'S SCHEDULE --}}
    <div class="col-lg-5 fade-up">
        <div class="dashboard-card h-100">
            <h6 class="fw-bold mb-3" style="font-size:14px">
                <i class="bi bi-calendar-day text-primary me-2"></i>Jadwal Hari Ini
            </h6>
            @forelse($todaySchedules as $sch)
            @php
                $statusColor = ['dijadwalkan'=>'#c84ddf','berlangsung'=>'#10b981','selesai'=>'#94a3b8','dibatalkan'=>'#ef4444'][$sch->status] ?? '#94a3b8';
            @endphp
            <div class="d-flex gap-3 mb-3 p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                <div class="d-flex flex-column align-items-center" style="min-width:48px">
                    <div class="fw-bold" style="font-size:15px;color:{{ $statusColor }}">
                        {{ \Carbon\Carbon::parse($sch->jam_mulai)->format('H:i') }}
                    </div>
                    <div style="width:2px;flex:1;background:var(--card-border);margin:4px 0"></div>
                    <div style="font-size:11px;color:var(--text-muted)">
                        {{ \Carbon\Carbon::parse($sch->jam_selesai)->format('H:i') }}
                    </div>
                </div>
                <div style="flex:1;min-width:0">
                    <div class="fw-semibold" style="font-size:13.5px">{{ $sch->topik ?? 'Sesi Mengajar' }}</div>
                    <div class="text-muted" style="font-size:11.5px">
                        @if($sch->jenis === 'online')
                            <i class="bi bi-camera-video text-info me-1"></i>Online
                            @if($sch->link_meeting)
                                · <a href="{{ $sch->link_meeting }}" target="_blank" class="text-primary" style="font-size:11px">Buka Link</a>
                            @endif
                        @else
                            <i class="bi bi-building me-1"></i>{{ $sch->ruangan ?? 'Kelas' }}
                        @endif
                    </div>
                    <span class="badge mt-1" style="font-size:10px;background:{{ $statusColor }}20;color:{{ $statusColor }};border-radius:6px;padding:2px 8px">
                        {{ ucfirst($sch->status) }}
                    </span>
                </div>
            </div>
            @empty
            <div class="empty-state" style="padding:2rem">
                <i class="bi bi-calendar-x" style="font-size:2.5rem;color:#cbd5e1;display:block;margin-bottom:8px"></i>
                <p class="text-muted mb-0" style="font-size:13px">
                    @if(!$teacher) Akun guru belum terhubung ke profil guru. @else Tidak ada jadwal hari ini. @endif
                </p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- WEEKLY SCHEDULE --}}
    <div class="col-lg-7 fade-up" style="animation-delay:.05s">
        <div class="dashboard-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0" style="font-size:14px">
                    <i class="bi bi-calendar-week text-purple me-2" style="color:#68117e"></i>Minggu Ini
                </h6>
                <span class="text-muted" style="font-size:11.5px">
                    {{ now()->startOfWeek()->locale('id')->isoFormat('D MMM') }} – {{ now()->endOfWeek()->locale('id')->isoFormat('D MMM Y') }}
                </span>
            </div>

            @if($weekSchedules->isEmpty())
            <div class="empty-state" style="padding:2rem">
                <i class="bi bi-calendar-check" style="font-size:2.5rem;color:#cbd5e1;display:block;margin-bottom:8px"></i>
                <p class="text-muted mb-0" style="font-size:13px">
                    @if(!$teacher) Profil guru belum dikonfigurasi. @else Tidak ada jadwal minggu ini. @endif
                </p>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size:12.5px">
                    <thead>
                        <tr style="background:var(--input-bg)">
                            <th class="py-2" style="color:var(--text-muted);font-size:11px;font-weight:600;text-transform:uppercase">Hari/Jam</th>
                            <th class="py-2" style="color:var(--text-muted);font-size:11px;font-weight:600;text-transform:uppercase">Topik</th>
                            <th class="py-2" style="color:var(--text-muted);font-size:11px;font-weight:600;text-transform:uppercase">Jenis</th>
                            <th class="py-2" style="color:var(--text-muted);font-size:11px;font-weight:600;text-transform:uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($weekSchedules as $sch)
                        @php
                            $isToday = $sch->tanggal->isToday();
                            $statusClr = ['dijadwalkan'=>'#c84ddf','berlangsung'=>'#10b981','selesai'=>'#94a3b8','dibatalkan'=>'#ef4444'][$sch->status] ?? '#94a3b8';
                        @endphp
                        <tr style="{{ $isToday ? 'background:rgba(200,77,223,.05)' : '' }}">
                            <td class="py-3">
                                <div class="fw-semibold" style="font-size:12.5px;{{ $isToday ? 'color:#68117e' : '' }}">
                                    {{ $sch->tanggal->locale('id')->isoFormat('ddd, D MMM') }}
                                    @if($isToday) <span class="badge ms-1" style="background:#f3d6fa;color:#461256;font-size:9px">Hari Ini</span> @endif
                                </div>
                                <div class="text-muted" style="font-size:11px">
                                    {{ \Carbon\Carbon::parse($sch->jam_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($sch->jam_selesai)->format('H:i') }}
                                </div>
                            </td>
                            <td class="py-3">{{ $sch->topik ?? 'Sesi Mengajar' }}</td>
                            <td class="py-3">
                                @if($sch->jenis === 'online')
                                    <span class="badge" style="background:#fdf4ff;color:#68117e;font-size:10px;border-radius:6px">
                                        <i class="bi bi-camera-video me-1"></i>Online
                                    </span>
                                @else
                                    <span class="badge" style="background:#f0fdf4;color:#166534;font-size:10px;border-radius:6px">
                                        <i class="bi bi-building me-1"></i>Offline
                                    </span>
                                @endif
                            </td>
                            <td class="py-3">
                                <span class="badge" style="background:{{ $statusClr }}20;color:{{ $statusClr }};font-size:10px;border-radius:6px;padding:3px 8px">
                                    {{ ucfirst($sch->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

</div>

@if(!$teacher)
<div class="alert alert-warning d-flex gap-3 align-items-start mt-4 fade-up" style="border-radius:14px;border:none;background:#fef3c7">
    <i class="bi bi-exclamation-triangle-fill text-warning mt-1" style="font-size:18px;flex-shrink:0"></i>
    <div>
        <div class="fw-bold mb-1">Profil Guru Belum Terhubung</div>
        <div style="font-size:13px;color:#78350f">
            Akun Anda belum terhubung ke profil guru. Minta administrator untuk menghubungkan akun ini ke data guru yang sesuai.
        </div>
    </div>
</div>
@endif

@endsection
