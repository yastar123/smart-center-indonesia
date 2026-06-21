@extends('layouts.app')

@section('title', 'Edit Guru')
@section('page-title', 'Edit Guru')

@section('content')
<div class="fade-up" style="margin:0;border-radius:0;box-shadow:none;background:transparent;padding:0;">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.teachers.index') }}">Data Guru</a></li>
            <li class="breadcrumb-item active">Edit Guru</li>
        </ol>
    </nav>

    <div class="w-100">
        <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
            <div style="width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:22px;flex-shrink:0">
                <i class="bi bi-pencil-square"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0">Edit Data Guru</h5>
                <p class="text-muted mb-0" style="font-size:13px">Perbarui data guru dan akun login pada halaman penuh ini</p>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.teachers.update', $teacher) }}" method="POST" enctype="multipart/form-data" style="width:100%;">
            @csrf
            @method('PUT')

            <div class="row g-4 align-items-start">
                <div class="col-lg-3 text-center">
                    <div class="mb-3">
                        <img id="photoPreview"
                             src="{{ $teacher->photo ? asset('storage/'.$teacher->photo) : 'https://ui-avatars.com/api/?name='.urlencode($teacher->name).'&background=68117e&color=fff&size=140' }}"
                             class="rounded-circle"
                             width="140"
                             height="140"
                             style="object-fit:cover;border:3px solid #c84ddf;box-shadow:0 10px 24px rgba(104,17,126,.18)">
                    </div>
                    <label class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-camera me-1"></i>Pilih Foto
                        <input type="file" name="photo" id="photoInput" class="d-none" accept="image/*">
                    </label>
                    <div class="text-muted mt-2" style="font-size:12px">Opsional</div>
                </div>

                <div class="col-lg-9">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name', $teacher->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">NIG <span class="text-danger">*</span></label>
                            <input type="text" name="nig" class="form-control form-control-sm" value="{{ old('nig', $teacher->nig) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select form-select-sm" required>
                                <option value="">Pilih...</option>
                                <option value="L" {{ old('gender', $teacher->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender', $teacher->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Tanggal Lahir</label>
                            <input type="date" name="birth_date" class="form-control form-control-sm" value="{{ old('birth_date', $teacher->birth_date) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Pendidikan</label>
                            <select name="education" class="form-select form-select-sm">
                                <option value="">Pilih...</option>
                                <option value="D3" {{ old('education', $teacher->education) == 'D3' ? 'selected' : '' }}>D3</option>
                                <option value="S1" {{ old('education', $teacher->education) == 'S1' ? 'selected' : '' }}>S1</option>
                                <option value="S2" {{ old('education', $teacher->education) == 'S2' ? 'selected' : '' }}>S2</option>
                                <option value="S3" {{ old('education', $teacher->education) == 'S3' ? 'selected' : '' }}>S3</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Cabang</label>
                            <select name="branch_id" class="form-select form-select-sm">
                                <option value="">Pilih Cabang</option>
                                <option value="pusat" {{ old('branch_id', $teacher->branch_id ?? 'pusat') == 'pusat' ? 'selected' : '' }}>Pusat</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ old('branch_id', $teacher->branch_id) == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">No. HP</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">+62</span>
                                <input type="text" name="phone" class="form-control" placeholder="8xxxxxxxxxx" value="{{ old('phone', $teacher->phone) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Jenis Guru</label>
                            <select name="jenis_guru" class="form-select form-select-sm">
                                <option value="">Pilih...</option>
                                <option value="kontrak" {{ old('jenis_guru', $teacher->jenis_guru) == 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                                <option value="freelance" {{ old('jenis_guru', $teacher->jenis_guru) == 'freelance' ? 'selected' : '' }}>Freelance</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Upload CV</label>
                            <input type="file" name="cv" class="form-control form-control-sm" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                            @if($teacher->cv_path)
                                <div class="mt-1">
                                    <a href="{{ asset('storage/'.$teacher->cv_path) }}" target="_blank" class="text-decoration-none small">Lihat CV saat ini</a>
                                </div>
                            @endif
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Alamat</label>
                            <textarea name="address" class="form-control form-control-sm" rows="2">{{ old('address', $teacher->address) }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Email Akun</label>
                            <input type="email" name="email" class="form-control form-control-sm" value="{{ old('email', $teacher->email ?? $teacher->user?->email) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Password</label>
                            <input type="password" name="password" class="form-control form-control-sm" minlength="8">
                            <div class="text-muted mt-1" style="font-size:11px">Kosongkan jika tidak ingin mengubah password</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-success px-4">
                    <i class="bi bi-check-lg me-2"></i>Simpan Perubahan
                </button>
                <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary px-4">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('photoInput')?.addEventListener('change', function () {
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('photoPreview').src = e.target.result;
        };
        reader.readAsDataURL(this.files[0]);
    }
});
</script>
@endpush
