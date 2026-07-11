<?php $__env->startSection('title', 'Daftar Registrasi Siswa'); ?>
<?php $__env->startSection('page-title', 'Manajemen Pendaftaran'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-up">


<div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-40px;top:-40px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div>
            <div style="font-size:11px;opacity:.6;text-transform:uppercase;letter-spacing:.08em;margin-bottom:5px">Manajemen Pendaftaran</div>
            <h4 class="fw-bold mb-1" style="color:white;font-size:clamp(16px,2vw,22px)">Daftar Registrasi Siswa</h4>
            <p style="opacity:.72;margin:0;font-size:13px">Kelola pendaftaran, status pembayaran, dan penugasan kelas siswa baru.</p>
        </div>
        <a href="<?php echo e(route('admin.registration.create')); ?>"
           class="btn fw-semibold px-4"
           style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.3);border-radius:12px;backdrop-filter:blur(10px);white-space:nowrap">
            <i class="bi bi-person-plus-fill me-2"></i>Registrasi Siswa
        </a>
    </div>
</div>


<?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible fade show mb-3"><i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if(session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show mb-3"><i class="bi bi-x-circle me-2"></i><?php echo e(session('error')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>


<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Pendaftar</div>
                    <div class="stat-value text-primary"><?php echo e($stats['total']); ?></div>
                    <div class="stat-growth text-muted">Semua waktu</div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-people-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-top:3px solid #f59e0b">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Menunggu Verifikasi</div>
                    <div class="stat-value text-warning"><?php echo e($stats['pending']); ?></div>
                    <div class="stat-growth text-muted">Perlu ditindak</div>
                </div>
                <div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-clock-history"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Terverifikasi</div>
                    <div class="stat-value text-success"><?php echo e($stats['verified']); ?></div>
                    <div class="stat-growth text-muted">Akun dibuat</div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-check-circle-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-top:3px solid #ef4444">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Ditolak</div>
                    <div class="stat-value text-danger"><?php echo e($stats['rejected']); ?></div>
                    <div class="stat-growth text-muted">Tidak dilanjutkan</div>
                </div>
                <div class="stat-icon bg-danger-soft" style="color:white"><i class="bi bi-x-circle-fill"></i></div>
            </div>
        </div>
    </div>
</div>


<div class="dashboard-card mb-3">
    <form method="GET" action="<?php echo e(route('admin.registration-list.index')); ?>" class="row g-2 align-items-end">
        <div class="col-12 col-md-4">
            <label class="form-label" style="font-size:.78rem;color:var(--text-muted)">Cari</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="Nama, HP, No. Reg...">
            </div>
        </div>
        <div class="col-6 col-md-2">
            <label class="form-label" style="font-size:.78rem;color:var(--text-muted)">Status Pendaftaran</label>
            <select name="reg_status" class="form-select form-select-sm">
                <option value="">Semua</option>
                <option value="pending"   <?php echo e(request('reg_status')==='pending'  ?'selected':''); ?>>Menunggu</option>
                <option value="verified"  <?php echo e(request('reg_status')==='verified' ?'selected':''); ?>>Terverifikasi</option>
                <option value="rejected"  <?php echo e(request('reg_status')==='rejected' ?'selected':''); ?>>Ditolak</option>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <label class="form-label" style="font-size:.78rem;color:var(--text-muted)">Status</label>
            <select name="academic_status" class="form-select form-select-sm">
                <option value="">Semua</option>
                <option value="pending"        <?php echo e(request('academic_status')==='pending'        ?'selected':''); ?>>Pending</option>
                <option value="menunggu_kelas" <?php echo e(request('academic_status')==='menunggu_kelas' ?'selected':''); ?>>Atur Jadwal</option>
                <option value="terjadwal"      <?php echo e(request('academic_status')==='terjadwal'      ?'selected':''); ?>>Terjadwal</option>
            </select>
        </div>
        <div class="col-6 col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-funnel me-1"></i>Filter</button>
            <a href="<?php echo e(route('admin.registration-list.index')); ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-lg"></i></a>
        </div>
    </form>
</div>


<div class="dashboard-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-list-check text-primary me-2"></i>Daftar Registrasi</h6>
        <span class="badge" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);padding:5px 12px;border-radius:20px;font-size:.73rem">
            <?php echo e($registrations->total()); ?> total pendaftar
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle" style="font-size:.84rem">
            <thead>
                <tr style="background:var(--input-bg)">
                    <th style="width:40px;color:var(--text-muted);font-size:.72rem;font-weight:600;text-transform:uppercase;padding:10px 12px">#</th>
                    <th style="color:var(--text-muted);font-size:.72rem;font-weight:600;text-transform:uppercase;padding:10px 12px">Siswa &amp; Paket</th>
                    <th style="color:var(--text-muted);font-size:.72rem;font-weight:600;text-transform:uppercase;padding:10px 12px">Total Pendaftar</th>
                    <th style="color:var(--text-muted);font-size:.72rem;font-weight:600;text-transform:uppercase;padding:10px 12px">Status</th>
                    <th style="color:var(--text-muted);font-size:.72rem;font-weight:600;text-transform:uppercase;padding:10px 12px;text-align:right">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $registrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $reg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $acadBadge = [
                    'pending'        => ['var(--soft-warning-bg)', 'var(--soft-warning-text)', 'Pending',     'bi-hourglass-split'],
                    'menunggu_kelas' => ['var(--soft-info-bg)',    'var(--soft-info-text)',    'Atur Jadwal', 'bi-calendar-plus'],
                    'terjadwal'      => ['var(--soft-success-bg)', 'var(--soft-success-text)', 'Terjadwal',   'bi-calendar-check-fill'],
                ][$reg->academic_status ?? 'pending'] ?? ['var(--soft-muted-bg)','var(--text-muted)','–','bi-dash'];

                $regBadge = [
                    'pending'  => ['var(--soft-warning-bg)', 'var(--soft-warning-text)', 'Menunggu'],
                    'verified' => ['var(--soft-success-bg)', 'var(--soft-success-text)', 'Terverifikasi'],
                    'rejected' => ['var(--soft-danger-bg)',  'var(--soft-danger-text)',  'Ditolak'],
                ][$reg->status] ?? ['var(--soft-muted-bg)','var(--text-muted)','–'];
            ?>
            <tr>
                <td style="padding:12px;color:var(--text-muted);font-size:.78rem">
                    <?php echo e($registrations->firstItem() + $idx); ?>

                </td>
                <td style="padding:12px">
                    <div class="d-flex align-items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($reg->name)); ?>&background=<?php echo e($reg->gender==='P'?'ec4899':'c84ddf'); ?>&color=fff&size=36"
                             class="rounded-circle flex-shrink-0" width="36" height="36"
                             style="border:2px solid var(--card-border)">
                        <div>
                            <div class="fw-semibold" style="font-size:.86rem"><?php echo e($reg->name); ?></div>
                            <div class="text-muted" style="font-size:.72rem;line-height:1.5">
                                <?php if($reg->phone): ?><i class="bi bi-telephone" style="font-size:.65rem"></i> <?php echo e($reg->phone); ?>&nbsp;<?php endif; ?>
                                <?php if($reg->program): ?><span class="badge" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);padding:2px 7px;border-radius:10px;font-size:.65rem"><?php echo e($reg->program); ?></span><?php endif; ?>
                            </div>
                            <?php if($reg->interests && count($reg->interests)): ?>
                            <div class="mt-1 d-flex flex-wrap gap-1">
                                <?php $__currentLoopData = array_slice($reg->interests, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $int): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span style="font-size:.65rem;background:var(--input-bg);border:1px solid var(--card-border);padding:1px 6px;border-radius:8px;color:var(--text-muted)"><?php echo e($int); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php if(count($reg->interests) > 3): ?><span style="font-size:.65rem;color:var(--text-muted)">+<?php echo e(count($reg->interests)-3); ?> lainnya</span><?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </td>
                <td style="padding:12px">
                    <div>
                        <code style="font-size:.72rem;background:var(--input-bg);padding:2px 6px;border-radius:6px"><?php echo e($reg->no_reg); ?></code>
                    </div>
                    <div class="text-muted mt-1" style="font-size:.72rem">
                        <i class="bi bi-calendar3" style="font-size:.65rem"></i>
                        <?php echo e($reg->created_at->format('d M Y')); ?>

                    </div>
                    <div class="mt-1">
                        <span class="badge" style="background:<?php echo e($regBadge[0]); ?>;color:<?php echo e($regBadge[1]); ?>;padding:2px 8px;border-radius:10px;font-size:.65rem">
                            <?php echo e($regBadge[2]); ?>

                        </span>
                    </div>
                </td>
                <td style="padding:12px">
                    <span class="badge d-inline-flex align-items-center gap-1"
                          style="background:<?php echo e($acadBadge[0]); ?>;color:<?php echo e($acadBadge[1]); ?>;padding:5px 10px;border-radius:20px;font-size:.75rem;font-weight:500">
                        <i class="bi <?php echo e($acadBadge[3]); ?>" style="font-size:.72rem"></i>
                        <?php echo e($acadBadge[2]); ?>

                    </span>
                </td>
                <td style="padding:12px;text-align:right">
                    <div class="d-flex justify-content-end gap-2 flex-wrap">
                        <?php if($reg->status !== 'verified'): ?>
                        <a href="<?php echo e(route('admin.registration-list.process', $reg->id)); ?>"
                           class="btn btn-sm btn-primary"
                           style="border-radius:8px;font-size:.75rem;padding:5px 12px">
                            <i class="bi bi-clipboard2-check me-1"></i>Proses Pendaftaran
                        </a>
                        <?php endif; ?>
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                onclick="openRegPreview(<?php echo e($reg->id); ?>)"
                                style="border-radius:8px;font-size:.75rem;padding:5px 12px">
                            <i class="bi bi-eye me-1"></i>Detail
                        </button>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="5" class="text-center py-5">
                    <i class="bi bi-inbox d-block mb-3" style="font-size:3rem;opacity:.2"></i>
                    <div class="fw-semibold mb-1">Belum ada data pendaftar</div>
                    <p class="text-muted" style="font-size:.83rem">Data akan muncul ketika calon siswa mengisi form pendaftaran.</p>
                </td>
            </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    
    <?php if($registrations->hasPages()): ?>
    <div class="d-flex justify-content-between align-items-center mt-3 pt-3" style="border-top:1px solid var(--card-border)">
        <div class="text-muted" style="font-size:.8rem">
            Menampilkan <?php echo e($registrations->firstItem()); ?>–<?php echo e($registrations->lastItem()); ?> dari <?php echo e($registrations->total()); ?>

        </div>
        <?php echo e($registrations->links()); ?>

    </div>
    <?php endif; ?>
