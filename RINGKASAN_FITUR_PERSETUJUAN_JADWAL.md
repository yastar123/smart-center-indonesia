# 📋 Ringkasan Fitur Persetujuan Jadwal Kelas & Absensi

---

## A. Halaman dan Menu yang Tersedia

### 🔔 Untuk GURU:

| No | Menu | URL/Route | Lokasi File |
|----|------|-----------|-------------|
| 1 | **Persetujuan Jadwal** | `/admin/schedule-agreements` → `guru.schedule-agreements.index` | `resources/views/guru/schedule-agreements/index.blade.php` |
| 2 | **Absensi Kelas** | `/admin/classes/{id}/attendance` → `guru.classes.attendance` | `resources/views/guru/classes/attendance.blade.php` |

- **Controller**: `App\Http\Controllers\Guru\ScheduleProposalController`
- **Controller**: `App\Http\Controllers\Guru\AttendanceController`

### 🎓 Untuk SISWA:

| No | Menu | URL/Route | Lokasi File |
|----|------|-----------|-------------|
| 1 | **Persetujuan Jadwal** | `/siswa/schedule-agreements` → `siswa.schedule-agreements.index` | `resources/views/siswa/schedule-agreements/index.blade.php` |
| 2 | **Absensi** | `/siswa/attendance` → `siswa.attendance` | `resources/views/siswa/attendance/index.blade.php` |

- **Controller**: `App\Http\Controllers\Siswa\ScheduleProposalController`
- **Controller**: `App\Http\Controllers\Siswa\AttendanceController`

---

## B. Alur Saat Ini (Sebelum Perubahan)

```
┌─────────────────┐     ┌──────────────────┐     ┌─────────────────┐
│ Usulkan Jadwal  │ ──► │ Semua Pihak      │ ──► │ Jadwal Otomatis │
│ (Guru/Siswa)    │     │ Menyetujui       │     │ Dibuat          │
└─────────────────┘     └──────────────────┘     └─────────────────┘
                                                         │
                                                         ▼
                                                 ┌─────────────────┐
                                                 │ Jadwal Dikunci  │
                                                 │ H-1 jam sebelum │
                                                 │ pertemuan        │
                                                 └─────────────────┘
```

---

## C. Alur yang Diinginkan (Setelah Perubahan)

```
┌─────────────────┐     ┌──────────────────┐     ┌─────────────────┐
│ Usulkan Jadwal  │ ──► │ Semua Pihak      │ ──► │ Jadwal Disetujui│
│ (Guru/Siswa)    │     │ Menyetujui       │     │ (Auto-created)  │
└─────────────────┘     └──────────────────┘     └─────────────────┘
                                                         │
                                                         ▼
                                                 ┌─────────────────┐
                                                 │ Pertemuan        │
                                                 │ Berlangsung      │
                                                 └─────────────────┘
                                                         │
                                                         ▼
                                                 ┌─────────────────┐
                                                 │ Guru Mengisi    │ ──► Guru bisa lihat
                                                 │ Absensi         │     daftar siswa
                                                 └─────────────────┘
                                                         │
                                                         ▼
                                                 ┌─────────────────┐
                                                 │ Absensi Terkunci│ ◄── Tidak bisa diubah
                                                 │ (Selesai)       │     setelah disimpan
                                                 └─────────────────┘

⚠️ Jadwal yang SUDAH memiliki absensi → TIDAK BISA dijadwalkan ulang / ditimpa
```

---

## D. Detail Fitur yang Perlu Ditambahkan

### 1. 📌 Pilihan Jumlah Pertemuan

**Kebutuhan**: Saat membuat proposal jadwal, tambahkan dropdown pilihan jumlah pertemuan.

**Sumber Data**: Ambil dari kolom `jumlah_pertemuan` di tabel `school_classes`

**Contoh tampilan**:
```
Kelas: Matematika Kelas 12 IPA
Jumlah Pertemuan yang Tersedia: 16x
(Jika sudah ada 5 jadwal, maka sisa = 11)
```

**Lokasi yang perlu diubah**:
- View: `guru/schedule-agreements/index.blade.php` (form modal)
- View: `siswa/schedule-agreements/index.blade.php` (form modal)

---

### 2. 🔒 Pencegahan Penimpaan Jadwal

