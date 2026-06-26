<?php $__env->startSection('title', 'Detail Siswa — '.$student->name); ?>
<?php $__env->startSection('page-title', 'Preview Siswa'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.students.index')); ?>">Data Siswa</a></li>
        <li class="breadcrumb-item active"><?php echo e($student->name); ?></li>
    </ol>
</nav>


<div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center gap-4" style="position:relative">
        <?php
            $photoUrl = $student->photo ? asset('storage/'.$student->photo) : 'https://ui-avatars.com/api/?name='.urlencode($student->name).'&background=68117e&color=fff&size=120';
        ?>
        <img src="<?php echo e($photoUrl); ?>" alt="<?php echo e($student->name); ?>"
             style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid rgba(255,255,255,.3);flex-shrink:0">
        <div style="flex:1">
            <div style="font-size:11px;opacity:.6;text-transform:uppercase;letter-spacing:.08em;margin-bottom:4px">Profil Siswa</div>
            <h4 class="fw-bold mb-1" style="color:white"><?php echo e($student->name); ?></h4>
            <div style="opacity:.8;font-size:13px">
                NIS: <?php echo e($student->nis ?? '—'); ?>

                <?php if($student->branch): ?>· <?php echo e($student->branch->name); ?><?php endif; ?>
                <?php if($student->status): ?>
                · <span class="badge" style="background:<?php echo e($student->status === 'aktif' ? 'rgba(16,185,129,.3)' : 'rgba(239,68,68,.3)'); ?>;color:white;font-size:11px"><?php echo e(ucfirst($student->status)); ?></span>
                <?php endif; ?>
            </div>
        </div>
        <div class="d-flex gap-2 flex-shrink-0">
            <a href="<?php echo e(route('admin.students.edit', $student)); ?>" class="btn fw-semibold" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px">
                <i class="bi bi-pencil me-1"></i>Edit
            </a>
            <a href="<?php echo e(route('admin.students.index')); ?>" class="btn fw-semibold" style="background:rgba(255,255,255,.1);color:white;border:1px solid rgba(255,255,255,.2);border-radius:10px">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    
    <div class="col-lg-8">

        
        <div class="dashboard-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-person-badge me-2 text-primary"></i>Data Pribadi</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="small text-muted">Nama Lengkap</div>
                    <div class="fw-semibold"><?php echo e($student->name); ?></div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">Jenis Kelamin</div>
                    <div class="fw-semibold"><?php echo e($student->gender === 'L' ? 'Laki-laki' : ($student->gender === 'P' ? 'Perempuan' : '—')); ?></div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">Tempat Lahir</div>
                    <div class="fw-semibold"><?php echo e($student->birth_place ?? '—'); ?></div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">Tanggal Lahir</div>
                    <div class="fw-semibold"><?php echo e($student->birth_date?->format('d M Y') ?? '—'); ?></div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">No. HP</div>
                    <div class="fw-semibold"><?php echo e($student->phone ?? '—'); ?></div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">Kategori</div>
                    <div class="fw-semibold"><?php echo e($student->kategori_peserta_didik ?? '—'); ?></div>
                </div>
                <div class="col-12">
                    <div class="small text-muted">Alamat</div>
                    <div class="fw-semibold"><?php echo e($student->address ?? '—'); ?></div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">Nama Orang Tua / Wali</div>
                    <div class="fw-semibold"><?php echo e($student->parent_name ?? '—'); ?></div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">No. HP Wali</div>
                    <div class="fw-semibold"><?php echo e($student->parent_phone ?? '—'); ?></div>
                </div>
                <?php if($student->school_name): ?>
                <div class="col-md-6">
                    <div class="small text-muted">Sekolah</div>
                    <div class="fw-semibold"><?php echo e($student->school_name); ?></div>
                </div>
                <?php endif; ?>
                <?php if($student->grade): ?>
                <div class="col-md-6">
                    <div class="small text-muted">Kelas / Tingkat</div>
                    <div class="fw-semibold"><?php echo e($student->grade); ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        
        <?php if($student->user): ?>
        <div class="dashboard-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-person-lock me-2 text-primary"></i>Akun Login</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="small text-muted">Email</div>
                    <div class="fw-semibold"><?php echo e($student->user->email); ?></div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">Status Akun</div>
                    <div class="fw-semibold">
                        <span class="badge" style="background:<?php echo e($student->user->is_active ? 'var(--soft-success-bg)' : 'var(--soft-danger-bg)'); ?>;color:<?php echo e($student->user->is_active ? 'var(--soft-success-text)' : 'var(--soft-danger-text)'); ?>;border:1px solid <?php echo e($student->user->is_active ? 'var(--soft-success-border)' : 'var(--soft-danger-border)'); ?>">
                            <?php echo e($student->user->is_active ? 'Aktif' : 'Nonaktif'); ?>

                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">Bergabung</div>
                    <div class="fw-semibold"><?php echo e($student->user->created_at?->format('d M Y') ?? '—'); ?></div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        
        <?php if($invoices->count()): ?>
        <div class="dashboard-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-receipt me-2 text-primary"></i>Riwayat Tagihan</h6>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-modern">
                        <tr><th>No. Invoice</th><th>Deskripsi</th><th>Total</th><th>Jatuh Tempo</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $statusColors = [
                                'lunas' => ['bg'=>'var(--soft-success-bg)','color'=>'var(--soft-success-text)','label'=>'Lunas'],
                                'sebagian' => ['bg'=>'var(--soft-warning-bg)','color'=>'var(--soft-warning-text)','label'=>'Sebagian'],
                                'belum_bayar' => ['bg'=>'var(--soft-danger-bg)','color'=>'var(--soft-danger-text)','label'=>'Belum Bayar'],
                            ];
                            $sc = $statusColors[$inv->status] ?? ['bg'=>'var(--soft-muted-bg)','color'=>'var(--text-muted)','label'=>$inv->status];
                        ?>
                        <tr>
                            <td class="fw-semibold" style="font-size:12px"><?php echo e($inv->nomor_invoice ?? '—'); ?></td>
                            <td style="font-size:13px"><?php echo e($inv->deskripsi ?? '—'); ?></td>
                            <td class="fw-bold text-primary">Rp <?php echo e(number_format($inv->total,0,',','.')); ?></td>
                            <td style="font-size:13px"><?php echo e($inv->jatuh_tempo?->format('d M Y') ?? '—'); ?></td>
                            <td><span class="badge" style="background:<?php echo e($sc['bg']); ?>;color:<?php echo e($sc['color']); ?>;font-size:11px;padding:4px 10px;border-radius:8px"><?php echo e($sc['label']); ?></span></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

    </div>

    
    <div class="col-lg-4">

        
        <div class="dashboard-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-box-seam me-2 text-primary"></i>Paket Belajar</h6>
            <?php if($student->package): ?>
            <div class="p-3 rounded-3" style="background:var(--soft-primary-bg);border:1px solid var(--soft-primary-border)">
                <div class="fw-bold text-primary mb-1"><?php echo e($student->package->nama); ?></div>
                <div class="small text-muted mb-2"><?php echo e($student->package->deskripsi ?? '—'); ?></div>
                <div class="small">
                    <div class="mb-1"><strong>Jenis:</strong> <?php echo e($student->package->jenis ?? '—'); ?></div>
                    <div class="mb-1"><strong>Sesi:</strong> <?php echo e($student->package->jumlah_pertemuan ?? '—'); ?></div>
                    <div class="mb-1"><strong>Harga:</strong> Rp <?php echo e(number_format($student->package->harga ?? 0,0,',','.')); ?></div>
                    <div class="mb-1"><strong>Cabang:</strong> <?php echo e($student->package->cabang?->name ?? 'Pusat'); ?></div>
                    <div><strong>Mata Pelajaran:</strong> <?php echo e($student->package->mataPelajaran->pluck('nama')->join(', ') ?: '—'); ?></div>
                </div>
            </div>
            <?php else: ?>
            <div class="text-muted small text-center py-3">
                <i class="bi bi-box d-block mb-2" style="font-size:2rem;opacity:.3"></i>
                Belum memiliki paket
            </div>
            <?php endif; ?>
        </div>

        
        <div class="dashboard-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-building me-2 text-primary"></i>Informasi Cabang</h6>
            <?php if($student->branch): ?>
            <div class="fw-semibold"><?php echo e($student->branch->name); ?></div>
            <div class="small text-muted mt-1"><?php echo e($student->branch->address ?? '—'); ?></div>
            <?php else: ?>
            <div class="text-muted small">Belum ditentukan</div>
            <?php endif; ?>
        </div>

        
        <div class="dashboard-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-calendar-check me-2 text-primary"></i>Riwayat Bergabung</h6>
            <div class="small text-muted">Tanggal Daftar</div>
            <div class="fw-semibold mb-2"><?php echo e($student->join_date?->format('d M Y') ?? '—'); ?></div>
            <div class="small text-muted">Status</div>
            <div class="fw-semibold">
                <span class="badge" style="background:<?php echo e($student->status === 'aktif' ? 'var(--soft-success-bg)' : 'var(--soft-danger-bg)'); ?>;color:<?php echo e($student->status === 'aktif' ? 'var(--soft-success-text)' : 'var(--soft-danger-text)'); ?>;border:1px solid <?php echo e($student->status === 'aktif' ? 'var(--soft-success-border)' : 'var(--soft-danger-border)'); ?>;font-size:12px;padding:5px 12px;border-radius:8px">
                    <?php echo e(ucfirst($student->status ?? '—')); ?>

                </span>
            </div>
        </div>

    </div>
</div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/students/show.blade.php ENDPATH**/ ?>