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

            {{-- Program Diminati + Sesi per Mata Pelajaran --}}
            @php
                $allInterests = $registration->interests ?? [];
                if (empty($allInterests) && $registration->program) {
                    $allInterests = [$registration->program];
                }
                $savedSessions = $registration->interest_sessions ?? [];
            @endphp
            @if(!empty($allInterests))
            <div class="mb-4 p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                <div class="fw-semibold mb-3" style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em">
                    <i class="bi bi-bookmark-star me-1" style="color:var(--primary)"></i>Mata Pelajaran & Jumlah Sesi
                </div>
                <div class="d-flex flex-column gap-2">
                    @foreach($allInterests as $idx => $prog)
                    @php $defaultSesi = $savedSessions[$prog] ?? 8; @endphp
                    <div class="d-flex align-items-center gap-3 p-2 rounded-2" style="background:var(--card-bg);border:1px solid var(--card-border)">
                        <div class="d-flex align-items-center gap-2 flex-grow-1" style="min-width:0">
                            <div style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#260632,#c84ddf);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.7rem;color:white;font-weight:700">
                                {{ $idx + 1 }}
                            </div>
                            <span class="fw-semibold text-truncate" style="font-size:.83rem;color:var(--text-primary)">{{ $prog }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                            <label class="text-muted mb-0" style="font-size:.72rem;white-space:nowrap">Sesi:</label>
                            <input type="number"
                                   class="form-control form-control-sm sesi-input"
                                   data-subject="{{ $prog }}"
                                   data-idx="{{ $idx }}"
                                   value="{{ $defaultSesi }}"
                                   min="1" max="999"
                                   oninput="recalcTotal()"
                                   style="width:75px;font-size:.85rem;font-weight:600;text-align:center">
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="d-flex align-items-center justify-content-between mt-3 pt-2" style="border-top:1px solid var(--card-border)">
                    <span class="text-muted" style="font-size:.72rem"><i class="bi bi-info-circle me-1"></i>Total sesi dihitung otomatis dari semua mata pelajaran</span>
                    <span class="fw-bold" style="font-size:.82rem;color:var(--primary)">
                        Total: <span id="totalSesiSum">{{ array_sum(array_values(array_intersect_key(array_merge(array_fill_keys($allInterests, 8), $savedSessions), array_fill_keys($allInterests, true)))) ?: count($allInterests) * 8 }}</span> sesi
                    </span>
                </div>
            </div>
            @endif

            <form id="approveForm">
                @csrf

                {{-- Pilih Guru --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold" style="font-size:.85rem">Pilih Guru <span class="text-danger">*</span></label>
                    <select id="teacherSelect" name="teacher_id" class="form-select" onchange="onTeacherChange(this)" required>
                        <option value="">— Pilih Guru —</option>
                        @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}"
                                data-jenis="{{ $teacher->jenis_guru ?? 'kontrak' }}"
                                data-salary="{{ $teacher->salary_base ?? 0 }}"
                                data-name="{{ $teacher->name }}"
                                data-subjects="{{ implode(', ', $teacher->subjects ?? []) }}">
                            {{ $teacher->name }}
                            @if($teacher->jenis_guru) — <span style="text-transform:capitalize">{{ $teacher->jenis_guru }}</span>@endif
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Teacher Info Card (dynamic) --}}
                <div id="teacherInfoCard" class="d-none mb-4">
                    <div class="p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div id="teacherAvatar" class="rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                 style="width:44px;height:44px;background:linear-gradient(135deg,#260632,#c84ddf);color:white;font-size:.9rem;flex-shrink:0">
                            </div>
                            <div>
                                <div id="teacherName" class="fw-semibold" style="font-size:.9rem"></div>
                                <div id="teacherJenis" class="text-muted" style="font-size:.75rem"></div>
                                <div id="teacherSubjects" class="text-muted" style="font-size:.72rem"></div>
                            </div>
                        </div>

                        {{-- Freelance: biaya per sesi (uses total from subject inputs above) --}}
                        <div id="infoFreelance" class="d-none">
                            <div class="row g-3 align-items-end">
                                <div class="col-6">
                                    <label class="form-label fw-semibold" style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em">
                                        Biaya Per Sesi (Rp) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text" style="font-size:.82rem;color:var(--text-muted);background:var(--input-bg);border-color:var(--card-border)">Rp</span>
                                        <input type="number" id="biayaPerSesi" name="biaya_per_sesi" class="form-control"
                                               placeholder="0" min="0" oninput="recalcTotal()"
                                               style="font-size:.88rem;background:var(--input-bg);color:var(--text-primary);border-color:var(--card-border)">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold" style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em">Total Biaya (Otomatis)</label>
                                    <div class="p-2 rounded-2 d-flex align-items-center justify-content-between"
                                         style="background:var(--card-bg);border:1px solid var(--card-border);min-height:38px">
                                        <span id="totalSesiLabel" class="text-muted" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.04em">TOTAL</span>
                                        <span id="totalSesiDisplay" class="fw-bold text-primary" style="font-size:.92rem">Rp 0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Biaya Program --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold" style="font-size:.85rem">Total Biaya Program (Rp) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text fw-semibold" style="font-size:.85rem">Rp</span>
                        <input type="number" id="totalBiaya" name="total_biaya" class="form-control fw-semibold"
                               placeholder="0" min="0" required style="font-size:.95rem">
                    </div>
                    <div class="form-text">Nominal yang akan ditagihkan kepada siswa.</div>
                </div>

                {{-- HIDDEN fields for form submission --}}
                <input type="hidden" id="h_teacher_id" name="teacher_id">
                <input type="hidden" id="h_biaya_per_sesi" name="biaya_per_sesi">
                <input type="hidden" id="h_total_sessions" name="total_sessions">
                <input type="hidden" id="h_total_biaya" name="total_biaya">

            </form>
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
    <input type="hidden" name="teacher_id"     id="fs_teacher_id">
    <input type="hidden" name="total_biaya"     id="fs_total_biaya">
    <input type="hidden" name="biaya_per_sesi"  id="fs_biaya_per_sesi">
    <input type="hidden" name="total_sessions"  id="fs_total_sessions">
    <div id="fs_interest_sessions_container"></div>
