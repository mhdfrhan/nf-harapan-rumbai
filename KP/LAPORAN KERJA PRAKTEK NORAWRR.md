**eLAPORAN KERJA PRAKTEK**

**RANCANG BANGUN SISTEM POINT OF SALE DAN INVENTARIS TOKO GROSIR NF HARAPAN RUMBAI BERBASIS WEB**

_Disusun Sebagai Salah Satu Syarat_

_Menyelesaikan Mata Kuliah_

_Kerja Praktek_

**Oleh:**

**Naurah Zahrawain**

**230401111**

**PROGRAM STUDI TEKNIK INFORMATIKA**

**FAKULTAS ILMU KOMPUTER**

**UNIVERSITAS MUHAMMADIYAH RIAU**

**PEKANBARU**

**2026**

# HALAMAN PENGESAHAN LAPORAN KERJA PRAKTEK

**RANCANG BANGUN SISTEM POINT OF SALE DAN INVENTARIS TOKO GROSIR NF HARAPAN RUMBAI BERBASIS WEB**

Oleh:

Nama : Naurah Zahrawain

NIM : 230401111

Pekanbaru, Februari 2025

| **Penguji I**<br><br>**Nama dosen**<br><br>**NIDN:** | **Penguji II**<br><br>**Nama dosen**<br><br>**NIDN:** |
| ---------------------------------------------------- | ----------------------------------------------------- |

**Pembimbing**

**Assoc. Prof. Dr. Januar Al Amien, S.Kom., M.Kom**

**NIDN: 1030018001**

**Mengetahui,  
Ketua Program Studi Teknik Informatika**

**Yulia Fatma, S. Kom., M. Cs**

**NIDN: 1018079001**

# KETERANGAN TELAH MENYELESAIKAN KERJA PRAKTEK

# KATA PENGANTAR

# ABSTRAK

# ABSTRACT

DAFTAR ISI

