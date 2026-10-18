# Implementasi+Sistem+Informasi+Manajemen+Stok+Barang+Berbasis+Web+Pada+Toko+Grosir+KBM

Jurnal Komputer, Informasi dan Teknologi Vol: 5, No 2, 2025, Page: 1-13
https://penerbitadm.pubmedia.id/index.php/KOMITEK
Implementasi Sistem Informasi Manajemen Stok Barang
Berbasis Web Pada Toko Grosir KBM
Resti Anika Dili Saputri*, Anisa Lutfiyani
Universitas Ma’arif Nahdlatul Ulama Kebumen
Abstrak: Penelitian ini bertujuan untuk merancang dan mengimplementasikan sistem informasi manajemen stok barang
berbasis web pada Toko Grosir KBM, yang sebelumnya masih menggunakan pencatatan secara manual. Metode manual
ini menimbulkan berbagai kendala seperti ketidaktepatan data, keterlambatan informasi, serta kesulitan dalam proses
pelaporan stok. Pengembangan sistem dilakukan menggunakan metode Waterfall yang terdiri dari tahapan analisis
kebutuhan, desain sistem, implementasi, pengujian, dan pemeliharaan. Sistem dibangun menggunakan teknologi HTML,
CSS, PHP, dan MySQL. Fitur utama mencakup pencatatan barang masuk dan keluar, pengelolaan data barang, laporan
stok, serta manajemen pengguna. Hasil penelitian menunjukkan bahwa seluruh fitur berfungsi dengan baik berdasarkan
pengujian black box, di mana setiap skenario menghasilkan keluaran sesuai yang diharapkan. Selain itu, pengujian
usability menggunakan System Usability Scale (SUS) dengan lima responden menghasilkan skor rata-rata sebesar 90,50
yang termasuk kategori sangat baik. Hal ini menunjukkan bahwa sistem tidak hanya berjalan dengan stabil secara teknis,
tetapi juga mudah digunakan dan diterima dengan baik oleh pengguna. Simpulan dari penelitian ini adalah bahwa sistem
informasi yang dikembangkan mampu meningkatkan efisiensi kerja, akurasi pencatatan, dan kemudahan dalam
pemantauan stok barang secara real-time. Implikasi dari temuan ini relevan bagi usaha kecil dan menengah (UKM)
lainnya yang ingin melakukan digitalisasi pengelolaan persediaan. Rekomendasi untuk pengembangan selanjutnya
adalah menambahkan fitur notifikasi stok minimum, integrasi mobile, serta dukungan laporan grafik untuk pengambilan
keputusan yang lebih cepat dan tepat.
Kata Kunci: Manajemen Stok, Sistem Informasi, Waterfall, Web, Usability
Abstract: This study aims to design and implement a web-based inventory
management information system at Toko Grosir KBM, which previously relied on
manual stock recording. The manual method often led to issues such as data inaccuracy,
delayed stock information, and difficulties in reporting. The system was developed
using the Waterfall method, consisting of requirements analysis, system design,
implementation, testing, and maintenance phases. It was built using HTML, CSS,
PHP, and MySQL technologies. Key features include recording incoming and outgoing
goods, item data management, stock reporting, and user account administration.
Research results show that all system features functioned correctly based on black box
testing, with each scenario producing the expected outcomes. Furthermore, usability
testing using the System Usability Scale (SUS) involving five respondents yielded an
average score of 90.50, categorized as excellent. This indicates that the system not only
runs reliably from a technical perspective but is also user-friendly and well-received.
The conclusion of this study is that the developed system significantly improves work
efficiency, recording accuracy, and ease of real-time inventory monitoring. The
implications of these findings are highly relevant for other small and medium-sized
enterprises (SMEs) seeking to digitize their inventory management processes.
Recommendations for future development include adding a minimum stock alert
feature, mobile integration, and visual reporting tools to support faster and more
accurate decision-making.
Keywords: Inventory Management, Information System, Waterfall, Web-Based,
Usability
DOI:
https://doi.org/10.53697/jkomitek.v5i2.28
50
*Correspondence: Resti Anika Dili
Saputri
Email: anikaaarestiii@gmail.com
Received: 27-05-2025
Accepted: 27-06-2025
Published: 27-12-2025
Copyright: © 2025 by the authors.
Submitted for open access publication
under the terms and conditions of the
Creative Commons Attribution (CC BY)
license
(http://creativecommons.org/licenses/by/
4.0/).

