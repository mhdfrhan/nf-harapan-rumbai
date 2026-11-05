# Rencana Perbaikan Komprehensif BAB IV & BAB V Laporan Kerja Praktek (Non-Teknis, Humanized, & Diagram Monokrom Bebas Tabrakan)

## 1. Ringkasan Eksekutif & Analisis Akar Masalah (*Root Cause Analysis*)

Berdasarkan evaluasi terhadap dokumen `KP/LAPORAN_KERJA_PRAKTEK_NORAWRR_BAB_IV.docx`, `KP/LAPORAN_KERJA_PRAKTEK_NORAWRR_BAB_V.docx`, draf Markdown (`KP/BAB IV HASIL DAN PEMBAHASAN.md` & `KP/BAB V PENUTUP.md`), serta 17 diagram di `KP/diagrams/`, ditemukan **4 akar permasalahan utama** yang harus diperbaiki secara menyeluruh:

### A. Kerusakan Pemformatan Huruf Tebal (`**tulisan**`) pada Dokumen Word (`.docx`)
- **Gejala**: Pada Subbab `4.2.1.5 Perancangan Aturan Bisnis Inti Toko NF Harapan Rumbai`, poin ke-1 tercetak tebal (*bold*), namun poin ke-2 (`**Penentuan Harga Eceran (*Retail*) dan Grosir (*Wholesale*) Otomatis di Sisi *Server***`) dan poin ke-3 (`**Otomatisasi Kewajiban Pemasok (*Payable*) dan Pengingat Jatuh Tempo H-7**`) menampilkan tanda bintang `**` secara mentah dan tidak menjadi tebal. Hal serupa terjadi di beberapa poin lain yang menggabungkan cetak tebal dan cetak miring.
- **Akar Masalah**: Fungsi `renderInlineRuns(rawText)` pada skrip kompilator `compile_bab4.js` menggunakan ekspresi reguler `const tokenRegex = /(\*\*[^*]+\*\*|\*[^*]+\*|`[^`]+`)/g;`. Pola `\*\*[^*]+\*\*` menolak string yang memiliki karakter `*` di dalamnya. Ketika terdapat istilah asing bercetak miring (`*Retail*`) di dalam teks bercetak tebal (`**...**`), parser gagal mengenali blok tebal tersebut dan mencetak `**` sebagai teks biasa.
- **Solusi Arsitektural**:
  1. Mengganti *tokenizer* di `compile_bab4.js` dengan parser bersarang (*nested parser*) dua tahap: tahap pertama memisahkan segmen `**...**` (`\*\*([\s\S]+?)\*\*`), kemudian di dalam segmen tebal tersebut mem-parsing segmen `*...*` sehingga menghasilkan `<w:rPr><w:b/><w:i/><w:iCs/></w:rPr>` untuk teks tebal-miring dan `<w:rPr><w:b/></w:rPr>` untuk teks tebal biasa.
  2. Menambahkan pemeriksaan otomatis (*post-build XML assertion*) pada `word/document.xml` yang memastikan **0 kemunculan** karakter `**` atau `` ` `` mentah di seluruh paragraf dan sel tabel.

