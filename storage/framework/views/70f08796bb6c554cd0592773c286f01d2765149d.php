<?php $__env->startSection('title','Input Gaji Guru'); ?>
<?php $__env->startSection('page-title','Input Gaji Guru'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-0">
    <div class="dashboard-card border-0 shadow-none rounded-0 mb-0 p-4 p-md-5" style="min-height: calc(100vh - 120px);">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
            <div>
                <h5 class="fw-bold mb-1">Form Input Gaji</h5>
                <div class="text-muted small">Isi data pembayaran gaji guru di halaman terpisah</div>
            </div>
            <a href="<?php echo e(route('admin.salaries.index')); ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>

        <form action="<?php echo e(route('admin.salaries.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Guru <span class="text-danger">*</span></label>
                        <select id="guru_id" name="guru_id" class="form-select" required>
                            <option value="">Pilih Guru</option>
                            <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($t->id); ?>" <?php echo e(old('guru_id', request('guru_id')) == $t->id ? 'selected' : ''); ?>>
                                    <?php echo e($t->name); ?> - <?php echo e($t->branch?->name ?? 'Pusat'); ?> - <?php echo e($t->jenis_guru ?? 'Guru'); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Periode <span class="text-danger">*</span></label>
                        <input type="month" name="periode" class="form-control" value="<?php echo e(old('periode', date('Y-m'))); ?>" required>
                    </div>
                    <div class="col-12">
                        <div id="teacherPackageInfo" class="d-none">
                            <div class="border rounded-3 p-3" style="background:var(--input-bg,#f8f9fa);">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="bi bi-journal-bookmark-fill text-primary"></i>
                                    <span class="fw-semibold">Ringkasan Mengajar Guru</span>
                                    <span id="teacherPackageCount" class="badge bg-primary-soft text-primary ms-1"></span>
                                </div>
                                <div id="teacherPackageList"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tipe Gaji</label>
                        <select name="tipe_gaji" class="form-select">
                            <option value="bulanan" <?php echo e(old('tipe_gaji') == 'bulanan' ? 'selected' : ''); ?>>Gaji Bulanan</option>
                            <option value="freelance" <?php echo e(old('tipe_gaji') == 'freelance' ? 'selected' : ''); ?>>Gaji Freelance</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Gaji Pokok (Rp)</label>
                        <input type="number" name="gaji_pokok" class="form-control" value="<?php echo e(old('gaji_pokok', 0)); ?>" min="0" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Bonus</label>
                        <input type="number" name="bonus" class="form-control" value="<?php echo e(old('bonus', 0)); ?>" min="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="pending" <?php echo e(old('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                            <option value="dibayar" <?php echo e(old('status') == 'dibayar' ? 'selected' : ''); ?>>Dibayar</option>
                            <option value="batal" <?php echo e(old('status') == 'batal' ? 'selected' : ''); ?>>Batal</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Metode Pembayaran</label>
                        <select name="metode_pembayaran" class="form-select">
                            <option value="">Pilih</option>
                            <option value="Transfer Bank" <?php echo e(old('metode_pembayaran') == 'Transfer Bank' ? 'selected' : ''); ?>>Transfer Bank</option>
                            <option value="Tunai" <?php echo e(old('metode_pembayaran') == 'Tunai' ? 'selected' : ''); ?>>Tunai</option>
                            <option value="E-Wallet" <?php echo e(old('metode_pembayaran') == 'E-Wallet' ? 'selected' : ''); ?>>E-Wallet</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Pembayaran</label>
                        <input type="date" name="tanggal_pembayaran" class="form-control" value="<?php echo e(old('tanggal_pembayaran')); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Bank</label>
                        <input type="text" name="nama_bank" class="form-control" value="<?php echo e(old('nama_bank')); ?>" placeholder="BCA, Mandiri, dll">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nomor Rekening</label>
                        <input type="text" name="nomor_rekening" class="form-control" value="<?php echo e(old('nomor_rekening')); ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Bukti Pembayaran</label>
                        <input type="file" name="bukti_pembayaran" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-2 border-top">
                    <a href="<?php echo e(route('admin.salaries.index')); ?>" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function initTeacherPackageInfo() {
    const guruSelect   = document.getElementById('guru_id');
    const packageInfo  = document.getElementById('teacherPackageInfo');
    const packageList  = document.getElementById('teacherPackageList');
    const packageCount = document.getElementById('teacherPackageCount');

    if (!guruSelect || !packageInfo || !packageList) return;

    function jenisLabel(jenis) {
        const map = { reguler: 'Reguler', intensif: 'Intensif', privat: 'Privat', online: 'Online', tryout: 'Try Out' };
        return map[jenis] || jenis || '-';
    }

    function renderPackages(packages) {
        packageCount.textContent = packages.length + ' paket';

        if (!packages.length) {
            packageList.innerHTML = `
                <div class="text-center py-3 text-muted small">
                    <i class="bi bi-inbox fs-4 d-block mb-1"></i>
                    Tidak ada paket aktif yang diajarkan guru ini.
                </div>`;
            packageInfo.classList.remove('d-none');
            return;
        }

        packageList.innerHTML = packages.map(pkg => {
            const total     = pkg.jumlah_pertemuan || 0;
            const selesai   = pkg.sesi_selesai || 0;
            const dijadwal  = pkg.sesi_dijadwalkan || 0;
            const belum     = pkg.sesi_belum || 0;
            const pctDone   = total > 0 ? Math.round((selesai / total) * 100) : 0;
            const pctSched  = total > 0 ? Math.round((dijadwal / total) * 100) : 0;

            const mapelTags = (pkg.mata_pelajaran || []).map(m =>
                `<span class="badge rounded-pill me-1 mb-1" style="background:var(--soft-primary,#f0e6ff);color:var(--bs-primary)">${m}</span>`
            ).join('') || '<span class="text-muted small">—</span>';

            return `
            <div class="border rounded-3 p-3 mb-2 bg-white">
                <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-2">
                    <div>
                        <div class="fw-semibold text-dark mb-1">${pkg.nama || '-'}</div>
                        <div class="d-flex flex-wrap gap-1 align-items-center">
                            <span class="badge bg-secondary-subtle text-secondary">${jenisLabel(pkg.jenis)}</span>
                            <span class="text-muted small"><i class="bi bi-calendar3 me-1"></i>${pkg.durasi_bulan ?? '-'} bulan</span>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="small text-muted mb-1">Progress Sesi</div>
                        <div class="fs-5 fw-bold text-primary">${selesai}<span class="fs-6 fw-normal text-muted"> / ${total}</span></div>
                        <div class="small text-muted">sesi selesai</div>
                    </div>
                </div>

                <div class="mb-2">
                    <div class="small text-muted mb-1"><i class="bi bi-book me-1"></i>Mata Pelajaran</div>
                    <div class="d-flex flex-wrap">${mapelTags}</div>
                </div>

                <div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Progress Sesi Kelas</span>
                        <span>${pctDone}% selesai</span>
                    </div>
                    <div class="progress rounded-pill" style="height:8px;">
                        <div class="progress-bar bg-success" style="width:${pctDone}%" title="${selesai} selesai"></div>
                        <div class="progress-bar bg-warning" style="width:${pctSched}%" title="${dijadwal} dijadwalkan"></div>
                    </div>
                    <div class="d-flex flex-wrap gap-3 mt-2 small">
                        <span><span class="d-inline-block rounded-circle me-1" style="width:9px;height:9px;background:#198754;"></span>${selesai} Selesai</span>
                        <span><span class="d-inline-block rounded-circle me-1" style="width:9px;height:9px;background:#ffc107;"></span>${dijadwal} Dijadwalkan</span>
                        <span><span class="d-inline-block rounded-circle me-1" style="width:9px;height:9px;background:#dee2e6;"></span>${belum} Belum</span>
                        <span class="ms-auto fw-semibold">Total: ${total} sesi</span>
                    </div>
                </div>
            </div>`;
        }).join('');

        packageInfo.classList.remove('d-none');
    }

    function loadPackages() {
        const teacherId = guruSelect.value;
        if (!teacherId) {
            packageInfo.classList.add('d-none');
            packageList.innerHTML = '';
            packageCount.textContent = '';
            return;
        }

        packageInfo.classList.remove('d-none');
        packageList.innerHTML = `
            <div class="text-center py-3 text-muted small">
                <div class="spinner-border spinner-border-sm text-primary me-2"></div>Memuat data paket guru...
            </div>`;

        fetch(`/admin/salaries/teachers/${teacherId}/packages`)
            .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
            .then(result => {
                if (result.success) {
                    renderPackages(result.data || []);
                } else {
                    packageList.innerHTML = '<div class="text-danger small p-2">Gagal memuat data paket.</div>';
                }
            })
            .catch(() => {
                packageList.innerHTML = '<div class="text-danger small p-2">Gagal memuat data paket.</div>';
            });
    }

    guruSelect.addEventListener('change', loadPackages);
    if (guruSelect.value) loadPackages();
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Edu Juanda Pratama\Downloads\smart-center-indonesia-1\smart-center-indonesia-1\resources\views/admin/salaries/create.blade.php ENDPATH**/ ?>