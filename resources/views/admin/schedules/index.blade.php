@extends('layouts.app')
@section('title', 'Jadwal')
@section('page-title', 'Jadwal')

@section('content')

<div class="dashboard-card fade-in text-center py-5" style="border:2px dashed var(--card-border)">
    <i class="bi bi-calendar-week text-primary mb-3" style="font-size:4rem;opacity:.5"></i>
    <h5 class="fw-bold">Modul Jadwal</h5>
    <p class="text-muted mb-4">Fitur manajemen jadwal kelas dan mengajar sedang dalam pengembangan.</p>
    <span class="badge bg-warning text-dark px-3 py-2" style="border-radius:20px;font-size:13px">
        <i class="bi bi-hourglass-split me-1"></i>Segera Hadir
    </span>
</div>

@endsection
