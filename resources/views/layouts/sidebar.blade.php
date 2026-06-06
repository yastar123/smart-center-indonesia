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
        @role('admin|owner')
        <li class="nav-header">AKADEMIK</li>
        <li class="nav-item {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
            <a href="{{ route('admin.students.index') }}" class="nav-link">
                <i class="nav-icon bi bi-people"></i>
                <span>Siswa</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}">
            <a href="{{ route('admin.teachers.index') }}" class="nav-link">
                <i class="nav-icon bi bi-person-badge"></i>
                <span>Guru</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('admin.classes.*') ? 'active' : '' }}">
            <a href="{{ route('admin.classes.index') }}" class="nav-link">
                <i class="nav-icon bi bi-grid-3x3-gap"></i>
                <span>Kelas</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
            <a href="{{ route('admin.courses.index') }}" class="nav-link">
                <i class="nav-icon bi bi-book"></i>
                <span>Mata Pelajaran</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
            <a href="{{ route('admin.schedules.index') }}" class="nav-link">
                <i class="nav-icon bi bi-calendar3"></i>
                <span>Jadwal</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('admin.certificates.*') ? 'active' : '' }}">
            <a href="{{ route('admin.certificates.index') }}" class="nav-link">
                <i class="nav-icon bi bi-patch-check"></i>
                <span>Sertifikat</span>
            </a>
        </li>

        <li class="nav-header">KEUANGAN</li>
        <li class="nav-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
            <a href="{{ route('admin.payments.index') }}" class="nav-link">
                <i class="nav-icon bi bi-cash-coin"></i>
                <span>Pembayaran</span>
                @php $pending = \App\Models\Invoice::where('status','unpaid')->count() @endphp
                @if($pending > 0)
                    <span class="badge bg-danger ms-auto">{{ $pending }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <a href="{{ route('admin.reports.index') }}" class="nav-link">
                <i class="nav-icon bi bi-file-earmark-bar-graph"></i>
                <span>Laporan</span>
            </a>
        </li>

        <li class="nav-header">UJIAN</li>
        <li class="nav-item {{ request()->routeIs('admin.tryouts.*') ? 'active' : '' }}">
            <a href="{{ route('admin.tryouts.index') }}" class="nav-link">
                <i class="nav-icon bi bi-clipboard-check"></i>
                <span>Tryout CBT</span>
            </a>
        </li>
        @endrole

        {{-- GURU MENU --}}
        @role('guru')
        <li class="nav-header">MENGAJAR</li>
        <li class="nav-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
            <a href="{{ route('guru.dashboard') }}#jadwal" class="nav-link">
                <i class="nav-icon bi bi-calendar3"></i>
                <span>Jadwal Mengajar</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('guru.attendance') ? 'active' : '' }}">
            <a href="{{ route('guru.attendance') }}" class="nav-link">
                <i class="nav-icon bi bi-check2-square"></i>
                <span>Absensi Siswa</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('guru.grades') ? 'active' : '' }}">
            <a href="{{ route('guru.grades') }}" class="nav-link">
                <i class="nav-icon bi bi-bar-chart"></i>
                <span>Input Nilai</span>
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
        <li class="nav-item {{ request()->routeIs('siswa.schedule') ? 'active' : '' }}">
            <a href="{{ route('siswa.schedule') }}" class="nav-link">
                <i class="nav-icon bi bi-calendar-week"></i>
                <span>Jadwal Belajar</span>
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
