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
    <ul class="nav nav-tabs lp-tabs mb-4" id="lpTabs">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-hero"><i class="bi bi-house-door me-1"></i>Hero</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-stats"><i class="bi bi-bar-chart me-1"></i>Statistik</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-programs"><i class="bi bi-award me-1"></i>Program</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-testimonials"><i class="bi bi-chat-heart me-1"></i>Testimoni</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-wa"><i class="bi bi-whatsapp me-1 text-success"></i>WhatsApp <span class="badge bg-success ms-1" style="font-size:.65rem">{{ $waNumbers->count() }}</span></button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-cta"><i class="bi bi-megaphone me-1"></i>CTA</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-footer"><i class="bi bi-layout-sidebar-inset-reverse me-1"></i>Footer</button></li>
    </ul>

    <div class="tab-content">

        {{-- ────── HERO TAB ────── --}}
        <div class="tab-pane fade show active" id="tab-hero">
            <div class="card lp-card">
                <div class="card-header lp-card-header"><i class="bi bi-house-door me-2"></i>Konten Hero Section</div>
                <div class="card-body">
                    <form action="{{ route('admin.landing.settings.update') }}" method="POST">
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
                            <div class="col-12"><hr class="my-1"><p class="fw-semibold text-muted mb-2"><i class="bi bi-images me-1"></i>URL Gambar Slide (gunakan link gambar langsung)</p></div>
                            <div class="col-12">
                                <label class="form-label">Slide 1 URL</label>
                                <input type="url" name="settings[hero.slide_1_url]" class="form-control" value="{{ $settings['hero.slide_1_url']->value ?? '' }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Slide 2 URL</label>
                                <input type="url" name="settings[hero.slide_2_url]" class="form-control" value="{{ $settings['hero.slide_2_url']->value ?? '' }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Slide 3 URL</label>
                                <input type="url" name="settings[hero.slide_3_url]" class="form-control" value="{{ $settings['hero.slide_3_url']->value ?? '' }}">
                            </div>
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

        {{-- ────── STATS TAB ────── --}}
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

        {{-- ────── TESTIMONIALS TAB ────── --}}
        <div class="tab-pane fade" id="tab-testimonials">

            {{-- Add Testimonial Form --}}
            <div class="card lp-card mb-4">
                <div class="card-header lp-card-header"><i class="bi bi-plus-circle me-2"></i>Tambah Testimoni Baru</div>
                <div class="card-body">
                    <form action="{{ route('admin.landing.testimonials.store') }}" method="POST">
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
                            <div class="testi-mini-avatar" style="background:{{ $testi->gradient }}">{{ $testi->initial }}</div>
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
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="is_popular" id="epPopular" value="1"><label class="form-check-label" for="epPopular">Populer</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="is_new" id="epNew" value="1"><label class="form-check-label" for="epNew">Baru</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" id="epActive" value="1"><label class="form-check-label" for="epActive">Aktif</label></div>
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
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" id="etActive" value="1"><label class="form-check-label" for="etActive">Tampilkan</label></div>
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
                                <input class="form-check-input" type="checkbox" name="is_primary" id="ewPrimary" value="1">
                                <label class="form-check-label fw-semibold" for="ewPrimary"><i class="bi bi-star-fill text-warning me-1"></i>Utama</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="ewActive" value="1">
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
</script>
@endpush
@endsection
