<?php $__env->startSection('title', 'Data Siswa'); ?>
<?php $__env->startSection('page-title', 'Data Siswa'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
    <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>


<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Manajemen Data Siswa</h5>
                    <span style="font-size:12px;opacity:.8">Kelola data seluruh siswa di semua cabang Smart Center Indonesia</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="<?php echo e(route('admin.students.create')); ?>" class="btn fw-semibold px-4"
                style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                <i class="bi bi-plus-lg me-2"></i>Tambah Siswa
            </a>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 fade-up">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Siswa</div>
                    <div class="stat-value text-primary"><?php echo e($stats['total']); ?></div>
                    <div class="stat-growth text-muted">
                        <i class="bi bi-people me-1"></i>Semua cabang
                    </div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Siswa Aktif</div>
                    <div class="stat-value text-success"><?php echo e($stats['aktif']); ?></div>
                    <div class="stat-growth text-success">
                        <i class="bi bi-check-circle me-1"></i>Berkegiatan
                    </div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white">
                    <i class="bi bi-person-check-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #f59e0b">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Siswa Cuti</div>
                    <div class="stat-value text-warning"><?php echo e($stats['cuti']); ?></div>
                    <div class="stat-growth text-warning">
                        <i class="bi bi-pause-circle me-1"></i>Sementara berhenti
                    </div>
                </div>
                <div class="stat-icon bg-warning-soft" style="color:white">
                    <i class="bi bi-person-dash-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card" style="border-top:3px solid #ef4444">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Tidak Aktif</div>
                    <div class="stat-value text-danger"><?php echo e($stats['tidak_aktif']); ?></div>
                    <div class="stat-growth text-danger">
                        <i class="bi bi-x-circle me-1"></i>Tidak berkegiatan
                    </div>
                </div>
                <div class="stat-icon bg-danger-soft" style="color:white">
                    <i class="bi bi-person-x-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="dashboard-card fade-up">

    
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h6 class="fw-bold mb-1" style="color:var(--text-primary)">
                <i class="bi bi-people-fill text-primary me-2"></i>Daftar Siswa
            </h6>
            <p class="text-muted mb-0" style="font-size:12px">Kelola data seluruh siswa di semua cabang</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-outline-secondary btn-sm" onclick="window.print()" title="Print">
                <i class="bi bi-printer me-1"></i><span class="d-none d-md-inline">Print</span>
            </button>
            <a href="<?php echo e(route('admin.students.create')); ?>" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Tambah Siswa
            </a>
        </div>
    </div>

    
    <form method="GET" action="<?php echo e(route('admin.students.index')); ?>" id="filterForm">
        <div class="row g-2 mb-4">
            <div class="col-12 col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="background:var(--input-bg);border:1.5px solid var(--card-border);border-right:none;border-radius:10px 0 0 10px">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                           class="form-control" placeholder="Cari nama atau NIS..."
                           style="border-left:none;border-radius:0 10px 10px 0"
                           oninput="debounceFilter()">
                </div>
            </div>
            <div class="col-6 col-md-2">
                <select name="kategori_peserta_didik" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    <option value="Pra Sekolah (PAUD/TK)" <?php echo e(request('kategori_peserta_didik')==="Pra Sekolah (PAUD/TK)" ? 'selected':''); ?>>Pra Sekolah (PAUD/TK)</option>
                    <option value="Sekolah Dasar (SD)" <?php echo e(request('kategori_peserta_didik')==="Sekolah Dasar (SD)" ? 'selected':''); ?>>Sekolah Dasar (SD)</option>
                    <option value="Sekolah Menengah Pertama (SMP)" <?php echo e(request('kategori_peserta_didik')==="Sekolah Menengah Pertama (SMP)" ? 'selected':''); ?>>Sekolah Menengah Pertama (SMP)</option>
                    <option value="Sekolah Menengah Atas/Kejuruan (SMA/SMK)" <?php echo e(request('kategori_peserta_didik')==="Sekolah Menengah Atas/Kejuruan (SMA/SMK)" ? 'selected':''); ?>>Sekolah Menengah Atas/Kejuruan (SMA/SMK)</option>
                    <option value="Mahasiswa" <?php echo e(request('kategori_peserta_didik')==="Mahasiswa" ? 'selected':''); ?>>Mahasiswa</option>
                    <option value="Umum" <?php echo e(request('kategori_peserta_didik')==="Umum" ? 'selected':''); ?>>Umum</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="gender" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Gender</option>
                    <option value="L" <?php echo e(request('gender')==='L' ? 'selected':''); ?>>Laki-laki</option>
                    <option value="P" <?php echo e(request('gender')==='P' ? 'selected':''); ?>>Perempuan</option>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <select name="branch_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Cabang</option>
                    <option value="pusat" <?php echo e(request('branch_id')==='pusat' ? 'selected':''); ?>>Pusat</option>
                    <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($b->id); ?>" <?php echo e(request('branch_id')==$b->id ? 'selected':''); ?>><?php echo e($b->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-6 col-md-1">
                <?php if(request()->hasAny(['search','kategori_peserta_didik','gender','branch_id'])): ?>
                <a href="<?php echo e(route('admin.students.index')); ?>" class="btn btn-sm btn-outline-secondary w-100" title="Reset filter">
                    <i class="bi bi-x-lg"></i>
                </a>
                <?php else: ?>
                <button type="submit" class="btn btn-sm btn-outline-primary w-100" title="Filter">
                    <i class="bi bi-funnel"></i>
                </button>
                <?php endif; ?>
            </div>
        </div>
    </form>

    
<?php if(request()->hasAny(['search','kategori_peserta_didik','gender','branch_id'])): ?>
                <div class="d-flex gap-2 mb-3 flex-wrap">
                    <?php if(request('search')): ?>
                    <span class="badge" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);border:1px solid var(--soft-primary-border);font-size:12px;padding:6px 12px;border-radius:8px">
                        <i class="bi bi-search me-1"></i><?php echo e(request('search')); ?>

                    </span>
                    <?php endif; ?>
                    <?php if(request('kategori_peserta_didik')): ?>
                    <span class="badge" style="background:var(--soft-success-bg);color:var(--soft-success-text);border:1px solid var(--soft-success-border);font-size:12px;padding:6px 12px;border-radius:8px">
                        <i class="bi bi-tag me-1"></i><?php echo e(request('kategori_peserta_didik')); ?>

        </span>
        <?php endif; ?>
        <?php if(request('gender')): ?>
        <span class="badge" style="background:var(--soft-danger-bg);color:var(--soft-danger-text);border:1px solid var(--soft-danger-border);font-size:12px;padding:6px 12px;border-radius:8px">
            <i class="bi bi-person me-1"></i><?php echo e(request('gender')==='L' ? 'Laki-laki':'Perempuan'); ?>

        </span>
        <?php endif; ?>
        <small class="text-muted align-self-center"><?php echo e($students->total()); ?> hasil ditemukan</small>
    </div>
    <?php endif; ?>

    
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="thead-modern">
                <tr>
                    <th class="small text-muted fw-semibold py-3">Siswa</th>
                    <th class="small text-muted fw-semibold py-3">Kategori Peserta Didik</th>
                    <th class="small text-muted fw-semibold py-3">Paket Aktif</th>
                    <th class="small text-muted fw-semibold py-3 text-center">Status</th>
                    <th class="small text-muted fw-semibold py-3">Wali / Kontak</th>
                    <th class="small text-muted fw-semibold py-3 text-center" style="width:110px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $statusColor = match($s->status ?? 'aktif') {
                        'aktif'                    => ['bg'=>'var(--soft-success-bg)','text'=>'var(--soft-success-text)','border'=>'var(--soft-success-border)','label'=>'Aktif'],
                        'cuti'                     => ['bg'=>'var(--soft-warning-bg)','text'=>'var(--soft-warning-text)','border'=>'var(--soft-warning-border)','label'=>'Cuti'],
                        'nonaktif','tidak_aktif'   => ['bg'=>'var(--soft-danger-bg)', 'text'=>'var(--soft-danger-text)', 'border'=>'var(--soft-danger-border)', 'label'=>'Tidak Aktif'],
                        default                    => ['bg'=>'var(--soft-muted-bg)', 'text'=>'var(--text-muted)', 'border'=>'var(--soft-border)', 'label'=>ucfirst($s->status ?? '-')],
                    };
                ?>
                <tr>
                    <td>
                        <div class="fw-semibold" style="font-size:13px"><?php echo e($s->name); ?></div>
                        <div class="text-muted" style="font-size:11px">
                            <i class="bi bi-hash" style="font-size:10px"></i><?php echo e($s->nis ?: '–'); ?>

                        </div>
                    </td>
                    <td>
                        <span class="badge" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);border:1px solid var(--soft-primary-border);font-size:11px;white-space:normal;max-width:160px">
                            <?php echo e($s->kategori_peserta_didik ?: '–'); ?>

                        </span>
                    </td>
                    <td>
                        <?php if($s->package): ?>
                            <div style="font-size:13px;font-weight:500"><?php echo e($s->package->nama); ?></div>
                            <div class="text-muted" style="font-size:11px">
                                <i class="bi bi-building me-1"></i><?php echo e($s->branch->name ?? 'Pusat'); ?>

                            </div>
                        <?php else: ?>
                            <span class="text-muted" style="font-size:12px">Belum ada paket</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <span class="badge" style="background:<?php echo e($statusColor['bg']); ?>;color:<?php echo e($statusColor['text']); ?>;border:1px solid <?php echo e($statusColor['border']); ?>;font-size:11px;padding:5px 10px;border-radius:8px">
                            <?php echo e($statusColor['label']); ?>

                        </span>
                    </td>
                    <td>
                        <div style="font-size:13px"><?php echo e($s->parent_name ?? '–'); ?></div>
                        <?php if($s->parent_phone): ?>
                        <a href="https://wa.me/<?php echo e(preg_replace('/[^0-9]/','',$s->parent_phone)); ?>" target="_blank"
                           class="text-muted text-decoration-none" style="font-size:11px">
                            <i class="bi bi-whatsapp me-1" style="color:#25d366"></i><?php echo e($s->parent_phone); ?>

                        </a>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="<?php echo e(route('admin.students.show', $s)); ?>" class="btn btn-sm btn-outline-info"
                                    title="Detail" style="border-radius:8px;padding:5px 8px">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="<?php echo e(route('admin.students.edit', $s)); ?>" class="btn btn-sm btn-outline-warning"
                                    title="Edit" style="border-radius:8px;padding:5px 8px">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteStudent(<?php echo e($s->id); ?>, '<?php echo e(addslashes($s->name)); ?>')"
                                    title="Hapus" style="border-radius:8px;padding:5px 8px">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="py-5">
                        <div class="text-center">
                            <div style="width:80px;height:80px;border-radius:50%;background:var(--input-bg);display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
                                <i class="bi bi-people text-muted" style="font-size:2rem;opacity:.5"></i>
                            </div>
                            <p class="text-muted mb-3">
                                <?php if(request()->hasAny(['search','kategori_peserta_didik','gender','branch_id'])): ?>
                                    Tidak ada siswa yang cocok dengan filter yang dipilih.
                                <?php else: ?>
                                    Belum ada data siswa. Tambahkan siswa pertama Anda!
                                <?php endif; ?>
                            </p>
                            <?php if(request()->hasAny(['search','kategori_peserta_didik','gender','branch_id'])): ?>
                            <a href="<?php echo e(route('admin.students.index')); ?>" class="btn btn-sm btn-outline-secondary me-2">
                                <i class="bi bi-x me-1"></i>Reset Filter
                            </a>
                            <?php endif; ?>
                            <a href="<?php echo e(route('admin.students.create')); ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-lg me-1"></i>Tambah Siswa
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    
    <?php if($students->hasPages()): ?>
    <div class="d-flex flex-wrap justify-content-between align-items-center mt-4 gap-2 pt-3" style="border-top:1px solid var(--card-border)">
        <small class="text-muted">
            Menampilkan <strong><?php echo e($students->firstItem() ?? 0); ?></strong>–<strong><?php echo e($students->lastItem() ?? 0); ?></strong>
            dari <strong><?php echo e($students->total()); ?></strong> siswa
        </small>
        <div><?php echo e($students->withQueryString()->links('pagination::bootstrap-5')); ?></div>
    </div>
    <?php else: ?>
    <div class="text-muted small mt-3 pt-3" style="border-top:1px solid var(--card-border)">
        Total: <strong><?php echo e($students->total()); ?></strong> siswa
    </div>
    <?php endif; ?>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// ---- SEARCH DEBOUNCE ----
let filterTimer;
function debounceFilter() {
    clearTimeout(filterTimer);
    filterTimer = setTimeout(() => document.getElementById('filterForm').submit(), 500);
}

// ---- DELETE STUDENT ----
function deleteStudent(id, name) {
    if (!confirm('Hapus siswa "' + name + '"? Akun user-nya juga akan dihapus. Tindakan ini tidak bisa dibatalkan.')) return;
    fetch('/admin/students/' + id, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message || 'Siswa berhasil dihapus!', 'success');
            setTimeout(() => location.reload(), 900);
        } else {
            showToast(data.message || 'Gagal menghapus siswa.', 'error');
        }
    })
    .catch(() => showToast('Terjadi kesalahan jaringan.', 'error'));
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/students/index.blade.php ENDPATH**/ ?>