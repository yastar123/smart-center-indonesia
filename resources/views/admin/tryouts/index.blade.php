@extends('layouts.app')
@section('title','Tryout UTBK/PTN')
@section('page-title','Tryout UTBK / PTN')

@section('content')
<div class="fade-up">

{{-- HEADER --}}
<div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#3b0764 0%,#7c3aed 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0"><i class="bi bi-journal-check"></i></div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Tryout UTBK / PTN</h5>
                    <span style="font-size:12px;opacity:.8">Buat, kelola soal, dan pantau hasil ujian siswa</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <button onclick="openModal()" class="btn fw-semibold px-4" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px">
                <i class="bi bi-plus-lg me-2"></i>Buat Tryout
            </button>
        </div>
    </div>
</div>

{{-- STATS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3"><div class="stat-card"><div class="d-flex justify-content-between"><div><div class="stat-title">Total Tryout</div><div class="stat-value" id="statTotal">–</div></div><div class="stat-icon" style="background:#7c3aed22;color:#7c3aed"><i class="bi bi-journal-check"></i></div></div></div></div>
    <div class="col-6 col-lg-3"><div class="stat-card"><div class="d-flex justify-content-between"><div><div class="stat-title">Aktif</div><div class="stat-value" id="statAktif">–</div></div><div class="stat-icon" style="background:#10b98122;color:#10b981"><i class="bi bi-check-circle"></i></div></div></div></div>
    <div class="col-6 col-lg-3"><div class="stat-card"><div class="d-flex justify-content-between"><div><div class="stat-title">Draft</div><div class="stat-value" id="statDraft">–</div></div><div class="stat-icon" style="background:#f6af2322;color:#b45309"><i class="bi bi-pencil-square"></i></div></div></div></div>
    <div class="col-6 col-lg-3"><div class="stat-card"><div class="d-flex justify-content-between"><div><div class="stat-title">Total Peserta</div><div class="stat-value" id="statPeserta">–</div></div><div class="stat-icon" style="background:#c84ddf22;color:#c84ddf"><i class="bi bi-people"></i></div></div></div></div>
</div>

{{-- FILTERS --}}
<div class="dashboard-card mb-4">
    <div class="row g-2">
        <div class="col-md-4"><div class="input-group"><span class="input-group-text"><i class="bi bi-search"></i></span><input type="text" id="searchInput" class="form-control" placeholder="Cari tryout..."></div></div>
        <div class="col-md-3"><select id="filterKategori" class="form-select"><option value="">Semua Kategori</option><option>UTBK</option><option>PTN</option><option>Matematika</option><option>Bahasa Inggris</option><option>IPA</option><option>IPS</option></select></div>
        <div class="col-md-3"><select id="filterStatus" class="form-select"><option value="">Semua Status</option><option value="aktif">Aktif</option><option value="draft">Draft</option><option value="selesai">Selesai</option></select></div>
        <div class="col-md-2"><button onclick="loadData()" class="btn btn-primary w-100"><i class="bi bi-funnel me-1"></i>Filter</button></div>
    </div>
</div>

{{-- TABLE --}}
<div class="dashboard-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr style="background:rgba(124,58,237,.06)"><th>Judul Tryout</th><th>Kategori</th><th>Durasi</th><th>Soal</th><th>Jadwal Mulai</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody id="tableBody"><tr><td colspan="7" class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Memuat data...</td></tr></tbody>
        </table>
    </div>
    <div id="paginationLinks" class="mt-3 d-flex justify-content-center"></div>
</div>

</div>

{{-- MODAL TRYOUT --}}
<div class="modal fade" id="tryoutModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:20px;border:none">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Buat Tryout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="tryoutForm">
                @csrf
                <input type="hidden" id="tryId">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8"><label class="form-label fw-semibold">Judul Tryout <span class="text-danger">*</span></label><input type="text" name="judul" class="form-control" required></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label><input type="text" name="kategori" class="form-control" required placeholder="UTBK, PTN, dll"></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Durasi (menit) <span class="text-danger">*</span></label><input type="number" name="durasi_menit" class="form-control" required value="90" min="5"></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Nilai Kelulusan (%)</label><input type="number" name="nilai_kelulusan" class="form-control" value="60" min="0" max="100"></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Maks Percobaan</label><input type="number" name="maksimal_percobaan" class="form-control" value="1" min="1"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Waktu Mulai</label><input type="datetime-local" name="waktu_mulai" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Waktu Selesai</label><input type="datetime-local" name="waktu_selesai" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Status</label><select name="status" class="form-select"><option value="draft">Draft</option><option value="aktif">Aktif</option><option value="selesai">Selesai</option></select></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Cabang</label><select name="cabang_id" class="form-select"><option value="">Semua Cabang</option>@foreach($branches as $b)<option value="{{ $b->id }}">{{ $b->name }}</option>@endforeach</select></div>
                        <div class="col-12"><label class="form-label fw-semibold">Deskripsi</label><textarea name="deskripsi" class="form-control" rows="3"></textarea></div>
                        <div class="col-12">
                            <div class="d-flex flex-wrap gap-4">
                                <div class="form-check form-switch"><input type="checkbox" class="form-check-input" name="is_random" id="isRandom" value="1"><label class="form-check-label" for="isRandom">Acak Soal</label></div>
                                <div class="form-check form-switch"><input type="checkbox" class="form-check-input" name="tampilkan_hasil_langsung" id="showResult" value="1" checked><label class="form-check-label" for="showResult">Tampilkan Hasil Langsung</label></div>
                                <div class="form-check form-switch"><input type="checkbox" class="form-check-input" name="tampilkan_kunci_jawaban" id="showKey" value="1"><label class="form-check-label" for="showKey">Tampilkan Kunci Jawaban</label></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4" id="submitBtn"><i class="bi bi-floppy me-2"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL SOAL --}}
<div class="modal fade" id="soalModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="border-radius:20px;border:none">
            <div class="modal-header border-0 pb-0">
                <div><h5 class="modal-title fw-bold" id="soalTitle">Kelola Soal</h5><small class="text-muted" id="soalSubtitle"></small></div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <div class="dashboard-card" style="max-height:500px;overflow-y:auto">
                            <div id="soalList"><div class="text-center py-4 text-muted"><i class="bi bi-journal-x" style="font-size:2rem;display:block;margin-bottom:8px"></i>Belum ada soal. Tambahkan dari form kanan.</div></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="dashboard-card">
                            <h6 class="fw-bold mb-3"><i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Soal</h6>
                            <form id="soalForm">
                                @csrf
                                <input type="hidden" id="soalTryoutId">
                                <div class="mb-3"><label class="form-label fw-semibold">Pertanyaan <span class="text-danger">*</span></label><textarea name="teks_pertanyaan" class="form-control" rows="4" required placeholder="Tulis pertanyaan di sini..."></textarea></div>
                                <div class="mb-3"><label class="form-label fw-semibold">Jenis Soal</label>
                                    <select name="jenis" class="form-select" id="soalJenis">
                                        <option value="pilihan_ganda">Pilihan Ganda</option>
                                        <option value="benar_salah">Benar / Salah</option>
                                        <option value="isian">Isian Singkat</option>
                                    </select>
                                </div>
                                <div id="pilihanSection" class="mb-3">
                                    <label class="form-label fw-semibold">Pilihan Jawaban</label>
                                    @foreach(['A','B','C','D','E'] as $opt)
                                    <div class="input-group mb-1">
                                        <span class="input-group-text fw-bold" style="width:36px;justify-content:center">{{ $opt }}</span>
                                        <input type="text" name="pilihan_jawaban[]" class="form-control" placeholder="Pilihan {{ $opt }}">
                                    </div>
                                    @endforeach
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6"><label class="form-label fw-semibold">Tingkat</label><select name="tingkat_kesulitan" class="form-select"><option value="mudah">Mudah</option><option value="sedang" selected>Sedang</option><option value="sulit">Sulit</option></select></div>
                                    <div class="col-6"><label class="form-label fw-semibold">Poin</label><input type="number" name="poin" class="form-control" value="1" min="0" step="0.5"></div>
                                </div>
                                <div class="mb-3"><label class="form-label fw-semibold">Pembahasan (opsional)</label><textarea name="penjelasan" class="form-control" rows="2" placeholder="Penjelasan jawaban..."></textarea></div>
                                <button type="submit" class="btn btn-primary w-100" id="addSoalBtn"><i class="bi bi-plus me-2"></i>Tambah Soal</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL HASIL --}}
