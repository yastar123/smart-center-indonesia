<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            // Kursus Komputer
            ['kode' => 'KOM-01', 'nama' => 'Microsoft Office Perkantoran', 'jenis_kursus' => 'komputer'],
            ['kode' => 'KOM-02', 'nama' => 'Word',                         'jenis_kursus' => 'komputer'],
            ['kode' => 'KOM-03', 'nama' => 'Excel',                        'jenis_kursus' => 'komputer'],
            ['kode' => 'KOM-04', 'nama' => 'PowerPoint',                   'jenis_kursus' => 'komputer'],
            ['kode' => 'KOM-05', 'nama' => 'Desain Grafis',                'jenis_kursus' => 'komputer'],
            ['kode' => 'KOM-06', 'nama' => 'CorelDraw',                    'jenis_kursus' => 'komputer'],
            ['kode' => 'KOM-07', 'nama' => 'Photoshop',                    'jenis_kursus' => 'komputer'],
            ['kode' => 'KOM-08', 'nama' => 'AutoCAD',                      'jenis_kursus' => 'komputer'],
            ['kode' => 'KOM-09', 'nama' => 'Programmer / Coding',          'jenis_kursus' => 'komputer'],

            // Kursus Bahasa Asing
            ['kode' => 'BHS-01', 'nama' => 'Bahasa Inggris',   'jenis_kursus' => 'bahasa'],
            ['kode' => 'BHS-02', 'nama' => 'Bahasa Arab',      'jenis_kursus' => 'bahasa'],
            ['kode' => 'BHS-03', 'nama' => 'Bahasa Mandarin',  'jenis_kursus' => 'bahasa'],
            ['kode' => 'BHS-04', 'nama' => 'Bahasa Jepang',    'jenis_kursus' => 'bahasa'],
            ['kode' => 'BHS-05', 'nama' => 'Bahasa Korea',     'jenis_kursus' => 'bahasa'],

            // Mata Pelajaran
            ['kode' => 'MAP-01', 'nama' => 'Matematika',              'jenis_kursus' => 'mapel'],
            ['kode' => 'MAP-02', 'nama' => 'Kimia',                   'jenis_kursus' => 'mapel'],
            ['kode' => 'MAP-03', 'nama' => 'Biologi',                 'jenis_kursus' => 'mapel'],
            ['kode' => 'MAP-04', 'nama' => 'Bahasa Indonesia',        'jenis_kursus' => 'mapel'],
            ['kode' => 'MAP-05', 'nama' => 'Fisika',                  'jenis_kursus' => 'mapel'],
            ['kode' => 'MAP-06', 'nama' => 'Akuntansi / Ekonomi',     'jenis_kursus' => 'mapel'],
            ['kode' => 'MAP-07', 'nama' => 'Geografi',                'jenis_kursus' => 'mapel'],
            ['kode' => 'MAP-08', 'nama' => 'IPA',                     'jenis_kursus' => 'mapel'],
            ['kode' => 'MAP-09', 'nama' => 'IPS',                     'jenis_kursus' => 'mapel'],

            // Program Kedinasan
            ['kode' => 'DIN-01', 'nama' => 'SKD TIU',   'jenis_kursus' => 'kedinasan'],
            ['kode' => 'DIN-02', 'nama' => 'SKD TWK',   'jenis_kursus' => 'kedinasan'],
            ['kode' => 'DIN-03', 'nama' => 'SKD TKP',   'jenis_kursus' => 'kedinasan'],
            ['kode' => 'DIN-04', 'nama' => 'TPA',        'jenis_kursus' => 'kedinasan'],
            ['kode' => 'DIN-05', 'nama' => 'Psikotes',   'jenis_kursus' => 'kedinasan'],
            ['kode' => 'DIN-06', 'nama' => 'TBI',        'jenis_kursus' => 'kedinasan'],

            // AKPOL / AKMIL / BINTARA
            ['kode' => 'AKP-01', 'nama' => 'Pengetahuan Umum',    'jenis_kursus' => 'akpol'],
            ['kode' => 'AKP-02', 'nama' => 'Wawasan Kebangsaan',  'jenis_kursus' => 'akpol'],
            ['kode' => 'AKP-03', 'nama' => 'TKD',                 'jenis_kursus' => 'akpol'],
            ['kode' => 'AKP-04', 'nama' => 'Tes Akademik',        'jenis_kursus' => 'akpol'],

            // CPNS
            ['kode' => 'CPN-01', 'nama' => 'SKD TIU (CPNS)',  'jenis_kursus' => 'cpns'],
            ['kode' => 'CPN-02', 'nama' => 'SKD TWK (CPNS)',  'jenis_kursus' => 'cpns'],
            ['kode' => 'CPN-03', 'nama' => 'SKD TKP (CPNS)',  'jenis_kursus' => 'cpns'],

            // BUMN
            ['kode' => 'BUM-01', 'nama' => 'TKD BUMN',    'jenis_kursus' => 'bumn'],
            ['kode' => 'BUM-02', 'nama' => 'Tes AKHLAK',  'jenis_kursus' => 'bumn'],
            ['kode' => 'BUM-03', 'nama' => 'TWK BUMN',    'jenis_kursus' => 'bumn'],
        ];

        foreach ($subjects as $s) {
            Course::firstOrCreate(
                ['kode' => $s['kode']],
                [
                    'nama'         => $s['nama'],
                    'jenis_kursus' => $s['jenis_kursus'],
                    'kategori'     => in_array($s['jenis_kursus'], ['komputer','bahasa']) ? 'skill' : 'academic',
                    'status'       => 'aktif',
                ]
            );
        }

        $this->command->info('✅ SubjectSeeder: ' . count($subjects) . ' mata pelajaran di-seed.');
    }
}
