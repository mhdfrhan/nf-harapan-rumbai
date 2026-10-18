# Domain Layer

Lapisan ini berisi aturan murni yang dapat diuji tanpa HTTP atau UI. Domain tidak boleh mengimpor Livewire, Blade, atau controller.

- `Identity`: role dan permission domain.
- `Catalog`: product unit dan price type.
- `Sales`: lifecycle penjualan.
- `Inventory`: tipe pergerakan dan aturan stok.
- `Procurement`: lifecycle penerimaan.
- `Payables`: lifecycle kewajiban dan pembayaran.
- `Shared`: value object dan primitive bersama.
