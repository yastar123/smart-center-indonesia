@extends('layouts.app')

@section('title', 'Edit Paket Belajar')
@section('page-title', 'Edit Paket Belajar')

@section('content')
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.course-package.index') }}">Paket Belajar</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.course-package.show', $coursePackage) }}">{{ $coursePackage->nama }}</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
</nav>

<div class="dashboard-card" style="max-width:780px;margin:0 auto">
    <div class="d-flex align-items-center gap-3 mb-4">
        <div style="width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:22px;flex-shrink:0">
            <i class="bi bi-pencil-square"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-0">Edit Paket Belajar</h5>
            <p class="text-muted mb-0" style="font-size:13px">Perbarui konfigurasi paket kursus</p>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.course-package.update', $coursePackage) }}">
        @csrf @method('PUT')

        <div class="row g-3">
            <div class="col-12">
                <label class="form-label fw-semibold">Nama Paket <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                       value="{{ old('nama', $coursePackage->nama) }}" required maxlength="150">
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Jenis Paket <span class="text-danger">*</span></label>
                <select name="jenis" class="form-select" required id="jenisSelect">
                    @foreach(['reguler','intensif','privat','online'] as $j)
                    <option value="{{ $j }}" {{ old('jenis',$coursePackage->jenis)==$j?'selected':'' }}>{{ ucfirst($j) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Jumlah Sesi <span class="text-danger">*</span></label>
                <input type="number" name="jumlah_pertemuan" class="form-control"
                       value="{{ old('jumlah_pertemuan', $coursePackage->jumlah_pertemuan) }}" min="1" required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Harga Dasar (Rp) <span class="text-danger">*</span></label>
                <input type="number" name="harga" class="form-control"
                       value="{{ old('harga', $coursePackage->harga) }}" min="0" required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Guru Pengampu</label>
                <select name="guru_id" class="form-select">
                    <option value="">— Pilih Guru —</option>
                    @foreach($teachers as $t)
                        <option value="{{ $t->id }}" {{ old('guru_id', $coursePackage->guru_id)==$t->id?'selected':'' }}>{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Cabang</label>
                <select name="cabang_id" class="form-select">
                    <option value="">Semua Cabang</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ old('cabang_id',$coursePackage->cabang_id)==$b->id?'selected':'' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="aktif"    {{ old('status',$coursePackage->status)=='aktif'   ?'selected':'' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status',$coursePackage->status)=='nonaktif'?'selected':'' }}>Draft</option>
                </select>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Mata Pelajaran</label>
                @php $selected = $coursePackage->mataPelajaran->pluck('id')->toArray(); @endphp
                <div class="p-3 rounded-3" style="background:var(--input-bg);border:1.5px solid var(--card-border)">
                    <div class="row g-2">
                        @foreach($courses as $c)
                        <div class="col-6 col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="course_ids[]"
                                       value="{{ $c->id }}" id="cp_course_{{ $c->id }}"
                                       {{ in_array($c->id, old('course_ids', $selected)) ? 'checked' : '' }}>
                                <label class="form-check-label" for="cp_course_{{ $c->id }}" style="font-size:13px">{{ $c->nama }}</label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if($courses->isEmpty())
                        <div class="text-muted" style="font-size:13px">Belum ada mata pelajaran aktif.</div>
                    @endif
                </div>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $coursePackage->deskripsi) }}</textarea>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4 pt-3 border-top">
            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-2"></i>Simpan Perubahan</button>
            <a href="{{ route('admin.course-package.show', $coursePackage) }}" class="btn btn-outline-secondary px-4"><i class="bi bi-arrow-left me-2"></i>Batal</a>
        </div>
    </form>
</div>

</div>
@endsection
