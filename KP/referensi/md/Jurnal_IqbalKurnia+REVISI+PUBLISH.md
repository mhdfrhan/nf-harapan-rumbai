# Jurnal_IqbalKurnia+REVISI+PUBLISH

p-ISSN: 2962-3022 | e-ISSN: 2963-7104
Volume 4 No 2 September 2025
Hal: 1104-1112
http://ejurnal.lkpkaryaprima.id/index.php/juktisi
DOI: https://doi.org/10.62712/juktisi.v4i2.596
Copyright © 2025 Author(s), Page 1104
This Journal is licensed under a Creative Commons Attribution 4.0 International License
Rancang Bangun Sistem Informasi Point of Sales Berbasis Web Pada
Toko Sembako Putri
Iqbal Kurniatullah Kumaini1, Mutamassikin2, Albet Triadi3
1,2,3 Sains dan teknologi, Sistem informasi, Universitas Islam Negeri Sulthan Thaha Saifuddin Jambi, Jambi, Indonesia
Email: 1IqbalKurnia831@gmail.com, 2Mutamassikin@gmail.com, 3albettriadi@uinjambi.ac.id
(* Email Corresponding Author: IqbalKurnia831@gmail.com )
Received: September 7, 2025 | Revision: September 8, 2025 | Accepted: September 11, 2025
Abstrak
Perkembangan teknologi informasi mempengaruhi berbagai sektor usaha, termasuk dalam manajemen penjualan dan pengelolaan stok
barang. Toko Sembako Putri, yang berlokasi di Desa Muara Jernih, Kabupaten Merangin, Provinsi Jambi, menghadapi tantangan dalam
pencatatan manual yang mempengaruhi efisiensi dan keakuratan data. Penelitian ini bertujuan untuk merancang sistem informasi Point
of Sales (POS) berbasis web menggunakan metode Rapid Application Development (RAD). Sistem dibangun dengan framework
Laravel dan menggunakan pendekatan UML dalam perancangannya. Hasil implementasi menunjukkan bahwa sistem mampu
meningkatkan efisiensi operasional toko melalui otomatisasi transaksi penjualan, manajemen stok barang, serta pembuatan laporan
keuangan secara real-time. Pengujian menggunakan metode blackbox menunjukkan semua fitur berjalan sesuai fungsinya, dan hasil
uji kelayakan sistem menggunakan Skala Likert memperoleh nilai sebesar 88%, yang termasuk dalam kategori sangat layak. Dengan
demikian, sistem ini dinilai efektif dalam membantu pemilik toko mengelola aktivitas operasional secara lebih sistematis dan efisien.
Kata kunci: Sistem Informasi, Point of Sales (POS), Web, RAD, Skala Likert, Laravel.
Abstract
The rapid development of information technology has significantly influenced various business sectors, including sales management
and inventory control. Toko Sembako Putri, located in Muara Jernih Village, Merangin Regency, Jambi Province, faces challenges due
to manual recording processes that affect efficiency and data accuracy. This study aims to design a web-based Point of Sales (POS)
information system using the Rapid Application Development (RAD) method. The system was developed using the Laravel framework
and designed with Unified Modeling Language (UML) tools. The implementation results indicate that the system successfully enhances
store operations by automating sales transactions, inventory management, and real-time financial reporting. Testing with the blackbox
method shows that all features function as intended. Additionally, a system feasibility test using the Likert Scale produced a score of
88%, categorized as highly feasible. Overall, the system is considered effective in helping store owners manage business operations
more systematically and efficiently.
Keywords: Information System, Point of Sales (POS), Web-Based, RAD, Laravel, Likert Scale.
1. PENDAHULUAN
Perkembangan teknologi informasi yang semakin pesat telah mendorong perusahaan dan instansi untuk
memanfaatkan sistem informasi guna meningkatkan efisiensi operasionalnya. Inovasi berbasis komputer memiliki peran
penting dalam penyajian informasi, terutama dalam dunia bisnis seperti pertokoan. Sistem informasi, seperti Point of Sale
(POS), memungkinkan penyajian data yang akurat dan terpercaya, serta mempermudah pengambilan keputusan.
Transformasi teknologi juga berdampak pada sektor UMKM, yang kini mulai mengadopsi teknologi seperti layanan swa-
kasir demi pengalaman belanja yang cepat dan praktis. Namun, masih banyak UMKM, terutama yang berada di daerah
pedesaan, yang menggunakan metode transaksi manual, termasuk pencatatan penjualan dan laporan keuangan [1].
Sembako Putri, yang berdiri sejak 2003 di Desa Muara Jernih, Kab. Merangin, Prov. Jambi, menghadapi
permasalahan serupa. Dengan jumlah pelanggan yang terus meningkat, toko ini masih mencatat transaksi dan laporan
secara manual, yang menyebabkan ketidakefisienan, human error, hingga kesulitan dalam pengecekan stok barang [2].
Selain itu, pengelolaan laporan dan stok hanya dilakukan oleh satu orang pegawai, sehingga memberatkan pekerjaan
dan menghambat efisiensi operasional. Penggunaan sistem POS dipandang sebagai solusi yang tepat karena mampu
mengelola transaksi dan stok barang secara otomatis dan terintegrasi. Dengan bantuan perangkat seperti komputer dan
dukungan sumber daya manusia, sistem ini akan sangat membantu dalam meminimalisir kesalahan dan meningkatkan
produktivitas [3]. Sistem informasi POS pada Toko Sembako Putri dirancang menggunakan framework Laravel yang
memiliki berbagai library pendukung untuk pengembangan web. Metode yang digunakan dalam pengembangan sistem
ini adalah Rapid Application Development (RAD), yang memungkinkan penyelesaian sistem dalam waktu singkat, yaitu
antara 60–90 hari, melalui tahapan perencanaan kebutuhan, desain sistem, dan implementasi [4].
Penjualan adalah salah satu aktivitas rutin yang dijalani setiap perusahaan dalam memperjualbelikan barang dan jasa
kepada konsumen, yang tujuannya untuk memperoleh laba dan membuat perusahaan tersebut semakin berkembang [5].
Dalam konteks pengembangan sistem informasi, perancangan merupakan serangkaian prosedur yang bertujuan untuk
menerjemahkan hasil analisis sistem ke dalam bentuk desain teknis menggunakan bahasa pemrograman tertentu, yang

