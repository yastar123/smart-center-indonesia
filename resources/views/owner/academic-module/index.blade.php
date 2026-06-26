@extends('layouts.app')
@section('title', 'Modul Akademik')
@section('page-title', 'Modul Akademik')

@section('content')
<div class="fade-up">

{{-- HEADER --}}
<div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px">
                    <i class="bi bi-journal-text"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Modul Akademik</h5>
                    <span style="font-size:12px;opacity:.8">Daftar silabus, bab materi, dan referensi modul belajar</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('owner.module.create') }}" class="btn fw-semibold px-4"
               style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                <i class="bi bi-plus-lg me-2"></i>Tambah Modul
            </a>
        </div>
    </div>
</div>

{{-- STATS --}}
<div class="row g-3 mb-4">
    <div class="col-4 fade-up">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Total Modul</div><div class="stat-value">{{ $stats['total'] }}</div></div>
                <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-journal-text"></i></div>
            </div>
        </div>
    </div>
    <div class="col-4 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Aktif</div><div class="stat-value text-success">{{ $stats['aktif'] }}</div></div>
                <div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-check-circle-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-4 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Nonaktif</div><div class="stat-value text-warning">{{ $stats['review'] }}</div></div>
                <div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-hourglass-split"></i></div>
            </div>
        </div>
    </div>
</div>

{{-- FILTER --}}
<div class="dashboard-card mb-4 fade-up">
    <form method="GET" action="{{ route('owner.module.index') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-semibold" style="font-size:12px">Cari Modul</label>
                <div class="input-group">
                    <span class="input-group-text" style="background:var(--input-bg);border-color:var(--card-border)"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Kode modul atau judul..."
                        class="form-control" style="border-color:var(--card-border);background:var(--input-bg)">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold" style="font-size:12px">Mata Pelajaran</label>
                <select name="mata_pelajaran_id" class="form-select" style="border-color:var(--card-border);background:var(--input-bg)">
                    <option value="">Semua Mapel</option>
                    @foreach($courses as $c)
                        <option value="{{ $c->id }}" {{ request('mata_pelajaran_id')==$c->id?'selected':'' }}>{{ $c->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold" style="font-size:12px">Status</label>
                <select name="status" class="form-select" style="border-color:var(--card-border);background:var(--input-bg)">
                    <option value="">Semua</option>
                    <option value="aktif"    {{ request('status')=='aktif'    ?'selected':'' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status')=='nonaktif' ?'selected':'' }}>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill fw-semibold">Filter</button>
                <a href="{{ route('owner.module.index') }}" class="btn btn-outline-secondary fw-semibold">Reset</a>
            </div>
        </div>
    </form>
</div>

{{-- FLASH --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

{{-- TABLE --}}
<div class="dashboard-card fade-up">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr style="background:var(--input-bg)">
                    <th class="fw-semibold" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px">Kode Modul</th>
                    <th class="fw-semibold" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px">Judul Modul</th>
                    <th class="fw-semibold" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px">Mata Pelajaran</th>
                    <th class="fw-semibold" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px">Deskripsi</th>
                    <th class="fw-semibold" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px">Jenis</th>
                    <th class="fw-semibold" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px">File Modul atau Link Modul</th>
                    <th class="fw-semibold text-center" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px">Status</th>
                    <th class="fw-semibold text-center" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($modules as $m)
                @php
                    $isAktif = $m->status === 'aktif';
                @endphp
                <tr>
                    <td>
                        <code style="background:var(--soft-primary);color:#461256;padding:3px 8px;border-radius:6px;font-size:13px;font-weight:600">
                            {{ $m->kode_modul ?: '—' }}
                        </code>
                    </td>
                    <td>
                        <div class="fw-semibold">{{ $m->judul }}</div>
                    </td>
                    <td>
                        @if($m->mataPelajaran)
                            <span style="background:var(--soft-info);color:#0369a1;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:500">
                                {{ $m->mataPelajaran->nama }}
                            </span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="text-muted" style="font-size:12px">{{ $m->deskripsi ? Str::limit($m->deskripsi, 80) : '—' }}</span>
                    </td>
                    <td>
                        <span class="text-capitalize">{{ $m->jenis ?: '—' }}</span>
                    </td>
                    <td>
                        @php
                            $moduleFileUrl = null;
                            if (!empty($m->file_path)) {
                                if (Storage::disk('public')->exists($m->file_path)) {
                                    $moduleFileUrl = Storage::disk('public')->url($m->file_path);
                                } elseif (file_exists(public_path($m->file_path))) {
                                    $moduleFileUrl = asset($m->file_path);
                                }
                            }
                        @endphp
                        @if($moduleFileUrl)
                            <a href="{{ $moduleFileUrl }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
                        @elseif($m->file_url)
                            <a href="{{ $m->file_url }}" target="_blank" class="btn btn-sm btn-outline-info">Lihat Link</a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($isAktif)
                            <span style="background:var(--soft-success);color:#059669;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600">
                                <i class="bi bi-check-circle-fill me-1"></i>Aktif
                            </span>
                        @else
                            <span style="background:var(--soft-warning);color:#d97706;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600">
                                <i class="bi bi-x-circle-fill me-1"></i>Nonaktif
                            </span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('owner.module.show', $m) }}" class="btn btn-sm btn-outline-primary me-1" title="Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('owner.module.edit', $m) }}" class="btn btn-sm btn-outline-secondary me-1" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('owner.module.destroy', $m) }}" class="d-inline"
                              onsubmit="return confirm('Hapus modul ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <div style="font-size:40px;opacity:.3;margin-bottom:8px"><i class="bi bi-journal-x"></i></div>
                        <div class="text-muted">Belum ada modul akademik</div>
                        <a href="{{ route('owner.module.create') }}" class="btn btn-sm btn-primary mt-2">Tambah Modul</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($modules->hasPages())
        <div class="p-3 border-top">{{ $modules->links() }}</div>
    @endif
</div>

</div>
@endsection
