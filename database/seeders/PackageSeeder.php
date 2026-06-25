<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;
use App\Models\Course;

class PackageSeeder extends Seeder
{
    /**
     * Seeder untuk menghubungkan Paket dengan Mata Pelajaran (course_package pivot).
     *
     * Paket Reguler SMA       → 5 mapel (Mat, Fis, Kim, Bio, Ing)
     * Paket Intensif SNBT     → 6 mapel (Mat, Fis, Kim, Bio, Ing, SNBT)
     * Paket Online Basic      → 2 mapel (Mat, Ing)
     * Paket Reguler Bandung   → 3 mapel (Mat, Ing, Fis)
     * Paket Reguler Surabaya  → 2 mapel (Mat, Ing)
     */
    public function run(): void
    {
        $this->command->info('📦 Seeding package ↔ mata pelajaran...');

        // Ambil semua courses berindeks (cabang_id, nama)
        $courses = Course::all()->keyBy(fn($c) => $c->cabang_id . '|' . $c->nama);

        // Helper: cari course_id berdasarkan cabang & nama
        $courseId = fn(int $cabangId, string $nama): ?int
            => optional($courses->get("{$cabangId}|{$nama}"))->id;

        // ------------------------------------------------------------------ //
        // Cabang 1 — Pusat Jakarta
        // ------------------------------------------------------------------ //
        $cabangPusat = \App\Models\Branch::where('email', 'cabang.pusat@akademibimbel.com')->value('id');

        // 1. Paket Reguler SMA — 5 mata pelajaran
        $this->attach(
            'Paket Reguler SMA',
            $cabangPusat,
            array_filter([
                $courseId($cabangPusat, 'Matematika'),
                $courseId($cabangPusat, 'Fisika'),
                $courseId($cabangPusat, 'Kimia'),
                $courseId($cabangPusat, 'Biologi'),
                $courseId($cabangPusat, 'Bahasa Inggris'),
            ])
        );

        // 2. Paket Intensif SNBT — 6 mata pelajaran
        $this->attach(
            'Paket Intensif SNBT',
            $cabangPusat,
            array_filter([
                $courseId($cabangPusat, 'Matematika'),
                $courseId($cabangPusat, 'Fisika'),
                $courseId($cabangPusat, 'Kimia'),
                $courseId($cabangPusat, 'Biologi'),
                $courseId($cabangPusat, 'Bahasa Inggris'),
                $courseId($cabangPusat, 'Persiapan SNBT'),
            ])
        );

        // 3. Paket Online Basic — 2 mata pelajaran
        $this->attach(
            'Paket Online Basic',
            $cabangPusat,
            array_filter([
                $courseId($cabangPusat, 'Matematika'),
                $courseId($cabangPusat, 'Bahasa Inggris'),
            ])
        );

        // ------------------------------------------------------------------ //
        // Cabang 2 — Bandung
        // ------------------------------------------------------------------ //
        $cabangBandung = \App\Models\Branch::where('email', 'cabang.bandung@akademibimbel.com')->value('id');

        // 4. Paket Reguler Bandung — 3 mata pelajaran
        $this->attach(
            'Paket Reguler Bandung',
            $cabangBandung,
            array_filter([
                $courseId($cabangBandung, 'Matematika'),
                $courseId($cabangBandung, 'Bahasa Inggris'),
                $courseId($cabangBandung, 'Fisika'),
            ])
        );

        // ------------------------------------------------------------------ //
        // Cabang 3 — Surabaya
        // ------------------------------------------------------------------ //
        $cabangSurabaya = \App\Models\Branch::where('email', 'cabang.surabaya@akademibimbel.com')->value('id');

        // 5. Paket Reguler Surabaya — 2 mata pelajaran
        $this->attach(
            'Paket Reguler Surabaya',
            $cabangSurabaya,
            array_filter([
                $courseId($cabangSurabaya, 'Matematika'),
                $courseId($cabangSurabaya, 'Bahasa Inggris'),
            ])
        );

        $this->command->info('  ✅ Selesai! Semua paket sudah terhubung ke mata pelajaran.');
    }

    private function attach(string $namaPaket, int $cabangId, array $courseIds): void
    {
        $package = Package::where('nama', $namaPaket)
            ->where('cabang_id', $cabangId)
            ->first();

        if (!$package) {
            $this->command->warn("  ⚠️  Paket '{$namaPaket}' tidak ditemukan, skip.");
            return;
        }

        $package->mataPelajaran()->syncWithoutDetaching($courseIds);

        $jumlah = count($courseIds);
        $this->command->info("  📌 {$namaPaket}: {$jumlah} mata pelajaran");
    }
}
