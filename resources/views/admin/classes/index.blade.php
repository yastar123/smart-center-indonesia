@extends('layouts.app')

@section('title', 'Manajemen Kelas')
@section('page-title', 'Kelas')

@section('content')
<div class="fade-up">

    {{-- HEADER BANNER --}}
    <div class="page-header mb-4" style="background:linear-gradient(135deg,#8b5cf6 0%,#c84ddf 50%,#6d28d9 100%);border-radius:20px;padding:2rem 2.5rem;color:#fff;position:relative;overflow:hidden;">
        <div style="position:absolute;top:-30px;right:-30px;width:180px;height:180px;background:rgba(255,255,255,.07);border-radius:50%;"></div>
        <div style="position:absolute;bottom:-50px;right:80px;width:120px;height:120px;background:rgba(255,255,255,.05);border-radius:50%;"></div>
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative;z-index:1;">
            <div>
                <nav aria-label="breadcrumb" class="mb-1">
                    <ol class="breadcrumb mb-0" style="font-size:.8rem;opacity:.8;">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active text-white">Kelas</li>
                    </ol>
                </nav>
                <h1 class="mb-1 fw-bold" style="font-size:1.8rem;">Manajemen Kelas</h1>
                <p class="mb-0 opacity-75">Atur kelas belajar, kapasitas, dan penugasan guru di semua cabang</p>
            </div>
            <button class="btn btn-light fw-semibold px-4 py-2" onclick="openModal()">
                <i class="bi bi-plus-circle me-2"></i>Tambah Kelas
            </button>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="row g-3 mb-4">
        @php
            $statCards = [
                ['label'=>'Total Kelas','value'=>$stats['total'],'icon'=>'bi-building','color'=>'#8b5cf6','bg'=>'rgba(139,92,246,.12)'],
                ['label'=>'Kelas Aktif','value'=>$stats['aktif'],'icon'=>'bi-check-circle','color'=>'#10b981','bg'=>'rgba(16,185,129,.12)'],
                ['label'=>'Kelas Online','value'=>$stats['online'],'icon'=>'bi-wifi','color'=>'#c84ddf','bg'=>'rgba(200,77,223,.12)'],
                ['label'=>'Kelas Offline','value'=>$stats['offline'],'icon'=>'bi-geo-alt','color'=>'#f6af23','bg'=>'rgba(245,158,11,.12)'],
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
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama kelas…" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <select name="jenis" class="form-select">
                        <option value="">Semua Jenis</option>
                        <option value="online"  {{ request('jenis')=='online'?'selected':'' }}>Online</option>
                        <option value="offline" {{ request('jenis')=='offline'?'selected':'' }}>Offline</option>
                        <option value="hybrid"  {{ request('jenis')=='hybrid'?'selected':'' }}>Hybrid</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="aktif"    {{ request('status')=='aktif'?'selected':'' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status')=='nonaktif'?'selected':'' }}>Nonaktif</option>
                        <option value="penuh"    {{ request('status')=='penuh'?'selected':'' }}>Penuh</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="cabang_id" class="form-select">
                        <option value="">Semua Cabang</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}" {{ request('cabang_id')==$b->id?'selected':'' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                    @if(request()->hasAny(['search','jenis','status','cabang_id']))
                        <a href="{{ route('admin.classes.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x"></i></a>
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
                    <thead style="background:rgba(139,92,246,.06);">
                        <tr>
                            <th class="px-4 py-3" style="font-size:.78rem;font-weight:700;text-transform:uppercase;">#</th>
                            <th class="py-3" style="font-size:.78rem;font-weight:700;text-transform:uppercase;">Nama Kelas</th>
                            <th class="py-3 d-none d-md-table-cell" style="font-size:.78rem;font-weight:700;text-transform:uppercase;">Mata Pelajaran</th>
                            <th class="py-3 d-none d-md-table-cell" style="font-size:.78rem;font-weight:700;text-transform:uppercase;">Guru</th>
                            <th class="py-3 d-none d-lg-table-cell" style="font-size:.78rem;font-weight:700;text-transform:uppercase;">Kapasitas</th>
                            <th class="py-3" style="font-size:.78rem;font-weight:700;text-transform:uppercase;">Jenis</th>
                            <th class="py-3" style="font-size:.78rem;font-weight:700;text-transform:uppercase;">Status</th>
                            <th class="py-3 text-end pe-4" style="font-size:.78rem;font-weight:700;text-transform:uppercase;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $i => $class)
                        <tr>
                            <td class="px-4 text-muted" style="font-size:.85rem;">{{ $classes->firstItem() + $i }}</td>
                            <td>
                                <div class="fw-semibold" style="font-size:.9rem;">{{ $class->nama_kelas }}</div>
                                <div class="text-muted" style="font-size:.78rem;">{{ $class->tahunAkademik?->nama ?? '—' }}</div>
                            </td>
                            <td class="d-none d-md-table-cell" style="font-size:.85rem;">
                                {{ $class->mataPelajaran?->nama ?? '—' }}
                            </td>
                            <td class="d-none d-md-table-cell" style="font-size:.85rem;">
                                {{ $class->guru?->name ?? '—' }}
                            </td>
                            <td class="d-none d-lg-table-cell">
                                @if($class->kapasitas)
                                <div class="d-flex align-items-center gap-2">
                                    <span style="font-size:.85rem;font-weight:600;">{{ $class->kapasitas }}</span>
                                    <small class="text-muted">siswa</small>
                                </div>
                                @else
                                <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @php $jeниsBadge = ['online'=>['#c84ddf','rgba(200,77,223,.15)'],'offline'=>['#f6af23','rgba(245,158,11,.15)'],'hybrid'=>['#8b5cf6','rgba(139,92,246,.15)']] @endphp
                                @php $j = $jeниsBadge[$class->jenis] ?? ['#6b7280','rgba(107,114,128,.15)'] @endphp
                                <span class="badge rounded-pill" style="background:{{ $j[1] }};color:{{ $j[0] }};font-size:.75rem;font-weight:600;padding:.35em .75em;text-transform:capitalize;">{{ $class->jenis }}</span>
                            </td>
                            <td>
                                @php $sb = ['aktif'=>['#10b981','rgba(16,185,129,.15)'],'nonaktif'=>['#ef4444','rgba(239,68,68,.15)'],'penuh'=>['#f6af23','rgba(245,158,11,.15)']] @endphp
                                @php $s = $sb[$class->status] ?? ['#6b7280','rgba(107,114,128,.15)'] @endphp
                                <span class="badge rounded-pill" style="background:{{ $s[1] }};color:{{ $s[0] }};font-size:.75rem;font-weight:600;padding:.35em .75em;text-transform:capitalize;">{{ $class->status }}</span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex gap-1 justify-content-end">
                                    <button class="btn btn-sm btn-light" onclick="editClass({{ $class->id }})" style="border-radius:8px;width:32px;height:32px;padding:0;">
                                        <i class="bi bi-pencil" style="font-size:.78rem;"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light text-danger" onclick="deleteClass({{ $class->id }}, '{{ addslashes($class->nama_kelas) }}')" style="border-radius:8px;width:32px;height:32px;padding:0;">
                                        <i class="bi bi-trash" style="font-size:.78rem;"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div style="opacity:.5;">
                                    <i class="bi bi-building" style="font-size:2.5rem;display:block;margin-bottom:.5rem;color:#8b5cf6;"></i>
                                    <div class="fw-semibold">Belum ada kelas</div>
                                    <small class="text-muted">Tambahkan kelas pertama Anda</small>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($classes->hasPages())
            <div class="px-4 py-3 border-top">{{ $classes->links() }}</div>
            @endif
        </div>
    </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="classModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0" style="border-radius:20px;box-shadow:0 20px 60px rgba(0,0,0,.15);">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h5 class="modal-title fw-bold" id="classModalTitle">Tambah Kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <form id="classForm">
                    @csrf
                    <input type="hidden" id="classId">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Nama Kelas <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_kelas" placeholder="cth: Kelas Matematika Intensif A" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Cabang <span class="text-danger">*</span></label>
                            <select class="form-select" id="cls_cabang_id" required>
                                <option value="">-- Pilih Cabang --</option>
                                @foreach($branches as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Mata Pelajaran</label>
                            <select class="form-select" id="mata_pelajaran_id">
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach($courses as $c)
                                <option value="{{ $c->id }}">{{ $c->kode }} — {{ $c->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Guru Pengajar</label>
                            <select class="form-select" id="guru_id">
                                <option value="">-- Pilih Guru --</option>
                                @foreach($teachers as $t)
                                <option value="{{ $t->id }}">{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Tahun Akademik</label>
                            <select class="form-select" id="tahun_akademik_id">
                                <option value="">-- Pilih Tahun Akademik --</option>
                                @foreach($tahunAkademik as $ta)
                                <option value="{{ $ta->id }}">{{ $ta->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Kapasitas</label>
                            <input type="number" class="form-control" id="kapasitas" placeholder="30" min="1" max="200">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Jenis <span class="text-danger">*</span></label>
                            <select class="form-select" id="jenis" required onchange="toggleZoom()">
                                <option value="offline">Offline</option>
                                <option value="online">Online</option>
                                <option value="hybrid">Hybrid</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="cls_status" required>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                                <option value="penuh">Penuh</option>
                            </select>
                        </div>
                        <div class="col-md-6" id="ruanganField">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Ruangan</label>
                            <input type="text" class="form-control" id="ruangan" placeholder="cth: R-101, Ruang A">
                        </div>
                        <div class="col-md-6" id="zoomField" style="display:none;">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Link Zoom</label>
                            <input type="url" class="form-control" id="link_zoom" placeholder="https://zoom.us/j/...">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-2 gap-2">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn px-4 fw-semibold" onclick="saveClass()" id="classSaveBtn" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9);color:#fff;">
                    <i class="bi bi-check-circle me-2"></i>Simpan
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const classModal = new bootstrap.Modal(document.getElementById('classModal'));

function toggleZoom() {
    const jenis = document.getElementById('jenis').value;
    document.getElementById('zoomField').style.display = (jenis === 'online' || jenis === 'hybrid') ? 'block' : 'none';
    document.getElementById('ruanganField').style.display = (jenis === 'offline' || jenis === 'hybrid') ? 'block' : 'none';
}

function openModal() {
    document.getElementById('classModalTitle').textContent = 'Tambah Kelas';
    document.getElementById('classId').value = '';
    document.getElementById('classForm').reset();
    toggleZoom();
    classModal.show();
}

function editClass(id) {
    document.getElementById('classModalTitle').textContent = 'Edit Kelas';
    document.getElementById('classSaveBtn').disabled = true;
    fetch(`/admin/classes/${id}`)
        .then(r => r.json())
        .then(d => {
            document.getElementById('classId').value = d.id;
            document.getElementById('nama_kelas').value = d.nama_kelas || '';
            document.getElementById('cls_cabang_id').value = d.cabang_id || '';
            document.getElementById('mata_pelajaran_id').value = d.mata_pelajaran_id || '';
            document.getElementById('guru_id').value = d.guru_id || '';
            document.getElementById('tahun_akademik_id').value = d.tahun_akademik_id || '';
            document.getElementById('kapasitas').value = d.kapasitas || '';
            document.getElementById('jenis').value = d.jenis || 'offline';
            document.getElementById('cls_status').value = d.status || 'aktif';
            document.getElementById('ruangan').value = d.ruangan || '';
            document.getElementById('link_zoom').value = d.link_zoom || '';
            toggleZoom();
            document.getElementById('classSaveBtn').disabled = false;
            classModal.show();
        });
}

function saveClass() {
    const id = document.getElementById('classId').value;
    const isEdit = id !== '';
    const btn = document.getElementById('classSaveBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan…';

    fetch(isEdit ? `/admin/classes/${id}` : '/admin/classes', {
        method: isEdit ? 'PUT' : 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify({
            nama_kelas:        document.getElementById('nama_kelas').value,
            cabang_id:         document.getElementById('cls_cabang_id').value,
            mata_pelajaran_id: document.getElementById('mata_pelajaran_id').value || null,
            guru_id:           document.getElementById('guru_id').value || null,
            tahun_akademik_id: document.getElementById('tahun_akademik_id').value || null,
            kapasitas:         document.getElementById('kapasitas').value || null,
            jenis:             document.getElementById('jenis').value,
            status:            document.getElementById('cls_status').value,
            ruangan:           document.getElementById('ruangan').value,
            link_zoom:         document.getElementById('link_zoom').value || null,
        })
    })
    .then(r => r.json())
    .then(res => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Simpan';
        if (res.success) {
            classModal.hide();
            window.showToast && window.showToast('success', res.message);
            setTimeout(() => location.reload(), 600);
        } else {
            const errs = res.errors ? Object.values(res.errors).flat().join(' | ') : res.message;
            window.showToast && window.showToast('error', errs || 'Terjadi kesalahan.');
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Simpan';
        window.showToast && window.showToast('error', 'Gagal menghubungi server.');
    });
}

function deleteClass(id, nama) {
    Swal.fire({
        title: 'Hapus Kelas?',
        html: `Kelas <strong>"${nama}"</strong> akan dihapus.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
    }).then(r => {
        if (!r.isConfirmed) return;
        fetch(`/admin/classes/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                window.showToast && window.showToast('success', res.message);
                setTimeout(() => location.reload(), 600);
            }
        });
    });
}
</script>
@endpush
@endsection
