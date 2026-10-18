# hh,+Revisi+dari+Author+rev (1)

Jurnal Innovation and Future Technology (IFTECH) P-ISSN: 2656-1719
Vol.6 No.2 Tahun 2024 E-ISSN: 2656-2774
223
SISTEM INFORMASI MANAJEMEN PADA TOKO
GROSIR AL-FATTAH BERBASIS WEB
Isnaini Intan Nur Khisanah1, Azizah Fatmawati2
Program Studi Informatika, Universitas Muhammadiyah Surakarta
Jl. Ahmad Yani, Pabelan, Kartasura, Surakarta
e-mail: *1isnatanoffice@gmail.com, 2af157@ums.ac.id
Abstract
This research addresses management challenges encountered by Al-Fattah wholesale store in Boyolali,
specifically in stock management, financial analysis, and profit calculations, resulting in supply and
financial gaps. To tackle these issues, a web-based Management Information System (MIS) was developed
using the Rapid Application Development (RAD) approach. The development process involved requirements
planning, design workshops, and implementation using PHP integrated with MySQL. The MIS aims to
enhance inventory reporting, accurate profit calculations, and structured sales transaction recording to
streamline operations and facilitate decision-making at Al-Fattah. Post-implementation, rigorous testing,
including System Usability Scale (SUS) assessment on 30 respondents, yielded an average score of 80,
categorizing usability as "GOOD" (Grade C), indicating favorable user acceptance due to its intuitive
interface and practical benefits. The system demonstrated significant improvements in stock management,
financial analysis, and profit calculations. In conclusion, the developed MIS effectively addresses Al-
Fattah's challenges by providing precise data insights, thereby enhancing decision-making capabilities.
This research contributes by adapting and implementing an MIS tailored for wholesale businesses in
Boyolali, showcasing practical applicability and effectiveness in real-world environments.
Keyword: Information Systems, MySQL, PHP, RAD, Wholesale Store
PENDAHULUAN
Dalam era teknologi informasi yang terus berkembang, teknologi memiliki peran penting
dalam berbagai aspek kehidupan kita, termasuk dalam dunia bisnis (Sarah et al., 2023). Aktivitas
manusia telah mendapat banyak manfaat dari kemajuan teknologi dan sistem informasi.
Penggunaan sistem informasi memiliki dampak yang signifikan terhadap operasional, terutama
bagi manajer yang terlibat dalam penjualan, di mana sejumlah besar barang disimpan dan dijual.
Pengorganisasian data dan informasi dengan benar dapat mencegah kesalahan operasional.
Catatan gudang merupakan bagian dari operasi penjualan dan elemen penting dalam distribusi
barang yang dijual. Oleh karena itu, sistem informasi dapat diimplementasikan untuk menciptakan
sumber informasi dan memproses data stok. Dahulu, proses ini membutuhkan waktu yang lama,
tetapi sekarang semuanya sudah terdigitalisasi (Susanto & Purnomo, 2023). Seiring dengan
perkembangan ini, penting juga untuk memahami konsep kinerja yang lebih luas dan dampaknya.
Hal ini dapat membantu menganalisis perkembangan transformasi digital, karena teknologi pada
dasarnya dapat mendukung produksi, layanan, dan kinerja (Bacca-Acosta et al., 2023). Transisi
ini dikenal sebagai layanan digital, yang dapat didefinisikan sebagai penggunaan teknologi digital
untuk proses transformasi. Teknologi digital ini mengubah model bisnis dan dasar persaingan
industri (Momeni et al., 2023). Implementasi Sistem Informasi Manajemen (SIM), seperti yang
diungkapkan oleh Agustin & Ikasari (2023) adalah fondasi utama dalam memanfaatkan teknologi
informasi dalam dunia bisnis, hal ini berkaitan dengan pengelolaan data dan penyampaian
informasi yang tepat kepada pihak manajemen, yang berperan dalam mendukung pengambilan
keputusan yang berbasis data, melakukan evaluasi kinerja, serta merespon perubahan dalam
lingkungan bisnis. Sedangkan menurut Aswiputri (2022) sistem Informasi Manajemen (SIM)

-- 1 of 11 --

