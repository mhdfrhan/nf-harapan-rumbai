# Security

## Tujuan dan Pendekatan

Keamanan dirancang berlapis. Framework membantu menyediakan kontrol, tetapi keamanan tetap bergantung pada konfigurasi, kebijakan akses, aturan domain, dependency, deployment, dan kebiasaan operasional. Baseline risiko mengacu pada OWASP Top 10:2025 serta dokumentasi resmi Laravel dan Livewire.

## Aset yang Dilindungi

- Kredensial dan session pengguna.
- Harga beli dan harga jual.
- Data stok dan histori movement.
- Nilai penjualan serta pembayaran.
- Kewajiban pemasok.
- Bukti foto dan PDF.
- Audit log, ekspor, backup, dan secret deployment.
- Ketersediaan sistem saat toko beroperasi.

## Aktor Ancaman

- Pengguna tanpa autentikasi dari internet atau jaringan lokal.
- Karyawan yang mencoba mengakses data di luar tugas.
- Akun sah yang perangkat atau kata sandinya diambil alih.
- File berbahaya yang diunggah sebagai bukti.
- Kesalahan manusia, misalnya klik ganda atau salah penyesuaian.
- Dependency, server, atau backup yang salah konfigurasi.
- Gangguan listrik, disk, jaringan, dan kehilangan perangkat.

## Trust Boundaries

1. Browser pengguna ke aplikasi Laravel.
2. Aplikasi ke MySQL.
3. Aplikasi ke private file storage.
4. Aplikasi ke queue worker dan scheduler.
5. Operator deployment ke secret, backup, dan production data.
6. Integrasi eksternal masa depan, bila ada.

Semua data dari browser dianggap tidak tepercaya, termasuk field tersembunyi, total, role, product ID, harga, dan MIME file.

## SEC-01 Authentication

- Pendaftaran publik dinonaktifkan.
- Akun dibuat dan dinonaktifkan oleh pemilik.
- Kata sandi di-hash memakai Laravel Hash facade dengan Argon2id atau bcrypt sesuai kemampuan server.
- Parameter hash mengikuti rekomendasi versi Laravel dan diuji terhadap kapasitas server.
- Session ID diregenerasi setelah login dan diinvalidasi saat logout.
- Login memiliki rate limiting berbasis identitas dan jaringan dengan pesan generik.
- `remember me` dinonaktifkan pada perangkat bersama kecuali kebijakan perangkat disetujui.
- Reset kata sandi awal dapat dilakukan pemilik dengan password sementara yang wajib diganti.
- MFA disiapkan sebagai fitur lanjutan untuk pemilik jika deployment dapat diakses internet.

## SEC-02 Authorization

- Policy Laravel menjadi sumber keputusan object-level.
- Permission server diperiksa di action kritis serta route atau component boundary.
- Livewire public property tidak boleh dianggap aman hanya karena field tidak terlihat.
- Model binding tidak memberikan izin otomatis.
- Download dokumen, ekspor, audit, report, dan print receipt memiliki policy tersendiri.
- Kasir hanya melihat transaksi sesuai kebijakan, bukan seluruh data sensitif pemilik.
- Sistem mencegah privilege lockout terhadap owner terakhir.

## SEC-03 CSRF, Origin, dan Session

- Semua mutation menggunakan web middleware, CSRF protection, dan origin verification Laravel yang aktif.
- Cookie production memakai `Secure`, `HttpOnly`, dan `SameSite` yang sesuai.
- HTTPS wajib bila diakses melalui jaringan selain loopback, termasuk LAN bila memungkinkan.
- Session disimpan pada driver yang sesuai topology dan memiliki idle timeout.
- Halaman 419 tidak mengulang request POST otomatis.
- CORS tidak dibuka luas karena tidak ada public API.

## SEC-04 Input Validation dan Output Encoding

