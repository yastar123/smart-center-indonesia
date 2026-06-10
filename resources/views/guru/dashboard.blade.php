@extends('layouts.app')
@section('title', 'Portal Guru')
@section('page-title', 'Portal Guru')

@section('content')

@php
use App\Models\Teacher;
use App\Models\Schedule;
use Carbon\Carbon;

$teacher = Teacher::where('user_id', auth()->id())->first();

$todaySchedules = $teacher
    ? Schedule::where('guru_id', $teacher->id)
        ->whereDate('tanggal', today())
        ->orderBy('jam_mulai')
        ->get()
    : collect();

$weekSchedules = $teacher
    ? Schedule::where('guru_id', $teacher->id)
        ->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()])
        ->orderBy('tanggal')->orderBy('jam_mulai')
        ->get()
    : collect();

$monthTotal = $teacher
    ? Schedule::where('guru_id', $teacher->id)
        ->whereMonth('tanggal', now()->month)
        ->whereYear('tanggal', now()->year)
        ->count()
    : 0;
@endphp

{{-- WELCOME BANNER --}}
<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div class="d-flex align-items-center gap-4">
            @if($teacher?->photo)
                <img src="{{ asset('storage/'.$teacher->photo) }}" alt="Foto"
                     style="width:64px;height:64px;border-radius:16px;object-fit:cover;border:2px solid rgba(255,255,255,.2);flex-shrink:0">
            @else
                <div style="width:64px;height:64px;border-radius:16px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:28px;flex-shrink:0">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
            @endif
            <div>
                <div style="font-size:11px;opacity:.6;margin-bottom:4px;text-transform:uppercase;letter-spacing:.08em">
                    Portal Guru · {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                </div>
                <h4 style="font-weight:800;margin-bottom:4px;color:white;letter-spacing:-.02em">
                    Selamat datang, {{ explode(' ', auth()->user()->name)[0] }}! 👋
                </h4>
                <p style="opacity:.65;margin:0;font-size:13px">
                    {{ $teacher?->name ?? auth()->user()->name }}
                    @if($teacher?->branch) · {{ $teacher->branch->name }} @endif
                    @if($teacher?->subjects) · {{ is_array($teacher->subjects) ? implode(', ', $teacher->subjects) : $teacher->subjects }} @endif
                </p>
            </div>
        </div>
        <div style="font-size:64px;opacity:.08;line-height:1;flex-shrink:0">
            <i class="bi bi-person-workspace"></i>
        </div>
    </div>
</div>

{{-- STATS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 fade-up">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Hari Ini</div>
                    <div class="stat-value text-primary">{{ $todaySchedules->count() }}</div>
                    <div class="stat-label" style="font-size:11px">sesi</div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white">
                    <i class="bi bi-calendar-day"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #68117e">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Minggu Ini</div>
                    <div class="stat-value" style="color:#c84ddf">{{ $weekSchedules->count() }}</div>
                    <div class="stat-label" style="font-size:11px">jadwal</div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white">
                    <i class="bi bi-calendar-week"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Bulan Ini</div>
                    <div class="stat-value" style="color:#059669">{{ $monthTotal }}</div>
                    <div class="stat-label" style="font-size:11px">total</div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white">
                    <i class="bi bi-calendar-month"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12">
        <div class="dashboard-card">
            <h6 class="fw-bold">Kelas Saya</h6>
            <p class="text-muted">Menu kelas dipindahkan — gunakan menu "Kelas" untuk melihat kelas yang Anda ajar.</p>
            <a href="{{ route('guru.classes.index') }}" class="btn btn-primary mt-2">Buka Kelas Saya</a>
        </div>
    </div>
</div>

@if(!$teacher)
<div class="alert alert-warning d-flex gap-3 align-items-start mt-4 fade-up" style="border-radius:14px;border:none">
    <i class="bi bi-exclamation-triangle-fill text-warning mt-1" style="font-size:18px;flex-shrink:0"></i>
    <div>
        <div class="fw-bold mb-1">Profil Guru Belum Terhubung</div>
        <div style="font-size:13px">
            Akun Anda belum terhubung ke profil guru. Minta administrator untuk menghubungkan akun ini ke data guru yang sesuai.
        </div>
    </div>
</div>
@endif

@endsection
