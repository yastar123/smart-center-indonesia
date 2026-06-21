@extends('layouts.app')

@section('title', 'Tambah Paket Belajar')
@section('page-title', 'Tambah Paket Belajar')

@section('content')
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.course-package.index') }}">Paket Belajar</a></li>
        <li class="breadcrumb-item active">Tambah Paket</li>
    </ol>
</nav>

<div class="w-100">
    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
        <div style="width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:22px;flex-shrink:0">
            <i class="bi bi-plus-circle"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-0">Tambah Paket Belajar Baru</h5>
            <p class="text-muted mb-0" style="font-size:13px">Konfigurasi paket kursus, harga, dan mata pelajaran</p>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.course-package.store') }}">
        @csrf

        <div class="row g-3">
            <div class="col-12">
                <label class="form-label fw-semibold">Nama Paket <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                       placeholder="cth. Intensif UTBK 12 SMA" value="{{ old('nama') }}" required maxlength="150">
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Jenis Paket <span class="text-danger">*</span></label>
                <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required id="jenisSelect">
                    <option value="">Pilih jenis…</option>
                    <option value="reguler"  {{ old('jenis')=='reguler' ?'selected':'' }}>Reguler</option>
                    <option value="intensif" {{ old('jenis')=='intensif'?'selected':'' }}>Intensif</option>
                    <option value="privat"   {{ old('jenis')=='privat'  ?'selected':'' }}>Privat (1 Siswa)</option>
                    <option value="online"   {{ old('jenis')=='online'  ?'selected':'' }}>Online</option>
                </select>
                @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Jumlah Sesi <span class="text-danger">*</span></label>
                <input type="number" name="jumlah_pertemuan" class="form-control @error('jumlah_pertemuan') is-invalid @enderror"
                       value="{{ old('jumlah_pertemuan', 8) }}" min="1" required>
                @error('jumlah_pertemuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Metode Absensi <span class="text-danger">*</span></label>
                <select name="metode_absensi" class="form-select @error('metode_absensi') is-invalid @enderror" required>
                    <option value="manual" {{ old('metode_absensi', 'manual') == 'manual' ? 'selected' : '' }}>Manual</option>
                    <option value="otomatis" {{ old('metode_absensi') == 'otomatis' ? 'selected' : '' }}>Otomatis</option>
                </select>
                @error('metode_absensi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Tipe Kelas <span class="text-danger">*</span></label>
                <select name="tipe_kelas" class="form-select @error('tipe_kelas') is-invalid @enderror" required>
                    <option value="offline" {{ old('tipe_kelas', 'offline') == 'offline' ? 'selected' : '' }}>Offline</option>
                    <option value="online" {{ old('tipe_kelas') == 'online' ? 'selected' : '' }}>Online</option>
                    <option value="private" {{ old('tipe_kelas') == 'private' ? 'selected' : '' }}>Private</option>
                </select>
                @error('tipe_kelas')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Harga Dasar (Rp) <span class="text-danger">*</span></label>
                <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror"
                       value="{{ old('harga', 0) }}" min="0" required>
                @error('harga')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Cabang</label>
                <select name="cabang_id" class="form-select">
                    <option value="">Semua Cabang</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ old('cabang_id')==$b->id?'selected':'' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Non Aktif</option>
                </select>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Mata Pelajaran</label>
                <div class="p-3 rounded-3" style="background:var(--input-bg);border:1.5px solid var(--card-border)">
                    <div class="row g-2">
                        @foreach($courses as $c)
                        <div class="col-6 col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="course_ids[]"
                                       value="{{ $c->id }}" id="cp_course_{{ $c->id }}"
                                       {{ in_array($c->id, old('course_ids', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="cp_course_{{ $c->id }}" style="font-size:13px">
                                    {{ $c->nama }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if($courses->isEmpty())
                        <div class="text-muted" style="font-size:13px">Belum ada mata pelajaran aktif.</div>
                    @endif
                </div>
                <div class="text-muted mt-1" style="font-size:11px">Centang satu atau lebih mata pelajaran yang termasuk dalam paket ini.</div>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi paket belajar…">{{ old('deskripsi') }}</textarea>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4 pt-3 border-top">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-lg me-2"></i>Simpan Paket
            </button>
            <a href="{{ route('admin.course-package.index') }}" class="btn btn-outline-secondary px-4">
                <i class="bi bi-arrow-left me-2"></i>Batal & Kembali
            </a>
        </div>
    </form>
</div>

</div>
@endsection
