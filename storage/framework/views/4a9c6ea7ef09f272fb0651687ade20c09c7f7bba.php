<?php $__env->startSection('title', 'Pendaftaran Siswa'); ?>
<?php $__env->startSection('page-title', 'Pendaftaran Siswa'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-card mb-4 fade-up">
    <div class="row g-3 align-items-center">
        <div class="col-md-8">
            <h5 class="fw-bold mb-1">Data Pendaftaran Siswa Baru</h5>
            <p class="text-muted mb-0" style="font-size:12px">Daftar pendaftar dari formulir publik — verifikasi untuk membuat akun siswa</p>
        </div>
        <div class="col-md-4 text-md-end">
            <span class="badge bg-warning text-dark"><?php echo e($stats['pending']); ?> menunggu verifikasi</span>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-title">Total</div>
            <div class="stat-value text-primary"><?php echo e($stats['total']); ?></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-title">Menunggu</div>
            <div class="stat-value text-warning"><?php echo e($stats['pending']); ?></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-title">Terverifikasi</div>
            <div class="stat-value text-success"><?php echo e($stats['verified']); ?></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-title">Ditolak</div>
            <div class="stat-value text-danger"><?php echo e($stats['rejected']); ?></div>
        </div>
    </div>
</div>

<?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if(session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo e(session('error')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="dashboard-card fade-up">
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-6">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control form-control-sm" placeholder="Cari nama / no registrasi / HP">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="pending"  <?php echo e(request('status') === 'pending'  ? 'selected' : ''); ?>>Menunggu</option>
                <option value="verified" <?php echo e(request('status') === 'verified' ? 'selected' : ''); ?>>Terverifikasi</option>
                <option value="rejected" <?php echo e(request('status') === 'rejected' ? 'selected' : ''); ?>>Ditolak</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-sm btn-primary w-100">Filter</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>No. Reg</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>HP</th>
                    <th>Orang Tua</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $registrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><code style="font-size:11px"><?php echo e($reg->no_reg); ?></code></td>
                    <td>
                        <div class="fw-semibold"><?php echo e($reg->name); ?></div>
                        <div class="small text-muted">
                            <?php echo e($reg->program ?? '-'); ?>

                            <?php if($reg->schedule_time): ?> · <?php echo e($reg->schedule_time); ?> <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <?php if($reg->education_level): ?>
                        <span class="badge" style="background:#fdf4ff;color:#461256;border:1px solid #e9d5ff;font-weight:600;font-size:11px;white-space:normal;text-align:left">
                            <?php echo e($reg->education_level); ?>

                        </span>
                        <?php else: ?>
                        <span class="text-muted small">-</span>
                        <?php endif; ?>
                    </td>
                    <td style="white-space:nowrap">
                        <?php if($reg->phone): ?>
                        <a href="https://wa.me/62<?php echo e(ltrim(preg_replace('/\D/','', $reg->phone), '0')); ?>" target="_blank" class="text-success text-decoration-none" style="font-size:13px">
                            <i class="bi bi-whatsapp me-1"></i><?php echo e($reg->phone); ?>

                        </a>
                        <?php else: ?> — <?php endif; ?>
                    </td>
                    <td>
                        <div style="font-size:13px"><?php echo e($reg->parent_name ?? '-'); ?></div>
                        <?php if($reg->parent_phone): ?>
                        <div class="small text-muted"><?php echo e($reg->parent_phone); ?></div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge bg-<?php echo e($reg->status === 'pending' ? 'warning text-dark' : ($reg->status === 'verified' ? 'success' : 'danger')); ?>">
                            <?php echo e($reg->status === 'pending' ? 'Menunggu' : ($reg->status === 'verified' ? 'Terverifikasi' : 'Ditolak')); ?>

                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center flex-wrap">
                            <button class="btn btn-sm btn-outline-primary" title="Lihat Detail" onclick="viewDetail(<?php echo e($reg->id); ?>)">
                                <i class="bi bi-eye"></i>
                            </button>
                            <?php if($reg->status === 'pending'): ?>
                            <a href="<?php echo e(route('admin.student-registrations.verify', $reg)); ?>"
                               class="btn btn-sm btn-success"
                               title="Verifikasi & Buat Akun Siswa"
                               onclick="return confirm('Verifikasi pendaftaran ini?\n\nAkun login (email & password) akan dibuat otomatis dan data akan disimpan sebagai siswa.')">
                                <i class="bi bi-check2-circle me-1"></i>Verifikasi
                            </a>
                            <?php endif; ?>
                            <button class="btn btn-sm btn-outline-danger" title="Hapus" onclick="deleteRegistration(<?php echo e($reg->id); ?>)">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:.5rem"></i>
                        Belum ada data pendaftaran.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($registrations->hasPages()): ?>
    <div class="mt-3"><?php echo e($registrations->links('pagination::bootstrap-5')); ?></div>
    <?php endif; ?>
</div>


<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#260632,#461256,#68117e);color:white">
                <h6 class="modal-title fw-bold"><i class="bi bi-person-lines-fill me-2"></i>Detail Pendaftaran</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailBody">
                <div class="text-center py-4"><span class="spinner-border spinner-border-sm"></span> Memuat...</div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function deleteRegistration(id) {
    if (!confirm('Hapus data pendaftaran ini? Tindakan ini tidak dapat dibatalkan.')) return;
    $.ajax({
        url: '/admin/student-registrations/' + id,
        type: 'POST',
        data: { _method: 'DELETE', _token: $('meta[name="csrf-token"]').attr('content') },
        success: function(res) { if (res.success) location.reload(); },
        error: function() { alert('Gagal menghapus data. Coba lagi.'); }
    });
}

function viewDetail(id) {
    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
    document.getElementById('detailBody').innerHTML = '<div class="text-center py-4"><span class="spinner-border spinner-border-sm"></span> Memuat...</div>';
    modal.show();

    fetch('/admin/student-registrations/' + id, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(res => {
        if (!res.success) { document.getElementById('detailBody').innerHTML = '<p class="text-danger">Gagal memuat data.</p>'; return; }
        const d = res.data;
        const row = (label, val) => val ? `<div class="col-md-6 mb-2"><div class="small text-muted">${label}</div><div class="fw-semibold">${val}</div></div>` : '';
        const interests = Array.isArray(d.interests) ? d.interests.join(', ') : (d.interests || '-');
        const days = Array.isArray(d.day_preferences) ? d.day_preferences.join(', ') : (d.day_preferences || '-');
        document.getElementById('detailBody').innerHTML = `
            <div class="row">
                ${row('Nama Lengkap', d.name)}
                ${row('No. Registrasi', d.no_reg)}
                ${row('Kategori Peserta Didik', d.education_level)}
                ${row('Jenis Kelamin', d.gender)}
                ${row('Tempat Lahir', d.birth_place)}
                ${row('Tanggal Lahir', d.birth_date)}
                ${row('Alamat', d.address)}
                ${row('Nomor WA/HP', d.phone)}
                ${row('Nama Orang Tua/Wali', d.parent_name)}
                ${row('HP Orang Tua/Wali', d.parent_phone)}
                ${row('Program Belajar', d.program)}
                ${row('Sistem Belajar', d.system)}
                ${row('Tempat Belajar', d.learning_place)}
                ${row('Program Diminati', interests)}
                ${row('Hari Belajar', days)}
                ${row('Jam Belajar', d.schedule_time)}
                ${row('Tanggal Mulai', d.start_date)}
                ${row('Catatan', d.notes)}
                ${row('Status', d.status)}
            </div>`;
    })
    .catch(() => { document.getElementById('detailBody').innerHTML = '<p class="text-danger">Gagal memuat data.</p>'; });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/student-registrations/index.blade.php ENDPATH**/ ?>