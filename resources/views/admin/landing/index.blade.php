@extends('layouts.app')

@section('page-title', 'Kelola Landing Page')

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER BANNER --}}
    <div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
        <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
        <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
        <div class="row align-items-center g-3" style="position:relative">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                        <i class="bi bi-window-fullscreen"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0" style="color:white">Kelola Konten Landing Page</h5>
                        <span style="font-size:12px;opacity:.8">Edit teks hero, program, testimoni, dan pengaturan halaman utama</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ url('/') }}" target="_blank" class="btn fw-semibold px-4"
                   style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                    <i class="bi bi-eye me-2"></i>Lihat Landing Page
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Tabs --}}
    <ul class="nav nav-tabs lp-tabs mb-4 flex-nowrap overflow-auto" id="lpTabs" style="flex-wrap:nowrap">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-hero"><i class="bi bi-house-door me-1"></i>Hero</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-ticker"><i class="bi bi-megaphone-fill me-1"></i>Promo Ticker</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-tentang"><i class="bi bi-building me-1"></i>Tentang</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-stats"><i class="bi bi-bar-chart me-1"></i>Statistik</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-programs"><i class="bi bi-award me-1"></i>Program</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-jenjang"><i class="bi bi-mortarboard me-1"></i>Jenjang</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-cariguru"><i class="bi bi-search me-1"></i>Cari Guru</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-keunggulan"><i class="bi bi-shield-fill-check me-1"></i>Keunggulan</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-testimonials"><i class="bi bi-chat-heart me-1"></i>Testimoni</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-galeri"><i class="bi bi-images me-1"></i>Galeri</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-faq"><i class="bi bi-question-circle me-1"></i>FAQ &amp; Kontak</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-tutor"><i class="bi bi-person-badge me-1"></i>Tutor</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-wa"><i class="bi bi-whatsapp me-1 text-success"></i>WhatsApp <span class="badge bg-success ms-1" style="font-size:.65rem">{{ $waNumbers->count() }}</span></button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-cta"><i class="bi bi-megaphone me-1"></i>CTA</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-footer"><i class="bi bi-layout-sidebar-inset-reverse me-1"></i>Footer</button></li>
        <li class="nav-item"><a class="nav-link d-flex align-items-center gap-1 fw-bold" href="{{ route('admin.landing.cabang.index') }}" style="color:var(--bs-primary)"><i class="bi bi-geo-alt-fill me-1"></i>Halaman Cabang <i class="bi bi-arrow-right" style="font-size:.75rem"></i></a></li>
    </ul>

    <div class="tab-content">

        {{-- ────── HERO TAB ────── --}}
        <div class="tab-pane fade show active" id="tab-hero">
            <div class="card lp-card">
                <div class="card-header lp-card-header"><i class="bi bi-house-door me-2"></i>Konten Hero Section</div>
                <div class="card-body">
                    <form action="{{ route('admin.landing.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Teks Badge</label>
                                <input type="text" name="settings[hero.badge_text]" class="form-control" value="{{ $settings['hero.badge_text']->value ?? 'Bimbel & Kursus Terbaik #1 di Indonesia' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Judul Baris 1</label>
                                <input type="text" name="settings[hero.title_line1]" class="form-control" value="{{ $settings['hero.title_line1']->value ?? 'Wujudkan Mimpi,' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Judul Baris 2 <small class="text-primary">(tampil gradien emas)</small></label>
                                <input type="text" name="settings[hero.title_line2]" class="form-control" value="{{ $settings['hero.title_line2']->value ?? 'Raih Prestasi!' }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Deskripsi Hero</label>
                                <textarea name="settings[hero.description]" class="form-control" rows="3">{{ $settings['hero.description']->value ?? '' }}</textarea>
                            </div>
                            <div class="col-12"><hr class="my-1"><p class="fw-semibold text-muted mb-2"><i class="bi bi-images me-1"></i>Gambar Slide Hero (upload dari komputer atau isi URL)</p></div>
                            @foreach([1,2,3] as $sn)
                            <div class="col-12">
                                <label class="form-label">Slide {{ $sn }}</label>
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-7">
                                        <input type="url" name="settings[hero.slide_{{ $sn }}_url]" class="form-control" value="{{ $settings['hero.slide_'.$sn.'_url']->value ?? '' }}" placeholder="URL gambar (opsional jika upload file)">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="file" name="setting_files[hero.slide_{{ $sn }}_url]" class="form-control" accept="image/*">
                                    </div>
                                </div>
                                @if(!empty($settings['hero.slide_'.$sn.'_url']->value))
                                <img src="{{ str_starts_with($settings['hero.slide_'.$sn.'_url']->value,'http') ? $settings['hero.slide_'.$sn.'_url']->value : asset($settings['hero.slide_'.$sn.'_url']->value) }}" class="mt-2 rounded" style="height:60px;width:100px;object-fit:cover">
                                @endif
                            </div>
                            @endforeach
                            <div class="col-12"><hr class="my-1"><p class="fw-semibold text-muted mb-2"><i class="bi bi-card-text me-1"></i>Float Card Animasi</p></div>
                            <div class="col-md-6">
                                <label class="form-label">Float Card 1 — Judul</label>
                                <input type="text" name="settings[hero.float1_title]" class="form-control" value="{{ $settings['hero.float1_title']->value ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Float Card 1 — Subjudul</label>
                                <input type="text" name="settings[hero.float1_subtitle]" class="form-control" value="{{ $settings['hero.float1_subtitle']->value ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Float Card 2 — Judul</label>
                                <input type="text" name="settings[hero.float2_title]" class="form-control" value="{{ $settings['hero.float2_title']->value ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Float Card 2 — Subjudul</label>
                                <input type="text" name="settings[hero.float2_subtitle]" class="form-control" value="{{ $settings['hero.float2_subtitle']->value ?? '' }}">
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Simpan Hero</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ────── TICKER TAB ────── --}}
        <div class="tab-pane fade" id="tab-ticker">
            <div class="card lp-card mb-4">
                <div class="card-header lp-card-header"><i class="bi bi-plus-circle me-2"></i>Tambah Teks Promo</div>
                <div class="card-body">
                    <form action="{{ route('admin.landing.tickers.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Emoji</label>
                                <input type="text" name="emoji" class="form-control" placeholder="🎉" maxlength="10">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Teks <span class="text-danger">*</span></label>
                                <input type="text" name="text" class="form-control" required placeholder="Diskon Spesial! Gratis biaya pendaftaran bulan ini">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="tkAddActive" value="1" checked>
                                    <label class="form-check-label" for="tkAddActive">Aktif</label>
                                </div>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-plus me-1"></i>Tambah</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card lp-card">
                <div class="card-header lp-card-header"><i class="bi bi-list-ul me-2"></i>Daftar Teks Promo ({{ $tickers->count() }})</div>
                <div class="card-body p-0">
                    @forelse($tickers as $tk)
                    <div class="lp-list-item">
                        <div class="lp-emoji-icon">{{ $tk->emoji ?: '📢' }}</div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2">
                                <span>{!! $tk->text !!}</span>
                                @if(!$tk->is_active)<span class="badge bg-secondary">Non-aktif</span>@endif
                            </div>
                        </div>
                        <div class="lp-item-actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="openEditTicker({{ $tk->id }})"><i class="bi bi-pencil"></i></button>
                            <form action="{{ route('admin.landing.tickers.destroy', $tk) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmAction('Hapus teks promo ini?', () => this.closest(\'form\').submit(), null, {title:'Hapus Promo', okText:'Ya, Hapus'})"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted"><i class="bi bi-inbox" style="font-size:2.5rem;opacity:.4"></i><p class="mt-2">Belum ada teks promo.</p></div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ────── TENTANG TAB ────── --}}
        <div class="tab-pane fade" id="tab-tentang">
            <div class="card lp-card mb-4">
                <div class="card-header lp-card-header"><i class="bi bi-building me-2"></i>Teks Bagian "Tentang SCI"</div>
                <div class="card-body">
                    <form action="{{ route('admin.landing.settings.update') }}" method="POST">
                        @csrf @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Judul Baris 1</label>
                                <input type="text" name="settings[tentang.title_line1]" class="form-control" value="{{ $settings['tentang.title_line1']->value ?? 'Tentang' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Judul Aksen</label>
                                <input type="text" name="settings[tentang.title_accent]" class="form-control" value="{{ $settings['tentang.title_accent']->value ?? 'Smart Center Indonesia' }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Deskripsi 1</label>
                                <textarea name="settings[tentang.desc1]" class="form-control" rows="2">{{ $settings['tentang.desc1']->value ?? '' }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Deskripsi 2</label>
                                <textarea name="settings[tentang.desc2]" class="form-control" rows="2">{{ $settings['tentang.desc2']->value ?? '' }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Kutipan</label>
                                <input type="text" name="settings[tentang.quote]" class="form-control" value="{{ $settings['tentang.quote']->value ?? '' }}">
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card lp-card mb-4">
                <div class="card-header lp-card-header"><i class="bi bi-plus-circle me-2"></i>Tambah Fitur Tentang</div>
                <div class="card-body">
                    <form action="{{ route('admin.landing.features.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Ikon (nama class bootstrap-icons)</label>
                                <input type="text" name="icon" class="form-control" placeholder="bi-patch-check-fill">
                                <small class="text-muted">Lihat di <a href="https://icons.getbootstrap.com" target="_blank">icons.getbootstrap.com</a>, salin nama class (mis. patch-check-fill)</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Label <span class="text-danger">*</span></label>
                                <input type="text" name="label" class="form-control" required placeholder="Tutor Bersertifikat">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="ftAddActive" value="1" checked>
                                    <label class="form-check-label" for="ftAddActive">Aktif</label>
                                </div>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-plus me-1"></i>Tambah</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card lp-card">
                <div class="card-header lp-card-header"><i class="bi bi-list-ul me-2"></i>Daftar Fitur ({{ $features->count() }})</div>
                <div class="card-body p-0">
                    @forelse($features as $ft)
                    <div class="lp-list-item">
                        <div class="lp-emoji-icon"><i class="bi {{ $ft->icon }}"></i></div>
                        <div class="flex-grow-1">
                            <strong>{{ $ft->label }}</strong>
                            @if(!$ft->is_active)<span class="badge bg-secondary ms-2">Non-aktif</span>@endif
                        </div>
                        <div class="lp-item-actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="openEditFeature({{ $ft->id }})"><i class="bi bi-pencil"></i></button>
                            <form action="{{ route('admin.landing.features.destroy', $ft) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmAction('Hapus fitur ini?', () => this.closest(\'form\').submit(), null, {title:'Hapus Fitur', okText:'Ya, Hapus'})"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted"><i class="bi bi-inbox" style="font-size:2.5rem;opacity:.4"></i><p class="mt-2">Belum ada fitur.</p></div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="tab-stats">
            <div class="card lp-card">
                <div class="card-header lp-card-header"><i class="bi bi-bar-chart me-2"></i>Statistik Strip</div>
                <div class="card-body">
                    <div class="alert alert-info border-0 mb-3" style="background:rgba(200,77,223,.07)">
                        <i class="bi bi-info-circle me-1"></i>
                        Jumlah <strong>Siswa Aktif</strong> dan <strong>Tutor</strong> diambil otomatis dari database. Di bawah ini yang bisa diubah secara manual.
                    </div>
                    <form action="{{ route('admin.landing.settings.update') }}" method="POST">
                        @csrf @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tahun Pengalaman</label>
                                <input type="text" name="settings[stats.years_exp]" class="form-control" value="{{ $settings['stats.years_exp']->value ?? '14+' }}" placeholder="14+">
                                <small class="text-muted">Contoh: 14+</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">% Kepuasan Pelanggan</label>
                                <input type="text" name="settings[stats.satisfaction]" class="form-control" value="{{ $settings['stats.satisfaction']->value ?? '98%' }}" placeholder="98%">
                                <small class="text-muted">Contoh: 98%</small>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Simpan Statistik</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ────── PROGRAMS TAB ────── --}}
        <div class="tab-pane fade" id="tab-programs">

            {{-- Add Program Form --}}
            <div class="card lp-card mb-4">
                <div class="card-header lp-card-header d-flex align-items-center">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Program Baru
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.landing.programs.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Judul Program <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" required placeholder="Bimbel Mata Pelajaran">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Ikon Emoji</label>
                                <input type="text" name="icon_emoji" class="form-control" placeholder="📖" maxlength="10">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Label Badge</label>
                                <input type="text" name="badge_label" class="form-control" required placeholder="SEMUA JENJANG">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Deskripsi <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control" rows="2" required placeholder="Deskripsi program..."></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Warna Background Badge</label>
                                <input type="text" name="badge_bg" class="form-control" placeholder="rgba(200,77,223,.1)">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Warna Teks Badge</label>
                                <input type="text" name="badge_color" class="form-control" placeholder="#68117e">
                            </div>
                            <div class="col-md-4 d-flex align-items-end gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_popular" id="addPopular" value="1">
                                    <label class="form-check-label" for="addPopular">Populer 🔥</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_new" id="addNew" value="1">
                                    <label class="form-check-label" for="addNew">Baru ✨</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="addActive" value="1" checked>
                                    <label class="form-check-label" for="addActive">Aktif</label>
                                </div>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-plus me-1"></i>Tambah Program</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Programs List --}}
            <div class="card lp-card">
                <div class="card-header lp-card-header"><i class="bi bi-list-ul me-2"></i>Daftar Program ({{ $programs->count() }})</div>
                <div class="card-body p-0">
                    @forelse($programs as $prog)
                    <div class="lp-list-item">
                        <div class="d-flex align-items-start gap-3 flex-grow-1">
                            <div class="lp-emoji-icon">{{ $prog->icon_emoji ?: '📖' }}</div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <strong>{{ $prog->title }}</strong>
                                    <span class="badge" style="background:{{ $prog->badge_bg }};color:{{ $prog->badge_color }};border:1px solid {{ $prog->badge_color }}20">{{ $prog->badge_label }}</span>
                                    @if($prog->is_popular)<span class="badge bg-warning text-dark">Populer</span>@endif
                                    @if($prog->is_new)<span class="badge bg-success">Baru</span>@endif
                                    @if(!$prog->is_active)<span class="badge bg-secondary">Non-aktif</span>@endif
                                </div>
                                <div class="text-muted small">{{ Str::limit($prog->description, 100) }}</div>
                            </div>
                        </div>
                        <div class="lp-item-actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="openEditProgram({{ $prog->id }})"><i class="bi bi-pencil"></i></button>
                            <form action="{{ route('admin.landing.programs.destroy', $prog) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="confirmAction('Hapus program ini?', () => this.closest(\'form\').submit(), null, {title:\'Hapus Program\', okText:\'Ya, Hapus\'})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox" style="font-size:2.5rem;opacity:.4"></i>
                        <p class="mt-2">Belum ada program. Tambahkan di atas.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ────── JENJANG TAB ────── --}}
        <div class="tab-pane fade" id="tab-jenjang">
            <div class="card lp-card mb-4">
                <div class="card-header lp-card-header"><i class="bi bi-plus-circle me-2"></i>Tambah Jenjang Pendidikan</div>
                <div class="card-body">
                    <form action="{{ route('admin.landing.jenjangs.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Kode <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required placeholder="sd">
                                <small class="text-muted">Kode unik, mis. sd, smp, sma</small>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Label <span class="text-danger">*</span></label>
                                <input type="text" name="label" class="form-control" required placeholder="Sekolah Dasar">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Emoji</label>
                                <input type="text" name="emoji" class="form-control" placeholder="🎒" maxlength="10">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="jjAddActive" value="1" checked>
                                    <label class="form-check-label" for="jjAddActive">Aktif</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Gambar</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-plus me-1"></i>Tambah</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card lp-card">
                <div class="card-header lp-card-header"><i class="bi bi-list-ul me-2"></i>Daftar Jenjang ({{ $jenjangs->count() }})</div>
                <div class="card-body p-0">
                    @forelse($jenjangs as $jj)
                    <div class="lp-list-item">
                        @if($jj->image)
                        <img src="{{ str_starts_with($jj->image,'http') ? $jj->image : asset($jj->image) }}" style="width:48px;height:48px;object-fit:cover;border-radius:12px" class="flex-shrink-0">
                        @else
                        <div class="lp-emoji-icon">{{ $jj->emoji ?: '🎒' }}</div>
                        @endif
                        <div class="flex-grow-1">
                            <strong>{{ $jj->label }}</strong> <span class="text-muted small">({{ $jj->name }})</span>
                            @if(!$jj->is_active)<span class="badge bg-secondary ms-2">Non-aktif</span>@endif
                        </div>
                        <div class="lp-item-actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="openEditJenjang({{ $jj->id }})"><i class="bi bi-pencil"></i></button>
                            <form action="{{ route('admin.landing.jenjangs.destroy', $jj) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmAction('Hapus jenjang ini?', () => this.closest(\'form\').submit(), null, {title:'Hapus Jenjang', okText:'Ya, Hapus'})"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted"><i class="bi bi-inbox" style="font-size:2.5rem;opacity:.4"></i><p class="mt-2">Belum ada jenjang.</p></div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ────── CARI GURU TAB ────── --}}
        <div class="tab-pane fade" id="tab-cariguru">
            <div class="card lp-card mb-4">
                <div class="card-header lp-card-header"><i class="bi bi-search me-2"></i>Teks Bagian "Cari Guru Terbaik"</div>
                <div class="card-body">
                    <form action="{{ route('admin.landing.settings.update') }}" method="POST">
                        @csrf @method('PUT')
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Eyebrow</label>
                                <input type="text" name="settings[cariguru.eyebrow]" class="form-control" value="{{ $settings['cariguru.eyebrow']->value ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Judul Baris 1</label>
                                <input type="text" name="settings[cariguru.title_line1]" class="form-control" value="{{ $settings['cariguru.title_line1']->value ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Judul Aksen</label>
                                <input type="text" name="settings[cariguru.title_accent]" class="form-control" value="{{ $settings['cariguru.title_accent']->value ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Judul Baris 2</label>
                                <input type="text" name="settings[cariguru.title_line2]" class="form-control" value="{{ $settings['cariguru.title_line2']->value ?? '' }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Subjudul</label>
                                <textarea name="settings[cariguru.subtitle]" class="form-control" rows="2">{{ $settings['cariguru.subtitle']->value ?? '' }}</textarea>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card lp-card mb-4">
                <div class="card-header lp-card-header"><i class="bi bi-plus-circle me-2"></i>Tambah Badge Kepercayaan</div>
                <div class="card-body">
                    <form action="{{ route('admin.landing.trusts.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Ikon (bootstrap-icons)</label>
                                <input type="text" name="icon" class="form-control" placeholder="bi-shield-check">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Teks <span class="text-danger">*</span></label>
                                <input type="text" name="text" class="form-control" required placeholder="Tutor Terverifikasi">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="trAddActive" value="1" checked>
                                    <label class="form-check-label" for="trAddActive">Aktif</label>
                                </div>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-plus me-1"></i>Tambah</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card lp-card">
                <div class="card-header lp-card-header"><i class="bi bi-list-ul me-2"></i>Daftar Badge ({{ $trusts->count() }})</div>
                <div class="card-body p-0">
                    @forelse($trusts as $tr)
                    <div class="lp-list-item">
                        <div class="lp-emoji-icon"><i class="bi {{ $tr->icon }}"></i></div>
                        <div class="flex-grow-1">
                            <strong>{{ $tr->text }}</strong>
                            @if(!$tr->is_active)<span class="badge bg-secondary ms-2">Non-aktif</span>@endif
                        </div>
                        <div class="lp-item-actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="openEditTrust({{ $tr->id }})"><i class="bi bi-pencil"></i></button>
                            <form action="{{ route('admin.landing.trusts.destroy', $tr) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmAction('Hapus badge ini?', () => this.closest(\'form\').submit(), null, {title:'Hapus Badge', okText:'Ya, Hapus'})"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted"><i class="bi bi-inbox" style="font-size:2.5rem;opacity:.4"></i><p class="mt-2">Belum ada badge.</p></div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ────── KEUNGGULAN TAB ────── --}}
        <div class="tab-pane fade" id="tab-keunggulan">
            <div class="card lp-card mb-4">
                <div class="card-header lp-card-header"><i class="bi bi-shield-fill-check me-2"></i>Teks Bagian "Keunggulan SCI"</div>
                <div class="card-body">
                    <form action="{{ route('admin.landing.settings.update') }}" method="POST">
                        @csrf @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Judul Aksen</label>
                                <input type="text" name="settings[keunggulan.title_accent]" class="form-control" value="{{ $settings['keunggulan.title_accent']->value ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Subjudul</label>
                                <input type="text" name="settings[keunggulan.subtitle]" class="form-control" value="{{ $settings['keunggulan.subtitle']->value ?? '' }}">
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card lp-card mb-4">
                <div class="card-header lp-card-header"><i class="bi bi-plus-circle me-2"></i>Tambah Keunggulan</div>
                <div class="card-body">
                    <form action="{{ route('admin.landing.highlights.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" required placeholder="Kurikulum Terpersonalisasi">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Deskripsi <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control" rows="2" required></textarea>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Gambar</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="hlAddActive" value="1" checked>
                                    <label class="form-check-label" for="hlAddActive">Aktif</label>
                                </div>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-plus me-1"></i>Tambah</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card lp-card">
                <div class="card-header lp-card-header"><i class="bi bi-list-ul me-2"></i>Daftar Keunggulan ({{ $highlights->count() }})</div>
                <div class="card-body p-0">
                    @forelse($highlights as $hl)
                    <div class="lp-list-item">
                        @if($hl->image)
                        <img src="{{ str_starts_with($hl->image,'http') ? $hl->image : asset($hl->image) }}" style="width:48px;height:48px;object-fit:cover;border-radius:12px" class="flex-shrink-0">
                        @else
                        <div class="lp-emoji-icon"><i class="bi bi-star-fill"></i></div>
                        @endif
                        <div class="flex-grow-1">
                            <strong>{{ $hl->title }}</strong>
                            @if(!$hl->is_active)<span class="badge bg-secondary ms-2">Non-aktif</span>@endif
                            <div class="text-muted small">{{ Str::limit($hl->description, 100) }}</div>
                        </div>
                        <div class="lp-item-actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="openEditHighlight({{ $hl->id }})"><i class="bi bi-pencil"></i></button>
                            <form action="{{ route('admin.landing.highlights.destroy', $hl) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmAction('Hapus keunggulan ini?', () => this.closest(\'form\').submit(), null, {title:'Hapus Keunggulan', okText:'Ya, Hapus'})"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted"><i class="bi bi-inbox" style="font-size:2.5rem;opacity:.4"></i><p class="mt-2">Belum ada keunggulan.</p></div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="tab-testimonials">

            {{-- Add Testimonial Form --}}
            <div class="card lp-card mb-4">
                <div class="card-header lp-card-header"><i class="bi bi-plus-circle me-2"></i>Tambah Testimoni Baru</div>
                <div class="card-body">
                    <form action="{{ route('admin.landing.testimonials.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required placeholder="Rini Kusumawati">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Peran / Keterangan <span class="text-danger">*</span></label>
                                <input type="text" name="role" class="form-control" required placeholder="Siswa SMA · Surabaya">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Teks Testimoni <span class="text-danger">*</span></label>
                                <textarea name="text" class="form-control" rows="3" required placeholder='"Testimoni dari siswa..."'></textarea>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Foto (opsional, jika kosong pakai inisial nama)</label>
                                <input type="file" name="photo" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Warna Avatar (CSS gradient)</label>
                                <input type="text" name="gradient" class="form-control" placeholder="linear-gradient(135deg,#c84ddf,#68117e)">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="testiActive" value="1" checked>
                                    <label class="form-check-label" for="testiActive">Tampilkan</label>
                                </div>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-plus me-1"></i>Tambah Testimoni</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Testimonials List --}}
            <div class="card lp-card">
                <div class="card-header lp-card-header"><i class="bi bi-chat-quote me-2"></i>Daftar Testimoni ({{ $testimonials->count() }})</div>
                <div class="card-body p-0">
                    @forelse($testimonials as $testi)
                    <div class="lp-list-item">
                        <div class="d-flex align-items-start gap-3 flex-grow-1">
                            @if($testi->photo)
                            <img src="{{ str_starts_with($testi->photo,'http') ? $testi->photo : asset($testi->photo) }}" class="testi-mini-avatar" style="object-fit:cover">
                            @else
                            <div class="testi-mini-avatar" style="background:{{ $testi->gradient }}">{{ $testi->initial }}</div>
                            @endif
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <strong>{{ $testi->name }}</strong>
                                    <span class="text-muted small">· {{ $testi->role }}</span>
                                    @if(!$testi->is_active)<span class="badge bg-secondary">Non-aktif</span>@endif
                                </div>
                                <div class="text-muted small fst-italic">{{ Str::limit($testi->text, 120) }}</div>
                            </div>
                        </div>
                        <div class="lp-item-actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="openEditTesti({{ $testi->id }})"><i class="bi bi-pencil"></i></button>
                            <form action="{{ route('admin.landing.testimonials.destroy', $testi) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="confirmAction('Hapus testimoni ini?', () => this.closest(\'form\').submit(), null, {title:\'Hapus Testimoni\', okText:\'Ya, Hapus\'})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-chat-slash" style="font-size:2.5rem;opacity:.4"></i>
                        <p class="mt-2">Belum ada testimoni. Tambahkan di atas.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ────── WHATSAPP TAB ────── --}}
        <div class="tab-pane fade" id="tab-wa">

            {{-- Info box --}}
            <div class="alert border-0 mb-4 d-flex align-items-start gap-3" style="background:rgba(37,211,102,.08);border-left:4px solid #25d366 !important;border-radius:12px">
                <i class="bi bi-whatsapp mt-1" style="color:#25d366;font-size:1.4rem;flex-shrink:0"></i>
                <div>
                    <div class="fw-bold mb-1" style="color:#1a7a3b">Nomor WhatsApp Landing Page</div>
                    <div class="small text-muted">Kelola semua nomor WA yang tampil di landing page. Nomor <strong>Utama</strong> digunakan untuk tombol floating WA dan footer. Tambahkan nomor per cabang untuk bagian Cabang.</div>
                </div>
            </div>

            {{-- Add WA Form --}}
            <div class="card lp-card mb-4">
                <div class="card-header lp-card-header d-flex align-items-center" style="background:linear-gradient(135deg,#075e54,#25d366)">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Nomor WhatsApp
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.landing.wa.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Label / Nama <span class="text-danger">*</span></label>
                                <input type="text" name="label" class="form-control" required placeholder="WhatsApp Pusat" maxlength="100">
                                <small class="text-muted">Contoh: WA Pusat, WA Cabang Surabaya</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Nomor WA <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background:#25d366;color:white;border-color:#25d366"><i class="bi bi-whatsapp"></i></span>
                                    <input type="text" name="number" class="form-control" required placeholder="628001234567" maxlength="30" pattern="[0-9]+" title="Hanya angka, tanpa + atau spasi">
                                </div>
                                <small class="text-muted">Hanya angka, tanpa + atau spasi. Contoh: 6281234567890</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Keterangan</label>
                                <input type="text" name="description" class="form-control" placeholder="Nomor utama kantor pusat" maxlength="255">
                            </div>
                            <div class="col-12 d-flex align-items-center gap-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_primary" id="waAddPrimary" value="1">
                                    <label class="form-check-label fw-semibold" for="waAddPrimary">
                                        <i class="bi bi-star-fill text-warning me-1"></i>Jadikan Utama
                                        <small class="text-muted fw-normal">(dipakai tombol float & footer)</small>
                                    </label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="waAddActive" value="1" checked>
                                    <label class="form-check-label" for="waAddActive">Aktif</label>
                                </div>
                                <button type="submit" class="btn ms-auto px-4" style="background:#25d366;color:white;font-weight:700">
                                    <i class="bi bi-plus me-1"></i>Tambah
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- WA Numbers List --}}
            <div class="card lp-card">
                <div class="card-header lp-card-header" style="background:linear-gradient(135deg,#075e54,#25d366)">
                    <i class="bi bi-list-ul me-2"></i>Daftar Nomor WhatsApp ({{ $waNumbers->count() }})
                </div>
                <div class="card-body p-0">
                    @forelse($waNumbers as $wa)
                    <div class="lp-list-item">
                        <div class="wa-number-icon {{ $wa->is_primary ? 'primary' : '' }}">
                            <i class="bi bi-whatsapp"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                <strong>{{ $wa->label }}</strong>
                                @if($wa->is_primary)
                                    <span class="badge" style="background:#fbbf24;color:#1a1a1a"><i class="bi bi-star-fill me-1" style="font-size:.6rem"></i>Utama</span>
                                @endif
                                @if(!$wa->is_active)
                                    <span class="badge bg-secondary">Non-aktif</span>
                                @endif
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <code class="wa-number-code">+{{ $wa->number }}</code>
                                <a href="https://wa.me/{{ $wa->number }}" target="_blank" class="btn btn-xs text-success p-0" style="font-size:.78rem">
                                    <i class="bi bi-box-arrow-up-right me-1"></i>Buka WA
                                </a>
                            </div>
                            @if($wa->description)
                                <div class="text-muted small mt-1">{{ $wa->description }}</div>
                            @endif
                        </div>
                        <div class="lp-item-actions">
                            <button class="btn btn-sm btn-outline-success" onclick="openEditWa({{ $wa->id }})">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('admin.landing.wa.destroy', $wa) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="confirmAction('Hapus nomor {{ addslashes($wa->label) }}?', () => this.closest(\'form\').submit(), null, {title:\'Hapus Nomor WA\', okText:\'Ya, Hapus\'})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-whatsapp" style="font-size:2.5rem;opacity:.3"></i>
                        <p class="mt-2">Belum ada nomor WA. Tambahkan di atas.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ────── CTA TAB ────── --}}
        <div class="tab-pane fade" id="tab-cta">
            <div class="card lp-card">
                <div class="card-header lp-card-header"><i class="bi bi-megaphone me-2"></i>Konten CTA (Call to Action)</div>
                <div class="card-body">
                    <form action="{{ route('admin.landing.settings.update') }}" method="POST">
                        @csrf @method('PUT')
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Teks Eyebrow (kecil di atas judul)</label>
                                <input type="text" name="settings[cta.eyebrow]" class="form-control" value="{{ $settings['cta.eyebrow']->value ?? 'Mulai Sekarang' }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Judul CTA</label>
                                <input type="text" name="settings[cta.title]" class="form-control" value="{{ $settings['cta.title']->value ?? 'Wujudkan Mimpi Bersama SCI!' }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Deskripsi CTA</label>
                                <textarea name="settings[cta.description]" class="form-control" rows="3">{{ $settings['cta.description']->value ?? '' }}</textarea>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Simpan CTA</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ────── FOOTER TAB ────── --}}
        <div class="tab-pane fade" id="tab-footer">
            <div class="card lp-card">
                <div class="card-header lp-card-header"><i class="bi bi-layout-sidebar-inset-reverse me-2"></i>Konten Footer</div>
                <div class="card-body">
                    <form action="{{ route('admin.landing.settings.update') }}" method="POST">
                        @csrf @method('PUT')
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Deskripsi Brand Footer</label>
                                <textarea name="settings[footer.brand_desc]" class="form-control" rows="3">{{ $settings['footer.brand_desc']->value ?? '' }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nomor WhatsApp Utama</label>
                                <div class="alert py-2 px-3 mb-0 d-flex align-items-center gap-2" style="background:rgba(37,211,102,.07);border:1px solid rgba(37,211,102,.2);border-radius:10px;font-size:12.5px">
                                    <i class="bi bi-info-circle" style="color:#25d366;flex-shrink:0"></i>
                                    <span class="text-muted">Kelola nomor WA di tab <strong class="text-success" style="cursor:pointer" onclick="document.querySelector('[data-bs-target=\'#tab-wa\']').click()">WhatsApp <i class="bi bi-arrow-right" style="font-size:10px"></i></strong></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">URL Instagram</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-instagram" style="color:#e1306c"></i></span>
                                    <input type="text" name="settings[footer.instagram]" class="form-control" value="{{ $settings['footer.instagram']->value ?? '#' }}" placeholder="https://instagram.com/...">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">URL Facebook</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-facebook" style="color:#1877f2"></i></span>
                                    <input type="text" name="settings[footer.facebook]" class="form-control" value="{{ $settings['footer.facebook']->value ?? '#' }}" placeholder="https://facebook.com/...">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">URL YouTube</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-youtube" style="color:#ff0000"></i></span>
                                    <input type="text" name="settings[footer.youtube]" class="form-control" value="{{ $settings['footer.youtube']->value ?? '#' }}" placeholder="https://youtube.com/...">
                                </div>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Simpan Footer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ────── GALERI TAB ────── --}}
        <div class="tab-pane fade" id="tab-galeri">
            <div class="card lp-card mb-4">
                <div class="card-header lp-card-header"><i class="bi bi-card-text me-2"></i>Teks Bagian "Galeri Kegiatan"</div>
                <div class="card-body">
                    <form action="{{ route('admin.landing.settings.update') }}" method="POST">
                        @csrf @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Judul Baris 1</label>
                                <input type="text" name="settings[galeri.title_line1]" class="form-control" value="{{ $settings['galeri.title_line1']->value ?? 'Galeri' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Judul Aksen <small class="text-primary">(tampil gradien)</small></label>
                                <input type="text" name="settings[galeri.title_accent]" class="form-control" value="{{ $settings['galeri.title_accent']->value ?? 'Kegiatan' }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Subjudul</label>
                                <textarea name="settings[galeri.subtitle]" class="form-control" rows="2">{{ $settings['galeri.subtitle']->value ?? 'Momen belajar menyenangkan bersama siswa dan tutor terbaik SCI di seluruh Indonesia.' }}</textarea>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Simpan Heading</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card lp-card mb-4">
                <div class="card-header lp-card-header"><i class="bi bi-plus-circle me-2"></i>Upload Foto Galeri</div>
                <div class="card-body">
                    <form action="{{ route('admin.landing.galleries.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Foto <span class="text-danger">*</span></label>
                                <input type="file" name="image" class="form-control" accept="image/*" required>
                                <small class="text-muted"><i class="bi bi-laptop me-1"></i>Upload langsung dari laptop/komputer. Format: JPG, PNG, WebP. Maks 4MB.</small>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Keterangan / Alt Teks (opsional)</label>
                                <input type="text" name="alt" class="form-control" placeholder="Sesi belajar matematika kelas SMA">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="galAddActive" value="1" checked>
                                    <label class="form-check-label" for="galAddActive">Tampilkan di landing</label>
                                </div>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-upload me-1"></i>Upload & Tambah</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card lp-card">
                <div class="card-header lp-card-header"><i class="bi bi-images me-2"></i>Foto Galeri ({{ $galleries->count() }})</div>
                <div class="card-body {{ $galleries->isEmpty() ? '' : 'p-0' }}">
                    @forelse($galleries as $gal)
                    <div class="lp-list-item">
                        @if($gal->image)
                        <img src="{{ str_starts_with($gal->image,'http') ? $gal->image : asset($gal->image) }}" style="width:90px;height:65px;object-fit:cover;border-radius:10px;flex-shrink:0">
                        @else
                        <div class="lp-emoji-icon"><i class="bi bi-image"></i></div>
                        @endif
                        <div class="flex-grow-1">
                            <div class="text-muted small">{{ $gal->alt ?: '(tanpa keterangan)' }}</div>
                            <div class="d-flex gap-2 mt-1 flex-wrap">
                                @if(!$gal->is_active)<span class="badge bg-secondary">Non-aktif</span>@endif
                                <span class="badge" style="background:rgba(200,77,223,.1);color:#68117e">Urutan: {{ $gal->sort_order }}</span>
                            </div>
                        </div>
                        <div class="lp-item-actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="openEditGallery({{ $gal->id }})"><i class="bi bi-pencil"></i></button>
                            <form action="{{ route('admin.landing.galleries.destroy', $gal) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmAction('Hapus foto galeri ini?', () => this.closest(\'form\').submit(), null, {title:'Hapus Foto', okText:'Ya, Hapus'})"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted"><i class="bi bi-images" style="font-size:2.5rem;opacity:.4"></i><p class="mt-2">Belum ada foto galeri. Upload di atas.</p></div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ────── FAQ & KONTAK TAB ────── --}}
        <div class="tab-pane fade" id="tab-faq">
            <div class="card lp-card mb-4">
                <div class="card-header lp-card-header"><i class="bi bi-card-text me-2"></i>Teks Bagian "Pertanyaan &amp; Hubungi Kami"</div>
                <div class="card-body">
                    <form action="{{ route('admin.landing.settings.update') }}" method="POST">
                        @csrf @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Eyebrow <small class="text-muted">(teks kecil atas)</small></label>
                                <input type="text" name="settings[bantuan.eyebrow]" class="form-control" value="{{ $settings['bantuan.eyebrow']->value ?? 'Bantuan & Kontak' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Judul Baris 1</label>
                                <input type="text" name="settings[bantuan.title_line1]" class="form-control" value="{{ $settings['bantuan.title_line1']->value ?? 'Pertanyaan &' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Judul Aksen <small class="text-primary">(tampil gradien)</small></label>
                                <input type="text" name="settings[bantuan.title_accent]" class="form-control" value="{{ $settings['bantuan.title_accent']->value ?? 'Hubungi Kami' }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Subjudul</label>
                                <textarea name="settings[bantuan.subtitle]" class="form-control" rows="2">{{ $settings['bantuan.subtitle']->value ?? 'Punya pertanyaan atau ingin bergabung? Kami siap membantu Anda kapan saja.' }}</textarea>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Simpan Heading</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card lp-card mb-4">
                <div class="card-header lp-card-header"><i class="bi bi-plus-circle me-2"></i>Tambah Pertanyaan (FAQ)</div>
                <div class="card-body">
                    <form action="{{ route('admin.landing.faqs.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Pertanyaan <span class="text-danger">*</span></label>
                                <input type="text" name="question" class="form-control" required placeholder="Berapa biaya pendaftaran di SCI?">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Jawaban <span class="text-danger">*</span></label>
                                <textarea name="answer" class="form-control" rows="3" required placeholder="Isi jawaban lengkap di sini..."></textarea>
                            </div>
                            <div class="col-12 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="faqAddActive" value="1" checked>
                                    <label class="form-check-label" for="faqAddActive">Tampilkan di landing</label>
                                </div>
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-plus me-1"></i>Tambah FAQ</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card lp-card">
                <div class="card-header lp-card-header"><i class="bi bi-question-circle me-2"></i>Daftar FAQ ({{ $faqs->count() }})</div>
                <div class="card-body p-0">
                    @forelse($faqs as $faq)
                    <div class="lp-list-item" style="align-items:flex-start">
                        <div class="lp-emoji-icon" style="margin-top:4px"><i class="bi bi-question-circle-fill" style="color:#c84ddf;font-size:1.3rem"></i></div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <strong>{{ $faq->question }}</strong>
                                @if(!$faq->is_active)<span class="badge bg-secondary">Non-aktif</span>@endif
                            </div>
                            <div class="text-muted small">{{ Str::limit($faq->answer, 120) }}</div>
                        </div>
                        <div class="lp-item-actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="openEditFaq({{ $faq->id }})"><i class="bi bi-pencil"></i></button>
                            <form action="{{ route('admin.landing.faqs.destroy', $faq) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmAction('Hapus FAQ ini?', () => this.closest(\'form\').submit(), null, {title:'Hapus FAQ', okText:'Ya, Hapus'})"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted"><i class="bi bi-inbox" style="font-size:2.5rem;opacity:.4"></i><p class="mt-2">Belum ada FAQ. Tambahkan di atas.</p></div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ────── TUTOR TAB ────── --}}
        <div class="tab-pane fade" id="tab-tutor">
            <div class="alert border-0 mb-4 d-flex align-items-start gap-3" style="background:rgba(200,77,223,.07);border-left:4px solid #c84ddf !important;border-radius:12px">
                <i class="bi bi-info-circle mt-1" style="color:#c84ddf;font-size:1.3rem;flex-shrink:0"></i>
                <div>
                    <div class="fw-bold mb-1">Tentang Bagian "Tutor Terbaik Kami"</div>
                    <div class="small text-muted">Data tutor (nama, foto, mata pelajaran) diambil otomatis dari <strong>Guru Aktif</strong> di sistem. Kelola data guru di menu <a href="{{ route('admin.teachers.index') }}" class="text-primary fw-semibold">Data Guru <i class="bi bi-arrow-up-right" style="font-size:.7rem"></i></a>. Di sini Anda bisa mengubah teks heading seksi ini saja.</div>
                </div>
            </div>

            <div class="card lp-card">
                <div class="card-header lp-card-header"><i class="bi bi-person-badge me-2"></i>Teks Bagian "Tutor Terbaik Kami"</div>
                <div class="card-body">
                    <form action="{{ route('admin.landing.settings.update') }}" method="POST">
                        @csrf @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Eyebrow <small class="text-muted">(teks kecil atas)</small></label>
                                <input type="text" name="settings[tutor.eyebrow]" class="form-control" value="{{ $settings['tutor.eyebrow']->value ?? 'Tim Pengajar' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Judul Baris 1</label>
                                <input type="text" name="settings[tutor.title_line1]" class="form-control" value="{{ $settings['tutor.title_line1']->value ?? 'Tutor' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Judul Aksen <small class="text-primary">(gradien)</small></label>
                                <input type="text" name="settings[tutor.title_accent]" class="form-control" value="{{ $settings['tutor.title_accent']->value ?? 'Terbaik' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Judul Baris 2</label>
                                <input type="text" name="settings[tutor.title_line2]" class="form-control" value="{{ $settings['tutor.title_line2']->value ?? 'Kami' }}">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Subjudul</label>
                                <textarea name="settings[tutor.subtitle]" class="form-control" rows="2">{{ $settings['tutor.subtitle']->value ?? 'Dilatih secara profesional dan berpengalaman di bidangnya masing-masing untuk memberikan hasil terbaik bagi setiap siswa.' }}</textarea>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Edit Program Modal --}}
