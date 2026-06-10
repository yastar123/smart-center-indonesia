@extends('layouts.app')
@section('title','Absensi Saya')
@section('page-title','Absensi')

@section('content')
<div class="dashboard-card mb-4">
    <h6 class="fw-bold">Mata Pelajaran Saya</h6>
    <div class="row mt-3">
        @forelse($courses as $c)
        <div class="col-md-6 mb-3">
            <div class="dashboard-card d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-semibold">{{ $c->nama }}</div>
                    <div class="small text-muted">{{ $c->deskripsi ?? '' }}</div>
                </div>
                <div>
                    <a href="{{ route('siswa.attendance.show', $c->id) }}" class="btn btn-primary btn-sm">Absensi</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center text-muted">Belum terdaftar mata pelajaran apapun.</div>
        @endforelse
    </div>
</div>
@endsection
