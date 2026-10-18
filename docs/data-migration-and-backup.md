# Data Migration and Backup

## Kondisi Awal

Data transaksi, stok, dan kewajiban saat ini dicatat manual di Excel. Bukti dikirim melalui WhatsApp dan disimpan di galeri. Migrasi harus menghindari pemindahan data yang tidak terverifikasi ke dalam sistem seolah-olah benar.

## Tujuan Migrasi

- Membuat master produk, kategori, satuan, harga, dan pemasok yang bersih.
- Menetapkan saldo stok awal yang dapat dipertanggungjawabkan.
- Menetapkan kewajiban awal yang masih terbuka.
- Menghubungkan bukti relevan tanpa bergantung pada nama file acak.
- Menyimpan laporan hasil impor, error, dan persetujuan pemilik.

## Ruang Lingkup Data Awal

| Data | Rekomendasi |
|---|---|
| Produk aktif | Impor setelah deduplikasi dan normalisasi. |
| Kategori dan satuan | Dibersihkan sebelum produk. |
| Harga eceran dan grosir | Impor setelah aturan harga disetujui. |
| Pemasok aktif | Impor data minimum yang masih relevan. |
| Saldo stok | Berdasarkan hitung fisik pada cutover, bukan hanya nilai Excel. |
| Kewajiban terbuka | Impor per pemasok dan bukti sumber. |
| Histori penjualan lama | Default tidak diimpor sebagai transaksi rinci; arsip read-only terpisah bila dibutuhkan. |
| Bukti lama | Hanya bukti yang mendukung kewajiban terbuka atau transaksi penting. |

Keputusan histori lengkap adalah `OPEN-13` karena dapat memperbesar biaya pembersihan tanpa membantu operasional awal.

## Template Impor

### Produk

Kolom: `sku`, `nama_produk`, `kategori`, `satuan_dasar`, `barcode`, `batas_stok_rendah`, `aktif`.

### Satuan dan Harga

Kolom: `sku`, `label_satuan`, `faktor_ke_dasar`, `harga_eceran`, `harga_grosir`, `minimum_grosir`, `barcode_satuan`, `aktif`.

### Pemasok

Kolom: `kode_pemasok`, `nama`, `telepon`, `alamat`, `catatan`, `aktif`.

### Stok Awal

Kolom: `sku`, `stok_fisik_dalam_satuan_dasar`, `tanggal_hitung`, `dihitung_oleh`, `catatan`.

### Kewajiban Awal

Kolom: `kode_pemasok`, `referensi`, `tanggal_transaksi`, `jatuh_tempo`, `nilai_awal`, `sudah_dibayar`, `sisa`, `nama_file_bukti`, `catatan`.

Template disertai sheet petunjuk, contoh yang jelas ditandai, kamus nilai, dan validasi sederhana. Contoh tidak ikut diimpor.

## Pipeline Migrasi

1. **Inventory sumber:** Daftar file Excel, pemilik data, periode, sheet, format, dan bukti.
2. **Salinan read-only:** Buat checksum dan salinan kerja. File sumber tidak diubah.
3. **Profiling:** Hitung baris, duplikat, field kosong, SKU atau barcode konflik, format uang, serta satuan tidak dikenal.
4. **Mapping:** Petakan kolom sumber ke template canonical.
5. **Staging import:** Semua baris masuk staging table dengan nomor baris dan source file, belum menjadi data produksi.
6. **Validation report:** Hasil `valid`, `warning`, atau `error` per baris.
7. **Cleansing:** Perbaikan dilakukan di file kerja dan memiliki catatan keputusan.
8. **Dry run:** Import ke environment staging dan hasilkan rekonsiliasi.
9. **Business review:** Pemilik meninjau total produk, stok, kewajiban, dan sampel bukti.
10. **Cutover count:** Lakukan hitung stok fisik sedekat mungkin dengan go-live.
11. **Production import:** Jalankan command idempotent dengan batch ID.
12. **Reconciliation:** Cocokkan jumlah dan nilai, lalu tandatangani hasil.
13. **Archive:** Simpan source, mapping, error report, dan hasil final sesuai kebijakan retensi.

## Validasi Migrasi

- SKU unik dan tidak kosong.
- Barcode unik jika tersedia.
- Nama produk tidak hanya berbeda spasi atau kapitalisasi.
- Unit diketahui dan faktor positif.
- Harga integer rupiah, tidak negatif.
- Stok decimal valid sesuai presisi.
- Sisa kewajiban sama dengan nilai awal dikurangi pembayaran.
- Due date valid atau memiliki catatan jika tidak ada.
- Pemasok referensi tersedia.
- Bukti file ada, checksum dapat dihitung, dan tipe diperbolehkan.