### B. Penggunaan Kode Program, Nama File, dan Nama Kolom Teknis di Narasi, Tabel Umum, & Diagram
- **Gejala**: Penjelasan di naskah Word, tabel-tabel analisis/perancangan/pengujian (seperti `Tabel 4.6 Matriks Hak Akses Pengguna`, `Tabel 4.1`–`Tabel 4.5`, `Tabel 4.7`–`Tabel 4.8`, `Tabel 4.21`–`Tabel 4.28`, `Tabel 4.30`–`Tabel 4.38`), serta teks di dalam diagram (`Gambar 4.1`–`Gambar 4.7`, `Gambar 4.10`–`Gambar 4.17`) mencantumkan kode teknis secara langsung seperti `stock.adjust`, `dashboard.view_owner`, `users.*`, `initial_payment_rupiah`, `total_rupiah`, `minimum_quantity`, `conversion_to_base`, `PostSale`, `PostGoodsReceipt`, `lockForUpdate()`, `partially_paid`, `unpaid`, maupun URL rute `/inventory/receipts`.
- **Aturan Batasan Teknis vs Non-Teknis**:
  - **DILARANG** mencantumkan nama kolom database (`snake_case`), kode izin (`permission.key`), nama *class/method* PHP (`PostSale`, `InventoryLedger::post()`), tipe data SQL (`decimal(18,4)`), atau *route path* (`/sales/create`) pada:
    1. Seluruh narasi paragraf di Bab IV dan Bab V.
    2. Tabel Analisis Sistem (`Tabel 4.1`–`Tabel 4.5`) dan Tabel Matriks Hak Akses (`Tabel 4.6`).
    3. Tabel Spesifikasi *Use Case* (`Tabel 4.7`–`Tabel 4.8`), Tabel Rancangan *Output* (`Tabel 4.21`–`Tabel 4.28`), Tabel Spesifikasi Lingkungan (`Tabel 4.29`), dan Tabel Pengujian *Black Box* (`Tabel 4.30`–`Tabel 4.38`).
    4. Diagram Proses & Perilaku (`Gambar 4.1` *Use Case*, `Gambar 4.2`–`4.3` *Flowchart*, `Gambar 4.4`–`4.5` *Activity Diagram*, `Gambar 4.6`–`4.7` *Sequence Diagram*) dan *Wireframe* (`Gambar 4.10`–`Gambar 4.17`).
  - **DIPERBOLEHKAN HANYA PADA**:
    1. Bagian dalam kotak atribut *Class Diagram* (`Gambar 4.8`) dan *Entity Relationship Diagram / ERD* (`Gambar 4.9`) karena standar diagram struktur data memang menampilkan struktur tabel/entitas. Namun penjelasan paragraf yang mengantar dan membahas gambar tersebut tetap ditulis secara fungsional/non-teknis.
    2. Kolom "Nama Kolom" dan "Tipe Data" di dalam Tabel Spesifikasi Basis Data (`Tabel 4.9` s.d. `Tabel 4.20` pada Subbab `4.2.2.2`), sementara kolom "Keterangan" pada tabel-tabel tersebut tetap menggunakan bahasa Indonesia bisnis yang mengalir tanpa kode teknis.
- ** Kamus Transformasi Istilah Teknis ke Bahasa Bisnis / Operasional (Mengikuti `KP/SKILL-humanize-penelitian.md`)**:
  | Istilah Teknis / Kode Sebelumnya | Pengganti Bahasa Bisnis / Operasional (Humanized) |
  | :--- | :--- |
  | `owner`, `inventory_admin`, `cashier`, `auditor` | Pemilik Toko, Admin Gudang, Kasir, Pemeriksa Internal (*Auditor*) |
  | `stock.adjust`, `stock.view` | Penyesuaian stok fisik (*stock opname*), pemantauan saldo persediaan |
  | `dashboard.view_owner`, `users.*`, `settings.manage` | Ringkasan eksekutif toko, pengelolaan akun pegawai, pengaturan profil toko |
  | `products.view`, `products.create/update`, `prices.*` | Pencarian katalog barang, pembaruan data barang, pengaturan tingkatan harga jual |
  | `sales.create`, `sales.view_all`, `sales.view_own`, `sales.void` | Pencatatan transaksi kasir, riwayat seluruh penjualan, riwayat penjualan kasir aktif, pembatalan nota penjualan |
  | `suppliers.manage`, `purchases.post`, `purchases.void` | Pengelolaan data pemasok, pencatatan barang masuk dari pemasok, pembatalan penerimaan barang |
  | `payables.view`, `payable_payments.post`, `payable_payments.void` | Pemantauan daftar hutang usaha, pencatatan pembayaran cicilan hutang, pembatalan pencatatan pembayaran hutang |
  | `documents.upload`, `documents.view`, `reports.*`, `audit.view` | Pengunggahan arsip nota fisik, penampilan berkas lampiran, pencetakan laporan berkala, pemeriksaan jejak aktivitas sistem |
  | `initial_payment_rupiah`, `total_rupiah`, `balance_rupiah`, `principal_rupiah`, `paid_rupiah` | Nominal pembayaran awal (uang muka), nilai total tagihan nota, sisa tagihan hutang, nilai pokok tagihan, akumulasi pembayaran cicilan |
  | `paid`, `partially_paid`, `unpaid` | Lunas, Dibayar Sebagian (Cicilan), Belum Dibayar (Kredit Penuh) |
  | `due_on`, `occurred_at` | Tanggal jatuh tempo pembayaran, waktu pencatatan transaksi |
  | `conversion_to_base`, `base_unit_id`, `base_quantity`, `decimal(18,4)` | Nilai rasio konversi satuan ke satuan terkecil, satuan dasar barang, jumlah barang dalam satuan terkecil, perhitungan pecahan hingga empat angka di belakang koma |
  | `minimum_quantity`, `retail`, `wholesale` | Batas jumlah pembelian minimum, harga eceran, harga grosir |
  | `PostSale`, `PostGoodsReceipt`, `RecordPayablePayment`, `InventoryLedger` | Proses penyimpanan transaksi penjualan, proses pencatatan barang masuk, proses pencatatan pembayaran hutang, buku besar mutasi persediaan |
  | `DB::transaction`, `lockForUpdate()`, `idempotency_key` | Pengamanan transaksi serentak secara utuh, penguncian antrean stok saat transaksi bersamaan, penanda transaksi unik pencegah kiriman ganda |

