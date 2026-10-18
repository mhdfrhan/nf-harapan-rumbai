# Dokumen Perencanaan Sistem NF Harapan Rumbai

## Status

Dokumen pada folder ini merupakan baseline rancangan. Scaffold Laravel tersedia pada `../nf-harapan-rumbai-web`, tetapi migrasi domain dan modul bisnis belum diimplementasikan. Implementasi fitur dilanjutkan bertahap setelah keputusan yang relevan pada `open-questions.md` disetujui.

## Design Read

Reading this as: aplikasi operasional internal untuk pemilik dan karyawan toko yang belum terbiasa menggunakan sistem, dengan bahasa visual bersih, tenang, kontras tinggi, dan berorientasi tugas, menggunakan komponen Livewire yang disesuaikan dengan identitas cream dan hijau tua.

| Parameter taste skill | Nilai | Alasan |
|---|---:|---|
| `DESIGN_VARIANCE` | 4 | Tata letak perlu mudah dipelajari dan konsisten, tetapi tidak terasa seperti templat admin generik. |
| `MOTION_INTENSITY` | 2 | Animasi hanya untuk umpan balik tindakan dan perubahan status. Layar kasir tidak boleh bergerak tanpa alasan. |
| `VISUAL_DENSITY` | 6 | Data transaksi dan persediaan cukup padat, tetapi tetap memiliki ruang, ukuran sentuh, dan hierarki yang nyaman bagi pengguna awam. |

Taste skill tidak digunakan sebagai design system dashboard karena skill tersebut menyatakan dashboard, tabel data, dan alur produk bertahap berada di luar cakupannya. Prinsip taste skill yang tetap digunakan adalah pembacaan brief, anti-default, konsistensi warna, konsistensi bentuk, aksesibilitas, pembatasan motion, kualitas copy, dan pre-flight visual. Fondasi komponen aplikasi menggunakan Flux UI dari starter kit resmi Livewire, lalu disesuaikan dengan token desain proyek.

## Keputusan Dasar

| Area | Keputusan rancangan |
|---|---|
| Jenis aplikasi | Sistem web internal POS dan inventaris untuk satu toko. |
| Framework | Laravel 13 dengan PHP 8.3 atau lebih baru. |
| Frontend | Livewire 4, Blade, Alpine.js seperlunya, Flux UI, Tailwind CSS 4, dan Vite. |
| Basis data | MySQL 8 dengan transaksi InnoDB. |
| Bahasa UI | Bahasa Indonesia dengan istilah operasional yang familier. |
| Tema | Light theme cream yang dikunci untuk rilis awal. Tidak ada pergantian tema per halaman. |
| Aksen | Hijau tua sebagai satu warna aksen utama. Warna status bersifat semantik dan bukan aksen dekoratif. |
| Pengguna awal | Pemilik dan karyawan toko. Pendaftaran publik dinonaktifkan. |
| Data uang | Bilangan bulat rupiah. Tidak menggunakan floating point. |
| Data kuantitas | Desimal presisi tetap dalam satuan dasar, agar 0,25 kg dan 0,5 kg aman dicatat. |
| Integritas transaksi | Penjualan, penerimaan barang, perubahan stok, dan kewajiban disimpan dalam transaksi basis data atomik. |
| Bukti transaksi | Berkas disimpan pada private storage dan ditautkan ke transaksi terkait. |
| Penghapusan | Transaksi yang sudah diposting tidak dihapus. Koreksi menggunakan void, retur, pembayaran pembalik, atau penyesuaian dengan alasan. |
| Metodologi | Tahapan Waterfall: analisis, perancangan, implementasi, pengujian, dan serah terima. |

## Urutan Membaca

1. `product-overview.md`
2. `requirements.md`
3. `roles-and-permissions.md`
4. `domain-rules.md`
5. `user-flows.md`
6. `information-architecture.md`
7. `ui-ux-design-system.md`
8. `screen-specifications.md`
9. `system-architecture.md`
10. `database-design.md`
11. `api-and-integration.md`
12. `reporting-and-audit.md`
13. `security.md`
14. `testing-strategy.md`
15. `data-migration-and-backup.md`
16. `deployment-and-operations.md`
17. `roadmap.md`
18. `open-questions.md`

## Indeks Dokumen

