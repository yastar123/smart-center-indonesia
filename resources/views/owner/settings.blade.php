@extends('layouts.app')
@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan')

@section('content')

{{-- HEADER --}}
<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632,#461256,#68117e);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.04);border-radius:50%"></div>
    <div class="d-flex align-items-center gap-3" style="position:relative">
        <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
            <i class="bi bi-gear-fill"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-0" style="color:white">Pengaturan Sistem</h5>
            <span style="font-size:12px;opacity:.75">Konfigurasi global platform Smart Center Indonesia</span>
        </div>
        <div class="ms-auto">
            <span class="badge" style="background:rgba(246,175,35,.2);color:#f6af23;border:1px solid rgba(246,175,35,.3);padding:6px 14px;border-radius:20px;font-size:11px">
                <i class="bi bi-hammer me-1"></i>Dalam Pengembangan
            </span>
        </div>
    </div>
</div>

{{-- COMING SOON NOTICE --}}
<div class="fade-up mb-4">
    <div class="d-flex align-items-start gap-3 p-3 rounded-3" style="background:rgba(246,175,35,.08);border:1.5px solid rgba(246,175,35,.25)">
        <i class="bi bi-info-circle-fill text-warning mt-1" style="font-size:18px;flex-shrink:0"></i>
        <div>
            <div class="fw-semibold" style="font-size:13px;color:var(--text-primary)">Fitur Pengaturan Sedang Dikembangkan</div>
            <p class="mb-0 text-muted" style="font-size:12px;line-height:1.6;margin-top:2px">
                Halaman ini menampilkan pratinjau konfigurasi sistem. Kemampuan menyimpan perubahan akan tersedia pada versi berikutnya.
                Untuk sekarang, gunakan <a href="{{ route('owner.branches.index') }}" class="fw-semibold text-decoration-none" style="color:#c84ddf">Kelola Cabang</a> dan
                <a href="{{ route('profile.edit') }}" class="fw-semibold text-decoration-none" style="color:#c84ddf">Profil Akun</a> untuk pengaturan yang tersedia.
            </p>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- GENERAL SETTINGS --}}
    <div class="col-lg-8">
        <div class="dashboard-card fade-up mb-4" style="opacity:.9">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-building me-2 text-primary"></i>Informasi Lembaga</h6>
                <span class="badge" style="background:#fdf4ff;color:#68117e;border:1px solid #e8b4f5;font-size:10.5px;padding:4px 10px;border-radius:12px">
                    <i class="bi bi-lock me-1"></i>Hanya Baca
                </span>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Lembaga</label>
                    <input type="text" class="form-control" value="Smart Center Indonesia" readonly
                           style="background:var(--input-bg);opacity:.75;cursor:not-allowed">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Singkatan</label>
                    <input type="text" class="form-control" value="SCI" readonly
                           style="background:var(--input-bg);opacity:.75;cursor:not-allowed">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email Utama</label>
                    <input type="email" class="form-control" placeholder="admin@smartcenter.id" disabled
                           style="background:var(--input-bg);opacity:.6;cursor:not-allowed">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nomor Telepon</label>
                    <input type="text" class="form-control" placeholder="08xxx" disabled
                           style="background:var(--input-bg);opacity:.6;cursor:not-allowed">
                </div>
                <div class="col-12">
                    <label class="form-label">Alamat Pusat</label>
                    <textarea class="form-control" rows="2" placeholder="Alamat kantor pusat..." disabled
                              style="background:var(--input-bg);opacity:.6;cursor:not-allowed;resize:none"></textarea>
                </div>
            </div>
            <div class="mt-4 d-flex justify-content-end">
                <button class="btn btn-primary px-4 fw-semibold" style="border-radius:10px"
                        onclick="Swal.fire({icon:'info',title:'Segera Hadir',text:'Fitur simpan pengaturan akan tersedia di versi berikutnya.',confirmButtonColor:'#c84ddf'})">
                    <i class="bi bi-save me-2"></i>Simpan Perubahan
                    <span class="badge ms-2" style="background:rgba(255,255,255,.25);font-size:9px;padding:2px 6px;border-radius:8px">Soon</span>
                </button>
            </div>
        </div>

        {{-- NOTIFIKASI SECTION --}}
        <div class="dashboard-card fade-up" style="opacity:.85" id="section-notif">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-bell me-2 text-warning"></i>Pengaturan Notifikasi</h6>
                <span class="badge" style="background:#fdf4ff;color:#68117e;border:1px solid #e8b4f5;font-size:10.5px;padding:4px 10px;border-radius:12px">
                    <i class="bi bi-lock me-1"></i>Hanya Baca
                </span>
            </div>
            @foreach([
                ['label'=>'Email Notifikasi Pembayaran','checked'=>true,'desc'=>'Kirim email saat ada pembayaran masuk'],
                ['label'=>'Notifikasi Absensi Guru','checked'=>true,'desc'=>'Alert saat guru tidak absen tepat waktu'],
                ['label'=>'Reminder Invoice Jatuh Tempo','checked'=>true,'desc'=>'Pengingat 3 hari sebelum jatuh tempo'],
                ['label'=>'Laporan Harian Otomatis','checked'=>false,'desc'=>'Kirim ringkasan harian ke email owner'],
            ] as $notif)
            <div class="d-flex align-items-start justify-content-between gap-3 py-3" style="border-bottom:1px solid var(--card-border)">
                <div>
                    <div class="fw-semibold" style="font-size:13px">{{ $notif['label'] }}</div>
                    <div class="text-muted" style="font-size:12px">{{ $notif['desc'] }}</div>
                </div>
                <div class="form-check form-switch" style="margin:0;padding-left:2.5em">
                    <input class="form-check-input" type="checkbox" {{ $notif['checked'] ? 'checked' : '' }} disabled
                           style="cursor:not-allowed;opacity:.6;width:2.5em;height:1.25em">
                </div>
            </div>
            @endforeach
            <div class="mt-3 d-flex justify-content-end">
                <button class="btn btn-outline-secondary px-4 fw-semibold" style="border-radius:10px"
                        onclick="Swal.fire({icon:'info',title:'Segera Hadir',text:'Pengaturan notifikasi akan tersedia di versi berikutnya.',confirmButtonColor:'#c84ddf'})">
                    <i class="bi bi-save me-2"></i>Simpan Notifikasi
                    <span class="badge ms-2" style="background:rgba(104,17,126,.15);color:#68117e;font-size:9px;padding:2px 6px;border-radius:8px">Soon</span>
                </button>
            </div>
        </div>
    </div>

    {{-- SIDE PANEL --}}
    <div class="col-lg-4">
        {{-- SYSTEM INFO --}}
        <div class="dashboard-card fade-up mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-cpu me-2" style="color:#68117e"></i>Info Sistem</h6>
            @php
                $sysInfo = [
                    ['label'=>'Laravel','value'=>app()->version(),'icon'=>'bi-code-slash','color'=>'#ef4444'],
                    ['label'=>'PHP','value'=>PHP_VERSION,'icon'=>'bi-filetype-php','color'=>'#6366f1'],
                    ['label'=>'Environment','value'=>ucfirst(app()->environment()),'icon'=>'bi-server','color'=>'#10b981'],
                    ['label'=>'Debug Mode','value'=>config('app.debug') ? 'On':'Off','icon'=>'bi-bug','color'=>config('app.debug') ? '#f6af23' : '#10b981'],
                    ['label'=>'Timezone','value'=>config('app.timezone'),'icon'=>'bi-clock','color'=>'#0284c7'],
                ];
            @endphp
            @foreach($sysInfo as $info)
            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid var(--card-border)">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi {{ $info['icon'] }}" style="color:{{ $info['color'] }};font-size:13px;width:16px;text-align:center"></i>
                    <span style="font-size:12px;color:var(--text-muted)">{{ $info['label'] }}</span>
                </div>
                <span class="badge" style="background:var(--input-bg);color:var(--text-primary);border:1px solid var(--card-border);font-size:11px;font-weight:600;font-family:monospace">
                    {{ $info['value'] }}
                </span>
            </div>
            @endforeach
        </div>

        {{-- QUICK ACTIONS --}}
        <div class="dashboard-card fade-up mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-lightning me-2 text-warning"></i>Aksi Cepat</h6>
            <div class="d-grid gap-2">
                <a href="{{ route('owner.branches.index') }}" class="btn btn-outline-primary text-start" style="border-radius:10px;font-size:13px">
                    <i class="bi bi-building me-2"></i>Kelola Cabang
                </a>
                <a href="{{ route('owner.activity-log') }}" class="btn btn-outline-secondary text-start" style="border-radius:10px;font-size:13px">
                    <i class="bi bi-journal-text me-2"></i>Log Aktivitas
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-success text-start" style="border-radius:10px;font-size:13px">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard Owner
                </a>
                <a href="{{ route('profile.edit') }}" class="btn text-start" style="border-radius:10px;font-size:13px;background:linear-gradient(135deg,#68117e,#c84ddf);color:white">
                    <i class="bi bi-person-circle me-2"></i>Edit Profil Saya
                </a>
            </div>
        </div>

        {{-- STORAGE INFO --}}
        <div class="dashboard-card fade-up">
            <h6 class="fw-bold mb-3"><i class="bi bi-hdd me-2" style="color:#0284c7"></i>Storage</h6>
            @php
                $diskTotal = disk_total_space('/') / 1073741824;
                $diskFree  = disk_free_space('/') / 1073741824;
                $diskUsed  = $diskTotal - $diskFree;
                $diskPct   = $diskTotal > 0 ? round($diskUsed / $diskTotal * 100) : 0;
            @endphp
            <div class="mb-2 d-flex justify-content-between" style="font-size:12px">
                <span class="text-muted">Terpakai</span>
                <span class="fw-semibold">{{ number_format($diskUsed,1) }} GB / {{ number_format($diskTotal,1) }} GB</span>
            </div>
            <div class="progress" style="height:8px;border-radius:6px">
                <div class="progress-bar" role="progressbar"
                     style="width:{{ $diskPct }}%;background:{{ $diskPct > 80 ? 'linear-gradient(90deg,#dc2626,#ef4444)' : 'linear-gradient(90deg,#68117e,#c84ddf)' }};border-radius:6px"
                     aria-valuenow="{{ $diskPct }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="text-muted mt-1" style="font-size:11px">{{ $diskPct }}% terpakai · {{ number_format($diskFree,1) }} GB tersedia</div>
        </div>
    </div>
</div>

@endsection
