@extends('layouts.app')

@section('title', 'Riwayat Sesi — '.$teacher->name)

@section('content')
<div class="container-fluid py-4">

    {{-- BREADCRUMB --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="font-size:13px">
            <li class="breadcrumb-item"><a href="{{ route('admin.riwayat-sesi.index') }}">Riwayat Sesi</a></li>
            <li class="breadcrumb-item active">{{ $teacher->name }}</li>
        </ol>
    </nav>

    {{-- TEACHER HEADER --}}
    <div class="dashboard-card mb-4">
        <div class="d-flex align-items-center gap-4 flex-wrap">
            <img src="{{ $teacher->photo_url }}" alt="{{ $teacher->name }}"
                style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:3px solid #c84ddf">
            <div class="flex-grow-1">
                <h5 class="fw-bold mb-1">{{ $teacher->name }}</h5>
                <div class="text-muted" style="font-size:13px">
                    <i class="bi bi-building me-1"></i>{{ $teacher->branch?->name ?? 'Pusat' }}
                    @if(!empty($teacher->subjects))
                    &nbsp;·&nbsp;<i class="bi bi-journal-bookmark me-1"></i>{{ implode(', ', $teacher->subjects) }}
                    @endif
                </div>
            </div>
            <div class="d-flex gap-4 flex-wrap text-center">
                <div>
                    <div class="fw-bold" style="font-size:24px;color:var(--primary)">{{ $stats['sesi_total'] }}</div>
                    <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Total Sesi</div>
                </div>
                <div>
                    <div class="fw-bold" style="font-size:24px;color:#10b981">{{ $stats['sesi_selesai'] }}</div>
                    <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Selesai</div>
                </div>
                <div>
                    <div class="fw-bold" style="font-size:24px;color:#6366f1">{{ $stats['total_kelas'] }}</div>
                    <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Kelas</div>
                </div>
                <div>
                    <div class="fw-bold" style="font-size:24px;color:#f59e0b">{{ $stats['total_siswa'] }}</div>
                    <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Siswa</div>
                </div>
            </div>
        </div>
    </div>

    {{-- CLASS PROGRESS --}}
    @if($classData->count() > 0)
    <div class="dashboard-card mb-4">
        <h6 class="fw-bold mb-4" style="font-size:14px;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted)">
            <i class="bi bi-grid me-2 text-primary"></i>Progress Per Kelas
        </h6>
        <div class="row g-3">
            @foreach($classData as $item)
            @php $kelas = $item['kelas']; @endphp
            <div class="col-12 col-md-6">
                <div class="p-3 rounded-3" style="border:1px solid var(--card-border);background:var(--input-bg)">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div>
                            <div class="fw-semibold" style="font-size:13px">{{ $kelas->nama_kelas }}</div>
                            <div class="text-muted" style="font-size:11px">
                                {{ $kelas->mataPelajaran?->nama ?? '—' }}
                                @if($kelas->siswa->count() > 0)
                                &nbsp;·&nbsp; {{ $kelas->siswa->count() }} siswa
                                @endif
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="fw-bold" style="font-size:14px;color:var(--primary)">{{ $item['selesai'] }}</span>
                            <span class="text-muted" style="font-size:12px">/{{ $item['target'] }}</span>
                        </div>
                    </div>
                    <div style="height:6px;border-radius:3px;background:var(--card-border);overflow:hidden">
                        <div style="width:{{ $item['progress'] }}%;height:100%;background:linear-gradient(90deg,#461256,#c84ddf);border-radius:3px;transition:width .6s"></div>
                    </div>
                    <div class="text-end mt-1" style="font-size:10px;color:var(--text-muted)">{{ $item['progress'] }}%</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- RECENT SESSIONS TABLE --}}
    <div class="dashboard-card">
        <h6 class="fw-bold mb-4" style="font-size:14px;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted)">
            <i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Sesi Terbaru
        </h6>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr style="background:var(--input-bg);color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.05em">
                        <th class="px-3 py-3">#</th>
                        <th class="py-3">Tanggal & Waktu</th>
                        <th class="py-3">Paket</th>
                        <th class="py-3">Sesi</th>
                        <th class="py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $statusColors = [
                            'selesai'    => ['rgba(16,185,129,.1)','#10b981','Selesai'],
                            'berlangsung'=> ['rgba(59,130,246,.1)','#3b82f6','Berlangsung'],
                            'terjadwal'  => ['rgba(245,158,11,.1)','#f59e0b','Terjadwal'],
                            'dibatalkan' => ['rgba(239,68,68,.1)','#ef4444','Dibatalkan'],
                        ];
                    @endphp
                    @forelse($recentSchedules as $i => $sched)
                    @php $sc = $statusColors[$sched->status] ?? ['rgba(100,116,139,.1)','#64748b',$sched->status]; @endphp
                    <tr>
                        <td class="px-3" style="font-size:12px;color:var(--text-muted)">{{ $i + 1 }}</td>
                        <td>
                            <div class="fw-semibold" style="font-size:13px">
                                {{ \Carbon\Carbon::parse($sched->tanggal)->isoFormat('ddd, D MMM YYYY') }}
                            </div>
                            <div class="text-muted" style="font-size:12px">
                                {{ substr($sched->jam_mulai ?? '', 0, 5) }} – {{ substr($sched->jam_selesai ?? '', 0, 5) }}
                            </div>
                        </td>
                        <td>
                            <div style="font-size:13px">{{ $sched->paket?->nama ?? '—' }}</div>
                        </td>
                        <td>
                            <span style="font-size:13px">Sesi ke-{{ $sched->pertemuan_ke ?? '—' }}</span>
                        </td>
                        <td class="text-center">
                            <span style="background:{{ $sc[0] }};color:{{ $sc[1] }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600">
                                {{ $sc[2] }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-calendar-x" style="font-size:36px;opacity:.2;display:block;margin-bottom:12px"></i>
                            Tidak ada sesi ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