[HALAMAN PENGESAHAN LAPORAN KERJA PRAKTEK i](#_Toc233884541)

[KETERANGAN TELAH MENYELESAIKAN KERJA PRAKTEK ii](#_Toc233884542)

[KATA PENGANTAR iii](#_Toc233884543)

[ABSTRAK iv](#_Toc233884544)

[ABSTRACT v](#_Toc233884545)

[DAFTAR GAMBAR viii](#_Toc233884546)

[DAFTAR TABEL ix](#_Toc233884547)

[BAB I PENDAHULUAN 1](#_Toc233884548)

[1.1. Latar Belakang 1](#_Toc233884549)

[1.2. Identifikasi Masalah 3](#_Toc233884550)

[1.3. Rumusan Masalah 4](#_Toc233884551)

[1.4. Batasan Kerja Praktik 5](#_Toc233884552)

[1.5. Tujuan Kerja Praktik 5](#_Toc233884553)

[1.6. Manfaat Kerja Praktik 6](#_Toc233884554)

[1.6.1. Bagi Toko NF Harapan Rumbai 6](#_Toc233884555)

[1.6.2. Bagi Penulis 7](#_Toc233884556)

[1.6.3. Bagi Kampus/Program Studi 7](#_Toc233884557)

[1.7. Sistematika Penulisan 7](#_Toc233884558)

[BAB II LANDASAN TEORI 9](#_Toc233884559)

[BAB III METODE PELAKSANAAN 10](#_Toc233884560)

[BAB IV HASIL DAN PEMBAHASAN 11](#_Toc233884561)

[BAB V PENUTUP 12](#_Toc233884562)

[DAFTAR PUSTAKA 13](#_Toc233884563)

# DAFTAR GAMBAR

# DAFTAR TABEL

# BAB I

PENDAHULUAN

## Latar Belakang

Perkembangan teknologi informasi telah membawa perubahan pada berbagai sektor usaha, termasuk usaha ritel dan perdagangan sembako. Proses bisnis seperti pencatatan transaksi penjualan, pengelolaan stok barang, dan pencatatan kewajiban kepada pemasok dapat didukung oleh sistem informasi agar data tersimpan secara lebih terstruktur, mudah ditelusuri, dan dapat digunakan untuk menghasilkan informasi operasional secara cepat. Digitalisasi proses bisnis tersebut penting bagi pelaku usaha karena ketepatan informasi berpengaruh terhadap kelancaran pelayanan dan pengambilan keputusan.

Toko NF Harapan Rumbai merupakan salah satu usaha dagang yang bergerak di bidang penjualan kebutuhan pokok (sembako) yang berlokasi di Kecamatan Rumbai, Kota Pekanbaru. Toko ini melayani penjualan secara eceran maupun grosir, dengan karakteristik barang yang cukup kompleks, yaitu barang dengan satuan pcs (buah/dus/pak) dan barang dengan satuan berat (kilogram) yang juga dapat dijual secara pecahan seperti ½ kg atau ¼ kg. Karakteristik penjualan seperti ini menuntut sistem pencatatan yang fleksibel, karena satu jenis barang dapat memiliki lebih dari satu satuan dan lebih dari satu tingkat harga (harga eceran dan harga grosir) sekaligus.

Proses pencatatan transaksi penjualan, pengelolaan stok, serta pencatatan pembelian dari pemasok (supplier) di Toko NF Harapan Rumbai masih dilakukan secara manual atau menggunakan pencatatan sederhana yang belum terintegrasi. Kondisi tersebut menyebabkan transaksi di kasir memerlukan waktu lebih lama, terutama ketika petugas menangani variasi satuan dan harga. Pembaruan stok juga tidak langsung terhubung dengan transaksi sehingga pemilik toko kesulitan mengetahui ketersediaan barang dan menelusuri selisih antara stok fisik dengan catatan. Selain itu, pencatatan kewajiban kepada supplier belum dilengkapi status pembayaran dan pengingat jatuh tempo. Data penjualan, stok, dan supplier yang tersebar turut menyebabkan penyusunan laporan memerlukan rekapitulasi ulang dan berisiko menghasilkan informasi yang tidak konsisten.

Permasalahan serupa ditemukan pada penelitian terdahulu. Kumaini et al. (2025) menyatakan bahwa pencatatan manual pada toko sembako menyebabkan rendahnya efisiensi dan akurasi data, sehingga dikembangkan sistem POS berbasis web untuk mengintegrasikan transaksi penjualan, manajemen stok, dan pelaporan. Saftini (2025) juga menunjukkan bahwa sistem informasi penjualan sembako berbasis web dapat mempercepat transaksi dan meminimalkan kesalahan pencatatan dibandingkan dengan cara manual. Sementara itu, Yulianti et al. (2024) menunjukkan pentingnya sistem informasi penjualan yang dapat menghasilkan laporan transaksi secara otomatis untuk mendukung pengambilan keputusan pemilik usaha.

Dalam pengelolaan persediaan, Jose et al. (2025) menjelaskan bahwa pengelolaan stok secara manual meningkatkan risiko kesalahan pencatatan dan ketidaktepatan pengambilan keputusan. Saputri dan Lutfiyani (2025) juga menunjukkan bahwa sistem manajemen stok berbasis web dapat meningkatkan akurasi pencatatan dan memudahkan pemantauan stok. Firmansyah et al. (2026) menekankan bahwa pemisahan sistem inventaris dan POS dapat menyebabkan ketidaksinkronan data stok serta rendahnya efisiensi operasional. Selain itu, Khisanah dan Fatmawati (2024) menunjukkan bahwa sistem manajemen berbasis web dapat meningkatkan akurasi laporan stok bagi pemilik usaha grosir.

Berdasarkan penelitian terdahulu tersebut, sistem POS dan inventaris berbasis web telah banyak digunakan untuk meningkatkan efisiensi transaksi dan akurasi stok. Namun, kebutuhan operasional Toko NF Harapan Rumbai memiliki kekhususan berupa pengelolaan penjualan eceran dan grosir, variasi satuan pcs dan berat termasuk penjualan pecahan, serta pemantauan kewajiban dan jatuh tempo pembayaran kepada supplier dalam satu sistem. Oleh karena itu, kerja praktik ini berfokus pada perancangan dan pembangunan sistem informasi POS dan manajemen inventaris yang mengintegrasikan transaksi, produk dan stok, supplier dan kewajiban pembayaran, serta pelaporan dalam satu aplikasi berbasis web.

Agar pengembangan sistem dilakukan secara terarah dan terdokumentasi, kerja praktik ini menggunakan pendekatan *System Development Life Cycle* (SDLC) dengan model Waterfall. SDLC merupakan kerangka pengembangan sistem yang mencakup tahapan perencanaan, analisis, perancangan, implementasi, dan pemeliharaan, sedangkan Waterfall mengatur pelaksanaan tahapan tersebut secara sistematis dan berurutan (Rezki et al., 2023). Dalam ruang lingkup kerja praktik ini, tahapan yang dilaksanakan meliputi analisis kebutuhan, perancangan sistem, implementasi, dan pengujian menggunakan metode *black box testing*. Dengan pendekatan tersebut, sistem yang dibangun diharapkan sesuai dengan kebutuhan operasional Toko NF Harapan Rumbai dan dapat diuji berdasarkan fungsi yang telah ditetapkan.

## Identifikasi Masalah

Berdasarkan uraian latar belakang di atas, maka dapat diidentifikasi beberapa permasalahan sebagai berikut:

- Pencatatan transaksi penjualan belum terintegrasi dan belum mendukung pengelolaan variasi satuan barang serta harga eceran dan grosir secara terstruktur, sehingga proses pelayanan dan pencatatan transaksi menjadi kurang efisien.
- Data stok belum diperbarui secara otomatis berdasarkan transaksi barang masuk dan penjualan, sehingga pemilik toko kesulitan memantau ketersediaan dan menelusuri selisih stok.
- Pencatatan data supplier dan kewajiban pembayaran belum dilengkapi status pembayaran serta pengingat jatuh tempo, sehingga pemantauan utang dagang berisiko terlewat.
- Laporan penjualan, stok, dan kewajiban kepada supplier belum dapat dihasilkan secara terintegrasi, cepat, dan informatif untuk mendukung pengambilan keputusan pemilik toko.

## Rumusan Masalah

Berdasarkan identifikasi masalah tersebut, maka rumusan masalah dalam kerja praktik ini adalah sebagai berikut:

- Bagaimana merancang dan membangun sistem informasi Point of Sale (POS) berbasis web yang dapat menangani transaksi eceran dan grosir dengan variasi satuan pcs dan berat, termasuk penjualan pecahan, pada Toko NF Harapan Rumbai?
- Bagaimana merancang dan membangun pengelolaan produk serta stok yang terintegrasi dengan transaksi sehingga kondisi persediaan dapat dipantau secara aktual?
- Bagaimana merancang dan membangun pengelolaan data supplier dan kewajiban pembayaran yang mencatat status pembayaran serta memberikan pengingat jatuh tempo?
- Bagaimana menghasilkan laporan penjualan, stok, dan kewajiban kepada supplier secara akurat dan informatif untuk mendukung pengambilan keputusan pemilik toko?

## Batasan Kerja Praktik

Agar pembahasan dalam kerja praktik ini lebih terarah dan tidak menyimpang dari tujuan yang telah ditetapkan, maka penulis membatasi ruang lingkup permasalahan sebagai berikut:

- Sistem yang dibangun merupakan sistem informasi Point of Sale (POS) dan manajemen inventaris berbasis web, dengan studi kasus pada Toko NF Harapan Rumbai, Pekanbaru.
- Sistem mencakup modul kasir untuk transaksi eceran dan grosir; pengelolaan produk, kategori, harga, dan satuan; pengelolaan stok; pengelolaan data supplier dan kewajiban pembayaran; pengingat jatuh tempo; serta laporan penjualan, stok, dan kewajiban kepada supplier.
- Satuan barang yang ditangani dibatasi pada satuan pcs dan berat, termasuk penjualan pecahan seperti ½ kg dan ¼ kg sesuai kebutuhan toko.
- Informasi stok dan penjualan disajikan melalui dashboard atau visualisasi data untuk membantu pemilik toko memantau kondisi operasional.
- Sistem tidak membahas modul penggajian karyawan, akuntansi keuangan perusahaan secara menyeluruh (neraca dan laporan laba rugi lengkap), maupun integrasi dengan payment gateway pihak ketiga.
- Pengembangan sistem menggunakan pendekatan SDLC dengan model Waterfall yang mencakup analisis kebutuhan, perancangan, implementasi, dan pengujian. Pemeliharaan sistem secara berkelanjutan berada di luar ruang lingkup kerja praktik.
- Pengujian sistem dibatasi pada *black box testing* untuk memeriksa kesesuaian fungsi sistem dengan kebutuhan yang telah ditetapkan.

## Tujuan Kerja Praktik

Tujuan dari pelaksanaan kerja praktik ini adalah sebagai berikut:

- Merancang dan membangun sistem informasi Point of Sale (POS) berbasis web yang mampu menangani transaksi eceran dan grosir dengan variasi satuan pcs dan berat, termasuk penjualan pecahan.
- Merancang dan membangun pengelolaan produk dan stok yang terintegrasi dengan transaksi serta dilengkapi informasi pemantauan persediaan.
- Merancang dan membangun pengelolaan data supplier dan kewajiban pembayaran yang dilengkapi status pembayaran serta pengingat jatuh tempo.
- Menghasilkan laporan penjualan, stok, dan kewajiban kepada supplier yang akurat dan informatif untuk mendukung pengambilan keputusan pemilik toko.

## Manfaat Kerja Praktik

Adapun manfaat yang diharapkan dari pelaksanaan kerja praktik ini adalah sebagai berikut:

### Bagi Toko NF Harapan Rumbai

- Mempermudah dan mempercepat proses transaksi penjualan di kasir, baik untuk penjualan eceran maupun grosir.
- Membantu pemilik toko memantau kondisi stok berdasarkan transaksi yang tercatat sehingga risiko kekurangan atau penumpukan barang dapat dikurangi.
- Mempermudah pemantauan kewajiban pembayaran kepada supplier beserta jatuh temponya.
- Menyediakan laporan penjualan, stok, dan kewajiban kepada supplier secara lebih cepat dan akurat sebagai dasar pengambilan keputusan bisnis.

### Bagi Penulis

- Menambah pengetahuan dan pengalaman dalam menerapkan ilmu yang telah dipelajari selama perkuliahan ke dalam permasalahan nyata di dunia kerja.
- Meningkatkan kemampuan dalam menerapkan tahapan SDLC untuk menganalisis kebutuhan, merancang, mengimplementasikan, dan menguji sistem informasi berbasis web.

### Bagi Kampus/Program Studi

- Menjadi bahan referensi bagi mahasiswa lain yang akan melaksanakan kerja praktik maupun tugas akhir dengan topik yang berkaitan.
- Mempererat kerja sama antara pihak kampus dengan instansi/usaha mitra kerja praktik.

## Sistematika Penulisan

Untuk mempermudah pembahasan dalam laporan kerja praktik ini, penulis menyusun sistematika penulisan sebagai berikut:

BAB I PENDAHULUAN

Bab ini berisi latar belakang, identifikasi masalah, rumusan masalah, batasan kerja praktik, tujuan kerja praktik, manfaat kerja praktik, serta sistematika penulisan laporan.

BAB II LANDASAN TEORI

Bab ini berisi teori-teori yang mendukung kerja praktik, meliputi sistem informasi, Point of Sale (POS), manajemen inventaris, aplikasi berbasis web, basis data, pemodelan UML, SDLC, model Waterfall, dan *black box testing*, serta penelitian terdahulu yang relevan.

BAB III METODE PELAKSANAAN

Bab ini berisi gambaran umum Toko NF Harapan Rumbai, teknik pengumpulan data, analisis proses bisnis yang sedang berjalan, serta tahapan pengembangan sistem menggunakan pendekatan SDLC dengan model Waterfall.

BAB IV HASIL DAN PEMBAHASAN

Bab ini membahas hasil analisis kebutuhan, perancangan sistem menggunakan UML, perancangan basis data dan antarmuka, implementasi sistem, serta hasil pengujian fungsi menggunakan *black box testing*.

BAB V PENUTUP

Bab ini berisi kesimpulan dari hasil kerja praktik yang telah dilaksanakan serta saran-saran yang bersifat membangun untuk pengembangan sistem lebih lanjut.

# BAB II

LANDASAN TEORI

Bab ini menguraikan konsep-konsep yang menjadi dasar dalam menganalisis, merancang, membangun, dan menguji sistem *Point of Sale* (POS) dan manajemen inventaris berbasis web pada Toko NF Harapan Rumbai. Landasan teori disusun berdasarkan kebutuhan proyek yang telah dijelaskan pada Bab I dan didukung oleh artikel ilmiah yang relevan. Teori yang dibahas meliputi sistem informasi, POS, manajemen inventaris, pengelolaan supplier dan kewajiban pembayaran, aplikasi berbasis web, basis data, *Unified Modeling Language* (UML), *System Development Life Cycle* (SDLC), model Waterfall, serta *black box testing*.

## Sistem Informasi

Sistem informasi merupakan kesatuan komponen yang bekerja sama untuk mengumpulkan, mengolah, menyimpan, dan menyajikan data menjadi informasi yang dapat digunakan dalam kegiatan operasional maupun pengambilan keputusan. Rezki et al. (2023) menjelaskan bahwa sistem informasi dibentuk oleh empat bagian utama, yaitu perangkat lunak, perangkat keras, infrastruktur, dan sumber daya manusia yang terlatih. Khisanah dan Fatmawati (2024) menambahkan bahwa sistem informasi manajemen melibatkan komponen-komponen yang saling berinteraksi untuk menghimpun, memproses, menyimpan, dan menyebarkan informasi guna mendukung pengelolaan serta pengambilan keputusan.

Dalam lingkungan usaha ritel, sistem informasi tidak hanya berfungsi sebagai media penyimpanan data, tetapi juga menghubungkan proses yang sebelumnya berjalan secara terpisah. Arista dan Nugroho (2023) menunjukkan bahwa pencatatan transaksi penjualan dan pembelian secara konvensional berisiko menyebabkan dokumen hilang atau rusak serta menyulitkan pembuatan laporan. Permana dan Wijana (2023) juga menjelaskan bahwa pencatatan manual pada toko kelontong dapat menimbulkan kesulitan dalam mengetahui stok, kesalahan pencatatan, dan keterlambatan pembuatan laporan penjualan. Integrasi diperlukan agar data transaksi, stok, produk, dan laporan menggunakan sumber data yang sama. Firmansyah et al. (2026) menunjukkan bahwa pemisahan sistem inventaris dan POS dapat menimbulkan ketidaksinkronan antara catatan stok dan jumlah barang yang sebenarnya. Oleh karena itu, sistem informasi pada kerja praktik ini diarahkan untuk mengintegrasikan proses penjualan, perubahan stok, pengelolaan supplier, kewajiban pembayaran, dan penyusunan laporan.

Komponen sistem informasi dalam proyek ini meliputi pengguna sistem, prosedur operasional, data, dan teknologi. Pengguna sistem terdiri atas pihak yang berwenang menjalankan transaksi dan mengelola data toko. Prosedur operasional mencakup pencatatan penjualan, barang masuk, perubahan persediaan, pembayaran kepada supplier, dan pembuatan laporan. Data yang dikelola meliputi data produk, kategori, satuan, harga, stok, transaksi, supplier, kewajiban pembayaran, serta pengguna. Teknologi berfungsi sebagai sarana untuk menjalankan seluruh prosedur tersebut melalui aplikasi berbasis web.

## *Point of Sale* (POS)

*Point of Sale* (POS) merupakan sistem yang digunakan untuk mendukung pencatatan transaksi pada titik terjadinya penjualan. Zaitunnisaa dan Arifin (2021) menjelaskan bahwa POS berbasis web dapat membantu kegiatan operasional, mempercepat pelayanan, dan mengurangi kesalahan pengelolaan data transaksi. Dalam penelitian Kumaini et al. (2025), POS berbasis web digunakan untuk mengotomatisasi transaksi penjualan, pengelolaan stok, dan pembuatan laporan sehingga aktivitas operasional toko dapat dilakukan secara lebih sistematis. Hasil penelitian tersebut juga menunjukkan bahwa integrasi fungsi transaksi dan stok membantu mengurangi permasalahan pencatatan manual serta meningkatkan efisiensi operasional toko. Septiani et al. (2026) turut menunjukkan bahwa integrasi POS, pengelolaan stok bahan baku, dan penyusunan laporan dapat mempercepat pencatatan serta mengurangi kesalahan yang terjadi pada proses manual.

Fungsi utama POS dalam kerja praktik ini adalah menangani proses transaksi mulai dari pemilihan produk, penentuan jumlah dan satuan, penerapan harga, perhitungan total, penyimpanan transaksi, hingga penyediaan data untuk laporan penjualan. Setiap transaksi yang berhasil disimpan harus menjadi dasar perubahan persediaan agar catatan penjualan dan stok tetap konsisten. Prinsip integrasi ini sejalan dengan Firmansyah et al. (2026), yang menghubungkan transaksi POS dengan pembaruan data inventaris secara otomatis untuk menghindari perbedaan data antarmodul.

Kebutuhan POS pada Toko NF Harapan Rumbai memiliki karakteristik khusus karena transaksi dilakukan secara eceran maupun grosir. Barang juga dapat dijual menggunakan satuan pcs atau satuan berat, termasuk pecahan 1/2 kg dan 1/4 kg. Oleh sebab itu, POS yang dirancang perlu menyimpan aturan satuan dan harga secara terstruktur agar jumlah barang yang dijual dapat dikonversi menjadi pengurang stok yang konsisten. Dengan demikian, POS pada proyek ini tidak hanya berfungsi sebagai kalkulator transaksi, tetapi menjadi sumber pencatatan utama yang terhubung dengan persediaan dan laporan.

## Manajemen Inventaris

Inventaris atau persediaan merupakan barang yang disimpan untuk mendukung kegiatan penjualan. Pengelolaan inventaris mencakup pencatatan barang masuk, barang keluar, jumlah stok, dan informasi yang diperlukan untuk mengetahui ketersediaan barang. Jose et al. (2025) menunjukkan bahwa pengelolaan stok secara manual meningkatkan risiko kesalahan pencatatan dan dapat menghambat pengambilan keputusan karena informasi mengenai jumlah persediaan tidak tersedia secara tepat. Penelitian tersebut juga menegaskan pentingnya pengendalian persediaan untuk mengurangi risiko kekurangan maupun kelebihan stok.

Sistem manajemen inventaris berbasis web memungkinkan pergerakan barang dicatat secara lebih terstruktur. Sriwinarti et al. (2021) menunjukkan bahwa sistem persediaan berbasis web dapat membantu pengelolaan barang masuk dan keluar serta menyediakan informasi stok yang lebih akurat. Triana et al. (2024) juga merancang sistem persediaan yang mencakup data barang, supplier, barang masuk, barang keluar, dan laporan persediaan. Saputri dan Lutfiyani (2025) mengembangkan sistem yang mencakup pencatatan barang masuk dan keluar, pengelolaan data barang, serta laporan stok. Hasilnya menunjukkan bahwa sistem dapat meningkatkan efisiensi kerja, akurasi pencatatan, dan kemudahan pemantauan stok. Dengan demikian, nilai stok dalam sistem seharusnya tidak hanya diubah melalui penyuntingan manual, tetapi terutama berasal dari transaksi yang memiliki bukti dan waktu pencatatan yang jelas.

Pada kerja praktik ini, stok bertambah melalui pencatatan barang masuk atau pembelian dan berkurang ketika transaksi penjualan berhasil disimpan. Karena terdapat variasi satuan penjualan, sistem perlu menggunakan satuan dasar persediaan sebagai acuan. Kuantitas penjualan dalam bentuk pecahan berat harus dikonversi terhadap satuan dasar tersebut sebelum stok diperbarui. Pendekatan ini diperlukan agar transaksi eceran, grosir, dan penjualan pecahan tetap menghasilkan catatan persediaan yang konsisten.

Informasi persediaan selanjutnya disajikan melalui data stok dan laporan yang dapat ditelusuri oleh pemilik toko. Informasi tersebut digunakan untuk mengetahui ketersediaan barang dan membantu menentukan barang yang perlu disediakan kembali. Namun, kerja praktik ini tidak menerapkan metode kuantitatif tertentu untuk menentukan batas minimum atau maksimum persediaan. Penelitian Jose et al. (2025) mengenai metode *min-max* digunakan sebagai penguat pentingnya pengendalian stok, bukan sebagai metode perhitungan yang diterapkan dalam sistem.

## Pengelolaan Supplier dan Kewajiban Pembayaran

Supplier atau pemasok merupakan pihak yang menyediakan barang bagi toko. Data supplier diperlukan untuk menghubungkan asal barang dengan aktivitas pembelian atau barang masuk. Khisanah dan Fatmawati (2024) memasukkan manajemen pemasok sebagai bagian dari pengembangan sistem informasi manajemen toko grosir. Sementara itu, Yulianti et al. (2024) menunjukkan bahwa pencatatan transaksi secara terstruktur diperlukan agar arus transaksi dan penyusunan laporan dapat ditelusuri dengan lebih jelas.

Dalam ruang lingkup kerja praktik ini, pengelolaan supplier mencakup identitas dan informasi kontak pemasok. Adapun kewajiban pembayaran merupakan catatan operasional atas nominal yang masih harus dibayarkan toko kepada supplier berdasarkan transaksi pembelian atau barang masuk. Data kewajiban sekurang-kurangnya memuat supplier, tanggal transaksi, jumlah kewajiban, tanggal jatuh tempo, dan status pembayaran. Pengertian tersebut merupakan definisi operasional yang digunakan dalam proyek dan tidak dimaksudkan sebagai penerapan sistem akuntansi utang dagang secara menyeluruh.

Pencatatan status pembayaran membedakan kewajiban yang belum dibayar dan yang telah diselesaikan, sedangkan tanggal jatuh tempo digunakan sebagai dasar pengingat. Hubungan antara data supplier, barang masuk, kewajiban, pembayaran, dan laporan harus dipertahankan agar riwayat transaksi dapat ditelusuri. Dengan pencatatan tersebut, pemilik toko dapat memantau kewajiban kepada supplier tanpa harus memeriksa catatan yang tersebar.

## Aplikasi Berbasis Web

Aplikasi berbasis web merupakan aplikasi yang antarmukanya diakses melalui peramban. Rezki et al. (2023) menjelaskan bahwa website terdiri atas halaman-halaman yang saling terhubung dan dapat digunakan untuk menyajikan informasi. Pada penelitian tersebut, sistem berbasis web digunakan untuk mengelola data barang, stok, transaksi, pencetakan struk, dan laporan pada toko kelontong. Saftini (2025) merancang sistem berbasis web yang memuat fungsi login, dashboard, pengelolaan kategori, inventaris, transaksi, dan laporan penjualan. Penelitian Kumaini et al. (2025) juga menunjukkan bahwa aplikasi POS berbasis web dapat mendukung otomatisasi transaksi, manajemen stok, dan pelaporan dalam satu sistem.

Pemilihan bentuk aplikasi berbasis web pada kerja praktik ini didasarkan pada kebutuhan untuk memusatkan data dan menyediakan antarmuka yang dapat digunakan sesuai hak akses pengguna. Logika aplikasi, data transaksi, dan data persediaan dikelola dalam satu sistem sehingga perubahan yang dilakukan pada modul transaksi dapat digunakan oleh modul stok dan laporan. Kemudahan akses tidak berarti seluruh data dapat diakses tanpa pembatasan; autentikasi dan pembagian hak akses tetap diperlukan agar fungsi pengelolaan hanya dapat dijalankan oleh pengguna yang berwenang.

## Basis Data

Basis data digunakan untuk menyimpan data secara terstruktur sehingga dapat ditambah, dicari, diperbarui, dihubungkan, dan ditampilkan kembali oleh aplikasi. Dalam penelitian Rezki et al. (2023), basis data relasional digunakan untuk menyimpan data sistem ke dalam tabel-tabel yang saling berkaitan. Khisanah dan Fatmawati (2024) juga menggunakan sistem manajemen basis data untuk mendukung pengelolaan data stok, transaksi penjualan, pemasok, dan laporan pada sistem toko grosir berbasis web.

Pada sistem POS dan inventaris, basis data perlu menjaga hubungan antara data master dan data transaksi. Data master meliputi pengguna, produk, kategori, satuan, harga, dan supplier. Data transaksi meliputi penjualan beserta rinciannya, barang masuk, perubahan stok, kewajiban pembayaran, pembayaran, dan informasi jatuh tempo. Hubungan tersebut diperlukan agar satu transaksi dapat ditelusuri hingga produk, pengguna, supplier, dan perubahan stok yang berkaitan.

Perancangan basis data juga perlu memperhatikan konsistensi data. Contohnya, transaksi penjualan tidak boleh merujuk pada produk yang tidak terdaftar, detail transaksi harus terhubung dengan transaksi induknya, dan perubahan status pembayaran harus mengacu pada kewajiban yang benar. Pembahasan mengenai struktur tabel, atribut, kunci, dan hubungan antardata akan dijelaskan lebih lanjut pada bagian perancangan sistem.

## *Unified Modeling Language* (UML)

*Unified Modeling Language* (UML) merupakan bahasa pemodelan yang digunakan untuk menggambarkan kebutuhan, perilaku, dan struktur sistem berorientasi objek. Kumaini et al. (2025) menggunakan UML dalam perancangan POS berbasis web untuk memperjelas hubungan pengguna dengan fungsi sistem. Yulianti et al. (2024) menggunakan beberapa diagram UML, antara lain *use case diagram*, *activity diagram*, *class diagram*, dan *sequence diagram*, untuk memodelkan sistem transaksi dan basis datanya.

Diagram UML yang digunakan dalam kerja praktik ini disesuaikan dengan kebutuhan perancangan, yaitu:

- *Use case diagram* digunakan untuk menggambarkan aktor, batas sistem, dan fungsi yang dapat dijalankan oleh setiap aktor. Diagram ini membantu memastikan bahwa kebutuhan pemilik toko, admin, atau kasir diterjemahkan menjadi fungsi sistem yang jelas.
- *Activity diagram* digunakan untuk menggambarkan urutan aktivitas, keputusan, dan aliran proses. Diagram ini dapat digunakan untuk memodelkan transaksi penjualan, barang masuk, pembaruan stok, pencatatan kewajiban, pembayaran, serta pembuatan laporan.
- *Class diagram* digunakan untuk menggambarkan kelas atau entitas, atribut, operasi, dan hubungan antarkelas. Dalam proyek ini, *class diagram* menjadi dasar konseptual untuk menyusun hubungan data produk, satuan, transaksi, stok, supplier, dan pembayaran.
- *Sequence diagram*, apabila diperlukan pada tahap perancangan rinci, digunakan untuk menunjukkan urutan pertukaran pesan antara pengguna, antarmuka, proses aplikasi, dan basis data pada suatu skenario.

Penggunaan UML tidak menggantikan spesifikasi kebutuhan, melainkan membantu menyajikannya dalam bentuk visual yang konsisten. Setiap elemen pada diagram harus dapat ditelusuri kembali ke kebutuhan yang telah diperoleh dari hasil observasi dan wawancara. Sebaliknya, fungsi yang tidak termasuk dalam ruang lingkup kerja praktik tidak perlu dimasukkan ke dalam model.

## *System Development Life Cycle* (SDLC)

*System Development Life Cycle* (SDLC) merupakan kerangka kerja yang mengatur tahapan pengembangan sistem informasi. Rezki et al. (2023) menjelaskan bahwa SDLC mencakup fase perencanaan, analisis, perancangan, implementasi, dan pemeliharaan. Kerangka tersebut membantu pengembang mengubah kebutuhan pengguna menjadi sistem melalui tahapan yang terdokumentasi dan saling berhubungan.

Penerapan SDLC pada kerja praktik ini dimulai dengan pengumpulan informasi mengenai proses bisnis dan masalah yang terjadi di Toko NF Harapan Rumbai. Informasi tersebut digunakan untuk menyusun kebutuhan fungsional dan nonfungsional. Kebutuhan kemudian diterjemahkan menjadi rancangan proses, basis data, dan antarmuka. Setelah rancangan dinilai memadai, sistem diimplementasikan dan diuji berdasarkan fungsi yang telah ditentukan. Tahap pemeliharaan berkelanjutan tidak termasuk dalam ruang lingkup kerja praktik, tetapi kebutuhan perbaikan yang ditemukan selama pengujian tetap ditangani sebelum sistem dinyatakan selesai.

## Model Waterfall

Waterfall merupakan salah satu model SDLC yang menjalankan pengembangan melalui tahapan yang tersusun secara berurutan. Rezki et al. (2023) menguraikan tahapan Waterfall menjadi analisis dan definisi kebutuhan, perancangan sistem dan perangkat lunak, implementasi dan pengujian unit, integrasi dan pengujian sistem, serta operasi dan pemeliharaan. Sari et al. (2023) menggunakan model Waterfall bersama pemodelan *use case diagram*, *activity diagram*, dan *class diagram* dalam perancangan sistem informasi penjualan berbasis web. Saputri dan Lutfiyani (2025) menerapkan urutan analisis kebutuhan, desain sistem, implementasi, pengujian, dan pemeliharaan dalam pengembangan sistem manajemen stok berbasis web.

Model Waterfall dipilih pada kerja praktik ini karena kebutuhan utama sistem dapat dirumuskan berdasarkan proses operasional toko dan hasil komunikasi dengan pengguna. Tahapan yang diterapkan adalah sebagai berikut:

1. **Analisis kebutuhan.** Tahap ini mengidentifikasi proses yang sedang berjalan, masalah, aktor, data, fungsi, keluaran, dan batasan sistem. Hasilnya berupa spesifikasi kebutuhan yang menjadi acuan tahap berikutnya.
2. **Perancangan sistem.** Kebutuhan diterjemahkan menjadi rancangan proses menggunakan UML, rancangan basis data, rancangan hak akses, dan rancangan antarmuka.
3. **Implementasi.** Rancangan direalisasikan menjadi modul aplikasi, meliputi transaksi POS, pengelolaan produk dan stok, supplier dan kewajiban pembayaran, pengingat jatuh tempo, serta laporan.
4. **Pengujian.** Setiap fungsi diperiksa menggunakan skenario *black box testing*. Apabila hasil aktual belum sesuai dengan hasil yang diharapkan, dilakukan perbaikan dan pengujian ulang.

Dokumentasi hasil pada setiap tahap diperlukan untuk menjaga keterlacakan. Kebutuhan yang ditemukan pada tahap analisis harus memiliki padanan dalam rancangan, implementasi, dan kasus uji. Dengan demikian, keberhasilan sistem tidak hanya dinilai berdasarkan tersedianya antarmuka, tetapi berdasarkan kesesuaiannya dengan kebutuhan operasional yang telah ditetapkan.

## *Black Box Testing*

*Black box testing* merupakan metode pengujian perangkat lunak yang menilai fungsi sistem melalui masukan dan keluaran tanpa memeriksa struktur kode program. Saputri dan Lutfiyani (2025) menjelaskan bahwa pengujian ini berfokus pada kesesuaian keluaran sistem terhadap masukan dan skenario yang diberikan. Kumaini et al. (2025) menggunakan *black box testing* untuk memeriksa apakah fitur POS berbasis web berjalan sesuai dengan fungsinya.

Pada kerja praktik ini, setiap kasus uji memuat nama fungsi, kondisi awal, data masukan atau langkah pengujian, keluaran yang diharapkan, keluaran aktual, dan status hasil. Skenario tidak hanya mencakup masukan yang benar, tetapi juga kondisi yang tidak valid, seperti autentikasi yang salah, data wajib yang kosong, jumlah transaksi yang tidak sesuai, atau akses pengguna yang tidak berwenang. Pengujian dilakukan terhadap fungsi utama, yaitu autentikasi, pengelolaan produk, transaksi eceran dan grosir, penjualan satuan berat dan pecahan, pembaruan stok, pengelolaan supplier, pencatatan kewajiban dan pembayaran, pengingat jatuh tempo, serta penyajian laporan.

Hasil pengujian dinyatakan berhasil apabila keluaran aktual sesuai dengan keluaran yang diharapkan. Apabila terdapat ketidaksesuaian, penyebabnya dicatat, fungsi diperbaiki, dan skenario yang sama dijalankan kembali. Hasil *black box testing* digunakan sebagai bukti bahwa fungsi sistem telah memenuhi kebutuhan yang ditetapkan, tetapi tidak digunakan untuk menyimpulkan kualitas struktur internal kode atau keamanan sistem secara menyeluruh.

## Penelitian Terdahulu

Penelitian terdahulu digunakan untuk mengetahui pendekatan yang telah diterapkan pada sistem POS, penjualan, dan inventaris berbasis web serta untuk menentukan posisi kerja praktik ini. Ringkasan penelitian yang relevan disajikan pada tabel berikut.

| No. | Peneliti | Fokus dan metode | Hasil yang relevan | Keterkaitan dengan kerja praktik |
| --- | --- | --- | --- | --- |
| 1 | Rezki et al. (2023) | Sistem pengolahan transaksi toko kelontong berbasis web menggunakan SDLC. | Sistem mengelola barang, stok, transaksi, pencetakan struk, dan laporan; kasus uji yang dilakukan menghasilkan fungsi yang dapat dijalankan sesuai harapan. | Menjadi acuan penggunaan SDLC dan integrasi transaksi dengan data barang serta laporan. |
| 2 | Yulianti et al. (2024) | Sistem informasi penjualan tunai toko grosir berbasis web dengan pemodelan UML dan basis data MySQL. | Sistem dirancang untuk menata pencatatan transaksi dan membantu penyusunan laporan penjualan. | Menjadi acuan pemodelan proses transaksi, basis data, dan pelaporan pada usaha grosir. |
| 3 | Khisanah dan Fatmawati (2024) | Sistem informasi manajemen toko grosir menggunakan RAD, PHP, dan MySQL. | Sistem mendukung pengelolaan stok, pencatatan penjualan, laporan, dan manajemen pemasok; pengujian SUS terhadap 30 responden memperoleh nilai rata-rata 80 dengan kategori baik. | Menguatkan kebutuhan integrasi data stok, transaksi, laporan, dan supplier dalam sistem toko grosir. |
| 4 | Kumaini et al. (2025) | POS berbasis web menggunakan RAD, Laravel, UML, dan *black box testing*. | Sistem mengotomatisasi transaksi, manajemen stok, dan laporan; seluruh fungsi pada pengujian *black box* berjalan sesuai rancangan dan uji kelayakan memperoleh nilai 88%. | Menjadi acuan fungsi POS, pemodelan UML, integrasi stok, dan pengujian fungsional. |
| 5 | Jose et al. (2025) | Sistem manajemen stok pada toko grosir menggunakan metode *min-max*. | Sistem membantu menentukan batas minimum dan maksimum persediaan untuk mengurangi risiko kekurangan dan kelebihan stok. | Menguatkan pentingnya informasi stok bagi pengendalian persediaan, meskipun metode *min-max* tidak diterapkan dalam proyek ini. |
| 6 | Saputri dan Lutfiyani (2025) | Sistem manajemen stok berbasis web menggunakan Waterfall, *black box testing*, dan pengujian SUS. | Sistem mencatat barang masuk dan keluar, data barang, serta laporan stok; fungsi berjalan sesuai skenario dan nilai rata-rata SUS sebesar 90,50. | Menjadi acuan tahapan Waterfall, pengelolaan pergerakan stok, dan penyusunan kasus uji fungsional. |
| 7 | Firmansyah et al. (2026) | Integrasi inventaris dan POS berbasis *cloud* dengan katalog publik secara waktu nyata. | Integrasi menyinkronkan transaksi penjualan, stok, dan informasi produk sehingga mengurangi perbedaan data antarmodul. | Menguatkan kebutuhan pembaruan stok secara otomatis setelah transaksi POS. |

Berdasarkan penelitian tersebut, sistem berbasis web telah terbukti dapat digunakan untuk menata pencatatan transaksi, persediaan, supplier, dan laporan. Sebagian penelitian berfokus pada POS, sedangkan penelitian lainnya berfokus pada inventaris atau pencatatan penjualan. Firmansyah et al. (2026) secara khusus menegaskan pentingnya integrasi POS dan inventaris agar pembaruan stok tidak berjalan terpisah dari transaksi.

Dalam artikel-artikel yang ditinjau pada laporan ini, belum terlihat pembahasan satu sistem dengan gabungan kebutuhan berupa harga eceran dan grosir, barang bersatuan pcs dan berat termasuk penjualan pecahan, serta pemantauan kewajiban dan jatuh tempo pembayaran kepada supplier. Posisi kerja praktik ini adalah menerapkan integrasi POS dan inventaris pada kebutuhan operasional Toko NF Harapan Rumbai dengan karakteristik tersebut. Kebaruan yang dimaksud bersifat kontekstual terhadap kebutuhan mitra kerja praktik, bukan klaim bahwa fungsi-fungsi tersebut belum pernah dikembangkan pada sistem lain.

# BAB III

METODE PELAKSANAAN

# BAB IV

HASIL DAN PEMBAHASAN

# BAB V

PENUTUP

# DAFTAR PUSTAKA

Arista, L. P., & Nugroho, Y. S. (2023). Sistem Informasi Pencatatan Transaksi Penjualan dan Pembelian Produk Berbasis Website di Toko Sembako Putrasena Sukoharjo. _JIP (Jurnal Informatika Polinema)_, _9_(4), 397-404.

Firmansyah, H., Listyorini, T., & Supriyati, E. (2026). Integrasi Sistem Inventory dan Point of Sale Berbasis Cloud dengan Katalog Publik Real-Time. _Jurnal Teknologi Informasi dan Komunikasi_, _21_(1), 33-42. <https://doi.org/10.33005/scan.v21i1.5067>

Jose, A. F., Sucipto, S., Muzaki, M. N., & Hidayat, M. N. F. (2025). Sistem Informasi Manajemen Stok Mengimplementasikan Metode Min-Max Pada Toko Grosir Amanah. _JSITIK: Jurnal Sistem Informasi Dan Teknologi Informasi Komputer_, _3_(2), 112-127. <https://doi.org/10.53624/jsitik.v3i2.563>

Khisanah, I. I. N., & Fatmawati, A. (2024). Sistem Informasi Manajemen pada Toko Grosir Al-Fattah Berbasis Web. _Journal of Innovation and Future Technology (IFTECH)_, _6_(2), 223-233. <https://doi.org/10.47080/iftech.v6i2.3308>

Kumaini, I. K., Mutamassikin, M., & Triadi, A. (2025). Rancang Bangun Sistem Informasi Point Of Sales Berbasis Web Pada Toko Sembako Putri. _Jurnal Komputer Teknologi Informasi Sistem Informasi (JUKTISI)_, _4_(2), 1104-1112. <https://doi.org/10.62712/juktisi.v4i2.596>

Permana, A. A., & Wijana, M. (2023). Rancang Bangun Sistem Informasi Penjualan Barang Berbasis Web di Toko Kelontong Haji Agus. _INTERNAL (Information System Journal)_, _6_(1), 46-54.

Rezki, M., Ihsan, M. I. R., & Nurdiani, S. (2023). Perancangan Sistem Informasi Pengolahan Data Transaksi Pada Toko Kelontong Tebas Berbasis Web. _JUSTIAN: Jurnal Sistem Informasi Akuntansi_, _4_(1), 54-63.

Saftini, A. D. (2025). Perancangan Sistem Informasi Penjualan Sembako pada Toko Misman. _Switch: Jurnal Sains dan Teknologi Informasi_, _3_(3), 49-54. <https://doi.org/10.62951/switch.v3i1.339>

Saputri, R. A. D., & Lutfiyani, A. (2025). Implementasi Sistem Informasi Manajemen Stok Barang Berbasis Web pada Toko Grosir KBM. _Jurnal Komputer, Informasi dan Teknologi_, _5_(2), 1-13. <https://doi.org/10.53697/jkomitek.v5i2.2850>

Sari, D. N., Husaein, A., & Gunardi. (2023). Perancangan Sistem Informasi Penjualan pada Toko Sejahtera Abadi Talang Babat Berbasis Web. _Jurnal Manajemen Teknologi dan Sistem Informasi_, _3_(2), 572-580. <https://ejournal.unama.ac.id/index.php/jms>

Septiani, A. F., Hayati, N., Putri, R. F. K., & Piarna, R. (2026). Optimalisasi Point of Sales (POS) dan Manajemen Bahan Baku Kebab Aba Zaid Berbasis Website. _Jurnal Rekayasa Sistem Informasi dan Teknologi_, _3_(3), 415-427.

Sriwinarti, N. K., Murapi, I., & Fathona, N. (2021). Sistem Informasi Persediaan Stok Barang pada Toko Kelontong Berbasis Web. _REKAN: Riset Ekonomi, Akuntansi dan Perpajakan_, _2_(2), 99-108. <https://doi.org/10.30812/rekan.v2i2.1405>

Triana, I., Nugroho, A., & Meisak, D. (2024). Perancangan Sistem Informasi Persediaan Barang Berbasis Web pada Toko Kuat. _Jurnal Informatika dan Rekayasa Komputer (JAKAKOM)_, _4_(1), 835-844. <http://ejournal.unama.ac.id/index.php/jakakom>

Yulianti, I., Saumi Ramdhani, L., Farlina, Y., Nurajizah, S., Ramdiani Azzahra, D., & Fitrika, S. (2024). Sistem Informasi Akuntansi Penjualan Tunai Pada Toko Grosir Mukti Garut Sukabumi. _Justika: Jurnal Sistem Informasi Akuntansi_, _4_(2), 52-58. <https://doi.org/10.31294/justika.v4i2.7501>

Zaitunnisaa, L., & Arifin, R. W. (2021). Sistem Informasi Point of Sales Berbasis Web pada Toko Usaha Mandiri. _Information System for Educators and Professionals_, _5_(2), 141-150.
