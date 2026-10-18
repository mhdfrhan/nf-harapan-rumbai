# User Flows

## Tujuan

Dokumen ini mendefinisikan urutan tindakan, keputusan sistem, respons antarmuka, dan kondisi gagal. Setiap alur menggunakan istilah `Draft`, `Posted`, `Void`, atau `Paid` sesuai aturan pada `domain-rules.md`.

## Prinsip Umum

1. Pengguna selalu mengetahui lokasi, status transaksi, dan tindakan berikutnya.
2. Tombol utama hanya satu pada setiap konteks.
3. Tindakan yang berdampak pada stok atau uang menampilkan ringkasan sebelum diposting.
4. Klik ganda, refresh, dan pengiriman ulang tidak boleh menggandakan transaksi.
5. Kesalahan ditulis dalam bahasa sederhana dan mempertahankan isian yang masih valid.
6. Tindakan sensitif meminta alasan, bukan hanya konfirmasi generik.

## FLOW-01 Masuk ke Sistem

**Aktor:** Pemilik atau karyawan.

**Prasyarat:** Akun aktif telah dibuat oleh pemilik.

1. Pengguna membuka halaman masuk.
2. Pengguna memasukkan identitas akun dan kata sandi.
3. Sistem melakukan rate limiting, validasi kredensial, dan pemeriksaan status akun.
4. Sistem meregenerasi session ID setelah autentikasi berhasil.
5. Sistem mengarahkan pengguna ke dashboard sesuai peran.
6. Sistem mencatat waktu masuk terakhir dan event audit tanpa menyimpan kata sandi.

**Kondisi gagal:**

- Kredensial salah: pesan tidak membocorkan apakah akun terdaftar.
- Akun nonaktif: akses ditolak dan pengguna diarahkan menghubungi pemilik.
- Terlalu banyak percobaan: formulir dikunci sementara dengan sisa waktu yang jelas.

## FLOW-02 Penjualan Tunai Normal

**Aktor:** Kasir atau pemilik.

**Prasyarat:** Shift pengguna aktif, produk aktif, dan stok tersedia.

1. Pengguna membuka POS dan fokus otomatis berada pada pencarian produk.
2. Pengguna mencari berdasarkan nama, SKU, atau barcode.
3. Pengguna memilih produk dan satuan jual.
4. Sistem menampilkan harga, label eceran atau grosir, stok tersedia dalam satuan yang mudah dipahami, dan menambah item ke keranjang.
5. Pengguna mengubah jumlah bila diperlukan.
6. Sistem menghitung ulang subtotal dan total pada server. Nilai dari browser tidak dianggap sebagai sumber kebenaran.
7. Pengguna memilih metode pembayaran dan memasukkan uang diterima jika tunai.
8. Sistem menampilkan total, uang diterima, dan kembalian.
9. Pengguna menekan `Selesaikan penjualan`.
10. Sistem mengunci sumber stok yang relevan, memvalidasi ulang harga dan stok, lalu dalam satu transaksi basis data membuat penjualan, item, pembayaran, serta stock movement.
11. Sistem memberi nomor transaksi, menampilkan status berhasil, dan menyediakan cetak struk.
12. Keranjang dibersihkan hanya setelah server mengonfirmasi keberhasilan.

**Kondisi gagal:**

- Stok berubah sejak item dimasukkan: sistem menandai item terkait dan meminta pengguna menyesuaikan kuantitas.
- Harga berubah: sistem menampilkan harga baru sebelum posting.
- Koneksi terputus: tombol masuk state `Memeriksa status`; sistem mencari idempotency key sebelum menawarkan kirim ulang.
- Printer gagal: penjualan tetap tersimpan dan struk dapat dicetak ulang.

## FLOW-03 Penjualan Grosir

1. Produk dan satuan dipilih seperti alur normal.
2. Sistem menerapkan tingkat harga grosir otomatis bila ambang kuantitas terpenuhi, atau melalui pilihan berizin jika model manual disetujui pada `OPEN-03`.
3. Label `Harga grosir` terlihat pada item dan ringkasan.
4. Jika kuantitas turun di bawah ambang, sistem mengembalikan harga ke eceran dan memberi pemberitahuan singkat.
5. Posting mengikuti kontrol yang sama dengan `FLOW-02`.

## FLOW-04 Menahan dan Melanjutkan Keranjang

1. Pengguna memilih `Simpan sementara`.
2. Sistem meminta label singkat opsional, lalu menyimpan draft tanpa memengaruhi stok.
3. Draft terlihat pada daftar keranjang tertahan beserta pemilik draft dan waktu pembaruan.
4. Pengguna yang berhak membuka draft, sistem memvalidasi ulang produk, harga, dan stok.
5. Draft yang kedaluwarsa ditandai, bukan dihapus diam-diam.

Fitur ini berprioritas `Should`. Jika tidak masuk MVP, kasir menyelesaikan atau membatalkan keranjang aktif.

## FLOW-05 Penerimaan Barang dari Pemasok

**Aktor:** Pemilik atau petugas persediaan.