<div class="modal fade" id="editProgramModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit Program</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form id="editProgramForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label fw-semibold">Judul *</label><input type="text" name="title" class="form-control" required></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">Ikon Emoji</label><input type="text" name="icon_emoji" class="form-control" maxlength="10"></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">Label Badge *</label><input type="text" name="badge_label" class="form-control" required></div>
                        <div class="col-12"><label class="form-label fw-semibold">Deskripsi *</label><textarea name="description" class="form-control" rows="3" required></textarea></div>
                        <div class="col-md-4"><label class="form-label">BG Badge</label><input type="text" name="badge_bg" class="form-control"></div>
                        <div class="col-md-4"><label class="form-label">Warna Teks Badge</label><input type="text" name="badge_color" class="form-control"></div>
                        <div class="col-md-4 d-flex align-items-end gap-3">
                            <div class="form-check"><input type="hidden" name="is_popular" value="0"><input class="form-check-input" type="checkbox" name="is_popular" id="epPopular" value="1"><label class="form-check-label" for="epPopular">Populer</label></div>
                            <div class="form-check"><input type="hidden" name="is_new" value="0"><input class="form-check-input" type="checkbox" name="is_new" id="epNew" value="1"><label class="form-check-label" for="epNew">Baru</label></div>
                            <div class="form-check"><input type="hidden" name="is_active" value="0"><input class="form-check-input" type="checkbox" name="is_active" id="epActive" value="1"><label class="form-check-label" for="epActive">Aktif</label></div>
                        </div>
                        <div class="col-md-4"><label class="form-label">Urutan</label><input type="number" name="sort_order" class="form-control" min="0"></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button></div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Testimonial Modal --}}
