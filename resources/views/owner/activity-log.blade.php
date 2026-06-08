@extends('layouts.app')
@section('title', 'Log Aktivitas')
@section('page-title', 'Log Aktivitas')

@section('content')

{{-- HEADER --}}
<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div class="row align-items-center g-3">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px">
                    <i class="bi bi-journal-text"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Log Aktivitas Sistem</h5>
                    <span style="font-size:12px;opacity:.8">Pantau semua perubahan dan aktivitas pengguna</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <span style="background:rgba(255,255,255,.15);color:rgba(255,255,255,.9);padding:6px 16px;border-radius:20px;font-size:12px;font-weight:600">
                <i class="bi bi-shield-check me-1"></i>Audit Trail Aktif
            </span>
        </div>
    </div>
</div>

{{-- LOG TABLE --}}
<div class="dashboard-card fade-up">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="fw-bold mb-0">
            <i class="bi bi-list-ul text-indigo me-2" style="color:#c84ddf"></i>Riwayat Aktivitas
            <span class="badge ms-2" style="background:#f0f0ff;color:#68117e;font-size:11px">{{ $activities->total() }} entri</span>
        </h6>
    </div>

    @forelse($activities as $act)
    @php
        $iconMap = [
            'created' => ['bi-plus-circle-fill','#f0fdf4','#16a34a'],
            'updated' => ['bi-pencil-fill','#fffbeb','#e09000'],
            'deleted' => ['bi-trash-fill','#fef2f2','#dc2626'],
        ];
        $event = $act->event ?? 'updated';
        [$icon,$ibg,$icol] = $iconMap[$event] ?? ['bi-activity','#f0f0ff','#c84ddf'];
        $causer = $act->causer;
        $changes = $act->properties['attributes'] ?? [];
        $subject = $act->subject_type ? class_basename($act->subject_type) : 'System';
    @endphp
    <div class="d-flex gap-3 mb-3 pb-3" style="border-bottom:1px solid var(--card-border)">
        <div style="width:36px;height:36px;border-radius:50%;background:{{ $ibg }};display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px">
            <i class="bi {{ $icon }}" style="font-size:14px;color:{{ $icol }}"></i>
        </div>
        <div class="flex-grow-1">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-1">
                <div>
                    <span class="fw-semibold" style="font-size:13px">
                        {{ $causer?->name ?? 'System' }}
                    </span>
                    <span style="font-size:13px;color:#6b7280"> — {{ $act->description }}</span>
                    <span class="badge ms-1" style="background:{{ $ibg }};color:{{ $icol }};font-size:10px;border-radius:6px">
                        {{ ucfirst($event) }}
                    </span>
                </div>
                <span style="font-size:11px;color:#9ca3af;white-space:nowrap">
                    <i class="bi bi-clock me-1"></i>{{ $act->created_at->diffForHumans() }}
                </span>
            </div>
            <div style="font-size:12px;color:#6b7280;margin-top:3px">
                <span style="background:#f1f5f9;padding:2px 8px;border-radius:5px">{{ $subject }}</span>
                @if($act->subject_id)
                    <span class="ms-1" style="color:#9ca3af">#{{ $act->subject_id }}</span>
                @endif
                @if($causer?->email)
                    <span class="ms-2"><i class="bi bi-envelope me-1"></i>{{ $causer->email }}</span>
                @endif
            </div>
            @if(count($changes) > 0)
            <div class="mt-2 d-flex flex-wrap gap-1">
                @foreach(array_slice($changes, 0, 5) as $field => $value)
                <span style="background:#f8fafc;border:1px solid #e2e8f0;padding:2px 8px;border-radius:5px;font-size:11px;color:#475569">
                    <span style="color:#94a3b8">{{ $field }}:</span>
                    {{ is_array($value) ? json_encode($value) : (strlen((string)$value) > 40 ? substr($value, 0, 40).'…' : $value) }}
                </span>
                @endforeach
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="text-center py-5">
        <i class="bi bi-journal-x" style="font-size:48px;color:#d1d5db;display:block;margin-bottom:12px"></i>
        <div class="fw-semibold text-muted mb-1">Belum ada log aktivitas</div>
        <div style="font-size:12px;color:#9ca3af">Semua perubahan data sistem akan tercatat di sini</div>
    </div>
    @endforelse

    @if($activities->hasPages())
    <div class="mt-4 d-flex justify-content-center">
        {{ $activities->links() }}
    </div>
    @endif
</div>

@endsection
