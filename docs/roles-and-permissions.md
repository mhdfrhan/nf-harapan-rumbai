# Roles and Permissions

## Tujuan

Dokumen ini menetapkan akses minimum bagi setiap pengguna. Otorisasi wajib diperiksa pada server melalui Laravel policies, gates, middleware, atau Form Request authorization. Menyembunyikan tombol bukan kontrol keamanan.

## Peran Rancangan

### Owner

Peran pemilik dan penanggung jawab. Memiliki kewenangan bisnis tertinggi, tetapi tetap tidak dapat mengubah transaksi posted tanpa alur koreksi.

### Inventory Admin

Peran opsional untuk karyawan yang dipercaya menangani produk, barang masuk, pemasok, stok, dan kewajiban. Jika tidak disetujui, seluruh tugas peran ini dijalankan Owner.

### Cashier

Peran karyawan yang fokus pada transaksi penjualan dan riwayat penjualan terbatas.

### Auditor Read Only

Peran opsional untuk pemeriksaan data tanpa izin menambah atau mengubah. Tidak termasuk MVP kecuali diminta.

## Matriks Izin

Legenda: `A` penuh, `O` operasional terbatas, `R` baca saja, `-` tidak memiliki akses.

| Fitur | Owner | Inventory Admin | Cashier | Auditor |
|---|:---:|:---:|:---:|:---:|
| Dashboard bisnis lengkap | A | O | O | R |
| User management | A | - | - | - |
| Role dan permission | A | - | - | - |
| Produk dan kategori | A | A | R | R |
| Satuan dan konversi | A | A | R | R |
| Harga eceran | A | A | R | R |
| Harga grosir | A | A | R | R |
| POS penjualan | A | O | A | - |
| Diskon manual | A | O sesuai batas | - secara default | - |
| Void penjualan | A | - atau approval | Permintaan saja | R |
| Penerimaan barang | A | A | - | R |
| Posting penerimaan | A | A | - | R |
| Penyesuaian stok | A | Permintaan atau batas | - | R |
| Pemasok | A | A | - | R |
| Kewajiban | A | A | - | R |
| Posting pembayaran | A | O jika diberi izin | - | R |
| Upload bukti | A | A pada modulnya | O pada transaksi sendiri jika diperlukan | R |
| Laporan penjualan lengkap | A | R | Riwayat terbatas | R |
| Laporan stok | A | A | Ringkas jika diperlukan | R |
| Laporan kewajiban | A | A | - | R |
| Audit log | A | - | - | R |
| Pengaturan toko | A | - | - | R |
| Backup dan restore | A melalui prosedur | - | - | - |

## Permission Keys

Implementasi disarankan menggunakan permission key eksplisit, terlepas dari penyimpanan role dilakukan dengan tabel custom atau enum.

```text
dashboard.view_owner
dashboard.view_operational
users.view
users.create
users.update
users.disable
roles.manage
products.view
products.create
products.update
products.disable
product_units.manage
prices.manage_retail
prices.manage_wholesale
sales.create
sales.view_own
sales.view_all
sales.discount
sales.void
purchases.view
purchases.create
purchases.post
stock.view
stock.adjust
suppliers.manage
payables.view
payables.manage
payable_payments.post
documents.upload
documents.view
reports.sales
reports.stock
reports.payables
reports.export
audit.view
settings.manage
```

## Kebijakan Scope Data

### Cashier

- Dapat membuat penjualan.
- Dapat melihat transaksi sendiri pada hari berjalan secara default.
- Tidak dapat melihat harga beli, nilai kewajiban, atau laporan pemilik.
- Tidak dapat mengubah produk, faktor konversi, atau harga.
- Tidak dapat void secara langsung. Kasir mengajukan koreksi kepada pemilik.
- Tidak dapat melihat bukti kewajiban pemasok.

### Inventory Admin

- Dapat melihat harga beli dan penerimaan.
- Dapat mengelola pemasok dan kewajiban bila izin diberikan.
- Tidak dapat mengelola akun Owner.
- Tidak dapat menghapus audit log.
- Penyesuaian stok di atas batas yang disepakati membutuhkan Owner.

### Owner

- Dapat mengakses seluruh data bisnis.
- Tidak dapat menghapus transaksi posted secara hard delete melalui UI.
- Perubahan sensitif Owner tetap dicatat dalam audit log.
- Restore backup dilakukan melalui runbook, bukan tombol biasa di aplikasi.

## Pemisahan Tugas

| Tindakan | Pelaksana | Persetujuan |
|---|---|---|
| Penjualan normal | Cashier | Tidak perlu. |
| Diskon manual | Owner atau pengguna dengan izin | Batas nominal atau persentase ditentukan. |
| Void penjualan | Owner | Alasan wajib. |
| Penerimaan barang | Inventory Admin atau Owner | Posting oleh pengguna berizin. |
| Penyesuaian stok kecil | Inventory Admin jika disetujui | Alasan wajib. |
| Penyesuaian stok besar | Owner | Konfirmasi ulang dan alasan wajib. |
| Pembayaran kewajiban | Owner atau Inventory Admin berizin | Bukti wajib sesuai kebijakan. |
| Perubahan konversi satuan terpakai | Owner melalui prosedur migrasi | Tidak boleh diedit langsung. |
| Menonaktifkan pengguna | Owner | Audit wajib. |

## Aturan Otorisasi

1. Setiap model sensitif memiliki Policy.
2. Query daftar menerapkan scope izin, bukan mengambil semua data lalu menyembunyikannya di UI.
3. Livewire public properties dianggap input tidak tepercaya.
4. Setiap action melakukan authorize ulang tepat sebelum mutation.
5. Download bukti menggunakan signed route atau controller terotorisasi.
6. Route admin dikelompokkan dengan middleware `auth`, status aktif, dan permission.
7. Owner tidak boleh menonaktifkan akun Owner terakhir tanpa proses transfer yang aman.
8. Pengguna tidak dapat mengubah role sendiri.
9. Perubahan password pengguna lain memaksa invalidasi sesi aktif pengguna tersebut.
10. Data audit tidak memiliki aksi edit atau delete di aplikasi.

## Keputusan yang Masih Terbuka

- Apakah MVP menggunakan dua peran saja, Owner dan Cashier, atau tiga peran dengan Inventory Admin.
- Apakah kasir boleh melihat seluruh transaksi hari berjalan atau hanya transaksi sendiri.
- Apakah Inventory Admin boleh memposting pembayaran kewajiban.
- Batas penyesuaian stok dan diskon yang membutuhkan Owner.
- Apakah akun menggunakan username, email, atau keduanya.

