# GreenGrid APAC — B2B Clean Energy & Carbon Marketplace

GreenGrid APAC adalah marketplace B2B lintas negara untuk energi surya dan kredit karbon di Asia Tenggara (Singapura, Malaysia, Indonesia, Vietnam, dan Laos). Platform memfasilitasi verifikasi KYC perusahaan, pembuatan listing energi dan kredit karbon, moderasi admin, verifikasi sertifikat registri (I-REC, VERRA, Gold Standard), dan pencarian marketplace.

---

## Persyaratan Sistem

- PHP 8.3+
- Composer
- MariaDB / MySQL 8
- Node.js & npm (untuk aset Tailwind)

---

## Cara Menjalankan

1. **Install dependensi PHP & aset:**
   ```bash
   composer install
   npm install && npm run build
   ```

2. **Konfigurasi Environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Pastikan konfigurasi koneksi database di `.env` sudah sesuai (misal: MySQL/MariaDB database `greengrid`).

3. **Migrasi dan Seed Data Demo:**
   ```bash
   php artisan migrate --seed
   ```
   *(Demo seeder akan otomatis aktif pada environment `local`)*.

4. **Jalankan Aplikasi:**
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses di `http://localhost:8000`.

---

## Cara Membuat Admin Baru

Untuk membuat akun `platform_admin` baru melalui CLI interaktif:
```bash
php artisan greengrid:create-admin admin@domain.com
```
Perintah akan meminta input kata sandi (minimal 12 karakter) secara aman dan mencatatnya ke audit log.

---

## Menjalankan Pengujian (Testing)

GreenGrid APAC menggunakan suite pengujian Pest:
```bash
# Menjalankan seluruh test suite
./vendor/bin/pest

# Menjalankan ringkas
./vendor/bin/pest --compact

# Menjalankan file tes tertentu
./vendor/bin/pest tests/Feature/DemoSeederTest.php
```

---

## Daftar Akun Demo (Hanya untuk Lingkungan Local)

Semua akun demo di bawah ini menggunakan kata sandi:
```
Password1234!
```

### Cara Login Akun Demo Tanpa Aplikasi Authenticator (2FA)

Setiap akun demo sudah memiliki **Recovery Code** yang bisa langsung dipakai tanpa perlu memasang aplikasi HP:

1. Masukkan email dan kata sandi (`Password1234!`).
2. Pada layar verifikasi 2FA, klik tautan **"Use a recovery code"**.
3. Masukkan kode pemulihan berikut sesuai akun:
   - `admin1@greengrid.test` &rarr; `code-admin-1`
   - `admin2@greengrid.test` &rarr; `code-admin2-1`
   - `seller.admin@nusantara-solar.test` &rarr; `code-s1`
   - `seller.procure@nusantara-solar.test` &rarr; `code-sp1`
   - `buyer.admin@singaporedc.test` &rarr; `code-b1`
   - `pending.admin@mekong-solar.test` &rarr; `code-p1`

**Alternatif jika ingin menggunakan TOTP 6-digit:**
- Masukkan secret key `JBSWY3DPEHPK3PXP` ke aplikasi authenticator, atau
- Jalankan perintah ini di terminal untuk mencetak kode 6-digit yang sedang aktif:
  ```bash
  python3 -c "import hmac,hashlib,struct,time,base64;k=base64.b32decode('JBSWY3DPEHPK3PXP');c=struct.pack('>Q',int(time.time())//30);h=hmac.new(k,c,hashlib.sha1).digest();o=h[-1]&15;print('%06d'%((struct.unpack('>I',h[o:o+4])[0]&0x7fffffff)%1000000))"
  ```

| Email | Peran / Pengguna | Perusahaan | Status / Keterangan |
| --- | --- | --- | --- |
| `admin1@greengrid.test` | Platform Admin 1 | GreenGrid Admin | Rani Kusuma (Admin Utama) |
| `admin2@greengrid.test` | Platform Admin 2 | GreenGrid Admin | Bima Pratama (Admin Kedua untuk Four-Eyes Rule) |
| `seller.admin@nusantara-solar.test` | Company Admin | PT Nusantara Solar Prima | Penjual, KYC APPROVED |
| `seller.procure@nusantara-solar.test` | Procurement | PT Nusantara Solar Prima | Penjual, KYC APPROVED |
| `buyer.admin@singaporedc.test` | Company Admin | Singapore Data Centers Pte Ltd | Pembeli, KYC APPROVED |
| `pending.admin@mekong-solar.test` | Company Admin | Mekong Renewables Co., Ltd | KYC PENDING_VERIFICATION (Antrean Admin) |

---

## Alur Uji Manual 6 Langkah

1. **Registrasi Akun Baru (`/register`):**
   - Buka `/register`, daftarkan akun baru dengan password minimal 12 karakter.
   - Selesaikan pembuatan profil perusahaan, lalu selesaikan aktivasi 2FA TOTP di `/two-factor-setup`.
2. **Pengajuan Verifikasi KYC (`/company/kyc`):**
   - Masuk ke dashboard perusahaan, buka menu KYC Verification.
   - Unggah dokumen legal per negara (NIB/SSM/ACRA) serta dokumen identitas direktur, lalu klik submit.
3. **Verifikasi KYC oleh Admin (`/admin/kyc`):**
   - Login sebagai `admin1@greengrid.test`.
   - Buka menu KYC Queue, periksa batas waktu SLA (72 jam), lalu setujui pengajuan KYC perusahaan.
4. **Pembuatan Listing Penjual (`/listings/create`):**
   - Login sebagai `seller.admin@nusantara-solar.test` atau `seller.procure@nusantara-solar.test`.
   - Buat listing baru (Solar Energy atau Carbon Credit) dengan mengisi kapasitas, harga, durasi, dan nomor seri. Listing tersimpan sebagai `DRAFT` / `PENDING_REVIEW`.
5. **Verifikasi Sertifikat & Aktivasi Listing (`/admin/certificates` & `/admin/listings`):**
   - Login sebagai platform admin.
   - Buka `/admin/certificates`, verifikasi keunikan nomor seri registri (VERRA/Gold Standard), lalu setujui.
   - Di `/admin/listings`, listing kini dapat diaktifkan menjadi status `ACTIVE`.
6. **Pencarian dan Filter Marketplace (`/listings`):**
   - Login sebagai pembeli `buyer.admin@singaporedc.test`.
   - Buka `/listings` (Browse Marketplace).
   - Uji filter berdasarkan jenis aset (Solar Energy / Carbon Credit), negara, kapasitas minimum MW, harga maksimum, dan durasi tahun.
