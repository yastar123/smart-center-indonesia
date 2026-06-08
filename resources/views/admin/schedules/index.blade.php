@extends('layouts.app')
@section('title', 'Jadwal Kelas')
@section('page-title', 'Jadwal Kelas')

@section('content')

{{-- HEADER BANNER --}}
<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#461256,#461256,#68117e);color:white;border:none">
    <div class="row align-items-center g-3">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px">
                    <i class="bi bi-calendar-week-fill"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Manajemen Jadwal Kelas</h5>
                    <span style="font-size:12px;opacity:.8">Kelola jadwal mengajar & sesi bimbingan</span>
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
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Jadwal</div>
                    <div class="stat-value">{{ $stats['total'] }}</div>
                    <div class="stat-growth text-muted"><i class="bi bi-calendar me-1"></i>Semua jadwal</div>
                </div>
                <div class="stat-icon" style="background:#fdf4ff;color:#68117e"><i class="bi bi-calendar-week"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Hari Ini</div>
                    <div class="stat-value text-warning">{{ $stats['hari_ini'] }}</div>
                    <div class="stat-growth text-warning"><i class="bi bi-sun me-1"></i>Jadwal hari ini</div>
                </div>
                <div class="stat-icon" style="background:#fffbeb;color:#e09000"><i class="bi bi-sun-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Dijadwalkan</div>
                    <div class="stat-value text-primary">{{ $stats['dijadwalkan'] }}</div>
                    <div class="stat-growth text-primary"><i class="bi bi-clock me-1"></i>Akan datang</div>
                </div>
                <div class="stat-icon" style="background:#fdf4ff;color:#68117e"><i class="bi bi-clock-history"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Selesai</div>
                    <div class="stat-value text-success">{{ $stats['selesai'] }}</div>
                    <div class="stat-growth text-success"><i class="bi bi-check-circle me-1"></i>Telah berlangsung</div>
                </div>
                <div class="stat-icon" style="background:#f0fdf4;color:#16a34a"><i class="bi bi-check-circle-fill"></i></div>
            </div>
        </div>
    </div>
</div>

