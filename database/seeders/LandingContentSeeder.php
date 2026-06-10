<?php

namespace Database\Seeders;

use App\Models\LandingSetting;
use App\Models\LandingTestimonial;
use App\Models\LandingProgram;
use Illuminate\Database\Seeder;

class LandingContentSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['section'=>'hero',   'key'=>'hero.badge_text',      'label'=>'Teks Badge Hero',           'type'=>'text',    'value'=>'Bimbel & Kursus Terbaik #1 di Indonesia', 'sort_order'=>1],
            ['section'=>'hero',   'key'=>'hero.title_line1',     'label'=>'Judul Hero Baris 1',         'type'=>'text',    'value'=>'Wujudkan Mimpi,',                          'sort_order'=>2],
            ['section'=>'hero',   'key'=>'hero.title_line2',     'label'=>'Judul Hero Baris 2 (gradien)','type'=>'text',   'value'=>'Raih Prestasi!',                           'sort_order'=>3],
            ['section'=>'hero',   'key'=>'hero.description',     'label'=>'Deskripsi Hero',             'type'=>'textarea','value'=>'Smart Center Indonesia — lembaga bimbingan belajar, kursus, dan les privat berbasis offline & online. Tutor profesional, metode modern, hasil terukur untuk semua jenjang dari TK hingga umum.', 'sort_order'=>4],
            ['section'=>'hero',   'key'=>'hero.slide_1_url',     'label'=>'URL Slide 1',                'type'=>'url',     'value'=>'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1920&q=80', 'sort_order'=>5],
            ['section'=>'hero',   'key'=>'hero.slide_2_url',     'label'=>'URL Slide 2',                'type'=>'url',     'value'=>'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1920&q=80', 'sort_order'=>6],
            ['section'=>'hero',   'key'=>'hero.slide_3_url',     'label'=>'URL Slide 3',                'type'=>'url',     'value'=>'https://images.unsplash.com/photo-1571260899304-425eee4c7efc?auto=format&fit=crop&w=1920&q=80', 'sort_order'=>7],
            ['section'=>'hero',   'key'=>'hero.float1_title',    'label'=>'Float Card 1 Judul',         'type'=>'text',    'value'=>'Nilai Naik!',                              'sort_order'=>8],
            ['section'=>'hero',   'key'=>'hero.float1_subtitle', 'label'=>'Float Card 1 Subjudul',      'type'=>'text',    'value'=>'Rata-rata +30 poin · Bulan ini',           'sort_order'=>9],
            ['section'=>'hero',   'key'=>'hero.float2_title',    'label'=>'Float Card 2 Judul',         'type'=>'text',    'value'=>'Siswa Baru Daftar',                        'sort_order'=>10],
            ['section'=>'hero',   'key'=>'hero.float2_subtitle', 'label'=>'Float Card 2 Subjudul',      'type'=>'text',    'value'=>'Les Privat · Baru saja',                   'sort_order'=>11],
            ['section'=>'stats',  'key'=>'stats.years_exp',      'label'=>'Tahun Pengalaman',           'type'=>'text',    'value'=>'14+',                                      'sort_order'=>1],
            ['section'=>'stats',  'key'=>'stats.satisfaction',   'label'=>'% Kepuasan Pelanggan',       'type'=>'text',    'value'=>'98%',                                      'sort_order'=>2],
            ['section'=>'cta',    'key'=>'cta.eyebrow',          'label'=>'Teks Eyebrow CTA',           'type'=>'text',    'value'=>'Mulai Sekarang',                           'sort_order'=>1],
            ['section'=>'cta',    'key'=>'cta.title',            'label'=>'Judul CTA',                  'type'=>'text',    'value'=>'Wujudkan Mimpi Bersama SCI!',              'sort_order'=>2],
            ['section'=>'cta',    'key'=>'cta.description',      'label'=>'Deskripsi CTA',              'type'=>'textarea','value'=>'Bergabunglah bersama ribuan siswa yang telah meraih prestasi bersama Smart Center Indonesia. Konsultasi gratis, daftar mudah!', 'sort_order'=>3],
            ['section'=>'footer', 'key'=>'footer.wa_number',     'label'=>'Nomor WhatsApp',             'type'=>'text',    'value'=>'628001234567',                            'sort_order'=>1],
            ['section'=>'footer', 'key'=>'footer.instagram',     'label'=>'URL Instagram',              'type'=>'url',     'value'=>'#',                                        'sort_order'=>2],
            ['section'=>'footer', 'key'=>'footer.facebook',      'label'=>'URL Facebook',               'type'=>'url',     'value'=>'#',                                        'sort_order'=>3],
            ['section'=>'footer', 'key'=>'footer.youtube',       'label'=>'URL YouTube',                'type'=>'url',     'value'=>'#',                                        'sort_order'=>4],
            ['section'=>'footer', 'key'=>'footer.brand_desc',    'label'=>'Deskripsi Brand Footer',     'type'=>'textarea','value'=>'Lembaga bimbingan belajar, kursus, dan les privat terbaik di Indonesia. Berkomitmen menjadi lembaga pendidikan nomor 1 di Indonesia. "Wujudkan mimpi, raih prestasi!"', 'sort_order'=>5],
        ];

        foreach ($settings as $s) {
            LandingSetting::updateOrCreate(['key' => $s['key']], $s);
        }

        $testimonials = [
            ['name'=>'Rini Kusumawati','role'=>'Siswa SMA · Surabaya','text'=>'"Nilai matematika saya naik dari 60 ke 90 setelah 3 bulan bimbel di SCI! Tutornya sabar dan cara jelasinnya mudah dipahami. Sekarang saya jadi suka matematika."','gradient'=>'linear-gradient(135deg,#c84ddf,#68117e)','is_active'=>true,'sort_order'=>1],
            ['name'=>'Dimas Prasetyo','role'=>'Orang Tua Siswa SD · Jakarta','text'=>'"Anak saya dulu kesulitan di Bahasa Inggris. Sejak les di SCI, dalam 2 bulan sudah bisa percakapan dasar dengan lancar. Tutornya sangat profesional dan sabar."','gradient'=>'linear-gradient(135deg,#f6af23,#e09000)','is_active'=>true,'sort_order'=>2],
            ['name'=>'Siti Nuraini','role'=>'Mahasiswa ITB · Bandung','text'=>'"Berkat program intensif SBMPTN di SCI, saya berhasil masuk ITB! Materinya lengkap, soal-soal latihannya mirip ujian asli, dan tutornya selalu siap membantu."','gradient'=>'linear-gradient(135deg,#10b981,#059669)','is_active'=>true,'sort_order'=>3],
            ['name'=>'Andika Putra','role'=>'Alumni Kursus Komputer · Yogyakarta','text'=>'"Kursus komputer di SCI luar biasa! Dalam 3 bulan saya sudah bisa desain grafis dan sekarang sudah dapat klien freelance. Materi up-to-date dan tutornya expert."','gradient'=>'linear-gradient(135deg,#6366f1,#4338ca)','is_active'=>true,'sort_order'=>4],
            ['name'=>'Melati Dewi','role'=>'Karyawan · Bekasi','text'=>'"Les privat Bahasa Jepang di SCI sangat membantu persiapan JLPT N3 saya. Tutor datang ke rumah, jadwal fleksibel, dan materi disesuaikan kebutuhan. Alhamdulillah lulus!"','gradient'=>'linear-gradient(135deg,#f43f5e,#be123c)','is_active'=>true,'sort_order'=>5],
            ['name'=>'Hendra Wijaya','role'=>'Orang Tua Siswa SD · Surabaya','text'=>'"SCI benar-benar membantu anakku yang kelas 4 SD. Dulu nilainya selalu di bawah rata-rata, sekarang masuk 10 besar kelas. Guru-gurunya baik dan tidak bikin bosan belajar."','gradient'=>'linear-gradient(135deg,#14b8a6,#0f766e)','is_active'=>true,'sort_order'=>6],
        ];
        foreach ($testimonials as $t) {
            $t['initial'] = strtoupper(substr($t['name'], 0, 1));
            LandingTestimonial::firstOrCreate(['name' => $t['name'], 'role' => $t['role']], $t);
        }

        $programs = [
            ['title'=>'Bimbel Mata Pelajaran','description'=>'Bimbingan semua mata pelajaran sekolah dengan metode efektif dan menyenangkan untuk meningkatkan nilai secara signifikan.','badge_label'=>'SEMUA JENJANG','badge_bg'=>'rgba(200,77,223,.1)','badge_color'=>'#68117e','icon_emoji'=>'📖','is_active'=>true,'is_popular'=>false,'is_new'=>false,'sort_order'=>1],
            ['title'=>'Persiapan Ujian','description'=>'Persiapan UTS, UAS & Ujian Sekolah agar nilai meningkat pesat dan lulus dengan hasil terbaik.','badge_label'=>'SMP · SMA','badge_bg'=>'rgba(99,102,241,.1)','badge_color'=>'#4f46e5','icon_emoji'=>'📝','is_active'=>true,'is_popular'=>false,'is_new'=>false,'sort_order'=>2],
            ['title'=>'Persiapan Tes & SBMPTN','description'=>'Persiapan masuk sekolah favorit, PTN, CPNS & tes lainnya secara intensif dengan mentor berpengalaman.','badge_label'=>'INTENSIF','badge_bg'=>'rgba(239,68,68,.1)','badge_color'=>'#dc2626','icon_emoji'=>'🎯','is_active'=>true,'is_popular'=>false,'is_new'=>false,'sort_order'=>3],
            ['title'=>'Kursus Bahasa','description'=>'Inggris, Jepang, Mandarin, Arab — tingkatkan kemampuan bahasa Anda bersama tutor native & bersertifikat.','badge_label'=>'SEMUA LEVEL','badge_bg'=>'rgba(20,184,166,.1)','badge_color'=>'#0f766e','icon_emoji'=>'🌐','is_active'=>true,'is_popular'=>false,'is_new'=>false,'sort_order'=>4],
            ['title'=>'Kursus Komputer','description'=>'Microsoft Office, Desain Grafis, Programming — teknologi terkini untuk karir dan masa depan gemilang.','badge_label'=>'POPULER 🔥','badge_bg'=>'rgba(246,175,35,.15)','badge_color'=>'#e09000','icon_emoji'=>'💻','is_active'=>true,'is_popular'=>true,'is_new'=>false,'sort_order'=>5],
            ['title'=>'Kursus Akuntansi','description'=>'Akuntansi dasar hingga profesional, perpajakan & keuangan untuk mahasiswa dan karyawan.','badge_label'=>'TERBARU ✨','badge_bg'=>'rgba(16,185,129,.1)','badge_color'=>'#059669','icon_emoji'=>'📊','is_active'=>true,'is_popular'=>false,'is_new'=>true,'sort_order'=>6],
        ];
        foreach ($programs as $p) {
            LandingProgram::firstOrCreate(['title' => $p['title']], $p);
        }
    }
}
