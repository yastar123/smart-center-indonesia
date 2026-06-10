@extends('layouts.app')
@section('title','Riwayat — '.$course->nama)
@section('page-title','Riwayat Absensi')

@section('content')

<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('siswa.attendance') }}"
                   class="btn btn-sm"
                   style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.25);border-radius:9px;padding:6px 12px;flex-shrink:0">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
                <div>
                    <div style="font-size:11px;opacity:.6;margin-bottom:2px;text-transform:uppercase;letter-spacing:.08em">
                        Riwayat Absensi
                    </div>
                    <h5 class="fw-bold mb-0" style="color:white">{{ $course->nama }}</h5>
                </div>
            </div>
        </div>
    </div>
</div>

@if($classes->isEmpty())
<div class="dashboard-card fade-up">
    <div class="text-center py-5 text-muted">Tidak ada kelas ditemukan.</div>
</div>
@else
@foreach($classes as $class)
<div class="dashboard-card mb-4 fade-up">
    <h6 class="fw-bold mb-3">{{ $class->nama_kelas }}</h6>

    @if($class->jadwal->isEmpty())
    <p class="text-muted mb-0">Belum ada pertemuan.</p>
    @else
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="thead-modern">
                <tr>
                    <th>#</th>
                    <th>Pertemuan</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Persetujuan Jadwal</th>
                    <th>Absensi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($class->jadwal as $i => $j)
                @php
                    $agreement = $agreements[$j->id] ?? null;
                    $status = $myAttendance[$j->id] ?? null;
                    $scheduleLocked = $lockService->isScheduleLocked($j);
                    $attendanceLocked = $lockService->isAttendanceLocked($j);
                    $statusMap = [
                        'hadir' => ['bg'=>'var(--soft-success-bg)','color'=>'#10b981','label'=>'Hadir'],
                        'izin'  => ['bg'=>'var(--soft-info-bg)','color'=>'#0284c7','label'=>'Izin'],
                        'sakit' => ['bg'=>'var(--soft-warning-bg)','color'=>'#f6af23','label'=>'Sakit'],
                        'alpa'  => ['bg'=>'var(--soft-danger-bg)','color'=>'#ef4444','label'=>'Alpa'],
                    ];
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="fw-semibold">Pertemuan #{{ $j->pertemuan_ke }}</td>
                    <td>{{ $j->tanggal->locale('id')->isoFormat('D MMM Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</td>
                    <td>
                        @if($agreement && $agreement->isAgreed())
                        <span class="badge bg-success"><i class="bi bi-check2-all me-1"></i>Sepakat</span>
                        @else
                        <span class="badge bg-warning text-dark">Menunggu</span>
                        @if(!$scheduleLocked)
                        <form action="{{ route('siswa.schedules.confirm', $j->id) }}" method="POST" class="d-inline ms-1 confirm-schedule-form">
                            @csrf
                            <button type="submit" class="btn btn-xs btn-outline-primary btn-sm" style="font-size:11px">
                                Konfirmasi
                            </button>
                        </form>
                        @else
                        <span class="text-muted small d-block">Terkunci H-1</span>
                        @endif
                        @endif
                    </td>
                    <td>
                        @if($status)
                        @php $b = $statusMap[$status] ?? null; @endphp
                        <span class="badge" style="background:{{ $b['bg'] ?? '#eee' }};color:{{ $b['color'] ?? '#333' }}">
                            {{ $b['label'] ?? ucfirst($status) }}
                        </span>
                        @elseif($attendanceLocked)
                        <span class="text-muted small">Belum diisi</span>
                        @else
                        <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($attendanceLocked && $status)
                        <span class="text-muted small"><i class="bi bi-lock me-1"></i>Terkunci</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endforeach
@endif

@push('scripts')
<script>
document.querySelectorAll('.confirm-schedule-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(r => r.json()).then(d => {
            if (d.success) { location.reload(); }
            else alert(d.message || 'Gagal konfirmasi');
        }).catch(() => alert('Terjadi kesalahan'));
    });
});
</script>
@endpush

@endsection
