@extends('layouts.app')
@section('title', 'Jadwal Mata Pelajaran')
@section('page-title', 'Jadwal Mata Pelajaran')

@section('content')

{{-- HEADER BANNER --}}
<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px">
                    <i class="bi bi-calendar-week-fill"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Jadwal Sesi Mata Pelajaran</h5>
                    <span style="font-size:12px;opacity:.8">Atur jadwal setiap sesi berdasarkan paket belajar yang tersedia</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('admin.schedules.create') }}" class="btn fw-semibold px-4"
               style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                <i class="bi bi-plus-lg me-2"></i>Tambah Jadwal
            </a>
        </div>
    </div>
</div>

{{-- STATS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 fade-up">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Total Jadwal</div><div class="stat-value">{{ $stats['total'] }}</div></div>
                <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-calendar-week"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Hari Ini</div><div class="stat-value text-warning">{{ $stats['hari_ini'] }}</div></div>
                <div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-sun-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #0284c7">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Dijadwalkan</div><div class="stat-value text-primary">{{ $stats['dijadwalkan'] }}</div></div>
                <div class="stat-icon bg-info-soft" style="color:white"><i class="bi bi-clock-history"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Selesai</div><div class="stat-value text-success">{{ $stats['selesai'] }}</div></div>
                <div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-check-circle-fill"></i></div>
            </div>
        </div>
    </div>
</div>

{{-- FILTER BAR --}}
<div class="dashboard-card mb-4 fade-up">
    <form id="filterForm" method="GET" action="{{ route('admin.schedules.index') }}">
        <div class="row g-2 align-items-end">
            <div class="col-12 col-md-3">
                <label class="form-label fw-semibold" style="font-size:12px">Cari Paket / Topik / Ruangan</label>
                <div class="input-group">
                    <span class="input-group-text" style="border-radius:10px 0 0 10px;background:var(--input-bg);border-color:var(--card-border)">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari jadwal..."
                        class="form-control" style="border-radius:0 10px 10px 0;border-color:var(--card-border);background:var(--input-bg)"
                        onchange="document.getElementById('filterForm').submit()">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label fw-semibold" style="font-size:12px">Paket</label>
                <select name="paket_id" class="form-select" style="border-radius:10px"
                    onchange="document.getElementById('filterForm').submit()">
                    <option value="">Semua Paket</option>
                    @foreach($pakets as $p)
                    <option value="{{ $p->id }}" {{ request('paket_id')==$p->id?'selected':'' }}>{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label fw-semibold" style="font-size:12px">Status</label>
                <select name="status" class="form-select" style="border-radius:10px"
                    onchange="document.getElementById('filterForm').submit()">
                    <option value="">Semua</option>
                    <option value="dijadwalkan" {{ request('status')=='dijadwalkan'?'selected':'' }}>Dijadwalkan</option>
                    <option value="berlangsung" {{ request('status')=='berlangsung'?'selected':'' }}>Berlangsung</option>
                    <option value="selesai"     {{ request('status')=='selesai'?'selected':'' }}>Selesai</option>
                    <option value="dibatalkan"  {{ request('status')=='dibatalkan'?'selected':'' }}>Dibatalkan</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label fw-semibold" style="font-size:12px">Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-control"
                    style="border-radius:10px" onchange="document.getElementById('filterForm').submit()">
            </div>
            <div class="col-6 col-md-2">
                @if(request()->hasAny(['search','status','paket_id','tanggal']))
                <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary w-100" style="border-radius:10px" title="Reset">
                    <i class="bi bi-x-lg me-1"></i>Reset
                </a>
                @else
                <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary w-100 fw-semibold" style="border-radius:10px">
                    <i class="bi bi-plus-lg"></i>
                </a>
                @endif
            </div>
        </div>
    </form>
</div>

{{-- TABLE --}}
<div class="dashboard-card fade-up">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-list-ul text-primary me-2"></i>Daftar Jadwal Sesi
            <span class="badge ms-2" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);font-size:11px">{{ $schedules->total() }} data</span>
        </h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-modern align-middle mb-0">
            <thead class="thead-modern">
                <tr>
                    <th class="ps-3">Paket</th>
                    <th>Tanggal &amp; Waktu</th>
                    <th class="d-none d-md-table-cell">Guru</th>
                    <th class="d-none d-lg-table-cell">Ruangan / Link</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $sc)
                @php
                    $statusMap = [
                        'dijadwalkan' => ['bg'=>'var(--soft-primary-bg)','color'=>'var(--soft-primary-text)','label'=>'Dijadwalkan'],
                        'berlangsung' => ['bg'=>'var(--soft-warning-bg)','color'=>'var(--soft-warning-text)','label'=>'Berlangsung'],
                        'selesai'     => ['bg'=>'var(--soft-success-bg)','color'=>'var(--soft-success-text)','label'=>'Selesai'],
                        'dibatalkan'  => ['bg'=>'var(--soft-danger-bg)','color'=>'var(--soft-danger-text)','label'=>'Dibatalkan'],
                    ];
                    $st = $statusMap[$sc->status] ?? ['bg'=>'var(--soft-muted-bg)','color'=>'var(--soft-muted-text)','label'=>$sc->status];
                    $isToday = $sc->tanggal && $sc->tanggal->isToday();
                    $mapelNames = $sc->mataPelajaran?->nama ?? ($sc->paket?->mataPelajaran->pluck('nama')->join(', ') ?? '–');
                @endphp
                <tr style="border-bottom:1px solid var(--card-border);transition:background .15s{{ $isToday ? ';background:rgba(104,17,126,.03)' : '' }}">
                    <td class="ps-3">
                        <div class="fw-semibold" style="font-size:13px">{{ $sc->paket?->nama ?? '–' }}</div>
                        <div class="text-muted" style="font-size:11px">
                            <i class="bi bi-journal-bookmark me-1"></i>{{ $mapelNames }}
                        </div>
                    </td>
                    <td>
                        <div class="fw-semibold" style="font-size:13px">
                            {{ $sc->tanggal ? $sc->tanggal->format('d M Y') : '–' }}
                            @if($isToday)<span class="badge ms-1" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);font-size:10px">Hari ini</span>@endif
                        </div>
                        <div class="text-muted" style="font-size:11px"><i class="bi bi-clock me-1"></i>{{ str_replace(':', '.', substr($sc->jam_mulai ?? '', 0, 5)) ?: '–' }} – {{ str_replace(':', '.', substr($sc->jam_selesai ?? '', 0, 5)) ?: '–' }} WIB</div>
                    </td>
                    <td class="d-none d-md-table-cell">
                        <div style="font-size:12.5px">{{ $sc->guru?->name ?? $sc->paket?->guru?->name ?? '–' }}</div>
                    </td>
                    <td class="d-none d-lg-table-cell text-muted" style="font-size:.82rem">
                        @if($sc->ruangan)
                        <i class="bi bi-door-open me-1"></i>{{ $sc->ruangan }}
                        @elseif($sc->link_meeting)
                        <a href="{{ $sc->link_meeting }}" target="_blank" class="text-decoration-none" style="font-size:.82rem"><i class="bi bi-link-45deg me-1"></i>Buka Link</a>
                        @else
                        –
                        @endif
                    </td>
                    <td>
                        <span style="background:{{ $st['bg'] }};color:{{ $st['color'] }};padding:4px 10px;border-radius:8px;font-size:11px;font-weight:600">{{ $st['label'] }}</span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            <button onclick="showDetail({{ $sc->id }})" class="btn btn-sm btn-act-view" title="Detail"><i class="bi bi-eye-fill"></i></button>
                            <button onclick="editSchedule({{ $sc->id }})" class="btn btn-sm btn-act-edit" title="Edit"><i class="bi bi-pencil-fill"></i></button>
                            <button onclick="deleteSchedule({{ $sc->id }}, '{{ addslashes($sc->paket?->nama ?? 'Jadwal ini') }}')" class="btn btn-sm btn-act-del" title="Hapus"><i class="bi bi-trash-fill"></i></button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="text-muted">
                            <i class="bi bi-calendar-x" style="font-size:40px;display:block;margin-bottom:12px;opacity:.4"></i>
                            <div class="fw-semibold mb-1">Belum ada jadwal sesi</div>
                            <div style="font-size:12px">Klik "Tambah Jadwal" untuk menjadwalkan sesi belajar</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($schedules->hasPages())
    <div class="mt-4 d-flex justify-content-center">{{ $schedules->links() }}</div>
    @endif
