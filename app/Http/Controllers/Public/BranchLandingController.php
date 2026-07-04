<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchLandingSetting;
use App\Models\LandingTestimonial;
use App\Models\LandingSetting;
use App\Models\LandingWaNumber;
use App\Models\Package;
use App\Models\Teacher;

class BranchLandingController extends Controller
{
    public function show(Branch $branch)
    {
        $city       = $branch->city ?: $branch->name;
        $bid        = $branch->id;

        /* ── Teachers & Packages ── */
        $teachers = Teacher::where('branch_id', $bid)->where('status', 'aktif')->get();
        $packages = Package::where('cabang_id', $bid)->where('status', 'aktif')->get();

        /* ── Global testimonials ── */
        $testimonials = LandingTestimonial::active()->orderBy('sort_order')->get();

        /* ── Main WA fallback ── */
        $lsAll   = LandingSetting::all()->keyBy('key');
        $ls      = fn(string $k, string $d = '') => $lsAll[$k]->value ?? $d;
        $waMain  = LandingWaNumber::primaryNumber($ls('footer.wa_number', '628001234567'));

        /* ── Branch WA ── */
        $branchWa = preg_replace('/[^0-9]/', '', $branch->phone ?? '');
        if (strlen($branchWa) < 8) {
            $branchWa = $waMain;
        } elseif (!str_starts_with($branchWa, '62')) {
            $branchWa = '62' . ltrim($branchWa, '0');
        }

        /* ── Branch landing settings from DB ── */
        $bls = BranchLandingSetting::forBranch($bid);

        /* ── Promo ticker items ── */
        $promoItems = json_decode($bls['promo_items'] ?? '[]', true) ?: [
            'Mulai belajar dari Rp 50.000/sesi',
            'Garansi nilai naik atau sesi gratis!',
            'Tersedia Home Visit, Online & Offline',
            'Gratis Konsultasi Pertama',
            '#1 Les Privat Terbaik di '.$city,
        ];

        /* ── Hero ── */
        $heroBadge = $bls['hero_badge'] ?? '#1 Jasa Les Privat '.strtoupper($city).' Terpercaya';
        $heroDesc  = $bls['hero_description']
            ?? 'Smart Center Indonesia hadir di '.$city.' dengan tutor bersertifikat. Layanan home visit, online, dan offline untuk semua jenjang dari TK hingga umum.';

        /* ── Jam Operasional ── */
        $hoursWeekday = $bls['hours_weekday'] ?? '08.00 – 20.00 WIB';
        $hoursWeekend = $bls['hours_weekend'] ?? '09.00 – 16.00 WIB';

        /* ── Area layanan chips ── */
        $areaChips = json_decode($bls['areas'] ?? '[]', true) ?: [
            'Kota '.$city,
            ...($branch->regency && $branch->regency !== $branch->city ? ['Kab. '.$branch->regency] : []),
            'Sekitarnya',
        ];

        /* ── Metode Belajar pricing ── */
        $prices = [
            'homevisi' => $bls['price_homevisi'] ?? 'Rp 65.000',
            'online'   => $bls['price_online']   ?? 'Rp 50.000',
            'offline'  => $bls['price_offline']  ?? 'Rp 55.000',
        ];

        /* ── FAQ ── */
        $faqItems = json_decode($bls['faq_items'] ?? '[]', true) ?: [
            ['q' => 'Berapa harga les privat SCI '.$city.'?',
             'a' => 'Harga les privat SCI '.$city.' mulai dari '.$prices['online'].'/sesi untuk online hingga '.$prices['homevisi'].'/sesi untuk home visit.'],
            ['q' => 'Apakah bisa les privat home visit di '.$city.'?',
             'a' => 'Ya! SCI '.$city.' melayani home visit ke seluruh kota dan kabupaten di '.$city.'.'],
            ['q' => 'Bagaimana cara mendaftar les privat di SCI '.$city.'?',
             'a' => 'Isi formulir konsultasi gratis di halaman ini, atau hubungi kami via WhatsApp. Tim kami akan menghubungi Anda dalam 1 jam.'],
            ['q' => 'Apakah ada garansi nilai naik?',
             'a' => 'Ya! SCI memberikan garansi nilai naik atau sesi gratis jika nilai tidak meningkat sesuai target.'],
            ['q' => 'Berapa lama satu sesi les privat berlangsung?',
             'a' => 'Satu sesi berlangsung 90 menit. Durasi dapat disesuaikan sesuai kebutuhan.'],
            ['q' => 'Apakah bisa berganti tutor jika tidak cocok?',
             'a' => 'Tentu! Penggantian tutor dapat dilakukan kapan saja dan sepenuhnya gratis.'],
            ['q' => 'Metode pembayaran apa saja yang tersedia?',
             'a' => 'Transfer bank, dompet digital (GoPay, OVO, Dana, ShopeePay), QRIS, dan tunai di kantor.'],
            ['q' => 'Apakah SCI juga melayani kursus untuk orang dewasa?',
             'a' => 'Ya! SCI melayani semua usia dari TK hingga profesional dewasa.'],
        ];

        /* ── Student count ── */
        $studentCount = \App\Models\Student::where('branch_id', $bid)->count() ?: 1400;
        $tutorCount   = $teachers->count() ?: 85;

        /* ── Testimonials fallback ── */
        if ($testimonials->isEmpty()) {
            $testimonials = collect([
                (object)['text'=>'Anakku yang awalnya kesulitan Matematika sekarang jadi juara kelas!','name'=>'Bunda Sari','role'=>'Orang Tua Siswa · '.$city,'initial'=>'B','gradient'=>'linear-gradient(135deg,#f97316,#ea580c)'],
                (object)['text'=>'Belajar di SCI sangat menyenangkan! Nilai saya meningkat pesat.','name'=>'Aisyah R.','role'=>'Siswa SMA · Matematika','initial'=>'A','gradient'=>'linear-gradient(135deg,#c84ddf,#68117e)'],
                (object)['text'=>'Program persiapan SBMPTN SCI sangat membantu. Akhirnya lolos kampus impian!','name'=>'Ricky P.','role'=>'Mahasiswa · SBMPTN','initial'=>'R','gradient'=>'linear-gradient(135deg,#10b981,#059669)'],
                (object)['text'=>'Home visit-nya sangat nyaman. Tutornya datang tepat waktu dan sabar.','name'=>'Pak Hendra','role'=>'Orang Tua Siswa · Home Visit','initial'=>'H','gradient'=>'linear-gradient(135deg,#6366f1,#4338ca)'],
                (object)['text'=>'Kursus Bahasa Inggris di SCI sangat bermanfaat. Sekarang saya sudah lulus TOEFL.','name'=>'Dinda L.','role'=>'Mahasiswi · Bahasa Inggris','initial'=>'D','gradient'=>'linear-gradient(135deg,#0ea5e9,#0284c7)'],
                (object)['text'=>'Saya kursus komputer di SCI dan langsung bisa kerja freelance!','name'=>'Fajar W.','role'=>'Karyawan · Kursus Komputer','initial'=>'F','gradient'=>'linear-gradient(135deg,#f59e0b,#d97706)'],
            ]);
        }

        return view('branch-landing', compact(
            'branch','city','teachers','packages','testimonials',
            'waMain','branchWa',
            'promoItems','heroBadge','heroDesc',
            'hoursWeekday','hoursWeekend','areaChips','prices','faqItems',
            'studentCount','tutorCount'
        ));
    }
}