Jurnal Innovation and Future Technology (IFTECH) P-ISSN: 2656-1719
Vol.6 No.2 Tahun 2024 E-ISSN: 2656-2774
224
merupakan serangkaian komponen yang berinteraksi satu sama lain yang digunakan untuk
menghimpun, mengolah, menyimpan, dan menyebarkan informasi guna mendukung pengambilan
keputusan dan pengelolaan. SIM juga memfasilitasi analisis masalah, pengidentifikasian isu-isu
kompleks, dan penghasilan data yang akurat. Selain itu, SIM mencakup unsur input, model, dan
proses. Sebuah sistem biasanya terdiri dari elemen-elemen yang saling berhubungan, yang bekerja
sama untuk mencapai tujuan utama sistem.
Toko grosir Al-Fattah merupakan toko grosir makanan dan minuman yang terletak di
Boyolali. Sebagai bisnis yang telah lama beroperasi, menyadari pentingnya memanfaatkan
teknologi untuk meningkatkan operasional dan akses cepat terhadap informasi krusial. Toko
grosir Al-Fattah ini belum melakukan pembukuan secara tertulis terkait pengelolaan stok dan
penghitungan laba, menyebabkan kesulitan dalam mendapatkan informasi laba secara akurat.
Observasi dan wawancara dengan pemilik toko mengungkapkan bahwa toko ini menghadapi
tantangan dalam manajemen persediaan stok, analisis laporan keuangan, dan perhitungan laba.
Berdasarkan masalah ini, maka penulis mengembangkan sistem informasi manajemen stok dan
penghitungan laba, diharapkan dapat membantu pemilik toko dalam pengelolaan bisnis. Beberapa
penelitian terdahulu yang relevan dengan penelitian ini adalah penelitian yang dilakukan oleh
Puspaningrum & Sudarmilah (2020), yaitu tentang pengembangan sistem informasi manajemen
pengelolaan aset tata ruang taman budaya Jawa Tengah bertujuan untuk mempermudah pendataan
manajemen aset sarana dan prasarana. Penelitian yang dilakukan oleh Masgo & Santoso (2022)
tentang sistem informasi manajemen stok pada toko Jasmine bertujuan untuk pencatatan
transaksi, stok barang dan laporan. Penelitian yang dilakukan oleh Gumilang (2023) tentang
perencanaan sistem manajemen stok barang pada PT.X bertujuan untuk membantu pengoprasian
pencatatan stok barang. Perbandingan dari penelitian ini dengan penelitian terdahulu yang telah
dilakukan tentang sistem informasi manajemen terletak pada objek penelitian yang berbeda, yaitu
toko grosir Al-Fattah. Selain itu, penelitian ini juga akan mengembangkan sistem informasi yang
lebih lengkap dengan adanya manajemen pemasok.
Penelitian ini penulis mengimplementasikan sistem informasi manajemen yang
terkomputerisasi. Sistem ini dikembangkan menggunakan metode Rapid Application
Development (RAD), dengan menggunakan bahasa pemrograman PHP, dan diintegrasikan
dengan database MySQL. Memilih database MYSQL karena itu adalah database relational yang
populer dan mudah digunakan saat ini (Negru et al., 2022). MySQL adalah perangkat lunak sistem
manajemen basis data SQL atau DBMS yang multithread dan multiuser yang mendukung bahasa
database SQL sebagai bahasa interaktif untuk mengelola data. MySQL juga merupakan database
engine atau server database (Fitri, R. 2020).
METODE PENELITIAN
Sistem informasi manajemen pada toko grosir Al-Fattah ini menggunakan metode
pengembangan RAD (Rapid Application Development). Rapid Application Development (RAD)
adalah model proses pengembangan perangkat lunak yang mengikuti urutan linier, tetapi dengan
siklus pengembangan yang sangat cepat, biasanya sekitar 60 hingga 90 hari, model RAD ini
adalah adaptasi berkecepatan tinggi dari model linier sekuensial, di mana kecepatan
pengembangan dicapai melalui pendekatan konstruksi berbasis komponen (Pricillia, 2021). RAD
merupakan metode pengembangan perangkat lunak yang menggunakan pendekatan berorientasi
objek dalam pengembangan sistem (Sikumbang et al., 2020). Penerapan metode RAD pada sistem
informasi manajemen toko grosir Al-Fattah bertujuan untuk menciptakan sistem informasi
manajemen dengan cepat. Dengan pendekatan iteratif RAD, setiap fase pengembangan
memungkinkan peningkatan dan penyempurnaan berkelanjutan, sehingga sistem dapat dengan
cepat menyesuaikan diri terhadap perubahan kebutuhan yang mungkin muncul selama proses
pengembangan. Menurut Kendall (2010), yang tertera pada Gambar 1 merupakan tiga fase
tahapan dari metode RAD.

