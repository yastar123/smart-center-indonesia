@extends('layouts.app')
@section('title', $title ?? 'Segera Hadir')
@section('page-title', $title ?? 'Segera Hadir')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">

        <div class="dashboard-card text-center py-5 px-4 fade-up" style="border-top:4px solid #c84ddf">
            <div style="width:90px;height:90px;border-radius:28px;background:linear-gradient(135deg,#68117e,#c84ddf);display:flex;align-items:center;justify-content:center;margin:0 auto 24px;box-shadow:0 16px 40px rgba(200,77,223,.3)">
                <i class="bi {{ $icon ?? 'bi-tools' }} text-white" style="font-size:40px"></i>
            </div>

            <div class="badge mb-3" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);font-size:12px;padding:6px 14px;border-radius:20px;border:1px solid var(--soft-primary-border)">
                <i class="bi bi-hammer me-1"></i>Dalam Pengembangan
            </div>

            <h4 class="fw-bold mb-3" style="letter-spacing:-.02em">{{ $title ?? 'Fitur Segera Hadir' }}</h4>
            <p class="text-muted mb-4" style="max-width:400px;margin:0 auto;font-size:14px;line-height:1.6">
                {{ $desc ?? 'Fitur ini sedang dalam pengembangan aktif dan akan segera tersedia.' }}
            </p>

            <div class="row g-3 mb-5" style="max-width:380px;margin:0 auto">
                <div class="col-4 col-sm-4">
                    <div style="background:var(--input-bg);border-radius:12px;padding:16px 8px;border:1px solid var(--card-border)">
                        <div style="font-size:22px;margin-bottom:4px">🎨</div>
                        <div style="font-size:11px;color:var(--text-muted);font-weight:600">UI/UX<br>Design</div>
                        <div class="progress mt-2" style="height:4px;border-radius:4px;background:var(--soft-primary-bg)">
                            <div class="progress-bar" style="width:80%;background:#c84ddf;border-radius:4px"></div>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-sm-4">
                    <div style="background:var(--input-bg);border-radius:12px;padding:16px 8px;border:1px solid var(--card-border)">
                        <div style="font-size:22px;margin-bottom:4px">⚙️</div>
                        <div style="font-size:11px;color:var(--text-muted);font-weight:600">Backend<br>Logic</div>
                        <div class="progress mt-2" style="height:4px;border-radius:4px;background:var(--soft-primary-bg)">
                            <div class="progress-bar" style="width:45%;background:#f6af23;border-radius:4px"></div>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-sm-4">
                    <div style="background:var(--input-bg);border-radius:12px;padding:16px 8px;border:1px solid var(--card-border)">
                        <div style="font-size:22px;margin-bottom:4px">🧪</div>
                        <div style="font-size:11px;color:var(--text-muted);font-weight:600">Testing<br>& QA</div>
                        <div class="progress mt-2" style="height:4px;border-radius:4px;background:var(--soft-primary-bg)">
                            <div class="progress-bar" style="width:20%;background:#10b981;border-radius:4px"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-center flex-wrap">
                <a href="{{ route('siswa.dashboard') }}" class="btn btn-primary fw-semibold px-4" style="border-radius:10px">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
                <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary fw-semibold px-4" style="border-radius:10px">
                    <i class="bi bi-person me-2"></i>Edit Profil
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
