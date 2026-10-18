# Reporting and Audit

## Prinsip Pelaporan

1. Setiap angka memiliki definisi, sumber, filter, dan aturan status.
2. Nilai agregat dapat ditelusuri ke transaksi sumber.
3. Laporan memakai waktu bisnis `Asia/Jakarta` meskipun timestamp disimpan UTC.
4. Transaksi draft tidak masuk laporan operasional posted.
5. Transaksi void dikeluarkan dari nilai bersih tetapi tetap dapat dilihat pada laporan kontrol.
6. Tidak ada klaim laba bersih atau laporan akuntansi lengkap pada MVP.

## REP-01 Ringkasan Penjualan

**Metrik:**

- Penjualan kotor: total transaksi posted sebelum void pada periode.
- Nilai void: total transaksi yang dibatalkan sesuai waktu kebijakan laporan.
- Penjualan bersih: posted valid dikurangi nilai void yang berlaku.
- Jumlah transaksi: hitungan penjualan posted nonvoid.
- Rata-rata transaksi: penjualan bersih dibagi jumlah transaksi nonvoid.
- Kuantitas item terjual: total base quantity dari item nonvoid.

**Dimensi:** Tanggal, jam, kasir, kategori, produk, satuan jual, jenis harga, dan metode pembayaran.

**Catatan:** Kebijakan atribusi void ke tanggal penjualan atau tanggal void perlu dipilih. Rancangan default menyajikan keduanya secara terpisah agar perubahan kas dapat dijelaskan.

## REP-02 Item Terjual

Kolom: produk snapshot, SKU, satuan, quantity jual, base quantity, nilai, jumlah transaksi, dan proporsi eceran atau grosir. Ranking hanya muncul bila filter dan periode jelas.

## REP-03 Posisi Stok

**Sumber:** `stock_balances` dengan tautan rekonsiliasi ke `stock_movements`.

Kolom: produk, kategori, base unit, quantity on hand, threshold, status rendah, movement terakhir, dan status rekonsiliasi.

Nilai persediaan rupiah tidak ditampilkan sebagai angka resmi sebelum metode valuasi seperti FIFO atau average cost disetujui.

## REP-04 Pergerakan Stok

Menampilkan opening quantity, masuk, keluar, adjustment net, dan closing quantity untuk periode. Rumus:

```text
closing = opening + receipt + sale reversal + positive adjustment
          - sale - receipt reversal - negative adjustment
```

Setiap kelompok dapat di-drill down ke movement individual dan transaksi referensi.

## REP-05 Penerimaan Barang

Metrik: jumlah penerimaan posted, total nilai penerimaan, jumlah item, nilai per pemasok, serta transaksi tanpa bukti. Nilai ini adalah data pembelian atau penerimaan operasional, bukan beban akuntansi.

## REP-06 Kewajiban Pemasok

**Metrik:** principal, pembayaran valid, saldo terbuka, saldo jatuh tempo, dan jumlah transaksi.

**Aging bucket rancangan:** Belum jatuh tempo, 1 sampai 7 hari lewat, 8 sampai 30 hari, dan lebih dari 30 hari. Bucket dapat diubah setelah kebiasaan pembayaran toko dipahami.

Saldo dihitung sebagai principal dikurangi payment posted nonvoid. Cached balance harus cocok dengan hasil hitung.

## REP-07 Aktivitas Kontrol

Mencakup:

- Penjualan void.
- Pembayaran void atau pembalik.
- Penyesuaian stok.
- Perubahan harga.
- Perubahan unit conversion.
- Perubahan role atau status user.
- Ekspor data.
- Download bukti sensitif bila logging diaktifkan.

Laporan ini ditujukan kepada pemilik, bukan kasir umum.

## Filter Standar

- Preset hari ini, kemarin, 7 hari, bulan berjalan, dan rentang khusus.
- Range menggunakan batas awal inklusif dan batas akhir eksklusif pada query timestamp.
- Pemasok, produk, kategori, kasir, status, dan metode hanya tersedia pada laporan yang relevan.
- Filter aktif ditampilkan pada layar dan dicetak di ekspor.
- Default range dibatasi untuk mencegah full-table scan tidak sengaja.

## Konsistensi Layar dan Ekspor

Layar dan ekspor menggunakan query object atau read model yang sama. Ekspor tidak menulis ulang rumus secara terpisah. Metadata ekspor mencakup:

- Nama laporan.
- Periode dan zona waktu.
- Filter.
- Waktu dibuat.
- Pengguna pembuat.
- Versi definisi laporan.
- Status data rekonsiliasi.

## Audit Trail

### Event Minimum

| Event | Data yang dicatat |
|---|---|
| `auth.login.succeeded` | Actor, waktu, request ID, IP sesuai kebijakan. |
| `auth.login.failed` | Login identifier teredaksi, waktu, request ID. |
| `sale.posted` | Sale, actor, total, idempotency reference. |
| `sale.voided` | Sale, actor, alasan, movement reversal. |
| `receipt.posted` | Receipt, actor, supplier, total. |
| `payable.payment_posted` | Payable, actor, amount. |
| `stock.adjusted` | Product, actor, before, delta, after, reason. |
| `catalog.price_changed` | Product unit, before, after, actor. |
| `catalog.conversion_changed` | Unit, before, after, actor. |
| `user.role_changed` | Target, role before dan after, actor. |
| `document.downloaded` | Document, actor, related aggregate jika kebijakan aktif. |
| `report.exported` | Report, filters hash, actor, file reference. |

### Redaction

Audit tidak boleh menyimpan:

- Kata sandi atau hash kata sandi.
- Session ID, CSRF token, remember token, atau API secret.
- Isi binary dokumen.
- Full request body tanpa allowlist.
- Data file environment.

`before_json` dan `after_json` dibentuk dari allowlist field per aggregate.

### Immutability dan Akses

- Tidak ada UI edit atau delete audit log.
- Database user aplikasi tidak diberi fitur admin untuk mass delete melalui aplikasi.
- Retensi ditentukan sebelum production dan mempertimbangkan kapasitas serta kebutuhan pemeriksaan.
- Audit log tidak menggantikan application log. Keduanya memiliki fungsi berbeda.

## Rekonsiliasi Harian

Scheduler melakukan pemeriksaan read-only:

1. Jumlah movement per product sama dengan stock balance.
2. Sale posted memiliki item, pembayaran, dan movement keluar yang sesuai.
3. Sale void memiliki movement pembalik yang sesuai.
4. Goods receipt posted memiliki item dan movement masuk.
5. Payable balance sama dengan principal dikurangi payment valid.
6. Document metadata memiliki file dan file private memiliki metadata.

Hasil berupa `pass`, `warning`, atau `fail`. Sistem tidak mengubah data otomatis ketika fail. Pemilik atau operator teknis menerima alert dan menjalankan prosedur investigasi.

## Anti Manipulasi Laporan

- Filter dan authorization dijalankan server-side.
- Query hanya memasukkan status yang didefinisikan.
- Formula spreadsheet di-escape.
- Export memiliki checksum dan masa berlaku bila diperlukan.
- Nilai historis memakai item snapshot.
- Void dan correction selalu menyimpan referensi pembalik.
- Perubahan definisi laporan diberi versi dan release note.

## Uji Penerimaan Laporan

Setiap laporan memiliki fixture kecil yang dapat dihitung manual. Uji mencakup batas tengah malam, awal dan akhir bulan, transaksi void, pembayaran sebagian, produk dengan satuan pecahan, pengguna tanpa izin, data kosong, filter gabungan, dan kesamaan layar dengan ekspor.

