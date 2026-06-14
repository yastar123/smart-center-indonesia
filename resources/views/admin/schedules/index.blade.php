@extends('layouts.app')
@section('title', 'Jadwal Kelas')
@section('page-title', 'Jadwal Kelas')

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
                    <h5 class="fw-bold mb-0" style="color:white">Jadwal Pertemuan Kelas</h5>
                    <span style="font-size:12px;opacity:.8">Atur jadwal setiap pertemuan berdasarkan kelas yang sudah dibuat</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <button onclick="openModal()" class="btn fw-semibold px-4"
                style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                <i class="bi bi-plus-lg me-2"></i>Tambah Jadwal
            </button>
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
                <label class="form-label fw-semibold" style="font-size:12px">Cari Kelas / Topik / Ruangan</label>
                <div class="input-group">
                    <span class="input-group-text" style="border-radius:10px 0 0 10px;background:var(--input-bg);border-color:var(--card-border)">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari jadwal..."
                        class="form-control" style="border-radius:0 10px 10px 0;border-color:var(--card-border);background:var(--input-bg)"
                        onchange="document.getElementById('filterForm').submit()">
                </div>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label fw-semibold" style="font-size:12px">Kelas</label>
                <select name="kelas_id" class="form-select" style="border-radius:10px"
                    onchange="document.getElementById('filterForm').submit()">
                    <option value="">Semua Kelas</option>
                    @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ request('kelas_id')==$c->id?'selected':'' }}>{{ $c->nama_kelas }}</option>
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
                <label class="form-label fw-semibold" style="font-size:12px">Jenis</label>
                <select name="jenis" class="form-select" style="border-radius:10px"
                    onchange="document.getElementById('filterForm').submit()">
                    <option value="">Semua</option>
                    <option value="offline" {{ request('jenis')=='offline'?'selected':'' }}>Offline</option>
                    <option value="online"  {{ request('jenis')=='online'?'selected':'' }}>Online</option>
                    <option value="private" {{ request('jenis')=='private'?'selected':'' }}>Private</option>
                </select>
            </div>
            <div class="col-12 col-md-1">
                @if(request()->hasAny(['search','status','kelas_id','jenis','tanggal']))
                <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary w-100" style="border-radius:10px" title="Reset">
                    <i class="bi bi-x-lg"></i>
                </a>
                @else
                <button type="button" onclick="openModal()" class="btn btn-primary w-100 fw-semibold" style="border-radius:10px">
                    <i class="bi bi-plus-lg"></i>
                </button>
                @endif
            </div>
        </div>
    </form>
</div>