-- 1 of 9 --

p-ISSN: 2962-3022 | e-ISSN: 2963-7104
Volume 4 No 2 September 2025
Hal: 1104-1112
http://ejurnal.lkpkaryaprima.id/index.php/juktisi
DOI: https://doi.org/10.62712/juktisi.v4i2.596
Copyright © 2025 Author(s), Page 1105
This Journal is licensed under a Creative Commons Attribution 4.0 International License
menjelaskan secara terperinci bagaimana komponen-komponen sistem akan diimplementasikan. Sedangkan
pembangunan sistem diartikan sebagai kegiatan untuk menciptakan sistem baru atau melakukan penggantian dan
perbaikan terhadap sistem yang telah ada, baik secara menyeluruh maupun sebagian [6].
Menurut Denso Wave, Quick Response code (QR code) adalah teknik mengubah data tertulis menjadi kode 2
dimensi yang tercetak pada media yang lebih ringkas [7]. Sejalan dengan hal tersebut, John Buch dan Gary Grudnitski
mengemukakan bahwa perancangan merupakan suatu proses yang mencakup perencanaan, penggambaran, serta
pengintegrasian berbagai komponen sistem hingga membentuk satu kesatuan yang utuh dan berfungsi dengan baik [8].
Sementara itu, menurut Satzinger, Jackson, dan Burd, perancangan sistem merupakan rangkaian aktivitas yang merinci
bagaimana sistem akan bekerja untuk menghasilkan perangkat lunak (software) yang mampu memenuhi kebutuhan
pengguna [9].
O’Brien menekankan bahwa sistem informasi tidak hanya mendukung aktivitas operasional, tetapi juga berperan
penting dalam mendukung strategi bisnis dan keunggulan kompetitif [10]. Kendall dan Kendall juga menambahkan
bahwa pendekatan sistematis dalam perancangan membantu pengembang untuk memahami kebutuhan pengguna
sekaligus mengantisipasi perubahan di masa depan [11]. Sementara itu, Pressman menggarisbawahi pentingnya
metodologi pengembangan perangkat lunak yang terstruktur, agar sistem yang dihasilkan memiliki kualitas, keandalan,
dan skalabilitas yang baik [12].
2. METODOLOGI PENELITIAN
2.1 Metode penelitian
Dalam melakukan sebuah penyusunnan laporan penelitian, dibutuhkan data dan informasi yang cukup lengkap dan
akurat guna mendukung keabsahan isi dan pembahasan dalam laporan. Maka dari itu, sebelum penyusunan laporan
dilakukan, peneliti sebelumnya melakukan riset sebagai tempat untuk mendapatkan data dan informasi yang tepat, benar
dan saling berkaitan dengan topik penelitian.
2.2 Tahapan Penelitian
Penulis membuat kerangka kerja penelitian untuk menyelesaikan penelitian. Dengan kerangka kerja ini, penelitian
diharapkan selesai dengan cepat dan tepat waktu.
Gambar 1 Tahapan Penelitian
2.3 Teknik Pengumpulan Data
Tujuan pengumpulan data bagi penulis adalah sebuah proses yang dilakukan untuk mendapatkan informasi-informasi
yang diperlukan atau dibutuhkan dalam tujuan mencapai tujuan penelitian yang dilakukan.
2.4 Observasi
Dalam Metode pengamatan ini merupakan salah satu metode pengumpulan pendataan dengan melaksanakan kegiatan
penglihatan secara langsung terjun ke lapangan supaya menghasilkan serta mengumpulkan data-data yang akurat dan
penglihatan selama projek bekerja.
2.5 Wawancara
Metode wawancara merupakan suatu proses/cara diperoleh suatu informasi yang detail mengenai apa yang menjadi
tujuan penelitian, dalam bentuk ini penulis melakukan wawancara dengan salah satu karyawan kantor terkait sistem
yang sedang berjalan. Kegunaan dari tahap wawancara ini yaitu agar penulis dapat menghindari kesalahan informasi
yang didapatkan dari sumber lain, serta mendapatkan informasi yang penulis inginkan bertujuan untuk menyelesaikan
laporan serta menjadi bekal kedepannya.