### C. Diagram Berwarna yang Terlihat Seperti Buatan AI (*AI-Generated Look*)
- **Gejala**: Ketujuh belas gambar (`Gambar 4.1` s.d. `Gambar 4.17`) menggunakan warna latar pastel (`#dae8fc` biru, `#d5e8d4` hijau, `#fff2cc` kuning, `#ffe6cc` oranye, `#f8cecc` merah muda, `#e1d5e7` ungu) dan *wireframe* berwarna gelap/biru yang mencolok seperti hasil *generate* AI.
- **Solusi Arsitektural**:
  - Mengubah seluruh 17 file `.drawio` dan hasil ekspor `.png` menjadi **100% Monokrom Hitam-Putih Akademis**:
    - Latar kanvas: Putih bersih (`#FFFFFF`).
    - Kotak simbol (*Process*, *Decision*, *Use Case*, *Lifeline*, *Class*, *Entity*, *Wireframe Card*): Latar putih (`fillColor=#FFFFFF`), garis tepi hitam tegas (`strokeColor=#000000;strokeWidth=1.5`), teks hitam (`fontColor=#000000`), font akademik konsisten (`Arial` / `Helvetica` / `Times New Roman`).
    - *Header* tabel pada ERD / Class Diagram / *Swimlane* / *Wireframe*: Hanya menggunakan pembeda garis batas hitam (`#000000`) atau arsiran abu-abu sangat terang (`#F2F2F2`) tanpa warna-warni sama sekali.

### D. Garis Panah (*Arrows*) Menimpa Kotak, Tertutup Elemen Lain, atau Arahnya Tidak Jelas
- **Gejala**: Pada diagram yang dihasilkan sebelumnya, terdapat garis panah yang memotong/menembus kotak lain, bertumpuk di titik masuk yang sama, tertutup oleh kotak karena urutan *layer* (*z-index*), atau ujung panahnya terlalu kecil/tertutup sehingga arah alurnya membingungkan.
- **Akar Masalah**:
  1. Fungsi `renderDiagramToHtml` pada `generate_diagrams_part2.js` menggambar garis (`edges`) hanya dengan menghubungkan titik tengah atau sisi terdekat secara naif tanpa membaca atribut titik keluar/masuk (`exitX`, `exitY`, `entryX`, `entryY`) maupun titik belok manual (`<Array as="points"><mxPoint x="..." y="..."/></Array>`).
  2. Ketika dua panah menuju satu simbol (misalnya akhir cabang *Decision* "Ya" dan "Tidak" atau panah balik *Loop*), keduanya masuk ke koordinat `(tcx, tgt.absY)` yang persis sama sehingga garis dan mata panahnya saling menimpa.
  3. Kotak label teks pada panah tidak memiliki latar putih yang cukup rapat atau diletakkan tepat di atas kepala panah.
