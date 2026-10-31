# Spesifikasi Fase 1: GreenGrid APAC

Sumber: PRD v1.2. Dokumen ini hanya memuat apa yang dibangun di Fase 1. Tanda `[D-xx]` berarti keputusan belum final (lihat `decisions.md`).

Keluaran Fase 1: registrasi, KYC, listing, pencarian, admin panel, email, log audit. Syarat selesai: 5 penjual dan 3 pembeli terverifikasi di lingkungan pilot.

## Cakupan

| ID | Kebutuhan | Prioritas |
| --- | --- | --- |
| FR-01 | Akun, anggota perusahaan, peran, MFA | P0 |
| FR-02 | KYC/AML | P0 |
| FR-03 | Listing (energi dan kredit karbon) | P0 |
| FR-04 | Pencarian dan filter | P0 |
| FR-05 | Admin panel | P0 |
| FR-06 | Notifikasi email (hanya KYC dan listing) | P0 |
| FR-11 | Verifikasi sertifikat, manual oleh admin | P0 |
| FR-12 | Log audit | P0 |

Urutan pengerjaan: FR-01, FR-12, FR-02, FR-05, FR-03, FR-11, FR-04, FR-06.

## Peran

Satu perusahaan punya banyak pengguna lewat `memberships`. Peran perusahaan: `company_admin`, `procurement`, `finance`, `legal`, `sustainability`, `viewer`. Peran platform: `platform_admin`.

| Aksi | company_admin | procurement | finance | legal | sustainability | viewer |
| --- | --- | --- | --- | --- | --- | --- |
| Kelola anggota | Ya | Tidak | Tidak | Tidak | Tidak | Tidak |
| Buat atau ubah listing | Ya | Ya | Tidak | Tidak | Tidak | Tidak |
| Unggah dokumen KYC | Ya | Tidak | Tidak | Ya | Tidak | Tidak |
| Lihat dashboard | Ya | Ya | Ya | Ya | Ya | Ya |

Aturan:

- Peran buyer atau seller (`company_role`) ditentukan server dari hasil KYC. Pengguna tidak memilihnya.
- Satu perusahaan boleh memegang dua peran hanya bila lulus verifikasi untuk keduanya.
- Pemulihan bila company_admin tunggal tidak dapat diakses: platform_admin memverifikasi ulang dokumen perusahaan.

## FR-01 Akun, anggota, peran

Kriteria penerimaan:

1. Registrasi dengan email dan password. Email unik. Password minimal 12 karakter.
2. 2FA TOTP wajib untuk semua pengguna sebelum mengakses fitur selain profil.
3. Pengguna pertama sebuah perusahaan menjadi `company_admin`.
4. `company_admin` mengundang anggota lewat email dan menetapkan peran. Undangan kedaluwarsa dalam 7 hari.
5. Sesi berakhir setelah 30 menit tanpa aktivitas. Draft form tersimpan dan pulih setelah login.
6. Semua aksi login, ubah peran, undang, dan hapus anggota masuk `audit_logs`.

## FR-02 KYC/AML

Dokumen per negara:

| Negara | Dokumen perusahaan |
| --- | --- |
| Indonesia | NIB, NPWP, akta pendirian dan pengesahannya |
| Singapura | Profil perusahaan ACRA |
| Malaysia | Dokumen SSM |
| Vietnam | Sertifikat registrasi perusahaan `[D-04]` |
| Laos | Sertifikat registrasi perusahaan `[D-04]` |

Semua negara juga mengirim: identitas direktur, pemilik manfaat akhir, surat penunjukan penandatangan, dan bukti rekening bank perusahaan.

Daftar dokumen per negara disimpan di `config/greengrid.php`, bukan di kode. Vietnam dan Laos memakai daftar sementara di atas sampai D-04 selesai.

Kriteria penerimaan:

1. Form menerima dokumen sesuai negara perusahaan. Dokumen kedaluwarsa ditolak otomatis dengan alasan.
2. Setelah submit, `kyc_status` menjadi `PENDING_VERIFICATION` dan email konfirmasi terkirim dalam 5 menit. Email menyebut estimasi keputusan 48 jam.
3. Screening sanksi lewat antarmuka `SanctionsScreener`. Fase 1 memakai implementasi stub yang mengembalikan `clear`, vendor menyusul. Screening diulang tiap 90 hari untuk perusahaan aktif (job terjadwal).
4. `platform_admin` menyetujui atau menolak. Penolakan wajib memuat alasan. Pengguna dapat mengirim ulang dokumen.
5. Pemeriksaan pemilik manfaat akhir dicatat. Perusahaan yang gagal tidak dapat transaksi sampai admin menyetujui ulang.
6. Median waktu keputusan 48 jam, 90% selesai dalam 72 jam. Antrian admin menampilkan sisa waktu dan menandai yang melewati 72 jam.
7. `kyc_status`: `NOT_SUBMITTED`, `PENDING_VERIFICATION`, `APPROVED`, `REJECTED`.

## FR-03 Listing

Listing energi:

- `capacity_mw` (kapasitas terpasang), `volume_available_mw`, `price_per_kwh` (USD, tarif tetap, eskalasi tahunan 0%), `duration_years`, `start_date`, lokasi, izin proyek, foto.
- Proyek tanpa bukti meter atau data produksi untuk proyek berjalan ditandai `pre_operational`.

Listing kredit karbon:

- `quantity_tco2e`, `vintage`, `registry_standard` (`VERRA` atau `GOLD_STANDARD`), `serial_number`, `price_per_ton` (USD).

Status LISTING: `DRAFT`, `PENDING_REVIEW`, `ACTIVE`, `REJECTED`, `INACTIVE`, `SOLD_OUT`, `EXPIRED`.

Transisi:

- `DRAFT` ke `PENDING_REVIEW`: penjual.
- `PENDING_REVIEW` ke `ACTIVE` atau `REJECTED`: `platform_admin`. Penolakan memuat alasan.
- `REJECTED` ke `DRAFT`: penjual.
- `ACTIVE` ke `SOLD_OUT`: otomatis bila volume tersedia nol (dipakai mulai Fase 2).
- `ACTIVE` ke `EXPIRED`: di tanggal kedaluwarsa yang diisi penjual.

Kriteria penerimaan:

1. Hanya perusahaan dengan `kyc_status = APPROVED` dan `company_role` seller yang dapat membuat listing.
2. Semua field wajib terisi. Pesan error menyebut field yang salah.
3. Formulir energi dan kredit karbon terpisah. Kredit karbon tidak memakai field MW.
4. Penjual mengedit atau menghapus listing hanya bila tidak ada request terbuka (di Fase 1 selalu benar). Tiap perubahan membuat versi baru (`version` naik, riwayat disimpan).
5. Listing tidak dapat `ACTIVE` sebelum nomor seri sertifikat diverifikasi admin (FR-11).

## FR-11 Verifikasi sertifikat (manual)

Status CERTIFICATE: `VALID`, `TRANSFERRED`, `RETIRED`, `REJECTED`. Fase 1 hanya memakai `VALID` dan `REJECTED`.

Kriteria penerimaan:

1. Admin memeriksa nomor seri di registri secara manual (di luar aplikasi), lalu menandai `VALID` atau `REJECTED` dengan catatan.
2. Nomor seri dinormalisasi (hapus spasi, huruf besar) sebelum disimpan dan dicek.
3. Nomor seri yang sama tidak boleh dipakai di dua listing (constraint unik pada nomor seri ternormalisasi plus registri).
4. Listing tidak dapat `ACTIVE` bila sertifikatnya bukan `VALID`.

## FR-04 Pencarian

Kriteria penerimaan:

1. Filter: kapasitas, lokasi (negara), harga, durasi, jenis aset (energi atau kredit karbon). Hanya listing `ACTIVE`.
2. Pencarian tanpa hasil menampilkan opsi simpan pencarian dan terima notifikasi bila ada listing baru yang cocok. Penyimpanan pencarian dan notifikasinya `[tunda ke Fase 2]`, tampilkan tombol nonaktif dengan keterangan.
3. Indikator memuat di daftar. Tombol aksi nonaktif selama proses.
4. p95 pencarian di bawah 1 detik pada data pilot.

