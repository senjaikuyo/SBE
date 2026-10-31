# AGENTS.md: GreenGrid APAC

Marketplace B2B energi surya dan kredit karbon lintas negara. Pembeli: korporat dan data center di Singapura dan Malaysia/Johor. Penjual: pengembang PLTS di Indonesia, Malaysia, Vietnam, Laos.

Platform tidak mengirim listrik, bukan pihak kontrak, bukan bursa.

## Stack (default, ubah di sini bila berbeda)

- PHP 8.3, Laravel 12
- Database: MySQL 8
- Frontend: Blade + Tailwind, server-rendered. Tanpa SPA.
- Auth: Laravel Fortify (email + password + 2FA TOTP)
- Queue: driver `database` saat dev
- File: disk `s3` privat (lokal saat dev), URL bertenggat
- Tes: Pest
- Bahasa UI dan kontrak: Inggris. Kode, nama tabel, dan komentar: Inggris. Dokumen ini: Indonesia.

## Dokumen (baca sesuai tugas, jangan baca semua sekaligus)

| File | Kapan dibaca |
| --- | --- |
| `docs/prd-fase1.md` | Setiap tugas Fase 1 (FR-01 sampai FR-06, FR-11, FR-12) |
| `docs/decisions.md` | Sebelum menyentuh apa pun bertanda `[D-xx]` |
| `docs/progress.md` | Awal tiap sesi. Perbarui di akhir tiap tugas |

Fase 2 (penawaran, kontrak, escrow) belum ada spesifikasinya di repo. Jangan mengerjakannya.

## Aturan kerja

1. Satu sesi, satu FR. Kerjakan hanya yang diminta.
2. Item bertanda `[D-xx]` belum diputuskan. Jangan menebak. Berhenti dan tanya, atau buat stub dengan komentar `// TODO D-xx` dan lapor di `docs/progress.md`.
3. Jangan mengarang aturan bisnis, angka, persentase, atau status yang tidak ada di `docs/prd-fase1.md`. Bila kurang jelas, tanya.
4. Nilai bisnis yang masih usulan (fee, batas waktu, masa simpan) taruh di `config/greengrid.php`. Jangan tulis keras di kode.
5. Tulis tes Pest untuk tiap kriteria penerimaan: jalur normal, jalur gagal, batas nilai.
6. Selesai satu FR: jalankan `php artisan test`, perbarui `docs/progress.md`, lalu commit.

## Aturan teknis wajib

- Uang dan harga: `decimal`, jangan `float`. Simpan `amount` dan `currency` berpasangan. MVP hanya USD.
- Waktu disimpan UTC. Tampilan mengikuti zona waktu pengguna.
- Peran buyer atau seller ditentukan server dari hasil KYC, bukan dari input pengguna.
- Otorisasi lewat Laravel Policy di setiap aksi. Jangan mengandalkan menu yang disembunyikan.
- Aksi sensitif (ubah rekening, tanda tangan, pembayaran) meminta autentikasi ulang.
- Tabel `audit_logs` append-only dengan hash berantai (`hash_prev`, `hash`). Tidak ada update dan delete.
- Upload: PDF, JPG, PNG, maksimum 10 MB, disk privat, tipe dicek dari isi file, pindai antivirus lewat antarmuka yang bisa diganti.
- Ubah data bersamaan: pakai kolom `version` (optimistic locking).
- Semua form POST memakai CSRF. Semua endpoint publik diberi rate limit.
- Jangan simpan nomor rekening atau kartu. Simpan token dari mitra saja (Fase 2).

## Larangan

- Jangan menambah fitur di luar `docs/prd-fase1.md`.
- Jangan membuat chat bebas, AI routing, stablecoin, atau multi-currency.
- Jangan mengubah file di `docs/` kecuali `docs/progress.md`.
- Jangan commit kunci API atau isi `.env`.

## Perintah

```
composer install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed
php artisan test
php artisan serve
```
