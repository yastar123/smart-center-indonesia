<?php $__env->startSection('title', 'Tagihan Siswa & Verifikasi Pembayaran'); ?>
<?php $__env->startSection('page-title', 'Tagihan Siswa & Verifikasi Pembayaran'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-up">


<div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Tagihan Siswa & Verifikasi Pembayaran</h5>
                    <span style="font-size:12px;opacity:.8">Kelola tagihan kelas, invoice pendaftaran, dan verifikasi bukti bayar</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <?php if(($counts['pending'] ?? 0) > 0): ?>
            <span class="badge fw-semibold px-3 py-2" style="background:rgba(255,255,255,.2);border:1px solid rgba(255,255,255,.3);border-radius:10px;font-size:12px">
                <i class="bi bi-clock me-1"></i><?php echo e($counts['pending']); ?> Menunggu Verifikasi
            </span>
            <?php endif; ?>
        </div>
    </div>
</div>


<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show mb-3"><i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-3"><i class="bi bi-x-circle me-2"></i><?php echo e(session('error')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>


<ul class="nav nav-pills mb-4 gap-2 flex-wrap fade-up" id="mainTabNav">
    <li class="nav-item">
        <button class="nav-link <?php echo e(!in_array(request('tab'), ['kelas','registrasi','verifikasi']) ? 'active' : ''); ?>"
                onclick="switchTab('siswa')" id="btn-tab-siswa"
                style="border-radius:10px;font-size:14px;padding:8px 18px">
            <i class="bi bi-wallet2 me-2"></i>Tagihan Siswa
            <?php if($stats['menunggu'] > 0): ?>
            <span class="badge ms-1" style="background:rgba(239,68,68,.2);color:#ef4444"><?php echo e($stats['menunggu']); ?></span>
            <?php endif; ?>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link <?php echo e(request('tab') === 'kelas' ? 'active' : ''); ?>"
                onclick="switchTab('kelas')" id="btn-tab-kelas"
                style="border-radius:10px;font-size:14px;padding:8px 18px">
            <i class="bi bi-diagram-3 me-2"></i>Tagihan Kelas
            <?php if($stats['total'] > 0): ?>
            <span class="badge ms-1" style="background:rgba(200,77,223,.2);color:#c84ddf"><?php echo e($stats['total']); ?></span>
            <?php endif; ?>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link <?php echo e(request('tab') === 'registrasi' ? 'active' : ''); ?>"
                onclick="switchTab('registrasi')" id="btn-tab-registrasi"
                style="border-radius:10px;font-size:14px;padding:8px 18px">
            <i class="bi bi-file-earmark-text me-2"></i>Tagihan Registrasi
            <?php if($stats['reg_belum'] > 0): ?>
            <span class="badge bg-danger ms-1"><?php echo e($stats['reg_belum']); ?></span>
            <?php endif; ?>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link <?php echo e(request('tab') === 'verifikasi' ? 'active' : ''); ?>"
                onclick="switchTab('verifikasi')" id="btn-tab-verifikasi"
                style="border-radius:10px;font-size:14px;padding:8px 18px">
            <i class="bi bi-shield-check me-2"></i>Verifikasi Pembayaran
            <?php if(($counts['pending'] ?? 0) + ($pkgCounts['pending'] ?? 0) > 0): ?>
            <span class="badge bg-danger ms-1"><?php echo e(($counts['pending'] ?? 0) + ($pkgCounts['pending'] ?? 0)); ?></span>
            <?php endif; ?>
        </button>
    </li>
</ul>


<div id="pane-tab-siswa" class="<?php echo e(in_array(request('tab'), ['kelas','registrasi','verifikasi']) ? 'd-none' : ''); ?>">
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-2 fade-up">
            <div class="stat-card" style="border-top:3px solid #c84ddf">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-title">Total Kelas</div>
                        <div class="stat-value text-primary count-up" data-target="<?php echo e($stats['total']); ?>"><?php echo e($stats['total']); ?></div>
                    </div>
                    <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-people"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2 fade-up" style="animation-delay:.05s">
            <div class="stat-card" style="border-top:3px solid #f6af23">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-title">Cicilan</div>
                        <div class="stat-value text-warning count-up" data-target="<?php echo e($stats['cicilan']); ?>"><?php echo e($stats['cicilan']); ?></div>
                    </div>
                    <div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-pie-chart"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2 fade-up" style="animation-delay:.10s">
            <div class="stat-card" style="border-top:3px solid #0ea5e9">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-title">Pascabayar</div>
                        <div class="stat-value count-up" data-target="<?php echo e($stats['postpaid']); ?>" style="color:#0ea5e9"><?php echo e($stats['postpaid']); ?></div>
                    </div>
                    <div class="stat-icon bg-info-soft" style="color:white"><i class="bi bi-receipt"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 fade-up" style="animation-delay:.12s">
            <div class="stat-card" style="border-top:3px solid #8b5cf6">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-title">Invoice Registrasi</div>
                        <div class="stat-value count-up" data-target="<?php echo e($stats['reg_total']); ?>" style="color:#8b5cf6"><?php echo e($stats['reg_total']); ?></div>
                        <div class="text-muted" style="font-size:11px"><?php echo e($stats['reg_belum']); ?> belum bayar</div>
                    </div>
                    <div class="stat-icon" style="background:rgba(139,92,246,.15);color:white"><i class="bi bi-file-earmark-text"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 fade-up" style="animation-delay:.15s">
            <div class="stat-card" style="border-top:3px solid #ef4444">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-title">Invoice Belum Lunas</div>
                        <div class="stat-value text-danger count-up" data-target="<?php echo e($stats['menunggu']); ?>"><?php echo e($stats['menunggu']); ?></div>
                    </div>
                    <div class="stat-icon bg-danger-soft" style="color:white"><i class="bi bi-exclamation-triangle"></i></div>
                </div>
            </div>
        </div>
    </div>

</div>


<div id="pane-tab-kelas" class="<?php echo e(request('tab') !== 'kelas' ? 'd-none' : ''); ?>">
        <div class="dashboard-card mb-4">
            <form method="GET" action="<?php echo e(route('admin.tagihan-siswa.index')); ?>">
                <input type="hidden" name="tab" value="kelas">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" style="font-size:12px">Cari (Nama Siswa / Kelas)</label>
                        <div class="input-group">
                            <span class="input-group-text" style="background:var(--input-bg);border-color:var(--card-border)"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari..."
                                   class="form-control" style="border-color:var(--card-border);background:var(--input-bg)">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" style="font-size:12px">Tipe Tagihan</label>
                        <select name="billing_mode" class="form-select" style="border-color:var(--card-border);background:var(--input-bg)">
                            <option value="">Semua Tipe</option>
                            <option value="cicilan"  <?php echo e(request('billing_mode')=='cicilan' ?'selected':''); ?>>Cicilan (Prabayar)</option>
                            <option value="postpaid" <?php echo e(request('billing_mode')=='postpaid'?'selected':''); ?>>Pascabayar (Per Sesi)</option>
                        </select>
                    </div>
                    <?php if(auth()->user()->hasRole('owner')): ?>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" style="font-size:12px">Cabang</label>
                        <select name="cabang_id" class="form-select" style="border-color:var(--card-border);background:var(--input-bg)">
                            <option value="">Semua Cabang</option>
                            <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($b->id); ?>" <?php echo e(request('cabang_id')==$b->id?'selected':''); ?>><?php echo e($b->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <?php endif; ?>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill fw-semibold">Filter</button>
                        <a href="<?php echo e(route('admin.tagihan-siswa.index')); ?>" class="btn btn-outline-secondary px-3">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="dashboard-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr style="background:var(--input-bg)">
                            <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Nama Siswa / Kelas</th>
                            <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Paket</th>
                            <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Harga Paket</th>
                            <th class="text-center" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Tipe</th>
                            <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Dibayar</th>
                            <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Sisa</th>
                            <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Cabang</th>
                            <th class="text-center" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $siswa = $kelas->siswa->first();
                            $siswaNama = $siswa?->user?->name ?? $siswa?->name ?? '—';
                            $paket = $siswa?->package;
                            $billingLabel = $kelas->billing_mode === 'postpaid' ? 'Pascabayar' : 'Cicilan';
                            $billingColor = $kelas->billing_mode === 'postpaid' ? '#0ea5e9' : '#f6af23';
                            $billingBg    = $kelas->billing_mode === 'postpaid' ? 'rgba(14,165,233,.12)' : 'rgba(246,175,35,.15)';
                            $kelasInvoices = $invoicesByKelas[$kelas->id] ?? collect();
                            $totalTagihan  = $kelasInvoices->sum('total');
                            $totalDibayar  = $kelasInvoices->flatMap->pembayaran->where('status', 'verified')->sum('jumlah');
                            $sisaCicilan   = max(0, $totalTagihan - $totalDibayar);
                        ?>
                        <tr>
                            <td>
                                <div class="fw-semibold" style="font-size:13px"><?php echo e($siswaNama); ?></div>
                                <div class="text-muted" style="font-size:12px"><i class="bi bi-diagram-3 me-1"></i><?php echo e($kelas->nama_kelas); ?></div>
                            </td>
                            <td style="font-size:13px">
                                <?php if($paket): ?>
                                    <div class="fw-semibold"><?php echo e($paket->nama); ?></div>
                                    <div class="text-muted" style="font-size:11px"><?php echo e(ucfirst($paket->jenis ?? '—')); ?> · <?php echo e($paket->jumlah_pertemuan ?? '?'); ?> sesi</div>
                                <?php else: ?> <span class="text-muted">—</span> <?php endif; ?>
                            </td>
                            <td style="font-size:13px">
                                <?php if($paket?->harga): ?>
                                    <span class="fw-semibold">Rp <?php echo e(number_format($paket->harga, 0, ',', '.')); ?></span>
                                <?php else: ?> <span class="text-muted">—</span> <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <span style="background:<?php echo e($billingBg); ?>;color:<?php echo e($billingColor); ?>;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600"><?php echo e($billingLabel); ?></span>
                            </td>
                            <td style="font-size:13px">
                                <?php if($totalDibayar > 0): ?>
                                    <span class="fw-semibold text-success">Rp <?php echo e(number_format($totalDibayar, 0, ',', '.')); ?></span>
                                <?php else: ?> <span class="text-muted">—</span> <?php endif; ?>
                            </td>
                            <td style="font-size:13px">
                                <?php if($sisaCicilan > 0): ?>
                                    <span class="fw-semibold text-danger">Rp <?php echo e(number_format($sisaCicilan, 0, ',', '.')); ?></span>
                                <?php elseif($totalTagihan > 0): ?>
                                    <span class="text-success fw-semibold"><i class="bi bi-check-circle me-1"></i>Lunas</span>
                                <?php else: ?> <span class="text-muted">—</span> <?php endif; ?>
                            </td>
                            <td style="font-size:13px"><?php echo e($kelas->cabang?->name ?? '—'); ?></td>
                            <td class="text-center">
                                <a href="<?php echo e(route('admin.tagihan-siswa.show', $kelas->id)); ?>"
                                   class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:11px">
                                    <i class="bi bi-eye me-1"></i>Detail
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div style="font-size:40px;opacity:.3;margin-bottom:8px"><i class="bi bi-wallet2"></i></div>
                                <div class="text-muted">Belum ada data tagihan siswa cicilan atau pascabayar</div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if($classes->hasPages()): ?>
            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-top">
                <div class="text-muted" style="font-size:13px"><?php echo e($classes->firstItem()); ?>–<?php echo e($classes->lastItem()); ?> dari <?php echo e($classes->total()); ?></div>
                <?php echo e($classes->appends(request()->all())->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>


<div id="pane-tab-registrasi" class="<?php echo e(request('tab') !== 'registrasi' ? 'd-none' : ''); ?>">
        <div class="dashboard-card mb-4">
            <form method="GET" action="<?php echo e(route('admin.tagihan-siswa.index')); ?>">
                <input type="hidden" name="tab" value="registrasi">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" style="font-size:12px">Cari (Nama Siswa / No Invoice)</label>
                        <div class="input-group">
                            <span class="input-group-text" style="background:var(--input-bg);border-color:var(--card-border)"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari..."
                                   class="form-control" style="border-color:var(--card-border);background:var(--input-bg)">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" style="font-size:12px">Status Invoice</label>
                        <select name="reg_status" class="form-select" style="border-color:var(--card-border);background:var(--input-bg)">
                            <option value="">Semua Status</option>
                            <option value="belum_bayar" <?php echo e(request('reg_status')=='belum_bayar'?'selected':''); ?>>Belum Dibayar</option>
                            <option value="sebagian"    <?php echo e(request('reg_status')=='sebagian'   ?'selected':''); ?>>Sebagian</option>
                            <option value="lunas"       <?php echo e(request('reg_status')=='lunas'      ?'selected':''); ?>>Lunas</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill fw-semibold">Filter</button>
                        <a href="<?php echo e(route('admin.tagihan-siswa.index', ['tab'=>'registrasi'])); ?>"
                           class="btn btn-outline-secondary px-3">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="dashboard-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr style="background:var(--input-bg)">
                            <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">No Invoice</th>
                            <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Nama Siswa</th>
                            <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Program</th>
                            <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Total</th>
                            <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Jatuh Tempo</th>
                            <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Cabang</th>
                            <th class="text-center" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $invStatusMap = [
                                'belum_bayar' => ['rgba(239,68,68,.1)','#ef4444','Belum Dibayar'],
                                'sebagian'    => ['rgba(245,158,11,.1)','#f59e0b','Sebagian'],
                                'lunas'       => ['rgba(16,185,129,.1)','#10b981','Lunas'],
                            ];
                        ?>
                        <?php $__empty_1 = true; $__currentLoopData = $registrationInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php $sc = $invStatusMap[$inv->status] ?? ['rgba(100,116,139,.1)','#64748b',$inv->status]; ?>
                        <tr>
                            <td><span style="font-size:11px;font-family:monospace;color:var(--text-muted)"><?php echo e($inv->nomor_invoice); ?></span></td>
                            <td>
                                <div class="fw-semibold" style="font-size:13px"><?php echo e($inv->siswa?->name ?? '—'); ?></div>
                                <div class="text-muted" style="font-size:11px"><?php echo e($inv->siswa?->user?->email ?? ''); ?></div>
                            </td>
                            <td style="font-size:12px;max-width:200px"><span class="text-muted"><?php echo e($inv->deskripsi); ?></span></td>
                            <td><span class="fw-bold" style="font-size:14px">Rp <?php echo e(number_format($inv->total, 0, ',', '.')); ?></span></td>
                            <td style="font-size:12px"><?php echo e($inv->jatuh_tempo ? \Carbon\Carbon::parse($inv->jatuh_tempo)->isoFormat('D MMM YYYY') : '—'); ?></td>
                            <td style="font-size:12px"><?php echo e($inv->cabang?->name ?? '—'); ?></td>
                            <td class="text-center">
                                <span class="fw-semibold" style="background:<?php echo e($sc[0]); ?>;color:<?php echo e($sc[1]); ?>;padding:3px 12px;border-radius:20px;font-size:11px"><?php echo e($sc[2]); ?></span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div style="font-size:40px;opacity:.3;margin-bottom:8px"><i class="bi bi-file-earmark-text"></i></div>
                                <div class="text-muted">Belum ada invoice dari pendaftaran siswa</div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if($registrationInvoices->hasPages()): ?>
            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-top">
                <div class="text-muted" style="font-size:13px"><?php echo e($registrationInvoices->firstItem()); ?>–<?php echo e($registrationInvoices->lastItem()); ?> dari <?php echo e($registrationInvoices->total()); ?></div>
                <?php echo e($registrationInvoices->appends(request()->all())->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>

</div>


<div id="pane-tab-verifikasi" class="<?php echo e(request('tab') !== 'verifikasi' ? 'd-none' : ''); ?>">

    
    <div class="d-flex gap-2 flex-wrap mb-3">
        <span class="badge" style="background:rgba(245,158,11,.12);color:#f59e0b;padding:6px 14px;border-radius:20px;font-size:12px;font-weight:600">
            <i class="bi bi-hourglass-split me-1"></i><?php echo e($counts['pending']); ?> Menunggu
        </span>
        <span class="badge" style="background:rgba(16,185,129,.12);color:#10b981;padding:6px 14px;border-radius:20px;font-size:12px;font-weight:600">
            <i class="bi bi-check-circle me-1"></i><?php echo e($counts['verified']); ?> Disetujui
        </span>
        <span class="badge" style="background:rgba(239,68,68,.12);color:#ef4444;padding:6px 14px;border-radius:20px;font-size:12px;font-weight:600">
            <i class="bi bi-x-circle me-1"></i><?php echo e($counts['rejected']); ?> Ditolak
        </span>
        <?php if(($pkgCounts['pending'] ?? 0) > 0): ?>
        <span class="badge" style="background:rgba(139,92,246,.12);color:#8b5cf6;padding:6px 14px;border-radius:20px;font-size:12px;font-weight:600">
            <i class="bi bi-box-seam me-1"></i><?php echo e($pkgCounts['pending']); ?> Paket Menunggu
        </span>
        <?php endif; ?>
    </div>

    
    <ul class="nav nav-pills mb-3 gap-2">
        <li class="nav-item">
            <button class="nav-link active" onclick="showVerTab('invoice')" id="ver-tab-invoice"
                    style="border-radius:10px;font-size:13px">
                <i class="bi bi-receipt me-1"></i>Tagihan / Invoice
                <?php if($counts['pending'] > 0): ?>
                <span class="badge bg-danger ms-1"><?php echo e($counts['pending']); ?></span>
                <?php endif; ?>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" onclick="showVerTab('package')" id="ver-tab-package"
                    style="border-radius:10px;font-size:13px">
                <i class="bi bi-box-seam me-1"></i>Pembayaran Paket
                <?php if($pkgCounts['pending'] > 0): ?>
                <span class="badge bg-danger ms-1"><?php echo e($pkgCounts['pending']); ?></span>
                <?php endif; ?>
            </button>
        </li>
    </ul>

    
    <div id="ver-pane-invoice">
        <div class="dashboard-card mb-4">
            <form method="GET" class="row g-2 align-items-end">
                <input type="hidden" name="tab" value="verifikasi">
                <input type="hidden" name="pkg_status" value="<?php echo e(request('pkg_status','pending')); ?>">
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold" style="font-size:12px">Cari Siswa / No Pembayaran</label>
                    <input type="text" name="ver_search" class="form-control form-control-sm"
                           placeholder="Nama siswa atau nomor pembayaran..." value="<?php echo e(request('ver_search')); ?>">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold" style="font-size:12px">Status</label>
                    <select name="ver_status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="pending"  <?php echo e((request('ver_status','pending'))=='pending'  ? 'selected' : ''); ?>>Menunggu</option>
                        <option value="verified" <?php echo e(request('ver_status')=='verified' ? 'selected' : ''); ?>>Disetujui</option>
                        <option value="rejected" <?php echo e(request('ver_status')=='rejected' ? 'selected' : ''); ?>>Ditolak</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search me-1"></i>Filter</button>
                    <a href="<?php echo e(route('admin.tagihan-siswa.index', ['tab'=>'verifikasi'])); ?>"
                       class="btn btn-outline-secondary btn-sm ms-1">Reset</a>
                </div>
            </form>
        </div>

        <div class="dashboard-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr style="background:var(--input-bg);color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.05em">
                            <th class="px-3 py-3">No Pembayaran</th>
                            <th class="py-3">Siswa</th>
                            <th class="py-3">Invoice</th>
                            <th class="py-3">Jumlah</th>
                            <th class="py-3">Metode</th>
                            <th class="py-3">Tanggal</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="py-3 text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $payStatusMap = [
                                'pending'  => ['bg'=>'rgba(245,158,11,.1)','color'=>'#f59e0b','label'=>'Menunggu'],
                                'verified' => ['bg'=>'rgba(16,185,129,.1)','color'=>'#10b981','label'=>'Disetujui'],
                                'rejected' => ['bg'=>'rgba(239,68,68,.1)','color'=>'#ef4444','label'=>'Ditolak'],
                            ];
                        ?>
                        <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php $psc = $payStatusMap[$payment->status] ?? ['bg'=>'rgba(100,116,139,.1)','color'=>'#64748b','label'=>$payment->status]; ?>
                        <tr>
                            <td class="px-3" style="font-size:12px;font-family:monospace;color:var(--text-muted)">
                                <?php echo e($payment->nomor_pembayaran ?? '—'); ?>

                            </td>
                            <td>
                                <div class="fw-semibold" style="font-size:13px"><?php echo e($payment->siswa?->name ?? '—'); ?></div>
                                <div class="text-muted" style="font-size:11px"><?php echo e($payment->cabang?->name ?? 'Pusat'); ?></div>
                            </td>
                            <td><div style="font-size:12px;color:var(--text-muted)"><?php echo e($payment->invoice?->nomor_invoice ?? '—'); ?></div></td>
                            <td><span class="fw-bold" style="font-size:14px">Rp <?php echo e(number_format($payment->jumlah, 0, ',', '.')); ?></span></td>
                            <td>
                                <span style="font-size:12px;text-transform:capitalize"><?php echo e($payment->metode ?? '—'); ?></span>
                                <?php if($payment->nama_bank): ?>
                                <div class="text-muted" style="font-size:11px"><?php echo e($payment->nama_bank); ?></div>
                                <?php endif; ?>
                            </td>
                            <td><span style="font-size:12px"><?php echo e($payment->tanggal_pembayaran ? \Carbon\Carbon::parse($payment->tanggal_pembayaran)->isoFormat('D MMM YYYY') : '—'); ?></span></td>
                            <td class="text-center">
                                <span style="background:<?php echo e($psc['bg']); ?>;color:<?php echo e($psc['color']); ?>;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600">
                                    <?php echo e($psc['label']); ?>

                                </span>
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-flex gap-1 justify-content-end">
                                    <a href="<?php echo e(route('admin.verifikasi-pembayaran.show', $payment)); ?>"
                                       class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:12px">
                                        <i class="bi bi-eye me-1"></i>Tinjau
                                    </a>
                                    <?php if($payment->status === 'pending'): ?>
                                    <button type="button" onclick="showApproveModal(<?php echo e($payment->id); ?>)"
                                            class="btn btn-sm btn-success" style="border-radius:8px;font-size:12px">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                    <button type="button" onclick="showRejectModal(<?php echo e($payment->id); ?>)"
                                            class="btn btn-sm btn-danger" style="border-radius:8px;font-size:12px">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-shield-check" style="font-size:36px;opacity:.2;display:block;margin-bottom:12px"></i>
                                Tidak ada data pembayaran ditemukan.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if($payments->hasPages()): ?>
            <div class="px-3 pt-3 border-top">
                <?php echo e($payments->withQueryString()->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div id="ver-pane-package" style="display:none">
        <div class="dashboard-card mb-4">
            <form method="GET" class="row g-2 align-items-end">
                <input type="hidden" name="tab" value="verifikasi">
                <input type="hidden" name="ver_status" value="<?php echo e(request('ver_status','pending')); ?>">
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold" style="font-size:12px">Cari Siswa</label>
                    <input type="text" name="ver_search" class="form-control form-control-sm"
                           placeholder="Nama siswa..." value="<?php echo e(request('ver_search')); ?>">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold" style="font-size:12px">Status</label>
                    <select name="pkg_status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="pending"  <?php echo e((request('pkg_status','pending'))=='pending'  ? 'selected' : ''); ?>>Menunggu</option>
                        <option value="verified" <?php echo e(request('pkg_status')=='verified' ? 'selected' : ''); ?>>Disetujui</option>
                        <option value="rejected" <?php echo e(request('pkg_status')=='rejected' ? 'selected' : ''); ?>>Ditolak</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search me-1"></i>Filter</button>
                    <a href="<?php echo e(route('admin.tagihan-siswa.index', ['tab'=>'verifikasi'])); ?>"
                       class="btn btn-outline-secondary btn-sm ms-1">Reset</a>
                </div>
            </form>
        </div>

        <div class="dashboard-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr style="background:var(--input-bg);color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.05em">
                            <th class="px-3 py-3">Siswa</th>
                            <th class="py-3">Mata Pelajaran</th>
                            <th class="py-3">Jumlah</th>
                            <th class="py-3">Bukti</th>
                            <th class="py-3">Catatan</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="py-3 text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $packagePayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php $psc2 = $payStatusMap[$pp->status] ?? ['bg'=>'rgba(100,116,139,.1)','color'=>'#64748b','label'=>$pp->status]; ?>
                        <tr>
                            <td class="px-3">
                                <div class="fw-semibold" style="font-size:13px"><?php echo e($pp->student?->name ?? '—'); ?></div>
                                <div class="text-muted" style="font-size:11px"><?php echo e($pp->student?->nis ?? ''); ?></div>
                            </td>
                            <td><div style="font-size:13px"><?php echo e($pp->course?->nama ?? '—'); ?></div></td>
                            <td><span class="fw-bold" style="font-size:14px">Rp <?php echo e(number_format($pp->amount, 0, ',', '.')); ?></span></td>
                            <td>
                                <?php if($pp->proof): ?>
                                <a href="<?php echo e(asset('storage/'.$pp->proof)); ?>" target="_blank"
                                   class="btn btn-sm btn-outline-secondary" style="border-radius:8px;font-size:12px">
                                    <i class="bi bi-file-earmark-image me-1"></i>Lihat
                                </a>
                                <?php else: ?>
                                <span class="text-muted" style="font-size:12px">—</span>
                                <?php endif; ?>
                            </td>
                            <td><span style="font-size:12px;color:var(--text-muted)"><?php echo e($pp->catatan ?: '—'); ?></span></td>
                            <td class="text-center">
                                <span style="background:<?php echo e($psc2['bg']); ?>;color:<?php echo e($psc2['color']); ?>;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600">
                                    <?php echo e($psc2['label']); ?>

                                </span>
                                <?php if($pp->status === 'rejected' && $pp->rejected_reason): ?>
                                <div class="text-muted mt-1" style="font-size:10px"><?php echo e(\Illuminate\Support\Str::limit($pp->rejected_reason, 30)); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-3">
                                <?php if($pp->status === 'pending'): ?>
                                <div class="d-flex gap-1 justify-content-end">
                                    <button type="button" onclick="showPkgApproveModal(<?php echo e($pp->id); ?>)"
                                            class="btn btn-sm btn-success" style="border-radius:8px;font-size:12px">
                                        <i class="bi bi-check-lg me-1"></i>Setujui
                                    </button>
                                    <button type="button" onclick="showPkgRejectModal(<?php echo e($pp->id); ?>)"
                                            class="btn btn-sm btn-danger" style="border-radius:8px;font-size:12px">
                                        <i class="bi bi-x-lg me-1"></i>Tolak
                                    </button>
                                </div>
                                <?php else: ?>
                                <span class="text-muted" style="font-size:12px">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-box-seam" style="font-size:36px;opacity:.2;display:block;margin-bottom:12px"></i>
                                Tidak ada pembayaran paket ditemukan.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if($packagePayments->hasPages()): ?>
            <div class="px-3 pt-3 border-top">
                <?php echo e($packagePayments->withQueryString()->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>

</div>

</div>


<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="approveForm" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-check-circle-fill text-success me-2"></i>Setujui Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p style="font-size:14px">Apakah Anda yakin ingin menyetujui pembayaran ini? Status invoice akan diperbarui secara otomatis.</p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan (opsional)</label>
                        <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan verifikasi..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success fw-semibold"><i class="bi bi-check-lg me-1"></i>Setujui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="rejectForm" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-x-circle-fill text-danger me-2"></i>Tolak Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="alasan_penolakan" class="form-control" rows="3" placeholder="Tuliskan alasan penolakan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger fw-semibold"><i class="bi bi-x-lg me-1"></i>Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="pkgApproveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="pkgApproveForm" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-check-circle-fill text-success me-2"></i>Setujui Pembayaran Paket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body"><p style="font-size:14px">Apakah Anda yakin ingin menyetujui pembayaran paket ini?</p></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success fw-semibold"><i class="bi bi-check-lg me-1"></i>Setujui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="pkgRejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="pkgRejectForm" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-x-circle-fill text-danger me-2"></i>Tolak Pembayaran Paket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="alasan_penolakan" class="form-control" rows="3" placeholder="Tuliskan alasan penolakan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger fw-semibold"><i class="bi bi-x-lg me-1"></i>Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function switchTab(tab) {
    ['siswa','kelas','registrasi','verifikasi'].forEach(function(t) {
        document.getElementById('pane-tab-'+t).classList.toggle('d-none', t !== tab);
        document.getElementById('btn-tab-'+t).classList.toggle('active', t === tab);
    });
}

function showVerTab(tab) {
    document.getElementById('ver-pane-invoice').style.display  = tab === 'invoice'  ? '' : 'none';
    document.getElementById('ver-pane-package').style.display  = tab === 'package'  ? '' : 'none';
    document.getElementById('ver-tab-invoice').classList.toggle('active', tab === 'invoice');
    document.getElementById('ver-tab-package').classList.toggle('active', tab === 'package');
}

function showApproveModal(paymentId) {
    document.getElementById('approveForm').action = `/admin/verifikasi-pembayaran/${paymentId}/approve`;
    new bootstrap.Modal(document.getElementById('approveModal')).show();
}
function showRejectModal(paymentId) {
    document.getElementById('rejectForm').action = `/admin/verifikasi-pembayaran/${paymentId}/reject`;
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}
function showPkgApproveModal(id) {
    document.getElementById('pkgApproveForm').action = `/admin/verifikasi-pembayaran/package/${id}/approve`;
    new bootstrap.Modal(document.getElementById('pkgApproveModal')).show();
}
function showPkgRejectModal(id) {
    document.getElementById('pkgRejectForm').action = `/admin/verifikasi-pembayaran/package/${id}/reject`;
    new bootstrap.Modal(document.getElementById('pkgRejectModal')).show();
}

// Auto-open correct tab from URL
(function() {
    const urlP = new URLSearchParams(window.location.search);
    const tab = urlP.get('tab') || 'siswa';
    switchTab(tab);
    if (tab === 'verifikasi' && (urlP.has('pkg_status') || urlP.has('pkg_page'))) {
        showVerTab('package');
    }
})();
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/tagihan-siswa/index.blade.php ENDPATH**/ ?>