- **Solusi Arsitektural & Protokol Verifikasi Visual Wajib**:
  1. **Peningkatan Mesin Render `.drawio` → SVG/PNG**:
     - Mendukung penuh atribut `exitX`, `exitY`, `entryX`, `entryY` (misal keluar dari sisi kanan `exitX=1, exitY=0.5`, masuk ke sisi kanan target `entryX=1, entryY=0.5` melalui *waypoint* luar) serta `<Array as="points">` pada elemen `<mxGeometry>`.
     - Menggambar seluruh elemen kotak terlebih dahulu, kemudian menggambar jalur garis (`<path>`) dan kepala panah (`<marker>`) di lapisan atas dengan perhitungan *port* tepi yang presisi agar kepala panah berhenti tepat di garis tepi luar kotak sasaran (tidak pernah tertutup kotak dan tidak pernah menembus ke dalam teks kotak).
     - Menambahkan **Validator Geometri Otomatis (`validateDiagramGeometry`)** di skrip generator yang memeriksa setiap segmen garis `(x1,y1)-(x2,y2)` terhadap seluruh kotak bukan sumber/tujuan: jika ada segmen garis yang memotong bagian dalam kotak lain (*bounding-box intersection*) atau dua garis kolinear yang bertumpuk, skrip otomatis menggagalkan *build* dan melaporkan ID garis yang bermasalah.
  2. **Inspeksi Visual Mandiri (*Visual Audit*) Setelah Setiap Pembuatan Gambar**:
     - Setiap kali gambar `.png` selesai dirender, agen **wajib** membuka gambar tersebut menggunakan `read_file` untuk memeriksa secara visual:
       - Apakah seluruh gambar sudah hitam-putih (tanpa warna)?
       - Apakah ada garis panah yang menabrak kotak atau teks?
       - Apakah ada panah yang tertutup atau arah mata panahnya tidak terlihat jelas?
       - Apakah teks di dalam gambar sudah bebas dari kode program/nama kolom teknis?
     - Hanya jika lolos pemeriksaan visual, pekerjaan boleh berlanjut ke tahap berikutnya.

---

## 2. Rincian Perbaikan per Subbab, Tabel, dan Gambar

### 2.1 Subbab 4.1 Analisis Sistem & Subbab 4.2.1 Perancangan Sistem (Fungsional, Non-Fungsional, Arsitektur, Hak Akses, Aturan Bisnis)
- **Target File Sumber**: `bab4_sec_41_421.js`
- **Perbaikan Spesifik**:
  1. **Subbab 4.1.1 – 4.1.3 (`Tabel 4.1` & `Tabel 4.2`)**:
     - Menghapus istilah teknis `decimal(18,4)`, `stock_balances`, `minimum_quantity`, `product_prices`, `conversion_to_base` dari `Tabel 4.1`. Menggantinya dengan penjelasan operasional (contoh: "Sistem mendukung pencatatan kuantitas pecahan hingga empat angka desimal", "Sistem menyimpan saldo stok berjalan dan riwayat mutasi barang secara terpusat", "Sistem menerapkan batas jumlah pembelian minimum untuk membedakan harga eceran dan grosir secara otomatis").
  2. **Subbab 4.2.1.1 – 4.2.1.3 (`Tabel 4.3`, `Tabel 4.4`, `Tabel 4.5`)**:
     - Menghapus `DB::transaction`, `lockForUpdate`, `idempotency_key`, `audit_logs`, `request_id`, `stock.adjust`, dan nama tabel teknis dari `Tabel 4.3` (Kebutuhan Fungsional), `Tabel 4.4` (Kebutuhan Non-Fungsional), dan `Tabel 4.5` (Lapisan Arsitektur).
     - Pada `Tabel 4.5`, kolom "Komponen" ditulis menggunakan nama modul fungsional berbahasa Indonesia (misal: *Antarmuka Pengguna (Tampilan Web)*, *Pengendali Permintaan & Pemeriksa Hak Akses*, *Layanan Aturan Bisnis (Penjualan, Persediaan, Pengadaan, Keuangan)*, *Lapisan Penyimpanan Data & Buku Besar Stok*), tanpa menulis `app/Http/Controllers` atau `PostSale.php`.
  3. **Subbab 4.2.1.4 & `Tabel 4.6 Matriks Hak Akses Pengguna Berdasarkan Peran`**:
     - Menghapus referensi `App\Domain\Identity\Enums\Role` dan kode peran `owner`, `inventory_admin`, `cashier`, `auditor` dari narasi.
     - Pada `Tabel 4.6`, menghapus seluruh kode izin di dalam tanda kurung seperti `(`dashboard.view_owner`)`, `(`users.*`, `settings.manage`)`, `(`products.view`)`, `(`sales.create`)`, `(`stock.adjust`)`, `(`purchases.post`)`, `(`payable_payments.post`)`, `(`reports.*`)`, `(`audit.view`)`. Seluruh baris fitur ditulis murni sebagai aktivitas kerja toko (misal: "Melakukan Penyesuaian Stok Fisik (*Stock Opname*)", "Mencatat Transaksi Penjualan Kasir", "Mencatat Barang Masuk dari Pemasok").
  4. **Subbab 4.2.1.5 `Perancangan Aturan Bisnis Inti Toko NF Harapan Rumbai`**:
     - Memperbaiki struktur teks poin 1, 2, 3, dan 4 agar seluruh judul poin tercetak **tebal (*bold*)** secara konsisten di Word tanpa tanda `**` yang bocor.
     - Menghapus seluruh nama kolom/variabel teknis dari penjelasan aturan bisnis (`base_unit_id`, `product_units`, `conversion_to_base`, `decimal(18,4)`, `Quantity`, `base_quantity = quantity * conversion_to_base`, `retail`, `wholesale`, `minimum_quantity`, `PostSale`, `PostGoodsReceipt`, `initial_payment_rupiah`, `total_rupiah`, `paid`, `unpaid`, `partially_paid`, `due_on`, `balance_rupiah > 0`).
     - Menggantinya dengan penjelasan logika bisnis yang jernih: misalnya membandingkan **nilai pembayaran awal (uang muka)** terhadap **nilai total tagihan nota**; jika pembayaran awal sama dengan total tagihan maka status hutang menjadi **Lunas**, jika pembayaran awal bernilai nol maka berstatus **Belum Dibayar**, dan jika pembayaran awal berada di antara nol hingga di bawah total tagihan maka berstatus **Dibayar Sebagian (Cicilan)**.

