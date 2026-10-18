# UI and UX Design System

## Design Intent

Antarmuka harus terasa bersih, tenang, dapat dipercaya, dan cepat dipahami oleh pemilik serta karyawan toko yang belum terbiasa dengan sistem. Tema cream memberi suasana lembut, sedangkan teks arang dan aksen hijau tua menjaga kontras. Desain bukan halaman promosi. Prioritasnya adalah kejelasan tugas, pencegahan kesalahan, dan keterlacakan transaksi.

## Penerapan Taste Skill

Pembacaan brief:

> Reading this as: aplikasi operasional internal untuk pengguna awam, dengan visual cream yang hangat, kontras tinggi, kepadatan data sedang, dan interaksi yang tenang.

Design dials:

| Dial | Nilai | Implikasi |
|---|---:|---|
| `DESIGN_VARIANCE` | 4/10 | Struktur familiar dengan detail identitas yang khas, tanpa eksperimen navigasi. |
| `MOTION_INTENSITY` | 2/10 | Transisi hanya sebagai feedback, tidak ada animasi dekoratif. |
| `VISUAL_DENSITY` | 6/10 | Tabel dan POS efisien, tetapi field, label, dan target sentuh tetap lapang. |

Taste skill secara eksplisit tidak menjadi design system untuk dashboard dan data table. Karena itu, Flux UI dan pola aplikasi operasional digunakan sebagai fondasi. Taste skill memandu arah visual, konsistensi, copy, aksesibilitas, motion, empty state, dan pemeriksaan akhir.

## Prinsip Desain

1. **Satu tugas utama per layar.** Aksi sekunder tidak bersaing dengan tombol utama.
2. **Dampak terlihat sebelum tindakan.** Total, perubahan stok, sisa kewajiban, dan alasan ditampilkan sebelum posting.
3. **Status tidak bergantung pada warna.** Warna selalu disertai label atau ikon.
4. **Konsistensi lebih penting daripada kebaruan.** Penempatan filter, aksi, pesan, dan dialog tetap.
5. **Bahasa operasional.** Copy singkat, langsung, dan menggunakan istilah toko.
6. **Progressive disclosure.** Pengaturan lanjut muncul ketika relevan.
7. **Tidak ada decorative clutter.** Tidak memakai gradient, glassmorphism, blob, atau kartu berlapis tanpa fungsi.

## Tema dan Token Warna

Rilis awal menggunakan light theme tunggal. Dark mode ditunda agar kualitas satu tema dapat dijaga dan pelatihan pengguna lebih sederhana.

| Token | Nilai rancangan | Penggunaan |
|---|---|---|
| `--color-canvas` | `#FCFAF5` | Latar utama cream sangat terang. |
| `--color-surface` | `#FFFDF8` | Form, panel, dialog, dan permukaan utama. |
| `--color-surface-muted` | `#F3EEE3` | Header tabel, filter, hover netral. |
| `--color-border` | `#D8CFBF` | Batas komponen. |
| `--color-border-strong` | `#B9AE9B` | Batas dengan penekanan. |
| `--color-text` | `#22251F` | Teks utama. |
| `--color-text-muted` | `#5D6458` | Teks sekunder. |
| `--color-primary` | `#315C48` | Aksi utama, tautan aktif, focus context. |
| `--color-primary-hover` | `#244638` | Hover dan pressed. |
| `--color-primary-soft` | `#E2ECE6` | Pilihan aktif dan latar status positif ringan. |
| `--color-success` | `#26734A` | Sukses. |
| `--color-warning` | `#8A5A00` | Perhatian dan mendekati jatuh tempo. |
| `--color-danger` | `#A9312A` | Error, void, dan tindakan berisiko. |
| `--color-info` | `#2E6380` | Informasi netral. |

### Aturan Warna

- Hijau tua adalah satu-satunya aksen brand utama.
- Danger merah hanya untuk error atau tindakan merusak.
- Warning kuning tua tidak digunakan sebagai dekorasi.
- Teks utama dan sekunder harus memenuhi WCAG AA terhadap latarnya.
- Border bukan satu-satunya pembeda field error; gunakan label, ikon, dan pesan.
- Kontras setiap kombinasi token diverifikasi otomatis sebelum implementasi disetujui.

### Verifikasi Kontras Token Rancangan

Rasio berikut telah dihitung pada token awal. Nilai tetap diuji ulang pada komponen final karena opacity, overlay, font weight, dan state disabled dapat mengubah hasil nyata.

