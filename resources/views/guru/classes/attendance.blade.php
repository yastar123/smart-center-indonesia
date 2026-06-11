@extends('layouts.app')
@section('title','Absensi — '.$class->nama_kelas)
@section('page-title','Input Absensi')

@section('content')

{{-- HEADER BANNER --}}
<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('guru.classes.show', $class->id) }}"
                   class="btn btn-sm flex-shrink-0"
                   style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.25);border-radius:9px;padding:6px 12px">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
                <div>
                    <div style="font-size:11px;opacity:.6;margin-bottom:2px;text-transform:uppercase;letter-spacing:.08em">
                        Input Absensi
                    </div>
                    <h5 class="fw-bold mb-0" style="color:white">{{ $class->nama_kelas }}</h5>
                    <div style="font-size:12px;opacity:.75">
                        {{ $class->mataPelajaran->nama ?? '–' }} · {{ $class->cabang->name ?? 'Pusat' }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <div style="font-size:48px;opacity:.1;line-height:1">
                <i class="bi bi-clipboard2-check-fill"></i>
            </div>
        </div>
    </div>
</div>

{{-- ALUR INFO --}}
<div class="dashboard-card mb-4 fade-up" style="border-left:4px solid #c84ddf">
    <div class="d-flex align-items-center gap-2 flex-wrap" style="font-size:12px;color:var(--text-muted)">
        <i class="bi bi-info-circle text-primary me-1"></i>
        <span class="fw-semibold text-primary me-2">Alur Absensi:</span>
        <span class="badge" style="background:var(--soft-warning-bg);color:var(--soft-warning-text);padding:4px 9px;border-radius:7px">① Jadwal Disetujui Semua Pihak</span>
        <i class="bi bi-arrow-right" style="font-size:11px"></i>
        <span class="badge" style="background:var(--soft-success-bg);color:var(--soft-success-text);padding:4px 9px;border-radius:7px">② Guru Isi &amp; Simpan Absensi</span>
        <i class="bi bi-arrow-right" style="font-size:11px"></i>
        <span class="badge" style="background:rgba(239,68,68,.1);color:#ef4444;padding:4px 9px;border-radius:7px"><i class="bi bi-lock-fill me-1"></i>③ Absensi Terkunci Permanen</span>
    </div>
</div>

{{-- SCHEDULE PICKER --}}
<div class="dashboard-card mb-4 fade-up">
    <div class="d-flex align-items-center gap-3 mb-3">
        <div style="width:36px;height:36px;border-radius:10px;background:rgba(200,77,223,.1);display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <i class="bi bi-calendar-check text-primary" style="font-size:15px"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-0" style="font-size:14px;color:var(--text-primary)">Pilih Pertemuan</h6>
            <p class="text-muted mb-0" style="font-size:12px">Pilih jadwal untuk melihat dan mengisi absensi</p>
        </div>
    </div>
    <select id="jadwalSelect" class="form-select" style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
        <option value="">— Pilih pertemuan —</option>
        @foreach($class->jadwal->sortBy('pertemuan_ke') as $j)
        @php
            $tgl = $j->tanggal instanceof \Carbon\Carbon ? $j->tanggal : \Carbon\Carbon::parse($j->tanggal);
        @endphp
        <option value="{{ $j->id }}">
            @if($j->pertemuan_ke) Pertemuan ke-{{ $j->pertemuan_ke }} · @endif
            {{ $tgl->locale('id')->isoFormat('dddd, D MMM Y') }} ·
            {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} –
            {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
        </option>
        @endforeach
    </select>
</div>

{{-- SCHEDULES LIST --}}
@if($class->jadwal->isEmpty())
<div class="dashboard-card fade-up">
    <div class="text-center py-5">
        <div style="width:80px;height:80px;border-radius:50%;background:var(--input-bg);display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
            <i class="bi bi-calendar-x text-muted" style="font-size:2rem;opacity:.5"></i>
        </div>
        <h6 class="fw-semibold mb-2" style="color:var(--text-primary)">Belum Ada Jadwal</h6>
        <p class="text-muted mb-0" style="font-size:13px">Ajukan proposal jadwal melalui menu <strong>Persetujuan Jadwal</strong>.</p>
        <a href="{{ route('guru.schedule-agreements.index') }}" class="btn btn-primary mt-3" style="border-radius:10px">
            <i class="bi bi-calendar-plus me-1"></i>Ajukan Jadwal
        </a>
    </div>
</div>
@else
<div id="jadwalList" class="d-flex flex-column gap-3">
    @foreach($class->jadwal->sortBy('pertemuan_ke') as $j)
    @php
        $tgl     = $j->tanggal instanceof \Carbon\Carbon ? $j->tanggal : \Carbon\Carbon::parse($j->tanggal);
        $isPast  = $tgl->isPast();
        $isToday = $tgl->isToday();
    @endphp
    <div class="dashboard-card fade-up" id="jadwal-card-{{ $j->id }}">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;border-radius:14px;background:{{ $isToday ? 'rgba(16,185,129,.1)' : 'rgba(200,77,223,.08)' }};display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="bi bi-calendar3" style="font-size:18px;color:{{ $isToday ? '#10b981' : '#c84ddf' }}"></i>
                </div>
                <div>
                    <div class="fw-semibold" style="font-size:14px;color:var(--text-primary)">
                        @if($j->pertemuan_ke)
                        <span style="background:var(--soft-primary-bg);color:var(--soft-primary-text);padding:2px 8px;border-radius:6px;font-size:11px;font-weight:700;margin-right:6px">Pertemuan ke-{{ $j->pertemuan_ke }}</span>
                        @endif
                        {{ $tgl->locale('id')->isoFormat('dddd, D MMMM Y') }}
                        @if($isToday)
                        <span class="badge ms-2" style="background:var(--soft-success-bg);color:var(--soft-success-text);font-size:10px;border-radius:6px;padding:2px 8px">Hari Ini</span>
                        @endif
                    </div>
                    <div class="text-muted" style="font-size:12px">
                        <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
                        @if($j->jenis) · <span class="text-capitalize">{{ $j->jenis }}</span> @endif
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                @if($j->status === 'selesai')
                <span class="badge" style="background:rgba(239,68,68,.1);color:#ef4444;font-size:11px;padding:4px 10px;border-radius:7px">
                    <i class="bi bi-lock-fill me-1"></i>Absensi Terkunci
                </span>
                @elseif($isToday)
                <span class="badge" style="background:var(--soft-success-bg);color:var(--soft-success-text);font-size:11px;padding:4px 10px;border-radius:7px">
                    <i class="bi bi-broadcast me-1"></i>Berlangsung
                </span>
                @elseif($isPast)
                <span class="badge" style="background:var(--input-bg);color:var(--text-muted);border:1px solid var(--card-border);font-size:11px;padding:4px 10px;border-radius:7px">
                    <i class="bi bi-check2 me-1"></i>Selesai
                </span>
                @else
                <span class="badge" style="background:var(--soft-info-bg);color:var(--soft-info-text);font-size:11px;padding:4px 10px;border-radius:7px">
                    <i class="bi bi-clock me-1"></i>Akan Datang
                </span>
                @endif
                <button class="btn btn-primary btn-sm" onclick="toggleAttendance({{ $j->id }})"
                        id="btn-abs-{{ $j->id }}"
                        style="border-radius:9px;font-size:12.5px;padding:6px 14px">
                    <i class="bi bi-clipboard2-check me-1"></i>Absensi
                </button>
            </div>
        </div>

        {{-- ATTENDANCE AREA --}}
        <div id="attendance-area-{{ $j->id }}" class="mt-0"></div>
    </div>
    @endforeach
</div>
@endif

@push('scripts')
<script>
function toggleAttendance(id) {
    const area = document.getElementById('attendance-area-' + id);
    const btn  = document.getElementById('btn-abs-' + id);
    if (!area) return;
    if (area.innerHTML.trim() !== '') {
        area.innerHTML = '';
        if (btn) { btn.innerHTML = '<i class="bi bi-clipboard2-check me-1"></i>Absensi'; btn.classList.remove('btn-secondary'); btn.classList.add('btn-primary'); }
        return;
    }
    if (btn) { btn.innerHTML = '<i class="bi bi-x me-1"></i>Tutup'; btn.classList.remove('btn-primary'); btn.classList.add('btn-secondary'); }
    loadAttendance(id, area);
}

function loadAttendance(id, areaEl) {
    const area = areaEl || document.getElementById('attendance-area-' + id);
    if (!area) return;
    area.innerHTML = `<div class="mt-3 text-center py-4"><div class="spinner-border spinner-border-sm text-primary"></div> <span class="text-muted ms-2" style="font-size:13px">Memuat siswa...</span></div>`;

    fetch('/guru/attendance/' + id + '/students', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(res => {
            if (!res.success) { area.innerHTML = '<div class="mt-3 text-muted">Gagal memuat data siswa.</div>'; return; }
            const students = res.students;
            const existing = res.existing || {};
            const attendanceLocked = res.attendance_locked;
            const hasAttendance = res.has_attendance;
            const allAgreed = res.all_agreed;

            // Not agreed yet
            if (!allAgreed && !hasAttendance) {
                area.innerHTML = `<div class="mt-3 p-3 rounded-3" style="background:var(--soft-warning-bg);border:1px solid var(--soft-warning-border)">
                    <i class="bi bi-exclamation-triangle-fill me-2" style="color:#f6af23"></i>
                    <strong>Persetujuan belum lengkap.</strong>
                    Absensi hanya dapat diisi setelah semua siswa dan guru menyetujui jadwal ini.
                </div>`;
                return;
            }

            // Permanently locked — has attendance already saved
            if (hasAttendance) {
                const statusMap = { hadir: {label:'Hadir',clr:'#10b981',bg:'var(--soft-success-bg)'}, izin: {label:'Izin',clr:'#0284c7',bg:'var(--soft-info-bg)'}, sakit: {label:'Sakit',clr:'#f6af23',bg:'var(--soft-warning-bg)'}, alpa: {label:'Alpa',clr:'#ef4444',bg:'var(--soft-danger-bg)'} };
                let rows = students.map((s, i) => {
                    const cur = existing[s.id] || '';
                    const sm = statusMap[cur] || {label:'–',clr:'#999',bg:'var(--input-bg)'};
                    const avatar = `https://ui-avatars.com/api/?name=${encodeURIComponent(s.name)}&background=68117e&color=fff&size=40`;
                    return `<tr>
                        <td style="padding:10px 12px">
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-muted" style="font-size:12px;min-width:22px">${i+1}</span>
                                <img src="${s.photo ? '/storage/' + s.photo : avatar}" class="rounded-circle flex-shrink-0" width="32" height="32" style="object-fit:cover">
                                <span class="fw-semibold" style="font-size:13px">${s.name}</span>
                            </div>
                        </td>
                        <td>
                            <span style="background:${sm.bg};color:${sm.clr};padding:4px 12px;border-radius:8px;font-size:12px;font-weight:600">${sm.label}</span>
                        </td>
                    </tr>`;
                }).join('');

                area.innerHTML = `
                    <div class="mt-3 p-3 rounded-3" style="background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.2)">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="bi bi-lock-fill" style="color:#ef4444;font-size:15px"></i>
                            <span class="fw-semibold" style="font-size:13px;color:#ef4444">Absensi Terkunci Permanen</span>
                            <span class="text-muted" style="font-size:12px">— Tidak dapat diubah</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0" style="font-size:13px">
                                <thead><tr>
                                    <th class="text-muted fw-semibold" style="font-size:11px">SISWA</th>
                                    <th class="text-muted fw-semibold" style="font-size:11px">STATUS</th>
                                </tr></thead>
                                <tbody>${rows}</tbody>
                            </table>
                        </div>
                    </div>`;
                return;
            }

            // Locked by time (past session end) but no attendance filled
            if (attendanceLocked) {
                area.innerHTML = `<div class="mt-3 p-3 rounded-3 text-muted" style="background:var(--input-bg);border:1px solid var(--card-border)">
                    <i class="bi bi-lock me-1"></i>Pertemuan sudah selesai. Absensi tidak sempat diisi.
                </div>`;
                return;
            }

            if (!students.length) {
                area.innerHTML = `<div class="mt-3 p-3 rounded-3 text-center" style="background:var(--input-bg)">
                    <i class="bi bi-people text-muted d-block mb-2" style="font-size:1.5rem;opacity:.4"></i>
                    <p class="text-muted mb-0" style="font-size:13px">Belum ada siswa terdaftar di kelas ini.</p>
                </div>`;
                return;
            }

            const statusOpts = [
                { val: 'hadir',  label: 'Hadir',  clr: '#10b981', bg: 'var(--soft-success-bg)' },
                { val: 'izin',   label: 'Izin',   clr: '#0284c7', bg: 'var(--soft-info-bg)' },
                { val: 'sakit',  label: 'Sakit',  clr: '#f6af23', bg: 'var(--soft-warning-bg)' },
                { val: 'alpa',   label: 'Alpa',   clr: '#ef4444', bg: 'var(--soft-danger-bg)' },
            ];

            let rows = students.map((s, i) => {
                const cur = existing[s.id] || '';
                const avatar = `https://ui-avatars.com/api/?name=${encodeURIComponent(s.name)}&background=68117e&color=fff&size=40`;
                const radios = statusOpts.map(opt => `
                    <td class="text-center" style="min-width:64px">
                        <label style="display:flex;flex-direction:column;align-items:center;gap:3px;cursor:pointer">
                            <input type="radio" name="status_${s.id}" value="${opt.val}" ${cur === opt.val ? 'checked' : ''}
                                   class="abs-radio" data-sid="${s.id}"
                                   style="width:16px;height:16px;accent-color:${opt.clr};cursor:pointer">
                            <span style="font-size:10.5px;color:${opt.clr};font-weight:600">${opt.label}</span>
                        </label>
                    </td>`).join('');
                return `<tr id="row-${s.id}">
                    <td style="white-space:nowrap;padding:10px 12px">
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted" style="font-size:12px;min-width:22px">${i + 1}</span>
                            <img src="${s.photo ? '/storage/' + s.photo : avatar}" class="rounded-circle flex-shrink-0" width="32" height="32" style="object-fit:cover">
                            <span class="fw-semibold" style="font-size:13px">${s.name}</span>
                        </div>
                    </td>
                    ${radios}
                </tr>`;
            }).join('');

            area.innerHTML = `
                <div class="mt-3 p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                    <div class="d-flex align-items-center gap-2 mb-3 p-2 rounded-2" style="background:rgba(200,77,223,.07)">
                        <i class="bi bi-exclamation-circle-fill text-primary"></i>
                        <span style="font-size:12px">Setelah absensi disimpan, <strong>data tidak dapat diubah</strong>. Pastikan data sudah benar.</span>
                    </div>
                    <form class="absForm">
                        <input type="hidden" name="jadwal_id" value="${id}">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-3" style="font-size:13px">
                                <thead class="thead-modern">
                                    <tr>
                                        <th class="small text-muted fw-semibold py-2">SISWA</th>
                                        <th class="text-center small text-muted fw-semibold py-2" style="color:#10b981!important">HADIR</th>
                                        <th class="text-center small text-muted fw-semibold py-2" style="color:#0284c7!important">IZIN</th>
                                        <th class="text-center small text-muted fw-semibold py-2" style="color:#f6af23!important">SAKIT</th>
                                        <th class="text-center small text-muted fw-semibold py-2" style="color:#ef4444!important">ALPA</th>
                                    </tr>
                                </thead>
                                <tbody>${rows}</tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div id="absCount-${id}" class="text-muted" style="font-size:12px"></div>
                            <button type="submit" class="btn btn-primary" style="border-radius:10px">
                                <i class="bi bi-save me-2"></i>Simpan &amp; Kunci Absensi
                            </button>
                        </div>
                    </form>
                </div>`;

            updateCount(id, students, existing);

            area.querySelectorAll('.abs-radio').forEach(r => r.addEventListener('change', () => {
                const cur = {};
                students.forEach(s => {
                    const checked = area.querySelector(`input[name="status_${s.id}"]:checked`);
                    if (checked) cur[s.id] = checked.value;
                });
                updateCount(id, students, cur);
            }));

            area.querySelector('.absForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const abs = [];
                students.forEach(s => {
                    const v = area.querySelector(`input[name="status_${s.id}"]:checked`);
                    if (v) abs.push({ siswa_id: s.id, status: v.value });
                });

                if (abs.length === 0) {
                    showToast('Isi status absensi minimal 1 siswa.', 'warning');
                    return;
                }

                if (abs.length < students.length) {
                    const unfilled = students.filter(s => !abs.find(a => a.siswa_id === s.id)).map(s => s.name).join(', ');
                    showToast(`${students.length - abs.length} siswa belum diisi: ${unfilled}`, 'warning');
                    return;
                }

                const submitBtn = area.querySelector('button[type=submit]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan & Mengunci...';

                fetch('{{ route("guru.attendance.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ jadwal_id: id, absensi: abs })
                }).then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
                .then(d => {
                    if (d.success) {
                        showToast(d.message || 'Absensi berhasil disimpan dan dikunci!', 'success');
                        setTimeout(() => loadAttendance(id, area), 800);
                    } else {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="bi bi-save me-2"></i>Simpan & Kunci Absensi';
                        showToast(d.message || 'Gagal menyimpan', 'error');
                    }
                }).catch(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-save me-2"></i>Simpan & Kunci Absensi';
                    showToast('Terjadi kesalahan. Coba lagi.', 'error');
                });
            });
        })
        .catch(() => { area.innerHTML = '<div class="mt-3 text-muted p-3">Gagal memuat data. Coba lagi.</div>'; });
}

function updateCount(id, students, existing) {
    const el = document.getElementById('absCount-' + id);
    if (!el) return;
    const filled = students.filter(s => existing[s.id]).length;
    el.textContent = `${filled} dari ${students.length} siswa sudah diisi`;
}

document.getElementById('jadwalSelect').addEventListener('change', function() {
    const id = this.value;
    if (!id) return;
    const card = document.getElementById('jadwal-card-' + id);
    const area = document.getElementById('attendance-area-' + id);
    if (card) { card.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
    if (area && area.innerHTML.trim() === '') toggleAttendance(parseInt(id));
});
</script>
@endpush

@endsection
