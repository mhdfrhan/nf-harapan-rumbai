# Screen Specifications

## Standar Setiap Layar

Semua layar wajib memiliki definisi izin, data sumber, tindakan utama, loading state, empty state, error state, dan perilaku responsive. Tombol atau menu yang disembunyikan tidak menggantikan pemeriksaan izin di server.

## SCR-01 Halaman Masuk

**Tujuan:** Mengautentikasi pengguna tanpa membebani pengguna awam.

**Isi:** Logo atau wordmark sederhana, nama sistem, identitas akun, kata sandi, tampilkan kata sandi, tombol masuk, dan informasi kontak pemilik bila akun bermasalah.

**Rancangan:** Panel form tunggal pada surface cream terang. Tidak ada hero marketing, statistik, testimoni, gradient, atau carousel.

**Validasi:** Field wajib, rate limit, pesan kredensial generik, indikator Caps Lock jika andal.

**State:** Memeriksa akun, kredensial tidak valid, akun nonaktif, terlalu banyak percobaan, session kedaluwarsa, dan berhasil.

## SCR-02 Dashboard Pemilik

**Tujuan:** Menunjukkan kondisi operasional yang memerlukan perhatian, bukan sekadar angka dekoratif.

**Bagian:**

- Penjualan hari ini: nilai bersih dan jumlah transaksi.
- Stok rendah: jumlah produk dan tautan ke daftar terfilter.
- Kewajiban jatuh tempo atau mendekati jatuh tempo.
- Penerimaan terakhir.
- Aktivitas sensitif terakhir, misalnya void atau penyesuaian besar.
- Quick action: buka kasir, catat barang masuk, dan catat pembayaran.

**Aturan:** Setiap ringkasan dapat ditelusuri ke sumber. Perbandingan periode hanya ditampilkan jika definisi periode lengkap dan data cukup.

**Empty state:** Menjelaskan bahwa dashboard akan terisi setelah produk dan transaksi pertama dibuat, dengan tautan ke setup awal.

## SCR-03 Dashboard Karyawan

**Tujuan:** Mengarahkan karyawan ke tugas shift.

**Bagian:** Nama dan waktu shift, jumlah transaksi pengguna hari ini, transaksi terakhir, draft tertahan jika diaktifkan, dan tombol `Buka kasir`.

Karyawan tidak melihat margin, nilai pembelian, total kewajiban, atau aktivitas pengguna lain kecuali diizinkan.

## SCR-04 POS

**Izin:** `sales.create`.

**Panel produk:** Search SKU, barcode, dan nama; filter kategori ringan; daftar hasil; indikator satuan dan stok.

**Panel keranjang:** Item, satuan, kuantitas, jenis harga, harga satuan, subtotal, hapus item, diskon bila disetujui, total, metode bayar, uang diterima, dan kembalian.

**Primary action:** `Selesaikan penjualan Rp...`.

**Validasi:** Produk aktif, satuan valid, kuantitas positif, stok cukup, metode bayar valid, uang diterima cukup, serta harga dihitung server-side.

**Keyboard:** `/` untuk pencarian, Enter menambah hasil tunggal bila aman, Escape menutup overlay, dan shortcut penyelesaian hanya jika tidak berisiko submit tidak sengaja.

**State khusus:** Keranjang kosong, hasil pencarian kosong, produk tidak tersedia, stok berubah, harga berubah, mengirim, memeriksa status, berhasil, gagal, dan cetak ulang.

## SCR-05 Detail dan Riwayat Penjualan

**Daftar:** Nomor, waktu, kasir, jumlah item, total, metode pembayaran, dan status. Filter tanggal default hari ini untuk menjaga performa.

**Detail:** Snapshot item dan harga, pembayaran, movement stok terkait, aktor, waktu posting, bukti void jika ada, dan tombol cetak ulang.

**Tindakan sensitif:** Void hanya untuk izin yang sesuai. Dialog menjelaskan dampak stok dan laporan serta meminta alasan.

## SCR-06 Daftar Produk

