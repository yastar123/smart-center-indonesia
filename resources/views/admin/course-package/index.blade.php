@extends('layouts.app')

@section('title', 'Paket Belajar')
@section('page-title', 'Paket Belajar & Harga')

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
                    <i class="bi bi-box-seam"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Paket Belajar & Harga</h5>
                    <span style="font-size:12px;opacity:.8">Daftar paket kursus yang tersedia, jenjang, dan harga</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('admin.course-package.create') }}" class="btn fw-semibold px-4" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                <i class="bi bi-plus-lg me-2"></i>Tambah Paket
            </a>
        </div>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    @php
        $statCards = [
            ['label'=>'Total Paket',   'value'=>$stats['total'],  'icon'=>'bi-box-seam',          'topColor'=>'#10b981','textColor'=>'text-success','iconBg'=>'bg-success-soft'],
            ['label'=>'Paket Aktif',   'value'=>$stats['aktif'],  'icon'=>'bi-check-circle-fill', 'topColor'=>'#c84ddf','textColor'=>'text-primary','iconBg'=>'bg-primary-soft'],
            ['label'=>'Draft',         'value'=>$stats['draft'],  'icon'=>'bi-hourglass-split',   'topColor'=>'#f6af23','textColor'=>'text-warning','iconBg'=>'bg-warning-soft'],
            ['label'=>'Privat',        'value'=>$stats['privat'], 'icon'=>'bi-person-check-fill', 'topColor'=>'#3b82f6','textColor'=>'text-info',   'iconBg'=>'bg-info-soft'],
        ];
    @endphp
    @foreach($statCards as $i => $sc)
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:{{ $i * 0.05 }}s">
        <div class="stat-card" style="border-top:3px solid {{ $sc['topColor'] }}">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">{{ $sc['label'] }}</div>
                    <div class="stat-value {{ $sc['textColor'] }} count-up" data-target="{{ $sc['value'] }}">{{ $sc['value'] }}</div>
                </div>
                <div class="stat-icon {{ $sc['iconBg'] }}" style="color:white">
                    <i class="bi {{ $sc['icon'] }}"></i>
                </div>
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
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama paket…" value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-6 col-md-2">
            <select name="jenis" class="form-select">
                <option value="">Semua Jenis</option>
                <option value="reguler"  {{ request('jenis')=='reguler' ?'selected':'' }}>Reguler</option>
                <option value="intensif" {{ request('jenis')=='intensif'?'selected':'' }}>Intensif</option>
                <option value="privat"   {{ request('jenis')=='privat'  ?'selected':'' }}>Privat</option>
                <option value="online"   {{ request('jenis')=='online'  ?'selected':'' }}>Online</option>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="aktif"    {{ request('status')=='aktif'   ?'selected':'' }}>Aktif</option>
                <option value="nonaktif" {{ request('status')=='nonaktif'?'selected':'' }}>Draft</option>
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
        <div class="col-3 col-md-1">
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel"></i></button>
        </div>
        <div class="col-3 col-md-1">
            <a href="{{ route('admin.course-package.index') }}" class="btn btn-outline-secondary w-100"><i class="bi bi-x-lg"></i></a>
        </div>
    </form>
</div>

{{-- TABLE --}}
<div class="dashboard-card fade-up">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover table-modern align-middle mb-0">
            <thead class="thead-modern">
                <tr>
                    <th>Nama Paket</th>
                    <th>Kategori & Jenjang</th>
                    <th>Mata Pelajaran</th>
                    <th class="text-center">Jumlah Sesi</th>
                    <th>Harga Dasar</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($packages as $p)
                @php
                    $jenisLabel = match($p->jenis) {
                        'reguler'  => 'Reguler',
                        'privat'   => 'Privat (1 Siswa)',
                        'intensif' => 'Intensif',
                        'online'   => 'Online',
                        default    => ucfirst($p->jenis),
                    };
                    $jenisColor = match($p->jenis) {
                        'privat'   => ['bg'=>'rgba(200,77,223,.15)','fg'=>'#461256'],
                        'intensif' => ['bg'=>'rgba(246,175,35,.15)','fg'=>'#e09000'],
                        'online'   => ['bg'=>'rgba(59,130,246,.15)', 'fg'=>'#3b82f6'],
                        default    => ['bg'=>'rgba(16,185,129,.15)', 'fg'=>'#059669'],
                    };
                @endphp
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $p->nama }}</div>
                                <div class="text-muted" style="font-size:11px">{{ $p->durasi_bulan }} bulan · {{ $p->cabang->name ?? 'Semua' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge" style="background:{{ $jenisColor['bg'] }};color:{{ $jenisColor['fg'] }};font-size:11px">
                            {{ $jenisLabel }}
                        </span>
                    </td>
                    <td>
                        @if($p->mataPelajaran->isNotEmpty())
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($p->mataPelajaran as $course)
                                    <span class="badge" style="background:rgba(16,185,129,.12);color:#059669;font-size:11px">{{ $course->nama }}</span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-muted" style="font-size:12px">—</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="fw-semibold">{{ $p->jumlah_pertemuan }}</span>
                        <span class="text-muted" style="font-size:11px"> Sesi</span>
                    </td>
                    <td>
                        <span class="fw-bold text-primary">Rp {{ number_format($p->harga, 0, ',', '.') }}</span>
                    </td>
                    <td>
                        @if($p->status === 'aktif')
                            <span class="badge" style="background:rgba(16,185,129,.15);color:#059669">Aktif</span>
                        @else
                            <span class="badge" style="background:rgba(246,175,35,.15);color:#e09000">Draft</span>
                        @endif
                        @if($p->is_unggulan)
                            <span class="badge ms-1" style="background:rgba(246,175,35,.2);color:#e09000"><i class="bi bi-star-fill"></i></span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            @if($p->status === 'aktif')
                                <a href="{{ route('admin.course-package.show', $p) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            @endif
                            <a href="{{ route('admin.course-package.edit', $p) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('admin.course-package.destroy', $p) }}" class="d-inline" onsubmit="return confirm('Hapus paket ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">
                    <i class="bi bi-box-seam fs-3 d-block mb-2"></i>Belum ada paket belajar.
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($packages->hasPages())
    <div class="d-flex justify-content-center mt-3">
        {{ $packages->links() }}
    </div>
    @endif
</div>

</div>
@endsection