### 2.2 Perombakan 17 Diagram Draw.io (`Gambar 4.1` s.d. `Gambar 4.17`) — Monokrom, Non-Teknis, & Rute Panah Presisi
- **Target File Sumber**: `generate_diagrams_part1.js` & `generate_diagrams_part2.js` -> menghasilkan `.drawio` dan `.png` di `KP/diagrams/`.
- **Daftar Perbaikan per Diagram**:
  1. **`Gambar 4.1 Use Case Diagram Sistem` (`01-use-case-diagram`)**:
     - Menghapus warna pada paket dan elips *use case*; seluruhnya hitam-putih.
     - Mengatur ulang tata letak 4 aktor (Pemilik Toko & Kasir di sisi kiri; Admin Gudang & Pemeriksa Internal di sisi kanan) dengan titik tambat (*anchor points*) terpisah pada setiap elips *use case* agar tidak ada garis asosiasi yang memotong elips lain.
  2. **`Gambar 4.2 Flowchart Transaksi Penjualan POS dan Konversi Multi-Satuan` (`02-flowchart-pos`)**:
     - Hitam-putih penuh.
     - Mengganti label teknis menjadi bahasa proses bisnis: `Mulai` -> `Kasir Memilih Barang, Satuan Jual, dan Jumlah Beli` -> `Sistem Menghitung Jumlah dalam Satuan Dasar dan Menentukan Harga Eceran atau Grosir` -> Keputusan `Apakah Saldo Stok Gudang Mencukupi?` -> Cabang `Tidak`: keluar dari sisi kanan belah ketupat (`exitX=1, exitY=0.5`) menuju kotak `Tampilkan Peringatan Stok Tidak Mencukupi` di jalur kanan, lalu kembali melalui jalur kanan ke input kasir dengan panah yang jelas tanpa menabrak kotak tengah -> Cabang `Ya`: turun lurus ke `Hitung Total Tagihan Nota dan Terima Pembayaran Pelanggan` -> `Simpan Nota Penjualan, Kurangi Saldo Stok Gudang, dan Catat Riwayat Mutasi` -> `Cetak Bukti Pembayaran (Struk Kasir)` -> `Selesai`.
  3. **`Gambar 4.3 Flowchart Penerimaan Barang Pemasok dan Pencatatan Hutang Usaha` (`03-flowchart-procurement`)**:
     - Hitam-putih penuh, seluruh kode seperti `initial_payment_rupiah` dan `total_rupiah` dihapus.
     - Alur keputusan cabang 3 arah dari `Periksa Nilai Pembayaran Awal terhadap Total Tagihan Nota`:
       - Jalur Kiri (`exitX=0, exitY=0.5`): Label `Sama dengan Total Tagihan` -> turun lurus ke kotak kiri `Tetapkan Status Lunas (Sisa Hutang Nol)`.
       - Jalur Tengah (`exitX=0.5, exitY=1`): Label `Sebagian dari Total Tagihan` -> turun lurus ke kotak tengah `Tetapkan Status Cicilan dan Hitung Sisa Hutang`.
       - Jalur Kanan (`exitX=1, exitY=0.5`): Label `Tanpa Pembayaran Awal` -> turun lurus ke kotak kanan `Tetapkan Status Belum Dibayar Sebesar Total Tagihan`.
       - Ketiga kotak tersebut bergabung melalui garis pengumpul horizontal (*merge bar* / *waypoint* terpisah) menuju `Simpan Catatan Hutang Pemasok, Jadwal Jatuh Tempo, dan Riwayat Pembayaran` -> `Selesai`. Tidak ada panah miring yang saling menimpa.
  4. **`Gambar 4.4 Activity Diagram Transaksi Penjualan Kasir` (`04-activity-pos`)**:
     - *Swimlane* 3 kolom hitam-putih (`Kasir`, `Antarmuka Sistem`, `Layanan Pengolahan & Basis Data`).
     - Seluruh teks teknis (`sales.create`, `minimum_quantity`, `base_quantity`, `stock_balances`, `sales & sale_items`) diganti menjadi aktivitas fungsional bahasa Indonesia.
     - Jalur cabang `Tidak Cukup` dari simbol keputusan diarahkan melalui *waypoint* bersih tanpa memotong aktivitas di atasnya, dengan titik masuk terpisah dari jalur utama.
  5. **`Gambar 4.5 Activity Diagram Pengadaan Barang dan Pelunasan Hutang Pemasok` (`05-activity-procurement`)**:
     - *Swimlane* 3 kolom hitam-putih (`Admin Gudang / Pemilik Toko`, `Antarmuka Sistem`, `Layanan Pengolahan & Basis Data`).
     - Menghapus istilah `initial_payment_rupiah`, `due_on`, `PostGoodsReceipt` dan merapikan rute panah antar-*swimlane* dengan belokan siku-siku (*orthogonal waypoints*) yang tidak memotong kotak mana pun.
  6. **`Gambar 4.6 Sequence Diagram Transaksi Penjualan dan Pengurangan Stok` (`06-sequence-pos`)**:
     - Hitam-putih penuh.
     - Mengganti nama objek *lifeline* dari nama *class* kode (`Halaman POS`, `SalesController`, `PostSale Action`, `InventoryLedger`, `Database MySQL`) menjadi komponen arsitektur fungsional (`Kasir`, `Halaman Kasir (POS)`, `Pengendali Penjualan`, `Layanan Transaksi Penjualan`, `Layanan Buku Besar Stok`, `Basis Data Sistem`).
     - Mengganti pesan panah dari nama *method* (`IdempotencyService::run()`, `lockForUpdate()`, `AuditLogger::record()`) menjadi pesan alur kerja berbahasa Indonesia (`1: Kirim rincian belanja pelanggan`, `2: Teruskan permintaan penyimpanan nota`, `3: Periksa keunikan transaksi`, `4: Simpan data utama dan rincian nota penjualan`, `5: Ajukan pengurangan saldo persediaan`, `6: Periksa dan kunci saldo stok barang`, `7: Kurangi saldo stok dan catat riwayat mutasi`, `8: Konfirmasi saldo stok terbaru`, `9: Catat rekam jejak aktivitas kasir`, `10: Kembalikan status transaksi berhasil`, `11: Tampilkan nomor nota dan pratinjau struk`).
  7. **`Gambar 4.7 Sequence Diagram Penerimaan Barang dan Pembayaran Hutang` (`07-sequence-procurement`)**:
     - Hitam-putih penuh, nama *lifeline* dan pesan panah menggunakan bahasa Indonesia non-teknis (`Admin Gudang / Pemilik`, `Halaman Pengadaan & Hutang`, `Pengendali Pengadaan`, `Layanan Penerimaan Barang`, `Layanan Buku Besar Stok`, `Layanan Hutang Usaha`, `Basis Data Sistem`), dengan panah maju (garis utuh) dan panah balikan (garis putus-putus) yang terpisah rapi secara vertikal.
  8. **`Gambar 4.8 Class Diagram Sistem` (`08-class-diagram`) & `Gambar 4.9 Entity Relationship Diagram (ERD)` (`09-erd-diagram`)**:
     - Hitam-putih penuh (kotak putih, garis hitam, *header* kelas/tabel berlatar putih atau abu-abu terang `#F5F5F5` dengan garis pemisah hitam).
     - Mengatur ulang posisi kotak dan jalur konektor ortogonal (`<Array as="points">`) di lorong antar-baris (*horizontal & vertical channels*) sehingga **tidak ada satu pun garis relasi yang menembus kotak kelas/entitas lain**, serta label kardinalitas (`1`, `0..*`, `1..*`) berada di ruang kosong yang mudah dibaca.
  9. **`Gambar 4.10` s.d. `Gambar 4.17` (8 *Wireframe* Rancangan Input & Output)**:
     - Mengubah seluruh *wireframe* menjadi sketsa kerangka hitam-putih (*clean monochrome wireframe*: latar putih `#FFFFFF`, garis batas komponen hitam `#000000`, teks hitam, tanpa warna biru/hijau/merah/gelap).
     - Menghapus label kode teknis di dalam *wireframe* (misalnya menghapus teks `[Konversi Otomatis: 2 x 25 = 50 Kg base_qty]` menjadi `[ Setara 50 Kg Satuan Dasar ]`, menghapus `(stock.adjust)` menjadi `Penyesuaian Stok Fisik`).

