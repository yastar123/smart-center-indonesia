<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
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
use App\Models\Tryout;
use App\Models\Question;
use App\Models\TryoutAttempt;
use App\Models\Grade;
use App\Models\Salary;
use App\Models\Announcement;
use App\Models\Certificate;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('🌱 Memulai seeding data demo...');

        // ------------------------------------------------------------------ //
        // 1. TAHUN AKADEMIK                                                    //
        // ------------------------------------------------------------------ //
        $tahunAktif = AcademicYear::firstOrCreate(
            ['name' => '2024/2025'],
            [
                'year_start' => 2024,
                'year_end'   => 2025,
                'is_active'  => true,
            ]
        );
        $tahunLalu = AcademicYear::firstOrCreate(
            ['name' => '2023/2024'],
            [
                'year_start' => 2023,
                'year_end'   => 2024,
                'is_active'  => false,
            ]
        );
        $this->command->info('  ✅ Tahun akademik');

        // ------------------------------------------------------------------ //
        // 2. CABANG                                                            //
        // ------------------------------------------------------------------ //
        $cabangPusat = Branch::firstOrCreate(
            ['email' => 'cabang.pusat@akademibimbel.com'],
            [
                'name'    => 'Cabang Pusat Jakarta',
                'city'    => 'Jakarta',
                'regency' => 'Jakarta Selatan',
                'address' => 'Jl. Sudirman No. 10, Kebayoran Baru, Jakarta Selatan',
                'phone'   => '021-5551001',
                'status'  => 'active',
                'can_students'  => true,
                'can_teachers'  => true,
                'can_schedules' => true,
                'can_payments'  => true,
                'can_tryouts'   => true,
            ]
        );
        $cabangBandung = Branch::firstOrCreate(
            ['email' => 'cabang.bandung@akademibimbel.com'],
            [
                'name'    => 'Cabang Bandung',
                'city'    => 'Bandung',
                'regency' => 'Bandung Kota',
                'address' => 'Jl. Dago No. 25, Coblong, Bandung',
                'phone'   => '022-2501234',
                'status'  => 'active',
                'can_students'  => true,
                'can_teachers'  => true,
                'can_schedules' => true,
                'can_payments'  => true,
                'can_tryouts'   => true,
            ]
        );
        $cabangSurabaya = Branch::firstOrCreate(
            ['email' => 'cabang.surabaya@akademibimbel.com'],
            [
                'name'    => 'Cabang Surabaya',
                'city'    => 'Surabaya',
                'regency' => 'Surabaya Pusat',
                'address' => 'Jl. Raya Darmo No. 8, Wonokromo, Surabaya',
                'phone'   => '031-5671234',
                'status'  => 'active',
                'can_students'  => true,
                'can_teachers'  => true,
                'can_schedules' => true,
                'can_payments'  => true,
                'can_tryouts'   => false,
            ]
        );
        $this->command->info('  ✅ Cabang (3 cabang)');

        // ------------------------------------------------------------------ //
        // 3. USER ADMIN PER CABANG                                             //
        // ------------------------------------------------------------------ //
        $adminPusat = $this->upsertUser(
            'adminpusat@akademibimbel.com',
            ['name' => 'Admin Pusat Jakarta', 'password' => Hash::make('password'), 'is_active' => true, 'branch_id' => $cabangPusat->id]
        );
        $adminPusat->syncRoles(['admin']);
        $cabangPusat->update(['admin_id' => $adminPusat->id]);

        $adminBandung = $this->upsertUser(
            'adminbandung@akademibimbel.com',
            ['name' => 'Admin Cabang Bandung', 'password' => Hash::make('password'), 'is_active' => true, 'branch_id' => $cabangBandung->id]
        );
        $adminBandung->syncRoles(['admin']);
        $cabangBandung->update(['admin_id' => $adminBandung->id]);

        $adminSurabaya = $this->upsertUser(
            'adminsurabaya@akademibimbel.com',
            ['name' => 'Admin Cabang Surabaya', 'password' => Hash::make('password'), 'is_active' => true, 'branch_id' => $cabangSurabaya->id]
        );
        $adminSurabaya->syncRoles(['admin']);
        $cabangSurabaya->update(['admin_id' => $adminSurabaya->id]);

        $this->command->info('  ✅ User admin cabang');

        // ------------------------------------------------------------------ //
        // 4. MATA PELAJARAN (COURSES)                                          //
        // ------------------------------------------------------------------ //
        $courses = [];
        $courseData = [
            // Cabang Pusat
            ['cabang_id' => $cabangPusat->id, 'kode' => 'MAT-001', 'nama' => 'Matematika', 'kategori' => 'Akademik', 'deskripsi' => 'Mata pelajaran matematika dasar hingga lanjutan mencakup aljabar, geometri, dan statistika.', 'status' => 'aktif'],
            ['cabang_id' => $cabangPusat->id, 'kode' => 'FIS-001', 'nama' => 'Fisika', 'kategori' => 'Akademik', 'deskripsi' => 'Fisika dasar hingga lanjutan, mekanika, termodinamika, listrik magnet, dan gelombang.', 'status' => 'aktif'],
            ['cabang_id' => $cabangPusat->id, 'kode' => 'KIM-001', 'nama' => 'Kimia', 'kategori' => 'Akademik', 'deskripsi' => 'Kimia umum, reaksi kimia, stoikiometri, termokimia, dan kimia organik dasar.', 'status' => 'aktif'],
            ['cabang_id' => $cabangPusat->id, 'kode' => 'BIO-001', 'nama' => 'Biologi', 'kategori' => 'Akademik', 'deskripsi' => 'Biologi sel, genetika, ekologi, anatomi manusia, dan fisiologi tumbuhan.', 'status' => 'aktif'],
            ['cabang_id' => $cabangPusat->id, 'kode' => 'ING-001', 'nama' => 'Bahasa Inggris', 'kategori' => 'Bahasa', 'deskripsi' => 'Grammar, reading comprehension, writing, speaking, dan listening skills.', 'status' => 'aktif'],
            ['cabang_id' => $cabangPusat->id, 'kode' => 'SNBT-001', 'nama' => 'Persiapan SNBT', 'kategori' => 'Ujian', 'deskripsi' => 'Persiapan UTBK-SNBT meliputi TPS, Literasi Bahasa, dan Penalaran Matematika.', 'status' => 'aktif'],
            // Cabang Bandung
            ['cabang_id' => $cabangBandung->id, 'kode' => 'MAT-BDG', 'nama' => 'Matematika', 'kategori' => 'Akademik', 'deskripsi' => 'Matematika dasar dan lanjutan untuk SD, SMP, SMA.', 'status' => 'aktif'],
            ['cabang_id' => $cabangBandung->id, 'kode' => 'ING-BDG', 'nama' => 'Bahasa Inggris', 'kategori' => 'Bahasa', 'deskripsi' => 'English course untuk semua jenjang.', 'status' => 'aktif'],
            ['cabang_id' => $cabangBandung->id, 'kode' => 'FIS-BDG', 'nama' => 'Fisika', 'kategori' => 'Akademik', 'deskripsi' => 'Fisika SMA dan persiapan ujian nasional.', 'status' => 'aktif'],
            // Cabang Surabaya
            ['cabang_id' => $cabangSurabaya->id, 'kode' => 'MAT-SBY', 'nama' => 'Matematika', 'kategori' => 'Akademik', 'deskripsi' => 'Matematika komprehensif untuk semua jenjang.', 'status' => 'aktif'],
            ['cabang_id' => $cabangSurabaya->id, 'kode' => 'ING-SBY', 'nama' => 'Bahasa Inggris', 'kategori' => 'Bahasa', 'deskripsi' => 'Kursus Bahasa Inggris komunikatif.', 'status' => 'aktif'],
        ];
        foreach ($courseData as $cd) {
            $courses[$cd['kode']] = Course::firstOrCreate(['kode' => $cd['kode'], 'cabang_id' => $cd['cabang_id']], $cd);
        }

        // Course Fees
        $feeMap = [
            'MAT-001' => 350000, 'FIS-001' => 350000, 'KIM-001' => 350000,
            'BIO-001' => 350000, 'ING-001' => 300000, 'SNBT-001' => 500000,
            'MAT-BDG' => 300000, 'ING-BDG' => 275000, 'FIS-BDG' => 300000,
            'MAT-SBY' => 325000, 'ING-SBY' => 275000,
        ];
        foreach ($feeMap as $kode => $amount) {
            if (isset($courses[$kode])) {
                CourseFee::firstOrCreate(['course_id' => $courses[$kode]->id], ['amount' => $amount]);
            }
        }
        $this->command->info('  ✅ Mata pelajaran & biaya kursus');

        // ------------------------------------------------------------------ //
        // 5. PAKET BELAJAR                                                     //
        // ------------------------------------------------------------------ //
        $packages = [];
        $packageData = [
            [
                'cabang_id' => $cabangPusat->id, 'nama' => 'Paket Reguler SMA', 'jenis' => 'reguler',
                'deskripsi' => 'Paket bimbingan belajar reguler untuk siswa SMA, 2x pertemuan per minggu.',
                'harga' => 750000, 'durasi_bulan' => 1, 'jumlah_pertemuan' => 8,
                'metode_absensi' => 'dual', 'tipe_kelas' => 'offline', 'status' => 'aktif', 'is_unggulan' => false,
                'fitur' => ['8 pertemuan/bulan', 'Modul digital', 'Evaluasi bulanan', 'Konsultasi gratis'],
            ],
            [
                'cabang_id' => $cabangPusat->id, 'nama' => 'Paket Intensif SNBT', 'jenis' => 'intensif',
                'deskripsi' => 'Program intensif persiapan SNBT selama 3 bulan dengan tryout rutin.',
                'harga' => 2500000, 'durasi_bulan' => 3, 'jumlah_pertemuan' => 36,
                'metode_absensi' => 'dual', 'tipe_kelas' => 'offline', 'status' => 'aktif', 'is_unggulan' => true,
                'fitur' => ['36 pertemuan', 'Tryout mingguan', 'Analisis hasil', 'Mentor pribadi', 'Modul eksklusif'],
            ],
            [
                'cabang_id' => $cabangPusat->id, 'nama' => 'Paket Online Basic', 'jenis' => 'online',
                'deskripsi' => 'Belajar online fleksibel, cocok untuk siswa yang sibuk atau jauh dari cabang.',
                'harga' => 500000, 'durasi_bulan' => 1, 'jumlah_pertemuan' => 8,
                'metode_absensi' => 'self', 'tipe_kelas' => 'online', 'status' => 'aktif', 'is_unggulan' => false,
                'fitur' => ['8 sesi online', 'Rekaman kelas', 'Modul digital', 'Forum diskusi'],
            ],
            [
                'cabang_id' => $cabangBandung->id, 'nama' => 'Paket Reguler Bandung', 'jenis' => 'reguler',
                'deskripsi' => 'Paket bimbel reguler untuk siswa di cabang Bandung.',
                'harga' => 700000, 'durasi_bulan' => 1, 'jumlah_pertemuan' => 8,
                'metode_absensi' => 'dual', 'tipe_kelas' => 'offline', 'status' => 'aktif', 'is_unggulan' => false,
                'fitur' => ['8 pertemuan/bulan', 'Modul belajar', 'Evaluasi bulanan'],
            ],
            [
                'cabang_id' => $cabangSurabaya->id, 'nama' => 'Paket Reguler Surabaya', 'jenis' => 'reguler',
                'deskripsi' => 'Paket bimbel reguler untuk siswa di cabang Surabaya.',
                'harga' => 725000, 'durasi_bulan' => 1, 'jumlah_pertemuan' => 8,
                'metode_absensi' => 'dual', 'tipe_kelas' => 'offline', 'status' => 'aktif', 'is_unggulan' => false,
                'fitur' => ['8 pertemuan/bulan', 'Modul belajar', 'Evaluasi bulanan'],
            ],
        ];
        foreach ($packageData as $pd) {
            $packages[$pd['nama']] = Package::firstOrCreate(['nama' => $pd['nama'], 'cabang_id' => $pd['cabang_id']], $pd);
        }
        $this->command->info('  ✅ Paket belajar');

        // ------------------------------------------------------------------ //
        // 6. GURU                                                              //
        // ------------------------------------------------------------------ //
        $teachers = [];
        $teacherData = [
            // Pusat
            ['email' => 'budi.santoso@guru.akademibimbel.com', 'name' => 'Budi Santoso, S.Pd.', 'branch_id' => $cabangPusat->id,
             'nig' => 'NIG-2020-001', 'gender' => 'L', 'birth_date' => '1985-03-15', 'birth_place' => 'Yogyakarta',
             'address' => 'Jl. Tebet Barat No. 12, Jakarta Selatan', 'phone' => '081234560001',
             'subjects' => ['Matematika', 'Fisika'], 'education' => 'S1 Pendidikan Matematika UNY',
             'salary_base' => 4500000, 'join_date' => '2020-01-15', 'status' => 'aktif', 'jenis_guru' => 'tetap'],
            ['email' => 'sari.dewi@guru.akademibimbel.com', 'name' => 'Sari Dewi, M.Sc.', 'branch_id' => $cabangPusat->id,
             'nig' => 'NIG-2020-002', 'gender' => 'P', 'birth_date' => '1988-07-22', 'birth_place' => 'Bandung',
             'address' => 'Jl. Mampang Prapatan No. 5, Jakarta Selatan', 'phone' => '081234560002',
             'subjects' => ['Kimia', 'Biologi'], 'education' => 'S2 Kimia ITB',
             'salary_base' => 5000000, 'join_date' => '2020-03-01', 'status' => 'aktif', 'jenis_guru' => 'tetap'],
            ['email' => 'rizky.pratama@guru.akademibimbel.com', 'name' => 'Rizky Pratama, S.Pd.', 'branch_id' => $cabangPusat->id,
             'nig' => 'NIG-2021-003', 'gender' => 'L', 'birth_date' => '1992-11-05', 'birth_place' => 'Jakarta',
             'address' => 'Jl. Fatmawati No. 88, Jakarta Selatan', 'phone' => '081234560003',
             'subjects' => ['Bahasa Inggris'], 'education' => 'S1 Sastra Inggris UI',
             'salary_base' => 4000000, 'join_date' => '2021-06-01', 'status' => 'aktif', 'jenis_guru' => 'tetap'],
            ['email' => 'gurusci@gmail.com', 'name' => 'Ahmad Fauzi, S.Si.', 'branch_id' => $cabangPusat->id,
             'nig' => 'NIG-2021-004', 'gender' => 'L', 'birth_date' => '1990-04-18', 'birth_place' => 'Semarang',
             'address' => 'Jl. Ciputat Raya No. 34, Jakarta Selatan', 'phone' => '081234560004',
             'subjects' => ['Matematika', 'Persiapan SNBT'], 'education' => 'S1 Matematika UNDIP',
             'salary_base' => 4500000, 'join_date' => '2021-08-01', 'status' => 'aktif', 'jenis_guru' => 'tetap'],
            // Bandung
            ['email' => 'hani.rahayu@guru.akademibimbel.com', 'name' => 'Hani Rahayu, S.Pd.', 'branch_id' => $cabangBandung->id,
             'nig' => 'NIG-2022-005', 'gender' => 'P', 'birth_date' => '1991-09-30', 'birth_place' => 'Bandung',
             'address' => 'Jl. Cihampelas No. 15, Bandung', 'phone' => '081234560005',
             'subjects' => ['Matematika', 'Fisika'], 'education' => 'S1 Pendidikan Matematika UPI',
             'salary_base' => 4000000, 'join_date' => '2022-01-10', 'status' => 'aktif', 'jenis_guru' => 'tetap'],
            ['email' => 'dimas.arya@guru.akademibimbel.com', 'name' => 'Dimas Arya, S.Pd.', 'branch_id' => $cabangBandung->id,
             'nig' => 'NIG-2022-006', 'gender' => 'L', 'birth_date' => '1994-02-14', 'birth_place' => 'Sumedang',
             'address' => 'Jl. Buah Batu No. 40, Bandung', 'phone' => '081234560006',
             'subjects' => ['Bahasa Inggris'], 'education' => 'S1 Bahasa Inggris UNPAD',
             'salary_base' => 3800000, 'join_date' => '2022-04-01', 'status' => 'aktif', 'jenis_guru' => 'honorer'],
            // Surabaya
            ['email' => 'yuni.kartika@guru.akademibimbel.com', 'name' => 'Yuni Kartika, M.Pd.', 'branch_id' => $cabangSurabaya->id,
             'nig' => 'NIG-2022-007', 'gender' => 'P', 'birth_date' => '1987-06-25', 'birth_place' => 'Surabaya',
             'address' => 'Jl. Diponegoro No. 100, Surabaya', 'phone' => '081234560007',
             'subjects' => ['Matematika', 'Bahasa Inggris'], 'education' => 'S2 Pendidikan Matematika UNESA',
             'salary_base' => 4800000, 'join_date' => '2022-07-01', 'status' => 'aktif', 'jenis_guru' => 'tetap'],
        ];

        foreach ($teacherData as $td) {
            $userT = $this->upsertUser($td['email'], [
                'name' => $td['name'], 'password' => Hash::make('password123'),
                'is_active' => true, 'branch_id' => $td['branch_id'],
            ]);
            $userT->syncRoles(['guru']);

            $teacher = Teacher::firstOrCreate(['nig' => $td['nig']], array_merge($td, ['user_id' => $userT->id]));
            $teachers[$td['nig']] = $teacher;

            // Attach courses to teacher
            $coursesToAttach = Course::where('cabang_id', $td['branch_id'])
                ->whereIn('nama', $td['subjects'])->pluck('id')->toArray();
            $teacher->courses()->syncWithoutDetaching($coursesToAttach);
        }
        $this->command->info('  ✅ Guru (7 guru)');

        // ------------------------------------------------------------------ //
        // 7. KELAS                                                             //
        // ------------------------------------------------------------------ //
        $classes = [];
        $classData = [
            // Pusat - Kelas Matematika SMA
            ['cabang_id' => $cabangPusat->id, 'nama_kelas' => 'Matematika SMA Reguler A',
             'kode' => 'MAT-001', 'guru_nig' => 'NIG-2020-001',
             'kapasitas' => 15, 'jumlah_pertemuan' => 8, 'jenis' => 'offline', 'status' => 'aktif', 'billing_mode' => 'per_kelas'],
            ['cabang_id' => $cabangPusat->id, 'nama_kelas' => 'Fisika SMA Reguler A',
             'kode' => 'FIS-001', 'guru_nig' => 'NIG-2020-001',
             'kapasitas' => 12, 'jumlah_pertemuan' => 8, 'jenis' => 'offline', 'status' => 'aktif', 'billing_mode' => 'per_kelas'],
            ['cabang_id' => $cabangPusat->id, 'nama_kelas' => 'Kimia SMA Reguler A',
             'kode' => 'KIM-001', 'guru_nig' => 'NIG-2020-002',
             'kapasitas' => 12, 'jumlah_pertemuan' => 8, 'jenis' => 'offline', 'status' => 'aktif', 'billing_mode' => 'per_kelas'],
            ['cabang_id' => $cabangPusat->id, 'nama_kelas' => 'Bahasa Inggris SMA Reguler',
             'kode' => 'ING-001', 'guru_nig' => 'NIG-2021-003',
             'kapasitas' => 15, 'jumlah_pertemuan' => 8, 'jenis' => 'offline', 'status' => 'aktif', 'billing_mode' => 'per_kelas'],
            ['cabang_id' => $cabangPusat->id, 'nama_kelas' => 'Intensif SNBT Batch 1',
             'kode' => 'SNBT-001', 'guru_nig' => 'NIG-2021-004',
             'kapasitas' => 20, 'jumlah_pertemuan' => 36, 'jenis' => 'offline', 'status' => 'aktif', 'billing_mode' => 'per_paket'],
            // Bandung
            ['cabang_id' => $cabangBandung->id, 'nama_kelas' => 'Matematika Reguler Bandung A',
             'kode' => 'MAT-BDG', 'guru_nig' => 'NIG-2022-005',
             'kapasitas' => 12, 'jumlah_pertemuan' => 8, 'jenis' => 'offline', 'status' => 'aktif', 'billing_mode' => 'per_kelas'],
            ['cabang_id' => $cabangBandung->id, 'nama_kelas' => 'Bahasa Inggris Bandung A',
             'kode' => 'ING-BDG', 'guru_nig' => 'NIG-2022-006',
             'kapasitas' => 10, 'jumlah_pertemuan' => 8, 'jenis' => 'offline', 'status' => 'aktif', 'billing_mode' => 'per_kelas'],
            // Surabaya
            ['cabang_id' => $cabangSurabaya->id, 'nama_kelas' => 'Matematika Reguler Surabaya A',
             'kode' => 'MAT-SBY', 'guru_nig' => 'NIG-2022-007',
             'kapasitas' => 12, 'jumlah_pertemuan' => 8, 'jenis' => 'offline', 'status' => 'aktif', 'billing_mode' => 'per_kelas'],
        ];

        foreach ($classData as $cd) {
            if (!isset($courses[$cd['kode']])) continue;
            $guru = $teachers[$cd['guru_nig']] ?? null;
            if (!$guru) continue;
            $kelas = SchoolClass::firstOrCreate(
                ['nama_kelas' => $cd['nama_kelas'], 'cabang_id' => $cd['cabang_id']],
                [
                    'mata_pelajaran_id' => $courses[$cd['kode']]->id,
                    'guru_id'           => $guru->id,
                    'tahun_akademik_id' => $tahunAktif->id,
                    'kapasitas'         => $cd['kapasitas'],
                    'jumlah_pertemuan'  => $cd['jumlah_pertemuan'],
                    'jenis'             => $cd['jenis'],
                    'status'            => $cd['status'],
                    'billing_mode'      => $cd['billing_mode'],
                ]
            );
            $classes[$cd['nama_kelas']] = $kelas;
        }
        $this->command->info('  ✅ Kelas (8 kelas)');

        // ------------------------------------------------------------------ //
        // 8. SISWA                                                             //
        // ------------------------------------------------------------------ //
        $students = [];
        $studentData = [
            // Pusat - 8 siswa
            ['email' => 'andi.nugroho@siswa.com', 'name' => 'Andi Nugroho', 'branch_id' => $cabangPusat->id,
             'nis' => 'SIS-2024-001', 'gender' => 'L', 'birth_date' => '2007-04-12', 'birth_place' => 'Jakarta',
             'address' => 'Jl. Merdeka No. 5, Jakarta Selatan', 'phone' => '087812340001',
             'parent_name' => 'Bapak Nugroho', 'parent_phone' => '081312340001',
             'school_name' => 'SMAN 70 Jakarta', 'grade' => 'XI', 'status' => 'aktif',
             'join_date' => '2024-01-10', 'kategori_peserta_didik' => 'SMA',
             'package_key' => 'Paket Reguler SMA', 'classes' => ['Matematika SMA Reguler A', 'Fisika SMA Reguler A']],
            ['email' => 'citra.lestari@siswa.com', 'name' => 'Citra Lestari', 'branch_id' => $cabangPusat->id,
             'nis' => 'SIS-2024-002', 'gender' => 'P', 'birth_date' => '2007-08-23', 'birth_place' => 'Bogor',
             'address' => 'Jl. Kemang Raya No. 12, Jakarta Selatan', 'phone' => '087812340002',
             'parent_name' => 'Ibu Lestari', 'parent_phone' => '081312340002',
             'school_name' => 'SMAN 34 Jakarta', 'grade' => 'XI', 'status' => 'aktif',
             'join_date' => '2024-01-15', 'kategori_peserta_didik' => 'SMA',
             'package_key' => 'Paket Reguler SMA', 'classes' => ['Matematika SMA Reguler A', 'Kimia SMA Reguler A', 'Bahasa Inggris SMA Reguler']],
            ['email' => 'fajar.hidayat@siswa.com', 'name' => 'Fajar Hidayat', 'branch_id' => $cabangPusat->id,
             'nis' => 'SIS-2024-003', 'gender' => 'L', 'birth_date' => '2006-01-30', 'birth_place' => 'Depok',
             'address' => 'Jl. Taman Makam Pahlawan No. 3, Depok', 'phone' => '087812340003',
             'parent_name' => 'Bapak Hidayat', 'parent_phone' => '081312340003',
             'school_name' => 'SMAN 5 Depok', 'grade' => 'XII', 'status' => 'aktif',
             'join_date' => '2024-02-01', 'kategori_peserta_didik' => 'SMA',
             'package_key' => 'Paket Intensif SNBT', 'classes' => ['Intensif SNBT Batch 1']],
            ['email' => 'gita.permata@siswa.com', 'name' => 'Gita Permata', 'branch_id' => $cabangPusat->id,
             'nis' => 'SIS-2024-004', 'gender' => 'P', 'birth_date' => '2006-05-17', 'birth_place' => 'Tangerang',
             'address' => 'Jl. BSD No. 22, Tangerang Selatan', 'phone' => '087812340004',
             'parent_name' => 'Ibu Permata', 'parent_phone' => '081312340004',
             'school_name' => 'SMAN 1 Serpong', 'grade' => 'XII', 'status' => 'aktif',
             'join_date' => '2024-02-05', 'kategori_peserta_didik' => 'SMA',
             'package_key' => 'Paket Intensif SNBT', 'classes' => ['Intensif SNBT Batch 1', 'Matematika SMA Reguler A']],
            ['email' => 'hendra.putra@siswa.com', 'name' => 'Hendra Putra', 'branch_id' => $cabangPusat->id,
             'nis' => 'SIS-2024-005', 'gender' => 'L', 'birth_date' => '2008-10-08', 'birth_place' => 'Jakarta',
             'address' => 'Jl. Cipete No. 7, Jakarta Selatan', 'phone' => '087812340005',
             'parent_name' => 'Bapak Putra', 'parent_phone' => '081312340005',
             'school_name' => 'SMPN 49 Jakarta', 'grade' => 'IX', 'status' => 'aktif',
             'join_date' => '2024-03-01', 'kategori_peserta_didik' => 'SMP',
             'package_key' => 'Paket Reguler SMA', 'classes' => ['Matematika SMA Reguler A', 'Bahasa Inggris SMA Reguler']],
            ['email' => 'indah.sari@siswa.com', 'name' => 'Indah Sari', 'branch_id' => $cabangPusat->id,
             'nis' => 'SIS-2024-006', 'gender' => 'P', 'birth_date' => '2007-12-25', 'birth_place' => 'Bekasi',
             'address' => 'Jl. Galaxy No. 45, Bekasi', 'phone' => '087812340006',
             'parent_name' => 'Bapak Sari', 'parent_phone' => '081312340006',
             'school_name' => 'SMAN 1 Bekasi', 'grade' => 'XI', 'status' => 'aktif',
             'join_date' => '2024-03-10', 'kategori_peserta_didik' => 'SMA',
             'package_key' => 'Paket Online Basic', 'classes' => ['Bahasa Inggris SMA Reguler']],
            ['email' => 'joko.santoso@siswa.com', 'name' => 'Joko Santoso', 'branch_id' => $cabangPusat->id,
             'nis' => 'SIS-2024-007', 'gender' => 'L', 'birth_date' => '2009-02-14', 'birth_place' => 'Jakarta',
             'address' => 'Jl. Pesanggrahan No. 8, Jakarta Barat', 'phone' => '087812340007',
             'parent_name' => 'Ibu Santoso', 'parent_phone' => '081312340007',
             'school_name' => 'SMPN 115 Jakarta', 'grade' => 'VIII', 'status' => 'aktif',
             'join_date' => '2024-04-01', 'kategori_peserta_didik' => 'SMP',
             'package_key' => 'Paket Reguler SMA', 'classes' => ['Matematika SMA Reguler A']],
            ['email' => 'kartini.wulandari@siswa.com', 'name' => 'Kartini Wulandari', 'branch_id' => $cabangPusat->id,
             'nis' => 'SIS-2024-008', 'gender' => 'P', 'birth_date' => '2006-07-21', 'birth_place' => 'Jakarta',
             'address' => 'Jl. Lebak Bulus No. 3, Jakarta Selatan', 'phone' => '087812340008',
             'parent_name' => 'Bapak Wulandari', 'parent_phone' => '081312340008',
             'school_name' => 'SMAN 86 Jakarta', 'grade' => 'XII', 'status' => 'aktif',
             'join_date' => '2024-04-15', 'kategori_peserta_didik' => 'SMA',
             'package_key' => 'Paket Intensif SNBT', 'classes' => ['Intensif SNBT Batch 1']],
            // Bandung - 4 siswa
            ['email' => 'luthfi.rahman@siswa.com', 'name' => 'Luthfi Rahman', 'branch_id' => $cabangBandung->id,
             'nis' => 'SIS-2024-009', 'gender' => 'L', 'birth_date' => '2007-03-19', 'birth_place' => 'Bandung',
             'address' => 'Jl. Setiabudi No. 20, Bandung', 'phone' => '087812340009',
             'parent_name' => 'Bapak Rahman', 'parent_phone' => '081312340009',
             'school_name' => 'SMAN 3 Bandung', 'grade' => 'XI', 'status' => 'aktif',
             'join_date' => '2024-01-20', 'kategori_peserta_didik' => 'SMA',
             'package_key' => 'Paket Reguler Bandung', 'classes' => ['Matematika Reguler Bandung A', 'Bahasa Inggris Bandung A']],
            ['email' => 'mira.kusuma@siswa.com', 'name' => 'Mira Kusuma', 'branch_id' => $cabangBandung->id,
             'nis' => 'SIS-2024-010', 'gender' => 'P', 'birth_date' => '2007-11-02', 'birth_place' => 'Cimahi',
             'address' => 'Jl. Cimahi No. 15, Cimahi', 'phone' => '087812340010',
             'parent_name' => 'Ibu Kusuma', 'parent_phone' => '081312340010',
             'school_name' => 'SMAN 1 Cimahi', 'grade' => 'XI', 'status' => 'aktif',
             'join_date' => '2024-02-10', 'kategori_peserta_didik' => 'SMA',
             'package_key' => 'Paket Reguler Bandung', 'classes' => ['Matematika Reguler Bandung A']],
            ['email' => 'naufal.ardiansyah@siswa.com', 'name' => 'Naufal Ardiansyah', 'branch_id' => $cabangBandung->id,
             'nis' => 'SIS-2024-011', 'gender' => 'L', 'birth_date' => '2008-06-15', 'birth_place' => 'Bandung',
             'address' => 'Jl. Pasteur No. 30, Bandung', 'phone' => '087812340011',
             'parent_name' => 'Bapak Ardiansyah', 'parent_phone' => '081312340011',
             'school_name' => 'SMPN 1 Bandung', 'grade' => 'IX', 'status' => 'aktif',
             'join_date' => '2024-03-01', 'kategori_peserta_didik' => 'SMP',
             'package_key' => 'Paket Reguler Bandung', 'classes' => ['Bahasa Inggris Bandung A']],
            ['email' => 'olivia.putri@siswa.com', 'name' => 'Olivia Putri', 'branch_id' => $cabangBandung->id,
             'nis' => 'SIS-2024-012', 'gender' => 'P', 'birth_date' => '2009-09-09', 'birth_place' => 'Bandung',
             'address' => 'Jl. Antapani No. 5, Bandung', 'phone' => '087812340012',
             'parent_name' => 'Ibu Putri', 'parent_phone' => '081312340012',
             'school_name' => 'SMPN 14 Bandung', 'grade' => 'VIII', 'status' => 'aktif',
             'join_date' => '2024-04-05', 'kategori_peserta_didik' => 'SMP',
             'package_key' => 'Paket Reguler Bandung', 'classes' => ['Bahasa Inggris Bandung A']],
            // Surabaya - 3 siswa
            ['email' => 'prasetyo.adi@siswa.com', 'name' => 'Prasetyo Adi', 'branch_id' => $cabangSurabaya->id,
             'nis' => 'SIS-2024-013', 'gender' => 'L', 'birth_date' => '2007-01-25', 'birth_place' => 'Surabaya',
             'address' => 'Jl. Darmo No. 50, Surabaya', 'phone' => '087812340013',
             'parent_name' => 'Bapak Adi', 'parent_phone' => '081312340013',
             'school_name' => 'SMAN 5 Surabaya', 'grade' => 'XI', 'status' => 'aktif',
             'join_date' => '2024-01-25', 'kategori_peserta_didik' => 'SMA',
             'package_key' => 'Paket Reguler Surabaya', 'classes' => ['Matematika Reguler Surabaya A']],
            ['email' => 'rini.agustina@siswa.com', 'name' => 'Rini Agustina', 'branch_id' => $cabangSurabaya->id,
             'nis' => 'SIS-2024-014', 'gender' => 'P', 'birth_date' => '2007-05-30', 'birth_place' => 'Gresik',
             'address' => 'Jl. Mayjend Sungkono No. 20, Surabaya', 'phone' => '087812340014',
             'parent_name' => 'Ibu Agustina', 'parent_phone' => '081312340014',
             'school_name' => 'SMAN 2 Surabaya', 'grade' => 'XI', 'status' => 'aktif',
             'join_date' => '2024-02-20', 'kategori_peserta_didik' => 'SMA',
             'package_key' => 'Paket Reguler Surabaya', 'classes' => ['Matematika Reguler Surabaya A']],
            ['email' => 'sandi.kurniawan@siswa.com', 'name' => 'Sandi Kurniawan', 'branch_id' => $cabangSurabaya->id,
             'nis' => 'SIS-2024-015', 'gender' => 'L', 'birth_date' => '2008-08-17', 'birth_place' => 'Sidoarjo',
             'address' => 'Jl. Sidoarjo No. 12, Sidoarjo', 'phone' => '087812340015',
             'parent_name' => 'Bapak Kurniawan', 'parent_phone' => '081312340015',
             'school_name' => 'SMPN 1 Sidoarjo', 'grade' => 'IX', 'status' => 'aktif',
             'join_date' => '2024-03-15', 'kategori_peserta_didik' => 'SMP',
             'package_key' => 'Paket Reguler Surabaya', 'classes' => ['Matematika Reguler Surabaya A']],
        ];

        foreach ($studentData as $sd) {
            $packageModel = $packages[$sd['package_key']] ?? null;
            $userS = $this->upsertUser($sd['email'], [
                'name' => $sd['name'], 'password' => Hash::make('siswa123'),
                'is_active' => true, 'branch_id' => $sd['branch_id'],
            ]);
            $userS->syncRoles(['siswa']);

            $student = Student::firstOrCreate(['nis' => $sd['nis']], array_merge(
                collect($sd)->except(['email', 'package_key', 'classes'])->toArray(),
                ['user_id' => $userS->id, 'package_id' => $packageModel?->id]
            ));
            $students[$sd['nis']] = $student;

            // Masukkan siswa ke kelas
            foreach (($sd['classes'] ?? []) as $namaKelas) {
                if (isset($classes[$namaKelas])) {
                    $classes[$namaKelas]->siswa()->syncWithoutDetaching([$student->id]);
                }
            }
        }
        $this->command->info('  ✅ Siswa (15 siswa)');

        // Jadwal & absensi tidak di-seed (diisi manual oleh admin/guru)

        // ------------------------------------------------------------------ //
        // 10. INVOICE & PEMBAYARAN                                             //
        // ------------------------------------------------------------------ //
        $invoiceCounter = 1;
        $bulanLalu      = $today->copy()->subMonth();

        // Siswa pusat: buat invoice untuk setiap kelas yang diikuti
        foreach ($students as $nis => $student) {
            $studentClasses = DB::table('class_students')
                ->where('student_id', $student->id)
                ->pluck('class_id');

            foreach ($studentClasses as $classId) {
                $kelas = SchoolClass::find($classId);
                if (!$kelas) continue;

                $course = Course::find($kelas->mata_pelajaran_id);
                $fee    = CourseFee::where('course_id', $course?->id)->first();
                $jumlah = $fee ? $fee->amount : 350000;

                $nomorInvoice = 'INV-' . $today->format('Ym') . '-' . str_pad($invoiceCounter++, 4, '0', STR_PAD_LEFT);

                // Tentukan status: 60% lunas, 20% sebagian, 20% belum bayar
                $rand = rand(1, 10);
                $statusInvoice = $rand <= 6 ? 'lunas' : ($rand <= 8 ? 'sebagian' : 'belum_bayar');

                $invoice = Invoice::firstOrCreate(
                    ['nomor_invoice' => $nomorInvoice],
                    [
                        'siswa_id'     => $student->id,
                        'cabang_id'    => $student->branch_id,
                        'kelas_id'     => $classId,
                        'subtotal'     => $jumlah,
                        'diskon'       => 0,
                        'pajak'        => 0,
                        'total'        => $jumlah,
                        'deskripsi'    => 'Biaya kursus ' . ($course?->nama ?? '-') . ' - ' . $bulanLalu->format('F Y'),
                        'periode'      => $bulanLalu->format('Y-m'),
                        'jatuh_tempo'  => $bulanLalu->copy()->endOfMonth(),
                        'status'       => $statusInvoice,
                    ]
                );

                // Buat pembayaran untuk yang lunas atau sebagian
                if (in_array($statusInvoice, ['lunas', 'sebagian'])) {
                    $jumlahBayar = $statusInvoice === 'lunas' ? $jumlah : intval($jumlah * 0.5);
                    Payment::firstOrCreate(
                        ['nomor_pembayaran' => 'PAY-' . $nomorInvoice],
                        [
                            'invoice_id'          => $invoice->id,
                            'siswa_id'            => $student->id,
                            'cabang_id'           => $student->branch_id,
                            'jumlah'              => $jumlahBayar,
                            'metode'              => collect(['transfer', 'cash', 'qris'])->random(),
                            'tanggal_pembayaran'  => $bulanLalu->copy()->addDays(rand(1, 10)),
                            'status'              => 'verified',
                            'disetujui_oleh'      => $adminPusat->id,
                            'tanggal_disetujui'   => $bulanLalu->copy()->addDays(rand(1, 12)),
                        ]
                    );
                }
            }
        }
        $this->command->info('  ✅ Invoice & pembayaran');

        // ------------------------------------------------------------------ //
        // 11. MODUL BELAJAR                                                    //
        // ------------------------------------------------------------------ //
        $modulData = [
            ['kode' => 'MAT-001', 'nama' => 'Matematika', 'modul' => [
                ['judul' => 'Modul 1 - Persamaan Linear', 'deskripsi' => 'Pengantar persamaan linear satu dan dua variabel', 'jenis' => 'pdf', 'is_gratis' => true],
                ['judul' => 'Modul 2 - Fungsi Kuadrat', 'deskripsi' => 'Fungsi kuadrat dan grafiknya', 'jenis' => 'pdf', 'is_gratis' => false],
                ['judul' => 'Modul 3 - Trigonometri', 'deskripsi' => 'Sudut, sinus, cosinus, tangen dan aplikasinya', 'jenis' => 'video', 'is_gratis' => false],
                ['judul' => 'Modul 4 - Statistika', 'deskripsi' => 'Ukuran pemusatan dan penyebaran data', 'jenis' => 'pdf', 'is_gratis' => false],
            ]],
            ['kode' => 'FIS-001', 'nama' => 'Fisika', 'modul' => [
                ['judul' => 'Modul 1 - Kinematika', 'deskripsi' => 'Gerak lurus beraturan dan berubah beraturan', 'jenis' => 'pdf', 'is_gratis' => true],
                ['judul' => 'Modul 2 - Dinamika', 'deskripsi' => 'Hukum Newton dan aplikasinya', 'jenis' => 'pdf', 'is_gratis' => false],
                ['judul' => 'Modul 3 - Listrik', 'deskripsi' => 'Listrik statis dan dinamis', 'jenis' => 'video', 'is_gratis' => false],
            ]],
            ['kode' => 'ING-001', 'nama' => 'Bahasa Inggris', 'modul' => [
                ['judul' => 'Module 1 - Grammar Fundamentals', 'deskripsi' => 'Tenses, articles, prepositions', 'jenis' => 'pdf', 'is_gratis' => true],
                ['judul' => 'Module 2 - Reading Strategies', 'deskripsi' => 'Teknik membaca cepat dan pemahaman', 'jenis' => 'pdf', 'is_gratis' => false],
                ['judul' => 'Module 3 - Writing Skills', 'deskripsi' => 'Essay, letter, and report writing', 'jenis' => 'pdf', 'is_gratis' => false],
            ]],
            ['kode' => 'SNBT-001', 'nama' => 'Persiapan SNBT', 'modul' => [
                ['judul' => 'Paket Soal TPS Penalaran Umum', 'deskripsi' => 'Kumpulan soal TPS penalaran umum dengan pembahasan', 'jenis' => 'pdf', 'is_gratis' => false],
                ['judul' => 'Strategi Mengerjakan Soal SNBT', 'deskripsi' => 'Tips dan trik mengerjakan soal UTBK-SNBT', 'jenis' => 'video', 'is_gratis' => true],
                ['judul' => 'Paket Soal Literasi Bahasa', 'deskripsi' => 'Latihan literasi bahasa Indonesia dan Inggris', 'jenis' => 'pdf', 'is_gratis' => false],
            ]],
        ];

        $ownerUser = User::where('email', 'adminpusatsci@akademi.com')->first();
        foreach ($modulData as $md) {
            if (!isset($courses[$md['kode']])) continue;
            $course = $courses[$md['kode']];
            foreach ($md['modul'] as $i => $m) {
                Module::firstOrCreate(
                    ['mata_pelajaran_id' => $course->id, 'judul' => $m['judul']],
                    [
                        'kode_modul'    => strtoupper($md['kode']) . '-M' . ($i + 1),
                        'diupload_oleh' => $ownerUser?->id ?? 1,
                        'deskripsi'     => $m['deskripsi'],
                        'jenis'         => $m['jenis'],
                        'file_url'      => null,
                        'is_gratis'     => $m['is_gratis'],
                        'status'        => 'aktif',
                        'jumlah_download' => rand(5, 150),
                    ]
                );
            }
        }
        $this->command->info('  ✅ Modul belajar');

        // ------------------------------------------------------------------ //
        // 12. TRYOUT & SOAL                                                    //
        // ------------------------------------------------------------------ //
        $tryout1 = Tryout::firstOrCreate(
            ['judul' => 'Tryout SNBT Perdana 2024', 'cabang_id' => $cabangPusat->id],
            [
                'dibuat_oleh'             => $ownerUser?->id ?? 1,
                'deskripsi'               => 'Tryout perdana persiapan UTBK-SNBT 2024 dengan soal TPS dan Literasi.',
                'kategori'                => 'SNBT',
                'durasi_menit'            => 90,
                'total_soal'              => 5,
                'nilai_kelulusan'         => 60,
                'waktu_mulai'             => $today->copy()->subDays(30),
                'waktu_selesai'           => $today->copy()->subDays(15),
                'is_random'               => true,
                'tampilkan_hasil_langsung'=> true,
                'tampilkan_kunci_jawaban' => false,
                'maksimal_percobaan'      => 1,
                'status'                  => 'selesai',
            ]
        );

        $tryout2 = Tryout::firstOrCreate(
            ['judul' => 'Tryout Matematika SMA Ulangan Harian', 'cabang_id' => $cabangPusat->id],
            [
                'dibuat_oleh'             => $ownerUser?->id ?? 1,
                'deskripsi'               => 'Ulangan harian materi fungsi kuadrat dan trigonometri.',
                'kategori'                => 'Matematika',
                'durasi_menit'            => 60,
                'total_soal'              => 5,
                'nilai_kelulusan'         => 70,
                'waktu_mulai'             => $today->copy()->addDays(3),
                'waktu_selesai'           => $today->copy()->addDays(5),
                'is_random'               => false,
                'tampilkan_hasil_langsung'=> true,
                'tampilkan_kunci_jawaban' => true,
                'maksimal_percobaan'      => 2,
                'status'                  => 'terjadwal',
            ]
        );

        // Soal untuk tryout 1
        $soalData1 = [
            ['teks' => 'Jika f(x) = 2x² - 3x + 1, maka nilai f(2) adalah...', 'pilihan' => ['A' => '3', 'B' => '5', 'C' => '7', 'D' => '1', 'E' => '4'], 'kunci' => 'A', 'poin' => 10, 'level' => 'sedang'],
            ['teks' => 'Nilai dari sin 30° + cos 60° adalah...', 'pilihan' => ['A' => '0', 'B' => '1', 'C' => '√2', 'D' => '2', 'E' => '½'], 'kunci' => 'B', 'poin' => 10, 'level' => 'mudah'],
            ['teks' => 'Sebuah persegi panjang memiliki panjang 12 cm dan lebar 8 cm. Luas persegi panjang tersebut adalah...', 'pilihan' => ['A' => '40 cm²', 'B' => '96 cm²', 'C' => '80 cm²', 'D' => '48 cm²', 'E' => '120 cm²'], 'kunci' => 'B', 'poin' => 10, 'level' => 'mudah'],
            ['teks' => 'Jika log 2 = 0,301, maka log 8 adalah...', 'pilihan' => ['A' => '0,602', 'B' => '0,903', 'C' => '0,800', 'D' => '1,204', 'E' => '2,401'], 'kunci' => 'B', 'poin' => 15, 'level' => 'sedang'],
            ['teks' => 'Himpunan penyelesaian dari |2x - 3| < 5 adalah...', 'pilihan' => ['A' => '-1 < x < 4', 'B' => 'x < -1 atau x > 4', 'C' => '-4 < x < 1', 'D' => '0 < x < 5', 'E' => '-2 < x < 4'], 'kunci' => 'A', 'poin' => 15, 'level' => 'sulit'],
        ];

        foreach ($soalData1 as $i => $soal) {
            Question::firstOrCreate(
                ['tryout_id' => $tryout1->id, 'urutan' => $i + 1],
                [
                    'teks_pertanyaan'  => $soal['teks'],
                    'jenis'            => 'pilihan_ganda',
                    'pilihan_jawaban'  => $soal['pilihan'],
                    'kunci_jawaban'    => $soal['kunci'],
                    'poin'             => $soal['poin'],
                    'tingkat_kesulitan'=> $soal['level'],
                ]
            );
        }

        // Soal untuk tryout 2
        $soalData2 = [
            ['teks' => 'Diskriminan dari persamaan x² - 5x + 6 = 0 adalah...', 'pilihan' => ['A' => '1', 'B' => '-1', 'C' => '4', 'D' => '25', 'E' => '0'], 'kunci' => 'A', 'poin' => 20, 'level' => 'mudah'],
            ['teks' => 'Akar-akar persamaan 2x² - 7x + 3 = 0 adalah...', 'pilihan' => ['A' => '3 dan ½', 'B' => '-3 dan ½', 'C' => '3 dan -½', 'D' => '1 dan 3', 'E' => '-1 dan -3'], 'kunci' => 'A', 'poin' => 20, 'level' => 'sedang'],
            ['teks' => 'Nilai maksimum dari f(x) = -x² + 4x + 5 adalah...', 'pilihan' => ['A' => '5', 'B' => '7', 'C' => '9', 'D' => '11', 'E' => '4'], 'kunci' => 'C', 'poin' => 20, 'level' => 'sedang'],
            ['teks' => 'tan 45° × cot 45° = ...', 'pilihan' => ['A' => '0', 'B' => '√2', 'C' => '2', 'D' => '1', 'E' => '½'], 'kunci' => 'D', 'poin' => 20, 'level' => 'mudah'],
            ['teks' => 'Jika sin α = 3/5 dan α di kuadran I, maka cos α = ...', 'pilihan' => ['A' => '4/5', 'B' => '3/4', 'C' => '5/4', 'D' => '5/3', 'E' => '4/3'], 'kunci' => 'A', 'poin' => 20, 'level' => 'sedang'],
        ];

        foreach ($soalData2 as $i => $soal) {
            Question::firstOrCreate(
                ['tryout_id' => $tryout2->id, 'urutan' => $i + 1],
                [
                    'teks_pertanyaan'  => $soal['teks'],
                    'jenis'            => 'pilihan_ganda',
                    'pilihan_jawaban'  => $soal['pilihan'],
                    'kunci_jawaban'    => $soal['kunci'],
                    'poin'             => $soal['poin'],
                    'tingkat_kesulitan'=> $soal['level'],
                ]
            );
        }

        // Hasil tryout untuk beberapa siswa
        $siswaSnbt = Student::whereIn('nis', ['SIS-2024-003', 'SIS-2024-004', 'SIS-2024-008'])->get();
        foreach ($siswaSnbt as $s) {
            $nilai  = rand(55, 95);
            $benar  = intval($nilai / 20);
            $salah  = max(0, 5 - $benar);
            TryoutAttempt::firstOrCreate(
                ['tryout_id' => $tryout1->id, 'siswa_id' => $s->id],
                [
                    'waktu_mulai'   => $today->copy()->subDays(25),
                    'waktu_selesai' => $today->copy()->subDays(25)->addMinutes(rand(60, 90)),
                    'nilai'         => $nilai,
                    'jawaban_benar' => $benar,
                    'jawaban_salah' => $salah,
                    'tidak_dijawab' => 0,
                    'percobaan_ke'  => 1,
                    'status'        => 'selesai',
                ]
            );
        }
        $this->command->info('  ✅ Tryout & soal');

        // ------------------------------------------------------------------ //
        // 13. NILAI / GRADE                                                    //
        // ------------------------------------------------------------------ //
        $matPelIds = [
            'MAT-001' => $courses['MAT-001']->id,
            'FIS-001' => $courses['FIS-001']->id,
            'ING-001' => $courses['ING-001']->id,
        ];
        $guruMap = [
            'MAT-001' => $teachers['NIG-2020-001']->id,
            'FIS-001' => $teachers['NIG-2020-001']->id,
            'ING-001' => $teachers['NIG-2021-003']->id,
        ];

        $siswaForGrade = Student::where('branch_id', $cabangPusat->id)->get();
        $jenisNilai    = ['ulangan_harian', 'mid_semester', 'akhir_semester'];

        foreach ($siswaForGrade as $student) {
            foreach ($matPelIds as $kode => $mpId) {
                foreach ($jenisNilai as $jenis) {
                    $existingClasses = DB::table('class_students')
                        ->join('school_classes', 'class_students.class_id', '=', 'school_classes.id')
                        ->where('class_students.student_id', $student->id)
                        ->where('school_classes.mata_pelajaran_id', $mpId)
                        ->count();
                    if ($existingClasses === 0) continue;

                    Grade::firstOrCreate(
                        ['siswa_id' => $student->id, 'mata_pelajaran_id' => $mpId, 'jenis_penilaian' => $jenis],
                        [
                            'guru_id'      => $guruMap[$kode],
                            'nama_penilaian' => ucfirst(str_replace('_', ' ', $jenis)),
                            'nilai'        => rand(65, 98),
                            'nilai_maksimal'=> 100,
                            'bobot'        => $jenis === 'akhir_semester' ? 40 : ($jenis === 'mid_semester' ? 30 : 30),
                            'tanggal'      => $bulanLalu->copy()->addDays(rand(1, 25)),
                        ]
                    );
                }
            }
        }
        $this->command->info('  ✅ Nilai / grade');

        // ------------------------------------------------------------------ //
        // 14. GAJI GURU                                                        //
        // ------------------------------------------------------------------ //
        $salaryData = [
            ['nig' => 'NIG-2020-001', 'cabang_id' => $cabangPusat->id,  'jam' => 32, 'tarif' => 100000],
            ['nig' => 'NIG-2020-002', 'cabang_id' => $cabangPusat->id,  'jam' => 24, 'tarif' => 110000],
            ['nig' => 'NIG-2021-003', 'cabang_id' => $cabangPusat->id,  'jam' => 24, 'tarif' => 90000],
            ['nig' => 'NIG-2021-004', 'cabang_id' => $cabangPusat->id,  'jam' => 40, 'tarif' => 100000],
            ['nig' => 'NIG-2022-005', 'cabang_id' => $cabangBandung->id,'jam' => 24, 'tarif' => 90000],
            ['nig' => 'NIG-2022-006', 'cabang_id' => $cabangBandung->id,'jam' => 16, 'tarif' => 85000],
            ['nig' => 'NIG-2022-007', 'cabang_id' => $cabangSurabaya->id,'jam' => 24, 'tarif' => 95000],
        ];

        foreach ($salaryData as $sd) {
            $guru = $teachers[$sd['nig']] ?? null;
            if (!$guru) continue;
            $totalMengajar = $sd['jam'] * $sd['tarif'];
            $total         = $guru->salary_base + $totalMengajar;

            Salary::firstOrCreate(
                ['guru_id' => $guru->id, 'periode' => $bulanLalu->format('Y-m')],
                [
                    'cabang_id'           => $sd['cabang_id'],
                    'tipe_gaji'           => 'bulanan',
                    'gaji_pokok'          => $guru->salary_base,
                    'jam_mengajar'        => $sd['jam'],
                    'tarif_per_jam'       => $sd['tarif'],
                    'total_gaji_mengajar' => $totalMengajar,
                    'bonus'               => 0,
                    'potongan'            => 0,
                    'total_gaji'          => $total,
                    'metode_pembayaran'   => 'transfer',
                    'nama_bank'           => 'BCA',
                    'tanggal_pembayaran'  => $bulanLalu->copy()->endOfMonth(),
                    'status'              => 'dibayar',
                ]
            );
        }
        $this->command->info('  ✅ Gaji guru');

        // ------------------------------------------------------------------ //
        // 15. PENGUMUMAN                                                       //
        // ------------------------------------------------------------------ //
        $announcements = [
            [
                'cabang_id' => $cabangPusat->id,
                'judul'     => 'Jadwal Tryout SNBT Nasional Februari 2025',
                'konten'    => 'Kepada seluruh peserta program Intensif SNBT, tryout nasional akan dilaksanakan pada tanggal 15 Februari 2025. Harap mempersiapkan diri sebaik mungkin. Tryout akan berlangsung selama 3 jam dan mencakup semua subtes SNBT. Informasi lebih lanjut akan disampaikan melalui WhatsApp grup.',
                'jenis'     => 'penting',
                'target'    => 'siswa',
                'tanggal_mulai'  => $today->copy()->subDays(5),
                'tanggal_selesai'=> $today->copy()->addDays(20),
                'is_pinned' => true,
                'status'    => 'aktif',
            ],
            [
                'cabang_id' => $cabangPusat->id,
                'judul'     => 'Libur Kelas: Hari Kemerdekaan RI 17 Agustus',
                'konten'    => 'Diberitahukan kepada seluruh siswa dan guru bahwa tidak ada kegiatan belajar mengajar pada tanggal 17 Agustus 2024 dalam rangka Hari Kemerdekaan Republik Indonesia ke-79. Kelas akan dilanjutkan pada jadwal berikutnya.',
                'jenis'     => 'informasi',
                'target'    => 'semua',
                'tanggal_mulai'  => $today->copy()->subDays(10),
                'tanggal_selesai'=> $today->copy()->addDays(5),
                'is_pinned' => false,
                'status'    => 'aktif',
            ],
            [
                'cabang_id' => $cabangPusat->id,
                'judul'     => 'Rapat Guru Bulanan - Evaluasi Pembelajaran',
                'konten'    => 'Seluruh guru diwajibkan hadir dalam rapat bulanan evaluasi pembelajaran yang akan dilaksanakan pada hari Sabtu, pukul 09.00 WIB di ruang rapat utama. Agenda: evaluasi capaian siswa, persiapan ujian akhir semester, dan koordinasi jadwal.',
                'jenis'     => 'penting',
                'target'    => 'guru',
                'tanggal_mulai'  => $today->copy()->subDays(2),
                'tanggal_selesai'=> $today->copy()->addDays(3),
                'is_pinned' => true,
                'status'    => 'aktif',
            ],
            [
                'cabang_id' => $cabangBandung->id,
                'judul'     => 'Promo Daftar Bimbel Bandung - Diskon 20%',
                'konten'    => 'Spesial untuk pendaftar baru di Cabang Bandung bulan ini, dapatkan diskon 20% untuk bulan pertama. Berlaku hingga akhir bulan. Hubungi admin untuk informasi pendaftaran.',
                'jenis'     => 'informasi',
                'target'    => 'semua',
                'tanggal_mulai'  => $today->copy()->subDays(3),
                'tanggal_selesai'=> $today->copy()->endOfMonth(),
                'is_pinned' => false,
                'status'    => 'aktif',
            ],
        ];

        foreach ($announcements as $ann) {
            Announcement::firstOrCreate(
                ['judul' => $ann['judul'], 'cabang_id' => $ann['cabang_id']],
                array_merge($ann, ['dibuat_oleh' => $ownerUser?->id ?? 1])
            );
        }
        $this->command->info('  ✅ Pengumuman');

        // ------------------------------------------------------------------ //
        // 16. SERTIFIKAT                                                       //
        // ------------------------------------------------------------------ //
        $certStudents = Student::whereIn('nis', ['SIS-2024-003', 'SIS-2024-004', 'SIS-2024-008'])->get();
        foreach ($certStudents as $i => $s) {
            Certificate::firstOrCreate(
                ['siswa_id' => $s->id, 'jenis' => 'kelulusan'],
                [
                    'cabang_id'        => $s->branch_id,
                    'course_id'        => $courses['SNBT-001']->id,
                    'diterbitkan_oleh'  => 'Admin Pusat SCI',
                    'nomor_sertifikat' => 'CERT-SNBT-2024-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                    'judul'            => 'Sertifikat Kelulusan Program Intensif SNBT 2024',
                    'deskripsi'        => 'Diberikan kepada ' . $s->name . ' atas keberhasilan menyelesaikan Program Intensif Persiapan SNBT 2024.',
                    'tanggal_terbit'   => $today->copy()->subDays(rand(5, 20)),
                    'tanggal_expired'  => $today->copy()->addYear(),
                ]
            );
        }
        $this->command->info('  ✅ Sertifikat');

        $this->command->info('');
        $this->command->info('🎉 Seeding data demo selesai!');
        $this->command->info('');
        $this->command->info('📊 Ringkasan data yang di-seed:');
        $this->command->info('   • Tahun Akademik : 2 (2023/2024, 2024/2025)');
        $this->command->info('   • Cabang         : 3 (Jakarta, Bandung, Surabaya)');
        $this->command->info('   • Mata Pelajaran : ' . Course::count());
        $this->command->info('   • Paket Belajar  : ' . Package::count());
        $this->command->info('   • Guru           : ' . Teacher::count());
        $this->command->info('   • Siswa          : ' . Student::count());
        $this->command->info('   • Kelas          : ' . SchoolClass::count());
        $this->command->info('   • Jadwal         : ' . Schedule::count());
        $this->command->info('   • Invoice        : ' . Invoice::count());
        $this->command->info('   • Pembayaran     : ' . Payment::count());
        $this->command->info('   • Modul          : ' . Module::count());
        $this->command->info('   • Tryout         : ' . Tryout::count());
        $this->command->info('   • Soal           : ' . Question::count());
        $this->command->info('   • Nilai          : ' . Grade::count());
        $this->command->info('   • Gaji           : ' . Salary::count());
        $this->command->info('   • Pengumuman     : ' . Announcement::count());
        $this->command->info('   • Sertifikat     : ' . Certificate::count());
        $this->command->info('');
        $this->command->info('🔑 Akun demo siswa (semua password: siswa123):');
        $this->command->info('   andi.nugroho@siswa.com | citra.lestari@siswa.com | fajar.hidayat@siswa.com');
        $this->command->info('🔑 Akun demo guru (semua password: password123):');
        $this->command->info('   budi.santoso@guru.akademibimbel.com | gurusci@gmail.com');
        $this->command->info('🔑 Akun admin cabang (semua password: password):');
        $this->command->info('   adminpusat@akademibimbel.com | adminbandung@akademibimbel.com | adminsurabaya@akademibimbel.com');
    }

    // ------------------------------------------------------------------ //
    // HELPER                                                               //
    // ------------------------------------------------------------------ //
    private function upsertUser(string $email, array $attributes): User
    {
        $user = User::where('email', $email)->first();
        if (! $user) {
            $user = new User();
            $user->email = $email;
        }
        $user->forceFill($attributes);
        $user->save();
        return $user;
    }
}
