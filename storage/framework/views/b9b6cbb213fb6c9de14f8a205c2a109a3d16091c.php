<?php $__env->startSection('title', 'Monitoring Cabang'); ?>
<?php $__env->startSection('page-title', 'Monitoring Cabang'); ?>

<?php $__env->startSection('content'); ?>


<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                    <i class="bi bi-building-fill"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Monitoring Cabang</h5>
                    <span style="font-size:12px;opacity:.8">Pantau dan kelola performa seluruh cabang Smart Center Indonesia</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="<?php echo e(route('owner.branches.create')); ?>" class="btn fw-semibold px-4"
                style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                <i class="bi bi-plus-lg me-2"></i>Tambah Cabang
            </a>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 fade-up">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Cabang</div>
                    <div class="stat-value text-primary"><?php echo e($total); ?></div>
                    <div class="stat-growth text-muted"><i class="bi bi-building me-1"></i>Semua cabang</div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-building-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Cabang Aktif</div>
                    <div class="stat-value text-success"><?php echo e($active); ?></div>
                    <div class="stat-growth text-success"><i class="bi bi-check-circle me-1"></i>Beroperasi</div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-building-fill-check"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Siswa</div>
                    <div class="stat-value text-warning"><?php echo e($students); ?></div>
                    <div class="stat-growth text-muted"><i class="bi bi-people me-1"></i>Semua cabang</div>
                </div>
                <div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-people-fill"></i></div>
            </div>
        </div>
    </div>
</div>