{{-- FILTER BAR --}}
<div class="dashboard-card mb-4 fade-up">
    <form id="filterForm" method="GET" action="{{ route('admin.schedules.index') }}">
        <div class="row g-2 align-items-end">
            <div class="col-12 col-md-3">
                <label class="form-label fw-semibold" style="font-size:12px">Cari Topik / Guru / Ruangan</label>
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
                <label class="form-label fw-semibold" style="font-size:12px">Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-control"
                    style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)"
                    onchange="document.getElementById('filterForm').submit()">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label fw-semibold" style="font-size:12px">Status</label>
                <select name="status" class="form-select" style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)"
                    onchange="document.getElementById('filterForm').submit()">
                    <option value="">Semua</option>
                    <option value="dijadwalkan" {{ request('status')=='dijadwalkan'?'selected':'' }}>Dijadwalkan</option>
                    <option value="berlangsung" {{ request('status')=='berlangsung'?'selected':'' }}>Berlangsung</option>
                    <option value="selesai"     {{ request('status')=='selesai'?'selected':'' }}>Selesai</option>
                    <option value="dibatalkan"  {{ request('status')=='dibatalkan'?'selected':'' }}>Dibatalkan</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label fw-semibold" style="font-size:12px">Jenis</label>
                <select name="jenis" class="form-select" style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)"
                    onchange="document.getElementById('filterForm').submit()">
                    <option value="">Semua</option>
                    <option value="offline" {{ request('jenis')=='offline'?'selected':'' }}>Offline</option>
                    <option value="online"  {{ request('jenis')=='online'?'selected':'' }}>Online</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label fw-semibold" style="font-size:12px">Cabang</label>
                <select name="branch_id" class="form-select" style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)"
                    onchange="document.getElementById('filterForm').submit()">
                    <option value="">Semua</option>
                    @foreach($branches as $b)
                    <option value="{{ $b->id }}" {{ request('branch_id')==$b->id?'selected':'' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-1">
                @if(request()->hasAny(['search','status','branch_id','jenis','tanggal']))
                <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary w-100" style="border-radius:10px" title="Reset Filter">
                    <i class="bi bi-x-lg"></i>
                </a>
                @else
                <button type="button" onclick="openModal()" class="btn btn-primary w-100 fw-semibold" style="border-radius:10px" title="Tambah">
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
        <h6 class="fw-bold mb-0"><i class="bi bi-list-ul text-primary me-2"></i>Daftar Jadwal
            <span class="badge ms-2" style="background:#fdf4ff;color:#68117e;font-size:11px">{{ $schedules->total() }} data</span>
        </h6>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0" style="font-size:13px">
            <thead>
                <tr style="background:var(--input-bg);border-bottom:2px solid var(--card-border)">
                    <th class="ps-3" style="font-weight:700;font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);padding:12px 8px">Tanggal & Waktu</th>
                    <th style="font-weight:700;font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);padding:12px 8px">Topik</th>
                    <th style="font-weight:700;font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);padding:12px 8px">Guru</th>
                    <th style="font-weight:700;font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);padding:12px 8px">Kelas</th>
                    <th style="font-weight:700;font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);padding:12px 8px">Jenis</th>
                    <th style="font-weight:700;font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);padding:12px 8px">Cabang</th>
                    <th style="font-weight:700;font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);padding:12px 8px">Status</th>
                    <th class="text-center" style="font-weight:700;font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);padding:12px 8px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $sc)
                @php
                    $statusMap = [
                        'dijadwalkan' => ['bg'=>'#fdf4ff','color'=>'#68117e','label'=>'Dijadwalkan'],
                        'berlangsung' => ['bg'=>'#fffbeb','color'=>'#e09000','label'=>'Berlangsung'],
                        'selesai'     => ['bg'=>'#f0fdf4','color'=>'#16a34a','label'=>'Selesai'],
                        'dibatalkan'  => ['bg'=>'#fef2f2','color'=>'#dc2626','label'=>'Dibatalkan'],
                    ];
                    $st = $statusMap[$sc->status] ?? ['bg'=>'#f1f5f9','color'=>'#64748b','label'=>$sc->status];
                    $isToday = $sc->tanggal && $sc->tanggal->isToday();
                @endphp
                <tr style="border-bottom:1px solid var(--card-border);transition:background .15s{{ $isToday ? ';background:rgba(104,17,126,.03)' : '' }}" onmouseover="this.style.background='rgba(104,17,126,.05)'" onmouseout="this.style.background='{{ $isToday ? 'rgba(104,17,126,.03)' : '' }}'">
                    <td class="ps-3">
                        <div class="fw-semibold" style="font-size:13px">
                            {{ $sc->tanggal ? $sc->tanggal->format('d M Y') : '–' }}
                            @if($isToday)<span class="badge ms-1" style="background:#f3d6fa;color:#461256;font-size:10px">Hari ini</span>@endif
                        </div>
                        <div style="font-size:11px;color:#6b7280">
                            <i class="bi bi-clock me-1"></i>{{ substr($sc->jam_mulai,0,5) ?? '–' }} – {{ substr($sc->jam_selesai,0,5) ?? '–' }}
                        </div>
                    </td>
                    <td>
                        <div class="fw-semibold" style="font-size:13px">{{ $sc->topik ?: '–' }}</div>
                        @if($sc->ruangan)<div style="font-size:11px;color:#6b7280"><i class="bi bi-door-open me-1"></i>{{ $sc->ruangan }}</div>@endif
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:30px;height:30px;border-radius:50%;background:#f0fdf4;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#16a34a;flex-shrink:0">
                                {{ strtoupper(substr($sc->guru?->name ?? 'G', 0, 1)) }}
                            </div>
                            <span>{{ $sc->guru?->name ?? '–' }}</span>
                        </div>
                    </td>
                    <td style="color:#6b7280">{{ $sc->kelas?->nama ?? '–' }}</td>
                    <td>
                        @if($sc->jenis === 'online')
                        <span style="background:#f0fdf4;color:#16a34a;padding:3px 9px;border-radius:7px;font-size:11px;font-weight:600">
                            <i class="bi bi-wifi me-1"></i>Online
                        </span>
                        @else
                        <span style="background:#fdf4ff;color:#68117e;padding:3px 9px;border-radius:7px;font-size:11px;font-weight:600">
                            <i class="bi bi-building me-1"></i>Offline
                        </span>
                        @endif
                    </td>
                    <td style="color:#6b7280">{{ $sc->cabang?->name ?? '–' }}</td>
                    <td>
                        <span style="background:{{ $st['bg'] }};color:{{ $st['color'] }};padding:4px 10px;border-radius:8px;font-size:11px;font-weight:600">
                            {{ $st['label'] }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            <button onclick="showDetail({{ $sc->id }})" class="btn btn-sm" title="Detail"
                                style="background:#fdf4ff;color:#68117e;border:none;border-radius:8px;width:32px;height:32px;padding:0">
                                <i class="bi bi-eye-fill" style="font-size:13px"></i>
                            </button>
                            <button onclick="editSchedule({{ $sc->id }})" class="btn btn-sm" title="Edit"
                                style="background:#fffbeb;color:#e09000;border:none;border-radius:8px;width:32px;height:32px;padding:0">
                                <i class="bi bi-pencil-fill" style="font-size:13px"></i>
                            </button>
                            <button onclick="deleteSchedule({{ $sc->id }}, '{{ addslashes($sc->topik ?? 'Jadwal ini') }}')" class="btn btn-sm" title="Hapus"
                                style="background:#fef2f2;color:#dc2626;border:none;border-radius:8px;width:32px;height:32px;padding:0">
                                <i class="bi bi-trash-fill" style="font-size:13px"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <div style="color:#9ca3af">
                            <i class="bi bi-calendar-x" style="font-size:40px;display:block;margin-bottom:12px;opacity:.4"></i>
                            <div class="fw-semibold mb-1">Belum ada jadwal</div>
                            <div style="font-size:12px">Klik "Tambah Jadwal" untuk membuat jadwal pertama</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($schedules->hasPages())
    <div class="mt-4 d-flex justify-content-center">
        {{ $schedules->links() }}
    </div>
    @endif
</div>

{{-- MODAL ADD/EDIT --}}
<div class="modal fade" id="scheduleModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius:18px;overflow:hidden">
            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#461256,#68117e);color:#fff">
                <h6 class="modal-title fw-bold" id="modalTitle"><i class="bi bi-calendar-plus me-2"></i>Tambah Jadwal Baru</h6>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="background:var(--input-bg)">
                <input type="hidden" id="scheduleId">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:12px">Guru <span class="text-danger">*</span></label>
                        <select id="guru_id" class="form-select" style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                            <option value="">— Pilih Guru —</option>
                            @foreach($teachers as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:12px">Cabang <span class="text-danger">*</span></label>
                        <select id="sc_cabang_id" class="form-select" style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                            <option value="">— Pilih Cabang —</option>
                            @foreach($branches as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:12px">Kelas</label>
                        <select id="kelas_id" class="form-select" style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                            <option value="">— Pilih Kelas —</option>
                            @foreach($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->nama ?? 'Kelas #'.$c->id }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:12px">Jenis <span class="text-danger">*</span></label>
                        <select id="jenis" class="form-select" style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)"
                            onchange="toggleJenis()">
                            <option value="offline">📍 Offline</option>
                            <option value="online">🌐 Online</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" style="font-size:12px">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" id="tanggal" class="form-control"
                            style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" style="font-size:12px">Jam Mulai <span class="text-danger">*</span></label>
                        <input type="time" id="jam_mulai" class="form-control"
                            style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" style="font-size:12px">Jam Selesai <span class="text-danger">*</span></label>
                        <input type="time" id="jam_selesai" class="form-control"
                            style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size:12px">Topik / Materi</label>
                        <input type="text" id="topik" class="form-control" placeholder="cth: Matematika - Persamaan Kuadrat"
                            style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                    </div>
                    <div class="col-12" id="ruanganField">
                        <label class="form-label fw-semibold" style="font-size:12px">Ruangan</label>
                        <input type="text" id="ruangan" class="form-control" placeholder="cth: Ruang A1"
                            style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                    </div>
                    <div class="col-12" id="linkField" style="display:none">
                        <label class="form-label fw-semibold" style="font-size:12px">Link Meeting</label>
                        <input type="text" id="link_meeting" class="form-control" placeholder="https://meet.google.com/..."
                            style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                    </div>
                    <div class="col-12" id="statusScField" style="display:none">
                        <label class="form-label fw-semibold" style="font-size:12px">Status</label>
                        <select id="sc_status" class="form-select" style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                            <option value="dijadwalkan">Dijadwalkan</option>
                            <option value="berlangsung">Berlangsung</option>
                            <option value="selesai">Selesai</option>
                            <option value="dibatalkan">Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size:12px">Catatan</label>
                        <textarea id="sc_catatan" class="form-control" rows="2" placeholder="Catatan tambahan..."
                            style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4" style="background:var(--input-bg)">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius:10px">
                    <i class="bi bi-x me-1"></i>Batal
                </button>
                <button type="button" class="btn btn-primary px-5 fw-semibold" id="saveBtn" onclick="saveSchedule()" style="border-radius:10px">
                    <i class="bi bi-check-lg me-1"></i>Simpan Jadwal
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DETAIL --}}
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:18px;overflow:hidden">
            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#461256,#68117e);color:#fff">
                <h6 class="modal-title fw-bold"><i class="bi bi-calendar-event me-2"></i>Detail Jadwal</h6>
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
<script>
function toggleJenis() {
    const jenis = document.getElementById('jenis').value;
    document.getElementById('ruanganField').style.display = jenis === 'offline' ? 'block' : 'none';
    document.getElementById('linkField').style.display    = jenis === 'online'  ? 'block' : 'none';
}

function openModal() {
    document.getElementById('scheduleId').value   = '';
    document.getElementById('modalTitle').innerHTML = '<i class="bi bi-calendar-plus me-2"></i>Tambah Jadwal Baru';
    document.getElementById('guru_id').value      = '';
    document.getElementById('sc_cabang_id').value = '';
    document.getElementById('kelas_id').value     = '';
    document.getElementById('jenis').value        = 'offline';
    document.getElementById('tanggal').value      = '';
    document.getElementById('jam_mulai').value    = '';
    document.getElementById('jam_selesai').value  = '';
    document.getElementById('topik').value        = '';
    document.getElementById('ruangan').value      = '';
    document.getElementById('link_meeting').value = '';
    document.getElementById('sc_catatan').value   = '';
    document.getElementById('statusScField').style.display = 'none';
    toggleJenis();
    new bootstrap.Modal('#scheduleModal').show();
}

function editSchedule(id) {
    $.get('/admin/schedules/' + id, function(res) {
        const s = res.data;
        document.getElementById('modalTitle').innerHTML = '<i class="bi bi-pencil me-2"></i>Edit Jadwal';
        document.getElementById('scheduleId').value   = s.id;
        document.getElementById('guru_id').value      = s.guru_id   ?? '';
        document.getElementById('sc_cabang_id').value = s.cabang_id ?? '';
        document.getElementById('kelas_id').value     = s.kelas_id  ?? '';
        document.getElementById('jenis').value        = s.jenis     ?? 'offline';
        document.getElementById('tanggal').value      = s.tanggal   ? s.tanggal.substr(0,10) : '';
        document.getElementById('jam_mulai').value    = s.jam_mulai   ? s.jam_mulai.substr(0,5) : '';
        document.getElementById('jam_selesai').value  = s.jam_selesai ? s.jam_selesai.substr(0,5) : '';
        document.getElementById('topik').value        = s.topik        ?? '';
        document.getElementById('ruangan').value      = s.ruangan      ?? '';
        document.getElementById('link_meeting').value = s.link_meeting ?? '';
        document.getElementById('sc_catatan').value   = s.catatan      ?? '';
        document.getElementById('sc_status').value    = s.status       ?? 'dijadwalkan';
        document.getElementById('statusScField').style.display = 'block';
        toggleJenis();
        new bootstrap.Modal('#scheduleModal').show();
    }).fail(() => Swal.fire({icon:'error', title:'Gagal', text:'Tidak dapat memuat data.'}));
}

function saveSchedule() {
    const id  = document.getElementById('scheduleId').value;
    const url = id ? '/admin/schedules/' + id : '{{ route("admin.schedules.store") }}';
    const payload = {
        _token:       document.querySelector('meta[name=csrf-token]').content,
        guru_id:      document.getElementById('guru_id').value,
        cabang_id:    document.getElementById('sc_cabang_id').value,
        kelas_id:     document.getElementById('kelas_id').value     || null,
        jenis:        document.getElementById('jenis').value,
        tanggal:      document.getElementById('tanggal').value,
        jam_mulai:    document.getElementById('jam_mulai').value,
        jam_selesai:  document.getElementById('jam_selesai').value,
        topik:        document.getElementById('topik').value,
        ruangan:      document.getElementById('ruangan').value,
        link_meeting: document.getElementById('link_meeting').value,
        catatan:      document.getElementById('sc_catatan').value,
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
                Swal.fire({icon:'success', title:'Berhasil!', text:res.message, timer:2000, showConfirmButton:false})
                    .then(() => location.reload());
            }
        },
        error(xhr) {
            const errors = xhr.responseJSON?.errors;
            const msg = errors
                ? '<ul class="text-start mb-0">'+Object.values(errors).flat().map(e=>`<li>${e}</li>`).join('')+'</ul>'
                : (xhr.responseJSON?.message ?? 'Terjadi kesalahan.');
            Swal.fire({icon:'error', title:'Gagal Menyimpan', html:msg});
        },
        complete() { btn.disabled=false; btn.innerHTML='<i class="bi bi-check-lg me-1"></i>Simpan Jadwal'; }
    });
}