### 2.3 Subbab 4.2.2, 4.2.3, 4.2.4, 4.2.5, 4.2.6, serta BAB V (Penutup)
- **Target File Sumber**: `bab4_sec_422.js`, `bab4_sec_423_426.js`, `bab5_sec.js`
- **Perbaikan Spesifik**:
  1. **`Tabel 4.7` & `Tabel 4.8` (Spesifikasi *Use Case*) dan Narasi `4.2.2.1`**: Menghapus seluruh nama tabel/kolom/method teknis di skenario normal dan alternatif.
  2. **Narasi `4.2.2.2` (Perancangan Basis Data) & `Tabel 4.9`–`Tabel 4.20`**: Memastikan narasi pengantar dan kolom "Keterangan" pada tabel struktur data menjelaskan kegunaan bisnis secara mengalir tanpa mengulang potongan kode fungsi PHP.
  3. **Narasi `4.2.2.3` & `4.2.2.4` (`Tabel 4.21`–`Tabel 4.28` Rancangan Output)**: Mengganti seluruh penyebutan kolom teknis (`occurred_at`, `quantity_delta`, `balance_after`, `principal_rupiah`, `paid_rupiah`, `balance_rupiah`, `after_state`, `request_id`) pada kolom "Sumber Data & Aturan Penyajian" di `Tabel 4.21`–`Tabel 4.28` menjadi penjelasan sumber data fungsional berbahasa Indonesia.
  4. **Subbab `4.2.3.2` (Implementasi Antarmuka Pengguna)**: Menghapus URL rute teknis (`/catalog/categories`, `/inventory/movements`, `/procurement/receipts`, `/admin/users`) dan nama *class*/kolom (`PostSale`, `InventoryLedger`, `DocumentStorage`, `is_active = false`, `force_password_change = 1`) dari penjelasan tiap gambar tangkapan layar (`Gambar 4.18`–`Gambar 4.35`).
  5. **Subbab `4.2.4` (`Tabel 4.30`–`Tabel 4.38` Pengujian *Black Box*)**: Menghapus seluruh kode kolom/method/status database (`force_password_change = 1`, `is_active = false`, `GET /admin/users`, `minimum_quantity`, `base_quantity`, `stock_balances`, `quantity_on_hand`, `balance_after`, `inventory.adjusted`, `due_on`, `balance_rupiah`, `unpaid`, `partially_paid`, `paid`) dari kolom Skenario, Data Masukan, dan Hasil yang Diharapkan.
  6. **Subbab `4.2.5`, `4.2.6`, dan `BAB V (Penutup)`**: Menghapus penyebutan `decimal(18,4)`, `PostSale`, `InventoryLedger`, `PostGoodsReceipt`, `RecordPayablePayment`, `lockForUpdate()`, `idempotency_key`, dan kode izin RBAC dari kesimpulan dan evaluasi sistem, sehingga kesimpulan benar-benar menjawab rumusan masalah dari sudut pandang fungsional dan operasional Toko NF Harapan Rumbai.

