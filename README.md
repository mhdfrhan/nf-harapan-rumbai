# Sistem NF Harapan Rumbai

Aplikasi internal Toko NF Harapan Rumbai untuk katalog, POS, persediaan berbasis ledger, penerimaan, pemasok, kewajiban, bukti privat, laporan, pengguna berbasis peran, dan audit aktivitas.

## Stack

- PHP 8.3+, Laravel 13, Livewire 4, Blade, Flux UI, Tailwind CSS 4, dan Vite 8.
- MySQL 8 pada production; SQLite tersedia untuk pengembangan dan automated test.
- Pest, PHPStan/Larastan level 7, dan Laravel Pint sebagai quality gate.

## Menjalankan Secara Lokal

```powershell
composer install
npm ci
php artisan key:generate
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

Konfigurasi lokal saat ini memakai `database/database.sqlite`. Kredensial seed lokal adalah username `owner` dan kata sandi `password`. Jangan gunakan kata sandi contoh ini pada staging atau production.

## Quality Gate

```powershell
composer test
npm run build
composer audit --locked --no-interaction
npm audit --audit-level=high
php artisan store:check
php artisan store:reconcile --fail-on-difference
```

## Production

Gunakan [DEPLOYMENT.md](DEPLOYMENT.md) dan `.env.production.example`. Production wajib memakai MySQL 8, HTTPS, kredensial database khusus aplikasi, `APP_DEBUG=false`, cookie aman, scheduler, backup database serta `storage/app/private`, dan uji restore sebelum go-live.

Dokumen perencanaan dan aturan domain berada di [`../docs`](../docs/README.md). Traceability hasil implementasi dan gate sebelum go-live berada di [`docs/implementation-status.md`](docs/implementation-status.md).
