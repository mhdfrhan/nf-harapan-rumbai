# Testing Strategy

## Tujuan

Pengujian membuktikan bahwa sistem memenuhi kebutuhan, menjaga integritas transaksi, membatasi akses, dapat dipakai pengguna toko, dan pulih dari kondisi gagal. Black box testing yang dibutuhkan laporan KP tetap menjadi bagian UAT, tetapi implementasi juga memiliki automated test untuk mencegah regresi.

## Piramida Pengujian

| Lapisan | Fokus | Proporsi rancangan |
|---|---|---:|
| Unit | Money, quantity, conversion, price rule, state machine | Sedang |
| Feature dan domain action | Database transaction, policy, validation, posting, reversal | Terbesar |
| Livewire component | State form, event, pagination, error rendering | Sedang |
| Browser end-to-end | Alur kritis pengguna dan JavaScript | Kecil tetapi wajib |
| UAT black box | Kesesuaian kebutuhan mitra dan kemudahan penggunaan | Wajib |

Target coverage angka bukan tujuan tunggal. Jalur risiko tinggi wajib tertutup meskipun persentase global tampak tinggi.

## Tools

- Pest sebagai test runner utama.
- Fitur pengujian Laravel untuk HTTP, authorization, notification, queue, file storage, time, dan database.
- Livewire testing utilities untuk component.
- Laravel Dusk atau browser tool setara yang dipilih satu kali untuk alur end-to-end.
- MySQL test service untuk test yang bergantung pada locking atau behavior database. SQLite tidak boleh menjadi satu-satunya database CI.
- Factory dan seeder deterministik.

## TEST-AUTH

- Login benar dan salah.
- Akun nonaktif ditolak.
- Rate limit bekerja dan pulih setelah waktu berlalu.
- Session diregenerasi saat login dan invalid saat logout.
- Public registration tidak ada.
- Reset atau perubahan kata sandi mengakhiri session sesuai kebijakan.
- Kasir, petugas persediaan, dan pemilik menerima menu serta akses yang benar.
- Direct URL, forged Livewire action, dan model ID pengguna lain tetap ditolak.

## TEST-CATALOG

- SKU dan barcode unik.
- Product unit tidak dapat memiliki conversion nol atau negatif.
- Konversi 0,25 dan 0,5 menghasilkan base quantity tepat.
- Produk nonaktif tidak dapat dijual atau diterima dalam transaksi baru.
- Perubahan nama atau harga tidak mengubah snapshot transaksi lama.
- Harga efektif tidak overlap tanpa rule.
- Kategori atau satuan terpakai tidak dapat dihapus secara merusak.

## TEST-SALES

### Happy path

- Penjualan eceran satu item.
- Beberapa item dan satuan berbeda.
- Harga grosir sesuai rule.
- Pembayaran tunai dan kembalian tepat.
- Receipt dapat dicetak kembali.

### Integrity

- Total server mengabaikan total palsu dari client.
- Harga server mengabaikan harga palsu dari client.
- Stok berkurang sesuai base quantity.
- Sale, item, payment, movement, dan balance dibuat atomik.
- Exception pada tengah action membuat seluruh transaksi rollback.
- Idempotency key sama tidak menggandakan penjualan.
- Key sama dengan payload berbeda ditolak.

### Concurrency

- Dua kasir menjual stok terakhir secara bersamaan: hanya transaksi yang valid berhasil jika stok negatif dilarang.
- Lock diambil dengan urutan konsisten untuk banyak produk.
- Retry setelah timeout mengembalikan hasil awal.
- Deadlock retry tidak menghasilkan duplikat.

### Void

- Hanya role berizin dapat void.
- Alasan wajib.
- Stock movement pembalik tepat.
- Void kedua ditolak.
- Laporan bersih dan kontrol void sesuai definisi.

## TEST-RECEIPT-AND-PAYABLE

- Receipt lunas menambah stok tanpa saldo payable terbuka.
- Receipt belum dibayar membuat payable penuh.
- Receipt sebagian membuat pembayaran awal dan saldo tepat.
- Nilai payment lebih besar daripada total ditolak.
- Due date rule diuji.
- File gagal tidak membuat transaksi posted sebagian.
- Payment bertahap mengubah Open ke Partial lalu Paid.
- Overpayment dan payment pada payable lunas ditolak.
- Void atau reversal payment menjaga audit dan saldo.

## TEST-INVENTORY

- Setiap movement memiliki reference dan balance after yang konsisten.
- Adjustment positif dan negatif tepat.
- Penyesuaian tanpa alasan ditolak.
- Ledger read-only melalui UI dan action.
- Reconciliation mendeteksi snapshot balance yang sengaja diubah.
- Job rekonsiliasi tidak memperbaiki data diam-diam.
- Boundary decimal dan rounding diuji.

## TEST-DOCUMENT

- JPEG, PNG, PDF valid diterima sesuai batas.
- File executable dengan ekstensi gambar ditolak.
- MIME palsu, double extension, oversized, zero-byte, nama path traversal, dan terlalu banyak file ditolak.
- File private tidak dapat diakses langsung.
- Kasir tidak dapat mengunduh dokumen yang tidak diizinkan.
- Header download aman.
- Draft orphan cleanup tidak menghapus file yang sudah terhubung.
- Checksum disimpan dan diverifikasi.