**Kolom:** Nama, SKU, kategori, satuan dasar, harga eceran utama, harga grosir ringkas, stok, status, dan tindakan.

**Filter:** Search, kategori, status aktif, stok rendah, dan tanpa harga.

**Primary action:** `Tambah produk`.

**Bulk action:** Tidak ada pada MVP kecuali impor data; ini mencegah perubahan massal tanpa kontrol.

**Empty state:** `Belum ada produk` dengan tindakan tambah atau impor template.

## SCR-07 Form Produk

**Bagian identitas:** Nama, SKU, barcode opsional, kategori, deskripsi internal, dan status.

**Bagian persediaan:** Satuan dasar, batas stok rendah, dan kebijakan stok negatif.

**Bagian satuan jual:** Nama satuan, faktor konversi ke satuan dasar, barcode khusus opsional, dan status.

**Bagian harga:** Harga eceran dan grosir per satuan, tanggal efektif opsional, ambang grosir jika model otomatis dipilih.

**Review:** Ringkasan konversi dengan contoh, misalnya `1 kemasan = 0,25 kg`. Sistem memperingatkan jika perubahan konversi akan memengaruhi transaksi baru.

## SCR-08 Kategori dan Satuan

Daftar sederhana dengan tambah dan ubah inline atau dialog. Satuan atau kategori yang sudah digunakan tidak dapat dihapus permanen, hanya dinonaktifkan. Form menolak nama duplikat setelah normalisasi spasi dan huruf besar kecil.

## SCR-09 Stok Saat Ini

**Kolom:** Produk, SKU, stok dasar, tampilan satuan yang familier, batas minimum, status, dan pembaruan terakhir.

**Filter:** Search, kategori, stok rendah, stok nol, dan ketidaksesuaian saldo.

**Tindakan:** Lihat movement dan buat penyesuaian jika diizinkan.

Nilai stok berasal dari saldo terkontrol dan dapat direkonsiliasi ke ledger, bukan dari penjumlahan browser.

## SCR-10 Buku Besar Pergerakan Stok

**Kolom:** Waktu, produk, tipe, referensi transaksi, kuantitas masuk atau keluar, saldo setelah, aktor, dan alasan.

**Filter:** Produk, tipe movement, tanggal, serta referensi.

**Aturan:** Read-only. Movement tidak memiliki tombol edit atau delete. Detail menautkan ke penjualan, penerimaan, void, atau penyesuaian asal.

## SCR-11 Penyesuaian Stok

**Isi:** Produk, stok sistem, stok fisik atau selisih, hasil akhir, alasan terstruktur, catatan wajib, dan bukti opsional.

**Primary action:** `Posting penyesuaian`.

**Peringatan:** Menyebut kuantitas yang akan ditambah atau dikurangi dan bahwa riwayat tidak dapat diedit setelah posting.

## SCR-12 Daftar Penerimaan Barang

**Kolom:** Nomor, tanggal, pemasok, jumlah item, total, status posting, status pembayaran, jatuh tempo, dan bukti.

**Filter:** Tanggal, pemasok, pembayaran, jatuh tempo, dan tanpa bukti.

**Primary action:** `Catat barang masuk`.

## SCR-13 Form dan Detail Penerimaan

**Header:** Pemasok, tanggal penerimaan, nomor nota pemasok, dan catatan.

**Item repeater:** Produk, satuan beli, kuantitas, harga beli, dan subtotal. Pencarian harus dapat dioperasikan dengan keyboard.

**Pembayaran:** Lunas, sebagian, atau belum dibayar; nominal awal; tanggal jatuh tempo.

**Bukti:** Unggah foto atau PDF, preview aman, progres, ganti, dan hapus selama draft.

**Review:** Dampak stok per produk, total, pembayaran, sisa kewajiban, serta daftar file.

Detail posted bersifat read-only dan menautkan ke stock movement, payable, pembayaran, serta audit event.

## SCR-14 Pemasok

**Daftar:** Nama, kontak, alamat ringkas, kewajiban berjalan, transaksi terakhir, dan status.

