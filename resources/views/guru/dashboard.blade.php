@extends('layouts.app')
@section('title', 'Dashboard Guru')
@section('page-title', 'Dashboard Guru')

@section('content')

@php
use App\Models\Teacher;
use App\Models\Schedule;
use App\Models\SchoolClass;
use Carbon\Carbon;

$teacher = Teacher::where('user_id', auth()->id())->first();

$todaySchedules = $teacher
    ? Schedule::with('kelas.mataPelajaran', 'kelas.siswa')
        ->where('guru_id', $teacher->id)
        ->whereDate('tanggal', today())
        ->orderBy('jam_mulai')
        ->get()
    : collect();

$now = Carbon::now();

$todayClassCount = $todaySchedules->count();

$pendingJurnals = $todaySchedules->filter(function ($s) use ($now) {
    $end = Carbon::parse($s->jam_selesai);
    return $now->isAfter($end) && $s->status !== 'selesai';
})->count();

$firstName = explode(' ', auth()->user()->name)[0] ?? 'Guru';
@endphp

{{-- GREETING BANNER --}}
<div class="dashboard-card mb-3 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative;padding:24px 28px">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>

    <div class="d-flex align-items-center gap-4" style="position:relative">
        {{-- Avatar --}}
        @if($teacher?->photo)
            <img src="{{ asset('storage/'.$teacher->photo) }}" alt="Foto"
                 style="width:62px;height:62px;border-radius:18px;object-fit:cover;border:2px solid rgba(255,255,255,.25);flex-shrink:0;box-shadow:0 8px 24px rgba(0,0,0,.2)">
        @else
            <div style="width:62px;height:62px;border-radius:18px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:28px;flex-shrink:0;border:1.5px solid rgba(255,255,255,.12)">
                <i class="bi bi-person-badge-fill"></i>
            </div>
        @endif

        {{-- Greeting text --}}
        <div>
            <div style="font-size:11px;opacity:.55;margin-bottom:4px;text-transform:uppercase;letter-spacing:.1em">
                <i class="bi bi-calendar3 me-1"></i>{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
            </div>
            <h4 style="font-weight:800;margin-bottom:4px;color:white;letter-spacing:-.03em;font-size:clamp(18px,2.5vw,24px)">
                Halo, {{ $firstName }}! 👋
            </h4>
            @if($teacher?->branch)
            <div style="font-size:12.5px;opacity:.70">
                <i class="bi bi-building me-1"></i>{{ $teacher->branch->name }}
            </div>
            @endif
        </div>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="row g-3 mb-3 fade-up" style="animation-delay:.04s">
    <div class="col-6">
        <div class="stat-card h-100" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Kelas Hari Ini</div>
                    <div class="stat-value text-primary" style="font-size:2.2rem">{{ $todayClassCount }}</div>
                    <div class="stat-label text-muted" style="font-size:11px">sesi dijadwalkan</div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white">
                    <i class="bi bi-calendar-check-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="stat-card h-100" style="border-top:3px solid {{ $pendingJurnals > 0 ? '#f6af23' : '#10b981' }}">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Jurnal Tertunda</div>
                    <div class="stat-value" style="font-size:2.2rem;color:{{ $pendingJurnals > 0 ? '#e09000' : '#10b981' }}">{{ $pendingJurnals }}</div>
                    <div class="stat-label text-muted" style="font-size:11px">belum diisi</div>
                </div>
                <div class="stat-icon {{ $pendingJurnals > 0 ? 'bg-warning-soft' : 'bg-success-soft' }}" style="color:white">
                    <i class="bi bi-journal-text"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- TIMELINE JADWAL HARI INI --}}