**Kebutuhan**: Jadwal yang SUDAH memiliki absensi tidak bisa ditimpa atau dijadwalkan ulang.

**Logika yang perlu ditambahkan**:
```
JIKA schedule.sudah_memiliki_absensi = true:
    → TIDAK BISA edit/hapus jadwal
    → TIDAK BISA buat jadwal baru yang menimpa
    → Tampilkan pesan: "Pertemuan ini sudah selesai dan tidak dapat diubah"
```

**Lokasi yang perlu diubah**:
- `app/Services/ScheduleLockService.php` - Tambahkan method untuk cek apakah sudah ada absensi
- `app/Http/Controllers/Guru/AttendanceController.php` - Validasi sebelum simpan
- `app/Http/Controllers/Admin/ScheduleController.php` - Validasi sebelum edit/hapus

---

### 3. 🔄 Rombak Fitur Absensi

**Kebutuhan**: Absensi dirombak dengan alur strict

**Perubahan yang diperlukan**:

| No | Aspek | Sebelum | Sesudah |
|----|-------|---------|---------|
| 1 | Syarat isi absensi | Guru & siswa sepakat | ✅ Guru & siswa sepakat |
| 2 | Pengisian absensi | Boleh diubah | ❌ Sekali simpan = terkunci |
| 3 | Status jadwal | Bisa dijadwalkan ulang | ❌ Tidak bisa jika sudah ada absensi |

**Lokasi yang perlu diubah**:
- `app/Services/ScheduleLockService.php` - Modifikasi `isAttendanceLocked()`
- `app/Http/Controllers/Guru/AttendanceController.php` - Modifikasi `store()`
- View: `guru/classes/attendance.blade.php` - Tampilan "Absensi Terkunci"
- View: `siswa/attendance/show.blade.php` - Tampilan status absensi

---

## E. Model dan Service Terkait

### Model:
- `app/Models/ScheduleProposal.php` - Proposal jadwal
- `app/Models/ScheduleProposalApproval.php` - Persetujuan
- `app/Models/Schedule.php` - Jadwal pertemuan
- `app/Models/ScheduleStudentAgreement.php` - Konfirmasi jadwal siswa
- `app/Models/SchoolClass.php` - Kelas (ada `jumlah_pertemuan`)

### Service:
- `app/Services/ScheduleProposalService.php` - Logic proposal jadwal
- `app/Services/ScheduleAgreementService.php` - Logic konfirmasi jadwal
- `app/Services/ScheduleLockService.php` - Logic penguncian jadwal & absensi

### Database Tables:
- `schedule_proposals` - Tabel proposal jadwal
- `schedule_proposal_approvals` - Tabel persetujuan
- `schedules` - Tabel jadwal
- `schedule_student_agreements` - Tabel konfirmasi siswa
- `absensi_siswas` - Tabel absensi siswa

---

## F. Ringkasan Perubahan yang Perlu Dilakukan

| No | Fitur | Prioritas | Estimasi Effort |
|----|-------|-----------|-----------------|
| 1 | Tambah pilihan jumlah pertemuan di form | 🟡 Medium | 1-2 jam |
| 2 | Cegah penimpaan jadwal dengan absensi | 🔴 High | 2-3 jam |
| 3 | Absensi terkunci setelah disimpan | 🔴 High | 2-3 jam |
| 4 | Update UI absensi terkunci | 🟡 Medium | 1-2 jam |

---

## G. Catatan Teknis Penting

1. **Status Jadwal** (`Schedule.status`):
   - `dijadwalkan` - Jadwal baru dibuat
   - `berlangsung` - Pertemuan sedang berlangsung
   - `selesai` - Pertemuan selesai

2. **Status Persetujuan** (`ScheduleProposal.status`):
   - `pending` - Menunggu persetujuan
   - `approved` - Disetujui semua pihak
   - `rejected` - Ditolak

3. **Status Absensi** (`absensi_siswas.status`):
   - `hadir` - Hadir
   - `izin` - Izin
   - `sakit` - Sakit
   - `alpa` - Tidak hadir tanpa izin

4. **Penguncian**:
   - Jadwal dikunci H-1 jam sebelum pertemuan (`ScheduleLockService.isScheduleLocked()`)
   - Absensi dikunci setelah pertemuan selesai (`ScheduleLockService.isAttendanceLocked()`)

---

*Terakhir diupdate: 2026-06-11*