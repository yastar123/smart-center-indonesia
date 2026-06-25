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
use App\Models\Package;
use App\Models\PackageCourseTeacher;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Module;
use App\Models\Salary;
use App\Models\Announcement;
use App\Models\Certificate;
use App\Models\Grade;
use App\Models\Tryout;
use App\Models\Question;

class AdminDataSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('🌱 Memulai AdminDataSeeder...');

        // ── Tahun Akademik ──────────────────────────────────────────────── //
        $tahun = AcademicYear::firstOrCreate(
            ['name' => '2025/2026'],
            ['year_start' => 2025, 'year_end' => 2026, 'is_active' => true]
        );

        // ── Cabang ──────────────────────────────────────────────────────── //
        $branchPusat = Branch::firstOrCreate(['name' => 'Pusat Jakarta'], [
            'address' => 'Jl. Sudirman No. 1, Jakarta Pusat',
            'phone'   => '021-5555001',
            'email'   => 'pusat@akademisci.com',
            'city'    => 'Jakarta',
            'status'  => 'active',
        ]);

        $branchBandung = Branch::firstOrCreate(['name' => 'Cabang Bandung'], [
            'address' => 'Jl. Braga No. 22, Bandung',
            'phone'   => '022-4444001',
            'email'   => 'bandung@akademisci.com',
            'city'    => 'Bandung',
            'status'  => 'active',
        ]);

        $branchSurabaya = Branch::firstOrCreate(['name' => 'Cabang Surabaya'], [
            'address' => 'Jl. Pemuda No. 15, Surabaya',
            'phone'   => '031-7777001',
            'email'   => 'surabaya@akademisci.com',
            'city'    => 'Surabaya',
            'status'  => 'active',
        ]);

        // ── Mata Pelajaran ───────────────────────────────────────────────── //
        $mapelData = [
            ['nama' => 'Matematika',       'kategori' => 'Saintek', 'status' => 'aktif'],
            ['nama' => 'Fisika',           'kategori' => 'Saintek', 'status' => 'aktif'],
            ['nama' => 'Kimia',            'kategori' => 'Saintek', 'status' => 'aktif'],
            ['nama' => 'Biologi',          'kategori' => 'Saintek', 'status' => 'aktif'],
            ['nama' => 'Bahasa Inggris',   'kategori' => 'Umum',    'status' => 'aktif'],
            ['nama' => 'Bahasa Indonesia', 'kategori' => 'Umum',    'status' => 'aktif'],
            ['nama' => 'Ekonomi',          'kategori' => 'Soshum',  'status' => 'aktif'],
            ['nama' => 'Sosiologi',        'kategori' => 'Soshum',  'status' => 'aktif'],
            ['nama' => 'Sejarah',          'kategori' => 'Soshum',  'status' => 'aktif'],
            ['nama' => 'Geografi',         'kategori' => 'Soshum',  'status' => 'aktif'],
        ];
        $mapels = [];
        foreach ($mapelData as $m) {
            $mapels[$m['nama']] = Course::firstOrCreate(['nama' => $m['nama']], $m);
        }

        // ── Guru ────────────────────────────────────────────────────────── //
        $guruData = [
            ['name' => 'Andi Prasetyo, S.Pd.',     'email' => 'andi.prasetyo@akademisci.com',     'gender' => 'L', 'nig' => 'NIG002', 'subjects' => ['Matematika','Fisika'],           'branch_id' => $branchPusat->id,   'jenis_guru' => 'Guru Tetap'],
            ['name' => 'Sari Dewi, S.Pd.',          'email' => 'sari.dewi@akademisci.com',          'gender' => 'P', 'nig' => 'NIG003', 'subjects' => ['Kimia','Biologi'],              'branch_id' => $branchPusat->id,   'jenis_guru' => 'Guru Tetap'],
            ['name' => 'Rizky Ananta, M.Pd.',       'email' => 'rizky.ananta@akademisci.com',       'gender' => 'L', 'nig' => 'NIG004', 'subjects' => ['Bahasa Inggris'],               'branch_id' => $branchBandung->id, 'jenis_guru' => 'Guru Paruh Waktu'],
            ['name' => 'Nurul Hidayah, S.Pd.',      'email' => 'nurul.hidayah@akademisci.com',      'gender' => 'P', 'nig' => 'NIG005', 'subjects' => ['Bahasa Indonesia','Sejarah'],   'branch_id' => $branchBandung->id, 'jenis_guru' => 'Guru Tetap'],
            ['name' => 'Hendra Wijaya, S.E., M.M.', 'email' => 'hendra.wijaya@akademisci.com',     'gender' => 'L', 'nig' => 'NIG006', 'subjects' => ['Ekonomi','Sosiologi'],           'branch_id' => $branchSurabaya->id,'jenis_guru' => 'Guru Paruh Waktu'],
            ['name' => 'Fitri Lestari, S.Pd.',      'email' => 'fitri.lestari@akademisci.com',      'gender' => 'P', 'nig' => 'NIG007', 'subjects' => ['Geografi','Sosiologi'],         'branch_id' => $branchSurabaya->id,'jenis_guru' => 'Guru Tetap'],
            ['name' => 'Dimas Arief, S.Pd.',        'email' => 'dimas.arief@akademisci.com',        'gender' => 'L', 'nig' => 'NIG008', 'subjects' => ['Matematika','Ekonomi'],         'branch_id' => $branchPusat->id,   'jenis_guru' => 'Freelance'],
        ];

        $teachers = [];
        foreach ($guruData as $gd) {
            $user = User::firstOrCreate(['email' => $gd['email']], [
                'name'     => $gd['name'],
                'password' => Hash::make('password123'),
            ]);
            $user->assignRole('guru');

            $teacher = Teacher::firstOrCreate(['user_id' => $user->id], [
                'name'        => $gd['name'],
                'email'       => $gd['email'],
                'gender'      => $gd['gender'],
                'nig'         => $gd['nig'],
                'subjects'    => $gd['subjects'],
                'branch_id'   => $gd['branch_id'],
                'jenis_guru'  => $gd['jenis_guru'],
                'status'      => 'aktif',
                'salary_base' => str_contains($gd['jenis_guru'], 'Freelance') ? 0 : 5000000,
            ]);

            // Link courses
            $courseIds = collect($gd['subjects'])->map(fn($s) => $mapels[$s]?->id)->filter()->values()->toArray();
            if ($courseIds) $teacher->courses()->syncWithoutDetaching($courseIds);

            $teachers[$gd['name']] = $teacher;
        }

        // ── Paket Belajar ────────────────────────────────────────────────── //
        $paketData = [
            [
                'nama'             => 'Intensif UTBK Saintek',
                'jenis'            => 'intensif',
                'harga'            => 3500000,
                'jumlah_pertemuan' => 24,
                'durasi_bulan'     => 3,
                'tipe_kelas'       => 'offline',
                'metode_absensi'   => 'manual',
                'status'           => 'aktif',
                'is_unggulan'      => true,
                'cabang_id'        => $branchPusat->id,
                'deskripsi'        => 'Program intensif persiapan UTBK khusus jurusan Saintek. Materi lengkap + tryout.',
                'courses'          => ['Matematika','Fisika','Kimia','Biologi'],
                'course_teachers'  => [
                    'Matematika'   => 'Andi Prasetyo, S.Pd.',
                    'Fisika'       => 'Andi Prasetyo, S.Pd.',
                    'Kimia'        => 'Sari Dewi, S.Pd.',
                    'Biologi'      => 'Sari Dewi, S.Pd.',
                ],
            ],
            [
                'nama'             => 'Intensif UTBK Soshum',
                'jenis'            => 'intensif',
                'harga'            => 3200000,
                'jumlah_pertemuan' => 20,
                'durasi_bulan'     => 3,
                'tipe_kelas'       => 'offline',
                'metode_absensi'   => 'manual',
                'status'           => 'aktif',
                'is_unggulan'      => true,
                'cabang_id'        => $branchBandung->id,
                'deskripsi'        => 'Program intensif persiapan UTBK jurusan Soshum. Termasuk materi TPS.',
                'courses'          => ['Bahasa Indonesia','Bahasa Inggris','Ekonomi','Sosiologi','Sejarah','Geografi'],
                'course_teachers'  => [
                    'Bahasa Indonesia' => 'Nurul Hidayah, S.Pd.',
                    'Bahasa Inggris'   => 'Rizky Ananta, M.Pd.',
                    'Ekonomi'          => 'Hendra Wijaya, S.E., M.M.',
                    'Sosiologi'        => 'Hendra Wijaya, S.E., M.M.',
                    'Sejarah'          => 'Nurul Hidayah, S.Pd.',
                    'Geografi'         => 'Fitri Lestari, S.Pd.',
                ],
            ],
            [
                'nama'             => 'Privat Matematika',
                'jenis'            => 'privat',
                'harga'            => 1800000,
                'jumlah_pertemuan' => 8,
                'durasi_bulan'     => 1,
                'tipe_kelas'       => 'private',
                'metode_absensi'   => 'manual',
                'status'           => 'aktif',
                'is_unggulan'      => false,
                'cabang_id'        => $branchPusat->id,
                'deskripsi'        => 'Les privat matematika 1-on-1 dengan guru berpengalaman.',
                'courses'          => ['Matematika'],
                'course_teachers'  => ['Matematika' => 'Andi Prasetyo, S.Pd.'],
            ],
            [
                'nama'             => 'Online English Intensive',
                'jenis'            => 'online',
                'harga'            => 1500000,
                'jumlah_pertemuan' => 12,
                'durasi_bulan'     => 2,
                'tipe_kelas'       => 'online',
                'metode_absensi'   => 'otomatis',
                'status'           => 'aktif',
                'is_unggulan'      => false,
                'cabang_id'        => null,
                'deskripsi'        => 'Program intensif Bahasa Inggris online. Belajar dari rumah.',
                'courses'          => ['Bahasa Inggris'],
                'course_teachers'  => ['Bahasa Inggris' => 'Rizky Ananta, M.Pd.'],
            ],
            [
                'nama'             => 'Reguler SMA Kelas 12',
                'jenis'            => 'reguler',
                'harga'            => 2000000,
                'jumlah_pertemuan' => 16,
                'durasi_bulan'     => 2,
                'tipe_kelas'       => 'offline',
                'metode_absensi'   => 'manual',
                'status'           => 'aktif',
                'is_unggulan'      => false,
                'cabang_id'        => $branchSurabaya->id,
                'deskripsi'        => 'Program reguler untuk siswa kelas 12 SMA. Semua mapel UN.',
                'courses'          => ['Matematika','Bahasa Indonesia','Bahasa Inggris','Ekonomi'],
                'course_teachers'  => [
                    'Matematika'       => 'Dimas Arief, S.Pd.',
                    'Bahasa Indonesia' => 'Nurul Hidayah, S.Pd.',
                    'Bahasa Inggris'   => 'Rizky Ananta, M.Pd.',
                    'Ekonomi'          => 'Hendra Wijaya, S.E., M.M.',
                ],
            ],
        ];

        $pakets = [];
        foreach ($paketData as $pd) {
            $courseNames = $pd['courses'];
            $ctMap       = $pd['course_teachers'];
            unset($pd['courses'], $pd['course_teachers']);

            $paket = Package::firstOrCreate(['nama' => $pd['nama']], $pd);

            $courseIds = collect($courseNames)->map(fn($n) => $mapels[$n]?->id)->filter()->values()->toArray();
            if ($courseIds) $paket->mataPelajaran()->sync($courseIds);

            foreach ($ctMap as $courseName => $teacherName) {
                $course  = $mapels[$courseName]  ?? null;
                $teacher = $teachers[$teacherName] ?? null;
                if ($course && $teacher) {
                    PackageCourseTeacher::firstOrCreate([
                        'package_id' => $paket->id,
                        'course_id'  => $course->id,
                        'teacher_id' => $teacher->id,
                    ]);
                }
            }

            $pakets[$paket->nama] = $paket;
        }

        // ── Siswa / Students ─────────────────────────────────────────────── //
        $siswaData = [
            ['name' => 'Bintang Samudera',    'email' => 'bintang@student.com',   'nisn' => '1234567890', 'gender' => 'L', 'phone' => '081234567890', 'branch_id' => $branchPusat->id],
            ['name' => 'Cahaya Bulan',         'email' => 'cahaya@student.com',    'nisn' => '1234567891', 'gender' => 'P', 'phone' => '081234567891', 'branch_id' => $branchPusat->id],
            ['name' => 'Darmawan Putra',       'email' => 'darmawan@student.com',  'nisn' => '1234567892', 'gender' => 'L', 'phone' => '081234567892', 'branch_id' => $branchBandung->id],
            ['name' => 'Elisa Ramadhani',      'email' => 'elisa@student.com',     'nisn' => '1234567893', 'gender' => 'P', 'phone' => '081234567893', 'branch_id' => $branchBandung->id],
            ['name' => 'Fajar Nugroho',        'email' => 'fajar@student.com',     'nisn' => '1234567894', 'gender' => 'L', 'phone' => '081234567894', 'branch_id' => $branchSurabaya->id],
            ['name' => 'Gita Permatasari',     'email' => 'gita@student.com',      'nisn' => '1234567895', 'gender' => 'P', 'phone' => '081234567895', 'branch_id' => $branchSurabaya->id],
            ['name' => 'Hafiz Ramadhan',       'email' => 'hafiz@student.com',     'nisn' => '1234567896', 'gender' => 'L', 'phone' => '081234567896', 'branch_id' => $branchPusat->id],
            ['name' => 'Intan Sari',           'email' => 'intan@student.com',     'nisn' => '1234567897', 'gender' => 'P', 'phone' => '081234567897', 'branch_id' => $branchPusat->id],
            ['name' => 'Joko Santoso',         'email' => 'joko@student.com',      'nisn' => '1234567898', 'gender' => 'L', 'phone' => '081234567898', 'branch_id' => $branchBandung->id],
            ['name' => 'Kania Maharani',       'email' => 'kania@student.com',     'nisn' => '1234567899', 'gender' => 'P', 'phone' => '081234567899', 'branch_id' => $branchSurabaya->id],
            ['name' => 'Lukman Hakim',         'email' => 'lukman@student.com',    'nisn' => '1234567800', 'gender' => 'L', 'phone' => '081234567800', 'branch_id' => $branchPusat->id],
            ['name' => 'Maya Sari',            'email' => 'maya@student.com',      'nisn' => '1234567801', 'gender' => 'P', 'phone' => '081234567801', 'branch_id' => $branchBandung->id],
        ];

        $students = [];
        foreach ($siswaData as $sd) {
            $user = User::firstOrCreate(['email' => $sd['email']], [
                'name'     => $sd['name'],
                'password' => Hash::make('password123'),
            ]);
            $user->assignRole('siswa');

            $student = Student::firstOrCreate(['user_id' => $user->id], [
                'name'      => $sd['name'],
                'nis'       => $sd['nisn'],
                'gender'    => $sd['gender'],
                'phone'     => $sd['phone'],
                'branch_id' => $sd['branch_id'],
                'status'    => 'aktif',
            ]);
            $students[$sd['name']] = $student;
        }

        // ── Kelas (SchoolClass) ───────────────────────────────────────────── //
        $kelasData = [
            [
                'nama_kelas'       => 'Saintek A - Pusat',
                'cabang_id'        => $branchPusat->id,
                'guru_id'          => $teachers['Andi Prasetyo, S.Pd.']->id,
                'mata_pelajaran_id'=> $mapels['Matematika']->id,
                'status'           => 'aktif',
                'paket_nama'       => 'Intensif UTBK Saintek',
                'siswa'            => ['Bintang Samudera','Cahaya Bulan','Hafiz Ramadhan','Intan Sari'],
            ],
            [
                'nama_kelas'       => 'Soshum A - Bandung',
                'cabang_id'        => $branchBandung->id,
                'guru_id'          => $teachers['Nurul Hidayah, S.Pd.']->id,
                'mata_pelajaran_id'=> $mapels['Bahasa Indonesia']->id,
                'status'           => 'aktif',
                'paket_nama'       => 'Intensif UTBK Soshum',
                'siswa'            => ['Darmawan Putra','Elisa Ramadhani','Joko Santoso'],
            ],
            [
                'nama_kelas'       => 'Reguler 12 - Surabaya',
                'cabang_id'        => $branchSurabaya->id,
                'guru_id'          => $teachers['Dimas Arief, S.Pd.']->id,
                'mata_pelajaran_id'=> $mapels['Matematika']->id,
                'status'           => 'aktif',
                'paket_nama'       => 'Reguler SMA Kelas 12',
                'siswa'            => ['Fajar Nugroho','Gita Permatasari','Kania Maharani'],
            ],
        ];

        $classes = [];
        foreach ($kelasData as $kd) {
            $siswaNama = $kd['siswa'];
            $paketNama = $kd['paket_nama'];
            unset($kd['siswa'], $kd['paket_nama']);

            $kelas = SchoolClass::firstOrCreate(['nama_kelas' => $kd['nama_kelas']], $kd);
            $classes[$kelas->nama_kelas] = $kelas;

            $paket = $pakets[$paketNama] ?? null;
            foreach ($siswaNama as $sn) {
                if (isset($students[$sn])) {
                    $kelas->siswa()->syncWithoutDetaching([$students[$sn]->id]);
                    if ($paket) $students[$sn]->update(['package_id' => $paket->id]);
                }
            }
        }

        // ── Invoice & Payment ────────────────────────────────────────────── //
        $invoiceData = [
            ['siswa' => 'Bintang Samudera',  'total' => 3500000, 'status' => 'lunas',       'tgl' => '2025-01-10', 'deskripsi' => 'Biaya Intensif UTBK Saintek'],
            ['siswa' => 'Cahaya Bulan',       'total' => 3500000, 'status' => 'sebagian',    'tgl' => '2025-01-12', 'deskripsi' => 'Biaya Intensif UTBK Saintek'],
            ['siswa' => 'Darmawan Putra',     'total' => 3200000, 'status' => 'lunas',       'tgl' => '2025-01-08', 'deskripsi' => 'Biaya Intensif UTBK Soshum'],
            ['siswa' => 'Elisa Ramadhani',    'total' => 3200000, 'status' => 'belum_bayar', 'tgl' => '2025-01-15', 'deskripsi' => 'Biaya Intensif UTBK Soshum'],
            ['siswa' => 'Fajar Nugroho',      'total' => 2000000, 'status' => 'lunas',       'tgl' => '2025-02-01', 'deskripsi' => 'Biaya Reguler SMA Kelas 12'],
            ['siswa' => 'Gita Permatasari',   'total' => 2000000, 'status' => 'lunas',       'tgl' => '2025-02-03', 'deskripsi' => 'Biaya Reguler SMA Kelas 12'],
            ['siswa' => 'Hafiz Ramadhan',     'total' => 3500000, 'status' => 'belum_bayar', 'tgl' => '2025-02-05', 'deskripsi' => 'Biaya Intensif UTBK Saintek'],
            ['siswa' => 'Intan Sari',         'total' => 3500000, 'status' => 'lunas',       'tgl' => '2025-02-10', 'deskripsi' => 'Biaya Intensif UTBK Saintek'],
            ['siswa' => 'Joko Santoso',       'total' => 3200000, 'status' => 'sebagian',    'tgl' => '2025-02-12', 'deskripsi' => 'Biaya Intensif UTBK Soshum'],
            ['siswa' => 'Kania Maharani',     'total' => 2000000, 'status' => 'lunas',       'tgl' => '2025-03-01', 'deskripsi' => 'Biaya Reguler SMA Kelas 12'],
            ['siswa' => 'Lukman Hakim',       'total' => 1800000, 'status' => 'lunas',       'tgl' => '2025-03-05', 'deskripsi' => 'Biaya Privat Matematika'],
            ['siswa' => 'Maya Sari',          'total' => 1500000, 'status' => 'lunas',       'tgl' => '2025-03-08', 'deskripsi' => 'Biaya Online English Intensive'],
        ];

        $invoiceCounter = 1000;
        foreach ($invoiceData as $inv) {
            $siswa = $students[$inv['siswa']] ?? null;
            if (!$siswa) continue;

            $invoice = Invoice::firstOrCreate(
                ['siswa_id' => $siswa->id, 'deskripsi' => $inv['deskripsi']],
                [
                    'cabang_id'      => $siswa->branch_id,
                    'nomor_invoice'  => 'INV-' . date('Y') . '-' . str_pad($invoiceCounter++, 4, '0', STR_PAD_LEFT),
                    'total'          => $inv['total'],
                    'subtotal'       => $inv['total'],
                    'status'         => $inv['status'],
                    'periode'        => Carbon::parse($inv['tgl'])->format('Y-m'),
                    'jatuh_tempo'    => Carbon::parse($inv['tgl'])->addDays(14)->format('Y-m-d'),
                ]
            );

            // Payment for paid invoices
            if (in_array($inv['status'], ['lunas', 'sebagian'])) {
                $bayar = $inv['status'] === 'lunas' ? $inv['total'] : intval($inv['total'] / 2);
                Payment::firstOrCreate(
                    ['invoice_id' => $invoice->id],
                    [
                        'siswa_id'           => $siswa->id,
                        'cabang_id'          => $siswa->branch_id,
                        'jumlah'             => $bayar,
                        'metode'             => collect(['transfer', 'cash', 'qris'])->random(),
                        'status'             => 'verified',
                        'tanggal_pembayaran' => Carbon::parse($inv['tgl'])->addDays(2)->format('Y-m-d'),
                    ]
                );
            }
        }

        // ── Gaji Guru ────────────────────────────────────────────────────── //
        $salaryData = [
            ['teacher' => 'Andi Prasetyo, S.Pd.',      'periode' => '2025-01', 'gaji_pokok' => 5000000, 'bonus' => 500000, 'tipe_gaji' => 'bulanan',   'status' => 'dibayar'],
            ['teacher' => 'Andi Prasetyo, S.Pd.',      'periode' => '2025-02', 'gaji_pokok' => 5000000, 'bonus' => 300000, 'tipe_gaji' => 'bulanan',   'status' => 'dibayar'],
            ['teacher' => 'Andi Prasetyo, S.Pd.',      'periode' => '2025-03', 'gaji_pokok' => 5000000, 'bonus' => 0,      'tipe_gaji' => 'bulanan',   'status' => 'pending'],
            ['teacher' => 'Sari Dewi, S.Pd.',           'periode' => '2025-01', 'gaji_pokok' => 5000000, 'bonus' => 400000, 'tipe_gaji' => 'bulanan',   'status' => 'dibayar'],
            ['teacher' => 'Sari Dewi, S.Pd.',           'periode' => '2025-02', 'gaji_pokok' => 5000000, 'bonus' => 200000, 'tipe_gaji' => 'bulanan',   'status' => 'dibayar'],
            ['teacher' => 'Rizky Ananta, M.Pd.',        'periode' => '2025-01', 'gaji_pokok' => 3500000, 'bonus' => 0,      'tipe_gaji' => 'bulanan',   'status' => 'dibayar'],
            ['teacher' => 'Rizky Ananta, M.Pd.',        'periode' => '2025-02', 'gaji_pokok' => 3500000, 'bonus' => 150000, 'tipe_gaji' => 'bulanan',   'status' => 'dibayar'],
            ['teacher' => 'Nurul Hidayah, S.Pd.',       'periode' => '2025-01', 'gaji_pokok' => 4500000, 'bonus' => 350000, 'tipe_gaji' => 'bulanan',   'status' => 'dibayar'],
            ['teacher' => 'Nurul Hidayah, S.Pd.',       'periode' => '2025-02', 'gaji_pokok' => 4500000, 'bonus' => 0,      'tipe_gaji' => 'bulanan',   'status' => 'pending'],
            ['teacher' => 'Hendra Wijaya, S.E., M.M.', 'periode' => '2025-01', 'gaji_pokok' => 2500000, 'bonus' => 200000, 'tipe_gaji' => 'freelance', 'status' => 'dibayar'],
            ['teacher' => 'Fitri Lestari, S.Pd.',       'periode' => '2025-01', 'gaji_pokok' => 4000000, 'bonus' => 0,      'tipe_gaji' => 'bulanan',   'status' => 'dibayar'],
            ['teacher' => 'Dimas Arief, S.Pd.',         'periode' => '2025-01', 'gaji_pokok' => 1500000, 'bonus' => 100000, 'tipe_gaji' => 'freelance', 'status' => 'dibayar'],
            ['teacher' => 'Dimas Arief, S.Pd.',         'periode' => '2025-02', 'gaji_pokok' => 1800000, 'bonus' => 0,      'tipe_gaji' => 'freelance', 'status' => 'pending'],
        ];

        foreach ($salaryData as $sd) {
            $teacher = $teachers[$sd['teacher']] ?? null;
            if (!$teacher) continue;
            $potongan  = 0;
            $totalGaji = $sd['gaji_pokok'] + ($sd['bonus'] ?? 0) - $potongan;
            Salary::firstOrCreate(
                ['guru_id' => $teacher->id, 'periode' => $sd['periode']],
                [
                    'cabang_id'            => $teacher->branch_id,
                    'tipe_gaji'            => $sd['tipe_gaji'],
                    'gaji_pokok'           => $sd['gaji_pokok'],
                    'bonus'                => $sd['bonus'] ?? 0,
                    'potongan'             => $potongan,
                    'jam_mengajar'         => rand(20, 40),
                    'tarif_per_jam'        => $sd['tipe_gaji'] === 'freelance' ? 75000 : 0,
                    'total_gaji_mengajar'  => 0,
                    'total_gaji'           => $totalGaji,
                    'status'               => $sd['status'],
                    'metode_pembayaran'    => $sd['status'] === 'dibayar' ? 'Transfer Bank' : null,
                    'tanggal_pembayaran'   => $sd['status'] === 'dibayar' ? Carbon::parse($sd['periode'].'-28')->format('Y-m-d') : null,
                    'nama_bank'            => $sd['status'] === 'dibayar' ? 'BCA' : null,
                ]
            );
        }

        // ── Pengumuman (Announcements) ───────────────────────────────────── //
        $announcements = [
            ['judul' => 'Selamat Datang di Semester Baru 2025/2026', 'isi' => 'Kami menyambut semua siswa di semester baru. Semoga pembelajaran berjalan lancar dan prestasi terus meningkat. Jadwal kelas sudah tersedia di dashboard masing-masing.', 'type' => 'info',    'target' => 'all'],
            ['judul' => 'Libur Hari Raya Idul Fitri',                'isi' => 'Akademi SCI akan libur pada tanggal 28 Maret - 7 April 2025 dalam rangka Hari Raya Idul Fitri 1446 H. Kelas akan dilanjutkan kembali pada 8 April 2025.', 'type' => 'warning', 'target' => 'all'],
            ['judul' => 'Tryout UTBK Perdana Tersedia',              'isi' => 'Tryout UTBK perdana sudah dibuka untuk siswa paket Intensif Saintek dan Soshum. Silakan akses menu Tryout di dashboard siswa Anda.', 'type' => 'success', 'target' => 'siswa'],
            ['judul' => 'Reminder: Input Jurnal Mengajar',           'isi' => 'Kepada seluruh guru, harap mengisi jurnal mengajar setelah setiap sesi selesai. Keterlambatan input jurnal akan mempengaruhi proses penggajian.', 'type' => 'warning', 'target' => 'guru'],
            ['judul' => 'Pembayaran Bulan Maret Jatuh Tempo',        'isi' => 'Tagihan bulan Maret 2025 akan jatuh tempo pada 31 Maret 2025. Harap segera melakukan pembayaran melalui transfer bank atau bayar langsung di cabang.', 'type' => 'danger',  'target' => 'siswa'],
        ];

        foreach ($announcements as $ann) {
            \App\Models\Announcement::firstOrCreate(
                ['judul' => $ann['judul']],
                [
                    'konten'        => $ann['isi'],
                    'jenis'         => $ann['type'],
                    'target'        => $ann['target'],
                    'status'        => 'aktif',
                    'tanggal_mulai' => now()->subDays(rand(1, 30))->format('Y-m-d'),
                ]
            );
        }

        // ── Modul Belajar ────────────────────────────────────────────────── //
        $modulData = [
            ['judul' => 'Modul Matematika Dasar',          'mapel' => 'Matematika',       'kode' => 'MTK-001', 'deskripsi' => 'Dasar aljabar, fungsi, dan trigonometri'],
            ['judul' => 'Modul Matematika Lanjut',         'mapel' => 'Matematika',       'kode' => 'MTK-002', 'deskripsi' => 'Kalkulus, statistika, dan geometri analitik'],
            ['judul' => 'Modul Fisika Mekanika',           'mapel' => 'Fisika',           'kode' => 'FIS-001', 'deskripsi' => 'Kinematika, dinamika, dan hukum Newton'],
            ['judul' => 'Modul Kimia Organik',             'mapel' => 'Kimia',            'kode' => 'KIM-001', 'deskripsi' => 'Senyawa organik dan reaksi kimia'],
            ['judul' => 'Modul Biologi Sel',               'mapel' => 'Biologi',          'kode' => 'BIO-001', 'deskripsi' => 'Struktur sel, organel, dan metabolisme'],
            ['judul' => 'Modul Grammar Bahasa Inggris',    'mapel' => 'Bahasa Inggris',   'kode' => 'ENG-001', 'deskripsi' => 'Tata bahasa dan struktur kalimat'],
            ['judul' => 'Modul Teks Bahasa Indonesia',     'mapel' => 'Bahasa Indonesia', 'kode' => 'IND-001', 'deskripsi' => 'Jenis teks dan teknik menulis'],
            ['judul' => 'Modul Ekonomi Makro',             'mapel' => 'Ekonomi',          'kode' => 'EKO-001', 'deskripsi' => 'Ekonomi nasional dan kebijakan fiskal'],
        ];

        foreach ($modulData as $md) {
            $mapel = $mapels[$md['mapel']] ?? null;
            Module::firstOrCreate(
                ['kode_modul' => $md['kode']],
                [
                    'judul'             => $md['judul'],
                    'mata_pelajaran_id' => $mapel?->id,
                    'deskripsi'         => $md['deskripsi'],
                    'jenis'             => 'pdf',
                    'status'            => 'aktif',
                    'is_gratis'         => true,
                ]
            );
        }

        // ── Tryout ───────────────────────────────────────────────────────── //
        $tryoutData = [
            ['judul' => 'Tryout UTBK Saintek Maret 2025', 'durasi' => 90, 'total_soal' => 30, 'status' => 'aktif'],
            ['judul' => 'Tryout Fisika April 2025',        'durasi' => 60, 'total_soal' => 20, 'status' => 'aktif'],
            ['judul' => 'Tryout Bahasa Inggris UTBK',     'durasi' => 60, 'total_soal' => 25, 'status' => 'aktif'],
        ];

        foreach ($tryoutData as $td) {
            $tryout = Tryout::firstOrCreate(
                ['judul' => $td['judul']],
                [
                    'total_soal'  => $td['total_soal'],
                    'durasi_menit'=> $td['durasi'],
                    'status'      => $td['status'],
                    'deskripsi'   => 'Tryout persiapan: ' . $td['judul'],
                    'kategori'    => 'latihan',
                    'waktu_mulai' => now()->subDays(30),
                    'waktu_selesai' => now()->addDays(60),
                ]
            );

            // Seed a few sample questions
            for ($q = 1; $q <= min(3, $td['total_soal']); $q++) {
                Question::firstOrCreate(
                    ['tryout_id' => $tryout->id, 'urutan' => $q],
                    [
                        'teks_pertanyaan' => "Soal nomor {$q} dari {$td['judul']}. Ini adalah contoh soal latihan.",
                        'jenis'           => 'pilihan_ganda',
                        'pilihan_jawaban' => ['A' => 'Jawaban A', 'B' => 'Jawaban B', 'C' => 'Jawaban C', 'D' => 'Jawaban D', 'E' => 'Jawaban E'],
                        'kunci_jawaban'   => 'A',
                        'penjelasan'      => 'Jawaban yang benar adalah A.',
                        'poin'            => 1,
                        'tingkat_kesulitan' => 'sedang',
                    ]
                );
            }
        }

        // ── Nilai / Grades ───────────────────────────────────────────────── //
        $penilaianTypes = ['tugas', 'ulangan_harian', 'uts', 'uas'];
        foreach (array_slice($siswaData, 0, 6) as $sd) {
            $student = $students[$sd['name']] ?? null;
            if (!$student) continue;
            foreach (['Matematika', 'Bahasa Inggris'] as $mapelName) {
                $mapel = $mapels[$mapelName] ?? null;
                if (!$mapel) continue;
                foreach ($penilaianTypes as $jenis) {
                    Grade::firstOrCreate(
                        ['siswa_id' => $student->id, 'mata_pelajaran_id' => $mapel->id, 'jenis_penilaian' => $jenis],
                        [
                            'nilai'         => rand(65, 98),
                            'nilai_maksimal' => 100,
                            'bobot'         => 1,
                            'tanggal'       => now()->subDays(rand(10, 60))->format('Y-m-d'),
                            'catatan'       => 'Input oleh Admin',
                        ]
                    );
                }
            }
        }

        $this->command->info('✅ AdminDataSeeder selesai!');
        $this->command->info('   • Cabang     : 3');
        $this->command->info('   • Guru       : ' . count($guruData));
        $this->command->info('   • Paket      : ' . count($paketData));
        $this->command->info('   • Siswa      : ' . count($siswaData));
        $this->command->info('   • Kelas      : ' . count($kelasData));
        $this->command->info('   • Invoice    : ' . count($invoiceData));
        $this->command->info('   • Gaji Guru  : ' . count($salaryData));
        $this->command->info('   • Pengumuman : ' . count($announcements));
        $this->command->info('   • Modul      : ' . count($modulData));
        $this->command->info('   • Tryout     : ' . count($tryoutData));
    }
}
