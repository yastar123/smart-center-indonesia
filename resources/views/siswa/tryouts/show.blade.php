@extends('layouts.app')
@section('title', $tryout->judul)
@section('page-title', 'Tryout Online')

@push('styles')
<style>
.cbt-wrap { display:flex;gap:16px;align-items:flex-start }
.cbt-main { flex:1;min-width:0 }
.cbt-nav  { width:220px;flex-shrink:0;position:sticky;top:80px }
@media(max-width:767px){ .cbt-wrap{flex-direction:column} .cbt-nav{width:100%;position:static} }
.q-num-btn { width:36px;height:36px;border-radius:8px;border:1.5px solid var(--card-border);background:var(--card-bg);color:var(--text-main);font-size:13px;font-weight:600;cursor:pointer;transition:.15s;display:inline-flex;align-items:center;justify-content:center }
.q-num-btn.answered { background:var(--bs-primary);border-color:var(--bs-primary);color:white }
.q-num-btn.current  { outline:2.5px solid var(--bs-primary);outline-offset:2px }
.option-label { display:flex;align-items:flex-start;gap:12px;padding:14px 16px;border:2px solid var(--card-border);border-radius:12px;cursor:pointer;transition:.15s;margin-bottom:10px;background:var(--card-bg) }
.option-label:hover { border-color:var(--bs-primary);background:var(--soft-primary-bg) }
.option-label input[type=radio]:checked ~ * { color:var(--bs-primary) }
.option-label:has(input:checked) { border-color:var(--bs-primary);background:var(--soft-primary-bg) }
.option-key { width:32px;height:32px;border-radius:8px;background:var(--body-bg);border:1.5px solid var(--card-border);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;flex-shrink:0;transition:.15s }
.option-label:has(input:checked) .option-key { background:var(--bs-primary);border-color:var(--bs-primary);color:white }
.timer-bar { height:6px;border-radius:3px;background:linear-gradient(90deg,#10b981,#c84ddf);transition:width .5s ease }
.timer-bar.warn { background:linear-gradient(90deg,#f59e0b,#ef4444) }
.timer-bar.crit { background:#ef4444;animation:blink-bar .8s ease-in-out infinite }
@keyframes blink-bar { 0%,100%{opacity:1}50%{opacity:.4} }
.cbt-question { display:none }
.cbt-question.active { display:block }
</style>
@endpush

@section('content')

@php
$totalSoal     = $soal->count();
$durasiDetik   = $tryout->durasi_menit * 60;
$waktuMulai    = $activeAttempt->waktu_mulai;
$elapsed       = now()->diffInSeconds($waktuMulai);
$sisaDetik     = max(0, $durasiDetik - $elapsed);
$opts          = ['A','B','C','D','E','F'];
@endphp

{{-- TIMER HEADER --}}
<div class="dashboard-card mb-4 fade-up" style="padding:14px 20px;border:none;background:linear-gradient(135deg,#260632,#461256,#c84ddf);color:white;border-radius:16px">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <div style="font-size:11px;opacity:.6;text-transform:uppercase;letter-spacing:.08em;margin-bottom:3px">{{ $tryout->kategori }}</div>
            <div style="font-weight:700;font-size:16px">{{ $tryout->judul }}</div>
        </div>
        <div class="d-flex align-items-center gap-4">
            <div class="text-center">
                <div id="timerDisplay" style="font-size:28px;font-weight:800;letter-spacing:.04em;font-variant-numeric:tabular-nums">
                    {{ sprintf('%02d:%02d', floor($sisaDetik/60), $sisaDetik%60) }}
                </div>
                <div style="font-size:10px;opacity:.6">Sisa Waktu</div>
            </div>
            <div class="text-center">
                <div style="font-size:20px;font-weight:700"><span id="answeredCount">0</span>/{{ $totalSoal }}</div>
                <div style="font-size:10px;opacity:.6">Terjawab</div>
            </div>
        </div>
    </div>
    <div style="margin-top:10px;background:rgba(255,255,255,.2);border-radius:3px;height:6px;overflow:hidden">
        <div id="timerBar" class="timer-bar" style="width:100%;background:rgba(255,255,255,.6)"></div>
    </div>
</div>

<form id="cbtForm" method="POST" action="{{ route('siswa.tryout.submit', $tryout->id) }}">
@csrf

<div class="cbt-wrap">
    {{-- QUESTIONS --}}
    <div class="cbt-main">
        @foreach($soal as $i => $s)
        @php $keys = $opts; @endphp
        <div class="cbt-question dashboard-card fade-up {{ $i === 0 ? 'active' : '' }}" id="q{{ $i }}" style="border-radius:16px">
            <div class="d-flex align-items-center gap-2 mb-3">
                <span style="background:linear-gradient(135deg,#461256,#c84ddf);color:white;width:32px;height:32px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;flex-shrink:0">
                    {{ $i + 1 }}
                </span>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    @php $diffColor = ['mudah'=>'success','sedang'=>'warning','sulit'=>'danger'] @endphp
                    <span class="badge bg-{{ $diffColor[$s->tingkat_kesulitan] ?? 'secondary' }}">{{ ucfirst($s->tingkat_kesulitan) }}</span>
                    <span class="badge" style="background:var(--soft-primary-bg);color:var(--bs-primary);border:1px solid rgba(200,77,223,.2)">{{ $s->poin }} poin</span>
                </div>
            </div>

            <div style="font-size:15px;font-weight:500;line-height:1.65;margin-bottom:20px;color:var(--text-main)">
                {!! nl2br(e($s->teks_pertanyaan)) !!}
            </div>

            @if($s->jenis === 'isian')
            <div>
                <input type="text" name="jawaban[{{ $s->id }}]" class="form-control jawaban-input" data-qidx="{{ $i }}"
                       placeholder="Tulis jawaban kamu di sini..."
                       style="border-radius:10px;padding:12px 16px;font-size:14px"
                       autocomplete="off">
            </div>
            @elseif($s->jenis === 'benar_salah')
            <div>
                @foreach(['Benar','Salah'] as $bi => $bopt)
                <label class="option-label">
                    <input type="radio" name="jawaban[{{ $s->id }}]" value="{{ $bi }}" class="d-none jawaban-input" data-qidx="{{ $i }}">
                    <span class="option-key">{{ $bi === 0 ? 'B' : 'S' }}</span>
                    <span style="font-size:14px;padding-top:2px">{{ $bopt }}</span>
                </label>
                @endforeach
            </div>
            @else
            {{-- Pilihan ganda --}}
            <div>
                @foreach(($s->pilihan_jawaban ?? []) as $pi => $pteks)
                @if($pteks)
                <label class="option-label">
                    <input type="radio" name="jawaban[{{ $s->id }}]" value="{{ $pi }}" class="d-none jawaban-input" data-qidx="{{ $i }}">
                    <span class="option-key">{{ $keys[$pi] ?? $pi }}</span>
                    <span style="font-size:14px;line-height:1.5;padding-top:2px">{{ $pteks }}</span>
                </label>
                @endif
                @endforeach
            </div>
            @endif

            <div class="d-flex justify-content-between mt-4 pt-3" style="border-top:1px solid var(--card-border)">
                @if($i > 0)
                <button type="button" onclick="goTo({{ $i - 1 }})" class="btn btn-outline-secondary" style="border-radius:8px;font-size:13px">
                    <i class="bi bi-chevron-left me-1"></i>Sebelumnya
                </button>
                @else
                <div></div>
                @endif

                @if($i < $totalSoal - 1)
                <button type="button" onclick="goTo({{ $i + 1 }})" class="btn btn-primary" style="border-radius:8px;font-size:13px">
                    Selanjutnya<i class="bi bi-chevron-right ms-1"></i>
                </button>
                @else
                <button type="button" onclick="confirmSubmit()" class="btn btn-success px-4" style="border-radius:8px;font-weight:600;font-size:13px">
                    <i class="bi bi-send-fill me-2"></i>Selesai & Kumpulkan
                </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- NAVIGATION PANEL --}}
    <div class="cbt-nav fade-up">
        <div class="dashboard-card" style="border-radius:16px">
            <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text-muted);margin-bottom:12px">
                <i class="bi bi-grid-3x3-gap me-1"></i>Navigasi Soal
            </div>
            <div id="qNavGrid" style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:16px">
                @foreach($soal as $i => $s)
                <button type="button" onclick="goTo({{ $i }})" class="q-num-btn {{ $i === 0 ? 'current' : '' }}" id="qbtn{{ $i }}" title="Soal {{ $i+1 }}">
                    {{ $i + 1 }}
                </button>
                @endforeach
            </div>
            <div style="font-size:11px;color:var(--text-muted);margin-bottom:12px">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <div style="width:14px;height:14px;border-radius:4px;background:var(--bs-primary)"></div>
                    <span>Sudah dijawab</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div style="width:14px;height:14px;border-radius:4px;border:1.5px solid var(--card-border);background:var(--card-bg)"></div>
                    <span>Belum dijawab</span>
                </div>
            </div>
            <button type="button" onclick="confirmSubmit()" class="btn btn-success w-100" style="border-radius:8px;font-size:13px;font-weight:600">
                <i class="bi bi-send-fill me-2"></i>Kumpulkan
            </button>
        </div>
    </div>
</div>

</form>

@endsection

@push('scripts')
<script>
const TOTAL_DETIK = {{ $durasiDetik }};
const SISA_DETIK  = {{ $sisaDetik }};
let sisaWaktu     = SISA_DETIK;
let currentQ      = 0;
let answered      = {};

function goTo(idx) {
    document.getElementById('q' + currentQ)?.classList.remove('active');
    document.getElementById('qbtn' + currentQ)?.classList.remove('current');
    currentQ = idx;
    document.getElementById('q' + idx)?.classList.add('active');
    document.getElementById('qbtn' + idx)?.classList.add('current');
    document.getElementById('q' + idx)?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function markAnswered(qidx) {
    answered[qidx] = true;
    const btn = document.getElementById('qbtn' + qidx);
    if (btn) btn.classList.add('answered');
    document.getElementById('answeredCount').textContent = Object.keys(answered).length;
}

document.querySelectorAll('.jawaban-input').forEach(el => {
    const ev = el.tagName === 'INPUT' && el.type === 'text' ? 'input' : 'change';
    el.addEventListener(ev, function() {
        const val = this.value?.trim?.() ?? this.value;
        if (val !== '' && val !== undefined) markAnswered(parseInt(this.dataset.qidx));
    });
});

// Timer
const timerEl = document.getElementById('timerDisplay');
const timerBar = document.getElementById('timerBar');

function updateTimer() {
    if (sisaWaktu <= 0) {
        timerEl.textContent = '00:00';
        timerBar.style.width = '0%';
        showToast('Waktu habis! Jawaban dikumpulkan otomatis.', 'warning');
        setTimeout(() => document.getElementById('cbtForm').submit(), 1500);
        return;
    }
    const m = Math.floor(sisaWaktu / 60);
    const s = sisaWaktu % 60;
    timerEl.textContent = String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
    const pct = (sisaWaktu / TOTAL_DETIK) * 100;
    timerBar.style.width = pct + '%';
    if (sisaWaktu <= 60) {
        timerBar.classList.add('crit');
        timerEl.style.color = '#fca5a5';
    } else if (sisaWaktu <= 300) {
        timerBar.classList.add('warn');
        timerEl.style.color = '#fcd34d';
    }
    sisaWaktu--;
}
updateTimer();
const timerInterval = setInterval(updateTimer, 1000);

function confirmSubmit() {
    clearInterval(timerInterval);
    const totalSoal  = {{ $totalSoal }};
    const terjawab   = Object.keys(answered).length;
    const belumJawab = totalSoal - terjawab;

    confirmAction(
        belumJawab > 0
            ? `Masih ada <strong>${belumJawab} soal</strong> yang belum dijawab. Yakin ingin mengumpulkan?`
            : 'Semua soal sudah dijawab. Kumpulkan jawaban?',
        function() { document.getElementById('cbtForm').submit(); },
        null,
        { title: 'Kumpulkan Jawaban', okText: '<i class="bi bi-send-fill me-1"></i>Ya, Kumpulkan', btnClass: 'btn-success' }
    );
}
</script>
@endpush
