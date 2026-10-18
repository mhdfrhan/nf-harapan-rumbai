# System Architecture

## Ringkasan

Sistem dirancang sebagai modular monolith Laravel. Pilihan ini menjaga deployment dan pemeliharaan tetap sederhana untuk proyek kerja praktik dan satu toko, tetapi boundary domain dibuat jelas agar kode tidak berubah menjadi kumpulan component besar.

## Stack yang Disetujui untuk Rancangan

| Lapisan | Teknologi |
|---|---|
| Runtime | PHP 8.3 atau lebih baru |
| Framework | Laravel 13 |
| UI server-driven | Livewire 4 dan Blade |
| Primitive UI | Flux UI |
| Styling | Tailwind CSS 4 |
| Interaksi ringan | Alpine.js hanya bila primitive tidak mencukupi |
| Build | Vite |
| Database | MySQL 8, InnoDB, utf8mb4 |
| Queue awal | Database queue, Redis opsional bila beban meningkat |
| Cache awal | Database atau file pada single node, Redis untuk multi-node |
| Storage | Private local disk atau S3-compatible private bucket |
| Test | Pest, Livewire tests, Laravel HTTP tests, dan browser test |

Laravel 13 dipilih karena merupakan major stabil terbaru pada saat rancangan dibuat dan mensyaratkan PHP 8.3. Livewire starter kit resmi dipilih karena pendekatan PHP-first sesuai dengan cakupan KP, sementara Flux UI memberi primitive accessible untuk aplikasi operasional.

## Architectural Decisions

### DEC-01 Modular Monolith

Satu aplikasi dan satu basis data digunakan. Pemisahan dilakukan melalui namespace, action, policy, query object, dan batas transaksi, bukan microservice. Microservice menambah deployment, observability, dan consistency cost yang tidak diperlukan untuk satu toko.

### DEC-02 Server sebagai Sumber Kebenaran

Harga akhir, diskon, total, kembalian, konversi unit, saldo stok, sisa kewajiban, dan izin dihitung atau divalidasi ulang di server. Browser hanya memberi preview.

### DEC-03 Action-Oriented Domain Logic

Operasi penting ditempatkan pada action terfokus, misalnya:

- `PostSale`
- `VoidSale`
- `PostGoodsReceipt`
- `RecordPayablePayment`
- `PostStockAdjustment`
- `ImportOpeningInventory`

Livewire component mengelola presentasi dan state form, bukan aturan domain utama.

### DEC-04 Ledger dan Snapshot

`stock_movements` menjadi ledger append-only. `stock_balances` menjadi snapshot performa yang diperbarui dalam transaksi yang sama dan direkonsiliasi berkala. Ledger tetap sumber audit ketika snapshot tidak cocok.

### DEC-05 Transaksi Posted Tidak Diedit

Record posted tetap historis. Koreksi memakai void, reversal, atau adjustment. Snapshot nama, satuan, harga, dan nilai pada item transaksi mencegah laporan lama berubah ketika master data diperbarui.

### DEC-06 Tidak Ada Public API pada MVP

Livewire HTTP flow menjadi interface utama. JSON endpoint hanya dibuat untuk kebutuhan internal yang terukur, misalnya pencarian barcode, dan tetap memakai session auth, policy, rate limit, serta validasi.

## Bounded Modules

| Modul | Tanggung jawab | Tidak bertanggung jawab atas |
|---|---|---|
| Identity | User, role, permission, session, profile | Aturan transaksi bisnis |
| Catalog | Product, category, unit, conversion, price | Saldo stok |
| Sales | Cart draft, sale posting, payment, receipt, void | Penerimaan pemasok |
| Inventory | Movement, balance, low-stock, adjustment | Harga jual |
| Procurement | Supplier, receipt, receipt item | General ledger akuntansi |
| Payables | Payable, payment, due status | Rekening bank dan payment gateway |
| Documents | Private upload, metadata, authorization, preview | Menentukan kebenaran transaksi |
| Reporting | Read model, filter, export, reconciliation | Mengubah transaksi sumber |
| Audit | Security dan business audit event | Log aplikasi mentah |
| Settings | Konfigurasi toko dan sequence | Secret environment |

## Struktur Kode Rancangan

```text
app/
  Actions/
    Sales/
    Inventory/
    Procurement/
    Payables/
  Domain/
    Money/
    Quantity/
    Sales/
    Inventory/
  Events/
  Http/
    Controllers/
    Middleware/
  Jobs/
  Livewire/
    Pages/
    Forms/
    Components/
  Models/
  Policies/
  Queries/
  Rules/
  Services/
  Support/
database/
  factories/
  migrations/
  seeders/
resources/
  views/
  css/
routes/
tests/
  Feature/
  Unit/
  Browser/
```

Tidak dibuat generic repository layer di atas Eloquent tanpa kebutuhan nyata. Query kompleks laporan ditempatkan pada query object agar component tetap kecil.