| Kombinasi | Rasio | Hasil awal |
|---|---:|---|
| Teks utama pada canvas | 14,89:1 | Lulus AA dan AAA untuk teks normal. |
| Teks sekunder pada canvas | 5,87:1 | Lulus AA untuk teks normal. |
| Teks surface terang pada primary | 7,50:1 | Lulus AA dan AAA untuk teks normal. |
| Teks surface terang pada primary hover | 10,29:1 | Lulus AA dan AAA untuk teks normal. |
| Danger pada canvas | 6,37:1 | Lulus AA untuk teks normal. |
| Warning pada canvas | 5,68:1 | Lulus AA untuk teks normal. |
| Success pada canvas | 5,54:1 | Lulus AA untuk teks normal. |
| Info pada canvas | 6,27:1 | Lulus AA untuk teks normal. |

## Tipografi

| Elemen | Rancangan |
|---|---|
| Keluarga | `Geist Sans`, self-hosted; fallback `Segoe UI`, sans-serif. |
| Base | 16 px, line-height 1.5. |
| Tabel padat | 14 px minimum, line-height 1.45. |
| Judul halaman | 28 sampai 32 px, weight 650, line-height 1.2. |
| Judul bagian | 20 sampai 24 px, weight 650. |
| Label field | 14 px, weight 600. |
| Nilai uang penting | Tabular numerals, weight 650. |

Hanya satu keluarga sans-serif digunakan. Uppercase penuh dibatasi untuk kode atau label yang sangat singkat. Nilai numerik menggunakan `font-variant-numeric: tabular-nums` agar kolom mudah dibandingkan.

## Spacing, Bentuk, dan Elevation

- Grid dasar 4 px dengan langkah utama 8, 12, 16, 24, 32, dan 48 px.
- Lebar konten administrasi maksimal 1440 px; POS dapat menggunakan lebar viewport.
- Radius kontrol 10 px.
- Radius panel dan dialog 14 px.
- Badge status menggunakan radius penuh karena merupakan label kapsul, bukan container.
- Bayangan hanya untuk dialog, menu mengambang, dan panel yang benar-benar berada di atas lapisan lain.
- Card tidak dipakai untuk setiap bagian. Banyak area cukup dipisahkan dengan heading, ruang, dan border.

## Ikon

- Gunakan satu keluarga ikon bawaan Flux UI/Heroicons secara konsisten.
- Ikon navigasi memiliki label teks.
- Ikon aksi kecil wajib memiliki accessible name dan tooltip.
- Tidak membuat SVG manual jika ikon yang sesuai sudah tersedia.
- Ukuran umum 18 atau 20 px; 16 px pada tabel; 24 px hanya untuk status utama.

## Komponen Inti

### Tombol

- `Primary`: satu per konteks, hijau tua, label berupa kata kerja dan objek.
- `Secondary`: surface dengan border kuat.
- `Ghost`: tindakan ringan pada toolbar.
- `Danger`: merah dan hanya setelah konteks risiko jelas.
- Tinggi minimal 44 px pada area sentuh utama.
- Loading mempertahankan lebar, mengganti ikon dengan spinner kecil, dan mengubah label bila proses lebih dari satu detik.

### Input

- Label selalu terlihat, tidak hanya placeholder.
- Bantuan format ditempatkan sebelum error.
- Input uang merender format rupiah tetapi menyimpan nilai integer terpisah.
- Input kuantitas mendukung koma sebagai kebiasaan lokal dan dinormalisasi server-side.
- Autofocus hanya pada tugas berulang seperti pencarian POS.

### Select dan Combobox

- Select sederhana untuk daftar pendek.
- Combobox server-side untuk produk dan pemasok.
- Hasil produk menunjukkan nama, SKU, satuan, harga, dan stok tanpa terlalu padat.
- Pilihan yang tidak tersedia menjelaskan alasannya, bukan sekadar disabled.

### Tabel

- Header sticky pada daftar panjang.
- Kolom identitas berada di kiri; status dan tindakan dekat sisi kanan.
- Nilai uang rata kanan dan menggunakan tabular numerals.
- Checkbox selection hanya muncul jika bulk action benar-benar tersedia.
- Pagination server-side dengan jumlah hasil.
- Mobile menggunakan item list terstruktur atau horizontal scroll, bergantung kebutuhan perbandingan kolom.

### Status Badge

Label standar:

- Draft
- Diposting
- Lunas
- Belum lunas
- Dibayar sebagian
- Jatuh tempo
- Dibatalkan
- Nonaktif

Label menggunakan teks, ikon opsional, dan warna semantik. Istilah tidak berubah antar layar.

### Dialog

- Dialog digunakan untuk keputusan singkat, bukan form panjang.
- Judul menyebut tindakan dan objek.
- Isi menjelaskan dampak nyata.
- Tindakan berisiko meminta alasan pada form terpisah atau dialog yang cukup lebar.
- Fokus terkunci, tombol Escape berfungsi kecuali proses commit sedang berlangsung, dan fokus kembali ke pemicu saat ditutup.

### Toast dan Inline Feedback

- Toast untuk keberhasilan singkat yang tidak memerlukan tindakan.
- Error transaksi muncul inline di area tindakan dan tidak menghilang otomatis.
- Toast maksimal satu tumpukan kecil dan tidak menutupi total POS.
- Pesan menyebut hasil, contoh `Penjualan TRX-000124 berhasil disimpan`.

## Layout Utama

### Shell Aplikasi

- Sidebar 248 px pada desktop, dapat dipersempit pada tablet.
- Topbar 64 px dengan judul konteks, status koneksi, dan menu profil.
- Konten memiliki padding 24 sampai 32 px pada desktop dan 16 px pada mobile.
- Sidebar menggunakan cream sedikit lebih gelap daripada canvas, border tunggal, tanpa gradient.

### POS

- Desktop: area katalog sekitar 58 persen dan keranjang 42 persen.
- Keranjang dan total tetap terlihat saat daftar item panjang.
- Search menjadi elemen pertama dan shortcut `/` memfokuskannya saat aman.
- Tombol penyelesaian menampilkan total pada label untuk mengurangi kesalahan.
- Setelah berhasil, fokus kembali ke pencarian hanya setelah receipt state ditutup.

### Form Transaksi

- Form panjang dibagi berdasarkan makna: identitas, item, pembayaran, bukti, review.
- Desktop dapat memakai dua kolom untuk field pendek, mobile selalu satu kolom.
- Sticky summary hanya digunakan jika tidak menutupi konten atau keyboard mobile.

## Motion

- Durasi feedback 120 sampai 180 ms.
- Hanya `opacity` dan `transform` untuk transisi ringan.
- Tidak ada parallax, loop, bounce, atau animasi masuk massal.
- Skeleton mengikuti bentuk konten dan tidak berkilau berlebihan.
- `prefers-reduced-motion` menonaktifkan transisi nonesensial.

## Accessibility

- Target WCAG 2.2 AA untuk alur utama.
- Semua tindakan dapat dilakukan dengan keyboard.
- Focus ring terlihat dengan outline ganda yang kontras terhadap cream dan hijau.
- Urutan tab mengikuti urutan visual.
- Target sentuh minimal 44 kali 44 px pada tindakan utama.
- Pesan error ditautkan melalui `aria-describedby`.
- Perubahan total, item ditambahkan, dan hasil submit diumumkan secara sopan melalui live region.
- Heading tidak melompati level tanpa alasan.
- Ikon dekoratif disembunyikan dari accessibility tree.
- Dialog, combobox, menu, dan tabs memakai primitive Flux UI sebelum membuat implementasi sendiri.

## Content Design

Pola label:

- `Simpan draft`
- `Posting penerimaan`
- `Selesaikan penjualan`
- `Catat pembayaran`
- `Batalkan transaksi`

Pola error:

- Jelaskan masalah: `Stok gula tidak mencukupi.`
- Sebut kondisi: `Tersedia 2 kg, diminta 3 kg.`
- Beri tindakan: `Kurangi jumlah atau pilih produk lain.`

Hindari pesan `Terjadi kesalahan` tanpa konteks. Hindari jargon seperti hydration, exception, foreign key, atau queue pada UI pengguna.

## Loading, Empty, Error, dan Offline States

Setiap layar harus memiliki spesifikasi untuk:

- Loading awal.
- Loading tindakan.
- Empty karena belum ada data.
- Empty karena filter tidak menemukan hasil.
- Validation error.
- Authorization error.
- Server error.
- Offline atau request timeout.
- Data berubah oleh pengguna lain.

Empty state menyebut arti kondisi dan satu tindakan yang relevan. Tidak menggunakan ilustrasi besar pada layar kerja padat.

## Pre-flight Visual Wajib

Sebelum sebuah layar diterima:

1. Periksa konsistensi warna, radius, spacing, ikon, dan copy.
2. Periksa tidak ada gradient, emoji sebagai ikon, atau elemen dekoratif tanpa fungsi.
3. Periksa semua tombol memiliki state hover, focus, active, disabled, dan loading.
4. Periksa semua form memiliki label, bantuan, validasi, dan pemulihan input.
5. Periksa semua halaman pada 360, 768, 1280, dan 1536 px.
6. Periksa zoom 200 persen dan keyboard-only.
7. Periksa kontras token dan state aktual.
8. Periksa screenshot visual regression untuk layar kritis.