<div class="modal fade" id="editTestiModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit Testimoni</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form id="editTestiForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label fw-semibold">Nama *</label><input type="text" name="name" class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Peran *</label><input type="text" name="role" class="form-control" required></div>
                        <div class="col-12"><label class="form-label fw-semibold">Teks Testimoni *</label><textarea name="text" class="form-control" rows="4" required></textarea></div>
                        <div class="col-md-8"><label class="form-label">Gradient Avatar</label><input type="text" name="gradient" class="form-control"></div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check"><input type="hidden" name="is_active" value="0"><input class="form-check-input" type="checkbox" name="is_active" id="etActive" value="1"><label class="form-check-label" for="etActive">Tampilkan</label></div>
                        </div>
                        <div class="col-md-4"><label class="form-label">Urutan</label><input type="number" name="sort_order" class="form-control" min="0"></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button></div>
            </form>
        </div>
    </div>
</div>

{{-- ───── Edit WA Modal ───── --}}
<div class="modal fade" id="editWaModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 12px 48px rgba(7,94,84,.15)">
            <div class="modal-header text-white border-0" style="background:linear-gradient(135deg,#075e54,#25d366);border-radius:16px 16px 0 0">
                <h6 class="modal-title fw-bold"><i class="bi bi-whatsapp me-2"></i>Edit Nomor WhatsApp</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editWaForm" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Label / Nama <span class="text-danger">*</span></label>
                            <input type="text" name="label" class="form-control" required maxlength="100">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Nomor WA <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" style="background:#25d366;color:white;border-color:#25d366"><i class="bi bi-whatsapp"></i></span>
                                <input type="text" name="number" class="form-control" required maxlength="30" pattern="[0-9]+" title="Hanya angka">
                            </div>
                            <small class="text-muted">Hanya angka, tanpa + atau spasi. Contoh: 6281234567890</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <input type="text" name="description" class="form-control" maxlength="255">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Urutan</label>
                            <input type="number" name="sort_order" class="form-control" min="0">
                        </div>
                        <div class="col-6 d-flex flex-column justify-content-end gap-2">
                            <div class="form-check form-switch">
                                <input type="hidden" name="is_primary" value="0"><input class="form-check-input" type="checkbox" name="is_primary" id="ewPrimary" value="1">
                                <label class="form-check-label fw-semibold" for="ewPrimary"><i class="bi bi-star-fill text-warning me-1"></i>Utama</label>
                            </div>
                            <div class="form-check form-switch">
                                <input type="hidden" name="is_active" value="0"><input class="form-check-input" type="checkbox" name="is_active" id="ewActive" value="1">
                                <label class="form-check-label" for="ewActive">Aktif</label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="button" class="btn btn-outline-secondary flex-fill" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn flex-fill text-white fw-bold" style="background:#25d366">
                            <i class="bi bi-check-lg me-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Edit Ticker Modal --}}
