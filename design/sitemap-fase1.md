# Sitemap dan Daftar Layar — Fase 1

Turunan langsung dari `docs/prd-fase1.md`, ditambah keputusan pemilik proyek tanggal 31 Oktober 2026 (bagian 0).
Tidak ada layar untuk fitur di luar Fase 1.

Legenda peran: `G` guest, `U` user login (2FA terverifikasi), `CA` company_admin, `PR` procurement,
`FI` finance, `LE` legal, `SU` sustainability, `VW` viewer, `PA` platform_admin.

## 0. Keputusan pemilik (31 Okt 2026)

| # | Keputusan | Dampak |
| --- | --- | --- |
| K1 | Browse listing **tidak publik**. Wajib login + 2FA + perusahaan `APPROVED`. Data harga & kapasitas dianggap komersial | F1, F2 masuk area pengguna; landing tanpa listing/harga |
| K2 | Penjual di Fase 1 hanya melihat listing miliknya sendiri | E1 tetap; tidak ada listing penjual lain di browse |
| K3 | Urutan pasca-registrasi: registrasi → buat company → setup 2FA → dashboard. Form KYC tidak dipaksa langsung | A2 pecah jadi 2 langkah + B4 wajib sebelum B2 |
| K4 | Form KYC punya field **peran yang diajukan** (buyer/seller). `company_role` baru diisi server setelah admin menyetujui | D2; menjaga aturan "peran ditentukan server" |
| K5 | `platform_admin` hanya lewat `php artisan greengrid:create-admin {email}`. Tanpa akun bawaan, tanpa password default, wajib 2FA, tercatat di `audit_logs` | Tidak ada layar kelola admin |
| K6 | Landing = ringkasan + CTA. Plus halaman placeholder Kebijakan Privasi & Syarat Layanan | A1, A7, A8 |

Keputusan ini perlu dicatat pemilik di `docs/decisions.md` sebagai D-11 s/d D-16.

## 1. Aturan akses perusahaan

| `companies.kyc_status` | Boleh melihat | Tidak boleh |
| --- | --- | --- |
| `NOT_SUBMITTED`, `PENDING_VERIFICATION`, `REJECTED` | Dashboard (B2), profil (B3, B4), form KYC (D1–D5), anggota (B5–C2 untuk CA) | Browse listing, buat listing |
| `APPROVED` | Semua di atas + browse (F1) + listing (E1–E7) | — |

Peran `company_role` hasil KYC: `buyer` → hanya browse & (Fase 2) request; `seller` → hanya listing milik sendiri. Company dua peran hanya bila disetujui dua kali.

## 2. Peta navigasi

```
Publik
├── /                          Landing: nilai + 3 langkah + CTA
├── /register                  Langkah 1: akun + company
├── /register/company           Langkah 2: data perusahaan (bila dipisah)
├── /login                     Masuk
├── /forgot-password           Lupa sandi
├── /reset-password/{token}    Atur ulang sandi
├── /invitations/{token}       Terima undangan anggota
├── /privacy                   Kebijakan Privasi (placeholder)
└── /terms                     Syarat Layanan (placeholder)

Onboarding (login, 2FA belum aktif)
├── /two-factor-challenge      Tantangan 2FA saat login
└── /profile/two-factor        Setup 2FA wajib — gerbang sebelum dashboard

Area pengguna (login + 2FA)
├── /dashboard                 Status KYC + tombol "Lengkapi KYC"
├── /profile                   Profil, zona waktu, sandi
├── /company/members           Anggota perusahaan                     [CA]
├── /company/members/invite    Undang anggota                         [CA]
├── /company/kyc               Status KYC + daftar dokumen            [CA, LE]
├── /company/kyc/submit        Form dokumen + peran diajukan          [CA, LE]
├── /company/kyc/owners        Pemilik manfaat akhir                  [CA, LE]
├── /company/kyc/resubmit      Kirim ulang setelah ditolak            [CA, LE]
├── /company/kyc/submitted     Konfirmasi terkirim (estimasi 48 jam)
├── /company/listings          Daftar listing milik company          [CA, PR]
│   ├── .../create             Pilih jenis aset
│   ├── .../create/energy      Form energi
│   ├── .../create/carbon      Form kredit karbon
│   └── .../{id}               Detail + sertifikat + riwayat versi
├── .../{id}/edit              Ubah (guard version)
└── /listings                  Browse listing ACTIVE                 [APPROVED]

Area admin platform                                        [PA]
├── /admin                     Ringkasan antrian
├── /admin/kyc                 Antrian KYC + sisa waktu
├── /admin/kyc/{id}            Review KYC (dokumen, UBO, sanksi, peran diajukan)
├── /admin/listings            Moderasi listing
├── /admin/listings/{id}       Detail moderasi + keputusan
├── /admin/certificates        Antrian sertifikat
├── /admin/certificates/{id}   Review nomor seri
├── /admin/settings            Pengaturan fee (kosong, D-08)
├── /admin/approvals           Daftar usulan empat mata
└── /admin/approvals/{id}      Setujui / tolak usulan

Sistem
├── /403 /404 /419 /429 /500   Halaman galat
└── /up                        Health check
```

