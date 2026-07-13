<?php $__env->startSection('title', 'Pengaturan Sistem'); ?>
<?php $__env->startSection('page-title', 'Pengaturan'); ?>

<?php $__env->startSection('content'); ?>


<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center gap-3" style="position:relative">
        <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
            <i class="bi bi-gear-fill"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-0" style="color:white">Pengaturan Sistem</h5>
            <span style="font-size:12px;opacity:.75">Konfigurasi global platform Smart Center Indonesia</span>
        </div>
    </div>
</div>

<?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible d-flex align-items-center gap-2 mb-4" style="border-radius:12px;border:none;background:rgba(16,185,129,.1);border-left:4px solid #10b981 !important">
    <i class="bi bi-check-circle-fill text-success"></i>
    <span><?php echo e(session('success')); ?></span>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row g-4">
    
    <div class="col-lg-8">
        <div class="dashboard-card fade-up mb-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-building me-2 text-primary"></i>Informasi Lembaga</h6>
            </div>
            <form action="<?php echo e(route('owner.settings.update')); ?>" method="POST">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Lembaga <span class="text-danger">*</span></label>
                        <input type="text" name="inst[name]" class="form-control <?php $__errorArgs = ['inst.name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('inst.name', $ss['inst.name'])); ?>" required maxlength="150"
                               placeholder="Smart Center Indonesia">
                        <?php $__errorArgs = ['inst.name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Singkatan <span class="text-danger">*</span></label>
                        <input type="text" name="inst[acronym]" class="form-control <?php $__errorArgs = ['inst.acronym'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('inst.acronym', $ss['inst.acronym'])); ?>" required maxlength="20"
                               placeholder="SCI">
                        <?php $__errorArgs = ['inst.acronym'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email Utama</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="inst[email]" class="form-control <?php $__errorArgs = ['inst.email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('inst.email', $ss['inst.email'])); ?>"
                                   placeholder="admin@smartcenter.id" maxlength="150">
                        </div>
                        <?php $__errorArgs = ['inst.email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nomor Telepon</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="inst[phone]" class="form-control"
                                   value="<?php echo e(old('inst.phone', $ss['inst.phone'])); ?>"
                                   placeholder="08xxx" maxlength="30">
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Alamat Pusat</label>
                        <textarea name="inst[address]" class="form-control" rows="3"
                                  placeholder="Alamat kantor pusat..." maxlength="500" style="resize:vertical"><?php echo e(old('inst.address', $ss['inst.address'])); ?></textarea>
                    </div>
                </div>
                <div class="mt-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-4 fw-semibold" style="border-radius:10px">
                        <i class="bi bi-save me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        
        <div class="dashboard-card fade-up" id="section-notif">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-bell me-2 text-warning"></i>Pengaturan Notifikasi</h6>
                <span class="badge" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);border:1px solid var(--soft-primary-border);font-size:10.5px;padding:4px 10px;border-radius:12px">
                    <i class="bi bi-tools me-1"></i>Segera Hadir
                </span>
            </div>
            <?php $__currentLoopData = [
                ['label'=>'Email Notifikasi Pembayaran','checked'=>true,'desc'=>'Kirim email saat ada pembayaran masuk'],
                ['label'=>'Notifikasi Absensi Guru','checked'=>true,'desc'=>'Alert saat guru tidak absen tepat waktu'],
                ['label'=>'Reminder Invoice Jatuh Tempo','checked'=>true,'desc'=>'Pengingat 3 hari sebelum jatuh tempo'],
                ['label'=>'Laporan Harian Otomatis','checked'=>false,'desc'=>'Kirim ringkasan harian ke email owner'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="d-flex align-items-start justify-content-between gap-3 py-3" style="border-bottom:1px solid var(--card-border)">
                <div>
                    <div class="fw-semibold" style="font-size:13px"><?php echo e($notif['label']); ?></div>
                    <div class="text-muted" style="font-size:12px"><?php echo e($notif['desc']); ?></div>
                </div>
                <div class="form-check form-switch" style="margin:0;padding-left:2.5em">
                    <input class="form-check-input" type="checkbox" <?php echo e($notif['checked'] ? 'checked' : ''); ?> disabled
                           style="cursor:not-allowed;opacity:.5;width:2.5em;height:1.25em">
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <div class="mt-3 d-flex justify-content-end">
                <button class="btn btn-outline-secondary px-4 fw-semibold" style="border-radius:10px" disabled>
                    <i class="bi bi-save me-2"></i>Simpan Notifikasi
                    <span class="badge ms-2" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);font-size:9px;padding:2px 6px;border-radius:8px">Soon</span>
                </button>
            </div>
        </div>
    </div>

    
    <div class="col-lg-4">
        
        <div class="dashboard-card fade-up mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-cpu text-primary me-2"></i>Info Sistem</h6>
            <?php
                $sysInfo = [
                    ['label'=>'Laravel','value'=>app()->version(),'icon'=>'bi-code-slash','color'=>'#ef4444'],
                    ['label'=>'PHP','value'=>PHP_VERSION,'icon'=>'bi-filetype-php','color'=>'#6366f1'],
                    ['label'=>'Environment','value'=>ucfirst(app()->environment()),'icon'=>'bi-server','color'=>'#10b981'],
                    ['label'=>'Debug Mode','value'=>config('app.debug') ? 'On':'Off','icon'=>'bi-bug','color'=>config('app.debug') ? '#f6af23' : '#10b981'],
                    ['label'=>'Timezone','value'=>config('app.timezone'),'icon'=>'bi-clock','color'=>'#0284c7'],
                ];
            ?>
            <?php $__currentLoopData = $sysInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid var(--card-border)">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi <?php echo e($info['icon']); ?>" style="color:<?php echo e($info['color']); ?>;font-size:13px;width:16px;text-align:center"></i>
                    <span style="font-size:12px;color:var(--text-muted)"><?php echo e($info['label']); ?></span>
                </div>
                <span class="badge" style="background:var(--input-bg);color:var(--text-primary);border:1px solid var(--card-border);font-size:11px;font-weight:600;font-family:monospace">
                    <?php echo e($info['value']); ?>

                </span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <div class="dashboard-card fade-up mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-lightning me-2 text-warning"></i>Aksi Cepat</h6>
            <div class="d-grid gap-2">
                <a href="<?php echo e(route('owner.branches.index')); ?>" class="btn btn-outline-primary text-start" style="border-radius:10px;font-size:13px">
                    <i class="bi bi-building me-2"></i>Kelola Cabang
                </a>
                <a href="<?php echo e(route('owner.activity-log')); ?>" class="btn btn-outline-secondary text-start" style="border-radius:10px;font-size:13px">
                    <i class="bi bi-journal-text me-2"></i>Log Aktivitas
                </a>
                <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-outline-success text-start" style="border-radius:10px;font-size:13px">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard Owner
                </a>
                <a href="<?php echo e(route('profile.edit')); ?>" class="btn text-start" style="border-radius:10px;font-size:13px;background:linear-gradient(135deg,#68117e,#c84ddf);color:white">
                    <i class="bi bi-person-circle me-2"></i>Edit Profil Saya
                </a>
            </div>
        </div>

        
        <div class="dashboard-card fade-up">
            <h6 class="fw-bold mb-3"><i class="bi bi-hdd me-2" style="color:#0284c7"></i>Storage</h6>
            <?php
                $diskTotal = disk_total_space('/') / 1073741824;
                $diskFree  = disk_free_space('/') / 1073741824;
                $diskUsed  = $diskTotal - $diskFree;
                $diskPct   = $diskTotal > 0 ? round($diskUsed / $diskTotal * 100) : 0;
            ?>
            <div class="mb-2 d-flex justify-content-between" style="font-size:12px">
                <span class="text-muted">Terpakai</span>
                <span class="fw-semibold"><?php echo e(number_format($diskUsed,1)); ?> GB / <?php echo e(number_format($diskTotal,1)); ?> GB</span>
            </div>
            <div class="progress" style="height:8px;border-radius:6px">
                <div class="progress-bar" role="progressbar"
                     style="width:<?php echo e($diskPct); ?>%;background:<?php echo e($diskPct > 80 ? 'linear-gradient(90deg,#dc2626,#ef4444)' : 'linear-gradient(90deg,#68117e,#c84ddf)'); ?>;border-radius:6px"
                     aria-valuenow="<?php echo e($diskPct); ?>" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="text-muted mt-1" style="font-size:11px"><?php echo e($diskPct); ?>% terpakai · <?php echo e(number_format($diskFree,1)); ?> GB tersedia</div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Edu Juanda Pratama\Downloads\smart-center-indonesia-1\smart-center-indonesia-1\resources\views/owner/settings.blade.php ENDPATH**/ ?>