</div>

{{-- MODAL ADD/EDIT --}}
<div class="modal fade" id="scheduleModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius:18px;overflow:hidden">
            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#461256,#68117e);color:#fff">
                <h6 class="modal-title fw-bold" id="modalTitle"><i class="bi bi-calendar-plus me-2"></i>Tambah Jadwal Sesi</h6>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="background:var(--input-bg)">
                <input type="hidden" id="scheduleId">

                {{-- INFO PAKET --}}
                <div class="mb-3 p-3 rounded-3" id="paketInfoBox" style="background:var(--soft-primary-bg);border:1.5px solid var(--soft-primary-border);display:none">
                    <div class="d-flex gap-3 align-items-center">
                        <div style="width:40px;height:40px;border-radius:10px;background:var(--primary);display:flex;align-items:center;justify-content:center;color:white;flex-shrink:0"><i class="bi bi-box-seam"></i></div>
                        <div style="flex:1">
                            <div class="fw-bold" id="paketInfoName" style="font-size:14px;color:var(--soft-primary-text)"></div>
                            <div style="font-size:12px;color:var(--text-muted)" id="paketInfoMeta"></div>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size:12px">Paket Belajar <span class="text-danger">*</span></label>
                        <select id="paket_id" class="form-select" onchange="onPaketChange(this.value)">
                            <option value="">— Pilih Paket —</option>
                            @foreach($pakets as $p)
                            <option value="{{ $p->id }}"
                                data-nama="{{ $p->nama }}"
                                data-guru="{{ $p->guru?->name ?? '–' }}"
                                data-mapel="{{ $p->mataPelajaran->pluck('nama')->join(', ') ?: '–' }}"
                                data-jenis="{{ $p->jenis }}">
                                {{ $p->nama }} — {{ $p->mataPelajaran->pluck('nama')->join(', ') ?: '–' }} — {{ $p->guru?->name ?? 'belum ada guru' }} — {{ ucfirst($p->jenis) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:12px">Metode Kelas <span class="text-danger">*</span></label>
                        <select id="sc_jenis" class="form-select">
                            <option value="offline">🏫 Offline (Tatap Muka)</option>
                            <option value="online">💻 Online</option>
                            <option value="private">👤 Private</option>
                        </select>
                    </div>
                    <div class="col-md-6" id="statusScWrap" style="display:none">
                        <label class="form-label fw-semibold" style="font-size:12px">Status</label>
                        <select id="sc_status" class="form-select">
                            <option value="dijadwalkan">Dijadwalkan</option>
                            <option value="berlangsung">Berlangsung</option>
                            <option value="selesai">Selesai</option>
                            <option value="dibatalkan">Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:12px">Tanggal Mulai <span class="text-danger">*</span></label>
                        <input type="date" id="tanggal" class="form-control" style="border-radius:10px">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:12px">Jam Mulai <span class="text-danger">*</span></label>
                        <input type="text" id="jam_mulai" class="form-control flatpickr-time-input" placeholder="13:30" autocomplete="off" style="border-radius:10px">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:12px">Jam Selesai <span class="text-danger">*</span></label>
                        <input type="text" id="jam_selesai" class="form-control flatpickr-time-input" placeholder="15:00" autocomplete="off" style="border-radius:10px">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:12px">Ruangan <span class="text-muted">(opsional)</span></label>
                        <input type="text" id="ruangan" class="form-control" placeholder="cth: Ruang A1, Lab Komputer..." style="border-radius:10px">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size:12px">Link Meeting <span class="text-muted">(opsional, untuk sesi online)</span></label>
                        <input type="text" id="link_meeting" class="form-control" placeholder="https://meet.google.com/..." style="border-radius:10px">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4" style="background:var(--input-bg)">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius:10px"><i class="bi bi-x me-1"></i>Batal</button>
                <button type="button" class="btn btn-primary px-5 fw-semibold" id="saveBtn" onclick="saveSchedule()" style="border-radius:10px"><i class="bi bi-check-lg me-1"></i>Simpan Jadwal</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DETAIL --}}
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:18px;overflow:hidden">
            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#461256,#68117e);color:#fff">
                <h6 class="modal-title fw-bold"><i class="bi bi-calendar-event me-2"></i>Detail Jadwal Sesi</h6>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="detailBody">
                <div class="text-center py-5"><div class="spinner-border text-primary"></div></div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.flatpickr-time-input').forEach(function(el) {
        flatpickr(el, {
            enableTime: true,
            noCalendar: true,
            time_24hr: true,
            dateFormat: 'H:i',
            minuteIncrement: 5,
        });
    });
});

