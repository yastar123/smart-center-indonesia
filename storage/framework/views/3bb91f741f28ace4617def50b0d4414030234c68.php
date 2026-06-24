<?php $__env->startSection('title', 'Persetujuan Jadwal'); ?>
<?php $__env->startSection('page-title', 'Persetujuan Jadwal'); ?>

<?php $__env->startSection('content'); ?>


<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div class="d-flex align-items-center gap-3">
            <div style="width:52px;height:52px;border-radius:16px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div>
                <div style="font-size:11px;opacity:.6;margin-bottom:3px;text-transform:uppercase;letter-spacing:.08em">Portal Siswa</div>
                <h4 class="fw-bold mb-1" style="color:white;letter-spacing:-.02em">Persetujuan Jadwal</h4>
                <p class="mb-0" style="opacity:.75;font-size:13px">Ajukan jadwal pertemuan · Jadwal dibuat setelah semua pihak setuju</p>
            </div>
        </div>
        <div>
            <button onclick="openProposalModal()" class="btn fw-semibold px-4"
                style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                <i class="bi bi-plus-lg me-2"></i>Ajukan Jadwal Baru
            </button>
        </div>
    </div>
</div>


<div class="dashboard-card mb-4 fade-up" style="border-left:4px solid #c84ddf">
    <div class="d-flex align-items-center gap-2 mb-2">
        <i class="bi bi-info-circle text-primary"></i>
        <span class="fw-semibold" style="font-size:13px">Alur Persetujuan Jadwal</span>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap" style="font-size:12px;color:var(--text-muted)">
        <span class="badge" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);padding:5px 10px;border-radius:8px">① Ajukan Jadwal</span>
        <i class="bi bi-arrow-right" style="font-size:11px"></i>
        <span class="badge" style="background:var(--soft-warning-bg);color:var(--soft-warning-text);padding:5px 10px;border-radius:8px">② Semua Setuju</span>
        <i class="bi bi-arrow-right" style="font-size:11px"></i>
        <span class="badge" style="background:var(--soft-info-bg);color:var(--soft-info-text);padding:5px 10px;border-radius:8px">③ Jadwal Dibuat Otomatis</span>
        <i class="bi bi-arrow-right" style="font-size:11px"></i>
        <span class="badge" style="background:var(--soft-success-bg);color:var(--soft-success-text);padding:5px 10px;border-radius:8px">④ Guru Isi Absensi</span>
        <i class="bi bi-arrow-right" style="font-size:11px"></i>
        <span class="badge" style="background:rgba(239,68,68,.1);color:#ef4444;padding:5px 10px;border-radius:8px"><i class="bi bi-lock-fill me-1"></i>⑤ Absensi Terkunci</span>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 fade-up">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Total Proposal</div><div class="stat-value"><?php echo e($stats['total']); ?></div></div>
                <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-calendar-week"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Menunggu Respons</div><div class="stat-value text-warning"><?php echo e($stats['pending']); ?></div></div>
                <div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-clock-history"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Disetujui</div><div class="stat-value text-success"><?php echo e($stats['approved']); ?></div></div>
                <div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-check-circle-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card" style="border-top:3px solid #ef4444">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Ditolak</div><div class="stat-value text-danger"><?php echo e($stats['rejected']); ?></div></div>
                <div class="stat-icon bg-danger-soft" style="color:white"><i class="bi bi-x-circle-fill"></i></div>
            </div>
        </div>
    </div>
</div>