## Request Lifecycle Tindakan Kritis

Contoh posting penjualan:

1. Middleware mengautentikasi session, memeriksa origin dan CSRF, serta rate limit.
2. Livewire memvalidasi bentuk input awal.
3. Policy memeriksa `sales.create`.
4. Action menerima data transfer object yang sudah dinormalisasi.
5. Idempotency key diperiksa.
6. Database transaction dimulai.
7. Product unit dan stock balance terkait dikunci untuk update dengan urutan konsisten.
8. Harga, konversi, stok, dan total dihitung ulang.
9. Sale, item snapshot, payment, stock movement, dan balance ditulis.
10. Idempotency result disimpan.
11. Transaction commit.
12. Event after-commit dikirim untuk audit, notification, atau export ringan.
13. UI menerima nomor transaksi dan receipt view.

Tidak ada email, file transform, atau network call pihak ketiga di dalam database transaction.

## Concurrency dan Idempotensi

- Semua action posted menerima UUID idempotency dari client yang dibuat saat form atau keranjang dimulai.
- Unique constraint mencegah key yang sama menghasilkan dua transaksi.
- Stock balance dikunci dengan `SELECT ... FOR UPDATE` dalam urutan ID produk yang konsisten untuk mengurangi deadlock.
- Deadlock yang aman dapat dicoba ulang dalam batas kecil oleh service layer.
- Optimistic version atau `updated_at` digunakan pada master data untuk mendeteksi edit bersamaan.
- UI tidak menganggap timeout berarti gagal; status idempotency diperiksa dahulu.

## Events dan Jobs

### Synchronous

- Validasi domain.
- Posting transaksi dan movement.
- Perhitungan saldo.
- Penyimpanan audit minimum untuk aksi kritis.

### Queued setelah commit

- Pembuatan ekspor besar.
- Pembuatan thumbnail bukti yang aman.
- Pengingat kewajiban.
- Rekonsiliasi berkala.
- Notifikasi internal nonkritis.

Job harus idempotent, memiliki timeout, backoff, batas retry, dan failed-job handling. Payload job menyimpan identifier, bukan model dengan data rahasia berlebihan.

## Caching

- Jangan cache saldo stok atau sisa kewajiban tanpa strategi invalidasi yang teruji.
- Kamus kategori, satuan, serta pengaturan nonkritis dapat di-cache dengan key berversi.
- Laporan berat dapat memiliki cache pendek berdasarkan user, filter, dan versi data.
- Cache tidak menjadi sumber kebenaran.

## File Storage

- Bukti tidak disimpan di `public/` dan tidak diakses dengan URL permanen.
- Download melewati controller berizin atau temporary signed URL berumur singkat.
- Metadata disimpan di database: nama asli yang disanitasi, nama storage acak, MIME terdeteksi, ukuran, checksum, uploader, serta relasi transaksi.
- File image diproses di queue setelah commit; file asli tetap private.
- Backup file dan backup database memiliki lifecycle yang sinkron.

## Observability

Setiap request memiliki request ID yang dibawa ke log dan halaman error. Log terstruktur minimal mencakup environment, level, route, user ID bila ada, request ID, exception class, dan durasi, tanpa password, token, isi dokumen, atau data sensitif lain.

Metric minimum:

- Error rate HTTP dan job.
- Durasi route kritis.
- Queue depth dan failed jobs.
- Disk atau bucket usage.
- Hasil backup terakhir.
- Hasil scheduler terakhir.
- Selisih rekonsiliasi stok.

## Performance Budget

- Query daftar selalu dipaginasi dan eager loading diperiksa.
- POS product search memiliki indeks dan response target pada `NFR-PERF`.
- Dashboard tidak menjalankan satu query per widget per item.
- Export besar dipindahkan ke queue.
- Gambar bukti tidak dimuat penuh pada daftar.
- Laravel debug mode wajib nonaktif pada production.

## Dependency Policy

- Gunakan fitur Laravel dan primitive Flux sebelum menambah package.
- Package baru wajib memiliki alasan, maintenance aktif, lisensi kompatibel, dan test.
- Versi dikunci melalui lockfile.
- Audit dependency Composer dan npm dijalankan dalam CI.
- Package untuk role atau permission tidak wajib; matrix kecil dapat diimplementasikan dengan model dan policy sendiri agar domain jelas.

## Sumber Teknis Utama

- [Laravel 13 Release Notes](https://laravel.com/docs/13.x/releases)
- [Laravel Starter Kits](https://laravel.com/starter-kits)
- [Laravel Frontend and Vite](https://laravel.com/docs/13.x/frontend)
- [Livewire 4 Security](https://livewire.laravel.com/docs/4.x/security)
- [Tailwind CSS with Laravel and Vite](https://tailwindcss.com/docs/installation/framework-guides/laravel/vite)

