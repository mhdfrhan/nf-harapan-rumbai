# Roadmap

## Strategi Pelaksanaan

Proyek mengikuti SDLC Waterfall sebagaimana arah laporan KP: analisis kebutuhan, perancangan, implementasi, pengujian, deployment, dan serah terima. Setiap fase memiliki gate persetujuan agar perubahan kebutuhan tidak masuk diam-diam ketika kode sudah bergantung pada keputusan sebelumnya.

Waterfall tidak berarti pengujian hanya dilakukan di akhir. Test design dimulai bersama kebutuhan, automated test dibuat bersama implementasi, dan UAT formal dilakukan setelah build stabil.

## Prinsip Prioritas

1. Integritas transaksi dan stok lebih dahulu daripada visual tambahan.
2. Alur kerja inti lebih dahulu daripada laporan agregat.
3. Keamanan dan backup dibangun sejak awal, bukan ditambahkan setelah selesai.
4. Fitur `Must` selesai end-to-end sebelum `Should`.
5. Fitur baru tidak menambah scope tanpa change request dan dampak yang jelas.

## Fase 0: Persetujuan Perencanaan

**Tujuan:** Menetapkan baseline sebelum coding.

**Aktivitas:**

- Meninjau seluruh folder `docs`.
- Menjawab semua `OPEN` berlabel `BLOCKER`.
- Menyetujui MVP, persona, role, dan permission.
- Menyetujui unit conversion, harga grosir, negative stock, diskon, serta void.
- Menyetujui topology deployment dan perangkat.
- Mengumpulkan sampel Excel, bukti, nota, serta data katalog.
- Menyetujui tema cream dan hijau tua melalui wireframe low fidelity setelah planning diterima.

**Deliverable:** Requirements baseline v1, decision log, sample data inventory, serta persetujuan implementasi.

**Exit gate:** Tidak ada blocker yang belum diputuskan.

## Fase 1: Analisis dan Validasi Lapangan

**Tujuan:** Mengubah asumsi menjadi kebutuhan terukur.

**Aktivitas:**

- Observasi satu siklus penjualan dan penerimaan barang.
- Memeriksa bentuk Excel, nota supplier, serta pola bukti WhatsApp atau galeri.
- Inventaris perangkat, printer, barcode scanner, koneksi, dan listrik.
- Mengukur volume produk, transaksi harian, file, serta user bersamaan.
- Memvalidasi istilah UI dengan pemilik dan karyawan.
- Menyelesaikan acceptance criteria dan black box scenario.

**Deliverable:** Requirement specification final, data profile, device survey, dan UAT draft.

**Exit gate:** Pemilik menyetujui alur normal serta pengecualian.

## Fase 2: Perancangan Sistem dan UX

**Tujuan:** Menghasilkan rancangan implementable tanpa ambiguity utama.

### Arsitektur

- Inisialisasi Laravel 13 Livewire starter kit pada branch implementasi setelah izin.
- Finalisasi module boundary dan directory structure.
- Finalisasi ERD, constraint, state machine, dan transaction boundary.
- Finalisasi deployment diagram, trust boundary, dan threat model.
- Membuat migration plan serta data dictionary.

### UX

- Low fidelity wireframe untuk login, dashboard, POS, receipt, payable, stock, dan report.
- Clickable prototype untuk alur penjualan dan barang masuk.
- Uji cepat dengan pemilik serta satu karyawan.
- Finalisasi token cream, hijau tua, tipografi, density, table, form, dialog, dan state.
- Accessibility review awal.

**Deliverable:** ERD, architecture decision record, wireframe, UI specification, prototype review, dan test plan final.

**Exit gate:** Tidak ada layar atau tabel kritis tanpa sumber data, izin, state, dan acceptance test.

## Fase 3: Fondasi Aplikasi

**Tujuan:** Menyediakan kerangka production-ready untuk semua modul.

**Work package:**