-- 1 of 13 --

Jurnal Komputer, Informasi dan Teknologi Vol: 5, No 2, 2025 2 of 13
https://penerbitadm.pubmedia.id/index.php/KOMITEK
Pendahuluan
Dalam era perkembangan sistem komputerisasi yang semakin pesat, pemanfaatan
teknologi informasi dalam pengelolaan dan manajemen data menjadi kebutuhan utama
bagi berbagai sektor (Noviana, 2022). Teknologi komputer memiliki peran yang sangat
vital, bukan hanya untuk perusahaan berskala besar, tetapi juga untuk usaha kecil dan
menengah (Maharani eit al., 2021). Hal ini disebabkan oleh tuntutan untuk meningkatkan
efisiensi dan efektivitas dalam setiap aktivitas operasional perusahaan (Putri et al, 2023).
Bahkan, di tengah persaingan global saat ini, keunggulan dalam mengelola informasi
menjadi salah satu kunci utama untuk mempertahankan daya saing perusahaan (Romli et
al, 2023).
Manajemen stok barang adalah rangkaian keputusan dan kebijakan yang disusun oleh
sebuah perusahaan dengan tujuan untuk memastikan ketersediaan persediaan dengan
mutu yang baik, dalam kuantitas yang sesuai, serta pada waktu yang tepat (Stinjak &
Masya, 2021). Pengelolaan stok menjadi aspek krusial, tidak hanya untuk mendukung
kelancaran proses operasional dan produksi, tetapi juga untuk menjaga keseimbangan
antara permintaan dan ketersediaan barang (Ahmadar et al, 2021). Dengan manajemen stok
yang baik, perusahaan dapat menghindari risiko kekurangan atau kelebihan barang, yang
berpotensi menyebabkan kerugian (Nilfaidah, 2021). Dalam era bisnis yang kompetitif saat
ini, manajemen stok yang optimal juga dapat menjadi strategi penting dalam meningkatkan
daya saing perusahaan di pasar (Lukas et al, 2024).
Toko Grosir KBM merupakan sebuah usaha retail yang berlokasi di Jl. Wilis No. 16,
Kelurahan Bumirejo, Kabupaten Kebumen. Alasan dipilihnya Toko Grosir KBM sebagai
objek penelitian adalah karena toko ini merupakan salah satu usaha retail dengan skala
distribusi jas hujan yang cukup besar di Kabupaten Kebumen, namun masih menggunakan
metode manual dalam pengelolaan stok barang. Kondisi ini menimbulkan berbagai
kendala, seperti potensi kesalahan pencatatan, kesulitan dalam cek stok secara cepat, dan
proses rekapitulasi data yang memakan waktu (Sinlaei eit al., 2024). Oleh karena itu, Toko
Grosir KBM menjadi contoh yang tepat untuk penerapan sistem manajemen stok berbasis
web guna meningkatkan efisiensi dan akurasi operasional (Anwar et al, 2025).
Untuk memberikan gambaran kondisi pengelolaan stok di Toko Grosir KBM, Tabel 1.
menyajikan data barang masuk dan keluar dalam kurun waktu tertentu. Data ini
menunjukkan bahwa pencatatan masih dilakukan secara manual, yang rentan terhadap
kesalahan, keterlambatan pembaruan, serta potensi selisih antara stok fisik dan catatan.
Proses ini juga memerlukan waktu dan tenaga lebih dalam rekapitulasi, sehingga tidak
efisien untuk operasional berskala besar (Anamisa & Muifarroha, 2022). Oleh karena itu,
data tersebut menjadi dasar penting dalam merancang sistem informasi stok berbasis web
yang lebih akurat, efisien, dan mudah diakses (Andaria eit al, 2025).

-- 2 of 13 --