- Form Request, Livewire validation, custom rule, dan domain rule berfungsi berlapis.
- Validasi mencakup tipe, panjang, range, relasi, state record, dan izin.
- Eloquent mass assignment memakai allowlist atau assignment eksplisit.
- Blade escaping digunakan untuk output teks. Raw HTML dilarang kecuali sumber dan sanitasi jelas.
- Query dibangun dengan Eloquent atau query builder parameterized. Raw expression ditinjau khusus.
- Identifier publik tidak menggantikan authorization.
- Nilai uang dan quantity dinormalisasi secara deterministik di server.

## SEC-05 Livewire

- Parameter action, model ID, dan property yang dapat diubah client selalu diotorisasi dan divalidasi.
- Locked property hanya pertahanan tambahan, bukan pengganti policy.
- Sensitive model data tidak ditempatkan pada public property jika tidak perlu.
- Component menggunakan pagination server-side dan tidak memuat dataset penuh ke browser.
- Computed values seperti total dan saldo dihitung ulang server-side.

## SEC-06 File Upload

- Allowlist JPEG, PNG, dan PDF untuk MVP.
- Tipe konten dideteksi dari file; ekstensi dan header client tidak dipercaya.
- Ukuran, jumlah, dimensi gambar, dan page count PDF dibatasi bila parser aman tersedia.
- Nama storage acak, path tidak berasal dari input pengguna, dan storage tidak public.
- Download melalui authorization dan header `X-Content-Type-Options: nosniff`.
- File HTML, SVG, script, executable, office macro, dan archive ditolak pada MVP.
- Preview PDF menggunakan download atau viewer yang tidak memberi file hak eksekusi origin aplikasi.
- Malware scan digunakan bila infrastruktur mendukung; status scan memblokir preview sebelum aman.
- EXIF sensitif dapat dihapus dari derivative image, sementara checksum asli dipertahankan untuk audit.

## SEC-07 Integrity Transaksi

- Posting memakai database transaction.
- Idempotency mencegah double submit.
- Row lock dan revalidation mencegah overselling akibat konkurensi.
- Harga, total, conversion, stock, dan payable balance tidak dipercaya dari client.
- Posted transactions immutable; koreksi menggunakan record pembalik.
- Audit event mencatat aktor, waktu, alasan, request ID, dan nilai penting.
- Foreign key dan check constraint mencegah orphan serta nilai tidak logis.

## SEC-08 Secrets dan Configuration

- `.env` tidak di-commit dan tidak dapat dilayani web server.
- `APP_DEBUG=false` pada production.
- `APP_ENV=production` dan `APP_URL` benar.
- `APP_KEY` dibuat kuat, disimpan aman, dan tidak dirotasi tanpa rencana re-encryption atau session impact.
- Credential database memiliki least privilege.
- Secret backup, mail, object storage, dan monitoring tidak disimpan pada tabel settings.
- Configuration di-cache saat deployment setelah validasi environment.

## SEC-09 Security Headers dan Transport

Baseline production:

- HSTS setelah HTTPS stabil.
- Content Security Policy yang dimulai dalam report-only kemudian ditegakkan.
- `X-Content-Type-Options: nosniff`.
- `Referrer-Policy: strict-origin-when-cross-origin` atau lebih ketat sesuai kebutuhan.
- `Permissions-Policy` menonaktifkan fitur browser yang tidak dipakai.
- Frame protection melalui CSP `frame-ancestors`.
- Cache-Control private atau no-store pada halaman berisi data sensitif.

CSP disesuaikan dengan Vite production assets dan tidak membuka `unsafe-eval` tanpa alasan terukur.

## SEC-10 Logging dan Privacy

- Log tidak berisi password, token, session, secret, full file, atau request body mentah.
- Request ID digunakan untuk korelasi.
- Percobaan login, authorization denial sensitif, export, download bukti, void, adjustment, dan role change dicatat sesuai kebutuhan.
- Akses log production terbatas.
- IP address dipertahankan hanya jika diperlukan untuk keamanan dan sesuai kebijakan retensi.
- Error kepada pengguna tidak menampilkan SQL, path server, stack trace, atau dependency version.