function fmtWib(t) {
    if (!t || t === '–') return '–';
    return t.substr(0,5).replace(':', '.') + ' WIB';
}

const paketData = {};
@foreach($pakets as $p)
paketData[{{ $p->id }}] = {
    nama:      @json($p->nama),
    guru:      @json($p->guru?->name ?? '–'),
    mapel:     @json($p->mataPelajaran->pluck('nama')->join(', ') ?: '–'),
    jenis:     @json($p->jenis),
    tipeKelas: @json($p->tipe_kelas ?? 'offline'),
};
@endforeach

function onPaketChange(paketId) {
    const box = document.getElementById('paketInfoBox');

    if (!paketId) {
        box.style.display = 'none';
        return;
    }

    const p = paketData[paketId];
    if (!p) return;

    document.getElementById('paketInfoName').textContent = p.nama + ' — ' + p.mapel;
    document.getElementById('paketInfoMeta').textContent = 'Guru: ' + p.guru + ' | Jenis: ' + p.jenis;
    box.style.display = 'block';

    // Auto-set delivery method from package tipe_kelas
    const jenisEl = document.getElementById('sc_jenis');
    if (jenisEl) {
        const validJenis = ['online', 'offline', 'private'];
        jenisEl.value = validJenis.includes(p.tipeKelas) ? p.tipeKelas : 'offline';
    }
}