Jurnal Komputer, Informasi dan Teknologi Vol: 5, No 2, 2025 3 of 13
https://penerbitadm.pubmedia.id/index.php/KOMITEK
Tabel 1. Data Barang Keluar Masuk Toko Grosir Kebumen
Tanggal Kode
Barang
Nama
Barang
Masuk
(pcs)
Keluar
(pcs)
Stok Akhir
(pcs)
Keterangan
01-05-
2025
JR001 Jas Hujan
Anak
100 0 100 Barang
baru masuk
03-05-
2025
JR001 Jas Hujan
Anak
0 20 80 Penjualan
ke
pelanggan
04-05-
2025
JR002 Jas Hujan
Dewasa
150 0 150 Barang
baru masuk
07-05-
2025
JR001 Jas Hujan
Anak
0 10 70 Penjualan
08-05-
2025
JR002 Jas Hujan
Dewasa
0 30 120 Penjualan
Salah satu solusi efektif untuk meningkatkan efisiensi dalam pengelolaan data
persediaan adalah dengan memanfaatkan sistem informasi (Ansyah et al, 2025). Sistem ini
dirancang untuk mendukung pencatatan dan pengelolaan persediaan barang secara lebih
terorganisir, cepat, dan tepat (Muilia et al, 2022). Untuk memenuhi kebutuhan tersebut,
dilakukan proses penelitian dan pengembangan aplikasi sistem informasi berbasis web
yang difokuskan pada kemudahan dalam mencatat stok, mengelola transaksi barang
masuk dan keluar, serta menyusun laporan persediaan secara otomatis (Nagara &
Nuirhayati, 2021). Pemanfaatan teknologi web juga memungkinkan akses yang fleksibel
dari berbagai perangkat dan lokasi, sehingga pengawasan stok dapat dilakukan secara
langsung dan waktu nyata (real-time) (Utami & Maharani, 2024).
Metodologi
Penelitian ini difokuskan pada Toko Grosir KBM, sebuah usaha retail yang berlokasi
di Jl. Wilis No. 16, Kelurahan Bumirejo, Kabupaten Kebumen. Toko ini merupakan salah
satu pusat grosir penjualan jas hujan dengan volume distribusi yang cukup besar. Fokus
penelitian diarahkan pada aktivitas pengelolaan stok barang yang dijalankan oleh toko
tersebut, sebagai bagian dari upaya untuk merancang sistem informasi manajemen stok
yang lebih efisien dan terkomputerisasi sesuai dengan kebutuhan operasional toko.
Penulis menggunakan metode Waterfall, yaitu suatu model dalam System Development
Life Cycle (SDLC) yang dikenal luas dalam pengembangan perangkat lunak (Nur Adiya et
al, 2024). Metode ini sering disebut juga sebagai model sekuensial linear atau model klasik,
karena setiap tahapannya dilakukan secara berurutan (Sapuitra et al, 2023). Proses
pengembangan diawali mulai tahap analisis kebutuhan, dilanjutkan dengan perancangan
sistem, implementasi atau penulisan kode (coding), pengujian sistem, hingga tahap
pemeliharaan (Wau, 2022).

-- 3 of 13 --

Jurnal Komputer, Informasi dan Teknologi Vol: 5, No 2, 2025 4 of 13
https://penerbitadm.pubmedia.id/index.php/KOMITEK
Gambar 1. Model Pengembangan Waterfall
Berikut langkah-langkah model Waterfall dalam penerapan sistem informasi manajemen
stok barang berbasis web pada toko grosir kbm.
1) Analisis Kebutuhan
Dalam tahap ini penulis melakukan pengumpulan informasi terkait kebutuhan
sistem dari pemilik dan pengguna Toko Grosir KBM. Fokus utamanya adalah memahami
alur pengelolaan stok saat ini, data yang dibutuhkan (barang masuk, keluar, laporan stok).
2) Desain Sistem
Desain sistem mencakup beberapa komponen penting, antara lain: desain antarmuka
pengguna (User Interface) yang dirancang agar mudah digunakan dan dimengerti oleh
pengguna toko, desain database menggunakan MySQL untuk menyimpan dan mengelola
data barang secara terstruktur, serta perancangan arsitektur sistem berbasis web dengan
memanfaatkan teknologi HTML, CSS, dan PHP.
3) Implementasi
Aplikasi web dibangun dengan memanfaatkan HTML dan CSS untuk tampilan, PHP
untuk logika program, serta MySQL untuk mengelola database stok barang.
4) Pengujian
Pengujian dilakukan pada sistem untuk memastikan bahwa setiap fitur berfungsi
dengan baik sesuai tujuan yang telah dirancang. Pengujian dilakukan untuk mengecek
validasi data, transaksi barang masuk dan keluar, serta keakuratan laporan stok. Diuji juga
responsivitas tampilan dan kestabilan sistem.
5) Pemeliharaan
Setelah sistem diterapkan, dilakukan pemantauan dan perawatan berkala. Jika
ditemukan bug, error, atau kebutuhan baru dari pengguna, maka sistem akan diperbarui.
Tahap ini penting untuk menjaga agar sistem tetap berjalan optimal sesuai perkembangan
operasional toko.