</form>

<form method="POST" action="{{ route('admin.registration-list.mark-lunas', $registration->id) }}" id="formLunas" style="display:none">
    @csrf
    <input type="hidden" name="teacher_id"     id="fl_teacher_id">
    <input type="hidden" name="total_biaya"     id="fl_total_biaya">
    <input type="hidden" name="biaya_per_sesi"  id="fl_biaya_per_sesi">
    <input type="hidden" name="total_sessions"  id="fl_total_sessions">
    <div id="fl_interest_sessions_container"></div>
</form>

<script>
function getTotalSesiFromInputs() {
    let total = 0;
    document.querySelectorAll('.sesi-input').forEach(inp => {
        total += parseInt(inp.value || 0);
    });
    return total;
}

function getInterestSessions() {
    const result = {};
    document.querySelectorAll('.sesi-input').forEach(inp => {
        const subj = inp.dataset.subject;
        if (subj) result[subj] = parseInt(inp.value || 0);
    });
    return result;
}

function injectInterestSessionsIntoForm(containerId) {
    const container = document.getElementById(containerId);
    container.innerHTML = '';
    const sessions = getInterestSessions();
    Object.entries(sessions).forEach(([subj, cnt]) => {
        const inp = document.createElement('input');
        inp.type  = 'hidden';
        inp.name  = 'interest_sessions[' + subj + ']';
        inp.value = cnt;
        container.appendChild(inp);
    });
}