-- 2 of 11 --

Jurnal Innovation and Future Technology (IFTECH) P-ISSN: 2656-1719
Vol.6 No.2 Tahun 2024 E-ISSN: 2656-2774
225
Gambar 1. Tahapan Metode RAD
a) Requirements Planning
Dalam fase ini, peneliti melakukan analisis dengan bertemu pemilik toko grosir
Al-Fattah yang dalam konteks ini pemilik toko nantinya akan menjadi pengguna (user)
utama dari sistem informasi manajemen ini, untuk membahas tujuan sistem informasi ini
dan mengidentifikasi sistem untuk mengetahui apa saja yang diperlukan untuk mencapai
tujuan tersebut. Orientasi dalam fase ini adalah menyelesaikan masalah dari pemilik toko
grosir Al-Fattah dan memastikan bahwa sistem yang akan dikembangkan dapat
mendukung kebutuhan bisnis. Keselarasan antara tujuan sistem informasi dengan visi
bisnis pemilik toko adalah kunci dalam menciptakan solusi yang berhasil dan berdaya
guna.
Kebutuhan fungsional dan non-fungsional pada sistem informasi manajemen
berbasis web untuk toko Al-Fattah diperoleh melalui teknik analisis kebutuhan yang
melibatkan observasi langsung terhadap proses bisnis toko dan wawancara dengan
pemilik toko. Kebutuhan fungsional adalah spesifikasi atau deskripsi tentang apa yang
harus dilakukan oleh sebuah sistem informasi. Identifikasi fitur-fitur, fungsi-fungsi, atau
operasi-operasi yang harus ada dalam sistem untuk memenuhi tujuan bisnis atau
kebutuhan pengguna. Kebutuhan fungsional menjawab pertanyaan “Apa yang harus
dilakukan sistem?”. Kebutuhan fungsional pada sistem informasi manajemen berbasis
web pada toko Al-Fattah antara lain sistem ini dapat membantu dalam melakukan
pendataan data produk dan data pemasok, menghitung pendapatan dan laba,
menampilkan stok barang dan dapat menampilkan laporan keuangan.
Kebutuhan non-fungsional adalah atribut atau karakteristik yang
menggambarkan kualitas atau performa sistem, tidak terkait dengan apa yang sistem
lakukan, tetapi lebih kepada bagaimana sistem itu melakukannya. Kebutuhan non-
fungsional mencakup aspek-aspek seperti keamanan, kinerja, tampilan antarmuka
pengguna, skalabilitas, dan persyaratan perangkat keras serta perangkat lunak yang
mendukung operasi sistem. Kebutuhan non-fungsional menjawab pertanyaan
“Bagaimana sistem harus beroperasi?”. Kebutuhan non-fungsional untuk pengembangan
sistem informasi ini mencakup kebutuhan perangkat keras seperti laptop atau komputer.
Sementara untuk kebutuhan perangkat lunak dalam proses pengembangannya termasuk
sistem operasi Windows.10 yang mendukung XAMPP dan dapat diakses dengan
software web browser diantaranya Google Chrome, Mozilla firefox dan Microsoft Edge.
b) RAD Design Workshop
Pada fase ini, desain perangkat lunak dibuat dan prototipe dikembangkan. Tujuannya
adalah untuk membuat prototipe kerja yang dapat diuji dan diperbaiki. Perancangan
desain sistem ini dibuat menggunakan UML (Unified Modeling Language).
1.) Use Case Diagram
Use case diagram merupakan deskripsi hak akses tertentu yaitu user dan
pengelola yang bisa memanfaatkan sistem (Arif et al., 2023). Gambar 2 menunjukkan
use case diagram sistem informasi manajemen pada toko grosir Al-Fattah berbasis
web. Sistem ini memberikan kemampuan untuk mengelola data produk,

