@extends('layouts.app')
@section('title', 'Sertifikat Siswa')
@section('page-title', 'Sertifikat Siswa')

@section('content')
<div class="fade-up">

{{-- BREADCRUMB --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.certificates.index') }}">Sertifikat</a></li>
        <li class="breadcrumb-item active">{{ $student->user?->name ?? 'Siswa' }}</li>
    </ol>
</nav>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-3"><i class="bi bi-x-circle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

{{-- STUDENT INFO HEADER --}}
<div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none">
    <div class="d-flex align-items-center gap-3">
        <div style="width:56px;height:56px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0">
            <i class="bi bi-person-fill"></i>
        </div>
        <div class="flex-fill">
            <h5 class="fw-bold mb-0" style="color:white">{{ $student->user?->name ?? 'Siswa' }}</h5>
            <div style="font-size:13px;opacity:.85">
                <span class="me-3"><i class="bi bi-building me-1"></i>{{ $student->branch?->name ?? '—' }}</span>
                <span class="me-3"><i class="bi bi-box-seam me-1"></i>{{ $student->package?->nama ?? 'Belum ada paket' }}</span>
                <span><i class="bi bi-credit-card me-1"></i>NIS: {{ $student->nis ?? '—' }}</span>
            </div>
        </div>
        <a href="{{ route('admin.certificates.index') }}" class="btn btn-sm" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3)">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

<div class="row g-4">

    {{-- LEFT: MATA PELAJARAN + UPLOAD FORM --}}
    <div class="col-lg-7">
        <div class="dashboard-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-journal-bookmark text-primary me-2"></i>Mata Pelajaran yang Diambil</h6>

            @if($courses->isEmpty())
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-journal-x" style="font-size:2rem;display:block;opacity:.3;margin-bottom:.5rem"></i>
                    Siswa ini belum terdaftar di mata pelajaran manapun.
                </div>
            @else
                <div class="row g-2 mb-4">
                    @foreach($courses as $c)
                    <div class="col-6 col-md-4">
                        <div class="p-2 rounded-2 border text-center" style="background:var(--input-bg);border-color:var(--card-border)">
                            <i class="bi bi-book-fill text-primary" style="font-size:1.2rem"></i>
                            <div class="fw-semibold mt-1" style="font-size:12px">{{ $c->nama }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif

            <hr class="my-3">
            <h6 class="fw-bold mb-3"><i class="bi bi-upload text-warning me-2"></i>Upload Sertifikat Baru</h6>

            <form method="POST" action="{{ route('admin.certificates.student.upload', $student->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold" style="font-size:.85rem">Judul Sertifikat <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                               placeholder="cth: Sertifikat Kompetensi Matematika" value="{{ old('judul') }}" required>
                        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" style="font-size:.85rem">Jenis <span class="text-danger">*</span></label>
                        <select name="jenis" class="form-select" required>
                            <option value="kompetensi" {{ old('jenis')=='kompetensi'?'selected':'' }}>Kompetensi</option>
                            <option value="kelulusan"  {{ old('jenis')=='kelulusan' ?'selected':'' }}>Kelulusan</option>
                            <option value="prestasi"   {{ old('jenis')=='prestasi'  ?'selected':'' }}>Prestasi</option>
                            <option value="partisipasi"{{ old('jenis')=='partisipasi'?'selected':'' }}>Partisipasi</option>
                        </select>
                    </div>
                    @if($courses->isNotEmpty())
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size:.85rem">Mata Pelajaran Terkait <span class="text-muted fw-normal">(opsional)</span></label>
                        <select name="course_id" class="form-select">
                            <option value="">— Pilih mata pelajaran —</option>
                            @foreach($courses as $c)
                                <option value="{{ $c->id }}" {{ old('course_id')==$c->id?'selected':'' }}>{{ $c->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.85rem">Tanggal Terbit <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_terbit" class="form-control" value="{{ old('tanggal_terbit', date('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.85rem">Tanggal Expired <span class="text-muted fw-normal">(opsional)</span></label>
                        <input type="date" name="tanggal_expired" class="form-control" value="{{ old('tanggal_expired') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size:.85rem">Diterbitkan Oleh</label>
                        <input type="text" name="diterbitkan_oleh" class="form-control" placeholder="cth: Kepala Cabang / Direktur" value="{{ old('diterbitkan_oleh') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size:.85rem">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="2" placeholder="Keterangan tambahan…">{{ old('deskripsi') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size:.85rem">File Sertifikat <span class="text-muted fw-normal">(PDF / Gambar, maks 10MB)</span></label>
                        <input type="file" name="file_sertifikat" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary fw-semibold px-4">
                            <i class="bi bi-award me-2"></i>Terbitkan Sertifikat
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- RIGHT: EXISTING CERTIFICATES --}}
    <div class="col-lg-5">
        <div class="dashboard-card">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-award-fill text-warning me-2"></i>Sertifikat Diterbitkan
                <span class="badge ms-1" style="background:var(--soft-warning-bg);color:var(--soft-warning-text);font-size:11px">{{ $certificates->count() }}</span>
            </h6>

            @forelse($certificates as $cert)
            @php
                $jenisMap = [
                    'kompetensi' =>['var(--soft-primary-bg)','var(--soft-primary-text)'],
                    'kelulusan'  =>['var(--soft-success-bg)','var(--soft-success-text)'],
                    'prestasi'   =>['var(--soft-warning-bg)','var(--soft-warning-text)'],
                    'partisipasi'=>['var(--soft-info-bg)','var(--soft-info-text)'],
                ];
                $jc = $jenisMap[$cert->jenis] ?? ['var(--soft-muted-bg)','var(--text-muted)'];
            @endphp
            <div class="p-3 mb-2 rounded-3 border" style="background:var(--input-bg);border-color:var(--card-border)">
                <div class="d-flex align-items-start justify-content-between gap-2">
                    <div class="flex-fill" style="min-width:0">
                        <div class="fw-semibold text-truncate" style="font-size:13px">{{ $cert->judul }}</div>
                        <code style="font-size:10px;color:var(--text-muted)">{{ $cert->nomor_sertifikat }}</code>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <span style="background:{{ $jc[0] }};color:{{ $jc[1] }};padding:2px 7px;border-radius:6px;font-size:10px;font-weight:600;text-transform:capitalize">{{ $cert->jenis }}</span>
                            <span class="text-muted" style="font-size:11px">{{ $cert->tanggal_terbit?->format('d M Y') ?? '—' }}</span>
                        </div>
                    </div>
                    <div class="d-flex gap-1 flex-shrink-0">
                        @if($cert->file_sertifikat)
                        <a href="{{ asset('storage/'.$cert->file_sertifikat) }}" target="_blank"
                           class="btn btn-sm btn-outline-success" style="border-radius:7px;font-size:11px" title="Lihat">
                            <i class="bi bi-eye"></i>
                        </a>
                        @endif
                        <button onclick="deleteCert({{ $cert->id }}, '{{ addslashes($cert->judul) }}')"
                                class="btn btn-sm btn-outline-danger" style="border-radius:7px;font-size:11px" title="Hapus">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-4 text-muted">
                <i class="bi bi-award" style="font-size:2rem;display:block;opacity:.25;margin-bottom:.5rem"></i>
                Belum ada sertifikat untuk siswa ini.
            </div>
            @endforelse
        </div>
    </div>

</div>
</div>

@push('scripts')
<script>
function deleteCert(id, judul) {
    if (!confirm('Hapus sertifikat "' + judul + '"?')) return;
    fetch('/admin/certificates/' + id, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    })
    .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
    .then(res => {
        if (res.success) {
            window.showToast && window.showToast(res.message, 'success');
            setTimeout(() => location.reload(), 600);
        } else {
            window.showToast && window.showToast(res.message || 'Gagal menghapus.', 'error');
        }
    })
    .catch(() => window.showToast && window.showToast('Gagal menghubungi server.', 'error'));
}
</script>
@endpush
@endsection
