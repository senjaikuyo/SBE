# Design System — GreenGrid APAC Fase 1

Dipakai untuk 30 layar di `design/sitemap-fase1.md`. Bahasa UI Inggris, dokumen ini Indonesia.
Target aksesibilitas: WCAG 2.1 AA (PRD §13, FR non-fungsional Fase 1).

## 1. Prinsip

1. **Padat, bukan ramai.** Marketplace B2B: tabel dan angka jadi fokus. Tanpa ilustrasi dekoratif.
2. **Status selalu terbaca.** Setiap status = warna + ikon + teks. Tidak pernah warna saja.
3. **Satu aksi utama per layar.** Tombol sekunder memakai gaya outline.
4. **Jujur soal batas.** Fitur Fase 2 tampil nonaktif dengan keterangan, bukan disembunyikan.
5. **Aman terasa.** KYC dan admin butuh kepastian: label jelas, konfirmasi eksplisit, alasan wajib.

## 2. Token warna

### Brand (hijau energi)

| Token | Hex | Pakai |
| --- | --- | --- |
| `brand-50` | `#EAF6EF` | latar badge lunak |
| `brand-100` | `#D0EBDC` | hover baris terpilih |
| `brand-300` | `#6FBF97` | border fokus sekunder |
| `brand-500` | `#1E8A5F` | tombol utama, tautan |
| `brand-600` | `#147048` | hover tombol utama |
| `brand-700` | `#0F5738` | active, teks di latar terang |
| `brand-900` | `#072D1D` | header gelap (opsional) |

### Netral

| Token | Hex | Pakai |
| --- | --- | --- |
| `ink-900` | `#111614` | teks utama |
| `ink-700` | `#333B39` | teks sekunder kuat |
| `ink-500` | `#6B7773` | teks bantu, placeholder |
| `ink-300` | `#CBD3D0` | border input |
| `ink-200` | `#E3E8E6` | pemisah tabel |
| `ink-100` | `#F1F4F3` | latar header tabel |
| `ink-50` | `#F8FAF9` | latar halaman |
| `white` | `#FFFFFF` | kartu |

### Semantik

| Token | Hex | Ikon | Pakai |
| --- | --- | --- | --- |
| `success` | `#147048` | centang | disetujui, aktif, valid |
| `warning` | `#B45309` | jam | menunggu, mendekati tenggat |
| `danger` | `#B42318` | tanda seru | ditolak, lewat tenggat, galat |
| `info` | `#1D4ED8` | info | terjual habis, keterangan netral |
| `muted` | `#6B7773` | titik | draf, nonaktif, kedaluwarsa |

Kontras teks: semua pasangan di atas ≥ 4.5:1 pada latar putih. Badge memakai latar 10% + teks versi 700.

## 3. Pemetaan status

| Domain | Status | Warna | Label UI |
| --- | --- | --- | --- |
| KYC | `NOT_SUBMITTED` | muted | Not submitted |
| KYC | `PENDING_VERIFICATION` | warning | Under review |
| KYC | `APPROVED` | success | Approved |
| KYC | `REJECTED` | danger | Rejected |
| Listing | `DRAFT` | muted | Draft |
| Listing | `PENDING_REVIEW` | warning | Pending review |
| Listing | `ACTIVE` | success | Active |
| Listing | `REJECTED` | danger | Rejected |
| Listing | `INACTIVE` | muted | Inactive |
| Listing | `SOLD_OUT` | info | Sold out |
| Listing | `EXPIRED` | muted | Expired |
| Certificate | `VALID` | success | Verified |
| Certificate | `REJECTED` | danger | Rejected |
| SLA KYC | < 48 jam | muted | 48h left |
| SLA KYC | 48–72 jam | warning | Due soon |
| SLA KYC | > 72 jam | danger | Overdue |

## 4. Tipografi

Font: `Instrument Sans` (sudah terpasang di `vite.config.js`).

| Peran | Ukuran / baris | Bobot |
| --- | --- | --- |
| Display (landing) | 36 / 44 | 600 |
| H1 halaman | 24 / 32 | 600 |
| H2 seksi | 20 / 28 | 600 |
| H3 kartu | 16 / 24 | 600 |
| Body | 15 / 24 | 400 |
| Body kecil | 13 / 20 | 400 |
| Label form | 13 / 18 | 500 |
| Angka tabel (harga, MW) | 14 / 20 | 500, `tabular-nums` |
| Caption / meta | 12 / 16 | 400 |

Aturan: harga dan kapasitas selalu `font-variant-numeric: tabular-nums`. Tanggal tenggat selalu menyebut zona waktu.

## 5. Spasi, radius, bayangan