<div class="dashboard-card fade-up">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-list-ul text-primary me-2"></i>Daftar Proposal Jadwal
            <span class="badge ms-2" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);font-size:11px"><?php echo e($proposals->total()); ?> data</span>
        </h6>
    </div>

    <?php if($proposals->isEmpty()): ?>
    <div class="text-center py-5">
        <i class="bi bi-calendar-x" style="font-size:56px;opacity:.25;display:block;margin-bottom:16px;color:var(--primary)"></i>
        <div class="fw-bold mb-1" style="font-size:17px">Belum Ada Proposal</div>
        <div class="text-muted" style="font-size:13px">Ajukan jadwal baru untuk memulai persetujuan.</div>
    </div>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover table-modern align-middle mb-0">
            <thead class="thead-modern">
                <tr>
                    <th class="ps-3">Kelas</th>
                    <th>Pertemuan</th>
                    <th>Pengaju</th>
                    <th>Tanggal & Waktu</th>
                    <th>Status</th>
                    <th>Persetujuan</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $proposals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proposal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $statusMap = [
                        'pending'  => ['bg'=>'var(--soft-warning-bg)','color'=>'var(--soft-warning-text)','label'=>'Menunggu'],
                        'approved' => ['bg'=>'var(--soft-success-bg)','color'=>'var(--soft-success-text)','label'=>'Disetujui'],
                        'rejected' => ['bg'=>'var(--soft-danger-bg)','color'=>'var(--soft-danger-text)','label'=>'Ditolak'],
                    ];
                    $st = $statusMap[$proposal->status] ?? ['bg'=>'var(--soft-muted-bg)','color'=>'var(--soft-muted-text)','label'=>$proposal->status];
                    $myApproval = $proposal->approvals->where('approver_type', 'siswa')->where('approver_id', $student->id)->first();
                ?>
                <tr style="border-bottom:1px solid var(--card-border)">
                    <td class="ps-3">
                        <div class="fw-semibold" style="font-size:13px"><?php echo e($proposal->kelas?->nama_kelas ?? '–'); ?></div>
                        <div class="text-muted" style="font-size:11px"><?php echo e($proposal->kelas?->mataPelajaran?->nama ?? '–'); ?></div>
                    </td>
                    <td>
                        <?php if($proposal->pertemuan_ke): ?>
                        <span style="background:var(--soft-primary-bg);color:var(--soft-primary-text);padding:3px 9px;border-radius:7px;font-size:11px;font-weight:600">
                            Ke-<?php echo e($proposal->pertemuan_ke); ?>

                        </span>
                        <?php else: ?>
                        <span class="text-muted" style="font-size:12px">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div style="font-size:13px"><?php echo e($proposal->proposerName()); ?></div>
                        <div class="text-muted" style="font-size:11px"><?php echo e($proposal->proposed_by_type === 'guru' ? 'Guru' : 'Siswa'); ?></div>
                    </td>
                    <td>
                        <div class="fw-semibold" style="font-size:13px"><?php echo e($proposal->tanggal ? $proposal->tanggal->format('d M Y') : '–'); ?></div>
                        <div class="text-muted" style="font-size:11px"><i class="bi bi-clock me-1"></i><?php echo e(substr($proposal->jam_mulai,0,5) ?? '–'); ?> – <?php echo e(substr($proposal->jam_selesai,0,5) ?? '–'); ?></div>
                    </td>
                    <td>
                        <span style="background:<?php echo e($st['bg']); ?>;color:<?php echo e($st['color']); ?>;padding:4px 10px;border-radius:8px;font-size:11px;font-weight:600"><?php echo e($st['label']); ?></span>
                    </td>
                    <td>
                        <div style="font-size:12px">
                            <div class="mb-1"><i class="bi bi-people-fill me-1"></i><?php echo e($proposal->approvedCount()); ?>/<?php echo e($proposal->approvals->count()); ?> setuju</div>
                            <?php if($proposal->pendingCount() > 0): ?>
                            <div class="text-muted" style="font-size:11px"><?php echo e($proposal->pendingCount()); ?> menunggu</div>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="text-center">
                        <?php if($proposal->status === 'pending' && $myApproval && $myApproval->status === 'pending'): ?>
                        <div class="d-flex justify-content-center gap-1">
                            <button onclick="respondProposal(<?php echo e($proposal->id); ?>, 'approved')" class="btn btn-sm btn-success" style="border-radius:8px;padding:5px 10px">
                                <i class="bi bi-check-lg"></i>
                            </button>
                            <button onclick="respondProposal(<?php echo e($proposal->id); ?>, 'rejected')" class="btn btn-sm btn-danger" style="border-radius:8px;padding:5px 10px">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        <?php elseif($proposal->status === 'approved'): ?>
                        <span class="text-success" style="font-size:12px"><i class="bi bi-check-circle-fill me-1"></i>Jadwal dibuat</span>
                        <?php elseif($proposal->status === 'rejected'): ?>
                        <span class="text-danger" style="font-size:12px"><i class="bi bi-x-circle-fill me-1"></i>Ditolak</span>
                        <?php else: ?>
                        <span class="text-muted" style="font-size:12px">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php if($proposals->hasPages()): ?>
    <div class="mt-4 d-flex justify-content-center"><?php echo e($proposals->links()); ?></div>
    <?php endif; ?>
    <?php endif; ?>
</div>


<div class="modal fade" id="proposalModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius:18px;overflow:hidden">
            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#461256,#68117e);color:#fff">
                <h6 class="modal-title fw-bold"><i class="bi bi-calendar-plus me-2"></i>Ajukan Jadwal Baru</h6>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="background:var(--input-bg)">
                <form id="proposalForm">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="font-size:12px">Kelas <span class="text-danger">*</span></label>
                            <select id="class_id" name="class_id" class="form-select" style="border-radius:10px" required onchange="onClassChange(this.value)">
                                <option value="">— Pilih Kelas —</option>
                                <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($c->id); ?>"
                                    data-jenis="<?php echo e($c->jenis); ?>"
                                    data-link="<?php echo e($c->link_zoom ?? ''); ?>">
                                    <?php echo e($c->nama_kelas); ?> — <?php echo e($c->mataPelajaran?->nama ?? ''); ?> (<?php echo e($c->guru?->name ?? '—'); ?>)
                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        
                        <div class="col-12" id="pertemuanField" style="display:none">
                            <label class="form-label fw-semibold" style="font-size:12px">Pertemuan Ke- <span class="text-muted">(opsional)</span></label>
                            <select id="pertemuan_ke" name="pertemuan_ke" class="form-select" style="border-radius:10px">
                                <option value="">— Tidak ditentukan —</option>
                            </select>
                            <div id="pertemuanInfo" class="mt-1" style="font-size:11px;color:var(--text-muted)"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:12px">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" id="tanggal" name="tanggal" class="form-control" style="border-radius:10px" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold" style="font-size:12px">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" id="jam_mulai" name="jam_mulai" class="form-control" style="border-radius:10px" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold" style="font-size:12px">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time" id="jam_selesai" name="jam_selesai" class="form-control" style="border-radius:10px" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:12px">Jenis <span class="text-danger">*</span></label>
                            <select id="jenis" name="jenis" class="form-select" style="border-radius:10px" onchange="toggleJenis()" required>
                                <option value="offline">📍 Offline</option>
                                <option value="online">🌐 Online</option>
                                <option value="private">🔒 Private</option>
                            </select>
                        </div>
                        <div class="col-md-6" id="ruanganField">
                            <label class="form-label fw-semibold" style="font-size:12px">Ruangan <span class="text-muted">(opsional)</span></label>
                            <input type="text" id="ruangan" name="ruangan" class="form-control" placeholder="cth: Ruang A1" style="border-radius:10px">
                        </div>
                        <div class="col-12" id="linkField" style="display:none">
                            <label class="form-label fw-semibold" style="font-size:12px">Link Meeting</label>
                            <input type="url" id="link_meeting" name="link_meeting" class="form-control" placeholder="https://meet.google.com/..." style="border-radius:10px">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 p-4" style="background:var(--input-bg)">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius:10px"><i class="bi bi-x me-1"></i>Batal</button>
                <button type="button" class="btn btn-primary px-5 fw-semibold" id="saveProposalBtn" onclick="saveProposal()" style="border-radius:10px"><i class="bi bi-check-lg me-1"></i>Ajukan</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function toggleJenis() {
    const jenis = document.getElementById('jenis').value;
    document.getElementById('ruanganField').style.display = (jenis === 'offline' || jenis === 'private') ? 'block' : 'none';
    document.getElementById('linkField').style.display = jenis === 'online' ? 'block' : 'none';
}