---

## 3. Urutan Tugas Eksekusi (*Task Breakdown*)

1. **Tugas 1 — Perbaikan Parser Bold/Italic Word (`compile_bab4.js`) & Penulisan Ulang Non-Teknis Subbab 4.1 s.d. 4.2.1 (`bab4_sec_41_421.js`)**
   - Memperbaiki `renderInlineRuns` di `compile_bab4.js` agar mendukung pemformatan bersarang (`**... *...* ...**`) dan menambahkan validasi otomatis anti-bocor `**` pada XML Word.
   - Menulis ulang `bab4_sec_41_421.js` (`4.1`, `4.2.1.1`–`4.2.1.5`, `Tabel 4.1`–`Tabel 4.6`) agar 100% bebas dari kode teknis, nama file, izin RBAC, dan nama kolom database.
2. **Tugas 2 — Pembaruan Mesin Render `.drawio` → `.png` Monokrom & Validator Geometri Anti-Tabrakan (`generate_diagrams_part2.js`)**
   - Memperbarui fungsi konversi `.drawio` ke SVG/PNG agar mendukung penuh `exitX/Y`, `entryX/Y`, `<Array as="points">`, pemisahan *port* panah masuk, lapisan panah di atas kotak, serta validator geometri otomatis yang mendeteksi tabrakan garis terhadap kotak.
