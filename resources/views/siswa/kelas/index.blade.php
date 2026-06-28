@extends('layouts.app')
@section('title', 'Kelas Saya')
@section('page-title', 'Kelas Saya')

@section('content')
<div>

{{-- HEADER --}}
<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3">
                <div style="width:52px;height:52px;border-radius:16px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0">
                    <i class="bi bi-collection-fill"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-1" style="color:white;letter-spacing:-.02em">Kelas Saya</h4>
                    <p class="mb-0" style="opacity:.75;font-size:13px">Akses modul, video, dan latihan soal dari paket Anda.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('siswa.schedule-agreements.index') }}" class="btn fw-semibold px-4"
               style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;font-size:13px">
                <i class="bi bi-plus-circle me-2"></i>Request Kelas Tambahan
            </a>
        </div>
    </div>
</div>

{{-- PROGRAM TERDAFTAR dari Registrasi --}}
@if(isset($registration) && $registration && !empty($registration->interest_sessions))
<div class="dashboard-card mb-4 fade-up">
    <div class="d-flex align-items-center gap-2 mb-3">
        <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#260632,#c84ddf);display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <i class="bi bi-bookmark-star-fill text-white" style="font-size:.9rem"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-0" style="font-size:.95rem">Program Terdaftar</h6>
            <div class="text-muted" style="font-size:.75rem">Mata pelajaran &amp; jumlah sesi yang ditetapkan admin</div>
        </div>
    </div>
    <div class="row g-3">
        @php
            $interestSessions = $registration->interest_sessions ?? [];
            $totalSesiAll = array_sum($interestSessions);
            $colors = [
                'linear-gradient(135deg,#6366f1,#8b5cf6)',
                'linear-gradient(135deg,#0284c7,#38bdf8)',
                'linear-gradient(135deg,#10b981,#34d399)',
                'linear-gradient(135deg,#f59e0b,#fcd34d)',
                'linear-gradient(135deg,#ec4899,#f9a8d4)',
                'linear-gradient(135deg,#c84ddf,#a855f7)',
                'linear-gradient(135deg,#14b8a6,#2dd4bf)',
                'linear-gradient(135deg,#ef4444,#f87171)',
            ];
            $i = 0;
        @endphp
        @foreach($interestSessions as $subject => $sessions)
        @php $grad = $colors[$i % count($colors)]; $i++; @endphp
        <div class="col-sm-6 col-lg-4">
            <div class="d-flex align-items-center gap-3 p-3 rounded-3 h-100"
                 style="background:var(--input-bg);border:1px solid var(--card-border)">
                <div style="width:42px;height:42px;border-radius:12px;background:{{ $grad }};display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="bi bi-book-fill text-white" style="font-size:.9rem"></i>
                </div>
                <div style="min-width:0">
                    <div class="fw-semibold text-truncate" style="font-size:.85rem;color:var(--text-primary)" title="{{ $subject }}">{{ $subject }}</div>
                    <div class="mt-1 d-flex align-items-center gap-1">
                        <span class="fw-bold" style="font-size:1rem;color:var(--primary)">{{ $sessions }}</span>
                        <span class="text-muted" style="font-size:.75rem">sesi</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @if($totalSesiAll > 0)
    <div class="mt-3 pt-3 d-flex align-items-center justify-content-between" style="border-top:1px solid var(--card-border)">
        <span class="text-muted" style="font-size:.78rem"><i class="bi bi-info-circle me-1"></i>Ditetapkan oleh admin cabang</span>
        <span class="fw-bold" style="font-size:.82rem;color:var(--primary)">
            Total {{ $totalSesiAll }} sesi
        </span>
    </div>
    @endif
</div>
@endif

