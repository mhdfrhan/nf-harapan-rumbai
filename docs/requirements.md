# Requirements

## Konvensi Prioritas

| Prioritas | Makna |
|---|---|
| Must | Wajib untuk MVP dan tujuan kerja praktik. |
| Should | Sangat bernilai, tetapi dapat ditunda bila waktu terbatas. |
| Could | Peningkatan setelah kebutuhan utama stabil. |
| Won't | Tidak dikerjakan dalam ruang lingkup saat ini. |

## Kebutuhan Fungsional

### Autentikasi dan Pengguna

| ID | Kebutuhan | Prioritas | Kriteria penerimaan ringkas |
|---|---|---|---|
| `FR-AUTH-01` | Pengguna internal dapat login menggunakan identitas akun dan kata sandi. | Must | Kredensial valid membuka halaman sesuai peran; kredensial salah memberi pesan generik. |
| `FR-AUTH-02` | Pendaftaran publik dinonaktifkan. | Must | Tidak ada route atau UI registrasi publik. |
| `FR-AUTH-03` | Pemilik dapat membuat, menonaktifkan, dan mereset akun karyawan. | Must | Akun nonaktif tidak dapat login; reset memaksa kata sandi baru sesuai kebijakan. |
| `FR-AUTH-04` | Sistem membatasi tindakan berdasarkan peran dan izin. | Must | Pengujian policy dan route menghasilkan 403 untuk pengguna tanpa izin. |
| `FR-AUTH-05` | Pengguna dapat logout dan sesi diakhiri. | Must | Session ID tidak dapat dipakai kembali setelah logout. |
| `FR-AUTH-06` | Pemilik dapat melihat sesi atau login terakhir pengguna. | Should | Waktu login terakhir tampil tanpa membuka data rahasia. |

### Produk, Satuan, dan Harga

| ID | Kebutuhan | Prioritas | Kriteria penerimaan ringkas |
|---|---|---|---|
| `FR-PROD-01` | Pemilik dapat membuat kategori dan produk. | Must | Nama, kode, kategori, satuan dasar, dan status tervalidasi. |
| `FR-PROD-02` | Produk memiliki satu satuan dasar stok. | Must | Semua pergerakan stok disimpan dalam satuan dasar. |
| `FR-PROD-03` | Produk dapat memiliki beberapa satuan jual dengan faktor konversi. | Must | 0,25 kg dan 0,5 kg menghasilkan pengurang stok yang tepat. |
| `FR-PROD-04` | Produk dapat memiliki harga eceran dan grosir per satuan jual. | Must | POS menampilkan harga aktif sesuai aturan dan izin. |
| `FR-PROD-05` | Sistem menolak harga negatif, faktor konversi nol, dan duplikasi aturan aktif. | Must | Validasi server mengembalikan error per field. |
| `FR-PROD-06` | Produk yang pernah ditransaksikan tidak dihapus keras. | Must | Produk dinonaktifkan dan transaksi historis tetap terbaca. |
| `FR-PROD-07` | Produk dapat dicari berdasarkan nama, kode, atau barcode jika diisi. | Should | Hasil pencarian relevan tampil dalam waktu target. |
| `FR-PROD-08` | Produk dapat diimpor dari template CSV tervalidasi. | Could | Baris gagal dilaporkan tanpa menggagalkan baris valid setelah konfirmasi. |

### Penjualan POS

| ID | Kebutuhan | Prioritas | Kriteria penerimaan ringkas |
|---|---|---|---|
| `FR-SALE-01` | Kasir dapat mencari dan menambahkan produk ke keranjang. | Must | Produk aktif dapat ditambah; produk nonaktif tidak dapat dijual. |
| `FR-SALE-02` | Kasir dapat memilih satuan jual dan kuantitas. | Must | Kuantitas mengikuti presisi satuan dan tidak boleh nol atau negatif. |
| `FR-SALE-03` | Sistem menentukan harga eceran atau grosir sesuai aturan yang disetujui. | Must | Harga dan alasan pemilihannya terlihat pada item. |
| `FR-SALE-04` | Sistem menghitung subtotal, diskon berizin, total, pembayaran, dan kembalian. | Must | Semua nilai dihitung server-side menggunakan integer rupiah. |
| `FR-SALE-05` | Sistem memvalidasi ketersediaan stok sebelum posting. | Must | Penjualan ditolak jika menyebabkan stok negatif, kecuali kebijakan mengizinkan. Default: ditolak. |
| `FR-SALE-06` | Posting penjualan menyimpan transaksi, item, pembayaran, dan pergerakan stok secara atomik. | Must | Kegagalan salah satu langkah membatalkan seluruh perubahan. |
| `FR-SALE-07` | Pengiriman ulang tidak membuat transaksi ganda. | Must | Kunci idempotensi yang sama menghasilkan referensi transaksi yang sama. |
| `FR-SALE-08` | Sistem menghasilkan nomor penjualan unik dan tampilan struk. | Must | Nomor tidak duplikat dan struk sesuai transaksi yang tersimpan. |
| `FR-SALE-09` | Pemilik dapat void transaksi dengan alasan dan pembalik stok. | Must | Transaksi asli tetap ada, status berubah, dan pembalik tercatat satu kali. |
| `FR-SALE-10` | Kasir dapat melihat riwayat transaksi yang diizinkan. | Should | Filter tanggal dan nomor tersedia sesuai policy. |
| `FR-SALE-11` | Transaksi dapat ditahan dan dilanjutkan. | Could | Stok belum berubah sampai transaksi diposting. |