-- 3 of 11 --

Jurnal Innovation and Future Technology (IFTECH) P-ISSN: 2656-1719
Vol.6 No.2 Tahun 2024 E-ISSN: 2656-2774
226
memanajemen data pemasok, melihat stok, melihat laporan keuangan termasuk login
dan logout.
Gambar 2. Use Case Diagram Sistem Informasi Manajemen pada Toko Grosir Al-Fattah
2.) Activity Diagram
Activity diagram adalah bagian penting dari kumpulan diagram yang digunakan
dalam UML (Unified Modelling Language). Diagram menunjukkan aliran kegiatan
yang berlangsung pada metode untuk menunjukkan alur pelaksanaan dari awal sistem
hingga akhir (Arif et al., 2023). Setelah tahapan perancangan skenario use case
selesai, tahapan ini akan menjelaskan fungsi sistem informasi manajemen toko grosir
Al-Fattah dengan menggunakan activity diagram. Activity diagram akan membantu
kita dalam memvisualisasikan proses dan aliran kegiatan yang terjadi dalam sistem
manajemen toko grosir Al-Fattah secara lebih rinci dan terstruktur. Pada Gambar 3
merupakan alur sistem informasi manajemen pada toko grosir Al-Fattah dari awal
masuk sistem informasi manajemen sampai akhir.
Gambar 3. Activity Diagram Sistem Informasi Manajemen pada Toko
Grosir Al-Fattah

-- 4 of 11 --

Jurnal Innovation and Future Technology (IFTECH) P-ISSN: 2656-1719
Vol.6 No.2 Tahun 2024 E-ISSN: 2656-2774
227
3.) ERD (Entity Relation Diagram)
Entity Relationship Diagram (ERD) adalah perancangan basis data yang
menggunakan diagram untuk membuat rancangan hubungan antar tabel pada
database yang sedang dibangun (Arif et al., 2023). Gambar 4 merupakan ERD sistem
informasi manajemen toko grosir berbasis web menggambarkan entitas, atribut, dan
relasi adalah bagian dari proses menyajikan informasi tentang struktur data. Setiap
entitas memiliki atribut yang menentukan karakteristiknya, sementara relasi
menggambarkan keterkaitan antara entitas-entitas tersebut.
Gambar 4. ERD (Entity Relation Diagram)
4.) UI (User Interface)
Antarmuka Pengguna (User Interface) adalah tahap di mana ide-ide terkait
dengan tampilan sistem yang akan dikembangkan direalisasikan menjadi gambar-
gambar.
c) Implementation
Tahap implementasi adalah saat di mana sistem yang telah dirancang digambarkan
dalam bentuk kode program, lalu diaplikasikan dalam bentuk situs web (Setyawan,
2021). Pada tahap implementasi melibatkan pengembangan sistem secara cepat
berdasarkan hasil desain prototipe yang telah disepakati. Pengujian berulang untuk
memastikan bahwa prototipe berfungsi sesuai dengan kebutuhan. Dalam RAD, fokus
utama adalah menghasilkan sistem yang dapat digunakan secepat mungkin, sehingga
pengembang dan pemangku kepentingan dapat terlibat aktif dalam pengujian dan validasi
sistem sepanjang proses. Pengujian dalam tahap implementasi ini dilakukan dengan
menggunakan pendekatan Black Box, yang berfokus untuk memastikan bahwa sistem
yang dikembangkan sesuai dengan spesifikasi fungsional yang telah ditentukan dengan
cara memastikan apakah input dan output sistem sudah sesuai dengan keinginan
pengguna sistem. Pengujian usability dilakukan melalui System Usability Scale (SUS)
yang merupakan salah satu metode standar yang digunakan untuk mengukur kegunaan
atau kemudahan sistem, SUS merupakan kuesioner yang terdiri dari 10 pertanyaan
dengan skala 5 poin yang dirancang untuk menilai faktor-faktor seperti kepuasan
pengguna, efisiensi dan kompleksitas sistem (Arjiansa & Sutabri, 2023). Dengan
menggunakan SUS, pengembang dapat mengumpulkan data yang objektif mengenai
persepsi pengguna terhadap kegunaan sistem yang telah diimplementasikan.

-- 5 of 11 --