<div class="modal fade" id="editTickerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit Teks Promo</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form id="editTickerForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-3"><label class="form-label fw-semibold">Emoji</label><input type="text" name="emoji" class="form-control" maxlength="10"></div>
                        <div class="col-md-9"><label class="form-label fw-semibold">Teks *</label><input type="text" name="text" class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Urutan</label><input type="number" name="sort_order" class="form-control" min="0"></div>
                        <div class="col-md-6 d-flex align-items-end"><div class="form-check"><input type="hidden" name="is_active" value="0"><input class="form-check-input" type="checkbox" name="is_active" id="etTickerActive" value="1"><label class="form-check-label" for="etTickerActive">Aktif</label></div></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button></div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Feature Modal --}}
<div class="modal fade" id="editFeatureModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit Fitur Tentang</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form id="editFeatureForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4"><label class="form-label fw-semibold">Ikon (bootstrap-icons)</label><input type="text" name="icon" class="form-control" placeholder="bi-patch-check-fill"></div>
                        <div class="col-md-8"><label class="form-label fw-semibold">Label *</label><input type="text" name="label" class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Urutan</label><input type="number" name="sort_order" class="form-control" min="0"></div>
                        <div class="col-md-6 d-flex align-items-end"><div class="form-check"><input type="hidden" name="is_active" value="0"><input class="form-check-input" type="checkbox" name="is_active" id="etFtActive" value="1"><label class="form-check-label" for="etFtActive">Aktif</label></div></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button></div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Jenjang Modal --}}
