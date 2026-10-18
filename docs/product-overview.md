# Product Overview

## Ringkasan Produk

Sistem NF Harapan Rumbai adalah aplikasi web internal yang mengintegrasikan penjualan, persediaan, penerimaan barang, data pemasok, kewajiban pembayaran, bukti transaksi, dan laporan dalam satu sumber data. Sistem menggantikan pencatatan manual berbantuan Excel serta bukti yang tersebar di WhatsApp dan galeri telepon seluler.

Produk ditujukan untuk satu toko dengan pengguna utama pemilik dan lima karyawan. Aplikasi harus mudah dipahami oleh pengguna awam, cepat digunakan saat pelanggan menunggu, dan memiliki jejak data yang cukup kuat untuk menelusuri transaksi serta perubahan stok.

## Latar Masalah

Proses saat ini memiliki karakteristik berikut:

- Penjualan dilakukan secara eceran dan grosir.
- Produk dapat dijual dalam pcs, satuan berat, dan pecahan seperti 0,5 kg atau 0,25 kg.
- Transaksi, persediaan, dan kewajiban dicatat manual di Excel.
- Bukti dikirim melalui WhatsApp dan disimpan di galeri.
- Catatan utama dan bukti berada pada media berbeda.
- Pembaruan stok tidak otomatis mengikuti transaksi.
- Rekap laporan membutuhkan pengumpulan data berulang.
- Bukti berisiko sulit ditemukan, terhapus, atau hilang.

## Pernyataan Visi

Menyediakan satu aplikasi operasional yang membuat transaksi toko lebih cepat, stok lebih dapat dipercaya, kewajiban pemasok lebih mudah dipantau, dan bukti lebih mudah ditelusuri tanpa menambah beban belajar yang berat bagi karyawan.

## Tujuan Produk

| ID | Tujuan | Hasil yang diharapkan |
|---|---|---|
| `GOAL-01` | Mempercepat transaksi kasir | Produk, satuan, harga, dan total dapat dipilih tanpa perhitungan ulang manual. |
| `GOAL-02` | Menjaga konsistensi persediaan | Penjualan dan penerimaan barang membuat pergerakan stok otomatis dalam satu transaksi basis data. |
| `GOAL-03` | Menata bukti transaksi | Setiap bukti ditautkan ke penjualan, penerimaan, kewajiban, atau pembayaran yang relevan. |
| `GOAL-04` | Memantau kewajiban pemasok | Pemilik dapat melihat nilai belum dibayar, sebagian dibayar, lunas, dan lewat jatuh tempo. |
| `GOAL-05` | Mempercepat laporan | Laporan berasal dari data transaksi terintegrasi dan dapat difilter tanpa rekap manual. |
| `GOAL-06` | Menjaga akuntabilitas | Perubahan penting memiliki pelaku, waktu, alasan, nilai sebelum, dan nilai sesudah. |
| `GOAL-07` | Memudahkan adopsi | Tugas utama dapat dipelajari melalui label Bahasa Indonesia, alur pendek, dan feedback yang jelas. |

## Persona

### Pemilik dan Penanggung Jawab

Nama konteks: Ibu Emi Erawati.

Kebutuhan utama:

- Memantau penjualan dan kondisi stok.
- Mengelola produk, satuan, harga, pemasok, dan pengguna.
- Memeriksa kewajiban serta jatuh tempo.
- Melihat bukti transaksi tanpa mencari di WhatsApp atau galeri.
- Menyetujui koreksi sensitif dan melihat audit trail.
- Mengunduh atau mencetak laporan.

Risiko UX:

- Layar terlalu padat atau menggunakan istilah teknis.
- Terlalu banyak langkah untuk tindakan yang sering dilakukan.
- Status tidak jelas antara draf, diposting, dibayar, dan dibatalkan.

### Karyawan Kasir atau Staf Toko

Kebutuhan utama:

- Masuk ke sistem dengan cepat.
- Mencari produk berdasarkan nama, kode, atau barcode jika tersedia.
- Memilih satuan dan harga dengan aman.
- Mencatat transaksi tanpa mengubah master data.
- Menerima feedback yang jelas ketika stok tidak cukup atau input salah.
- Melihat transaksi yang dibuat sendiri untuk kebutuhan koreksi terbatas.

Risiko UX:

- Salah memilih harga eceran atau grosir.
- Salah memasukkan kuantitas pecahan.
- Menekan tombol simpan dua kali ketika jaringan lambat.
- Tidak memahami istilah stok ledger, idempotensi, atau void.

### Staf Persediaan

Peran ini bersifat opsional dan perlu persetujuan. Tugas dapat dijalankan pemilik bila tidak ada staf khusus.

Kebutuhan utama:

- Mencatat barang masuk.
- Memeriksa dan memperbarui data pemasok.
- Mengunggah bukti.
- Melakukan penyesuaian stok dengan alasan.
- Melihat riwayat pergerakan stok.

## Prinsip Produk

