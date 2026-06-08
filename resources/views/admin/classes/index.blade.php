@extends('layouts.app')

@section('title', 'Manajemen Kelas')
@section('page-title', 'Kelas')

@section('content')
<div>

    {{-- HEADER BANNER --}}
    <div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
        <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
        <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
        <div class="row align-items-center g-3" style="position:relative">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                        <i class="bi bi-building-fill"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0" style="color:white">Manajemen Kelas</h5>
                        <span style="font-size:12px;opacity:.8">Atur kelas belajar, kapasitas, dan penugasan guru di semua cabang</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <button onclick="openModal()" class="btn fw-semibold px-4" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Kelas
                </button>
            </div>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="row g-3 mb-4">
        @php
            $statCards = [
                ['label'=>'Total Kelas',  'value'=>$stats['total'],  'icon'=>'bi-building',      'color'=>'text-primary', 'topColor'=>'#c84ddf', 'iconBg'=>'bg-primary-soft'],
                ['label'=>'Kelas Aktif',  'value'=>$stats['aktif'],  'icon'=>'bi-check-circle',  'color'=>'text-success', 'topColor'=>'#10b981', 'iconBg'=>'bg-success-soft'],
                ['label'=>'Kelas Online', 'value'=>$stats['online'], 'icon'=>'bi-wifi',           'color'=>'text-primary', 'topColor'=>'#c84ddf', 'iconBg'=>'bg-primary-soft'],
                ['label'=>'Kelas Offline','value'=>$stats['offline'],'icon'=>'bi-geo-alt-fill',  'color'=>'text-warning', 'topColor'=>'#f6af23', 'iconBg'=>'bg-warning-soft'],
            ];
        @endphp
        @foreach($statCards as $i => $sc)
        <div class="col-6 col-lg-3 fade-up" style="animation-delay:{{ $i * 0.05 }}s">
            <div class="stat-card" style="border-top:3px solid {{ $sc['topColor'] }}">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-title">{{ $sc['label'] }}</div>
                        <div class="stat-value {{ $sc['color'] }} count-up" data-target="{{ $sc['value'] }}">{{ $sc['value'] }}</div>
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
        <div>
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
    <div class="dashboard-card fade-up">
        <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-modern">
                        <tr>
                            <th class="px-4">#</th>
                            <th>Nama Kelas</th>
                            <th class="d-none d-md-table-cell">Mata Pelajaran</th>
                            <th class="d-none d-md-table-cell">Guru</th>
                            <th class="d-none d-lg-table-cell">Kapasitas</th>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
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
                                @php $jeниsBadge = ['online'=>['#c84ddf','rgba(200,77,223,.15)'],'offline'=>['#f6af23','rgba(245,158,11,.15)'],'hybrid'=>['#68117e','rgba(104,17,126,.15)']] @endphp
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
                                    <button class="btn btn-sm btn-act-edit" onclick="editClass({{ $class->id }})">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-act-del" onclick="deleteClass({{ $class->id }}, '{{ addslashes($class->nama_kelas) }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div style="opacity:.5;">
                                    <i class="bi bi-building text-primary" style="font-size:2.5rem;display:block;margin-bottom:.5rem"></i>
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
            <div class="mt-4 pt-3 d-flex justify-content-center" style="border-top:1px solid var(--card-border)">{{ $classes->links() }}</div>
            @endif
    </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="classModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0" style="border-radius:20px;box-shadow:0 20px 60px rgba(0,0,0,.15);">
            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#68117e,#c84ddf);border-radius:20px 20px 0 0">
                <div>
                    <h5 class="modal-title fw-bold mb-0 text-white" id="classModalTitle"><i class="bi bi-building me-2"></i>Tambah Kelas</h5>
                    <div style="font-size:12px;opacity:.75;color:white;margin-top:3px">Isi data kelas belajar di bawah ini</div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
                                <option value="pusat">Pusat</option>
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
                <button type="button" class="btn px-4 fw-semibold" onclick="saveClass()" id="classSaveBtn" style="background:linear-gradient(135deg,#68117e,#461256);color:#fff;">
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
        .then(r => { if (!r.ok) throw new Error('HTTP '+r.status); return r.json(); })
        .then(d => {
            document.getElementById('classId').value = d.id;
            document.getElementById('nama_kelas').value = d.nama_kelas || '';
            const cabangVal = (d.cabang_id === null) ? 'pusat' : (d.cabang_id || '');
            document.getElementById('cls_cabang_id').value = cabangVal;
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
        }).catch(() => {
            document.getElementById('classSaveBtn').disabled = false;
            window.showToast && window.showToast('Gagal memuat data kelas.', 'error');
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
            window.showToast && window.showToast(res.message, 'success');
            setTimeout(() => location.reload(), 600);
        } else {
            const errs = res.errors ? Object.values(res.errors).flat().join(' | ') : res.message;
            window.showToast && window.showToast(errs || 'Terjadi kesalahan.', 'error');
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Simpan';
        window.showToast && window.showToast('Gagal menghubungi server.', 'error');
    });
}

function deleteClass(id, nama) {
    confirmAction(`Hapus kelas "${nama}"? Data tidak dapat dikembalikan.`, function() {
        fetch(`/admin/classes/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        })
        .then(r => { if (!r.ok) throw new Error('HTTP '+r.status); return r.json(); })
        .then(res => {
            if (res.success) {
                window.showToast && window.showToast(res.message, 'success');
                setTimeout(() => location.reload(), 600);
            } else {
                window.showToast && window.showToast(res.message || 'Gagal menghapus kelas.', 'error');
            }
        }).catch(() => {
            window.showToast && window.showToast('Gagal menghubungi server.', 'error');
        });
    });
}
</script>
@endpush
@endsection
