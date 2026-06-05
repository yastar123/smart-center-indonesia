@extends('layouts.app')
@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')

@php $user = auth()->user(); @endphp

{{-- SUCCESS FLASH --}}
@if(session('status') === 'profile-updated')
<div class="alert border-0 rounded-3 mb-4 d-flex align-items-center gap-2 fade show"
     style="background:#f0fdf4;color:#15803d;border-left:4px solid #10b981!important"
     role="alert" id="profileAlert">
    <i class="bi bi-check-circle-fill fs-5"></i>
    <span class="fw-semibold">Profil berhasil diperbarui!</span>
    <button class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('status') === 'password-updated')
<div class="alert border-0 rounded-3 mb-4 d-flex align-items-center gap-2 fade show"
     style="background:#f0fdf4;color:#15803d;border-left:4px solid #10b981!important"
     role="alert">
    <i class="bi bi-shield-check-fill fs-5"></i>
    <span class="fw-semibold">Password berhasil diperbarui!</span>
    <button class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-4">

    {{-- ===== SIDEBAR: AVATAR & INFO ===== --}}
    <div class="col-lg-3">

        <div class="dashboard-card text-center fade-up">
            <div class="position-relative d-inline-block mb-3">
                <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=2563eb&color=fff&size=160' }}"
                     class="rounded-circle"
                     width="100" height="100"
                     style="object-fit:cover;border:4px solid #3b82f6;box-shadow:0 8px 24px rgba(59,130,246,.3)"
                     id="avatarPreview">
                <div style="position:absolute;bottom:4px;right:4px;width:30px;height:30px;background:#3b82f6;border-radius:50%;display:flex;align-items:center;justify-content:center;border:2.5px solid white;cursor:pointer"
                     onclick="document.getElementById('avatarFile').click()">
                    <i class="bi bi-camera-fill text-white" style="font-size:12px"></i>
                </div>
                <input type="file" id="avatarFile" class="d-none" accept="image/*">
            </div>

            <h6 class="fw-bold mb-1">{{ $user->name }}</h6>
            <span class="badge" style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;font-size:12px;padding:5px 14px;border-radius:20px">
                {{ ucfirst($user->getRoleNames()->first() ?? 'User') }}
            </span>

            <hr style="border-color:var(--card-border);margin:16px 0">

            <div class="text-start" style="font-size:13px">
                <div class="d-flex align-items-center gap-2 mb-2 text-muted">
                    <i class="bi bi-envelope text-primary"></i>
                    <span>{{ $user->email }}</span>
                </div>
                @if($user->phone)
                <div class="d-flex align-items-center gap-2 mb-2 text-muted">
                    <i class="bi bi-telephone text-success"></i>
                    <span>{{ $user->phone }}</span>
                </div>
                @endif
                @if($user->branch)
                <div class="d-flex align-items-center gap-2 mb-2 text-muted">
                    <i class="bi bi-building text-warning"></i>
                    <span>{{ $user->branch->name }}</span>
                </div>
                @endif
                <div class="d-flex align-items-center gap-2 text-muted">
                    <i class="bi bi-calendar3 text-info"></i>
                    <span>Bergabung {{ $user->created_at->format('M Y') }}</span>
                </div>
            </div>
        </div>

        {{-- QUICK LINKS --}}
        <div class="dashboard-card mt-3 fade-up" style="animation-delay:.05s">
            <div class="small fw-bold text-muted mb-2" style="text-transform:uppercase;letter-spacing:.06em;font-size:10.5px">Navigasi Cepat</div>
            <div class="d-grid gap-1">
                <a href="#section-profile" class="btn btn-sm text-start" style="background:var(--input-bg);color:var(--text-primary);border-radius:8px">
                    <i class="bi bi-person me-2 text-primary"></i>Edit Profil
                </a>
                <a href="#section-password" class="btn btn-sm text-start" style="background:var(--input-bg);color:var(--text-primary);border-radius:8px">
                    <i class="bi bi-shield-lock me-2 text-success"></i>Ganti Password
                </a>
                <a href="#section-danger" class="btn btn-sm text-start" style="background:var(--input-bg);color:var(--text-primary);border-radius:8px">
                    <i class="bi bi-trash me-2 text-danger"></i>Hapus Akun
                </a>
            </div>
        </div>

    </div>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="col-lg-9">

        {{-- SECTION 1: UPDATE PROFILE --}}
        <div class="dashboard-card mb-4 fade-up" id="section-profile">
            <div class="d-flex align-items-center gap-3 mb-4 pb-3" style="border-bottom:1px solid var(--card-border)">
                <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,#2563eb,#6366f1);display:flex;align-items:center;justify-content:center">
                    <i class="bi bi-person-fill text-white" style="font-size:18px"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">Informasi Profil</h6>
                    <p class="text-muted mb-0" style="font-size:12px">Perbarui nama dan email akun Anda</p>
                </div>
            </div>

            @if($errors->any() && !$errors->updatePassword->any() && !$errors->userDeletion->any())
            <div class="alert alert-danger rounded-3 border-0 mb-3" style="background:#fef2f2;color:#dc2626">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-2 p-2 rounded-2" style="background:#fffbeb;border:1px solid #fcd34d">
                            <small class="text-warning">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>Email belum diverifikasi.
                                <button form="send-verification" class="btn btn-link btn-sm p-0 ms-1 text-warning fw-semibold text-decoration-underline">
                                    Kirim ulang email verifikasi
                                </button>
                            </small>
                        </div>
                        @endif
                    </div>
                </div>

                <form id="send-verification" method="POST" action="{{ route('verification.send') }}">@csrf</form>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary px-4 fw-semibold">
                        <i class="bi bi-check-lg me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        {{-- SECTION 2: UPDATE PASSWORD --}}
        <div class="dashboard-card mb-4 fade-up" id="section-password">
            <div class="d-flex align-items-center gap-3 mb-4 pb-3" style="border-bottom:1px solid var(--card-border)">
                <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,#059669,#10b981);display:flex;align-items:center;justify-content:center">
                    <i class="bi bi-shield-lock-fill text-white" style="font-size:18px"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">Ganti Password</h6>
                    <p class="text-muted mb-0" style="font-size:12px">Gunakan password yang panjang dan acak untuk keamanan akun</p>
                </div>
            </div>

            @if($errors->updatePassword->any())
            <div class="alert alert-danger rounded-3 border-0 mb-3" style="background:#fef2f2;color:#dc2626">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->updatePassword->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Password Saat Ini <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="current_password" id="currentPwd" class="form-control" placeholder="Password lama" autocomplete="current-password">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePwd('currentPwd','eyeCurrent')">
                                <i class="bi bi-eye" id="eyeCurrent"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Password Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="password" id="newPwd" class="form-control" placeholder="Min. 8 karakter" autocomplete="new-password">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePwd('newPwd','eyeNew')">
                                <i class="bi bi-eye" id="eyeNew"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="confirmPwd" class="form-control" placeholder="Ulangi password baru" autocomplete="new-password">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePwd('confirmPwd','eyeConfirm')">
                                <i class="bi bi-eye" id="eyeConfirm"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Password Strength --}}
                <div class="mt-3" id="strengthBar" style="display:none">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="text-muted">Kekuatan password</small>
                        <small id="strengthLabel" class="fw-semibold"></small>
                    </div>
                    <div style="height:5px;background:var(--card-border);border-radius:10px;overflow:hidden">
                        <div id="strengthFill" style="height:100%;width:0%;border-radius:10px;transition:.3s"></div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success px-4 fw-semibold">
                        <i class="bi bi-shield-check me-2"></i>Perbarui Password
                    </button>
                </div>
            </form>
        </div>

        {{-- SECTION 3: DANGER ZONE --}}
        <div class="dashboard-card fade-up" id="section-danger"
             style="border:1.5px solid #fecaca;background:var(--card-bg)">
            <div class="d-flex align-items-center gap-3 mb-4 pb-3" style="border-bottom:1px solid #fecaca">
                <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,#ef4444,#dc2626);display:flex;align-items:center;justify-content:center">
                    <i class="bi bi-exclamation-triangle-fill text-white" style="font-size:18px"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-danger">Zona Berbahaya</h6>
                    <p class="text-muted mb-0" style="font-size:12px">Tindakan ini bersifat permanen dan tidak dapat dibatalkan</p>
                </div>
            </div>

            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <div class="fw-semibold mb-1" style="font-size:14px">Hapus Akun Saya</div>
                    <p class="text-muted mb-0" style="font-size:13px">
                        Setelah dihapus, semua data dan sumber daya akun akan hilang permanen.
                    </p>
                </div>
                <button class="btn btn-danger fw-semibold" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="bi bi-trash me-2"></i>Hapus Akun
                </button>
            </div>
        </div>

    </div>