Jurnal Innovation and Future Technology (IFTECH) P-ISSN: 2656-1719
Vol.6 No.2 Tahun 2024 E-ISSN: 2656-2774
228
HASIL DAN PEMBAHASAN
Hasil
Hasil dari penelitian ini adalah telah terciptanya sistem informasi manajemen di toko
grosir Al-Fattah berbasis web. Pengguna harus melakukan registrasi terlebih dahulu sebelum
mengakses sistem, dan setelahnya bisa melakukan login dengan mengisi e-mail dan password.
Gambar 6 merupakan tampilan registrasi dan login.
(a) (b)
Gambar 6. (a) Menu Registrasi, (b) Menu Login
Aktivitas yang dapat dilakukan oleh pengguna yaitu melihat perkembangan toko yang ada pada
dashboard. Tampilan menu dashboard ada pada Gambar 7.
Gambar 7. Menu Dashboard
Selain itu, pengguna juga dapat melakukan berbagai aktivitas dalam sistem. Pengguna memiliki
akses untuk melihat daftar produk yang tersedia di toko, termasuk kemampuan untuk
menambahkan produk baru ke dalam daftar, mengedit detail produk yang sudah ada, menghapus
produk yang tidak lagi relevan, serta melihat detail spesifik dari setiap produk. Selain itu,
pengguna juga memiliki kemampuan untuk mengelola daftar pemasok, seperti melihat daftar
pemasok yang tersedia, menambahkan pemasok baru ke dalam sistem, mengedit informasi
pemasok yang sudah ada, menghapus pemasok yang tidak diperlukan lagi, dan melihat detail
informasi dari setiap pemasok.
Pengguna juga memiliki kontrol atas inventori barang, menambahkan barang-barang
baru ke dalam inventori, memantau stok barang yang tersedia, serta melakukan aktivitas
manajemen lainnya yang terkait dengan inventori, seperti mengatur penempatan barang atau
menghitung jumlah barang yang tersisa. Pengguna juga memiliki kemampuan untuk mencatat
setiap transaksi penjualan harian yang terjadi di toko. Pengguna dapat melihat total laba dan
penghasilan yang dihasilkan dari penjualan, serta melakukan manajemen penjualan lainnya sesuai
kebutuhan. Selain fitur-fitur operasional tersebut, pengguna juga memiliki akses untuk mencetak
laporan-laporan yang diperlukan, seperti laporan keuangan sesuai dengan kebutuhan bisnis.
Pengguna juga dapat mengakses dan mengedit informasi profil.
Setelah menggunakan fitur-fitur tersebut pengguna dapat logout dari sistem dan akan
kembali ke halaman login untuk keluar dari sesi pengguna. Proses logout ini memberikan
pengguna kemudahan dalam mengelola keamanan akun dan menjaga kerahasiaan informasi.
Selain itu, semua menu dan fitur tersebut dirancang untuk memberikan pengguna kendali penuh
atas operasional dan manajemen toko grosir Al-Fattah. Tampilan menu produk, menu pemasok,
menu inventori, menu penjualan, menu cetak laporan, dan menu profil dapat dilihat pada Gambar
8.

-- 6 of 11 --

