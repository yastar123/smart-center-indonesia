# Product Requirements Document (PRD)
## Akademi Bimbel — Platform Manajemen Bimbel Multi-Cabang

| | |
|---|---|
| **Versi Dokumen** | 1.0 |
| **Tanggal** | 11 Juli 2026 |
| **Status** | Living document — diperbarui setiap ada perubahan fitur besar |
| **Pemilik Produk** | Tim internal Akademi Bimbel |

---

## 1. Ringkasan Eksekutif

**Akademi Bimbel** adalah platform manajemen operasional untuk lembaga bimbingan belajar (bimbel) yang memiliki **banyak cabang**. Platform ini menyatukan seluruh proses inti sebuah bimbel — pendaftaran siswa, penjadwalan kelas, presensi, penilaian, pembayaran/tagihan, penggajian guru, sertifikat kelulusan, hingga komunikasi internal — dalam satu sistem berbasis web yang dapat diakses oleh empat peran pengguna: **Owner (pemilik/pusat)**, **Admin cabang**, **Guru**, dan **Siswa**.

### 1.1 Latar Belakang & Masalah yang Diselesaikan
Bimbel dengan banyak cabang umumnya mengelola data siswa, jadwal, presensi, dan keuangan secara manual (spreadsheet, WhatsApp, buku catatan) di masing-masing cabang. Ini menyebabkan:
- Tidak ada visibilitas terpusat bagi pemilik atas performa semua cabang.
- Proses pendaftaran siswa baru lambat dan rawan salah catat data pembayaran/kelas.
- Presensi dan nilai tercecer, sulit dilacak untuk pelaporan ke orang tua.
- Penagihan pembayaran manual, rawan telat/lupa tagih.
- Tidak ada jejak audit (siapa mengubah apa).

### 1.2 Solusi
Sebuah aplikasi web terpusat (Laravel 9 + PostgreSQL) dengan **role-based access control**, dashboard analitik per peran, dan alur kerja terstruktur (wizard) untuk proses-proses kunci seperti pendaftaran siswa.

### 1.3 Tujuan Produk
1. Memusatkan seluruh data operasional bimbel dari banyak cabang dalam satu sumber kebenaran (single source of truth).
2. Mengotomasi proses administratif berulang: pendaftaran siswa, penagihan, pengiriman kredensial akun.
3. Memberi visibilitas real-time ke pemilik (owner) atas kinerja tiap cabang (jumlah siswa, guru, pendapatan).
4. Menyediakan portal mandiri bagi guru dan siswa agar tidak lagi bergantung pada komunikasi manual untuk hal-hal rutin (lihat jadwal, absen, nilai, tagihan).

---

## 2. Target Pengguna & Peran (Roles)

Sistem menggunakan RBAC (Spatie Laravel Permission) dengan 5 peran:

| Peran | Siapa | Fokus Utama |
|---|---|---|
| **Owner** | Pemilik bisnis / pusat | Monitoring seluruh cabang, kurikulum master, analitik, promo, paket belajar global |
| **Admin** | Staf administrasi per cabang | Operasional harian cabang: registrasi siswa, jadwal, presensi, tagihan, guru, sertifikat |
| **Guru** | Pengajar | Mengajar, mengisi presensi & nilai kelasnya, melihat jadwal, mengajukan reschedule |
| **Siswa** | Peserta didik / orang tua | Melihat jadwal, presensi, nilai, tagihan, sertifikat, tryout, mengajukan cuti/reschedule |
| **Karyawan** | Staf non-pengajar lain | Peran tambahan untuk staf operasional (cakupan lebih terbatas) |

Setiap pengguna (`User`) terhubung ke satu `branch_id` (cabang), kecuali Owner yang punya akses lintas cabang. Middleware `check.branch.access` memastikan admin/guru/siswa hanya melihat data cabangnya sendiri.

---

## 3. Modul & Fitur Utama

### 3.1 Autentikasi & Onboarding
- Login berbasis sesi (Laravel Breeze), dengan halaman landing publik per cabang.
- Registrasi publik calon siswa (`/register`) — form multi-langkah yang menghasilkan **lead** (`student_registrations`, status awal *pending*), belum berupa akun aktif.
- Halaman demo login menampilkan kredensial contoh untuk tiap peran (mode staging/demo).