-- 2 of 9 --

p-ISSN: 2962-3022 | e-ISSN: 2963-7104
Volume 4 No 2 September 2025
Hal: 1104-1112
http://ejurnal.lkpkaryaprima.id/index.php/juktisi
DOI: https://doi.org/10.62712/juktisi.v4i2.596
Copyright © 2025 Author(s), Page 1106
This Journal is licensed under a Creative Commons Attribution 4.0 International License
2.6 Metode Pengembangan
Rapid Application Development (RAD) adalah pendekatan berorientasi objek dalam pengembangan sistem yang
mencakup metode sekaligus perangkat lunak. Tujuan utamanya adalah mempercepat siklus hidup pengembangan
dibandingkan metode tradisional, khususnya antara tahap perancangan hingga penerapan sistem informasi. RAD
menggunakan metode iteratif (berulang) dengan membangun working model sejak awal untuk menetapkan kebutuhan
pengguna, yang kemudian dapat disesuaikan atau diganti.
Berdasarkan Susmiyanto & Ghufron, penggunaan model Rapid Application Development (RAD) dapat mempercepat
proses pengembangan perangkat lunak tanpa mengurangi kualitas, karena melibatkan pengguna secara langsung dalam
setiap tahap pengembangan[13]. Hal serupa disampaikan Sopiyan Dalis et al., bahwa RAD sangat sesuai bagi
pengembangan sistem informasi karena fleksibel terhadap perubahan kebutuhan [14]., Selain itu, Suryasari, Wiratama,
dan Desanti menekankan bahwa RAD merupakan pendekatan efektif untuk mengembangkan aplikasi bisnis modern
karena mengutamakan keterlibatan pengguna dan prototyping berulang [15]. Jika metode tradisional memerlukan waktu
sekitar 180 hari, dengan RAD sistem dapat diselesaikan hanya dalam 30–90 hari.
Dalam metode RAD terdapat Tiga tahap yang terlibat, berikut ini adalah tahapannya :
1. Requirements Planning, dalam fase ini penganalisis dan pengguna bertemu untuk mengidentifikasikan tujuan -
tujuan sistem atau aplikasi serta untuk mengidentifikasikan syarat - syarat informasi yang ditimbulkan dari tujuan
- tujuan tersebut. Pada tahap ini dilakukan pengumpulan data oleh peneliti apa saja kebutuhan dan informasi
yang dibutuhkan pengguna.
2. RAD Workshop Design, Fase ini adalah fase untuk merancang dan memperbaiki yang bisa digambarkan sebagai
workshop. Penganalisis dan dan pemrogram dapat bekerja membangun dan menunjukkan representasi visual
desain dan pola kerja kepada pengguna. Workshop desain ini dapat dilakukan selama beberapa hari tergantung
dari ukuran aplikasi yang akan dikembangkan. Selama workshop desain RAD, pengguna merespon prototipe
yang ada dan penganalisis memperbaiki modul-modul yang dirancang berdasarkan respon pengguna.
3. Implementation, Pada fase implementasi ini penganalisis bekerja dengan para pengguna secara intens selama
workshop dan merancang aspek-aspek bisnis dan nonteknis perusahaan. Segera setelah aspek-aspek ini disetujui
dan sistem-sistem dibangun dan disaring, sistem-sistem baru atau bagian dari sistem diujicoba dan kemudian
diperkenalkan.
Gambar 2 Tahapan Rapid Application Development (RAD)
Metode Penelitian (bisa meliputi analisa, arsitektur, metode yang dipakai untuk menyelesaikan masalah,
implementasi), dalam bahasan ini penulis bisa menguraikan bagaimana penelitian tersebut dilakukan.
3. HASIL DAN PEMBAHASAN
3.1 Analisis dan Perancangan
3.1.1 Analisis Sistem Yang Sedang Berjalan
Analisis sistem yang berjalan dilakukan untuk mengevaluasi efektivitas dan efisiensi proses transaksi di toko
sembako putri. Peneliti mengidentifikasi kekuatan dan kelemahan dari sistem saat ini, termasuk pengelolaan stok barang,
pencatatan keuntungan serta pemindaian expired barang.
Melalui pengumpulan data dari observasi dan wawancara, peneliti menemukan bahwa pelanggan dan staf toko
menghadapi kendala dalam proses transaksi, seperti antrian yang panjang dan kesalahan dalam pencatatan penjualan dan
staff toko juga mengalami kesulitan dalam mengelola stok barang. Selain itu, sistem manual yang digunakan saat ini
kurang transparan dalam pelaporan stok barang dan pencatatan pengeluaran operasional. Hasil analisis ini menjadi dasar
untuk merumuskan rekomendasi sistem POS yang lebih terintegrasi dan ramah pengguna.
3.1.2 Analisis Kebutuhan

