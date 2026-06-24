@extends('layouts.app')

@section('title', 'Riwayat Mengajar — '.$teacher->name)

@section('content')
<div class="container-fluid py-4">

    {{-- BREADCRUMB --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="font-size:13px">
            <li class="breadcrumb-item"><a href="{{ route('admin.riwayat-guru.index') }}">Riwayat Guru Mengajar</a></li>
            <li class="breadcrumb-item active">{{ $teacher->name }}</li>
        </ol>
    </nav>

    {{-- TEACHER HEADER --}}
    <div class="dashboard-card mb-4">
        <div class="d-flex align-items-center gap-4 flex-wrap">
            <img src="{{ $teacher->photo_url ?? asset('images/default-avatar.png') }}"
                alt="{{ $teacher->name }}"
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
                    <div class="fw-bold" style="font-size:24px;color:var(--primary)">{{ $stats['total'] }}</div>
                    <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Total Sesi</div>
                </div>
                <div>
                    <div class="fw-bold" style="font-size:24px;color:#10b981">{{ $stats['selesai'] }}</div>
                    <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Selesai</div>
                </div>
                <div>
                    <div class="fw-bold" style="font-size:24px;color:#f59e0b">{{ $stats['dijadwalkan'] }}</div>
                    <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Terjadwal</div>
                </div>
                <div>
                    <div class="fw-bold" style="font-size:24px;color:#6366f1">{{ $stats['paket_count'] }}</div>
                    <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Paket</div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="dashboard-card mb-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold" style="font-size:12px">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="selesai" {{ request('status')=='selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dijadwalkan" {{ request('status')=='dijadwalkan' ? 'selected' : '' }}>Dijadwalkan</option>
                    <option value="dibatalkan" {{ request('status')=='dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            @if($pakets->count() > 0)
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold" style="font-size:12px">Paket</label>
                <select name="paket_id" class="form-select form-select-sm">
                    <option value="">Semua Paket</option>
                    @foreach($pakets as $p)
                    <option value="{{ $p->id }}" {{ request('paket_id') == $p->id ? 'selected' : '' }}>{{ $p->name ?? $p->nama ?? $p->id }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search me-1"></i>Filter</button>
                <a href="{{ route('admin.riwayat-guru.show', $teacher) }}" class="btn btn-outline-secondary btn-sm ms-1">Reset</a>
            </div>
        </form>
    </div>

    {{-- SCHEDULES TABLE --}}
    <div class="dashboard-card">
        <h6 class="fw-bold mb-4" style="font-size:14px;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted)">
            <i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Sesi Mengajar
        </h6>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr style="background:var(--input-bg);color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.05em">
                        <th class="px-3 py-3">#</th>
                        <th class="py-3">Tanggal</th>
                        <th class="py-3">Paket</th>
                        <th class="py-3">Kelas</th>
                        <th class="py-3 text-center">Sesi ke-</th>
                        <th class="py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $statusMap = [
                            'selesai'     => ['rgba(16,185,129,.12)','#10b981','Selesai'],
                            'berlangsung' => ['rgba(59,130,246,.12)','#3b82f6','Berlangsung'],
                            'dijadwalkan' => ['rgba(245,158,11,.12)','#f59e0b','Dijadwalkan'],
                            'dibatalkan'  => ['rgba(239,68,68,.12)','#ef4444','Dibatalkan'],
                        ];
                    @endphp
                    @forelse($schedules as $i => $sched)
                    @php $sc = $statusMap[$sched->status] ?? ['rgba(100,116,139,.12)','#64748b',$sched->status]; @endphp
                    <tr>
                        <td class="px-3" style="font-size:12px;color:var(--text-muted)">{{ $schedules->firstItem() + $i }}</td>
                        <td>
                            <div class="fw-semibold" style="font-size:13px">
                                {{ $sched->tanggal ? \Carbon\Carbon::parse($sched->tanggal)->isoFormat('ddd, D MMM YYYY') : '—' }}
                            </div>
                            @if($sched->waktu_mulai && $sched->waktu_selesai)
                            <div class="text-muted" style="font-size:11px">
                                {{ \Carbon\Carbon::parse($sched->waktu_mulai)->format('H:i') }} –
                                {{ \Carbon\Carbon::parse($sched->waktu_selesai)->format('H:i') }}
                            </div>
                            @endif
                        </td>
                        <td style="font-size:13px">{{ $sched->paket?->name ?? $sched->paket?->nama ?? '—' }}</td>
                        <td style="font-size:13px">{{ $sched->kelas?->nama_kelas ?? '—' }}</td>
                        <td class="text-center">
                            <span class="fw-semibold" style="font-size:13px">{{ $sched->pertemuan_ke ?? '—' }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge rounded-pill px-3 py-1"
                                style="font-size:11px;background:{{ $sc[0] }};color:{{ $sc[1] }}">
                                {{ $sc[2] }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-calendar-x d-block mb-2" style="font-size:32px"></i>
                            Tidak ada riwayat sesi ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($schedules->hasPages())
        <div class="px-3 py-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2" style="border-color:var(--card-border)!important">
            <div class="text-muted" style="font-size:12px">
                Menampilkan {{ $schedules->firstItem() }}–{{ $schedules->lastItem() }} dari {{ $schedules->total() }} sesi
            </div>
            {{ $schedules->links('vendor.pagination.bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection
