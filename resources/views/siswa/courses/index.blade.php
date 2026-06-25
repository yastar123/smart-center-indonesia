@extends('layouts.app')
@section('title','List Mata Pelajaran')
@section('page-title','List Mata Pelajaran')

@section('content')

<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div class="d-flex align-items-center gap-3">
            <div style="width:52px;height:52px;border-radius:16px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0">
                <i class="bi bi-journal-bookmark-fill"></i>
            </div>
            <div>
                <div style="font-size:11px;opacity:.6;margin-bottom:3px;text-transform:uppercase;letter-spacing:.08em">Program Belajar</div>
                <h4 class="fw-bold mb-1" style="color:white;letter-spacing:-.02em">Mata Pelajaran Saya</h4>
                <p class="mb-0" style="opacity:.75;font-size:13px">Mata pelajaran terdaftar beserta status pembayaran</p>
            </div>
        </div>
        <div style="font-size:64px;opacity:.08;line-height:1;flex-shrink:0">
            <i class="bi bi-journals"></i>
        </div>
    </div>
</div>

{{-- Mata Pelajaran dari Registrasi (payment verified) --}}
@if($registrationCourses->isNotEmpty())
<div class="dashboard-card fade-up mb-4">
    <div class="d-flex align-items-center gap-2 mb-3">
        <div style="width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,#260632,#c84ddf);display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <i class="bi bi-journal-check text-white" style="font-size:.9rem"></i>
        </div>
        <div>
            <div class="fw-bold" style="font-size:.92rem">Mata Pelajaran Terdaftar</div>
            <div class="text-muted" style="font-size:.75rem">
                @php
                    $statusLabel = match($registration->academic_status ?? '') {
                        'terjadwal'      => 'Terjadwal',
                        'menunggu_kelas' => 'Menunggu Kelas',
                        default          => 'Aktif',
                    };
                    $statusColor = $registration->academic_status === 'terjadwal' ? 'var(--soft-success-text)' : 'var(--soft-warning-text)';
                @endphp
                Status: <span style="color:{{ $statusColor }};font-weight:600">{{ $statusLabel }}</span>
            </div>
        </div>
    </div>
    <div class="row g-3">
        @foreach($registrationCourses as $rc)
        <div class="col-md-6 col-lg-4">
            <div class="p-3 rounded-3 h-100 d-flex align-items-center gap-3"
                 style="background:var(--input-bg);border:1.5px solid var(--card-border)">
                <div style="width:42px;height:42px;border-radius:11px;background:rgba(200,77,223,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="bi bi-journal-bookmark-fill text-primary" style="font-size:17px"></i>
                </div>
                <div>
                    <div class="fw-semibold" style="font-size:.88rem;color:var(--text-primary)">{{ $rc->nama }}</div>
                    @if($rc->deskripsi)
                    <div class="text-muted" style="font-size:.73rem;margin-top:2px">{{ Str::limit($rc->deskripsi, 50) }}</div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<div class="dashboard-card fade-up">
    @if($packages->isEmpty() && $registrationCourses->isEmpty())
    <div class="empty-state">
        <div class="empty-state-icon"><i class="bi bi-journal-x"></i></div>
        <div class="empty-state-title">Belum Ada Mata Pelajaran</div>
        <div class="empty-state-desc">Kamu belum terdaftar di paket apapun. Hubungi admin cabang untuk pendaftaran.</div>
    </div>
    @elseif($packages->isEmpty())
    <div class="text-center py-4">
        <p class="text-muted mb-0" style="font-size:13px">Belum ada paket belajar. Mata pelajaran terdaftar sudah ditampilkan di atas.</p>
    </div>
    @else
    <div class="d-flex align-items-center gap-2 mb-3">
        <div style="width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,#166534,#22c55e);display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <i class="bi bi-boxes text-white" style="font-size:.9rem"></i>
        </div>
        <div class="fw-bold" style="font-size:.92rem">Paket Belajar</div>
    </div>
    <div class="table-responsive">
        <table class="table table-modern align-middle mb-0">
            <thead>
                <tr>
                    <th>Nama Paket</th>
                    <th class="text-center">Jumlah Sesi</th>
                    <th>Metode Absensi</th>
                    <th>Tipe Kelas</th>
                    <th>Mata Pelajaran</th>
                </tr>
            </thead>
            <tbody>
                @foreach($packages as $package)
                @php
                    $courseNames = $package->mataPelajaran->pluck('nama')->filter()->implode(', ');
                @endphp
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $package->nama }}</div>
                        @if($package->deskripsi)
                            <div class="text-muted" style="font-size:11px;margin-top:2px">{{ Str::limit($package->deskripsi, 60) }}</div>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);border:1px solid var(--soft-primary-border);border-radius:8px;padding:4px 10px;font-weight:700">
                            {{ $package->jumlah_pertemuan ?? 0 }}x
                        </span>
                    </td>
                    <td>
                        <span class="badge" style="background:var(--soft-info-bg);color:var(--soft-info-text);border:1px solid var(--soft-info-border);border-radius:8px;padding:4px 10px;font-size:11px">
                            {{ $package->metode_absensi ?? '-' }}
                        </span>
                    </td>
                    <td>
                        @php
                            $tipeBg = match(strtolower($package->tipe_kelas ?? '')) {
                                'private' => ['bg'=>'var(--soft-warning-bg)','color'=>'var(--soft-warning-text)','border'=>'var(--soft-warning-border)'],
                                'reguler','regular','group' => ['bg'=>'var(--soft-success-bg)','color'=>'var(--soft-success-text)','border'=>'var(--soft-success-border)'],
                                default   => ['bg'=>'var(--soft-muted-bg)','color'=>'var(--soft-muted-text)','border'=>'var(--soft-muted-border)'],
                            };
                        @endphp
                        <span class="badge" style="background:{{ $tipeBg['bg'] }};color:{{ $tipeBg['color'] }};border:1px solid {{ $tipeBg['border'] }};border-radius:8px;padding:4px 10px;font-size:11px">
                            {{ $package->tipe_kelas ?? '-' }}
                        </span>
                    </td>
                    <td>
                        @if($courseNames)
                            @foreach($package->mataPelajaran->pluck('nama')->filter() as $nama)
                                <span class="badge me-1 mb-1" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);border:1px solid var(--soft-primary-border);border-radius:6px;font-weight:500">{{ $nama }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection
