<?php $__env->startSection('title', 'Profil Saya'); ?>
<?php $__env->startSection('page-title', 'Profil Saya'); ?>

<?php $__env->startSection('content'); ?>

<?php $user = auth()->user(); ?>


<?php if(session('status') === 'profile-updated'): ?>
<div class="alert border-0 rounded-3 mb-4 d-flex align-items-center gap-2 fade show"
     style="background:var(--soft-success-bg);color:var(--soft-success-text);border-left:4px solid #10b981!important"
     role="alert" id="profileAlert">
    <i class="bi bi-check-circle-fill fs-5"></i>
    <span class="fw-semibold">Profil berhasil diperbarui!</span>
    <button class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if(session('status') === 'password-updated'): ?>
<div class="alert border-0 rounded-3 mb-4 d-flex align-items-center gap-2 fade show"
     style="background:var(--soft-success-bg);color:var(--soft-success-text);border-left:4px solid #10b981!important"
     role="alert">
    <i class="bi bi-shield-check-fill fs-5"></i>
    <span class="fw-semibold">Password berhasil diperbarui!</span>
    <button class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row g-4">

    
    <div class="col-lg-3">

        <div class="dashboard-card text-center fade-up">
            <div class="position-relative d-inline-block mb-3">
                <img src="<?php echo e($user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=68117e&color=fff&size=160'); ?>"
                     class="rounded-circle"
                     width="100" height="100"
                     style="object-fit:cover;border:4px solid #c84ddf;box-shadow:0 8px 24px rgba(200,77,223,.3)"
                     id="avatarPreview">
                <div style="position:absolute;bottom:4px;right:4px;width:30px;height:30px;background:#c84ddf;border-radius:50%;display:flex;align-items:center;justify-content:center;border:2.5px solid white;cursor:pointer"
                     onclick="document.getElementById('avatarFile').click()">
                    <i class="bi bi-camera-fill text-white" style="font-size:12px"></i>
                </div>
                <input type="file" id="avatarFile" class="d-none" accept="image/*">
            </div>

            <h6 class="fw-bold mb-1"><?php echo e($user->name); ?></h6>
            <span class="badge" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);border:1px solid var(--soft-primary-border);font-size:12px;padding:5px 14px;border-radius:20px">
                <?php echo e(ucfirst($user->getRoleNames()->first() ?? 'User')); ?>

            </span>

            <hr style="border-color:var(--card-border);margin:16px 0">

            <div class="text-start" style="font-size:13px">
                <div class="d-flex align-items-center gap-2 mb-2 text-muted">
                    <i class="bi bi-envelope text-primary"></i>
                    <span><?php echo e($user->email); ?></span>
                </div>
                <?php if($user->phone): ?>
                <div class="d-flex align-items-center gap-2 mb-2 text-muted">
                    <i class="bi bi-telephone text-success"></i>
                    <span><?php echo e($user->phone); ?></span>
                </div>
                <?php endif; ?>
                <?php if($user->branch): ?>
                <div class="d-flex align-items-center gap-2 mb-2 text-muted">
                    <i class="bi bi-building text-warning"></i>
                    <span><?php echo e($user->branch->name); ?></span>
                </div>
                <?php endif; ?>
                <div class="d-flex align-items-center gap-2 text-muted">
                    <i class="bi bi-calendar3" style="color:#68117e"></i>
                    <span>Bergabung <?php echo e($user->created_at->format('M Y')); ?></span>
                </div>
            </div>
        </div>

        
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

    
    <div class="col-lg-9">

        
        <div class="dashboard-card mb-4 fade-up" id="section-profile">
            <div class="d-flex align-items-center gap-3 mb-4 pb-3" style="border-bottom:1px solid var(--card-border)">
                <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,#68117e,#c84ddf);display:flex;align-items:center;justify-content:center">
                    <i class="bi bi-person-fill text-white" style="font-size:18px"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">Informasi Profil</h6>
                    <p class="text-muted mb-0" style="font-size:12px">Perbarui nama dan email akun Anda</p>
                </div>
            </div>

            <?php if($errors->any() && !$errors->updatePassword->any() && !$errors->userDeletion->any()): ?>
            <div class="alert alert-danger rounded-3 border-0 mb-3" style="background:var(--soft-danger-bg);color:var(--soft-danger-text)">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo e($errors->first()); ?>

            </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('profile.update')); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $user->name)); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="<?php echo e(old('email', $user->email)); ?>" required>
                        <?php if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail()): ?>
                        <div class="mt-2 p-2 rounded-2" style="background:var(--soft-warning-bg);border:1px solid var(--soft-warning-border)">
                            <small class="text-warning">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>Email belum diverifikasi.
                                <button form="send-verification" class="btn btn-link btn-sm p-0 ms-1 text-warning fw-semibold text-decoration-underline">
                                    Kirim ulang email verifikasi
                                </button>
                            </small>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <form id="send-verification" method="POST" action="<?php echo e(route('verification.send')); ?>"><?php echo csrf_field(); ?></form>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary px-4 fw-semibold">
                        <i class="bi bi-check-lg me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        
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

            <?php if($errors->updatePassword->any()): ?>
            <div class="alert alert-danger rounded-3 border-0 mb-3" style="background:var(--soft-danger-bg);color:var(--soft-danger-text)">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo e($errors->updatePassword->first()); ?>

            </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('password.update')); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

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
                    <div style="width:64px;height:64px;border-radius:50%;background:var(--soft-danger-bg);display:flex;align-items:center;justify-content:center;margin:0 auto 12px">
                        <i class="bi bi-trash-fill text-danger" style="font-size:28px"></i>
                    </div>
                    <p class="text-muted" style="font-size:14px">
                        Apakah Anda yakin ingin menghapus akun? Semua data akan hilang selamanya.
                        Masukkan password untuk konfirmasi.
                    </p>
                </div>
                <form method="POST" action="<?php echo e(route('profile.destroy')); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>

                    <?php if($errors->userDeletion->any()): ?>
                    <div class="alert alert-danger rounded-3 border-0 mb-3" style="background:var(--soft-danger-bg);color:var(--soft-danger-text);font-size:13px">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo e($errors->userDeletion->first()); ?>

                    </div>
                    <?php endif; ?>

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

<?php if($errors->userDeletion->isNotEmpty()): ?>
<script>document.addEventListener('DOMContentLoaded', () => new bootstrap.Modal('#deleteModal').show());</script>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
            { pct:'50%', color:'#f6af23', text:'Cukup' },
            { pct:'75%', color:'#c84ddf', text:'Kuat' },
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

    fetch('<?php echo e(route("profile.avatar")); ?>', { method: 'POST', body: fd })
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(res => {
            if (res.success) {
                // Update sidebar avatar too
                const sidebarAvatar = document.getElementById('sidebarAvatar');
                if (sidebarAvatar) sidebarAvatar.src = res.avatar_url;
                const topbarAvatar = document.getElementById('topbarAvatar');
                if (topbarAvatar) topbarAvatar.src = res.avatar_url;
                // Show success toast
                showToast(res.message || 'Foto profil berhasil diperbarui!', 'success');
            }
        })
        .catch(() => {
            showToast('Tidak dapat mengunggah foto. Coba lagi.', 'error');
        });
});

// Auto-dismiss profile alert
setTimeout(() => {
    const al = document.getElementById('profileAlert');
    if (al) { const bsAlert = bootstrap.Alert.getOrCreateInstance(al); bsAlert.close(); }
}, 4000);
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/profile/edit.blade.php ENDPATH**/ ?>