{{-- TABLE --}}
<div class="dashboard-card fade-up">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-list-ul text-primary me-2"></i>Daftar Jadwal Pertemuan
            <span class="badge ms-2" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);font-size:11px">{{ $schedules->total() }} data</span>
        </h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-modern align-middle mb-0">
            <thead class="thead-modern">
                <tr>
                    <th class="ps-3">Kelas</th>
                    <th>Pertemuan</th>
                    <th>Tanggal &amp; Waktu</th>
                    <th class="d-none d-md-table-cell">Jenis</th>
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
                @endphp
                <tr style="border-bottom:1px solid var(--card-border);transition:background .15s{{ $isToday ? ';background:rgba(104,17,126,.03)' : '' }}">
                    <td class="ps-3">
                        <div class="fw-semibold" style="font-size:13px">{{ $sc->kelas?->nama_kelas ?? '–' }}</div>
                        <div class="text-muted" style="font-size:11px">
                            <i class="bi bi-person me-1"></i>{{ $sc->kelas?->guru?->name ?? '–' }}
                        </div>
                    </td>
                    <td>
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:50%;background:var(--soft-primary-bg);color:var(--soft-primary-text);font-size:13px;font-weight:700">
                            {{ $sc->pertemuan_ke ?? '–' }}
                        </span>
                    </td>
                    <td>
                        <div class="fw-semibold" style="font-size:13px">
                            {{ $sc->tanggal ? $sc->tanggal->format('d M Y') : '–' }}
                            @if($isToday)<span class="badge ms-1" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);font-size:10px">Hari ini</span>@endif
                        </div>
                        @if($sc->tanggal_selesai && $sc->tanggal_selesai != $sc->tanggal)
                        <div class="text-muted" style="font-size:11px">s/d {{ $sc->tanggal_selesai->format('d M Y') }}</div>
                        @endif
                        <div class="text-muted" style="font-size:11px"><i class="bi bi-clock me-1"></i>{{ str_replace(':', '.', substr($sc->jam_mulai ?? '', 0, 5)) ?: '–' }} – {{ str_replace(':', '.', substr($sc->jam_selesai ?? '', 0, 5)) ?: '–' }} WIB</div>
                    </td>
                    <td class="d-none d-md-table-cell">
                        @if($sc->jenis === 'online')
                        <span class="badge rounded-pill" style="background:var(--soft-success-bg);color:var(--soft-success-text);font-size:11px;font-weight:600"><i class="bi bi-wifi me-1"></i>Online</span>
                        @elseif($sc->jenis === 'private')
                        <span class="badge rounded-pill" style="background:var(--soft-warning-bg);color:var(--soft-warning-text);font-size:11px;font-weight:600"><i class="bi bi-person-lock me-1"></i>Private</span>
                        @else
                        <span class="badge rounded-pill" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);font-size:11px;font-weight:600"><i class="bi bi-building me-1"></i>Offline</span>
                        @endif
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
                            <button onclick="deleteSchedule({{ $sc->id }}, '{{ addslashes($sc->kelas?->nama_kelas ?? 'Jadwal ini') }}', {{ $sc->pertemuan_ke ?? 0 }})" class="btn btn-sm btn-act-del" title="Hapus"><i class="bi bi-trash-fill"></i></button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <div class="text-muted">
                            <i class="bi bi-calendar-x" style="font-size:40px;display:block;margin-bottom:12px;opacity:.4"></i>
                            <div class="fw-semibold mb-1">Belum ada jadwal pertemuan</div>
                            <div style="font-size:12px">Klik "Tambah Jadwal" untuk menjadwalkan pertemuan kelas</div>
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
                <h6 class="modal-title fw-bold" id="modalTitle"><i class="bi bi-calendar-plus me-2"></i>Tambah Jadwal Pertemuan</h6>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="background:var(--input-bg)">
                <input type="hidden" id="scheduleId">

                {{-- INFO KELAS --}}
                <div class="mb-3 p-3 rounded-3" id="classInfoBox" style="background:var(--soft-primary-bg);border:1.5px solid var(--soft-primary-border);display:none">
                    <div class="d-flex gap-3 align-items-center">
                        <div style="width:40px;height:40px;border-radius:10px;background:var(--primary);display:flex;align-items:center;justify-content:center;color:white;flex-shrink:0"><i class="bi bi-building-fill"></i></div>
                        <div style="flex:1">
                            <div class="fw-bold" id="classInfoName" style="font-size:14px;color:var(--soft-primary-text)"></div>
                            <div style="font-size:12px;color:var(--text-muted)" id="classInfoMeta"></div>
                        </div>
                        <div id="classInfoProgress" style="text-align:right">
                            <div style="font-size:12px;color:var(--text-muted)">Pertemuan terjadwal</div>
                            <div class="fw-bold" id="classInfoCount" style="font-size:15px;color:var(--soft-primary-text)">– / –</div>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size:12px">Kelas <span class="text-danger">*</span></label>
                        <select id="kelas_id" class="form-select" onchange="onKelasChange(this.value)">
                            <option value="">— Pilih Kelas —</option>
                            @foreach($classes as $c)
                            <option value="{{ $c->id }}"
                                data-nama="{{ $c->nama_kelas }}"
                                data-guru="{{ $c->guru?->name ?? '–' }}"
                                data-cabang="{{ $c->cabang?->name ?? '–' }}"
                                data-mapel="{{ $c->mataPelajaran?->nama ?? '–' }}"
                                data-jenis="{{ $c->jenis }}"
                                data-jumlah="{{ $c->jumlah_pertemuan }}"
                                data-link="{{ $c->link_zoom ?? '' }}">
                                {{ $c->nama_kelas }} — {{ $c->mataPelajaran?->nama ?? '' }} ({{ $c->guru?->name ?? 'belum ada guru' }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" style="font-size:12px">Pertemuan Ke <span class="text-danger">*</span></label>
                        <select id="pertemuan_ke" class="form-select">
                            <option value="">— Pilih dulu kelas —</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" style="font-size:12px">Jenis <span class="text-danger">*</span></label>
                        <select id="jenis" class="form-select" onchange="toggleJenis()">
                            <option value="offline">📍 Offline</option>
                            <option value="online">🌐 Online</option>
                            <option value="private">🔒 Private</option>
                        </select>
                    </div>
                    <div class="col-md-4" id="statusScWrap" style="display:none">
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
                    {{-- Tanggal Selesai dihapus sesuai permintaan --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:12px">Jam Mulai <span class="text-danger">*</span></label>
                        <input type="text" id="jam_mulai" class="form-control flatpickr-time-input" placeholder="13:30" autocomplete="off" style="border-radius:10px">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:12px">Jam Selesai <span class="text-danger">*</span></label>
                        <input type="text" id="jam_selesai" class="form-control flatpickr-time-input" placeholder="15:00" autocomplete="off" style="border-radius:10px">
                    </div>
                    <div class="col-12" id="ruanganField">
                        <label class="form-label fw-semibold" style="font-size:12px">Ruangan <span class="text-muted">(opsional)</span></label>
                        <input type="text" id="ruangan" class="form-control" placeholder="cth: Ruang A1, Lab Komputer..." style="border-radius:10px">
                    </div>
                    <div class="col-12" id="linkField" style="display:none">
                        <label class="form-label fw-semibold" style="font-size:12px">Link Meeting</label>
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
                <h6 class="modal-title fw-bold"><i class="bi bi-calendar-event me-2"></i>Detail Jadwal Pertemuan</h6>
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
// Initialize 24-hour time pickers
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

// Cache kelas data dari server
const kelasData = {};
@foreach($classes as $c)
kelasData[{{ $c->id }}] = {
    nama: @json($c->nama_kelas),
    guru: @json($c->guru?->name ?? '–'),
    cabang: @json($c->cabang?->name ?? '–'),
    mapel: @json($c->mataPelajaran?->nama ?? '–'),
    jenis: @json($c->jenis),
    jumlah: {{ $c->jumlah_pertemuan }},
    link: @json($c->link_zoom ?? '')
};
@endforeach

function toggleJenis() {
    const jenis = document.getElementById('jenis').value;
    document.getElementById('ruanganField').style.display = (jenis === 'offline' || jenis === 'private') ? 'block' : 'none';
    document.getElementById('linkField').style.display    = jenis === 'online' ? 'block' : 'none';
}

function onKelasChange(kelasId, currentPertemuan) {
    const box = document.getElementById('classInfoBox');
    const sel = document.getElementById('pertemuan_ke');

    if (!kelasId) {
        box.style.display = 'none';
        sel.innerHTML = '<option value="">— Pilih dulu kelas —</option>';
        return;
    }

    const k = kelasData[kelasId];
    if (!k) return;

    // Info box
    document.getElementById('classInfoName').textContent = k.nama + ' — ' + k.mapel;
    document.getElementById('classInfoMeta').textContent = 'Guru: ' + k.guru + ' | Cabang: ' + k.cabang + ' | Jenis: ' + k.jenis;
    box.style.display = 'block';

    // Jenis sesuai kelas
    document.getElementById('jenis').value = k.jenis;
    toggleJenis();

    // Link from kelas (for online)
    if (k.link) document.getElementById('link_meeting').value = k.link;

    // Load already scheduled pertemuan for this class
    fetch(`/admin/schedules?kelas_id=${kelasId}&all=1`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(data => {
            const used = (data.data || []).map(s => s.pertemuan_ke);
            document.getElementById('classInfoCount').textContent = used.length + ' / ' + k.jumlah;

            sel.innerHTML = '';
            for (let i = 1; i <= k.jumlah; i++) {
                const opt = document.createElement('option');
                opt.value = i;
                const alreadyScheduled = used.includes(i) && i !== Number(currentPertemuan);
                opt.textContent = 'Pertemuan ke-' + i + (alreadyScheduled ? ' (sudah dijadwalkan)' : '');
                if (alreadyScheduled) opt.style.color = 'var(--text-muted)';
                if (i === Number(currentPertemuan)) opt.selected = true;
                sel.appendChild(opt);
            }
        })
        .catch(() => {
            sel.innerHTML = '';
            for (let i = 1; i <= k.jumlah; i++) {
                const opt = document.createElement('option');
                opt.value = i;
                opt.textContent = 'Pertemuan ke-' + i;
                if (i === Number(currentPertemuan)) opt.selected = true;
                sel.appendChild(opt);
            }
        });
}

function openModal() {
    document.getElementById('scheduleId').value = '';
    document.getElementById('modalTitle').innerHTML = '<i class="bi bi-calendar-plus me-2"></i>Tambah Jadwal Pertemuan';
    document.getElementById('kelas_id').value   = '';
    document.getElementById('pertemuan_ke').innerHTML = '<option value="">— Pilih dulu kelas —</option>';
    document.getElementById('jenis').value      = 'offline';
    document.getElementById('tanggal').value    = '';
    document.getElementById('jam_mulai').value  = '';
    document.getElementById('jam_selesai').value= '';
    document.getElementById('ruangan').value    = '';
    document.getElementById('link_meeting').value = '';
    document.getElementById('classInfoBox').style.display = 'none';
    document.getElementById('statusScWrap').style.display = 'none';
    toggleJenis();
    new bootstrap.Modal('#scheduleModal').show();
}

function editSchedule(id) {
    $.get('/admin/schedules/' + id, function(res) {
        const s = res.data;
        document.getElementById('modalTitle').innerHTML = '<i class="bi bi-pencil me-2"></i>Edit Jadwal Pertemuan';
        document.getElementById('scheduleId').value    = s.id;
        document.getElementById('kelas_id').value      = s.kelas_id ?? '';
        document.getElementById('jenis').value         = s.jenis ?? 'offline';
        document.getElementById('tanggal').value       = s.tanggal ? s.tanggal.substr(0,10) : '';
        document.getElementById('jam_mulai').value     = s.jam_mulai ? s.jam_mulai.substr(0,5) : '';
        document.getElementById('jam_selesai').value   = s.jam_selesai ? s.jam_selesai.substr(0,5) : '';
        document.getElementById('ruangan').value       = s.ruangan ?? '';
        document.getElementById('link_meeting').value  = s.link_meeting ?? '';
        document.getElementById('sc_status').value     = s.status ?? 'dijadwalkan';
        document.getElementById('statusScWrap').style.display = 'block';
        toggleJenis();
        onKelasChange(s.kelas_id, s.pertemuan_ke);
        new bootstrap.Modal('#scheduleModal').show();
    }).fail(() => showToast('Tidak dapat memuat data jadwal.', 'error'));
}

function saveSchedule() {
    const id  = document.getElementById('scheduleId').value;
    const url = id ? '/admin/schedules/' + id : '{{ route("admin.schedules.store") }}';

    if (!document.getElementById('kelas_id').value) { showToast('Pilih kelas terlebih dahulu.', 'warning'); return; }
    if (!document.getElementById('pertemuan_ke').value) { showToast('Pilih pertemuan ke berapa.', 'warning'); return; }
    if (!document.getElementById('tanggal').value) { showToast('Tanggal wajib diisi.', 'warning'); return; }
    if (!document.getElementById('jam_mulai').value || !document.getElementById('jam_selesai').value) { showToast('Jam mulai dan selesai wajib diisi.', 'warning'); return; }

    const payload = {
        _token:       document.querySelector('meta[name=csrf-token]').content,
        kelas_id:     document.getElementById('kelas_id').value,
        pertemuan_ke: document.getElementById('pertemuan_ke').value,
        jenis:        document.getElementById('jenis').value,
        tanggal:      document.getElementById('tanggal').value,
        jam_mulai:    document.getElementById('jam_mulai').value,
        jam_selesai:  document.getElementById('jam_selesai').value,
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
        const tglSelesai = s.tanggal_selesai && s.tanggal_selesai !== s.tanggal ? ' s/d ' + s.tanggal_selesai.substr(0,10) : '';
        document.getElementById('detailBody').innerHTML = `
            <div style="padding:20px">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="fw-bold" style="font-size:15px">${s.kelas?.nama_kelas ?? 'Kelas'} — Pertemuan ke-${s.pertemuan_ke ?? '?'}</div>
                        <div style="font-size:12px;color:var(--text-muted)">${tgl}${tglSelesai} · ${fmtWib(s.jam_mulai)} – ${fmtWib(s.jam_selesai)}</div>
                    </div>
                    <span style="background:${sbg};color:${scol};padding:4px 12px;border-radius:8px;font-size:12px;font-weight:600">${slbl}</span>
                </div>
                <table style="width:100%;border-collapse:collapse;font-size:13px">
                    ${drow('Kelas', s.kelas?.nama_kelas ?? '–')}
                    ${drow('Guru', s.kelas?.guru?.name ?? '–')}
                    ${drow('Mata Pelajaran', s.kelas?.mata_pelajaran?.nama ?? '–')}
                    ${drow('Cabang', s.kelas?.cabang?.name ?? '–')}
                    ${drow('Jenis', s.jenis === 'online' ? '🌐 Online' : (s.jenis === 'private' ? '🔒 Private' : '📍 Offline'))}
                    ${s.jenis === 'online'
                        ? drow('Link Meeting', s.link_meeting ? '<a href="'+s.link_meeting+'" target="_blank">Buka Link</a>' : '–')
                        : drow('Ruangan', s.ruangan || '–')}
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

function deleteSchedule(id, kelas, pertemuan) {
    confirmAction(`Hapus jadwal pertemuan ke-${pertemuan} kelas "${kelas}"?`, function() {
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