<div class="modal fade" id="editJenjangModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit Jenjang Pendidikan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form id="editJenjangForm" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-3"><label class="form-label fw-semibold">Kode *</label><input type="text" name="name" class="form-control" required placeholder="sd"></div>
                        <div class="col-md-5"><label class="form-label fw-semibold">Label *</label><input type="text" name="label" class="form-control" required placeholder="Sekolah Dasar"></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Emoji</label><input type="text" name="emoji" class="form-control" maxlength="10"></div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Ganti Gambar (opsional)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted"><i class="bi bi-laptop me-1"></i>Upload dari laptop. Kosongkan jika tidak ingin mengubah gambar.</small>
                            <div id="jenjangCurrentImg" class="mt-2"></div>
                        </div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Urutan</label><input type="number" name="sort_order" class="form-control" min="0"></div>
                        <div class="col-md-6 d-flex align-items-end"><div class="form-check"><input type="hidden" name="is_active" value="0"><input class="form-check-input" type="checkbox" name="is_active" id="etJjActive" value="1"><label class="form-check-label" for="etJjActive">Aktif</label></div></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button></div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Trust Modal --}}
<div class="modal fade" id="editTrustModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit Badge Kepercayaan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form id="editTrustForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4"><label class="form-label fw-semibold">Ikon (bootstrap-icons)</label><input type="text" name="icon" class="form-control" placeholder="bi-shield-check"></div>
                        <div class="col-md-8"><label class="form-label fw-semibold">Teks *</label><input type="text" name="text" class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Urutan</label><input type="number" name="sort_order" class="form-control" min="0"></div>
                        <div class="col-md-6 d-flex align-items-end"><div class="form-check"><input type="hidden" name="is_active" value="0"><input class="form-check-input" type="checkbox" name="is_active" id="etTrActive" value="1"><label class="form-check-label" for="etTrActive">Aktif</label></div></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button></div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Highlight Modal --}}