-- 3 of 9 --

p-ISSN: 2962-3022 | e-ISSN: 2963-7104
Volume 4 No 2 September 2025
Hal: 1104-1112
http://ejurnal.lkpkaryaprima.id/index.php/juktisi
DOI: https://doi.org/10.62712/juktisi.v4i2.596
Copyright © 2025 Author(s), Page 1107
This Journal is licensed under a Creative Commons Attribution 4.0 International License
Kebutuhan fungsional sistem menggambarkan fitur dan kemampuan yang harus dimiliki oleh sistem, mencakup
interaksi pengguna serta proses internal yang mendukung operasional secara efisien dan efektif. Berikut adalah kebutuhan
fungsional yang harus disediakan oleh sistem:
1. Manajemen Transaksi
a. Sistem dapat mencatat transaksi penjualan secara otomatis.
b. Sistem dapat menghitung total harga dan pemotongan diskon secara otomatis.
c. Sistem dapat mencetak struk pembelian untuk pelanggan.
2. Manajemen Stok Barang
a. Sistem dapat mencatat dan memperbarui stok barang secara real-time.
b. Sistem dapat memberikan notifikasi ketika stok barang hampir habis.
c. Sistem dapat mengatur barang berdasarkan tanggal masuk untuk mencegah barang lama tertimbun.
d. Sistem dapat menandai barang yang mendekati tanggal kedaluwarsa.
3. Manajemen Laporan
a. Sistem dapat menghasilkan laporan penjualan harian, bulanan, dan tahunan.
b. Sistem dapat menyajikan laporan stok barang secara berkala.
c. Sistem dapat menyajikan laporan data transaksi .
4. User Management
a. Sistem memiliki akses pengguna berdasarkan peran (admin, kasir, pemilik toko).
b. Sistem dapat melakukan pengeditan untuk user yang sudah ada, dan penambahan untuk yg belum ada.
Sistem ini diharapkan mampu meningkatkan efisiensi pengelolaan transaksi dan stok barang, serta mendukung
pengambilan keputusan berbasis data bagi pemilik Toko Sembako Putri.
3.1.3 UML
1. Use Case Diagram
a. Use Case Admin
Use case diagram yang di gunakan untuk pengembangan sistem ini melibatkan tiga aktor: admin,
pemilik, dan kasir :
Gambar 3 Use Case Diagram
3.2 Desain
1. Halaman Login
Berikut ini merupakan halaman Login yang dirancang untuk masuk kedalam sistem Point Of Sales.

