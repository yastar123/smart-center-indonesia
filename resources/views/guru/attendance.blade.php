@extends('layouts.app')
@section('title', 'Absensi Siswa')
@section('page-title', 'Absensi Siswa')

@section('content')

@php
use App\Models\Teacher;
use App\Models\Schedule;
use App\Models\Student;
use Carbon\Carbon;

$teacher = Teacher::where('user_id', auth()->id())->first();
$today   = today();

$selectedDate = request('tanggal') ? Carbon::parse(request('tanggal')) : $today;
$selectedId   = request('schedule_id');

$schedules = $teacher
    ? Schedule::where('guru_id', $teacher->id)
        ->whereDate('tanggal', $selectedDate)
        ->orderBy('jam_mulai')
        ->get()
    : collect();

$selectedSchedule = null;
$classStudents    = collect();

if ($selectedId) {
    $selectedSchedule = Schedule::find($selectedId);
    if ($selectedSchedule && $selectedSchedule->cabang_id) {
        $classStudents = Student::where('branch_id', $selectedSchedule->cabang_id)
            ->where('status', 'aktif')
            ->orderBy('name')
            ->limit(40)
            ->get();
    }
}

$weekSchedules = $teacher
    ? Schedule::where('guru_id', $teacher->id)
        ->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()])
        ->orderBy('tanggal')->orderBy('jam_mulai')
        ->get()
    : collect();
@endphp

{{-- HEADER BANNER --}}
<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div>
            <div style="font-size:11px;opacity:.6;margin-bottom:4px;text-transform:uppercase;letter-spacing:.08em">
                <i class="bi bi-check2-square me-1"></i>Absensi Siswa
            </div>
            <h4 style="font-weight:800;margin-bottom:4px;color:white;letter-spacing:-.02em">
                Input Kehadiran Siswa
            </h4>
            <p style="opacity:.65;margin:0;font-size:13px">
                Pilih jadwal untuk mencatat kehadiran siswa per sesi.
            </p>
        </div>
        <div style="font-size:64px;opacity:.08;line-height:1;flex-shrink:0">
            <i class="bi bi-clipboard-check"></i>
        </div>
    </div>
</div>

@if(!$teacher)
<div class="alert d-flex gap-3 align-items-start mb-4 fade-up" style="border-radius:14px;border:none;background:#fef3c7;color:#78350f">
    <i class="bi bi-exclamation-triangle-fill text-warning mt-1" style="font-size:18px;flex-shrink:0"></i>
    <div>
        <div class="fw-bold mb-1">Profil Guru Belum Terhubung</div>
        <div style="font-size:13px">Akun Anda belum terhubung ke profil guru. Hubungi administrator.</div>
    </div>
</div>
@endif

