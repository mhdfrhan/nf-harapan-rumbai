# Review Dosen Pembimbing terhadap Bab I

## Ringkasan Penilaian

Bab I telah memiliki topik, objek, dan arah solusi yang relevan. Persoalan utamanya bukan kekurangan fitur, melainkan belum kuatnya hubungan logis antara kondisi lapangan, identifikasi masalah, rumusan masalah, tujuan, batasan, dan metode pengembangan. Revisi telah dilakukan untuk membentuk alur argumentasi yang lebih akademik dan konsisten.

Status setelah revisi: **layak dilanjutkan dengan revisi data pendukung**. Struktur Bab I sudah lebih terarah, tetapi bukti empiris mengenai kondisi awal toko masih perlu dilengkapi pada tahap pengumpulan data agar klaim dalam laporan tidak hanya bersifat deskriptif.

## Hasil Review Utama dan Perbaikan

### 1. Metode pengembangan sebelumnya belum tegas

**Temuan:** Pada versi awal, batasan hanya menyebut “metode pengembangan perangkat lunak tertentu”. Kalimat tersebut masih berupa placeholder dan belum memberi tahu pembaca bagaimana sistem akan dikembangkan. Selain itu, istilah metode pengembangan belum dihubungkan dengan sistematika laporan.

**Penilaian pembimbing:** Ini merupakan masalah substansial karena pembaca harus sudah memperoleh gambaran pendekatan penyelesaian masalah sejak Bab I. SDLC adalah kerangka siklus pengembangan, sedangkan Waterfall merupakan model pelaksanaan tahapannya. Keduanya tidak seharusnya dipakai sebagai istilah yang saling menggantikan tanpa penjelasan.

**Perbaikan:** Latar belakang sekarang menyatakan bahwa pengembangan menggunakan pendekatan *System Development Life Cycle* (SDLC) dengan model Waterfall. Tahap yang masuk ruang lingkup kerja praktik ditegaskan sebagai analisis kebutuhan, perancangan, implementasi, dan pengujian. Pemeliharaan berkelanjutan dinyatakan di luar ruang lingkup. Penggunaan SDLC juga telah dimasukkan ke batasan, manfaat bagi penulis, dan sistematika Bab II–IV.

### 2. Identifikasi masalah terlalu banyak dan saling tumpang tindih

**Temuan:** Versi awal memiliki enam butir. Masalah transaksi dan perbedaan satuan dipisahkan, padahal keduanya merupakan satu kelompok masalah. Demikian pula, pengelolaan supplier dan pengingat jatuh tempo dipisahkan, padahal pengingat merupakan bagian dari pengelolaan kewajiban kepada supplier.

**Penilaian pembimbing:** Identifikasi masalah seharusnya berisi akar persoalan, bukan daftar semua fitur yang akan dibuat. Jika setiap fitur ditulis sebagai masalah terpisah, Bab I terlihat berorientasi pada solusi sebelum akar masalah dirumuskan.

**Perbaikan:** Identifikasi diringkas menjadi empat masalah inti:

1. transaksi serta variasi satuan dan harga;
2. integrasi dan pemantauan stok;
3. supplier, status kewajiban, dan pengingat jatuh tempo; serta
4. laporan terintegrasi untuk pengambilan keputusan.

Jumlah ini memenuhi arahan maksimal tiga sampai lima butir dan cukup mewakili ruang lingkup sistem.

### 3. Hubungan masalah, rumusan, dan tujuan belum sepenuhnya satu banding satu

**Temuan:** Pada versi awal, enam identifikasi masalah diarahkan menjadi empat rumusan masalah dan empat tujuan. Akibatnya, pembaca harus menebak butir mana yang digabung. Istilah yang digunakan juga berubah-ubah, misalnya “laporan supplier”, “manajemen kredit”, dan “utang pembayaran barang”.

**Penilaian pembimbing:** Dalam penulisan ilmiah, keterlacakan antarsubbagian harus terlihat jelas. Setiap rumusan masalah harus menjawab satu kelompok identifikasi, dan setiap tujuan harus menjadi bentuk pernyataan dari rumusan tersebut.

