<?php $__env->startSection('title', 'Approve & Biaya — ' . $registration->name); ?>
<?php $__env->startSection('page-title', 'Approve & Biaya Pendaftaran'); ?>

<?php
$teachersJson = $teachers->map(fn($t) => [
    'id'          => $t->id,
    'name'        => $t->name,
    'jenis_guru'  => $t->jenis_guru,
    'salary_base' => $t->salary_base,
]);
?>

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
                    <div class="d-flex flex-wrap gap-2">
                        <?php $__currentLoopData = $registration->interests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $int): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-2"
                              style="background:var(--card-bg);border:1px solid var(--card-border);font-size:.8rem;color:var(--text-primary)">
                            <i class="bi bi-check2 text-primary"></i><?php echo e($int); ?>

                        </span>
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

            
            <?php
                $allInterests        = $registration->interests ?? [];
                if (empty($allInterests) && $registration->program) {
                    $allInterests = [$registration->program];
                }
                $savedSessions       = $registration->interest_sessions       ?? [];
                $savedTeachers       = $registration->interest_teachers       ?? [];
                $savedTeacherHonor   = $registration->interest_teacher_honor  ?? [];
                $savedTeacherSesi    = $registration->interest_teacher_sesi   ?? [];
                $teachersById        = $teachers->keyBy('id');
            ?>
            <?php if(!empty($allInterests)): ?>
            <div class="mb-4 p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                <div class="fw-semibold mb-3" style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em">
                    <i class="bi bi-bookmark-star me-1" style="color:var(--primary)"></i>Mata Pelajaran — Guru &amp; Sesi
                </div>
                <div class="d-flex flex-column gap-3" id="subjectRows">
                    <?php $__currentLoopData = $allInterests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $prog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $defaultSesi        = $savedSessions[$prog]     ?? 8;
                        $defaultTeacher     = $savedTeachers[$prog]     ?? null;
                        $defaultHonor       = $savedTeacherHonor[$prog] ?? '';
                        $defaultTeacherSesi = $savedTeacherSesi[$prog]  ?? 8;
                        $savedTeacherObj    = $defaultTeacher ? ($teachersById[$defaultTeacher] ?? null) : null;
                        $isFreelance        = $savedTeacherObj && $savedTeacherObj->jenis_guru === 'freelance';
                    ?>
                    <div class="subject-row p-2 rounded-2" style="background:var(--card-bg);border:1px solid var(--card-border)">

                        
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div style="width:24px;height:24px;border-radius:6px;background:linear-gradient(135deg,#260632,#c84ddf);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.68rem;color:white;font-weight:700">
                                <?php echo e($idx + 1); ?>

                            </div>
                            <span class="fw-semibold" style="font-size:.85rem;color:var(--text-primary)"><?php echo e($prog); ?></span>
                        </div>

                        
                        <div class="d-flex gap-2 align-items-center">
                            <div class="flex-grow-1">
                                <div class="text-muted mb-1" style="font-size:.7rem;font-weight:600;text-transform:uppercase;letter-spacing:.04em">Guru Pengajar</div>
                                <select class="form-select form-select-sm guru-select"
                                        data-subject="<?php echo e($prog); ?>"
                                        onchange="onGuruChange(this)"
                                        style="font-size:.82rem">
                                    <option value="">— Pilih Guru —</option>
                                    <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($teacher->id); ?>"
                                            data-jenis="<?php echo e($teacher->jenis_guru); ?>"
                                            data-salary="<?php echo e($teacher->salary_base ?? 0); ?>"
                                            <?php echo e($defaultTeacher == $teacher->id ? 'selected' : ''); ?>>
                                        <?php echo e($teacher->name); ?><?php if($teacher->jenis_guru === 'freelance'): ?> <span style="font-size:.7rem">(Freelance)</span><?php elseif($teacher->jenis_guru): ?> (<?php echo e(ucfirst($teacher->jenis_guru)); ?>)<?php endif; ?>
                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div style="flex-shrink:0">
                                <div class="text-muted mb-1" style="font-size:.7rem;font-weight:600;text-transform:uppercase;letter-spacing:.04em">Sesi Siswa</div>
                                <div class="input-group input-group-sm" style="width:105px">
                                    <span class="input-group-text" style="font-size:.72rem;padding:4px 7px;background:var(--input-bg);border-color:var(--card-border);color:var(--text-muted)">Sesi</span>
                                    <input type="number"
                                           class="form-control form-control-sm sesi-input"
                                           data-subject="<?php echo e($prog); ?>"
                                           value="<?php echo e($defaultSesi); ?>"
                                           min="1" max="999"
                                           oninput="recalcSesiTotal()"
                                           style="font-size:.85rem;font-weight:600;text-align:center;background:var(--input-bg);color:var(--text-primary);border-color:var(--card-border)">
                                </div>
                            </div>
                        </div>

                        
                        <div class="freelance-fields mt-2 p-2 rounded-2"
                             data-subject="<?php echo e($prog); ?>"
                             style="display:<?php echo e($isFreelance ? '' : 'none'); ?>;background:rgba(245,158,11,.06);border:1px solid rgba(245,158,11,.25)">
                            <div class="d-flex align-items-center gap-1 mb-2">
                                <i class="bi bi-coin" style="color:#d97706;font-size:.8rem"></i>
                                <span class="fw-semibold" style="font-size:.72rem;color:#92400e;text-transform:uppercase;letter-spacing:.04em">Ketentuan Guru Freelance</span>
                            </div>
                            <div class="d-flex gap-2">
                                
                                <div class="flex-grow-1">
                                    <label class="text-muted mb-1 d-block" style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.04em">
                                        <i class="bi bi-cash-coin me-1"></i>Honor / Sesi Guru (Rp)
                                    </label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text" style="font-size:.72rem;background:var(--input-bg);border-color:rgba(245,158,11,.4);color:#92400e;padding:4px 7px">Rp</span>
                                        <input type="number"
                                               class="form-control form-control-sm honor-sesi-input"
                                               data-subject="<?php echo e($prog); ?>"
                                               value="<?php echo e($defaultHonor); ?>"
                                               min="0"
                                               placeholder="Contoh: 150000"
                                               style="font-size:.83rem;background:var(--input-bg);color:var(--text-primary);border-color:rgba(245,158,11,.4)">
                                    </div>
                                    <div class="text-muted mt-1" style="font-size:.68rem">Nominal yang dibayar ke guru per sesi mengajar</div>
                                </div>
                                
                                <div style="flex-shrink:0;min-width:110px">
                                    <label class="text-muted mb-1 d-block" style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.04em">
                                        <i class="bi bi-calendar-check me-1"></i>Total Sesi Guru
                                    </label>
                                    <div class="input-group input-group-sm">
                                        <input type="number"
                                               class="form-control form-control-sm sesi-guru-input"
                                               data-subject="<?php echo e($prog); ?>"
                                               value="<?php echo e($defaultTeacherSesi); ?>"
                                               min="1" max="999"
                                               placeholder="8"
                                               style="font-size:.83rem;font-weight:600;text-align:center;background:var(--input-bg);color:var(--text-primary);border-color:rgba(245,158,11,.4)">
                                        <span class="input-group-text" style="font-size:.72rem;background:var(--input-bg);border-color:rgba(245,158,11,.4);color:#92400e;padding:4px 7px">sesi</span>
                                    </div>
                                    <div class="text-muted mt-1" style="font-size:.68rem">Total sesi mengajar guru ini</div>
                                </div>
                            </div>
                            
                            <div class="mt-2 pt-2" style="border-top:1px solid rgba(245,158,11,.2)">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="text-muted" style="font-size:.72rem">Estimasi total honor guru:</span>
                                    <span class="fw-bold honor-total-label" data-subject="<?php echo e($prog); ?>" style="font-size:.8rem;color:#92400e">Rp —</span>
                                </div>
                            </div>
                        </div>

                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-3 pt-2" style="border-top:1px solid var(--card-border)">
                    <span class="text-muted" style="font-size:.72rem"><i class="bi bi-info-circle me-1"></i>Setiap mata pelajaran bisa memiliki guru berbeda</span>
                    <span class="fw-bold" style="font-size:.82rem;color:var(--primary)">
                        Total: <span id="totalSesiSum">0</span> sesi siswa
                    </span>
                </div>
            </div>
            <?php endif; ?>

            
            <div class="mb-2">
                <label class="form-label fw-semibold" style="font-size:.85rem">Total Biaya Program (Rp) <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text fw-semibold" style="font-size:.85rem">Rp</span>
                    <input type="number" id="totalBiaya" class="form-control fw-semibold"
                           placeholder="0" min="0" style="font-size:.95rem">
                </div>
                <div class="form-text">Nominal yang akan ditagihkan kepada siswa.</div>
            </div>
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
    <input type="hidden" name="total_biaya"    id="fs_total_biaya">
    <input type="hidden" name="total_sessions" id="fs_total_sessions">
    <div id="fs_interest_sessions_container"></div>
    <div id="fs_interest_teachers_container"></div>
    <div id="fs_interest_teacher_honor_container"></div>
    <div id="fs_interest_teacher_sesi_container"></div>