-- 4 of 13 --

Jurnal Komputer, Informasi dan Teknologi Vol: 5, No 2, 2025 5 of 13
https://penerbitadm.pubmedia.id/index.php/KOMITEK
Hasil dan Pembahasan
1. Perancangan Sistem
Use Case Diagram
Gambar 2. Use Case Diagram
Dalam sistem manajemen stok yang dirancang, terdapat dua jenis pengguna utama
dengan hak akses yang berbeda, yaitu Pemilik Toko dan Admin Gudang. Perbedaan akses
disesuaikan berdasarkan peran dan tanggung jawab masing-masing. Pemilik Toko
memiliki akses terhadap fitur Login, Melihat Data Barang, dan Cetak Barang Masuk dan
Keluar untuk memantau informasi stok secara langsung. Sementara itu, Admin Gudang
memiliki akses yang lebih luas, mencakup fitur Login, Melihat Data Barang, Input Data
Barang Masuk, Input Data Barang Keluar, Cetak Barang Masuk dan Keluar, serta Edit Profil
Akun. Hal ini menunjukkan bahwa admin bertanggung jawab penuh dalam operasional
teknis gudang, termasuk pengelolaan data barang dan pengaturan akun, sedangkan
pemilik toko difokuskan pada pengawasan dan pengambilan keputusan berdasarkan data
yang tersedia.
a) Alur Proses Sistem
Gambar 3. Alur Proses Sistem
Alur sistem dimulai dari login admin, melihat data barang, mengelola barang masuk
dan keluar, hingga mencetak laporan dan mengubah profil.

-- 5 of 13 --

Jurnal Komputer, Informasi dan Teknologi Vol: 5, No 2, 2025 6 of 13
https://penerbitadm.pubmedia.id/index.php/KOMITEK
b) Login
Gambar 4. Halaman Login
Pada halaman ini, admin diminta untuk memasukkan username dan password
untuk dapat mengakses sistem. Terdapat fitur "Show Password" yang memungkinkan
pengguna melihat karakter kata sandi yang diketik, serta tombol "Sign in" untuk
melanjutkan proses login. Tampilan ini dirancang dengan antarmuka yang sederhana dan
mudah digunakan, memberikan keamanan sekaligus kemudahan akses bagi admin
sebelum masuk ke system.
c) Dashboard
Gambar 5. Halaman Dashboard
Dashboard ini menyajikan ringkasan informasi penting terkait data stok barang,
termasuk jumlah total data barang, jumlah barang masuk, dan jumlah barang keluar pada
bulan berjalan. Di bagian bawah dashboard terdapat dua tabel yang menampilkan rincian
data barang masuk dan barang keluar, lengkap dengan informasi kode barang, tanggal
transaksi, dan jumlah unit. Tampilan ini membantu admin dalam memantau dan mengelola
pergerakan stok barang secara efektif dan real-time melalui satu tampilan pusat yang
informatif.

-- 6 of 13 --

