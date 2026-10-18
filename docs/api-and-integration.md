# API and Integration

## Keputusan API

MVP tidak menyediakan public REST API. Antarmuka utama menggunakan session-authenticated Livewire. Keputusan ini mengurangi attack surface, versioning, token management, dan duplikasi validasi. Kontrak aksi internal tetap didokumentasikan agar aturan domain dapat diuji tanpa bergantung pada UI.

## Kontrak Aksi Internal

Setiap action menerima data transfer object, actor, dan idempotency key untuk tindakan posted. Action mengembalikan aggregate ID serta ringkasan hasil, atau melempar domain exception yang dapat diterjemahkan menjadi pesan pengguna.

### `PostSale`

**Input:** Actor, cart items berisi product unit dan quantity, payment method, received amount, notes, idempotency key.

**Server lookup:** Product, active unit, conversion, effective price, stock balance, permission.

**Output:** Sale public ID, business number, total, change, posted time, receipt URL.

**Error domain:** Product inactive, unit invalid, price changed, insufficient stock, payment invalid, duplicate payload mismatch.

### `PostGoodsReceipt`

**Input:** Supplier, document date, supplier reference, item list, payment state, initial payment, due date, document IDs, idempotency key.

**Output:** Receipt, stock movement references, payable jika ada, payment jika ada.

### `RecordPayablePayment`

**Input:** Payable, date, amount, method, reference, document IDs, idempotency key.

**Output:** Payment number dan payable balance terbaru.

### `PostStockAdjustment`

**Input:** Product, expected balance version, physical quantity atau delta, reason, notes, document IDs, idempotency key.

**Output:** Adjustment number, movement, dan saldo baru.

### `VoidSale`

**Input:** Sale, reason code, explanation, actor confirmation, idempotency key.

**Output:** Void record, reversal movement, dan status akhir.

## Endpoint Internal yang Diizinkan

Endpoint berikut dapat dibuat jika Livewire request biasa tidak mencukupi:

| Method | Rute rancangan | Tujuan | Kontrol |
|---|---|---|---|
| GET | `/internal/products/search` | Search POS | Auth, `sales.create`, throttle, query min/max length |
| GET | `/internal/products/barcode/{barcode}` | Exact barcode lookup | Auth, permission, throttle |
| GET | `/documents/{document}/download` | Download bukti | Auth, policy, audit, response private |
| GET | `/exports/{export}/download` | Download hasil ekspor | Auth, owner policy, expiry |
| GET | `/sales/{sale}/receipt` | View atau print receipt | Auth, policy |

Endpoint internal tidak memakai prefix `/api` jika menggunakan session web middleware. Response JSON memiliki format error konsisten dan tidak mengandung stack trace.

## Idempotency Contract

1. Client membuat UUID baru ketika draft transaksi dimulai.
2. Key dikirim pada submit dan tidak berubah saat retry payload yang sama.
3. Server menyimpan actor, action, hash payload normalized, status, dan result.
4. Key sama dengan payload sama mengembalikan hasil pertama.
5. Key sama dengan payload berbeda ditolak sebagai conflict.
6. Request yang statusnya tidak pasti dapat dipoll atau diperiksa melalui server action aman.
7. Masa simpan key tindakan finansial mengikuti masa audit transaksi, bukan dibuang beberapa menit setelah submit.

## Error Model

| Kategori | HTTP atau UI mapping | Contoh |
|---|---|---|
| Validation | 422 | Quantity kosong, file terlalu besar. |
| Authentication | 401 atau redirect login | Session tidak valid. |
| Authorization | 403 | Kasir mencoba melihat audit log. |
| Not found | 404 | Identifier tidak ada atau tidak boleh dibocorkan. |
| Conflict | 409 | Data telah berubah atau idempotency mismatch. |
| Rate limit | 429 | Search atau login terlalu cepat. |
| Domain rule | 422 atau conflict | Stok tidak cukup, saldo sudah lunas. |
| Server | 500 | Request ID diberikan tanpa detail internal. |

Error field menggunakan key stabil untuk UI, sedangkan pesan ditulis dalam Bahasa Indonesia.

## Upload Dokumen

Alur upload dirancang dua tahap:

1. File diunggah ke area draft private dan mendapat document public ID.
2. Saat transaksi diposting, document di-attach ke aggregate dalam transaksi atau mekanisme after-commit yang dapat direkonsiliasi.

Kontrol:

- Allowlist format awal: JPEG, PNG, dan PDF.
- MIME dideteksi dari konten, bukan percaya `Content-Type` atau ekstensi client.
- Batas ukuran awal dirancang 8 MB per file dan maksimal 5 file per transaksi, menunggu konfirmasi kebutuhan nyata.
- Nama file storage acak dan original filename disanitasi hanya untuk tampilan.
- File disimpan di luar web root.
- Image decoder dan PDF metadata processing berjalan terisolasi dari request posting.
- Download memakai `Content-Disposition` aman, `nosniff`, dan authorization.
- File draft tanpa relasi dibersihkan scheduler setelah masa retensi yang disetujui.

Antivirus scanning bersifat wajib bila deployment memiliki layanan yang mendukung. Jika belum tersedia, risiko dicatat dan preview file aktif dibatasi.

## Cetak

- Rilis awal menggunakan browser print stylesheet untuk struk 58 atau 80 mm, sesuai perangkat yang dikonfirmasi.
- Struk tidak bergantung pada koneksi pihak ketiga.
- Nomor transaksi, waktu, item, total, pembayaran, kembalian, kasir, dan status tercetak.
- Struk void memiliki watermark teks `Dibatalkan`.
- Cetak ulang dicatat sebagai event audit ringan bila diperlukan.

## Ekspor

- CSV memakai UTF-8 BOM agar mudah dibuka di Excel lokal.
- Formula injection dicegah dengan escaping field yang dimulai `=`, `+`, `-`, atau `@`.
- PDF berisi judul, filter, periode, zona waktu, waktu dibuat, dan page number.
- Ekspor besar dijalankan di queue dengan status queued, processing, completed, failed, expired.
- File ekspor private, memiliki expiry, dan hanya dapat diakses pembuat atau pemilik.
- Semua nilai pada ekspor menggunakan query dan definisi laporan yang sama dengan layar.

## WhatsApp dan Galeri

Masalah saat ini adalah bukti dikirim melalui WhatsApp dan tersimpan di galeri. MVP mengganti pola tersebut dengan upload bukti langsung ke transaksi. Sistem tidak melakukan integrasi WhatsApp otomatis karena:

- Tidak diperlukan untuk menyelesaikan masalah inti.
- Menambah pengelolaan credential dan data pihak ketiga.
- Dapat mendorong duplikasi sumber kebenaran.

Tautan berbagi WhatsApp atau notifikasi WhatsApp hanya dapat masuk fase lanjutan setelah tujuan, persetujuan data, biaya, dan provider resmi disetujui.

## Barcode Scanner

Scanner USB yang berperilaku seperti keyboard dapat digunakan tanpa integrasi khusus. UI POS menjaga fokus dan mendeteksi Enter terminator. Kamera barcode di browser bukan MVP karena izin perangkat dan reliabilitas perlu diuji pada perangkat nyata.

## Integrasi yang Tidak Termasuk MVP

- Payment gateway.
- Sinkronisasi marketplace.
- Sistem supplier eksternal.
- Integrasi akuntansi.
- WhatsApp Business API.
- Aplikasi mobile native.
- Public API untuk pihak ketiga.

Setiap integrasi baru wajib memiliki threat model, data flow, retry strategy, idempotency, observability, biaya, dan mekanisme penghentian.