<div class="modal fade" id="hasilModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:20px;border:none">
            <div class="modal-header border-0 pb-0"><h5 class="modal-title fw-bold">Hasil Tryout</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead><tr><th>#</th><th>Nama Siswa</th><th>Nilai</th><th>Status</th><th>Waktu Ujian</th></tr></thead>
                        <tbody id="hasilBody"><tr><td colspan="5" class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Loading...</td></tr></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentPage = 1, currentTryoutId = null;

function loadData(page = 1) {
    currentPage = page;
    const params = new URLSearchParams({ page, search: document.getElementById('searchInput').value, kategori: document.getElementById('filterKategori').value, status: document.getElementById('filterStatus').value });
    fetch(`{{ route('admin.tryouts.index') }}?${params}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json()).then(data => {
            document.getElementById('statTotal').textContent    = data.stats.total;
            document.getElementById('statAktif').textContent    = data.stats.aktif;
            document.getElementById('statDraft').textContent    = data.stats.draft;
            document.getElementById('statPeserta').textContent  = data.stats.peserta;
            renderTable(data.data);
            renderPagination(data);
        });
}

function renderTable(rows) {
    const sm = { aktif: '<span class="badge bg-success">Aktif</span>', draft: '<span class="badge bg-warning text-dark">Draft</span>', selesai: '<span class="badge bg-secondary">Selesai</span>' };
    if (!rows.length) { document.getElementById('tableBody').innerHTML = '<tr><td colspan="7" class="text-center py-5 text-muted"><i class="bi bi-journal-x" style="font-size:2rem;display:block;margin-bottom:8px"></i>Belum ada tryout</td></tr>'; return; }
    document.getElementById('tableBody').innerHTML = rows.map(t => `
        <tr>
            <td><div class="fw-semibold">${t.judul}</div><small class="text-muted">${(t.deskripsi||'').substring(0,50)}${(t.deskripsi||'').length>50?'...':''}</small></td>
            <td><span class="badge bg-primary-subtle text-primary">${t.kategori}</span></td>
            <td><i class="bi bi-clock me-1"></i>${t.durasi_menit} mnt</td>
            <td class="fw-semibold">${t.total_soal || 0} soal</td>
            <td style="font-size:11px">${t.waktu_mulai ? (t.waktu_mulai+'').substring(0,16).replace('T',' ') : '–'}</td>
            <td>${sm[t.status] || t.status}</td>
            <td>
                <div class="d-flex gap-1">
                    <button onclick="openSoal(${t.id},'${t.judul.replace(/'/g,"\\\'")}')" class="btn btn-sm btn-outline-primary" title="Kelola Soal"><i class="bi bi-list-ul"></i></button>
                    <button onclick="lihatHasil(${t.id})" class="btn btn-sm btn-outline-success" title="Lihat Hasil"><i class="bi bi-bar-chart-line"></i></button>
                    <button onclick="editTryout(${t.id})" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                    <button onclick="deleteTryout(${t.id},'${t.judul.replace(/'/g,"\\\'")}')" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </div>
            </td>
        </tr>`).join('');
}

function renderPagination(data) {
    const el = document.getElementById('paginationLinks');
    if (data.last_page <= 1) { el.innerHTML = ''; return; }
    let h = '<nav><ul class="pagination pagination-sm mb-0">';
    h += `<li class="page-item ${data.current_page==1?'disabled':''}"><a class="page-link" href="#" onclick="loadData(${data.current_page-1});return false">‹</a></li>`;
    for (let i = 1; i <= data.last_page; i++) h += `<li class="page-item ${i==data.current_page?'active':''}"><a class="page-link" href="#" onclick="loadData(${i});return false">${i}</a></li>`;
    h += `<li class="page-item ${data.current_page==data.last_page?'disabled':''}"><a class="page-link" href="#" onclick="loadData(${data.current_page+1});return false">›</a></li></ul></nav>`;
    el.innerHTML = h;
}

function openModal(reset = true) {
    if (reset) { document.getElementById('tryoutForm').reset(); document.getElementById('tryId').value = ''; document.getElementById('modalTitle').textContent = 'Buat Tryout'; }
    new bootstrap.Modal(document.getElementById('tryoutModal')).show();
}

function editTryout(id) {
    fetch(`{{ url('admin/tryouts') }}/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json()).then(res => {
            const t = res.data, f = document.getElementById('tryoutForm');
            ['judul','kategori','durasi_menit','nilai_kelulusan','maksimal_percobaan','status','deskripsi'].forEach(k => { if (f.querySelector(`[name=${k}]`)) f.querySelector(`[name=${k}]`).value = t[k] || ''; });
            if (f.querySelector('[name=cabang_id]')) f.querySelector('[name=cabang_id]').value = t.cabang_id || '';
            if (t.waktu_mulai)  f.querySelector('[name=waktu_mulai]').value  = (t.waktu_mulai+'').replace(' ','T').substring(0,16);
            if (t.waktu_selesai) f.querySelector('[name=waktu_selesai]').value = (t.waktu_selesai+'').replace(' ','T').substring(0,16);
            f.querySelector('[name=is_random]').checked               = !!t.is_random;
            f.querySelector('[name=tampilkan_hasil_langsung]').checked = !!t.tampilkan_hasil_langsung;
            f.querySelector('[name=tampilkan_kunci_jawaban]').checked  = !!t.tampilkan_kunci_jawaban;
            document.getElementById('tryId').value = id;
            document.getElementById('modalTitle').textContent = 'Edit Tryout';
            openModal(false);
        });
}

