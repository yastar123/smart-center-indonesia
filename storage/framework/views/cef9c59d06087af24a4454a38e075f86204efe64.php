<?php $__env->startSection('page-title', 'Edit Landing — '.$branch->name); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-0">

    
    <div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
        <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
        <div class="row align-items-center g-3" style="position:relative">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0" style="color:white"><?php echo e($branch->name); ?></h5>
                        <span style="font-size:12px;opacity:.8">Edit konten halaman landing <code style="background:rgba(255,255,255,.15);padding:1px 6px;border-radius:5px">/cabang/<?php echo e($branch->id); ?></code></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end d-flex gap-2 justify-content-md-end flex-wrap">
                <a href="<?php echo e(route('cabang.show', $branch)); ?>" target="_blank" class="btn btn-sm fw-semibold px-3"
                   style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.25);border-radius:10px">
                    <i class="bi bi-eye me-1"></i>Lihat Halaman
                </a>
                <a href="<?php echo e(route('admin.landing.cabang.index')); ?>" class="btn btn-sm fw-semibold px-3"
                   style="background:rgba(255,255,255,.1);color:white;border:1px solid rgba(255,255,255,.2);border-radius:10px">
                    <i class="bi bi-arrow-left me-1"></i>Semua Cabang
                </a>
            </div>
        </div>
    </div>

    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <?php
        $city        = $branch->city ?: $branch->name;
        $promoItems  = json_decode($s['promo_items'] ?? '[]', true) ?: ['Mulai belajar dari Rp 50.000/sesi','Garansi nilai naik atau sesi gratis!','Tersedia Home Visit, Online & Offline','Gratis Konsultasi Pertama','#1 Les Privat Terbaik di '.$city];
        $faqItems    = json_decode($s['faq_items'] ?? '[]', true) ?: [['q'=>'Berapa harga les privat SCI '.$city.'?','a'=>'Harga les privat SCI '.$city.' mulai dari Rp 50.000/sesi untuk online.'],['q'=>'Apakah bisa home visit di '.$city.'?','a'=>'Ya! SCI '.$city.' melayani home visit ke seluruh area.']];
        $areasArr    = json_decode($s['areas'] ?? '[]', true) ?: [];
        $areasStr    = implode(', ', $areasArr);
        $defaultFeatures = [
            ['num'=>'01','icon'=>'👩‍🏫','title'=>'Tutor Bersertifikat',   'desc'=>'Semua tutor SCI '.$city.' telah melalui seleksi ketat dan memiliki sertifikat mengajar resmi.'],
            ['num'=>'02','icon'=>'🏠', 'title'=>'Home Visit',              'desc'=>'Tutor datang ke rumah Anda di seluruh wilayah '.$city.'. Nyaman, privat, dan efisien.'],
            ['num'=>'03','icon'=>'📈', 'title'=>'Prestasi Meningkat',      'desc'=>'Mayoritas siswa SCI '.$city.' mengalami peningkatan nilai dalam beberapa bulan pertama.'],
            ['num'=>'04','icon'=>'⏰', 'title'=>'Jadwal Fleksibel',        'desc'=>'Belajar bisa pagi, siang, sore, ataupun malam hari sesuai kebutuhan siswa.'],
            ['num'=>'05','icon'=>'💰', 'title'=>'Harga Transparan',        'desc'=>'Tidak ada biaya tersembunyi. Tersedia paket hemat dan pembayaran per sesi.'],
            ['num'=>'06','icon'=>'🛡️', 'title'=>'Garansi Kepuasan',        'desc'=>'Tutor dapat diganti apabila kurang cocok tanpa biaya tambahan.'],
        ];
        $savedFeatures = json_decode($s['features'] ?? '[]', true) ?: [];
        $editFeatures  = !empty($savedFeatures) ? $savedFeatures : $defaultFeatures;

        $defaultSubjects = [
            ['icon'=>'🔢','name'=>'Matematika',     'desc'=>'SD, SMP, SMA, Kuliah.',           'badge'=>'Terpopuler','badge_type'=>'popular'],
            ['icon'=>'⚡','name'=>'Fisika',          'desc'=>'Mekanika, gelombang, listrik.',    'badge'=>'SMP–SMA',  'badge_type'=>'level'],
            ['icon'=>'🧪','name'=>'Kimia',           'desc'=>'Organik, anorganik, stoikiometri.','badge'=>'SMP–SMA',  'badge_type'=>'level'],
            ['icon'=>'🌿','name'=>'Biologi',         'desc'=>'Sel, genetika, ekosistem.',        'badge'=>'SMP–SMA',  'badge_type'=>'level'],
            ['icon'=>'🇬🇧','name'=>'Bahasa Inggris', 'desc'=>'Speaking, grammar, TOEFL/IELTS.', 'badge'=>'Terpopuler','badge_type'=>'popular'],
            ['icon'=>'💻','name'=>'Komputer',        'desc'=>'MS Office, Programming, Canva.',   'badge'=>'Populer',  'badge_type'=>'hot'],
            ['icon'=>'📊','name'=>'Akuntansi',       'desc'=>'Akuntansi dasar–profesional.',     'badge'=>'Umum',     'badge_type'=>'general'],
            ['icon'=>'🇯🇵','name'=>'Bahasa Jepang',  'desc'=>'Hiragana, katakana, JLPT.',        'badge'=>'Semua Level','badge_type'=>'general'],
            ['icon'=>'📐','name'=>'Bahasa Indonesia','desc'=>'Tata bahasa, UN/UTBK.',            'badge'=>'SD–SMA',   'badge_type'=>'level'],
            ['icon'=>'🎨','name'=>'Seni & Desain',   'desc'=>'Menggambar, desain grafis.',       'badge'=>'Umum',     'badge_type'=>'general'],
            ['icon'=>'🗣️','name'=>'Public Speaking',  'desc'=>'Percaya diri, debat, presentasi.','badge'=>'Semua Level','badge_type'=>'general'],
            ['icon'=>'📚','name'=>'Persiapan SBMPTN','desc'=>'Latihan soal UTBK, simulasi.',     'badge'=>'SMA',      'badge_type'=>'level'],
        ];
        $savedSubjects = json_decode($s['subjects'] ?? '[]', true) ?: [];
        $editSubjects  = !empty($savedSubjects) ? $savedSubjects : $defaultSubjects;

        $heroBgCurrent = $s['hero_bg'] ?? '';
        $metodeImgHv   = $s['metode_img_homevisi'] ?? '';
        $metodeImgOn   = $s['metode_img_online'] ?? '';
        $metodeImgOf   = $s['metode_img_offline'] ?? '';

        /* ── Harga / Pricing cards ── */
        $defaultPricingCards = [
            ['name'=>'Paket Reguler', 'desc'=>'Cocok untuk pemula dan maintenance nilai.','price'=>'Rp 50.000','unit'=>'/sesi','sessions'=>'1× per minggu (4 sesi/bln)','fitur'=>['Durasi 90 menit/sesi','Pilihan tutor sesuai kebutuhan','Laporan perkembangan bulanan','Bisa home visit/online/offline'],'unggulan'=>false],
            ['name'=>'Paket Intensif','desc'=>'Cocok untuk persiapan ujian dan akselerasi nilai.','price'=>'Rp 45.000','unit'=>'/sesi','sessions'=>'2–3× per minggu (8–12 sesi/bln)','fitur'=>['Durasi 90 menit/sesi','Materi soal ujian eksklusif','Laporan perkembangan mingguan','Try-out bulanan gratis','Konsultasi guru kapan saja'],'unggulan'=>true],
            ['name'=>'Paket Premium', 'desc'=>'Solusi lengkap untuk target nilai terbaik.','price'=>'Hubungi Kami','unit'=>'','sessions'=>'Jadwal & sesi fleksibel','fitur'=>['Sesi tak terbatas per bulan','Tutor spesialis bidang studi','Monitoring nilai real-time','Garansi nilai naik tertulis','Materi custom sesuai kurikulum'],'unggulan'=>false],
        ];
        $savedPricingCards = json_decode($s['pricing_cards'] ?? '[]', true) ?: [];
        $editPricingCards  = !empty($savedPricingCards) ? $savedPricingCards : $defaultPricingCards;

        /* ── Branch testimonials ── */
        $defaultTestimonials = [
            ['text'=>'Anakku yang awalnya kesulitan Matematika sekarang jadi juara kelas!','name'=>'Bunda Sari','role'=>'Orang Tua Siswa · '.$city,'initial'=>'B','gradient'=>'linear-gradient(135deg,#f97316,#ea580c)'],
            ['text'=>'Belajar di SCI sangat menyenangkan! Nilai saya meningkat pesat.','name'=>'Aisyah R.','role'=>'Siswa SMA · Matematika','initial'=>'A','gradient'=>'linear-gradient(135deg,#c84ddf,#68117e)'],
            ['text'=>'Program persiapan SBMPTN SCI sangat membantu. Akhirnya lolos kampus impian!','name'=>'Ricky P.','role'=>'Mahasiswa · SBMPTN','initial'=>'R','gradient'=>'linear-gradient(135deg,#10b981,#059669)'],
            ['text'=>'Home visit-nya sangat nyaman. Tutornya datang tepat waktu dan sabar.','name'=>'Pak Hendra','role'=>'Orang Tua Siswa · Home Visit','initial'=>'H','gradient'=>'linear-gradient(135deg,#6366f1,#4338ca)'],
        ];
        $savedTestimonials = json_decode($s['branch_testimonials'] ?? '[]', true) ?: [];
        $editTestimonials  = !empty($savedTestimonials) ? $savedTestimonials : $defaultTestimonials;
    ?>

    
    <div class="alert border-0 mb-4 small" style="background:rgba(200,77,223,.06);border-left:4px solid #c84ddf !important;border-radius:12px">
        <i class="bi bi-info-circle me-1" style="color:var(--bs-primary)"></i>
        <strong>Kontak, alamat &amp; email</strong> dikelola di menu <a href="<?php echo e(route('owner.branches.edit', $branch)); ?>">Kelola Cabang</a>.
        Tab <strong>Harga</strong> mengatur kartu paket khusus cabang ini (menimpa paket global).
        Tab <strong>Testimoni</strong> mengatur testimoni khusus cabang ini (menimpa testimoni global).
    </div>

    
    <form action="<?php echo e(route('admin.landing.cabang.update', $branch)); ?>" method="POST"
          enctype="multipart/form-data" id="branchLandingForm">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

        
        <ul class="nav nav-tabs lp-tabs mb-4 flex-wrap" id="blTabs">
            <li class="nav-item"><button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#bl-promo"><i class="bi bi-megaphone me-1"></i>Promo</button></li>
            <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#bl-hero"><i class="bi bi-house-door me-1"></i>Hero</button></li>
            <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#bl-dipercaya"><i class="bi bi-star me-1"></i>Dipercaya</button></li>
            <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#bl-program"><i class="bi bi-book me-1"></i>Program</button></li>
            <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#bl-metode"><i class="bi bi-grid-3x3 me-1"></i>Metode</button></li>
            <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#bl-harga"><i class="bi bi-tag me-1"></i>Harga</button></li>
            <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#bl-testi"><i class="bi bi-chat-quote me-1"></i>Testimoni</button></li>
            <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#bl-jam"><i class="bi bi-clock me-1"></i>Lokasi &amp; Area</button></li>
            <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#bl-faq"><i class="bi bi-question-circle me-1"></i>FAQ</button></li>
            <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#bl-cta"><i class="bi bi-rocket me-1"></i>CTA</button></li>
        </ul>

        <div class="tab-content">

            
            <div class="tab-pane fade show active" id="bl-promo">
                <div class="card lp-card">
                    <div class="card-header lp-card-header"><i class="bi bi-megaphone me-2"></i>Promo Ticker
                        <small class="fw-normal ms-2 opacity-75">Teks bergulir di baris paling atas halaman cabang</small>
                    </div>
                    <div class="card-body">
                        <div class="alert border-0 mb-3" style="background:rgba(246,175,35,.08);border-left:3px solid #f6af23 !important;border-radius:10px;font-size:.83rem">
                            <i class="bi bi-lightbulb-fill me-1" style="color:#f6af23"></i>
                            Hingga <strong>8 item</strong> teks promo yang bergulir otomatis di bagian atas halaman.
                        </div>
                        <div id="promoList" class="d-flex flex-column gap-2 mb-3">
                            <?php $__currentLoopData = $promoItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="promo-row d-flex align-items-center gap-2">
                                <span class="badge bg-light text-muted fw-normal" style="font-size:.68rem;min-width:24px"><?php echo e($i+1); ?></span>
                                <input type="text" name="promo_items[]" value="<?php echo e($item); ?>" class="form-control form-control-sm" placeholder="Teks promo..." maxlength="200">
                                <button type="button" class="btn btn-sm btn-outline-danger promo-remove flex-shrink-0"><i class="bi bi-x-lg"></i></button>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <button type="button" id="promoAdd" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus me-1"></i>Tambah Item</button>
                        <div class="mt-3 p-3 rounded-3" style="background:linear-gradient(90deg,#e09000,#f6af23)">
                            <div class="text-dark fw-bold small mb-1">Preview:</div>
                            <div id="promoPreview" class="d-flex gap-3 flex-nowrap overflow-hidden" style="font-size:.82rem;font-weight:600;color:#260632"></div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="tab-pane fade" id="bl-hero">
                <div class="card lp-card">
                    <div class="card-header lp-card-header"><i class="bi bi-house-door me-2"></i>Les Privat Terbaik di <?php echo e($city); ?></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Teks Badge <small class="text-muted fw-normal">(tampil di atas judul utama)</small></label>
                                <div class="input-group">
                                    <span class="input-group-text">🏆</span>
                                    <input type="text" name="hero_badge" class="form-control"
                                           value="<?php echo e($s['hero_badge'] ?? '#1 Jasa Les Privat '.strtoupper($city).' Terpercaya'); ?>"
                                           maxlength="200">
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Deskripsi Hero <small class="text-muted fw-normal">(tampil di bawah judul)</small></label>
                                <textarea name="hero_description" class="form-control" rows="4" maxlength="600"><?php echo e($s['hero_description'] ?? 'Smart Center Indonesia hadir di '.$city.' dengan tutor bersertifikat. Layanan home visit, online, dan offline untuk semua jenjang dari TK hingga umum.'); ?></textarea>
                                <div class="form-text"><span id="heroDescCount">0</span>/600 karakter</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-image me-1" style="color:var(--bs-primary)"></i>
                                    Gambar Background Hero
                                    <small class="text-muted fw-normal ms-1">(upload dari laptop — jpg/png/webp, maks 5MB)</small>
                                </label>
                                <input type="file" name="hero_bg" class="form-control" accept="image/*">
                                <?php if($heroBgCurrent): ?>
                                <div class="mt-2">
                                    <small class="text-muted d-block mb-1">Gambar saat ini:</small>
                                    <img src="<?php echo e($heroBgCurrent); ?>" alt="Hero BG" style="height:90px;border-radius:10px;object-fit:cover;border:1px solid rgba(200,77,223,.2)">
                                </div>
                                <?php else: ?>
                                <div class="form-text">Kosongkan untuk menggunakan gambar default. Gambar akan ditampilkan transparan di belakang konten hero.</div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-12">
                                <div class="p-3 rounded-3" style="background:linear-gradient(160deg,#1a0228 0%,#461256 45%,#6d1a7e 100%)">
                                    <div class="small fw-bold mb-2" style="color:rgba(255,255,255,.5)">Preview Hero:</div>
                                    <div style="background:rgba(246,175,35,.15);border:1px solid rgba(246,175,35,.3);border-radius:50px;display:inline-flex;align-items:center;gap:6px;padding:4px 12px;font-size:.73rem;font-weight:700;color:#f6af23;margin-bottom:.6rem">
                                        🏆 <span id="prevBadge"><?php echo e($s['hero_badge'] ?? '#1 Jasa Les Privat '.strtoupper($city).' Terpercaya'); ?></span>
                                    </div><br>
                                    <div style="font-size:1.5rem;font-weight:900;color:white;line-height:1.1;margin-bottom:.5rem">
                                        Les Privat <em style="font-style:italic;color:#f6af23">Terbaik</em> di <?php echo e($city); ?>

                                    </div>
                                    <div style="font-size:.85rem;color:rgba(255,255,255,.7);max-width:380px" id="prevDesc"><?php echo e($s['hero_description'] ?? ''); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="tab-pane fade" id="bl-dipercaya">
                <div class="card lp-card">
                    <div class="card-header lp-card-header d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-star me-2"></i>Dipercaya Ribuan Keluarga di <?php echo e($city); ?></span>
                        <small class="fw-normal opacity-75">6 kartu keunggulan yang tampil di bawah hero</small>
                    </div>
                    <div class="card-body">
                        <div class="alert border-0 mb-3" style="background:rgba(200,77,223,.06);border-left:3px solid #c84ddf !important;border-radius:10px;font-size:.83rem">
                            <i class="bi bi-info-circle me-1" style="color:var(--bs-primary)"></i>
                            Edit 6 kartu keunggulan SCI di kota <?php echo e($city); ?>. Setiap kartu memiliki nomor, ikon emoji, judul, dan deskripsi.
                        </div>
                        <div id="featList" class="row g-3 mb-3">
                            <?php $__currentLoopData = $editFeatures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fi => $feat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6 feat-row">
                                <div class="p-3 rounded-3 h-100" style="background:#f9f4ff;border:1.5px solid rgba(200,77,223,.15)">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge fw-bold" style="background:rgba(200,77,223,.1);color:#68117e;min-width:28px"><?php echo e($fi+1); ?></span>
                                        <span class="text-muted small">Kartu Keunggulan</span>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-3">
                                            <label class="form-label small fw-semibold">Label No.</label>
                                            <input type="text" name="feat_num[]" value="<?php echo e($feat['num'] ?? sprintf('%02d',$fi+1)); ?>" class="form-control form-control-sm" maxlength="5" placeholder="01">
                                        </div>
                                        <div class="col-3">
                                            <label class="form-label small fw-semibold">Ikon</label>
                                            <input type="text" name="feat_icon[]" value="<?php echo e($feat['icon'] ?? ''); ?>" class="form-control form-control-sm" maxlength="10" placeholder="👩‍🏫">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small fw-semibold">Judul *</label>
                                            <input type="text" name="feat_title[]" value="<?php echo e($feat['title'] ?? ''); ?>" class="form-control form-control-sm" maxlength="60" placeholder="Tutor Bersertifikat" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small fw-semibold">Deskripsi</label>
                                            <textarea name="feat_desc[]" class="form-control form-control-sm" rows="2" maxlength="200"><?php echo e($feat['desc'] ?? ''); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Rekomendasi: tepat 6 kartu untuk tampilan terbaik di halaman cabang.</small>
                    </div>
                </div>
            </div>

            
            <div class="tab-pane fade" id="bl-program">
                <div class="card lp-card">
                    <div class="card-header lp-card-header d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-book me-2"></i>Program Les &amp; Kursus di <?php echo e($city); ?></span>
                        <small class="fw-normal opacity-75">Mata pelajaran yang tersedia di cabang ini</small>
                    </div>
                    <div class="card-body">
                        <div class="alert border-0 mb-3" style="background:rgba(200,77,223,.06);border-left:3px solid #c84ddf !important;border-radius:10px;font-size:.83rem">
                            <i class="bi bi-info-circle me-1" style="color:var(--bs-primary)"></i>
                            Edit atau hapus mata pelajaran yang tersedia. Gunakan tombol <strong>Tambah</strong> untuk menambah program baru.
                        </div>
                        <div id="subjList" class="row g-3 mb-3">
                            <?php $__currentLoopData = $editSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $si => $subj): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6 subj-row">
                                <div class="p-3 rounded-3" style="background:#f9f4ff;border:1.5px solid rgba(200,77,223,.15)">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="badge fw-bold" style="background:rgba(200,77,223,.1);color:#68117e;min-width:28px"><?php echo e($si+1); ?></span>
                                        <button type="button" class="btn btn-sm btn-outline-danger subj-remove py-0 px-2"><i class="bi bi-trash"></i></button>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-2">
                                            <label class="form-label small fw-semibold">Ikon</label>
                                            <input type="text" name="subj_icon[]" value="<?php echo e($subj['icon'] ?? ''); ?>" class="form-control form-control-sm" maxlength="10" placeholder="📚">
                                        </div>
                                        <div class="col-10">
                                            <label class="form-label small fw-semibold">Nama Program *</label>
                                            <input type="text" name="subj_name[]" value="<?php echo e($subj['name'] ?? ''); ?>" class="form-control form-control-sm" maxlength="60" placeholder="Matematika">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small fw-semibold">Deskripsi</label>
                                            <input type="text" name="subj_desc[]" value="<?php echo e($subj['desc'] ?? ''); ?>" class="form-control form-control-sm" maxlength="150">
                                        </div>
                                        <div class="col-7">
                                            <label class="form-label small fw-semibold">Teks Badge</label>
                                            <input type="text" name="subj_badge[]" value="<?php echo e($subj['badge'] ?? ''); ?>" class="form-control form-control-sm" maxlength="30" placeholder="Terpopuler">
                                        </div>
                                        <div class="col-5">
                                            <label class="form-label small fw-semibold">Warna Badge</label>
                                            <select name="subj_badge_type[]" class="form-select form-select-sm">
                                                <?php $__currentLoopData = ['popular'=>'Ungu (populer)','hot'=>'Merah (hot)','level'=>'Biru (level)','general'=>'Hijau (umum)']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $lbl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($val); ?>" <?php echo e(($subj['badge_type'] ?? 'general') === $val ? 'selected' : ''); ?>><?php echo e($lbl); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <button type="button" id="subjAdd" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-plus me-1"></i>Tambah Program
                        </button>
                    </div>
                </div>
            </div>

            
            <div class="tab-pane fade" id="bl-metode">
                <div class="card lp-card">
                    <div class="card-header lp-card-header"><i class="bi bi-grid-3x3 me-2"></i>Pilih Cara Belajar Terbaik</div>
                    <div class="card-body">
                        <div class="alert border-0 mb-4" style="background:rgba(200,77,223,.06);border-left:3px solid #c84ddf !important;border-radius:10px;font-size:.83rem">
                            <i class="bi bi-info-circle me-1" style="color:var(--bs-primary)"></i>
                            Atur harga dan gambar untuk setiap metode belajar. Upload gambar dari laptop untuk mengganti foto default.
                        </div>
                        <div class="row g-4">
                            <?php $__currentLoopData = [
                                ['key'=>'homevisi','label'=>'Home Visit','emoji'=>'🏠','default_price'=>'Rp 65.000','img_field'=>'metode_img_homevisi','cur_img'=>$metodeImgHv,'default_img'=>'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=600&q=80'],
                                ['key'=>'online',  'label'=>'Online',    'emoji'=>'🖥️','default_price'=>'Rp 50.000','img_field'=>'metode_img_online',   'cur_img'=>$metodeImgOn,'default_img'=>'https://images.unsplash.com/photo-1588702547923-7093a6c3ba33?w=600&q=80'],
                                ['key'=>'offline', 'label'=>'Offline',   'emoji'=>'🏫','default_price'=>'Rp 55.000','img_field'=>'metode_img_offline',  'cur_img'=>$metodeImgOf,'default_img'=>'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=600&q=80'],
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-4">
                                <div class="p-3 rounded-3 h-100" style="background:#f9f4ff;border:1.5px solid rgba(200,77,223,.15)">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <span style="font-size:1.4rem"><?php echo e($m['emoji']); ?></span>
                                        <div class="fw-bold" style="color:#260632"><?php echo e($m['label']); ?></div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Harga per Sesi</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" name="price_<?php echo e($m['key']); ?>" class="form-control price-input"
                                                   value="<?php echo e($s['price_'.$m['key']] ?? $m['default_price']); ?>"
                                                   placeholder="<?php echo e($m['default_price']); ?>" maxlength="50">
                                        </div>
                                        <div class="form-text">Default: <?php echo e($m['default_price']); ?></div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold"><i class="bi bi-laptop me-1"></i>Foto (upload dari laptop)</label>
                                        <input type="file" name="<?php echo e($m['img_field']); ?>" class="form-control form-control-sm" accept="image/*">
                                    </div>
                                    
                                    <?php $imgSrc = $m['cur_img'] ?: $m['default_img']; ?>
                                    <div class="rounded-2 overflow-hidden" style="height:110px">
                                        <img src="<?php echo e($imgSrc); ?>" alt="<?php echo e($m['label']); ?>" style="width:100%;height:100%;object-fit:cover" class="metode-img-<?php echo e($m['key']); ?>">
                                        <?php if($m['cur_img']): ?>
                                        <div class="mt-1"><span class="badge bg-success" style="font-size:.65rem">Gambar kustom aktif</span></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="tab-pane fade" id="bl-harga">
                <div class="card lp-card">
                    <div class="card-header lp-card-header d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-tag me-2"></i>Harga Les Privat <?php echo e($city); ?></span>
                        <small class="fw-normal opacity-75">Kartu paket pada section Harga di halaman cabang</small>
                    </div>
                    <div class="card-body">
                        <div class="alert border-0 mb-4" style="background:rgba(200,77,223,.06);border-left:3px solid #c84ddf !important;border-radius:10px;font-size:.83rem">
                            <i class="bi bi-info-circle me-1" style="color:var(--bs-primary)"></i>
                            Maksimal <strong>3 kartu paket</strong> yang tampil di halaman cabang. Centang <strong>Unggulan</strong> pada satu kartu untuk tampilan menonjol (warna gelap).
                        </div>
                        <div id="hargaList" class="row g-4 mb-4">
                            <?php $__currentLoopData = $editPricingCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pi => $pkg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-4 harga-card-col" data-idx="<?php echo e($pi); ?>">
                                <div class="p-3 rounded-3 h-100" style="background:#f9f4ff;border:1.5px solid rgba(200,77,223,.15)">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <span class="badge fw-bold harga-num" style="background:rgba(200,77,223,.1);color:#68117e;min-width:28px"><?php echo e($pi+1); ?></span>
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" name="pkg_unggulan[]" value="<?php echo e($pi); ?>" id="pkgUng<?php echo e($pi); ?>" <?php echo e(($pkg['unggulan'] ?? false) ? 'checked' : ''); ?>>
                                            <label class="form-check-label small fw-semibold" for="pkgUng<?php echo e($pi); ?>">Unggulan</label>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger harga-remove py-0 px-2"><i class="bi bi-trash"></i></button>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <label class="form-label small fw-semibold mb-1">Nama Paket *</label>
                                            <input type="text" name="pkg_name[]" value="<?php echo e($pkg['name'] ?? ''); ?>" class="form-control form-control-sm" maxlength="80" placeholder="Paket Reguler" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small fw-semibold mb-1">Deskripsi Singkat</label>
                                            <input type="text" name="pkg_desc[]" value="<?php echo e($pkg['desc'] ?? ''); ?>" class="form-control form-control-sm" maxlength="150" placeholder="Cocok untuk pemula...">
                                        </div>
                                        <div class="col-7">
                                            <label class="form-label small fw-semibold mb-1">Harga</label>
                                            <input type="text" name="pkg_price[]" value="<?php echo e($pkg['price'] ?? ''); ?>" class="form-control form-control-sm" maxlength="40" placeholder="Rp 50.000">
                                        </div>
                                        <div class="col-5">
                                            <label class="form-label small fw-semibold mb-1">Satuan</label>
                                            <input type="text" name="pkg_unit[]" value="<?php echo e($pkg['unit'] ?? '/sesi'); ?>" class="form-control form-control-sm" maxlength="20" placeholder="/sesi">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small fw-semibold mb-1">Info Sesi</label>
                                            <input type="text" name="pkg_sessions[]" value="<?php echo e($pkg['sessions'] ?? ''); ?>" class="form-control form-control-sm" maxlength="80" placeholder="1× per minggu (4 sesi/bln)">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small fw-semibold mb-1">Fitur <small class="text-muted fw-normal">(1 fitur per baris)</small></label>
                                            <textarea name="pkg_fitur[]" class="form-control form-control-sm" rows="4" placeholder="Durasi 90 menit/sesi&#10;Laporan bulanan&#10;..."><?php echo e(implode("\n", $pkg['fitur'] ?? [])); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <button type="button" id="hargaAdd" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus me-1"></i>Tambah Kartu Paket</button>
                            <small class="text-muted">Rekomendasi: 3 kartu</small>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="tab-pane fade" id="bl-testi">
                <div class="card lp-card">
                    <div class="card-header lp-card-header d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-chat-quote me-2"></i>Testimoni Siswa <?php echo e($city); ?></span>
                        <small class="fw-normal opacity-75">Carousel testimoni pada halaman cabang</small>
                    </div>
                    <div class="card-body">
                        <div class="alert border-0 mb-4" style="background:rgba(200,77,223,.06);border-left:3px solid #c84ddf !important;border-radius:10px;font-size:.83rem">
                            <i class="bi bi-info-circle me-1" style="color:var(--bs-primary)"></i>
                            Testimoni ini <strong>khusus untuk cabang <?php echo e($city); ?></strong> dan akan menimpa testimoni global. Rekomendasi: 4–8 testimoni. Carousel berulang otomatis.
                        </div>
                        <div id="testiList" class="d-flex flex-column gap-3 mb-4">
                            <?php $__currentLoopData = $editTestimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ti => $testi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="testi-row border rounded-3 p-3" style="border-color:rgba(200,77,223,.15) !important;background:#fdf8ff">
                                <div class="d-flex align-items-start gap-2">
                                    <span class="badge fw-bold mt-1 testi-num" style="background:rgba(200,77,223,.1);color:#68117e;min-width:24px"><?php echo e($ti+1); ?></span>
                                    <div class="flex-grow-1">
                                        <div class="row g-2">
                                            <div class="col-12">
                                                <label class="form-label small fw-semibold mb-1">Teks Testimoni *</label>
                                                <textarea name="testi_text[]" class="form-control form-control-sm" rows="3" maxlength="400" placeholder="Cerita sukses siswa..."><?php echo e($testi['text'] ?? ''); ?></textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold mb-1">Nama</label>
                                                <input type="text" name="testi_name[]" value="<?php echo e($testi['name'] ?? ''); ?>" class="form-control form-control-sm" maxlength="60" placeholder="Bunda Sari">
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-label small fw-semibold mb-1">Peran / Keterangan</label>
                                                <input type="text" name="testi_role[]" value="<?php echo e($testi['role'] ?? ''); ?>" class="form-control form-control-sm" maxlength="80" placeholder="Orang Tua Siswa · <?php echo e($city); ?>">
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label small fw-semibold mb-1">Inisial</label>
                                                <input type="text" name="testi_initial[]" value="<?php echo e($testi['initial'] ?? ''); ?>" class="form-control form-control-sm" maxlength="3" placeholder="B">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label small fw-semibold mb-1">Warna Avatar</label>
                                                <select name="testi_color[]" class="form-select form-select-sm">
                                                    <?php
                                                        $colorOpts = [
                                                            'linear-gradient(135deg,#c84ddf,#68117e)' => 'Ungu (default)',
                                                            'linear-gradient(135deg,#f97316,#ea580c)' => 'Oranye',
                                                            'linear-gradient(135deg,#10b981,#059669)' => 'Hijau',
                                                            'linear-gradient(135deg,#6366f1,#4338ca)' => 'Biru',
                                                            'linear-gradient(135deg,#f43f5e,#be123c)' => 'Merah',
                                                            'linear-gradient(135deg,#f59e0b,#d97706)' => 'Kuning',
                                                            'linear-gradient(135deg,#06b6d4,#0891b2)' => 'Teal',
                                                        ];
                                                        $curGrad = $testi['gradient'] ?? 'linear-gradient(135deg,#c84ddf,#68117e)';
                                                    ?>
                                                    <?php $__currentLoopData = $colorOpts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($val); ?>" <?php echo e($curGrad === $val ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger testi-remove flex-shrink-0 mt-1"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <button type="button" id="testiAdd" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus me-1"></i>Tambah Testimoni</button>
                            <small class="text-muted">Rekomendasi: 4–8 testimoni</small>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="tab-pane fade" id="bl-jam">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card lp-card h-100">
                            <div class="card-header lp-card-header"><i class="bi bi-clock me-2"></i>Kantor SCI <?php echo e($city); ?> — Jam Operasional</div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-semibold"><i class="bi bi-calendar-week me-1"></i>Senin – Sabtu</label>
                                        <input type="text" name="hours_weekday" class="form-control"
                                               value="<?php echo e($s['hours_weekday'] ?? '08.00 – 20.00 WIB'); ?>" maxlength="100">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold"><i class="bi bi-calendar-event me-1" style="color:#10b981"></i>Minggu &amp; Hari Libur</label>
                                        <input type="text" name="hours_weekend" class="form-control"
                                               value="<?php echo e($s['hours_weekend'] ?? '09.00 – 16.00 WIB'); ?>" maxlength="100">
                                        <div class="form-text">Contoh: 09.00 – 16.00 WIB atau Tutup</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="p-3 rounded-3" style="background:#f9f4ff;border:1.5px solid rgba(200,77,223,.15)">
                                            <div class="small fw-bold mb-1" style="color:var(--bs-primary)">Preview:</div>
                                            <div class="small text-muted">Senin–Sabtu: <span id="prevWeekday"><?php echo e($s['hours_weekday'] ?? '08.00 – 20.00 WIB'); ?></span></div>
                                            <div class="small text-muted">Minggu: <span id="prevWeekend"><?php echo e($s['hours_weekend'] ?? '09.00 – 16.00 WIB'); ?></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card lp-card h-100">
                            <div class="card-header lp-card-header"><i class="bi bi-pin-map me-2"></i>Area Layanan Home Visit</div>
                            <div class="card-body">
                                <label class="form-label fw-semibold">Daftar Area <small class="text-muted fw-normal">(pisahkan dengan koma)</small></label>
                                <textarea name="areas" id="areasInput" class="form-control mb-3" rows="4"
                                          placeholder="Kota <?php echo e($city); ?>, Kab. <?php echo e($city); ?>, Sekitarnya"><?php echo e($areasStr); ?></textarea>
                                <div class="small fw-semibold mb-2" style="color:var(--bs-primary)">Preview Chip Area:</div>
                                <div id="areasPreview" class="d-flex gap-2 flex-wrap"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="tab-pane fade" id="bl-faq">
                <div class="card lp-card">
                    <div class="card-header lp-card-header d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-question-circle me-2"></i>Pertanyaan Umum Les Privat <?php echo e($city); ?></span>
                        <small class="fw-normal opacity-75">Accordion FAQ pada halaman cabang</small>
                    </div>
                    <div class="card-body">
                        <div id="faqList" class="d-flex flex-column gap-3 mb-4">
                            <?php $__currentLoopData = $faqItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fi => $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="faq-row border rounded-3 p-3" style="border-color:rgba(200,77,223,.15) !important;background:#fdf8ff">
                                <div class="d-flex align-items-start gap-2">
                                    <span class="badge fw-bold mt-1 faq-num" style="background:rgba(200,77,223,.1);color:#68117e;min-width:24px"><?php echo e($fi+1); ?></span>
                                    <div class="flex-grow-1">
                                        <label class="form-label small fw-semibold mb-1">Pertanyaan</label>
                                        <input type="text" name="faq_q[]" value="<?php echo e($faq['q']); ?>" class="form-control form-control-sm mb-2" maxlength="300">
                                        <label class="form-label small fw-semibold mb-1">Jawaban</label>
                                        <textarea name="faq_a[]" class="form-control form-control-sm" rows="3" maxlength="1000"><?php echo e($faq['a']); ?></textarea>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger faq-remove flex-shrink-0 mt-1"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <button type="button" id="faqAdd" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus me-1"></i>Tambah FAQ</button>
                            <small class="text-muted">Rekomendasi: 6–8 pertanyaan</small>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="tab-pane fade" id="bl-cta">
                <div class="card lp-card">
                    <div class="card-header lp-card-header"><i class="bi bi-rocket me-2"></i>Siap Mulai Belajar — Section CTA</div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Eyebrow <small class="text-muted fw-normal">(label kecil di atas judul CTA)</small></label>
                                <input type="text" name="cta_eyebrow" class="form-control"
                                       value="<?php echo e($s['cta_eyebrow'] ?? '🎉 Bergabung Sekarang'); ?>" maxlength="80">
                                <div class="form-text">Contoh: "🎉 Bergabung Sekarang"</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Judul CTA <small class="text-muted fw-normal">(baris sebelum nama kota)</small></label>
                                <input type="text" name="cta_title" class="form-control"
                                       value="<?php echo e($s['cta_title'] ?? 'Siap Mulai Belajar'); ?>" maxlength="120">
                                <div class="form-text">Nama kota otomatis ditambahkan di bawah judul ini.</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Deskripsi CTA</label>
                                <textarea name="cta_desc" class="form-control" rows="3" maxlength="400"><?php echo e($s['cta_desc'] ?? ''); ?></textarea>
                                <div class="form-text">Kosongkan untuk menggunakan teks default (menyebutkan jumlah siswa dan kota).</div>
                            </div>
                            
                            <div class="col-12">
                                <div class="p-4 rounded-3 text-center" style="background:linear-gradient(160deg,#260632 0%,#461256 60%,#8b1fa8 100%)">
                                    <div style="display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.15);border-radius:50px;padding:5px 16px;font-size:.73rem;font-weight:700;color:rgba(255,255,255,.9);margin-bottom:.75rem">
                                        <?php echo e($s['cta_eyebrow'] ?? '🎉 Bergabung Sekarang'); ?>

                                    </div>
                                    <div style="font-size:1.5rem;font-weight:900;color:white;line-height:1.2;margin-bottom:.5rem">
                                        <?php echo e($s['cta_title'] ?? 'Siap Mulai Belajar'); ?><br>
                                        <em style="font-style:italic;color:#f6af23"><?php echo e($city); ?>?</em>
                                    </div>
                                    <div style="font-size:.9rem;color:rgba(255,255,255,.7)"><?php echo e($s['cta_desc'] ?? 'Bergabung dengan siswa SCI '.$city.' yang telah merasakan manfaatnya.'); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        
        <div class="position-sticky bottom-0 mt-4 py-3" style="z-index:100;background:linear-gradient(to top,var(--body-bg,#f8f5ff) 60%,transparent)">
            <div class="d-flex align-items-center justify-content-between gap-3 p-3 rounded-3"
                 style="background:white;box-shadow:0 -4px 24px rgba(38,6,50,.1);border:1px solid rgba(200,77,223,.12)">
                <div class="small text-muted d-none d-md-block">
                    <i class="bi bi-save me-1" style="color:var(--bs-primary)"></i>
                    Pastikan semua tab sudah dikonfigurasi sebelum menyimpan
                </div>
                <div class="d-flex gap-2 ms-auto">
                    <a href="<?php echo e(route('admin.landing.cabang.index')); ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-x me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">
                        <i class="bi bi-save me-1"></i>Simpan Semua Perubahan
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>


<template id="hargaCardTpl">
    <div class="col-md-4 harga-card-col">
        <div class="p-3 rounded-3 h-100" style="background:#f9f4ff;border:1.5px solid rgba(200,77,223,.15)">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="badge fw-bold harga-num" style="background:rgba(200,77,223,.1);color:#68117e;min-width:28px">?</span>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" name="pkg_unggulan[]" value="0">
                    <label class="form-check-label small fw-semibold">Unggulan</label>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger harga-remove py-0 px-2"><i class="bi bi-trash"></i></button>
            </div>
            <div class="row g-2">
                <div class="col-12">
                    <label class="form-label small fw-semibold mb-1">Nama Paket *</label>
                    <input type="text" name="pkg_name[]" class="form-control form-control-sm" maxlength="80" placeholder="Nama paket...">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold mb-1">Deskripsi Singkat</label>
                    <input type="text" name="pkg_desc[]" class="form-control form-control-sm" maxlength="150" placeholder="Cocok untuk...">
                </div>
                <div class="col-7">
                    <label class="form-label small fw-semibold mb-1">Harga</label>
                    <input type="text" name="pkg_price[]" class="form-control form-control-sm" maxlength="40" placeholder="Rp 50.000">
                </div>
                <div class="col-5">
                    <label class="form-label small fw-semibold mb-1">Satuan</label>
                    <input type="text" name="pkg_unit[]" value="/sesi" class="form-control form-control-sm" maxlength="20" placeholder="/sesi">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold mb-1">Info Sesi</label>
                    <input type="text" name="pkg_sessions[]" class="form-control form-control-sm" maxlength="80" placeholder="1× per minggu">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold mb-1">Fitur <small class="text-muted fw-normal">(1 fitur per baris)</small></label>
                    <textarea name="pkg_fitur[]" class="form-control form-control-sm" rows="4" placeholder="Durasi 90 menit/sesi&#10;Laporan bulanan&#10;..."></textarea>
                </div>
            </div>
        </div>
    </div>
</template>


<template id="testiRowTpl">
    <div class="testi-row border rounded-3 p-3" style="border-color:rgba(200,77,223,.15) !important;background:#fdf8ff">
        <div class="d-flex align-items-start gap-2">
            <span class="badge fw-bold mt-1 testi-num" style="background:rgba(200,77,223,.1);color:#68117e;min-width:24px">?</span>
            <div class="flex-grow-1">
                <div class="row g-2">
                    <div class="col-12">
                        <label class="form-label small fw-semibold mb-1">Teks Testimoni *</label>
                        <textarea name="testi_text[]" class="form-control form-control-sm" rows="3" maxlength="400" placeholder="Cerita sukses siswa..."></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold mb-1">Nama</label>
                        <input type="text" name="testi_name[]" class="form-control form-control-sm" maxlength="60" placeholder="Bunda Sari">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small fw-semibold mb-1">Peran / Keterangan</label>
                        <input type="text" name="testi_role[]" class="form-control form-control-sm" maxlength="80" placeholder="Orang Tua Siswa">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label small fw-semibold mb-1">Inisial</label>
                        <input type="text" name="testi_initial[]" class="form-control form-control-sm" maxlength="3" placeholder="B">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold mb-1">Warna Avatar</label>
                        <select name="testi_color[]" class="form-select form-select-sm">
                            <option value="linear-gradient(135deg,#c84ddf,#68117e)">Ungu (default)</option>
                            <option value="linear-gradient(135deg,#f97316,#ea580c)">Oranye</option>
                            <option value="linear-gradient(135deg,#10b981,#059669)">Hijau</option>
                            <option value="linear-gradient(135deg,#6366f1,#4338ca)">Biru</option>
                            <option value="linear-gradient(135deg,#f43f5e,#be123c)">Merah</option>
                            <option value="linear-gradient(135deg,#f59e0b,#d97706)">Kuning</option>
                            <option value="linear-gradient(135deg,#06b6d4,#0891b2)">Teal</option>
                        </select>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger testi-remove flex-shrink-0 mt-1"><i class="bi bi-trash"></i></button>
        </div>
    </div>
</template>


<template id="faqRowTpl">
    <div class="faq-row border rounded-3 p-3" style="border-color:rgba(200,77,223,.15) !important;background:#fdf8ff">
        <div class="d-flex align-items-start gap-2">
            <span class="badge fw-bold mt-1 faq-num" style="background:rgba(200,77,223,.1);color:#68117e;min-width:24px">?</span>
            <div class="flex-grow-1">
                <label class="form-label small fw-semibold mb-1">Pertanyaan</label>
                <input type="text" name="faq_q[]" class="form-control form-control-sm mb-2" maxlength="300" placeholder="Pertanyaan yang sering diajukan...">
                <label class="form-label small fw-semibold mb-1">Jawaban</label>
                <textarea name="faq_a[]" class="form-control form-control-sm" rows="3" maxlength="1000" placeholder="Jawaban lengkap..."></textarea>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger faq-remove flex-shrink-0 mt-1"><i class="bi bi-trash"></i></button>
        </div>
    </div>
</template>


<template id="subjRowTpl">
    <div class="col-md-6 subj-row">
        <div class="p-3 rounded-3" style="background:#f9f4ff;border:1.5px solid rgba(200,77,223,.15)">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="badge fw-bold subj-num" style="background:rgba(200,77,223,.1);color:#68117e;min-width:28px">?</span>
                <button type="button" class="btn btn-sm btn-outline-danger subj-remove py-0 px-2"><i class="bi bi-trash"></i></button>
            </div>
            <div class="row g-2">
                <div class="col-2">
                    <label class="form-label small fw-semibold">Ikon</label>
                    <input type="text" name="subj_icon[]" class="form-control form-control-sm" maxlength="10" placeholder="📚">
                </div>
                <div class="col-10">
                    <label class="form-label small fw-semibold">Nama Program *</label>
                    <input type="text" name="subj_name[]" class="form-control form-control-sm" maxlength="60" placeholder="Matematika">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold">Deskripsi</label>
                    <input type="text" name="subj_desc[]" class="form-control form-control-sm" maxlength="150">
                </div>
                <div class="col-7">
                    <label class="form-label small fw-semibold">Teks Badge</label>
                    <input type="text" name="subj_badge[]" class="form-control form-control-sm" maxlength="30" placeholder="Terpopuler">
                </div>
                <div class="col-5">
                    <label class="form-label small fw-semibold">Warna Badge</label>
                    <select name="subj_badge_type[]" class="form-select form-select-sm">
                        <option value="popular">Ungu (populer)</option>
                        <option value="hot">Merah (hot)</option>
                        <option value="level">Biru (level)</option>
                        <option value="general" selected>Hijau (umum)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.lp-tabs .nav-link { color:var(--text-muted,#6b7280); border-radius:10px 10px 0 0; font-weight:600; font-size:.875rem; padding:.6rem 1.1rem; }
.lp-tabs .nav-link.active { color:var(--bs-primary,#c84ddf); background:var(--card-bg,white); border-color:var(--card-border,#dee2e6) var(--card-border,#dee2e6) var(--card-bg,white); }
.lp-card { border:1px solid rgba(200,77,223,.15); border-radius:16px; overflow:hidden; box-shadow:0 2px 16px rgba(38,6,50,.06); }
.lp-card-header { background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%); color:white; font-weight:700; font-size:.925rem; padding:.9rem 1.25rem; }
</style>

<?php $__env->startPush('scripts'); ?>
<script>
/* ── Promo ticker ── */
const promoList = document.getElementById('promoList');
function updatePromoNumbers() {
    promoList.querySelectorAll('.promo-row').forEach((row, i) => {
        const b = row.querySelector('.badge'); if (b) b.textContent = i + 1;
    });
    renderPromoPreview();
}
function addPromoRow(val = '') {
    if (promoList.querySelectorAll('.promo-row').length >= 8) return alert('Maksimal 8 item promo.');
    const d = document.createElement('div');
    d.className = 'promo-row d-flex align-items-center gap-2';
    d.innerHTML = `<span class="badge bg-light text-muted fw-normal" style="font-size:.68rem;min-width:24px"></span><input type="text" name="promo_items[]" value="${val.replace(/"/g,'&quot;')}" class="form-control form-control-sm" placeholder="Teks promo..." maxlength="200"><button type="button" class="btn btn-sm btn-outline-danger promo-remove flex-shrink-0"><i class="bi bi-x-lg"></i></button>`;
    promoList.appendChild(d);
    d.querySelector('input').addEventListener('input', renderPromoPreview);
    updatePromoNumbers();
}
document.getElementById('promoAdd').addEventListener('click', () => addPromoRow());
promoList.addEventListener('click', e => { if (e.target.closest('.promo-remove')) { e.target.closest('.promo-row').remove(); updatePromoNumbers(); } });
promoList.querySelectorAll('input').forEach(i => i.addEventListener('input', renderPromoPreview));
function renderPromoPreview() {
    const vals = [...promoList.querySelectorAll('input')].map(i => i.value).filter(Boolean);
    document.getElementById('promoPreview').innerHTML = vals.map(v => `<span style="white-space:nowrap">📢 ${v}</span><span style="opacity:.4">|</span>`).join('');
}
renderPromoPreview();

/* ── Hero live preview ── */
const heroBadgeIn = document.querySelector('[name=hero_badge]');
const heroDescIn  = document.querySelector('[name=hero_description]');
const descCount   = document.getElementById('heroDescCount');
function updateHeroPreview() {
    if (heroBadgeIn) document.getElementById('prevBadge').textContent = heroBadgeIn.value;
    if (heroDescIn) { document.getElementById('prevDesc').textContent = heroDescIn.value; if(descCount) descCount.textContent = heroDescIn.value.length; }
}
if (heroBadgeIn) heroBadgeIn.addEventListener('input', updateHeroPreview);
if (heroDescIn)  heroDescIn.addEventListener('input', updateHeroPreview);
updateHeroPreview();

/* ── Jam live preview ── */
const weekdayIn = document.querySelector('[name=hours_weekday]');
const weekendIn = document.querySelector('[name=hours_weekend]');
if (weekdayIn) weekdayIn.addEventListener('input', () => { document.getElementById('prevWeekday').textContent = weekdayIn.value; });
if (weekendIn) weekendIn.addEventListener('input', () => { document.getElementById('prevWeekend').textContent = weekendIn.value; });

/* ── Area chips preview ── */
const areasInput   = document.getElementById('areasInput');
const areasPreview = document.getElementById('areasPreview');
function renderAreaChips() {
    const chips = areasInput.value.split(',').map(s => s.trim()).filter(Boolean);
    areasPreview.innerHTML = chips.map(c => `<span style="display:inline-flex;align-items:center;gap:.3rem;padding:.35rem .9rem;border-radius:8px;background:#fdf6ff;border:1.5px solid rgba(200,77,223,.2);font-size:.8rem;font-weight:600;color:#260632">📍 ${c}</span>`).join('');
}
if (areasInput) { areasInput.addEventListener('input', renderAreaChips); renderAreaChips(); }

/* ── FAQ dynamic rows ── */
const faqList = document.getElementById('faqList');
const faqTpl  = document.getElementById('faqRowTpl');
function updateFaqNumbers() {
    faqList.querySelectorAll('.faq-row').forEach((row, i) => {
        const n = row.querySelector('.faq-num'); if (n) n.textContent = i + 1;
    });
}
document.getElementById('faqAdd').addEventListener('click', () => {
    faqList.appendChild(faqTpl.content.cloneNode(true)); updateFaqNumbers();
});
faqList.addEventListener('click', e => {
    if (e.target.closest('.faq-remove')) { e.target.closest('.faq-row').remove(); updateFaqNumbers(); }
});

/* ── Subject dynamic rows ── */
const subjList = document.getElementById('subjList');
const subjTpl  = document.getElementById('subjRowTpl');
function updateSubjNumbers() {
    subjList.querySelectorAll('.subj-row').forEach((row, i) => {
        const n = row.querySelector('.subj-num'); if (n) n.textContent = i + 1;
    });
}
document.getElementById('subjAdd').addEventListener('click', () => {
    subjList.appendChild(subjTpl.content.cloneNode(true)); updateSubjNumbers();
});
subjList.addEventListener('click', e => {
    if (e.target.closest('.subj-remove')) { e.target.closest('.subj-row').remove(); updateSubjNumbers(); }
});

/* ── Harga / Pricing card dynamic rows ── */
const hargaList = document.getElementById('hargaList');
const hargaTpl  = document.getElementById('hargaCardTpl');
function updateHargaNumbers() {
    let idx = 0;
    hargaList.querySelectorAll('.harga-card-col').forEach((col) => {
        const n = col.querySelector('.harga-num'); if (n) n.textContent = idx + 1;
        const cb = col.querySelector('input[name="pkg_unggulan[]"]'); if (cb) cb.value = idx;
        idx++;
    });
}
document.getElementById('hargaAdd').addEventListener('click', () => {
    if (hargaList.querySelectorAll('.harga-card-col').length >= 3) {
        return alert('Maksimal 3 kartu paket.');
    }
    hargaList.appendChild(hargaTpl.content.cloneNode(true));
    updateHargaNumbers();
});
hargaList.addEventListener('click', e => {
    if (e.target.closest('.harga-remove')) {
        e.target.closest('.harga-card-col').remove();
        updateHargaNumbers();
    }
});

/* ── Testimoni dynamic rows ── */
const testiList = document.getElementById('testiList');
const testiTpl  = document.getElementById('testiRowTpl');
function updateTestiNumbers() {
    testiList.querySelectorAll('.testi-row').forEach((row, i) => {
        const n = row.querySelector('.testi-num'); if (n) n.textContent = i + 1;
    });
}
document.getElementById('testiAdd').addEventListener('click', () => {
    testiList.appendChild(testiTpl.content.cloneNode(true));
    updateTestiNumbers();
});
testiList.addEventListener('click', e => {
    if (e.target.closest('.testi-remove')) {
        e.target.closest('.testi-row').remove();
        updateTestiNumbers();
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/landing/cabang.blade.php ENDPATH**/ ?>