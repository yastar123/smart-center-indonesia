@extends('layouts.app')
@section('title', 'Input Nilai Siswa')
@section('page-title', 'Input Nilai')

@php
if (!function_exists('getGradeLetter')) {
    function getGradeLetter($n) {
        if ($n >= 90) return 'A';
        if ($n >= 75) return 'B';
        if ($n >= 60) return 'C';
        if ($n >= 50) return 'D';
        return 'E';
    }
}
if (!function_exists('getGradeColor')) {
    function getGradeColor($n) {
        if ($n >= 90) return '#10b981';
        if ($n >= 75) return '#2563eb';
        if ($n >= 60) return '#f6af23';
        if ($n >= 50) return '#f97316';
        return '#ef4444';
    }
}
@endphp

@section('content')

@php
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Course;

$teacher  = Teacher::where('user_id', auth()->id())->first();
$courses  = $teacher
    ? Course::where('cabang_id', $teacher->branch_id)->orderBy('nama')->get()
    : collect();

$selCourse = request('course_id');
$selType   = request('jenis', 'tugas');
$selClass  = request('class_id');

$classes = $teacher && $selCourse
    ? SchoolClass::where('cabang_id', $teacher->branch_id)->orderBy('nama_kelas')->get()
    : collect();

$students = collect();
if ($teacher && $selCourse) {
    $students = Student::where('branch_id', $teacher->branch_id)
        ->where('status', 'aktif')
        ->orderBy('name')
        ->get();
}

$gradeTypes = [
    'tugas'   => ['label'=>'Tugas Harian',  'icon'=>'bi-pencil-square',     'color'=>'#2563eb', 'bg'=>'var(--soft-info-bg)'],
    'uts'     => ['label'=>'UTS',           'icon'=>'bi-file-earmark-text', 'color'=>'#c84ddf', 'bg'=>'var(--soft-primary-bg)'],
    'uas'     => ['label'=>'UAS',           'icon'=>'bi-journal-check',     'color'=>'#e09000', 'bg'=>'var(--soft-warning-bg)'],
    'praktek' => ['label'=>'Praktikum',     'icon'=>'bi-tools',             'color'=>'#059669', 'bg'=>'var(--soft-success-bg)'],
    'lainnya' => ['label'=>'Lainnya',       'icon'=>'bi-three-dots',        'color'=>'#64748b', 'bg'=>'var(--input-bg)'],
];

$selTypeData = $gradeTypes[$selType] ?? $gradeTypes['tugas'];

$existingGrades = [];
if ($students->isNotEmpty() && $selCourse) {
    $g = Grade::where('mata_pelajaran_id', $selCourse)
        ->where('jenis_penilaian', $selType)
        ->whereIn('siswa_id', $students->pluck('id'))
        ->get()
        ->keyBy('siswa_id');
    foreach ($g as $sid => $grade) {
        $existingGrades[$sid] = $grade->nilai;
    }
}
@endphp

{{-- HEADER BANNER --}}
<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div>
            <div style="font-size:11px;opacity:.6;margin-bottom:4px;text-transform:uppercase;letter-spacing:.08em">
                <i class="bi bi-bar-chart-line me-1"></i>Input Nilai
            </div>
            <h4 style="font-weight:800;margin-bottom:4px;color:white;letter-spacing:-.02em">
                Input Nilai Siswa
            </h4>
            <p style="opacity:.65;margin:0;font-size:13px">
                Pilih mata pelajaran, jenis penilaian, dan input nilai per siswa.
            </p>
        </div>
        <div style="font-size:64px;opacity:.08;line-height:1;flex-shrink:0">
            <i class="bi bi-award"></i>
        </div>
    </div>
</div>

@if(!$teacher)
<div class="alert alert-warning d-flex gap-3 align-items-start mb-4 fade-up" style="border-radius:14px;border:none">
    <i class="bi bi-exclamation-triangle-fill text-warning mt-1" style="font-size:18px;flex-shrink:0"></i>
    <div>
        <div class="fw-bold mb-1">Profil Guru Belum Terhubung</div>
        <div style="font-size:13px">Akun Anda belum terhubung ke profil guru. Hubungi administrator.</div>
    </div>