function deleteTryout(id, name) {
    Swal.fire({ title: 'Hapus Tryout?', text: name, icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', confirmButtonText: 'Hapus' })
        .then(r => { if (!r.isConfirmed) return;
            fetch(`{{ url('admin/tryouts') }}/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.json()).then(d => { showToast(d.message, d.success ? 'success' : 'error'); if (d.success) loadData(currentPage); });
        });
}

document.getElementById('tryoutForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('tryId').value;
    const url = id ? `{{ url('admin/tryouts') }}/${id}` : `{{ route('admin.tryouts.store') }}`;
    const fd = new FormData(this); if (id) fd.append('_method', 'PUT');
    document.getElementById('submitBtn').disabled = true;
    fetch(url, { method: 'POST', body: fd, headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json()).then(d => {
            document.getElementById('submitBtn').disabled = false;
            showToast(d.message, d.success ? 'success' : 'error');
            if (d.success) { bootstrap.Modal.getInstance(document.getElementById('tryoutModal')).hide(); loadData(currentPage); }
        }).catch(() => { document.getElementById('submitBtn').disabled = false; });
});

// ---------- SOAL ----------
function openSoal(id, judul) {
    currentTryoutId = id;
    document.getElementById('soalTitle').textContent = 'Soal — ' + judul;
    document.getElementById('soalTryoutId').value = id;
    document.getElementById('soalForm').reset();
    new bootstrap.Modal(document.getElementById('soalModal')).show();
    loadSoal(id);
}

function loadSoal(id) {
    document.getElementById('soalList').innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div></div>';
    fetch(`{{ url('admin/tryouts') }}/${id}/soal`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json()).then(res => {
            document.getElementById('soalSubtitle').textContent = res.soal.length + ' soal terdaftar';
            const diffBadge = { mudah: 'bg-success', sedang: 'bg-warning text-dark', sulit: 'bg-danger' };
            if (!res.soal.length) {
                document.getElementById('soalList').innerHTML = '<div class="text-center py-4 text-muted"><i class="bi bi-journal-x" style="font-size:2rem;display:block;margin-bottom:8px"></i>Belum ada soal</div>';
                return;
            }
            document.getElementById('soalList').innerHTML = res.soal.map((s, i) => `
                <div class="p-3 mb-2 rounded-3" style="background:var(--body-bg);border:1px solid var(--card-border)">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div style="font-size:13px;font-weight:500;flex:1">${i+1}. ${s.teks_pertanyaan.substring(0,120)}${s.teks_pertanyaan.length>120?'...':''}</div>
                        <div class="d-flex align-items-center gap-1 flex-shrink-0">
                            <span class="badge ${diffBadge[s.tingkat_kesulitan]||'bg-secondary'}" style="font-size:10px">${s.tingkat_kesulitan||'sedang'}</span>
                            <button onclick="deleteSoal(${s.id})" class="btn btn-sm btn-outline-danger p-0" style="width:22px;height:22px;font-size:11px;line-height:1"><i class="bi bi-x"></i></button>
                        </div>
                    </div>
                    <small class="text-muted">${s.poin} poin &middot; ${s.jenis}</small>
                </div>`).join('');
        });
}

document.getElementById('soalJenis').addEventListener('change', function() {
    document.getElementById('pilihanSection').style.display = this.value === 'isian' ? 'none' : '';
});

document.getElementById('soalForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('soalTryoutId').value;
    const fd = new FormData(this);
    document.getElementById('addSoalBtn').disabled = true;
    fetch(`{{ url('admin/tryouts') }}/${id}/soal`, { method: 'POST', body: fd, headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json()).then(d => {
            document.getElementById('addSoalBtn').disabled = false;
            showToast(d.message, d.success ? 'success' : 'error');
            if (d.success) { this.querySelector('[name=teks_pertanyaan]').value = ''; this.querySelector('[name=penjelasan]').value = ''; this.querySelectorAll('[name="pilihan_jawaban[]"]').forEach(i=>i.value=''); loadSoal(id); }
        }).catch(() => { document.getElementById('addSoalBtn').disabled = false; });
});

function deleteSoal(soalId) {
    fetch(`{{ url('admin/tryouts') }}/${currentTryoutId}/soal/${soalId}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json()).then(d => { showToast(d.message, d.success ? 'success' : 'error'); if (d.success) loadSoal(currentTryoutId); });
}

function lihatHasil(id) {
    document.getElementById('hasilBody').innerHTML = '<tr><td colspan="5" class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Loading...</td></tr>';
    new bootstrap.Modal(document.getElementById('hasilModal')).show();
    fetch(`{{ url('admin/tryouts') }}/${id}/results`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json()).then(res => {
            if (!res.data.length) {
                document.getElementById('hasilBody').innerHTML = '<tr><td colspan="5" class="text-center py-4 text-muted">Belum ada peserta</td></tr>';
            } else {
                document.getElementById('hasilBody').innerHTML = res.data.map((a, i) => `
                    <tr>
                        <td>${i+1}</td>
                        <td>${a.siswa?.name || '–'}</td>
                        <td class="fw-bold ${(a.nilai||0)>=60?'text-success':'text-danger'}">${parseFloat(a.nilai||0).toFixed(1)}</td>
                        <td>${(a.nilai||0)>=60?'<span class="badge bg-success">Lulus</span>':'<span class="badge bg-danger">Belum Lulus</span>'}</td>
                        <td style="font-size:11px">${(a.created_at||'').toString().substring(0,16).replace('T',' ')}</td>
                    </tr>`).join('');
            }
        });
}

let st; document.getElementById('searchInput').addEventListener('input', () => { clearTimeout(st); st = setTimeout(() => loadData(1), 400); });
document.addEventListener('DOMContentLoaded', () => loadData());
</script>
@endpush
