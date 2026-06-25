@extends('layouts.app')
@section('title', 'Edit Paket Belajar')
@section('page-title', 'Edit Paket Belajar')

@section('content')
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.course-package.index') }}">Paket Belajar</a></li>
        <li class="breadcrumb-item active">Edit — {{ $coursePackage->nama }}</li>
    </ol>
</nav>

<div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none">
    <div class="d-flex align-items-center gap-3">
        <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
            <i class="bi bi-pencil-square"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-0" style="color:white">Edit Paket Belajar</h5>
            <p class="mb-0" style="font-size:12px;opacity:.8">Perbarui konfigurasi, mata pelajaran, dan guru pengajar</p>
        </div>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger mb-3"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

@php
    $selectedCourseIds   = old('course_ids', $coursePackage->mataPelajaran->pluck('id')->toArray());
    $existingCTMap       = $coursePackage->courseTeachers->groupBy('course_id');
@endphp

<form method="POST" action="{{ route('admin.course-package.update', $coursePackage) }}" id="pkgForm">
    @csrf @method('PUT')

    {{-- CARD 1: Info Dasar --}}
    <div class="dashboard-card mb-4">
        <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
            <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:13px;font-weight:700">1</div>
            <h6 class="fw-bold mb-0">Informasi Dasar Paket</h6>
        </div>
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label fw-semibold">Nama Paket <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                       value="{{ old('nama', $coursePackage->nama) }}" required maxlength="150">
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Jenis Paket <span class="text-danger">*</span></label>
                <select name="jenis" class="form-select" required>
                    @foreach(['reguler'=>'Reguler','intensif'=>'Intensif','privat'=>'Privat (1 Siswa)','online'=>'Online'] as $val=>$label)
                    <option value="{{ $val }}" {{ old('jenis',$coursePackage->jenis)==$val?'selected':'' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Metode Absensi <span class="text-danger">*</span></label>
                <select name="metode_absensi" class="form-select" required>
                    <option value="manual"   {{ old('metode_absensi',$coursePackage->metode_absensi??'manual')=='manual'  ?'selected':'' }}>Manual</option>
                    <option value="otomatis" {{ old('metode_absensi',$coursePackage->metode_absensi??'')=='otomatis'       ?'selected':'' }}>Otomatis</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Tipe Kelas <span class="text-danger">*</span></label>
                <select name="tipe_kelas" class="form-select" required>
                    <option value="offline" {{ old('tipe_kelas',$coursePackage->tipe_kelas??'offline')=='offline'?'selected':'' }}>Offline</option>
                    <option value="online"  {{ old('tipe_kelas',$coursePackage->tipe_kelas??'')=='online'         ?'selected':'' }}>Online</option>
                    <option value="private" {{ old('tipe_kelas',$coursePackage->tipe_kelas??'')=='private'        ?'selected':'' }}>Private</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Harga Dasar (Rp) <span class="text-danger">*</span></label>
                <input type="number" name="harga" class="form-control" value="{{ old('harga', $coursePackage->harga) }}" min="0" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Jumlah Sesi <span class="text-danger">*</span></label>
                <input type="number" name="jumlah_pertemuan" class="form-control" value="{{ old('jumlah_pertemuan', $coursePackage->jumlah_pertemuan) }}" min="1" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Durasi (bulan)</label>
                <input type="number" name="durasi_bulan" class="form-control" value="{{ old('durasi_bulan', $coursePackage->durasi_bulan) }}" min="1">
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
            <div class="col-md-3">
                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="aktif"    {{ old('status',$coursePackage->status)=='aktif'   ?'selected':'' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status',$coursePackage->status)=='nonaktif'?'selected':'' }}>Non Aktif</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end pb-1">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_unggulan" id="isUnggulan" value="1"
                           {{ old('is_unggulan', $coursePackage->is_unggulan) ? 'checked' : '' }}>
                    <label class="form-check-label" for="isUnggulan">Paket Unggulan ⭐</label>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="2">{{ old('deskripsi', $coursePackage->deskripsi) }}</textarea>
            </div>
        </div>
    </div>

    {{-- CARD 2: Mata Pelajaran & Guru per Mapel --}}
    <div class="dashboard-card mb-4">
        <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
            <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:13px;font-weight:700">2</div>
            <h6 class="fw-bold mb-0">Mata Pelajaran & Guru Pengajar</h6>
        </div>
        <p class="text-muted mb-3" style="font-size:13px">
            <i class="bi bi-info-circle me-1"></i>
            Centang mata pelajaran yang termasuk dalam paket ini, lalu pilih guru yang mengajar setiap mata pelajaran.
        </p>

        @if($courses->isEmpty())
            <div class="alert alert-warning mb-0">Belum ada mata pelajaran aktif.</div>
        @else
        <div id="courseTeacherRows">
            @foreach($courses as $c)
            @php
                $isChecked = in_array($c->id, $selectedCourseIds);
                $ctForCourse = $existingCTMap->get($c->id, collect())->pluck('teacher_id')->toArray();
                $oldTeachers = old("course_teachers.{$c->id}", $ctForCourse);
            @endphp
            <div class="mb-3 rounded-3" style="border:1.5px solid {{ $isChecked ? '#c84ddf' : 'var(--card-border)' }};overflow:hidden;transition:.2s" id="card-{{ $c->id }}">
                <div class="d-flex align-items-center gap-3 px-3 py-2" style="background:var(--input-bg);cursor:pointer"
                     onclick="document.getElementById('chk-{{ $c->id }}').click()">
                    <input class="form-check-input course-check" type="checkbox"
                           name="course_ids[]" value="{{ $c->id }}"
                           id="chk-{{ $c->id }}"
                           {{ $isChecked ? 'checked' : '' }}
                           onchange="toggleTeacherSection({{ $c->id }}, this.checked)"
                           onclick="event.stopPropagation()">
                    <div class="flex-fill fw-semibold" style="font-size:14px">
                        <i class="bi bi-book text-primary me-2"></i>{{ $c->nama }}
                    </div>
                    <div id="badge-{{ $c->id }}">
                        @if($isChecked)
                            <span class="badge bg-success-subtle text-success">Dipilih</span>
                        @else
                            <span class="badge bg-secondary-subtle text-muted">Tidak dipilih</span>
                        @endif
                    </div>
                </div>
                <div class="px-3 py-3 teacher-section border-top" id="teachers-{{ $c->id }}"
                     style="{{ $isChecked ? '' : 'display:none' }}">
                    <div class="text-muted mb-2" style="font-size:12px">
                        <i class="bi bi-person-badge me-1 text-primary"></i>
                        Guru yang mengajar <strong>{{ $c->nama }}</strong>:
                    </div>
                    <div class="row g-2">
                        @foreach($teachers as $t)
                        <div class="col-md-4 col-sm-6">
                            <div class="form-check p-2 rounded-2" style="border:1px solid var(--card-border);background:var(--bs-body-bg)">
                                <input class="form-check-input" type="checkbox"
                                       name="course_teachers[{{ $c->id }}][]"
                                       value="{{ $t->id }}"
                                       id="ct_{{ $c->id }}_{{ $t->id }}"
                                       {{ is_array($oldTeachers) && in_array($t->id, $oldTeachers) ? 'checked' : '' }}>
                                <label class="form-check-label" for="ct_{{ $c->id }}_{{ $t->id }}" style="font-size:12px;cursor:pointer">
                                    <span class="fw-semibold">{{ $t->name }}</span>
                                    @if($t->nig)<br><span class="text-muted" style="font-size:11px">NIG: {{ $t->nig }}</span>@endif
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-check-lg me-2"></i>Simpan Perubahan
        </button>
        <a href="{{ route('admin.course-package.show', $coursePackage) }}" class="btn btn-outline-secondary px-4">
            <i class="bi bi-arrow-left me-2"></i>Batal
        </a>
    </div>
</form>
</div>

@push('scripts')
<script>
function toggleTeacherSection(courseId, checked) {
    const section = document.getElementById('teachers-' + courseId);
    const card    = document.getElementById('card-' + courseId);
    const badge   = document.getElementById('badge-' + courseId);
    if (section) section.style.display = checked ? '' : 'none';
    if (card)    card.style.borderColor = checked ? '#c84ddf' : 'var(--card-border)';
    if (badge)   badge.innerHTML = checked
        ? '<span class="badge bg-success-subtle text-success">Dipilih</span>'
        : '<span class="badge bg-secondary-subtle text-muted">Tidak dipilih</span>';
    if (!checked && section) {
        section.querySelectorAll('input[type=checkbox]').forEach(cb => cb.checked = false);
    }
}
</script>
@endpush
@endsection
