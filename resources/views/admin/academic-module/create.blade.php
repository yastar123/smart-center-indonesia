@extends('layouts.app')
@section('title', 'Tambah Modul Akademik')
@section('page-title', 'Tambah Modul Akademik')

@section('content')
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.module.index') }}">Modul Akademik</a></li>
        <li class="breadcrumb-item active">Tambah Modul</li>
    </ol>
</nav>

@if($errors->any())
    <div class="alert alert-danger mb-3">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('admin.module.store') }}">
        @csrf
        <div class="dashboard-card">
            <h6 class="fw-bold mb-4 pb-2 border-bottom">Informasi Modul Akademik</h6>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kode Modul</label>
                    <input type="text" name="kode_modul" class="form-control @error('kode_modul') is-invalid @enderror"
                           value="{{ old('kode_modul') }}" placeholder="MOD-MAT-01" style="font-family:monospace">
                    <div class="form-text">Format: MOD-[MAPEL]-[NOMOR]</div>
                    @error('kode_modul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Judul Modul <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                           value="{{ old('judul') }}" placeholder="Misal: Aljabar Linear Lanjut" required>
                    @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Mata Pelajaran <span class="text-danger">*</span></label>
                    <select name="mata_pelajaran_id" class="form-select @error('mata_pelajaran_id') is-invalid @enderror" required>
                        <option value="">— Pilih Mata Pelajaran —</option>
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}" {{ old('mata_pelajaran_id')==$c->id?'selected':'' }}>{{ $c->nama }}</option>
                        @endforeach
                    </select>
                    @error('mata_pelajaran_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Jenis</label>
                    <select name="jenis" class="form-select">
                        <option value="materi" {{ old('jenis','materi')=='materi'?'selected':'' }}>Materi</option>
                        <option value="pdf"    {{ old('jenis')=='pdf'   ?'selected':'' }}>PDF</option>
                        <option value="video"  {{ old('jenis')=='video' ?'selected':'' }}>Video</option>
                        <option value="link"   {{ old('jenis')=='link'  ?'selected':'' }}>Link</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="aktif"  {{ old('status','aktif')=='aktif'  ?'selected':'' }}>Aktif</option>
                        <option value="review" {{ old('status')=='review'?'selected':'' }}>Review</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Deskripsi / Silabus</label>
                    <textarea name="deskripsi" rows="4" class="form-control" placeholder="Isi silabus, bab, atau deskripsi singkat modul...">{{ old('deskripsi') }}</textarea>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary fw-semibold px-4">
                    <i class="bi bi-check-lg me-2"></i>Simpan Modul
                </button>
                <a href="{{ route('admin.module.index') }}" class="btn btn-outline-secondary fw-semibold px-4">
                    <i class="bi bi-arrow-left me-2"></i>Batal
                </a>
            </div>
        </div>
        </form>
    </div>

    <div class="col-lg-4">
        <div class="dashboard-card">
            <h6 class="fw-bold mb-3">Panduan Kode Modul</h6>
            <div class="text-muted" style="font-size:13px">
                <p>Format kode modul yang disarankan:</p>
                <div class="d-flex flex-column gap-2">
                    <code style="background:var(--soft-primary);color:#461256;padding:4px 10px;border-radius:6px">MOD-MAT-01</code>
                    <code style="background:var(--soft-primary);color:#461256;padding:4px 10px;border-radius:6px">MOD-FIS-05</code>
                    <code style="background:var(--soft-primary);color:#461256;padding:4px 10px;border-radius:6px">MOD-ING-02</code>
                </div>
                <ul class="mt-3 ps-3">
                    <li>Prefix <strong>MOD-</strong> wajib</li>
                    <li>Kode mapel 2–4 huruf kapital</li>
                    <li>Nomor urut 2 digit</li>
                    <li>Harus unik di seluruh sistem</li>
                </ul>
            </div>
        </div>
    </div>
</div>

</div>
@endsection
