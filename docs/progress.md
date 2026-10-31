# Progress

Perbarui di akhir tiap tugas. Format: tanggal, FR, status, catatan, TODO yang tertinggal.

## Status FR Fase 1

| FR | Status | Catatan |
| --- | --- | --- |
| FR-01 | Selesai | Registrasi, perusahaan, anggota, peran, 2FA wajib, sesi idle 30 menit, draft form, audit aksi anggota. |
| FR-12 | Sebagian | Tabel, hash berantai, penjagaan model + trigger MySQL, `audit:verify` selesai. Aksi KYC, listing, sertifikat, admin menyusul di FR terkait. |
| FR-02 | Belum | |
| FR-05 | Belum | |
| FR-03 | Belum | |
| FR-11 | Belum | |
| FR-04 | Belum | |
| FR-06 | Belum | |

## Riwayat

### 2026-10-31 — FR-01 akun, anggota, peran, MFA

- Registrasi email + password (minimal 12 karakter, nilai di `config/greengrid.php`), email unik.
- Alur onboarding: daftar → buat perusahaan → 2FA → dashboard. Pengguna pertama perusahaan otomatis `company_admin`.
- Undangan anggota lewat email berisi token (disimpan sebagai SHA-256, berlaku 7 hari), diterima tamu atau akun yang sudah ada.
- Ubah peran dan hapus anggota hanya untuk `company_admin`, lewat Policy.
- 2FA TOTP wajib sebelum fitur aplikasi; profil tetap bisa diakses. Recovery code bisa dilihat di profil.
- Sesi berakhir setelah 30 menit tanpa aktivitas. Draft form perusahaan tersimpan otomatis dan dipulihkan setelah login.
- Audit: login, undang anggota, ubah peran, hapus anggota, terima undangan. Perintah `php artisan audit:verify` memeriksa rantai hash.
- Halaman mockup yang belum punya FR tetap tersedia sebagai pratinjau statis di URL yang sama.

## TODO yang menunggu keputusan

- FR-12: catat aksi KYC, listing, sertifikat, dan admin saat FR terkait dikerjakan.
- Halaman pratinjau KYC, listing, dan admin masih publik sampai FR-nya selesai.

## Pertanyaan untuk pemilik proyek

- Bolehkah `company_admin` menurunkan peran atau menghapus `company_admin` terakhir? PRD belum mengatur. Saat ini aksi tersebut diizinkan.