## SEC-11 Dependency dan Supply Chain

- `composer.lock` dan package manager lockfile di-commit saat implementasi.
- `composer audit` dan audit npm dijalankan di CI.
- Dependency baru membutuhkan alasan, lisensi, status pemeliharaan, dan pembatasan versi.
- Composer script dari sumber tidak tepercaya tidak dijalankan sembarangan.
- Build artifact dibuat melalui pipeline terkontrol.
- Update keamanan diprioritaskan dan diuji sebelum deployment.
- Versi Laravel dan PHP dipantau terhadap security support window.

## SEC-12 Availability dan Recovery

- Backup database dan file private terenkripsi saat transit dan, bila memungkinkan, saat tersimpan.
- Salinan backup tidak hanya berada pada server yang sama.
- Restore test dilakukan berkala.
- Disk usage, failed job, scheduler, error rate, dan backup age dimonitor.
- Queue action kritis tidak boleh menjadi satu-satunya tempat transaksi bisnis disimpan.
- Prosedur offline sementara dan pencatatan manual darurat didokumentasikan untuk gangguan operasional.

## Pemetaan OWASP Top 10:2025

| Risiko OWASP | Kontrol proyek utama |
|---|---|
| A01 Broken Access Control | Policy, permission matrix, object-level authorization, private downloads, test forbidden path. |
| A02 Security Misconfiguration | Hardened production config, debug off, headers, least privilege, environment checklist. |
| A03 Software Supply Chain Failures | Lockfile, dependency audit, package review, controlled build dan patching. |
| A04 Cryptographic Failures | Laravel Hash, TLS, secure cookies, managed secret, encrypted backup. |
| A05 Injection | Parameterized query, validation, escaped output, CSV formula defense. |
| A06 Insecure Design | Threat model, domain state machine, idempotency, transaction, reversal, review gate. |
| A07 Authentication Failures | Rate limit, secure session, password policy, owner MFA roadmap, generic errors. |
| A08 Software or Data Integrity Failures | Checksums, immutable ledger, controlled deployment, audit trail, signed downloads. |
| A09 Security Logging and Alerting Failures | Structured log, audit events, monitoring, request ID, tested alert path. |
| A10 Mishandling of Exceptional Conditions | Fail-safe transaction, no partial write, explicit timeout state, safe error page, recovery tests. |

## Security Verification Gate

Sebelum production:

1. Threat model ditinjau ulang berdasarkan topology deployment final.
2. Semua route dan Livewire action memiliki auth serta policy test.
3. Upload diuji dengan file salah ekstensi, MIME palsu, oversized, polyglot, dan path traversal name.
4. Double submit, replay, concurrent sale, dan deadlock recovery diuji.
5. Dependency audit bersih atau exception risikonya disetujui.
6. TLS, cookie, session, CSP, dan headers diverifikasi pada environment production.
7. Backup berhasil dibuat dan dipulihkan pada environment terpisah.
8. Secret dan log diperiksa untuk kebocoran.
9. Akun default, sample credential, dan debug tool dihapus.
10. Audit event kritis diverifikasi end-to-end.

## Sumber Resmi

- [OWASP Top 10:2025](https://owasp.org/Top10/2025/0x00_2025-Introduction/)
- [Laravel CSRF Protection](https://laravel.com/docs/13.x/csrf)
- [Laravel Hashing](https://laravel.com/docs/13.x/hashing)
- [Laravel Validation](https://laravel.com/docs/13.x/validation)
- [Laravel Rate Limiting](https://laravel.com/docs/13.x/rate-limiting)
- [Livewire 4 Security](https://livewire.laravel.com/docs/4.x/security)

