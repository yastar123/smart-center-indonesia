<?php $__env->startSection('title', 'Detail Jadwal Sesi'); ?>
<?php $__env->startSection('page-title', 'Detail Jadwal Sesi'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.schedules.index')); ?>">Jadwal Sesi</a></li>
        <li class="breadcrumb-item active">Detail Jadwal #<?php echo e($schedule->id); ?></li>
    </ol>
</nav>


<?php
    $statusColor = match($schedule->status) {
        'selesai'     => '#10b981',
        'berlangsung' => '#f6af23',
        'dibatalkan'  => '#ef4444',
        default       => '#0ea5e9',
    };
    $statusIcon = match($schedule->status) {
        'selesai'     => 'bi-check-circle-fill',
        'berlangsung' => 'bi-play-circle-fill',
        'dibatalkan'  => 'bi-x-circle-fill',
        default       => 'bi-calendar-event-fill',
    };
    $statusLabel = match($schedule->status) {
        'selesai'     => 'Selesai',
        'berlangsung' => 'Berlangsung',
        'dibatalkan'  => 'Dibatalkan',
        default       => 'Dijadwalkan',
    };
?>

<div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-20px;top:-20px;width:160px;height:160px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div class="d-flex align-items-center gap-3">
            <div style="width:52px;height:52px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0">
                <i class="bi bi-calendar3"></i>
            </div>
            <div>
                <div style="font-size:12px;opacity:.7;margin-bottom:2px">Jadwal Sesi #<?php echo e($schedule->id); ?></div>
                <h5 class="fw-bold mb-0" style="color:white">
                    <?php echo e($schedule->paket?->nama ?? 'Tanpa Paket'); ?>

                    <span style="font-size:14px;font-weight:400;opacity:.85">— Sesi ke-<?php echo e($schedule->pertemuan_ke); ?></span>
                </h5>
                <div style="font-size:12px;opacity:.8;margin-top:3px">
                    <?php echo e($schedule->cabang?->name ?? '—'); ?>

                    <?php if($schedule->paket): ?>
                        · Total <?php echo e($schedule->paket->jumlah_pertemuan); ?> sesi
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span style="background:<?php echo e($statusColor); ?>;color:white;padding:6px 14px;border-radius:20px;font-size:13px;font-weight:600;display:inline-flex;align-items:center;gap:6px">
                <i class="bi <?php echo e($statusIcon); ?>"></i><?php echo e($statusLabel); ?>

            </span>
            <?php if($schedule->status === 'dijadwalkan'): ?>
            <a href="<?php echo e(route('admin.schedules.edit', $schedule)); ?>" class="btn btn-sm"
               style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.35)">
                <i class="bi bi-pencil me-1"></i>Edit
            </a>
            <?php endif; ?>
            <a href="<?php echo e(route('admin.schedules.index')); ?>" class="btn btn-sm"
               style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.3)">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>