## FR-05 Admin panel

Kriteria penerimaan:

1. Antrian KYC dengan sisa waktu keputusan.
2. Moderasi listing dan sertifikat (FR-03, FR-11).
3. Pengaturan fee disimpan di tabel pengaturan dan tidak dipakai di Fase 1 `[D-08]`. Nilai awal kosong.
4. Pelacakan sengketa `[tunda ke Fase 2]`.
5. Aksi berdampak besar (ubah pengaturan fee, setujui ulang perusahaan yang gagal pemeriksaan pemilik manfaat) memakai dua admin: satu mengusulkan, satu menyetujui. Alasan wajib. Admin tidak dapat memproses perusahaan tempat ia berkepentingan.
6. Semua aksi admin masuk `audit_logs` dengan alasan.

## FR-06 Notifikasi email (Fase 1)

Hanya peristiwa: KYC diterima, KYC disetujui, KYC ditolak (dengan alasan), listing disetujui, listing ditolak (dengan alasan), undangan anggota.

Kriteria penerimaan:

1. Email dikirim lewat queue. Kegagalan kirim di-retry dengan backoff dan dicatat di `notifications`.
2. Kegagalan layanan email tidak menggagalkan aksi utama.

## FR-12 Log audit

Kriteria penerimaan:

1. Tabel `audit_logs`: `actor_id`, `action`, `entity`, `entity_id`, `reason`, `hash_prev`, `hash`, `created_at`.
2. `hash` = SHA-256 dari isi baris ditambah `hash_prev`. Tidak ada update atau delete, dijaga di level model dan izin database.
3. Perintah `php artisan audit:verify` memeriksa rantai hash dan melaporkan baris yang rusak.
4. Dicatat: login, ubah peran, aksi KYC, aksi listing, aksi sertifikat, aksi admin.

## Model data Fase 1

| Tabel | Kolom utama | Catatan |
| --- | --- | --- |
| `companies` | id, name, country, registration_number, kyc_status, company_role, version | Satu perusahaan, banyak pengguna |
| `users` | id, email, password, two_factor_secret | Fortify |
| `memberships` | user_id, company_id, role, approval_limit | `approval_limit` dipakai Fase 2 |
| `kyc_documents` | company_id, type, file_path, expires_at, status | File di disk privat |
| `listings` | company_id, type, capacity_mw, volume_available_mw, price (decimal 12,6), price_unit, duration_years, start_date, country, status, version, expires_at | Energi dan karbon dalam satu tabel dengan kolom nullable per jenis, atau dua tabel bila lebih bersih |
| `listing_versions` | listing_id, version, snapshot (json), created_at | Riwayat perubahan |
| `certificates` | listing_id, type, registry, serial_normalized, status, reviewed_by | Unik (registry, serial_normalized) |
| `notifications` | user_id, channel, event, status, sent_at | |
| `audit_logs` | lihat FR-12 | Append-only |

Tabel berikut TIDAK dibuat di Fase 1: requests, offers, contracts, contract_parties, escrows, payments, releases, ledger_entries, meter_readings, disputes, bank_accounts.

## Kebutuhan non-fungsional Fase 1

- p95 halaman utama di bawah 2 detik pada 4G.
- Responsif di mobile. Browser: Chrome, Edge, Safari, Firefox dua versi terbaru.
- Aksesibilitas WCAG 2.1 AA untuk registrasi, KYC, dan listing.
- Antarmuka bahasa Inggris. Format angka dan tanggal mengikuti locale pengguna. Tenggat menyebut zona waktu.
- Skala: 50 pengguna aktif. Tidak perlu optimasi di luar itu.

## Definition of Done per FR

1. Semua kriteria penerimaan lolos tes Pest (normal, gagal, batas).
2. Policy diterapkan di setiap aksi dan diuji untuk peran yang tidak berhak.
3. Aksi penting tercatat di `audit_logs`.
4. Tidak ada nilai bisnis yang ditulis keras di kode.
5. `docs/progress.md` diperbarui.
