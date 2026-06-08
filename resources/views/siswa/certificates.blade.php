@extends('layouts.app')
@section('title','Sertifikat Saya')
@section('page-title','Sertifikat Saya')

@section('content')
<div class="fade-up">

{{-- HEADER --}}
<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0"><i class="bi bi-award"></i></div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Sertifikat & Piagam Saya</h5>
                    <span style="font-size:12px;opacity:.85">Lihat, unduh, dan upload sertifikat kompetensi Anda</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <button onclick="openUploadModal()" class="btn fw-semibold px-4" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px">
                <i class="bi bi-upload me-2"></i>Upload Sertifikat
            </button>
        </div>
    </div>
</div>

{{-- STATS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3"><div class="stat-card"><div class="d-flex justify-content-between"><div><div class="stat-title">Total</div><div class="stat-value count-up" data-target="{{ $certificates->count() }}">{{ $certificates->count() }}</div></div><div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-award"></i></div></div></div></div>
    <div class="col-6 col-md-3"><div class="stat-card"><div class="d-flex justify-content-between"><div><div class="stat-title">Kompetensi</div><div class="stat-value">{{ $certificates->where('jenis','kompetensi')->count() }}</div></div><div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-patch-check"></i></div></div></div></div>
    <div class="col-6 col-md-3"><div class="stat-card"><div class="d-flex justify-content-between"><div><div class="stat-title">Kelulusan</div><div class="stat-value">{{ $certificates->where('jenis','kelulusan')->count() }}</div></div><div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-mortarboard"></i></div></div></div></div>
    <div class="col-6 col-md-3"><div class="stat-card"><div class="d-flex justify-content-between"><div><div class="stat-title">Prestasi</div><div class="stat-value">{{ $certificates->whereIn('jenis',['prestasi','partisipasi'])->count() }}</div></div><div class="stat-icon bg-danger-soft" style="color:white"><i class="bi bi-trophy"></i></div></div></div></div>
</div>

{{-- GRID --}}
@if($certificates->isEmpty())
<div class="dashboard-card text-center py-5">
    <i class="bi bi-award" style="font-size:3rem;color:#cbd5e1;display:block;margin-bottom:16px"></i>
    <div class="fw-semibold mb-2" style="font-size:16px;color:var(--text-primary)">Belum ada sertifikat</div>
    <p class="text-muted mb-4">Sertifikat yang diterbitkan admin atau yang Anda upload akan tampil di sini.</p>
    <button onclick="openUploadModal()" class="btn btn-primary px-4"><i class="bi bi-upload me-2"></i>Upload Sertifikat Pertama</button>
</div>
@else
<div class="row g-3">
    @foreach($certificates as $cert)
    @php
        $colors = ['kompetensi'=>['#c84ddf','rgba(200,77,223,.1)','bi-patch-check'], 'kelulusan'=>['#10b981','rgba(16,185,129,.1)','bi-mortarboard'], 'prestasi'=>['#f59e0b','rgba(245,158,11,.1)','bi-trophy'], 'partisipasi'=>['#6366f1','rgba(99,102,241,.1)','bi-star']];
        $c = $colors[$cert->jenis] ?? ['#64748b','rgba(100,116,139,.1)','bi-award'];
    @endphp
    <div class="col-md-6 col-lg-4">
        <div class="dashboard-card h-100" style="border-top:4px solid {{ $c[0] }};position:relative">
            {{-- Jenis badge --}}
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div style="width:44px;height:44px;border-radius:12px;background:{{ $c[1] }};display:flex;align-items:center;justify-content:center">
                    <i class="bi {{ $c[2] }}" style="font-size:20px;color:{{ $c[0] }}"></i>
                </div>
                <span class="badge" style="background:{{ $c[1] }};color:{{ $c[0] }};border:1px solid {{ $c[0] }}44;text-transform:capitalize">{{ $cert->jenis }}</span>
            </div>
            <h6 class="fw-bold mb-1">{{ $cert->judul }}</h6>
            <p class="text-muted mb-3" style="font-size:12px;line-height:1.5">{{ $cert->deskripsi ?: 'Sertifikat ' . $cert->jenis . ' dari Smart Center Indonesia' }}</p>
            <div class="d-flex align-items-center gap-2 mb-3" style="font-size:12px;color:var(--text-muted)">
                <i class="bi bi-calendar3"></i>
                <span>{{ $cert->tanggal_terbit ? $cert->tanggal_terbit->format('d M Y') : '–' }}</span>
                @if($cert->tanggal_expired)
                <span class="ms-2"><i class="bi bi-clock me-1"></i>s/d {{ $cert->tanggal_expired->format('d M Y') }}</span>
                @endif
            </div>
            <div class="mt-auto pt-2 border-top d-flex justify-content-between align-items-center">
                <code style="font-size:10px;color:var(--text-muted)">{{ $cert->nomor_sertifikat }}</code>
                <a href="{{ route('siswa.certificates.download', $cert) }}" class="btn btn-sm btn-primary" target="_blank">
                    <i class="bi bi-download me-1"></i>Unduh
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

</div>

{{-- MODAL UPLOAD --}}
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:20px;border:none">
            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#260632,#461256,#c84ddf);border-radius:20px 20px 0 0">
                <h5 class="modal-title fw-bold text-white"><i class="bi bi-upload me-2"></i>Upload Sertifikat</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label fw-semibold">Judul Sertifikat <span class="text-danger">*</span></label><input type="text" name="judul" class="form-control" required placeholder="Contoh: Sertifikat OSN Matematika 2025"></div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                        <select name="jenis" class="form-select" required>
                            <option value="prestasi">Prestasi</option>
                            <option value="partisipasi">Partisipasi</option>
                            <option value="kompetensi">Kompetensi</option>
                            <option value="kelulusan">Kelulusan</option>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label fw-semibold">Tanggal Terbit <span class="text-danger">*</span></label><input type="date" name="tanggal_terbit" class="form-control" required value="{{ date('Y-m-d') }}"></div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">File Sertifikat <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" required accept=".pdf,.jpg,.jpeg,.png">
                        <div class="form-text">Format: PDF, JPG, PNG. Maks 10 MB</div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4" id="uploadBtn"><i class="bi bi-upload me-2"></i>Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openUploadModal() {
    document.getElementById('uploadForm').reset();
    new bootstrap.Modal(document.getElementById('uploadModal')).show();
}

document.getElementById('uploadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const fd = new FormData(this);
    document.getElementById('uploadBtn').disabled = true;
    document.getElementById('uploadBtn').innerHTML = '<div class="spinner-border spinner-border-sm me-2"></div>Mengupload...';
    fetch(`{{ route('siswa.certificates.upload') }}`, { method: 'POST', body: fd, headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json()).then(d => {
            document.getElementById('uploadBtn').disabled = false;
            document.getElementById('uploadBtn').innerHTML = '<i class="bi bi-upload me-2"></i>Upload';
            showToast(d.message, d.success ? 'success' : 'error');
            if (d.success) { bootstrap.Modal.getInstance(document.getElementById('uploadModal')).hide(); setTimeout(() => location.reload(), 1200); }
        }).catch(() => {
            document.getElementById('uploadBtn').disabled = false;
            document.getElementById('uploadBtn').innerHTML = '<i class="bi bi-upload me-2"></i>Upload';
            showToast('Terjadi kesalahan', 'error');
        });
});
</script>
@endpush