Jurnal Innovation and Future Technology (IFTECH) P-ISSN: 2656-1719
Vol.6 No.2 Tahun 2024 E-ISSN: 2656-2774
229
(a) (b)
(c) (d)
(e) (f)
Gambar 8. (a) Menu Produk, (b) Menu Pemasok, (c) Menu Inventori, (d) Menu Penjualan,
(e) Menu Cetak Laporan, (f) Menu Profil
Pembahasan
Dalam proses pengembangan sistem, ada langkah pengujian yang diperlukan untuk
menilai sejauh mana sistem yang dibangun dapat memenuhi kebutuhan pengguna dan berfungsi
sesuai dengan yang diharapkan. Tahap pengujian sistem ini dilakukan dengan pendekatan Black
Box, di mana fokus utamanya adalah pada fungsionalitas dan interaksi antarmuka pengguna tanpa
memperhatikan struktur internal atau logika program yang mendasarinya. Tabel 1 adalah tabel
yang memuat hasil pengujian Black Box sistem informasi manajemen toko grosir Al-Fattah, yang
menjadi landasan evaluasi untuk memastikan kehandalan dan kualitas sistem sebelum
diimplementasikan secara penuh.
Tabel 1. Hasil Pengujian Black Box
No Pengujian Kondisi Pengujian Harapan Hasil
1 Menu Produk
1. Tampilkan daftar
produk
1. Daftar produk ditampilkan
dengan benar
Valid
2. Mencari produk
berdasarkan nama
produk
2. Menampilkan nama produk
yang dicari
3. Tambahkan produk
baru
3. Produk baru ditambahkan
dan terlihat di daftar
4. Edit produk yang ada 4. Produk yang ada dapat
diedit dengan sukses
5. Hapus produk dari
daftar
5. Produk dihapus dengan
benar dari daftar
6. Lihat detail produk
tertentu
6. Detail produk tertentu dapat
dilihat dengan benar
2 Menu Pemasok
1. Tampilkan daftar
pemasok
1. Daftar pemasok
ditampilkan dengan benar Valid
2. Tambahkan pemasok
baru
2. Pemasok baru ditambahkan
dan terlihat di daftar

-- 7 of 11 --

Jurnal Innovation and Future Technology (IFTECH) P-ISSN: 2656-1719
Vol.6 No.2 Tahun 2024 E-ISSN: 2656-2774
230
Pengujian usability dilakukan dengan menggunakan System Usability Scale (SUS), di mana
peneliti menguji ini untuk memahami respons atau reaksi pengguna terhadap situs web tersebut.
Peneliti menyampaikan kuesioner kepada responden dan menampilkan hasilnya dalam Tabel 2.
Tabel 2. Hasil Akhir Kuisioner dengan Menggunakan SUS
Responden Q1 Q2 Q3 Q4 Q5 Q6 Q7 Q8 Q9 Q10 Jumlah Skor Akhir
SUS
1 4 4 3 3 3 4 4 4 4 2 35 88
2 3 3 3 2 3 2 3 3 3 2 27 68
3 3 3 3 1 3 2 2 2 3 1 23 58
4 3 4 4 4 3 3 4 4 4 0 33 83
5 3 3 3 2 3 3 4 3 3 1 28 70
6 4 4 4 4 4 4 4 4 4 4 40 100
7 3 4 4 3 3 3 3 3 3 3 32 80
8 3 3 2 1 3 3 3 3 3 1 25 63
9 4 4 4 2 4 4 4 4 4 3 37 93
10 4 4 4 3 4 4 4 4 4 3 38 95
11 3 3 3 3 4 3 4 4 3 4 34 85
12 4 3 3 3 4 4 4 3 3 4 35 88
13 3 4 4 4 4 4 3 4 4 1 35 88
14 4 3 4 1 3 3 4 3 3 1 29 73
3. Edit pemasok yang
ada
3. Pemasok yang ada dapat
diedit dengan sukses
4. Hapus pemasok dari
daftar
4. Pemasok dihapus dengan
benar dari daftar
5. Lihat detail pemasok
tertentu
5. Detail pemasok tertentu
dapat dilihat dengan benar
3 Menu Inventori
1. Tambahkan barang ke
inventori
1. Barang berhasil
ditambahkan ke inventori
Valid
2. Pastikan stok produk
otomatis bertambah
setelah barang masuk
2. Stok produk bertambah
setelah barang masuk
3. Edit inventori yang
ada
3. Inventori dapat diedit
dengan sukses
4. Hapus inventori yang
ada
4. Inventori dapat dihapus
dengan sukses
5. Lihat detail inventori
tertentu
5. Detail inventori tertentu
dapat dilihat dengan benar
4 Menu Penjualan
1. Catat penjualan harian 1. Penjualan harian berhasil
dicatat
Valid
2. Deteksi total laba dan
penghasilan secara
otomatis setelah
penjualan harian
2. Total laba dan penghasilan
dihitung secara akurat
setelah penjualan
3. Pastikan stok produk
otomatis berkurang
setelah penjualan
3. Stok produk berkurang
sesuai dengan penjualan
yang dicatat
4. Edit penjualan yang
ada
4. Penjualan dapat diedit
dengan sukses
5. Hapus penjualan yang
ada
5. Penjualan dapat dihapus
dengan sukses
6. Lihat detail penjualan
tertentu
6. Detail penjualan tertentu
dapat dilihat dengan benar
5 Menu Cetak Laporan
1. Filter laporan
berdasarkan tanggal
1. Laporan dapat disaring
berdasarkan tanggal dengan
benar Valid
2. Cetak laporan dalam
format PDF
2. Laporan dicetak dalam
format PDF dengan tata
letak yang sesuai