**Perbaikan:** Empat identifikasi, empat rumusan, dan empat tujuan sekarang memiliki urutan serta objek yang sama. Istilah diseragamkan menjadi “kewajiban pembayaran kepada supplier” untuk menghindari ketidakjelasan apakah kredit diberikan kepada pelanggan atau berasal dari pemasok.

| Identifikasi masalah | Rumusan masalah | Tujuan |
| --- | --- | --- |
| Transaksi, satuan, dan harga belum terintegrasi | Bagaimana membangun POS yang menangani eceran/grosir dan variasi satuan? | Membangun POS dengan dukungan transaksi dan satuan tersebut |
| Stok belum otomatis mengikuti transaksi | Bagaimana membangun pengelolaan produk dan stok terintegrasi? | Membangun pengelolaan produk dan stok terintegrasi |
| Kewajiban supplier dan jatuh tempo belum terkelola | Bagaimana membangun pengelolaan supplier dan kewajiban pembayaran? | Membangun modul supplier, status pembayaran, dan pengingat |
| Laporan belum terintegrasi | Bagaimana menghasilkan laporan yang akurat dan informatif? | Menghasilkan laporan untuk mendukung keputusan |

### 4. Latar belakang terlalu berorientasi pada daftar fitur

**Temuan:** Paragraf penutup versi awal memuat daftar fitur yang sangat panjang. Kesenjangan penelitian dinyatakan secara luas dengan frasa “belum banyak”, tetapi belum didukung perbandingan sistematis terhadap seluruh penelitian yang dikutip.

**Penilaian pembimbing:** Latar belakang perlu bergerak dari konteks umum, kondisi objek, dampak masalah, dukungan penelitian terdahulu, kekhususan kebutuhan objek, lalu pendekatan penyelesaian. Klaim kebaruan tidak perlu dibesar-besarkan untuk laporan kerja praktik.

**Perbaikan:** Narasi disusun ulang mengikuti alur tersebut. Klaim kebaruan diturunkan menjadi “kekhususan kebutuhan operasional” Toko NF Harapan Rumbai, yaitu kombinasi transaksi eceran/grosir, variasi satuan, stok, dan kewajiban supplier dalam satu aplikasi.

### 5. Batasan sistem belum operasional

**Temuan:** Batasan awal mencampur fitur, teknologi yang belum disebutkan, metode yang belum diputuskan, dan ruang lingkup pengujian. Istilah “real-time” juga dipakai tanpa definisi teknis.

**Penilaian pembimbing:** Batasan harus menjelaskan apa yang dikerjakan dan tidak dikerjakan. Istilah teknis yang berpotensi menimbulkan tuntutan pembuktian perlu digunakan secara hati-hati.

**Perbaikan:** Ruang lingkup modul, jenis satuan, bentuk pemantauan, pendekatan SDLC, tahap Waterfall, dan jenis pengujian telah dipisahkan secara eksplisit. Istilah “real-time” pada bagian utama diganti dengan kondisi stok yang diperbarui berdasarkan transaksi tercatat atau informasi yang aktual.

### 6. Sistematika Bab I tidak konsisten dengan daftar isi

**Temuan:** Daftar isi menyebut Bab II Landasan Teori, Bab III Metode Pelaksanaan, dan Bab IV Hasil dan Pembahasan. Namun, sistematika pada Bab I sebelumnya menyebut Bab II Gambaran Umum, Bab III Landasan Teori, dan Bab IV Analisis dan Perancangan Sistem.

**Penilaian pembimbing:** Ketidakkonsistenan ini merupakan kesalahan struktur yang mudah terlihat oleh penguji dan dapat menimbulkan kebingungan saat bab berikutnya ditulis.

**Perbaikan:** Sistematika Bab I telah diselaraskan dengan daftar isi dan judul bab yang tersedia dalam dokumen. Penjelasan Bab III kini memuat teknik pengumpulan data serta SDLC-Waterfall, sedangkan Bab IV memuat analisis, desain, implementasi, dan pengujian.

### 7. Gaya sitasi dan istilah perlu distandardisasi

**Temuan:** Sitasi naratif sebelumnya ditulis seperti “(Kumaini et al., 2025) menemukan”, yang kurang tepat secara tata tulis. Nama penulis juga tidak konsisten antara naskah dan daftar pustaka. Istilah pemasok, supplier, kredit, utang, dan kewajiban digunakan bergantian.