**Profil:** Informasi pemasok, riwayat penerimaan, kewajiban aktif, pembayaran, dokumen, dan catatan internal.

Nomor rekening dan data sensitif lain tidak dikumpulkan pada MVP tanpa kebutuhan nyata.

## SCR-15 Daftar dan Detail Kewajiban

**Daftar:** Pemasok, referensi penerimaan, nilai awal, sudah dibayar, sisa, jatuh tempo, umur, dan status.

**Filter cepat:** Jatuh tempo, 7 hari ke depan, sebagian, belum dibayar, dan lunas.

**Detail:** Timeline penerimaan, pembayaran, pembalik, bukti, serta saldo yang dihitung ulang dari record valid.

**Primary action:** `Catat pembayaran` jika saldo masih ada.

## SCR-16 Form Pembayaran Kewajiban

**Isi:** Referensi, sisa kewajiban read-only, tanggal, nominal, metode, nomor referensi opsional, bukti, dan catatan.

**Validasi:** Nominal lebih dari nol dan tidak melampaui sisa; tanggal tidak lebih awal dari penerimaan tanpa override pemilik; file valid.

**Review:** Saldo sebelum, pembayaran ini, dan saldo sesudah.

## SCR-17 Laporan

Semua laporan memakai pola seragam: judul, definisi singkat, rentang waktu, filter, waktu pembaruan, ringkasan, tabel rinci, dan ekspor.

**Jenis:**

- Penjualan dan item terjual.
- Stok saat ini dan stok rendah.
- Pergerakan stok.
- Penerimaan atau pembelian.
- Kewajiban dan umur utang.
- Aktivitas pembatalan dan penyesuaian.

Setiap nilai agregat dapat ditelusuri ke transaksi sumber. Laporan laba penuh tidak masuk MVP karena belum ada cakupan akuntansi dan penetapan metode HPP.

## SCR-18 Pengguna dan Peran

**Izin:** Pemilik.

**Daftar:** Nama, identitas masuk, peran, status, login terakhir, dan tindakan.

**Tindakan:** Tambah akun, reset kata sandi, ubah peran, nonaktifkan, dan akhiri session aktif jika diperlukan.

Sistem mencegah pemilik terakhir menonaktifkan akun sendiri atau kehilangan seluruh hak pengelolaan.

## SCR-19 Audit Log

**Izin:** Pemilik atau auditor berizin.

**Kolom:** Waktu, aktor, event, objek, ringkasan perubahan, alamat IP, dan request ID.

**Filter:** Rentang waktu terbatas, aktor, event, serta tipe objek.

Nilai rahasia, token, kata sandi, dan isi penuh dokumen tidak ditampilkan. Audit log read-only.

## SCR-20 Pengaturan Toko

**Bagian:** Nama toko, alamat, jam operasional, zona waktu yang dikunci `Asia/Jakarta`, format nomor transaksi, batas stok rendah default, kebijakan bukti, dan preferensi cetak.

Perubahan pengaturan penting dicatat ke audit log. Pengaturan yang memengaruhi data historis berlaku prospektif.

## SCR-21 Halaman Kesalahan

- `403`: akses tidak tersedia, tautan kembali ke dashboard.
- `404`: data atau halaman tidak ditemukan tanpa membocorkan keberadaan record terlarang.
- `419`: sesi berakhir, arahkan login, jangan ulangi POST.
- `422`: validasi ditampilkan pada form.
- `429`: waktu tunggu yang jelas.
- `500`: pesan aman, request ID, serta tindakan kembali.

## Checklist Penerimaan Layar

- Izin server teruji.
- State loading, empty, filtered-empty, success, validation, forbidden, server-error, dan offline tersedia.
- Keyboard, focus, dan screen reader diuji.
- Tampilan 360, 768, 1280, dan 1536 px diuji.
- Tidak ada nilai uang atau stok yang hanya dihitung di client.
- Copy menggunakan istilah yang sama dengan dokumen domain.
- Tindakan posted tidak menawarkan edit atau delete langsung.

