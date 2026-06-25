<?php $__env->startSection('title','Tagihan Saya'); ?>
<?php $__env->startSection('page-title','Tagihan Saya'); ?>

<?php $__env->startSection('content'); ?>


<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div class="d-flex align-items-center gap-3">
            <div style="width:52px;height:52px;border-radius:16px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0">
                <i class="bi bi-wallet2"></i>
            </div>
            <div>
                <div style="font-size:11px;opacity:.6;margin-bottom:3px;text-transform:uppercase;letter-spacing:.08em">
                    Tagihan &amp; Pembayaran
                </div>
                <h4 style="font-weight:800;margin-bottom:3px;color:white;letter-spacing:-.02em">
                    Tagihan Saya
                </h4>
                <p style="opacity:.65;margin:0;font-size:13px">
                    Lihat tagihan dan unggah bukti pembayaran mata pelajaran
                </p>
            </div>
        </div>
        <div style="font-size:64px;opacity:.08;line-height:1;flex-shrink:0">
            <i class="bi bi-receipt-cutoff"></i>
        </div>
    </div>
</div>


<?php
    $totalCourses  = $courses->count();
    $paidCount     = collect($payments)->filter(fn($p) => $p->status === 'verified')->count();
    $pendingCount  = collect($payments)->filter(fn($p) => $p->status === 'pending')->count();
    $unpaidCount   = $totalCourses - collect($payments)->count();