Jurnal Komputer, Informasi dan Teknologi Vol: 5, No 2, 2025 7 of 13
https://penerbitadm.pubmedia.id/index.php/KOMITEK
d) Data Barang
Gambar 6. Data Barang
Menampilkan daftar seluruh barang yang tersedia di gudang, lengkap dengan
informasi kode barang, nama barang, harga satuan, jumlah stok yang tersedia, serta tombol
aksi untuk mengedit atau menghapus data barang tersebut.
e) Barang Masuk
Gambar 7. Barang Masuk
Digunakan untuk mencatat setiap pemasukan barang ke dalam gudang. Informasi
yang ditampilkan meliputi kode barang, tanggal masuk, dan jumlah barang yang diterima,
serta tersedia fitur edit dan hapus untuk masing-masing entri.
f) Barang Keluar
Gambar 8. Barang Keluar

-- 7 of 13 --

Jurnal Komputer, Informasi dan Teknologi Vol: 5, No 2, 2025 8 of 13
https://penerbitadm.pubmedia.id/index.php/KOMITEK
Berfungsi mencatat barang yang keluar dari gudang, baik untuk penjualan maupun
distribusi. Tampilannya memuat data kode barang, tanggal keluar, jumlah yang
dikeluarkan, serta opsi untuk mengedit dan menghapus data yang telah dicatat.
g) Profil Admin
Gambar 9. Profil Admin
Menampilkan informasi akun pengguna yang sedang login, termasuk ID profil,
username, nama lengkap toko, serta terdapat tombol untuk mengedit profil, mengganti
password, dan mengubah foto profil yang ditampilkan di sisi kiri.
h) Cetak Barang Masuk Keluar
Gambar 10. Cetak Barang Masuk/Keluar
Merupakan fungsi aplikasi berupa mencetak data barang yang masuk atau keluar
berdasarkan bulan yang diinginkan.
2. Hasil Pengujian
Blackbox Testing
Pengujian ini dilakukan dengan menerapkan metode Black-box Testing, yakni
pendekatan yang menitikberatkan pada pengujian input dan output sistem tanpa
memeriksa proses internal atau struktur kode program. Evaluasi ini bertujuan untuk
mengetahui apakah fungsi-fungsi setiap elemen, menu, dan fitur-fitur pada sistem sudah
dapat berjalan, atau dengan kata lain, sistem sudah bisa dikatakan berhasil.

-- 8 of 13 --

Jurnal Komputer, Informasi dan Teknologi Vol: 5, No 2, 2025 9 of 13
https://penerbitadm.pubmedia.id/index.php/KOMITEK
Tabel 2. Blackbox Testing
No Nama Fitur Skenario
Pengujian
Input Output yang
Diharapkan
Status
1 Halaman
Login
Memasukkan
username dan
password valid
Username &
Password valid
Berhasil
masuk ke
dashboard
Berhasil
2 Halaman
Login
Memasukkan
username atau
password yang
salah
Username/Password
tidak valid
Muncul
pesan error
"Login Gagal"
Berhasil
3 Halaman
Stok Produk
Menambahkan
data produk
baru
Data produk lengkap Data produk
berhasil
disimpan
Berhasil
4 Halaman
Stok Produk
Mengubah data
produk
Perubahan data
produk
Data produk
berhasil
diperbarui
Berhasil
5 Halaman
Stok Produk
Menghapus
data produk
ID produk Data produk
berhasil
dihapus
Berhasil
6 Barang
Masuk
Menambahkan
data barang
masuk
Data barang masuk
lengkap
Data barang
masuk
berhasil
disimpan
Berhasil
7 Barang
Keluar
Menambahkan
data barang
keluar
Data barang keluar
lengkap
Data barang
keluar
berhasil
disimpan
Berhasil
8 Barang
Rusak
Menambahkan
data barang
rusak
Data barang rusak
lengkap
Data barang
rusak
berhasil
disimpan
Berhasil
9 Laporan
Penjualan
Melihat laporan
berdasarkan
filter waktu
Pilih rentang waktu Data laporan
sesuai filter
tampil
Berhasil
10 Daftar
Pembeli
Menambahkan
data pembeli
baru
Data pembeli lengkap Data pembeli
berhasil
disimpan
Berhasil
11 Manajemen
Pengguna
Menambahkan
akun pengguna
baru
Data pengguna
lengkap
Akun
pengguna
berhasil
disimpan
Berhasil
12 Logout Keluar dari
sistem
Klik tombol logout Dialihkan ke
halaman
login
Berhasil

-- 9 of 13 --

