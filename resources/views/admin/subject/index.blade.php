@extends('layouts.app')

@section('title', 'Mata Pelajaran')
@section('page-title', 'Mata Pelajaran')

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
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Mata Pelajaran</h5>
                    <span style="font-size:12px;opacity:.8">Daftar master mata pelajaran yang ditawarkan kepada siswa</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end d-flex justify-content-md-end gap-2">
            <a href="{{ route('admin.subject.create') }}" class="btn fw-semibold px-4" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                <i class="bi bi-plus-lg me-2"></i>Tambah Mapel
            </a>
        </div>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    @php
        $statCards = [
            ['label'=>'Total Mapel',  'value'=>$stats['total'],    'icon'=>'bi-book-fill',        'topColor'=>'#10b981', 'textColor'=>'text-success', 'iconBg'=>'bg-success-soft'],
            ['label'=>'Mapel Aktif',  'value'=>$stats['aktif'],    'icon'=>'bi-check-circle-fill','topColor'=>'#c84ddf', 'textColor'=>'text-primary', 'iconBg'=>'bg-primary-soft'],
            ['label'=>'Tidak Aktif',  'value'=>$stats['nonaktif'], 'icon'=>'bi-x-circle-fill',   'topColor'=>'#ef4444', 'textColor'=>'text-danger',  'iconBg'=>'bg-danger-soft'],
            ['label'=>'Academic',     'value'=>$stats['academic'], 'icon'=>'bi-mortarboard-fill', 'topColor'=>'#3b82f6', 'textColor'=>'text-info',    'iconBg'=>'bg-info-soft'],
            ['label'=>'Skill/Soft',   'value'=>$stats['skill'],    'icon'=>'bi-lightning-fill',   'topColor'=>'#f6af23', 'textColor'=>'text-warning', 'iconBg'=>'bg-warning-soft'],
        ];
    @endphp
    @foreach($statCards as $i => $sc)
    <div class="col-6 col-lg fade-up" style="animation-delay:{{ $i * 0.05 }}s">
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
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama atau kode mapel…" value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-6 col-md-2">
            <select name="kategori" class="form-select">
                <option value="">Semua Kategori</option>
                <option value="academic" {{ request('kategori')=='academic'?'selected':'' }}>Academic</option>
                <option value="skill"    {{ request('kategori')=='skill'   ?'selected':'' }}>Skill / Soft-Skill</option>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="aktif"    {{ request('status')=='aktif'   ?'selected':'' }}>Aktif</option>
                <option value="nonaktif" {{ request('status')=='nonaktif'?'selected':'' }}>Tidak Aktif</option>
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
            <a href="{{ route('admin.subject.index') }}" class="btn btn-outline-secondary w-100"><i class="bi bi-x-lg"></i></a>
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
                    <th>Kode</th>
                    <th>Nama Pelajaran</th>
                    <th>Kategori Keterampilan</th>
                    <th>Cabang</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subjects as $s)
                <tr>
                    <td><span class="badge bg-secondary">{{ $s->kode }}</span></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0">
                                <i class="bi bi-book"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $s->nama }}</div>
                                <div class="text-muted" style="font-size:11px">{{ Str::limit($s->deskripsi, 50) }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($s->kategori === 'academic')
                            <span class="badge" style="background:rgba(59,130,246,.15);color:#3b82f6;font-size:11px">
                                <i class="bi bi-mortarboard me-1"></i>Academic
                            </span>
                        @else
                            <span class="badge" style="background:rgba(246,175,35,.15);color:#e09000;font-size:11px">
                                <i class="bi bi-lightning me-1"></i>Skill / Soft-Skill
                            </span>
                        @endif
                    </td>
                    <td>{{ $s->cabang->name ?? '—' }}</td>
                    <td>
                        @if($s->status === 'aktif')
                            <span class="badge" style="background:rgba(16,185,129,.15);color:#059669">Aktif</span>
                        @else
                            <span class="badge" style="background:rgba(239,68,68,.15);color:#dc2626">Tidak Aktif</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('admin.subject.show', $s) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                            <a href="{{ route('admin.subject.edit', $s) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('admin.subject.destroy', $s) }}" class="d-inline" onsubmit="return confirm('Hapus mata pelajaran ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">
                    <i class="bi bi-journal-x fs-3 d-block mb-2"></i>Belum ada mata pelajaran.
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($subjects->hasPages())
    <div class="d-flex justify-content-center mt-3">
        {{ $subjects->links() }}
    </div>
    @endif
</div>

</div>
@endsection