## 3. Daftar layar

30 layar + 6 template email. Setiap layar wajib punya tiga keadaan: kosong, memuat, galat.

### A. Publik (8)

| # | Layar | Route | Peran | FR | Catatan |
| --- | --- | --- | --- | --- | --- |
| A1 | Landing | `/` | G | K6 | Satu kalimat nilai, 3 langkah, CTA Daftar/Masuk. **Tanpa listing/harga** |
| A2 | Registrasi akun | `/register` | G | FR-01#1 | Email, sandi ≥12; lanjut ke A3 |
| A3 | Data perusahaan | `/register/company` | G | FR-01#1,#3 | Nama, negara, nomor registrasi; pendaftar jadi CA |
| A4 | Masuk | `/login` | G | FR-01 | Rate limit; sukses → B1 atau B2 |
| A5 | Lupa sandi | `/forgot-password` | G | FR-01 | |
| A6 | Atur ulang sandi | `/reset-password/{token}` | G | FR-01 | |
| A7 | Kebijakan Privasi | `/privacy` | G | K6 | Placeholder, isi menunggu konsultan hukum |
| A8 | Syarat Layanan | `/terms` | G | K6 | Placeholder |

### B. Onboarding dan akun (7)

| # | Layar | Route | Peran | FR | Catatan |
| --- | --- | --- | --- | --- | --- |
| B1 | Tantangan 2FA | `/two-factor-challenge` | G→U | FR-01#2 | Setelah login bila 2FA aktif |
| B2 | Setup 2FA wajib | `/profile/two-factor` | U (2FA belum aktif) | FR-01#2, K3 | Gerbang: tidak bisa lanjut sebelum konfirmasi |
| B3 | Dashboard | `/dashboard` | U | FR-01, K3 | Kartu status KYC + tombol "Lengkapi KYC"; isi beda per peran |
| B4 | Profil | `/profile` | U | FR-01 | Nama, zona waktu, ganti sandi |
| B5 | Anggota perusahaan | `/company/members` | CA | FR-01#3,#4 | Tabel anggota + status undangan |

### C. Undangan (2)

| # | Layar | Route | Peran | FR | Catatan |
| --- | --- | --- | --- | --- | --- |
| C1 | Terima undangan | `/invitations/{token}` | G→U | FR-01#4 | Token 7 hari; bedakan kedaluwarsa vs sudah dipakai |
| C2 | Undang anggota | `/company/members/invite` | CA | FR-01#4 | Email + peran; aksi tercatat audit |

Ubah peran dan hapus anggota memakai modal di B5.

### D. KYC (5)

| # | Layar | Route | Peran | FR | Catatan |
| --- | --- | --- | --- | --- | --- |
| D1 | Status KYC | `/company/kyc` | CA, LE | FR-02#7 | Badge 4 status + alasan penolakan |
| D2 | Form dokumen | `/company/kyc/submit` | CA, LE | FR-02#1, K4 | Daftar dokumen mengikuti negara; **pilih peran diajukan** (buyer/seller) |
| D3 | Pemilik manfaat akhir | `/company/kyc/owners` | CA, LE | FR-02#5 | Bagian alur submit |
| D4 | Kirim ulang | `/company/kyc/resubmit` | CA, LE | FR-02#4 | Hanya saat `REJECTED` |
| D5 | Konfirmasi terkirim | `/company/kyc/submitted` | CA, LE | FR-02#2 | Sebut estimasi 48 jam |

### E. Listing (7)

| # | Layar | Route | Peran | FR | Catatan |
| --- | --- | --- | --- | --- | --- |
| E1 | Daftar listing company | `/company/listings` | CA, PR | FR-03, K2 | Badge 7 status, filter status; hanya milik sendiri |
| E2 | Pilih jenis aset | `/company/listings/create` | CA, PR | FR-03#3 | Dua kartu: energi / karbon |
| E3 | Form energi | `.../create/energy` | CA, PR | FR-03 | MW, harga, durasi, foto, izin |
| E4 | Form karbon | `.../create/carbon` | CA, PR | FR-03#3 | Tanpa field MW |
| E5 | Detail listing | `/company/listings/{id}` | CA, PR | FR-03#4 | Tab: ringkasan, sertifikat, riwayat versi |
| E6 | Ubah listing | `/company/listings/{id}/edit` | CA, PR | FR-03#4 | Hidden `version`; konflik → 409 |
| E7 | Status sertifikat | bagian E5 | CA, PR | FR-11 | Seri, registri, status VALID/REJECTED + catatan |

