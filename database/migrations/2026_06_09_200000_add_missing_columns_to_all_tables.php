<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. packages
        Schema::table('packages', function (Blueprint $table) {
            $table->foreignId('cabang_id')->nullable()->constrained('branches')->nullOnDelete()->after('id');
            $table->string('nama', 150)->after('cabang_id');
            $table->text('deskripsi')->nullable()->after('nama');
            $table->decimal('harga', 12, 2)->default(0)->after('deskripsi');
            $table->unsignedInteger('durasi_bulan')->default(1)->after('harga');
            $table->unsignedInteger('jumlah_pertemuan')->default(1)->after('durasi_bulan');
            $table->string('jenis', 50)->after('jumlah_pertemuan');
            $table->json('fitur')->nullable()->after('jenis');
            $table->boolean('is_unggulan')->default(false)->after('fitur');
            $table->string('status', 20)->default('aktif')->after('is_unggulan');
            $table->softDeletes();
        });

        // 2. salaries
        Schema::table('salaries', function (Blueprint $table) {
            $table->foreignId('guru_id')->constrained('teachers')->cascadeOnDelete()->after('id');
            $table->foreignId('cabang_id')->nullable()->constrained('branches')->nullOnDelete()->after('guru_id');
            $table->string('periode', 20)->after('cabang_id');
            $table->decimal('gaji_pokok', 12, 2)->default(0)->after('periode');
            $table->decimal('jam_mengajar', 6, 1)->nullable()->after('gaji_pokok');
            $table->decimal('tarif_per_jam', 12, 2)->nullable()->after('jam_mengajar');
            $table->decimal('total_gaji_mengajar', 12, 2)->default(0)->after('tarif_per_jam');
            $table->decimal('bonus', 12, 2)->default(0)->after('total_gaji_mengajar');
            $table->decimal('potongan', 12, 2)->default(0)->after('bonus');
            $table->decimal('total_gaji', 12, 2)->default(0)->after('potongan');
            $table->string('metode_pembayaran', 50)->nullable()->after('total_gaji');
            $table->string('nama_bank', 50)->nullable()->after('metode_pembayaran');
            $table->string('nomor_rekening', 50)->nullable()->after('nama_bank');
            $table->date('tanggal_pembayaran')->nullable()->after('nomor_rekening');
            $table->string('status', 20)->default('pending')->after('tanggal_pembayaran');
            $table->text('catatan')->nullable()->after('status');
            $table->foreignId('dibayar_oleh')->nullable()->constrained('users')->nullOnDelete()->after('catatan');
            $table->softDeletes();
        });

        // 3. tryouts
        Schema::table('tryouts', function (Blueprint $table) {
            $table->foreignId('cabang_id')->nullable()->constrained('branches')->nullOnDelete()->after('id');
            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->nullOnDelete()->after('cabang_id');
            $table->string('judul', 200)->after('dibuat_oleh');
            $table->text('deskripsi')->nullable()->after('judul');
            $table->string('kategori', 50)->after('deskripsi');
            $table->unsignedInteger('durasi_menit')->default(60)->after('kategori');
            $table->unsignedInteger('total_soal')->default(0)->after('durasi_menit');
            $table->decimal('nilai_kelulusan', 5, 2)->nullable()->after('total_soal');
            $table->dateTime('waktu_mulai')->nullable()->after('nilai_kelulusan');
            $table->dateTime('waktu_selesai')->nullable()->after('waktu_mulai');
            $table->boolean('is_random')->default(false)->after('waktu_selesai');
            $table->boolean('tampilkan_hasil_langsung')->default(true)->after('is_random');
            $table->boolean('tampilkan_kunci_jawaban')->default(false)->after('tampilkan_hasil_langsung');
            $table->unsignedInteger('maksimal_percobaan')->nullable()->after('tampilkan_kunci_jawaban');
            $table->string('status', 20)->default('draft')->after('maksimal_percobaan');
            $table->softDeletes();
        });

        // 4. tryout_attempts
        Schema::table('tryout_attempts', function (Blueprint $table) {
            $table->foreignId('tryout_id')->constrained('tryouts')->cascadeOnDelete()->after('id');
            $table->foreignId('siswa_id')->constrained('students')->cascadeOnDelete()->after('tryout_id');
            $table->dateTime('waktu_mulai')->nullable()->after('siswa_id');
            $table->dateTime('waktu_selesai')->nullable()->after('waktu_mulai');
            $table->decimal('nilai', 5, 2)->nullable()->after('waktu_selesai');
            $table->unsignedInteger('jawaban_benar')->default(0)->after('nilai');
            $table->unsignedInteger('jawaban_salah')->default(0)->after('jawaban_benar');
            $table->unsignedInteger('tidak_dijawab')->default(0)->after('jawaban_salah');
            $table->unsignedInteger('percobaan_ke')->default(1)->after('tidak_dijawab');
            $table->string('status', 20)->default('berlangsung')->after('percobaan_ke');
            $table->json('jawaban')->nullable()->after('status');
        });

        // 5. modules
        Schema::table('modules', function (Blueprint $table) {
            $table->foreignId('mata_pelajaran_id')->nullable()->constrained('courses')->nullOnDelete()->after('id');
            $table->foreignId('diupload_oleh')->nullable()->constrained('users')->nullOnDelete()->after('mata_pelajaran_id');
            $table->string('judul', 200)->after('diupload_oleh');
            $table->text('deskripsi')->nullable()->after('judul');
            $table->unsignedInteger('urutan')->default(0)->after('deskripsi');
            $table->string('jenis', 20)->after('urutan');
            $table->string('file_path')->nullable()->after('jenis');
            $table->string('file_url')->nullable()->after('file_path');
            $table->unsignedBigInteger('ukuran_file')->nullable()->after('file_url');
            $table->boolean('is_gratis')->default(false)->after('ukuran_file');
            $table->string('status', 20)->default('draft')->after('is_gratis');
            $table->unsignedInteger('jumlah_download')->default(0)->after('status');
            $table->softDeletes();
        });

        // 6. absensi_siswas
        Schema::table('absensi_siswas', function (Blueprint $table) {
            $table->foreignId('jadwal_id')->constrained('schedules')->cascadeOnDelete()->after('id');
            $table->foreignId('siswa_id')->constrained('students')->cascadeOnDelete()->after('jadwal_id');
            $table->string('status', 20)->default('hadir')->after('siswa_id');
            $table->text('catatan')->nullable()->after('status');
        });

        // 7. absensi_gurus
        Schema::table('absensi_gurus', function (Blueprint $table) {
            $table->foreignId('jadwal_id')->constrained('schedules')->cascadeOnDelete()->after('id');
            $table->foreignId('guru_id')->constrained('teachers')->cascadeOnDelete()->after('jadwal_id');
            $table->string('status', 20)->default('hadir')->after('guru_id');
            $table->text('catatan')->nullable()->after('status');
        });

        // 8. questions
        Schema::table('questions', function (Blueprint $table) {
            $table->foreignId('tryout_id')->constrained('tryouts')->cascadeOnDelete()->after('id');
            $table->text('teks_pertanyaan')->after('tryout_id');
            $table->string('gambar_pertanyaan')->nullable()->after('teks_pertanyaan');
            $table->string('jenis', 30)->default('pilihan_ganda')->after('gambar_pertanyaan');
            $table->json('pilihan_jawaban')->nullable()->after('jenis');
            $table->text('penjelasan')->nullable()->after('pilihan_jawaban');
            $table->decimal('poin', 5, 2)->default(1)->after('penjelasan');
            $table->unsignedInteger('urutan')->default(1)->after('poin');
            $table->string('tingkat_kesulitan', 20)->default('sedang')->after('urutan');
        });

        // 9. grades
        Schema::table('grades', function (Blueprint $table) {
            $table->foreignId('siswa_id')->constrained('students')->cascadeOnDelete()->after('id');
            $table->foreignId('mata_pelajaran_id')->nullable()->constrained('courses')->nullOnDelete()->after('siswa_id');
            $table->foreignId('guru_id')->nullable()->constrained('teachers')->nullOnDelete()->after('mata_pelajaran_id');
            $table->foreignId('semester_id')->nullable()->constrained('semesters')->nullOnDelete()->after('guru_id');
            $table->string('jenis_penilaian', 50)->after('semester_id');
            $table->string('nama_penilaian', 100)->nullable()->after('jenis_penilaian');
            $table->decimal('nilai', 5, 2)->after('nama_penilaian');
            $table->decimal('nilai_maksimal', 5, 2)->default(100)->after('nilai');
            $table->decimal('bobot', 5, 2)->default(1)->after('nilai_maksimal');
            $table->date('tanggal')->nullable()->after('bobot');
            $table->text('catatan')->nullable()->after('tanggal');
        });
    }

    public function down()
    {
        // Reverse order to respect FK constraints
        Schema::table('grades', fn($t) => $t->dropColumn(['siswa_id','mata_pelajaran_id','guru_id','semester_id','jenis_penilaian','nama_penilaian','nilai','nilai_maksimal','bobot','tanggal','catatan']));
        Schema::table('questions', fn($t) => $t->dropColumn(['tryout_id','teks_pertanyaan','gambar_pertanyaan','jenis','pilihan_jawaban','penjelasan','poin','urutan','tingkat_kesulitan']));
        Schema::table('absensi_gurus', fn($t) => $t->dropColumn(['jadwal_id','guru_id','status','catatan']));
        Schema::table('absensi_siswas', fn($t) => $t->dropColumn(['jadwal_id','siswa_id','status','catatan']));
        Schema::table('modules', fn($t) => $t->dropColumn(['mata_pelajaran_id','diupload_oleh','judul','deskripsi','urutan','jenis','file_path','file_url','ukuran_file','is_gratis','status','jumlah_download','deleted_at']));
        Schema::table('tryout_attempts', fn($t) => $t->dropColumn(['tryout_id','siswa_id','waktu_mulai','waktu_selesai','nilai','jawaban_benar','jawaban_salah','tidak_dijawab','percobaan_ke','status','jawaban']));
        Schema::table('tryouts', fn($t) => $t->dropColumn(['cabang_id','dibuat_oleh','judul','deskripsi','kategori','durasi_menit','total_soal','nilai_kelulusan','waktu_mulai','waktu_selesai','is_random','tampilkan_hasil_langsung','tampilkan_kunci_jawaban','maksimal_percobaan','status','deleted_at']));
        Schema::table('salaries', fn($t) => $t->dropColumn(['guru_id','cabang_id','periode','gaji_pokok','jam_mengajar','tarif_per_jam','total_gaji_mengajar','bonus','potongan','total_gaji','metode_pembayaran','nama_bank','nomor_rekening','tanggal_pembayaran','status','catatan','dibayar_oleh','deleted_at']));
        Schema::table('packages', fn($t) => $t->dropColumn(['cabang_id','nama','deskripsi','harga','durasi_bulan','jumlah_pertemuan','jenis','fitur','is_unggulan','status','deleted_at']));
    }
};