### 3.2 Pendaftaran Siswa (Registration)
Dua jalur yang saling melengkapi:

**A. Jalur Lead → Verifikasi (untuk calon siswa yang mendaftar sendiri)**
1. Calon siswa mengisi form publik (`/register`) → tersimpan sebagai lead `pending` di `student_registrations`.
2. Admin melihat lead ini di dashboard pada komponen **"Siswa Terbaru Mendaftar"** (juga bisa ditinjau lengkap di **Registration List**).
3. Admin klik tombol **"Verifikasi"** → **diarahkan ke halaman wizard 5 langkah** (bukan langsung membuat akun): Informasi Siswa (terisi otomatis dari data lead, **dapat dikoreksi admin** — lihat catatan di bawah) → Paket Kelas → Mapel & Guru → Pembayaran → Preview.
4. Setelah admin melengkapi paket/mapel/guru/pembayaran dan submit di langkah Preview, sistem baru membuat akun `User` + `Student`, invoice, dan penugasan guru. Koreksi data siswa yang dilakukan admin di Langkah 1 juga disimpan balik ke record lead (`student_registrations`) sebelum akun dibuat.
5. Halaman sukses menampilkan kredensial (email & password) dan tombol **"Kirim ke WhatsApp Siswa"** — admin klik tombol ini untuk membuka WhatsApp dengan pesan kredensial sudah terisi (memakai nomor HP yang didaftarkan calon siswa saat mengisi form publik), lalu admin menekan kirim secara manual di WhatsApp. Proses ini **tidak otomatis penuh** — tidak ada pengiriman via API WhatsApp tanpa interaksi admin (lihat §8).

> Perubahan produk (11 Jul 2026): sebelumnya tombol "Verifikasi" langsung membuat akun & mengirim WA tanpa melalui wizard. Sekarang proses verifikasi lead **wajib melalui wizard 5 langkah yang sama** dengan jalur pendaftaran manual (§3.2.B), supaya admin selalu menentukan paket/mapel/guru/pembayaran sebelum akun dibuat.

> Perubahan produk (11 Jul 2026, lanjutan): pada Langkah 1 (Informasi Siswa) di wizard verifikasi lead, field data pribadi siswa (nama, no. HP, jenis kelamin, tempat/tgl lahir, alamat, nama & no. HP orang tua, program, sistem) **kini dapat diedit langsung oleh admin** — sebelumnya field-field ini terkunci (`disabled`, hanya tampilan) sehingga admin tidak bisa mengoreksi kesalahan input siswa. Sebaliknya, pada Langkah 3 (Mapel & Guru), daftar **mata pelajaran yang sudah diminati siswa tidak dapat dihapus/dibatalkan centangnya oleh admin** (dikunci sebagai keputusan produk agar minat asli siswa tidak hilang) — admin tetap bebas mengubah guru pengajar, jumlah sesi, dan biaya per mapel.

**B. Jalur Wizard Langsung (untuk admin mendaftarkan siswa baru secara manual)**
1. Admin membuka **Registrasi Siswa Baru** (`/admin/registrasi-baru`) — wizard 5 langkah dengan Langkah 1 (Informasi Siswa) **dapat diedit langsung**, tanpa perlu lead sebelumnya.
2. Langkah selanjutnya sama: Paket Kelas → Mapel & Guru → Pembayaran → Preview & Submit.
3. Sistem membuat `User`, `Student`, penugasan guru (pivot `student_teachers`), dan `Invoice`.
4. Setelah sukses, sistem menghasilkan **kredensial login (email + password sementara)** dan menampilkan tombol **"Kirim ke WhatsApp Siswa"** yang membuka `wa.me` dengan pesan kredensial sudah terisi otomatis — admin cukup menekan kirim di WhatsApp.

> Catatan produk: pengiriman WA saat ini bersifat *semi-otomatis* (link `wa.me`, bukan API WhatsApp resmi). Otomasi penuh via WhatsApp Business API tercatat sebagai kebutuhan lanjutan (lihat §8).

### 3.3 Manajemen Cabang (Branch)
- Owner mengelola data master cabang: nama, kode, alamat, kontak, status aktif/nonaktif, penunjukan admin cabang.
- Admin mengelola konten landing page cabangnya (teks promosi, nomor WA, dsb).

