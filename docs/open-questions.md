# Open Questions and Approval Checklist

## Cara Menggunakan Dokumen

Pertanyaan `BLOCKER` harus diputuskan sebelum implementasi. Setiap pertanyaan memiliki rekomendasi awal untuk mempercepat persetujuan. Rekomendasi bukan fakta dari mitra dan dapat diubah oleh pemilik proyek.

## Keputusan Bisnis Blocker

### OPEN-01 Peran Pengguna

**Pertanyaan:** Apakah MVP memakai dua peran, `Owner` dan `Kasir`, atau tiga peran dengan `Petugas Persediaan`?

**Rekomendasi:** Tiga peran. Owner tetap dapat melakukan semua tugas, sementara hak barang masuk dan kewajiban dapat diberikan hanya kepada karyawan yang dipercaya.

**Dampak:** Navigation, permission, UAT, dan training.

### OPEN-02 Cakupan Riwayat Kasir

**Pertanyaan:** Kasir boleh melihat transaksi sendiri pada hari berjalan saja atau semua transaksi pada shift atau hari yang sama?

**Rekomendasi:** Transaksi sendiri pada hari berjalan. Owner dapat melihat seluruhnya.

**Dampak:** Privasi operasional, koreksi, dan kemudahan serah shift.

### OPEN-03 Pemicu Harga Grosir

**Pertanyaan:** Harga grosir berlaku otomatis setelah kuantitas minimum per produk, dipilih manual oleh pengguna berizin, atau keduanya?

**Rekomendasi:** Otomatis berdasarkan ambang per satuan jual. Manual override hanya untuk Owner dan selalu diaudit.

**Dampak:** Product price schema, POS, audit, dan test.

### OPEN-04 Diskon

**Pertanyaan:** Apakah toko menggunakan diskon per item atau per transaksi? Siapa yang boleh memberi dan berapa batasnya?

**Rekomendasi:** Tidak ada diskon manual pada MVP bila praktiknya belum ada. Jangan membangun fungsi hanya sebagai antisipasi.

**Dampak:** Total, permission, laporan, struk, dan potensi penyalahgunaan.

### OPEN-05 Stok Negatif

**Pertanyaan:** Apakah transaksi boleh selesai ketika stok sistem tidak cukup?

**Rekomendasi:** Tidak. Owner melakukan pemeriksaan atau adjustment beralasan terlebih dahulu.

**Dampak:** Integritas persediaan dan kecepatan kasir.

### OPEN-06 Aturan Satuan

**Pertanyaan:** Daftar produk mana yang dijual dalam pecahan, dus, renteng, pak, atau satuan lain, dan berapa conversion per produk?

**Rekomendasi:** Kumpulkan minimal 20 contoh produk yang mewakili semua pola. Conversion didefinisikan per produk, bukan asumsi global.

**Dampak:** Data migration, form produk, stok, harga, dan test.

### OPEN-07 Kewajiban dan Bukti

**Pertanyaan:** Apakah tanggal jatuh tempo dan bukti pembayaran wajib untuk semua pembelian yang belum lunas?

**Rekomendasi:** Jatuh tempo wajib untuk kewajiban baru; bukti wajib untuk setiap pembayaran kewajiban. Owner dapat memberi catatan pengecualian pada data migrasi.

**Dampak:** Validation, reminder, upload, dan migration.

### OPEN-08 Koreksi Penerimaan

**Pertanyaan:** Bagaimana proses jika barang masuk yang sudah diposting salah atau dikembalikan kepada supplier?

**Rekomendasi:** MVP mendukung void penuh hanya jika belum ada pembayaran lanjutan dan stok masih dapat dibalik. Retur sebagian menjadi fase berikutnya.

**Dampak:** State machine, stock reversal, payable, dan report.

### OPEN-09 Nomor Transaksi

**Pertanyaan:** Apakah format `PJ-YYYYMMDD-0001`, `PB-YYYYMMDD-0001`, `KW-YYYYMMDD-0001`, `BY-YYYYMMDD-0001`, dan `PS-YYYYMMDD-0001` dapat diterima?

**Rekomendasi:** Gunakan format tersebut dan reset sequence setiap hari per tipe transaksi.

**Dampak:** Struk, search, migration, dan sequence concurrency.

## Keputusan Teknis Blocker

### OPEN-10 Lokasi Deployment

**Pertanyaan:** Sistem akan berjalan pada cloud VPS atau server lokal atau mini PC di toko?

**Rekomendasi:** Survei internet terlebih dahulu. Pilih cloud jika koneksi stabil dan owner perlu akses dari luar; pilih lokal jika POS harus tetap aktif saat internet putus.

**Dampak:** Availability, HTTPS, backup, security, biaya, dan support.

### OPEN-11 Perangkat Nyata