**Penilaian pembimbing:** Konsistensi istilah memengaruhi ketepatan makna. Sitasi naratif sebaiknya menempatkan nama penulis sebagai bagian dari kalimat, sedangkan sitasi dalam tanda kurung dipakai ketika nama penulis bukan bagian dari struktur kalimat.

**Perbaikan:** Sitasi diubah menjadi bentuk seperti “Kumaini et al. (2025)”. Entri Firmansyah et al. (2026) pada daftar pustaka juga telah diperbaiki berdasarkan metadata artikel referensi. Istilah “kewajiban pembayaran kepada supplier” dipakai secara konsisten pada bagian masalah, tujuan, batasan, dan manfaat.

## Catatan Revisi Lanjutan yang Memerlukan Data Lapangan

Catatan berikut belum dapat diperbaiki secara faktual tanpa hasil observasi atau wawancara. Bagian ini perlu dilengkapi sebelum seminar atau penyerahan laporan akhir.

1. **Tambahkan bukti kondisi awal.** Cantumkan cara pencatatan yang digunakan, siapa yang mencatat, dokumen apa yang dipakai, dan contoh alur transaksi. Jika tersedia, tambahkan angka seperti rata-rata transaksi per hari, jumlah produk, frekuensi selisih stok, atau jumlah tagihan supplier per bulan.
2. **Jelaskan sumber kebutuhan sistem.** Nyatakan tanggal observasi/wawancara, jabatan narasumber, serta kebutuhan yang disampaikan. Informasi ini dapat ditempatkan secara rinci pada Bab III dan diringkas satu atau dua kalimat pada latar belakang.
3. **Tentukan aturan konversi satuan.** Sistem harus menjelaskan apakah satu produk memiliki stok dasar kilogram/pcs, bagaimana konversi dus atau pak dilakukan, dan bagaimana transaksi ½ kg atau ¼ kg mengurangi stok. Ini akan menjadi titik kritis pada desain basis data dan pengujian.
4. **Definisikan harga eceran dan grosir.** Tentukan apakah harga grosir dipilih manual atau aktif berdasarkan batas kuantitas tertentu. Tanpa aturan ini, kebutuhan fungsional dan skenario pengujian akan ambigu.
5. **Definisikan pengingat jatuh tempo.** Jelaskan kapan pengingat muncul, kepada aktor siapa, melalui media apa, dan apakah sistem hanya menampilkan notifikasi pada dashboard atau mengirim pesan melalui layanan eksternal.
6. **Tetapkan indikator keberhasilan.** Minimal gunakan indikator ketepatan fungsi berdasarkan skenario *black box testing*. Jika ingin menyatakan sistem “mempercepat” atau “meningkatkan efisiensi”, tambahkan perbandingan waktu proses sebelum dan sesudah atau pengujian penerimaan pengguna.
7. **Periksa ulang metadata referensi.** Berkas referensi Saftini menunjukkan metadata tahun, volume, nomor, dan halaman yang tidak sepenuhnya sama dengan entri daftar pustaka saat ini. Metadata harus dicocokkan dengan halaman resmi artikel atau PDF asli sebelum laporan difinalkan.
8. **Perbarui daftar isi setelah tata letak final.** Nomor halaman dan tautan daftar isi saat ini berasal dari ekspor dokumen sebelumnya. Regenerasi daftar isi setelah semua bab selesai agar judul dan halaman sesuai.
9. **Periksa tahun pada halaman awal.** Sampul mencantumkan 2026, sedangkan halaman pengesahan mencantumkan Februari 2025. Pilih tahun yang sesuai dengan pelaksanaan dan jadwal pengesahan.

## Kesimpulan Pembimbing

Bab I setelah revisi sudah mempunyai alur yang lebih kuat: masalah lapangan mengarah pada empat kebutuhan sistem, keempat kebutuhan tersebut diterjemahkan menjadi rumusan dan tujuan yang setara, lalu pelaksanaannya dibatasi melalui SDLC dengan model Waterfall. Prioritas berikutnya bukan menambah fitur, melainkan memperkuat bukti kondisi awal dan mendefinisikan aturan bisnis secara terukur agar Bab III dan Bab IV dapat diuji secara akademik.