function onTeacherChange(sel) {
    const opt = sel.options[sel.selectedIndex];
    const infoCard = document.getElementById('teacherInfoCard');
    const infoFree = document.getElementById('infoFreelance');

    if (!opt.value) { infoCard.classList.add('d-none'); return; }

    const jenis    = opt.dataset.jenis || 'kontrak';
    const name     = opt.dataset.name || '';
    const subjects = opt.dataset.subjects || '';

    infoCard.classList.remove('d-none');
    document.getElementById('teacherName').textContent     = name;
    document.getElementById('teacherJenis').textContent    = jenis.charAt(0).toUpperCase() + jenis.slice(1);
    document.getElementById('teacherSubjects').textContent = subjects || 'Semua Mapel';
    document.getElementById('teacherAvatar').textContent   = name.charAt(0).toUpperCase();

    if (jenis.toLowerCase() === 'freelance') {
        infoFree.classList.remove('d-none');
        recalcTotal();
    } else {
        infoFree.classList.add('d-none');
    }
}

function recalcTotal() {
    const bps   = parseFloat(document.getElementById('biayaPerSesi')?.value || 0);
    const sesi  = getTotalSesiFromInputs();
    const total = bps * sesi;
    const sumEl = document.getElementById('totalSesiSum');
    if (sumEl) sumEl.textContent = sesi;
    document.getElementById('totalSesiLabel').textContent   = 'TOTAL (' + sesi + ' SESI)';
    document.getElementById('totalSesiDisplay').textContent = 'Rp ' + total.toLocaleString('id-ID');
    if (bps > 0) document.getElementById('totalBiaya').value = total > 0 ? total : '';
}

function collectForm() {
    const teacherId  = document.getElementById('teacherSelect').value;
    const totalBiaya = document.getElementById('totalBiaya').value;
    const biayaSesi  = document.getElementById('biayaPerSesi')?.value || '';
    const totalSesi  = getTotalSesiFromInputs();

    if (!teacherId) { showToast('Pilih guru terlebih dahulu.', 'error'); return null; }
    if (!totalBiaya || parseFloat(totalBiaya) < 0) { showToast('Masukkan total biaya program.', 'error'); return null; }

    return { teacherId, totalBiaya, biayaSesi, totalSesi };
}

function submitAction(action) {
    const data = collectForm();
    if (!data) return;

    const label = action === 'send' ? 'Kirim invoice ke siswa' : 'Tandai pembayaran sebagai <strong>Lunas</strong>';
    confirmAction(label + '?', function() {
        const formId = action === 'send' ? 'formSend' : 'formLunas';
        const prefix = action === 'send' ? 'fs' : 'fl';

        document.getElementById(prefix + '_teacher_id').value    = data.teacherId;
        document.getElementById(prefix + '_total_biaya').value   = data.totalBiaya;
        document.getElementById(prefix + '_biaya_per_sesi').value = data.biayaSesi;
        document.getElementById(prefix + '_total_sessions').value = data.totalSesi;
        injectInterestSessionsIntoForm(prefix + '_interest_sessions_container');

        document.getElementById(formId).submit();
    }, null, {
        title: action === 'send' ? 'Kirim Invoice' : 'Tandai Lunas',
        okText: action === 'send' ? '<i class="bi bi-send me-1"></i>Kirim Invoice' : '<i class="bi bi-check-circle me-1"></i>Lunas',
        btnClass: action === 'send' ? 'btn-primary' : 'btn-success',
        type: 'warning'
    });
}

function confirmReject() {
    confirmAction('Pendaftaran ini akan <strong>ditolak</strong>. Lanjutkan?', function() {
        document.getElementById('rejectForm').submit();
    }, null, { title: 'Tolak Pendaftaran', okText: '<i class="bi bi-x-circle me-1"></i>Tolak', btnClass: 'btn-danger' });
}

// Init: update total sesi display on page load
document.addEventListener('DOMContentLoaded', function() {
    const sumEl = document.getElementById('totalSesiSum');
    if (sumEl) sumEl.textContent = getTotalSesiFromInputs();
});
</script>
@endsection