<div class="modal fade" id="editHighlightModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit Keunggulan SCI</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form id="editHighlightForm" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12"><label class="form-label fw-semibold">Judul *</label><input type="text" name="title" class="form-control" required></div>
                        <div class="col-12"><label class="form-label fw-semibold">Deskripsi *</label><textarea name="description" class="form-control" rows="3" required></textarea></div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Ganti Gambar (opsional)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted"><i class="bi bi-laptop me-1"></i>Upload dari laptop. Kosongkan jika tidak ingin mengubah gambar.</small>
                            <div id="highlightCurrentImg" class="mt-2"></div>
                        </div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Urutan</label><input type="number" name="sort_order" class="form-control" min="0"></div>
                        <div class="col-md-6 d-flex align-items-end"><div class="form-check"><input type="hidden" name="is_active" value="0"><input class="form-check-input" type="checkbox" name="is_active" id="etHlActive" value="1"><label class="form-check-label" for="etHlActive">Aktif</label></div></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button></div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Gallery Modal --}}
<div class="modal fade" id="editGalleryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit Foto Galeri</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form id="editGalleryForm" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div id="galleryCurrentImg" class="col-12"></div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Ganti Foto (opsional)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted"><i class="bi bi-laptop me-1"></i>Upload dari laptop/komputer. Kosongkan jika tidak ingin mengganti foto.</small>
                        </div>
                        <div class="col-12"><label class="form-label fw-semibold">Keterangan / Alt Teks</label><input type="text" name="alt" class="form-control" placeholder="Keterangan foto (opsional)"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Urutan</label><input type="number" name="sort_order" class="form-control" min="0"></div>
                        <div class="col-md-6 d-flex align-items-end"><div class="form-check"><input type="hidden" name="is_active" value="0"><input class="form-check-input" type="checkbox" name="is_active" id="etGalActive" value="1"><label class="form-check-label" for="etGalActive">Tampilkan</label></div></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button></div>
            </form>
        </div>
    </div>
