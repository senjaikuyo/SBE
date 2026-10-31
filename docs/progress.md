# Progress Implementasi GreenGrid APAC

Status per 31 Oktober 2026. Ditulis secara objektif berdasarkan verifikasi kode dan pengujian otomatis (Pest).

## Ringkasan Status FR

| Fitur | Status | Bukti Tes | Sisa Pekerjaan / Catatan |
| --- | --- | --- | --- |
| FR-01 Akun, anggota, peran, MFA | Selesai | `AuthTest`, `CompanyOnboardingTest`, `MembershipTest`, `TwoFactorTest` (79 passed) | Tidak ada. Sudah memenuhi seluruh AC FR-01. |
| FR-02 KYC / AML dasar | Selesai | `KycTest` (9 passed, 53 assertions) | Tidak ada. Dokumen dinamis per negara, screening sanksi, approval/rejection admin, dan create-admin. |
| FR-03 Listing penjual | Partial | `ListingTest` (3 passed, 11 assertions) | **Partial:** Form draft energi & kredit karbon sudah ada, tetapi edit/hapus berversi (optimistic locking), penyimpanan foto asli ke disk S3/privat, dan validasi ketat peran seller belum diverifikasi penuh. |
| FR-04 Pencarian dan filter | Selesai | `MarketplaceTest` (9 passed, 41 assertions) | Filter jenis aset, negara, kapasitas minimum, harga maksimum, dan durasi sudah jalan. P95 < 1 detik dicatat sebagai TIDAK DIVERIFIKASI. |
| FR-05 Admin panel | Selesai | `AdminPanelTest` (11 passed, 54 assertions) | Overview metrik, antrean KYC dengan SLA 72 jam, moderasi listing & sertifikat, tabel `settings`, dan four-eyes approval request dengan deteksi conflict of interest. |
| FR-06 Notifikasi email | Selesai | `NotificationTest` (3 passed, 8 assertions) | Mail queued untuk KYC diterima, KYC disetujui/ditolak, undangan anggota, listing disetujui, dan listing ditolak dengan alasan. Tabel `notifications` tersedia. |
| FR-11 Verifikasi sertifikat (manual) | Selesai | `CertificateVerificationTest` (6 passed, 59 assertions) | Antrean verifikasi platform admin, nomor seri ternormalisasi & unik per registri, proteksi status ACTIVE (listing tidak bisa aktif tanpa sertifikat valid). |
| FR-12 Log audit | Belum | `AuditLogTest` | Tabel `audit_logs` dan `AuditLogger` (hash berantai SHA-256) sudah aktif mencatat aksi FR-01 s/d FR-06 & FR-11, namun butir AC FR-12 belum dibandingkan/diaudit secara menyeluruh. |
| Legal two-step (D-xx) | Belum | Belum ada | Belum dikerjakan. Menunggu keputusan legal D-01/D-05/D-10. |

Total suite pengujian: **114 passed, 397 assertions, 0 failed**.

## Rincian Pekerjaan

### FR-01: Akun dan Onboarding
- Pendaftaran akun dengan password minimal 12 karakter (`config/greengrid.php`).
- Alur wajib: Daftar -> Buat Perusahaan -> Setup 2FA TOTP -> Dashboard.
- Manajemen anggota (undang, ubah peran, hapus, token kedaluwarsa 7 hari).
- Idle session timeout 30 menit dan auto-save draft form.

### FR-02: KYC / AML Dasar
- Submit KYC dengan dokumen dinamis per negara (`config/greengrid.php`) dan dokumen bersama.
- Deteksi kedaluwarsa dokumen otomatis (auto-reject).
- Resubmit hanya dari status `REJECTED`.
- Antrean admin untuk approve/reject dengan alasan wajib.
- Screening sanksi otomatis (`SanctionsScreener` stub) dan command berkala `kyc:screening`.

### FR-03: Listing Penjual (Status: Partial)
- Tabel `listings` mendukung tipe energi dan kredit karbon dengan kolom spesifik.
- Input divalidasi per tipe aset.
- **Sisa pekerjaan:** Fitur edit dan hapus dengan optimistic locking (`version`), penyimpanan berkas foto ke storage privat, dan penegakan peran seller di tingkat controller/policy.

### FR-04: Pencarian dan Marketplace
- Halaman `/listings` hanya menampilkan listing berstatus `ACTIVE`.
- Filter aktif: jenis aset (`energy`, `carbon_credit`), negara/lokasi, kapasitas minimum MW (kredit karbon tidak difilter MW), harga maksimum (per kWh untuk energi, per ton untuk karbon), dan durasi minimum tahun.
- Empty state informatif dengan tombol simpan pencarian dinonaktifkan (keterangan Fase 2).
- Detail listing `/listings/{id}` menampilkan atribut lengkap per jenis aset.

### FR-05: Admin Panel
- Dashboard overview `/admin` dengan metrik antrean operasional.
- Antrean KYC `/admin/kyc` menghitung sisa waktu keputusan dari target SLA 72 jam (`OVERDUE`, `DUE_SOON`).
- Moderasi listing dan sertifikat terintegrasi.
- Tabel `settings` menyimpan konfigurasi platform (`marketplace_fee_percentage`), nilai awal kosong, tidak dipakai di Fase 1 (D-08).
- Mekanisme four-eyes approval (`approval_requests`): aksi berdampak besar mewajibkan dua admin berbeda dan memblokir admin yang memiliki benturan kepentingan (conflict of interest) di perusahaan terkait.
- Seluruh aksi admin mewajibkan alasan dan tercatat di `audit_logs`.

### FR-06: Notifikasi Email
- Email terantre (`ShouldQueue`) untuk peristiwa: KYC diterima, KYC disetujui, KYC ditolak (dengan alasan), undangan anggota, listing disetujui, dan listing ditolak (dengan alasan).
- Penerima notifikasi terisolasi hanya untuk anggota perusahaan terkait.
- Kegagalan pengiriman email tidak menggagalkan aksi bisnis utama.

### FR-11: Verifikasi Sertifikat Manual
- Antrean verifikasi sertifikat kredit karbon oleh platform admin.
- Normalisasi dan validasi keunikan nomor seri per registri (`VERRA`, `GOLD_STANDARD`).
- Listing tidak dapat berstatus `ACTIVE` sebelum sertifikat diverifikasi menjadi `VALID`.

## Sisa Pekerjaan & Rencana Berikutnya

1. **Selesaikan sisa FR-03:** Implementasi edit/hapus listing dengan optimistic locking (`version`), upload foto privat, dan verifikasi peran seller.
2. **Audit & Verifikasi FR-12:** Evaluasi kepatuhan tabel dan model `audit_logs` terhadap seluruh butir AC FR-12.
3. **Legal Two-Step & Keputusan D-xx:** Analisis ketergantungan keputusan hukum untuk persiapan Fase 2.
