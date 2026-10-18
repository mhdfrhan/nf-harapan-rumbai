# Deployment Runbook

Runbook ini menyiapkan aplikasi, tetapi pilihan akhir cloud VPS atau server lokal tetap harus diputuskan berdasarkan kestabilan internet, kebutuhan akses luar toko, UPS, printer, biaya, dan penanggung jawab teknis.

## Prasyarat

- Linux LTS, Nginx, PHP-FPM 8.3+ dengan `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `filter`, `hash`, `mbstring`, `openssl`, `pdo_mysql`, `session`, `tokenizer`, dan `xml`.
- MySQL 8 dengan database dan user khusus aplikasi. Jangan gunakan akun `root`.
- TLS aktif. Document root Nginx harus menunjuk hanya ke folder `public`.
- Cron/scheduler, ruang backup offsite, monitoring disk dan endpoint `/up` serta `/health/ready`.

## Release Baru

1. Jalankan quality gate dari README pada commit yang akan dirilis.
2. Bangun asset dengan `npm ci && npm run build`; deploy source tanpa `.env`, `node_modules`, data lokal, atau credential contoh.
3. Salin `.env.production.example` menjadi `.env` pada server dan isi seluruh secret. Isi `SEED_OWNER_PASSWORD` kuat hanya untuk seed awal, lalu hapus nilainya setelah akun dibuat.
4. Jalankan:

```bash
composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader
php artisan key:generate --force        # hanya instalasi pertama
php artisan store:check
php artisan migrate --force
php artisan db:seed --force             # hanya instalasi pertama
php artisan optimize
php artisan queue:restart
php artisan store:reconcile --fail-on-difference
```

5. Pastikan user web dapat menulis `storage` dan `bootstrap/cache`, tetapi tidak memiliki akses lebih luas dari direktori aplikasi.
6. Pasang cron: `* * * * * cd /var/www/nf-harapan-rumbai && php artisan schedule:run >> /dev/null 2>&1`.
7. Uji login, satu penjualan kecil, void oleh Owner, penerimaan, bukti privat, pembayaran, CSV, dan seluruh role pada staging sebelum production.

## Backup dan Restore Gate

- Backup malam mencakup dump MySQL konsisten, `storage/app/private`, `.env` terenkripsi secara terpisah, dan manifest checksum.
- Template aman tersedia di `deploy/backup.sh.example` dan `deploy/restore-drill.sh.example`. Kredensial MySQL disimpan pada file client terpisah dengan izin minimum; jangan ditulis di command line atau repository.
- Simpan salinan di lokasi berbeda dengan retensi 14 harian, 8 mingguan, dan 6 bulanan sesuai planning.
- Sebelum setiap release, ambil backup dan verifikasi dapat dibaca.
- Uji restore pada environment terpisah; jalankan `php artisan store:check` dan `php artisan store:reconcile --fail-on-difference` setelah restore.
- Jangan memakai `migrate:rollback` otomatis pada production. Pulihkan artifact lama hanya jika schema masih kompatibel; gunakan forward fix atau restore terkontrol untuk kerusakan data.

## Hardening

- Firewall membuka hanya SSH terbatas dan HTTPS. Aktifkan patch keamanan, fail2ban atau kontrol setara, dan TLS renewal otomatis.
- Set `APP_ENV=production`, `APP_DEBUG=false`, URL HTTPS, `SESSION_SECURE_COOKIE=true`, `SESSION_HTTP_ONLY=true`, dan `SESSION_ENCRYPT=true`.
- Batasi `upload_max_filesize` dan `post_max_size` sedikit di atas 8 MB; jangan melonggarkan MIME aplikasi.
- Pantau 5xx, login gagal, disk, umur backup, scheduler, dan hasil rekonsiliasi. Jangan tampilkan log kepada pengguna.
- Hapus credential contoh dan data sintetis. Seed production hanya membuat Owner awal serta satuan referensi.

## Rollback Aplikasi

1. Hentikan perubahan baru dan ambil snapshot.
2. Aktifkan maintenance mode bila diperlukan.
3. Kembalikan artifact release sebelumnya hanya jika migration kompatibel.
4. Jalankan `php artisan optimize:clear`, `php artisan optimize`, dan `php artisan queue:restart`.
5. Jalankan health check, smoke test, dan rekonsiliasi sebelum membuka traffic.