Baris error tidak diimpor sebagian. Warning membutuhkan persetujuan atau rule eksplisit.

## Opening Balance

Saldo awal tidak ditulis langsung ke `stock_balances` tanpa ledger. Sistem membuat satu batch `Opening Inventory` dan stock movement per produk dengan referensi batch, actor, waktu, sumber hitung, serta catatan. Setelah itu snapshot balance harus sama dengan movement sum.

Kewajiban awal dibuat dengan tipe `Opening Payable` dan ditandai sebagai data migrasi. Pembayaran historis dapat diringkas menjadi nilai sudah dibayar jika bukti rinci tidak tersedia, dengan persetujuan pemilik.

## Bukti Lama

Prosedur:

1. Pilih bukti yang masih relevan.
2. Salin dari perangkat tanpa menghapus sumber.
3. Kelompokkan menurut pemasok atau referensi.
4. Hitung checksum dan deteksi duplikat.
5. Validasi tipe file.
6. Ubah nama display secara terstruktur tanpa mengubah bukti asli.
7. Upload private dan hubungkan ke opening payable atau transaksi.
8. Ambil sampel dan cocokkan dengan pemilik.

File tanpa referensi disimpan pada quarantine review, bukan langsung ditempelkan secara tebakan.

## Cutover Plan

- Tentukan waktu penghentian pencatatan Excel.
- Backup semua data dan file sebelum cutover.
- Catat transaksi selama freeze dalam lembar cutover terkontrol jika toko tetap buka.
- Import master data, opening stock, opening payable, dan bukti.
- Jalankan rekonsiliasi.
- Masukkan transaksi selama freeze secara berurutan.
- Pemilik melakukan smoke UAT.
- Umumkan system of record baru.
- Excel lama menjadi read-only dan tidak lagi digunakan untuk transaksi baru.

Rollback cutover berarti kembali sementara ke prosedur manual dengan daftar transaksi yang sudah masuk, bukan menghapus data production secara improvisasi.

## Backup Strategy

Target awal memakai prinsip 3-2-1:

- Minimal tiga salinan.
- Dua jenis media atau lokasi berbeda.
- Satu salinan di luar server utama.

### Jadwal rancangan

| Jenis | Jadwal awal | Retensi awal |
|---|---|---|
| Database logical backup | Setiap malam | 14 harian, 8 mingguan, 6 bulanan |
| File private incremental | Setiap malam | Selaras dengan database |
| Full system configuration | Setelah perubahan deployment | 10 versi |
| Pre-deployment snapshot | Sebelum release | Sampai release stabil dan backup berikutnya teruji |

Nilai final bergantung topology dan kapasitas yang dipilih.

## Konsistensi Backup Database dan File

Backup memiliki manifest berisi waktu, versi aplikasi, schema version, database checksum, jumlah document record, daftar atau checksum file, serta hasil verifikasi. Backup database dan file harus dapat dipasangkan. File orphan dan metadata tanpa file dilaporkan.

## Encryption dan Akses

- Backup dienkripsi sebelum meninggalkan server atau menggunakan storage encryption yang dikelola baik.
- Kunci tidak disimpan hanya bersama backup.
- Credential backup memiliki akses minimal.
- Download backup dicatat dan dibatasi operator.
- Data production tidak disalin ke laptop pengembangan tanpa sanitasi dan izin.

## Restore Procedure

1. Deklarasikan tujuan restore dan restore point.
2. Siapkan environment terisolasi.
3. Verifikasi checksum dan manifest.
4. Pulihkan database.
5. Pulihkan file private yang cocok.
6. Jalankan migration hanya jika prosedur versi mengharuskan.
7. Jalankan integrity checks dan rekonsiliasi.
8. Uji login, produk, penjualan sampel, bukti, laporan, queue, dan scheduler.
9. Dokumentasikan durasi, kehilangan data, error, serta persetujuan.
10. Untuk production restore, alihkan traffic hanya setelah owner dan operator menyetujui.

## Target Recovery

Rancangan awal mengusulkan:

- RPO maksimal 24 jam untuk backup harian, lebih kecil bila binary log atau backup lebih sering disediakan.
- RTO 4 jam untuk deployment cloud kecil dan 8 jam untuk perangkat lokal, menunggu pilihan topology dan dukungan.

Target harus disetujui pada `OPEN-15`. Backup yang belum pernah direstore tidak dianggap terverifikasi.

