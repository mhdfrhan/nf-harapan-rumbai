# Information Architecture

## Tujuan

Struktur informasi dibangun berdasarkan tugas pengguna, bukan struktur tabel basis data. Pengguna awam harus dapat menjawab tiga pertanyaan pada setiap layar: sedang berada di mana, apa yang dapat dilakukan, dan apa dampak tindakannya.

## Navigasi Utama

### Pemilik

1. Ringkasan
2. Kasir
3. Penjualan
4. Barang dan Stok
5. Pemasok dan Kewajiban
6. Laporan
7. Pengaturan

### Karyawan Kasir

1. Ringkasan Shift
2. Kasir
3. Riwayat Penjualan Saya

### Petugas Persediaan Opsional

1. Ringkasan Stok
2. Barang dan Stok
3. Pemasok dan Penerimaan

Menu yang tidak diizinkan tidak ditampilkan, tetapi server tetap melakukan pemeriksaan izin pada setiap request.

## Sitemap dan Rute Rancangan

| Grup | Rute | Nama rute | Tujuan |
|---|---|---|---|
| Autentikasi | `/login` | `login` | Masuk ke sistem. |
| Dashboard | `/dashboard` | `dashboard` | Ringkasan sesuai peran. |
| POS | `/pos` | `pos.index` | Pencarian produk dan keranjang aktif. |
| POS | `/pos/held` | `pos.held` | Keranjang tertahan, jika fitur diaktifkan. |
| Penjualan | `/sales` | `sales.index` | Daftar dan filter penjualan. |
| Penjualan | `/sales/{sale}` | `sales.show` | Detail, cetak, dan void berizin. |
| Barang | `/catalog/products` | `products.index` | Daftar produk. |
| Barang | `/catalog/products/create` | `products.create` | Produk baru. |
| Barang | `/catalog/products/{product}/edit` | `products.edit` | Identitas, satuan, dan harga produk. |
| Barang | `/catalog/categories` | `categories.index` | Kategori. |
| Barang | `/catalog/units` | `units.index` | Kamus satuan. |
| Stok | `/inventory` | `inventory.index` | Stok saat ini dan indikator rendah. |
| Stok | `/inventory/movements` | `stock-movements.index` | Buku besar pergerakan stok. |
| Stok | `/inventory/adjustments/create` | `adjustments.create` | Penyesuaian stok. |
| Penerimaan | `/inventory/receipts` | `receipts.index` | Daftar penerimaan barang. |
| Penerimaan | `/inventory/receipts/create` | `receipts.create` | Draft penerimaan. |
| Penerimaan | `/inventory/receipts/{receipt}` | `receipts.show` | Detail dan bukti penerimaan. |
| Pemasok | `/suppliers` | `suppliers.index` | Daftar pemasok. |
| Pemasok | `/suppliers/{supplier}` | `suppliers.show` | Profil dan riwayat pemasok. |
| Kewajiban | `/payables` | `payables.index` | Daftar utang dan jatuh tempo. |
| Kewajiban | `/payables/{payable}` | `payables.show` | Detail dan pembayaran. |
| Laporan | `/reports/sales` | `reports.sales` | Laporan penjualan. |
| Laporan | `/reports/inventory` | `reports.inventory` | Posisi dan pergerakan stok. |
| Laporan | `/reports/purchases` | `reports.purchases` | Penerimaan dan pembelian. |
| Laporan | `/reports/payables` | `reports.payables` | Kewajiban pemasok. |
| Pengguna | `/admin/users` | `users.index` | Akun dan peran. |
| Audit | `/admin/audit` | `audit.index` | Jejak aktivitas sensitif. |
| Pengaturan | `/settings/store` | `settings.store` | Identitas toko dan preferensi. |
| Profil | `/profile` | `profile.edit` | Profil dan kata sandi pengguna aktif. |

Rute final mengikuti route model binding Laravel dan kebijakan otorisasi. URL tidak menjadi kontrol akses.

## Pengelompokan Barang dan Stok

Subnavigasi `Barang dan Stok` menggunakan tab atau secondary navigation yang konsisten:

- Produk
- Kategori
- Stok saat ini
- Pergerakan stok
- Penerimaan barang
- Penyesuaian

Satuan diletakkan di pengaturan katalog karena jarang berubah. Pengguna tidak perlu memahami tabel `product_units` untuk mengelola produk.

## Hierarki Halaman