-- 8 of 11 --

Jurnal Innovation and Future Technology (IFTECH) P-ISSN: 2656-1719
Vol.6 No.2 Tahun 2024 E-ISSN: 2656-2774
231
15 2 2 3 4 2 3 2 4 4 2 28 70
16 4 4 4 3 4 3 4 3 4 3 36 90
17 3 3 3 3 3 3 3 4 2 1 28 70
18 4 4 4 2 4 1 4 2 3 4 32 80
19 3 3 3 2 3 3 2 3 2 1 25 63
20 3 4 4 4 1 3 2 3 3 3 30 75
21 4 4 4 4 4 4 4 4 4 4 40 100
22 3 3 4 2 4 2 3 3 3 0 27 68
23 3 2 2 1 3 2 3 3 3 1 23 58
24 4 4 4 4 4 4 4 4 4 4 40 100
25 4 3 3 3 4 4 3 3 4 0 31 78
26 3 3 3 3 3 3 3 3 2 3 29 73
27 4 4 4 4 4 4 4 4 4 4 40 100
28 3 3 3 2 3 3 3 3 3 1 27 68
29 3 3 4 3 4 3 4 3 4 2 33 83
30 3 4 4 4 3 4 4 4 4 3 37 93
Skor Rata-rata (Hasil Akhir) 80
Hasil akhir dari nilai rata-rata pengujian System Usability Scale (SUS) adalah 80. Berdasarkan
panduan pengujian SUS pada Gambar 9, skor rata-rata 80 mengklasifikasikan sistem ke dalam
kategori grade C dengan penilaian "GOOD". Ini menunjukkan bahwa sistem yang telah
dikembangkan memiliki tingkat kegunaan yang baik dan diterima dengan baik oleh pengguna.
Gambar 9. Menunjukkan Perbandingan antara Kata Sifat, Skor Penerimaan, dan Skala Penilaian yang
Terkait dengan Skor Rata-rata System Usability Scale (SUS).
KESIMPULAN
Hasil yang diperoleh dari penelitian ini mencakup:
1. Terwujudnya sistem informasi manajemen berbasis web pada toko grosir Al-Fattah.
2. Pengujian Black Box menunjukkan bahwa tidak ada kesalahan yang terdeteksi, menunjukkan
kualitas sistem yang baik.
3. Hasil pengujian System Usability Scale (SUS) dengan nilai rata-rata sebesar 80 menunjukkan
bahwa sistem memiliki tingkat kegunaan yang memuaskan grade C dengan rating "GOOD".
4. Meskipun berhasil, sistem ini masih memiliki potensi untuk pengembangan lebih lanjut,
seperti penambahan fitur atau peningkatan kinerja untuk meningkatkan pengalaman
pengguna.
Dengan demikian, implementasi sistem informasi manajemen telah membawa manfaat bagi toko
grosir Al-Fattah dan masih memiliki potensi untuk pengembangan lebih lanjut.
SARAN
Toko grosir Al-Fattah dapat memperluas fungsionalitas sistem informasi manajemen
dengan menambahkan fitur pemindaian barcode. Penambahan fitur ini akan memberikan
kemudahan bagi pengguna untuk memindai barcode setiap produk di toko menggunakan
perangkat pemindaian barcode. Dengan adanya fitur ini, sistem dapat secara otomatis mengenali
dan mengekstrak informasi yang terkait dari barcode, termasuk nama produk dan harga.
Selanjutnya, informasi tersebut akan ditampilkan langsung di antarmuka pengguna sistem,
memudahkan proses manajemen stok dan penjualan di toko.

-- 9 of 11 --

