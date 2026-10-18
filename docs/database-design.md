# Database Design

## Prinsip

1. MySQL 8 dengan InnoDB, foreign key, strict mode, dan `utf8mb4`.
2. Primary key internal menggunakan `BIGINT UNSIGNED`; identifier publik transaksi menggunakan ULID atau UUID yang tidak mudah ditebak.
3. Nilai uang disimpan sebagai integer rupiah, misalnya `BIGINT`.
4. Kuantitas dan faktor konversi memakai `DECIMAL(18,4)` atau presisi lebih tinggi bila hasil uji data memerlukan.
5. Tanggal bisnis disimpan sebagai `DATE`; timestamp disimpan UTC dan ditampilkan pada `Asia/Jakarta`.
6. Master data memakai soft delete hanya bila benar-benar perlu. Transaksi posted tidak dihapus.
7. Constraint database melengkapi validasi aplikasi.

## Entity Groups

### Identity

#### `users`

Kolom utama: `id`, `public_id`, `name`, `login_id`, `password`, `is_active`, `last_login_at`, `remember_token`, timestamps.

Constraint: `login_id` unik setelah normalisasi. Tidak ada public registration.

#### `roles`, `permissions`, `role_user`, `permission_role`

Role memberikan baseline izin. Jika override per user tidak dibutuhkan, tidak dibuat tabel override. Perubahan role dicatat ke audit log.

### Catalog

#### `categories`

`id`, `name`, `slug`, `is_active`, timestamps. Nama atau slug unik.

#### `units`

`id`, `code`, `name`, `dimension`, `decimal_scale`, `is_active`.

`dimension` membedakan count, mass, volume, atau package agar konversi lintas dimensi yang tidak valid dapat ditolak.

#### `products`

`id`, `public_id`, `category_id`, `sku`, `name`, `description`, `base_unit_id`, `barcode`, `low_stock_threshold`, `allows_negative_stock`, `is_active`, `lock_version`, timestamps.

Indeks: unique `sku`; unique barcode jika tidak null; index `(category_id, is_active)`; pencarian nama sesuai kemampuan MySQL.

#### `product_units`

`id`, `product_id`, `unit_id`, `label`, `conversion_to_base`, `barcode`, `is_default_sale_unit`, `is_active`, timestamps.

Constraint: `conversion_to_base > 0`; unique `(product_id, unit_id, label)`; hanya satu default sale unit per product ditegakkan melalui service dan test bila partial unique index tidak tersedia.

Contoh gula dengan base unit kg:

| Label | Faktor ke kg |
|---|---:|
| 1 kg | 1.0000 |
| 0,5 kg | 0.5000 |
| 0,25 kg | 0.2500 |

#### `product_prices`

`id`, `product_unit_id`, `price_type`, `amount_rupiah`, `minimum_quantity`, `effective_from`, `effective_until`, `is_active`, timestamps.

`price_type` dibatasi pada enum domain seperti `retail` dan `wholesale`. Range efektif tidak boleh overlap untuk kombinasi yang sama tanpa rule eksplisit.

### Sales

#### `sales`

`id`, `public_id`, `number`, `status`, `sold_at`, `cashier_id`, `subtotal_rupiah`, `discount_rupiah`, `total_rupiah`, `notes`, `idempotency_key`, `posted_at`, `voided_at`, `voided_by`, `void_reason`, timestamps.

Constraint: `number` unik; `idempotency_key` unik; total tidak negatif; status mengikuti state machine.

#### `sale_items`

`id`, `sale_id`, `product_id`, `product_unit_id`, snapshot `product_name`, `sku`, `unit_label`, `conversion_to_base`, `quantity`, `base_quantity`, `price_type`, `unit_price_rupiah`, `discount_rupiah`, `subtotal_rupiah`, timestamps.

Snapshot wajib agar histori tidak berubah ketika nama, konversi, atau harga master berubah.

#### `sale_payments`

`id`, `sale_id`, `method`, `amount_rupiah`, `received_rupiah`, `change_rupiah`, `reference`, `status`, timestamps.

MVP dapat membatasi satu payment per penjualan. Struktur tetap memungkinkan pengembangan split payment setelah aturan bisnis disetujui.

#### `sale_voids`

`id`, `sale_id`, `voided_by`, `reason_code`, `reason_text`, `created_at`.

Tabel terpisah memudahkan audit dan mencegah reason historis tertimpa.

### Procurement

#### `suppliers`

`id`, `public_id`, `name`, `phone`, `address`, `notes`, `is_active`, timestamps.

#### `goods_receipts`

`id`, `public_id`, `number`, `supplier_id`, `supplier_document_number`, `status`, `received_on`, `subtotal_rupiah`, `total_rupiah`, `payment_state`, `due_on`, `notes`, `created_by`, `posted_by`, `posted_at`, `idempotency_key`, timestamps.

#### `goods_receipt_items`

`id`, `goods_receipt_id`, `product_id`, `product_unit_id`, snapshot fields, `quantity`, `base_quantity`, `unit_cost_rupiah`, `subtotal_rupiah`, timestamps.

Harga beli disimpan untuk bukti transaksi dan analisis penerimaan, tetapi laporan laba tidak dibuat sampai metode HPP ditetapkan.

### Inventory

#### `stock_movements`

`id`, `public_id`, `product_id`, `movement_type`, `quantity_delta`, `balance_after`, polymorphic atau explicit reference fields, `reference_type`, `reference_id`, `occurred_at`, `actor_id`, `reason_code`, `notes`, timestamps.