<?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible fade show mb-4">
    <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row g-4">

    
    <div class="col-lg-8">

        
        <div class="dashboard-card mb-4">
            <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
                <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#0ea5e9,#0369a1);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0">
                    <i class="bi bi-clock"></i>
                </div>
                <div class="fw-bold" style="font-size:15px">Waktu & Lokasi</div>
            </div>
            <div class="row g-3">
                <div class="col-md-4 col-6">
                    <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px">Tanggal</div>
                    <div class="fw-semibold">
                        <?php echo e($schedule->tanggal ? \Carbon\Carbon::parse($schedule->tanggal)->translatedFormat('l, d F Y') : '—'); ?>

                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px">Jam</div>
                    <div class="fw-semibold">
                        <?php echo e($schedule->jam_mulai ? substr($schedule->jam_mulai,0,5) : '—'); ?>

                        <?php if($schedule->jam_selesai): ?>
                            <span class="text-muted">–</span> <?php echo e(substr($schedule->jam_selesai,0,5)); ?>

                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px">Metode</div>
                    <div class="fw-semibold">
                        <?php if($schedule->jenis === 'online'): ?>   💻 Online
                        <?php elseif($schedule->jenis === 'private'): ?> 👤 Private
                        <?php else: ?> 🏫 Offline
                        <?php endif; ?>
                    </div>
                </div>
                <?php if($schedule->ruangan): ?>
                <div class="col-md-4 col-6">
                    <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px">Ruangan</div>
                    <div class="fw-semibold"><i class="bi bi-door-open text-primary me-1"></i><?php echo e($schedule->ruangan); ?></div>
                </div>
                <?php endif; ?>
                <?php if($schedule->link_meeting): ?>
                <div class="col-12">
                    <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px">Link Meeting</div>
                    <a href="<?php echo e($schedule->link_meeting); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-camera-video me-1"></i><?php echo e($schedule->link_meeting); ?>

                    </a>
                </div>
                <?php endif; ?>
                <?php if($schedule->topik): ?>
                <div class="col-12">
                    <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px">Topik / Materi</div>
                    <div class="fw-semibold"><i class="bi bi-book text-primary me-1"></i><?php echo e($schedule->topik); ?></div>
                </div>
                <?php endif; ?>
                <?php if($schedule->catatan): ?>
                <div class="col-12">
                    <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px">Catatan</div>
                    <div style="background:var(--input-bg);border:1px solid var(--card-border);border-radius:8px;padding:10px;font-size:13px"><?php echo e($schedule->catatan); ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="dashboard-card mb-4">
            <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
                <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#10b981,#047857);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="fw-bold" style="font-size:15px">Absensi Siswa</div>
                    <div class="text-muted" style="font-size:12px">Data kehadiran siswa pada sesi ini</div>
                </div>
            </div>

            <?php if($absensi->isEmpty()): ?>
                <div class="text-center py-4">
                    <div style="font-size:36px;margin-bottom:8px">📋</div>
                    <div class="fw-semibold text-muted">Belum ada data absensi</div>
                    <div class="text-muted" style="font-size:12px;margin-top:4px">
                        <?php if($schedule->status === 'dijadwalkan'): ?>
                            Absensi akan tersedia saat sesi berlangsung
                        <?php else: ?>
                            Data absensi belum diisi untuk sesi ini
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <?php
                    $countHadir   = $absensi->where('status','hadir')->count();
                    $countIzin    = $absensi->where('status','izin')->count();
                    $countSakit   = $absensi->where('status','sakit')->count();
                    $countAlpa    = $absensi->where('status','alpa')->count();
                    $total        = $absensi->count();
                ?>
                
                <div class="row g-2 mb-3">
                    <div class="col-3">
                        <div class="text-center p-2 rounded-2" style="background:#d1fae5;color:#065f46">
                            <div class="fw-bold" style="font-size:18px"><?php echo e($countHadir); ?></div>
                            <div style="font-size:11px">Hadir</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="text-center p-2 rounded-2" style="background:#bfdbfe;color:#1e40af">
                            <div class="fw-bold" style="font-size:18px"><?php echo e($countIzin); ?></div>
                            <div style="font-size:11px">Izin</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="text-center p-2 rounded-2" style="background:#fef3c7;color:#92400e">
                            <div class="fw-bold" style="font-size:18px"><?php echo e($countSakit); ?></div>
                            <div style="font-size:11px">Sakit</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="text-center p-2 rounded-2" style="background:#fee2e2;color:#991b1b">
                            <div class="fw-bold" style="font-size:18px"><?php echo e($countAlpa); ?></div>
                            <div style="font-size:11px">Alpha</div>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0" style="font-size:13px">
                        <thead>
                            <tr style="background:var(--input-bg)">
                                <th class="fw-semibold" style="color:var(--text-muted)">Siswa</th>
                                <th class="fw-semibold text-center" style="color:var(--text-muted)">Status</th>
                                <th class="fw-semibold" style="color:var(--text-muted)">Waktu Konfirmasi</th>
                                <th class="fw-semibold" style="color:var(--text-muted)">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $absensi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold"><?php echo e($ab->siswa?->name ?? '—'); ?></div>
                                    <div class="text-muted" style="font-size:11px"><?php echo e($ab->siswa?->nis ?? ''); ?></div>
                                </td>
                                <td class="text-center">
                                    <?php
                                        $badge = match($ab->status) {
                                            'hadir'  => ['#d1fae5','#065f46','✅'],
                                            'izin'   => ['#bfdbfe','#1e40af','📋'],
                                            'sakit'  => ['#fef3c7','#92400e','🏥'],
                                            'alpa'   => ['#fee2e2','#991b1b','❌'],
                                            default  => ['#f3f4f6','#374151','⏳'],
                                        };
                                    ?>
                                    <span style="background:<?php echo e($badge[0]); ?>;color:<?php echo e($badge[1]); ?>;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600">
                                        <?php echo e($badge[2]); ?> <?php echo e(ucfirst($ab->status)); ?>

                                    </span>
                                </td>
                                <td class="text-muted" style="font-size:11px">
                                    <?php echo e($ab->siswa_konfirmasi_at ? \Carbon\Carbon::parse($ab->siswa_konfirmasi_at)->format('H:i') : '—'); ?>

                                </td>
                                <td class="text-muted" style="font-size:11px"><?php echo e($ab->catatan ?? '—'); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </div>

    
    <div class="col-lg-4">

        
        <div class="dashboard-card mb-4">
            <div class="d-flex align-items-center gap-2 mb-3 pb-3 border-bottom">
                <div style="width:28px;height:28px;border-radius:7px;background:linear-gradient(135deg,#c84ddf,#461256);display:flex;align-items:center;justify-content:center;color:white;font-size:13px;flex-shrink:0">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="fw-bold" style="font-size:14px">Paket Belajar</div>
            </div>
            <?php if($schedule->paket): ?>
            <div class="mb-2">
                <div class="fw-semibold" style="font-size:14px"><?php echo e($schedule->paket->nama); ?></div>
                <div class="text-muted" style="font-size:12px"><?php echo e($schedule->paket->deskripsi); ?></div>
            </div>
            <div class="d-flex flex-column gap-2 mt-3" style="font-size:12px">
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Sesi ini</span>
                    <span class="fw-semibold">ke-<?php echo e($schedule->pertemuan_ke); ?> / <?php echo e($schedule->paket->jumlah_pertemuan); ?></span>
                </div>
                <div class="progress" style="height:6px;border-radius:3px">
                    <?php $pct = $schedule->paket->jumlah_pertemuan > 0 ? round(($schedule->pertemuan_ke / $schedule->paket->jumlah_pertemuan) * 100) : 0; ?>
                    <div class="progress-bar" style="width:<?php echo e($pct); ?>%;background:linear-gradient(90deg,#c84ddf,#461256);border-radius:3px"></div>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Jenis</span>
                    <span class="fw-semibold"><?php echo e(ucfirst($schedule->paket->jenis ?? '—')); ?></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Tipe Kelas</span>
                    <span class="fw-semibold"><?php echo e(ucfirst($schedule->paket->tipe_kelas ?? '—')); ?></span>
                </div>
                <?php if($schedule->paket->mataPelajaran->isNotEmpty()): ?>
                <div class="d-flex justify-content-between align-items-start">
                    <span class="text-muted">Mata Pelajaran</span>
                    <span class="fw-semibold text-end"><?php echo e($schedule->paket->mataPelajaran->pluck('nama')->join(', ')); ?></span>
                </div>
                <?php endif; ?>
            </div>
            <?php else: ?>
            <div class="text-muted" style="font-size:13px">Data paket tidak tersedia</div>
            <?php endif; ?>
        </div>

        
        <div class="dashboard-card mb-4">
            <div class="d-flex align-items-center gap-2 mb-3 pb-3 border-bottom">
                <div style="width:28px;height:28px;border-radius:7px;background:linear-gradient(135deg,#10b981,#047857);display:flex;align-items:center;justify-content:center;color:white;font-size:13px;flex-shrink:0">
                    <i class="bi bi-person-badge"></i>
                </div>
                <div class="fw-bold" style="font-size:14px">Guru Pengajar</div>
            </div>
            <?php if($schedule->guru): ?>
            <div class="d-flex align-items-center gap-3">
                <img src="<?php echo e($schedule->guru->photo_url); ?>" alt="<?php echo e($schedule->guru->name); ?>"
                     style="width:48px;height:48px;border-radius:50%;object-fit:cover;border:2px solid var(--card-border)">
                <div>
                    <div class="fw-semibold" style="font-size:14px"><?php echo e($schedule->guru->name); ?></div>
                    <div class="text-muted" style="font-size:12px">NIG: <?php echo e($schedule->guru->nig ?? '—'); ?></div>
                    <?php if($schedule->guru->subjects): ?>
                    <div class="text-muted" style="font-size:11px;margin-top:2px"><?php echo e(implode(', ', $schedule->guru->subjects)); ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <?php else: ?>
            <div class="text-muted" style="font-size:13px">Belum ada guru pengajar</div>
            <?php endif; ?>
        </div>

        
        <div class="dashboard-card mb-4">
            <div class="d-flex align-items-center gap-2 mb-3 pb-3 border-bottom">
                <div style="width:28px;height:28px;border-radius:7px;background:linear-gradient(135deg,#0ea5e9,#0369a1);display:flex;align-items:center;justify-content:center;color:white;font-size:13px;flex-shrink:0">
                    <i class="bi bi-building"></i>
                </div>
                <div class="fw-bold" style="font-size:14px">Cabang</div>
            </div>
            <?php if($schedule->cabang): ?>
            <div class="fw-semibold" style="font-size:14px"><?php echo e($schedule->cabang->name); ?></div>
            <div class="text-muted" style="font-size:12px;margin-top:2px"><?php echo e($schedule->cabang->city ?? ''); ?><?php echo e($schedule->cabang->regency ? ', '.$schedule->cabang->regency : ''); ?></div>
            <?php if($schedule->cabang->phone): ?>
            <div class="text-muted" style="font-size:12px;margin-top:4px"><i class="bi bi-telephone me-1"></i><?php echo e($schedule->cabang->phone); ?></div>
            <?php endif; ?>
            <?php else: ?>
            <div class="text-muted" style="font-size:13px">—</div>
            <?php endif; ?>
        </div>

        
        <?php if($schedule->module): ?>
        <div class="dashboard-card mb-4">
            <div class="d-flex align-items-center gap-2 mb-3 pb-3 border-bottom">
                <div style="width:28px;height:28px;border-radius:7px;background:linear-gradient(135deg,#f6af23,#d97706);display:flex;align-items:center;justify-content:center;color:white;font-size:13px;flex-shrink:0">
                    <i class="bi bi-journals"></i>
                </div>
                <div class="fw-bold" style="font-size:14px">Modul Belajar</div>
            </div>
            <div class="fw-semibold" style="font-size:13px"><?php echo e($schedule->module->judul); ?></div>
            <div class="text-muted" style="font-size:12px;margin-top:2px"><?php echo e($schedule->module->deskripsi); ?></div>
            <div class="mt-2" style="font-size:11px;color:var(--text-muted)">
                Jenis: <?php echo e(ucfirst($schedule->module->jenis ?? '—')); ?>

                <?php if($schedule->module->kode_modul): ?> · Kode: <?php echo e($schedule->module->kode_modul); ?> <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        
        <div class="dashboard-card">
            <div class="fw-bold mb-3" style="font-size:14px">Aksi</div>
            <div class="d-flex flex-column gap-2">
                <?php if($schedule->status === 'dijadwalkan'): ?>
                <a href="<?php echo e(route('admin.schedules.edit', $schedule)); ?>" class="btn btn-primary btn-sm">
                    <i class="bi bi-pencil me-2"></i>Edit Jadwal
                </a>
                <?php endif; ?>
                <a href="<?php echo e(route('admin.attendance.show', $schedule)); ?>" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-clipboard-check me-2"></i>Lihat Absensi
                </a>
                <a href="<?php echo e(route('admin.schedules.create')); ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-plus-circle me-2"></i>Buat Jadwal Baru
                </a>
                <?php if($schedule->status === 'dijadwalkan'): ?>
                <form action="<?php echo e(route('admin.schedules.destroy', $schedule)); ?>" method="POST"
                      onsubmit="return confirm('Hapus jadwal sesi ini?')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                        <i class="bi bi-trash me-2"></i>Hapus Jadwal
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/schedules/show.blade.php ENDPATH**/ ?>