@extends('layouts.app')
@section('title','Edit Ruangan')
@section('page-title','Edit Ruangan')

@section('content')
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.rooms.index') }}">Fasilitas Ruangan</a></li>
        <li class="breadcrumb-item active">Edit: {{ $room->nama_ruangan }}</li>
    </ol>
</nav>

@if($errors->any())
    <div class="alert alert-danger mb-3">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div class="row g-4">
    <div class="col-lg-7">
        <div class="dashboard-card">
            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:18px">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">Edit Ruangan</h6>
                    <span class="text-muted" style="font-size:13px">Perbarui data ruangan belajar</span>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.rooms.update', $room) }}">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Nama Ruangan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_ruangan"
                               class="form-control @error('nama_ruangan') is-invalid @enderror"
                               value="{{ old('nama_ruangan', $room->nama_ruangan) }}"
                               placeholder="Contoh: Ruang A1 - VIP" required>
                        @error('nama_ruangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kapasitas Maksimal (Siswa) <span class="text-danger">*</span></label>
                        <input type="number" name="kapasitas"
                               class="form-control @error('kapasitas') is-invalid @enderror"
                               value="{{ old('kapasitas', $room->kapasitas) }}"
                               min="1" required>
                        @error('kapasitas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="aktif"       {{ old('status', $room->status)=='aktif'       ? 'selected' : '' }}>Bisa Digunakan</option>
                            <option value="maintenance" {{ old('status', $room->status)=='maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" rows="3"
                                  class="form-control @error('keterangan') is-invalid @enderror"
                                  placeholder="Fasilitas ruangan, kondisi, catatan khusus...">{{ old('keterangan', $room->keterangan) }}</textarea>
                        @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary fw-semibold px-4">
                        <i class="bi bi-arrow-left me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary fw-semibold px-4">
                        <i class="bi bi-floppy me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>
@endsection
