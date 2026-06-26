@extends('layouts.app')

@section('title', 'Tambah Mata Pelajaran')
@section('page-title', 'Tambah Mata Pelajaran')

@section('content')
<div class="fade-up">

{{-- BREADCRUMB --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('owner.subject.index') }}">Mata Pelajaran</a></li>
        <li class="breadcrumb-item active">Tambah Mapel</li>
    </ol>
</nav>

<div class="dashboard-card" style="max-width:700px;margin:0 auto">
    <div class="d-flex align-items-center gap-3 mb-4">
        <div style="width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:22px;flex-shrink:0">
            <i class="bi bi-plus-circle"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-0">Tambah Mata Pelajaran Baru</h5>
            <p class="text-muted mb-0" style="font-size:13px">Isi data mata pelajaran yang akan ditambahkan ke sistem</p>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('owner.subject.store') }}">
        @csrf

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Kode Mapel <span class="text-danger">*</span></label>
                <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror"
                       placeholder="MTK-01" value="{{ old('kode') }}" required maxlength="20">
                @error('kode')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-8">
                <label class="form-label fw-semibold">Nama Pelajaran <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                       placeholder="cth. Matematika Wajib" value="{{ old('nama') }}" required maxlength="100">
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Jenis Kursus <span class="text-danger">*</span></label>
                <select name="jenis_kursus" class="form-select @error('jenis_kursus') is-invalid @enderror" required>
                    <option value="">-- Pilih Jenis --</option>
                    @foreach([
                        'komputer'  => 'Kursus Komputer',
                        'bahasa'    => 'Kursus Bahasa Asing',
                        'mapel'     => 'Mata Pelajaran',
                        'kedinasan' => 'Program Kedinasan',
                        'akpol'     => 'AKPOL / AKMIL / BINTARA',
                        'cpns'      => 'CPNS',
                        'bumn'      => 'BUMN',
                    ] as $val => $label)
                        <option value="{{ $val }}" {{ old('jenis_kursus')==$val?'selected':'' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('jenis_kursus')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Cabang</label>
                <select name="cabang_id" class="form-select @error('cabang_id') is-invalid @enderror">
                    <option value="">Semua Cabang</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ old('cabang_id')==$b->id?'selected':'' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
                @error('cabang_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                          rows="3" placeholder="Deskripsi singkat mata pelajaran…">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="aktif"    {{ old('status','aktif')=='aktif'   ?'selected':'' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status')=='nonaktif'?'selected':'' }}>Tidak Aktif</option>
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="d-flex gap-2 mt-4 pt-3 border-top">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-lg me-2"></i>Simpan Mata Pelajaran
            </button>
            <a href="{{ route('owner.subject.index') }}" class="btn btn-outline-secondary px-4">
                <i class="bi bi-arrow-left me-2"></i>Batal & Kembali
            </a>
        </div>
    </form>
</div>

</div>
@endsection