</form>

<form method="POST" action="<?php echo e(route('admin.registration-list.mark-lunas', $registration->id)); ?>" id="formLunas" style="display:none">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="total_biaya"    id="fl_total_biaya">
    <input type="hidden" name="total_sessions" id="fl_total_sessions">
    <div id="fl_interest_sessions_container"></div>
    <div id="fl_interest_teachers_container"></div>
    <div id="fl_interest_teacher_honor_container"></div>
    <div id="fl_interest_teacher_sesi_container"></div>
</form>

<script>
/* ── Teacher data from server ─────────────────────────────────────────── */
const teachersData = <?php echo json_encode($teachersJson, 15, 512) ?>;

function getTeacherById(id) {
    return teachersData.find(t => t.id === parseInt(id)) || null;
}

/* ── Freelance toggle ─────────────────────────────────────────────────── */
function onGuruChange(selectEl) {
    const subj       = selectEl.dataset.subject;
    const teacherId  = selectEl.value;
    const teacher    = teacherId ? getTeacherById(teacherId) : null;
    const isFreelance = teacher && teacher.jenis_guru === 'freelance';

    const freelanceBox = document.querySelector(`.freelance-fields[data-subject="${CSS.escape(subj)}"]`);
    if (freelanceBox) {
        freelanceBox.style.display = isFreelance ? '' : 'none';
        // If freelance and honor is empty, pre-fill with teacher's salary_base if available
        if (isFreelance && teacher.salary_base) {
            const honorInput = freelanceBox.querySelector('.honor-sesi-input');
            if (honorInput && !honorInput.value) {
                honorInput.value = teacher.salary_base;
            }
        }
        updateHonorTotal(subj);
    }
}