| Dokumen | Fungsi |
|---|---|
| `product-overview.md` | Menetapkan masalah, visi, tujuan, persona, ruang lingkup, dan indikator hasil. |
| `requirements.md` | Menetapkan kebutuhan fungsional, nonfungsional, prioritas, dan kriteria penerimaan. |
| `roles-and-permissions.md` | Menetapkan aktor, hak akses, pemisahan tugas, dan aturan otorisasi. |
| `domain-rules.md` | Menetapkan aturan uang, satuan, harga, stok, transaksi, kewajiban, dan dokumen. |
| `user-flows.md` | Menjelaskan alur pengguna normal dan alur pengecualian. |
| `information-architecture.md` | Menjelaskan struktur navigasi, rute, pencarian, filter, dan breadcrumb. |
| `ui-ux-design-system.md` | Menjelaskan token cream, tipografi, komponen, pola interaksi, aksesibilitas, dan responsive behavior. |
| `screen-specifications.md` | Menetapkan isi, tindakan, state, validasi, dan empty state untuk setiap layar. |
| `system-architecture.md` | Menjelaskan stack Laravel, boundary modul, transaksi, jobs, cache, storage, dan struktur kode. |
| `database-design.md` | Menjelaskan entitas, relasi, kolom, indeks, constraint, dan strategi konsistensi data. |
| `api-and-integration.md` | Menjelaskan kontrak aksi internal, endpoint, idempotensi, ekspor, cetak, dan integrasi yang dilarang. |
| `reporting-and-audit.md` | Menjelaskan definisi laporan, sumber data, filter, audit trail, dan aturan rekonsiliasi. |
| `security.md` | Menjelaskan threat model dan kontrol keamanan berdasarkan Laravel dan OWASP Top 10:2025. |
| `testing-strategy.md` | Menjelaskan pengujian unit, feature, browser, keamanan, performa, UAT, dan traceability. |
| `data-migration-and-backup.md` | Menjelaskan impor Excel, pemindahan bukti, rekonsiliasi saldo awal, backup, dan pemulihan. |
| `deployment-and-operations.md` | Menjelaskan environment, deployment, scheduler, monitoring, dukungan, dan runbook. |
| `roadmap.md` | Menjelaskan fase implementasi, milestone, definition of done, risiko, dan change control. |
| `open-questions.md` | Menampung keputusan bisnis dan teknis yang harus disetujui sebelum kode dibuat. |

## Sumber Kebutuhan

Urutan prioritas sumber ketika terdapat perbedaan adalah:

1. Konfirmasi terbaru dari pemilik proyek atau mitra.
2. Batasan kerja praktik pada Bab I.
3. Draf Bab III dan hasil wawancara mitra.
4. Landasan teori pada Bab II.
5. Asumsi perencanaan yang diberi label `ASUMSI`.

## Traceability

| Prefix | Jenis |
|---|---|
| `GOAL` | Tujuan produk. |
| `FR` | Kebutuhan fungsional. |
| `NFR` | Kebutuhan nonfungsional. |
| `BR` | Business rule. |
| `SEC` | Kontrol keamanan. |
| `TEST` | Skenario pengujian. |
| `DEC` | Keputusan arsitektur. |
| `OPEN` | Pertanyaan terbuka. |

Setiap fitur implementasi harus dapat ditelusuri minimal ke satu `FR`, satu atau lebih `BR`, satu kebijakan izin, dan satu skenario `TEST`.

## Istilah Utama

| Istilah | Makna dalam proyek |
|---|---|
| Pemilik | Ibu Emi Erawati sebagai pemilik dan penanggung jawab utama toko. |
| Karyawan | Pengguna operasional yang diberi akun oleh pemilik. |
| POS | Modul pencatatan penjualan pada titik transaksi. |
| Produk | Barang yang dijual dan memiliki satuan dasar persediaan. |
| Satuan jual | Bentuk kuantitas yang dapat dipilih saat menjual, misalnya pcs, kg, 0,5 kg, atau 0,25 kg. |
| Satuan dasar | Satuan penyimpanan stok yang menjadi sumber kebenaran kuantitas. |
| Harga eceran | Harga jual untuk transaksi eceran. |
| Harga grosir | Harga jual untuk transaksi grosir berdasarkan aturan kuantitas atau pilihan berizin. |
| Barang masuk | Penerimaan barang dari pemasok yang menambah stok. |
| Kewajiban | Nilai yang masih harus dibayar kepada pemasok terkait penerimaan atau pembelian. |
| Bukti | Nota, foto, dokumen, atau berkas pendukung yang ditautkan ke transaksi. |
| Posting | Tindakan final yang membuat transaksi memengaruhi stok, kewajiban, atau laporan. |
| Void | Pembatalan terkontrol yang mempertahankan jejak transaksi dan membuat pembalik bila diperlukan. |

## Gate Sebelum Implementasi

Implementasi belum boleh dimulai sebelum:

- Semua pertanyaan berlabel `BLOCKER` dalam `open-questions.md` dijawab.
- Ruang lingkup MVP disetujui.
- Peran pengguna disetujui.
- Aturan satuan dan harga grosir disetujui.
- Pilihan deployment disetujui.
- Strategi migrasi data awal disetujui.
- Arah UI cream dan hijau tua disetujui.