</div>

</div>


<div class="modal fade" id="regPreviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:16px;border:1px solid var(--card-border);background:var(--card-bg)">
            <div class="modal-header" style="background:linear-gradient(135deg,#260632,#461256,#c84ddf);border-radius:16px 16px 0 0;border:none">
                <h5 class="modal-title text-white fw-bold" style="font-size:1rem"><i class="bi bi-person-vcard me-2"></i>Detail Pendaftaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="regPreviewBody">
                <div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>
            </div>
        </div>
    </div>
</div>

<script>
const _regDetailUrl = '<?php echo e(url("admin/student-registrations")); ?>';
const _regCsrf = '<?php echo e(csrf_token()); ?>';

function openRegPreview(id) {
    const modal = new bootstrap.Modal(document.getElementById('regPreviewModal'));
    document.getElementById('regPreviewBody').innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>';
    modal.show();
    fetch(`${_regDetailUrl}/${id}`, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': _regCsrf } })
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(d => {
            document.getElementById('regPreviewBody').innerHTML = `
            <div class="row g-3">
                <div class="col-md-6">
                    <h6 class="text-muted fw-semibold" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.06em">Data Diri</h6>
                    <table class="table table-sm table-borderless" style="font-size:.83rem">
                        <tr><td class="text-muted" style="width:120px">No. Registrasi</td><td><code>${d.no_reg||'–'}</code></td></tr>
                        <tr><td class="text-muted">Nama</td><td>${d.name||'–'}</td></tr>
                        <tr><td class="text-muted">No. HP</td><td>${d.phone||'–'}</td></tr>
                        <tr><td class="text-muted">Jenis Kelamin</td><td>${d.gender==='L'?'Laki-laki':d.gender==='P'?'Perempuan':(d.gender||'–')}</td></tr>
                        <tr><td class="text-muted">Tgl Lahir</td><td>${d.birth_date||'–'}</td></tr>
                        <tr><td class="text-muted">Alamat</td><td>${d.address||'–'}</td></tr>
                        <tr><td class="text-muted">Orang Tua</td><td>${d.parent_name||'–'}</td></tr>
                        <tr><td class="text-muted">HP Ortu</td><td>${d.parent_phone||'–'}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted fw-semibold" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.06em">Info Program</h6>
                    <table class="table table-sm table-borderless" style="font-size:.83rem">
                        <tr><td class="text-muted" style="width:120px">Program</td><td><strong>${d.program||'–'}</strong></td></tr>
                        <tr><td class="text-muted">Sistem</td><td>${d.system||'–'}</td></tr>
                        <tr><td class="text-muted">Cabang</td><td>${d.branch||'–'}</td></tr>
                        <tr><td class="text-muted">Hari</td><td>${(d.day_preferences||[]).join(', ')||'–'}</td></tr>
                        <tr><td class="text-muted">Jam</td><td>${d.schedule_time||'–'}</td></tr>
                        <tr><td class="text-muted">Tgl Mulai</td><td>${d.start_date||'–'}</td></tr>
                        <tr><td class="text-muted">Status</td><td><span class="badge bg-${d.status==='verified'?'success':d.status==='rejected'?'danger':'warning'}">${d.status}</span></td></tr>
                        <tr><td class="text-muted">Mendaftar</td><td>${d.created_at||'–'}</td></tr>
                    </table>
                </div>
                ${(d.interests||[]).length ? `<div class="col-12"><h6 class="text-muted fw-semibold" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.06em">Program yang Diminati</h6><div class="d-flex flex-wrap gap-2">${(d.interests||[]).map(i=>`<span class="badge" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);padding:5px 12px;border-radius:20px">${i}</span>`).join('')}</div></div>` : ''}
                ${d.notes ? `<div class="col-12"><h6 class="text-muted fw-semibold" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.06em">Catatan</h6><p style="font-size:.85rem">${d.notes}</p></div>` : ''}
            </div>`;
        })
        .catch(() => {
            document.getElementById('regPreviewBody').innerHTML = '<div class="text-center py-4 text-danger"><i class="bi bi-x-circle-fill d-block mb-2" style="font-size:2rem"></i>Gagal memuat data.</div>';
        });
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/registration/registration-list.blade.php ENDPATH**/ ?>