- Skala spasi: 4, 8, 12, 16, 20, 24, 32, 40, 48, 64.
- Radius: `sm 6px` (badge, input), `md 10px` (tombol, kartu), `lg 14px` (panel, modal), `full` (pill).
- Bayangan: `sm` untuk kartu, `md` untuk dropdown/modal. Tanpa bayangan berlapis.
- Border: 1px `ink-200`. Fokus: 2px `brand-500` + offset 2px.

## 6. Tata letak

| Area | Struktur |
| --- | --- |
| Publik (A) | Header sederhana, konten maks 720px untuk form, 1120px untuk landing |
| Pengguna (B–F) | Sidebar 240px (Dashboard, Company, Listings, Browse) + konten maks 1120px |
| Admin (G) | Sidebar 240px dengan bagian Queue, Moderation, Settings; konten maks 1280px |
| Mobile | Sidebar jadi drawer; tabel jadi kartu bertumpuk di bawah 768px |

Breakpoint: `sm 640`, `md 768`, `lg 1024`, `xl 1280`.

## 7. Komponen wajib

| Komponen | Varian | Catatan aksesibilitas |
| --- | --- | --- |
| Button | primary, secondary, ghost, danger; sm/md/lg | `aria-busy` + spinner saat memuat, target ≥ 44px |
| Input / Select / Textarea | normal, error, disabled, read-only | label eksplisit, `aria-describedby` untuk bantuan dan galat |
| File upload | dropzone | tulis batas: PDF/JPG/PNG, maks 10 MB; tampilkan nama file + tombol hapus |
| Checkbox / Radio | normal, error | label menempel, bukan placeholder |
| Badge status | 5 warna | ikon + teks, bukan warna saja |
| Table | sortable, empty, loading | header `scope="col"`, skeleton 3 baris saat memuat |
| Card / Stat card | biasa, ringkas | — |
| Stepper | 3–4 langkah (onboarding, KYC) | `aria-current="step"` |
| Tabs | underline | panah kiri/kanan |
| Modal | konfirmasi, form | focus trap, Esc menutup, kembalikan fokus |
| Alert | info, warning, danger, success | `role="alert"` untuk galat |
| Toast | sukses, galat | `aria-live="polite"` |
| Pagination | nomor + prev/next | tombol nonaktif saat memuat |
| Empty state | ikon + 1 kalimat + aksi | dipakai F2, E1, G1 |
| Filter bar | chip + hapus filter | menampilkan jumlah hasil |
| SLA pill | 3 warna | teks sisa waktu, bukan hanya warna |
| Confirm-with-reason | textarea alasan wajib | dipakai tolak KYC/listing, aksi admin |

## 8. Pola khusus

**Form panjang (D2, E3, E4)**
- Satu kolom, lebar maks 640px.
- Ringkasan galat di atas form + fokus ke field pertama yang salah.
- Tombol utama kanan bawah, "Save draft" tersier di kiri.
- Draft otomatis tersimpan (FR-01#5) ditandai teks "Draft saved 12:04".

**Tabel antrian admin (G2, G4, G6)**
- Kolom: entitas, pengaju, waktu masuk, SLA, aksi.
- Baris lewat tenggat diberi border kiri 3px `danger` + SLA pill.
- Aksi cepat: Review. Aksi lain di menu baris.

**Konflik versi (E6)**
- 409 → modal: "This listing changed since you opened it." Pilihan: muat ulang atau lihat perbedaan.

**Empat mata (G9)**
- Kartu usulan menampilkan pengusul, alasan, ringkasan perubahan.
- Tombol Setujui nonaktif bila pengusul = pengguna saat ini, dengan keterangan.

**Kosong vs galat**
- Kosong: "No listings yet" + aksi.
- Galat: pesan spesifik + tombol coba lagi. Jangan pakai teks generik.

## 9. Aksesibilitas (checklist per layar)

1. Semua input punya label terhubung; galat di `aria-describedby`.
2. Fokus terlihat di semua elemen interaktif; urutan tab logis.
3. Status tidak bergantung warna; selalu ikon + teks.
4. Tombol nonaktif saat proses berjalan (FR-04#2, FR-04#3).
5. Toast dan galat diumumkan lewat `role="alert"` / `aria-live`.
6. Modal menahan fokus dan bisa ditutup Esc.
7. Kontras teks ≥ 4.5:1; target sentuh ≥ 44×44 px.
8. Halaman galat 419 menjelaskan sesi habis dan menyediakan tautan masuk.

## 10. Implementasi token

Token ditulis di `resources/css/app.css` memakai `@theme` Tailwind 4. Kelas turunan: `bg-brand-500`, `text-ink-700`, `border-ink-200`, `text-danger`.

## 11. Yang belum diputuskan

- Logo dan aset merek: belum ada.
- Dark mode: tidak diminta PRD, tidak dibuat.
- Bahasa Indonesia di UI: Fase 3 (PRD §13), tidak dibuat sekarang.