1. **Satu sumber kebenaran.** Stok dan kewajiban tidak boleh dihitung dari catatan terpisah yang tidak dapat direkonsiliasi.
2. **Transaksi final bersifat tidak mudah diubah.** Koreksi dilakukan dengan mekanisme yang meninggalkan jejak.
3. **Bahasa pengguna lebih penting daripada bahasa teknis.** UI menggunakan “Stok berkurang”, bukan “negative stock movement”.
4. **Tindakan sering harus pendek.** Penjualan tunai normal ditargetkan dapat diselesaikan tanpa berpindah halaman.
5. **Tindakan berisiko membutuhkan konfirmasi.** Void, penyesuaian stok, perubahan harga, dan pembayaran kewajiban memiliki pemeriksaan tambahan.
6. **Bukti adalah bagian dari transaksi.** Berkas tidak diperlakukan sebagai galeri umum tanpa hubungan data.
7. **Akses minimum.** Pengguna hanya melihat dan melakukan yang diperlukan untuk tugasnya.
8. **Kondisi gagal dirancang.** Jaringan lambat, input ganda, stok kurang, file gagal diunggah, dan sesi habis harus memiliki perilaku jelas.

## Ruang Lingkup MVP

### Termasuk

- Autentikasi pengguna internal.
- Manajemen pengguna dan peran oleh pemilik.
- Produk, kategori, satuan dasar, satuan jual, konversi, harga eceran, dan harga grosir.
- Penjualan tunai eceran dan grosir.
- Penjualan produk satuan berat termasuk 0,25 kg dan 0,5 kg.
- Penerimaan barang dari pemasok.
- Pembaruan stok otomatis dari penjualan dan penerimaan.
- Penyesuaian stok beralasan.
- Pemasok, kewajiban, jatuh tempo, pembayaran sebagian atau penuh.
- Upload bukti untuk penerimaan dan pembayaran kewajiban.
- Dashboard pemilik dan dashboard staf.
- Laporan penjualan, stok, pergerakan stok, penerimaan, dan kewajiban.
- Audit log untuk aktivitas sensitif.
- Ekspor CSV dan tampilan cetak untuk laporan utama.

### Tidak Termasuk

- Penggajian karyawan.
- Akuntansi buku besar, neraca, dan laporan laba rugi lengkap.
- Integrasi payment gateway.
- E-commerce atau katalog publik.
- Multi-cabang.
- Pembelian pelanggan secara kredit.
- Optimasi stok dengan metode min-max otomatis.
- Aplikasi mobile native.
- Sinkronisasi offline penuh.
- Integrasi WhatsApp otomatis.
- Integrasi perangkat fiskal atau pajak.

### Kandidat Setelah MVP

- Barcode scanner dan pencetakan label.
- Cash session per giliran kerja.
- Retur penjualan dan retur pembelian lengkap.
- Peringatan stok rendah yang dapat dikonfigurasi.
- Impor produk massal dari Excel.
- Backup offsite otomatis.
- Dashboard pemilik yang dapat diakses dari luar jaringan toko.
- Notifikasi jatuh tempo melalui email atau kanal lain.

## Indikator Keberhasilan

Angka target awal harus divalidasi pada UAT, bukan dianggap sebagai data bisnis yang sudah terbukti.

| Indikator | Target rancangan |
|---|---|
| Waktu menyelesaikan penjualan tunai normal | Maksimal 60 detik setelah pengguna mengenal sistem. |
| Penjualan ganda akibat klik ulang | 0 transaksi ganda pada skenario idempotensi. |
| Perbedaan stok akibat transaksi yang sudah diposting | 0 perbedaan antara stock ledger dan saldo stok. |
| Bukti penerimaan atau pembayaran yang tidak terkait transaksi | 0 pada transaksi yang mewajibkan bukti. |
| Akses kasir ke fitur pemilik | 0 akses berhasil pada pengujian otorisasi. |
| Tugas UAT utama selesai tanpa bantuan pengembang | Minimal 90 persen skenario tugas. |
| Backup dapat dipulihkan | Restore test lulus sesuai jadwal yang ditetapkan. |

## Asumsi

- Toko memiliki perangkat dengan browser modern dan koneksi yang cukup stabil untuk mode deployment yang dipilih.
- Mata uang hanya rupiah.
- Zona waktu aplikasi adalah `Asia/Jakarta`.
- Satu transaksi dilakukan dalam satu mata uang dan satu toko.
- Harga jual dapat berbeda berdasarkan satuan dan tipe harga.
- Semua stok dinormalisasi ke satuan dasar.
- Pemilik menjadi akun superuser bisnis, bukan superuser server.
- Pengguna tidak mendaftar sendiri.

## Risiko Produk

| Risiko | Dampak | Mitigasi rancangan |
|---|---|---|
| Aturan harga grosir belum pasti | Total transaksi salah | Jadikan aturan harga sebagai blocker sebelum implementasi POS. |
| Konversi satuan tidak konsisten | Stok tidak akurat | Satuan dasar wajib dan faktor konversi tidak boleh diubah setelah dipakai tanpa migrasi terkontrol. |
| Pengguna kembali memakai Excel | Data sistem tidak lengkap | Migrasi awal, pelatihan, alur cepat, dan kebijakan satu sumber kebenaran. |
| Bukti terlalu besar atau tidak jelas | Penyimpanan boros dan sulit diverifikasi | Batas ukuran, kompresi, preview, validasi tipe, dan panduan foto. |
| Internet terputus | Kasir berhenti bila cloud-only | Tentukan deployment lokal atau prosedur fallback sebelum rilis. |
| Koreksi transaksi dilakukan sembarangan | Audit dan stok rusak | Void terkontrol, policy, alasan wajib, dan audit log. |