1. Pengguna membuat draft penerimaan dan memilih pemasok.
2. Pengguna mengisi tanggal dokumen, nomor nota pemasok jika ada, produk, satuan beli, kuantitas, dan harga beli.
3. Sistem mengonversi kuantitas ke satuan dasar dan menghitung total.
4. Pengguna memilih status pembayaran: lunas, sebagian, atau belum dibayar.
5. Jika belum lunas, pengguna mengisi tanggal jatuh tempo sesuai kebijakan toko.
6. Pengguna mengunggah bukti. Sistem memvalidasi tipe, ukuran, dan konten dasar sebelum menyimpan pada private storage.
7. Halaman review menampilkan pemasok, item, kenaikan stok, total, pembayaran, sisa kewajiban, dan bukti.
8. Pengguna memposting penerimaan.
9. Dalam satu transaksi basis data, sistem membuat penerimaan, item, stock movement masuk, kewajiban, dan pembayaran awal bila ada.
10. Sistem menampilkan nomor penerimaan dan status berhasil.

**Kondisi gagal:** item tidak aktif, konversi satuan tidak tersedia, nilai pembayaran melebihi total, tanggal tidak logis, atau file ditolak. Draft tetap tersimpan.

## FLOW-06 Mencatat Pembayaran Kewajiban

1. Pengguna membuka daftar kewajiban dan memilih pemasok atau transaksi.
2. Sistem menampilkan nilai awal, total pembayaran valid, sisa, jatuh tempo, dan dokumen terkait.
3. Pengguna mengisi tanggal, nominal, metode, referensi, serta bukti jika diwajibkan.
4. Sistem menolak nominal nol, negatif, atau lebih besar dari sisa.
5. Pengguna meninjau dan mengonfirmasi.
6. Sistem menyimpan pembayaran secara atomik dan mengubah status kewajiban menjadi `Partial` atau `Paid`.
7. Audit log menyimpan aktor, waktu, nominal, dan sumber transaksi.

## FLOW-07 Penyesuaian Stok

**Aktor:** Pemilik atau petugas persediaan dengan izin.

1. Pengguna memilih produk dan melihat stok sistem serta riwayat terakhir.
2. Pengguna memilih alasan terstruktur: hasil hitung fisik, rusak, hilang, kedaluwarsa, atau koreksi input.
3. Pengguna memasukkan `stok fisik` atau `selisih`, bergantung mode yang disetujui.
4. Sistem menghitung dampak dan meminta catatan wajib.
5. Pengguna mengonfirmasi.
6. Sistem menulis stock movement baru. Riwayat lama tidak diubah.

Penyesuaian besar di atas ambang yang akan ditetapkan dapat memerlukan persetujuan pemilik.

## FLOW-08 Void Penjualan

1. Pengguna membuka detail penjualan yang sudah diposting.
2. Sistem memeriksa izin, status, dan apakah transaksi sudah pernah di-void.
3. Pengguna memilih `Batalkan transaksi`, membaca dampak, dan memasukkan alasan.
4. Pemilik mengonfirmasi ulang kredensial atau PIN jika kebijakan ini disetujui.
5. Sistem membuat record void dan stock movement pembalik dalam transaksi atomik.
6. Transaksi awal tetap terlihat dengan status `Void` serta tautan ke pembalik.
7. Laporan mengecualikan nilai penjualan void tetapi tetap menampilkan jumlah void pada bagian audit.

## FLOW-09 Melihat dan Mengekspor Laporan

1. Pengguna memilih jenis laporan dan rentang tanggal.
2. Sistem menggunakan zona waktu `Asia/Jakarta`, menampilkan filter aktif dan waktu pembaruan data.
3. Sistem menampilkan ringkasan serta tabel rinci yang dapat ditelusuri ke transaksi sumber.
4. Pengguna mengekspor CSV atau PDF bila memiliki izin.
5. Ekspor kecil diproses langsung. Ekspor besar dibuat sebagai queued job dan hanya dapat diunduh oleh peminta atau pemilik.
6. Event ekspor dicatat pada audit log.

## FLOW-10 Keluar dan Session Timeout

1. Pengguna memilih keluar atau session mencapai batas tidak aktif.
2. Jika ada draft belum tersimpan, sistem memperingatkan pengguna sebelum navigasi normal.
3. Sistem menginvalidasi session dan meregenerasi token CSRF.
4. Pada timeout, pengguna diarahkan masuk kembali tanpa memposting ulang tindakan terakhir.

## Pola Kegagalan Lintas Alur

| Kondisi | Respons sistem |
|---|---|
| Validasi field | Pesan diletakkan dekat field, ringkasan singkat di atas, fokus menuju kesalahan pertama. |
| Server gagal | Tampilkan ID insiden, pertahankan draft, dan sediakan `Coba lagi` jika aman. |
| Koneksi lambat | Loading state muncul tanpa menggeser layout; tombol submit dinonaktifkan sementara. |
| Hak akses berubah | Batalkan tindakan, jangan simpan sebagian, dan arahkan kembali ke layar aman. |
| Record telah diperbarui pihak lain | Tampilkan data terbaru serta minta pengguna meninjau ulang. |
| File gagal | Transaksi draft tetap ada; file dapat diunggah ulang tanpa mengisi ulang semua field. |

## Acceptance Gate Alur

Setiap alur kritis harus lulus happy path, validasi, unauthorized path, double-submit, kehilangan koneksi, konkurensi, dan pemulihan tampilan sebelum dinyatakan selesai.