3. **Tugas 3 — Pembuatan Ulang & Audit Visual Diagram Proses Monokrom Non-Teknis (`Gambar 4.1`–`Gambar 4.7`) + Revisi Subbab `4.2.2.1`**
   - Membuat ulang `01-use-case-diagram` s.d. `07-sequence-procurement` dalam format monokrom hitam-putih tanpa istilah kode teknis.
   - Memeriksa setiap hasil `.png` menggunakan `read_file` untuk memastikan tidak ada panah yang menimpa, tertutup, atau arahnya ambigu.
   - Merevisi narasi dan `Tabel 4.7`–`Tabel 4.8` di `bab4_sec_422.js`.
4. **Tugas 4 — Pembuatan Ulang & Audit Visual Diagram Struktur & Wireframe Monokrom (`Gambar 4.8`–`Gambar 4.17`) + Revisi Subbab `4.2.2.2`–`4.2.2.4`**
   - Membuat ulang `08-class-diagram`, `09-erd-diagram`, dan `10-wireframe-login` s.d. `17-wireframe-audit-log` dalam format monokrom hitam-putih dengan rute garis bebas tabrakan.
   - Memeriksa setiap hasil `.png` (`08` s.d. `17`) menggunakan `read_file`.
   - Merevisi narasi `4.2.2.2`–`4.2.2.4` dan `Tabel 4.21`–`Tabel 4.28` di `bab4_sec_422.js` agar bebas dari kode teknis.
5. **Tugas 5 — Penulisan Ulang Non-Teknis Subbab `4.2.3`–`4.2.6` (`bab4_sec_423_426.js`) dan `BAB V` (`bab5_sec.js`)**
   - Menghapus seluruh nama rute URL, nama *class/method*, kode status, dan nama kolom teknis dari pembahasan antarmuka (`Gambar 4.18`–`Gambar 4.35`), tabel pengujian *Black Box* (`Tabel 4.30`–`Tabel 4.38`), pembahasan terpadu (`4.2.5`–`4.2.6`), serta Kesimpulan & Saran (`Bab V`).
6. **Tugas 6 — Kompilasi Ulang Dokumen Word (`.docx`) & Verifikasi Akhir Menyeluruh**
   - Menjalankan `compile_bab4.js` untuk menghasilkan `KP/BAB IV HASIL DAN PEMBAHASAN.md`, `KP/BAB V PENUTUP.md`, `KP/LAPORAN_KERJA_PRAKTEK_NORAWRR_BAB_IV.docx`, dan `KP/LAPORAN_KERJA_PRAKTEK_NORAWRR_BAB_V.docx`.
   - Menjalankan skrip audit akhir untuk memastikan: (a) 0 tanda `**` atau `` ` `` yang gagal terformat di `.docx`, (b) 0 istilah kode teknis bocor di narasi/tabel umum, dan (c) seluruh 35 gambar tersemat dengan ukuran dan kualitas tajam.
