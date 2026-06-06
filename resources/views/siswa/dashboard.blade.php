@extends('layouts.app')
@section('title', 'Portal Siswa')
@section('page-title', 'Portal Siswa')

@section('content')

@php
use App\Models\Student;
use App\Models\Invoice;
use App\Models\Schedule;
use Carbon\Carbon;

$student = Student::where('user_id', auth()->id())->first();

$invoices = $student
    ? Invoice::where('siswa_id', $student->id)
        ->latest()
        ->limit(5)
        ->get()
    : collect();

$totalTagihan = $student
    ? Invoice::where('siswa_id', $student->id)->sum('total')
    : 0;
$totalLunas   = $student
    ? Invoice::where('siswa_id', $student->id)->where('status','lunas')->sum('total')
    : 0;
$sisaTunggakan = $totalTagihan - $totalLunas;

// Schedules for this branch (public schedules)
$weekSchedules = ($student && $student->branch_id)
    ? Schedule::where('cabang_id', $student->branch_id)
        ->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()])
        ->where('status','!=','dibatalkan')
        ->orderBy('tanggal')->orderBy('jam_mulai')
        ->limit(10)->get()
    : collect();
@endphp

{{-- WELCOME BANNER --}}
<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.04);border-radius:50%"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div class="d-flex align-items-center gap-4">
            @if($student?->photo)
                <img src="{{ asset('storage/'.$student->photo) }}" alt="Foto"
                     style="width:64px;height:64px;border-radius:16px;object-fit:cover;border:2px solid rgba(255,255,255,.2);flex-shrink:0">
            @else
                <div style="width:64px;height:64px;border-radius:16px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:28px;flex-shrink:0">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
            @endif
            <div>
                <div style="font-size:11px;opacity:.6;margin-bottom:4px;text-transform:uppercase;letter-spacing:.08em">
                    Portal Siswa · {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                </div>
                <h4 style="font-weight:800;margin-bottom:4px;color:white;letter-spacing:-.02em">
                    Halo, {{ explode(' ', auth()->user()->name)[0] }}! 👋
                </h4>
                <p style="opacity:.65;margin:0;font-size:13px">
                    @if($student)
                        NIS: {{ $student->nis ?? '-' }} · {{ $student->branch?->name ?? 'N/A' }}
                        @if($student->grade) · Kelas {{ $student->grade }} @endif
                    @else
                        Selamat belajar di Smart Center Indonesia
                    @endif
                </p>
            </div>
        </div>
        <div style="font-size:64px;opacity:.08;line-height:1;flex-shrink:0">
            <i class="bi bi-mortarboard"></i>
        </div>
    </div>
</div>

{{-- STATS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 fade-up">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Tagihan</div>
                    <div class="stat-value" style="color:#68117e;font-size:20px">
                        Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                    </div>
                    <div class="stat-label" style="font-size:11px">{{ $invoices->count() }} invoice</div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#68117e,#c84ddf)">
                    <i class="bi bi-receipt" style="color:white"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Sudah Dibayar</div>
                    <div class="stat-value" style="color:#059669;font-size:20px">
                        Rp {{ number_format($totalLunas, 0, ',', '.') }}
                    </div>
                    <div class="stat-label" style="font-size:11px;color:#10b981">
                        <i class="bi bi-check-circle-fill me-1"></i>Terverifikasi
                    </div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#059669,#10b981)">
                    <i class="bi bi-check-circle" style="color:white"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid {{ $sisaTunggakan > 0 ? '#ef4444' : '#10b981' }}">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Sisa Tunggakan</div>
                    <div class="stat-value" style="color:{{ $sisaTunggakan > 0 ? '#dc2626' : '#059669' }};font-size:20px">
                        Rp {{ number_format($sisaTunggakan, 0, ',', '.') }}
                    </div>
                    <div class="stat-label" style="font-size:11px">
                        @if($sisaTunggakan > 0)
                            <i class="bi bi-exclamation-circle text-danger me-1"></i>Belum lunas
                        @else
                            <i class="bi bi-check2-all text-success me-1"></i>Lunas semua
                        @endif
                    </div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,{{ $sisaTunggakan > 0 ? '#dc2626,#ef4444' : '#059669,#10b981' }})">
                    <i class="bi bi-wallet2" style="color:white"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card" style="border-top:3px solid #68117e">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Jadwal Minggu Ini</div>
                    <div class="stat-value" style="color:#c84ddf">{{ $weekSchedules->count() }}</div>
                    <div class="stat-label" style="font-size:11px">sesi belajar</div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#c84ddf,#68117e)">
                    <i class="bi bi-calendar-week" style="color:white"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4" id="pembayaran">

    {{-- PAYMENT HISTORY --}}
    <div class="col-lg-5 fade-up">
        <div class="dashboard-card h-100">
            <h6 class="fw-bold mb-3" style="font-size:14px">
                <i class="bi bi-credit-card text-primary me-2"></i>Tagihan Saya
            </h6>
            @forelse($invoices as $inv)
            @php
                $stClr = ['lunas'=>'#10b981','belum_bayar'=>'#f6af23','sebagian'=>'#c84ddf'][$inv->status] ?? '#94a3b8';
                $stBg  = ['lunas'=>'#ecfdf5','belum_bayar'=>'#fffbeb','sebagian'=>'#fdf4ff'][$inv->status] ?? '#f1f5f9';
                $stLbl = ['lunas'=>'Lunas','belum_bayar'=>'Belum Bayar','sebagian'=>'Sebagian'][$inv->status] ?? $inv->status;
                $overdue = $inv->status !== 'lunas' && $inv->jatuh_tempo && \Carbon\Carbon::parse($inv->jatuh_tempo)->isPast();
            @endphp
            <div class="d-flex align-items-center justify-content-between p-3 rounded-3 mb-2 {{ $overdue ? 'row-overdue' : '' }}"
                 style="background:{{ $overdue ? 'var(--overdue-bg,#fef2f2)' : 'var(--input-bg)' }};border:1px solid {{ $overdue ? 'var(--overdue-border,#fecaca)' : 'var(--card-border)' }}">
                <div style="min-width:0">
                    <div class="fw-semibold" style="font-size:13px">{{ $inv->nomor_invoice }}</div>
                    <div style="font-size:11px;color:var(--text-muted)">
                        @if($overdue)
                            <i class="bi bi-exclamation-triangle-fill text-danger me-1"></i>Jatuh tempo: terlambat
                        @elseif($inv->jatuh_tempo)
                            <i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($inv->jatuh_tempo)->locale('id')->isoFormat('D MMMM Y') }}
                        @endif
                    </div>
                </div>
                <div class="text-end flex-shrink-0 ms-2">
                    <div class="fw-bold" style="font-size:13.5px">Rp {{ number_format($inv->total, 0, ',', '.') }}</div>
                    <span class="badge mt-1" style="background:{{ $stBg }};color:{{ $stClr }};font-size:10px;border-radius:6px;padding:2px 8px">
                        {{ $stLbl }}
                    </span>
                </div>
            </div>
            @empty
            <div class="empty-state" style="padding:2rem">
                <i class="bi bi-receipt-cutoff" style="font-size:2.5rem;color:#cbd5e1;display:block;margin-bottom:8px"></i>
                <p class="text-muted mb-0" style="font-size:13px">
                    @if(!$student) Akun belum terhubung ke data siswa. @else Belum ada tagihan. @endif
                </p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- WEEKLY SCHEDULE --}}
    <div class="col-lg-7 fade-up" id="jadwal" style="animation-delay:.05s">
        <div class="dashboard-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0" style="font-size:14px">
                    <i class="bi bi-calendar-week me-2" style="color:#68117e"></i>Jadwal Belajar Minggu Ini
                </h6>
                <span class="text-muted" style="font-size:11.5px">
                    {{ now()->startOfWeek()->locale('id')->isoFormat('D MMM') }} – {{ now()->endOfWeek()->locale('id')->isoFormat('D MMM') }}
                </span>
            </div>

            @if($weekSchedules->isEmpty())
            <div class="empty-state" style="padding:2rem">
                <i class="bi bi-calendar3" style="font-size:2.5rem;color:#cbd5e1;display:block;margin-bottom:8px"></i>
                <p class="text-muted mb-0" style="font-size:13px">
                    @if(!$student) Profil siswa belum dikonfigurasi. @else Tidak ada jadwal minggu ini. @endif
                </p>
            </div>
            @else
            <div class="d-flex flex-column gap-2">
                @foreach($weekSchedules as $sch)
                @php
                    $isToday = $sch->tanggal->isToday();
                    $statusClr = ['dijadwalkan'=>'#c84ddf','berlangsung'=>'#10b981','selesai'=>'#94a3b8'][$sch->status] ?? '#94a3b8';
                @endphp
                <div class="d-flex gap-3 align-items-center p-3 rounded-3"
                     style="background:{{ $isToday ? 'rgba(200,77,223,.07)' : 'var(--input-bg)' }};border:1px solid {{ $isToday ? '#e8b4f5' : 'var(--card-border)' }}">
                    <div class="text-center flex-shrink-0" style="min-width:48px">
                        <div class="fw-bold" style="font-size:13px;color:{{ $isToday ? '#68117e' : 'var(--text-primary)' }}">
                            {{ $sch->tanggal->locale('id')->isoFormat('ddd') }}
                        </div>
                        <div style="font-size:11px;color:var(--text-muted)">
                            {{ \Carbon\Carbon::parse($sch->jam_mulai)->format('H:i') }}
                        </div>
                    </div>
                    <div style="flex:1;min-width:0">
                        <div class="fw-semibold text-truncate" style="font-size:13px">
                            {{ $sch->topik ?? 'Sesi Belajar' }}
                            @if($isToday)
                                <span class="badge ms-1" style="background:#f3d6fa;color:#461256;font-size:9px">Hari Ini</span>
                            @endif
                        </div>
                        <div style="font-size:11px;color:var(--text-muted)">
                            {{ \Carbon\Carbon::parse($sch->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($sch->jam_selesai)->format('H:i') }}
                            @if($sch->jenis === 'online')
                                · <i class="bi bi-camera-video me-1" style="color:#c84ddf"></i>Online
                            @else
                                · <i class="bi bi-building me-1"></i>{{ $sch->ruangan ?? 'Kelas' }}
                            @endif
                        </div>
                    </div>
                    <span class="badge flex-shrink-0" style="background:{{ $statusClr }}20;color:{{ $statusClr }};font-size:10px;border-radius:6px">
                        {{ ucfirst($sch->status) }}
                    </span>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

</div>

@if(!$student)
<div class="alert d-flex gap-3 align-items-start mt-4 fade-up" style="border-radius:14px;border:none;background:#fef3c7;color:#78350f">
    <i class="bi bi-exclamation-triangle-fill text-warning mt-1" style="font-size:18px;flex-shrink:0"></i>
    <div>
        <div class="fw-bold mb-1">Profil Siswa Belum Terhubung</div>
        <div style="font-size:13px">
            Akun Anda belum terhubung ke data siswa. Hubungi administrator cabang Anda untuk menghubungkan akun ini.
        </div>
    </div>
</div>
@endif

@endsection