</div>
@endif

<div class="row g-4">

    {{-- LEFT: FILTER PANEL --}}
    <div class="col-lg-4 fade-up">

        {{-- Filter Form --}}
        <div class="dashboard-card mb-4">
            <h6 class="fw-bold mb-4" style="font-size:13px">
                <i class="bi bi-funnel text-primary me-2"></i>Filter Penilaian
            </h6>
            <form method="GET" id="filterForm">

                {{-- Mata Pelajaran --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:12px">Mata Pelajaran</label>
                    <select name="course_id" class="form-select" style="border-radius:10px;font-size:13px"
                            onchange="document.getElementById('filterForm').submit()">
                        <option value="">— Pilih Mata Pelajaran —</option>
                        @foreach($courses as $c)
                        <option value="{{ $c->id }}" {{ $selCourse == $c->id ? 'selected':'' }}>{{ $c->nama }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Kelas --}}
                @if($selCourse)
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:12px">Kelas</label>
                    <select name="class_id" class="form-select" style="border-radius:10px;font-size:13px"
                            onchange="document.getElementById('filterForm').submit()">
                        <option value="">— Semua Siswa Aktif —</option>
                        @foreach($classes as $cl)
                        <option value="{{ $cl->id }}" {{ $selClass == $cl->id ? 'selected':'' }}>{{ $cl->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Jenis Penilaian --}}
                @if($selCourse)
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:12px">Jenis Penilaian</label>
                    <input type="hidden" name="course_id" value="{{ $selCourse }}">
                    <input type="hidden" name="class_id"  value="{{ $selClass }}">
                    <div class="d-flex flex-column gap-2">
                        @foreach($gradeTypes as $key => $gt)
                        <label class="d-flex align-items-center gap-2 p-2 rounded-3 cursor-pointer"
                               style="border:1.5px solid {{ $selType===$key ? $gt['color'] : 'var(--card-border)' }};background:{{ $selType===$key ? $gt['bg'] : 'transparent' }};cursor:pointer;transition:.15s">
                            <input type="radio" name="jenis" value="{{ $key }}"
                                   {{ $selType===$key ? 'checked':'' }}
                                   onchange="document.getElementById('filterForm').submit()"
                                   style="accent-color:{{ $gt['color'] }};flex-shrink:0">
                            <i class="bi {{ $gt['icon'] }}" style="color:{{ $gt['color'] }};font-size:15px"></i>
                            <span style="font-size:13px;font-weight:{{ $selType===$key?'600':'400' }};color:{{ $selType===$key?$gt['color']:'var(--text-primary)' }}">{{ $gt['label'] }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif

            </form>
        </div>

        {{-- Grade Scale --}}
        <div class="dashboard-card">
            <h6 class="fw-bold mb-3" style="font-size:13px">
                <i class="bi bi-info-circle text-primary me-2"></i>Skala Nilai
            </h6>
            @php $scale = [['A','90–100','#10b981'],['B','75–89','#2563eb'],['C','60–74','#f6af23'],['D','50–59','#f97316'],['E','0–49','#ef4444']]; @endphp
            @foreach($scale as [$ltr, $range, $clr])
            <div class="d-flex align-items-center justify-content-between py-2" style="border-bottom:1px solid var(--card-border)">
                <div class="d-flex align-items-center gap-2">
                    <div style="width:28px;height:28px;border-radius:8px;background:{{ $clr }}20;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;color:{{ $clr }}">{{ $ltr }}</div>
                    <span style="font-size:12.5px">{{ $range }}</span>
                </div>
                @php $labels=['A'=>'Sangat Baik','B'=>'Baik','C'=>'Cukup','D'=>'Kurang','E'=>'Sangat Kurang']; @endphp
                <span style="font-size:11.5px;color:{{ $clr }};font-weight:600">{{ $labels[$ltr] }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- RIGHT: GRADE INPUT --}}
    <div class="col-lg-8 fade-up" style="animation-delay:.06s">

        @if(!$selCourse)
        <div class="dashboard-card h-100 d-flex flex-column align-items-center justify-content-center text-center py-5">
            <div style="width:80px;height:80px;border-radius:24px;background:var(--soft-success-bg);display:flex;align-items:center;justify-content:center;margin-bottom:20px">
                <i class="bi bi-journal-bookmark-fill" style="font-size:2.5rem;color:#059669;opacity:.4"></i>
            </div>
            <h6 class="fw-bold mb-2">Pilih Mata Pelajaran</h6>
            <p class="text-muted mb-0" style="font-size:13.5px;max-width:300px">
                Pilih mata pelajaran dan jenis penilaian di panel kiri untuk mulai input nilai.
            </p>
        </div>

        @else

        {{-- Header --}}
        <div class="dashboard-card mb-4" style="border-left:4px solid {{ $selTypeData['color'] }};background:linear-gradient(135deg,var(--card-bg),{{ $selTypeData['bg'] }})">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                <div>
                    <div style="font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:{{ $selTypeData['color'] }};font-weight:700;margin-bottom:6px">
                        {{ $selTypeData['label'] }}
                    </div>
                    <h5 class="fw-bold mb-1" style="font-size:17px">
                        {{ $courses->find($selCourse)?->nama ?? 'Mata Pelajaran' }}
                    </h5>
                    <div style="font-size:12.5px;color:var(--text-muted)">
                        <i class="bi bi-people me-1"></i>{{ $students->count() }} siswa aktif
                        @if($selClass)
                        · <i class="bi bi-diagram-3 me-1"></i>{{ $classes->find($selClass)?->nama_kelas ?? '' }}
                        @endif
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="d-flex align-items-center gap-1 p-2 rounded-3" style="background:{{ $selTypeData['bg'] }}">
                        <i class="bi {{ $selTypeData['icon'] }}" style="color:{{ $selTypeData['color'] }};font-size:20px"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Row --}}
        @if($students->isNotEmpty())
        <div class="row g-3 mb-4" id="gradeStats">
            <div class="col-3">
                <div class="text-center p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                    <div class="fw-bold" style="font-size:22px;color:#10b981" id="statA">0</div>
                    <div style="font-size:11px;color:var(--text-muted)">A (≥90)</div>
                </div>
            </div>
            <div class="col-3">
                <div class="text-center p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                    <div class="fw-bold" style="font-size:22px;color:#2563eb" id="statB">0</div>
                    <div style="font-size:11px;color:var(--text-muted)">B (75–89)</div>
                </div>
            </div>
            <div class="col-3">
                <div class="text-center p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                    <div class="fw-bold" style="font-size:22px;color:#f6af23" id="statC">0</div>
                    <div style="font-size:11px;color:var(--text-muted)">C (60–74)</div>
                </div>
            </div>
            <div class="col-3">
                <div class="text-center p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                    <div class="fw-bold" style="font-size:22px;color:#ef4444" id="statD">0</div>
                    <div style="font-size:11px;color:var(--text-muted)">D/E (&lt;60)</div>
                </div>
            </div>
        </div>
        @endif

        {{-- Grade Input Table --}}
        <div class="dashboard-card">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h6 class="fw-bold mb-0" style="font-size:14px">
                    <i class="bi bi-table me-2 text-primary"></i>Input Nilai Siswa
                </h6>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-secondary" onclick="autoFill()" style="border-radius:8px;font-size:12px">
                        <i class="bi bi-magic me-1"></i>Isi Otomatis (Random)
                    </button>
                </div>
            </div>

            @if($students->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-people d-block mb-3" style="font-size:3rem;opacity:.2"></i>
                <p class="text-muted mb-0" style="font-size:13px">Tidak ada siswa aktif yang ditemukan.</p>
            </div>
            @else
            <form id="gradeForm" onsubmit="submitGrades(event)">
                <input type="hidden" name="course_id" value="{{ $selCourse }}">
                <input type="hidden" name="jenis"     value="{{ $selType }}">
                <div class="table-responsive">
                    <table class="table align-middle mb-0" style="font-size:13px">
                        <thead>
                            <tr style="background:var(--input-bg)">
                                <th class="py-2 ps-3" style="color:var(--text-muted);font-weight:600;font-size:11px;text-transform:uppercase;width:40px">#</th>
                                <th class="py-2" style="color:var(--text-muted);font-weight:600;font-size:11px;text-transform:uppercase">Nama Siswa</th>
                                <th class="py-2 text-center" style="color:var(--text-muted);font-weight:600;font-size:11px;text-transform:uppercase;width:110px">Nilai</th>
                                <th class="py-2 text-center" style="color:var(--text-muted);font-weight:600;font-size:11px;text-transform:uppercase;width:70px">Huruf</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $i => $s)
                            @php
                                $existNilai = $existingGrades[$s->id] ?? null;
                                $avatar = 'https://ui-avatars.com/api/?name='.urlencode($s->name).'&background='.($s->gender==='P'?'ec4899':'3b82f6').'&color=fff&size=64';
                            @endphp
                            <tr class="grade-row" data-student="{{ $s->id }}">
                                <td class="py-3 ps-3 text-muted" style="font-size:12px">{{ $i+1 }}</td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $s->photo ? \Storage::url($s->photo) : $avatar }}"
                                             class="rounded-circle flex-shrink-0"
                                             width="34" height="34"
                                             style="object-fit:cover"
                                             loading="lazy">
                                        <div>
                                            <div class="fw-semibold" style="font-size:13px">{{ $s->name }}</div>
                                            <div class="text-muted" style="font-size:11px">{{ $s->nis ?? 'NIS-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 text-center">
                                    <input type="number"
                                           name="grades[{{ $s->id }}]"
                                           id="grade_{{ $s->id }}"
                                           class="form-control text-center fw-bold grade-input"
                                           min="0" max="100"
                                           value="{{ $existNilai }}"
                                           placeholder="—"
                                           oninput="updateGradeDisplay(this, {{ $s->id }})"
                                           style="border-radius:10px;font-size:14px;width:90px;margin:0 auto;border-color:var(--card-border);background:var(--input-bg)">
                                </td>
                                <td class="py-3 text-center">
                                    <span id="letter_{{ $s->id }}" class="badge fw-bold"
                                          style="font-size:13px;padding:5px 12px;border-radius:8px;background:{{ $existNilai ? getGradeColor($existNilai) : 'var(--card-border)' }};color:{{ $existNilai ? '#fff' : 'var(--text-muted)' }}">
                                        {{ $existNilai ? getGradeLetter($existNilai) : '—' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-between align-items-center pt-3 flex-wrap gap-2"
                     style="border-top:1px solid var(--card-border)">
                    <div style="font-size:12.5px;color:var(--text-muted)">
                        <i class="bi bi-info-circle me-1"></i>
                        Rata-rata kelas: <strong id="avgDisplay">—</strong>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary px-4" onclick="clearAll()" style="border-radius:10px">
                            <i class="bi bi-trash me-2"></i>Hapus Semua
                        </button>
                        <button type="submit" class="btn btn-primary px-5 fw-semibold" id="submitGradeBtn" style="border-radius:10px">
                            <i class="bi bi-save me-2"></i>Simpan Nilai
                        </button>
                    </div>
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

function getLetterLocal(n) {
    if (n >= 90) return ['A','#10b981'];
    if (n >= 75) return ['B','#2563eb'];
    if (n >= 60) return ['C','#f6af23'];
    if (n >= 50) return ['D','#f97316'];
    return ['E','#ef4444'];
}

function updateGradeDisplay(input, sid) {
    const val = parseFloat(input.value);
    const span = document.getElementById(`letter_${sid}`);
    if (isNaN(val) || input.value === '') {
        span.textContent = '—';
        span.style.background = 'var(--card-border)';
        span.style.color = 'var(--text-muted)';
        input.style.borderColor = 'var(--card-border)';
    } else {
        const [letter, color] = getLetterLocal(Math.min(100, Math.max(0, val)));
        span.textContent = letter;
        span.style.background = color;
        span.style.color = '#fff';
        input.style.borderColor = color;
        input.style.color = color;
    }
    updateStats();
    updateAvg();
}

function updateStats() {
    let a=0,b=0,c=0,d=0;
    document.querySelectorAll('.grade-input').forEach(inp => {
        const v = parseFloat(inp.value);
        if (isNaN(v)) return;
        if (v>=90) a++; else if (v>=75) b++; else if (v>=60) c++; else d++;
    });
    const sa=document.getElementById('statA'),sb=document.getElementById('statB'),sc=document.getElementById('statC'),sd=document.getElementById('statD');
    if(sa) sa.textContent=a;
    if(sb) sb.textContent=b;
    if(sc) sc.textContent=c;
    if(sd) sd.textContent=d;
}

function updateAvg() {
    const vals = [...document.querySelectorAll('.grade-input')]
        .map(i => parseFloat(i.value))
        .filter(v => !isNaN(v));
    const el = document.getElementById('avgDisplay');
    if (!el) return;
    if (vals.length === 0) { el.textContent = '—'; return; }
    const avg = (vals.reduce((a,b)=>a+b,0)/vals.length).toFixed(1);
    el.textContent = avg + ` (${getLetterLocal(avg)[0]})`;
    el.style.color = getLetterLocal(parseFloat(avg))[1];
}

function autoFill() {
    document.querySelectorAll('.grade-input').forEach(inp => {
        const v = Math.floor(Math.random() * 40) + 60;
        inp.value = v;
        const sid = inp.id.replace('grade_','');
        updateGradeDisplay(inp, sid);
    });
}

function clearAll() {
    document.querySelectorAll('.grade-input').forEach(inp => {
        inp.value = '';
        const sid = inp.id.replace('grade_','');
        updateGradeDisplay(inp, sid);
    });
}

async function submitGrades(e) {
    e.preventDefault();
    const inputs = [...document.querySelectorAll('.grade-input')];
    const filled = inputs.filter(i => i.value !== '').length;
    if (filled === 0) {
        window.showToast('Belum ada nilai yang diisi!', 'warning');
        return;
    }
    const btn = document.getElementById('submitGradeBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

    const fd = new FormData();
    fd.append('_token', document.querySelector('meta[name=csrf-token]').content);
    fd.append('course_id', document.querySelector('[name=course_id]').value);
    fd.append('jenis',     document.querySelector('[name=jenis]').value);
    inputs.forEach(inp => {
        if (inp.value !== '') {
            const sid = inp.id.replace('grade_', '');
            fd.append(`grades[${sid}]`, inp.value);
        }
    });

    try {
        const res  = await fetch('{{ route("guru.grades.storeBatch") }}', { method: 'POST', body: fd });
        if (!res.ok) throw new Error('HTTP ' + res.status);
        const data = await res.json();
        if (data.success) {
            window.showToast(data.message || 'Nilai berhasil disimpan!', 'success');
        } else {
            window.showToast(data.message || 'Gagal menyimpan nilai.', 'error');
        }
    } catch (err) {
        window.showToast('Terjadi kesalahan jaringan. Coba lagi.', 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-save me-2"></i>Simpan Nilai';
    }
}

// Init stats on page load
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.grade-input').forEach(inp => {
        if (inp.value !== '') {
            const sid = inp.id.replace('grade_','');
            updateGradeDisplay(inp, sid);
        }
    });
});
</script>
@endpush
