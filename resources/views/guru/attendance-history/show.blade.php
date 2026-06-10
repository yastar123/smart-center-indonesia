@extends('layouts.app')
@section('title','Riwayat — '.$course->nama)
@section('page-title','Detail Riwayat Absensi')

@section('content')

<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('guru.attendance.history') }}" class="btn btn-sm" style="background:rgba(255,255,255,.15);color:white;border:none">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h5 class="fw-bold mb-0" style="color:white">{{ $course->nama }}</h5>
            <span style="font-size:12px;opacity:.75">Riwayat absensi per pertemuan</span>
        </div>
    </div>
</div>

@foreach($classes as $class)
<div class="dashboard-card mb-4 fade-up">
    <h6 class="fw-bold mb-3">{{ $class->nama_kelas }}</h6>

    @if($class->jadwal->isEmpty())
    <p class="text-muted mb-0">Belum ada pertemuan.</p>
    @else
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="thead-modern">
                <tr>
                    <th>Pertemuan</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Hadir</th>
                    <th>Izin</th>
                    <th>Sakit</th>
                    <th>Alpa</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($class->jadwal as $j)
                @php $stat = $attendanceStats[$j->id] ?? null; @endphp
                <tr>
                    <td class="fw-semibold">#{{ $j->pertemuan_ke }}</td>
                    <td>{{ $j->tanggal->locale('id')->isoFormat('D MMM Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</td>
                    <td><span class="text-success fw-semibold">{{ $stat->hadir ?? 0 }}</span></td>
                    <td>{{ $stat->izin ?? 0 }}</td>
                    <td>{{ $stat->sakit ?? 0 }}</td>
                    <td>{{ $stat->alpa ?? 0 }}</td>
                    <td class="fw-bold">{{ $stat->total ?? 0 }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endforeach

@endsection
