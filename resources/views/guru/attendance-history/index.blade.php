@extends('layouts.app')
@section('title','Riwayat Absensi')
@section('page-title','Riwayat Absensi')

@section('content')

<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;">
    <div class="d-flex align-items-center gap-3">
        <div style="width:52px;height:52px;border-radius:16px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:24px">
            <i class="bi bi-clock-history"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-1" style="color:white">Riwayat Absensi</h4>
            <p class="mb-0" style="opacity:.75;font-size:13px">Daftar mata pelajaran dan jumlah pertemuan</p>
        </div>
    </div>
</div>

<div class="dashboard-card fade-up">
    @if($courses->isEmpty())
    <div class="text-center py-5 text-muted">Belum ada mata pelajaran.</div>
    @else
    <div class="row g-3">
        @foreach($courses as $course)
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('guru.attendance.history.show', $course->id) }}"
               class="text-decoration-none d-block p-4 rounded-3 h-100"
               style="background:var(--input-bg);border:1.5px solid var(--card-border)">
                <div class="fw-bold mb-1" style="color:var(--text-primary)">{{ $course->nama }}</div>
                <div class="text-muted mb-3" style="font-size:12px">{{ $course->deskripsi ?? 'Mata pelajaran' }}</div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge bg-primary-soft text-primary">
                        <i class="bi bi-calendar-event me-1"></i>
                        {{ $meetingCounts[$course->id] ?? 0 }} Pertemuan
                    </span>
                    <i class="bi bi-arrow-right text-primary"></i>
                </div>
            </a>
        </div>
        @endforeach
    </div>
    @endif
</div>

@endsection