</div>

{{-- DELETE ACCOUNT MODAL --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0" style="background:linear-gradient(135deg,#ef4444,#dc2626);color:white">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Hapus Akun
                </h6>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div style="width:64px;height:64px;border-radius:50%;background:#fef2f2;display:flex;align-items:center;justify-content:center;margin:0 auto 12px">
                        <i class="bi bi-trash-fill text-danger" style="font-size:28px"></i>
                    </div>
                    <p class="text-muted" style="font-size:14px">
                        Apakah Anda yakin ingin menghapus akun? Semua data akan hilang selamanya.
                        Masukkan password untuk konfirmasi.
                    </p>
                </div>
                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('DELETE')

                    @if($errors->userDeletion->any())
                    <div class="alert alert-danger rounded-3 border-0 mb-3" style="background:#fef2f2;color:#dc2626;font-size:13px">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->userDeletion->first() }}
                    </div>
                    @endif

                    <label class="form-label small fw-semibold">Password Anda</label>
                    <div class="input-group mb-4">
                        <input type="password" name="password" id="deletePwd" class="form-control" placeholder="Masukkan password untuk konfirmasi">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePwd('deletePwd','eyeDelete')">
                            <i class="bi bi-eye" id="eyeDelete"></i>
                        </button>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-danger fw-bold">
                            <i class="bi bi-trash me-2"></i>Ya, Hapus Akun Saya
                        </button>
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if($errors->userDeletion->isNotEmpty())
<script>document.addEventListener('DOMContentLoaded', () => new bootstrap.Modal('#deleteModal').show());</script>
@endif

@endsection

@push('scripts')
<script>
function togglePwd(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}

// Password strength meter
const newPwd = document.getElementById('newPwd');
if (newPwd) {
    newPwd.addEventListener('input', function() {
        const val = this.value;
        const bar = document.getElementById('strengthBar');
        const fill = document.getElementById('strengthFill');
        const label = document.getElementById('strengthLabel');

        if (!val) { bar.style.display = 'none'; return; }
        bar.style.display = 'block';

        let strength = 0;
        if (val.length >= 8)  strength++;
        if (/[A-Z]/.test(val)) strength++;
        if (/[0-9]/.test(val)) strength++;
        if (/[^A-Za-z0-9]/.test(val)) strength++;

        const configs = [
            { pct:'25%', color:'#ef4444', text:'Lemah' },
            { pct:'50%', color:'#f59e0b', text:'Cukup' },
            { pct:'75%', color:'#3b82f6', text:'Kuat' },
            { pct:'100%', color:'#10b981', text:'Sangat Kuat' },
        ];
        const cfg = configs[strength - 1] || configs[0];
        fill.style.width = cfg.pct;
        fill.style.background = cfg.color;
        label.textContent = cfg.text;
        label.style.color = cfg.color;
    });
}

// Avatar upload with live preview
document.getElementById('avatarFile').addEventListener('change', function() {
    if (!this.files[0]) return;
    const file = this.files[0];

    // Local preview immediately
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('avatarPreview').src = e.target.result;
    };
    reader.readAsDataURL(file);

    // Upload to server
    const fd = new FormData();
    fd.append('avatar', file);
    fd.append('_token', document.querySelector('meta[name=csrf-token]').content);

    fetch('{{ route("profile.avatar") }}', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                // Update sidebar avatar too
                const sidebarAvatar = document.getElementById('sidebarAvatar');
                if (sidebarAvatar) sidebarAvatar.src = res.avatar_url;
                const topbarAvatar = document.getElementById('topbarAvatar');
                if (topbarAvatar) topbarAvatar.src = res.avatar_url;
                // Show success toast
                Swal.fire({ icon:'success', title:'Foto Diperbarui!', text:res.message, timer:2000, showConfirmButton:false, iconColor:'#10b981' });
            }
        })
        .catch(() => {
            Swal.fire({ icon:'error', title:'Gagal', text:'Tidak dapat mengunggah foto. Coba lagi.' });
        });
});

// Auto-dismiss profile alert
setTimeout(() => {
    const al = document.getElementById('profileAlert');
    if (al) { const bsAlert = bootstrap.Alert.getOrCreateInstance(al); bsAlert.close(); }
}, 4000);
</script>
@endpush
