<?php $__env->startSection('title','Pembayaran Saya'); ?>
<?php $__env->startSection('page-title','Pembayaran Guru'); ?>

<?php $__env->startSection('content'); ?>

<?php
    $teacher = $teacher ?? null;
?>

<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div class="d-flex align-items-center gap-3">
            <div style="width:52px;height:52px;border-radius:16px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div>
                <div style="font-size:11px;opacity:.6;margin-bottom:3px;text-transform:uppercase;letter-spacing:.08em">Riwayat Gaji</div>
                <h4 class="fw-bold mb-1" style="color:white;letter-spacing:-.02em">Pembayaran Saya</h4>
                <p class="mb-0" style="opacity:.75;font-size:13px">
                    <?php echo e($teacher?->name ?? '-'); ?> · <?php echo e($teacher?->branch?->name ?? 'Pusat'); ?>

                </p>
            </div>
        </div>
        <div style="font-size:64px;opacity:.08;line-height:1;flex-shrink:0">
            <i class="bi bi-wallet2"></i>
        </div>
    </div>
</div>


<?php
    $totalDibayar  = $salaries->where('status','dibayar')->sum('total_gaji');
    $totalPending  = $salaries->where('status','pending')->count();
    $countDibayar  = $salaries->where('status','dibayar')->count();
?>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 fade-up">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Diterima</div>
                    <div class="stat-value text-success" style="font-size:17px">Rp <?php echo e(number_format($totalDibayar,0,',','.')); ?></div>
                    <div class="stat-label text-muted" style="font-size:11px">kumulatif</div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-cash-coin"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Riwayat Pembayaran</div>
                    <div class="stat-value text-primary"><?php echo e($countDibayar); ?></div>
                    <div class="stat-label text-muted" style="font-size:11px">kali dibayar</div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-receipt"></i></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Menunggu Proses</div>
                    <div class="stat-value text-warning"><?php echo e($totalPending); ?></div>
                    <div class="stat-label text-muted" style="font-size:11px">pending</div>
                </div>
                <div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-hourglass-split"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-card fade-up">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="thead-modern">
                <tr>
                    <th>Periode</th>
                    <th>Tipe Gaji</th>
                    <th>Gaji Pokok</th>
                    <th>Bonus</th>
                    <th>Total Gaji</th>
                    <th>Status</th>
                    <th>Metode</th>
                    <th>Tanggal Bayar</th>
                    <th>Nama Bank</th>
                    <th>No. Rekening</th>
                    <th>Bukti</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $salaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="fw-semibold"><?php echo e($s->periode); ?></td>
                    <td><span class="badge" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);border:1px solid var(--soft-primary-border);font-size:11px"><?php echo e($s->tipe_gaji === 'freelance' ? 'Freelance' : 'Bulanan'); ?></span></td>
                    <td>Rp <?php echo e(number_format($s->gaji_pokok,0,',','.')); ?></td>
                    <td><?php echo e($s->bonus > 0 ? 'Rp '.number_format($s->bonus,0,',','.') : '—'); ?></td>
                    <td class="fw-bold text-primary">Rp <?php echo e(number_format($s->total_gaji,0,',','.')); ?></td>
                    <td>
                        <?php if($s->status === 'dibayar'): ?>
                            <span class="badge" style="background:rgba(16,185,129,.15);color:#059669;border:1px solid rgba(16,185,129,.2);border-radius:8px;padding:5px 10px">
                                <i class="bi bi-check-circle-fill me-1"></i>Dibayar
                            </span>
                        <?php elseif($s->status === 'pending'): ?>
                            <span class="badge" style="background:rgba(246,175,35,.15);color:#b45309;border:1px solid rgba(246,175,35,.2);border-radius:8px;padding:5px 10px">
                                <i class="bi bi-clock-fill me-1"></i>Pending
                            </span>
                        <?php else: ?>
                            <span class="badge" style="background:rgba(239,68,68,.15);color:#dc2626;border:1px solid rgba(239,68,68,.2);border-radius:8px;padding:5px 10px">
                                <i class="bi bi-x-circle-fill me-1"></i>Batal
                            </span>
                        <?php endif; ?>
                    </td>
                    <td><span style="font-size:13px"><?php echo e($s->metode_pembayaran ?? '—'); ?></span></td>
                    <td><?php echo e($s->tanggal_pembayaran?->format('d M Y') ?? '—'); ?></td>
                    <td style="font-size:13px"><?php echo e($s->nama_bank ?? '—'); ?></td>
                    <td style="font-size:13px"><?php echo e($s->nomor_rekening ?? '—'); ?></td>
                    <td>
                        <?php if($s->bukti_pembayaran): ?>
                            <a href="<?php echo e(asset('storage/'.$s->bukti_pembayaran)); ?>" target="_blank"
                               class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:12px">
                                <i class="bi bi-eye me-1"></i>Lihat
                            </a>
                        <?php else: ?>
                            <span class="text-muted" style="font-size:12px">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="11">
                        <div class="empty-state py-4">
                            <div class="empty-state-icon"><i class="bi bi-receipt-cutoff"></i></div>
                            <div class="empty-state-title">Belum Ada Pembayaran</div>
                            <div class="empty-state-desc">Riwayat pembayaran gaji akan muncul di sini setelah admin memproses.</div>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($salaries->hasPages()): ?>
    <div class="mt-3"><?php echo e($salaries->links()); ?></div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Edu Juanda Pratama\Downloads\smart-center-indonesia-1 (2)\smart-center-indonesia\resources\views/guru/payments/index.blade.php ENDPATH**/ ?>