<div class="dashboard-card fade-up">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <h6 class="fw-bold mb-0" style="color:var(--text-primary)">
            <i class="bi bi-building text-primary me-2"></i>Daftar Cabang
        </h6>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?php echo e(route('owner.branches.export.excel')); ?>" class="btn btn-success btn-sm">
                <i class="bi bi-file-earmark-excel me-1"></i>Excel
            </a>
            <a href="<?php echo e(route('owner.branches.export.pdf')); ?>" class="btn btn-danger btn-sm">
                <i class="bi bi-file-earmark-pdf me-1"></i>PDF
            </a>
            <button onclick="window.print()" class="btn btn-secondary btn-sm">
                <i class="bi bi-printer me-1"></i>Print
            </button>
            <a href="<?php echo e(route('owner.branches.create')); ?>" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Tambah Cabang
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
    <div class="alert alert-success border-0 rounded-3 mb-3 d-flex align-items-center gap-2">
        <i class="bi bi-check-circle-fill"></i> <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
    <div class="alert alert-danger border-0 rounded-3 mb-3">
        <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo e($errors->first()); ?>

    </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead style="background:var(--input-bg)">
                <tr>
                    <th class="small text-muted fw-semibold py-3">CABANG</th>
                    <th class="small text-muted fw-semibold py-3">LOKASI</th>
                    <th class="small text-muted fw-semibold py-3 text-center">SISWA</th>
                    <th class="small text-muted fw-semibold py-3">KONTAK</th>
                    <th class="small text-muted fw-semibold py-3">FITUR</th>
                    <th class="small text-muted fw-semibold py-3 text-center">STATUS</th>
                    <th class="small text-muted fw-semibold py-3 text-center" style="width:160px">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#c84ddf,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:15px;flex-shrink:0">
                                <i class="bi bi-building"></i>
                            </div>
                            <div>
                                <div class="fw-semibold small"><?php echo e($branch->name); ?></div>
                                <div class="text-muted" style="font-size:.72rem"><?php echo e($branch->email ?? '-'); ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="small fw-semibold"><?php echo e($branch->city ?? '-'); ?></div>
                        <div class="text-muted" style="font-size:.72rem"><?php echo e($branch->regency ?? ''); ?></div>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-primary"><?php echo e($branch->students->count()); ?></span>
                    </td>
                    <td class="small text-muted"><?php echo e($branch->phone ?? '-'); ?></td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <?php if($branch->can_students): ?>
                                <span class="badge" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);font-size:10px">Siswa</span>
                            <?php endif; ?>
                            <?php if($branch->can_teachers): ?>
                                <span class="badge" style="background:var(--soft-success-bg);color:var(--soft-success-text);font-size:10px">Guru</span>
                            <?php endif; ?>
                            <?php if($branch->can_schedules): ?>
                                <span class="badge" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);font-size:10px">Jadwal</span>
                            <?php endif; ?>
                            <?php if($branch->can_payments): ?>
                                <span class="badge" style="background:var(--soft-warning-bg);color:var(--soft-warning-text);font-size:10px">Keuangan</span>
                            <?php endif; ?>
                            <?php if($branch->can_tryouts): ?>
                                <span class="badge" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);font-size:10px">Tryout</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="text-center">
                        <?php if($branch->status === 'active'): ?>
                            <span class="badge" style="background:var(--soft-success-bg);color:var(--soft-success-text)">
                                <i class="bi bi-circle-fill me-1" style="font-size:8px"></i>Aktif
                            </span>
                        <?php else: ?>
                            <span class="badge" style="background:var(--soft-danger-bg);color:var(--soft-danger-text)">
                                <i class="bi bi-circle-fill me-1" style="font-size:8px"></i>Nonaktif
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <form method="POST" action="<?php echo e(route('owner.branches.impersonate', $branch)); ?>" style="display:inline;margin-left:4px">
                                <?php echo csrf_field(); ?>
                                <button class="btn btn-outline-secondary" title="Masuk sebagai cabang">
                                    <i class="bi bi-box-arrow-in-right"></i>
                                </button>
                            </form>
                            <a href="<?php echo e(route('owner.branches.edit', $branch)); ?>" class="btn btn-outline-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button class="btn btn-outline-info"
                                data-bs-toggle="modal" data-bs-target="#resetModal<?php echo e($branch->id); ?>"
                                title="Reset Password">
                                <i class="bi bi-key"></i>
                            </button>
                            <form method="POST" action="<?php echo e(route('owner.branches.destroy', $branch)); ?>"
                                  onsubmit="return confirmDelete(event, '<?php echo e($branch->name); ?>')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-outline-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                <?php $__env->startPush('modals'); ?>
                
                <div class="modal fade" id="editModal<?php echo e($branch->id); ?>" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header border-0" style="background:linear-gradient(135deg,#f6af23,#e09000);color:white">
                                <h6 class="modal-title fw-bold"><i class="bi bi-pencil me-2"></i>Edit Cabang — <?php echo e($branch->name); ?></h6>
                                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
                                <form method="POST" action="<?php echo e(route('owner.branches.update', $branch)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>

                                    <h6 class="fw-bold mb-3 text-muted" style="font-size:12px;text-transform:uppercase;letter-spacing:.06em">
                                        <i class="bi bi-building me-2 text-primary"></i>Info Cabang
                                    </h6>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-semibold">Nama Cabang <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm" name="name" placeholder="Contoh: Cabang Jakarta" value="<?php echo e(old('name', $branch->name)); ?>" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-semibold">Kota</label>
                                            <input type="text" class="form-control form-control-sm" name="city" placeholder="Jakarta" value="<?php echo e(old('city', $branch->city)); ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-semibold">Kabupaten / Kecamatan</label>
                                            <input type="text" class="form-control form-control-sm" name="regency" placeholder="Kebayoran Baru" value="<?php echo e(old('regency', $branch->regency)); ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-semibold">Telepon</label>
                                            <input type="text" class="form-control form-control-sm" name="phone" placeholder="021-xxxxxxxx" value="<?php echo e(old('phone', $branch->phone)); ?>">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small fw-semibold">Alamat</label>
                                            <input type="text" class="form-control form-control-sm" name="address" placeholder="Alamat lengkap cabang" value="<?php echo e(old('address', $branch->address)); ?>">
                                        </div>
                                    </div>

                                    <hr class="my-3" style="border-color:var(--card-border)">

                                    <h6 class="fw-bold mb-3 text-muted" style="font-size:12px;text-transform:uppercase;letter-spacing:.06em">
                                        <i class="bi bi-person-badge me-2 text-success"></i>Akun Login Cabang
                                    </h6>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-semibold">Nama Admin</label>
                                            <input type="text" class="form-control form-control-sm" name="admin_name" placeholder="Nama admin cabang" value="<?php echo e(old('admin_name', optional($branch->admin)->name ?? '')); ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-semibold">Username (opsional)</label>
                                            <input type="text" class="form-control form-control-sm" name="admin_username" placeholder="admin.jakarta" value="<?php echo e(old('admin_username', optional($branch->admin)->username ?? '')); ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-semibold">Email</label>
                                            <input type="email" class="form-control form-control-sm" name="email" placeholder="admin@cabang.com" value="<?php echo e(old('email', optional($branch->admin)->email ?? $branch->email ?? '')); ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-semibold">Password (kosongkan jika tidak diubah)</label>
                                            <input type="password" class="form-control form-control-sm" name="password" placeholder="Kosongkan untuk tidak merubah" minlength="6">
                                        </div>
                                    </div>

                                    <hr class="my-3" style="border-color:var(--card-border)">

                                    <h6 class="fw-bold mb-3 text-muted" style="font-size:12px;text-transform:uppercase;letter-spacing:.06em">
                                        <i class="bi bi-toggles me-2 text-warning"></i>Fitur Akses
                                    </h6>
                                    <div class="row g-2 mb-4">
                                        <?php
                                            $branchAllowed = $branch->allowed_pages ?? [];
                                            if (! is_array($branchAllowed) || empty($branchAllowed)) {
                                                $branchAllowed = [];
                                                if ($branch->can_students) $branchAllowed[] = 'student';
                                                if ($branch->can_teachers) $branchAllowed[] = 'teacher';
                                                if ($branch->can_schedules) $branchAllowed[] = 'schedule';
                                                if ($branch->can_payments) $branchAllowed[] = 'payment';
                                                if ($branch->can_tryouts) $branchAllowed[] = 'tryout';
                                            }
                                        ?>

                                        <?php if(!empty($menuStructure) && count($menuStructure)): ?>
                                            <?php $__currentLoopData = $menuStructure; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-12 mb-1"><strong class="small text-muted"><?php echo e($section['section']); ?></strong></div>
                                                <?php $__currentLoopData = $section['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php $checked = in_array($item['key'], (array)$branchAllowed); ?>
                                                    <div class="col-md-6">
                                                        <div class="form-check p-3 rounded-3 d-flex align-items-center justify-content-between" style="background:var(--input-bg);border:1.5px solid var(--card-border);">
                                                            <div class="d-flex align-items-center">
                                                                <input class="form-check-input me-2" type="checkbox" name="pages[]" id="page-<?php echo e($item['key']); ?>-<?php echo e($branch->id); ?>" value="<?php echo e($item['key']); ?>" <?php echo e($checked ? 'checked' : ''); ?>>
                                                                <label class="form-check-label fw-semibold small mb-0" for="page-<?php echo e($item['key']); ?>-<?php echo e($branch->id); ?>">
                                                                    <a href="<?php echo e($item['url']); ?>" target="_blank" class="text-decoration-none"><?php echo e($item['label']); ?></a>
                                                                </label>
                                                            </div>
                                                            <div>
                                                                <span class="badge bg-secondary"><?php echo e($item['count'] ?? '-'); ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php elseif(!empty($pages) && count($pages)): ?>
                                            <?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php $checked = in_array($page, (array)$branchAllowed); ?>
                                                <div class="col-md-6">
                                                    <div class="form-check p-3 rounded-3" style="background:var(--input-bg);border:1.5px solid var(--card-border);">
                                                        <input class="form-check-input" type="checkbox" name="pages[]" id="page-<?php echo e($page); ?>-<?php echo e($branch->id); ?>" value="<?php echo e($page); ?>" <?php echo e($checked ? 'checked' : ''); ?>>
                                                        <label class="form-check-label fw-semibold small" for="page-<?php echo e($page); ?>-<?php echo e($branch->id); ?>">
                                                            <?php echo e(ucwords(str_replace(['-','_'], ' ', $page))); ?>

                                                        </label>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <?php $__currentLoopData = [
                                                ['can_students','Manajemen Siswa','people','primary','student'],
                                                ['can_teachers','Manajemen Guru','person-workspace','success','teacher'],
                                                ['can_schedules','Jadwal & Kelas','calendar-week','info','schedule'],
                                                ['can_payments','Keuangan','wallet2','warning','payment'],
                                                ['can_tryouts','Tryout CBT','ui-checks-grid','purple','tryout'],
                                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$name, $label, $icon, $color, $key]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php $checked = in_array($key, (array)$branchAllowed); ?>
                                            <div class="col-md-6">
                                                <div class="form-check form-switch p-3 rounded-3" style="background:var(--input-bg);border:1.5px solid var(--card-border);padding-left:3rem !important">
                                                    <input class="form-check-input" type="checkbox" name="pages[]" id="<?php echo e($name); ?>-<?php echo e($branch->id); ?>" value="<?php echo e($key); ?>" <?php echo e($checked ? 'checked' : ''); ?>>
                                                    <label class="form-check-label fw-semibold small" for="<?php echo e($name); ?>-<?php echo e($branch->id); ?>">
                                                        <i class="bi bi-<?php echo e($icon); ?> me-1 text-<?php echo e($color==='purple'?'primary':$color); ?>"></i><?php echo e($label); ?>

                                                    </label>
                                                </div>
                                            </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </div>

                                    <button class="btn btn-warning w-100 fw-semibold">
                                        <i class="bi bi-check-lg me-2"></i>Simpan Perubahan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="modal fade" id="resetModal<?php echo e($branch->id); ?>" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header border-0" style="background:linear-gradient(135deg,#06b6d4,#68117e);color:white">
                                <h6 class="modal-title fw-bold"><i class="bi bi-key me-2"></i>Reset Password — <?php echo e($branch->name); ?></h6>
                                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
                                <form method="POST" action="<?php echo e(route('owner.branches.resetPassword', $branch)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Password Baru</label>
                                        <input type="password" class="form-control form-control-sm" name="password" placeholder="Min. 8 karakter" required minlength="8">
                                    </div>
                                    <button class="btn btn-info text-white w-100 fw-semibold">
                                        <i class="bi bi-key me-2"></i>Reset Password
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $__env->stopPush(); ?>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="empty-state">
                            <i class="bi bi-building text-muted d-block mb-2" style="font-size:3rem;opacity:.3"></i>
                            <p class="text-muted">Belum ada data cabang</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?php echo $__env->yieldPushContent('modals'); ?>


<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0" style="background:linear-gradient(135deg,#68117e,#c84ddf);color:white">
                <h6 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Tambah Cabang Baru</h6>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" action="<?php echo e(route('owner.branches.store')); ?>">
                    <?php echo csrf_field(); ?>

                    <h6 class="fw-bold mb-3 text-muted" style="font-size:12px;text-transform:uppercase;letter-spacing:.06em">
                        <i class="bi bi-building me-2 text-primary"></i>Info Cabang
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nama Cabang <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="name" placeholder="Contoh: Cabang Jakarta" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Kota</label>
                            <input type="text" class="form-control form-control-sm" name="city" placeholder="Jakarta">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Kabupaten / Kecamatan</label>
                            <input type="text" class="form-control form-control-sm" name="regency" placeholder="Kebayoran Baru">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Telepon</label>
                            <input type="text" class="form-control form-control-sm" name="phone" placeholder="021-xxxxxxxx">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Alamat</label>
                            <input type="text" class="form-control form-control-sm" name="address" placeholder="Alamat lengkap cabang">
                        </div>
                    </div>

                    <hr class="my-3" style="border-color:var(--card-border)">

                    <h6 class="fw-bold mb-3 text-muted" style="font-size:12px;text-transform:uppercase;letter-spacing:.06em">
                        <i class="bi bi-person-badge me-2 text-success"></i>Akun Login Cabang
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nama Admin <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="admin_name" placeholder="Nama admin cabang" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Username (opsional)</label>
                            <input type="text" class="form-control form-control-sm" name="admin_username" placeholder="admin.jakarta">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control form-control-sm" name="email" placeholder="admin@cabang.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control form-control-sm" name="password" placeholder="Min. 8 karakter" required minlength="8">
                        </div>
                    </div>

                    <hr class="my-3" style="border-color:var(--card-border)">

                    <h6 class="fw-bold mb-3 text-muted" style="font-size:12px;text-transform:uppercase;letter-spacing:.06em">
                        <i class="bi bi-toggles me-2 text-warning"></i>Fitur Akses
                    </h6>
                    <div class="row g-2 mb-4">
                        <?php if(!empty($menuStructure) && count($menuStructure)): ?>
                            <?php $__currentLoopData = $menuStructure; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-12 mb-1"><strong class="small text-muted"><?php echo e($section['section']); ?></strong></div>
                                <?php $__currentLoopData = $section['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-6">
                                        <div class="form-check p-3 rounded-3 d-flex align-items-center justify-content-between" style="background:var(--input-bg);border:1.5px solid var(--card-border);">
                                            <div class="d-flex align-items-center">
                                                <input class="form-check-input me-2" type="checkbox" name="pages[]" id="page-<?php echo e($item['key']); ?>" value="<?php echo e($item['key']); ?>" checked>
                                                <label class="form-check-label fw-semibold small mb-0" for="page-<?php echo e($item['key']); ?>">
                                                    <a href="<?php echo e($item['url']); ?>" target="_blank" class="text-decoration-none"><?php echo e($item['label']); ?></a>
                                                </label>
                                            </div>
                                            <div>
                                                <span class="badge bg-secondary"><?php echo e($item['count'] ?? '-'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php elseif(!empty($pages) && count($pages)): ?>
                            <?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-6">
                                    <div class="form-check p-3 rounded-3" style="background:var(--input-bg);border:1.5px solid var(--card-border);">
                                        <input class="form-check-input" type="checkbox" name="pages[]" id="page-<?php echo e($page); ?>" value="<?php echo e($page); ?>" checked>
                                        <label class="form-check-label fw-semibold small" for="page-<?php echo e($page); ?>">
                                            <?php echo e(ucwords(str_replace(['-','_'], ' ', $page))); ?>

                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <?php $__currentLoopData = [
                                ['can_students','Manajemen Siswa','people','primary'],
                                ['can_teachers','Manajemen Guru','person-workspace','success'],
                                ['can_schedules','Jadwal & Kelas','calendar-week','info'],
                                ['can_payments','Keuangan','wallet2','warning'],
                                ['can_tryouts','Tryout CBT','ui-checks-grid','purple'],
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$name, $label, $icon, $color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6">
                                <div class="form-check form-switch p-3 rounded-3" style="background:var(--input-bg);border:1.5px solid var(--card-border);padding-left:3rem !important">
                                    <input class="form-check-input" type="checkbox" name="<?php echo e($name); ?>" id="<?php echo e($name); ?>" checked>
                                    <label class="form-check-label fw-semibold small" for="<?php echo e($name); ?>">
                                        <i class="bi bi-<?php echo e($icon); ?> me-1 text-<?php echo e($color==='purple'?'primary':$color); ?>"></i><?php echo e($label); ?>

                                    </label>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </div>

                    <button class="btn btn-primary w-100 py-2 fw-bold">
                        <i class="bi bi-check-lg me-2"></i>Simpan Cabang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function confirmDelete(e, name) {
    e.preventDefault();
    const form = e.target;
    confirmAction(`Hapus cabang "${name}"? Semua data terkait akan dihapus permanen.`, function() {
        form.submit();
    }, null, {title:'Hapus Cabang', okText:'Ya, Hapus'});
    return false;
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Edu Juanda Pratama\Downloads\smart-center-indonesia (1)\smart-center-indonesia\resources\views/owner/branches/index.blade.php ENDPATH**/ ?>