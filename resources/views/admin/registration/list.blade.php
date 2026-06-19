@extends('layouts.app')

@section('title', 'Daftar Registrasi')
@section('page-title', 'Daftar Registrasi')

@section('content')
<div>

{{-- HEADER --}}
<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Daftar Registrasi</h5>
                    <span style="font-size:12px;opacity:.8">Semua pendaftaran kelas aktif siswa di semua cabang</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('admin.registration.create') }}" class="btn fw-semibold px-4" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                <i class="bi bi-plus-lg me-2"></i>Registrasi Baru
            </a>
        </div>
    </div>
</div>

{{-- STATS --}}
<div class="row g-3 mb-4">
    @php
        $statCards = [
            ['label'=>'Total Registrasi','value'=>$stats['total'],  'icon'=>'bi-people-fill',     'color'=>'#c84ddf','bg'=>'bg-primary-soft'],
            ['label'=>'Kelas Aktif',     'value'=>$stats['aktif'],  'icon'=>'bi-check-circle-fill','color'=>'#10b981','bg'=>'bg-success-soft'],
            ['label'=>'Kelas Privat',    'value'=>$stats['privat'], 'icon'=>'bi-person-check',     'color'=>'#f6af23','bg'=>'bg-warning-soft'],
            ['label'=>'Kelas Reguler',   'value'=>$stats['reguler'],'icon'=>'bi-people',           'color'=>'#3b82f6','bg'=>'bg-info-soft'],
        ];
    @endphp
    @foreach($statCards as $i => $sc)
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:{{ $i * 0.05 }}s">
        <div class="stat-card" style="border-top:3px solid {{ $sc['color'] }}">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">{{ $sc['label'] }}</div>
                    <div class="stat-value" style="color:{{ $sc['color'] }}" data-auto-count="{{ $sc['value'] }}">{{ $sc['value'] }}</div>
                </div>
                <div class="stat-icon {{ $sc['bg'] }}" style="color:white"><i class="bi {{ $sc['icon'] }}"></i></div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- FILTERS --}}
<div class="dashboard-card mb-4 fade-up">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-12 col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama kelas…" value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-6 col-md-2">
            <select name="jenis" class="form-select">
                <option value="">Semua Jenis</option>
                <option value="offline" {{ request('jenis')=='offline'?'selected':'' }}>Offline (Reguler)</option>
                <option value="online"  {{ request('jenis')=='online' ?'selected':'' }}>Online</option>
                <option value="private" {{ request('jenis')=='private'?'selected':'' }}>Private</option>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="aktif"  {{ request('status')=='aktif'  ?'selected':'' }}>Aktif</option>
                <option value="selesai"{{ request('status')=='selesai'?'selected':'' }}>Selesai</option>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="cabang_id" class="form-select">
                <option value="">Semua Cabang</option>
                @foreach($branches as $b)
                    <option value="{{ $b->id }}" {{ request('cabang_id')==$b->id?'selected':'' }}>{{ $b->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3 col-md-1"><button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel"></i></button></div>
        <div class="col-3 col-md-1"><a href="{{ route('admin.registration.index') }}" class="btn btn-outline-secondary w-100"><i class="bi bi-x-lg"></i></a></div>
    </form>
</div>

{{-- TABLE --}}
<div class="dashboard-card fade-up">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3"><i class="bi bi-x-circle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover table-modern align-middle mb-0">
            <thead class="thead-modern">
                <tr>
                    <th>Nama Kelas</th>
                    <th>Guru</th>
                    <th>Mata Pelajaran</th>
                    <th>Jenis</th>
                    <th class="text-center">Siswa</th>
                    <th>Cabang</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registrations as $r)
                @php
                    $jenisColor = match($r->jenis) {
                        'private' => ['bg'=>'rgba(200,77,223,.15)','fg'=>'#461256'],
                        'online'  => ['bg'=>'rgba(59,130,246,.15)', 'fg'=>'#3b82f6'],
                        default   => ['bg'=>'rgba(16,185,129,.15)', 'fg'=>'#059669'],
                    };
                @endphp
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $r->nama_kelas }}</div>
                        <div class="text-muted" style="font-size:11px">{{ $r->jumlah_pertemuan }} pertemuan</div>
                    </td>
                    <td>{{ $r->guru->name ?? '—' }}</td>
                    <td>{{ $r->mataPelajaran->nama ?? '—' }}</td>
                    <td>
                        <span class="badge" style="background:{{ $jenisColor['bg'] }};color:{{ $jenisColor['fg'] }};font-size:11px">
                            {{ ucfirst($r->jenis) }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="fw-semibold">{{ $r->siswa->count() }}</span>
                        <span class="text-muted" style="font-size:11px">/{{ $r->kapasitas }}</span>
                    </td>
                    <td>{{ $r->cabang->name ?? '—' }}</td>
                    <td>
                        @if($r->status === 'aktif')
                            <span class="badge" style="background:rgba(16,185,129,.15);color:#059669">Aktif</span>
                        @else
                            <span class="badge" style="background:rgba(107,114,128,.15);color:#6b7280">{{ ucfirst($r->status) }}</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.classes.show', $r) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">
                    <i class="bi bi-person-x fs-3 d-block mb-2"></i>Belum ada registrasi.
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($registrations->hasPages())
    <div class="d-flex justify-content-center mt-3">{{ $registrations->links() }}</div>
    @endif
</div>

</div>
@endsection