### Penerimaan Barang dan Persediaan

| ID | Kebutuhan | Prioritas | Kriteria penerimaan ringkas |
|---|---|---|---|
| `FR-STOCK-01` | Pengguna berizin dapat mencatat penerimaan barang dari pemasok. | Must | Header, item, kuantitas, harga beli, dan tanggal tervalidasi. |
| `FR-STOCK-02` | Posting penerimaan menambah stok secara atomik. | Must | Purchase receipt dan stock movement tersimpan atau seluruhnya rollback. |
| `FR-STOCK-03` | Sistem menyimpan stock ledger untuk setiap perubahan. | Must | Setiap movement memiliki sumber, pelaku, waktu, dan kuantitas dasar. |
| `FR-STOCK-04` | Pengguna berizin dapat melihat saldo dan riwayat pergerakan stok. | Must | Saldo dapat ditelusuri ke seluruh movement pembentuknya. |
| `FR-STOCK-05` | Penyesuaian stok membutuhkan alasan dan izin. | Must | Nilai sebelum, nilai sesudah, selisih, alasan, dan pelaku tercatat. |
| `FR-STOCK-06` | Sistem mencegah duplikasi posting penerimaan. | Must | Penerimaan yang sudah diposting tidak dapat diposting ulang. |
| `FR-STOCK-07` | Sistem mendukung stok awal pada proses migrasi. | Must | Opening balance dibuat sebagai movement khusus dan dapat diaudit. |
| `FR-STOCK-08` | Sistem menampilkan peringatan stok rendah berbasis batas manual. | Could | Tidak mengklaim metode min-max otomatis. |

### Pemasok, Kewajiban, dan Pembayaran

| ID | Kebutuhan | Prioritas | Kriteria penerimaan ringkas |
|---|---|---|---|
| `FR-PAY-01` | Pengguna berizin dapat mengelola data pemasok. | Must | Nama wajib; kontak dan alamat opsional; pemasok historis dinonaktifkan, bukan dihapus. |
| `FR-PAY-02` | Penerimaan kredit dapat membentuk kewajiban. | Must | Nilai kewajiban terkait penerimaan dan tidak melebihi nilai yang relevan. |
| `FR-PAY-03` | Kewajiban memiliki tanggal transaksi, jatuh tempo, nilai, dan status. | Must | Status dihitung dari total pembayaran dan tanggal jatuh tempo. |
| `FR-PAY-04` | Sistem mendukung pembayaran sebagian dan penuh. | Must | Sisa dihitung dari pembayaran valid; overpayment ditolak. |
| `FR-PAY-05` | Pembayaran membutuhkan bukti jika kebijakan mewajibkan. | Must | Posting ditolak jika bukti wajib belum ada. |
| `FR-PAY-06` | Sistem menampilkan kewajiban yang segera jatuh tempo dan terlambat. | Must | Query menggunakan zona waktu `Asia/Jakarta` dan aturan hari yang disetujui. |
| `FR-PAY-07` | Koreksi pembayaran dilakukan dengan void atau reversal. | Must | Pembayaran asli tidak dihapus keras dan sisa kewajiban dihitung ulang. |
| `FR-PAY-08` | Pemilik dapat memfilter kewajiban per pemasok, status, dan tanggal. | Must | Total filter sama dengan rincian data yang ditampilkan. |

### Bukti dan Dokumen

| ID | Kebutuhan | Prioritas | Kriteria penerimaan ringkas |
|---|---|---|---|
| `FR-DOC-01` | Pengguna berizin dapat mengunggah bukti ke transaksi yang relevan. | Must | Berkas tervalidasi, disimpan privat, dan memiliki relasi parent. |
| `FR-DOC-02` | Sistem menyediakan preview atau unduhan terotorisasi. | Must | URL tidak membuka berkas kepada pengguna tanpa izin. |
| `FR-DOC-03` | Sistem mencatat nama asli, nama penyimpanan, tipe, ukuran, checksum, dan pengunggah. | Must | Metadata tersedia untuk audit dan deteksi duplikasi. |
| `FR-DOC-04` | Penggantian bukti mempertahankan histori. | Should | Dokumen lama ditandai diganti, bukan hilang tanpa jejak. |

### Dashboard, Laporan, dan Audit