-- 4 of 9 --

p-ISSN: 2962-3022 | e-ISSN: 2963-7104
Volume 4 No 2 September 2025
Hal: 1104-1112
http://ejurnal.lkpkaryaprima.id/index.php/juktisi
DOI: https://doi.org/10.62712/juktisi.v4i2.596
Copyright © 2025 Author(s), Page 1108
This Journal is licensed under a Creative Commons Attribution 4.0 International License
Gambar 4 Halaman Login
2. Halaman Dashboard admin
Berikut ini merupakan halaman Dashboard yang dirancang sebagai halaman utama setelah login.
Gambar 5 Halaman Dashboard admin
3. Halaman Transaksi
Berikut ini merupakan Rancangan halaman Transaksi yang dirancang sebagai halaman.

-- 5 of 9 --

p-ISSN: 2962-3022 | e-ISSN: 2963-7104
Volume 4 No 2 September 2025
Hal: 1104-1112
http://ejurnal.lkpkaryaprima.id/index.php/juktisi
DOI: https://doi.org/10.62712/juktisi.v4i2.596
Copyright © 2025 Author(s), Page 1109
This Journal is licensed under a Creative Commons Attribution 4.0 International License
Gambar 6 Halaman Transaksi
4. Halaman Daftar Kategori
Berikut ini merupakan rancangan awal halaman kategori yang peneliti rancang untuk di tunjukan ke pemilik
toko sembako putri.
Gambar 7 Halaman Daftar Kategori
5. Halaman Setting Toko Berikut ini merupakan Rancangan halaman untuk melakukan setting toko.

-- 6 of 9 --

p-ISSN: 2962-3022 | e-ISSN: 2963-7104
Volume 4 No 2 September 2025
Hal: 1104-1112
http://ejurnal.lkpkaryaprima.id/index.php/juktisi
DOI: https://doi.org/10.62712/juktisi.v4i2.596
Copyright © 2025 Author(s), Page 1110
This Journal is licensed under a Creative Commons Attribution 4.0 International License
Gambar 8 Halaman Setting Toko
3.3 Tahap Pengujian
Tabel 1 Tabel Skema Pengujian
No Test Case Hasil yang diharapkan Hasil yang didapat Keterangan
Berhasil Tidak berhasil
1
Memasukkan
Username dan
Password dengan
benar lalu klik
“Login”
Dapat diproses lalu
menampilkan halaman
dashboard
Menampilkan Halaman
dashboard
✓
2
Memasukkan
Username dan
Password dengan
benar lalu klik
“Login”
Dapat Menampilkan Pop-
up username dan
password salah
Menampilkan Pop-up
dan Halaman tidak
pindah
✓
3 Mengklik
“Kategori”
Dapat Menampilkan
Halaman Kategori Barang
Menampilkan Halaman
Kategori Barang
✓
4 Mengklik
“Produk”
Dapat Menampilkan
Halaman Daftar Produk
barang
Menampilkan Halaman
Daftar Produk
✓
5
Mengklik
Tambah Produk
Dan Kategori
Dapat Menampilkan form
Tambah Produk Dan
Kategori
Menampilkan form
Tambah Produk dan
kategori
✓
6
Mengisi form
tambah produk
dan kategori lalu
klik “Simpan”
Dapat Menampilkan Pop-
up Data berhasil
tersimpan
Menampilkan Pop-up
berhasil tersimpan
✓