**Pertanyaan:** Perangkat apa yang dipakai untuk kasir dan pemilik, termasuk sistem operasi, browser, ukuran layar, printer struk, dan barcode scanner?

**Rekomendasi:** Desktop atau laptop Windows dengan Chrome atau Edge terbaru, monitor minimal 1366 x 768, scanner USB keyboard mode, dan printer 80 mm jika memang tersedia.

**Dampak:** Responsive design, shortcut, print stylesheet, browser matrix, dan performance test.

### OPEN-12 Identitas Login

**Pertanyaan:** Pengguna masuk menggunakan username, email, atau keduanya?

**Rekomendasi:** Username karena lebih mudah untuk akun internal dan tidak membutuhkan email setiap karyawan. Email owner dapat dipakai khusus pemulihan jika tersedia.

**Dampak:** User schema, reset password, dan training.

### OPEN-13 Histori yang Dimigrasikan

**Pertanyaan:** Apakah histori penjualan dan penerimaan lama perlu diimpor penuh, atau cukup master data, stok awal, kewajiban terbuka, dan bukti relevan?

**Rekomendasi:** Cukup data awal yang masih operasional. Arsip Excel lama dibuat read-only. Histori penuh hanya jika ada kebutuhan laporan yang jelas.

**Dampak:** Biaya cleansing, reliabilitas laporan, jadwal, dan storage.

### OPEN-14 File Bukti

**Pertanyaan:** Apakah batas awal 8 MB per file, maksimal 5 file, dengan format JPEG, PNG, dan PDF cukup?

**Rekomendasi:** Ya untuk MVP, lalu ukur file nyata sebelum production.

**Dampak:** PHP config, storage, upload UX, backup, dan scanning.

### OPEN-15 Recovery Target

**Pertanyaan:** Berapa kehilangan data maksimal dan waktu pemulihan yang dapat diterima?

**Rekomendasi awal:** RPO 24 jam dengan backup malam dan RTO 4 sampai 8 jam. Jika tidak dapat menerima kehilangan transaksi satu hari, gunakan backup lebih sering atau binlog recovery.

**Dampak:** Biaya storage, konfigurasi database, monitoring, dan SOP.

## Pertanyaan Penting tetapi Tidak Menghalangi Scaffold

### OPEN-16 Threshold Penyesuaian

Berapa selisih kuantitas atau nilai yang mengharuskan persetujuan Owner?

**Rekomendasi:** Mulai tanpa auto-approval untuk petugas. Semua adjustment hanya Owner sampai pola selisih diketahui.

### OPEN-17 Pengingat

Pengingat jatuh tempo cukup pada dashboard atau juga perlu email atau WhatsApp?

**Rekomendasi:** Dashboard untuk MVP. Integrasi eksternal ditunda.

### OPEN-18 Jam dan Hari Operasional

Apakah jam Senin sampai Sabtu pukul 09.00 sampai 22.00 harus membatasi posting transaksi atau hanya metadata laporan?

**Rekomendasi:** Metadata dan warning saja. Owner tetap dapat posting koreksi di luar jam dengan audit.

### OPEN-19 Data Pelanggan

Apakah struk atau transaksi perlu mencatat nama pelanggan?

**Rekomendasi:** Tidak pada MVP karena masalah utama tidak membutuhkan data pelanggan.

### OPEN-20 Ukuran Katalog dan Volume

Berapa jumlah produk aktif, transaksi harian, penerimaan bulanan, dan pertumbuhan file bukti?

**Rekomendasi:** Ambil jumlah nyata dari Excel dan observasi untuk sizing, index, dan target performa.

## Persetujuan Arah UI

Mohon setujui atau koreksi:

- Tema tunggal light cream.
- Aksen utama hijau tua.
- Bahasa Indonesia sederhana.
- Sidebar desktop dan drawer mobile.
- POS dua panel pada desktop.
- Motion minimum.
- Tidak ada gradient, glassmorphism, atau kartu dekoratif.
- Target WCAG 2.2 AA.
- Flux UI sebagai primitive dengan kustomisasi token, bukan tampilan default.

## Approval Record

Bagian ini diisi setelah konfirmasi.

| Area | Keputusan | Disetujui oleh | Tanggal | Catatan |
|---|---|---|---|---|
| MVP | Belum | | | |
| Role | Belum | | | |
| Unit dan harga | Belum | | | |
| Kewajiban dan bukti | Belum | | | |
| Deployment | Belum | | | |
| Migrasi | Belum | | | |
| UI | Belum | | | |
| Mulai implementasi | Belum | | | |

## Pernyataan Gate

Scaffold dan boundary proyek sudah dibuat pada `../nf-harapan-rumbai-web`. Implementasi migrasi domain dan fitur bisnis dimulai setelah pengguna memberikan keputusan atas pertanyaan yang memengaruhi modul terkait, minimal dengan menerima rekomendasi yang tertulis.