`quantity_delta` positif untuk masuk dan negatif untuk keluar. Record append-only. Index `(product_id, occurred_at, id)` dan `(reference_type, reference_id)`.

Jika morph type digunakan, alias morph map eksplisit wajib dipakai agar nama class internal tidak tersimpan langsung.

#### `stock_balances`

`product_id` sebagai unique key, `quantity_on_hand`, `version`, `last_movement_id`, timestamps.

Balance diperbarui pada database transaction yang sama dengan movement. Nilai dapat dibangun ulang dari ledger.

#### `stock_adjustments`

`id`, `public_id`, `number`, `status`, `adjusted_at`, `created_by`, `posted_by`, `reason_code`, `notes`, `idempotency_key`, timestamps.

#### `stock_adjustment_items`

`id`, `stock_adjustment_id`, `product_id`, `system_quantity`, `physical_quantity`, `quantity_delta`, `notes`.

### Payables

#### `payables`

`id`, `public_id`, `number`, `supplier_id`, `goods_receipt_id`, `status`, `principal_rupiah`, `paid_rupiah`, `balance_rupiah`, `due_on`, `closed_at`, timestamps.

`goods_receipt_id` unik pada MVP jika satu penerimaan menghasilkan maksimal satu kewajiban.

#### `payable_payments`

`id`, `public_id`, `number`, `payable_id`, `paid_on`, `amount_rupiah`, `method`, `reference`, `notes`, `status`, `created_by`, `voided_by`, `voided_at`, `void_reason`, `idempotency_key`, timestamps.

Pembayaran yang dibatalkan tetap ada dan memiliki record pembalik atau status void sesuai aturan final.

### Documents

#### `documents`

`id`, `public_id`, `attachable_type`, `attachable_id`, `category`, `original_name`, `storage_disk`, `storage_path`, `detected_mime`, `extension`, `size_bytes`, `sha256`, `uploaded_by`, `scanned_status`, `created_at`, `deleted_at` bila draft.

Index pada attachable dan checksum. Path menggunakan nilai acak, bukan original filename.

### Audit dan Operations

#### `audit_logs`

`id`, `public_id`, `occurred_at`, `actor_id`, `event`, `auditable_type`, `auditable_id`, `request_id`, `ip_hash_or_address`, `user_agent_summary`, `before_json`, `after_json`, `metadata_json`.

Kolom JSON melalui redaction dan allowlist. Audit log immutable bagi UI aplikasi.

#### `idempotency_keys`

`id`, `key`, `user_id`, `action`, `request_hash`, `result_type`, `result_id`, `status`, `expires_at`, timestamps.

Unique `(user_id, action, key)`. Request hash mencegah key sama dipakai untuk payload berbeda.

#### `settings`

`id`, `key`, `value_json`, `type`, `is_sensitive`, timestamps. Secret tidak disimpan di sini.

#### Framework tables

Sessions, cache, jobs, job batches, failed jobs, notifications, dan password reset token dibuat hanya sesuai driver yang digunakan.

## Relasi Utama

```text
categories 1 -> n products
units 1 -> n product_units
products 1 -> n product_units
product_units 1 -> n product_prices

sales 1 -> n sale_items
sales 1 -> n sale_payments
sales 1 -> 0..1 sale_voids

suppliers 1 -> n goods_receipts
goods_receipts 1 -> n goods_receipt_items
goods_receipts 1 -> 0..1 payables
payables 1 -> n payable_payments

products 1 -> n stock_movements
products 1 -> 1 stock_balances

transactions 1 -> n documents
users 1 -> n audit_logs
```

## Constraint dan Check Penting

- Semua kuantitas input transaksi lebih dari nol.
- `base_quantity = quantity * conversion_snapshot`, diverifikasi di action dan test.
- Money tidak negatif kecuali field delta atau reversal yang memang diizinkan.
- Payment tidak melampaui saldo.
- Due date tidak diperlukan untuk transaksi lunas.
- Posted record memiliki `posted_at` dan aktor.
- Void record memiliki waktu, aktor, dan alasan.
- Satu idempotency key hanya menghasilkan satu aggregate.
- Foreign key transaksi posted menggunakan restrict, bukan cascade delete.

## Numbering

Nomor bisnis tidak bergantung pada primary key. Format rancangan:

- Penjualan: `PJ-YYYYMMDD-0001`
- Penerimaan: `PB-YYYYMMDD-0001`
- Kewajiban: `KW-YYYYMMDD-0001`
- Pembayaran: `BY-YYYYMMDD-0001`
- Penyesuaian: `PS-YYYYMMDD-0001`

Sequence dibuat atomik per jenis dan tanggal. Format final memerlukan konfirmasi `OPEN-09`.

## Migration Discipline

- Migration production bersifat maju dan tidak mengandalkan rollback untuk pemulihan data.
- Perubahan kolom besar dilakukan dengan pola expand, migrate, contract.
- Data backfill dibuat idempotent dan memiliki laporan jumlah baris.
- Constraint baru dipasang setelah data lama divalidasi.
- Seed production hanya untuk role, permission, dan configuration baseline yang aman.

## Rekonsiliasi

Job harian menghitung ulang saldo dari movement dan membandingkan `stock_balances`. Selisih tidak diperbaiki diam-diam. Sistem membuat alert, menyimpan hasil, dan meminta investigasi sebelum repair action berizin dijalankan.

Kewajiban direkonsiliasi dari principal dan pembayaran valid. Laporan menandai record jika cached balance berbeda dari hasil hitung.