### 3.4 Manajemen Kurikulum & Paket
- **Mata Pelajaran (Course)**: dikelola per cabang, punya biaya per sesi (`CourseFee`), modul ajar, kategori/jenis kursus.
- **Modul Akademik**: materi ajar per mata pelajaran (file, kode modul).
- **Paket Belajar (Package)**: ditentukan di level global (owner) dan diinstansiasi per cabang (admin) dengan harga, durasi, jumlah pertemuan, tipe kehadiran (online/offline/hybrid), jumlah sesi kuota siswa.
- **Kelas (SchoolClass)**: entitas pengelompokan siswa-guru-mapel untuk satu periode/tahun akademik, dengan mode billing tertentu.

### 3.5 Penjadwalan (Scheduling)
- Kalender visual jadwal kelas (hari, jam mulai/selesai, jenis online/offline, guru, mapel, modul yang diajarkan).
- Status jadwal: `dijadwalkan`, `berlangsung`, `selesai`, `dibatalkan`.
- **Reschedule / Cuti & Freeze**: guru atau siswa dapat **mengajukan** perubahan jadwal atau cuti; admin (atau pihak terkait) harus **menyetujui** melalui alur *proposal → agreement* sebelum jadwal berubah — mencegah perubahan sepihak tanpa persetujuan.

### 3.6 Presensi (Attendance / Absensi)
- Guru mengisi presensi siswa per pertemuan kelas dari portalnya.
- Admin dapat melakukan koreksi/bulk update presensi.
- Status presensi mencakup kehadiran guru dan konfirmasi siswa (logika `computeStatus` menentukan status akhir seperti hadir/alpa/izin berdasarkan kombinasi keduanya).
- Presensi terhubung ke kuota sesi siswa (mengurangi sisa sesi paket).

### 3.7 Penilaian (Grading)
- Guru menginput nilai/evaluasi siswa di kelas yang diampu (jenis penilaian bervariasi, nilai numerik).
- Siswa dapat melihat riwayat nilainya di portal masing-masing.

### 3.8 Pembayaran & Tagihan (Billing/Invoice)
- **Invoice** dibuat otomatis saat registrasi (atau manual oleh admin), berisi nominal total, status: `belum_bayar`, `sebagian` (cicilan), `lunas`.
- **Payment**: pencatatan pembayaran per invoice (metode cash/transfer/QRIS), dengan **upload bukti pembayaran** oleh siswa dan **verifikasi** oleh admin (status `pending` → `verified`/`rejected`).
- **Tagihan Siswa**: tampilan ringkas status pembayaran siswa untuk admin (menunggak/lunas) dan laporan keuangan cabang (total pendapatan terverifikasi, dihitung dari tanggal pembayaran aktual).

### 3.9 Penggajian Guru (Salary)
- Perhitungan gaji bulanan guru (`salaries`) berdasarkan tipe gaji (misalnya per sesi/tetap), status pembayaran gaji per bulan/tahun.

### 3.10 Sertifikat
- Admin menerbitkan sertifikat kelulusan/penyelesaian kursus untuk siswa yang telah menuntaskan paket/kelas tertentu; siswa dapat mengunduh sertifikatnya dari portal.

### 3.11 Tryout / CBT (Computer-Based Test)
- Admin/Owner cabang membuat tryout dengan jadwal waktu mulai/selesai dan durasi, terdiri dari soal-soal (pilihan jawaban JSON + kunci jawaban).
- Siswa mengerjakan tryout dari portalnya dalam jendela waktu yang ditentukan; hasil tercatat sebagai *attempt*.

### 3.12 Pesan Internal (Messaging/Chat)
- Sistem chat berbasis "room" antar peran (admin–guru–siswa), dengan daftar room yang di-refresh otomatis secara berkala (polling).
- Mencegah kebutuhan komunikasi operasional lewat WhatsApp pribadi untuk hal-hal yang harus tercatat di sistem.

### 3.13 Pengumuman (Announcements) & Notifikasi
- Admin/Owner dapat membuat pengumuman yang tampil ke peran terkait.
- Panel notifikasi in-app menampilkan info terbaru sesuai peran pengguna yang login.

