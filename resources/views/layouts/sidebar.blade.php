{{-- resources/views/layouts/sidebar.blade.php --}}
<nav class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none" style="gap:12px;flex:1;min-width:0;">
            <div class="brand-logo">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <div style="min-width:0;">
                <div class="brand-title">Akademi<strong>Pro</strong></div>
                <div class="brand-sub">Smart Center</div>
            </div>
        </a>
        <button class="sidebar-toggle" id="sidebarToggle" onclick="closeSidebar()">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <div class="sidebar-user">
        <img src="{{ auth()->user()->avatar_url }}" class="sidebar-avatar" alt="Avatar">
        <div class="sidebar-user-info">
            <span class="user-name">{{ auth()->user()->name }}</span>
            <span class="user-role badge bg-primary">{{ ucfirst(auth()->user()->getRoleNames()->first()) }}</span>
        </div>
    </div>

    <ul class="sidebar-nav">

        @php
            $user = auth()->user();
            $isOwner = $user && method_exists($user, 'hasRole') && $user->hasRole('owner');
            $allowedPages = null;
            if (! $isOwner && $user && method_exists($user, 'hasRole') && $user->hasRole('admin')) {
                $impersonateBranchId = session('impersonate.branch_id');
                $branch = null;
                if ($impersonateBranchId) {
                    $branch = \App\Models\Branch::find($impersonateBranchId);
                }
                if (! $branch && ! empty($user->branch_id)) {
                    $branch = \App\Models\Branch::find($user->branch_id);
                }
                if (! $branch) {
                    $branch = \App\Models\Branch::where('admin_id', $user->id)->first();
                }

                if ($branch) {
                    $allowedPages = $branch->allowed_pages ?? [];
                    if (! is_array($allowedPages) || empty($allowedPages)) {
                        $allowedPages = [];
                        if ($branch->can_students) $allowedPages[] = 'student';
                        if ($branch->can_teachers) $allowedPages[] = 'teacher';
                        if ($branch->can_schedules) $allowedPages[] = 'schedule';
                        if ($branch->can_payments) $allowedPages[] = 'payment';
                        if ($branch->can_tryouts) $allowedPages[] = 'tryout';
                    }
                } else {
                    $allowedPages = [];
                }
            }
            \Log::info('Sidebar Render Debug', [
                'user_id' => $user->id,
                'user_role' => $user->getRoleNames()->first(),
                'isOwner' => $isOwner,
                'allowedPages' => $allowedPages,
                'allowedPages_type' => gettype($allowedPages),
            ]);
        @endphp

        <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="nav-link">
                <i class="nav-icon bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>

        {{-- OWNER MENU --}}
        @role('owner')
        <li class="nav-header">MANAJEMEN</li>
        <li class="nav-item {{ request()->routeIs('owner.branches.*') ? 'active' : '' }}">
            <a href="{{ route('owner.branches.index') }}" class="nav-link">
                <i class="nav-icon bi bi-building"></i>
                <span>Cabang</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('owner.analytics') ? 'active' : '' }}">
            <a href="{{ route('owner.analytics') }}" class="nav-link">
                <i class="nav-icon bi bi-graph-up-arrow"></i>
                <span>Analytics</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('owner.activity-log') ? 'active' : '' }}">
            <a href="{{ route('owner.activity-log') }}" class="nav-link">
                <i class="nav-icon bi bi-activity"></i>
                <span>Activity Log</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('owner.settings.*') ? 'active' : '' }}">
            <a href="{{ route('owner.settings.index') }}" class="nav-link">
                <i class="nav-icon bi bi-gear"></i>
                <span>Pengaturan</span>
            </a>
        </li>
        @endrole

        {{-- ADMIN MENU --}}
        @hasanyrole('owner|admin')
        @php
            $showAkademik = $isOwner ||
                in_array('student', (array)$allowedPages) ||
                in_array('teacher', (array)$allowedPages) ||
                in_array('module', (array)$allowedPages) ||
                in_array('package', (array)$allowedPages) ||
                in_array('course', (array)$allowedPages) ||
                in_array('course_fee', (array)$allowedPages) ||
                in_array('class', (array)$allowedPages) ||
                in_array('schedule', (array)$allowedPages) ||
                in_array('certificate', (array)$allowedPages);

            $showKeuangan = $isOwner ||
                in_array('payment', (array)$allowedPages) ||
                in_array('salary', (array)$allowedPages) ||
                in_array('report', (array)$allowedPages);

            $showLanding = $isOwner || in_array('landing', (array)$allowedPages);

            $showKomunikasi = $isOwner ||
                in_array('announcement', (array)$allowedPages) ||
                in_array('message', (array)$allowedPages) ||
                in_array('videocall', (array)$allowedPages);

            $showUjian = $isOwner || in_array('tryout', (array)$allowedPages);
        @endphp

        @if($showAkademik)
        <li class="nav-header">AKADEMIK</li>

        @if($isOwner || in_array('student', (array)$allowedPages))
        <li class="nav-item {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
            <a href="{{ route('admin.students.index') }}" class="nav-link">
                <i class="nav-icon bi bi-people"></i>
                <span>Siswa</span>
            </a>
        </li>
        @endif

        @if($isOwner || in_array('teacher', (array)$allowedPages))
        <li class="nav-item {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}">
            <a href="{{ route('admin.teachers.index') }}" class="nav-link">
                <i class="nav-icon bi bi-person-badge"></i>
                <span>Guru</span>
            </a>
        </li>
        @endif

        @if($isOwner || in_array('module', (array)$allowedPages))
        <li class="nav-item {{ request()->routeIs('admin.modules.*') ? 'active' : '' }}">
            <a href="{{ route('admin.modules.index') }}" class="nav-link">
                <i class="nav-icon bi bi-bookmark"></i>
                <span>Modul Belajar</span>
            </a>
        </li>
        @endif

        @if($isOwner || in_array('package', (array)$allowedPages))
        <li class="nav-item {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
            <a href="{{ route('admin.packages.index') }}" class="nav-link">
                <i class="nav-icon bi bi-box-seam"></i>
                <span>Paket Belajar</span>
            </a>
        </li>
        @endif

        @if($isOwner || in_array('course', (array)$allowedPages))
        <li class="nav-item {{ (request()->routeIs('admin.courses.*') && !request()->routeIs('admin.courses.fees*')) ? 'active' : '' }}">
            <a href="{{ route('admin.courses.index') }}" class="nav-link">
                <i class="nav-icon bi bi-book"></i>
                <span>Mata Pelajaran</span>
            </a>
        </li>
        @endif

        @if($isOwner || in_array('course_fee', (array)$allowedPages) || in_array('course', (array)$allowedPages))
        <li class="nav-item {{ request()->routeIs('admin.courses.fees*') ? 'active' : '' }}">
            <a href="{{ route('admin.courses.fees') }}" class="nav-link">
                <i class="nav-icon bi bi-tag"></i>
                <span>Biaya Mapel</span>
            </a>
        </li>
        @endif

        @if($isOwner || in_array('class', (array)$allowedPages))
        <li class="nav-item {{ request()->routeIs('admin.classes.*') ? 'active' : '' }}">
            <a href="{{ route('admin.classes.index') }}" class="nav-link">
                <i class="nav-icon bi bi-grid-3x3-gap"></i>
                <span>Kelas</span>
            </a>
        </li>
        @endif

        @if($isOwner || in_array('schedule', (array)$allowedPages))
        <li class="nav-item {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
            <a href="{{ route('admin.schedules.index') }}" class="nav-link">
                <i class="nav-icon bi bi-calendar3"></i>
                <span>Jadwal</span>
            </a>
        </li>
        @endif

        @if($isOwner || in_array('certificate', (array)$allowedPages))
        <li class="nav-item {{ request()->routeIs('admin.certificates.*') ? 'active' : '' }}">
            <a href="{{ route('admin.certificates.index') }}" class="nav-link">
                <i class="nav-icon bi bi-patch-check"></i>
                <span>Sertifikat</span>
            </a>
        </li>
        @endif
        @endif

        @if($showKeuangan)
        <li class="nav-header">KEUANGAN</li>

        @if($isOwner || in_array('payment', (array)$allowedPages))
        <li class="nav-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
            <a href="{{ route('admin.payments.index') }}" class="nav-link">
                <i class="nav-icon bi bi-cash-coin"></i>
                <span>Pembayaran Invoice</span>
            </a>
        </li>
        @endif

        @if($isOwner || in_array('payment', (array)$allowedPages))
        <li class="nav-item {{ request()->routeIs('admin.course-payments.*') ? 'active' : '' }}">
            <a href="{{ route('admin.course-payments.index') }}" class="nav-link">
                <i class="nav-icon bi bi-credit-card"></i>
                <span>Verifikasi Bayar Mapel</span>
                @php $pendingPay = \App\Models\StudentCoursePayment::where('status','pending')->count() @endphp
                @if($pendingPay > 0)
                    <span class="badge bg-danger ms-auto">{{ $pendingPay }}</span>
                @endif
            </a>
        </li>
        @endif

        @if($isOwner || in_array('salary', (array)$allowedPages))
        <li class="nav-item {{ request()->routeIs('admin.salaries.*') ? 'active' : '' }}">
            <a href="{{ route('admin.salaries.index') }}" class="nav-link">
                <i class="nav-icon bi bi-currency-dollar"></i>
                <span>Gaji Guru</span>
            </a>
        </li>
        @endif

        @if($isOwner || in_array('report', (array)$allowedPages))
        <li class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <a href="{{ route('admin.reports.index') }}" class="nav-link">
                <i class="nav-icon bi bi-file-earmark-bar-graph"></i>
                <span>Laporan</span>
            </a>
        </li>
        @endif
        @endif

        @if($showLanding)
        <li class="nav-header">LANDING PAGE</li>
        <li class="nav-item {{ request()->routeIs('admin.landing.*') ? 'active' : '' }}">
            <a href="{{ route('landing.index') }}" class="nav-link">
                <i class="nav-icon bi bi-window-sidebar"></i>
                <span>Kelola Landing Page</span>
            </a>
        </li>
        @endif

        @if($showKomunikasi)
        <li class="nav-header">KOMUNIKASI</li>

        @if($isOwner || in_array('announcement', (array)$allowedPages))
        <li class="nav-item {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
            <a href="{{ route('admin.announcements.index') }}" class="nav-link">
                <i class="nav-icon bi bi-megaphone"></i>
                <span>Pengumuman</span>
            </a>
        </li>
        @endif

        @if($isOwner || in_array('message', (array)$allowedPages))
        <li class="nav-item {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
            <a href="{{ route('admin.messages.index') }}" class="nav-link">
                <i class="nav-icon bi bi-chat-dots"></i>
                <span>Pesan Aplikasi</span>
            </a>
        </li>
        @endif

        @if($isOwner || in_array('videocall', (array)$allowedPages))
        <li class="nav-item {{ request()->routeIs('admin.videocall') ? 'active' : '' }}">
            <a href="{{ route('videocall.index') }}" class="nav-link">
                <i class="nav-icon bi bi-camera-video"></i>
                <span>Video Call</span>
            </a>
        </li>
        @endif
        @endif

        @if($showUjian)
        <li class="nav-header">UJIAN</li>
        @if($isOwner || in_array('tryout', (array)$allowedPages))
        <li class="nav-item {{ request()->routeIs('admin.tryouts.*') ? 'active' : '' }}">
            <a href="{{ route('admin.tryouts.index') }}" class="nav-link">
                <i class="nav-icon bi bi-clipboard-check"></i>
                <span>Tryout CBT</span>
            </a>
        </li>
        @endif
        @endif
        @endhasanyrole

        {{-- GURU MENU --}}
        @role('guru')
        <li class="nav-header">MENGAJAR</li>
        <li class="nav-item {{ request()->routeIs('guru.classes.*') ? 'active' : '' }}">
            <a href="{{ route('guru.classes.index') }}" class="nav-link">
                <i class="nav-icon bi bi-grid-3x3-gap"></i>
                <span>Kelas Saya</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('guru.attendance.history*') ? 'active' : '' }}">
            <a href="{{ route('guru.attendance.history') }}" class="nav-link">
                <i class="nav-icon bi bi-clock-history"></i>
                <span>Riwayat Absensi</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
            <a href="{{ route('guru.dashboard') }}#jadwal" class="nav-link">
                <i class="nav-icon bi bi-calendar3"></i>
                <span>Jadwal Mengajar</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('guru.schedule-agreements.*') ? 'active' : '' }}">
            <a href="{{ route('guru.schedule-agreements.index') }}" class="nav-link">
                <i class="nav-icon bi bi-calendar-check"></i>
                <span>Persetujuan Jadwal</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('guru.announcements') ? 'active' : '' }}">
            <a href="{{ route('guru.announcements') }}" class="nav-link">
                <i class="nav-icon bi bi-megaphone"></i>
                <span>Pengumuman</span>
            </a>
        </li>
        @endrole

        {{-- SISWA MENU --}}
        @role('siswa')
        <li class="nav-header">BELAJAR</li>
        <li class="nav-item {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
            <a href="{{ route('siswa.dashboard') }}" class="nav-link">
                <i class="nav-icon bi bi-house"></i>
                <span>Beranda</span>
            </a>
        </li>
        {{-- Jadwal Belajar (siswa) dihapus — link dihilangkan sesuai permintaan pengguna --}}
        <li class="nav-item {{ request()->routeIs('siswa.courses*') ? 'active' : '' }}">
            <a href="{{ route('siswa.courses.index') }}" class="nav-link">
                <i class="nav-icon bi bi-journal-bookmark"></i>
                <span>List Mata Pelajaran</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('siswa.courses.fees') ? 'active' : '' }}">
            <a href="{{ route('siswa.courses.fees') }}" class="nav-link">
                <i class="nav-icon bi bi-cash-coin"></i>
                <span>Harga Mapel</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('siswa.attendance*') ? 'active' : '' }}">
            <a href="{{ route('siswa.attendance') }}" class="nav-link">
                <i class="nav-icon bi bi-clock-history"></i>
                <span>Riwayat Absensi</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('siswa.schedule-agreements.*') ? 'active' : '' }}">
            <a href="{{ route('siswa.schedule-agreements.index') }}" class="nav-link">
                <i class="nav-icon bi bi-calendar-check"></i>
                <span>Persetujuan Jadwal</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('siswa.billing*') ? 'active' : '' }}">
            <a href="{{ route('siswa.billing.index') }}" class="nav-link">
                <i class="nav-icon bi bi-wallet2"></i>
                <span>Tagihan</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('siswa.tryout') ? 'active' : '' }}">
            <a href="{{ route('siswa.tryout') }}" class="nav-link">
                <i class="nav-icon bi bi-pencil-square"></i>
                <span>Tryout Online</span>
            </a>
        </li>
        @endrole

        {{-- COMMON --}}
        <li class="nav-header">LAINNYA</li>
        <li class="nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
            <a href="{{ route('profile.edit') }}" class="nav-link">
                <i class="nav-icon bi bi-person-circle"></i>
                <span>Profil Saya</span>
            </a>
        </li>
    </ul>
</nav>