?>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 fade-up">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Mata Pelajaran</div>
                    <div class="stat-value text-primary"><?php echo e($totalCourses); ?></div>
                    <div class="stat-label text-muted" style="font-size:11px">terdaftar</div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Sudah Lunas</div>
                    <div class="stat-value text-success"><?php echo e($paidCount); ?></div>
                    <div class="stat-label" style="font-size:11px;color:#10b981">
                        <i class="bi bi-check-circle-fill me-1"></i>Terverifikasi
                    </div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Menunggu Verifikasi</div>
                    <div class="stat-value text-warning"><?php echo e($pendingCount); ?></div>
                    <div class="stat-label text-warning" style="font-size:11px">
                        <i class="bi bi-hourglass-split me-1"></i>Sedang diproses
                    </div>
                </div>
                <div class="stat-icon bg-warning-soft" style="color:white">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card" style="border-top:3px solid #ef4444">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Belum Dibayar</div>
                    <div class="stat-value" style="color:#dc2626"><?php echo e($unpaidCount); ?></div>
                    <div class="stat-label" style="font-size:11px;color:#ef4444">
                        <i class="bi bi-exclamation-circle me-1"></i>Segera bayar
                    </div>
                </div>
                <div class="stat-icon bg-danger-soft" style="color:white">
                    <i class="bi bi-exclamation-circle-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="dashboard-card fade-up">

    <?php if($draftCourses && $draftCourses->isNotEmpty()): ?>
    
    <div class="p-4 rounded-3 mb-4" style="background:linear-gradient(135deg,rgba(200,77,223,.08),rgba(104,17,126,.08));border:1.5px solid rgba(200,77,223,.2)">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h6 class="fw-bold mb-1" style="color:var(--text-primary)">
                    <i class="bi bi-cart-plus text-primary me-2"></i>Keranjang Pembayaran
                </h6>
                <p class="text-muted mb-0" style="font-size:12px">Mata pelajaran yang ditambahkan untuk dibayar</p>
            </div>
            <a href="<?php echo e(route('siswa.billing.index', ['clear_draft' => 1])); ?>" class="btn btn-outline-danger btn-sm" style="border-radius:10px">
                <i class="bi bi-trash me-1"></i>Hapus Semua
            </a>
        </div>

        <div class="row g-3">
            <?php $__currentLoopData = $draftCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $fee = $fees[$course->id] ?? 0;
                $payment = $payments[$course->id] ?? null;
            ?>
            <div class="col-md-6">
                <div class="p-3 rounded-3" style="background:var(--card-bg);border:1px solid var(--card-border)">
                    <div class="d-flex align-items-start justify-content-between gap-3 mb-2">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:40px;height:40px;border-radius:10px;background:rgba(200,77,223,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <i class="bi bi-journal-bookmark-fill text-primary" style="font-size:16px"></i>
                            </div>
                            <div>
                                <div class="fw-bold" style="font-size:13px;color:var(--text-primary)"><?php echo e($course->nama); ?></div>
                                <div class="text-muted" style="font-size:11px"><?php echo e($course->cabang->name ?? 'Pusat'); ?></div>
                            </div>
                        </div>
                        <a href="<?php echo e(route('siswa.billing.index', ['remove_course' => $course->id])); ?>" class="text-danger" style="font-size:18px" title="Hapus">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="fw-bold" style="font-size:15px;color:var(--primary)">
                            Rp <?php echo e(number_format($fee, 0, ',', '.')); ?>

                        </div>
                        <?php if(!$payment || $payment->status === 'rejected'): ?>
                        <button class="btn btn-primary btn-sm px-3"
                                data-bs-toggle="modal"
                                data-bs-target="#payModal<?php echo e($course->id); ?>"
                                style="border-radius:10px;font-size:12px">
                            <i class="bi bi-upload me-1"></i>Bayar
                        </button>
                        <?php elseif($payment->status === 'pending'): ?>
                        <span class="badge bg-warning" style="font-size:11px">
                            <i class="bi bi-hourglass-split me-1"></i>Menunggu
                        </span>
                        <?php else: ?>
                        <span class="badge bg-success" style="font-size:11px">
                            <i class="bi bi-check-circle me-1"></i>Lunas
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <?php $totalDraft = $draftCourses->sum(function($c) use ($fees) { return $fees[$c->id] ?? 0; }); ?>
        <div class="mt-3 pt-3 d-flex align-items-center justify-content-between" style="border-top:1px solid rgba(200,77,223,.2)">
            <span class="fw-bold" style="color:var(--primary)">Total: Rp <?php echo e(number_format($totalDraft, 0, ',', '.')); ?></span>
            <a href="<?php echo e(route('siswa.courses.fees')); ?>" class="btn btn-outline-primary btn-sm" style="border-radius:10px">
                <i class="bi bi-plus-lg me-1"></i>Tambah Mata Pelajaran
            </a>
        </div>

        
        <?php $__currentLoopData = $draftCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $fee = $fees[$course->id] ?? 0; ?>
        <div class="modal fade" id="payModal<?php echo e($course->id); ?>" tabindex="-1" aria-labelledby="payLabel<?php echo e($course->id); ?>">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius:20px;border:none;overflow:hidden">
                    <div class="modal-header border-0 p-4"
                         style="background:linear-gradient(135deg,#260632,#68117e);color:white">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:40px;height:40px;border-radius:10px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center">
                                <i class="bi bi-upload"></i>
                            </div>
                            <div>
                                <h6 class="modal-title fw-bold mb-0" id="payLabel<?php echo e($course->id); ?>">Upload Bukti Pembayaran</h6>
                                <div style="font-size:12px;opacity:.75"><?php echo e($course->nama); ?></div>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="<?php echo e(route('siswa.billing.pay', $course->id)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body p-4">
                            <div class="p-3 rounded-3 mb-4"
                                 style="background:var(--soft-primary-bg);border:1px solid var(--soft-primary-border)">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span style="font-size:13px;color:var(--soft-primary-text);font-weight:600">
                                        <i class="bi bi-tag me-1"></i>Total Pembayaran
                                    </span>
                                    <span style="font-size:16px;font-weight:800;color:var(--primary)">
                                        <?php if($fee > 0): ?> Rp <?php echo e(number_format($fee, 0, ',', '.')); ?>

                                        <?php else: ?> <span class="text-success">Gratis</span>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="font-size:13px">
                                    Deskripsi Pembayaran <span class="text-muted">(opsional)</span>
                                </label>
                                <input type="text" name="catatan" class="form-control" placeholder="cth: Transfer BCA atas nama Budi / no. ref: 12345"
                                       style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="font-size:13px">
                                    Bukti Pembayaran <span class="text-danger">*</span>
                                </label>
                                <input type="file" name="proof" class="form-control" required
                                       accept=".jpg,.jpeg,.png,.pdf"
                                       style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                                <div class="form-text">Format: JPG, PNG, atau PDF. Maks 5MB.</div>
                            </div>

                            <div class="p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                                <div style="font-size:12px;color:var(--text-muted);line-height:1.6">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Bukti pembayaran akan diverifikasi oleh admin dalam 1×24 jam.
                                    Pastikan foto/scan jelas dan terbaca.
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-4 pt-0 gap-2">
                            <button type="button" class="btn btn-outline-secondary"
                                    data-bs-dismiss="modal" style="border-radius:10px">
                                <i class="bi bi-x me-1"></i>Batal
                            </button>
                            <button type="submit" class="btn btn-primary px-4" style="border-radius:10px">
                                <i class="bi bi-upload me-2"></i>Kirim Bukti
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h6 class="fw-bold mb-1" style="color:var(--text-primary)">
                <i class="bi bi-receipt text-primary me-2"></i>Daftar Tagihan
            </h6>
            <p class="text-muted mb-0" style="font-size:12px">Unggah bukti pembayaran untuk setiap mata pelajaran</p>
        </div>
    </div>

    <?php if($courses->isEmpty()): ?>
    <div class="text-center py-5">
        <div style="width:80px;height:80px;border-radius:50%;background:var(--input-bg);display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
            <i class="bi bi-journal-x text-muted" style="font-size:2rem;opacity:.5"></i>
        </div>
        <h6 class="fw-semibold mb-2" style="color:var(--text-primary)">Belum Ada Tagihan</h6>
        <p class="text-muted mb-0" style="font-size:13px">Anda belum terdaftar di mata pelajaran apapun.</p>
    </div>
    <?php else: ?>
    <?php
        $enrolledCourses = $courses->filter(function($c) use ($draftCourses) {
            return !$draftCourses || !$draftCourses->contains('id', $c->id);
        });
    ?>
    <?php if($enrolledCourses->isEmpty()): ?>
    <div class="text-center py-4">
        <p class="text-muted mb-0" style="font-size:13px">Tidak ada tagihan lain selain yang ada di keranjang.</p>
    </div>
    <?php else: ?>
    <div class="row g-3">
        <?php $__currentLoopData = $enrolledCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $payment  = $payments[$course->id] ?? null;
            $fee      = $fees[$course->id] ?? 0;
            $statusMap = [
                'verified' => ['bg'=>'var(--soft-success-bg)','color'=>'var(--soft-success-text)','icon'=>'bi-check-circle-fill','label'=>'Lunas'],
                'pending'  => ['bg'=>'var(--soft-warning-bg)','color'=>'var(--soft-warning-text)','icon'=>'bi-hourglass-split','label'=>'Menunggu Verifikasi'],
                'rejected' => ['bg'=>'var(--soft-danger-bg)','color'=>'var(--soft-danger-text)','icon'=>'bi-x-circle-fill','label'=>'Ditolak'],
            ];
            $badge = $payment ? ($statusMap[$payment->status] ?? $statusMap['pending']) : null;
        ?>
        <div class="col-md-6">
            <div class="p-4 rounded-3 h-100" id="billing-card-<?php echo e($course->id); ?>"
                 style="background:var(--input-bg);border:1.5px solid var(--card-border)">
                <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:44px;height:44px;border-radius:12px;background:rgba(200,77,223,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i class="bi bi-journal-bookmark-fill text-primary" style="font-size:18px"></i>
                        </div>
                        <div>
                            <div class="fw-bold" style="font-size:14px;color:var(--text-primary)"><?php echo e($course->nama); ?></div>
                            <div class="text-muted" style="font-size:12px">
                                <?php if($course->deskripsi): ?>
                                    <?php echo e(Str::limit($course->deskripsi, 40)); ?>

                                <?php else: ?>
                                    Mata pelajaran
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php if($badge): ?>
                    <span class="badge flex-shrink-0"
                          style="background:<?php echo e($badge['bg']); ?>;color:<?php echo e($badge['color']); ?>;padding:5px 11px;border-radius:8px;font-size:11px;font-weight:600;white-space:nowrap">
                        <i class="bi <?php echo e($badge['icon']); ?> me-1"></i><?php echo e($badge['label']); ?>

                    </span>
                    <?php else: ?>
                    <span class="badge flex-shrink-0"
                          style="background:var(--soft-danger-bg);color:var(--soft-danger-text);padding:5px 11px;border-radius:8px;font-size:11px;font-weight:600;white-space:nowrap">
                        <i class="bi bi-clock me-1"></i>Belum Bayar
                    </span>
                    <?php endif; ?>
                </div>

                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted" style="font-size:11px;margin-bottom:2px">Biaya</div>
                        <div class="fw-bold" style="font-size:16px;color:<?php echo e($payment && $payment->status==='verified' ? '#059669' : 'var(--text-primary)'); ?>">
                            <?php if($fee > 0): ?>
                                Rp <?php echo e(number_format($fee, 0, ',', '.')); ?>

                            <?php else: ?>
                                <span class="text-success">Gratis</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if(!$payment || $payment->status === 'rejected'): ?>
                        <button class="btn btn-primary btn-sm px-3"
                                data-bs-toggle="modal"
                                data-bs-target="#payModal<?php echo e($course->id); ?>"
                                style="border-radius:10px;font-size:12.5px">
                            <i class="bi bi-upload me-1"></i>
                            <?php echo e($payment && $payment->status==='rejected' ? 'Upload Ulang' : 'Bayar Sekarang'); ?>

                        </button>
                    <?php elseif($payment->status === 'pending'): ?>
                        <button class="btn btn-sm" disabled
                                style="background:var(--soft-warning-bg);color:var(--soft-warning-text);border:none;border-radius:10px;font-size:12.5px">
                            <i class="bi bi-hourglass me-1"></i>Menunggu
                        </button>
                    <?php else: ?>
                        <button class="btn btn-sm" disabled
                                style="background:var(--soft-success-bg);color:var(--soft-success-text);border:none;border-radius:10px;font-size:12.5px">
                            <i class="bi bi-check2 me-1"></i>Lunas
                        </button>
                    <?php endif; ?>
                </div>

                <?php if($payment && $payment->status === 'rejected'): ?>
                <div class="mt-2 p-2 rounded-2" style="background:var(--soft-danger-bg);border:1px solid var(--soft-danger-border)">
                    <div style="font-size:11.5px;color:var(--soft-danger-text)">
                        <i class="bi bi-info-circle me-1"></i>Bukti pembayaran ditolak. Silakan upload ulang.
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="modal fade" id="payModal<?php echo e($course->id); ?>" tabindex="-1" aria-labelledby="payLabel<?php echo e($course->id); ?>">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius:20px;border:none;overflow:hidden">
                    <div class="modal-header border-0 p-4"
                         style="background:linear-gradient(135deg,#260632,#68117e);color:white">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:40px;height:40px;border-radius:10px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center">
                                <i class="bi bi-upload"></i>
                            </div>
                            <div>
                                <h6 class="modal-title fw-bold mb-0" id="payLabel<?php echo e($course->id); ?>">Upload Bukti Pembayaran</h6>
                                <div style="font-size:12px;opacity:.75"><?php echo e($course->nama); ?></div>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="<?php echo e(route('siswa.billing.pay', $course->id)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body p-4">
                            <div class="p-3 rounded-3 mb-4"
                                 style="background:var(--soft-primary-bg);border:1px solid var(--soft-primary-border)">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span style="font-size:13px;color:var(--soft-primary-text);font-weight:600">
                                        <i class="bi bi-tag me-1"></i>Total Pembayaran
                                    </span>
                                    <span style="font-size:16px;font-weight:800;color:var(--primary)">
                                        <?php if($fee > 0): ?> Rp <?php echo e(number_format($fee, 0, ',', '.')); ?>

                                        <?php else: ?> <span class="text-success">Gratis</span>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="font-size:13px">
                                    Deskripsi Pembayaran <span class="text-muted">(opsional)</span>
                                </label>
                                <input type="text" name="catatan" class="form-control" placeholder="cth: Transfer BCA atas nama Budi / no. ref: 12345"
                                       style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="font-size:13px">
                                    Bukti Pembayaran <span class="text-danger">*</span>
                                </label>
                                <input type="file" name="proof" class="form-control" required
                                       accept=".jpg,.jpeg,.png,.pdf"
                                       style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                                <div class="form-text">Format: JPG, PNG, atau PDF. Maks 5MB.</div>
                            </div>

                            <div class="p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                                <div style="font-size:12px;color:var(--text-muted);line-height:1.6">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Bukti pembayaran akan diverifikasi oleh admin dalam 1×24 jam.
                                    Pastikan foto/scan jelas dan terbaca.
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-4 pt-0 gap-2">
                            <button type="button" class="btn btn-outline-secondary"
                                    data-bs-dismiss="modal" style="border-radius:10px">
                                <i class="bi bi-x me-1"></i>Batal
                            </button>
                            <button type="submit" class="btn btn-primary px-4" style="border-radius:10px">
                                <i class="bi bi-upload me-2"></i>Kirim Bukti
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>

</div>


<?php $__currentLoopData = $invoices ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php
    $invSc = 'lunas' === $inv->status
        ? ['bg'=>'var(--soft-success-bg)','color'=>'var(--soft-success-text)','label'=>'Lunas','icon'=>'bi-check-circle-fill']
        : ('sebagian' === $inv->status
            ? ['bg'=>'var(--soft-warning-bg)','color'=>'var(--soft-warning-text)','label'=>'Sebagian Bayar','icon'=>'bi-dash-circle-fill']
            : ['bg'=>'var(--soft-danger-bg)','color'=>'var(--soft-danger-text)','label'=>'Belum Bayar','icon'=>'bi-x-circle-fill']);
    $invOverdue  = $inv->status !== 'lunas' && $inv->jatuh_tempo && $inv->jatuh_tempo->isPast();
    $invPending  = \App\Models\Payment::where('invoice_id', $inv->id)->where('status','pending')->exists();
    $invPaid     = $inv->status === 'sebagian'
        ? \App\Models\Payment::where('invoice_id', $inv->id)->where('status','verified')->sum('jumlah')
        : 0;
    $invBorderColor = ($invOverdue && $inv->status !== 'lunas') ? '#fca5a5' : 'var(--card-border)';
?>
<div class="dashboard-card fade-up mt-3">
    <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
        <div>
            <div class="fw-bold" style="font-size:14px;color:var(--text-primary)"><?php echo e($inv->deskripsi ?? 'Tagihan Program'); ?></div>
            <div class="text-muted" style="font-size:11px;font-family:monospace"><?php echo e($inv->nomor_invoice ?? '—'); ?></div>
        </div>
        <span class="badge flex-shrink-0" style="background:<?php echo e($invSc['bg']); ?>;color:<?php echo e($invSc['color']); ?>;padding:4px 10px;border-radius:8px;font-size:11px;font-weight:600">
            <i class="bi <?php echo e($invSc['icon']); ?> me-1"></i><?php echo e($invSc['label']); ?>

        </span>
    </div>
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <div class="fw-bold" style="font-size:20px;color:<?php echo e($inv->status === 'lunas' ? '#059669' : 'var(--text-primary)'); ?>">
                Rp <?php echo e(number_format($inv->total, 0, ',', '.')); ?>

            </div>
            <div class="text-muted" style="font-size:11px;margin-top:2px">
                Jatuh tempo: <?php echo e($inv->jatuh_tempo?->format('d M Y') ?? '—'); ?>

                <?php if($invOverdue): ?>
                    &nbsp;<i class="bi bi-exclamation-triangle-fill" style="color:#dc2626"></i>
                    <span style="color:#dc2626">Lewat jatuh tempo</span>
                <?php endif; ?>
            </div>
        </div>
        <?php if($inv->status === 'lunas'): ?>
            <span class="btn btn-sm" disabled style="background:var(--soft-success-bg);color:var(--soft-success-text);border:none;border-radius:10px;font-size:12px">
                <i class="bi bi-check2 me-1"></i>Lunas
            </span>
        <?php elseif($invPending): ?>
            <span class="btn btn-sm" disabled style="background:var(--soft-warning-bg);color:var(--soft-warning-text);border:none;border-radius:10px;font-size:12px">
                <i class="bi bi-hourglass me-1"></i>Menunggu
            </span>
        <?php else: ?>
            <button class="btn btn-primary btn-sm px-3"
                    data-bs-toggle="modal"
                    data-bs-target="#invPayModal<?php echo e($inv->id); ?>"
                    style="border-radius:10px;font-size:12.5px">
                <i class="bi bi-upload me-1"></i>Bayar Sekarang
            </button>
        <?php endif; ?>
    </div>
    <?php if($inv->status === 'sebagian'): ?>
    <div class="mt-3 pt-3" style="border-top:1px solid var(--card-border)">
        <div class="d-flex justify-content-between mb-1" style="font-size:12px;color:var(--text-muted)">
            <span>Sudah dibayar</span>
            <span class="text-success fw-semibold">Rp <?php echo e(number_format($invPaid,0,',','.')); ?></span>
        </div>
        <div class="d-flex justify-content-between" style="font-size:12px;color:var(--text-muted)">
            <span>Sisa tagihan</span>
            <span class="fw-semibold" style="color:#dc2626">Rp <?php echo e(number_format($inv->total - $invPaid,0,',','.')); ?></span>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php if($inv->status !== 'lunas' && !$invPending): ?>
<div class="modal fade" id="invPayModal<?php echo e($inv->id); ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:20px;border:none;overflow:hidden">
            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#260632,#68117e);color:white">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:40px;height:40px;border-radius:10px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center">
                        <i class="bi bi-upload"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold mb-0">Upload Bukti Pembayaran</h6>
                        <div style="font-size:12px;opacity:.75"><?php echo e($inv->deskripsi ?? $inv->nomor_invoice); ?></div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('siswa.billing.invoice-upload', $inv)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-body p-4">
                    <div class="p-3 rounded-3 mb-3" style="background:var(--soft-primary-bg);border:1px solid var(--soft-primary-border)">
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="font-size:13px;color:var(--soft-primary-text);font-weight:600">
                                <i class="bi bi-tag me-1"></i>Total Tagihan
                            </span>
                            <span style="font-size:16px;font-weight:800;color:var(--primary)">
                                Rp <?php echo e(number_format($inv->total, 0, ',', '.')); ?>

                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px">Jumlah Dibayar <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah" class="form-control" required
                               value="<?php echo e($inv->total); ?>" min="1000"
                               style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px">Metode Pembayaran <span class="text-danger">*</span></label>
                        <select name="metode" class="form-select" required style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                            <option value="transfer">Transfer Bank</option>
                            <option value="cash">Cash</option>
                            <option value="qris">QRIS</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px">Bukti Pembayaran <span class="text-danger">*</span></label>
                        <input type="file" name="bukti_pembayaran" class="form-control" required
                               accept=".jpg,.jpeg,.png,.pdf"
                               style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                        <div class="form-text">Format: JPG, PNG, atau PDF. Maks 5MB.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px">Catatan <span class="text-muted">(opsional)</span></label>
                        <input type="text" name="catatan" class="form-control"
                               placeholder="cth: Transfer BCA atas nama Budi"
                               style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0 gap-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius:10px">
                        <i class="bi bi-x me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary px-4" style="border-radius:10px">
                        <i class="bi bi-upload me-2"></i>Kirim Bukti
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/siswa/billing/index.blade.php ENDPATH**/ ?>