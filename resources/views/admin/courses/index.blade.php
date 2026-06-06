@extends('layouts.app')

@section('title', 'Mata Pelajaran')
@section('page-title', 'Mata Pelajaran')

@section('content')
<div class="fade-up">

    {{-- HEADER BANNER --}}
    <div class="page-header mb-4" style="background:linear-gradient(135deg,#10b981 0%,#059669 50%,#047857 100%);border-radius:20px;padding:2rem 2.5rem;color:#fff;position:relative;overflow:hidden;">
        <div style="position:absolute;top:-30px;right:-30px;width:180px;height:180px;background:rgba(255,255,255,.07);border-radius:50%;"></div>
        <div style="position:absolute;bottom:-50px;right:80px;width:120px;height:120px;background:rgba(255,255,255,.05);border-radius:50%;"></div>
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative;z-index:1;">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0" style="font-size:.8rem;opacity:.8;">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white text-decoration-none">Dashboard</a></li>
                            <li class="breadcrumb-item active text-white">Mata Pelajaran</li>
                        </ol>
                    </nav>
                </div>
                <h1 class="mb-1 fw-bold" style="font-size:1.8rem;">Mata Pelajaran</h1>
                <p class="mb-0 opacity-75">Kelola semua mata pelajaran dan subjek yang tersedia di semua cabang</p>
            </div>
            <button class="btn btn-light fw-semibold px-4 py-2" onclick="openModal()">
                <i class="bi bi-plus-circle me-2"></i>Tambah Mata Pelajaran
            </button>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="row g-3 mb-4">
        @php
            $statCards = [
                ['label'=>'Total Mapel','value'=>$stats['total'],'icon'=>'bi-book','color'=>'#10b981','bg'=>'rgba(16,185,129,.12)'],
                ['label'=>'Mapel Aktif','value'=>$stats['aktif'],'icon'=>'bi-check-circle','color'=>'#c84ddf','bg'=>'rgba(200,77,223,.12)'],
                ['label'=>'Tidak Aktif','value'=>$stats['nonaktif'],'icon'=>'bi-x-circle','color'=>'#ef4444','bg'=>'rgba(239,68,68,.12)'],
            ];
        @endphp
        @foreach($statCards as $sc)
        <div class="col-6 col-lg-4">
            <div class="card border-0 h-100" style="border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,.06);">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div style="width:48px;height:48px;border-radius:14px;background:{{ $sc['bg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi {{ $sc['icon'] }}" style="font-size:1.3rem;color:{{ $sc['color'] }};"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-4 count-up" data-target="{{ $sc['value'] }}">{{ $sc['value'] }}</div>
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
                <div class="col-12 col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Cari kode atau nama mata pelajaran…" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <select name="cabang_id" class="form-select">
                        <option value="">Semua Cabang</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}" {{ request('cabang_id')==$b->id?'selected':'' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status')=='aktif'?'selected':'' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status')=='nonaktif'?'selected':'' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                    @if(request()->hasAny(['search','cabang_id','status']))
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x"></i></a>
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
                    <thead style="background:rgba(16,185,129,.06);">
                        <tr>
                            <th class="px-4 py-3" style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;">#</th>
                            <th class="py-3" style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;">Kode</th>
                            <th class="py-3" style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;">Nama Mata Pelajaran</th>
                            <th class="py-3 d-none d-md-table-cell" style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;">Kategori</th>
                            <th class="py-3 d-none d-md-table-cell" style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;">Cabang</th>
                            <th class="py-3" style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;">Status</th>
                            <th class="py-3 text-end pe-4" style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $i => $course)
                        <tr style="transition:background .15s;">
                            <td class="px-4 text-muted" style="font-size:.85rem;">{{ $courses->firstItem() + $i }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @php $color = $course->warna ?: '#10b981'; @endphp
                                    <div style="width:36px;height:36px;border-radius:10px;background:{{ $color }}22;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="bi {{ $course->icon ?: 'bi-book' }}" style="color:{{ $color }};font-size:.95rem;"></i>
                                    </div>
                                    <span class="fw-semibold" style="font-size:.85rem;font-family:monospace;">{{ $course->kode }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold" style="font-size:.9rem;">{{ $course->nama }}</div>
                                @if($course->deskripsi)
                                <div class="text-muted" style="font-size:.78rem;">{{ Str::limit($course->deskripsi, 50) }}</div>
                                @endif
                            </td>
                            <td class="d-none d-md-table-cell">
                                @if($course->kategori)
                                <span class="badge rounded-pill" style="background:rgba(16,185,129,.15);color:#059669;font-size:.75rem;font-weight:600;">{{ $course->kategori }}</span>
                                @else
                                <span class="text-muted" style="font-size:.82rem;">—</span>
                                @endif
                            </td>
                            <td class="d-none d-md-table-cell">
                                <span style="font-size:.85rem;">{{ $course->cabang?->name ?? 'Semua Cabang' }}</span>
                            </td>
                            <td>
                                @if($course->status === 'aktif')
                                    <span class="badge rounded-pill" style="background:rgba(16,185,129,.15);color:#059669;font-size:.75rem;font-weight:600;padding:.35em .75em;">Aktif</span>
                                @else
                                    <span class="badge rounded-pill" style="background:rgba(239,68,68,.15);color:#dc2626;font-size:.75rem;font-weight:600;padding:.35em .75em;">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex gap-1 justify-content-end">
                                    <button class="btn btn-sm btn-light" onclick="editCourse({{ $course->id }})" title="Edit" style="border-radius:8px;width:32px;height:32px;padding:0;">
                                        <i class="bi bi-pencil" style="font-size:.78rem;"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light text-danger" onclick="deleteCourse({{ $course->id }}, '{{ addslashes($course->nama) }}')" title="Hapus" style="border-radius:8px;width:32px;height:32px;padding:0;">
                                        <i class="bi bi-trash" style="font-size:.78rem;"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div style="opacity:.5;">
                                    <i class="bi bi-book" style="font-size:2.5rem;display:block;margin-bottom:.5rem;color:#10b981;"></i>
                                    <div class="fw-semibold">Belum ada mata pelajaran</div>
                                    <small class="text-muted">Tambahkan mata pelajaran pertama Anda</small>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($courses->hasPages())
            <div class="px-4 py-3 border-top">
                {{ $courses->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

{{-- MODAL: ADD / EDIT --}}
<div class="modal fade" id="courseModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0" style="border-radius:20px;box-shadow:0 20px 60px rgba(0,0,0,.15);">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h5 class="modal-title fw-bold" id="modalTitle">Tambah Mata Pelajaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <form id="courseForm">
                    @csrf
                    <input type="hidden" id="courseId">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Kode <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="kode" placeholder="cth: MTK, IPA, BIG" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama" placeholder="cth: Matematika Dasar" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Kategori</label>
                            <select class="form-select" id="kategori">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Sains">Sains</option>
                                <option value="Bahasa">Bahasa</option>
                                <option value="Matematika">Matematika</option>
                                <option value="Sosial">Sosial</option>
                                <option value="Teknologi">Teknologi</option>
                                <option value="Seni">Seni</option>
                                <option value="Olahraga">Olahraga</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Cabang</label>
                            <select class="form-select" id="cabang_id">
                                <option value="">Semua Cabang</option>
                                @foreach($branches as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Icon Bootstrap</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-book" id="iconPreview"></i></span>
                                <input type="text" class="form-control" id="icon" placeholder="bi-book" oninput="document.getElementById('iconPreview').className='bi '+this.value">
                            </div>
                            <div class="form-text">Contoh: bi-calculator, bi-globe, bi-pencil</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Warna</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color" id="warna" value="#10b981" style="max-width:60px;">
                                <input type="text" class="form-control" id="warnaHex" placeholder="#10b981" oninput="document.getElementById('warna').value=this.value">
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" rows="3" placeholder="Deskripsi singkat mata pelajaran…"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" required>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-2 gap-2">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success px-4 fw-semibold" onclick="saveCourse()" id="saveBtn">
                    <i class="bi bi-check-circle me-2"></i>Simpan
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const modal = new bootstrap.Modal(document.getElementById('courseModal'));

function openModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Mata Pelajaran';
    document.getElementById('courseId').value = '';
    document.getElementById('courseForm').reset();
    document.getElementById('warna').value = '#10b981';
    document.getElementById('warnaHex').value = '#10b981';
    document.getElementById('iconPreview').className = 'bi bi-book';
    modal.show();
}

function editCourse(id) {
    document.getElementById('modalTitle').textContent = 'Edit Mata Pelajaran';
    document.getElementById('saveBtn').disabled = true;
    fetch(`/admin/courses/${id}`)
        .then(r => r.json())
        .then(d => {
            document.getElementById('courseId').value = d.id;
            document.getElementById('kode').value = d.kode || '';
            document.getElementById('nama').value = d.nama || '';
            document.getElementById('kategori').value = d.kategori || '';
            document.getElementById('cabang_id').value = d.cabang_id || '';
            document.getElementById('icon').value = d.icon || '';
            document.getElementById('iconPreview').className = 'bi ' + (d.icon || 'bi-book');
            const w = d.warna || '#10b981';
            document.getElementById('warna').value = w;
            document.getElementById('warnaHex').value = w;
            document.getElementById('deskripsi').value = d.deskripsi || '';
            document.getElementById('status').value = d.status || 'aktif';
            document.getElementById('saveBtn').disabled = false;
            modal.show();
        });
}

document.getElementById('warna').addEventListener('input', function() {
    document.getElementById('warnaHex').value = this.value;
});

function saveCourse() {
    const id = document.getElementById('courseId').value;
    const isEdit = id !== '';
    const url  = isEdit ? `/admin/courses/${id}` : '/admin/courses';
    const method = isEdit ? 'PUT' : 'POST';
    const btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan…';

    fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify({
            kode:       document.getElementById('kode').value,
            nama:       document.getElementById('nama').value,
            kategori:   document.getElementById('kategori').value,
            cabang_id:  document.getElementById('cabang_id').value || null,
            icon:       document.getElementById('icon').value,
            warna:      document.getElementById('warna').value,
            deskripsi:  document.getElementById('deskripsi').value,
            status:     document.getElementById('status').value,
        })
    })
    .then(r => r.json())
    .then(res => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Simpan';
        if (res.success) {
            modal.hide();
            window.showToast && window.showToast(res.message, 'success');
            setTimeout(() => location.reload(), 600);
        } else {
            window.showToast && window.showToast(res.message || 'Terjadi kesalahan.', 'error');
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Simpan';
        window.showToast && window.showToast('Gagal menghubungi server.', 'error');
    });
}

function deleteCourse(id, nama) {
    Swal.fire({
        title: 'Hapus Mata Pelajaran?',
        html: `Mata pelajaran <strong>"${nama}"</strong> akan dihapus.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
    }).then(r => {
        if (!r.isConfirmed) return;
        fetch(`/admin/courses/${id}`, {
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