- Project scaffold, environment sample, coding standard, lint, CI.
- Authentication tanpa public registration.
- Role, permission, policy, dan active-user middleware.
- App shell, navigation, theme token, form primitive, table primitive, feedback, serta error pages.
- Request ID, structured logging, audit infrastructure.
- Private document storage foundation.
- Queue, scheduler, health check, dan base monitoring.
- Database baseline serta factory.

**Exit gate:** Auth matrix lulus, theme shell responsive, CI hijau, dan tidak ada secret dalam repository.

## Fase 4: Catalog dan Persediaan Dasar

**Tujuan:** Menetapkan sumber kebenaran barang sebelum transaksi.

**Work package:**

- Category, unit, product, product unit, conversion, retail dan wholesale price.
- Daftar, filter, form, active status, serta price history.
- Stock movement ledger dan stock balance.
- Opening inventory dan reconciliation command.
- Low-stock threshold.
- Import template serta staging validation jika prioritas disetujui.

**Exit gate:** Product pecahan 0,25 kg dan 0,5 kg lulus test, master data historis aman, serta balance dapat dibangun ulang dari ledger.

## Fase 5: POS dan Penjualan

**Tujuan:** Menyelesaikan alur pendapatan utama.

**Work package:**

- Product search, barcode keyboard flow, cart, unit selection, price selection.
- Server-side calculation, payment, change, and receipt.
- Atomic posting, lock, idempotency, dan concurrency handling.
- History, detail, print, void, reversal movement, dan audit.
- POS loading, empty, error, offline, timeout, dan session expired state.

**Exit gate:** Seluruh `TEST-SALES`, browser critical path, serta UAT penjualan lulus pada perangkat target.

## Fase 6: Penerimaan, Pemasok, dan Kewajiban

**Tujuan:** Menggantikan pencatatan barang masuk, kewajiban, dan bukti yang tersebar.

**Work package:**

- Supplier master.
- Goods receipt draft, review, posted state, item snapshot, dan stock movement.
- Payment state lunas, sebagian, atau belum dibayar.
- Payable, due status, partial payment, payment reversal.
- Private proof upload, preview, download, checksum, dan authorization.
- Reminder job dan due dashboard.

**Exit gate:** Receipt, stock, payable, payment, proof, idempotency, dan permission test lulus end-to-end.

## Fase 7: Dashboard, Laporan, dan Audit

**Tujuan:** Memberi pemilik informasi yang dapat ditelusuri.

**Work package:**

- Dashboard sesuai peran.
- Laporan penjualan, item, stok, movement, receipt, payable, serta control activity.
- Filter, drill-down, CSV, print atau PDF.
- Audit viewer dengan redaction.
- Reconciliation dashboard.
- Performance tuning query serta queue export.

**Exit gate:** Fixture manual cocok dengan report, export sama dengan layar, permission lulus, dan performance budget tercapai.

## Fase 8: Migrasi dan System Testing

**Tujuan:** Menyiapkan data nyata dan menguji keseluruhan sistem.

**Work package:**

- Data profiling dan cleansing.
- Staging import dry run.
- Opening stock dan opening payable rehearsal.
- Full regression, security verification, performance test, accessibility review, browser matrix.
- Backup serta restore rehearsal.
- Incident and offline procedure rehearsal.
- Bug triage dengan severity.

**Exit gate:** Tidak ada critical atau high defect, rekonsiliasi lulus, dan restore terbukti.

## Fase 9: UAT, Pelatihan, dan Cutover

**Tujuan:** Memindahkan proses kerja dengan risiko terkontrol.

**Work package:**

- UAT bersama pemilik dan karyawan.
- Perbaikan serta retest.
- Pelatihan berdasarkan peran dengan data latihan.
- Panduan singkat bergambar dan SOP.
- Freeze Excel, physical count, production import, reconciliation.
- Smoke test dan persetujuan go-live.
- Hypercare setelah go-live.

**Exit gate:** UAT ditandatangani, system of record baru diumumkan, support owner jelas, dan known limitations diterima.

