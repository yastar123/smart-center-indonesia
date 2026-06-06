@extends('layouts.app')

@section('title', 'Sertifikat')
@section('page-title', 'Sertifikat')

@section('content')
<div class="fade-up">

    {{-- HEADER BANNER --}}
    <div class="page-header mb-4" style="background:linear-gradient(135deg,#f6af23 0%,#e09000 50%,#b45309 100%);border-radius:20px;padding:2rem 2.5rem;color:#fff;position:relative;overflow:hidden;">
        <div style="position:absolute;top:-30px;right:-30px;width:180px;height:180px;background:rgba(255,255,255,.07);border-radius:50%;"></div>
        <div style="position:absolute;bottom:-50px;right:80px;width:120px;height:120px;background:rgba(255,255,255,.05);border-radius:50%;"></div>
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative;z-index:1;">
            <div>
                <nav aria-label="breadcrumb" class="mb-1">
                    <ol class="breadcrumb mb-0" style="font-size:.8rem;opacity:.8;">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active text-white">Sertifikat</li>
                    </ol>
                </nav>
                <h1 class="mb-1 fw-bold" style="font-size:1.8rem;">Manajemen Sertifikat</h1>
                <p class="mb-0 opacity-75">Terbitkan dan kelola sertifikat kompetensi, kelulusan, dan prestasi siswa</p>
            </div>
            <button class="btn btn-light fw-semibold px-4 py-2" onclick="openCertModal()">
                <i class="bi bi-plus-circle me-2"></i>Terbitkan Sertifikat
            </button>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="row g-3 mb-4">
        @php
            $statCards = [
                ['label'=>'Total Sertifikat','value'=>$stats['total'],'icon'=>'bi-award','color'=>'#f6af23','bg'=>'rgba(245,158,11,.12)'],
                ['label'=>'Kompetensi','value'=>$stats['kompetensi'],'icon'=>'bi-patch-check','color'=>'#c84ddf','bg'=>'rgba(200,77,223,.12)'],
                ['label'=>'Kelulusan','value'=>$stats['kelulusan'],'icon'=>'bi-mortarboard','color'=>'#10b981','bg'=>'rgba(16,185,129,.12)'],
                ['label'=>'Prestasi','value'=>$stats['prestasi'],'icon'=>'bi-trophy','color'=>'#ef4444','bg'=>'rgba(239,68,68,.12)'],
            ];
        @endphp
        @foreach($statCards as $sc)
        <div class="col-6 col-lg-3">
            <div class="card border-0 h-100" style="border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,.06);">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div style="width:48px;height:48px;border-radius:14px;background:{{ $sc['bg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi {{ $sc['icon'] }}" style="font-size:1.3rem;color:{{ $sc['color'] }};"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-4 count-up">{{ $sc['value'] }}</div>
                        <div class="text-muted" style="font-size:.78rem;">{{ $sc['label'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- FILTERS --}}
    <div class="card border-0 mb-3" style="border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,.06);">
        <div class="card-body p-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Cari judul atau nomor sertifikat…" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <select name="jenis" class="form-select">
                        <option value="">Semua Jenis</option>
                        <option value="kompetensi"   {{ request('jenis')=='kompetensi'?'selected':'' }}>Kompetensi</option>
                        <option value="kelulusan"    {{ request('jenis')=='kelulusan'?'selected':'' }}>Kelulusan</option>
                        <option value="prestasi"     {{ request('jenis')=='prestasi'?'selected':'' }}>Prestasi</option>
                        <option value="partisipasi"  {{ request('jenis')=='partisipasi'?'selected':'' }}>Partisipasi</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select name="cabang_id" class="form-select">
                        <option value="">Semua Cabang</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}" {{ request('cabang_id')==$b->id?'selected':'' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                    @if(request()->hasAny(['search','jenis','cabang_id']))
                        <a href="{{ route('admin.certificates.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="card border-0" style="border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,.06);">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background:rgba(245,158,11,.06);">
                        <tr>
                            <th class="px-4 py-3" style="font-size:.78rem;font-weight:700;text-transform:uppercase;">#</th>
                            <th class="py-3" style="font-size:.78rem;font-weight:700;text-transform:uppercase;">No. Sertifikat</th>
                            <th class="py-3" style="font-size:.78rem;font-weight:700;text-transform:uppercase;">Judul</th>
                            <th class="py-3 d-none d-md-table-cell" style="font-size:.78rem;font-weight:700;text-transform:uppercase;">Penerima</th>
                            <th class="py-3 d-none d-md-table-cell" style="font-size:.78rem;font-weight:700;text-transform:uppercase;">Jenis</th>
                            <th class="py-3 d-none d-lg-table-cell" style="font-size:.78rem;font-weight:700;text-transform:uppercase;">Tgl Terbit</th>
                            <th class="py-3 d-none d-lg-table-cell" style="font-size:.78rem;font-weight:700;text-transform:uppercase;">Tgl Expired</th>
                            <th class="py-3 text-end pe-4" style="font-size:.78rem;font-weight:700;text-transform:uppercase;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($certificates as $i => $cert)
                        <tr>
                            <td class="px-4 text-muted" style="font-size:.85rem;">{{ $certificates->firstItem() + $i }}</td>
                            <td>
                                <span class="fw-semibold" style="font-size:.82rem;font-family:monospace;background:rgba(245,158,11,.1);color:#b45309;padding:.2em .6em;border-radius:6px;">{{ $cert->nomor_sertifikat }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold" style="font-size:.9rem;">{{ $cert->judul }}</div>
                                @if($cert->diterbitkan_oleh)
                                <div class="text-muted" style="font-size:.78rem;">Oleh: {{ $cert->diterbitkan_oleh }}</div>
                                @endif
                            </td>
                            <td class="d-none d-md-table-cell">
                                @if($cert->siswa?->user)
                                <div class="d-flex align-items-center gap-2">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($cert->siswa->user->name) }}&size=28&background=f59e0b&color=fff&rounded=true" alt="" style="width:28px;height:28px;border-radius:8px;">
                                    <span style="font-size:.85rem;">{{ $cert->siswa->user->name }}</span>
                                </div>
                                @else
                                <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="d-none d-md-table-cell">
                                @php
                                    $jenisColors = [
                                        'kompetensi'  => ['#c84ddf','rgba(200,77,223,.15)'],
                                        'kelulusan'   => ['#10b981','rgba(16,185,129,.15)'],
                                        'prestasi'    => ['#ef4444','rgba(239,68,68,.15)'],
                                        'partisipasi' => ['#68117e','rgba(104,17,126,.15)'],
                                    ];
                                    $jc = $jenisColors[$cert->jenis] ?? ['#6b7280','rgba(107,114,128,.15)'];
                                @endphp
                                <span class="badge rounded-pill" style="background:{{ $jc[1] }};color:{{ $jc[0] }};font-size:.75rem;font-weight:600;padding:.35em .75em;text-transform:capitalize;">{{ $cert->jenis }}</span>
                            </td>
                            <td class="d-none d-lg-table-cell" style="font-size:.85rem;">
                                {{ $cert->tanggal_terbit ? $cert->tanggal_terbit->format('d M Y') : '—' }}
                            </td>
                            <td class="d-none d-lg-table-cell" style="font-size:.85rem;">
                                @if($cert->tanggal_expired)
                                    @if($cert->tanggal_expired->isPast())
                                        <span style="color:#ef4444;">{{ $cert->tanggal_expired->format('d M Y') }}</span>
                                    @else
                                        {{ $cert->tanggal_expired->format('d M Y') }}
                                    @endif
                                @else
                                    <span class="badge rounded-pill" style="background:rgba(16,185,129,.15);color:#059669;font-size:.73rem;">Seumur Hidup</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex gap-1 justify-content-end">
                                    <button class="btn btn-sm btn-light" onclick="editCert({{ $cert->id }})" style="border-radius:8px;width:32px;height:32px;padding:0;" title="Edit">
                                        <i class="bi bi-pencil" style="font-size:.78rem;"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light text-danger" onclick="deleteCert({{ $cert->id }}, '{{ addslashes($cert->judul) }}')" style="border-radius:8px;width:32px;height:32px;padding:0;" title="Hapus">
                                        <i class="bi bi-trash" style="font-size:.78rem;"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div style="opacity:.5;">
                                    <i class="bi bi-award" style="font-size:2.5rem;display:block;margin-bottom:.5rem;color:#f6af23;"></i>
                                    <div class="fw-semibold">Belum ada sertifikat</div>
                                    <small class="text-muted">Terbitkan sertifikat pertama untuk siswa Anda</small>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($certificates->hasPages())
            <div class="px-4 py-3 border-top">{{ $certificates->links() }}</div>
            @endif
        </div>
    </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="certModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0" style="border-radius:20px;box-shadow:0 20px 60px rgba(0,0,0,.15);">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h5 class="modal-title fw-bold" id="certModalTitle">Terbitkan Sertifikat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <form id="certForm">
                    @csrf
                    <input type="hidden" id="certId">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Siswa <span class="text-danger">*</span></label>
                            <select class="form-select" id="siswa_id" required>
                                <option value="">-- Pilih Siswa --</option>
                                @foreach($students as $st)
                                <option value="{{ $st->id }}">{{ $st->user?->name ?? 'Siswa #'.$st->id }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Cabang <span class="text-danger">*</span></label>
                            <select class="form-select" id="cert_cabang_id" required>
                                <option value="">-- Pilih Cabang --</option>
                                @foreach($branches as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Judul Sertifikat <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="cert_judul" placeholder="cth: Sertifikat Kompetensi Matematika Level A" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Jenis <span class="text-danger">*</span></label>
                            <select class="form-select" id="cert_jenis" required>
                                <option value="kompetensi">Kompetensi</option>
                                <option value="kelulusan">Kelulusan</option>
                                <option value="prestasi">Prestasi</option>
                                <option value="partisipasi">Partisipasi</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Tanggal Terbit <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_terbit" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Tanggal Expired <span class="text-muted">(opsional)</span></label>
                            <input type="date" class="form-control" id="tanggal_expired">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Diterbitkan Oleh</label>
                            <input type="text" class="form-control" id="diterbitkan_oleh" placeholder="cth: Kepala Cabang / Direktur Smart Center Indonesia">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Deskripsi</label>
                            <textarea class="form-control" id="cert_deskripsi" rows="2" placeholder="Keterangan tambahan tentang sertifikat ini…"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-2 gap-2">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn px-4 fw-semibold" onclick="saveCert()" id="certSaveBtn" style="background:linear-gradient(135deg,#f6af23,#b45309);color:#fff;">
                    <i class="bi bi-award me-2"></i>Terbitkan
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const certModal = new bootstrap.Modal(document.getElementById('certModal'));

function openCertModal() {
    document.getElementById('certModalTitle').textContent = 'Terbitkan Sertifikat';
    document.getElementById('certId').value = '';
    document.getElementById('certForm').reset();
    document.getElementById('tanggal_terbit').value = new Date().toISOString().split('T')[0];
    certModal.show();
}

function editCert(id) {
    document.getElementById('certModalTitle').textContent = 'Edit Sertifikat';
    document.getElementById('certSaveBtn').disabled = true;
    fetch(`/admin/certificates/${id}`)
        .then(r => r.json())
        .then(d => {
            document.getElementById('certId').value = d.id;
            document.getElementById('siswa_id').value = d.siswa_id || '';
            document.getElementById('cert_cabang_id').value = d.cabang_id || '';
            document.getElementById('cert_judul').value = d.judul || '';
            document.getElementById('cert_jenis').value = d.jenis || 'kompetensi';
            document.getElementById('tanggal_terbit').value = d.tanggal_terbit ? d.tanggal_terbit.split('T')[0] : '';
            document.getElementById('tanggal_expired').value = d.tanggal_expired ? d.tanggal_expired.split('T')[0] : '';
            document.getElementById('diterbitkan_oleh').value = d.diterbitkan_oleh || '';
            document.getElementById('cert_deskripsi').value = d.deskripsi || '';
            document.getElementById('certSaveBtn').disabled = false;
            document.getElementById('certSaveBtn').innerHTML = '<i class="bi bi-check-circle me-2"></i>Simpan';
            certModal.show();
        });
}

function saveCert() {
    const id = document.getElementById('certId').value;
    const isEdit = id !== '';
    const btn = document.getElementById('certSaveBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan…';

    fetch(isEdit ? `/admin/certificates/${id}` : '/admin/certificates', {
        method: isEdit ? 'PUT' : 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify({
            siswa_id:         document.getElementById('siswa_id').value,
            cabang_id:        document.getElementById('cert_cabang_id').value,
            judul:            document.getElementById('cert_judul').value,
            jenis:            document.getElementById('cert_jenis').value,
            tanggal_terbit:   document.getElementById('tanggal_terbit').value,
            tanggal_expired:  document.getElementById('tanggal_expired').value || null,
            diterbitkan_oleh: document.getElementById('diterbitkan_oleh').value,
            deskripsi:        document.getElementById('cert_deskripsi').value,
        })
    })
    .then(r => r.json())
    .then(res => {
        btn.disabled = false;
        btn.innerHTML = isEdit ? '<i class="bi bi-check-circle me-2"></i>Simpan' : '<i class="bi bi-award me-2"></i>Terbitkan';
        if (res.success) {
            certModal.hide();
            window.showToast && window.showToast(res.message, 'success');
            setTimeout(() => location.reload(), 600);
        } else {
            const errs = res.errors ? Object.values(res.errors).flat().join(' | ') : res.message;
            window.showToast && window.showToast(errs || 'Terjadi kesalahan.', 'error');
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = isEdit ? '<i class="bi bi-check-circle me-2"></i>Simpan' : '<i class="bi bi-award me-2"></i>Terbitkan';
        window.showToast && window.showToast('Gagal menghubungi server.', 'error');
    });
}

function deleteCert(id, judul) {
    Swal.fire({
        title: 'Hapus Sertifikat?',
        html: `Sertifikat <strong>"${judul}"</strong> akan dihapus.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
    }).then(r => {
        if (!r.isConfirmed) return;
        fetch(`/admin/certificates/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                window.showToast && window.showToast(res.message, 'success');
                setTimeout(() => location.reload(), 600);
            }
        });
    });
}
</script>
@endpush
@endsection
