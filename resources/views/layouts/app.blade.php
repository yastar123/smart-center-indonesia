<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title','Dashboard') | Akademi Pro</title>

    {{-- BOOTSTRAP --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- GOOGLE FONTS --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    {{-- ICON --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    {{-- DATATABLE --}}
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    {{-- APEXCHART --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>

        :root{
            --sidebar-width:270px;
            --sidebar-bg:#0f172a;
            --sidebar-hover:#1e293b;
            --primary:#3b82f6;
            --success:#10b981;
            --danger:#ef4444;
            --warning:#f59e0b;
            --dark:#0f172a;
            --text:#cbd5e1;
            --border:rgba(255,255,255,.06);

            /* Typography */
            --font-sans: 'Inter', 'Segoe UI', system-ui, -apple-system, 'Helvetica Neue', Arial;
            --type-base: 16px;
            --type-scale: 1.15;
            --space-1: 8px;
            --space-2: 16px;
            --space-3: 24px;
        }

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:var(--font-sans);
            font-size:clamp(14px, 1.2vw, 16px);
            background:#f1f5f9;
            overflow-x:hidden;
            -webkit-font-smoothing:antialiased;
            -moz-osx-font-smoothing:grayscale;
            color:#0f172a;
        }

        h1{font-size:clamp(28px, 4vw, 42px);}
        h2{font-size:clamp(22px, 3vw, 32px);}
        h3{font-size:clamp(18px, 2.2vw, 24px);}

        /* Micro animations */
        .anim { transition: all .45s cubic-bezier(.2,.9,.2,1); will-change: transform, opacity; }
        .fade-up { opacity:0; transform:translateY(12px); }
        .fade-up.in-view { opacity:1; transform:translateY(0); }

        /* =========================
            SIDEBAR
        ==========================*/

        .sidebar{
            position:fixed;
            top:0;
            left:0;
            width:var(--sidebar-width);
            height:100vh;
            background:linear-gradient(180deg,#0f172a,#111827);
            overflow-y:auto;
            z-index:999;
            transition:.3s;
            border-right:1px solid var(--border);
        }

        .sidebar::-webkit-scrollbar{
            width:5px;
        }

        .sidebar::-webkit-scrollbar-thumb{
            background:#334155;
            border-radius:10px;
        }

        .sidebar-brand{
            height:75px;
            display:flex;
            align-items:center;
            padding:0 22px;
            border-bottom:1px solid var(--border);
        }

        .brand-logo{
            width:45px;
            height:45px;
            border-radius:14px;
            background:linear-gradient(135deg,#3b82f6,#6366f1);
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:22px;
            color:white;
            margin-right:14px;
        }

        .brand-title{
            color:white;
            font-size:20px;
            font-weight:700;
            line-height:1;
        }

        .brand-sub{
            color:#94a3b8;
            font-size:12px;
        }

        /* =========================
            USER
        ==========================*/

        .sidebar-user{
            padding:20px;
            border-bottom:1px solid var(--border);
            display:flex;
            align-items:center;
            gap:14px;
        }

        .sidebar-avatar{
            width:52px;
            height:52px;
            border-radius:50%;
            object-fit:cover;
            border:3px solid rgba(255,255,255,.1);
        }

        .user-name{
            color:white;
            font-size:15px;
            font-weight:600;
        }

        .user-role{
            font-size:11px;
            padding:4px 10px;
            border-radius:30px;
            background:rgba(59,130,246,.2);
            color:#93c5fd;
            display:inline-block;
            margin-top:4px;
        }

        /* =========================
            MENU
        ==========================*/

        .sidebar-nav{
            padding:15px 10px 100px;
        }

        .nav-header{
            color:#64748b;
            font-size:11px;
            font-weight:700;
            margin:20px 10px 10px;
            text-transform:uppercase;
            letter-spacing:1px;
        }

        .nav-item{
            margin-bottom:4px;
        }

        .nav-link{
            display:flex;
            align-items:center;
            gap:14px;
            color:#cbd5e1;
            text-decoration:none;
            padding:12px 14px;
            border-radius:14px;
            transition:.25s;
            font-size:14px;
            font-weight:500;
        }

        .nav-link i{
            font-size:18px;
        }

        .nav-link:hover{
            background:var(--sidebar-hover);
            color:white;
            transform:translateX(4px);
        }

        .nav-link.active{
            background:linear-gradient(90deg,#2563eb,#3b82f6);
            color:white;
            box-shadow:0 8px 20px rgba(59,130,246,.3);
        }

        .menu-badge{
            margin-left:auto;
            background:#ef4444;
            color:white;
            padding:2px 8px;
            border-radius:20px;
            font-size:10px;
        }

        /* =========================
            MAIN
        ==========================*/

        .main-content{
            margin-left:var(--sidebar-width);
            min-height:100vh;
        }

        /* =========================
            TOPBAR
        ==========================*/

        .topbar{
            height:75px;
            background:white;
            border-bottom:1px solid #e2e8f0;
            display:flex;
            align-items:center;
            justify-content:space-between;
            padding:0 30px;
            position:sticky;
            top:0;
            z-index:100;
        }

        .topbar-left h4{
            margin:0;
            font-size:22px;
            font-weight:700;
            color:#0f172a;
        }

        .topbar-left p{
            margin:0;
            color:#64748b;
            font-size:13px;
        }

        .topbar-right{
            display:flex;
            align-items:center;
            gap:18px;
        }

        .top-icon{
            width:42px;
            height:42px;
            border-radius:12px;
            background:#f1f5f9;
            display:flex;
            align-items:center;
            justify-content:center;
            cursor:pointer;
            position:relative;
        }

        .top-icon i{
            font-size:18px;
            color:#334155;
        }

        .notif-badge{
            position:absolute;
            top:-5px;
            right:-5px;
            width:18px;
            height:18px;
            border-radius:50%;
            background:#ef4444;
            color:white;
            font-size:10px;
            display:flex;
            align-items:center;
            justify-content:center;
        }

        /* =========================
            CONTENT
        ==========================*/

        .content-wrapper{
            padding:25px;
        }

        .dashboard-card{
            background:white;
            border-radius:24px;
            padding:24px;
            border:1px solid #e2e8f0;
            box-shadow:0 10px 30px rgba(0,0,0,.03);
            transition:.3s;
        }

        .dashboard-card:hover{
            transform:translateY(-4px);
        }

        .stat-card{
            background:white;
            border-radius:22px;
            padding:24px;
            position:relative;
            overflow:hidden;
            border:1px solid #e2e8f0;
            transition:.3s;
        }

        .stat-card:hover{
            transform:translateY(-5px);
            box-shadow:0 15px 40px rgba(0,0,0,.08);
        }

        .stat-icon{
            width:60px;
            height:60px;
            border-radius:18px;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:24px;
            color:white;
            margin-bottom:18px;
        }

        .bg-primary-soft{
            background:linear-gradient(135deg,#3b82f6,#2563eb);
        }

        .bg-success-soft{
            background:linear-gradient(135deg,#10b981,#059669);
        }

        .bg-warning-soft{
            background:linear-gradient(135deg,#f59e0b,#d97706);
        }

        .bg-danger-soft{
            background:linear-gradient(135deg,#ef4444,#dc2626);
        }

        .stat-title{
            color:#64748b;
            font-size:14px;
            margin-bottom:8px;
        }

        .stat-value{
            font-size:30px;
            font-weight:700;
            color:#0f172a;
        }

        .stat-growth{
            font-size:13px;
            margin-top:10px;
            color:#10b981;
        }

        /* =========================
            TABLE
        ==========================*/

        .table-modern{
            background:white;
            border-radius:20px;
            overflow:hidden;
        }

        .table-modern thead{
            background:#f8fafc;
        }

        .table-modern th{
            border:none;
            padding:16px;
            font-size:13px;
            color:#475569;
        }

        .table-modern td{
            padding:16px;
            vertical-align:middle;
        }

        /* =========================
            MOBILE
        ==========================*/

        @media(max-width:992px){

            .sidebar{
                transform:translateX(-100%);
            }

            .sidebar.show{
                transform:translateX(0);
            }

            .main-content{
                margin-left:0;
            }

        }

    </style>

    @stack('styles')
</head>
<body>

{{-- SIDEBAR --}}
<nav class="sidebar" id="sidebar">

    {{-- BRAND --}}
    <div class="sidebar-brand">

        <div class="d-flex align-items-center">
            <div class="brand-logo">
                <i class="bi bi-mortarboard-fill"></i>
            </div>

            <div>
                <div class="brand-title">Smart Center </div>
                <div class="brand-sub">Enterprise System</div>
            </div>
        </div>

    </div>

    {{-- USER --}}
    <div class="sidebar-user">

        <img
            src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=2563eb&color=fff"
            class="sidebar-avatar">

        <div>
            <div class="user-name">
                {{ auth()->user()->name }}
            </div>

            <div class="user-role">
                {{ ucfirst(auth()->user()->getRoleNames()->first()) }}
            </div>
        </div>

    </div>

    {{-- MENU --}}
    <div class="sidebar-nav">

        {{-- DASHBOARD --}}
        <div class="nav-item">
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>
        </div>

        {{-- OWNER --}}
        @role('owner')

        <div class="nav-header">OWNER PANEL</div>

        <div class="nav-item">
            <a href="{{ route('owner.branches.index') }}" class="nav-link">
                <i class="bi bi-building"></i>
                <span>Monitoring Cabang</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-graph-up-arrow"></i>
                <span>Analytics</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-cash-stack"></i>
                <span>Laporan Keuangan</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-people-fill"></i>
                <span>Manajemen User</span>
            </a>
        </div>

        @endrole

        {{-- ADMIN --}}
        @role('admin|owner')

        <div class="nav-header">AKADEMIK</div>

        <div class="nav-item">
            <a href="{{ route('admin.students.index') }}" class="nav-link">
                <i class="bi bi-mortarboard"></i>
                <span>Siswa</span>
                <span class="menu-badge">120</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.teachers.index') }}" class="nav-link">
                <i class="bi bi-person-workspace"></i>
                <span>Guru</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-book-half"></i>
                <span>Modul Belajar</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-journal-bookmark"></i>
                <span>Mata Pelajaran</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-diagram-3"></i>
                <span>Kelas</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.schedules.index') }}" class="nav-link">
                <i class="bi bi-calendar-week"></i>
                <span>Jadwal</span>
            </a>
        </div>

        <div class="nav-header">KEUANGAN</div>

        <div class="nav-item">
            <a href="{{ route('admin.payments.index') }}" class="nav-link">
                <i class="bi bi-wallet2"></i>
                <span>Pembayaran</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-receipt"></i>
                <span>Invoice</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-award"></i>
                <span>Sertifikat</span>
            </a>
        </div>

        <div class="nav-header">TRYOUT CBT</div>

        <div class="nav-item">
            <a href="{{ route('admin.tryouts.index') }}" class="nav-link">
                <i class="bi bi-ui-checks-grid"></i>
                <span>Tryout Online</span>
            </a>
        </div>

        @endrole

        {{-- GURU --}}
        @role('guru')

        <div class="nav-header">GURU PANEL</div>

        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-calendar2-week"></i>
                <span>Jadwal Mengajar</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-check2-square"></i>
                <span>Absensi</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-bar-chart-line"></i>
                <span>Input Nilai</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-cloud-upload"></i>
                <span>Upload Materi</span>
            </a>
        </div>

        @endrole

        {{-- SISWA --}}
        @role('siswa')

        <div class="nav-header">SISWA PANEL</div>

        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-calendar-event"></i>
                <span>Jadwal Belajar</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-credit-card"></i>
                <span>Pembayaran</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-laptop"></i>
                <span>Tryout CBT</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-file-earmark-text"></i>
                <span>Raport Digital</span>
            </a>
        </div>

        @endrole

        {{-- SYSTEM --}}
        <div class="nav-header">SYSTEM</div>

        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-chat-dots"></i>
                <span>Chat Realtime</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-bell"></i>
                <span>Notifikasi</span>
            </a>
        </div>

        <div class="nav-item">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="nav-link border-0 bg-transparent w-100 text-start">
                    <i class="bi bi-box-arrow-left"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>

    </div>

</nav>

{{-- MAIN --}}
<div class="main-content">

    {{-- TOPBAR --}}
    <div class="topbar">

        <button class="btn btn-sm d-lg-none me-2" style="background:transparent;border:0;color:#0f172a" onclick="toggleSidebar()" aria-label="Toggle navigation">
            <i class="bi bi-list" style="font-size:20px"></i>
        </button>

        <div class="topbar-left">

            <h4>@yield('page-title','Dashboard')</h4>

            <p>
                Sistem Management Akademi & Bimbel Enterprise
            </p>

        </div>

        <div class="topbar-right">

            <div class="top-icon">
                <i class="bi bi-search"></i>
            </div>

            <div class="top-icon">
                <i class="bi bi-moon"></i>
            </div>

            <div class="top-icon">
                <i class="bi bi-bell"></i>
                <span class="notif-badge">5</span>
            </div>

            <div class="dropdown">

                <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2"
                        data-bs-toggle="dropdown">

                    <img
                        src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=2563eb&color=fff"
                        width="38"
                        class="rounded-circle">

                    <div class="text-start d-none d-md-block">
                        <div class="fw-semibold small">
                            {{ auth()->user()->name }}
                        </div>

                        <div style="font-size:11px;color:#64748b">
                            {{ ucfirst(auth()->user()->getRoleNames()->first()) }}
                        </div>
                    </div>

                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow border-0">

                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-person me-2"></i>
                            Profile
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-gear me-2"></i>
                            Settings
                        </a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>
                                Logout
                            </button>

                        </form>

                    </li>

                </ul>

            </div>

        </div>

    </div>

    {{-- CONTENT --}}
    <div class="content-wrapper">

        @yield('content')

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

    function toggleSidebar(){
        document.getElementById('sidebar').classList.toggle('show');
    }

</script>
<script>
    // IntersectionObserver for simple reveal animations
    document.addEventListener('DOMContentLoaded', function(){
        const io = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if(entry.isIntersecting){
                    entry.target.classList.add('in-view');
                    io.unobserve(entry.target);
                }
            });
        }, {threshold: 0.12});

        document.querySelectorAll('.fade-up').forEach(el => io.observe(el));
    });
</script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stack('scripts')

</body>
</html>