### F. Pencarian (2)

| # | Layar | Route | Peran | FR | Catatan |
| --- | --- | --- | --- | --- | --- |
| F1 | Browse listing | `/listings` | U + APPROVED | FR-04#1, K1 | Filter kapasitas, negara, harga, durasi, jenis; hanya ACTIVE |
| F2 | Tanpa hasil | state F1 | U + APPROVED | FR-04#2 | Tombol "simpan pencarian" nonaktif + ket. Fase 2 |

### G. Admin (9)

| # | Layar | Route | Peran | FR | Catatan |
| --- | --- | --- | --- | --- | --- |
| G1 | Ringkasan antrian | `/admin` | PA | FR-05 | Hitungan KYC, listing, sertifikat, usulan |
| G2 | Antrian KYC | `/admin/kyc` | PA | FR-05#1 | Sisa waktu + tanda >72 jam |
| G3 | Review KYC | `/admin/kyc/{id}` | PA | FR-02#4,#5, K4 | Dokumen, UBO, sanksi, peran diajukan; setuju/tolak + alasan |
| G4 | Antrian listing | `/admin/listings` | PA | FR-05#2 | |
| G5 | Moderasi listing | `/admin/listings/{id}` | PA | FR-03 | Setuju → ACTIVE / tolak + alasan |
| G6 | Antrian sertifikat | `/admin/certificates` | PA | FR-11#1 | |
| G7 | Review sertifikat | `/admin/certificates/{id}` | PA | FR-11#1,#2 | VALID/REJECTED + catatan |
| G8 | Pengaturan fee | `/admin/settings` | PA | FR-05#3 | Kosong + label `D-08` |
| G9 | Usulan empat mata | `/admin/approvals` + `/{id}` | PA | FR-05#5 | Usul ≠ setuju; alasan wajib; benturan kepentingan diblokir |

### H. Sistem (2)

| # | Layar | Route | Peran | FR | Catatan |
| --- | --- | --- | --- | --- | --- |
| H1 | Halaman galat | 403/404/419/429/500 | semua | — | 419 khusus sesi habis → arahkan login |
| H2 | Galat undangan | state C1 | G | FR-01#4 | Bedakan kedaluwarsa dan sudah dipakai |

### I. Template email (6, bukan layar)

`kyc_received`, `kyc_approved`, `kyc_rejected`, `listing_approved`, `listing_rejected`, `member_invitation`.

### J. Tanpa layar

`php artisan greengrid:create-admin {email}` — minta password di terminal, wajib 2FA saat login pertama, tulis `audit_logs`. Tanpa akun bawaan di seeder.
`php artisan audit:verify` — FR-12.

## 4. Cakupan FR ke layar

| FR | Layar |
| --- | --- |
| FR-01 | A2, A3, A4, A5, A6, B1, B2, B3, B4, B5, C1, C2 |
| FR-02 | D1, D2, D3, D4, D5, G2, G3 |
| FR-03 | E1, E2, E3, E4, E5, E6, G4, G5 |
| FR-04 | F1, F2 |
| FR-05 | G1, G2, G3, G4, G5, G6, G7, G8, G9 |
| FR-06 | I |
| FR-11 | E7, G6, G7 |
| FR-12 | tanpa layar |

Tidak ada layar untuk: request, penawaran, kontrak, escrow, pembayaran, sengketa, laporan emisi, chat, dashboard keuangan, kelola admin.

## 5. Alur kritis

1. **Onboarding:** A2 → A3 → B2 (2FA wajib) → B3. B3 menampilkan status KYC + CTA.
2. **KYC:** B3 → D2 (pilih peran + dokumen) → D3 → D5 → email. Admin G2 → G3 → setuju/tolak.
3. **Listing:** B3 → E2 → E3/E4 → E1 → admin G4 → G5 → ACTIVE setelah G6/G7 VALID.
4. **Browse:** U + `APPROVED` → F1 → detail (Fase 1 hanya baca; request ditunda).
5. **Undangan:** C2 → email → C1 → B3.

## 6. Sisa pertanyaan

Tidak ada yang memblokir desain. Terbuka untuk implementasi: masa simpan dokumen (D-07) dan daftar dokumen VN/LA (D-04) tetap lewat config.
