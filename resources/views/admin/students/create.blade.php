@extends('layouts.app')
@section('title', 'Tambah Siswa')
@section('page-title', 'Tambah Siswa')

@section('content')
<div class="fade-up" style="margin:0;border-radius:0;box-shadow:none;background:transparent;padding:0;">
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data" style="width:100%;">
        @csrf
        <div class="row g-4 align-items-start">
            <div class="col-md-4 text-center" style="padding-right:24px;">
                <div class="mb-3">
                    <img id="photoPreview" src="https://ui-avatars.com/api/?name=Siswa&background=68117e&color=fff&size=120" class="rounded-circle" width="120" height="120" style="object-fit:cover;border:3px solid #c84ddf">
                </div>
                <label class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-camera me-1"></i>Pilih Foto
                    <input type="file" name="photo" class="d-none" accept="image/*" onchange="previewPhoto(this)">
                </label>

                @php
                    $studentPackages = isset($student) && $student->relationLoaded('package')
                        ? collect([$student->package])->filter()
                        : collect();
                @endphp

                <div class="mt-4 p-3 rounded-3 border text-start" style="background:var(--input-bg);border-color:var(--card-border);">
                    <div class="fw-semibold small mb-2">Paket yang Diambil</div>
                    @if($studentPackages->isNotEmpty())
                        @foreach($studentPackages as $pkg)
                            <div class="small text-muted mb-1">
                                <strong>{{ $pkg->nama ?? '-' }}</strong>
                            </div>
                            @if(!empty($pkg->deskripsi))
                                <div class="small text-muted mb-1">{{ $pkg->deskripsi }}</div>
                            @endif
                            <div class="small text-muted">
                                @if(!empty($pkg->cabang->name))
                                    {{ $pkg->cabang->name }}
                                @endif
                                @if(!empty($pkg->guru->name))
                                    · {{ $pkg->guru->name }}
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="small text-muted mb-0">Siswa ini belum memiliki paket</div>
                    @endif
                </div>
            </div>
            <div class="col-md-8" style="padding-left:8px;">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select name="gender" class="form-select form-select-sm" required>
                            <option value="">Pilih...</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Tempat Lahir</label>
                        <input type="text" name="birth_place" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Tanggal Lahir</label>
                        <input type="date" name="birth_date" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Cabang <span class="text-danger">*</span></label>
                        <select name="branch_id" class="form-select form-select-sm" required>
                            <option value="">Pilih Cabang</option>
                            <option value="pusat">Pusat</option>
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Kategori Peserta Didik</label>
                        <select name="kategori_peserta_didik" class="form-select form-select-sm">
                            <option value="">Pilih kategori...</option>
                            <option value="Pra Sekolah (PAUD/TK)">Pra Sekolah (PAUD/TK)</option>
                            <option value="Sekolah Dasar (SD)">Sekolah Dasar (SD)</option>
                            <option value="Sekolah Menengah Pertama (SMP)">Sekolah Menengah Pertama (SMP)</option>
                            <option value="Sekolah Menengah Atas/Kejuruan (SMA/SMK)">Sekolah Menengah Atas/Kejuruan (SMA/SMK)</option>
                            <option value="Mahasiswa">Mahasiswa</option>
                            <option value="Umum">Umum</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">No. HP Siswa</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">+62</span>
                            <input type="text" name="phone" class="form-control" placeholder="8xxxxxxxxxx">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label small fw-semibold">Alamat</label>
                        <textarea name="address" class="form-control form-control-sm" rows="2"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Email Akun <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control form-control-sm" minlength="8" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nama Orang Tua / Wali</label>
                        <input type="text" name="parent_name" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">HP Orang Tua</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">+62</span>
                            <input type="text" name="parent_phone" class="form-control" placeholder="8xxxxxxxxxx">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 pt-3 border-top text-end" style="border-color:var(--card-border);">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-lg me-1"></i>Simpan Siswa
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('photoPreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
