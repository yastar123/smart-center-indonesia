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
            <span style="font-size:12px;opacity:.75">Riwayat absensi per siswa per pertemuan</span>
        </div>
    </div>
</div>

@php
if (!function_exists('attendanceIcon')) {
    function attendanceIcon($status) {
        return match($status) {
            'hadir'                => '<span title="Hadir" style="color:#10b981;font-size:16px">✅</span>',
            'menunggu_konfirmasi'  => '<span title="Menunggu Konfirmasi Siswa" style="color:#f59e0b;font-size:14px">⏳</span>',
            'tidak_valid'          => '<span title="Tidak Valid" style="color:#ef4444;font-size:14px">⚠️</span>',
            'izin'                 => '<span title="Izin" style="color:#3b82f6;font-size:14px">📋</span>',
            'sakit'                => '<span title="Sakit" style="color:#8b5cf6;font-size:14px">🏥</span>',
            default                => '<span title="Tidak Hadir" style="color:#cbd5e1;font-size:16px">❌</span>',
        };
    }
}
@endphp

@foreach($classes as $class)
@php
    $schedules = $class->jadwal;
    $students  = $classStudents[$class->id] ?? collect();
@endphp

<div class="dashboard-card mb-4 fade-up">
    <h6 class="fw-bold mb-1">{{ $class->nama_kelas }}</h6>
    <p class="text-muted mb-3" style="font-size:12px">{{ $schedules->count() }} pertemuan · {{ $students->count() }} siswa</p>

    @if($schedules->isEmpty())
        <p class="text-muted mb-0">Belum ada pertemuan.</p>
    @elseif($students->isEmpty())
        <p class="text-muted mb-0">Belum ada siswa di kelas ini.</p>
    @else
    <div class="table-responsive" style="overflow-x:auto">
        <table class="table table-modern align-middle mb-0" style="min-width:600px;font-size:13px">
            <thead>
                <tr style="background:var(--input-bg)">
                    <th style="min-width:160px;position:sticky;left:0;background:var(--input-bg);z-index:2;color:var(--text-muted);font-size:11px;text-transform:uppercase;letter-spacing:.04em">Nama Siswa</th>
                    @foreach($schedules as $j)
                    <th class="text-center" style="min-width:80px;color:var(--text-muted);font-size:11px;font-weight:600">
                        <div>P{{ $j->pertemuan_ke }}</div>
                        <div style="font-weight:400;font-size:10px;opacity:.7">{{ \Carbon\Carbon::parse($j->tanggal)->format('d/m') }}</div>
                        <div style="font-weight:400;font-size:10px;opacity:.7">{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}</div>
                    </th>
                    @endforeach
                    <th class="text-center" style="min-width:90px;color:var(--text-muted);font-size:11px;text-transform:uppercase;letter-spacing:.04em">Total Hadir</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                @php
                    $totalHadir = 0;
                @endphp
                <tr>
                    <td style="position:sticky;left:0;background:var(--card-bg);z-index:1;font-weight:500">
                        {{ $student->name }}
                        @if($student->nis)
                            <div class="text-muted" style="font-size:10px">{{ $student->nis }}</div>
                        @endif
                    </td>
                    @foreach($schedules as $j)
                    @php
                        $rec    = ($attendanceRecords[$j->id] ?? collect())[$student->id] ?? null;
                        $status = $rec ? $rec->status : null;
                        if ($status === 'hadir') $totalHadir++;
                    @endphp
                    <td class="text-center">{!! attendanceIcon($status) !!}</td>
                    @endforeach
                    <td class="text-center fw-bold" style="color:var(--text-primary)">
                        {{ $totalHadir }} <span class="text-muted fw-normal" style="font-size:11px">/ {{ $schedules->count() }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Legend --}}
    <div class="d-flex flex-wrap gap-3 mt-3" style="font-size:12px">
        <span>✅ Hadir</span>
        <span>⏳ Menunggu konfirmasi siswa</span>
        <span>❌ Tidak hadir</span>
        <span>📋 Izin</span>
        <span>🏥 Sakit</span>
        <span>⚠️ Tidak valid</span>
    </div>
    @endif
</div>
@endforeach

@endsection