function updateHonorTotal(subj) {
    const freelanceBox = document.querySelector(`.freelance-fields[data-subject="${CSS.escape(subj)}"]`);
    if (!freelanceBox || freelanceBox.style.display === 'none') return;

    const honor = parseFloat(freelanceBox.querySelector('.honor-sesi-input')?.value || 0);
    const sesi  = parseInt(freelanceBox.querySelector('.sesi-guru-input')?.value || 0);
    const total = honor * sesi;

    const label = freelanceBox.querySelector('.honor-total-label');
    if (label) {
        label.textContent = total > 0
            ? 'Rp ' + new Intl.NumberFormat('id-ID').format(total)
            : 'Rp —';
    }
}

/* ── Session helpers ──────────────────────────────────────────────────── */
function getTotalSesiFromInputs() {
    let total = 0;
    document.querySelectorAll('.sesi-input').forEach(inp => {
        total += parseInt(inp.value || 0);
    });
    return total;
}

function recalcSesiTotal() {
    const sumEl = document.getElementById('totalSesiSum');
    if (sumEl) sumEl.textContent = getTotalSesiFromInputs();
}

function getInterestSessions() {
    const result = {};
    document.querySelectorAll('.sesi-input').forEach(inp => {
        const subj = inp.dataset.subject;
        if (subj) result[subj] = parseInt(inp.value || 0);
    });
    return result;
}

/* ── Teacher helpers ──────────────────────────────────────────────────── */
function getInterestTeachers() {
    const result = {};
    document.querySelectorAll('.guru-select').forEach(sel => {
        const subj = sel.dataset.subject;
        if (subj && sel.value) result[subj] = parseInt(sel.value);
    });
    return result;
}

/* ── Freelance helpers ────────────────────────────────────────────────── */
function getInterestTeacherHonor() {
    const result = {};
    document.querySelectorAll('.honor-sesi-input').forEach(inp => {
        const subj       = inp.dataset.subject;
        const parentBox  = document.querySelector(`.freelance-fields[data-subject="${CSS.escape(subj)}"]`);
        if (subj && parentBox && parentBox.style.display !== 'none' && inp.value !== '') {
            result[subj] = parseFloat(inp.value);
        }
    });
    return result;
}

function getInterestTeacherSesi() {
    const result = {};
    document.querySelectorAll('.sesi-guru-input').forEach(inp => {
        const subj       = inp.dataset.subject;
        const parentBox  = document.querySelector(`.freelance-fields[data-subject="${CSS.escape(subj)}"]`);
        if (subj && parentBox && parentBox.style.display !== 'none' && inp.value !== '') {
            result[subj] = parseInt(inp.value);
        }
    });
    return result;
}

