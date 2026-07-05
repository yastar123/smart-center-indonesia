<?php $__env->startSection('title', 'Manajemen E-Billing'); ?>
<?php $__env->startSection('page-title', 'Billing'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-up">


<div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-7">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px">
                    <i class="bi bi-receipt-cutoff"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Billing Lunas</h5>
                    <span style="font-size:12px;opacity:.8">Riwayat pembayaran yang telah lunas — tagihan aktif ada di menu Tagihan Siswa</span>
                </div>
            </div>
        </div>
        <div class="col-md-5 text-md-end d-flex gap-2 justify-content-md-end">
            <button onclick="exportLaporan()" class="btn fw-semibold px-3"
                style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px">
                <i class="bi bi-download me-2"></i>Export Laporan
            </button>
            <button onclick="document.getElementById('modalBuatTagihan').style.display='flex'" class="btn fw-semibold px-4"
                style="background:rgba(255,255,255,.9);color:#461256;border:none;border-radius:10px">
                <i class="bi bi-plus-lg me-2"></i>Buat Tagihan
            </button>
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
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Pendapatan</div>
                    <div class="stat-value text-success" style="font-size:18px">Rp <?php echo e(number_format($stats['pendapatan'],0,',','.')); ?></div>
                    <div class="stat-growth text-success" style="font-size:11px">Semua invoice lunas</div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-cash-stack"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Invoice Lunas</div>
                    <div class="stat-value text-primary count-up" data-target="<?php echo e($invoices->total()); ?>"><?php echo e($invoices->total()); ?></div>
                    <div class="stat-growth text-muted" style="font-size:11px">Halaman ini</div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-check-circle-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Belum Lunas (Tagihan)</div>
                    <div class="stat-value text-warning count-up" data-target="<?php echo e($stats['menunggu']); ?>"><?php echo e($stats['menunggu']); ?></div>
                    <div class="stat-growth" style="font-size:11px"><a href="<?php echo e(route('admin.tagihan-siswa.index')); ?>" class="text-warning">Lihat Tagihan Siswa →</a></div>
                </div>
                <div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-hourglass-split"></i></div>
            </div>
        </div>
    </div>
</div>


<div class="dashboard-card mb-4 fade-up">
    <form method="GET" action="<?php echo e(route('admin.billing.index')); ?>">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold" style="font-size:12px">Cari (Nama, No Invoice)</label>
                <div class="input-group">
                    <span class="input-group-text" style="background:var(--input-bg);border-color:var(--card-border)"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari siswa atau nomor invoice..."
                        class="form-control" style="border-color:var(--card-border);background:var(--input-bg)">
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold" style="font-size:12px">Periode Bulan</label>
                <input type="month" name="periode" value="<?php echo e(request('periode')); ?>" class="form-control"
                    style="border-color:var(--card-border);background:var(--input-bg)">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill fw-semibold">Filter</button>
                <a href="<?php echo e(route('admin.billing.index')); ?>" class="btn btn-outline-secondary fw-semibold px-3">Reset</a>
            </div>
        </div>
    </form>
</div>


<div class="dashboard-card fade-up">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr style="background:var(--input-bg)">
                    <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">No. Invoice & Tanggal</th>
                    <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Siswa / Orang Tua</th>
                    <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Rincian Paket</th>
                    <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600;text-align:right">Total Tagihan</th>
                    <th class="text-center" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Status</th>
                    <th class="text-center" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $statusMap = [
                        'belum_bayar' => ['bg'=>'var(--soft-danger)','fg'=>'#dc2626','icon'=>'bi-x-circle-fill','label'=>'Belum Bayar'],
                        'sebagian'    => ['bg'=>'rgba(59,130,246,.12)','fg'=>'#2563eb','icon'=>'bi-pie-chart-fill','label'=>'Sebagian'],
                        'lunas'       => ['bg'=>'var(--soft-success)','fg'=>'#059669','icon'=>'bi-check-circle-fill','label'=>'Lunas'],
                    ];
                    $s = $statusMap[$inv->status] ?? ['bg'=>'var(--soft-muted-bg)','fg'=>'#6b7280','icon'=>'bi-dash-circle','label'=>ucfirst($inv->status)];
                    $isOverdue = $inv->status === 'belum_bayar' && $inv->jatuh_tempo && $inv->jatuh_tempo->isPast();
                    $canEdit   = in_array($inv->status, ['belum_bayar', 'sebagian']);
                ?>
                <tr style="<?php echo e($inv->status === 'lunas' ? 'opacity:.85' : ''); ?>">
                    <td>
                        <div class="fw-semibold" style="font-size:13px;color:#461256"><?php echo e($inv->nomor_invoice); ?></div>
                        <div class="text-muted" style="font-size:12px"><?php echo e($inv->created_at->format('d M Y')); ?></div>
                        <?php if($isOverdue): ?>
                            <span style="background:var(--soft-danger);color:#dc2626;padding:2px 7px;border-radius:10px;font-size:10px;font-weight:600">
                                <i class="bi bi-exclamation-triangle me-1"></i>Overdue
                            </span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="fw-semibold"><?php echo e($inv->siswa?->name ?? '—'); ?></div>
                        <div class="text-muted" style="font-size:12px"><?php echo e($inv->cabang?->name ?? '—'); ?></div>
                    </td>
                    <td>
                        <div class="fw-semibold" style="font-size:13px"><?php echo e(Str::limit($inv->deskripsi ?? '—', 45)); ?></div>
                        <?php if($inv->jatuh_tempo): ?>
                            <div class="text-muted" style="font-size:12px">
                                <i class="bi bi-calendar-event me-1"></i>Jatuh tempo: <?php echo e($inv->jatuh_tempo->format('d M Y')); ?>

                            </div>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <div class="fw-bold" style="color:#461256">Rp <?php echo e(number_format($inv->total,0,',','.')); ?></div>
                    </td>
                    <td class="text-center">
                        <span style="background:<?php echo e($s['bg']); ?>;color:<?php echo e($s['fg']); ?>;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;white-space:nowrap">
                            <i class="bi <?php echo e($s['icon']); ?> me-1"></i><?php echo e($s['label']); ?>

                        </span>
                    </td>
                    <td class="text-center">
                        <a href="<?php echo e(route('admin.billing.show', $inv)); ?>" class="btn btn-sm btn-outline-primary" title="Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                        <?php if($canEdit): ?>
                        <button onclick="openEditModal(<?php echo e($inv->id); ?>, '<?php echo e(addslashes($inv->deskripsi)); ?>', <?php echo e($inv->total); ?>, '<?php echo e($inv->jatuh_tempo?->format('Y-m-d') ?? ''); ?>', '<?php echo e(addslashes($inv->catatan ?? '')); ?>')"
                            class="btn btn-sm btn-outline-secondary ms-1" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div style="font-size:40px;opacity:.3;margin-bottom:8px"><i class="bi bi-receipt"></i></div>
                        <div class="text-muted">Belum ada data invoice</div>
                        <button onclick="document.getElementById('modalBuatTagihan').style.display='flex'" class="btn btn-sm btn-primary mt-2">Buat Tagihan Pertama</button>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($invoices->hasPages()): ?>
        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-top">
            <div class="text-muted" style="font-size:13px">
                Menampilkan <?php echo e($invoices->firstItem()); ?>–<?php echo e($invoices->lastItem()); ?> dari <?php echo e($invoices->total()); ?> invoice
            </div>
            <?php echo e($invoices->appends(request()->all())->links()); ?>

        </div>
    <?php endif; ?>
</div>

</div>


<div id="modalBuatTagihan" style="display:none;position:fixed;inset:0;z-index:1050;background:rgba(0,0,0,.5);align-items:center;justify-content:center">
    <div class="dashboard-card" style="width:100%;max-width:500px;margin:20px;max-height:90vh;overflow-y:auto">
        <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
            <h6 class="fw-bold mb-0"><i class="bi bi-plus-circle text-primary me-2"></i>Buat Tagihan Baru</h6>
            <button onclick="document.getElementById('modalBuatTagihan').style.display='none'" class="btn-close"></button>
        </div>
        <form method="POST" action="<?php echo e(route('admin.billing.store')); ?>">
        <?php echo csrf_field(); ?>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-semibold">Siswa / Peserta Didik <span class="text-danger">*</span></label>
                    <select name="siswa_id" class="form-select" required>
                        <option value="">— Pilih Siswa —</option>
                        <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($st->id); ?>"><?php echo e($st->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Program Belajar / Deskripsi <span class="text-danger">*</span></label>
                    <input type="text" name="deskripsi" class="form-control" required placeholder="Misal: Paket Matematika Intensif 3 Bulan">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nominal Tagihan <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="total" class="form-control" required min="1000" placeholder="1500000">
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Jatuh Tempo <span class="text-danger">*</span></label>
                    <input type="date" name="jatuh_tempo" class="form-control" required min="<?php echo e(date('Y-m-d')); ?>">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Catatan (opsional)</label>
                    <textarea name="catatan" rows="2" class="form-control" placeholder="Catatan tambahan..."></textarea>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary flex-fill fw-semibold">
                    <i class="bi bi-receipt me-2"></i>Terbitkan Tagihan
                </button>
                <button type="button" onclick="document.getElementById('modalBuatTagihan').style.display='none'"
                    class="btn btn-outline-secondary fw-semibold">Batal</button>
            </div>
        </form>
    </div>
</div>


<div id="modalEditTagihan" style="display:none;position:fixed;inset:0;z-index:1050;background:rgba(0,0,0,.5);align-items:center;justify-content:center">
    <div class="dashboard-card" style="width:100%;max-width:500px;margin:20px;max-height:90vh;overflow-y:auto">
        <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
            <h6 class="fw-bold mb-0"><i class="bi bi-pencil text-primary me-2"></i>Edit Tagihan</h6>
            <button onclick="document.getElementById('modalEditTagihan').style.display='none'" class="btn-close"></button>
        </div>
        <form method="POST" id="editForm" action="">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-semibold">Program Belajar / Deskripsi <span class="text-danger">*</span></label>
                    <input type="text" id="editDeskripsi" name="deskripsi" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nominal Tagihan <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" id="editTotal" name="total" class="form-control" required min="1000">
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Jatuh Tempo <span class="text-danger">*</span></label>
                    <input type="date" id="editJatuhTempo" name="jatuh_tempo" class="form-control" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Catatan (opsional)</label>
                    <textarea id="editCatatan" name="catatan" rows="2" class="form-control"></textarea>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary flex-fill fw-semibold">
                    <i class="bi bi-check-lg me-2"></i>Perbarui Tagihan
                </button>
                <button type="button" onclick="document.getElementById('modalEditTagihan').style.display='none'"
                    class="btn btn-outline-secondary fw-semibold">Batal</button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function openEditModal(id, deskripsi, total, jatuhTempo, catatan) {
    document.getElementById('editForm').action = '/admin/billing/' + id;
    document.getElementById('editDeskripsi').value = deskripsi;
    document.getElementById('editTotal').value = total;
    document.getElementById('editJatuhTempo').value = jatuhTempo;
    document.getElementById('editCatatan').value = catatan;
    document.getElementById('modalEditTagihan').style.display = 'flex';
}

function exportLaporan() {
    const params = new URLSearchParams(window.location.search);
    const base   = '<?php echo e(route("admin.billing.export")); ?>';
    window.location.href = base + (params.toString() ? '?' + params.toString() : '');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.getElementById('modalBuatTagihan').style.display = 'none';
        document.getElementById('modalEditTagihan').style.display = 'none';
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/billing/index.blade.php ENDPATH**/ ?>