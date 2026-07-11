<?php $__env->startSection('title','Manajemen Cuti'); ?>
<?php $__env->startSection('page-title','Manajemen Cuti'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-up">


<div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                    <i class="bi bi-snow2"></i>
                </div>
                <div>
                    <div style="font-size:11px;opacity:.7;letter-spacing:1px;text-transform:uppercase">Portal Siswa</div>
                    <h5 class="fw-bold mb-0" style="color:white">Manajemen Cuti & Freeze</h5>
                    <span style="font-size:12px;opacity:.8">Pantau status pengajuan dan riwayat pembekuan paket belajar Anda.</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="<?php echo e(route('siswa.leave.create')); ?>" class="btn fw-semibold px-4" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px">
                <i class="bi bi-plus-lg me-2"></i>Ajukan Cuti Baru
            </a>
        </div>
    </div>
</div>


<div class="alert mb-4" style="background:var(--soft-warning);border:1px solid rgba(246,175,35,.3);border-radius:14px;color:#92400e">
    <div class="d-flex align-items-start gap-3">
        <i class="bi bi-shield-exclamation" style="font-size:22px;flex-shrink:0;margin-top:2px;color:#b45309"></i>
        <div>
            <div class="fw-bold mb-1">Hanya Kelas PRIVATE yang dapat Mengajukan Cuti & Freeze</div>
            <div style="font-size:13px">Siswa kelas Reguler tidak diizinkan mengajukan cuti atau pembekuan paket. Sesi kelas reguler yang terlewat akan otomatis terhitung sebagai <strong>Hangus</strong>.</div>
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
    <form method="GET" action="<?php echo e(route('siswa.leave.index')); ?>" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan paket..." value="<?php echo e(request('search')); ?>">
            </div>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="pending"  <?php echo e(request('status')=='pending'  ? 'selected' : ''); ?>>Menunggu</option>
                <option value="approved" <?php echo e(request('status')=='approved' ? 'selected' : ''); ?>>Disetujui</option>
                <option value="rejected" <?php echo e(request('status')=='rejected' ? 'selected' : ''); ?>>Ditolak</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel me-1"></i>Filter</button>
        </div>
        <div class="col-md-2">
            <a href="<?php echo e(route('siswa.leave.index')); ?>" class="btn btn-outline-secondary w-100"><i class="bi bi-x-lg"></i></a>
        </div>
    </form>
</div>


<div class="dashboard-card">
    <div class="table-responsive">
        <table class="table table-hover table-modern align-middle mb-0">
            <thead class="thead-modern">
                <tr>
                    <th>Paket & Alasan</th>
                    <th>Tipe</th>
                    <th>Periode Cuti</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $statusMap = [
                        'pending'  => ['label'=>'Menunggu',  'bg'=>'var(--soft-warning)', 'color'=>'#b45309', 'icon'=>'bi-hourglass-split'],
                        'approved' => ['label'=>'Disetujui', 'bg'=>'var(--soft-success)', 'color'=>'#10b981', 'icon'=>'bi-check-circle'],
                        'rejected' => ['label'=>'Ditolak',   'bg'=>'var(--soft-danger)',  'color'=>'#ef4444', 'icon'=>'bi-x-circle'],
                    ];
                    $s = $statusMap[$leave->status] ?? $statusMap['pending'];
                ?>
                <tr>
                    <td style="max-width:280px">
                        <div class="fw-semibold"><?php echo e($leave->schoolClass->nama_kelas ?? '–'); ?></div>
                        <?php if($leave->schoolClass?->mataPelajaran): ?>
                            <div class="text-muted" style="font-size:12px"><?php echo e($leave->schoolClass->mataPelajaran->nama); ?></div>
                        <?php endif; ?>
                        <div class="mt-1" style="font-size:12px;color:var(--text-muted);font-style:italic">"<?php echo e(Str::limit($leave->alasan, 60)); ?>"</div>
                        <?php if($leave->catatan_admin): ?>
                            <div class="mt-1" style="font-size:11px;color:#6b7280"><i class="bi bi-chat-text me-1"></i>Admin: <?php echo e($leave->catatan_admin); ?></div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge" style="background:rgba(104,17,126,.15);color:#461256;font-size:11px;padding:4px 10px;font-weight:700">PRIVAT</span>
                    </td>
                    <td>
                        <div class="fw-semibold" style="font-size:13px">
                            <?php echo e($leave->tanggal_mulai->format('d M Y')); ?>

                        </div>
                        <div class="text-muted" style="font-size:12px">— <?php echo e($leave->tanggal_selesai->format('d M Y')); ?></div>
                        <div class="text-muted" style="font-size:11px"><?php echo e($leave->tanggal_mulai->diffInDays($leave->tanggal_selesai) + 1); ?> hari</div>
                    </td>
                    <td>
                        <span class="badge" style="background:<?php echo e($s['bg']); ?>;color:<?php echo e($s['color']); ?>;font-size:12px;padding:5px 12px">
                            <i class="bi <?php echo e($s['icon']); ?> me-1"></i><?php echo e($s['label']); ?>

                        </span>
                    </td>
                    <td class="text-center">
                        <?php if($leave->status === 'pending'): ?>
                            <span class="text-muted" style="font-size:12px"><i class="bi bi-clock me-1"></i>Menunggu</span>
                        <?php elseif($leave->status === 'approved'): ?>
                            <span class="text-success" style="font-size:12px"><i class="bi bi-check2 me-1"></i>Selesai</span>
                        <?php else: ?>
                            <span class="text-danger" style="font-size:12px"><i class="bi bi-x me-1"></i>Ditolak</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="bi bi-snow2" style="font-size:2.5rem;display:block;margin-bottom:10px;opacity:.4"></i>
                        Belum ada pengajuan cuti. <a href="<?php echo e(route('siswa.leave.create')); ?>">Ajukan sekarang</a>
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

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/siswa/leave/index.blade.php ENDPATH**/ ?>