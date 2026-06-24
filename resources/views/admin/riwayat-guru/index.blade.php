@extends('layouts.app')

@section('title', 'Riwayat Guru Mengajar')

@section('content')
<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="font-size:22px">Riwayat Guru Mengajar</h4>
            <p class="text-muted mb-0" style="font-size:13px">Rekap jumlah sesi mengajar dan performa masing-masing guru.</p>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="dashboard-card mb-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-12 col-md-5">
                <label class="form-label fw-semibold" style="font-size:12px">Cari Guru</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Nama guru..." value="{{ request('search') }}">
            </div>
            @if(auth()->user()->hasRole('owner'))
            <div class="col-12 col-md-3">
                <label class="form-label fw-semibold" style="font-size:12px">Cabang</label>
                <select name="cabang_id" class="form-select form-select-sm">
                    <option value="">Semua Cabang</option>
                    @foreach($branches as $b)
                    <option value="{{ $b->id }}" {{ request('cabang_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="col-12 col-md-2">
                <label class="form-label fw-semibold" style="font-size:12px">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <option value="aktif" {{ request('status')=='aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="tidak aktif" {{ request('status')=='tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search me-1"></i>Cari</button>
                <a href="{{ route('admin.riwayat-guru.index') }}" class="btn btn-outline-secondary btn-sm ms-1">Reset</a>
            </div>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="dashboard-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr style="background:var(--input-bg);color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.05em">
                        <th class="px-3 py-3">#</th>
                        <th class="py-3">Guru</th>
                        <th class="py-3">Cabang</th>
                        <th class="py-3 text-center">Total Sesi</th>
                        <th class="py-3 text-center">Selesai</th>
                        <th class="py-3 text-center">Progress</th>
                        <th class="py-3 text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $i => $teacher)
                    @php
                        $total   = $teacher->total_sesi ?? 0;
                        $selesai = $teacher->sesi_selesai ?? 0;
                        $pct     = $total > 0 ? round($selesai / $total * 100) : 0;
                    @endphp
                    <tr>
                        <td class="px-3" style="font-size:12px;color:var(--text-muted)">{{ $teachers->firstItem() + $i }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $teacher->photo_url ?? asset('images/default-avatar.png') }}"
                                    alt="{{ $teacher->name }}"
                                    style="width:38px;height:38px;border-radius:50%;object-fit:cover;border:2px solid var(--card-border)">
                                <div>
                                    <div class="fw-semibold" style="font-size:14px">{{ $teacher->name }}</div>
                                    <div class="text-muted" style="font-size:11px">{{ implode(', ', $teacher->subjects ?? []) ?: '—' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span style="font-size:12px;color:var(--text-muted)">{{ $teacher->branch?->name ?? 'Pusat' }}</span>
                        </td>
                        <td class="text-center">
                            <span class="fw-bold" style="font-size:15px;color:var(--primary)">{{ $total }}</span>
                        </td>
                        <td class="text-center">
                            <span class="fw-semibold" style="font-size:14px;color:#10b981">{{ $selesai }}</span>
                        </td>
                        <td style="min-width:120px">
                            <div class="d-flex align-items-center gap-2">
                                <div style="flex:1;height:6px;border-radius:3px;background:var(--card-border);overflow:hidden">
                                    <div style="width:{{ $pct }}%;height:100%;background:linear-gradient(90deg,#461256,#c84ddf);border-radius:3px"></div>
                                </div>
                                <span style="font-size:11px;color:var(--text-muted);min-width:32px">{{ $pct }}%</span>
                            </div>
                        </td>
                        <td class="text-end pe-3">
                            <a href="{{ route('admin.riwayat-guru.show', $teacher) }}"
                               class="btn btn-outline-primary btn-sm" style="font-size:12px">
                                <i class="bi bi-eye me-1"></i>Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-person-x d-block mb-2" style="font-size:32px"></i>
                            Tidak ada data guru ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($teachers->hasPages())
        <div class="px-3 py-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2" style="border-color:var(--card-border)!important">
            <div class="text-muted" style="font-size:12px">
                Menampilkan {{ $teachers->firstItem() }}–{{ $teachers->lastItem() }} dari {{ $teachers->total() }} guru
            </div>
            {{ $teachers->links('vendor.pagination.bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection
