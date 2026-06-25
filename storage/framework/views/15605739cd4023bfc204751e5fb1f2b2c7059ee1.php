<?php $__env->startSection('title', 'Approve & Biaya — ' . $registration->name); ?>
<?php $__env->startSection('page-title', 'Approve & Biaya Pendaftaran'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-up">


<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.registration-list.index')); ?>" class="text-decoration-none">Daftar Registrasi</a></li>
        <li class="breadcrumb-item active">Approve &amp; Biaya</li>
    </ol>
</nav>

<?php if(session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show mb-3"><i class="bi bi-x-circle me-2"></i><?php echo e(session('error')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="row g-4">

    
    <div class="col-lg-5">
        <div class="dashboard-card h-100">
            <div class="d-flex align-items-center gap-2 mb-4">
                <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#260632,#c84ddf);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="bi bi-person-vcard-fill text-white" style="font-size:1rem"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">Detail Pengajuan</h6>
                    <div class="text-muted" style="font-size:.75rem">Data pendaftaran calon siswa</div>
                </div>
            </div>

            
            <div class="text-center mb-4 p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($registration->name)); ?>&background=<?php echo e($registration->gender==='P'?'ec4899':'c84ddf'); ?>&color=fff&size=72"
                     class="rounded-circle mb-2" width="72" height="72"
                     style="border:3px solid var(--card-border)">
                <div class="fw-bold" style="font-size:1rem"><?php echo e($registration->name); ?></div>
                <div class="text-muted" style="font-size:.78rem">
                    <code style="background:var(--card-bg);padding:2px 7px;border-radius:6px"><?php echo e($registration->no_reg); ?></code>
                </div>
                <div class="mt-2">
                    <?php
                        $statusMap = [
                            'pending'  => ['var(--soft-warning-bg)','var(--soft-warning-text)','Menunggu'],
                            'verified' => ['var(--soft-success-bg)','var(--soft-success-text)','Terverifikasi'],
                            'rejected' => ['var(--soft-danger-bg)','var(--soft-danger-text)','Ditolak'],
                        ];
                        $sc = $statusMap[$registration->status] ?? ['var(--soft-muted-bg)','var(--text-muted)','–'];
                    ?>
                    <span class="badge" style="background:<?php echo e($sc[0]); ?>;color:<?php echo e($sc[1]); ?>;padding:4px 12px;border-radius:20px;font-size:.73rem"><?php echo e($sc[2]); ?></span>
                </div>
            </div>

            
            <div class="d-flex flex-column gap-3">

                
                <div>
                    <div class="fw-semibold mb-2" style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em">Data Diri</div>
                    <div class="d-flex flex-column gap-1" style="font-size:.83rem">
                        <?php if($registration->phone): ?>
                        <div class="d-flex gap-2"><span class="text-muted" style="min-width:100px">No. HP</span> <span><?php echo e($registration->phone); ?></span></div>
                        <?php endif; ?>
                        <?php if($registration->gender): ?>
                        <div class="d-flex gap-2"><span class="text-muted" style="min-width:100px">Kelamin</span> <span><?php echo e($registration->gender==='L'?'Laki-laki':($registration->gender==='P'?'Perempuan':$registration->gender)); ?></span></div>
                        <?php endif; ?>
                        <?php if($registration->birth_date): ?>
                        <div class="d-flex gap-2"><span class="text-muted" style="min-width:100px">Tgl Lahir</span> <span><?php echo e($registration->birth_date->format('d M Y')); ?></span></div>
                        <?php endif; ?>
                        <?php if($registration->address): ?>
                        <div class="d-flex gap-2"><span class="text-muted" style="min-width:100px">Alamat</span> <span><?php echo e($registration->address); ?></span></div>
                        <?php endif; ?>
                        <?php if($registration->parent_name): ?>
                        <div class="d-flex gap-2"><span class="text-muted" style="min-width:100px">Orang Tua</span> <span><?php echo e($registration->parent_name); ?></span></div>
                        <?php endif; ?>
                        <?php if($registration->parent_phone): ?>
                        <div class="d-flex gap-2"><span class="text-muted" style="min-width:100px">HP Ortu</span> <span><?php echo e($registration->parent_phone); ?></span></div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div style="border-top:1px solid var(--card-border);padding-top:12px">
                    <div class="fw-semibold mb-2" style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em">Info Program</div>
                    <div class="d-flex flex-column gap-1" style="font-size:.83rem">
                        <?php if($registration->program): ?>
                        <div class="d-flex gap-2"><span class="text-muted" style="min-width:100px">Program</span> <strong><?php echo e($registration->program); ?></strong></div>
                        <?php endif; ?>
                        <?php if($registration->system): ?>
                        <div class="d-flex gap-2"><span class="text-muted" style="min-width:100px">Sistem</span> <span><?php echo e($registration->system); ?></span></div>
                        <?php endif; ?>
                        <?php if($registration->learning_place): ?>
                        <div class="d-flex gap-2"><span class="text-muted" style="min-width:100px">Tempat</span> <span><?php echo e($registration->learning_place); ?></span></div>
                        <?php endif; ?>
                        <?php if($registration->branch): ?>
                        <div class="d-flex gap-2"><span class="text-muted" style="min-width:100px">Cabang</span> <span><?php echo e($registration->branch); ?></span></div>
                        <?php endif; ?>
                        <?php if($registration->start_date): ?>
                        <div class="d-flex gap-2"><span class="text-muted" style="min-width:100px">Tgl Mulai</span> <span><?php echo e($registration->start_date->format('d M Y')); ?></span></div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <?php if(($registration->day_preferences && count($registration->day_preferences)) || $registration->schedule_time): ?>
                <div style="border-top:1px solid var(--card-border);padding-top:12px">
                    <div class="fw-semibold mb-2" style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em">Jadwal Belajar</div>
                    <div class="d-flex flex-wrap gap-1 mb-1">
                        <?php $__currentLoopData = $registration->day_preferences ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="badge" style="background:var(--soft-info-bg);color:var(--soft-info-text);padding:3px 9px;border-radius:10px;font-size:.73rem"><?php echo e($day); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php if($registration->schedule_time): ?>
                    <div class="text-muted" style="font-size:.8rem;white-space:pre-line">🕐 <?php echo e($registration->schedule_time); ?></div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                
                <?php if($registration->interests && count($registration->interests)): ?>
                <div style="border-top:1px solid var(--card-border);padding-top:12px">
                    <div class="fw-semibold mb-2" style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em">Program Diminati</div>
                    <div class="d-flex flex-column gap-2">
                        <?php $__currentLoopData = $registration->interests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $int): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $harga = $coursePrices[$int] ?? null; ?>
                        <div class="d-flex align-items-center justify-content-between px-2 py-1 rounded-2" style="background:var(--card-bg);border:1px solid var(--card-border)">
                            <span style="font-size:.8rem;color:var(--text-primary)">
                                <i class="bi bi-check2 text-primary me-1"></i><?php echo e($int); ?>

                            </span>
                            <?php if($harga !== null): ?>
                            <span class="fw-bold" style="font-size:.8rem;color:var(--primary)">
                                Rp <?php echo e(number_format($harga, 0, ',', '.')); ?>

                            </span>
                            <?php else: ?>
                            <span class="text-muted" style="font-size:.75rem">Harga belum diset</span>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>

                
                <?php if($registration->notes): ?>
                <div style="border-top:1px solid var(--card-border);padding-top:12px">
                    <div class="fw-semibold mb-1" style="font-size:.75rem;color:var(--soft-warning-text);text-transform:uppercase;letter-spacing:.06em"><i class="bi bi-chat-text me-1"></i>Catatan</div>
                    <p class="mb-0" style="font-size:.82rem;color:var(--text-primary)"><?php echo e($registration->notes); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="col-lg-7">

        
        <?php if(!$registration->student_id): ?>
        <div class="alert mb-3" style="background:var(--soft-warning-bg);border:1px solid rgba(245,158,11,.2);border-radius:12px;color:var(--soft-warning-text)">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Siswa ini belum memiliki akun terdaftar. Verifikasi terlebih dahulu dari dashboard sebelum menetapkan guru dan biaya.
        </div>
        <?php endif; ?>

        <div class="dashboard-card">
            <div class="d-flex align-items-center gap-2 mb-4">
                <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#166534,#22c55e);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="bi bi-person-check-fill text-white" style="font-size:1rem"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">Tunjuk Pengajar</h6>
                    <div class="text-muted" style="font-size:.75rem">Pilih guru dan tentukan biaya program</div>
                </div>
            </div>

            <form id="approveForm">
                <?php echo csrf_field(); ?>

                
                <div class="mb-4">
                    <label class="form-label fw-semibold" style="font-size:.85rem">Pilih Guru <span class="text-danger">*</span></label>
                    <select id="teacherSelect" name="teacher_id" class="form-select" onchange="onTeacherChange(this)" required>
                        <option value="">— Pilih Guru —</option>
                        <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($teacher->id); ?>"
                                data-jenis="<?php echo e($teacher->jenis_guru ?? 'kontrak'); ?>"
                                data-salary="<?php echo e($teacher->salary_base ?? 0); ?>"
                                data-name="<?php echo e($teacher->name); ?>"
                                data-subjects="<?php echo e(implode(', ', $teacher->subjects ?? [])); ?>">
                            <?php echo e($teacher->name); ?>

                            <?php if($teacher->jenis_guru): ?> — <span style="text-transform:capitalize"><?php echo e($teacher->jenis_guru); ?></span><?php endif; ?>
                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div id="teacherInfoCard" class="d-none mb-4">
                    <div class="p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div id="teacherAvatar" class="rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                 style="width:44px;height:44px;background:linear-gradient(135deg,#260632,#c84ddf);color:white;font-size:.9rem;flex-shrink:0">
                            </div>
                            <div>
                                <div id="teacherName" class="fw-semibold" style="font-size:.9rem"></div>
                                <div id="teacherJenis" class="text-muted" style="font-size:.75rem"></div>
                                <div id="teacherSubjects" class="text-muted" style="font-size:.72rem"></div>
                            </div>
                        </div>

                        
                        <div id="infoFreelance" class="d-none">
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label" style="font-size:.78rem;color:var(--text-muted)">Biaya Per Sesi (Rp)</label>
                                    <input type="number" id="biayaPerSesi" name="biaya_per_sesi" class="form-control form-control-sm"
                                           placeholder="0" min="0" oninput="recalcTotal()">
                                </div>
                                <div class="col-6">
                                    <label class="form-label" style="font-size:.78rem;color:var(--text-muted)">Jumlah Sesi</label>
                                    <input type="number" id="totalSessions" name="total_sessions" class="form-control form-control-sm"
                                           placeholder="0" min="1" value="8" oninput="recalcTotal()">
                                </div>
                                <div class="col-12">
                                    <div class="p-2 rounded-2 d-flex align-items-center justify-content-between" style="background:var(--card-bg);border:1px solid var(--card-border)">
                                        <span class="text-muted" style="font-size:.82rem">Total Biaya Per Sesi</span>
                                        <span id="totalSesiDisplay" class="fw-bold text-primary" style="font-size:.9rem">Rp 0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="mb-4">
                    <label class="form-label fw-semibold" style="font-size:.85rem">Total Biaya Program (Rp) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text fw-semibold" style="font-size:.85rem">Rp</span>
                        <input type="number" id="totalBiaya" name="total_biaya" class="form-control fw-semibold"
                               placeholder="0" min="0" required style="font-size:.95rem">
                    </div>
                    <div class="form-text">Nominal yang akan ditagihkan kepada siswa.</div>
                </div>

                
                <input type="hidden" id="h_teacher_id" name="teacher_id">
                <input type="hidden" id="h_biaya_per_sesi" name="biaya_per_sesi">
                <input type="hidden" id="h_total_sessions" name="total_sessions">
                <input type="hidden" id="h_total_biaya" name="total_biaya">

            </form>
        </div>

        
        <div class="dashboard-card mt-3">
            <h6 class="fw-bold mb-3" style="font-size:.85rem"><i class="bi bi-lightning-charge text-primary me-2"></i>Tindakan</h6>
            <div class="d-flex flex-column gap-2">

                
                <button type="button" onclick="submitAction('send')"
                        class="btn fw-semibold d-flex align-items-center gap-2 justify-content-center"
                        style="background:linear-gradient(135deg,#260632,#461256,#c84ddf);color:white;border:none;border-radius:12px;padding:13px;font-size:.88rem">
                    <i class="bi bi-send-fill" style="font-size:1rem"></i>
                    <div class="text-start">
                        <div>Kirim Invoice ke Siswa</div>
                        <div style="font-size:.72rem;opacity:.8;font-weight:400">Tagihan muncul di halaman billing siswa</div>
                    </div>
                </button>

                
                <button type="button" onclick="submitAction('lunas')"
                        class="btn fw-semibold d-flex align-items-center gap-2 justify-content-center"
                        style="background:linear-gradient(135deg,#14532d,#166534,#22c55e);color:white;border:none;border-radius:12px;padding:13px;font-size:.88rem">
                    <i class="bi bi-check-circle-fill" style="font-size:1rem"></i>
                    <div class="text-start">
                        <div>Lunas</div>
                        <div style="font-size:.72rem;opacity:.8;font-weight:400">Catat sebagai sudah dibayar penuh</div>
                    </div>
                </button>

                
                <form method="POST" action="<?php echo e(route('admin.registration-list.reject', $registration->id)); ?>" id="rejectForm">
                    <?php echo csrf_field(); ?>
                    <button type="button" onclick="confirmReject()"
                            class="btn btn-outline-danger fw-semibold w-100 d-flex align-items-center gap-2 justify-content-center"
                            style="border-radius:12px;padding:13px;font-size:.88rem">
                        <i class="bi bi-x-circle-fill" style="font-size:1rem"></i>
                        <div class="text-start">
                            <div>Tolak Pendaftaran</div>
                            <div style="font-size:.72rem;opacity:.8;font-weight:400">Pendaftaran tidak dilanjutkan</div>
                        </div>
                    </button>
                </form>

            </div>
        </div>

    </div>
</div>

</div>


<form method="POST" action="<?php echo e(route('admin.registration-list.send-invoice', $registration->id)); ?>" id="formSend" style="display:none">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="teacher_id"     id="fs_teacher_id">
    <input type="hidden" name="total_biaya"     id="fs_total_biaya">
    <input type="hidden" name="biaya_per_sesi"  id="fs_biaya_per_sesi">
    <input type="hidden" name="total_sessions"  id="fs_total_sessions">
</form>

<form method="POST" action="<?php echo e(route('admin.registration-list.mark-lunas', $registration->id)); ?>" id="formLunas" style="display:none">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="teacher_id"     id="fl_teacher_id">
    <input type="hidden" name="total_biaya"     id="fl_total_biaya">
    <input type="hidden" name="biaya_per_sesi"  id="fl_biaya_per_sesi">
    <input type="hidden" name="total_sessions"  id="fl_total_sessions">
</form>

<script>
function onTeacherChange(sel) {
    const opt = sel.options[sel.selectedIndex];
    const infoCard   = document.getElementById('teacherInfoCard');
    const infoFree   = document.getElementById('infoFreelance');

    if (!opt.value) { infoCard.classList.add('d-none'); return; }

    const jenis    = opt.dataset.jenis || 'kontrak';
    const salary   = parseFloat(opt.dataset.salary || 0);
    const name     = opt.dataset.name || '';
    const subjects = opt.dataset.subjects || '';

    infoCard.classList.remove('d-none');
    document.getElementById('teacherName').textContent     = name;
    document.getElementById('teacherJenis').textContent    = jenis.charAt(0).toUpperCase() + jenis.slice(1);
    document.getElementById('teacherSubjects').textContent = subjects || 'Semua Mapel';
    document.getElementById('teacherAvatar').textContent   = name.charAt(0).toUpperCase();

    if (jenis === 'freelance') {
        infoFree.classList.remove('d-none');
        recalcTotal();
    } else {
        infoFree.classList.add('d-none');
    }
}

function recalcTotal() {
    const bps      = parseFloat(document.getElementById('biayaPerSesi').value || 0);
    const sesi     = parseFloat(document.getElementById('totalSessions').value || 0);
    const total    = bps * sesi;
    document.getElementById('totalSesiDisplay').textContent = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('totalBiaya').value = total > 0 ? total : '';
}

function collectForm() {
    const teacherId   = document.getElementById('teacherSelect').value;
    const totalBiaya  = document.getElementById('totalBiaya').value;
    const biayaSesi   = document.getElementById('biayaPerSesi')?.value || '';
    const totalSesi   = document.getElementById('totalSessions')?.value || '';

    if (!teacherId) { showToast('Pilih guru terlebih dahulu.', 'error'); return null; }
    if (!totalBiaya || parseFloat(totalBiaya) < 0) { showToast('Masukkan total biaya program.', 'error'); return null; }

    return { teacherId, totalBiaya, biayaSesi, totalSesi };
}

function submitAction(action) {
    const data = collectForm();
    if (!data) return;

    const label = action === 'send' ? 'Kirim invoice ke siswa' : 'Tandai pembayaran sebagai <strong>Lunas</strong>';
    confirmAction(label + '?', function() {
        const formId = action === 'send' ? 'formSend' : 'formLunas';
        const prefix = action === 'send' ? 'fs' : 'fl';

        document.getElementById(prefix + '_teacher_id').value    = data.teacherId;
        document.getElementById(prefix + '_total_biaya').value   = data.totalBiaya;
        document.getElementById(prefix + '_biaya_per_sesi').value = data.biayaSesi;
        document.getElementById(prefix + '_total_sessions').value = data.totalSesi;

        document.getElementById(formId).submit();
    }, null, {
        title: action === 'send' ? 'Kirim Invoice' : 'Tandai Lunas',
        okText: action === 'send' ? '<i class="bi bi-send me-1"></i>Kirim Invoice' : '<i class="bi bi-check-circle me-1"></i>Lunas',
        btnClass: action === 'send' ? 'btn-primary' : 'btn-success',
        type: 'warning'
    });
}

function confirmReject() {
    confirmAction('Pendaftaran ini akan <strong>ditolak</strong>. Lanjutkan?', function() {
        document.getElementById('rejectForm').submit();
    }, null, { title: 'Tolak Pendaftaran', okText: '<i class="bi bi-x-circle me-1"></i>Tolak', btnClass: 'btn-danger' });
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/registration/approve.blade.php ENDPATH**/ ?>