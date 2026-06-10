@extends('layouts.app')
@section('title','Pertemuan '.$course->nama)
@section('page-title','Pertemuan — '.$course->nama)

@section('content')
<div class="dashboard-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0">Pertemuan — {{ $course->nama }}</h6>
        <div>
            <a href="{{ route('siswa.attendance') }}" class="btn btn-sm btn-outline-secondary">Kembali</a>
        </div>
    </div>

    @if($classes->isEmpty())
        <div class="text-muted">Tidak ditemukan kelas untuk mata pelajaran ini.</div>
    @else
        @foreach($classes as $class)
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold">{{ $class->nama_kelas }} @if($class->guru) · {{ $class->guru->name ?? '' }} @endif</div>
                        <div class="small text-muted">Cabang: {{ $class->cabang->name ?? 'Pusat' }}</div>
                    </div>
                    <div></div>
                </div>
                <div class="mt-2">
                    <table class="table table-sm">
                        <thead><tr><th>Tanggal</th><th>Waktu</th><th>Lokasi</th><th class="text-end">Aksi</th></tr></thead>
                        <tbody>
                        @foreach($class->jadwal as $j)
                        <tr>
                            <td>{{ $j->tanggal?->format('Y-m-d') }}</td>
                            <td>{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} — {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</td>
                            <td>{{ $j->lokasi ?? '-' }}</td>
                            <td class="text-end"><a href="#" class="btn btn-sm btn-outline-primary">Lihat Absensi</a></td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
