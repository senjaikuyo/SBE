# Progress Implementasi GreenGrid APAC

Status per 31 Oktober 2026. Ditulis secara objektif berdasarkan verifikasi kode dan pengujian otomatis (Pest).

## Ringkasan Status FR

| Fitur | Status | Bukti Tes | Sisa Pekerjaan / Catatan |
| --- | --- | --- | --- |
| FR-01 Akun, anggota, peran, MFA | Selesai (Done) | `AuthTest`, `CompanyOnboardingTest`, `MembershipTest`, `TwoFactorTest` (79 passed) | Seluruh AC FR-01 terpenuhi. |
| FR-02 KYC / AML dasar | Selesai (Done) | `KycTest` (9 passed, 53 assertions) | Dokumen dinamis per negara, screening sanksi, approval/rejection admin, dan create-admin. |
| FR-03 Listing penjual | Partial | `ListingTest` (4 passed, 17 assertions) | **Partial:** Pembuatan draft dan pengecekan otorisasi `company_role: seller` + role `company_admin`/`procurement` sudah selesai. **Belum selesai:** Edit/hapus berversi (optimistic locking `version`), penyimpanan foto asli ke storage privat/S3, serta pemisahan form energi dan karbon di UI. |
| FR-04 Pencarian dan filter | Selesai (Done) | `MarketplaceTest` (9 passed, 41 assertions) | Filter jenis aset, negara, kapasitas minimum MW, harga maksimum, dan durasi sudah aktif. P95 < 1 detik dicatat sebagai TIDAK DIVERIFIKASI. |
| FR-05 Admin panel | Selesai (Done) | `AdminPanelTest` (11 passed, 54 assertions) | Overview metrik, antrean KYC dengan SLA 72 jam, moderasi listing & sertifikat, tabel `settings`, dan four-eyes approval request dengan deteksi conflict of interest. |
| FR-06 Notifikasi email | Selesai (Done) | `NotificationTest` (3 passed, 8 assertions) | Email terantre untuk peristiwa KYC diterima, KYC disetujui/ditolak, undangan anggota, listing disetujui, dan listing ditolak dengan alasan. Tabel `notifications` tersedia. |
| FR-11 Verifikasi sertifikat (manual) | Selesai (Done) | `CertificateVerificationTest` (6 passed, 59 assertions) | Antrean verifikasi platform admin, nomor seri unik ternormalisasi per registri, proteksi status ACTIVE (listing tidak bisa aktif tanpa sertifikat valid). |
| FR-12 Log audit | Belum | `AuditLogTest` | Tabel `audit_logs` dan `AuditLogger` (hash berantai SHA-256) sudah aktif mencatat aksi FR-01 s/d FR-06 & FR-11, namun butir AC FR-12 belum dibandingkan/diaudit secara menyeluruh terhadap spesifikasi PRD. |
| Legal two-step (D-xx) | Belum | Belum ada | Menunggu keputusan legal D-01, D-05, dan D-10. |

Total suite pengujian: **115 passed, 403 assertions, 0 failed**.

## Temuan Pemeriksaan Keamanan (Item 1)

1. **File Sensitif (.env):** `git ls-files | grep -i "^\.env"` hanya menampilkan `.env.example`. File `.env` tidak pernah ter-commit.
2. **Integritas Status Listing:** Pemeriksaan kode memastikan status `ACTIVE` hanya diset melalui alur verifikasi FR-11 (`AdminCertificateController` dan `Listing::activate()`). Tidak ada jalur bypass langsung ke status `ACTIVE`.
3. **Proteksi Route Admin:**
   - Seluruh rute fungsional admin (`/admin`, `/admin/kyc`, `/admin/listings`, `/admin/certificates`, `/admin/settings`, `/admin/approvals`) terproteksi penuh oleh middleware `['auth', 'can:manage,App\Models\User']`.
   - Rute pratinjau statis `/admin/kyc/{id}` dan `/admin/listings/{id}` belum diberi middleware admin karena masih berfungsi sebagai mockup pratinjau.
