<?php $__env->startSection('title', 'Detail Tagihan Siswa'); ?>
<?php $__env->startSection('page-title', 'Detail Tagihan Siswa'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.tagihan-siswa.index')); ?>">Tagihan Siswa</a></li>
        <li class="breadcrumb-item active"><?php echo e($kelas->nama_kelas); ?></li>
    </ol>
</nav>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show mb-3"><i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-3"><i class="bi bi-x-circle me-2"></i><?php echo e(session('error')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>


<div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none">
    <div class="row g-3 align-items-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3">
                <div style="width:52px;height:52px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white"><?php echo e($kelas->nama_kelas); ?></h5>
                    <div style="font-size:13px;opacity:.85">
                        <span class="me-3"><i class="bi bi-building me-1"></i><?php echo e($kelas->cabang?->name ?? '—'); ?></span>
                        <span class="me-3"><i class="bi bi-person-badge me-1"></i><?php echo e($kelas->guru?->name ?? '—'); ?></span>
                        <span><i class="bi bi-credit-card me-1"></i><?php echo e($kelas->billing_mode === 'postpaid' ? 'Pascabayar' : 'Cicilan'); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="<?php echo e(route('admin.tagihan-siswa.index')); ?>" class="btn btn-sm"
               style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3)">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>


<?php if($student): ?>
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 fade-up">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Harga Paket</div>
                    <div class="fw-bold" style="font-size:1rem;color:var(--text-primary)">
                        <?php if($hargaPaket > 0): ?>
                            Rp <?php echo e(number_format($hargaPaket, 0, ',', '.')); ?>

                        <?php else: ?> —
                        <?php endif; ?>
                    </div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-box-seam"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Cicilan Dibayar</div>
                    <div class="fw-bold text-success" style="font-size:1rem">
                        <?php if($totalDibayar > 0): ?>
                            Rp <?php echo e(number_format($totalDibayar, 0, ',', '.')); ?>

                        <?php else: ?> —
                        <?php endif; ?>
                    </div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-check-circle"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #ef4444">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Sisa Cicilan</div>
                    <div class="fw-bold" style="font-size:1rem;color:<?php echo e($sisaCicilan > 0 ? '#ef4444' : '#10b981'); ?>">
                        <?php if($sisaCicilan > 0): ?>
                            Rp <?php echo e(number_format($sisaCicilan, 0, ',', '.')); ?>

                        <?php elseif($totalTagihan > 0): ?>
                            <span style="color:#10b981"><i class="bi bi-check-circle me-1"></i>Lunas</span>
                        <?php else: ?> —
                        <?php endif; ?>
                    </div>
                </div>
                <div class="stat-icon bg-danger-soft" style="color:white"><i class="bi bi-clock-history"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Invoice</div>
                    <div class="fw-bold text-warning" style="font-size:1rem"><?php echo e($invoices->count()); ?> invoice</div>
                    <div class="text-muted" style="font-size:11px"><?php echo e($jumlahInvoiceLunas); ?> lunas</div>
                </div>
                <div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-receipt"></i></div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row g-4">

    
    <div class="col-lg-7">

        
        <?php if($student): ?>
        <div class="dashboard-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-person-fill text-primary me-2"></i>Informasi Siswa</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3 rounded-3" style="background:var(--input-bg)">
                        <div class="text-muted" style="font-size:11px">Nama Siswa</div>
                        <div class="fw-semibold"><?php echo e($student->user?->name ?? $student->name ?? '—'); ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded-3" style="background:var(--input-bg)">
                        <div class="text-muted" style="font-size:11px">Paket Belajar (Kelas Ini)</div>
                        <div class="fw-semibold"><?php echo e($student->package?->nama ?? '—'); ?></div>
                        <?php if($student->package?->harga): ?>
                        <div class="text-muted" style="font-size:11px">Rp <?php echo e(number_format($student->package->harga, 0, ',', '.')); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded-3" style="background:var(--input-bg)">
                        <div class="text-muted" style="font-size:11px">Cabang</div>
                        <div class="fw-semibold"><?php echo e($student->branch?->name ?? '—'); ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded-3" style="background:var(--input-bg)">
                        <div class="text-muted" style="font-size:11px">Status Siswa</div>
                        <div class="fw-semibold"><?php echo e(ucfirst($student->status ?? '—')); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="dashboard-card mb-4">
            <div class="text-center py-4 text-muted">
                <i class="bi bi-person-x" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px"></i>
                Belum ada siswa terdaftar di kelas ini.
            </div>
        </div>
        <?php endif; ?>

        
        <?php if($invoices->isNotEmpty()): ?>
        <div class="dashboard-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-pie-chart text-warning me-2"></i>Rincian Cicilan</h6>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size:13px">
                    <thead>
                        <tr style="background:var(--input-bg)">
                            <th style="font-size:11px;color:var(--text-muted);text-transform:uppercase">Invoice</th>
                            <th class="text-end" style="font-size:11px;color:var(--text-muted);text-transform:uppercase">Tagihan</th>
                            <th class="text-end" style="font-size:11px;color:var(--text-muted);text-transform:uppercase">Dibayar</th>
                            <th class="text-end" style="font-size:11px;color:var(--text-muted);text-transform:uppercase">Sisa</th>
                            <th class="text-center" style="font-size:11px;color:var(--text-muted);text-transform:uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $statusMap = [
                                'belum_bayar' => ['bg'=>'var(--soft-danger-bg)','fg'=>'#dc2626','label'=>'Belum Bayar'],
                                'sebagian'    => ['bg'=>'rgba(59,130,246,.12)','fg'=>'#2563eb','label'=>'Sebagian'],
                                'lunas'       => ['bg'=>'var(--soft-success-bg)','fg'=>'#059669','label'=>'Lunas'],
                            ];
                        ?>
                        <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $st = $statusMap[$inv->status] ?? ['bg'=>'var(--soft-muted-bg)','fg'=>'#6b7280','label'=>ucfirst($inv->status)];
                            $dibayar = $inv->pembayaran->where('status','verified')->sum('jumlah');
                            $sisa = max(0, $inv->total - $dibayar);
                        ?>
                        <tr>
                            <td>
                                <div class="fw-semibold" style="font-size:12px"><?php echo e($inv->nomor_invoice); ?></div>
                                <div class="text-muted" style="font-size:11px"><?php echo e($inv->deskripsi); ?></div>
                                <?php if($inv->jatuh_tempo): ?>
                                <div class="text-muted" style="font-size:10px">Jatuh tempo: <?php echo e(\Carbon\Carbon::parse($inv->jatuh_tempo)->format('d M Y')); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="text-end fw-semibold">Rp <?php echo e(number_format($inv->total, 0, ',', '.')); ?></td>
                            <td class="text-end text-success fw-semibold">
                                <?php if($dibayar > 0): ?> Rp <?php echo e(number_format($dibayar, 0, ',', '.')); ?>

                                <?php else: ?> <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end fw-semibold">
                                <?php if($sisa > 0): ?>
                                    <span class="text-danger">Rp <?php echo e(number_format($sisa, 0, ',', '.')); ?></span>
                                <?php else: ?>
                                    <span class="text-success"><i class="bi bi-check"></i> Lunas</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <span style="background:<?php echo e($st['bg']); ?>;color:<?php echo e($st['fg']); ?>;padding:2px 10px;border-radius:20px;font-size:11px;font-weight:600">
                                    <?php echo e($st['label']); ?>

                                </span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot>
                        <tr style="background:var(--input-bg);font-weight:700">
                            <td>Total</td>
                            <td class="text-end">Rp <?php echo e(number_format($totalTagihan, 0, ',', '.')); ?></td>
                            <td class="text-end text-success">Rp <?php echo e(number_format($totalDibayar, 0, ',', '.')); ?></td>
                            <td class="text-end <?php echo e($sisaCicilan > 0 ? 'text-danger' : 'text-success'); ?>">
                                <?php if($sisaCicilan > 0): ?> Rp <?php echo e(number_format($sisaCicilan, 0, ',', '.')); ?>

                                <?php else: ?> <i class="bi bi-check-circle me-1"></i>Lunas
                                <?php endif; ?>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <?php endif; ?>

        
        <div class="dashboard-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-receipt text-primary me-2"></i>Riwayat Invoice</h6>
                <span class="badge bg-primary rounded-pill"><?php echo e($invoices->count()); ?> invoice</span>
            </div>

            <?php if($invoices->isEmpty()): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-receipt" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px"></i>
                    Belum ada invoice untuk kelas ini.
                </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr style="background:var(--input-bg)">
                            <th style="font-size:11px;color:var(--text-muted);text-transform:uppercase">No. Invoice</th>
                            <th style="font-size:11px;color:var(--text-muted);text-transform:uppercase">Deskripsi</th>
                            <th class="text-end" style="font-size:11px;color:var(--text-muted);text-transform:uppercase">Total</th>
                            <th class="text-center" style="font-size:11px;color:var(--text-muted);text-transform:uppercase">Status</th>
                            <th style="font-size:11px;color:var(--text-muted);text-transform:uppercase">Jatuh Tempo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $st = $statusMap[$inv->status] ?? ['bg'=>'var(--soft-muted-bg)','fg'=>'#6b7280','label'=>ucfirst($inv->status)]; ?>
                        <tr>
                            <td style="font-size:12px;font-weight:600"><?php echo e($inv->nomor_invoice); ?></td>
                            <td style="font-size:12px"><?php echo e($inv->deskripsi); ?></td>
                            <td class="text-end fw-semibold" style="font-size:13px">Rp <?php echo e(number_format($inv->total,0,',','.')); ?></td>
                            <td class="text-center">
                                <span style="background:<?php echo e($st['bg']); ?>;color:<?php echo e($st['fg']); ?>;padding:2px 10px;border-radius:20px;font-size:11px;font-weight:600">
                                    <?php echo e($st['label']); ?>

                                </span>
                            </td>
                            <td style="font-size:12px">
                                <?php echo e($inv->jatuh_tempo ? \Carbon\Carbon::parse($inv->jatuh_tempo)->format('d M Y') : '—'); ?>

                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

    
    <?php if($student): ?>
    <div class="col-lg-5">
        <div class="dashboard-card" style="position:sticky;top:80px">
            <div style="background:linear-gradient(135deg,#260632,#c84ddf);margin:-16px -16px 20px;padding:16px;border-radius:12px 12px 0 0;color:white">
                <h6 class="fw-bold mb-0"><i class="bi bi-plus-circle me-2"></i>Buat Invoice Baru</h6>
                <div style="font-size:12px;opacity:.8">Invoice akan muncul di billing siswa</div>
            </div>

            <form method="POST" action="<?php echo e(route('admin.tagihan-siswa.generate-invoice', $kelas->id)); ?>">
                <?php echo csrf_field(); ?>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Siswa</label>
                        <input type="text" class="form-control" value="<?php echo e($student->user?->name ?? $student->name); ?>" readonly
                               style="background:var(--input-bg)">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Paket Belajar</label>
                        <input type="text" class="form-control" value="<?php echo e($student->package?->nama ?? '—'); ?>" readonly
                               style="background:var(--input-bg)">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi Invoice <span class="text-danger">*</span></label>
                        <input type="text" name="deskripsi" class="form-control" required
                               value="<?php echo e(old('deskripsi', 'Tagihan Sesi – '.$kelas->nama_kelas)); ?>"
                               placeholder="cth: Tagihan Sesi Bulan Juli">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Nominal (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="total" class="form-control" required min="1000"
                                   value="<?php echo e(old('total', $student->package?->harga ?? '')); ?>"
                                   placeholder="500000">
                        </div>
                        <?php if($student->package?->harga): ?>
                        <div class="form-text">Harga paket: Rp <?php echo e(number_format($student->package->harga, 0, ',', '.')); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Jatuh Tempo <span class="text-danger">*</span></label>
                        <input type="date" name="jatuh_tempo" class="form-control" required
                               min="<?php echo e(date('Y-m-d')); ?>"
                               value="<?php echo e(old('jatuh_tempo', date('Y-m-d', strtotime('+7 days')))); ?>">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary w-100 fw-semibold">
                            <i class="bi bi-receipt me-2"></i>Terbitkan Invoice
                        </button>
                    </div>
                </div>
            </form>

            
            <div class="mt-4 pt-3 border-top">
                <div class="fw-semibold mb-2" style="font-size:13px">Ringkasan Pembayaran</div>
                <div class="text-muted" style="font-size:12px">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Total invoice</span>
                        <span class="fw-semibold"><?php echo e($invoices->count()); ?>x</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Total tagihan</span>
                        <span class="fw-semibold">Rp <?php echo e(number_format($totalTagihan, 0, ',', '.')); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Sudah dibayar</span>
                        <span class="fw-semibold text-success">Rp <?php echo e(number_format($totalDibayar, 0, ',', '.')); ?></span>
                    </div>
                    <div class="d-flex justify-content-between pt-1 border-top mt-1">
                        <span class="fw-semibold">Sisa cicilan</span>
                        <span class="fw-bold <?php echo e($sisaCicilan > 0 ? 'text-danger' : 'text-success'); ?>">
                            <?php if($sisaCicilan > 0): ?> Rp <?php echo e(number_format($sisaCicilan, 0, ',', '.')); ?>

                            <?php else: ?> <i class="bi bi-check-circle me-1"></i>Lunas
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/tagihan-siswa/show.blade.php ENDPATH**/ ?>