Jurnal Komputer, Informasi dan Teknologi Vol: 5, No 2, 2025 10 of 13
https://penerbitadm.pubmedia.id/index.php/KOMITEK
System Uisability Scale
Pengujian System Usability Scale (SUS) digunakan untuk mengevaluasi tingkat
kegunaan atau usability dari sistem informasi manajemen stok barang berbasis web
(Muzaki et al, 2025). Metode ini menggunakan 10 pernyataan dengan skala likert 1 sampai
5, di mana responden memberikan penilaian terhadap pernyataan berdasarkan
pengalaman penggunaan system.
Tabel 3. Pertanyaan SUS
1. Saya cenderung menggunakan sistem ini secara rutin.
2. Menurut saya, sistem ini cukup membingungkan untuk digunakan.
3. Saya menilai sistem ini mudah untuk dioperasikan.
4. Saya membutuhkan bantuan teknis agar dapat menjalankan sistem ini.
5. Menurut saya, fitur-fitur dalam sistem ini saling terhubung dan berfungsi secara harmonis.
6. Saya merasa terdapat banyak ketidakkonsistenan dalam penggunaan sistem ini.
7. Saya yakin kebanyakan orang dapat mempelajari sistem ini dengan cepat.
8. Bagi saya, sistem ini cukup menyulitkan untuk digunakan.
9. Saya merasa yakin dan nyaman saat menggunakan sistem ini.
10. Saya harus mempelajari banyak hal terlebih dahulu sebelum bisa menggunakan sistem ini
dengan baik
Setiap pernyataan dinilai dengan skala sebagai berikut:
1 = SangatTidak Setuju
2 = Tidak Setuju
3 = Netral
4 = Setuju
5 = Sangat Setuju
Nilai akhir SUS dihitung dengan menjumlahkan skor kontribusi dari setiap
pernyataan, yang kemudian dikalikan 2.5 agar mendapatkan nilai dalam rentang 0 hingga
100. Sistem dengan skor di atas 68 dikategorikan memiliki tingkat kegunaan yang baik.
Dalam penelitian ini, pengujian sistem dilakukan dengan melibatkan 5 responden yang
memiliki keterkaitan langsung maupun pemahaman terhadap proses pengelolaan stok di
Toko Grosir KBM.
Tabel 4. Hasil SUS
Responden Skor SUS
Responden 1 82.50
Responden 2 92.50
Responden 3 87.50
Responden 4 100.00
Responden 5 90.00
Skor Rata-rata 90.50

-- 10 of 13 --

Jurnal Komputer, Informasi dan Teknologi Vol: 5, No 2, 2025 11 of 13
https://penerbitadm.pubmedia.id/index.php/KOMITEK
Gambar 11. Interpretasi SUS Score
Berdasarkan hasil pengujian SUS, sistem mendapatkan skor rata-rata 90.50. Nilai ini
berada dalam kategori sangat baik, yang menunjukkan bahwa sistem memiliki tingkat
kegunaan yang dapat diterima dan cukup nyaman digunakan oleh pengguna.
Simpulan
Berdasarkan hasil pengembangan dan penerapan sistem informasi manajemen stok
barang berbasis web di Toko Grosir KBM, dapat disimpulkan bahwa sistem ini berhasil
mengatasi berbagai kendala yang sebelumnya timbul akibat pencatatan manual, seperti
ketidaksesuaian data, keterlambatan informasi, dan kesulitan pelaporan. Sistem yang
dikembangkan dengan metode Waterfall dan teknologi HTML, CSS, PHP, serta MySQL ini
telah melalui pengujian fungsional (black box) dan usability (SUS), dengan hasil sangat
memuaskan. Skor rata-rata SUS sebesar 90,50 menandakan sistem memiliki tingkat
kemudahan penggunaan yang sangat tinggi.
Implikasi penting dari temuan ini menunjukkan bahwa digitalisasi sistem stok secara
langsung mampu meningkatkan efisiensi operasional, akurasi data, serta mendukung
pengambilan keputusan yang lebih cepat dan tepat. Hal ini sangat relevan bagi usaha kecil
dan menengah (UKM) yang ingin meningkatkan profesionalisme dan daya saing di era
digital.
Untuk penelitian selanjutnya, disarankan agar sistem dikembangkan lebih lanjut
dengan fitur notifikasi stok minimum, manajemen multi-gudang, dan integrasi dengan
perangkat mobile serta cloud storage. Rekomendasi praktis lainnya termasuk pelatihan
intensif bagi pengguna sistem, serta evaluasi rutin untuk penyesuaian fitur terhadap
kebutuhan operasional yang terus berkembang.