{{-- TABLE --}}
<div class="dashboard-card fade-up">
    <div class="table-responsive">
        <table class="table table-modern align-middle mb-0" style="min-width:900px">
            <thead class="thead-modern">
                <tr>
                    <th class="ps-3" style="min-width:240px">Paket Belajar</th>
                    <th style="min-width:90px">Tipe</th>
                    <th style="min-width:180px">Jadwal Berikutnya</th>
                    <th style="min-width:150px">Progres Belajar</th>
                    <th style="min-width:160px">Status</th>
                    <th class="text-center" style="min-width:160px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paginator as $row)
                @php
                    $statusMap = [
                        'berlangsung'        => ['label'=>'Berjalan',            'bg'=>'var(--soft-success-bg)',  'color'=>'var(--soft-success-text)'],
                        'menunggu'           => ['label'=>'Menunggu',            'bg'=>'var(--soft-warning-bg)',  'color'=>'var(--soft-warning-text)'],
                        'selesai'            => ['label'=>'Selesai',             'bg'=>'var(--soft-muted-bg)',    'color'=>'var(--soft-muted-text)'],
                        'menunggu_konfirmasi'=> ['label'=>'Menunggu Konfirmasi', 'bg'=>'var(--soft-info-bg)',    'color'=>'var(--soft-info-text)'],
                        'proposed'           => ['label'=>'Proposed',            'bg'=>'var(--soft-primary-bg)', 'color'=>'var(--soft-primary-text)'],
                    ];
                    $badge = $statusMap[$row['status']] ?? $statusMap['menunggu'];

                    $pct   = $row['total'] > 0 ? min(100, round($row['done'] / $row['total'] * 100)) : 0;
                    $barClr = $pct >= 100 ? '#10b981' : ($pct >= 50 ? '#c84ddf' : '#f6af23');

                    $subjColors = [
                        'mat' => 'linear-gradient(135deg,#6366f1,#8b5cf6)',
                        'fis' => 'linear-gradient(135deg,#0284c7,#38bdf8)',
                        'kim' => 'linear-gradient(135deg,#10b981,#34d399)',
                        'ing' => 'linear-gradient(135deg,#f59e0b,#fcd34d)',
                        'bio' => 'linear-gradient(135deg,#059669,#10b981)',
                        'eng' => 'linear-gradient(135deg,#f59e0b,#fcd34d)',
                    ];
                    $avatarBg = 'linear-gradient(135deg,#461256,#c84ddf)';
                    foreach ($subjColors as $key => $grad) {
                        if (str_contains(strtolower($row['subject_name']), $key)) {
                            $avatarBg = $grad; break;
                        }
                    }

                    $ni = $row['next_info'];
                @endphp
                <tr style="border-bottom:1px solid var(--card-border);vertical-align:middle">

                    {{-- Paket Belajar --}}
                    <td class="ps-3">
                        <div class="d-flex align-items-start gap-3">
                            <div style="width:42px;height:42px;border-radius:12px;background:{{ $avatarBg }};color:white;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;flex-shrink:0">
                                {{ $row['initials'] }}
                            </div>
                            <div style="min-width:0">
                                <div class="fw-bold text-truncate" style="font-size:13px;max-width:180px">{{ $row['nama_kelas'] }}</div>
                                <span class="badge mt-1" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);font-size:10px;border-radius:6px">
                                    {{ $row['subject_name'] }}
                                </span>
                            </div>
                        </div>
                    </td>

                    {{-- Tipe --}}
                    <td>
                        @php
                            $tipeBg = $row['tipe_label'] === 'PRIVAT'
                                ? 'background:var(--soft-warning-bg);color:var(--soft-warning-text)'
                                : 'background:var(--soft-success-bg);color:var(--soft-success-text)';
                        @endphp
                        <span class="badge fw-bold" style="{{ $tipeBg }};font-size:10px;border-radius:6px;letter-spacing:.04em">
                            {{ $row['tipe_label'] }}
                        </span>
                    </td>

                    {{-- Jadwal Berikutnya --}}
                    <td>
                        @if(!$ni)
                            <span class="text-muted" style="font-size:12px">Tidak Ada</span>
                        @elseif($ni['type'] === 'berlangsung')
                            <span class="badge fw-semibold" style="background:rgba(16,185,129,.15);color:#059669;font-size:10px;padding:5px 9px;border-radius:8px;display:inline-block">
                                <i class="bi bi-record-circle-fill me-1" style="font-size:9px"></i>SEDANG BERLANGSUNG
                            </span>
                        @elseif($ni['type'] === 'proposed')
                            <div>
                                <span class="badge fw-semibold mb-1" style="background:rgba(200,77,223,.15);color:#8b1dc5;font-size:10px;padding:4px 8px;border-radius:7px;display:block;width:fit-content">
                                    <i class="bi bi-exclamation-circle me-1"></i>PERSETUJUAN DIPERLUKAN
                                </span>
                                @foreach($ni['slots'] as $slot)
                                <div class="text-muted" style="font-size:11px"><i class="bi bi-clock me-1"></i>{{ $slot }}</div>
                                @endforeach
                            </div>
                        @elseif($ni['type'] === 'jadwal')
                            <div class="fw-semibold" style="font-size:13px;color:var(--text-color)">
                                <i class="bi bi-calendar3 me-1 text-muted" style="font-size:11px"></i>{{ $ni['text'] }}
                            </div>
                        @endif
                    </td>

                    {{-- Progres Belajar --}}
                    <td>
                        <div class="fw-semibold" style="font-size:14px;color:var(--text-color)">
                            {{ $row['done'] }}/{{ $row['total'] ?: '?' }}
                        </div>
                        @if($row['total'] > 0)
                        <div class="mt-2" style="height:6px;background:var(--card-border);border-radius:99px;width:110px;overflow:hidden">
                            <div style="height:100%;width:{{ $pct }}%;background:{{ $barClr }};border-radius:99px;transition:width .4s"></div>
                        </div>
                        <div class="text-muted mt-1" style="font-size:10px">{{ $pct }}% selesai</div>
                        @endif
                    </td>

                    {{-- Status --}}
                    <td>
                        <span style="background:{{ $badge['bg'] }};color:{{ $badge['color'] }};padding:5px 11px;border-radius:8px;font-size:11px;font-weight:600;white-space:nowrap">
                            {{ $badge['label'] }}
                        </span>
                    </td>

                    {{-- Aksi --}}
                    <td class="text-center">
                        <div class="d-flex gap-1 flex-wrap justify-content-center">
                            @if($row['status'] === 'proposed')
                                @foreach($row['proposals'] as $proposal)
                                <form method="POST" action="{{ route('siswa.schedule-agreements.reject', $proposal->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger fw-semibold" style="font-size:11px;border-radius:8px">
                                        <i class="bi bi-x-lg me-1"></i>Tolak
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('siswa.schedule-agreements.approve', $proposal->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success fw-semibold" style="font-size:11px;border-radius:8px">
                                        <i class="bi bi-check-lg me-1"></i>Setuju
                                    </button>
                                </form>
                                @endforeach
                            @elseif($row['status'] === 'menunggu_konfirmasi')
                                <button class="btn btn-sm btn-outline-secondary fw-semibold" style="font-size:11px;border-radius:8px" disabled>
                                    <i class="bi bi-hourglass-split me-1"></i>Sedang Diproses
                                </button>
                            @else
                                @if($row['status'] === 'selesai')
                                <a href="{{ route('siswa.certificates.index') }}" class="btn btn-sm fw-semibold"
                                   style="font-size:11px;border-radius:8px;background:var(--soft-warning-bg);color:var(--soft-warning-text);border:1px solid var(--soft-warning-border)">
                                    <i class="bi bi-award me-1"></i>Sertifikat
                                </a>
                                @endif
                                <a href="{{ route('siswa.attendance.show', $row['id']) }}" class="btn btn-sm btn-primary fw-semibold" style="font-size:11px;border-radius:8px">
                                    <i class="bi bi-info-circle me-1"></i>Detail Kelas
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="bi bi-collection" style="font-size:48px;color:var(--text-muted);opacity:.4;display:block;margin-bottom:12px"></i>
                        <div class="fw-semibold mb-1">Belum terdaftar di kelas manapun</div>
                        <div class="text-muted" style="font-size:13px">Hubungi admin cabang untuk mendaftarkan kelas.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($paginator->hasPages())
    <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="text-muted" style="font-size:13px">
            Menampilkan {{ $paginator->firstItem() }} - {{ $paginator->lastItem() }} dari {{ $paginator->total() }} Paket
        </div>
        <div>{{ $paginator->links() }}</div>
    </div>
    @else
    <div class="mt-3 text-muted" style="font-size:13px">
        Menampilkan 1 - {{ $paginator->count() }} dari {{ $paginator->total() }} Paket
    </div>
    @endif
</div>

</div>
@endsection

@push('scripts')
<script>
// Handle approve/reject as AJAX so page stays, then reload
document.querySelectorAll('form[action*="approve"], form[action*="reject"]').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        const origText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>';
        fetch(this.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success !== false) {
                showToast('Berhasil diperbarui.', 'success');
                setTimeout(() => location.reload(), 800);
            } else {
                showToast(data.message || 'Gagal memperbarui.', 'error');
                btn.disabled = false;
                btn.innerHTML = origText;
            }
        })
        .catch(() => {
            showToast('Terjadi kesalahan.', 'error');
            btn.disabled = false;
            btn.innerHTML = origText;
        });
    });
});
</script>
@endpush