| ID | Kebutuhan | Prioritas | Kriteria penerimaan ringkas |
|---|---|---|---|
| `FR-REP-01` | Pemilik melihat ringkasan penjualan, stok, penerimaan, dan kewajiban. | Must | Nilai memiliki periode jelas dan dapat ditelusuri ke laporan rinci. |
| `FR-REP-02` | Staf melihat ringkasan yang relevan dengan tugasnya. | Must | Data sensitif pemilik tidak tampil kepada staf tanpa izin. |
| `FR-REP-03` | Laporan penjualan dapat difilter tanggal, kasir, tipe harga, dan produk. | Must | Total laporan sama dengan transaksi posted dikurangi void yang valid. |
| `FR-REP-04` | Laporan stok menampilkan saldo dan pergerakan. | Must | Saldo rekonsiliasi sama dengan jumlah movement. |
| `FR-REP-05` | Laporan kewajiban menampilkan nilai awal, pembayaran, sisa, dan status. | Must | Nilai laporan sama dengan ledger pembayaran. |
| `FR-REP-06` | Laporan dapat dicetak dan diekspor CSV. | Should | Ekspor menghormati filter aktif dan izin pengguna. |
| `FR-AUD-01` | Sistem mencatat aktivitas sensitif. | Must | Audit berisi aktor, aksi, target, waktu, IP, user agent, dan perubahan terpilih. |
| `FR-AUD-02` | Pemilik dapat mencari audit log. | Must | Filter aktor, aksi, target, dan tanggal tersedia. |

## Kebutuhan Nonfungsional

| ID | Area | Kebutuhan terukur |
|---|---|---|
| `NFR-PERF-01` | Respons | Halaman umum memiliki p95 server response di bawah 500 ms pada beban toko normal, tidak termasuk upload dan ekspor besar. |
| `NFR-PERF-02` | POS | Pencarian produk p95 di bawah 300 ms untuk katalog awal setelah indeks dan cache diterapkan. |
| `NFR-PERF-03` | Posting | Posting penjualan memberikan feedback segera dan selesai di bawah 2 detik pada jaringan lokal normal. |
| `NFR-REL-01` | Konsistensi | Tidak ada transaksi posted yang hanya menyimpan sebagian item, pembayaran, atau movement. |
| `NFR-REL-02` | Idempotensi | Retry request posting tidak membuat duplikasi. |
| `NFR-REL-03` | Backup | Backup basis data dan bukti dilakukan sesuai RPO, serta restore diuji berkala. |
| `NFR-SEC-01` | Otorisasi | Seluruh tindakan bisnis dilindungi policy server-side, bukan hanya menyembunyikan tombol. |
| `NFR-SEC-02` | Transport | Produksi menggunakan HTTPS; cookie session Secure, HttpOnly, dan SameSite sesuai deployment. |
| `NFR-SEC-03` | Upload | Berkas divalidasi berdasarkan MIME, signature, ukuran, dan izin akses. |
| `NFR-UX-01` | Bahasa | Label, error, bantuan, dan status menggunakan Bahasa Indonesia yang dapat ditindaklanjuti. |
| `NFR-UX-02` | Aksesibilitas | Target WCAG 2.2 AA untuk kontras, keyboard, label, focus visible, dan error association. |
| `NFR-UX-03` | Touch | Target sentuh minimal 44 x 44 CSS pixel untuk tindakan utama. |
| `NFR-UX-04` | Responsive | POS dioptimalkan untuk desktop atau tablet landscape; modul lain mendukung mobile untuk monitoring. |
| `NFR-OPS-01` | Observability | Error, job gagal, login gagal berulang, dan backup gagal dapat diketahui melalui log atau alert. |
| `NFR-MNT-01` | Maintainability | Modul domain terpisah, action class dapat diuji, dan query kompleks memiliki test. |
| `NFR-MNT-02` | Compatibility | Browser minimum mengikuti dukungan Tailwind CSS 4 atau fallback yang disetujui. |
| `NFR-DATA-01` | Retensi | Transaksi dan audit tidak dihapus tanpa kebijakan retensi serta persetujuan pemilik. |
| `NFR-PRIV-01` | Data minimum | Sistem tidak mengumpulkan data pelanggan yang tidak diperlukan. |

## Acceptance Gate MVP

- Seluruh kebutuhan `Must` memiliki test otomatis atau skenario UAT.
- Tidak ada blocker keamanan level tinggi yang terbuka.
- Rekonsiliasi stock ledger dan saldo lulus pada data uji.
- Penjualan 0,25 kg, 0,5 kg, eceran, dan grosir lulus.
- Posting ulang dengan idempotency key yang sama tidak menggandakan transaksi.
- Kasir tidak dapat mengakses user management, audit, dan laporan sensitif.
- Bukti private tidak dapat diakses melalui URL langsung tanpa otorisasi.
- Backup dapat dipulihkan pada environment uji.
- Pengguna mitra menyelesaikan skenario UAT utama.

