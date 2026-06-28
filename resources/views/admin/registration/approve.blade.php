@extends('layouts.app')
@section('title', 'Approve & Biaya — ' . $registration->name)
@section('page-title', 'Approve & Biaya Pendaftaran')

@section('content')
<div class="fade-up">

{{-- BREADCRUMB --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.registration-list.index') }}" class="text-decoration-none">Daftar Registrasi</a></li>
        <li class="breadcrumb-item active">Approve &amp; Biaya</li>
    </ol>
</nav>

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show mb-3"><i class="bi bi-x-circle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row g-4">

    {{-- LEFT: Detail Pengajuan --}}
    <div class="col-lg-5">
        <div class="dashboard-card h-100">
            <div class="d-flex align-items-center gap-2 mb-4">
                <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#260632,#c84ddf);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="bi bi-person-vcard-fill text-white" style="font-size:1rem"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">Detail Pengajuan</h6>
                    <div class="text-muted" style="font-size:.75rem">Data pendaftaran calon siswa</div>
                </div>
            </div>

            {{-- Avatar & name --}}
            <div class="text-center mb-4 p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($registration->name) }}&background={{ $registration->gender==='P'?'ec4899':'c84ddf' }}&color=fff&size=72"
                     class="rounded-circle mb-2" width="72" height="72"
                     style="border:3px solid var(--card-border)">
                <div class="fw-bold" style="font-size:1rem">{{ $registration->name }}</div>
                <div class="text-muted" style="font-size:.78rem">
                    <code style="background:var(--card-bg);padding:2px 7px;border-radius:6px">{{ $registration->no_reg }}</code>
                </div>
                <div class="mt-2">
                    @php
                        $statusMap = [
                            'pending'  => ['var(--soft-warning-bg)','var(--soft-warning-text)','Menunggu'],
                            'verified' => ['var(--soft-success-bg)','var(--soft-success-text)','Terverifikasi'],
                            'rejected' => ['var(--soft-danger-bg)','var(--soft-danger-text)','Ditolak'],
                        ];
                        $sc = $statusMap[$registration->status] ?? ['var(--soft-muted-bg)','var(--text-muted)','–'];
                    @endphp
                    <span class="badge" style="background:{{ $sc[0] }};color:{{ $sc[1] }};padding:4px 12px;border-radius:20px;font-size:.73rem">{{ $sc[2] }}</span>
                </div>
            </div>

            {{-- Data sections --}}
            <div class="d-flex flex-column gap-3">

                {{-- Data Diri --}}
                <div>
                    <div class="fw-semibold mb-2" style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em">Data Diri</div>
                    <div class="d-flex flex-column gap-1" style="font-size:.83rem">
                        @if($registration->phone)
                        <div class="d-flex gap-2"><span class="text-muted" style="min-width:100px">No. HP</span> <span>{{ $registration->phone }}</span></div>
                        @endif
                        @if($registration->gender)
                        <div class="d-flex gap-2"><span class="text-muted" style="min-width:100px">Kelamin</span> <span>{{ $registration->gender==='L'?'Laki-laki':($registration->gender==='P'?'Perempuan':$registration->gender) }}</span></div>
                        @endif
                        @if($registration->birth_date)
                        <div class="d-flex gap-2"><span class="text-muted" style="min-width:100px">Tgl Lahir</span> <span>{{ $registration->birth_date->format('d M Y') }}</span></div>
                        @endif
                        @if($registration->address)
                        <div class="d-flex gap-2"><span class="text-muted" style="min-width:100px">Alamat</span> <span>{{ $registration->address }}</span></div>
                        @endif
                        @if($registration->parent_name)
                        <div class="d-flex gap-2"><span class="text-muted" style="min-width:100px">Orang Tua</span> <span>{{ $registration->parent_name }}</span></div>
                        @endif
                        @if($registration->parent_phone)
                        <div class="d-flex gap-2"><span class="text-muted" style="min-width:100px">HP Ortu</span> <span>{{ $registration->parent_phone }}</span></div>
                        @endif
                    </div>
                </div>

                {{-- Program --}}
                <div style="border-top:1px solid var(--card-border);padding-top:12px">
                    <div class="fw-semibold mb-2" style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em">Info Program</div>
                    <div class="d-flex flex-column gap-1" style="font-size:.83rem">
                        @if($registration->program)
                        <div class="d-flex gap-2"><span class="text-muted" style="min-width:100px">Program</span> <strong>{{ $registration->program }}</strong></div>
                        @endif
                        @if($registration->system)
                        <div class="d-flex gap-2"><span class="text-muted" style="min-width:100px">Sistem</span> <span>{{ $registration->system }}</span></div>
                        @endif
                        @if($registration->learning_place)
                        <div class="d-flex gap-2"><span class="text-muted" style="min-width:100px">Tempat</span> <span>{{ $registration->learning_place }}</span></div>
                        @endif
                        @if($registration->branch)
                        <div class="d-flex gap-2"><span class="text-muted" style="min-width:100px">Cabang</span> <span>{{ $registration->branch }}</span></div>
                        @endif
                        @if($registration->start_date)
                        <div class="d-flex gap-2"><span class="text-muted" style="min-width:100px">Tgl Mulai</span> <span>{{ $registration->start_date->format('d M Y') }}</span></div>
                        @endif
                    </div>
                </div>

                {{-- Jadwal --}}
                @if(($registration->day_preferences && count($registration->day_preferences)) || $registration->schedule_time)
                <div style="border-top:1px solid var(--card-border);padding-top:12px">
                    <div class="fw-semibold mb-2" style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em">Jadwal Belajar</div>
                    <div class="d-flex flex-wrap gap-1 mb-1">
                        @foreach($registration->day_preferences ?? [] as $day)
                        <span class="badge" style="background:var(--soft-info-bg);color:var(--soft-info-text);padding:3px 9px;border-radius:10px;font-size:.73rem">{{ $day }}</span>
                        @endforeach
                    </div>
                    @if($registration->schedule_time)
                    <div class="text-muted" style="font-size:.8rem;white-space:pre-line">🕐 {{ $registration->schedule_time }}</div>
                    @endif
                </div>
                @endif

                {{-- Minat (nama saja, tanpa harga) --}}
                @if($registration->interests && count($registration->interests))
                <div style="border-top:1px solid var(--card-border);padding-top:12px">
                    <div class="fw-semibold mb-2" style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em">Program Diminati</div>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($registration->interests as $int)
                        <span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-2"
                              style="background:var(--card-bg);border:1px solid var(--card-border);font-size:.8rem;color:var(--text-primary)">
                            <i class="bi bi-check2 text-primary"></i>{{ $int }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Catatan --}}
                @if($registration->notes)
                <div style="border-top:1px solid var(--card-border);padding-top:12px">
                    <div class="fw-semibold mb-1" style="font-size:.75rem;color:var(--soft-warning-text);text-transform:uppercase;letter-spacing:.06em"><i class="bi bi-chat-text me-1"></i>Catatan</div>
                    <p class="mb-0" style="font-size:.82rem;color:var(--text-primary)">{{ $registration->notes }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- RIGHT: Form Tunjuk Pengajar + Aksi --}}
    <div class="col-lg-7">

        {{-- Warning if no student account --}}
        @if(!$registration->student_id)
        <div class="alert mb-3" style="background:var(--soft-warning-bg);border:1px solid rgba(245,158,11,.2);border-radius:12px;color:var(--soft-warning-text)">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Siswa ini belum memiliki akun terdaftar. Verifikasi terlebih dahulu dari dashboard sebelum menetapkan guru dan biaya.
        </div>
        @endif

        <div class="dashboard-card">
            <div class="d-flex align-items-center gap-2 mb-4">
                <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#166534,#22c55e);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="bi bi-person-check-fill text-white" style="font-size:1rem"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">Tunjuk Pengajar</h6>
                    <div class="text-muted" style="font-size:.75rem">Pilih guru dan tentukan biaya program</div>
                </div>
            </div>

            {{-- Mata Pelajaran: Guru & Sesi per Mapel --}}
            @php
                $allInterests   = $registration->interests ?? [];
                if (empty($allInterests) && $registration->program) {
                    $allInterests = [$registration->program];
                }
                $savedSessions  = $registration->interest_sessions ?? [];
                $savedTeachers  = $registration->interest_teachers ?? [];
                $teachersById   = $teachers->keyBy('id');
            @endphp
            @if(!empty($allInterests))
            <div class="mb-4 p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                <div class="fw-semibold mb-3" style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em">
                    <i class="bi bi-bookmark-star me-1" style="color:var(--primary)"></i>Mata Pelajaran — Guru &amp; Jumlah Sesi
                </div>
                <div class="d-flex flex-column gap-2">
                    @foreach($allInterests as $idx => $prog)
                    @php
                        $defaultSesi    = $savedSessions[$prog] ?? 8;
                        $defaultTeacher = $savedTeachers[$prog] ?? null;
                    @endphp
                    <div class="p-2 rounded-2" style="background:var(--card-bg);border:1px solid var(--card-border)">
                        {{-- Subject label --}}
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div style="width:24px;height:24px;border-radius:6px;background:linear-gradient(135deg,#260632,#c84ddf);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.68rem;color:white;font-weight:700">
                                {{ $idx + 1 }}
                            </div>
                            <span class="fw-semibold" style="font-size:.85rem;color:var(--text-primary)">{{ $prog }}</span>
                        </div>
                        {{-- Guru + Sesi row --}}
                        <div class="d-flex gap-2 align-items-center">
                            <div class="flex-grow-1">
                                <select class="form-select form-select-sm guru-select"
                                        data-subject="{{ $prog }}"
                                        style="font-size:.82rem">
                                    <option value="">— Pilih Guru —</option>
                                    @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}"
                                            {{ $defaultTeacher == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}@if($teacher->jenis_guru) ({{ ucfirst($teacher->jenis_guru) }})@endif
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div style="flex-shrink:0">
                                <div class="input-group input-group-sm" style="width:100px">
                                    <span class="input-group-text" style="font-size:.72rem;padding:4px 7px;background:var(--input-bg);border-color:var(--card-border);color:var(--text-muted)">Sesi</span>
                                    <input type="number"
                                           class="form-control form-control-sm sesi-input"
                                           data-subject="{{ $prog }}"
                                           value="{{ $defaultSesi }}"
                                           min="1" max="999"
                                           oninput="recalcSesiTotal()"
                                           style="font-size:.85rem;font-weight:600;text-align:center;background:var(--input-bg);color:var(--text-primary);border-color:var(--card-border)">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="d-flex align-items-center justify-content-between mt-3 pt-2" style="border-top:1px solid var(--card-border)">
                    <span class="text-muted" style="font-size:.72rem"><i class="bi bi-info-circle me-1"></i>Setiap mata pelajaran bisa memiliki guru berbeda</span>
                    <span class="fw-bold" style="font-size:.82rem;color:var(--primary)">
                        Total: <span id="totalSesiSum">0</span> sesi
                    </span>
                </div>
            </div>
            @endif

            {{-- Total Biaya Program --}}
            <div class="mb-2">
                <label class="form-label fw-semibold" style="font-size:.85rem">Total Biaya Program (Rp) <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text fw-semibold" style="font-size:.85rem">Rp</span>
                    <input type="number" id="totalBiaya" class="form-control fw-semibold"
                           placeholder="0" min="0" style="font-size:.95rem">
                </div>
                <div class="form-text">Nominal yang akan ditagihkan kepada siswa.</div>
            </div>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="dashboard-card mt-3">
            <h6 class="fw-bold mb-3" style="font-size:.85rem"><i class="bi bi-lightning-charge text-primary me-2"></i>Tindakan</h6>
            <div class="d-flex flex-column gap-2">

                {{-- Kirim Invoice ke Siswa --}}
                <button type="button" onclick="submitAction('send')"
                        class="btn fw-semibold d-flex align-items-center gap-2 justify-content-center"
                        style="background:linear-gradient(135deg,#260632,#461256,#c84ddf);color:white;border:none;border-radius:12px;padding:13px;font-size:.88rem">
                    <i class="bi bi-send-fill" style="font-size:1rem"></i>
                    <div class="text-start">
                        <div>Kirim Invoice ke Siswa</div>
                        <div style="font-size:.72rem;opacity:.8;font-weight:400">Tagihan muncul di halaman billing siswa</div>
                    </div>
                </button>

                {{-- Lunas --}}
                <button type="button" onclick="submitAction('lunas')"
                        class="btn fw-semibold d-flex align-items-center gap-2 justify-content-center"
                        style="background:linear-gradient(135deg,#14532d,#166534,#22c55e);color:white;border:none;border-radius:12px;padding:13px;font-size:.88rem">
                    <i class="bi bi-check-circle-fill" style="font-size:1rem"></i>
                    <div class="text-start">
                        <div>Lunas</div>
                        <div style="font-size:.72rem;opacity:.8;font-weight:400">Catat sebagai sudah dibayar penuh</div>
                    </div>
                </button>

                {{-- Tolak --}}
                <form method="POST" action="{{ route('admin.registration-list.reject', $registration->id) }}" id="rejectForm">
                    @csrf
                    <button type="button" onclick="confirmReject()"
                            class="btn btn-outline-danger fw-semibold w-100 d-flex align-items-center gap-2 justify-content-center"
                            style="border-radius:12px;padding:13px;font-size:.88rem">
                        <i class="bi bi-x-circle-fill" style="font-size:1rem"></i>
                        <div class="text-start">
                            <div>Tolak Pendaftaran</div>
                            <div style="font-size:.72rem;opacity:.8;font-weight:400">Pendaftaran tidak dilanjutkan</div>
                        </div>
                    </button>
                </form>

            </div>
        </div>

    </div>
</div>

</div>

{{-- Hidden forms for POST actions --}}
<form method="POST" action="{{ route('admin.registration-list.send-invoice', $registration->id) }}" id="formSend" style="display:none">
    @csrf
    <input type="hidden" name="total_biaya"     id="fs_total_biaya">
    <input type="hidden" name="biaya_per_sesi"  id="fs_biaya_per_sesi">
    <input type="hidden" name="total_sessions"  id="fs_total_sessions">
    <div id="fs_interest_sessions_container"></div>
    <div id="fs_interest_teachers_container"></div>
</form>

<form method="POST" action="{{ route('admin.registration-list.mark-lunas', $registration->id) }}" id="formLunas" style="display:none">
    @csrf
    <input type="hidden" name="total_biaya"     id="fl_total_biaya">
    <input type="hidden" name="biaya_per_sesi"  id="fl_biaya_per_sesi">
    <input type="hidden" name="total_sessions"  id="fl_total_sessions">
    <div id="fl_interest_sessions_container"></div>
    <div id="fl_interest_teachers_container"></div>
</form>

<script>
/* ── helpers ─────────────────────────────────────────── */
function getTotalSesiFromInputs() {
    let total = 0;
    document.querySelectorAll('.sesi-input').forEach(inp => {
        total += parseInt(inp.value || 0);
    });
    return total;
}

function recalcSesiTotal() {
    const sumEl = document.getElementById('totalSesiSum');
    if (sumEl) sumEl.textContent = getTotalSesiFromInputs();
}

function getInterestSessions() {
    const result = {};
    document.querySelectorAll('.sesi-input').forEach(inp => {
        const subj = inp.dataset.subject;
        if (subj) result[subj] = parseInt(inp.value || 0);
    });
    return result;
}

/* Collect per-subject teacher selections: { subject: teacher_id } */
function getInterestTeachers() {
    const result = {};
    document.querySelectorAll('.guru-select').forEach(sel => {
        const subj = sel.dataset.subject;
        if (subj && sel.value) result[subj] = parseInt(sel.value);
    });
    return result;
}

function injectInterestSessionsIntoForm(containerId) {
    const container = document.getElementById(containerId);
    container.innerHTML = '';
    Object.entries(getInterestSessions()).forEach(([subj, cnt]) => {
        const inp = document.createElement('input');
        inp.type  = 'hidden';
        inp.name  = 'interest_sessions[' + subj + ']';
        inp.value = cnt;
        container.appendChild(inp);
    });
}

function injectInterestTeachersIntoForm(containerId) {
    const container = document.getElementById(containerId);
    container.innerHTML = '';
    Object.entries(getInterestTeachers()).forEach(([subj, tid]) => {
        const inp = document.createElement('input');
        inp.type  = 'hidden';
        inp.name  = 'interest_teachers[' + subj + ']';
        inp.value = tid;
        container.appendChild(inp);
    });
}

/* ── validation & submit ─────────────────────────────── */
function collectForm() {
    const totalBiaya  = document.getElementById('totalBiaya').value;
    const totalSesi   = getTotalSesiFromInputs();

    if (!totalBiaya || parseFloat(totalBiaya) < 0) {
        showToast('Masukkan total biaya program.', 'error');
        return null;
    }

    /* Warn (not block) if any subject has no guru assigned */
    const selects  = document.querySelectorAll('.guru-select');
    const noGuru   = [...selects].filter(s => !s.value);
    if (selects.length > 0 && noGuru.length === selects.length) {
        showToast('Pilih minimal satu guru untuk mata pelajaran.', 'error');
        return null;
    }

    return { totalBiaya, totalSesi };
}

function submitAction(action) {
    const data = collectForm();
    if (!data) return;

    const label = action === 'send'
        ? 'Kirim invoice ke siswa'
        : 'Tandai pembayaran sebagai <strong>Lunas</strong>';

    confirmAction(label + '?', function() {
        const formId = action === 'send' ? 'formSend'  : 'formLunas';
        const prefix = action === 'send' ? 'fs'        : 'fl';

        document.getElementById(prefix + '_total_biaya').value    = data.totalBiaya;
        document.getElementById(prefix + '_biaya_per_sesi').value = '';
        document.getElementById(prefix + '_total_sessions').value = data.totalSesi;

        injectInterestSessionsIntoForm(prefix + '_interest_sessions_container');
        injectInterestTeachersIntoForm(prefix + '_interest_teachers_container');

        document.getElementById(formId).submit();
    }, null, {
        title:    action === 'send' ? 'Kirim Invoice'                       : 'Tandai Lunas',
        okText:   action === 'send' ? '<i class="bi bi-send me-1"></i>Kirim Invoice' : '<i class="bi bi-check-circle me-1"></i>Lunas',
        btnClass: action === 'send' ? 'btn-primary'                         : 'btn-success',
        type: 'warning'
    });
}

function confirmReject() {
    confirmAction('Pendaftaran ini akan <strong>ditolak</strong>. Lanjutkan?', function() {
        document.getElementById('rejectForm').submit();
    }, null, { title: 'Tolak Pendaftaran', okText: '<i class="bi bi-x-circle me-1"></i>Tolak', btnClass: 'btn-danger' });
}

/* ── init ────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', function() {
    recalcSesiTotal();
});
</script>
@endsection