4. **Estimasi Waktu KYC:** `KycSubmittedMail` telah memuat estimasi keputusan 48 jam (`decisionEstimateHours => 48`) sesuai kriteria penerimaan FR-02 butir 2.

## Rincian Status per FR

### FR-01: Akun dan Onboarding (Done)
- Pendaftaran akun dengan password minimal 12 karakter (`config/greengrid.php`).
- Alur wajib: Daftar -> Buat Perusahaan -> Setup 2FA TOTP -> Dashboard.
- Manajemen anggota (undang, ubah peran, hapus, token kedaluwarsa 7 hari).
- Idle session timeout 30 menit dan auto-save draft form.

### FR-02: KYC / AML Dasar (Done)
- Submit KYC dengan dokumen dinamis per negara (`config/greengrid.php`) dan dokumen bersama.
- Deteksi kedaluwarsa dokumen otomatis (auto-reject).
- Resubmit hanya dari status `REJECTED`.
- Antrean admin untuk approve/reject dengan alasan wajib.
- Screening sanksi otomatis (`SanctionsScreener` stub) dan command berkala `kyc:screening`.

### FR-03: Listing Penjual (Partial)
- Tabel `listings` mendukung tipe energi dan kredit karbon dengan kolom spesifik.
- Otorisasi `ListingPolicy`: hanya perusahaan KYC APPROVED dengan `company_role: seller` dan peran anggota `company_admin` atau `procurement` yang dapat membuat listing (pembeli ditolak 403).
- **Belum selesai:**
  1. Fitur edit dan hapus dengan optimistic locking (`version`).
  2. Penyimpanan berkas foto asli ke storage privat/S3.
  3. Pemisahan form UI mandiri untuk energi vs kredit karbon.

### FR-04: Pencarian dan Marketplace (Done)
- Halaman `/listings` hanya menampilkan listing berstatus `ACTIVE`.
- Filter aktif: jenis aset (`energy`, `carbon_credit`), negara/lokasi, kapasitas minimum MW (kredit karbon tidak difilter MW), harga maksimum (per kWh untuk energi, per ton untuk karbon), dan durasi minimum tahun.
- Empty state informatif dengan tombol simpan pencarian dinonaktifkan (keterangan Fase 2).
- Detail listing `/listings/{id}` menampilkan atribut lengkap per jenis aset.

### FR-05: Admin Panel (Done)
- Dashboard overview `/admin` dengan metrik antrean operasional.
- Antrean KYC `/admin/kyc` menghitung sisa waktu keputusan dari target SLA 72 jam (`OVERDUE`, `DUE_SOON`).
- Moderasi listing dan sertifikat terintegrasi.
- Tabel `settings` menyimpan konfigurasi platform (`marketplace_fee_percentage`), nilai awal kosong, tidak dipakai di Fase 1 (D-08).
- Mekanisme four-eyes approval (`approval_requests`): aksi berdampak besar mewajibkan dua admin berbeda dan memblokir admin yang memiliki benturan kepentingan (conflict of interest) di perusahaan terkait.
- Seluruh aksi admin mewajibkan alasan dan tercatat di `audit_logs`.

### FR-06: Notifikasi Email (Done)
- Email terantre (`ShouldQueue`) untuk peristiwa: KYC diterima, KYC disetujui, KYC ditolak (dengan alasan), undangan anggota, listing disetujui, dan listing ditolak (dengan alasan).
- Penerima notifikasi terisolasi hanya untuk anggota perusahaan terkait.
- Kegagalan pengiriman email tidak menggagalkan aksi bisnis utama.

### FR-11: Verifikasi Sertifikat Manual (Done)
- Antrean verifikasi sertifikat kredit karbon oleh platform admin.
- Normalisasi dan validasi keunikan nomor seri per registri (`VERRA`, `GOLD_STANDARD`).
- Listing tidak dapat berstatus `ACTIVE` sebelum sertifikat diverifikasi menjadi `VALID`.