<div class="row g-4">

    {{-- LEFT: DATE PICKER + SCHEDULE LIST --}}
    <div class="col-lg-4 fade-up">

        {{-- Date Picker --}}
        <div class="dashboard-card mb-4">
            <h6 class="fw-bold mb-3" style="font-size:13px">
                <i class="bi bi-calendar3 text-primary me-2"></i>Pilih Tanggal
            </h6>
            <form method="GET" id="dateForm">
                <input type="hidden" name="schedule_id" value="{{ $selectedId }}">
                <input type="date" name="tanggal" id="tanggalPicker"
                       class="form-control"
                       value="{{ $selectedDate->format('Y-m-d') }}"
                       max="{{ now()->format('Y-m-d') }}"
                       onchange="document.getElementById('dateForm').submit()">
            </form>
            <div class="mt-2 d-flex gap-2 flex-wrap">
                <a href="?tanggal={{ today()->format('Y-m-d') }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:12px">
                    <i class="bi bi-calendar-day me-1"></i>Hari Ini
                </a>
                <a href="?tanggal={{ today()->subDay()->format('Y-m-d') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;font-size:12px">
                    <i class="bi bi-arrow-left me-1"></i>Kemarin
                </a>
            </div>
        </div>

        {{-- Schedule List --}}
        <div class="dashboard-card">
            <h6 class="fw-bold mb-3" style="font-size:13px">
                <i class="bi bi-list-ul text-primary me-2"></i>
                Jadwal {{ $selectedDate->locale('id')->isoFormat('dddd, D MMM') }}
            </h6>

            @if(!$teacher)
            <div class="text-center py-3 text-muted" style="font-size:13px">
                <i class="bi bi-person-slash d-block mb-2" style="font-size:2rem;opacity:.4"></i>
                Profil guru belum terhubung
            </div>
            @elseif($schedules->isEmpty())
            <div class="text-center py-3 text-muted" style="font-size:13px">
                <i class="bi bi-calendar-x d-block mb-2" style="font-size:2rem;opacity:.4"></i>
                Tidak ada jadwal pada tanggal ini
            </div>
            @else
            <div class="d-flex flex-column gap-2">
                @foreach($schedules as $sch)
                @php
                    $isSelected = $selectedId == $sch->id;
                    $statusClr  = ['dijadwalkan'=>'#c84ddf','berlangsung'=>'#10b981','selesai'=>'#94a3b8','dibatalkan'=>'#ef4444'][$sch->status] ?? '#94a3b8';
                @endphp
                <a href="?tanggal={{ $selectedDate->format('Y-m-d') }}&schedule_id={{ $sch->id }}"
                   class="d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none"
                   style="background:{{ $isSelected ? 'linear-gradient(135deg,#68117e,#c84ddf)' : 'var(--input-bg)' }};border:1.5px solid {{ $isSelected ? '#c84ddf' : 'var(--card-border)' }};transition:.2s">
                    <div style="min-width:48px;text-align:center">
                        <div class="fw-bold" style="font-size:14px;color:{{ $isSelected ? 'white' : $statusClr }}">
                            {{ \Carbon\Carbon::parse($sch->jam_mulai)->format('H:i') }}
                        </div>
                        <div style="font-size:11px;color:{{ $isSelected ? 'rgba(255,255,255,.6)' : 'var(--text-muted)' }}">
                            {{ \Carbon\Carbon::parse($sch->jam_selesai)->format('H:i') }}
                        </div>
                    </div>
                    <div style="flex:1;min-width:0">
                        <div class="fw-semibold text-truncate" style="font-size:13px;color:{{ $isSelected ? 'white' : 'var(--text-primary)' }}">
                            {{ $sch->topik ?? 'Sesi Mengajar' }}
                        </div>
                        <div style="font-size:11px;color:{{ $isSelected ? 'rgba(255,255,255,.65)' : 'var(--text-muted)' }}">
                            <i class="bi bi-{{ $sch->jenis==='online' ? 'camera-video' : 'building' }} me-1"></i>
                            {{ $sch->jenis==='online' ? 'Online' : ($sch->ruangan ?? 'Kelas') }}
                        </div>
                    </div>
                    @if($isSelected)
                    <i class="bi bi-check-circle-fill" style="color:white;font-size:16px;flex-shrink:0"></i>
                    @endif
                </a>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Week mini summary --}}
        <div class="dashboard-card mt-4">
            <h6 class="fw-bold mb-3" style="font-size:13px">
                <i class="bi bi-calendar-week text-primary me-2"></i>Jadwal Minggu Ini
            </h6>
            @if($weekSchedules->isEmpty())
            <div class="text-center py-3 text-muted" style="font-size:12px">Tidak ada jadwal minggu ini</div>
            @else
            @foreach($weekSchedules as $ws)
            @php $isToday = $ws->tanggal->isToday(); @endphp
            <div class="d-flex align-items-center gap-2 py-2" style="border-bottom:1px solid var(--card-border)">
                <div style="width:8px;height:8px;border-radius:50%;background:{{ $isToday ? '#c84ddf' : 'var(--card-border)' }};flex-shrink:0"></div>
                <div style="font-size:12px;color:{{ $isToday ? 'var(--primary)' : 'var(--text-muted)' }};flex:1">
                    {{ $ws->tanggal->locale('id')->isoFormat('ddd, D MMM') }} · {{ \Carbon\Carbon::parse($ws->jam_mulai)->format('H:i') }}
                </div>
                <div style="font-size:11px;color:var(--text-muted)" class="text-truncate" style="max-width:80px">
                    {{ $ws->topik ?? 'Sesi' }}
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>

    {{-- RIGHT: ATTENDANCE FORM --}}
    <div class="col-lg-8 fade-up" style="animation-delay:.06s">

        @if(!$selectedSchedule)
        <div class="dashboard-card h-100 d-flex flex-column align-items-center justify-content-center text-center py-5">
            <div style="width:80px;height:80px;border-radius:24px;background:linear-gradient(135deg,#f0e8f5,#fdf4ff);display:flex;align-items:center;justify-content:center;margin-bottom:20px">
                <i class="bi bi-arrow-left-circle-fill" style="font-size:2.5rem;color:#c84ddf;opacity:.4"></i>
            </div>
            <h6 class="fw-bold mb-2">Pilih Jadwal</h6>
            <p class="text-muted mb-0" style="font-size:13.5px;max-width:320px">
                Pilih tanggal dan klik salah satu jadwal di sebelah kiri untuk mulai mencatat kehadiran siswa.
            </p>
        </div>
        @else

        {{-- Schedule Detail Header --}}
        @php
            $sStatusClr = ['dijadwalkan'=>'#c84ddf','berlangsung'=>'#10b981','selesai'=>'#94a3b8','dibatalkan'=>'#ef4444'][$selectedSchedule->status] ?? '#94a3b8';
        @endphp
        <div class="dashboard-card mb-4"
             style="border-left:4px solid #c84ddf;background:linear-gradient(135deg,var(--card-bg),rgba(200,77,223,.03))">
            <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                <div>
                    <div style="font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:#c84ddf;font-weight:700;margin-bottom:6px">
                        Detail Sesi
                    </div>
                    <h5 class="fw-bold mb-1" style="font-size:17px">{{ $selectedSchedule->topik ?? 'Sesi Mengajar' }}</h5>
                    <div class="d-flex flex-wrap gap-3 mt-2">
                        <span style="font-size:12.5px;color:var(--text-muted)">
                            <i class="bi bi-clock me-1"></i>
                            {{ \Carbon\Carbon::parse($selectedSchedule->jam_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($selectedSchedule->jam_selesai)->format('H:i') }}
                        </span>
                        <span style="font-size:12.5px;color:var(--text-muted)">
                            <i class="bi bi-{{ $selectedSchedule->jenis==='online' ? 'camera-video' : 'building' }} me-1"></i>
                            {{ $selectedSchedule->jenis==='online' ? 'Online' : ($selectedSchedule->ruangan ?? 'Offline') }}
                        </span>
                        @if($selectedSchedule->jenis==='online' && $selectedSchedule->link_meeting)
                        <a href="{{ $selectedSchedule->link_meeting }}" target="_blank" style="font-size:12.5px;color:#c84ddf">
                            <i class="bi bi-link me-1"></i>Buka Link Meeting
                        </a>
                        @endif
                    </div>
                </div>
                <span class="badge" style="background:{{ $sStatusClr }}20;color:{{ $sStatusClr }};font-size:12px;padding:6px 12px;border-radius:8px;font-weight:600;flex-shrink:0">
                    <i class="bi bi-circle-fill me-1" style="font-size:8px"></i>{{ ucfirst($selectedSchedule->status) }}
                </span>
            </div>
        </div>

        {{-- Attendance Form --}}
        <div class="dashboard-card">
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                <div>
                    <h6 class="fw-bold mb-1" style="font-size:14px">
                        <i class="bi bi-people-fill text-primary me-2"></i>Daftar Hadir Siswa
                    </h6>
                    <p class="text-muted mb-0" style="font-size:12px">{{ $classStudents->count() }} siswa terdaftar</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-success" onclick="setAll('hadir')" style="border-radius:8px;font-size:12px">
                        <i class="bi bi-check-all me-1"></i>Semua Hadir
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="setAll('alpha')" style="border-radius:8px;font-size:12px">
                        <i class="bi bi-x-circle me-1"></i>Semua Alpha
                    </button>
                </div>
            </div>

            @if($classStudents->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-people d-block mb-3" style="font-size:3rem;opacity:.2"></i>
                <p class="text-muted mb-0" style="font-size:13px">Tidak ada siswa aktif yang ditemukan untuk kelas/cabang ini.</p>
            </div>
            @else

            {{-- Summary Bar --}}
            <div class="row g-2 mb-4" id="summaryBar">
                <div class="col-3">
                    <div class="text-center p-2 rounded-3" style="background:#dcfce7;border:1px solid #bbf7d0">
                        <div class="fw-bold" style="font-size:20px;color:#15803d" id="countHadir">0</div>
                        <div style="font-size:11px;color:#15803d">Hadir</div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="text-center p-2 rounded-3" style="background:#fef3c7;border:1px solid #fcd34d">
                        <div class="fw-bold" style="font-size:20px;color:#92400e" id="countSakit">0</div>
                        <div style="font-size:11px;color:#92400e">Sakit</div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="text-center p-2 rounded-3" style="background:#e0f2fe;border:1px solid #7dd3fc">
                        <div class="fw-bold" style="font-size:20px;color:#075985" id="countIzin">0</div>
                        <div style="font-size:11px;color:#075985">Izin</div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="text-center p-2 rounded-3" style="background:#fee2e2;border:1px solid #fecaca">
                        <div class="fw-bold" style="font-size:20px;color:#991b1b" id="countAlpha">0</div>
                        <div style="font-size:11px;color:#991b1b">Alpha</div>
                    </div>
                </div>
            </div>

            <form id="attendanceForm" onsubmit="submitAttendance(event)">
                <input type="hidden" name="schedule_id" value="{{ $selectedSchedule->id }}">
                <div class="d-flex flex-column gap-2" id="studentList">
                    @foreach($classStudents as $i => $s)
                    @php
                        $avatar = 'https://ui-avatars.com/api/?name='.urlencode($s->name).'&background='.($s->gender==='P'?'ec4899':'68117e').'&color=fff&size=64';
                    @endphp
                    <div class="d-flex align-items-center gap-3 p-3 rounded-3 student-row"
                         style="background:var(--input-bg);border:1.5px solid var(--card-border);transition:.2s"
                         data-student="{{ $s->id }}">
                        <img src="{{ $s->photo ? \Storage::url($s->photo) : $avatar }}"
                             class="rounded-circle flex-shrink-0"
                             width="40" height="40"
                             style="object-fit:cover;border:2px solid {{ $s->gender==='P'?'#f9a8d4':'#e8b4f5' }}"
                             loading="lazy">
                        <div style="flex:1;min-width:0">
                            <div class="fw-semibold text-truncate" style="font-size:13.5px">{{ $s->name }}</div>
                            <div class="text-muted" style="font-size:11px">
                                {{ $s->nis ?? 'NIS-' }} · {{ $s->grade ?? '-' }}
                            </div>
                        </div>
                        <div class="d-flex gap-1 flex-shrink-0">
                            @foreach(['hadir'=>['green','#15803d','#dcfce7','bi-check-lg'],'sakit'=>['yellow','#92400e','#fef3c7','bi-thermometer-half'],'izin'=>['blue','#075985','#e0f2fe','bi-file-text'],'alpha'=>['red','#991b1b','#fee2e2','bi-x-lg']] as $status => [$color,$textClr,$bgClr,$icon])
                            <button type="button"
                                class="att-btn btn btn-sm"
                                data-status="{{ $status }}"
                                data-student="{{ $s->id }}"
                                onclick="setStatus(this, {{ $s->id }}, '{{ $status }}')"
                                title="{{ ucfirst($status) }}"
                                style="width:34px;height:34px;padding:0;border-radius:9px;border:1.5px solid {{ $bgClr }};background:{{ $bgClr }};color:{{ $textClr }};font-size:13px;transition:.15s;opacity:.4">
                                <i class="bi {{ $icon }}"></i>
                            </button>
                            @endforeach
                        </div>
                        <input type="hidden" name="attendance[{{ $s->id }}]" id="att_{{ $s->id }}" value="">
                    </div>
                    @endforeach
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2 pt-3" style="border-top:1px solid var(--card-border)">
                    <button type="button" class="btn btn-outline-secondary px-4" onclick="resetAll()" style="border-radius:10px">
                        <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                    </button>
                    <button type="submit" class="btn btn-primary px-5 fw-semibold" id="submitBtn" style="border-radius:10px">
                        <i class="bi bi-save me-2"></i>Simpan Absensi
                    </button>
                </div>
            </form>
            @endif
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
const studentStatus = {};

function setStatus(btn, studentId, status) {
    const row = document.querySelector(`[data-student="${studentId}"].student-row`);
    const allBtns = row.querySelectorAll('.att-btn');
    allBtns.forEach(b => { b.style.opacity = '.4'; b.style.transform = 'scale(1)'; });
    btn.style.opacity = '1';
    btn.style.transform = 'scale(1.1)';
    document.getElementById(`att_${studentId}`).value = status;
    studentStatus[studentId] = status;
    updateSummary();
    // Highlight row
    const colors = { hadir:'rgba(21,128,61,.05)', sakit:'rgba(146,64,14,.04)', izin:'rgba(7,89,133,.04)', alpha:'rgba(153,27,27,.05)' };
    row.style.background = colors[status] || 'var(--input-bg)';
}

function setAll(status) {
    document.querySelectorAll('.student-row').forEach(row => {
        const sid = row.dataset.student;
        const btn = row.querySelector(`[data-status="${status}"]`);
        if (btn) setStatus(btn, sid, status);
    });
}

function resetAll() {
    document.querySelectorAll('.student-row').forEach(row => {
        const sid = row.dataset.student;
        row.querySelectorAll('.att-btn').forEach(b => { b.style.opacity = '.4'; b.style.transform = 'scale(1)'; });
        document.getElementById(`att_${sid}`).value = '';
        row.style.background = 'var(--input-bg)';
        delete studentStatus[sid];
    });
    updateSummary();
}

function updateSummary() {
    const counts = { hadir:0, sakit:0, izin:0, alpha:0 };
    Object.values(studentStatus).forEach(s => { if(counts[s]!==undefined) counts[s]++; });
    document.getElementById('countHadir').textContent = counts.hadir;
    document.getElementById('countSakit').textContent  = counts.sakit;
    document.getElementById('countIzin').textContent   = counts.izin;
    document.getElementById('countAlpha').textContent  = counts.alpha;
}

async function submitAttendance(e) {
    e.preventDefault();
    const filled = Object.keys(studentStatus).length;
    const total  = document.querySelectorAll('.student-row').length;
    if (filled < total) {
        confirmAction(`Baru ${filled} dari ${total} siswa yang diisi. Lanjutkan simpan absensi?`, function() {
            doSubmitAttendance();
        }, null, {title:'Konfirmasi Absensi', okText:'Ya, Simpan', type:'warning'});
        return;
    }
    doSubmitAttendance();
}

async function doSubmitAttendance() {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

    const jadwalId = document.querySelector('[name=schedule_id]').value;
    const statusMap = { alpha: 'alpa' };
    const fd = new FormData();
    fd.append('_token', document.querySelector('meta[name=csrf-token]').content);
    fd.append('jadwal_id', jadwalId);
    let i = 0;
    for (const [siswaId, status] of Object.entries(studentStatus)) {
        fd.append(`absensi[${i}][siswa_id]`, siswaId);
        fd.append(`absensi[${i}][status]`, statusMap[status] || status);
        i++;
    }

    try {
        const res  = await fetch('{{ route("guru.attendance.store") }}', { method: 'POST', body: fd });
        const data = await res.json();
        if (data.success) {
            window.showToast(data.message || 'Absensi berhasil disimpan!', 'success');
        } else {
            window.showToast(data.message || 'Gagal menyimpan absensi.', 'error');
        }
    } catch (err) {
        window.showToast('Terjadi kesalahan jaringan. Coba lagi.', 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-save me-2"></i>Simpan Absensi';
    }
}
</script>
@endpush
