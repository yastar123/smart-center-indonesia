<?php $__env->startSection('title', 'Tinjau Pembayaran'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">

    
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="font-size:13px">
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.verifikasi-pembayaran.index')); ?>">Verifikasi Pembayaran</a></li>
            <li class="breadcrumb-item active"><?php echo e($payment->nomor_pembayaran ?? 'Detail'); ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        
        <div class="col-12 col-lg-7">
            <div class="dashboard-card mb-4">
                <h6 class="fw-bold mb-4" style="font-size:14px;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted)">
                    <i class="bi bi-receipt me-2 text-primary"></i>Informasi Pembayaran
                </h6>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">No Pembayaran</div>
                        <div class="fw-semibold" style="font-size:13px;font-family:monospace"><?php echo e($payment->nomor_pembayaran ?? '—'); ?></div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Status</div>
                        <?php
                            $sc = ['pending'=>['rgba(245,158,11,.1)','#f59e0b','Menunggu'],'verified'=>['rgba(16,185,129,.1)','#10b981','Disetujui'],'rejected'=>['rgba(239,68,68,.1)','#ef4444','Ditolak']][$payment->status] ?? ['rgba(100,116,139,.1)','#64748b',$payment->status];
                        ?>
                        <span style="background:<?php echo e($sc[0]); ?>;color:<?php echo e($sc[1]); ?>;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600"><?php echo e($sc[2]); ?></span>
                    </div>
                    <div class="col-6">
                        <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Jumlah</div>
                        <div class="fw-bold" style="font-size:20px;color:var(--primary)">Rp <?php echo e(number_format($payment->jumlah, 0, ',', '.')); ?></div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Tanggal Bayar</div>
                        <div class="fw-semibold" style="font-size:13px"><?php echo e($payment->tanggal_pembayaran ? \Carbon\Carbon::parse($payment->tanggal_pembayaran)->isoFormat('D MMMM YYYY') : '—'); ?></div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Metode</div>
                        <div class="fw-semibold" style="font-size:13px;text-transform:capitalize"><?php echo e($payment->metode ?? '—'); ?></div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Bank / Rek</div>
                        <div style="font-size:13px"><?php echo e($payment->nama_bank ?? '—'); ?> <?php echo e($payment->nomor_rekening ? '('.$payment->nomor_rekening.')' : ''); ?></div>
                    </div>
                    <?php if($payment->catatan): ?>
                    <div class="col-12">
                        <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Catatan Siswa</div>
                        <div style="font-size:13px"><?php echo e($payment->catatan); ?></div>
                    </div>
                    <?php endif; ?>
                    <?php if($payment->alasan_penolakan): ?>
                    <div class="col-12">
                        <div class="text-muted mb-1" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Alasan Penolakan</div>
                        <div class="p-3 rounded-3" style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);font-size:13px;color:#ef4444">
                            <?php echo e($payment->alasan_penolakan); ?>

                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="dashboard-card">
                <h6 class="fw-bold mb-3" style="font-size:14px;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted)">
                    <i class="bi bi-person me-2 text-primary"></i>Data Siswa
                </h6>
                <div class="d-flex align-items-center gap-3">
                    <?php $s = $payment->siswa; ?>
                    <?php if($s): ?>
                    <img src="<?php echo e($s->photo ? asset('storage/'.$s->photo) : 'https://ui-avatars.com/api/?name='.urlencode($s->name).'&background=c84ddf&color=fff'); ?>"
                        style="width:52px;height:52px;border-radius:50%;object-fit:cover" alt="<?php echo e($s->name); ?>">
                    <div>
                        <div class="fw-semibold" style="font-size:15px"><?php echo e($s->name); ?></div>
                        <div class="text-muted" style="font-size:12px">
                            <?php echo e($s->email ?? ($s->user?->email ?? '—')); ?>

                            &nbsp;·&nbsp; <?php echo e($payment->cabang?->name ?? 'Pusat'); ?>

                        </div>
                        <?php if($payment->invoice): ?>
                        <div class="text-muted mt-1" style="font-size:11px">Invoice: <span class="fw-semibold"><?php echo e($payment->invoice->nomor_invoice ?? '#'.$payment->invoice_id); ?></span></div>
                        <?php endif; ?>
                    </div>
                    <?php else: ?>
                    <div class="text-muted">Data siswa tidak ditemukan.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="col-12 col-lg-5">
            
            <div class="dashboard-card mb-4">
                <h6 class="fw-bold mb-3" style="font-size:14px;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted)">
                    <i class="bi bi-image me-2 text-primary"></i>Bukti Pembayaran
                </h6>
                <?php if($payment->bukti_pembayaran): ?>
                    <?php $ext = pathinfo($payment->bukti_pembayaran, PATHINFO_EXTENSION); ?>
                    <?php if(in_array(strtolower($ext), ['jpg','jpeg','png','webp'])): ?>
                    <a href="<?php echo e(asset('storage/'.$payment->bukti_pembayaran)); ?>" target="_blank">
                        <img src="<?php echo e(asset('storage/'.$payment->bukti_pembayaran)); ?>" alt="Bukti"
                            style="width:100%;border-radius:12px;object-fit:contain;max-height:320px;background:var(--input-bg)">
                    </a>
                    <?php else: ?>
                    <a href="<?php echo e(asset('storage/'.$payment->bukti_pembayaran)); ?>" target="_blank"
                        class="btn btn-outline-primary w-100" style="border-radius:10px">
                        <i class="bi bi-file-earmark-pdf me-2"></i>Lihat Dokumen Bukti
                    </a>
                    <?php endif; ?>
                <?php else: ?>
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-image" style="font-size:36px;opacity:.2;display:block;margin-bottom:8px"></i>
                    Tidak ada bukti pembayaran diunggah
                </div>
                <?php endif; ?>
            </div>

            
            <?php if($payment->status === 'pending'): ?>
            <div class="dashboard-card">
                <h6 class="fw-bold mb-3" style="font-size:14px;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted)">
                    <i class="bi bi-lightning me-2 text-primary"></i>Tindakan
                </h6>
                <form method="POST" action="<?php echo e(route('admin.verifikasi-pembayaran.approve', $payment)); ?>" class="mb-3">
                    <?php echo csrf_field(); ?>
                    <div class="mb-2">
                        <label class="form-label fw-semibold" style="font-size:12px">Catatan Persetujuan (opsional)</label>
                        <textarea name="catatan" class="form-control form-control-sm" rows="2" placeholder="Catatan tambahan..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100 fw-semibold"
                        onclick="return confirm('Setujui pembayaran ini?')">
                        <i class="bi bi-check-lg me-2"></i>Setujui Pembayaran
                    </button>
                </form>

                <form method="POST" action="<?php echo e(route('admin.verifikasi-pembayaran.reject', $payment)); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-2">
                        <label class="form-label fw-semibold" style="font-size:12px">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="alasan_penolakan" class="form-control form-control-sm" rows="2"
                            placeholder="Tuliskan alasan..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger w-100 fw-semibold"
                        onclick="return confirm('Tolak pembayaran ini?')">
                        <i class="bi bi-x-lg me-2"></i>Tolak Pembayaran
                    </button>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/verifikasi-pembayaran/show.blade.php ENDPATH**/ ?>