function onClassChange(classId) {
    const pertemuanField = document.getElementById('pertemuanField');
    const pertemuanSelect = document.getElementById('pertemuan_ke');
    const pertemuanInfo = document.getElementById('pertemuanInfo');

    if (!classId) {
        pertemuanField.style.display = 'none';
        pertemuanSelect.innerHTML = '<option value="">— Tidak ditentukan —</option>';
        return;
    }

    pertemuanInfo.textContent = 'Memuat data pertemuan...';
    pertemuanField.style.display = 'block';
    pertemuanSelect.innerHTML = '<option value="">Memuat...</option>';
    pertemuanSelect.disabled = true;

    fetch(`/siswa/schedule-agreements/class/${classId}/meetings`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
    .then(res => {
        pertemuanSelect.disabled = false;
        if (!res.success || !res.meetings || !res.meetings.length) {
            pertemuanField.style.display = 'none';
            pertemuanInfo.textContent = '';
            return;
        }
        const total = res.jumlah_pertemuan || 0;
        let options = '<option value="">— Tidak ditentukan —</option>';
        let available = 0, done = 0;
        res.meetings.forEach(m => {
            if (m.status === 'done') {
                options += `<option value="${m.no}" disabled style="color:#ef4444">Pertemuan ke-${m.no} (Sudah selesai — absensi terkunci)</option>`;
                done++;
            } else if (m.status === 'scheduled') {
                options += `<option value="${m.no}">Pertemuan ke-${m.no} (Sudah ada jadwal)</option>`;
                available++;
            } else {
                options += `<option value="${m.no}">Pertemuan ke-${m.no}</option>`;
                available++;
            }
        });
        pertemuanSelect.innerHTML = options;
        pertemuanInfo.innerHTML = `<i class="bi bi-info-circle me-1"></i>Total ${total} pertemuan · ${available} tersedia · <span style="color:#ef4444">${done} terkunci</span>`;
    })
    .catch(() => {
        pertemuanSelect.disabled = false;
        pertemuanSelect.innerHTML = '<option value="">— Tidak ditentukan —</option>';
        pertemuanInfo.textContent = 'Gagal memuat data pertemuan.';
    });
}

function openProposalModal() {
    document.getElementById('proposalForm').reset();
    document.getElementById('class_id').value = '';
    document.getElementById('jenis').value = 'offline';
    document.getElementById('pertemuanField').style.display = 'none';
    document.getElementById('pertemuanInfo').textContent = '';
    toggleJenis();
    new bootstrap.Modal('#proposalModal').show();
}

function saveProposal() {
    const form = document.getElementById('proposalForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const btn = document.getElementById('saveProposalBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';

    $.ajax({
        url: '<?php echo e(route('siswa.schedule-agreements.store')); ?>',
        method: 'POST',
        data: $('#proposalForm').serialize() + '&_token=' + document.querySelector('meta[name=csrf-token]').content,
        success(res) {
            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('proposalModal'))?.hide();
                showToast(res.message, 'success');
                setTimeout(() => location.reload(), 1200);
            } else {
                showToast(res.message || 'Terjadi kesalahan.', 'error');
            }
        },
        error(xhr) {
            const errors = xhr.responseJSON?.errors;
            const msg = errors ? Object.values(errors).flat().join('; ') : (xhr.responseJSON?.message ?? 'Terjadi kesalahan.');
            showToast(msg, 'error');
        },
        complete() { btn.disabled = false; btn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Ajukan'; }
    });
}

function respondProposal(proposalId, status) {
    const actionText = status === 'approved' ? 'setujui' : 'tolak';
    confirmAction(`Anda yakin ingin ${actionText} proposal jadwal ini?`, function() {
        $.ajax({
            url: status === 'approved' ? `/siswa/schedule-agreements/${proposalId}/approve` : `/siswa/schedule-agreements/${proposalId}/reject`,
            method: 'POST',
            data: { _token: document.querySelector('meta[name=csrf-token]').content },
            success(res) {
                if (res.success) {
                    showToast(res.message, 'success');
                    setTimeout(() => location.reload(), 1200);
                } else {
                    showToast(res.message || 'Terjadi kesalahan.', 'error');
                }
            },
            error(xhr) {
                const msg = xhr.responseJSON?.message || 'Terjadi kesalahan.';
                showToast(msg, 'error');
            }
        });
    }, null, {title: actionText.charAt(0).toUpperCase() + actionText.slice(1) + ' Jadwal', okText:'Ya, ' + actionText});
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Edu Juanda Pratama\Downloads\smart-center-indonesia\smart-center-indonesia\resources\views/siswa/schedule-agreements/index.blade.php ENDPATH**/ ?>