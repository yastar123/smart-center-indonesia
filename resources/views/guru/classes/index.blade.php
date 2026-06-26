@extends('layouts.app')
@section('title','Kelas Absensi')
@section('page-title','Kelas Absensi')

@section('content')

{{-- HEADER BANNER --}}
<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div class="d-flex align-items-center gap-3">
            <div style="width:52px;height:52px;border-radius:16px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0">
                <i class="bi bi-diagram-3-fill"></i>
            </div>
            <div>
                <div style="font-size:11px;opacity:.6;margin-bottom:3px;text-transform:uppercase;letter-spacing:.08em">
                    Portal Guru
                </div>
                <h4 style="font-weight:800;margin-bottom:3px;color:white;letter-spacing:-.02em">
                    Kelas yang Saya Ajar
                </h4>
                <p style="opacity:.65;margin:0;font-size:13px">
                    @if($teacher)
                        {{ $teacher->name }} ·
                        {{ is_array($teacher->subjects) ? implode(', ', $teacher->subjects) : ($teacher->subjects ?? '-') }}
                    @else
                        Daftar kelas dan jadwal mengajar Anda
                    @endif
                </p>
            </div>
        </div>
        <div style="font-size:64px;opacity:.08;line-height:1;flex-shrink:0">
            <i class="bi bi-diagram-3"></i>
        </div>
    </div>
</div>

{{-- STATS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3 fade-up">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Kelas</div>
                    <div class="stat-value text-primary">{{ $classes->count() }}</div>
                    <div class="stat-label text-muted" style="font-size:11px">diajar</div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white">
                    <i class="bi bi-diagram-3-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Siswa</div>
                    <div class="stat-value text-success">{{ $classes->sum(fn($c) => $c->siswa->count()) }}</div>
                    <div class="stat-label" style="font-size:11px;color:#10b981">
                        <i class="bi bi-people me-1"></i>semua kelas
                    </div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Mapel Diajar</div>
                    <div class="stat-value" style="color:#e09000">
                        {{ $classes->pluck('mata_pelajaran_id')->unique()->count() }}
                    </div>
                    <div class="stat-label text-muted" style="font-size:11px">
                        <i class="bi bi-journal-bookmark me-1"></i>mata pelajaran
                    </div>
                </div>
                <div class="stat-icon bg-warning-soft" style="color:white">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card" style="border-top:3px solid #0284c7">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Cabang</div>
                    <div class="stat-value" style="color:#0284c7">
                        {{ $teacher?->branch?->name ?? '—' }}
                    </div>
                    <div class="stat-label text-muted" style="font-size:11px">
                        <i class="bi bi-building me-1"></i>lokasi mengajar
                    </div>
                </div>
                <div class="stat-icon bg-info-soft" style="color:white">
                    <i class="bi bi-building-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CLASSES TABLE --}}
<div class="dashboard-card fade-up">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h6 class="fw-bold mb-1" style="color:var(--text-primary)">
                <i class="bi bi-diagram-3 text-primary me-2"></i>Daftar Kelas
            </h6>
            <p class="text-muted mb-0" style="font-size:12px">Kelola absensi dan lihat detail tiap kelas</p>
        </div>
    </div>

    @if($classes->isEmpty())
    <div class="text-center py-5">
        <div style="width:80px;height:80px;border-radius:50%;background:var(--input-bg);display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
            <i class="bi bi-diagram-3 text-muted" style="font-size:2rem;opacity:.5"></i>
        </div>
        <h6 class="fw-semibold mb-2" style="color:var(--text-primary)">Belum Ada Kelas</h6>
        <p class="text-muted mb-0" style="font-size:13px">Belum ada kelas yang ditugaskan kepada Anda.</p>
    </div>
    @else
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="thead-modern">
                <tr>
                    <th class="small text-muted fw-semibold py-3" style="width:40px">#</th>
                    <th class="small text-muted fw-semibold py-3">KELAS</th>
                    <th class="small text-muted fw-semibold py-3 d-none d-md-table-cell">MATA PELAJARAN</th>
                    <th class="small text-muted fw-semibold py-3 d-none d-lg-table-cell">CABANG</th>
                    <th class="small text-muted fw-semibold py-3 text-center">SISWA</th>
                    <th class="small text-muted fw-semibold py-3 text-center" style="width:160px">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($classes as $i => $c)
                @php
                    $colors = ['#c84ddf','#10b981','#0284c7','#f6af23','#68117e','#059669'];
                    $clr = $colors[$i % count($colors)];
                    $siswaCnt = $c->siswa->count();
                @endphp
                <tr>
                    <td class="text-muted small fw-semibold">{{ $i + 1 }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:38px;height:38px;border-radius:10px;background:{{ $clr }}18;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <i class="bi bi-diagram-3-fill" style="color:{{ $clr }};font-size:15px"></i>
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:13.5px">{{ $c->nama_kelas }}</div>
                                @if($c->jenis ?? null)
                                <div class="text-muted" style="font-size:11px">{{ ucfirst($c->jenis) }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="d-none d-md-table-cell">
                        <span class="badge" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);border:1px solid var(--soft-primary-border);font-size:11.5px;padding:4px 10px;border-radius:7px">
                            <i class="bi bi-journal-bookmark me-1"></i>{{ $c->mataPelajaran->nama ?? '–' }}
                        </span>
                    </td>
                    <td class="d-none d-lg-table-cell">
                        <span class="badge" style="background:var(--input-bg);color:var(--text-muted);border:1px solid var(--card-border);font-size:11px;padding:4px 10px;border-radius:7px">
                            <i class="bi bi-building me-1"></i>{{ $c->cabang->name ?? 'Pusat' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="fw-bold" style="font-size:16px;color:var(--text-primary)">{{ $siswaCnt }}</span>
                        <div class="text-muted" style="font-size:10px">siswa</div>
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="{{ route('guru.classes.show', $c->id) }}"
                               class="btn btn-sm"
                               title="Detail Kelas"
                               style="background:var(--input-bg);border:1px solid var(--card-border);color:var(--text-muted);border-radius:8px;padding:5px 10px;font-size:12px">
                                <i class="bi bi-eye me-1"></i>Detail
                            </a>
                            <a href="{{ route('guru.classes.attendance', $c->id) }}"
                               class="btn btn-sm btn-primary"
                               title="Input Absensi"
                               style="border-radius:8px;padding:5px 10px;font-size:12px">
                                <i class="bi bi-clipboard2-check me-1"></i>Absensi
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection
