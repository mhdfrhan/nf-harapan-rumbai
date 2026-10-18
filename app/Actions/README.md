# Application Actions

Setiap folder menampung use case yang mengubah state bisnis. Action kritis mengurus otorisasi, normalisasi input, idempotensi, database transaction, dan event after-commit. Livewire component tidak boleh menyalin aturan tersebut.

- `Catalog`: pengelolaan produk, satuan, dan harga.
- `Inventory`: opening balance, movement, dan adjustment.
- `Sales`: posting serta void penjualan.
- `Procurement`: posting dan koreksi penerimaan barang.
- `Payables`: pencatatan serta reversal pembayaran kewajiban.
- `Documents`: attachment dan lifecycle bukti private.
- `Administration`: akun, izin, dan pengaturan sensitif.
