<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Branch;
use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\CourseFee;
use App\Models\Package;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Module;
use App\Models\Salary;
use App\Models\Announcement;
use App\Models\Tryout;
use App\Models\Certificate;
use App\Models\StudentRegistration;

class SlimSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('🌱 SlimSeeder: 2 data per entitas...');

        $driver = DB::connection()->getDriverName();
        if ($driver === 'pgsql') {
            DB::statement('SET session_replication_role = replica');
        } elseif ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }

        $tables = [
            'absensi_siswas', 'absensi_gurus', 'grades', 'tryout_attempts', 'questions',
            'certificates', 'salaries', 'payments', 'invoices', 'schedules',
            'school_class_student', 'school_classes', 'announcements', 'modules', 'tryouts',
            'course_teacher', 'package_course_teacher', 'package_mata_pelajaran', 'packages',
            'course_fees', 'courses', 'teachers', 'students',
            'schedule_proposals', 'student_leaves', 'message_rooms', 'messages',
            'curricula', 'promos',
        ];

        foreach ($tables as $t) {
            try { DB::table($t)->truncate(); } catch (\Exception $e) { }
        }

        $keepEmails = ['adminpusatsci@akademi.com', 'admincabangasci@akademi.com'];
        User::withTrashed()->whereNotIn('email', $keepEmails)->get()->each(function ($user) {
            try { $user->forceDelete(); } catch (\Exception $e) { }
        });

        if ($driver === 'pgsql') {
            DB::statement('SET session_replication_role = DEFAULT');
        } elseif ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $this->command->info('  ✅ Tabel dikosongkan');

        // ── TAHUN AKADEMIK ─────────────────────────────────────────────── //
        $tahun = AcademicYear::firstOrCreate(
            ['name' => '2025/2026'],
            ['year_start' => 2025, 'year_end' => 2026, 'is_active' => true]
        );

        // ── 2 CABANG ──────────────────────────────────────────────────── //
        $cabangA = Branch::firstOrCreate(['name' => 'Cabang Jakarta'], [
            'address' => 'Jl. Sudirman No. 1, Jakarta Pusat',
            'phone'   => '021-5555001',
            'email'   => 'jakarta@akademisci.com',
            'city'    => 'Jakarta', 'regency' => 'Jakarta Pusat', 'status' => 'active',
            'can_students' => true, 'can_teachers' => true, 'can_schedules' => true,
            'can_payments' => true, 'can_tryouts' => true,
        ]);

        $cabangB = Branch::firstOrCreate(['name' => 'Cabang Bandung'], [
            'address' => 'Jl. Braga No. 22, Bandung',
            'phone'   => '022-4444001',
            'email'   => 'bandung@akademisci.com',
            'city'    => 'Bandung', 'regency' => 'Bandung Kota', 'status' => 'active',
            'can_students' => true, 'can_teachers' => true, 'can_schedules' => true,
            'can_payments' => true, 'can_tryouts' => true,
        ]);

        $adminUser = User::where('email', 'admincabangasci@akademi.com')->first();
        if ($adminUser) {
            $adminUser->update(['branch_id' => $cabangA->id]);
            $cabangA->update(['admin_id' => $adminUser->id]);
        }

        $this->command->info('  ✅ 2 Cabang');

        // ── 2 MATA PELAJARAN ─────────────────────────────────────────── //
        $mapelA = Course::create([
            'cabang_id' => $cabangA->id, 'kode' => 'MAT-001',
            'nama' => 'Matematika', 'kategori' => 'Saintek',
            'deskripsi' => 'Matematika dasar dan lanjutan untuk semua jenjang.', 'status' => 'aktif',
        ]);
        CourseFee::create(['course_id' => $mapelA->id, 'amount' => 350000]);

        $mapelB = Course::create([
            'cabang_id' => $cabangB->id, 'kode' => 'ING-001',
            'nama' => 'Bahasa Inggris', 'kategori' => 'Umum',
            'deskripsi' => 'Grammar, reading, writing, dan speaking skills.', 'status' => 'aktif',
        ]);
        CourseFee::create(['course_id' => $mapelB->id, 'amount' => 300000]);

        $this->command->info('  ✅ 2 Mata Pelajaran');

        // ── 2 PAKET BELAJAR ──────────────────────────────────────────── //
        Package::create([
            'cabang_id' => $cabangA->id, 'nama' => 'Paket Reguler SMA',
            'jenis' => 'reguler', 'harga' => 750000, 'durasi_bulan' => 1,
            'jumlah_pertemuan' => 8, 'tipe_kelas' => 'offline',
            'metode_absensi' => 'dual', 'status' => 'aktif', 'is_unggulan' => false,
            'deskripsi' => 'Paket reguler 8 pertemuan per bulan untuk siswa SMA.',
            'fitur' => ['8 pertemuan/bulan', 'Modul digital', 'Evaluasi bulanan'],
        ]);

        $paketB = Package::create([
            'cabang_id' => $cabangA->id, 'nama' => 'Paket Intensif SNBT',
            'jenis' => 'intensif', 'harga' => 2500000, 'durasi_bulan' => 3,
            'jumlah_pertemuan' => 36, 'tipe_kelas' => 'offline',
            'metode_absensi' => 'dual', 'status' => 'aktif', 'is_unggulan' => true,
            'deskripsi' => 'Program intensif 3 bulan persiapan SNBT dengan tryout rutin.',
            'fitur' => ['36 pertemuan', 'Tryout mingguan', 'Mentor pribadi'],
        ]);

        $this->command->info('  ✅ 2 Paket Belajar');

        // ── 2 GURU ───────────────────────────────────────────────────── //
        $userGuruA = User::create([
            'name' => 'Budi Santoso, S.Pd.', 'email' => 'budi.santoso@guru.akademisci.com',
            'password' => Hash::make('password123'), 'is_active' => true, 'branch_id' => $cabangA->id,
        ]);
        $userGuruA->syncRoles(['guru']);
        $guruA = Teacher::create([
            'user_id' => $userGuruA->id, 'name' => 'Budi Santoso, S.Pd.',
            'email' => $userGuruA->email, 'nig' => 'NIG-2024-001',
            'gender' => 'L', 'branch_id' => $cabangA->id,
            'subjects' => ['Matematika'], 'status' => 'aktif',
            'salary_base' => 4500000, 'jenis_guru' => 'tetap',
        ]);
        $guruA->courses()->sync([$mapelA->id]);

        $userGuruB = User::create([
            'name' => 'Sari Dewi, S.Pd.', 'email' => 'sari.dewi@guru.akademisci.com',
            'password' => Hash::make('password123'), 'is_active' => true, 'branch_id' => $cabangB->id,
        ]);
        $userGuruB->syncRoles(['guru']);
        $guruB = Teacher::create([
            'user_id' => $userGuruB->id, 'name' => 'Sari Dewi, S.Pd.',
            'email' => $userGuruB->email, 'nig' => 'NIG-2024-002',
            'gender' => 'P', 'branch_id' => $cabangB->id,
            'subjects' => ['Bahasa Inggris'], 'status' => 'aktif',
            'salary_base' => 4000000, 'jenis_guru' => 'tetap',
        ]);
        $guruB->courses()->sync([$mapelB->id]);

        $this->command->info('  ✅ 2 Guru');

        // ── 2 SISWA ──────────────────────────────────────────────────── //
        $userSiswaA = User::create([
            'name' => 'Andi Nugroho', 'email' => 'andi.nugroho@siswa.com',
            'password' => Hash::make('password'), 'is_active' => true, 'branch_id' => $cabangA->id,
        ]);
        $userSiswaA->syncRoles(['siswa']);
        $siswaA = Student::create([
            'user_id' => $userSiswaA->id, 'name' => 'Andi Nugroho',
            'nis' => 'SIS-2025-001', 'gender' => 'L', 'branch_id' => $cabangA->id,
            'phone' => '081200000001', 'status' => 'aktif',
        ]);

        $userSiswaB = User::create([
            'name' => 'Citra Lestari', 'email' => 'citra.lestari@siswa.com',
            'password' => Hash::make('password'), 'is_active' => true, 'branch_id' => $cabangA->id,
        ]);
        $userSiswaB->syncRoles(['siswa']);
        $siswaB = Student::create([
            'user_id' => $userSiswaB->id, 'name' => 'Citra Lestari',
            'nis' => 'SIS-2025-002', 'gender' => 'P', 'branch_id' => $cabangA->id,
            'phone' => '081200000002', 'status' => 'aktif',
        ]);

        $this->command->info('  ✅ 2 Siswa');

        // ── 2 KELAS ──────────────────────────────────────────────────── //
        $kelasA = SchoolClass::create([
            'nama_kelas' => 'Matematika Reguler A', 'cabang_id' => $cabangA->id,
            'mata_pelajaran_id' => $mapelA->id, 'guru_id' => $guruA->id,
            'tahun_akademik_id' => $tahun->id, 'kapasitas' => 15,
            'jumlah_pertemuan' => 8, 'jenis' => 'offline',
            'status' => 'aktif', 'billing_mode' => 'per_kelas',
        ]);
        $kelasA->siswa()->sync([$siswaA->id, $siswaB->id]);

        SchoolClass::create([
            'nama_kelas' => 'Bahasa Inggris Reguler A', 'cabang_id' => $cabangB->id,
            'mata_pelajaran_id' => $mapelB->id, 'guru_id' => $guruB->id,
            'tahun_akademik_id' => $tahun->id, 'kapasitas' => 12,
            'jumlah_pertemuan' => 8, 'jenis' => 'offline',
            'status' => 'aktif', 'billing_mode' => 'per_kelas',
        ]);

        $this->command->info('  ✅ 2 Kelas');

        // ── 2 MODUL AKADEMIK ─────────────────────────────────────────── //
        Module::create([
            'kode_modul' => 'MOD-001', 'mata_pelajaran_id' => $mapelA->id,
            'diupload_oleh' => $userGuruA->id, 'judul' => 'Modul Aljabar Dasar',
            'deskripsi' => 'Materi aljabar dasar untuk kelas 10 SMA.',
            'jenis' => 'pdf', 'is_gratis' => true, 'status' => 'aktif',
        ]);
        Module::create([
            'kode_modul' => 'MOD-002', 'mata_pelajaran_id' => $mapelB->id,
            'diupload_oleh' => $userGuruB->id, 'judul' => 'Modul Grammar Dasar',
            'deskripsi' => 'Panduan grammar Bahasa Inggris untuk pemula.',
            'jenis' => 'pdf', 'is_gratis' => true, 'status' => 'aktif',
        ]);

        $this->command->info('  ✅ 2 Modul Akademik');

        // ── 2 INVOICE & PAYMENT ──────────────────────────────────────── //
        $ownerUser = User::where('email', 'adminpusatsci@akademi.com')->first();

        $invA = Invoice::create([
            'siswa_id' => $siswaA->id, 'cabang_id' => $cabangA->id,
            'nomor_invoice' => 'INV-2025-0001',
            'deskripsi' => 'Biaya Paket Reguler SMA - Januari 2025',
            'subtotal' => 750000, 'total' => 750000, 'status' => 'lunas',
            'periode' => '2025-01', 'jatuh_tempo' => '2025-01-15',
        ]);
        Payment::create([
            'invoice_id' => $invA->id, 'siswa_id' => $siswaA->id,
            'cabang_id' => $cabangA->id, 'jumlah' => 750000,
            'metode' => 'transfer', 'status' => 'verified',
            'tanggal_pembayaran' => '2025-01-10',
        ]);

        Invoice::create([
            'siswa_id' => $siswaB->id, 'cabang_id' => $cabangA->id,
            'nomor_invoice' => 'INV-2025-0002',
            'deskripsi' => 'Biaya Paket Intensif SNBT - Februari 2025',
            'subtotal' => 2500000, 'total' => 2500000, 'status' => 'belum_bayar',
            'periode' => '2025-02', 'jatuh_tempo' => '2025-02-15',
        ]);

        $this->command->info('  ✅ 2 Invoice + 1 Payment');

        // ── 2 GAJI GURU ──────────────────────────────────────────────── //
        Salary::create([
            'guru_id' => $guruA->id, 'cabang_id' => $cabangA->id,
            'periode' => '2025-01', 'tipe_gaji' => 'bulanan',
            'gaji_pokok' => 4500000, 'bonus' => 500000, 'potongan' => 0,
            'total_gaji' => 5000000, 'status' => 'dibayar',
            'tanggal_pembayaran' => '2025-01-28',
        ]);
        Salary::create([
            'guru_id' => $guruB->id, 'cabang_id' => $cabangB->id,
            'periode' => '2025-01', 'tipe_gaji' => 'bulanan',
            'gaji_pokok' => 4000000, 'bonus' => 0, 'potongan' => 0,
            'total_gaji' => 4000000, 'status' => 'pending',
        ]);

        $this->command->info('  ✅ 2 Gaji Guru');

        // ── 2 PENGUMUMAN ─────────────────────────────────────────────── //
        Announcement::create([
            'cabang_id' => $cabangA->id, 'dibuat_oleh' => $ownerUser?->id ?? 1,
            'judul' => 'Selamat Datang di Akademi SCI',
            'konten' => 'Selamat datang di sistem manajemen Akademi SCI. Kami siap mendukung proses belajar Anda.',
            'jenis' => 'info', 'target' => 'semua',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(6)->toDateString(),
            'is_pinned' => true, 'status' => 'aktif',
        ]);
        Announcement::create([
            'cabang_id' => $cabangA->id, 'dibuat_oleh' => $ownerUser?->id ?? 1,
            'judul' => 'Jadwal Tryout SNBT Bulan Februari',
            'konten' => 'Tryout SNBT akan diadakan pada 15 Februari 2025. Harap mempersiapkan diri dengan baik.',
            'jenis' => 'event', 'target' => 'siswa',
            'tanggal_mulai' => '2025-02-01',
            'tanggal_selesai' => '2025-02-15',
            'is_pinned' => false, 'status' => 'aktif',
        ]);

        $this->command->info('  ✅ 2 Pengumuman');

        // ── 2 TRYOUT ─────────────────────────────────────────────────── //
        Tryout::create([
            'cabang_id' => $cabangA->id, 'dibuat_oleh' => $adminUser?->id ?? $ownerUser?->id ?? 1,
            'judul' => 'Tryout SNBT Februari 2025',
            'deskripsi' => 'Simulasi SNBT dengan soal terkini.',
            'kategori' => 'SNBT', 'durasi_menit' => 120, 'total_soal' => 40,
            'nilai_kelulusan' => 60, 'maksimal_percobaan' => 1,
            'waktu_mulai' => '2025-02-15 08:00:00',
            'waktu_selesai' => '2025-02-15 10:00:00',
            'is_random' => false, 'tampilkan_hasil_langsung' => true,
            'tampilkan_kunci_jawaban' => false, 'status' => 'aktif',
        ]);
        Tryout::create([
            'cabang_id' => $cabangA->id, 'dibuat_oleh' => $adminUser?->id ?? $ownerUser?->id ?? 1,
            'judul' => 'Tryout Matematika Reguler',
            'deskripsi' => 'Latihan soal matematika untuk penilaian bulanan.',
            'kategori' => 'Reguler', 'durasi_menit' => 60, 'total_soal' => 20,
            'nilai_kelulusan' => 70, 'maksimal_percobaan' => 2,
            'waktu_mulai' => '2025-03-01 09:00:00',
            'waktu_selesai' => '2025-03-01 10:00:00',
            'is_random' => false, 'tampilkan_hasil_langsung' => true,
            'tampilkan_kunci_jawaban' => true, 'status' => 'draft',
        ]);

        $this->command->info('  ✅ 2 Tryout');

        // ── 2 SERTIFIKAT ─────────────────────────────────────────────── //
        Certificate::create([
            'siswa_id' => $siswaA->id, 'cabang_id' => $cabangA->id,
            'course_id' => $mapelA->id, 'diterbitkan_oleh' => $ownerUser?->id ?? 1,
            'nomor_sertifikat' => 'CERT-2025-0001',
            'jenis' => 'kelulusan', 'judul' => 'Sertifikat Kelulusan Matematika',
            'deskripsi' => 'Dinyatakan lulus program Matematika Reguler.',
            'tanggal_terbit' => '2025-01-31',
        ]);
        Certificate::create([
            'siswa_id' => $siswaB->id, 'cabang_id' => $cabangA->id,
            'course_id' => $mapelB->id, 'diterbitkan_oleh' => $ownerUser?->id ?? 1,
            'nomor_sertifikat' => 'CERT-2025-0002',
            'jenis' => 'kelulusan', 'judul' => 'Sertifikat Kelulusan Bahasa Inggris',
            'deskripsi' => 'Dinyatakan lulus program Bahasa Inggris Dasar.',
            'tanggal_terbit' => '2025-01-31',
        ]);

        $this->command->info('  ✅ 2 Sertifikat');

        // ── GURU DEMO (gurusci@gmail.com) ────────────────────────────── //
        $guruDemo = User::firstOrCreate(['email' => 'gurusci@gmail.com'], [
            'name' => 'Ahmad Fauzi, S.Si.', 'password' => Hash::make('password123'),
            'is_active' => true, 'branch_id' => $cabangA->id,
        ]);
        $guruDemo->syncRoles(['guru']);
        Teacher::firstOrCreate(['user_id' => $guruDemo->id], [
            'name' => 'Ahmad Fauzi, S.Si.', 'email' => 'gurusci@gmail.com',
            'nig' => 'NIG-2024-000', 'gender' => 'L',
            'branch_id' => $cabangA->id, 'subjects' => ['Matematika'],
            'status' => 'aktif', 'salary_base' => 4500000, 'jenis_guru' => 'tetap',
        ]);

        // ── 2 PENDAFTARAN SISWA BARU (untuk dashboard Siswa Terbaru Mendaftar) ── //
        try { DB::table('student_registrations')->truncate(); } catch (\Exception $e) {}

        StudentRegistration::create([
            'no_reg'           => 'REG-2025-0001',
            'name'             => 'Rizal Maulana',
            'phone'            => '081298765401',
            'gender'           => 'L',
            'education_level'  => 'SMA',
            'status'           => 'pending',
            'branch'           => $cabangA->name,
            'interests'        => ['Matematika', 'Fisika'],
            'interest_sessions' => ['Matematika' => 8, 'Fisika' => 8],
            'notes'            => 'Ingin mempersiapkan SNBT tahun depan.',
        ]);

        StudentRegistration::create([
            'no_reg'           => 'REG-2025-0002',
            'name'             => 'Nadia Putri Utami',
            'phone'            => '082198765402',
            'gender'           => 'P',
            'education_level'  => 'SMP',
            'status'           => 'verified',
            'branch'           => $cabangA->name,
            'interests'        => ['Bahasa Inggris'],
            'interest_sessions' => ['Bahasa Inggris' => 8],
            'notes'            => 'Perlu persiapan ujian sekolah.',
        ]);

        $this->command->info('  ✅ 2 Pendaftaran siswa baru');

        $this->command->info('✅ SlimSeeder selesai! ' . PHP_EOL .
            '   Owner: adminpusatsci@akademi.com / password' . PHP_EOL .
            '   Admin: admincabangasci@akademi.com / password' . PHP_EOL .
            '   Guru : gurusci@gmail.com / password123'
        );
    }
}
