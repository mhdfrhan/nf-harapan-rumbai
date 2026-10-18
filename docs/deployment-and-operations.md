# Deployment and Operations

## Keputusan yang Masih Terbuka

Topology final adalah blocker karena memengaruhi akses internet, biaya, backup, HTTPS, printer, dukungan, dan availability. Dua opsi yang realistis dipertimbangkan.

## Opsi A: Cloud VPS Terkelola Ringan

**Kelebihan:** Dapat diakses dari perangkat berbeda, backup offsite lebih mudah, pembaruan terpusat, pemilik dapat memantau di luar toko.

**Kekurangan:** Bergantung internet toko, membutuhkan hardening server dan biaya bulanan, attack surface publik lebih besar.

**Kontrol tambahan:** Firewall ketat, HTTPS publik, automated patching terencana, MFA pemilik, monitoring eksternal, dan backup lintas lokasi.

## Opsi B: Server Lokal atau Mini PC di Toko

**Kelebihan:** POS tetap dapat bekerja saat internet publik putus dan attack surface dapat dibatasi LAN.

**Kekurangan:** Risiko listrik, perangkat rusak atau hilang, backup offsite dan remote support lebih sulit, HTTPS lokal perlu dikelola.

**Kontrol tambahan:** UPS, enclosure aman, backup offsite saat internet tersedia, monitoring disk, TLS lokal, dan prosedur penggantian perangkat.

## Rekomendasi Bersyarat

Cloud VPS direkomendasikan bila internet toko stabil dan kebutuhan akses pemilik dari luar penting. Server lokal direkomendasikan bila kontinuitas POS saat internet putus lebih penting. Keputusan tidak boleh dibuat tanpa survei koneksi, perangkat, printer, anggaran, dan siapa yang memelihara server.

## Environment

| Environment | Data | Tujuan |
|---|---|---|
| Local | Factory atau data sintetis | Pengembangan. |
| Test CI | Data ephemeral | Automated tests. |
| Staging | Data sintetis atau sanitasi | UAT, migration dry run, release rehearsal. |
| Production | Data nyata | Operasional toko. |

Production tidak digunakan untuk development atau demo. Debug toolbar tidak terpasang atau nonaktif pada production.

## Production Runtime

- Linux LTS yang masih didukung.
- Nginx atau web server setara.
- PHP-FPM 8.3+ dengan extension yang dibutuhkan saja.
- MySQL 8.
- Supervisor atau systemd untuk queue worker.
- Cron setiap menit untuk Laravel scheduler.
- Node tidak diperlukan pada runtime bila asset dibangun di CI.
- Private storage dengan kapasitas dan backup terukur.
- TLS certificate otomatis dan health check.

Sizing awal ditentukan setelah jumlah produk, transaksi, file, user serentak, dan topology dikonfirmasi. Overprovision tanpa data tidak diperlukan, tetapi disk untuk bukti dan backup harus memiliki headroom.

## Configuration Checklist

- `APP_ENV=production`.
- `APP_DEBUG=false`.
- `APP_URL` HTTPS benar.
- Locale Indonesia dan timezone aplikasi `Asia/Jakarta`; database timestamp strategy tetap UTC.
- Secure session cookie.
- Queue dan cache driver sesuai topology.
- Trusted proxy hanya dikonfigurasi bila benar-benar berada di belakang proxy.
- Mail atau notification credential terpisah bila digunakan.
- Logging channel dan retention ditetapkan.
- Filesystem permission least privilege.
- PHP upload limit selaras dengan validasi aplikasi, tidak jauh lebih longgar.

## Deployment Pipeline

1. Developer membuat perubahan dan test lokal.
2. CI menjalankan lint, static analysis, automated tests, asset build, dan dependency audit.
3. Artifact release diberi versi dan checksum.
4. Staging deploy otomatis atau semi otomatis.
5. Migration rehearsal dan smoke test.
6. Pemilik menyetujui perubahan yang memengaruhi alur bisnis.
7. Pre-deployment backup production dan health check.
8. Aktifkan maintenance mode hanya jika migration tidak backward compatible.
9. Deploy artifact, install production dependency dari lockfile, cache config, route, serta view.
10. Jalankan migration dengan kontrol timeout dan backup.
11. Restart queue workers secara graceful.
12. Jalankan smoke test dan monitoring.
13. Keluar maintenance mode.
14. Catat release, actor, waktu, schema version, dan hasil.

## Zero or Low Downtime Discipline

