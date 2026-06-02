{{-- resources/views/layouts/sidebar.blade.php --}}
<nav class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" class="brand-link">
            <span class="brand-icon"><i class="bi bi-mortarboard-fill"></i></span>
            <span class="brand-text">Akademi<strong>Pro</strong></span>
        </a>
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="bi bi-list"></i>
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
        <li class="nav-item">
            <a href="{{ route('owner.activity-log') }}" class="nav-link">
                <i class="nav-icon bi bi-activity"></i>
                <span>Activity Log</span>
            </a>
        </li>
        <li class="nav-item">
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
        <li class="nav-item {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
            <a href="{{ route('admin.schedules.index') }}" class="nav-link">
                <i class="nav-icon bi bi-calendar3"></i>
                <span>Jadwal</span>
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
        <li class="nav-header">UJIAN</li>
        <li class="nav-item {{ request()->routeIs('admin.tryouts.*') ? 'active' : '' }}">
            <a href="{{ route('admin.tryouts.index') }}" class="nav-link">
                <i class="nav-icon bi bi-clipboard-check"></i>
                <span>Tryout</span>
            </a>
        </li>
        @endrole

        {{-- GURU MENU --}}
        @role('guru')
        <li class="nav-header">MENGAJAR</li>
        <li class="nav-item">
            <a href="{{ route('guru.schedule') }}" class="nav-link">
                <i class="nav-icon bi bi-calendar3"></i>
                <span>Jadwal Mengajar</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('guru.attendance.index') }}" class="nav-link">
                <i class="nav-icon bi bi-check2-square"></i>
                <span>Absensi Siswa</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('guru.grades.index') }}" class="nav-link">
                <i class="nav-icon bi bi-bar-chart"></i>
                <span>Input Nilai</span>
            </a>
        </li>
        @endrole

        {{-- SISWA MENU --}}
        @role('siswa')
        <li class="nav-header">BELAJAR</li>
        <li class="nav-item">
            <a href="{{ route('siswa.tryout.index') }}" class="nav-link">
                <i class="nav-icon bi bi-pencil-square"></i>
                <span>Tryout Online</span>
            </a>
        </li>
        @endrole

        {{-- COMMON --}}
        <li class="nav-header">LAINNYA</li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon bi bi-chat-dots"></i>
                <span>Chat</span>
            </a>
        </li>
    </ul>
</nav>