function showDetail(id) {
    document.getElementById('detailBody').innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';
    new bootstrap.Modal('#detailModal').show();
    $.get('/admin/schedules/' + id, function(res) {
        const s = res.data;
        const statusMap = {
            dijadwalkan:'#fdf4ff:#68117e:Dijadwalkan',
            berlangsung:'#fffbeb:#e09000:Berlangsung',
            selesai:'#f0fdf4:#16a34a:Selesai',
            dibatalkan:'#fef2f2:#dc2626:Dibatalkan'
        };
        const [sbg,scol,slbl] = (statusMap[s.status]||'#f1f5f9:#64748b:'+s.status).split(':');
        document.getElementById('detailBody').innerHTML = `
            <div style="padding:20px">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="fw-bold" style="font-size:15px">${s.topik ?? 'Jadwal #'+s.id}</div>
                        <div style="font-size:12px;color:#6b7280">${s.tanggal ? s.tanggal.substr(0,10) : '–'} · ${(s.jam_mulai||'–').substr(0,5)} – ${(s.jam_selesai||'–').substr(0,5)}</div>
                    </div>
                    <span style="background:${sbg};color:${scol};padding:4px 12px;border-radius:8px;font-size:12px;font-weight:600">${slbl}</span>
                </div>
                <table style="width:100%;border-collapse:collapse;font-size:13px">
                    ${drow('Guru', s.guru?.name ?? '–')}
                    ${drow('Kelas', s.kelas?.nama ?? '–')}
                    ${drow('Cabang', s.cabang?.name ?? '–')}
                    ${drow('Jenis', s.jenis === 'online' ? '🌐 Online' : '📍 Offline')}
                    ${s.jenis === 'offline' ? drow('Ruangan', s.ruangan ?? '–') : drow('Link Meeting', s.link_meeting ? '<a href="'+s.link_meeting+'" target="_blank">Buka Link</a>' : '–')}
                    ${drow('Catatan', s.catatan ?? '–')}
                </table>
            </div>
        `;
    }).fail(() => { document.getElementById('detailBody').innerHTML = '<div class="text-center py-5 text-muted">Gagal memuat data</div>'; });
}

function drow(label, val) {
    return `<tr style="border-bottom:1px solid #f1f5f9">
        <td style="padding:7px 4px 7px 0;color:#6b7280;font-size:12px;width:36%">${label}</td>
        <td style="padding:7px 0;font-size:13px;font-weight:500">${val}</td>
    </tr>`;
}

function deleteSchedule(id, name) {
    Swal.fire({
        title: 'Hapus Jadwal?',
        html: `Jadwal "<b>${name}</b>" akan dihapus secara permanen.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#c84ddf',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="bi bi-trash me-1"></i>Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then(r => {
        if (r.isConfirmed) {
            $.post('/admin/schedules/' + id, {
                _method: 'DELETE',
                _token:  document.querySelector('meta[name=csrf-token]').content
            }, function(res) {
                if (res.success) {
                    Swal.fire({icon:'success', title:'Terhapus!', text:res.message, timer:2000, showConfirmButton:false})
                        .then(() => location.reload());
                }
            }).fail(() => Swal.fire({icon:'error', title:'Gagal!', text:'Tidak dapat menghapus jadwal.'}));
        }
    });
}
</script>
@endpush