## TEST-REPORTING

- Fixture manual memastikan nilai agregat.
- Batas waktu 00:00 dan akhir bulan pada `Asia/Jakarta`.
- Draft, posted, void, dan reversal masuk atau keluar dengan benar.
- Pembayaran sebagian dan aging bucket tepat.
- Filter layar sama dengan hasil CSV atau PDF.
- CSV formula injection di-escape.
- Ekspor pengguna A tidak dapat diunduh pengguna B.
- Data kosong tetap menghasilkan laporan yang valid.

## TEST-UX-ACCESSIBILITY

- Keyboard-only untuk login, POS, receipt, payment, dan dialog.
- Focus order dan focus return.
- Label, error association, live region, dan dialog semantics.
- Kontras token dan semua state.
- Zoom 200 persen.
- Reduced motion.
- Target sentuh pada mobile.
- Loading tidak menyebabkan layout shift besar.
- Empty, filtered-empty, offline, timeout, forbidden, dan session-expired.
- Bahasa dan istilah diverifikasi bersama mitra.

Automated accessibility scan membantu tetapi tidak menggantikan pemeriksaan keyboard dan screen reader manual.

## Browser Matrix

Rilis awal menargetkan versi stabil terbaru Chrome dan Edge pada Windows serta Chrome Android jika mobile dipakai. Perangkat toko aktual wajib diuji. Browser lama hanya didukung jika teridentifikasi pada `OPEN-11`.

## Performance Test

Dataset representatif awal:

- 5.000 produk.
- 100.000 penjualan.
- 500.000 sale items.
- 1.000.000 stock movements.
- 10.000 penerimaan dan pembayaran.

Angka ini merupakan stress dataset desain, bukan klaim volume toko. Target:

- Search POS p95 di bawah 300 ms pada jaringan deployment yang disetujui.
- Posting penjualan p95 di bawah 2 detik tanpa cetak.
- Daftar umum p95 di bawah 1 detik untuk filter default.
- Dashboard p95 di bawah 2 detik.
- Query bebas N+1 dan menggunakan pagination.

Target disesuaikan setelah baseline server dan jaringan nyata diketahui.

## Security Test

- Authorization matrix untuk setiap route dan action.
- CSRF dan origin failure.
- Session fixation dan timeout.
- XSS pada nama produk, pemasok, catatan, filename, dan search.
- SQL injection probes pada filter dan sort.
- IDOR pada sale, payable, document, export, dan audit.
- Rate limit login dan search.
- Upload abuse.
- Security headers dan HTTPS.
- Secret scanning dan dependency audit.
- Error page tidak membocorkan stack trace.

## UAT Black Box

UAT dilakukan dengan pemilik dan minimal satu karyawan representatif menggunakan data latihan.

| ID | Skenario | Hasil yang diharapkan |
|---|---|---|
| UAT-01 | Pemilik masuk | Dashboard pemilik tampil. |
| UAT-02 | Kasir menjual eceran | Struk dan stok tercatat benar. |
| UAT-03 | Kasir menjual satuan 0,25 kg | Harga dan stok dasar tepat. |
| UAT-04 | Harga grosir berlaku | Label dan total dapat dipahami. |
| UAT-05 | Barang masuk belum lunas | Stok bertambah dan kewajiban dibuat. |
| UAT-06 | Catat pembayaran sebagian | Sisa dan status tepat. |
| UAT-07 | Unggah dan cari bukti | Bukti ditemukan dari transaksi. |
| UAT-08 | Stok fisik berbeda | Adjustment dan alasan tercatat. |
| UAT-09 | Pemilik melihat laporan | Nilai dapat ditelusuri ke transaksi. |
| UAT-10 | Kasir membuka audit | Akses ditolak dengan aman. |

Pengamat mencatat waktu penyelesaian, kesalahan, bantuan yang diminta, istilah membingungkan, dan komentar pengguna. Temuan kritis diperbaiki lalu diuji ulang.

## CI Quality Gate

Setiap perubahan harus lulus:

1. Format dan lint PHP.
2. Static analysis pada level yang disetujui.
3. Unit, feature, dan Livewire tests.
4. MySQL integration tests untuk transaksi kritis.
5. Frontend build.
6. Dependency audit.
7. Browser smoke test pada branch release.
8. Migration test dari snapshot versi production sebelumnya.

## Traceability Matrix Minimum

Setiap `FR` memiliki minimal satu test. `FR-SALE`, `FR-STOCK`, `FR-PAY`, dan semua `SEC` kritis memiliki happy path, invalid path, unauthorized path, concurrency atau replay path bila relevan, serta audit assertion.

## Exit Criteria

- Tidak ada defect severity critical atau high yang terbuka.
- Semua acceptance criteria Must lulus.
- UAT kritis ditandatangani pemilik.
- Backup restore test lulus.
- Security checklist production lulus.
- Performance target kritis tercapai pada environment mendekati production.
- Known limitation didokumentasikan dan diterima.

## Sumber Teknis

- [Laravel Testing](https://laravel.com/docs/13.x/testing)
- [Laravel Database Testing](https://laravel.com/docs/13.x/database-testing)
- [Livewire 4 Testing](https://livewire.laravel.com/docs/4.x/testing)
