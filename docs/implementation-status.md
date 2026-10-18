# Status Implementasi dan Release Gate

Dokumen ini menelusurkan hasil implementasi terhadap planning utama di `../../docs`. Status **Implemented** berarti alur aplikasi telah terhubung ke UI, memiliki validasi server, dan masuk quality gate otomatis. Status **Operational gate** membutuhkan lingkungan atau persetujuan mitra dan tidak dapat dibuktikan hanya dari mesin pengembangan.

## Cakupan Must

| Area kebutuhan | Status | Implementasi utama |
|---|---|---|
| `FR-AUTH-01`–`05` | Implemented | Login username internal, registrasi/reset publik nonaktif, role-permission server-side, akun aktif, logout, reset sementara, dan invalidasi sesi database. |
| `FR-PROD-01`–`06` | Implemented | Kategori, produk, satuan dasar/jual, konversi empat desimal, harga retail/grosir berversi, nonaktif tanpa hard delete, optimistic lock, serta snapshot transaksi. |
| `FR-SALE-01`–`09` | Implemented | POS terhubung, harga dan total dihitung server, stok dikunci, transaksi atomik, idempotensi, nomor unik, struk, serta void dengan movement pembalik. Diskon manual bernilai nol sesuai rekomendasi `OPEN-04`. |
| `FR-STOCK-01`–`07` | Implemented | Penerimaan atomik, ledger immutable, saldo, movement, adjustment beralasan, stok awal bertipe khusus, idempotensi, dan void penerimaan aman. |
| `FR-PAY-01`–`08` | Implemented | Pemasok, kewajiban turunan, jatuh tempo, pembayaran sebagian/penuh, bukti wajib, filter, serta reversal pembayaran tanpa menghapus record asli. |
| `FR-DOC-01`–`03` | Implemented | File privat, MIME/ukuran/dimensi/checksum, nama acak, metadata audit, dan download terotorisasi tanpa public URL. |
| `FR-REP-01`–`05`, `FR-AUD-01`–`02` | Implemented | Dashboard berbasis role, laporan berfilter, rekonsiliasi, CSV aman dari formula injection, audit sensitif, pencarian audit, request ID, dan audit ekspor/download/penolakan izin. |

## Quality dan Security Gate Otomatis

- Laravel Pint harus bersih.
- PHPStan/Larastan level 7 harus menghasilkan nol error.
- Seluruh Pest feature/unit test harus lulus.
- Blade, route, config, dan asset produksi harus dapat dikompilasi/cache.
- `composer audit` dan `npm audit` harus bersih pada ambang CI.
- `store:check` harus memvalidasi database, private storage, dan konfigurasi keamanan production.
- `store:reconcile --fail-on-difference` harus memastikan saldo stok dan kewajiban sama dengan ledger.

## Operational Gate Sebelum Go-live

1. Putuskan `OPEN-10` topology cloud atau server lokal berdasarkan survei internet dan kebutuhan akses luar toko.
2. Isi `.env.production` dengan secret nyata, database MySQL berizin minimum, domain HTTPS, dan password Owner awal yang tidak disimpan di repository.
3. Jalankan migrasi data master, stok awal, kewajiban terbuka, dan bukti yang sudah diverifikasi pemilik.
4. Jalankan backup lalu restore drill menggunakan environment terpisah; simpan bukti checksum dan hasil rekonsiliasi.
5. Uji browser, resolusi, printer, scanner, jaringan, serta target performa pada perangkat toko nyata.
6. Mitra menjalankan skenario UAT di `../../docs/testing-strategy.md`, menyetujui role, harga grosir, satuan produk, dan SOP koreksi.
7. Aktifkan monitoring 5xx, disk, scheduler, backup, login gagal, dan hasil rekonsiliasi sebelum traffic production dibuka.

Fitur `Could` seperti held cart, impor CSV mandiri, retur parsial, dan mode offline tetap di luar MVP sesuai prioritas planning. Fitur tersebut tidak boleh dianggap sebagai blocker untuk cakupan Must, tetapi memerlukan change request bila akan ditambahkan.
