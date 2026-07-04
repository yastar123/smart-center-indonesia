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
        $city = $branch->city ?: $branch->name;
        $bid  = $branch->id;

        /* ── Teachers & Packages ── */
        $teachers = Teacher::where('branch_id', $bid)->where('status', 'aktif')->get();
        $packages = Package::where('cabang_id', $bid)->where('status', 'aktif')->get();

        /* ── Global testimonials ── */
        $testimonials = LandingTestimonial::active()->orderBy('sort_order')->get();

        /* ── Main WA fallback ── */
        $lsAll  = LandingSetting::all()->keyBy('key');
        $ls     = fn(string $k, string $d = '') => $lsAll[$k]->value ?? $d;
        $waMain = LandingWaNumber::primaryNumber($ls('footer.wa_number', '628001234567'));

        /* ── Branch WA ── */
        $branchWa = preg_replace('/[^0-9]/', '', $branch->phone ?? '');
        if (strlen($branchWa) < 8) {
            $branchWa = $waMain;
        } elseif (!str_starts_with($branchWa, '62')) {
            $branchWa = '62' . ltrim($branchWa, '0');
        }

        /* ── Branch landing settings ── */
        $bls = BranchLandingSetting::forBranch($bid);
        $get = fn(string $k, string $d = '') => $bls[$k] ?? $d;
        $getJson = fn(string $k) => json_decode($bls[$k] ?? '[]', true) ?: [];

        /* ── Promo ticker ── */
        $promoItems = $getJson('promo_items') ?: [
            'Mulai belajar dari Rp 50.000/sesi',
            'Garansi nilai naik atau sesi gratis!',
            'Tersedia Home Visit, Online & Offline',
            'Gratis Konsultasi Pertama',
            '#1 Les Privat Terbaik di ' . $city,
        ];

        /* ── Hero ── */
        $heroBadge = $get('hero_badge') ?: '#1 Jasa Les Privat ' . strtoupper($city) . ' Terpercaya';
        $heroDesc  = $get('hero_description') ?: 'Smart Center Indonesia hadir di ' . $city . ' dengan tutor bersertifikat. Layanan home visit, online, dan offline untuk semua jenjang dari TK hingga umum.';
        $heroBg    = $get('hero_bg') ?: 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=1400&q=60';

        /* ── Jam Operasional ── */
        $hoursWeekday = $get('hours_weekday') ?: '08.00 – 20.00 WIB';
        $hoursWeekend = $get('hours_weekend') ?: '09.00 – 16.00 WIB';

        /* ── Area chips ── */
        $areaChips = $getJson('areas') ?: [
            'Kota ' . $city,
            ...($branch->regency && $branch->regency !== $branch->city ? ['Kab. ' . $branch->regency] : []),
            'Sekitarnya',
        ];

        /* ── Metode prices ── */
        $prices = [
            'homevisi' => $get('price_homevisi') ?: 'Rp 65.000',
            'online'   => $get('price_online')   ?: 'Rp 50.000',
            'offline'  => $get('price_offline')  ?: 'Rp 55.000',
        ];

        /* ── Metode images ── */
        $metodeImages = [
            'homevisi' => $get('metode_img_homevisi') ?: 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=600&q=80',
            'online'   => $get('metode_img_online')   ?: 'https://images.unsplash.com/photo-1588702547923-7093a6c3ba33?w=600&q=80',
            'offline'  => $get('metode_img_offline')  ?: 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=600&q=80',
        ];

        /* ── Feature cards — Dipercaya Ribuan Keluarga ── */
        $featuresDb = $getJson('features');
        $features   = $featuresDb ?: [
            ['num'=>'01','icon'=>'👩‍🏫','title'=>'Tutor Bersertifikat',    'desc'=>'Semua tutor SCI '.$city.' telah melalui seleksi ketat, pelatihan intensif, dan memiliki sertifikat mengajar resmi.'],
            ['num'=>'02','icon'=>'🏠','title'=>'Home Visit',               'desc'=>'Tutor datang ke rumah Anda di seluruh wilayah '.$city.'. Nyaman, privat, dan efisien.'],
            ['num'=>'03','icon'=>'📈','title'=>'Prestasi Siswa Meningkat', 'desc'=>'Berdasarkan evaluasi internal, mayoritas siswa SCI '.$city.' mengalami peningkatan nilai dalam beberapa bulan pertama.'],
            ['num'=>'04','icon'=>'⏰','title'=>'Jadwal Fleksibel',         'desc'=>'Belajar bisa pagi, siang, sore, ataupun malam hari sesuai kebutuhan siswa.'],
            ['num'=>'05','icon'=>'💰','title'=>'Harga Transparan',         'desc'=>'Tidak ada biaya tersembunyi. Tersedia paket hemat, pembayaran bulanan, maupun per sesi belajar.'],
            ['num'=>'06','icon'=>'🛡️','title'=>'Garansi Kepuasan',         'desc'=>'Tutor dapat diganti apabila kurang cocok tanpa biaya tambahan.'],
        ];

        /* ── Subject cards — Program Les & Kursus ── */
        $subjectsDb = $getJson('subjects');
        $subjects   = $subjectsDb ?: [
            ['icon'=>'🔢','name'=>'Matematika',     'desc'=>'SD, SMP, SMA, Kuliah. Dari aritmatika hingga kalkulus.',           'badge'=>'Terpopuler','badge_type'=>'popular'],
            ['icon'=>'⚡','name'=>'Fisika',          'desc'=>'Mekanika, gelombang, listrik magnetik, termodinamika.',             'badge'=>'SMP–SMA',   'badge_type'=>'level'],
            ['icon'=>'🧪','name'=>'Kimia',           'desc'=>'Kimia organik, anorganik, stoikiometri, kimia analitik.',           'badge'=>'SMP–SMA',   'badge_type'=>'level'],
            ['icon'=>'🌿','name'=>'Biologi',         'desc'=>'Sel, genetika, ekosistem, anatomi, fisiologi manusia.',             'badge'=>'SMP–SMA',   'badge_type'=>'level'],
            ['icon'=>'🇬🇧','name'=>'Bahasa Inggris', 'desc'=>'Speaking, grammar, reading, TOEFL/IELTS preparation.',             'badge'=>'Terpopuler','badge_type'=>'popular'],
            ['icon'=>'💻','name'=>'Komputer',        'desc'=>'MS Office, Photoshop, Canva, Programming, Web Design.',            'badge'=>'Populer',   'badge_type'=>'hot'],
            ['icon'=>'📊','name'=>'Akuntansi',       'desc'=>'Akuntansi dasar–profesional, perpajakan, MYOB.',                   'badge'=>'Umum',      'badge_type'=>'general'],
            ['icon'=>'🇯🇵','name'=>'Bahasa Jepang',  'desc'=>'Hiragana, katakana, kanji, JLPT N5–N1 preparation.',              'badge'=>'Semua Level','badge_type'=>'general'],
            ['icon'=>'📐','name'=>'Bahasa Indonesia','desc'=>'Tata bahasa, menulis, membaca, persiapan UN/UTBK.',               'badge'=>'SD–SMA',    'badge_type'=>'level'],
            ['icon'=>'🎨','name'=>'Seni & Desain',   'desc'=>'Menggambar, desain grafis, fotografi dasar, digital art.',         'badge'=>'Umum',      'badge_type'=>'general'],
            ['icon'=>'🗣️','name'=>'Public Speaking',  'desc'=>'Kepercayaan diri berbicara, debat, presentasi profesional.',      'badge'=>'Semua Level','badge_type'=>'general'],
            ['icon'=>'📚','name'=>'Persiapan SBMPTN','desc'=>'Latihan soal UTBK, strategi menjawab, simulasi ujian lengkap.',   'badge'=>'SMA',       'badge_type'=>'level'],
        ];

        /* ── CTA ── */
        $ctaEyebrow = $get('cta_eyebrow') ?: '🎉 Bergabung Sekarang';
        $ctaTitle   = $get('cta_title')   ?: 'Siap Mulai Belajar';
        $ctaDesc    = $get('cta_desc')    ?: '';

        /* ── FAQ ── */
        $faqItems = $getJson('faq_items') ?: [
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

        /* ── Student / tutor counts ── */
        $studentCount = \App\Models\Student::where('branch_id', $bid)->count() ?: 1400;
        $tutorCount   = $teachers->count() ?: 85;

        /* ── Testimonials fallback ── */
        if ($testimonials->isEmpty()) {
            $testimonials = collect([
                (object)['text'=>'Anakku yang awalnya kesulitan Matematika sekarang jadi juara kelas!','name'=>'Bunda Sari','role'=>'Orang Tua Siswa · '.$city,'initial'=>'B','gradient'=>'linear-gradient(135deg,#f97316,#ea580c)'],
                (object)['text'=>'Belajar di SCI sangat menyenangkan! Nilai saya meningkat pesat.','name'=>'Aisyah R.','role'=>'Siswa SMA · Matematika','initial'=>'A','gradient'=>'linear-gradient(135deg,#c84ddf,#68117e)'],
                (object)['text'=>'Program persiapan SBMPTN SCI sangat membantu. Akhirnya lolos kampus impian!','name'=>'Ricky P.','role'=>'Mahasiswa · SBMPTN','initial'=>'R','gradient'=>'linear-gradient(135deg,#10b981,#059669)'],
                (object)['text'=>'Home visit-nya sangat nyaman. Tutornya datang tepat waktu dan sabar.','name'=>'Pak Hendra','role'=>'Orang Tua Siswa · Home Visit','initial'=>'H','gradient'=>'linear-gradient(135deg,#6366f1,#4338ca)'],
            ]);
        }

        return view('branch-landing', compact(
            'branch', 'city', 'teachers', 'packages', 'testimonials',
            'waMain', 'branchWa',
            'promoItems', 'heroBadge', 'heroDesc', 'heroBg',
            'hoursWeekday', 'hoursWeekend', 'areaChips', 'prices', 'metodeImages',
            'features', 'subjects',
            'ctaEyebrow', 'ctaTitle', 'ctaDesc',
            'faqItems', 'studentCount', 'tutorCount'
        ));
    }
}
