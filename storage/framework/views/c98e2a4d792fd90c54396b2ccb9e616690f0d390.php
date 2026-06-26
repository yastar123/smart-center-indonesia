<?php $__env->startSection('title', 'Detail Invoice'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">

    
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="font-size:13px">
            <li class="breadcrumb-item"><a href="<?php echo e(route('siswa.billing.index')); ?>">Tagihan Saya</a></li>
            <li class="breadcrumb-item active">Detail Invoice</li>
        </ol>
    </nav>

    <?php if(session('success')): ?>
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4" style="border-radius:12px">
        <i class="bi bi-check-circle-fill"></i><?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
    <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" style="border-radius:12px">
        <i class="bi bi-exclamation-circle-fill"></i><?php echo e(session('error')); ?>

    </div>
    <?php endif; ?>

    <div class="row g-4">
        
        <div class="col-12 col-lg-7">
            
            <div class="dashboard-card mb-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="fw-bold mb-0" style="font-size:14px;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted)">
                        <i class="bi bi-receipt me-2 text-primary"></i>Invoice
                    </h6>
                    <?php
                        $statusColors = [
                            'belum_bayar'=> ['rgba(239,68,68,.1)','#ef4444','Belum Bayar'],
                            'sebagian'   => ['rgba(245,158,11,.1)','#f59e0b','Sebagian'],
                            'lunas'      => ['rgba(16,185,129,.1)','#10b981','Lunas'],
                        ];
                        $sc = $statusColors[$invoice->status] ?? ['rgba(100,116,139,.1)','#64748b',$invoice->status];
                    ?>
                    <span style="background:<?php echo e($sc[0]); ?>;color:<?php echo e($sc[1]); ?>;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600">
                        <?php echo e($sc[2]); ?>

                    </span>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Nomor Invoice</div>
                        <div class="fw-semibold" style="font-size:13px;font-family:monospace"><?php echo e($invoice->nomor_invoice ?? '#'.$invoice->id); ?></div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Jatuh Tempo</div>
                        <div class="fw-semibold" style="font-size:13px;<?php echo e($invoice->jatuh_tempo && now()->toDateString() > $invoice->jatuh_tempo && $invoice->status !== 'lunas' ? 'color:#ef4444' : ''); ?>">
                            <?php echo e($invoice->jatuh_tempo ? \Carbon\Carbon::parse($invoice->jatuh_tempo)->isoFormat('D MMMM YYYY') : '—'); ?>

                            <?php if($invoice->jatuh_tempo && now()->toDateString() > $invoice->jatuh_tempo && $invoice->status !== 'lunas'): ?>
                            <span style="font-size:10px;background:rgba(239,68,68,.1);color:#ef4444;padding:1px 6px;border-radius:10px;margin-left:4px">Terlambat</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Total Tagihan</div>
                        <div class="fw-bold" style="font-size:20px;color:var(--primary)">Rp <?php echo e(number_format($invoice->total ?? 0, 0, ',', '.')); ?></div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Sudah Dibayar</div>
                        <div class="fw-bold" style="font-size:20px;color:#10b981">Rp <?php echo e(number_format($invoice->jumlah_terbayar, 0, ',', '.')); ?></div>
                    </div>
                    <?php $sisa = ($invoice->total ?? 0) - $invoice->jumlah_terbayar; ?>
                    <?php if($sisa > 0): ?>
                    <div class="col-12">
                        <div class="p-3 rounded-3" style="background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.15)">
                            <div class="text-muted mb-1" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Sisa Tagihan</div>
                            <div class="fw-bold" style="font-size:22px;color:#ef4444">Rp <?php echo e(number_format($sisa, 0, ',', '.')); ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if($invoice->keterangan): ?>
                    <div class="col-12">
                        <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Keterangan</div>
                        <div style="font-size:13px"><?php echo e($invoice->keterangan); ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <?php if($invoice->schoolClass): ?>
            <div class="dashboard-card mb-4">
                <h6 class="fw-bold mb-3" style="font-size:14px;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted)">
                    <i class="bi bi-journal-bookmark me-2 text-primary"></i>Kelas
                </h6>
                <div class="d-flex align-items-center gap-3">
                    <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#260632,#c84ddf);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i class="bi bi-mortarboard-fill" style="color:white;font-size:18px"></i>
                    </div>
                    <div>
                        <div class="fw-semibold" style="font-size:14px"><?php echo e($invoice->schoolClass->nama_kelas); ?></div>
                        <div class="text-muted" style="font-size:12px">
                            <?php echo e($invoice->schoolClass->mataPelajaran?->nama ?? '—'); ?>

                            <?php if($invoice->schoolClass->cabang): ?>
                            &nbsp;·&nbsp; <?php echo e($invoice->schoolClass->cabang->name); ?>

                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            
            <div class="dashboard-card">
                <h6 class="fw-bold mb-3" style="font-size:14px;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted)">
                    <i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Pembayaran
                </h6>
                <?php if($payments->isEmpty()): ?>
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-receipt" style="font-size:36px;opacity:.2;display:block;margin-bottom:8px"></i>
                    Belum ada pembayaran yang diunggah.
                </div>
                <?php else: ?>
                <div class="d-flex flex-column gap-3">
                    <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $psc = ['pending'=>['rgba(245,158,11,.1)','#f59e0b','Menunggu'],'verified'=>['rgba(16,185,129,.1)','#10b981','Disetujui'],'rejected'=>['rgba(239,68,68,.1)','#ef4444','Ditolak']][$pay->status] ?? ['rgba(100,116,139,.1)','#64748b',$pay->status];
                    ?>
                    <div class="p-3 rounded-3" style="border:1px solid var(--card-border);background:var(--input-bg)">
                        <div class="d-flex align-items-start justify-content-between mb-2">
                            <div>
                                <div class="fw-semibold" style="font-size:14px">Rp <?php echo e(number_format($pay->jumlah, 0, ',', '.')); ?></div>
                                <div class="text-muted" style="font-size:12px">
                                    <?php echo e($pay->tanggal_pembayaran ? \Carbon\Carbon::parse($pay->tanggal_pembayaran)->isoFormat('D MMM YYYY') : '—'); ?>

                                    &nbsp;·&nbsp; <?php echo e(ucfirst($pay->metode ?? '—')); ?>

                                    <?php if($pay->nama_bank): ?> &nbsp;·&nbsp; <?php echo e($pay->nama_bank); ?> <?php endif; ?>
                                </div>
                            </div>
                            <span style="background:<?php echo e($psc[0]); ?>;color:<?php echo e($psc[1]); ?>;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;flex-shrink:0">
                                <?php echo e($psc[2]); ?>

                            </span>
                        </div>
                        <?php if($pay->alasan_penolakan): ?>
                        <div class="mt-2 p-2 rounded-2" style="background:rgba(239,68,68,.06);font-size:12px;color:#ef4444">
                            <i class="bi bi-info-circle me-1"></i><?php echo e($pay->alasan_penolakan); ?>

                        </div>
                        <?php endif; ?>
                        <?php if($pay->bukti_pembayaran): ?>
                        <div class="mt-2">
                            <a href="<?php echo e(asset('storage/'.$pay->bukti_pembayaran)); ?>" target="_blank"
                                style="font-size:12px;color:var(--primary)" class="text-decoration-none">
                                <i class="bi bi-image me-1"></i>Lihat Bukti
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="col-12 col-lg-5">
            <?php if($invoice->status !== 'lunas'): ?>
            <div class="dashboard-card">
                <h6 class="fw-bold mb-4" style="font-size:14px;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted)">
                    <i class="bi bi-upload me-2 text-primary"></i>Upload Bukti Pembayaran
                </h6>
                <form method="POST" action="<?php echo e(route('siswa.billing.invoice-upload', $invoice)); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px">Jumlah Dibayar <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="jumlah" class="form-control" required min="1000"
                                value="<?php echo e(old('jumlah', $sisa > 0 ? $sisa : '')); ?>"
                                placeholder="0">
                        </div>
                        <?php $__errorArgs = ['jumlah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger" style="font-size:12px"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px">Metode Pembayaran <span class="text-danger">*</span></label>
                        <select name="metode" class="form-select" required>
                            <option value="">— Pilih Metode —</option>
                            <option value="transfer" <?php echo e(old('metode')=='transfer' ? 'selected' : ''); ?>>Transfer Bank</option>
                            <option value="cash" <?php echo e(old('metode')=='cash' ? 'selected' : ''); ?>>Cash / Tunai</option>
                            <option value="qris" <?php echo e(old('metode')=='qris' ? 'selected' : ''); ?>>QRIS</option>
                            <option value="lainnya" <?php echo e(old('metode')=='lainnya' ? 'selected' : ''); ?>>Lainnya</option>
                        </select>
                        <?php $__errorArgs = ['metode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger" style="font-size:12px"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px">Nama Bank / E-Wallet</label>
                        <input type="text" name="nama_bank" class="form-control" value="<?php echo e(old('nama_bank')); ?>"
                            placeholder="Contoh: BCA, Mandiri, OVO...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px">Nomor Rekening / Akun</label>
                        <input type="text" name="nomor_rekening" class="form-control" value="<?php echo e(old('nomor_rekening')); ?>"
                            placeholder="Nomor rekening pengirim">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px">Bukti Pembayaran</label>
                        <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*,.pdf">
                        <div class="form-text">JPG, PNG, atau PDF. Maks 5MB.</div>
                        <?php $__errorArgs = ['bukti_pembayaran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger" style="font-size:12px"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:13px">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan tambahan (opsional)"><?php echo e(old('catatan')); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-semibold" style="border-radius:10px">
                        <i class="bi bi-upload me-2"></i>Kirim Bukti Pembayaran
                    </button>
                </form>
            </div>
            <?php else: ?>
            <div class="dashboard-card text-center py-5">
                <i class="bi bi-check-circle-fill" style="font-size:48px;color:#10b981;display:block;margin-bottom:12px"></i>
                <div class="fw-bold mb-1" style="font-size:16px">Invoice Lunas</div>
                <div class="text-muted" style="font-size:13px">Pembayaran untuk invoice ini sudah selesai.</div>
                <a href="<?php echo e(route('siswa.billing.index')); ?>" class="btn btn-outline-primary mt-3">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Tagihan
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/siswa/billing/invoice-detail.blade.php ENDPATH**/ ?>