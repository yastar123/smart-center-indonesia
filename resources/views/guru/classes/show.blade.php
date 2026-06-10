@extends('layouts.app')
@section('title','Detail Kelas')
@section('page-title','Detail Kelas')

@section('content')
<div class="dashboard-card mb-4">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h5 class="fw-bold">{{ $class->nama_kelas }}</h5>
            <div class="text-muted">{{ $class->mataPelajaran->nama ?? '-' }} · {{ $class->tahunAkademik->nama ?? '-' }}</div>
        </div>
        <div class="text-end">
            <a href="{{ route('guru.classes.attendance', $class->id) }}" class="btn btn-primary">Absensi</a>
        </div>
    </div>

    <div class="mb-3">
        <strong>Detail:</strong>
        <ul>
            <li>Cabang: {{ $class->cabang->name ?? 'Pusat' }}</li>
            <li>Kapasitas: {{ $class->kapasitas ?? '–' }}</li>
            <li>Jumlah Pertemuan: {{ $class->jumlah_pertemuan ?? '–' }}</li>
            <li>Jenis: {{ $class->jenis ?? '–' }}</li>
        </ul>
    </div>

    <h6 class="fw-semibold">Daftar Siswa</h6>
    @if($class->siswa->isEmpty())
        <div class="text-muted">Belum ada siswa terdaftar di kelas ini.</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead><tr><th>Nama</th><th>NIS</th><th>Cabang</th><th>Status</th></tr></thead>
                <tbody>
                @foreach($class->siswa as $s)
                    <tr>
                        <td class="fw-semibold">{{ $s->name }}</td>
                        <td><code>{{ $s->nis }}</code></td>
                        <td>{{ $s->branch->name ?? 'Pusat' }}</td>
                        <td>{{ ucfirst($s->status) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection
