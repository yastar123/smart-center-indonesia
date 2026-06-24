<?php $__env->startSection('title', $title); ?>
<?php $__env->startSection('page-title', $title); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-0">
    <div class="dashboard-card border-0 shadow-none rounded-0 mb-0">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <div class="text-muted small fw-semibold">Owner / Monitoring Cabang</div>
                <h5 class="fw-bold mb-0"><?php echo e($title); ?></h5>
            </div>
            <a href="<?php echo e(route('owner.branches.index')); ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>

        <form method="POST" action="<?php echo e($branch ? route('owner.branches.update', $branch) : route('owner.branches.store')); ?>">
            <?php echo csrf_field(); ?>
            <?php if($branch): ?>
                <?php echo method_field('PUT'); ?>
            <?php endif; ?>

            <div class="row g-4">
                <div class="col-12">
                    <div class="border rounded-0 p-4" style="background:var(--input-bg);border-color:var(--card-border)!important">
                        <h6 class="fw-bold mb-3 text-muted" style="font-size:12px;text-transform:uppercase;letter-spacing:.06em">
                            <i class="bi bi-building me-2 text-primary"></i>Info Cabang
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Nama Cabang <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="name" placeholder="Contoh: Cabang Jakarta" value="<?php echo e(old('name', $branch->name ?? '')); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Kota</label>
                                <input type="text" class="form-control form-control-sm" name="city" placeholder="Jakarta" value="<?php echo e(old('city', $branch->city ?? '')); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Kabupaten / Kecamatan</label>
                                <input type="text" class="form-control form-control-sm" name="regency" placeholder="Kebayoran Baru" value="<?php echo e(old('regency', $branch->regency ?? '')); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Telepon</label>
                                <input type="text" class="form-control form-control-sm" name="phone" placeholder="021-xxxxxxxx" value="<?php echo e(old('phone', $branch->phone ?? '')); ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Alamat</label>
                                <input type="text" class="form-control form-control-sm" name="address" placeholder="Alamat lengkap cabang" value="<?php echo e(old('address', $branch->address ?? '')); ?>">
                            </div>
                            <?php if($branch): ?>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Status</label>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="active" <?php echo e(old('status', $branch->status) == 'active' ? 'selected' : ''); ?>>Aktif</option>
                                    <option value="inactive" <?php echo e(old('status', $branch->status) == 'inactive' ? 'selected' : ''); ?>>Nonaktif</option>
                                </select>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="border rounded-0 p-4" style="background:var(--input-bg);border-color:var(--card-border)!important">
                        <h6 class="fw-bold mb-3 text-muted" style="font-size:12px;text-transform:uppercase;letter-spacing:.06em">
                            <i class="bi bi-person-badge me-2 text-success"></i>Akun Login Cabang
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Nama Admin</label>
                                <input type="text" class="form-control form-control-sm" name="admin_name" placeholder="Nama admin cabang" value="<?php echo e(old('admin_name', optional(optional($branch)->admin)->name ?? '')); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Username (opsional)</label>
                                <input type="text" class="form-control form-control-sm" name="admin_username" placeholder="admin.jakarta" value="<?php echo e(old('admin_username', optional(optional($branch)->admin)->username ?? '')); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Email</label>
                                <input type="email" class="form-control form-control-sm" name="email" placeholder="admin@cabang.com" value="<?php echo e(old('email', optional(optional($branch)->admin)->email ?? optional($branch)->email ?? '')); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Password <?php echo e($branch ? '(kosongkan jika tidak diubah)' : ''); ?></label>
                                <input type="password" class="form-control form-control-sm" name="password" placeholder="<?php echo e($branch ? 'Kosongkan untuk tidak merubah' : 'Min. 8 karakter'); ?>" <?php echo e($branch ? '' : 'required minlength=8'); ?>>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="border rounded-0 p-4" style="background:var(--input-bg);border-color:var(--card-border)!important">
                        <h6 class="fw-bold mb-3 text-muted" style="font-size:12px;text-transform:uppercase;letter-spacing:.06em">
                            <i class="bi bi-toggles me-2 text-warning"></i>Fitur Akses
                        </h6>
                        <div class="row g-2">
                            <?php
                                $branchAllowed = optional($branch)->allowed_pages ?? [];
                                if (! is_array($branchAllowed) || empty($branchAllowed)) {
                                    $branchAllowed = [];
                                    if (!empty($branch) && $branch->can_students) $branchAllowed[] = 'student';
                                    if (!empty($branch) && $branch->can_teachers) $branchAllowed[] = 'teacher';
                                    if (!empty($branch) && $branch->can_schedules) $branchAllowed[] = 'schedule';
                                    if (!empty($branch) && $branch->can_payments) $branchAllowed[] = 'payment';
                                    if (!empty($branch) && $branch->can_tryouts) $branchAllowed[] = 'tryout';
                                }
                            ?>

                            <?php if(!empty($menuStructure) && count($menuStructure)): ?>
                                <?php $__currentLoopData = $menuStructure; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-12 mb-1"><strong class="small text-muted"><?php echo e($section['section']); ?></strong></div>
                                    <?php $__currentLoopData = $section['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $checked = in_array($item['key'], (array)$branchAllowed); ?>
                                        <div class="col-md-6">
                                            <div class="form-check p-3 rounded-0 d-flex align-items-center justify-content-between" style="background:var(--surface);border:1px solid var(--card-border);">
                                                <div class="d-flex align-items-center">
                                                    <input class="form-check-input me-2" type="checkbox" name="pages[]" id="page-<?php echo e($item['key']); ?>-<?php echo e($branch->id ?? 'new'); ?>" value="<?php echo e($item['key']); ?>" <?php echo e($checked ? 'checked' : ''); ?>>
                                                    <label class="form-check-label fw-semibold small mb-0" for="page-<?php echo e($item['key']); ?>-<?php echo e($branch->id ?? 'new'); ?>">
                                                        <a href="<?php echo e($item['url']); ?>" target="_blank" class="text-decoration-none"><?php echo e($item['label']); ?></a>
                                                    </label>
                                                </div>
                                                <span class="badge bg-secondary"><?php echo e($item['count'] ?? '-'); ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <?php $__currentLoopData = [
                                    ['student','Manajemen Siswa','people','primary'],
                                    ['teacher','Manajemen Guru','person-workspace','success'],
                                    ['schedule','Jadwal & Kelas','calendar-week','info'],
                                    ['payment','Keuangan','wallet2','warning'],
                                    ['tryout','Tryout CBT','ui-checks-grid','purple'],
                                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$key, $label, $icon, $color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $checked = in_array($key, (array)$branchAllowed); ?>
                                    <div class="col-md-6">
                                        <div class="form-check p-3 rounded-0" style="background:var(--surface);border:1px solid var(--card-border);">
                                            <input class="form-check-input" type="checkbox" name="pages[]" id="page-<?php echo e($key); ?>-<?php echo e($branch->id ?? 'new'); ?>" value="<?php echo e($key); ?>" <?php echo e($checked ? 'checked' : ''); ?>>
                                            <label class="form-check-label fw-semibold small" for="page-<?php echo e($key); ?>-<?php echo e($branch->id ?? 'new'); ?>">
                                                <i class="bi bi-<?php echo e($icon); ?> me-1 text-<?php echo e($color==='purple'?'primary':$color); ?>"></i><?php echo e($label); ?>

                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-end">
                <button class="btn btn-primary fw-semibold px-4">
                    <i class="bi bi-check-lg me-2"></i><?php echo e($branch ? 'Simpan Perubahan' : 'Simpan Cabang'); ?>

                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/owner/branches/form.blade.php ENDPATH**/ ?>