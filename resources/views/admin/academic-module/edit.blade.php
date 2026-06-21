@extends('layouts.app')
@section('title', 'Edit Modul Akademik')
@section('page-title', 'Edit Modul Akademik')

@section('content')
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.module.index') }}">Modul Akademik</a></li>
        <li class="breadcrumb-item active">Edit Modul</li>
    </ol>
</nav>

@if($errors->any())
    <div class="alert alert-danger mb-3">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('admin.module.update', $module) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="dashboard-card">
            <h6 class="fw-bold mb-4 pb-2 border-bottom">Edit Modul: {{ $module->judul }}</h6>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kode Modul</label>
                    <input type="text" name="kode_modul" class="form-control @error('kode_modul') is-invalid @enderror"
                           value="{{ old('kode_modul', $module->kode_modul) }}" placeholder="MOD-MAT-01" style="font-family:monospace">
                    @error('kode_modul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Judul Modul <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                           value="{{ old('judul', $module->judul) }}" required>
                    @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Mata Pelajaran <span class="text-danger">*</span></label>
                    <select name="mata_pelajaran_id" class="form-select @error('mata_pelajaran_id') is-invalid @enderror" required>
                        <option value="">— Pilih Mata Pelajaran —</option>
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}" {{ old('mata_pelajaran_id', $module->mata_pelajaran_id)==$c->id?'selected':'' }}>{{ $c->nama }}</option>
                        @endforeach
                    </select>
                    @error('mata_pelajaran_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Jenis</label>
                    <select name="jenis" class="form-select">
                        <option value="materi" {{ old('jenis', $module->jenis)=='materi'?'selected':'' }}>Materi</option>
                        <option value="pdf"    {{ old('jenis', $module->jenis)=='pdf'   ?'selected':'' }}>PDF</option>
                        <option value="video"  {{ old('jenis', $module->jenis)=='video' ?'selected':'' }}>Video</option>
                        <option value="link"   {{ old('jenis', $module->jenis)=='link'  ?'selected':'' }}>Link</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="aktif"  {{ old('status', $module->status)=='aktif'  ?'selected':'' }}>Aktif</option>
                        <option value="review" {{ old('status', $module->status)=='review' ?'selected':'' }}>Review</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Upload File Modul (PDF / DOC / DOCX)</label>
                    <input type="file" name="module_file"
                           class="form-control @error('module_file') is-invalid @enderror"
                           accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                    <div class="form-text">Kosongkan jika tidak ingin mengganti file. Wajib salah satu: file atau link video.</div>
                    @error('module_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Link Video Modul</label>
                    <input type="url" name="video_url" value="{{ old('video_url', $module->file_url) }}"
                           class="form-control @error('video_url') is-invalid @enderror"
                           placeholder="https://www.youtube.com/watch?v=...">
                    <div class="form-text">Isi jika modul menggunakan video pembelajaran.</div>
                    @error('video_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Deskripsi / Silabus</label>
                    <textarea name="deskripsi" rows="4" class="form-control" placeholder="Isi silabus, bab, atau deskripsi singkat modul...">{{ old('deskripsi', $module->deskripsi) }}</textarea>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary fw-semibold px-4">
                    <i class="bi bi-check-lg me-2"></i>Perbarui Modul
                </button>
                <a href="{{ route('admin.module.show', $module) }}" class="btn btn-outline-secondary fw-semibold px-4">
                    <i class="bi bi-arrow-left me-2"></i>Batal
                </a>
            </div>
        </div>
        </form>
    </div>

    <div class="col-lg-4">
        <div class="dashboard-card">
            <h6 class="fw-bold mb-3">Info Modul</h6>
            <div class="d-flex flex-column gap-2">
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted" style="font-size:13px">Kode Saat Ini</span>
                    <code style="font-size:13px">{{ $module->kode_modul ?: '—' }}</code>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted" style="font-size:13px">Status Saat Ini</span>
                    <span class="fw-semibold text-capitalize" style="font-size:13px">{{ ucfirst($module->status) }}</span>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span class="text-muted" style="font-size:13px">Terakhir Diubah</span>
                    <span style="font-size:13px">{{ $module->updated_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
@endsection