Jurnal Innovation and Future Technology (IFTECH) P-ISSN: 2656-1719
Vol.6 No.2 Tahun 2024 E-ISSN: 2656-2774
232
DAFTAR PUSTAKA
Agustin, W., & Ikasari, I. H. (2023). Literature Review Sistem Informasi Manajemen Dalam
Perkembangan Bisnis. JRIIN: Jurnal Riset Informatika Dan Inovasi, 1(2), 337-340.
Akhsanul'Arif, A., & Putri, D. A. P. (2023). Perancangan Dan Implementasi Web Penjualan Pada
Toko Juragan Laptop Second Pati. Emitor: Jurnal Teknik Elektro, 1(1), 55-64.
Arjiansa, R. R., & Sutabri, T. (2023). Pengukuran Tingkat Kemudahan Pegawai Terhadap
Penggunaan Layanan Aplikasi SIMRS Menggunakan Metode System Usability Scale
(SUS) Pada Rumah Sakit Umum Daerah Sekayu. Indonesian Journal of
Multidisciplinary on Social and Technology, 1(2), 115-120.
Aswiputri, M. (2022). Literature Review Determinasi Sistem Informasi Manajemen: Database,
CCTV Dan Brainware. Jurnal Ekonomi Manajemen Sistem Informasi, 3(3), 312-322.
Bacca-Acosta, J., Gómez-Caicedo, M. I., Gaitán-Angulo, M., Robayo-Acuña, P., Ariza-Salazar,
J., Mercado Suárez, Á. L., & Alarcón Villamil, N. O. (2023). The impact of digital
technologies on business competitiveness: a comparison between Latin America and
Europe. Competitiveness Review: An International Business Journal, 33(7), 22-46.
Fitri, R. (2020). Pemrograman Basis Data Menggunakan MySQL. Deepublish.
Gumilang, V. T. (2023). Perancangan Sistem Manajemen Stok Barang Berbasis Web Pada PT.
X. Jurnal Ilmu Komputer dan Sistem Informasi, 11(1).
Kendall & Kendall. (2010). Analisis dan Perancangan Sistem Informasi. Edisi 5. Jakarta.
Masgo, M., & Santoso, S. (2022). Prototype Sistem Informasi Manajemen Stok Berbasis Web
Pada Toko Jasmine. Jurnal Ilmiah Media Sisfo, 16(1), 33-40.
Momeni, K., Raddats, C., & Martinsuo, M. (2023). Mechanisms for developing operational
capabilities in digital servitization. International Journal of Operations & Production
Management, 43(13), 101-127.
Negru, C., Musat, G., Colezea, M., Anghel, C., Dumitrascu, A., Pop, F., & Castiglione, A. (2022).
Dependable workflow management system for smart farms. Connection Science, 34(1),
1833-1854.
Pricillia, T. (2021). Perbandingan metode pengembangan perangkat lunak (waterfall, prototype,
RAD). Jurnal Bangkit Indonesia, 10(1), 6-12.
Puspaningrum, A., & Sudarmilah, E. (2020). Sistem Informasi Manajemen Peminjaman (Studi
Kasus: Pengelolaan Aset Dan Tata Ruang Taman Budaya Jawa Tengah). Technologia:
Jurnal Ilmiah, 11(1), 37-45.
Sarah, S., Syahputra, E. R., & Elsera, M. (2023). E–Manajemen Butik Dengan Menggunakan
Metode Rapid Application Development (Rad). Djtechno: Jurnal Teknologi Informasi,
4(1), 254-262.
Setyawan, R., & Maryam, M. (2021). Sistem Informasi Penjualan Alat Elektronik Berbasis Web
Pada Toko Mandiri Elektronik Purwantoro. Jurnal Digital Teknologi Informasi, 4(1), 8-
17.
Sikumbang, M. A. R., Habibi, R., & Pane, S. F. (2020). Sistem informasi absensi pegawai

-- 10 of 11 --

Jurnal Innovation and Future Technology (IFTECH) P-ISSN: 2656-1719
Vol.6 No.2 Tahun 2024 E-ISSN: 2656-2774
233
menggunakan metode RAD dan metode LBS pada koordinat absensi. Jurnal Media
Informatika Budidarma, 4(1), 59-64.
Susanto, D. A. (2023). Perancangan Sistem Informasi Gudang Berbasis Web Mengunakan
Framework Laravel. JATISI (Jurnal Teknik Informatika dan Sistem Informasi), 10(1), 63-
77.

-- 11 of 11 --

