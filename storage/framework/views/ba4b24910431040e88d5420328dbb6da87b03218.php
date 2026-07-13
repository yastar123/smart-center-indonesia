<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ayo Kursus – Admin Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --bg:#0f0f1a;--sidebar:#13131f;--card:#1a1a2e;--card2:#1e1e32;
  --border:#2a2a45;--accent:#c84ddf;--accent2:#c84ddf;--accent3:#ab8db2;
  --text:#e2e8f0;--muted:#94a3b8;--green:#10b981;--yellow:#f6af23;--red:#ef4444;--blue:#c84ddf;
}
body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--text);display:flex;min-height:100vh;overflow-x:hidden}

/* ── SIDEBAR ── */
.sidebar{width:240px;background:var(--sidebar);border-right:1px solid var(--border);display:flex;flex-direction:column;flex-shrink:0;position:sticky;top:0;height:100vh;overflow-y:auto}
.sidebar-logo{padding:1.5rem 1.25rem;border-bottom:1px solid var(--border)}
.sidebar-logo .badge{display:inline-flex;align-items:center;gap:5px;background:rgba(124,58,237,.2);border:1px solid rgba(124,58,237,.4);color:var(--accent3);font-size:10px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;padding:4px 10px;border-radius:20px;margin-bottom:10px}
.sidebar-logo h2{font-family:'Space Grotesk',sans-serif;font-size:18px;font-weight:700;color:#fff}
.sidebar-logo h2 span{color:var(--accent3)}
.sidebar-logo p{font-size:11px;color:var(--muted);margin-top:3px}
.nav-section{padding:.75rem 1rem .25rem;font-size:10px;font-weight:800;color:var(--muted);letter-spacing:.1em;text-transform:uppercase}
.nav-item{display:flex;align-items:center;gap:10px;padding:9px 1.25rem;font-size:13px;font-weight:500;color:var(--muted);cursor:pointer;border-left:3px solid transparent;transition:all .2s}
.nav-item:hover{color:var(--text);background:rgba(124,58,237,.08)}
.nav-item.active{color:var(--accent3);background:rgba(124,58,237,.12);border-left-color:var(--accent)}
.nav-item i{font-size:16px}
.sidebar-bottom{margin-top:auto;padding:1rem;border-top:1px solid var(--border)}
.user-chip{display:flex;align-items:center;gap:10px}
.avatar{width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;flex-shrink:0}
.user-chip .info p{font-size:13px;font-weight:600;color:var(--text)}
.user-chip .info span{font-size:11px;color:var(--muted)}

/* ── MAIN ── */
.main{flex:1;display:flex;flex-direction:column;min-width:0}
.topbar{background:var(--sidebar);border-bottom:1px solid var(--border);padding:.875rem 1.5rem;display:flex;align-items:center;justify-content:space-between;gap:12px;position:sticky;top:0;z-index:10}
.topbar-title{font-family:'Space Grotesk',sans-serif;font-size:17px;font-weight:600;color:#fff}
.topbar-right{display:flex;align-items:center;gap:10px}
.btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;border:none;transition:all .2s;font-family:'Plus Jakarta Sans',sans-serif}
.btn-primary{background:var(--accent);color:#fff}.btn-primary:hover{background:#6d28d9}
.btn-ghost{background:rgba(255,255,255,.06);color:var(--text);border:1px solid var(--border)}.btn-ghost:hover{background:rgba(255,255,255,.1)}
.btn-danger{background:rgba(239,68,68,.15);color:var(--red);border:1px solid rgba(239,68,68,.3)}.btn-danger:hover{background:rgba(239,68,68,.25)}
.btn-success{background:rgba(16,185,129,.15);color:var(--green);border:1px solid rgba(16,185,129,.3)}.btn-success:hover{background:rgba(16,185,129,.25)}
.btn-sm{padding:5px 12px;font-size:12px}
.btn-xs{padding:4px 10px;font-size:11px;border-radius:6px}

.content{padding:1.5rem;display:flex;flex-direction:column;gap:1.5rem}

/* ── STATS ── */
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}
.stat-card{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:1.25rem;position:relative;overflow:hidden}
.stat-card::before{content:'';position:absolute;top:-30px;right:-30px;width:90px;height:90px;border-radius:50%;opacity:.15}
.stat-card.p .icon-wrap{background:rgba(124,58,237,.2);color:var(--accent3)}
.stat-card.p::before{background:var(--accent)}
.stat-card.a .icon-wrap{background:rgba(16,185,129,.2);color:var(--green)}
.stat-card.a::before{background:var(--green)}
.stat-card.w .icon-wrap{background:rgba(245,158,11,.2);color:var(--yellow)}
.stat-card.w::before{background:var(--yellow)}
.stat-card.r .icon-wrap{background:rgba(239,68,68,.2);color:var(--red)}
.stat-card.r::before{background:var(--red)}
.stat-card .icon-wrap{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;margin-bottom:12px}
.stat-card .num{font-family:'Space Grotesk',sans-serif;font-size:28px;font-weight:700;color:#fff}
.stat-card .lbl{font-size:12px;color:var(--muted);margin-top:3px;font-weight:500}

/* ── TABLES ── */
.section-card{background:var(--card);border:1px solid var(--border);border-radius:12px;overflow:hidden}
.section-card-header{padding:1rem 1.25rem;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:12px}
.section-card-header h3{font-family:'Space Grotesk',sans-serif;font-size:15px;font-weight:600;color:#fff;display:flex;align-items:center;gap:8px}
.section-card-header h3 i{color:var(--accent3);font-size:16px}
.section-tools{display:flex;align-items:center;gap:8px}
.search-box{display:flex;align-items:center;gap:6px;background:var(--card2);border:1px solid var(--border);border-radius:8px;padding:7px 12px}
.search-box i{color:var(--muted);font-size:14px}
.search-box input{background:none;border:none;outline:none;color:var(--text);font-size:13px;width:160px;font-family:'Plus Jakarta Sans',sans-serif}
.search-box input::placeholder{color:var(--muted)}
.filter-select{background:var(--card2);border:1px solid var(--border);border-radius:8px;padding:7px 12px;color:var(--text);font-size:13px;outline:none;font-family:'Plus Jakarta Sans',sans-serif}
.table-wrap{overflow-x:auto}
table{width:100%;border-collapse:collapse}
th{padding:10px 1.25rem;text-align:left;font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.05em;text-transform:uppercase;border-bottom:1px solid var(--border);background:rgba(255,255,255,.02)}
td{padding:12px 1.25rem;font-size:13px;color:var(--text);border-bottom:1px solid rgba(255,255,255,.04)}
tr:last-child td{border-bottom:none}
tr:hover td{background:rgba(124,58,237,.05)}
.badge{display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;letter-spacing:.03em}
.badge-pending{background:rgba(245,158,11,.15);color:var(--yellow);border:1px solid rgba(245,158,11,.3)}
.badge-approved{background:rgba(16,185,129,.15);color:var(--green);border:1px solid rgba(16,185,129,.3)}
.badge-rejected{background:rgba(239,68,68,.15);color:var(--red);border:1px solid rgba(239,68,68,.3)}
.badge-scheduled{background:rgba(200,77,223,.15);color:#818cf8;border:1px solid rgba(200,77,223,.3)}
.actions{display:flex;align-items:center;gap:6px}
.name-cell{display:flex;align-items:center;gap:10px}
.av-sm{width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;flex-shrink:0}

/* ── MODAL ── */
.modal-bg{position:fixed;inset:0;background:rgba(0,0,0,.75);z-index:100;display:flex;align-items:center;justify-content:center;opacity:0;pointer-events:none;transition:opacity .25s}
.modal-bg.open{opacity:1;pointer-events:all}
.modal{background:var(--card);border:1px solid var(--border);border-radius:16px;width:100%;max-width:680px;max-height:90vh;overflow-y:auto;transform:scale(.95);transition:transform .25s}
.modal-bg.open .modal{transform:scale(1)}
.modal-header{padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
.modal-header h3{font-family:'Space Grotesk',sans-serif;font-size:17px;font-weight:600;color:#fff}
.modal-body{padding:1.5rem}
.modal-footer{padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:10px}
.close-btn{background:none;border:none;color:var(--muted);font-size:20px;cursor:pointer;line-height:1}
.close-btn:hover{color:var(--text)}
.detail-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.detail-item label{font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.05em;text-transform:uppercase;margin-bottom:4px;display:block}
.detail-item p{font-size:13px;color:var(--text)}
.detail-full{grid-column:1/-1}
.section-divider{grid-column:1/-1;border:none;border-top:1px solid var(--border);margin:4px 0}
.modal-section-title{grid-column:1/-1;font-size:11px;font-weight:800;color:var(--accent3);letter-spacing:.08em;text-transform:uppercase}

/* ── FORM FIELDS ── */
.form-group{display:flex;flex-direction:column;gap:5px;margin-bottom:14px}
.form-group label{font-size:12px;font-weight:700;color:var(--muted);letter-spacing:.03em}
.form-group input,.form-group select,.form-group textarea{background:var(--card2);border:1.5px solid var(--border);border-radius:8px;padding:9px 14px;font-size:13px;font-family:'Plus Jakarta Sans',sans-serif;color:var(--text);outline:none;width:100%;transition:border-color .2s}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{border-color:var(--accent)}
.form-group select option{background:#1a1a2e}
.two-col-form{display:grid;grid-template-columns:1fr 1fr;gap:14px}

/* ── SCHEDULE ── */
.schedule-slot{background:var(--card2);border:1px solid var(--border);border-radius:8px;padding:10px 14px;margin-bottom:8px;display:flex;align-items:center;justify-content:space-between}
.schedule-slot .day-tag{font-size:12px;font-weight:700;color:var(--accent3);background:rgba(124,58,237,.15);padding:3px 10px;border-radius:20px}

/* ── TABS ── */
.tabs{display:flex;gap:0;border-bottom:1px solid var(--border)}
.tab{padding:10px 1.25rem;font-size:13px;font-weight:600;color:var(--muted);cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-1px;transition:all .2s}
.tab:hover{color:var(--text)}
.tab.active{color:var(--accent3);border-bottom-color:var(--accent)}

/* ── PAGES ── */
.page{display:none}.page.active{display:block}

/* ── NOTIFICATION ── */
.notif{position:fixed;top:20px;right:20px;z-index:200;background:var(--green);color:#fff;padding:12px 20px;border-radius:10px;font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px;transform:translateX(200px);opacity:0;transition:all .3s}
.notif.show{transform:translateX(0);opacity:1}
.notif.err{background:var(--red)}

/* ── EMPTY STATE ── */
.empty-state{padding:3rem;text-align:center;color:var(--muted)}
.empty-state i{font-size:40px;margin-bottom:12px;opacity:.4}
.empty-state p{font-size:14px}

/* ── STUDENTS PAGE ── */
.student-card{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:1.25rem;display:flex;flex-direction:column;gap:10px}
.student-card-header{display:flex;align-items:center;gap:12px}
.av-lg{width:42px;height:42px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:700;color:#fff;flex-shrink:0}
.progress-bar{background:var(--border);border-radius:4px;height:5px;overflow:hidden}
.progress-bar .fill{height:100%;border-radius:4px;background:linear-gradient(90deg,var(--accent),var(--accent2));transition:width .4s}

/* ── JADWAL PAGE ── */
.schedule-card{background:var(--card);border:1px solid var(--border);border-radius:12px;overflow:hidden}
.week-grid{display:grid;grid-template-columns:80px repeat(7,1fr);gap:1px;background:var(--border)}
.week-cell{background:var(--card);padding:8px;min-height:60px}
.week-header{background:var(--card2);padding:8px;text-align:center;font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em}
.week-event{background:rgba(124,58,237,.2);border:1px solid rgba(124,58,237,.4);border-radius:6px;padding:4px 7px;margin-bottom:3px;font-size:11px;color:var(--accent3);font-weight:600;cursor:pointer}
.week-event:hover{background:rgba(124,58,237,.35)}

/* ── RESPONSIVE ── */
@media(max-width:768px){
  .sidebar{display:none}
  .stats-grid{grid-template-columns:1fr 1fr}
  .two-col-form{grid-template-columns:1fr}
  .detail-grid{grid-template-columns:1fr}
}
</style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="badge"><i class="ti ti-school"></i> Ayo Kursus</div>
    <h2>Admin <span>Panel</span></h2>
    <p>Manajemen Siswa & Jadwal</p>
  </div>
  <div class="nav-section">Menu Utama</div>
  <div class="nav-item active" onclick="switchPage('dashboard')"><i class="ti ti-layout-dashboard"></i> Dashboard</div>
  <div class="nav-item" onclick="switchPage('pendaftar')"><i class="ti ti-clipboard-list"></i> Pendaftar</div>
  <div class="nav-item" onclick="switchPage('siswa')"><i class="ti ti-users"></i> Daftar Siswa</div>
  <div class="nav-section">Jadwal</div>
  <div class="nav-item" onclick="switchPage('jadwal')"><i class="ti ti-calendar"></i> Jadwal Belajar</div>
  <div class="nav-item" onclick="switchPage('guru')"><i class="ti ti-chalkboard"></i> Data Guru</div>
  <div class="nav-section">Lainnya</div>
  <div class="nav-item" onclick="switchPage('form')"><i class="ti ti-forms"></i> Formulir Daftar</div>
  <div class="nav-item" onclick="switchPage('laporan')"><i class="ti ti-chart-bar"></i> Laporan</div>
  <div class="sidebar-bottom">
    <div class="user-chip">
      <div class="avatar">AD</div>
      <div class="info"><p>Admin</p><span>Superadmin</span></div>
    </div>
  </div>
</aside>

<!-- MAIN -->
<main class="main">
  <div class="topbar">
    <div class="topbar-title" id="pageTitle">Dashboard</div>
    <div class="topbar-right">
      <button class="btn btn-ghost btn-sm" onclick="seedDummyData()"><i class="ti ti-database"></i> Isi Data Demo</button>
      <button class="btn btn-primary btn-sm" onclick="openAddModal()"><i class="ti ti-plus"></i> Tambah Siswa</button>
    </div>
  </div>

  <!-- DASHBOARD PAGE -->
  <div id="page-dashboard" class="page active content">
    <div class="stats-grid">
      <div class="stat-card p"><div class="icon-wrap"><i class="ti ti-users"></i></div><div class="num" id="statTotal">0</div><div class="lbl">Total Pendaftar</div></div>
      <div class="stat-card a"><div class="icon-wrap"><i class="ti ti-check"></i></div><div class="num" id="statApproved">0</div><div class="lbl">Disetujui</div></div>
      <div class="stat-card w"><div class="icon-wrap"><i class="ti ti-clock"></i></div><div class="num" id="statPending">0</div><div class="lbl">Menunggu</div></div>
      <div class="stat-card r"><div class="icon-wrap"><i class="ti ti-x"></i></div><div class="num" id="statRejected">0</div><div class="lbl">Ditolak</div></div>
    </div>

    <div class="section-card">
      <div class="section-card-header">
        <h3><i class="ti ti-clock-hour-4"></i> Pendaftar Terbaru</h3>
        <button class="btn btn-ghost btn-sm" onclick="switchPage('pendaftar')">Lihat Semua <i class="ti ti-arrow-right"></i></button>
      </div>
      <div class="table-wrap"><table id="tblRecent">
        <thead><tr><th>Nama</th><th>Program</th><th>Cabang</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody id="tbodyRecent"></tbody>
      </table></div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
      <div class="section-card">
        <div class="section-card-header"><h3><i class="ti ti-chart-pie"></i> Program Terpopuler</h3></div>
        <div style="padding:1rem" id="programStats"></div>
      </div>
      <div class="section-card">
        <div class="section-card-header"><h3><i class="ti ti-map-pin"></i> Top Cabang</h3></div>
        <div style="padding:1rem" id="cabangStats"></div>
      </div>
    </div>
  </div>

  <!-- PENDAFTAR PAGE -->
  <div id="page-pendaftar" class="page content">
    <div class="section-card">
      <div class="section-card-header">
        <h3><i class="ti ti-clipboard-list"></i> Data Pendaftar</h3>
        <div class="section-tools">
          <div class="search-box"><i class="ti ti-search"></i><input type="text" id="searchPendaftar" placeholder="Cari nama..." oninput="renderPendaftar()"></div>
          <select class="filter-select" id="filterStatus" onchange="renderPendaftar()">
            <option value="">Semua Status</option>
            <option value="Pending">Pending</option>
            <option value="Approved">Approved</option>
            <option value="Rejected">Rejected</option>
          </select>
          <select class="filter-select" id="filterCabang" onchange="renderPendaftar()">
            <option value="">Semua Cabang</option>
          </select>
        </div>
      </div>
      <div class="table-wrap"><table>
        <thead><tr><th>No Reg</th><th>Nama</th><th>Program</th><th>Minat</th><th>Cabang</th><th>Tgl Daftar</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody id="tbodyPendaftar"></tbody>
      </table></div>
    </div>
  </div>

  <!-- SISWA PAGE -->
  <div id="page-siswa" class="page content">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px">
      <div style="display:flex;gap:8px">
        <div class="search-box" style="background:var(--card);border-color:var(--border)"><i class="ti ti-search"></i><input type="text" id="searchSiswa" placeholder="Cari siswa..." oninput="renderSiswa()"></div>
        <select class="filter-select" id="filterJenjang" onchange="renderSiswa()">
          <option value="">Semua Jenjang</option>
          <option>TK</option><option>SD</option><option>SMP</option><option>SMA</option><option>Umum</option>
        </select>
      </div>
    </div>
    <div id="siswaGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:14px;margin-top:14px"></div>
  </div>

  <!-- JADWAL PAGE -->
  <div id="page-jadwal" class="page content">
    <div class="section-card" style="margin-bottom:14px">
      <div class="section-card-header">
        <h3><i class="ti ti-calendar-event"></i> Atur Jadwal Siswa</h3>
        <button class="btn btn-primary btn-sm" onclick="openJadwalModal()"><i class="ti ti-plus"></i> Tambah Jadwal</button>
      </div>
      <div class="table-wrap"><table>
        <thead><tr><th>Siswa</th><th>Program</th><th>Hari</th><th>Jam</th><th>Guru</th><th>Lokasi</th><th>Aksi</th></tr></thead>
        <tbody id="tbodyJadwal"></tbody>
      </table></div>
    </div>
  </div>

  <!-- GURU PAGE -->
  <div id="page-guru" class="page content">
    <div class="section-card">
      <div class="section-card-header">
        <h3><i class="ti ti-chalkboard"></i> Data Guru</h3>
        <button class="btn btn-primary btn-sm" onclick="openGuruModal()"><i class="ti ti-plus"></i> Tambah Guru</button>
      </div>
      <div class="table-wrap"><table>
        <thead><tr><th>Nama Guru</th><th>Bidang</th><th>HP</th><th>Status</th><th>Jadwal Aktif</th><th>Aksi</th></tr></thead>
        <tbody id="tbodyGuru"></tbody>
      </table></div>
    </div>
  </div>

  <!-- FORM PAGE -->
  <div id="page-form" class="page">
    <div style="padding:1.5rem">
      <div style="background:var(--card);border:1px solid var(--border);border-radius:12px;padding:1.25rem;margin-bottom:14px">
        <p style="font-size:13px;color:var(--muted)">Formulir pendaftaran siswa baru. Data yang diisi akan masuk ke sistem sebagai <strong style="color:var(--accent3)">Pending</strong> dan perlu disetujui admin.</p>
      </div>
      <iframe id="formFrame" src="about:blank" style="width:100%;min-height:800px;border:none;border-radius:12px;overflow:hidden"></iframe>
    </div>
  </div>

  <!-- LAPORAN PAGE -->
  <div id="page-laporan" class="page content">
    <div class="section-card">
      <div class="section-card-header"><h3><i class="ti ti-chart-bar"></i> Rekap Laporan</h3></div>
      <div style="padding:1.5rem" id="laporanContent"></div>
    </div>
  </div>
</main>

<!-- MODALS -->
<!-- Detail/Approve Modal -->
<div class="modal-bg" id="modalDetail">
  <div class="modal">
    <div class="modal-header">
      <h3 id="modalDetailTitle">Detail Pendaftar</h3>
      <button class="close-btn" onclick="closeModal('modalDetail')"><i class="ti ti-x"></i></button>
    </div>
    <div class="modal-body" id="modalDetailBody"></div>
    <div class="modal-footer" id="modalDetailFooter"></div>
  </div>
</div>

<!-- Add/Edit Siswa Modal -->
<div class="modal-bg" id="modalSiswa">
  <div class="modal">
    <div class="modal-header">
      <h3 id="modalSiswaTitle">Tambah Siswa</h3>
      <button class="close-btn" onclick="closeModal('modalSiswa')"><i class="ti ti-x"></i></button>
    </div>
    <div class="modal-body">
      <div class="two-col-form">
        <div class="form-group"><label>Nama Lengkap *</label><input id="f_nama" type="text" placeholder="Nama siswa"></div>
        <div class="form-group"><label>No WA/HP *</label><input id="f_hp" type="tel" placeholder="08xx-xxxx-xxxx"></div>
        <div class="form-group"><label>Jenis Kelamin</label>
          <select id="f_jk"><option value="">Pilih</option><option>Laki-laki</option><option>Perempuan</option></select>
        </div>
        <div class="form-group"><label>Jenis Peserta</label>
          <select id="f_jenis"><option>Pelajar</option><option>Umum</option></select>
        </div>
        <div class="form-group"><label>Jenjang</label>
          <select id="f_jenjang"><option value="">-</option><option>TK</option><option>SD</option><option>SMP</option><option>SMA</option></select>
        </div>
        <div class="form-group"><label>Kelas</label><input id="f_kelas" type="text" placeholder="Contoh: Kelas 10"></div>
        <div class="form-group"><label>Sekolah</label><input id="f_sekolah" type="text" placeholder="Nama sekolah"></div>
        <div class="form-group"><label>Cabang</label>
          <select id="f_cabang">
            <option>Pekanbaru</option><option>Jakarta Selatan</option><option>Jakarta Barat</option><option>Jakarta Timur</option>
            <option>Bandung</option><option>Surabaya</option><option>Medan</option><option>Yogyakarta</option>
            <option>Semarang</option><option>Batam</option><option>Palembang</option><option>Makassar</option>
          </select>
        </div>
        <div class="form-group"><label>Program</label>
          <select id="f_program"><option>Kelas</option><option>Privat</option></select>
        </div>
        <div class="form-group"><label>Sistem</label>
          <select id="f_sistem"><option>Offline</option><option>Online</option></select>
        </div>
      </div>
      <div class="form-group"><label>Program Diminati</label><input id="f_minat" type="text" placeholder="Contoh: Matematika, Bahasa Inggris"></div>
      <div class="form-group"><label>Nama Orang Tua</label><input id="f_ortu" type="text" placeholder="Nama orang tua/wali"></div>
      <div class="form-group"><label>HP Orang Tua</label><input id="f_hp_ortu" type="tel" placeholder="08xx-xxxx-xxxx"></div>
      <div class="form-group"><label>Alamat</label><textarea id="f_alamat" rows="2" placeholder="Alamat lengkap"></textarea></div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('modalSiswa')">Batal</button>
      <button class="btn btn-primary" onclick="saveSiswa()"><i class="ti ti-device-floppy"></i> Simpan</button>
    </div>
  </div>
</div>

<!-- Jadwal Modal -->
<div class="modal-bg" id="modalJadwal">
  <div class="modal" style="max-width:500px">
    <div class="modal-header">
      <h3 id="modalJadwalTitle">Tambah Jadwal</h3>
      <button class="close-btn" onclick="closeModal('modalJadwal')"><i class="ti ti-x"></i></button>
    </div>
    <div class="modal-body">
      <div class="form-group"><label>Pilih Siswa *</label>
        <select id="j_siswa"></select>
      </div>
      <div class="two-col-form">
        <div class="form-group"><label>Hari *</label>
          <select id="j_hari">
            <option>Senin</option><option>Selasa</option><option>Rabu</option><option>Kamis</option>
            <option>Jumat</option><option>Sabtu</option><option>Minggu</option>
          </select>
        </div>
        <div class="form-group"><label>Jam Mulai *</label>
          <input id="j_jam" type="time" value="08:00">
        </div>
        <div class="form-group"><label>Durasi (menit)</label>
          <select id="j_durasi"><option>60</option><option>90</option><option>120</option></select>
        </div>
        <div class="form-group"><label>Guru *</label>
          <select id="j_guru"></select>
        </div>
      </div>
      <div class="form-group"><label>Mata Pelajaran / Program</label>
        <input id="j_mapel" type="text" placeholder="Contoh: Matematika, Adobe Photoshop">
      </div>
      <div class="form-group"><label>Lokasi / Ruangan</label>
        <input id="j_lokasi" type="text" placeholder="Contoh: Ruang A / Online Zoom">
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('modalJadwal')">Batal</button>
      <button class="btn btn-primary" onclick="saveJadwal()"><i class="ti ti-device-floppy"></i> Simpan Jadwal</button>
    </div>
  </div>
</div>

<!-- Guru Modal -->
<div class="modal-bg" id="modalGuru">
  <div class="modal" style="max-width:480px">
    <div class="modal-header">
      <h3 id="modalGuruTitle">Tambah Guru</h3>
      <button class="close-btn" onclick="closeModal('modalGuru')"><i class="ti ti-x"></i></button>
    </div>
    <div class="modal-body">
      <div class="form-group"><label>Nama Guru *</label><input id="g_nama" type="text" placeholder="Nama lengkap guru"></div>
      <div class="form-group"><label>Bidang / Mata Pelajaran *</label><input id="g_bidang" type="text" placeholder="Contoh: Matematika, Bahasa Inggris"></div>
      <div class="form-group"><label>Nomor HP</label><input id="g_hp" type="tel" placeholder="08xx-xxxx-xxxx"></div>
      <div class="form-group"><label>Status</label>
        <select id="g_status"><option>Aktif</option><option>Tidak Aktif</option></select>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('modalGuru')">Batal</button>
      <button class="btn btn-primary" onclick="saveGuru()"><i class="ti ti-device-floppy"></i> Simpan</button>
    </div>
  </div>
</div>

<!-- NOTIF -->
<div class="notif" id="notif"><i class="ti ti-check"></i><span id="notifMsg"></span></div>

<script>
// ─── DATA STORE ───
const KEYS={pendaftar:'ayokursus_pendaftar',siswa:'ayokursus_siswa',jadwal:'ayokursus_jadwal',guru:'ayokursus_guru'};
const get=k=>JSON.parse(localStorage.getItem(k)||'[]');
const set=(k,v)=>localStorage.setItem(k,JSON.stringify(v));
let editId=null,editJadwalId=null,editGuruId=null;

// ─── DUMMY DATA ───
function seedDummyData(){
  const names=[['Budi Santoso','Pelajar','SMA','Kelas 12','Pekanbaru'],['Sari Dewi','Pelajar','SMP','Kelas 8','Batam'],
    ['Ahmad Fauzi','Umum','','','Bandung'],['Rina Kusuma','Pelajar','SD','Kelas 5','Pekanbaru'],
    ['Doni Pratama','Pelajar','SMA','Kelas 10','Jakarta Selatan'],['Fitri Amalia','Pelajar','SMP','Kelas 9','Surabaya'],
    ['Hendra Wijaya','Umum','','','Medan'],['Citra Nanda','Pelajar','SMA','Kelas 11','Yogyakarta'],
    ['Reza Firmansyah','Pelajar','SD','Kelas 6','Pekanbaru'],['Maya Sari','Umum','','','Semarang']];
  const programs=['Kelas','Privat'];
  const sistems=['Online','Offline'];
  const minats=[['Matematika','Fisika'],['Bahasa Inggris'],['Adobe Photoshop','Corel Draw'],['Microsoft Office'],
    ['Bahasa Mandarin','Bahasa Jepang'],['PKN STAN','CPNS'],['Programmer/Coding'],['Kimia','Biologi'],
    ['Bahasa Inggris','Matematika'],['Akuntansi/Ekonomi']];
  const statuses=['Pending','Pending','Approved','Approved','Approved','Rejected','Pending','Approved','Pending','Approved'];
  const cabangs=['Pekanbaru','Batam','Bandung','Pekanbaru','Jakarta Selatan','Surabaya','Medan','Yogyakarta','Pekanbaru','Semarang'];
  
  let existing=get(KEYS.pendaftar);
  if(existing.length===0){
    const now=Date.now();
    names.forEach(([nama,jenis,jenjang,kelas,kota],i)=>{
      existing.push({
        id:now+i,noReg:'AK-'+String(100+i),status:statuses[i],
        tglDaftar:new Date(now-i*86400000*2).toISOString(),
        nama,hp:'0812-'+String(34567890+i),jk:i%2===0?'Laki-laki':'Perempuan',
        tempat_lahir:kota,tanggal_lahir:'200'+Math.floor(Math.random()*9)+'-0'+((i%9)+1)+'-15',
        alamat:'Jl. Contoh No. '+String(i+1)+', '+kota,
        jenisPeserta:jenis,jenjang,kelas,sekolah:jenjang?'SMA/SMP/SD Negeri '+String(i+1)+' '+kota:'',
        nama_ortu:'Orang Tua '+nama.split(' ')[0],hp_ortu:'0811-'+String(23456789+i),pekerjaan:['PNS','Wiraswasta','Guru','Dokter','TNI'][i%5],
        program:programs[i%2],sistem:sistems[i%2],tempat:i%2===0?'Kantor':'Rumah',pengambilan:i%2===0?'Paket':'Per Sesi',
        cabang:cabangs[i],minat:minats[i].join(', '),hariPref:'',jamPref:'',tanggalMulai:'',catatan:'',jadwal:[]
      });
    });
    set(KEYS.pendaftar,existing);
  }

  let gurus=get(KEYS.guru);
  if(gurus.length===0){
    [['Bu Ani','Matematika, Fisika','0813-11110001'],
     ['Pak Budi','Bahasa Inggris, Bahasa Arab','0813-11110002'],
     ['Bu Cici','Bahasa Mandarin, Bahasa Jepang','0813-11110003'],
     ['Pak Deni','Programmer/Coding, Microsoft Office','0813-11110004'],
     ['Bu Erni','Adobe Photoshop, Corel Draw, AutoCAD','0813-11110005'],
     ['Pak Fajar','Kimia, Biologi, IPA','0813-11110006']].forEach(([nama,bidang,hp],i)=>{
      gurus.push({id:Date.now()+i,nama,bidang,hp,status:'Aktif'});
    });
    set(KEYS.guru,gurus);
  }

  // Auto-approve some to siswa
  const approved=get(KEYS.pendaftar).filter(p=>p.status==='Approved');
  let siswas=get(KEYS.siswa);
  approved.forEach(p=>{
    if(!siswas.find(s=>s.noReg===p.noReg)){
      siswas.push({...p,tglMasuk:new Date().toISOString()});
    }
  });
  set(KEYS.siswa,siswas);

  showNotif('Data demo berhasil dimuat!');
  renderAll();
}

// ─── PAGE SWITCH ───
function switchPage(id){
  document.querySelectorAll('.page').forEach(p=>p.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(n=>n.classList.remove('active'));
  document.getElementById('page-'+id).classList.add('active');
  document.querySelectorAll('.nav-item').forEach(n=>{ if(n.textContent.toLowerCase().includes(id.substring(0,4))) n.classList.add('active'); });
  const titles={dashboard:'Dashboard',pendaftar:'Data Pendaftar',siswa:'Daftar Siswa',jadwal:'Jadwal Belajar',guru:'Data Guru',form:'Formulir Pendaftaran',laporan:'Laporan'};
  document.getElementById('pageTitle').textContent=titles[id]||id;
  if(id==='pendaftar') renderPendaftar();
  if(id==='siswa') renderSiswa();
  if(id==='jadwal') renderJadwal();
  if(id==='guru') renderGuru();
  if(id==='laporan') renderLaporan();
  if(id==='dashboard') renderDashboard();
}

// ─── HELPERS ───
function fmtDate(iso){if(!iso)return'-';const d=new Date(iso);return d.toLocaleDateString('id-ID',{day:'numeric',month:'short',year:'numeric'});}
function initials(name){return name.split(' ').slice(0,2).map(w=>w[0]||'').join('').toUpperCase();}
function badgeStatus(s){const m={'Pending':'badge-pending','Approved':'badge-approved','Rejected':'badge-rejected','Scheduled':'badge-scheduled'};return`<span class="badge ${m[s]||'badge-pending'}">${s}</span>`;}
const colors=['#c84ddf','#c84ddf','#10b981','#c84ddf','#f6af23','#ef4444','#06b6d4','#ec4899'];
function avColor(name){return colors[name.charCodeAt(0)%colors.length];}
function showNotif(msg,isErr=false){const n=document.getElementById('notif');document.getElementById('notifMsg').textContent=msg;n.className='notif'+(isErr?' err':'');void n.offsetWidth;n.classList.add('show');setTimeout(()=>n.classList.remove('show'),3000);}
function closeModal(id){document.getElementById(id).classList.remove('open');}

// ─── DASHBOARD ───
function renderDashboard(){
  const all=get(KEYS.pendaftar);
  document.getElementById('statTotal').textContent=all.length;
  document.getElementById('statApproved').textContent=all.filter(x=>x.status==='Approved').length;
  document.getElementById('statPending').textContent=all.filter(x=>x.status==='Pending').length;
  document.getElementById('statRejected').textContent=all.filter(x=>x.status==='Rejected').length;

  const recent=all.slice(-5).reverse();
  const tbody=document.getElementById('tbodyRecent');
  tbody.innerHTML=recent.length?recent.map(r=>`<tr>
    <td><div class="name-cell"><div class="av-sm" style="background:${avColor(r.nama)}">${initials(r.nama)}</div>${r.nama}</div></td>
    <td>${r.program||'-'}</td><td>${r.cabang||'-'}</td><td>${fmtDate(r.tglDaftar)}</td>
    <td>${badgeStatus(r.status)}</td>
    <td><button class="btn btn-ghost btn-xs" onclick="openDetail(${r.id})"><i class="ti ti-eye"></i></button></td>
  </tr>`).join(''):`<tr><td colspan="6"><div class="empty-state"><i class="ti ti-inbox"></i><p>Belum ada data</p></div></td></tr>`;

  // Program stats
  const minatCount={};
  all.forEach(r=>{r.minat&&r.minat.split(',').forEach(m=>{const k=m.trim();if(k)minatCount[k]=(minatCount[k]||0)+1;});});
  const topMinat=Object.entries(minatCount).sort((a,b)=>b[1]-a[1]).slice(0,5);
  const maxM=topMinat[0]?.[1]||1;
  document.getElementById('programStats').innerHTML=topMinat.map(([k,v])=>`
    <div style="margin-bottom:12px">
      <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px">
        <span style="color:var(--text)">${k}</span><span style="color:var(--muted)">${v}</span>
      </div>
      <div class="progress-bar"><div class="fill" style="width:${(v/maxM*100).toFixed(0)}%"></div></div>
    </div>`).join('')||'<p style="color:var(--muted);font-size:13px">Belum ada data</p>';

  // Cabang stats
  const cabCount={};
  all.forEach(r=>{if(r.cabang)cabCount[r.cabang]=(cabCount[r.cabang]||0)+1;});
  const topCab=Object.entries(cabCount).sort((a,b)=>b[1]-a[1]).slice(0,5);
  const maxC=topCab[0]?.[1]||1;
  document.getElementById('cabangStats').innerHTML=topCab.map(([k,v])=>`
    <div style="margin-bottom:12px">
      <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px">
        <span style="color:var(--text)">${k}</span><span style="color:var(--muted)">${v}</span>
      </div>
      <div class="progress-bar"><div class="fill" style="width:${(v/maxC*100).toFixed(0)}%;background:linear-gradient(90deg,#10b981,#c84ddf)"></div></div>
    </div>`).join('')||'<p style="color:var(--muted);font-size:13px">Belum ada data</p>';
}

// ─── PENDAFTAR ───
function renderPendaftar(){
  const all=get(KEYS.pendaftar);
  const q=document.getElementById('searchPendaftar').value.toLowerCase();
  const st=document.getElementById('filterStatus').value;
  const cb=document.getElementById('filterCabang').value;
  // populate cabang filter
  const cabSet=[...new Set(all.map(x=>x.cabang).filter(Boolean))];
  const fCab=document.getElementById('filterCabang');
  const cur=fCab.value;
  fCab.innerHTML='<option value="">Semua Cabang</option>'+cabSet.map(c=>`<option ${c===cur?'selected':''}>${c}</option>`).join('');
  const filtered=all.filter(r=>{
    const matchQ=!q||r.nama.toLowerCase().includes(q)||r.noReg.toLowerCase().includes(q);
    const matchSt=!st||r.status===st;
    const matchCb=!cb||r.cabang===cb;
    return matchQ&&matchSt&&matchCb;
  }).reverse();
  document.getElementById('tbodyPendaftar').innerHTML=filtered.length?filtered.map(r=>`<tr>
    <td style="font-family:'Space Grotesk',sans-serif;font-size:12px;color:var(--accent3)">${r.noReg}</td>
    <td><div class="name-cell"><div class="av-sm" style="background:${avColor(r.nama)}">${initials(r.nama)}</div>
      <div><div>${r.nama}</div><div style="font-size:11px;color:var(--muted)">${r.jenisPeserta||''} ${r.jenjang?'· '+r.jenjang:''}</div></div>
    </div></td>
    <td>${r.program||'-'}</td>
    <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:12px;color:var(--muted)" title="${r.minat}">${r.minat||'-'}</td>
    <td>${r.cabang||'-'}</td>
    <td style="font-size:12px;color:var(--muted)">${fmtDate(r.tglDaftar)}</td>
    <td>${badgeStatus(r.status)}</td>
    <td><div class="actions">
      <button class="btn btn-ghost btn-xs" onclick="openDetail(${r.id})" title="Detail"><i class="ti ti-eye"></i></button>
      ${r.status==='Pending'?`<button class="btn btn-success btn-xs" onclick="setStatus(${r.id},'Approved')" title="Setujui"><i class="ti ti-check"></i></button>
      <button class="btn btn-danger btn-xs" onclick="setStatus(${r.id},'Rejected')" title="Tolak"><i class="ti ti-x"></i></button>`:''}
      <button class="btn btn-danger btn-xs" onclick="deletePendaftar(${r.id})" title="Hapus"><i class="ti ti-trash"></i></button>
    </div></td>
  </tr>`).join(''):`<tr><td colspan="8"><div class="empty-state"><i class="ti ti-clipboard-x"></i><p>Tidak ada data ditemukan</p></div></td></tr>`;
}

function setStatus(id,status){
  const all=get(KEYS.pendaftar);
  const idx=all.findIndex(x=>x.id===id);
  if(idx<0)return;
  all[idx].status=status;
  set(KEYS.pendaftar,all);
  if(status==='Approved'){
    const siswas=get(KEYS.siswa);
    if(!siswas.find(s=>s.noReg===all[idx].noReg)){
      siswas.push({...all[idx],tglMasuk:new Date().toISOString()});
      set(KEYS.siswa,siswas);
    }
  }
  showNotif(status==='Approved'?'Pendaftar disetujui!':'Pendaftar ditolak.');
  renderPendaftar();renderDashboard();
}

function deletePendaftar(id){
  if(!confirm('Hapus pendaftar ini?'))return;
  set(KEYS.pendaftar,get(KEYS.pendaftar).filter(x=>x.id!==id));
  showNotif('Data dihapus.');renderPendaftar();renderDashboard();
}

function openDetail(id){
  const r=get(KEYS.pendaftar).find(x=>x.id===id);if(!r)return;
  document.getElementById('modalDetailTitle').textContent='Detail Pendaftar – '+r.nama;
  document.getElementById('modalDetailBody').innerHTML=`
    <div class="detail-grid">
      <div class="modal-section-title">Data Siswa</div>
      <div class="detail-item"><label>No Reg</label><p style="color:var(--accent3);font-family:'Space Grotesk',sans-serif">${r.noReg}</p></div>
      <div class="detail-item"><label>Status</label><p>${badgeStatus(r.status)}</p></div>
      <div class="detail-item"><label>Nama Lengkap</label><p>${r.nama}</p></div>
      <div class="detail-item"><label>No WA/HP</label><p>${r.hp}</p></div>
      <div class="detail-item"><label>Jenis Kelamin</label><p>${r.jk||'-'}</p></div>
      <div class="detail-item"><label>Tanggal Lahir</label><p>${r.tempat_lahir?r.tempat_lahir+', ':''} ${r.tanggal_lahir||'-'}</p></div>
      <div class="detail-item detail-full"><label>Alamat</label><p>${r.alamat||'-'}</p></div>
      <div class="detail-item"><label>Jenis Peserta</label><p>${r.jenisPeserta||'-'}</p></div>
      <div class="detail-item"><label>Jenjang / Kelas</label><p>${r.jenjang||'-'} ${r.kelas?'/ '+r.kelas:''}</p></div>
      <div class="detail-item detail-full"><label>Sekolah</label><p>${r.sekolah||'-'}</p></div>
      <hr class="section-divider">
      <div class="modal-section-title">Data Orang Tua</div>
      <div class="detail-item"><label>Nama Orang Tua</label><p>${r.nama_ortu||'-'}</p></div>
      <div class="detail-item"><label>HP Orang Tua</label><p>${r.hp_ortu||'-'}</p></div>
      <div class="detail-item detail-full"><label>Pekerjaan</label><p>${r.pekerjaan||'-'}</p></div>
      <hr class="section-divider">
      <div class="modal-section-title">Program Belajar</div>
      <div class="detail-item"><label>Program</label><p>${r.program||'-'}</p></div>
      <div class="detail-item"><label>Sistem</label><p>${r.sistem||'-'}</p></div>
      <div class="detail-item"><label>Tempat</label><p>${r.tempat||'-'}</p></div>
      <div class="detail-item"><label>Cabang</label><p>${r.cabang||'-'}</p></div>
      <div class="detail-item detail-full"><label>Program Diminati</label><p>${r.minat||'-'}</p></div>
      <div class="detail-item"><label>Tanggal Daftar</label><p>${fmtDate(r.tglDaftar)}</p></div>
    </div>`;
  document.getElementById('modalDetailFooter').innerHTML=`
    ${r.status==='Pending'?`<button class="btn btn-success" onclick="setStatus(${r.id},'Approved');closeModal('modalDetail')"><i class="ti ti-check"></i> Setujui</button>
    <button class="btn btn-danger" onclick="setStatus(${r.id},'Rejected');closeModal('modalDetail')"><i class="ti ti-x"></i> Tolak</button>`:''}
    <button class="btn btn-ghost" onclick="closeModal('modalDetail')">Tutup</button>`;
  document.getElementById('modalDetail').classList.add('open');
}

// ─── SISWA ───
function renderSiswa(){
  const all=get(KEYS.siswa);
  const q=document.getElementById('searchSiswa').value.toLowerCase();
  const fj=document.getElementById('filterJenjang').value;
  const filtered=all.filter(r=>{
    const mq=!q||r.nama.toLowerCase().includes(q);
    const mj=!fj||(fj==='Umum'?r.jenisPeserta==='Umum':r.jenjang===fj);
    return mq&&mj;
  });
  const jadwals=get(KEYS.jadwal);
  document.getElementById('siswaGrid').innerHTML=filtered.length?filtered.map(r=>{
    const siswaJadwal=jadwals.filter(j=>j.siswaId===r.id||j.siswaId===r.noReg);
    return`<div class="student-card">
      <div class="student-card-header">
        <div class="av-lg" style="background:${avColor(r.nama)}">${initials(r.nama)}</div>
        <div style="flex:1;min-width:0">
          <div style="font-weight:700;font-size:14px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${r.nama}</div>
          <div style="font-size:11px;color:var(--muted)">${r.jenjang||r.jenisPeserta||'-'} ${r.kelas?'· '+r.kelas:''}</div>
          <div style="font-size:11px;color:var(--accent3);font-family:'Space Grotesk',sans-serif">${r.noReg}</div>
        </div>
        <div class="actions">
          <button class="btn btn-ghost btn-xs" onclick="openEditSiswa('${r.noReg}')" title="Edit"><i class="ti ti-edit"></i></button>
          <button class="btn btn-danger btn-xs" onclick="deleteSiswa('${r.noReg}')" title="Hapus"><i class="ti ti-trash"></i></button>
        </div>
      </div>
      <div style="display:flex;gap:8px;flex-wrap:wrap">
        <span style="font-size:11px;background:rgba(124,58,237,.15);color:var(--accent3);padding:2px 8px;border-radius:20px">${r.cabang||'-'}</span>
        <span style="font-size:11px;background:rgba(16,185,129,.1);color:var(--green);padding:2px 8px;border-radius:20px">${r.program||'Kelas'}</span>
        <span style="font-size:11px;background:rgba(255,255,255,.06);color:var(--muted);padding:2px 8px;border-radius:20px">${r.sistem||'-'}</span>
      </div>
      <div style="font-size:12px;color:var(--muted)">
        <i class="ti ti-star" style="font-size:13px;color:var(--yellow)"></i> ${r.minat||'Belum dipilih'}
      </div>
      <div style="font-size:12px;color:var(--muted)">
        <i class="ti ti-calendar" style="font-size:13px;color:var(--blue)"></i> ${siswaJadwal.length} jadwal aktif
        <button class="btn btn-ghost btn-xs" style="margin-left:6px" onclick="openJadwalForSiswa('${r.noReg}')"><i class="ti ti-plus"></i> Jadwal</button>
      </div>
    </div>`}).join(''):`<div style="grid-column:1/-1"><div class="empty-state"><i class="ti ti-users-group"></i><p>Belum ada siswa terdaftar</p></div></div>`;
}

function deleteSiswa(noReg){
  if(!confirm('Hapus siswa ini?'))return;
  set(KEYS.siswa,get(KEYS.siswa).filter(x=>x.noReg!==noReg));
  showNotif('Siswa dihapus.');renderSiswa();
}

function openEditSiswa(noReg){
  const r=get(KEYS.siswa).find(x=>x.noReg===noReg);if(!r)return;
  editId=r.id;
  document.getElementById('modalSiswaTitle').textContent='Edit Siswa';
  document.getElementById('f_nama').value=r.nama||'';
  document.getElementById('f_hp').value=r.hp||'';
  document.getElementById('f_jk').value=r.jk||'';
  document.getElementById('f_jenis').value=r.jenisPeserta||'Pelajar';
  document.getElementById('f_jenjang').value=r.jenjang||'';
  document.getElementById('f_kelas').value=r.kelas||'';
  document.getElementById('f_sekolah').value=r.sekolah||'';
  document.getElementById('f_cabang').value=r.cabang||'Pekanbaru';
  document.getElementById('f_program').value=r.program||'Kelas';
  document.getElementById('f_sistem').value=r.sistem||'Offline';
  document.getElementById('f_minat').value=r.minat||'';
  document.getElementById('f_ortu').value=r.nama_ortu||'';
  document.getElementById('f_hp_ortu').value=r.hp_ortu||'';
  document.getElementById('f_alamat').value=r.alamat||'';
  document.getElementById('modalSiswa').classList.add('open');
}

function openAddModal(){
  editId=null;
  document.getElementById('modalSiswaTitle').textContent='Tambah Siswa';
  document.getElementById('modalSiswa').querySelectorAll('input,select,textarea').forEach(el=>el.value='');
  document.getElementById('modalSiswa').classList.add('open');
}

function saveSiswa(){
  const nama=document.getElementById('f_nama').value.trim();
  const hp=document.getElementById('f_hp').value.trim();
  if(!nama||!hp){showNotif('Nama dan HP wajib diisi!',true);return;}
  const siswas=get(KEYS.siswa);
  if(editId){
    const idx=siswas.findIndex(x=>x.id===editId);
    if(idx>=0){
      Object.assign(siswas[idx],{nama,hp,jk:document.getElementById('f_jk').value,
        jenisPeserta:document.getElementById('f_jenis').value,jenjang:document.getElementById('f_jenjang').value,
        kelas:document.getElementById('f_kelas').value,sekolah:document.getElementById('f_sekolah').value,
        cabang:document.getElementById('f_cabang').value,program:document.getElementById('f_program').value,
        sistem:document.getElementById('f_sistem').value,minat:document.getElementById('f_minat').value,
        nama_ortu:document.getElementById('f_ortu').value,hp_ortu:document.getElementById('f_hp_ortu').value,
        alamat:document.getElementById('f_alamat').value});
    }
  } else {
    const noReg='AK-'+Date.now().toString().slice(-6);
    siswas.push({id:Date.now(),noReg,status:'Approved',tglDaftar:new Date().toISOString(),tglMasuk:new Date().toISOString(),
      nama,hp,jk:document.getElementById('f_jk').value,
      jenisPeserta:document.getElementById('f_jenis').value,jenjang:document.getElementById('f_jenjang').value,
      kelas:document.getElementById('f_kelas').value,sekolah:document.getElementById('f_sekolah').value,
      cabang:document.getElementById('f_cabang').value,program:document.getElementById('f_program').value,
      sistem:document.getElementById('f_sistem').value,minat:document.getElementById('f_minat').value,
      nama_ortu:document.getElementById('f_ortu').value,hp_ortu:document.getElementById('f_hp_ortu').value,
      alamat:document.getElementById('f_alamat').value,jadwal:[]});
  }
  set(KEYS.siswa,siswas);
  closeModal('modalSiswa');
  showNotif(editId?'Siswa diperbarui!':'Siswa berhasil ditambah!');
  editId=null;renderSiswa();renderDashboard();
}

// ─── JADWAL ───
function renderJadwal(){
  const all=get(KEYS.jadwal);
  const siswas=get(KEYS.siswa);
  document.getElementById('tbodyJadwal').innerHTML=all.length?all.map(j=>{
    const s=siswas.find(x=>x.id===j.siswaId||x.noReg===j.siswaId);
    return`<tr>
      <td><div class="name-cell"><div class="av-sm" style="background:${s?avColor(s.nama):'var(--accent)'}">${s?initials(s.nama):'?'}</div>
        <div><div>${s?s.nama:'Tidak dikenal'}</div><div style="font-size:11px;color:var(--muted)">${s?s.noReg:''}</div></div>
      </div></td>
      <td style="font-size:12px">${j.mapel||'-'}</td>
      <td><span class="day-tag">${j.hari}</span></td>
      <td style="font-size:13px;font-family:'Space Grotesk',sans-serif;color:var(--accent3)">${j.jam} <span style="font-size:11px;color:var(--muted)">(${j.durasi} mnt)</span></td>
      <td>${j.guru||'-'}</td>
      <td style="font-size:12px;color:var(--muted)">${j.lokasi||'-'}</td>
      <td><div class="actions">
        <button class="btn btn-ghost btn-xs" onclick="editJadwal(${j.id})" title="Edit"><i class="ti ti-edit"></i></button>
        <button class="btn btn-danger btn-xs" onclick="deleteJadwal(${j.id})" title="Hapus"><i class="ti ti-trash"></i></button>
      </div></td>
    </tr>`}).join(''):`<tr><td colspan="7"><div class="empty-state"><i class="ti ti-calendar-off"></i><p>Belum ada jadwal</p></div></td></tr>`;
}

function populateJadwalForm(siswaId=''){
  const siswas=get(KEYS.siswa);
  const gurus=get(KEYS.guru);
  document.getElementById('j_siswa').innerHTML=siswas.map(s=>`<option value="${s.noReg}" ${s.noReg===siswaId?'selected':''}>${s.nama} (${s.noReg})</option>`).join('');
  document.getElementById('j_guru').innerHTML='<option value="">-- Pilih Guru --</option>'+gurus.filter(g=>g.status==='Aktif').map(g=>`<option>${g.nama} – ${g.bidang}</option>`).join('');
}

function openJadwalModal(){
  editJadwalId=null;
  document.getElementById('modalJadwalTitle').textContent='Tambah Jadwal';
  populateJadwalForm();
  document.getElementById('j_hari').value='Senin';
  document.getElementById('j_jam').value='08:00';
  document.getElementById('j_durasi').value='60';
  document.getElementById('j_mapel').value='';
  document.getElementById('j_lokasi').value='';
  document.getElementById('modalJadwal').classList.add('open');
}

function openJadwalForSiswa(noReg){
  editJadwalId=null;
  document.getElementById('modalJadwalTitle').textContent='Tambah Jadwal';
  populateJadwalForm(noReg);
  document.getElementById('modalJadwal').classList.add('open');
  switchPage('jadwal');
  setTimeout(()=>document.getElementById('modalJadwal').classList.add('open'),100);
}

function editJadwal(id){
  const j=get(KEYS.jadwal).find(x=>x.id===id);if(!j)return;
  editJadwalId=id;
  populateJadwalForm(j.siswaId);
  document.getElementById('modalJadwalTitle').textContent='Edit Jadwal';
  document.getElementById('j_hari').value=j.hari;
  document.getElementById('j_jam').value=j.jam;
  document.getElementById('j_durasi').value=j.durasi||'60';
  document.getElementById('j_mapel').value=j.mapel||'';
  document.getElementById('j_lokasi').value=j.lokasi||'';
  document.getElementById('j_guru').value=j.guru||'';
  document.getElementById('modalJadwal').classList.add('open');
}

function saveJadwal(){
  const siswaId=document.getElementById('j_siswa').value;
  const hari=document.getElementById('j_hari').value;
  const jam=document.getElementById('j_jam').value;
  if(!siswaId||!hari||!jam){showNotif('Lengkapi data jadwal!',true);return;}
  const all=get(KEYS.jadwal);
  const obj={siswaId,hari,jam,durasi:document.getElementById('j_durasi').value,
    guru:document.getElementById('j_guru').value,mapel:document.getElementById('j_mapel').value,
    lokasi:document.getElementById('j_lokasi').value};
  if(editJadwalId){
    const idx=all.findIndex(x=>x.id===editJadwalId);
    if(idx>=0)Object.assign(all[idx],obj);
  } else {
    all.push({id:Date.now(),...obj});
  }
  set(KEYS.jadwal,all);
  closeModal('modalJadwal');
  showNotif(editJadwalId?'Jadwal diperbarui!':'Jadwal ditambahkan!');
  editJadwalId=null;renderJadwal();
}

function deleteJadwal(id){
  if(!confirm('Hapus jadwal ini?'))return;
  set(KEYS.jadwal,get(KEYS.jadwal).filter(x=>x.id!==id));
  showNotif('Jadwal dihapus.');renderJadwal();
}

// ─── GURU ───
function renderGuru(){
  const all=get(KEYS.guru);
  const jadwals=get(KEYS.jadwal);
  document.getElementById('tbodyGuru').innerHTML=all.length?all.map(g=>{
    const count=jadwals.filter(j=>j.guru&&j.guru.includes(g.nama)).length;
    return`<tr>
      <td><div class="name-cell"><div class="av-sm" style="background:${avColor(g.nama)}">${initials(g.nama)}</div>${g.nama}</div></td>
      <td style="font-size:12px;color:var(--muted)">${g.bidang}</td>
      <td>${g.hp||'-'}</td>
      <td><span class="badge ${g.status==='Aktif'?'badge-approved':'badge-rejected'}">${g.status}</span></td>
      <td style="font-family:'Space Grotesk',sans-serif;color:var(--accent3)">${count}</td>
      <td><div class="actions">
        <button class="btn btn-ghost btn-xs" onclick="editGuru(${g.id})" title="Edit"><i class="ti ti-edit"></i></button>
        <button class="btn btn-danger btn-xs" onclick="deleteGuru(${g.id})" title="Hapus"><i class="ti ti-trash"></i></button>
      </div></td>
    </tr>`}).join(''):`<tr><td colspan="6"><div class="empty-state"><i class="ti ti-chalkboard"></i><p>Belum ada guru</p></div></td></tr>`;
}

function openGuruModal(){
  editGuruId=null;
  document.getElementById('modalGuruTitle').textContent='Tambah Guru';
  ['g_nama','g_bidang','g_hp'].forEach(id=>document.getElementById(id).value='');
  document.getElementById('g_status').value='Aktif';
  document.getElementById('modalGuru').classList.add('open');
}

function editGuru(id){
  const g=get(KEYS.guru).find(x=>x.id===id);if(!g)return;
  editGuruId=id;
  document.getElementById('modalGuruTitle').textContent='Edit Guru';
  document.getElementById('g_nama').value=g.nama||'';
  document.getElementById('g_bidang').value=g.bidang||'';
  document.getElementById('g_hp').value=g.hp||'';
  document.getElementById('g_status').value=g.status||'Aktif';
  document.getElementById('modalGuru').classList.add('open');
}

function saveGuru(){
  const nama=document.getElementById('g_nama').value.trim();
  const bidang=document.getElementById('g_bidang').value.trim();
  if(!nama){showNotif('Nama guru wajib diisi!',true);return;}
  const all=get(KEYS.guru);
  const obj={nama,bidang,hp:document.getElementById('g_hp').value,status:document.getElementById('g_status').value};
  if(editGuruId){
    const idx=all.findIndex(x=>x.id===editGuruId);
    if(idx>=0)Object.assign(all[idx],obj);
  } else {
    all.push({id:Date.now(),...obj});
  }
  set(KEYS.guru,all);
  closeModal('modalGuru');
  showNotif(editGuruId?'Data guru diperbarui!':'Guru berhasil ditambahkan!');
  editGuruId=null;renderGuru();
}

function deleteGuru(id){
  if(!confirm('Hapus guru ini?'))return;
  set(KEYS.guru,get(KEYS.guru).filter(x=>x.id!==id));
  showNotif('Guru dihapus.');renderGuru();
}

// ─── LAPORAN ───
function renderLaporan(){
  const all=get(KEYS.pendaftar);
  const siswas=get(KEYS.siswa);
  const jadwals=get(KEYS.jadwal);
  const gurus=get(KEYS.guru);
  const minatCount={};
  all.forEach(r=>{r.minat&&r.minat.split(',').forEach(m=>{const k=m.trim();if(k)minatCount[k]=(minatCount[k]||0)+1;});});
  const topMinat=Object.entries(minatCount).sort((a,b)=>b[1]-a[1]).slice(0,10);
  document.getElementById('laporanContent').innerHTML=`
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:1.5rem">
      <div style="background:var(--card2);border:1px solid var(--border);border-radius:10px;padding:1rem;text-align:center">
        <div style="font-size:28px;font-weight:700;font-family:'Space Grotesk',sans-serif;color:var(--accent3)">${all.length}</div>
        <div style="font-size:12px;color:var(--muted);margin-top:4px">Total Pendaftar</div>
      </div>
      <div style="background:var(--card2);border:1px solid var(--border);border-radius:10px;padding:1rem;text-align:center">
        <div style="font-size:28px;font-weight:700;font-family:'Space Grotesk',sans-serif;color:var(--green)">${siswas.length}</div>
        <div style="font-size:12px;color:var(--muted);margin-top:4px">Siswa Aktif</div>
      </div>
      <div style="background:var(--card2);border:1px solid var(--border);border-radius:10px;padding:1rem;text-align:center">
        <div style="font-size:28px;font-weight:700;font-family:'Space Grotesk',sans-serif;color:var(--blue)">${jadwals.length}</div>
        <div style="font-size:12px;color:var(--muted);margin-top:4px">Total Jadwal</div>
      </div>
      <div style="background:var(--card2);border:1px solid var(--border);border-radius:10px;padding:1rem;text-align:center">
        <div style="font-size:28px;font-weight:700;font-family:'Space Grotesk',sans-serif;color:var(--yellow)">${gurus.length}</div>
        <div style="font-size:12px;color:var(--muted);margin-top:4px">Total Guru</div>
      </div>
    </div>
    <div style="font-size:13px;font-weight:700;color:var(--muted);letter-spacing:.05em;text-transform:uppercase;margin-bottom:12px">Top Program Diminati</div>
    ${topMinat.map(([k,v])=>`
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px">
        <div style="font-size:13px;color:var(--text);width:200px;flex-shrink:0">${k}</div>
        <div style="flex:1;background:var(--border);border-radius:4px;height:8px;overflow:hidden">
          <div style="width:${Math.round(v/(topMinat[0]?.[1]||1)*100)}%;height:100%;background:linear-gradient(90deg,var(--accent),var(--accent2));border-radius:4px"></div>
        </div>
        <div style="font-size:13px;font-weight:700;color:var(--accent3);font-family:'Space Grotesk',sans-serif;width:24px;text-align:right">${v}</div>
      </div>`).join('')||'<p style="color:var(--muted)">Belum ada data</p>'}
  `;
}

// ─── INIT ───
function renderAll(){renderDashboard();}
renderAll();
</script>
</body>
</html><?php /**PATH /home/runner/workspace/resources/views/admin.blade.php ENDPATH**/ ?>