## Fase 10: Serah Terima dan Evaluasi KP

**Tujuan:** Menutup pekerjaan secara dapat dipelihara.

**Deliverable:**

- Source code dan release tag.
- Dokumen instalasi dan operasi.
- Data dictionary, ERD, dan arsitektur final.
- Test report, UAT, security checklist, backup restore report.
- User manual serta training material.
- Migration report dan reconciliation sign-off.
- Daftar limitasi, backlog, dan rekomendasi pengembangan.
- Bahan evaluasi untuk Bab IV dan kesimpulan laporan KP.

## Estimasi Relatif

Estimasi berikut hanya untuk capacity planning satu pengembang utama. Durasi kalender final menunggu scope, data, perangkat, dan jadwal akademik.

| Fase | Estimasi usaha relatif | Risiko utama |
|---|---:|---|
| Fase 0 sampai 2 | 15 sampai 20 persen | Kebutuhan belum jelas. |
| Fase 3 | 10 persen | Fondasi dilewati karena mengejar UI. |
| Fase 4 | 10 sampai 15 persen | Unit conversion dan data kotor. |
| Fase 5 | 15 sampai 20 persen | Concurrency dan usability POS. |
| Fase 6 | 15 sampai 20 persen | Bukti, saldo, dan koreksi. |
| Fase 7 | 10 sampai 15 persen | Definisi laporan dan performa. |
| Fase 8 sampai 10 | 20 sampai 25 persen | Migrasi, UAT, deployment, support. |

Persentase overlap sebagai rentang risiko dan tidak harus dijumlahkan tepat 100 sebelum estimasi rinci.

## Milestone

| Milestone | Bukti selesai |
|---|---|
| M0 Planning approved | Open questions blocker dijawab. |
| M1 Design approved | ERD, wireframe, threat model, dan test plan disetujui. |
| M2 Foundation ready | Auth, permission, UI shell, CI, log, storage siap. |
| M3 Inventory ready | Catalog, conversion, ledger, opening balance lulus. |
| M4 POS ready | Penjualan dan void lulus concurrency serta UAT awal. |
| M5 Procurement ready | Receipt, payable, proof, dan payment lulus. |
| M6 Reporting ready | Dashboard, report, audit, export cocok dengan fixture. |
| M7 Release candidate | Full test, migration rehearsal, restore lulus. |
| M8 Go-live | UAT dan cutover sign-off. |
| M9 Handover | Dokumen serta support ownership lengkap. |

## Definition of Done Fitur

- Requirement dan business rule ditautkan.
- Policy dan data scope diimplementasikan.
- Validation serta error copy tersedia.
- Loading, empty, success, error, offline, dan conflict state tersedia bila relevan.
- Automated test happy, invalid, unauthorized, dan integrity path lulus.
- Accessibility dan responsive check lulus.
- Audit dan observability ditambahkan bila sensitif.
- Dokumentasi pengguna dan teknis diperbarui.
- Tidak ada dependency atau security warning tanpa keputusan.
- Reviewer menyetujui dan CI hijau.

## Change Control

Permintaan baru setelah baseline mencatat:

- Masalah yang ingin diselesaikan.
- Pengguna dan frekuensi.
- Acceptance criteria.
- Dampak ke database, security, UI, migration, test, deployment, laporan, dan jadwal.
- Prioritas MoSCoW.
- Fitur yang ditukar bila waktu tetap.
- Keputusan pemilik dan tanggal.

## Backlog Setelah MVP

- Retur penjualan dan retur pemasok.
- Multi gudang atau cabang.
- Customer credit.
- Purchase order formal.
- Barcode camera.
- WhatsApp Business notification resmi.
- Mobile app atau PWA offline.
- Akuntansi dan metode HPP.
- Forecast stok.
- Approval workflow bertingkat.

Fitur tersebut tidak boleh disisipkan ke MVP hanya karena struktur database tampak mampu mendukungnya.

