<?php

namespace Database\Seeders;

use App\Models\LandingFaq;
use App\Models\LandingFeature;
use App\Models\LandingGallery;
use App\Models\LandingHighlight;
use App\Models\LandingJenjang;
use App\Models\LandingProgram;
use App\Models\LandingSetting;
use App\Models\LandingTestimonial;
use App\Models\LandingTicker;
use App\Models\LandingTrust;
use App\Models\LandingWaNumber;
use Illuminate\Database\Seeder;

class LandingContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSettings();
        $this->seedTicker();
        $this->seedFeatures();
        $this->seedPrograms();
        $this->seedJenjang();
        $this->seedTrust();
        $this->seedHighlights();
        $this->seedTestimonials();
        $this->seedGallery();
        $this->seedFaqs();
        $this->seedWa();
    }

    private function upsert(string $section, string $key, string $value, string $label, string $type = 'text'): void
    {
        LandingSetting::updateOrCreate(['key' => $key], [
            'section' => $section, 'value' => $value, 'label' => $label, 'type' => $type,
        ]);
    }

    private function seedSettings(): void
    {
        $s = [
            ['hero','hero.badge_text','Bimbel & Kursus Terbaik #1 di Indonesia','Teks Badge Hero'],
            ['hero','hero.title_line1','Wujudkan Mimpi,','Judul Baris 1'],
            ['hero','hero.title_line2','Raih Prestasi!','Judul Baris 2'],
            ['hero','hero.description','Lembaga bimbingan belajar, kursus, dan les privat terbaik di Indonesia. Melayani TK hingga umum dengan tutor profesional dan hasil terukur.','Deskripsi Hero'],
            ['hero','hero.slide_1_url','https://images.unsplash.com/photo-1580582932707-520aed937b7b?auto=format&fit=crop&w=1920&q=80','Slide 1 URL','image'],
            ['hero','hero.slide_2_url','https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1920&q=80','Slide 2 URL','image'],
            ['hero','hero.slide_3_url','https://images.unsplash.com/photo-1571260899304-425eee4c7efc?auto=format&fit=crop&w=1920&q=80','Slide 3 URL','image'],
            ['hero','hero.float1_title','','Float Card 1 Judul'],
            ['hero','hero.float1_subtitle','','Float Card 1 Subjudul'],
            ['hero','hero.float2_title','','Float Card 2 Judul'],
            ['hero','hero.float2_subtitle','','Float Card 2 Subjudul'],
            ['stats','stats.years_exp','14+','Tahun Pengalaman'],
            ['stats','stats.satisfaction','98%','Kepuasan Pelanggan'],
            ['cta','cta.eyebrow','Mulai Sekarang','Eyebrow CTA'],
            ['cta','cta.title','Wujudkan Mimpi Bersama SCI!','Judul CTA'],
            ['cta','cta.description','Daftar sekarang dan mulai perjalanan belajarmu bersama tutor terbaik kami.','Deskripsi CTA'],
            ['footer','footer.brand_desc','Platform pendidikan modern untuk semua jenjang. Dari TK hingga profesional — kami selalu ada untuk mendukung perjalanan belajar Anda.','Deskripsi Brand Footer'],
            ['footer','footer.instagram','#','URL Instagram'],
            ['footer','footer.facebook','#','URL Facebook'],
            ['footer','footer.youtube','#','URL YouTube'],
            ['tentang','tentang.title_line1','Tentang','Judul Baris 1'],
            ['tentang','tentang.title_accent','Smart Center Indonesia','Judul Aksen'],
            ['tentang','tentang.desc1','Smart Center Indonesia (SCI) adalah lembaga pendidikan yang bergerak di bidang bimbingan belajar, kursus, dan les privat (1 guru 1 siswa) berbasis offline dan online yang berkomitmen menjadi lembaga terbaik nomor 1 di Indonesia.','Deskripsi 1'],
            ['tentang','tentang.desc2','Dengan metode pembelajaran efektif, pengajar berpengalaman, serta pendekatan personal, SCI hadir sebagai solusi pendidikan terpercaya.','Deskripsi 2'],
            ['tentang','tentang.quote','Wujudkan mimpi, raih prestasi!','Kutipan'],
            ['cariguru','cariguru.eyebrow','TEMUKAN PENGAJAR TERBAIK','Eyebrow'],
            ['cariguru','cariguru.title_line1','Cari Guru','Judul Baris 1'],
            ['cariguru','cariguru.title_accent','Terbaik','Judul Aksen'],
            ['cariguru','cariguru.title_line2',', Secepat Klik','Judul Baris 2'],
            ['cariguru','cariguru.subtitle','Temukan tutor privat terbaik di kotamu — pilih berdasarkan mata pelajaran, lokasi, dan metode belajar yang kamu inginkan.','Subjudul'],
            ['keunggulan','keunggulan.title_accent','SCI','Judul Aksen'],
            ['keunggulan','keunggulan.subtitle','Lima pilar yang membuat SCI menjadi pilihan terpercaya jutaan keluarga Indonesia selama 14+ tahun.','Subjudul'],
            ['galeri','galeri.subtitle','Momen belajar menyenangkan bersama siswa dan tutor terbaik SCI di seluruh Indonesia.','Subjudul'],
            ['bantuan','bantuan.eyebrow','Bantuan & Kontak','Eyebrow'],
            ['bantuan','bantuan.subtitle','Punya pertanyaan atau ingin bergabung? Kami siap membantu Anda kapan saja.','Subjudul'],
            ['cabang','cabang.eyebrow','Hadir di Seluruh Indonesia','Eyebrow'],
            ['cabang','cabang.subtitle','Dengan 150+ cabang di berbagai kota, SCI selalu dekat dengan Anda dan keluarga.','Subjudul'],
        ];
        foreach ($s as $row) {
            [$section, $key, $value, $label] = $row;
            $type = $row[4] ?? 'text';
            $this->upsert($section, $key, $value, $label, $type);
        }
    }

    private function seedTicker(): void
    {
        if (LandingTicker::count() > 0) return;
        $items = [
            ['🎉','Diskon Spesial! Gratis biaya pendaftaran bulan ini'],
            ['📚','Daftar sekarang & dapatkan sesi konsultasi GRATIS!'],
            ['🎁','Promo Paket Hemat: Beli 10 sesi gratis 2 sesi ekstra'],
            ['⭐','Lebih dari 1.000+ siswa sudah bergabung bersama kami'],
            ['🏆','Tutor berpengalaman & bersertifikat nasional'],
            ['📞','Hubungi kami sekarang — konsultasi gratis!'],
        ];
        foreach ($items as $i => [$emoji, $text]) {
            LandingTicker::create(['emoji'=>$emoji,'text'=>$text,'sort_order'=>$i]);
        }
    }

    private function seedFeatures(): void
    {
        if (LandingFeature::count() > 0) return;
        $items = [
            ['bi-patch-check-fill','Tutor Bersertifikat'],
            ['bi-house-heart-fill','Bisa Home Visit'],
            ['bi-camera-video-fill','Kelas Online & Offline'],
            ['bi-bar-chart-fill','Evaluasi Rutin Bulanan'],
            ['bi-headset','Konsultasi 24/7'],
            ['bi-bullseye','Target & Hasil Terukur'],
        ];
        foreach ($items as $i => [$icon, $label]) {
            LandingFeature::create(['icon'=>$icon,'label'=>$label,'sort_order'=>$i]);
        }
    }

    private function seedPrograms(): void
    {
        if (LandingProgram::count() > 0) return;
        $items = [
            ['Bimbel Mata Pelajaran','Bimbingan semua mata pelajaran sekolah dengan metode efektif dan menyenangkan.','SEMUA JENJANG','#e8f5e9','#2e7d32','📖','https://images.unsplash.com/photo-1509228468518-180dd4864904?w=600&q=80'],
            ['Persiapan Ujian','Persiapan UTS, UAS & Ujian Sekolah agar nilai meningkat pesat dan lulus terbaik.','SMP · SMA','#f3e8ff','#7e22ce','📝','https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?w=600&q=80'],
            ['Persiapan Tes & SBMPTN','Persiapan masuk sekolah favorit, PTN, CPNS & tes lainnya secara intensif.','INTENSIF','#fff7ed','#c2410c','🎯','https://images.unsplash.com/photo-1503676382389-4809596d5290?w=600&q=80'],
            ['Kursus Bahasa','Inggris, Jepang, Mandarin, Arab — tingkatkan kemampuan bahasa Anda bersama kami.','SEMUA LEVEL','#e0f2fe','#0369a1','🗣️','https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600&q=80'],
            ['Kursus Komputer','Microsoft Office, Desain Grafis, Programming — teknologi terkini untuk karir masa depan.','POPULER 🔥','#fef2f2','#b91c1c','💻','https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=600&q=80'],
            ['Kursus Akuntansi','Akuntansi dasar hingga profesional: perpajakan & keuangan untuk mahasiswa dan karyawan.','TERBARU ✨','#f5f3ff','#6d28d9','📊','https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=600&q=80'],
        ];
        foreach ($items as $i => [$title, $desc, $badge, $bg, $color, $emoji, $img]) {
            LandingProgram::create([
                'title'=>$title,'description'=>$desc,'badge_label'=>$badge,'badge_bg'=>$bg,'badge_color'=>$color,
                'icon_emoji'=>$emoji,'image'=>$img,'sort_order'=>$i,'is_popular'=>$i===4,'is_new'=>$i===5,
            ]);
        }
    }

    private function seedJenjang(): void
    {
        if (LandingJenjang::count() > 0) return;
        $items = [
            ['TK','Taman Kanak-Kanak','🌱','https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?w=200&q=80&auto=format&fit=crop'],
            ['SD','Sekolah Dasar','📚','https://images.unsplash.com/photo-1588072432836-e10032774350?w=200&q=80&auto=format&fit=crop'],
            ['SMP','Sekolah Menengah Pertama','🔬','https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=200&q=80&auto=format&fit=crop'],
            ['SMA / Umum','SMA & Karyawan','🎓','https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=200&q=80&auto=format&fit=crop'],
        ];
        foreach ($items as $i => [$name, $label, $emoji, $img]) {
            LandingJenjang::create(['name'=>$name,'label'=>$label,'emoji'=>$emoji,'image'=>$img,'sort_order'=>$i]);
        }
    }

    private function seedTrust(): void
    {
        if (LandingTrust::count() > 0) return;
        $items = [
            ['bi-patch-check-fill','500+ Tutor Bersertifikat'],
            ['bi-lightning-fill','Respon dalam 1 Jam'],
            ['bi-shield-fill-check','Aman & Terpercaya'],
            ['bi-award-fill','Garansi Hasil Belajar'],
        ];
        foreach ($items as $i => [$icon, $text]) {
            LandingTrust::create(['icon'=>$icon,'text'=>$text,'sort_order'=>$i]);
        }
    }

    private function seedHighlights(): void
    {
        if (LandingHighlight::count() > 0) return;
        $items = [
            ['https://images.unsplash.com/photo-1607990281513-2c110a25bd8c?w=200&q=80','Tutor Profesional','Pengajar ahli bersertifikat resmi dengan pengalaman bertahun-tahun dan rekam jejak hasil nyata.'],
            ['https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=200&q=80','Bisa Home Visit','Tutor kami siap datang ke rumah Anda kapan saja. Jadwal fleksibel, nyaman, dan tanpa perlu repot.'],
            ['https://images.unsplash.com/photo-1581092334651-ddf26d9a09d0?w=200&q=80','Metode Modern','Sistem belajar interaktif yang disesuaikan dengan gaya belajar masing-masing siswa. Belajar itu menyenangkan!'],
            ['https://images.unsplash.com/photo-1551836022-d5d88e9218df?w=200&q=80','Hasil Terukur','Evaluasi rutin, progress terpantau, laporan bulanan. Nilai meningkat signifikan — dijamin atau kami ulang!'],
            ['https://images.unsplash.com/photo-1560250097-0b93528c311a?w=200&q=80','Support Penuh','Bantuan belajar & konsultasi 24/7 via WhatsApp. Kami selalu ada untuk mendukung perjalanan belajar Anda.'],
        ];
        foreach ($items as $i => [$img, $title, $desc]) {
            LandingHighlight::create(['image'=>$img,'title'=>$title,'description'=>$desc,'sort_order'=>$i]);
        }
    }

    private function seedTestimonials(): void
    {
        if (LandingTestimonial::count() > 0) return;
        $items = [
            ['Aisyah Rahma','Siswa SMA · Matematika','Belajar di SCI sangat menyenangkan! Tutor menjelaskan dengan cara yang mudah dipahami dan nilai saya meningkat pesat. Sangat merekomendasikan untuk semua!','linear-gradient(135deg,#c84ddf,#68117e)'],
            ['Ricky Pratama','Mahasiswa · Persiapan SBMPTN','Program persiapan ujian di SCI sangat membantu. Akhirnya lolos ke kampus impian! Materinya lengkap banget dan tutornya super sabar dan profesional.','linear-gradient(135deg,#10b981,#059669)'],
            ['Dinda Lestari','Mahasiswi · Akuntansi','Kursus akuntansi di SCI sangat bermanfaat untuk tugas kuliah dan persiapan kerja. Tutornya sabar, materi lengkap, dan nilai kuliah saya jadi meningkat!','linear-gradient(135deg,#6366f1,#4338ca)'],
            ['Bunda Sari','Orang Tua Siswa · Jakarta','Anakku yang awalnya kesulitan di pelajaran IPA sekarang jadi juara kelas! Metode belajar di SCI sangat efektif dan tutornya sangat sabar dan perhatian.','linear-gradient(135deg,#f97316,#ea580c)'],
        ];
        foreach ($items as $i => [$name, $role, $text, $gradient]) {
            LandingTestimonial::create([
                'name'=>$name,'role'=>$role,'text'=>$text,'gradient'=>$gradient,
                'initial'=>strtoupper(substr($name,0,1)),'sort_order'=>$i,
            ]);
        }
    }

    private function seedGallery(): void
    {
        if (LandingGallery::count() > 0) return;
        $items = [
            ['https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=700&q=80','Kelas Belajar'],
            ['https://images.unsplash.com/photo-1509062522246-3755977927d7?w=700&q=80','Diskusi Kelompok'],
            ['https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=700&q=80','Les Online'],
            ['https://images.unsplash.com/photo-1544717305-2782549b5136?w=700&q=80','Tutor Mengajar'],
            ['https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?w=700&q=80','Les Privat'],
            ['https://images.unsplash.com/photo-1509869175650-a1d97972541a?w=700&q=80','Kursus Komputer'],
            ['https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=700&q=80','Persiapan Ujian'],
            ['https://images.unsplash.com/photo-1543269865-cbf427effbad?w=700&q=80','Belajar Bersama'],
        ];
        foreach ($items as $i => [$url, $alt]) {
            LandingGallery::create(['image'=>$url,'alt'=>$alt,'sort_order'=>$i]);
        }
    }

    private function seedFaqs(): void
    {
        if (LandingFaq::count() > 0) return;
        $items = [
            ['Bagaimana cara mendaftar di SCI?','Anda bisa mendaftar melalui website ini, menghubungi kami via WhatsApp, atau langsung datang ke cabang SCI terdekat. Tim kami akan membantu proses pendaftaran dengan mudah dan cepat.'],
            ['Apakah bisa datang ke rumah?','Ya! Kami menyediakan layanan home visit di mana tutor kami akan datang langsung ke rumah Anda. Jadwal fleksibel dan nyaman tanpa perlu keluar rumah.'],
            ['Jenjang apa saja yang dilayani?','SCI melayani semua jenjang mulai dari TK, SD, SMP, SMA, hingga mahasiswa dan umum. Tersedia juga kursus bahasa, komputer, dan akuntansi untuk semua usia.'],
            ['Berapa biaya les privat di SCI?','Biaya bervariasi tergantung jenjang, mata pelajaran, dan metode belajar (online/offline/home visit). Hubungi kami untuk mendapatkan penawaran terbaik sesuai kebutuhan Anda.'],
            ['Apakah ada garansi hasil belajar?','Ya! SCI memberikan garansi hasil belajar. Jika nilai tidak meningkat sesuai target yang disepakati, kami siap memberikan sesi tambahan tanpa biaya ekstra.'],
            ['Bagaimana sistem pembayaran di SCI?','Pembayaran bisa dilakukan bulanan atau per paket belajar. Tersedia berbagai metode pembayaran termasuk transfer bank, dompet digital, dan tunai di cabang.'],
        ];
        foreach ($items as $i => [$q, $a]) {
            LandingFaq::create(['question'=>$q,'answer'=>$a,'sort_order'=>$i]);
        }
    }

    private function seedWa(): void
    {
        if (LandingWaNumber::count() > 0) return;
        LandingWaNumber::create([
            'label'=>'WhatsApp Pusat','number'=>'6285333399210','description'=>'Nomor utama kantor pusat',
            'is_primary'=>true,'is_active'=>true,'sort_order'=>0,
        ]);
    }
}