### 3.14 Dashboard & Analitik
- **Owner**: ringkasan lintas cabang — jumlah siswa/guru aktif, jumlah cabang aktif, tren pendapatan, monitoring performa tiap cabang, log aktivitas sistem (audit trail).
- **Admin**: ringkasan cabangnya — siswa aktif, pendapatan bulanan (dari pembayaran terverifikasi), tagihan tertunggak, jadwal hari ini.
- **Guru**: kelas yang diampu, jadwal mengajar mendatang, tugas presensi/nilai yang belum diisi.
- **Siswa**: jadwal pertemuan berikutnya, status tagihan, riwayat presensi/nilai, sertifikat yang dimiliki.

---

## 4. Alur Pengguna Kunci (Key User Flows)

### 4.1 Admin mendaftarkan siswa baru (paling sering digunakan)
```
Admin login → Dashboard → "Registrasi Siswa Baru"
  → Langkah 1: isi data siswa & orang tua
  → Langkah 2: pilih paket kelas
  → Langkah 3: pilih mapel + guru pengajar + jumlah sesi + biaya
  → Langkah 4: tentukan status pembayaran awal
  → Langkah 5: preview seluruh data → Submit
  → Sistem membuat akun + invoice
  → Admin klik "Kirim ke WhatsApp Siswa" → WhatsApp terbuka dengan pesan kredensial siap kirim
```

### 4.2 Siswa mengikuti kelas & membayar tagihan
```
Siswa login → lihat jadwal pertemuan → hadir di kelas (guru mengisi presensi)
  → siswa cek tagihan → upload bukti pembayaran
  → admin verifikasi pembayaran → status invoice ter-update (lunas/sebagian)
```

### 4.3 Guru mengajar & melapor
```
Guru login → lihat "Kelas Saya" → pilih pertemuan hari ini
  → isi presensi siswa → isi nilai (jika ada evaluasi)
  → jika perlu ubah jadwal → ajukan reschedule → menunggu persetujuan
```

### 4.4 Owner memantau bisnis
```
Owner login → Dashboard → lihat ringkasan semua cabang
  → buka Monitoring Cabang untuk detail satu cabang
  → buka Log Aktivitas untuk audit
  → kelola Master Kurikulum / Paket Belajar / Promo secara global
```

---

## 5. Model Data Inti (Ringkasan Entitas)

| Entitas | Deskripsi Singkat | Relasi Kunci |
|---|---|---|
| **User** | Akun login, terhubung ke satu peran & cabang | 1–1 Student/Teacher, belongsTo Branch |
| **Branch** (cabang) | Unit cabang bimbel | hasMany Student/Teacher/Package |
| **Student** (siswa) | Profil siswa, nomor induk (NIS), status | belongsTo User & Branch & Package, belongsToMany Teacher, hasMany Invoice |
| **Teacher** (guru) | Profil pengajar | belongsTo User & Branch, belongsToMany Course & Student |
| **Course** (mata pelajaran) | Mapel per cabang | hasOne CourseFee, hasMany Module/SchoolClass |
| **Package** (paket) | Paket belajar (harga, durasi, kuota sesi) | belongsTo Branch |
| **SchoolClass** (kelas) | Entitas kelas per periode | belongsTo Branch/Course/Teacher, hasMany Schedule |
| **Schedule** (jadwal) | Slot pertemuan kelas | belongsTo SchoolClass/Teacher/Course/Module |
| **Invoice** (tagihan) | Tagihan per siswa | belongsTo Student/Branch/SchoolClass, hasMany Payment |
| **Payment** (pembayaran) | Transaksi pembayaran | belongsTo Invoice |
| **AbsensiSiswa** | Presensi per pertemuan | belongsTo Schedule & Student |
| **Salary** (gaji) | Gaji guru per periode | belongsTo Teacher |
| **StudentRegistration** | Lead pendaftaran publik | belongsTo Student/Invoice, ditugaskan ke Teacher |
| **Tryout / Question** | Ujian CBT & soal | hasMany Question, TryoutAttempt |

Status penting yang berlaku di seluruh sistem:
- **Invoice**: `belum_bayar` → `sebagian` → `lunas`
- **Payment**: `pending` → `verified` / `rejected`
- **Student**: `aktif`, `nonaktif`, `lulus`, `drop_out`
- **Teacher**: `tetap`, `kontrak`, `probation`, `resigned`
- **Schedule**: `dijadwalkan` → `berlangsung` → `selesai` / `dibatalkan`

