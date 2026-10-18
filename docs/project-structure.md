# Struktur Proyek

## Boundary Utama

```text
app/
  Actions/        Use case yang mengubah state bisnis.
  Domain/         Enum, value object, dan aturan domain murni.
  Events/         Event setelah transaksi berhasil di-commit.
  Http/           Controller dan middleware boundary HTTP.
  Jobs/           Pekerjaan asynchronous dan scheduled.
  Livewire/       Page, form object, dan component UI.
  Models/         Model Eloquent dan relasi persistence.
  Policies/       Otorisasi berbasis model dan tindakan.
  Queries/        Read model dan query laporan.
  Rules/          Aturan validasi reusable.
  Services/       Integrasi dokumen, penomoran, dan layanan teknis.
  Support/        Utility terkontrol tanpa aturan domain utama.

routes/modules/   Route per boundary bisnis.
resources/views/  Layout, component, dan Livewire single-file component.
tests/Feature/    Pengujian use case, policy, database, dan UI boundary.
tests/Unit/       Pengujian domain murni.
```

## Modul

| Modul | Cakupan |
|---|---|
| Identity | Pengguna, peran, izin, dan session. |
| Catalog | Produk, kategori, satuan, konversi, dan harga. |
| Sales | POS, penjualan, pembayaran, struk, dan void. |
| Inventory | Stock movement, saldo, stok rendah, dan penyesuaian. |
| Procurement | Pemasok dan penerimaan barang. |
| Payables | Kewajiban, pembayaran, jatuh tempo, dan reversal. |
| Documents | Private upload, metadata, checksum, dan download berizin. |
| Reports | Read model, filter, ekspor, dan rekonsiliasi. |
| Administration | Pengguna, audit, pengaturan toko, dan operasi. |

## Dependency Direction

Livewire dan controller memanggil Action. Action mengotorisasi actor, menjalankan aturan domain, dan mengoordinasikan model dalam database transaction. Domain tidak bergantung pada Livewire atau view. Query hanya membaca dan tidak mengubah transaksi. Event eksternal dikirim setelah commit.

Generic repository tidak digunakan tanpa kebutuhan nyata. Eloquent tetap digunakan langsung pada action dan query yang terfokus.

## Konvensi Implementasi

- Satu action untuk satu use case kritis, misalnya `PostSale`.
- Input action menggunakan DTO atau value object ketika field mulai kompleks.
- Livewire public property selalu diperlakukan sebagai input tidak tepercaya.
- Semua mutation sensitif memanggil policy di server.
- Query daftar dipaginasi server-side.
- Money, quantity, price, dan saldo dihitung ulang di server.
- Job asynchronous hanya dikirim setelah transaction commit.
- Semua state gagal memiliki test dan pesan pengguna yang dapat ditindaklanjuti.