- Perubahan schema memakai expand, migrate, contract.
- Kode lama dan baru dapat berjalan bersamaan selama deployment jika memungkinkan.
- Migration tidak melakukan backfill besar dalam satu lock panjang.
- Queue payload memiliki compatibility window saat worker berganti versi.
- Destructive schema cleanup dilakukan pada release kemudian setelah penggunaan lama hilang.

Untuk skala satu toko, maintenance terjadwal singkat masih dapat diterima jika disepakati dan dilakukan di luar jam operasional.

## Rollback

Rollback aplikasi dilakukan ke artifact sebelumnya jika schema masih kompatibel. Rollback database bukan default karena dapat menghilangkan data baru. Jika migration merusak data, gunakan restore terkontrol atau forward fix berdasarkan incident plan. Jangan memakai `migrate:rollback` secara otomatis pada production tanpa analisis dampak.

## Scheduler

| Job | Jadwal rancangan | Hasil |
|---|---|---|
| Reminder kewajiban | Setiap pagi | Daftar due dan overdue. |
| Reconciliation | Setelah tutup toko | Laporan stok dan payable. |
| Backup | Malam | Manifest dan status. |
| Draft cleanup | Harian | File draft kedaluwarsa saja. |
| Export cleanup | Harian | File ekspor lewat retensi. |
| Log rotation | Harian | Log sesuai retensi. |
| Health summary | Harian | Backup, queue, disk, scheduler. |

Timezone scheduler ditetapkan secara eksplisit dan diuji pada waktu Asia/Jakarta.

## Queue Operations

- Pisahkan queue default, exports, dan documents bila volume membutuhkan.
- Job memiliki timeout, retry, backoff, dan unique key.
- Failed job memicu alert setelah ambang.
- Retry manual dilakukan setelah penyebab diperiksa.
- Queue worker direstart saat deploy.
- Job posting transaksi inti tidak bergantung pada queue untuk konsistensi.

## Monitoring dan Alerts

### Critical

- Aplikasi tidak dapat diakses.
- Database tidak dapat dihubungi.
- Disk hampir penuh.
- Backup gagal berulang atau terlalu lama.
- Reconciliation fail.
- Lonjakan error posting transaksi.

### Warning

- Queue backlog.
- Failed jobs.
- Certificate mendekati kedaluwarsa.
- Waktu respons melewati budget.
- File storage mendekati threshold.

Alert harus memiliki penerima, jam respons, serta tindakan. Dashboard monitoring tanpa prosedur respons tidak cukup.

## Runbook Operasional

### Aplikasi tidak dapat dibuka

1. Periksa listrik, jaringan, DNS, TLS, dan health endpoint sesuai topology.
2. Periksa web server, PHP-FPM, database, disk, dan log dengan request ID.
3. Jangan mengubah data sebelum membuat snapshot jika kondisi tidak jelas.
4. Aktifkan prosedur manual darurat dan catat transaksi berurutan.
5. Setelah pulih, input transaksi darurat dan rekonsiliasi.

### Penjualan timeout

1. Jangan langsung submit dengan key baru.
2. Periksa status idempotency atau nomor transaksi.
3. Jika sudah posted, cetak ulang.
4. Jika tidak ada, lanjutkan retry aman.

### Stok tidak cocok

1. Bekukan adjustment spontan untuk produk terkait.
2. Bandingkan balance dengan movement.
3. Cari transaksi pending, void, receipt, dan adjustment.
4. Lakukan hitung fisik.
5. Buat adjustment beralasan hanya setelah sumber selisih didokumentasikan.

### Backup gagal

1. Periksa kapasitas, credential, jaringan, dan log.
2. Jalankan ulang setelah penyebab diperbaiki.
3. Verifikasi manifest dan restore sampel.
4. Eskalasi jika RPO terancam.

## Support dan Ownership

Sebelum go-live harus ditentukan:

- Siapa owner bisnis sistem.
- Siapa admin akun.
- Siapa operator teknis.
- Kanal pelaporan masalah.
- Jam support.
- Klasifikasi severity dan target respons.
- Siapa yang dapat menyetujui restore, role change, dan koreksi data.

## Dokumentasi Serah Terima

- Arsitektur final dan diagram deployment.
- Environment inventory tanpa secret.
- SOP akun, transaksi, bukti, backup, dan restore.
- Runbook incident.
- Panduan pengguna per peran.
- Data dictionary dan migration report.
- Test report dan known limitations.
- Release process dan dependency update schedule.