function openModal() {
    document.getElementById('scheduleId').value    = '';
    document.getElementById('modalTitle').innerHTML= '<i class="bi bi-calendar-plus me-2"></i>Tambah Jadwal Sesi';
    document.getElementById('paket_id').value      = '';
    document.getElementById('tanggal').value       = '';
    document.getElementById('jam_mulai').value     = '';
    document.getElementById('jam_selesai').value   = '';
    document.getElementById('ruangan').value       = '';
    document.getElementById('link_meeting').value  = '';
    document.getElementById('sc_jenis').value      = 'offline';
    document.getElementById('paketInfoBox').style.display  = 'none';
    document.getElementById('statusScWrap').style.display  = 'none';
    new bootstrap.Modal('#scheduleModal').show();
}

function editSchedule(id) {
    window.location.href = '/admin/schedules/' + id + '/edit';
}

function saveSchedule() {
    const id     = document.getElementById('scheduleId').value;
    const url    = id ? '/admin/schedules/' + id : '{{ route("admin.schedules.store") }}';
    const paketId = document.getElementById('paket_id').value;
    const tgl     = document.getElementById('tanggal').value;
    const jMulai  = document.getElementById('jam_mulai').value;
    const jSelesai= document.getElementById('jam_selesai').value;

    if (!paketId)  { showToast('Pilih paket belajar terlebih dahulu.', 'warning'); return; }
    if (!tgl)      { showToast('Tanggal wajib diisi.', 'warning'); return; }
    if (!jMulai || !jSelesai) { showToast('Jam mulai dan selesai wajib diisi.', 'warning'); return; }

    const payload = {
        _token:       document.querySelector('meta[name=csrf-token]').content,
        paket_id:     paketId,
        jenis:        document.getElementById('sc_jenis').value || 'offline',
        tanggal:      tgl,
        jam_mulai:    jMulai,
        jam_selesai:  jSelesai,
        ruangan:      document.getElementById('ruangan').value || null,
        link_meeting: document.getElementById('link_meeting').value || null,
    };
    if (id) { payload._method = 'PUT'; payload.status = document.getElementById('sc_status').value; }

    const btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

    $.ajax({
        url, method: 'POST', data: payload,
        success(res) {
            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('scheduleModal'))?.hide();
                showToast(res.message, 'success');
                setTimeout(() => location.reload(), 1200);
            } else {
                showToast(res.message || 'Terjadi kesalahan.', 'error');
            }
        },
        error(xhr) {
            const errors = xhr.responseJSON?.errors;
            const msg = errors ? Object.values(errors).flat().join('; ') : (xhr.responseJSON?.message ?? 'Terjadi kesalahan.');
            showToast(msg, 'error');
        },
        complete() { btn.disabled = false; btn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Simpan Jadwal'; }
    });
}

