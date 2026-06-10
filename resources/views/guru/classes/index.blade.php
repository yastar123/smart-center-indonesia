@extends('layouts.app')
@section('title','Kelas Saya')
@section('page-title','Kelas Saya')

@section('content')
<div class="dashboard-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0">Daftar Kelas yang Anda Ajar</h6>
    </div>

    @if($classes->isEmpty())
    <div class="empty-state py-5 text-center text-muted">Belum ada kelas yang diajar.</div>
    @else
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Nama Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Cabang</th>
                    <th>Siswa</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($classes as $c)
                <tr>
                    <td class="fw-semibold">{{ $c->nama_kelas }}</td>
                    <td>{{ $c->mataPelajaran->nama ?? '-' }}</td>
                    <td>{{ $c->cabang->name ?? 'Pusat' }}</td>
                    <td>{{ $c->siswa()->count() }}</td>
                    <td class="text-end">
                        <a href="{{ route('guru.classes.show', $c->id) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                        <a href="{{ route('guru.classes.attendance', $c->id) }}" class="btn btn-sm btn-primary">Absensi</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection
