# Daftar Tugas Eksekusi (*Task Breakdown*) — Perbaikan BAB IV & BAB V Laporan KP

Daftar tugas ini diurutkan berdasarkan ketergantungan (*dependencies*) agar setiap irisan pekerjaan dapat diverifikasi secara tuntas sebelum melangkah ke tahap berikutnya.

---

## [x] Tugas 1: Perbaikan Parser Bold/Italic Word & Penulisan Ulang Non-Teknis Subbab 4.1 s.d. 4.2.1
- **Tujuan**: Memperbaiki bug `**tulisan**` yang tidak menjadi tebal ketika memuat cetak miring di dalamnya (seperti pada poin 2 dan 3 Subbab `4.2.1.5`), serta membersihkan Subbab `4.1` dan `4.2.1` (`Tabel 4.1` s.d. `Tabel 4.6`) dari seluruh nama kode program, nama file, kode hak akses (`stock.adjust`, `dashboard.view_owner`, dll.), dan nama kolom basis data (`initial_payment_rupiah`, `total_rupiah`, `minimum_quantity`, `conversion_to_base`, dll.).
- **File yang Diubah**:
  - `C:\Users\PREDATOR\.gemini\antigravity\brain\963a8e43-bae7-40d6-883e-42932eceeeda\scratch\compile_bab4.js`
  - `C:\Users\PREDATOR\.gemini\antigravity\brain\963a8e43-bae7-40d6-883e-42932eceeeda\scratch\bab4_sec_41_421.js`
- **Ketergantungan**: Tidak ada (Fondasi awal).
- **Kriteria Verifikasi**:
  - Parser `renderInlineRuns` mendukung pemformatan bersarang `**... *...* ...**` dan menghasilkan `<w:b/>` + `<w:i/>` yang valid di XML Word.
  - Tidak ada lagi kode izin seperti `stock.adjust`, `users.*`, `sales.create` di `Tabel 4.6` maupun nama kolom seperti `initial_payment_rupiah`, `total_rupiah`, `conversion_to_base` di Subbab `4.2.1.5`.

---

## [x] Tugas 2: Pembaruan Mesin Render `.drawio` → `.png` Monokrom & Validator Geometri Anti-Tabrakan
- **Tujuan**: Meningkatkan perender `.drawio` ke SVG/PNG agar mendukung gaya **100% Monokrom Hitam-Putih**, membaca atribut `exitX`, `exitY`, `entryX`, `entryY`, serta `<Array as="points">` (titik belok ortogonal), memisahkan titik masuk panah agar tidak bertumpuk, dan menyertakan validator geometri otomatis yang menolak garis yang menembus kotak lain.
- **File yang Diubah**:
  - `C:\Users\PREDATOR\.gemini\antigravity\brain\963a8e43-bae7-40d6-883e-42932eceeeda\scratch\generate_diagrams_part2.js`
- **Ketergantungan**: Tugas 1.
- **Kriteria Verifikasi**:
  - Fungsi validator geometri otomatis melaporkan **0 tabrakan garis-ke-kotak** (*0 edge-box collisions*) dan **0 tumpukan garis** (*0 overlapping segments*).
  - Seluruh elemen didefinisikan dengan warna latar `#FFFFFF`, garis `#000000`, dan teks `#000000`.

---

## [x] Tugas 3: Pembuatan Ulang & Audit Visual Diagram Proses Monokrom Non-Teknis (`Gambar 4.1`–`Gambar 4.7`) + Revisi Subbab `4.2.2.1`
- **Tujuan**: Membuat ulang 7 diagram pemodelan proses (`01-use-case-diagram`, `02-flowchart-pos`, `03-flowchart-procurement`, `04-activity-pos`, `05-activity-procurement`, `06-sequence-pos`, `07-sequence-procurement`) dalam format hitam-putih tanpa istilah teknis kode/database, memeriksa setiap PNG secara visual, serta merevisi narasi dan `Tabel 4.7`–`Tabel 4.8` pada Subbab `4.2.2.1`.
- **File yang Diubah**:
  - `C:\Users\PREDATOR\.gemini\antigravity\brain\963a8e43-bae7-40d6-883e-42932eceeeda\scratch\generate_diagrams_part1.js`
  - `C:\Users\PREDATOR\.gemini\antigravity\brain\963a8e43-bae7-40d6-883e-42932eceeeda\scratch\bab4_sec_422.js` (bagian `4.2.2.1`)
  - `d:\Herd\nf-harapan-rumbai-web\KP\diagrams\01-*.{drawio,png}` s.d. `07-*.{drawio,png}`
- **Ketergantungan**: Tugas 2.
- **Kriteria Verifikasi**:
  - Inspeksi visual wajib menggunakan `read_file` pada `01-use-case-diagram.png` s.d. `07-sequence-procurement.png`: memastikan 100% hitam-putih, 0 istilah teknis kode, tidak ada panah yang menimpa kotak/teks, dan arah setiap panah terlihat jelas.

---

## [x] Tugas 4: Pembuatan Ulang & Audit Visual Diagram Struktur & Wireframe Monokrom (`Gambar 4.8`–`Gambar 4.17`) + Revisi Subbab `4.2.2.2`–`4.2.2.4`
- **Tujuan**: Membuat ulang *Class Diagram* (`08-class-diagram`), *ERD* (`09-erd-diagram`), dan 8 *Wireframe* (`10-wireframe-login` s.d. `17-wireframe-audit-log`) dalam format hitam-putih bersih tanpa tabrakan garis dan tanpa label kode di *wireframe*, memeriksa setiap PNG secara visual, serta merevisi narasi `4.2.2.2`–`4.2.2.4` dan `Tabel 4.21`–`Tabel 4.28`.
- **File yang Diubah**:
  - `C:\Users\PREDATOR\.gemini\antigravity\brain\963a8e43-bae7-40d6-883e-42932eceeeda\scratch\generate_diagrams_part1.js`
  - `C:\Users\PREDATOR\.gemini\antigravity\brain\963a8e43-bae7-40d6-883e-42932eceeeda\scratch\generate_diagrams_part2.js`
  - `C:\Users\PREDATOR\.gemini\antigravity\brain\963a8e43-bae7-40d6-883e-42932eceeeda\scratch\bab4_sec_422.js` (bagian `4.2.2.2`–`4.2.2.4`)
  - `d:\Herd\nf-harapan-rumbai-web\KP\diagrams\08-*.{drawio,png}` s.d. `17-*.{drawio,png}`
- **Ketergantungan**: Tugas 2 & Tugas 3.
- **Kriteria Verifikasi**:
  - Inspeksi visual wajib menggunakan `read_file` pada `08-class-diagram.png` s.d. `17-wireframe-audit-log.png`: memastikan 100% hitam-putih, garis relasi `08` dan `09` berjalan di lorong kosong tanpa memotong kotak entitas/kelas, dan *wireframe* `10`–`17` bersih dari kode teknis.

---

## [x] Tugas 5: Penulisan Ulang Non-Teknis Subbab `4.2.3`–`4.2.6` dan `BAB V (Penutup)`
- **Tujuan**: Membersihkan seluruh narasi implementasi antarmuka (`4.2.3.2`), skenario pengujian *Black Box* (`4.2.4` / `Tabel 4.30`–`Tabel 4.38`), pembahasan hasil pengujian (`4.2.5`), evaluasi sistem (`4.2.6`), serta Kesimpulan dan Saran (`Bab V`) dari rute URL, nama *class/method*, kode event, dan nama kolom basis data.
- **File yang Diubah**:
  - `C:\Users\PREDATOR\.gemini\antigravity\brain\963a8e43-bae7-40d6-883e-42932eceeeda\scratch\bab4_sec_423_426.js`
  - `C:\Users\PREDATOR\.gemini\antigravity\brain\963a8e43-bae7-40d6-883e-42932eceeeda\scratch\bab5_sec.js`
- **Ketergantungan**: Tugas 1.
- **Kriteria Verifikasi**:
  - Pemeriksaan pola teks (*regex scan*) pada `bab4_sec_423_426.js` dan `bab5_sec.js` memastikan **0 kemunculan** identifier kode teknis (`PostSale`, `InventoryLedger`, `PostGoodsReceipt`, `stock.adjust`, `initial_payment_rupiah`, `total_rupiah`, `balance_rupiah`, `due_on`, `minimum_quantity`, `conversion_to_base`, `lockForUpdate`, dll.).

---

## [x] Tugas 6: Kompilasi Ulang Dokumen Word (`.docx`) & Verifikasi Akhir Menyeluruh
- **Tujuan**: Menjalankan kompilator `compile_bab4.js` untuk menghasilkan dokumen Markdown dan Word terbaru, lalu memverifikasi struktur XML `.docx` dan kualitas visual dokumen akhir.
- **File yang Dihasilkan**:
  - `d:\Herd\nf-harapan-rumbai-web\KP\BAB IV HASIL DAN PEMBAHASAN.md`
  - `d:\Herd\nf-harapan-rumbai-web\KP\BAB V PENUTUP.md`
  - `d:\Herd\nf-harapan-rumbai-web\KP\LAPORAN_KERJA_PRAKTEK_NORAWRR_BAB_IV.docx`
  - `d:\Herd\nf-harapan-rumbai-web\KP\LAPORAN_KERJA_PRAKTEK_NORAWRR_BAB_V.docx`
- **Ketergantungan**: Tugas 1, 2, 3, 4, dan 5.
- **Kriteria Verifikasi**:
  - Audit XML `word/document.xml` pada kedua file `.docx` membuktikan **0 karakter `**` atau `` ` `` yang bocor**.
  - Seluruh 17 diagram monokrom (`Gambar 4.1`–`Gambar 4.17`) dan 18 tangkapan layar (`Gambar 4.18`–`Gambar 4.35`) tersemat secara utuh.