function showDetail(id) {
    document.getElementById('detailBody').innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';
    new bootstrap.Modal('#detailModal').show();
    $.get('/admin/schedules/' + id, function(res) {
        const s = res.data;
        const statusMap = {
            dijadwalkan:'rgba(200,77,223,.15):#c84ddf:Dijadwalkan',
            berlangsung:'rgba(246,175,35,.15):#e09000:Berlangsung',
            selesai:'rgba(16,185,129,.15):#16a34a:Selesai',
            dibatalkan:'rgba(239,68,68,.15):#dc2626:Dibatalkan'
        };
        const [sbg,scol,slbl] = (statusMap[s.status]||'rgba(148,163,184,.15):#64748b:'+s.status).split(':');
        const tgl = s.tanggal ? s.tanggal.substr(0,10) : '–';
        const mapelNames = s.mata_pelajaran?.nama ?? s.paket?.mata_pelajaran?.map(m => m.nama).join(', ') ?? '–';
        document.getElementById('detailBody').innerHTML = `
            <div style="padding:20px">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="fw-bold" style="font-size:15px">${s.paket?.nama ?? 'Paket'}</div>
                        <div style="font-size:12px;color:var(--text-muted)">${tgl} · ${fmtWib(s.jam_mulai)} – ${fmtWib(s.jam_selesai)}</div>
                    </div>
                    <span style="background:${sbg};color:${scol};padding:4px 12px;border-radius:8px;font-size:12px;font-weight:600">${slbl}</span>
                </div>
                <table style="width:100%;border-collapse:collapse;font-size:13px">
                    ${drow('Paket', s.paket?.nama ?? '–')}
                    ${drow('Mata Pelajaran', mapelNames)}
                    ${drow('Guru', s.guru?.name ?? s.paket?.guru?.name ?? '–')}
                    ${drow('Cabang', s.paket?.cabang?.name ?? s.cabang?.name ?? '–')}
                    ${drow('Jenis', s.paket?.jenis ? (s.paket.jenis.charAt(0).toUpperCase() + s.paket.jenis.slice(1)) : '–')}
                    ${s.link_meeting ? drow('Link Meeting', '<a href="'+s.link_meeting+'" target="_blank">Buka Link</a>') : drow('Ruangan', s.ruangan || '–')}
                </table>
            </div>
        `;
    }).fail(() => { document.getElementById('detailBody').innerHTML = '<div class="text-center py-5 text-muted">Gagal memuat data</div>'; });
}

function drow(label, val) {
    return `<tr style="border-bottom:1px solid var(--card-border)">
        <td style="padding:7px 4px 7px 0;color:var(--text-muted);font-size:12px;width:38%">${label}</td>
        <td style="padding:7px 0;font-size:13px;font-weight:500;color:var(--text-primary)">${val}</td>
    </tr>`;
}

function deleteSchedule(id, paket, sesi) {
    confirmAction(`Hapus jadwal sesi ke-${sesi} dari paket "${paket}"?`, function() {
        $.post('/admin/schedules/' + id, {
            _method: 'DELETE',
            _token:  document.querySelector('meta[name=csrf-token]').content
        }, function(res) {
            if (res.success) { showToast(res.message, 'success'); setTimeout(() => location.reload(), 1200); }
        }).fail(() => showToast('Tidak dapat menghapus jadwal.', 'error'));
    }, null, {title:'Hapus Jadwal', okText:'Ya, Hapus'});
}
</script>
@endpush