</div>

{{-- Edit FAQ Modal --}}
<div class="modal fade" id="editFaqModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit FAQ</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form id="editFaqForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12"><label class="form-label fw-semibold">Pertanyaan *</label><input type="text" name="question" class="form-control" required></div>
                        <div class="col-12"><label class="form-label fw-semibold">Jawaban *</label><textarea name="answer" class="form-control" rows="4" required></textarea></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Urutan</label><input type="number" name="sort_order" class="form-control" min="0"></div>
                        <div class="col-md-6 d-flex align-items-end"><div class="form-check"><input type="hidden" name="is_active" value="0"><input class="form-check-input" type="checkbox" name="is_active" id="etFaqActive" value="1"><label class="form-check-label" for="etFaqActive">Tampilkan</label></div></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button></div>
            </form>
        </div>
    </div>
</div>

@php
$programsJson      = $programs->keyBy('id')->toJson();
$testimonialsJson  = $testimonials->keyBy('id')->toJson();
$waNumbersJson     = $waNumbers->keyBy('id')->toJson();
@endphp

<style>
.lp-tabs .nav-link { color: var(--text-muted,#6b7280); border-radius: 10px 10px 0 0; font-weight: 600; font-size: .875rem; padding: .6rem 1.1rem; }
.lp-tabs .nav-link.active { color: var(--bs-primary,#c84ddf); background: var(--card-bg,white); border-color: var(--card-border,#dee2e6) var(--card-border,#dee2e6) var(--card-bg,white); }
.lp-card { border: 1px solid rgba(200,77,223,.15); border-radius: 16px; overflow: hidden; box-shadow: 0 2px 16px rgba(38,6,50,.06); }
.lp-card-header { background: linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%); color: white; font-weight: 700; font-size: .925rem; padding: .9rem 1.25rem; }
.page-header-card { background: var(--card-bg,white); border-radius: 16px; padding: 1.25rem 1.5rem; display: flex; align-items: center; box-shadow: 0 2px 16px rgba(38,6,50,.06); border: 1px solid var(--card-border,rgba(200,77,223,.1)); }
.lp-list-item { display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; border-bottom: 1px solid rgba(200,77,223,.08); transition: background .2s; }
.lp-list-item:last-child { border-bottom: none; }
.lp-list-item:hover { background: rgba(200,77,223,.04); }
.lp-item-actions { display: flex; gap: .4rem; flex-shrink: 0; }
.lp-emoji-icon { font-size: 1.8rem; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; background: rgba(200,77,223,.07); border-radius: 12px; flex-shrink: 0; }
.testi-mini-avatar { width: 42px; height: 42px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 800; color: white; flex-shrink: 0; }
.wa-number-icon { width: 44px; height: 44px; border-radius: 12px; background: rgba(37,211,102,.12); color: #25d366; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0; }
.wa-number-icon.primary { background: linear-gradient(135deg,#075e54,#25d366); color: white; }
.wa-number-code { background: rgba(37,211,102,.08); color: #075e54; padding: .15rem .5rem; border-radius: 6px; font-size: .88rem; border: 1px solid rgba(37,211,102,.2); }
</style>

@push('scripts')
<script>
const programsData     = @json($programs->keyBy('id'));
const testimonialsData = @json($testimonials->keyBy('id'));
const waNumbersData    = @json($waNumbers->keyBy('id'));
const tickersData      = @json($tickers->keyBy('id'));
const featuresData     = @json($features->keyBy('id'));
const jenjangsData     = @json($jenjangs->keyBy('id'));
const trustsData       = @json($trusts->keyBy('id'));
const highlightsData   = @json($highlights->keyBy('id'));
const galleriesData    = @json($galleries->keyBy('id'));
const faqsData         = @json($faqs->keyBy('id'));

function openEditProgram(id) {
    const p  = programsData[id];
    const fm = document.getElementById('editProgramForm');
    fm.action = '/admin/landing/programs/' + id;
    fm.querySelector('[name=title]').value       = p.title;
    fm.querySelector('[name=icon_emoji]').value  = p.icon_emoji || '';
    fm.querySelector('[name=badge_label]').value = p.badge_label;
    fm.querySelector('[name=description]').value = p.description;
    fm.querySelector('[name=badge_bg]').value    = p.badge_bg || '';
    fm.querySelector('[name=badge_color]').value = p.badge_color || '';
    fm.querySelector('[name=sort_order]').value  = p.sort_order;
    document.getElementById('epPopular').checked = !!p.is_popular;
    document.getElementById('epNew').checked     = !!p.is_new;
    document.getElementById('epActive').checked  = !!p.is_active;
    new bootstrap.Modal(document.getElementById('editProgramModal')).show();
}

function openEditWa(id) {
    const w  = waNumbersData[id];
    const fm = document.getElementById('editWaForm');
    fm.action = '/admin/landing/wa/' + id;
    fm.querySelector('[name=label]').value       = w.label;
    fm.querySelector('[name=number]').value      = w.number;
    fm.querySelector('[name=description]').value = w.description || '';
    fm.querySelector('[name=sort_order]').value  = w.sort_order;
    document.getElementById('ewPrimary').checked = !!w.is_primary;
    document.getElementById('ewActive').checked  = !!w.is_active;
    new bootstrap.Modal(document.getElementById('editWaModal')).show();
}

function openEditTesti(id) {
    const t  = testimonialsData[id];
    const fm = document.getElementById('editTestiForm');
    fm.action = '/admin/landing/testimonials/' + id;
    fm.querySelector('[name=name]').value       = t.name;
    fm.querySelector('[name=role]').value       = t.role;
    fm.querySelector('[name=text]').value       = t.text;
    fm.querySelector('[name=gradient]').value   = t.gradient || '';
    fm.querySelector('[name=sort_order]').value = t.sort_order;
    document.getElementById('etActive').checked = !!t.is_active;
    new bootstrap.Modal(document.getElementById('editTestiModal')).show();
}

function openEditTicker(id) {
    const t  = tickersData[id];
    const fm = document.getElementById('editTickerForm');
    fm.action = '/admin/landing/tickers/' + id;
    fm.querySelector('[name=emoji]').value      = t.emoji || '';
    fm.querySelector('[name=text]').value       = t.text;
    fm.querySelector('[name=sort_order]').value = t.sort_order;
    document.getElementById('etTickerActive').checked = !!t.is_active;
    new bootstrap.Modal(document.getElementById('editTickerModal')).show();
}

function openEditFeature(id) {
    const f  = featuresData[id];
    const fm = document.getElementById('editFeatureForm');
    fm.action = '/admin/landing/features/' + id;
    fm.querySelector('[name=icon]').value       = f.icon || '';
    fm.querySelector('[name=label]').value      = f.label;
    fm.querySelector('[name=sort_order]').value = f.sort_order;
    document.getElementById('etFtActive').checked = !!f.is_active;
    new bootstrap.Modal(document.getElementById('editFeatureModal')).show();
}

function openEditJenjang(id) {
    const j  = jenjangsData[id];
    const fm = document.getElementById('editJenjangForm');
    fm.action = '/admin/landing/jenjangs/' + id;
    fm.querySelector('[name=name]').value       = j.name;
    fm.querySelector('[name=label]').value      = j.label;
    fm.querySelector('[name=emoji]').value      = j.emoji || '';
    fm.querySelector('[name=sort_order]').value = j.sort_order;
    document.getElementById('etJjActive').checked = !!j.is_active;
    // Show current image if exists
    const imgWrap = document.getElementById('jenjangCurrentImg');
    imgWrap.innerHTML = j.image
        ? `<p class="text-muted small mb-1">Gambar saat ini:</p><img src="${j.image.startsWith('http') ? j.image : '/'+j.image.replace(/^\//,'')}" style="height:60px;border-radius:8px;object-fit:cover">`
        : '<p class="text-muted small mb-0">Belum ada gambar.</p>';
    new bootstrap.Modal(document.getElementById('editJenjangModal')).show();
}

function openEditTrust(id) {
    const t  = trustsData[id];
    const fm = document.getElementById('editTrustForm');
    fm.action = '/admin/landing/trusts/' + id;
    fm.querySelector('[name=icon]').value       = t.icon || '';
    fm.querySelector('[name=text]').value       = t.text;
    fm.querySelector('[name=sort_order]').value = t.sort_order;
    document.getElementById('etTrActive').checked = !!t.is_active;
    new bootstrap.Modal(document.getElementById('editTrustModal')).show();
}

function openEditHighlight(id) {
    const h  = highlightsData[id];
    const fm = document.getElementById('editHighlightForm');
    fm.action = '/admin/landing/highlights/' + id;
    fm.querySelector('[name=title]').value       = h.title;
    fm.querySelector('[name=description]').value = h.description;
    fm.querySelector('[name=sort_order]').value  = h.sort_order;
    document.getElementById('etHlActive').checked = !!h.is_active;
    const imgWrap = document.getElementById('highlightCurrentImg');
    imgWrap.innerHTML = h.image
        ? `<p class="text-muted small mb-1">Gambar saat ini:</p><img src="${h.image.startsWith('http') ? h.image : '/'+h.image.replace(/^\//,'')}" style="height:60px;border-radius:8px;object-fit:cover">`
        : '<p class="text-muted small mb-0">Belum ada gambar.</p>';
    new bootstrap.Modal(document.getElementById('editHighlightModal')).show();
}

function openEditGallery(id) {
    const g  = galleriesData[id];
    const fm = document.getElementById('editGalleryForm');
    fm.action = '/admin/landing/galleries/' + id;
    fm.querySelector('[name=alt]').value        = g.alt || '';
    fm.querySelector('[name=sort_order]').value = g.sort_order;
    document.getElementById('etGalActive').checked = !!g.is_active;
    const imgWrap = document.getElementById('galleryCurrentImg');
    imgWrap.innerHTML = g.image
        ? `<p class="text-muted small mb-1">Foto saat ini:</p><img src="${g.image.startsWith('http') ? g.image : '/'+g.image.replace(/^\//,'')}" style="height:80px;border-radius:8px;object-fit:cover">`
        : '';
    new bootstrap.Modal(document.getElementById('editGalleryModal')).show();
}

function openEditFaq(id) {
    const f  = faqsData[id];
    const fm = document.getElementById('editFaqForm');
    fm.action = '/admin/landing/faqs/' + id;
    fm.querySelector('[name=question]').value   = f.question;
    fm.querySelector('[name=answer]').value     = f.answer;
    fm.querySelector('[name=sort_order]').value = f.sort_order;
    document.getElementById('etFaqActive').checked = !!f.is_active;
    new bootstrap.Modal(document.getElementById('editFaqModal')).show();
}
</script>
@endpush
@endsection
