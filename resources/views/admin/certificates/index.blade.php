@extends('layouts.app')

@section('title', 'Sertifikat')
@section('page-title', 'Sertifikat')

@section('content')
<div class="fade-up">

    {{-- HEADER BANNER --}}
    <div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
        <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
        <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
        <div class="row align-items-center g-3" style="position:relative">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0" style="color:white">Manajemen Sertifikat</h5>
                        <span style="font-size:12px;opacity:.8">Terbitkan dan kelola sertifikat kompetensi, kelulusan, dan prestasi siswa</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <button onclick="openCertModal()" class="btn fw-semibold px-4" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                    <i class="bi bi-plus-lg me-2"></i>Terbitkan Sertifikat
                </button>
            </div>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="row g-3 mb-4">
        @php
            $statCards = [
                ['label'=>'Total Sertifikat','value'=>$stats['total'],     'icon'=>'bi-award-fill',     'topColor'=>'#f6af23','textColor'=>'text-warning','iconBg'=>'bg-warning-soft'],
                ['label'=>'Kompetensi',      'value'=>$stats['kompetensi'],'icon'=>'bi-patch-check-fill','topColor'=>'#c84ddf','textColor'=>'text-primary','iconBg'=>'bg-primary-soft'],
                ['label'=>'Kelulusan',       'value'=>$stats['kelulusan'], 'icon'=>'bi-mortarboard-fill','topColor'=>'#10b981','textColor'=>'text-success','iconBg'=>'bg-success-soft'],
                ['label'=>'Prestasi',        'value'=>$stats['prestasi'],  'icon'=>'bi-trophy-fill',    'topColor'=>'#ef4444','textColor'=>'text-danger', 'iconBg'=>'bg-danger-soft'],
            ];
        @endphp
        @foreach($statCards as $i => $sc)
        <div class="col-6 col-lg-3 fade-up" style="animation-delay:{{ $i * 0.05 }}s">
            <div class="stat-card" style="border-top:3px solid {{ $sc['topColor'] }}">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-title">{{ $sc['label'] }}</div>
                        <div class="stat-value {{ $sc['textColor'] }} count-up" data-target="{{ $sc['value'] }}">{{ $sc['value'] }}</div>
                    </div>
                    <div class="stat-icon {{ $sc['iconBg'] }}" style="color:white">
                        <i class="bi {{ $sc['icon'] }}"></i>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- FILTERS --}}
    <div class="dashboard-card mb-4 fade-up">
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

    {{-- TABLE --}}
    <div class="dashboard-card fade-up">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-modern">
                        <tr>
                            <th class="px-4">#</th>
                            <th>No. Sertifikat</th>
                            <th>Judul</th>
                            <th class="d-none d-md-table-cell">Penerima</th>
                            <th class="d-none d-md-table-cell">Jenis</th>
                            <th class="d-none d-lg-table-cell">Tgl Terbit</th>
                            <th class="d-none d-lg-table-cell">Tgl Expired</th>
                            <th class="text-end pe-4">Aksi</th>
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
            <div class="mt-4 pt-3 d-flex justify-content-center" style="border-top:1px solid var(--card-border)">{{ $certificates->links() }}</div>
            @endif
    </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="certModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0" style="border-radius:20px;box-shadow:0 20px 60px rgba(0,0,0,.15);">
            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#f6af23,#b45309);border-radius:20px 20px 0 0">
                <div>
                    <h5 class="modal-title fw-bold mb-0 text-white" id="certModalTitle"><i class="bi bi-award me-2"></i>Terbitkan Sertifikat</h5>
                    <div style="font-size:12px;opacity:.75;color:white;margin-top:3px">Isi data sertifikat yang akan diterbitkan</div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
                            <label class="form-label fw-semibold d-flex align-items-center justify-content-between" style="font-size:.85rem;">
                                <span>Tanggal Expired <span class="text-muted fw-normal">(opsional)</span></span>
                                <span class="d-flex align-items-center gap-1" style="font-size:.78rem;font-weight:400">
                                    <input type="checkbox" id="seumurHidup" class="form-check-input" style="margin-top:0">
                                    <label for="seumurHidup" class="mb-0" style="cursor:pointer">Seumur Hidup</label>
                                </span>
                            </label>
                            <input type="date" class="form-control" id="tanggal_expired">
                            <div class="invalid-feedback" id="expiredError">Tanggal expired harus setelah tanggal terbit.</div>
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

// ---- Date validation helpers ----
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('tanggal_terbit').addEventListener('change', function() {
        const exp = document.getElementById('tanggal_expired');
        if (this.value) {
            const d = new Date(this.value); d.setDate(d.getDate() + 1);
            exp.min = d.toISOString().split('T')[0];
            if (exp.value && exp.value <= this.value) { exp.value = ''; }
        }
        exp.classList.remove('is-invalid');
    });
    document.getElementById('seumurHidup').addEventListener('change', function() {
        const exp = document.getElementById('tanggal_expired');
        exp.disabled = this.checked;
        if (this.checked) { exp.value = ''; exp.classList.remove('is-invalid'); }
    });
});

function openCertModal() {
    document.getElementById('certModalTitle').innerHTML = '<i class="bi bi-award me-2"></i>Terbitkan Sertifikat';
    document.getElementById('certId').value = '';
    document.getElementById('certForm').reset();
    document.getElementById('seumurHidup').checked = false;
    document.getElementById('tanggal_expired').disabled = false;
    document.getElementById('tanggal_terbit').value = new Date().toISOString().split('T')[0];
    certModal.show();
}

function editCert(id) {
    document.getElementById('certModalTitle').innerHTML = '<i class="bi bi-pencil-square me-2"></i>Edit Sertifikat';
    document.getElementById('seumurHidup').checked = false;
    document.getElementById('tanggal_expired').disabled = false;
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

    // Validate expiry date
    const terbit  = document.getElementById('tanggal_terbit').value;
    const expired = document.getElementById('tanggal_expired').value;
    const expEl   = document.getElementById('tanggal_expired');
    expEl.classList.remove('is-invalid');
    if (!document.getElementById('seumurHidup').checked && expired && terbit && expired <= terbit) {
        expEl.classList.add('is-invalid');
        expEl.focus();
        return;
    }

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
    confirmAction(`Hapus sertifikat "${judul}"? Data tidak dapat dikembalikan.`, function() {
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