Setiap halaman mengikuti urutan:

1. Breadcrumb, kecuali dashboard dan POS.
2. Judul yang menyebut objek atau tugas.
3. Deskripsi satu kalimat bila konsekuensi belum jelas.
4. Primary action di kanan pada desktop dan sticky action area pada mobile jika relevan.
5. Filter dan pencarian.
6. Konten utama.
7. Metadata, aktivitas, atau bantuan kontekstual.

## Pencarian

| Konteks | Field yang dicari | Perilaku |
|---|---|---|
| POS | Nama, SKU, barcode | Debounce singkat untuk nama, langsung untuk barcode, hasil prioritas produk aktif. |
| Produk | Nama, SKU, barcode | Server-side, mempertahankan filter pada URL. |
| Penjualan | Nomor transaksi | Exact dan partial match dengan batas rentang tanggal. |
| Pemasok | Nama, telepon | Server-side. |
| Kewajiban | Nomor penerimaan, pemasok | Dikombinasikan dengan status dan jatuh tempo. |
| Audit | Aktor, objek, event | Hanya pemilik dan dibatasi rentang waktu. |

## Filter dan State URL

- Filter daftar ditulis pada query string agar dapat dibagikan, di-refresh, dan kembali melalui browser tanpa hilang.
- Nilai default tidak perlu memenuhi URL.
- Tombol `Reset filter` muncul hanya jika filter aktif.
- Tabel menampilkan chip ringkas untuk filter aktif dan jumlah hasil.
- Pagination dilakukan server-side.
- Urutan kolom tidak berubah tanpa persetujuan desain karena konsistensi membantu pengguna awam.

## Breadcrumb

Contoh:

`Barang dan Stok / Produk / Gula Pasir 1 kg / Ubah`

Breadcrumb maksimal empat level. Jika struktur lebih dalam, bagian tengah diringkas secara visual tetapi nama halaman induk tetap tersedia bagi screen reader.

## Penamaan Menu dan Copy

Gunakan istilah nyata toko:

- `Barang masuk`, bukan `Inbound inventory`.
- `Kewajiban pemasok`, bukan `Accounts payable` pada UI utama.
- `Batalkan transaksi`, dengan label status `Void` sebagai keterangan sekunder.
- `Stok tersedia`, bukan `On hand balance`.
- `Bukti pembayaran`, bukan `Attachment`.

Istilah teknis tetap dipakai dalam dokumentasi dan kode bila meningkatkan ketepatan.

## Responsive Information Architecture

### Desktop, lebar minimal 1280 px

- Sidebar tetap dengan label ikon.
- Header ringkas untuk pencarian kontekstual, profil, dan status koneksi.
- POS menggunakan panel produk dan keranjang berdampingan.

### Tablet, 768 sampai 1279 px

- Sidebar dapat diciutkan tetapi label tersedia saat dibuka.
- POS tetap dua panel jika ruang cukup; keranjang dapat menjadi panel kanan yang dapat diperbesar.
- Tabel mengizinkan scroll horizontal dengan kolom identitas sticky.

### Mobile, di bawah 768 px

- Navigasi utama melalui drawer, bukan memadatkan semua menu ke bottom bar.
- Tindakan utama sticky di bagian bawah.
- POS menggunakan alur produk lalu keranjang, dengan jumlah item selalu terlihat.
- Tabel kompleks beralih menjadi daftar ringkas dengan detail drill-down, bukan menyembunyikan data kritis.

## Keadaan Global

Semua halaman menyediakan:

- Nama toko dan environment indicator hanya pada nonproduction.
- Identitas pengguna dan aksi keluar.
- Status koneksi bila request gagal atau browser offline.
- Halaman 403 yang menjelaskan akses tidak tersedia.
- Halaman 404 dengan tindakan kembali yang aman.
- Halaman 419/session expired yang tidak menyebabkan submit ulang.
- Halaman 500 dengan ID insiden tanpa stack trace.

## Batasan Arsitektur Informasi

- Tidak ada dashboard dengan banyak kartu dekoratif yang tidak dapat ditindaklanjuti.
- Tidak ada submenu lebih dari dua tingkat di sidebar.
- Tidak ada ikon tanpa label untuk navigasi primer.
- Tidak ada menu berdasarkan nama tabel teknis.
- Tidak ada tindakan destruktif di dalam menu tiga titik tanpa label dan konfirmasi dampak.