<div class="dashboard-card fade-up" style="animation-delay:.08s">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h6 class="fw-bold mb-0" style="color:var(--text-primary)">Timeline Jadwal Hari Ini</h6>
            <span class="text-muted" style="font-size:12px">{{ $todayClassCount }} Sesi &middot; {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
        </div>
        <a href="{{ route('guru.classes.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius:10px;font-size:12px">
            <i class="bi bi-diagram-3 me-1"></i>Semua Kelas
        </a>
    </div>

    @if($todaySchedules->isEmpty())
    <div class="d-flex flex-column align-items-center justify-content-center py-5 text-center">
        <div style="width:64px;height:64px;border-radius:18px;background:var(--soft-muted-bg);display:flex;align-items:center;justify-content:center;margin-bottom:14px">
            <i class="bi bi-calendar3" style="font-size:2rem;opacity:.3;color:var(--text-muted)"></i>
        </div>
        <div class="fw-semibold mb-1" style="font-size:15px">Tidak ada jadwal hari ini</div>
        <div class="text-muted" style="font-size:13px">Nikmati hari Anda! 🌟</div>
    </div>
    @else
    <div class="d-flex flex-column gap-3">
        @foreach($todaySchedules as $idx => $s)
        @php
            $start    = Carbon::parse($s->jam_mulai);
            $end      = Carbon::parse($s->jam_selesai);
            $isNow    = $now->between($start, $end);
            $isPast   = $now->isAfter($end);
            $isFuture = $now->isBefore($start);

            $kelas       = $s->kelas;
            $namaKelas   = $kelas?->nama_kelas ?? 'Sesi Belajar';
            $mapel       = $kelas?->mataPelajaran?->nama ?? null;
            $jumlahSiswa = $kelas?->siswa?->count() ?? 0;
            $ruangan     = $s->ruangan ?? null;
            $jenis       = strtolower($s->jenis ?? '');
            $isPrivat    = in_array($jenis, ['private', 'privat']);

            if ($isPast) {
                $statusLabel = 'Selesai';
                $statusBg    = 'rgba(148,163,184,.15)';
                $statusColor = '#64748b';
                $statusIcon  = 'bi-check-circle-fill';
                $cardBorder  = 'var(--card-border)';
                $cardBg      = 'var(--input-bg)';
                $accentColor = '#94a3b8';
                $dotBg       = 'var(--soft-muted-bg)';
            } elseif ($isNow) {
                $statusLabel = 'Sedang Berjalan';
                $statusBg    = '#dcfce7';
                $statusColor = '#16a34a';
                $statusIcon  = 'bi-circle-fill';
                $cardBorder  = 'rgba(16,185,129,.3)';
                $cardBg      = 'rgba(16,185,129,.04)';
                $accentColor = '#10b981';
                $dotBg       = 'linear-gradient(135deg,#10b981,#059669)';
            } else {
                $statusLabel = 'Nanti';
                $statusBg    = 'rgba(200,77,223,.1)';
                $statusColor = '#c84ddf';
                $statusIcon  = 'bi-clock-fill';
                $cardBorder  = 'rgba(200,77,223,.2)';
                $cardBg      = 'rgba(200,77,223,.03)';
                $accentColor = '#c84ddf';
                $dotBg       = 'linear-gradient(135deg,#461256,#c84ddf)';
            }
        @endphp

        <div class="d-flex gap-0" style="position:relative">
            {{-- Timeline connector --}}
            <div class="d-flex flex-column align-items-center me-3" style="flex-shrink:0;width:38px">
                <div style="width:36px;height:36px;border-radius:50%;background:{{ $dotBg }};display:flex;align-items:center;justify-content:center;color:white;font-size:13px;font-weight:700;flex-shrink:0;z-index:1;box-shadow:0 2px 8px rgba(0,0,0,.12)">
                    {{ $idx + 1 }}
                </div>
                @if(!$loop->last)
                <div style="width:2px;flex:1;min-height:20px;background:var(--card-border);margin-top:4px;border-radius:2px"></div>
                @endif
            </div>

            {{-- Session card --}}
            <div class="flex-1 mb-2" style="flex:1;border-radius:16px;border:1.5px solid {{ $cardBorder }};background:{{ $cardBg }};overflow:hidden">
                @if($isNow)
                <div style="height:3px;background:linear-gradient(90deg,#10b981,#34d399)"></div>
                @endif
                <div class="p-3">
                    <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                        <div style="flex:1;min-width:0">
                            <div class="fw-bold text-truncate" style="font-size:15px;color:var(--text-primary)">{{ $namaKelas }}</div>
                            @if($mapel)
                            <div class="text-muted" style="font-size:12px">{{ $mapel }}</div>
                            @endif
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                            @if($isPrivat)
                            <span class="badge" style="background:rgba(104,17,126,.12);color:#461256;font-size:10px;padding:3px 8px;font-weight:700">PRIVAT</span>
                            @endif
                            <span class="badge d-flex align-items-center gap-1"
                                  style="background:{{ $statusBg }};color:{{ $statusColor }};font-size:11px;padding:4px 10px;border-radius:20px;{{ $isNow ? 'animation:pulseDot 2s ease-in-out infinite' : '' }}">
                                <i class="bi {{ $statusIcon }}" style="font-size:{{ $isNow ? '7px' : '10px' }}"></i>
                                @if($isNow)<strong>LIVE</strong>&nbsp;@endif{{ $statusLabel }}
                            </span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="d-flex align-items-center gap-1" style="font-size:12.5px;color:{{ $accentColor }};font-weight:600">
                            <i class="bi bi-clock"></i>
                            {{ $start->format('H:i') }} – {{ $end->format('H:i') }}
                        </div>
                        @if($ruangan)
                        <div class="d-flex align-items-center gap-1 text-muted" style="font-size:12px">
                            <i class="bi bi-geo-alt"></i>{{ $ruangan }}
                        </div>
                        @elseif($jenis === 'online')
                        <div class="d-flex align-items-center gap-1" style="font-size:12px;color:#c84ddf">
                            <i class="bi bi-camera-video"></i>Online
                        </div>
                        @endif
                        @if($jumlahSiswa > 0)
                        <div class="d-flex align-items-center gap-1 text-muted" style="font-size:12px">
                            <i class="bi bi-people"></i>{{ $jumlahSiswa }} siswa
                        </div>
                        @endif
                    </div>

                    {{-- Action button --}}
                    @if($isNow && $kelas)
                    <div class="mt-3 pt-2" style="border-top:1px solid {{ $cardBorder }}">
                        <a href="{{ route('guru.attendance.index', $kelas->id) }}"
                           class="btn btn-sm fw-semibold px-3"
                           style="background:linear-gradient(135deg,#10b981,#059669);color:white;border:none;border-radius:10px;font-size:12px">
                            <i class="bi bi-clipboard-check me-1"></i>Absen Siswa &amp; Jurnal
                        </a>
                    </div>
                    @elseif($isPast && $s->status !== 'selesai' && $kelas)
                    <div class="mt-3 pt-2" style="border-top:1px solid {{ $cardBorder }}">
                        <a href="{{ route('guru.attendance.index', $kelas->id) }}"
                           class="btn btn-sm fw-semibold px-3"
                           style="background:linear-gradient(135deg,#c84ddf,#461256);color:white;border:none;border-radius:10px;font-size:12px">
                            <i class="bi bi-pencil-square me-1"></i>Isi Jurnal Selesai
                            <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

@if(!$teacher)
<div class="alert alert-warning d-flex gap-3 align-items-start mt-3 fade-up" style="border-radius:14px;border:none">
    <i class="bi bi-exclamation-triangle-fill text-warning mt-1" style="font-size:18px;flex-shrink:0"></i>
    <div>
        <div class="fw-bold mb-1">Profil Guru Belum Terhubung</div>
        <div style="font-size:13px">Akun Anda belum terhubung ke profil guru. Minta administrator untuk menghubungkan akun ini ke data guru yang sesuai.</div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<style>
@keyframes pulseDot {
    0%, 100% { opacity: 1; }
    50%       { opacity: .55; }
}
</style>
@endpush
