@extends('layouts.app')
@section('title', 'Tambah Paket Belajar')
@section('page-title', 'Tambah Paket Belajar')

@section('content')
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('owner.course-package.index') }}">Paket Belajar</a></li>
        <li class="breadcrumb-item active">Tambah Paket</li>
    </ol>
</nav>

<div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none">
    <div class="d-flex align-items-center gap-3">
        <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
            <i class="bi bi-plus-circle"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-0" style="color:white">Tambah Paket Belajar Baru</h5>
            <p class="mb-0" style="font-size:12px;opacity:.8">Konfigurasi paket, mata pelajaran, dan guru pengajar per-mapel</p>
        </div>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger mb-3"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif
@if(session('success'))
    <div class="alert alert-success mb-3"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('owner.course-package.store') }}" id="pkgForm">
    @csrf

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
                       placeholder="cth. Intensif UTBK 12 SMA" value="{{ old('nama') }}" required maxlength="150">
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Harga Dasar (Rp) <span class="text-danger">*</span></label>
                <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror"
                       value="{{ old('harga', 0) }}" min="0" required>
                @error('harga')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Jumlah Sesi <span class="text-danger">*</span></label>
                <input type="number" name="jumlah_pertemuan" class="form-control @error('jumlah_pertemuan') is-invalid @enderror"
                       value="{{ old('jumlah_pertemuan', 8) }}" min="1" required>
                @error('jumlah_pertemuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
            <div class="col-md-3">
                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="aktif"    {{ old('status','aktif')=='aktif'   ?'selected':'' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status')=='nonaktif'         ?'selected':'' }}>Non Aktif</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="2" placeholder="Deskripsi singkat paket belajar…">{{ old('deskripsi') }}</textarea>
            </div>
        </div>
    </div>

    {{-- CARD 2: Mata Pelajaran --}}
    <div class="dashboard-card mb-4">
        <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
            <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:13px;font-weight:700">2</div>
            <h6 class="fw-bold mb-0">Mata Pelajaran</h6>
        </div>
        <p class="text-muted mb-3" style="font-size:13px">
            <i class="bi bi-info-circle me-1"></i>
            Klik kategori untuk melihat mata pelajaran, lalu centang yang termasuk dalam paket ini.
        </p>

        @if($courses->isEmpty())
            <div class="alert alert-warning mb-0">
                Belum ada mata pelajaran aktif. <a href="{{ route('owner.subject.index') }}">Buat mata pelajaran</a> terlebih dahulu.
            </div>
        @else
        @php
            $jenisLabels = [
                'komputer'  => 'Kursus Komputer',
                'bahasa'    => 'Kursus Bahasa Asing',
                'mapel'     => 'Mata Pelajaran',
                'kedinasan' => 'Program Kedinasan',
                'akpol'     => 'AKPOL / AKMIL / BINTARA',
                'cpns'      => 'CPNS',
                'bumn'      => 'BUMN',
                'lainnya'   => 'Lainnya',
            ];
            $oldCourses = old('course_ids', []);
        @endphp
        <div id="courseTeacherRows">
            @foreach($coursesGrouped as $jenis => $groupCourses)
            @php $gKey = preg_replace('/[^a-z0-9]/','', $jenis); @endphp
            <div class="mb-1 px-1 py-1 rounded-2" style="background:linear-gradient(135deg,#f8f5ff,#f3eeff);border:1px solid #e9d5ff;cursor:pointer"
                 onclick="toggleGroupCourses('{{ $gKey }}')">
                <div class="d-flex align-items-center justify-content-between" style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:#68117e;padding:6px 10px 4px">
                    <span><i class="bi bi-folder2-open me-1"></i>{{ $jenisLabels[$jenis] ?? ucfirst($jenis) }}</span>
                    <i class="bi bi-chevron-down" id="group-icon-{{ $gKey }}" style="transition:.2s"></i>
                </div>
            </div>
            <div id="group-courses-{{ $gKey }}" class="mb-3" style="display:none">
                @foreach($groupCourses as $c)
                <div class="mb-2 rounded-3" style="border:1.5px solid var(--card-border);overflow:hidden;transition:.2s" id="card-{{ $c->id }}">
                    <div class="d-flex align-items-center gap-3 px-3 py-2" style="background:var(--input-bg);cursor:pointer"
                         onclick="document.getElementById('chk-{{ $c->id }}').click()">
                        <input class="form-check-input course-check" type="checkbox"
                               name="course_ids[]" value="{{ $c->id }}"
                               id="chk-{{ $c->id }}"
                               {{ in_array($c->id, $oldCourses) ? 'checked' : '' }}
                               onchange="toggleCourseCheck({{ $c->id }}, this.checked)"
                               onclick="event.stopPropagation()">
                        <div class="flex-fill fw-semibold" style="font-size:14px">
                            <i class="bi bi-book text-primary me-2"></i>{{ $c->nama }}
                        </div>
                        <div style="font-size:12px" id="badge-{{ $c->id }}">
                            @if(in_array($c->id, $oldCourses))
                                <span class="badge bg-success-subtle text-success">Dipilih</span>
                            @else
                                <span class="badge bg-secondary-subtle text-muted">Tidak dipilih</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-check-lg me-2"></i>Simpan Paket
        </button>
        <a href="{{ route('owner.course-package.index') }}" class="btn btn-outline-secondary px-4">
            <i class="bi bi-arrow-left me-2"></i>Batal
        </a>
    </div>
</form>
</div>

@push('scripts')
<script>
function toggleGroupCourses(key) {
    const el   = document.getElementById('group-courses-' + key);
    const icon = document.getElementById('group-icon-' + key);
    if (!el) return;
    const hidden = el.style.display === 'none';
    el.style.display = hidden ? '' : 'none';
    if (icon) icon.style.transform = hidden ? 'rotate(180deg)' : '';
}

function toggleCourseCheck(courseId, checked) {
    const card  = document.getElementById('card-' + courseId);
    const badge = document.getElementById('badge-' + courseId);
    if (card)  card.style.borderColor = checked ? '#c84ddf' : 'var(--card-border)';
    if (badge) badge.innerHTML = checked
        ? '<span class="badge bg-success-subtle text-success">Dipilih</span>'
        : '<span class="badge bg-secondary-subtle text-muted">Tidak dipilih</span>';
}

// On load: expand groups that have pre-checked items (old input)
document.querySelectorAll('.course-check').forEach(cb => {
    if (!cb.checked) return;
    toggleCourseCheck(cb.value, true);
    const group = cb.closest('[id^="group-courses-"]');
    if (group) {
        group.style.display = '';
        const key  = group.id.replace('group-courses-', '');
        const icon = document.getElementById('group-icon-' + key);
        if (icon) icon.style.transform = 'rotate(180deg)';
    }
});
</script>
@endpush
@endsection