-- 7 of 9 --

p-ISSN: 2962-3022 | e-ISSN: 2963-7104
Volume 4 No 2 September 2025
Hal: 1104-1112
http://ejurnal.lkpkaryaprima.id/index.php/juktisi
DOI: https://doi.org/10.62712/juktisi.v4i2.596
Copyright © 2025 Author(s), Page 1111
This Journal is licensed under a Creative Commons Attribution 4.0 International License
4. KESIMPULAN
Setelah dilakukan perancangan Sistem Informasi Point of Sales (POS) berbasis web pada Toko Sembako Putri,
dapat disimpulkan bahwa sistem ini telah berhasil. Sistem ini dirancang untuk mengurangi kesalahan pencatatan manual
serta meningkatkan efisiensi dalam operasional toko. Pengembangan sistem menggunakan Metode Rapid Application
Development (RAD), yang memungkinkan perancangan lebih cepat dengan keterlibatan pengguna dalam setiap
tahapannya. Pengujian sistem menggunakan Blackbox Testing menunjukkan bahwa semua fitur berjalan sesuai dengan
spesifikasi yang telah dirancang, sementara uji kelayakan dengan Skala Likert memperoleh hasil sebesar 88% (sangat
layak). Sistem ini juga telah dihosting oleh peneliti sehingga dapat diakses secara online, Secara keseluruhan, sistem ini
dapat membantu pemilik usaha dalam mengelola transaksi dan stok dengan lebih sistematis.
7 Mengklik Suplier
Dapat Menampilkan
Halaman Informasi
tentang suplier
Menampilkan Halaman
Informasi Supplier
✓
8 Mengklik
Transaksi Aktif
Dapat Menampilkan
Halaman Transaksi Yang
Belum Di simpan
Menampilkan Halaman
Transaksi Yang Masih
Aktif
✓
9 Mengklik
Transaksi Baru
Dapat Menampilkan
Halaman Transaksi
Menampilkan Halaman
Transaksi
✓
10 Klik Pengeluaran Dapat Menampilkan
Halaman Pengeluaran
Menampilkan Halaman
Informasi Pengeluaran
toko
✓
11 Klik Pembelian
Dapat Menampilkan
Halaman Daftar
Pembelian yang di
lakukan oleh toko beserta
dengan nama supplier
Menampilkan Halaman
Daftar Pembelian yang
di lakukan oleh toko
beserta dengan supplier
✓
12 Klik Penjualan
Dapat Menampilkan
Halaman Daftar Penjualan
Toko
Menampilkan Halaman
Penjualan Toko
✓
13 Klik User Dapat Menampilkan
Halaman Daftar User
Menampilkan Halaman
Daftar User
✓
14 Klik Pengaturan
Dapat Menampilkan
Halaman Pengaturan
Toko
Menampilkan Halaman
Pengaturan Toko
✓
15 Klik Laporan
Dapat Menampilkan
Halaman Laporan
Pendapatan
Menampilkan Halaman
Laporan Pendapatan
✓
16 Pilih Metode
Pembayaran
Dapat Menampilkan
Metode pembayaran
Tunai dan Non tunai dan
menampilkan pilihan Qris
atau debit
Menampilkan Pilihan
Serta Semua Berfungsi
dengan Baik
✓
17
Alert Peringatan
Barang
Kadaluarsa dan
Hampir Habis
Menampilkan Alert
Kadaluarsa ketika
mendekati tanggal dan
barang hampir habis
ketika barang tersisa 5
Menampilkan Alert
Sesuai dengan ketentuan
✓

-- 8 of 9 --