---

## 6. Kebutuhan Non-Fungsional

| Aspek | Kebutuhan |
|---|---|
| **Bahasa UI** | Bahasa Indonesia di seluruh antarmuka |
| **Akses berbasis peran** | Setiap endpoint dibatasi middleware `role:` dan `check.branch.access`; admin/guru/siswa tidak boleh mengakses data cabang lain |
| **Konsistensi tampilan** | Tema warna brand ungu (`#c84ddf`/`#461256`) konsisten di semua halaman terstruktur; mendukung mode terang & gelap |
| **Responsif** | Seluruh portal (terutama siswa & guru) harus nyaman diakses dari perangkat mobile |
| **Auditability** | Aktivitas penting (login, perubahan data kritikal) tercatat di log aktivitas yang bisa ditinjau owner |
| **Ketahanan alur kritikal** | Proses pendaftaran & pembayaran tidak boleh gagal senyap — error divalidasi & ditampilkan jelas ke admin |
| **Skalabilitas multi-cabang** | Penambahan cabang baru tidak memerlukan perubahan kode, hanya data master baru |

---

## 7. Tumpukan Teknologi (Tech Stack)

| Lapisan | Teknologi |
|---|---|
| Backend | PHP 8.2, Laravel 9 |
| Database | PostgreSQL |
| Autentikasi | Laravel Breeze (sesi native) |
| Otorisasi (RBAC) | Spatie Laravel Permission |
| Audit Log | Spatie Laravel Activitylog |
| Frontend | Blade templates, Bootstrap-based styling, Alpine.js, Vite |
| Deployment | Replit (workflow `bash start.sh`, port 5000) |

Prinsip arsitektur yang dipertahankan (lihat juga `replit.md`):
- Struktur MVC per peran: controller dipisah ke `app/Http/Controllers/{Admin|Owner|Guru|Siswa}/`.
- Tetap menggunakan Blade + server-rendered views — **bukan** SPA berat berbasis JS.
- Tidak ada API WhatsApp resmi terpasang; pengiriman kredensial memakai link `wa.me` manual.

---

## 8. Batasan Saat Ini & Kebutuhan Lanjutan (Roadmap Awal)

Bagian ini mencatat gap yang **diketahui secara sadar** sebagai keputusan produk sementara, agar tim berikutnya paham arah pengembangan:

1. **Pengiriman WhatsApp semi-manual** — kredensial siswa baru dikirim lewat link `wa.me` yang harus ditekan admin secara manual, bukan API otomatis. Kandidat peningkatan: integrasi WhatsApp Business API (Fonnte/Wablas) agar terkirim otomatis saat akun dibuat.
2. **Dua jalur pendaftaran paralel** — jalur wizard baru (edit langsung) hidup berdampingan dengan jalur registrasi lama berbasis `SchoolClass` (`admin.registration.create`) yang sudah tidak ditautkan dari menu tapi masih bisa diakses lewat URL langsung. Perlu keputusan: hapus atau definisikan ulang kegunaannya.
3. **Belum ada integrasi payment gateway otomatis** — verifikasi pembayaran masih manual (admin memeriksa bukti unggahan), belum terhubung ke payment gateway (misal Midtrans/Xendit) untuk pembayaran online instan.
4. **Belum ada aplikasi mobile native** — seluruh portal saat ini web-responsive, belum ada aplikasi Android/iOS terpisah.

---

## 9. Glosarium Istilah

| Istilah | Arti |
|---|---|
| Bimbel | Bimbingan belajar |
| Cabang | Unit lokasi operasional bimbel (branch) |
| Siswa | Peserta didik/murid |
| Guru | Pengajar/tutor |
| NIS | Nomor Induk Siswa |
| Absensi | Presensi/kehadiran |
| Tagihan | Invoice |
| Paket | Paket belajar (bundel sesi + harga) |
| Reschedule | Pengajuan perubahan jadwal pertemuan |
| Cuti & Freeze | Pengajuan penangguhan sementara sesi belajar siswa |
| Tryout | Simulasi ujian berbasis komputer (CBT) |
| Lead | Calon siswa yang mendaftar via form publik, belum berstatus akun aktif |
