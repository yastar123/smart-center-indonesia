# Panduan Deploy Laravel (Smart Center Indonesia) ke cPanel

Panduan singkat langkah demi langkah untuk menyebarkan aplikasi Laravel ini ke hosting berbasis cPanel. Asumsi: Anda punya akses cPanel (File Manager, Databases, Cron, Terminal/SSH optional), dan server menjalankan PHP >= 8.0 dengan ekstensi standar Laravel.

## 1. Persiapan Prasyarat

- PHP 8.x (sesuaikan dengan `.platform`/composer.json), ekstensi: OpenSSL, PDO, Mbstring, Tokenizer, JSON, BCMath, Fileinfo, Ctype, XML.
- MySQL/MariaDB database dan kredensial (nama DB, user, password, host).
- Composer (lebih mudah via SSH). Jika tidak ada SSH/composer di server, Anda bisa meng-upload folder `vendor/` yang sudah dibuat secara lokal.
- Pastikan `public` sebagai document root (lihat bagian Document Root).

## 2. Upload Kode

Opsi A — Git (direkomendasikan jika tersedia):

1. Di cPanel buka Terminal/SSH, clone repo ke folder `~/project-name` atau gunakan `git pull`.

Opsi B — File Manager / FTP:

1. Zip seluruh repo dari lokal, upload ke `home/username/` menggunakan File Manager atau FTP.
2. Extract di folder misal `~/smart-center`.

Catatan struktur: letakkan seluruh project di luar `public_html`/`httpdocs` (mis: `/home/username/smart-center`). Folder `public` harus diakses oleh web server.

## 3. Set Document Root

Pilihan A (jika cPanel memungkinkan):

- Di cPanel → Domains atau Addon Domains → ubah Document Root domain/subdomain menjadi `/home/username/smart-center/public`.

Pilihan B (jika tidak bisa mengubah):

- Pindahkan isi folder `public` ke `public_html` (atau `httpdocs`), lalu edit `index.php` dan `server.php` path require:
    - Ubah `require __DIR__.'/../vendor/autoload.php'` menjadi `require __DIR__.'/../smart-center/vendor/autoload.php'` (sesuaikan path relatif).
    - Ubah `$app = require_once __DIR__.'/../bootstrap/app.php';` menjadi path yang benar.
- Jangan pindahkan folder `public` secara keseluruhan; copy isinya saja dan pastikan tidak menimpa file lain tanpa backup.

## 4. Konfigurasi `.env`

1. Di server salin `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

2. Edit `.env` dan isi:

- `APP_URL` → `https://your-domain.com`
- `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `DB_HOST`
- `MAIL_*` jika ingin email bekerja

3. Jika tidak ada SSH untuk menjalankan artisan, Anda bisa generate APP_KEY secara lokal (`php artisan key:generate --show`) lalu paste ke `.env`:

```bash
php artisan key:generate --show
# copy output dan isi APP_KEY di .env
```

## 5. Install Dependensi (Composer)

Jika punya akses SSH + Composer di server (direkomendasikan):

```bash
cd /home/username/smart-center
composer install --no-dev --optimize-autoloader
php -d memory_limit=512M artisan clear-compiled
```

Jika tidak bisa, jalankan `composer install` secara lokal lalu upload folder `vendor/` ke server.

## 6. Migrasi dan Seeder

Jalankan migrasi (SSH):

```bash
php artisan migrate --force
php artisan db:seed --force # opsional
```

Jika tidak punya SSH, Anda bisa menjalankan SQL dump via phpMyAdmin.

## 7. Storage Link & Permissions

- Buat symlink storage: `php artisan storage:link` (lebih mudah via SSH). Jika tidak, buat folder `storage/app/public` dan pastikan bisa diakses, atau upload file statis ke lokasi publik.

- Set izin folder agar webserver dapat menulis:

```bash
cd /home/username/smart-center
chmod -R 775 storage bootstrap/cache
# Jika perlu, adjust owner sesuai user cPanel
chown -R username:username storage bootstrap/cache
```

## 8. Cron Jobs (Scheduler) & Queue

- Scheduler: tambahkan cron di cPanel (Cron Jobs) untuk menjalankan setiap menit:

```
* * * * * cd /home/username/smart-center && php artisan schedule:run >> /dev/null 2>&1
```

- Queue workers: jika tidak ada Supervisor, opsi:
    - Jalankan `php artisan queue:work` via Terminal/SSH dan jalankan di background (nohup) jika server mengizinkan.
    - Atau buat cron tiap menit menjalankan `php artisan queue:work --once` untuk mengeksekusi job tertunda sekali per menit (kurang efisien):

```
* * * * * cd /home/username/smart-center && php artisan queue:work --once >> /dev/null 2>&1
```

Catatan: `queue:work --once` cocok untuk volume rendah.

## 9. SSL (HTTPS)

- Di cPanel → SSL/TLS → Gunakan AutoSSL atau LetsEncrypt (jika disediakan) untuk domain Anda.
- Pastikan `APP_URL` memakai `https://` setelah SSL aktif.

## 10. Optimisasi Produksi

Jalankan:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Jika tidak punya SSH, jangan jalankan perintah cache ini; Anda bisa menjalankannya secara lokal dan men-deploy hasilnya, tetapi beberapa cache memerlukan path absolut dari server.

## 11. Troubleshooting

- 500 Internal Server Error → cek `storage/logs/laravel.log` untuk pesan detail.
- Permission denied → pastikan `storage` dan `bootstrap/cache` dapat ditulis webserver.
- Composer memory exhausted → jalankan `php -d memory_limit=-1 composer install` atau buat composer di lokal.
- APP_KEY missing → generate lokal dan masukkan ke `.env`.

## 12. Ringkasan Cepat (Checklist)

- [ ] Upload project (di luar public_html)
- [ ] Set Document Root ke `/path/to/project/public` atau copy `public` ke `public_html` dan sesuaikan `index.php`
- [ ] Isi `.env` dan `APP_KEY`
- [ ] Composer install / upload `vendor`
- [ ] php artisan migrate --force
- [ ] php artisan storage:link
- [ ] Set permissions `storage` & `bootstrap/cache`
- [ ] Tambah Cron `schedule:run` dan queue worker sesuai kebutuhan
- [ ] Aktifkan SSL dan set `APP_URL`

---

Jika Anda ingin, saya bisa membuat versi singkat untuk diberikan ke tim hosting Anda (bahasa Inggris) atau menambahkan contoh penyesuaian `index.php` ketika memindahkan `public` ke `public_html`.
