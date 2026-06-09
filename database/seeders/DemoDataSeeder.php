<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        // ── BRANCHES ──────────────────────────────────────────────────────
        $adminUser = \App\Models\User::where('email','admincabangsci@akademi.com')->first();
        $ownerUser = \App\Models\User::where('email','adminpusatsci@akademi.com')->first();
        $guruUser  = \App\Models\User::where('email','gurusci@gmail.com')->first();
        $siswaUser = \App\Models\User::where('email','siswasci@gmail.com')->first();

        if (\App\Models\Branch::count() === 0) {
            $b1 = \App\Models\Branch::create([
                'name' => 'Smart Center Jakarta Selatan',
                'city' => 'Jakarta Selatan',
                'address' => 'Jl. Sudirman No. 12, Jakarta Selatan',
                'phone' => '021-5555-1001',
                'email' => 'jaksel@smartcenter.id',
                'status' => 'active',
                'user_id' => $adminUser?->id,
            ]);
            $b2 = \App\Models\Branch::create([
                'name' => 'Smart Center Bandung',
                'city' => 'Bandung',
                'address' => 'Jl. Dago No. 45, Bandung',
                'phone' => '022-5555-2002',
                'email' => 'bandung@smartcenter.id',
                'status' => 'active',
            ]);
            $b3 = \App\Models\Branch::create([
                'name' => 'Smart Center Surabaya',
                'city' => 'Surabaya',
                'address' => 'Jl. Basuki Rahmat No. 88, Surabaya',
                'phone' => '031-5555-3003',
                'email' => 'surabaya@smartcenter.id',
                'status' => 'active',
            ]);
            $this->command->info("Branches seeded: {$b1->id}, {$b2->id}, {$b3->id}");
        }

        $branches = \App\Models\Branch::all();
        $b1 = $branches->first();
        $b2 = $branches->skip(1)->first() ?? $b1;
        $b3 = $branches->skip(2)->first() ?? $b1;

        // Assign admin user to first branch if not already set
        if ($adminUser && !$adminUser->branch_id && $b1) {
            $adminUser->update(['branch_id' => $b1->id]);
            $this->command->info("Admin branch_id assigned to: {$b1->name}");
        }

        // ── TEACHERS ─────────────────────────────────────────────────────
        if (\App\Models\Teacher::count() === 0) {
            $t1 = \App\Models\Teacher::create([
                'user_id' => $guruUser?->id,
                'branch_id' => $b1->id,
                'nig' => 'NIG-001',
                'name' => 'Budi Santoso, S.Pd',
                'gender' => 'L',
                'birth_date' => '1985-04-15',
                'birth_place' => 'Solo',
                'address' => 'Jl. Melati No. 5, Jakarta',
                'phone' => '081234567890',
                'email' => 'gurusci@gmail.com',
                'education' => 'S1 Matematika',
                'subjects' => json_encode(['Matematika', 'Fisika']),
                'salary_base' => 4500000,
                'join_date' => '2022-01-10',
                'status' => 'aktif',
            ]);
            \App\Models\Teacher::create([
                'branch_id' => $b2->id,
                'nig' => 'NIG-002',
                'name' => 'Siti Rahayu, S.Pd',
                'gender' => 'P',
                'birth_date' => '1988-08-20',
                'birth_place' => 'Bandung',
                'address' => 'Jl. Anggrek No. 7, Bandung',
                'phone' => '081298765432',
                'email' => 'siti.rahayu@smartcenter.id',
                'education' => 'S1 Bahasa Inggris',
                'subjects' => json_encode(['Bahasa Inggris']),
                'salary_base' => 4000000,
                'join_date' => '2022-03-01',
                'status' => 'aktif',
            ]);
            \App\Models\Teacher::create([
                'branch_id' => $b1->id,
                'nig' => 'NIG-003',
                'name' => 'Ahmad Fauzi, M.Si',
                'gender' => 'L',
                'birth_date' => '1983-12-05',
                'birth_place' => 'Yogyakarta',
                'address' => 'Jl. Mawar No. 10, Jakarta',
                'phone' => '082112345678',
                'email' => 'ahmad.fauzi@smartcenter.id',
                'education' => 'S2 Kimia',
                'subjects' => json_encode(['Kimia', 'Biologi']),
                'salary_base' => 5200000,
                'join_date' => '2021-06-15',
                'status' => 'aktif',
            ]);
            \App\Models\Teacher::create([
                'branch_id' => $b3->id,
                'nig' => 'NIG-004',
                'name' => 'Dewi Kusuma, S.Pd',
                'gender' => 'P',
                'birth_date' => '1990-03-22',
                'birth_place' => 'Surabaya',
                'address' => 'Jl. Kenanga No. 3, Surabaya',
                'phone' => '085612345678',
                'email' => 'dewi.kusuma@smartcenter.id',
                'education' => 'S1 Bahasa Indonesia',
                'subjects' => json_encode(['Bahasa Indonesia', 'SBMPTN']),
                'salary_base' => 3800000,
                'join_date' => '2023-01-05',
                'status' => 'aktif',
            ]);
            $this->command->info('Teachers seeded: 4');
        }

        // ── STUDENTS ─────────────────────────────────────────────────────
        if (\App\Models\Student::count() === 0) {
            $students = [
                ['user_id'=>$siswaUser?->id,'branch_id'=>$b1->id,'nis'=>'SIS-2024-001','name'=>'Ahmad Rizki Pratama','gender'=>'L','birth_date'=>'2007-03-15','address'=>'Jl. Melati 5, Jakarta','phone'=>'081212345678','parent_name'=>'Bapak Pratama','parent_phone'=>'081287654321','school_name'=>'SMA Negeri 1 Jakarta','grade'=>'XII','status'=>'aktif','join_date'=>Carbon::now()->subMonths(8)],
                ['branch_id'=>$b1->id,'nis'=>'SIS-2024-002','name'=>'Sari Dewi Rahayu','gender'=>'P','birth_date'=>'2007-07-22','address'=>'Jl. Anggrek 7, Jakarta','phone'=>'081298765432','parent_name'=>'Ibu Rahayu','parent_phone'=>'082198765432','school_name'=>'SMA Negeri 2 Jakarta','grade'=>'XII','status'=>'aktif','join_date'=>Carbon::now()->subMonths(7)],
                ['branch_id'=>$b1->id,'nis'=>'SIS-2024-003','name'=>'Budi Cahyono','gender'=>'L','birth_date'=>'2008-01-10','address'=>'Jl. Mawar 10, Jakarta','phone'=>'085611223344','parent_name'=>'Bapak Cahyono','parent_phone'=>'081311223344','school_name'=>'SMA Negeri 3 Jakarta','grade'=>'XI','status'=>'aktif','join_date'=>Carbon::now()->subMonths(6)],
                ['branch_id'=>$b2->id,'nis'=>'SIS-2024-004','name'=>'Putri Amelia Sari','gender'=>'P','birth_date'=>'2007-11-05','address'=>'Jl. Dahlia 3, Bandung','phone'=>'081312345678','parent_name'=>'Ibu Sari','parent_phone'=>'082312345678','school_name'=>'SMA Negeri 1 Bandung','grade'=>'XII','status'=>'aktif','join_date'=>Carbon::now()->subMonths(5)],
                ['branch_id'=>$b2->id,'nis'=>'SIS-2024-005','name'=>'Dicky Hermawan','gender'=>'L','birth_date'=>'2008-04-18','address'=>'Jl. Bougenville 15, Bandung','phone'=>'087812345678','parent_name'=>'Bapak Hermawan','parent_phone'=>'081787654321','school_name'=>'SMA Negeri 2 Bandung','grade'=>'XI','status'=>'aktif','join_date'=>Carbon::now()->subMonths(4)],
                ['branch_id'=>$b3->id,'nis'=>'SIS-2024-006','name'=>'Nurul Hidayah','gender'=>'P','birth_date'=>'2007-09-12','address'=>'Jl. Cempaka 8, Surabaya','phone'=>'081987654321','parent_name'=>'Ibu Hidayah','parent_phone'=>'085987654321','school_name'=>'SMA Negeri 1 Surabaya','grade'=>'XII','status'=>'aktif','join_date'=>Carbon::now()->subMonths(6)],
                ['branch_id'=>$b3->id,'nis'=>'SIS-2024-007','name'=>'Fajar Ramadhan','gender'=>'L','birth_date'=>'2008-06-25','address'=>'Jl. Tulip 12, Surabaya','phone'=>'082387654321','parent_name'=>'Bapak Ramadhan','parent_phone'=>'081287654321','school_name'=>'SMA Negeri 2 Surabaya','grade'=>'XI','status'=>'aktif','join_date'=>Carbon::now()->subMonths(3)],
                ['branch_id'=>$b1->id,'nis'=>'SIS-2024-008','name'=>'Reza Kurniawan','gender'=>'L','birth_date'=>'2007-12-30','address'=>'Jl. Kenanga 4, Jakarta','phone'=>'081345678901','parent_name'=>'Bapak Kurniawan','parent_phone'=>'082245678901','school_name'=>'SMA Negeri 4 Jakarta','grade'=>'XII','status'=>'nonaktif','join_date'=>Carbon::now()->subMonths(10)],
                ['branch_id'=>$b2->id,'nis'=>'SIS-2024-009','name'=>'Indah Permatasari','gender'=>'P','birth_date'=>'2007-05-14','address'=>'Jl. Flamboyan 9, Bandung','phone'=>'085612345678','parent_name'=>'Ibu Permatasari','parent_phone'=>'081512345678','school_name'=>'SMA Negeri 3 Bandung','grade'=>'XII','status'=>'aktif','join_date'=>Carbon::now()->subMonths(5)],
                ['branch_id'=>$b1->id,'nis'=>'SIS-2024-010','name'=>'Kevin Saputra','gender'=>'L','birth_date'=>'2008-08-08','address'=>'Jl. Lavender 6, Jakarta','phone'=>'087654321098','parent_name'=>'Bapak Saputra','parent_phone'=>'082154321098','school_name'=>'SMA Negeri 5 Jakarta','grade'=>'XI','status'=>'aktif','join_date'=>Carbon::now()->subMonths(2)],
            ];
            foreach ($students as $s) {
                \App\Models\Student::create($s);
            }
            $this->command->info('Students seeded: 10');
        }

        // ── COURSES ──────────────────────────────────────────────────────
        if (\App\Models\Course::count() === 0) {
            $courses = [
                ['kode'=>'MTK','nama'=>'Matematika','deskripsi'=>'Persiapan UN dan SNBT Matematika','kategori'=>'Sains','icon'=>'bi-calculator','warna'=>'#3b82f6','status'=>'aktif'],
                ['kode'=>'FIS','nama'=>'Fisika','deskripsi'=>'Konsep dan soal Fisika tingkat SMA','kategori'=>'Sains','icon'=>'bi-lightning','warna'=>'#6366f1','status'=>'aktif'],
                ['kode'=>'KIM','nama'=>'Kimia','deskripsi'=>'Kimia organik, anorganik dan soal CBT','kategori'=>'Sains','icon'=>'bi-moisture','warna'=>'#10b981','status'=>'aktif'],
                ['kode'=>'BIO','nama'=>'Biologi','deskripsi'=>'Biologi sel, genetika dan ekologi','kategori'=>'Sains','icon'=>'bi-tree','warna'=>'#059669','status'=>'aktif'],
                ['kode'=>'BI','nama'=>'Bahasa Indonesia','deskripsi'=>'Tata bahasa, sastra dan penulisan','kategori'=>'Bahasa','icon'=>'bi-book','warna'=>'#f59e0b','status'=>'aktif'],
                ['kode'=>'BIG','nama'=>'Bahasa Inggris','deskripsi'=>'Grammar, reading dan writing skills','kategori'=>'Bahasa','icon'=>'bi-translate','warna'=>'#ef4444','status'=>'aktif'],
                ['kode'=>'SNBT','nama'=>'Tryout SNBT','deskripsi'=>'Simulasi lengkap seleksi masuk PTN','kategori'=>'CBT','icon'=>'bi-clipboard-check','warna'=>'#8b5cf6','status'=>'aktif'],
            ];
            foreach ($courses as $c) {
                \App\Models\Course::create($c);
            }
            $this->command->info('Courses seeded: 7');
        }

        // ── SCHOOL CLASSES ────────────────────────────────────────────────
        if (\App\Models\SchoolClass::count() === 0) {
            $guru1 = \App\Models\Teacher::where('branch_id', $b1->id)->first();
            $guru2 = \App\Models\Teacher::where('branch_id', $b2->id)->first();
            $mtk   = \App\Models\Course::where('kode','MTK')->first();
            $big   = \App\Models\Course::where('kode','BIG')->first();
            $snbt  = \App\Models\Course::where('kode','SNBT')->first();

            $k1 = \App\Models\SchoolClass::create([
                'cabang_id' => $b1->id,
                'guru_id' => $guru1?->id,
                'mata_pelajaran_id' => $mtk?->id,
                'nama_kelas' => 'Matematika XII - Pagi',
                'kapasitas' => 20,
                'jenis' => 'offline',
                'ruangan' => 'Ruang A1',
                'status' => 'aktif',
            ]);
            \App\Models\SchoolClass::create([
                'cabang_id' => $b2->id,
                'guru_id' => $guru2?->id,
                'mata_pelajaran_id' => $big?->id,
                'nama_kelas' => 'Bahasa Inggris XII - Sore',
                'kapasitas' => 15,
                'jenis' => 'offline',
                'ruangan' => 'Ruang B2',
                'status' => 'aktif',
            ]);
            \App\Models\SchoolClass::create([
                'cabang_id' => $b1->id,
                'guru_id' => $guru1?->id,
                'mata_pelajaran_id' => $snbt?->id,
                'nama_kelas' => 'Tryout SNBT Online - Kelas A',
                'kapasitas' => 40,
                'jenis' => 'online',
                'link_zoom' => 'https://zoom.us/j/example',
                'status' => 'aktif',
            ]);
            $this->command->info('School classes seeded: 3');
        }

        // ── SCHEDULES ────────────────────────────────────────────────────
        if (\App\Models\Schedule::count() === 0) {
            $guru1 = \App\Models\Teacher::first();
            $guru2 = \App\Models\Teacher::skip(1)->first() ?? $guru1;
            $kelas1 = \App\Models\SchoolClass::first();
            $kelas2 = \App\Models\SchoolClass::skip(1)->first() ?? $kelas1;
            $kelas3 = \App\Models\SchoolClass::skip(2)->first() ?? $kelas1;

            $days = collect(range(-3, 14))->filter(fn($d) => !in_array(Carbon::now()->addDays($d)->dayOfWeek, [0,6]));
            $scheduled = 0;
            foreach ($days->take(10) as $d) {
                $date = Carbon::now()->addDays($d)->format('Y-m-d');
                $isToday = $d === 0;
                \App\Models\Schedule::create([
                    'guru_id'    => $guru1->id,
                    'kelas_id'   => $kelas1->id,
                    'cabang_id'  => $b1->id,
                    'tanggal'    => $date,
                    'jam_mulai'  => '08:00',
                    'jam_selesai'=> '10:00',
                    'topik'      => 'Aljabar Linear & Matriks',
                    'jenis'      => 'offline',
                    'ruangan'    => 'Ruang A1',
                    'status'     => $d < 0 ? 'selesai' : ($isToday ? 'berlangsung' : 'dijadwalkan'),
                ]);
                $scheduled++;
            }
            foreach ($days->take(8) as $d) {
                $date = Carbon::now()->addDays($d)->format('Y-m-d');
                \App\Models\Schedule::create([
                    'guru_id'    => $guru2->id,
                    'kelas_id'   => $kelas2->id,
                    'cabang_id'  => $b2->id,
                    'tanggal'    => $date,
                    'jam_mulai'  => '14:00',
                    'jam_selesai'=> '16:00',
                    'topik'      => 'Reading Comprehension & Grammar',
                    'jenis'      => 'offline',
                    'ruangan'    => 'Ruang B2',
                    'status'     => $d < 0 ? 'selesai' : 'dijadwalkan',
                ]);
            }
            $this->command->info("Schedules seeded: ".($scheduled+8));
        }

        // ── INVOICES & PAYMENTS ──────────────────────────────────────────
        if (\App\Models\Invoice::count() === 0) {
            $students = \App\Models\Student::all();
            $invoiceNum = 1;
            foreach ($students as $s) {
                // 3 months of invoices per student
                foreach (range(2, 0) as $monthsAgo) {
                    $period = Carbon::now()->subMonths($monthsAgo);
                    $total = collect([750000, 850000, 950000, 1000000, 1200000])->random();
                    $dueDate = $period->copy()->endOfMonth()->format('Y-m-d');
                    $isPast = Carbon::parse($dueDate)->isPast();
                    $status = $monthsAgo > 0 ? ($isPast ? 'lunas' : 'belum_bayar') : ($isPast ? 'lunas' : 'belum_bayar');
                    if ($monthsAgo > 1) $status = 'lunas';

                    $inv = \App\Models\Invoice::create([
                        'siswa_id' => $s->id,
                        'cabang_id' => $s->branch_id,
                        'nomor_invoice' => 'INV-'.date('Ymd',strtotime($period->format('Y-m-01'))).'-'.str_pad($invoiceNum++, 4,'0',STR_PAD_LEFT),
                        'subtotal' => $total,
                        'diskon' => 0,
                        'pajak' => 0,
                        'total' => $total,
                        'deskripsi' => 'SPP Bimbel '.$period->locale('id')->isoFormat('MMMM Y'),
                        'periode' => $period->format('Y-m'),
                        'jatuh_tempo' => $dueDate,
                        'status' => $status,
                        'catatan' => null,
                    ]);

                    if ($status === 'lunas') {
                        \App\Models\Payment::create([
                            'invoice_id' => $inv->id,
                            'siswa_id' => $s->id,
                            'cabang_id' => $s->branch_id,
                            'nomor_pembayaran' => 'PAY-'.date('Ymd',strtotime($period->format('Y-m-15'))).'-'.str_pad($invoiceNum, 4,'0',STR_PAD_LEFT),
                            'jumlah' => $total,
                            'metode' => collect(['cash','transfer','qris'])->random(),
                            'tanggal_pembayaran' => $period->copy()->addDays(rand(5,20))->format('Y-m-d'),
                            'status' => 'verified',
                            'catatan' => 'Pembayaran lunas',
                            'tanggal_disetujui' => now(),
                        ]);
                    }
                }
            }
            $this->command->info('Invoices and payments seeded');
        }

        $this->command->info('Demo data seeding complete!');
    }
}