/* ── Form injection helpers ───────────────────────────────────────────── */
function injectHidden(containerId, name, map) {
    const container = document.getElementById(containerId);
    if (!container) return;
    container.innerHTML = '';
    Object.entries(map).forEach(([key, val]) => {
        const inp  = document.createElement('input');
        inp.type   = 'hidden';
        inp.name   = name + '[' + key + ']';
        inp.value  = val;
        container.appendChild(inp);
    });
}

function injectInterestSessionsIntoForm(prefix) {
    injectHidden(prefix + '_interest_sessions_container', 'interest_sessions', getInterestSessions());
}

function injectInterestTeachersIntoForm(prefix) {
    injectHidden(prefix + '_interest_teachers_container', 'interest_teachers', getInterestTeachers());
}

function injectFreelanceFieldsIntoForm(prefix) {
    injectHidden(prefix + '_interest_teacher_honor_container', 'interest_teacher_honor', getInterestTeacherHonor());
    injectHidden(prefix + '_interest_teacher_sesi_container',  'interest_teacher_sesi',  getInterestTeacherSesi());
}

/* ── Validation & submit ──────────────────────────────────────────────── */
function collectForm() {
    const totalBiaya = document.getElementById('totalBiaya').value;
    const totalSesi  = getTotalSesiFromInputs();

    if (!totalBiaya || parseFloat(totalBiaya) < 0) {
        showToast('Masukkan total biaya program.', 'error');
        return null;
    }

    const selects = document.querySelectorAll('.guru-select');
    const noGuru  = [...selects].filter(s => !s.value);
    if (selects.length > 0 && noGuru.length === selects.length) {
        showToast('Pilih minimal satu guru untuk mata pelajaran.', 'error');
        return null;
    }

    // Validate freelance fields: if freelance guru selected, honor per sesi is required
    let freelanceError = null;
    document.querySelectorAll('.freelance-fields').forEach(box => {
        if (box.style.display === 'none') return;
        const honorInput = box.querySelector('.honor-sesi-input');
        const sesiInput  = box.querySelector('.sesi-guru-input');
        if (!honorInput.value || parseFloat(honorInput.value) <= 0) {
            freelanceError = 'Masukkan honor per sesi untuk semua guru freelance.';
        }
        if (!sesiInput.value || parseInt(sesiInput.value) < 1) {
            freelanceError = 'Masukkan total sesi mengajar untuk semua guru freelance.';
        }
    });
    if (freelanceError) {
        showToast(freelanceError, 'error');
        return null;
    }

    return { totalBiaya, totalSesi };
}

function submitAction(action) {
    const data = collectForm();
    if (!data) return;

    const label = action === 'send'
        ? 'Kirim invoice ke siswa'
        : 'Tandai pembayaran sebagai <strong>Lunas</strong>';

    confirmAction(label + '?', function() {
        const formId = action === 'send' ? 'formSend'  : 'formLunas';
        const prefix = action === 'send' ? 'fs'        : 'fl';

        document.getElementById(prefix + '_total_biaya').value    = data.totalBiaya;
        document.getElementById(prefix + '_total_sessions').value = data.totalSesi;

        injectInterestSessionsIntoForm(prefix);
        injectInterestTeachersIntoForm(prefix);
        injectFreelanceFieldsIntoForm(prefix);

        document.getElementById(formId).submit();
    }, null, {
        title:    action === 'send' ? 'Kirim Invoice'                        : 'Tandai Lunas',
        okText:   action === 'send' ? '<i class="bi bi-send me-1"></i>Kirim Invoice' : '<i class="bi bi-check-circle me-1"></i>Lunas',
        btnClass: action === 'send' ? 'btn-primary'                          : 'btn-success',
        type: 'warning'
    });
}

function confirmReject() {
    confirmAction('Pendaftaran ini akan <strong>ditolak</strong>. Lanjutkan?', function() {
        document.getElementById('rejectForm').submit();
    }, null, { title: 'Tolak Pendaftaran', okText: '<i class="bi bi-x-circle me-1"></i>Tolak', btnClass: 'btn-danger' });
}

/* ── Init ─────────────────────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', function() {
    recalcSesiTotal();

    // Trigger onGuruChange for pre-selected teachers on page load
    document.querySelectorAll('.guru-select').forEach(sel => {
        if (sel.value) onGuruChange(sel);
    });

    // Live recalc honor total on input
    document.querySelectorAll('.honor-sesi-input, .sesi-guru-input').forEach(inp => {
        inp.addEventListener('input', () => updateHonorTotal(inp.dataset.subject));
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/registration/approve.blade.php ENDPATH**/ ?>