-- 11 of 13 --

Jurnal Komputer, Informasi dan Teknologi Vol: 5, No 2, 2025 12 of 13
https://penerbitadm.pubmedia.id/index.php/KOMITEK
Daftar Pustaka
Ahmadar, M., Perwito, P., & Taufik, C. (2021). Perancangan Sistem Informasi Penjualan
Berbasis Web pada Rahayu Photo Copy dengan Database MySQL. Dharmakarya:
Jurnal Aplikasi Ipteks Untuk Masyarakat, 10(4), 284–289.
Anamisa, D. R., & Mufarroha, F. A. (2022). Dasar Pemrograman WEB Teori dan Implementasi:
HTML, CSS, Javascript, Bootstrap, Codelgniter. Media Nusa Creative (MNC
Publishing).
Andaria, A. C., Ardhana, V. Y. P., Nugroho, A. Y., Prastyo, D., Bakhtiar, M. Y., Martawireja,
A. R. H., Arifin, M., Zaenuddin, I., Saputra, M. H. K., & Wijaksono, B. A. (2025).
Pengenalan Bahasa Pemrograman Untuk Pemula. Hadla Media Informasi.
Anisah, S. (2022). Implementasi Metode Rapid Application Development pada
Pengembangan Aplikasi Inventory Barang. STRING (Satuan Tulisan Riset Dan Inovasi
Teknologi), 7(1), 57–65.
Ansyah, F. A., Firmansyah, K. L., Rohman, M., & Fais, M. (2025). Perancangan Sistem Informasi
Persediaan Barang Berbasis Web Pada PT Eka Mas Republika.
Anwar, M. S., Nindyasari, R., & Murti, A. C. (2025). Implementasi Aplikasi Persediaan
Barang Berbasis web. In Jurnal Mahasiswa Teknik Informatika) (Vol. 9, Issue 4).
Fathullah, H. (2025). Implementasi Algoritma AES Dalam Optimalisasi Keamanan Citra Digital
Pada Layanan Cloud. Universitas Islam Sultan Agung Semarang.
Hidayatulloh, A., Tanuwijaya, H., & Hananto, V. R. (2024). Penerapan Metode Rapid
Application Development Dan Weighted Moving Average Pada Sistem Informasi
Peramalan Persediaan Berbasis Web. Jutisi: Jurnal Ilmiah Teknik Informatika Dan Sistem
Informasi, 13(1), 20–30.
Lukas, L., Praptiwi, R. A., Priandika, A. T., Suaidah, S., & Alita, D. (2024). Implementasi REST
API pada Manajemen Stok Barang Berbasis Aplikasi WEB (Studi Kasus: PT Jon
Kuliner Indonesia). Jurnal Teknik Komputer, 3(1), 19–24.
Maharani, D., Helmiah, F., & Rahmadani, N. (2021). Penyuluhan manfaat menggunakan
internet dan website pada masa pandemi Covid-19. Abdiformatika: Jurnal Pengabdian
Masyarakat Informatika, 1(1), 1–7.
Mulia, G., Najoan, X., & Lumenta, A. (2022). Analisa Teknologi Hyper Text Markup
Language (HTML) Versi 5. J. Tek. Inform, 1–6.
Muzaki, M. R., Vitianingsih, A. V., Hamidan, R., Maukar, A. L., & Wati, S. F. A. (2025). Sistem
Informasi Persediaan Stok Berdasarkan Turnover Ratio. In Journal of Information
System Management (JOISM) e-ISSN (Vol. 7, Issue 1).
Nagara, E. S., & Nurhayati, R. (2021). Sistem Pendukung Keputusan Penentuan Hama Padi
Menggunakan PHP. Jurnal TAM (Technology Acceptance Model), 4(1), 1–12.
Nilfaidah, N. (2021). Pengembangan Sistem Absensi Mahasiswa Realtime Menggunakan PHP,
MYSQL, SMS Gateway, dan Framework Codeigniter. Universitas Negeri Makassar.
Noviana, R. (2022). Pembuatan aplikasi penjualan berbasis web monja store menggunakan
php dan mysql. Jurnal Teknik Dan Science, 1(2), 112–124.