p-ISSN: 2962-3022 | e-ISSN: 2963-7104
Volume 4 No 2 September 2025
Hal: 1104-1112
http://ejurnal.lkpkaryaprima.id/index.php/juktisi
DOI: https://doi.org/10.62712/juktisi.v4i2.596
Copyright © 2025 Author(s), Page 1112
This Journal is licensed under a Creative Commons Attribution 4.0 International License
REFERENCES
[1] M. Andarwati, A. R. Muslikh, F. Amrullah, and E. Thamrin, “An Analysis of Point of Sales (POS) Information
Systems in SMEswith The Black Box Testing and PIECES Method,” Article in IOSR Journal of Business and
Management, vol. 22, no. 9, pp. 20–25, 2020, doi: 10.9790/487X-2209052025.
[2] S. Andriani, M. Ika Prajawati, and U. Maulana Malik Ibrahim Malang, “Pendampingan Desain Penyusunan
Laporan Keuangan Berdasarkan Sak Emkm Berbasis Microsoft Excel Pada Umkm Pia Rb Pasuruan,” 2020.
[3] H. Setiawan, R. Tanamal, and D. B. Tonara, “Implementasi Sistem Informasi Point of Sales dan Inventory
Berbasis Web untuk Retail (UD. Mulia Jaya),” Jurnal Manajemen dan Bisnis Indonesia, vol. 2, no. 3, pp. 382–
392, Jun. 2025, doi: 10.31843/jmbi.v2i3.62.
[4] I. Imam Sholihin, Ahmad Turmudi Zy, and Ucok Darmanto Soer, “Rancang bangun sistem aplikasi e-cashier
berbasis web dengan metode rapid application development,” INFOTECH : Jurnal Informatika & Teknologi,
vol. 5, no. 1, pp. 14–26, Jun. 2024, doi: 10.37373/infotech.v5i1.970.
[5] R. Yodi and J. Susilo, “Analisis Dan Perancangan Sistem Informasi Point Of Sale Berbasis Web Pada Cv.
Yanti,” 2023.
[6] Y. Wijaya and R. Tanamal, “Design and Development of A Web-Based Point Of Sale (POS) Information
System for Ngadi Rejo Store,” SISFORMA, vol. 12, no. 1, pp. 1–10, May 2025, doi:
10.24167/sisforma.v12i1.11922.
[7] S. Rao and M. Alda, “Sistem Informasi Point Of Sales Pada Toko Distributor Salah Duga Berbasis Android,”
2024. [Online]. Available: http://jurnal.goretanpena.com/index.php/JSSR
[8] V. Widiyanti and R. Tisnawati, “Perancangan Sistem Informasi Point of Sale di PT. Abercode Software
Berbasis Web”.
[9] A. G. Gani, P. Furtuna Dewi, and A. Sugiharto, “Sistem Informasi Point Of Sale Berbasis Web Pada Dapur
Caringin Tilu Bandung,” 2022.
[10] T. Luthfi Almas’ud, D. K Pramudito, and A. Badruzzaman, “IoT-Enabled Smart Home Energy Monitoring
System Using Web Server-Based Control Logic,” Jurnal Informasi dan Teknologi, pp. 27–34, Apr. 2025, doi:
10.60083/jidt.vi0.611.
[11] A. T. Anggara and E. Sudarmilah, “Design of a Web-Based Point of Sale System for Warung Bakso Megaria,”
Journal La Multiapp, vol. 6, no. 1, pp. 170–187, Feb. 2025, doi: 10.37899/journallamultiapp.v6i1.1931.
[12] I. Gede Wiarta Sena and D. Marthin Dharmawan, “Design and Implementation of Point-of-Sales Systems for
Goodfather Vapor,” vol. 7, no. 1, 2024, [Online]. Available:
http://proceedings.itbwigalumajang.ac.id/index.php/progress
[13] J. Kridatama and S. Dan Teknologi Pengembangan, “Sistem Informasi Penjualan Menggunakan Model Rapid
Application Development (RAD) di.”
[14] S. Dalis, S. Agustiani, S. Bahri, A. Prawikas, and U. Bina Sarana Informatika, “Model Rapid Application
Development (RAD) Untuk Perancangan Sistem Informasi Pengelolaan Surat (SIPERA) Pada Kelurahan,”
2024. [Online]. Available: http://jurnal.bsi.ac.id/index.php/imtechno
[15] J. Wiratama and R. Ikana Desanti, “The Development of Web-based Sales Reporting Information Systems
using Rapid Application Development Method,” Ultima Infosys : Jurnal Ilmu Sistem Informasi, vol. 13, no. 2,
2022.

-- 9 of 9 --

