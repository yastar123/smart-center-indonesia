<?php $__env->startSection('title', 'Tagihan Siswa'); ?>
<?php $__env->startSection('page-title', 'Tagihan Siswa'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-up">


<div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Tagihan Siswa</h5>
                    <span style="font-size:12px;opacity:.8">Siswa dengan pembayaran cicilan dan pascabayar (per sesi)</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="<?php echo e(route('admin.billing.index')); ?>" class="btn fw-semibold px-3"
               style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px">
                <i class="bi bi-receipt me-2"></i>Lihat Pembayaran Lunas
            </a>
        </div>
    </div>
</div>


<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show mb-3"><i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-3"><i class="bi bi-x-circle me-2"></i><?php echo e(session('error')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>


<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 fade-up">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Tagihan Siswa</div>
                    <div class="stat-value text-primary count-up" data-target="<?php echo e($stats['total']); ?>"><?php echo e($stats['total']); ?></div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-people"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Cicilan (Prabayar)</div>
                    <div class="stat-value text-warning count-up" data-target="<?php echo e($stats['cicilan']); ?>"><?php echo e($stats['cicilan']); ?></div>
                </div>
                <div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-pie-chart"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #0ea5e9">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Pascabayar (Per Sesi)</div>
                    <div class="stat-value count-up" data-target="<?php echo e($stats['postpaid']); ?>" style="color:#0ea5e9"><?php echo e($stats['postpaid']); ?></div>
                </div>
                <div class="stat-icon bg-info-soft" style="color:white"><i class="bi bi-receipt"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card" style="border-top:3px solid #ef4444">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Invoice Belum Lunas</div>
                    <div class="stat-value text-danger count-up" data-target="<?php echo e($stats['menunggu']); ?>"><?php echo e($stats['menunggu']); ?></div>
                </div>
                <div class="stat-icon bg-danger-soft" style="color:white"><i class="bi bi-exclamation-triangle"></i></div>
            </div>
        </div>
    </div>
</div>


<div class="dashboard-card mb-4 fade-up">
    <form method="GET" action="<?php echo e(route('admin.tagihan-siswa.index')); ?>">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold" style="font-size:12px">Cari (Nama Siswa / Kelas)</label>
                <div class="input-group">
                    <span class="input-group-text" style="background:var(--input-bg);border-color:var(--card-border)"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari..."
                           class="form-control" style="border-color:var(--card-border);background:var(--input-bg)">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold" style="font-size:12px">Tipe Tagihan</label>
                <select name="billing_mode" class="form-select" style="border-color:var(--card-border);background:var(--input-bg)">
                    <option value="">Semua Tipe</option>
                    <option value="cicilan"  <?php echo e(request('billing_mode')=='cicilan' ?'selected':''); ?>>Cicilan (Prabayar)</option>
                    <option value="postpaid" <?php echo e(request('billing_mode')=='postpaid'?'selected':''); ?>>Pascabayar (Per Sesi)</option>
                </select>
            </div>
            <?php if(auth()->user()->hasRole('owner')): ?>
            <div class="col-md-3">
                <label class="form-label fw-semibold" style="font-size:12px">Cabang</label>
                <select name="cabang_id" class="form-select" style="border-color:var(--card-border);background:var(--input-bg)">
                    <option value="">Semua Cabang</option>
                    <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($b->id); ?>" <?php echo e(request('cabang_id')==$b->id?'selected':''); ?>><?php echo e($b->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <?php endif; ?>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill fw-semibold">Filter</button>
                <a href="<?php echo e(route('admin.tagihan-siswa.index')); ?>" class="btn btn-outline-secondary px-3">Reset</a>
            </div>
        </div>
    </form>
</div>


<div class="dashboard-card fade-up">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr style="background:var(--input-bg)">
                    <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Nama Siswa / Kelas</th>
                    <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Paket</th>
                    <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Harga Paket</th>
                    <th class="text-center" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Tipe Tagihan</th>
                    <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Cicilan Dibayar</th>
                    <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Sisa Cicilan</th>
                    <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Cabang</th>
                    <th class="text-center" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $siswa = $kelas->siswa->first();
                    $siswaNama = $siswa?->user?->name ?? $siswa?->name ?? '—';
                    $paket = $siswa?->package;
                    $billingLabel = $kelas->billing_mode === 'postpaid' ? 'Pascabayar' : 'Cicilan';
                    $billingColor = $kelas->billing_mode === 'postpaid' ? '#0ea5e9' : '#f6af23';
                    $billingBg    = $kelas->billing_mode === 'postpaid' ? 'rgba(14,165,233,.12)' : 'rgba(246,175,35,.15)';

                    // Cicilan summary for this kelas
                    $kelasInvoices   = $invoicesByKelas[$kelas->id] ?? collect();
                    $totalTagihan    = $kelasInvoices->sum('total');
                    $totalDibayar    = $kelasInvoices->flatMap->pembayaran->where('status', 'verified')->sum('jumlah');
                    $sisaCicilan     = max(0, $totalTagihan - $totalDibayar);
                ?>
                <tr>
                    <td>
                        <div class="fw-semibold" style="font-size:13px"><?php echo e($siswaNama); ?></div>
                        <div class="text-muted" style="font-size:12px"><i class="bi bi-diagram-3 me-1"></i><?php echo e($kelas->nama_kelas); ?></div>
                    </td>
                    <td style="font-size:13px">
                        <?php if($paket): ?>
                            <div class="fw-semibold" style="font-size:13px"><?php echo e($paket->nama); ?></div>
                            <div class="text-muted" style="font-size:11px"><?php echo e(ucfirst($paket->jenis ?? '—')); ?> · <?php echo e($paket->jumlah_pertemuan ?? '?'); ?> sesi</div>
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>
                    <td style="font-size:13px">
                        <?php if($paket?->harga): ?>
                            <span class="fw-semibold">Rp <?php echo e(number_format($paket->harga, 0, ',', '.')); ?></span>
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <span style="background:<?php echo e($billingBg); ?>;color:<?php echo e($billingColor); ?>;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600">
                            <?php echo e($billingLabel); ?>

                        </span>
                    </td>
                    <td style="font-size:13px">
                        <?php if($totalDibayar > 0): ?>
                            <span class="fw-semibold text-success">Rp <?php echo e(number_format($totalDibayar, 0, ',', '.')); ?></span>
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>
                    <td style="font-size:13px">
                        <?php if($sisaCicilan > 0): ?>
                            <span class="fw-semibold text-danger">Rp <?php echo e(number_format($sisaCicilan, 0, ',', '.')); ?></span>
                        <?php elseif($totalTagihan > 0): ?>
                            <span class="text-success fw-semibold"><i class="bi bi-check-circle me-1"></i>Lunas</span>
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>
                    <td style="font-size:13px"><?php echo e($kelas->cabang?->name ?? '—'); ?></td>
                    <td class="text-center">
                        <a href="<?php echo e(route('admin.tagihan-siswa.show', $kelas->id)); ?>"
                           class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:11px">
                            <i class="bi bi-eye me-1"></i>Lihat Detail
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <div style="font-size:40px;opacity:.3;margin-bottom:8px"><i class="bi bi-wallet2"></i></div>
                        <div class="text-muted">Belum ada data tagihan siswa cicilan atau pascabayar</div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($classes->hasPages()): ?>
        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-top">
            <div class="text-muted" style="font-size:13px">
                Menampilkan <?php echo e($classes->firstItem()); ?>–<?php echo e($classes->lastItem()); ?> dari <?php echo e($classes->total()); ?> kelas
            </div>
            <?php echo e($classes->appends(request()->all())->links()); ?>

        </div>
    <?php endif; ?>
</div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/tagihan-siswa/index.blade.php ENDPATH**/ ?>