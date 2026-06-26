<?php $__env->startSection('title','Cuti & Freeze Paket'); ?>
<?php $__env->startSection('page-title','Cuti & Freeze Paket'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-up">


<div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-12">
            <div class="d-flex align-items-center gap-3 mb-1">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                    <i class="bi bi-snow2"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Cuti & Freeze Paket</h5>
                    <span style="font-size:12px;opacity:.8">Kelola permohonan freeze paket siswa</span>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Total Pengajuan</div><div class="stat-value"><?php echo e($stats['total']); ?></div></div>
                <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-snow2"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Menunggu</div><div class="stat-value"><?php echo e($stats['pending']); ?></div></div>
                <div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-hourglass-split"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Disetujui</div><div class="stat-value"><?php echo e($stats['approved']); ?></div></div>
                <div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-check-circle"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="border-top:3px solid #ef4444">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Ditolak</div><div class="stat-value"><?php echo e($stats['rejected']); ?></div></div>
                <div class="stat-icon bg-danger-soft" style="color:white"><i class="bi bi-x-circle"></i></div>
            </div>
        </div>
    </div>
</div>


<div class="alert mb-4" style="background:var(--soft-warning);border:1px solid rgba(246,175,35,.3);border-radius:14px;color:#92400e">
    <div class="d-flex align-items-start gap-3">
        <i class="bi bi-shield-exclamation" style="font-size:20px;flex-shrink:0;margin-top:2px;color:#b45309"></i>
        <div style="font-size:13px">
            <div class="fw-bold mb-1">Hanya Kelas PRIVATE yang dapat Mengajukan Freeze</div>
            Sistem memblokir pengajuan dari paket Reguler. Semua entri di daftar ini adalah paket Privat yang terverifikasi. Siswa reguler tidak hadir = sesi <strong>Hangus</strong> otomatis.
        </div>
    </div>
</div>


<?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
    <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>


<div class="dashboard-card mb-4">
    <form method="GET" action="<?php echo e(route('admin.leave.index')); ?>" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Cari nama siswa..." value="<?php echo e(request('search')); ?>">
            </div>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="pending"  <?php echo e(request('status')=='pending'  ? 'selected' : ''); ?>>Pending</option>
                <option value="approved" <?php echo e(request('status')=='approved' ? 'selected' : ''); ?>>Disetujui</option>
                <option value="rejected" <?php echo e(request('status')=='rejected' ? 'selected' : ''); ?>>Ditolak</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel me-1"></i>Filter</button>
        </div>
        <div class="col-md-2">
            <a href="<?php echo e(route('admin.leave.index')); ?>" class="btn btn-outline-secondary w-100"><i class="bi bi-x-lg"></i></a>
        </div>
    </form>
</div>


<div class="dashboard-card">
    <div class="table-responsive">
        <table class="table table-hover table-modern align-middle mb-0">
            <thead class="thead-modern">
                <tr>
                    <th>Siswa / Ortu</th>
                    <th>Tipe</th>
                    <th>Paket & Sisa Sesi</th>
                    <th>Periode Freeze</th>
                    <th>Alasan</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $statusMap = [
                        'pending'  => ['label'=>'Pending',   'bg'=>'var(--soft-warning)', 'color'=>'#b45309', 'icon'=>'bi-hourglass-split'],
                        'approved' => ['label'=>'Disetujui', 'bg'=>'var(--soft-success)', 'color'=>'#10b981', 'icon'=>'bi-check-circle'],
                        'rejected' => ['label'=>'Ditolak',   'bg'=>'var(--soft-danger)',  'color'=>'#ef4444', 'icon'=>'bi-x-circle'],
                    ];
                    $s = $statusMap[$leave->status] ?? $statusMap['pending'];
                    $student = $leave->student;
                    $class   = $leave->schoolClass;
                ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:13px;font-weight:700;flex-shrink:0">
                                <?php echo e(strtoupper(mb_substr($student->name ?? 'S', 0, 1))); ?>

                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:13px"><?php echo e($student->name ?? '–'); ?></div>
                                <?php if($student?->parent_name): ?>
                                    <div class="text-muted" style="font-size:11px"><?php echo e($student->parent_name); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge" style="background:rgba(104,17,126,.15);color:#461256;font-size:11px;padding:4px 10px;font-weight:700">PRIVAT</span>
                    </td>
                    <td style="max-width:220px">
                        <div class="fw-semibold" style="font-size:13px"><?php echo e($class->nama_kelas ?? '–'); ?></div>
                        <?php if($class?->mataPelajaran): ?>
                            <div class="text-muted" style="font-size:11px"><?php echo e($class->mataPelajaran->nama); ?></div>
                        <?php endif; ?>
                        <?php if($class): ?>
                        <div style="font-size:11px;color:#6b7280">
                            Sisa: <?php echo e($class->jumlah_pertemuan ?? '?'); ?> sesi
                        </div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="fw-semibold" style="font-size:13px"><?php echo e($leave->tanggal_mulai->format('d M Y')); ?></div>
                        <div class="text-muted" style="font-size:12px">— <?php echo e($leave->tanggal_selesai->format('d M Y')); ?></div>
                    </td>
                    <td style="max-width:200px">
                        <div style="font-size:12px;color:var(--text-muted)"><?php echo e(Str::limit($leave->alasan, 60)); ?></div>
                    </td>
                    <td>
                        <span class="badge" style="background:<?php echo e($s['bg']); ?>;color:<?php echo e($s['color']); ?>;font-size:12px;padding:5px 12px">
                            <i class="bi <?php echo e($s['icon']); ?> me-1"></i><?php echo e($s['label']); ?>

                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1 justify-content-center">
                            <?php if($leave->status === 'pending'): ?>
                            <button class="btn btn-sm btn-success" onclick="actionLeave(<?php echo e($leave->id); ?>, 'approve')" title="Setujui">
                                <i class="bi bi-check-lg"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="actionLeave(<?php echo e($leave->id); ?>, 'reject')" title="Tolak">
                                <i class="bi bi-x-lg"></i>
                            </button>
                            <?php else: ?>
                            <span class="text-muted" style="font-size:12px">—</span>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-snow2" style="font-size:2.5rem;display:block;margin-bottom:10px;opacity:.4"></i>
                        Belum ada pengajuan cuti & freeze.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($leaves->hasPages()): ?>
    <div class="mt-3 d-flex justify-content-center">
        <?php echo e($leaves->links()); ?>

    </div>
    <?php endif; ?>
</div>


<form id="approveForm" method="POST" style="display:none">
    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
    <input type="hidden" name="catatan_admin" id="catatanInput">
</form>

</div>

<?php $__env->startPush('scripts'); ?>
<script>
function actionLeave(id, action) {
    const label    = action === 'approve' ? 'Setujui' : 'Tolak';
    const catatan  = prompt((action === 'approve' ? 'Catatan persetujuan (opsional):' : 'Alasan penolakan (opsional):'), '');
    if (catatan === null) return;

    const form    = document.getElementById('approveForm');
    const route   = action === 'approve'
        ? `<?php echo e(url('admin/leave')); ?>/${id}/approve`
        : `<?php echo e(url('admin/leave')); ?>/${id}/reject`;
    form.action   = route;
    document.getElementById('catatanInput').value = catatan;
    form.submit();
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/leave/index.blade.php ENDPATH**/ ?>