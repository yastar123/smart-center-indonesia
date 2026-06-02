<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicYear;
use App\Models\Semester;

class AcademicYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tahun akademik
        $tahunAkademik = AcademicYear::create([
            'nama'            => '2025/2026',
            'tahun_mulai'     => 2025,
            'tahun_selesai'   => 2026,
            'is_active'       => true,
        ]);

        // Semester ganjil
        Semester::create([
            'tahun_akademik_id' => $tahunAkademik->id,
            'nama_semester'     => 'Ganjil',
            'nomor_semester'    => 1,
            'tanggal_mulai'     => '2025-07-15',
            'tanggal_selesai'   => '2025-12-20',
            'is_active'         => false,
        ]);

        // Semester genap
        Semester::create([
            'tahun_akademik_id' => $tahunAkademik->id,
            'nama_semester'     => 'Genap',
            'nomor_semester'    => 2,
            'tanggal_mulai'     => '2026-01-06',
            'tanggal_selesai'   => '2026-06-27',
            'is_active'         => true,
        ]);
    }
}