-- 12 of 13 --

Jurnal Komputer, Informasi dan Teknologi Vol: 5, No 2, 2025 13 of 13
https://penerbitadm.pubmedia.id/index.php/KOMITEK
Nur Adiya, A. Z. D., Anggraeni, D. L., & Ilham Albana. (2024). Analisa Perbandingan
Penggunaan Metodologi Pengembangan Perangkat Lunak (Waterfall, Prototype,
Iterative, Spiral, Rapid Application Development (RAD)). Merkurius : Jurnal Riset
Sistem Informasi Dan Teknik Informatika, 2(4), 122–134.
https://doi.org/10.61132/merkurius.v2i4.148
Putri, M., Ginting, A., & Lubis, A. S. (2024). Pengujian Aplikasi Berbasis Web Data Ska
Menggunakan Metode Black Box Testing. COSMIC JURNAL TEKNIK, 2(1), 41–48.
https://doi.org/10.55537/cosmic
Putri, M. P., Nadeak, E., Malahayati, M., Rahmi, N., Rini, A., Sari, D. N., Kurniati, K.,
Kusmiati, H., & Pratama, R. A. A. (2023). Sistem Manajemen Basis Data Menggunakan
MYSQL.
Romli, R., Wiyanto, W., & Butsianto, S. (2023). Pengembangan Aplikasi Persediaan Barang
berbasis Web Menggunakan Metode SDLC Pada CV Padu Nusantara Jakarta.
JINTEKS (Jurnal Informatika Teknologi Dan Sains), 5(3), 468–476.
Saputra, A. A., Pakpahan, A. G. S., Kurtubi, A., Amiruddin, A., Fridaniarta, B., Wicaksono,
E. Y., Saputra, H., Putra, M. Y. A., & Azahra, R. Y. (2023). Pelatihan Dan Pembuatan
Website Menggunakan HTML Dan CSS. Beujroh : Jurnal Pemberdayaan Dan
Pengabdian Pada Masyarakat, 1(1), 119–125.
Sari, I. P., Jannah, A., Meuraxa, A. M., Syahfitri, A., & Omar, R. (2022). Perancangan Sistem
Informasi Penginputan Database Mahasiswa Berbasis Web. Hello World Jurnal Ilmu
Komputer, 1(2), 106–110. https://doi.org/10.56211/helloworld.v1i2.57
Sika, S. N. R. (2021). Sistem informasi persediaan stok barang berbasis web pada toko putra
gresik. Jurnal Fasilkom, 11(3), 157–164.
Sinlae, F., Maulana, I., Setiyansyah, F., & Ihsan, M. (2024). Pengenalan Pemrograman Web:
Pembuatan Aplikasi Web Sederhana Dengan PHP dan MYSQL. Jurnal Siber Multi
Disiplin, 2(2), 68–82.
Stinjak, M. L., & Masya, F. (2021). Perancangan Dan Implementasi Sistem Informasi
Inventory Berbasis Website Menggunakan Iterative Waterfall. Rabit : Jurnal Teknologi
Dan Sistem Informasi Univrab, 6(2), 83–91.
Utami, N. M. S., & Maharani, N. M. Dela. (2024). Penerapan Sistem Digitalisasi Pencatatan
Stok Barang Serta Pengimplementasian Program Poin Melalui Sistem Mamber Card
Untuk Meningkatkan Loyalitas Pelanggan Pada CV Busana Utama Sentosa.
Prosiding Seminar Nasional Pengabdian Masyarakat (SENEMA), 3(1), 317–322.
Wau, K. (2022). Pengembangan Sistem Informasi Persediaan Gudang Berbasis Website
Dengan Metode Waterfall. Jurnal Teknik, Komputer, Agroteknologi Dan Sains, 1(1), 10–
23.

-- 13 of 13 --

