# PRD v1.2: GreenGrid APAC, Marketplace Energi Surya dan Kredit Karbon Lintas Batas

Versi 1.2. Status: Draft untuk review. Tanggal: 31 Oktober 2026.

Pemilik: Product Owner (nama diisi sebelum review). Penyetuju: Pemilik Bisnis, Lead Engineering, Konsultan Hukum.

Tanda \[D-xx\] menandai keputusan yang butuh persetujuan. Pemilik dan batas waktunya ada di bagian 21. Angka berlabel “usulan” adalah hipotesis yang perlu validasi.

## Riwayat Perubahan

| Versi | Tanggal | Perubahan |
| --- | --- | --- |
| 1.0 | 31 Okt 2026 | Draft awal |
| 1.1 | 31 Okt 2026 | Perbaikan atas 24 temuan roasting: escrow disatukan, aset dipisah, AI dipindah ke Fase 4, model data, status, keamanan, privasi, bisnis |
| 1.2 | 31 Okt 2026 | Menutup celah antar bagian pada v1.1: struktur transaksi dan pengiriman (D-10), top-up escrow per periode, tenggat deposit, fee dipotong dari pelepasan, sertifikat atribut per periode, kontrol admin empat mata, batas negosiasi, ekonomi unit, kriteria uji, Definition of Ready, rencana desain |

## 1. Ringkasan

GreenGrid APAC mempertemukan pembeli korporat dan data center di Singapura dan Malaysia/Johor dengan pengembang PLTS di Indonesia, Malaysia, Vietnam, dan Laos. MVP menangani tiga hal: kontrak pembelian energi surya, kredit karbon, dan escrow pembayaran.

Platform tidak mengirim listrik, tidak menjadi pihak dalam kontrak, dan bukan bursa. Status ini perlu dikonfirmasi opini hukum \[D-01\].

### 1.1 Struktur transaksi MVP \[D-10\]

v1.1 memakai kata “pasokan” tanpa menjelaskan siapa mengirim listrik. v1.2 menetapkan struktur default untuk MVP:

- Kontrak energi adalah kontrak pembelian berbasis produksi: penjual menyerahkan MWh yang diproduksi proyek surya dan terverifikasi meter, beserta sertifikat atribut energi (I-REC) untuk volume itu.
- Pengiriman fisik listrik dan hubungan dengan utilitas berada di luar platform dan di luar kontrak GreenGrid.
- Struktur lain (PPA fisik lintas batas, PPA virtual) tidak masuk MVP sebelum opini hukum D-01 dan keputusan D-10.

Semua kata “pasokan” di dokumen ini berarti penyerahan volume produksi terverifikasi dan sertifikat atributnya, bukan pengiriman listrik oleh platform.

## 2. Masalah dan Janji Nilai

| Pihak | Masalah |
| --- | --- |
| Pembeli | Sumber listrik hijau lokal terbatas. Target keberlanjutan dan kewajiban karbon menekan biaya |
| Penjual | Sulit menemukan offtaker jangka panjang. Modal proyek tertahan karena risiko offtake |
| Lintas negara | Regulasi, mata uang, dan sertifikasi berbeda di tiap negara |

### 2.1 Janji nilai

Pembeli menutup sebagian konsumsi listriknya dengan energi surya terkontrak dan melaporkannya tiap bulan. Platform tidak menjanjikan pasokan 24 jam. Surya hanya berproduksi di siang hari. Kebutuhan malam berada di luar cakupan sampai ada penyimpanan atau sumber lain.

Metrik janji nilai: persentase konsumsi bulanan pembeli yang tertutup kontrak (MWh kontrak dibagi MWh konsumsi yang dilaporkan pembeli).

### 2.2 Mekanisme manfaat bagi pembeli

- Kontrak energi menurunkan emisi Scope 2 pembeli (market-based, GHG Protocol).
- Kredit karbon dipakai untuk klaim offset atau pengurang kewajiban sesuai aturan regulator masing-masing.

PRD memakai “pajak karbon” hanya untuk pajak. Pembeli target belum tentu membayar pajak karbon dari pembelian listrik. Product Owner mewawancarai lima calon pembeli dan mencatat jenis kewajiban, tonase, dan produk yang menurunkannya \[D-02\]. Rumus yang dicatat per pembeli: kewajiban awal, pengurang dari produk GreenGrid, selisih.

## 3. Tujuan dan Metrik

| No | Tujuan | Metrik | Rumus dan sumber data | Target awal (usulan) | Pemilik |
| --- | --- | --- | --- | --- | --- |
| 1 | Demand anchor | Pembeli dengan kontrak aktif | Jumlah COMPANY pembeli dengan CONTRACT ACTIVE | 5 dalam 6 bulan setelah go-live Fase 2 | Product Owner |
| 2 | Kontrak terikat | Kapasitas terkontrak | Jumlah MW pada kontrak ACTIVE | 10 MW dalam 12 bulan setelah go-live Fase 2 | Product Owner |
| 3 | Siklus penjualan | Median hari dari listing ACTIVE ke kontrak SIGNED | Selisih timestamp | 60 hari, ditinjau setelah 10 kontrak | Product Owner |
| 4 | Likuiditas penjual | Listing aktif terverifikasi | Jumlah LISTING ACTIVE | 15 dalam 3 bulan setelah go-live Fase 1 | Product Owner |
| 5 | Kredit karbon | Ton CO2e terjual | Jumlah ton pada transaksi karbon selesai | Ditetapkan setelah D-02 | Product Owner |
| 6 | Kecepatan KYC | Median jam dari submit ke keputusan | Selisih timestamp KYC | 48 jam, 90% selesai dalam 72 jam | Admin Platform |
| 7 | Keamanan | Insiden kebocoran data dan temuan kritis audit internal | Laporan insiden dan audit | 0 dalam 3 bulan pertama | Lead Engineering |
| 8 | Konversi | Request menjadi kontrak | Kontrak SIGNED dibagi REQUEST | Dicatat sejak hari pertama, target setelah 20 request | Product Owner |
| 9 | Pendapatan | Pendapatan platform per bulan | Jumlah fee yang dipotong dari pelepasan escrow (bagian 16.4) | Ditetapkan setelah D-08 | Pemilik Bisnis |
| 10 | Pelepasan tepat waktu | Pelepasan escrow dalam SLA | Pelepasan dalam 5 hari kerja dibagi total pelepasan | 95% | Admin Platform |

Kontrak “ditandatangani lewat platform” berarti status SIGNED kedua pihak tercatat di CONTRACT. Target 5 pembeli dan 10 MW saling konsisten bila rata-rata kontrak 2 MW. Angka ini tetap hipotesis sampai D-02 selesai.

## 4. Non-Goals dan Di Luar Cakupan

- Manajemen operasi fisik pembangkit (monitoring SCADA).
- Energi selain surya, termasuk angin dan hidro. Penjual Laos diterima bila memiliki proyek surya.
- Pasar sekunder karbon dan perdagangan bursa.
- Marketplace B2C untuk rumah tangga.
- Layanan pembiayaan langsung. Platform hanya menyediakan escrow lewat mitra berlisensi.
- Pengiriman fisik listrik, PPA fisik lintas batas, dan pengaturan kuota transmisi oleh platform.
- Stablecoin dan multi-currency pada MVP.
- Chat bebas. Komentar pada penawaran masuk Fase 3 dan tidak mengikat.
- AI Smart Routing dan alokasi kuota transmisi (Fase 4 dan 5).

## 5. Persona dan Peran

| Persona | Peran | Kebutuhan utama | Pain point |
| --- | --- | --- | --- |
| Buyer-APAC | Kepala Operasi Data Center atau Sustainability Head | Energi hijau berharga tetap, laporan emisi | Sumber hijau lokal terbatas |
| Developer-SEA | CEO atau Project Manager pengembang PLTS | Offtaker jangka panjang, pendanaan lebih cepat | Pembeli sulit ditemukan |
| Admin Platform | Tim operasi dan kepatuhan | Verifikasi KYC, moderasi listing, fee, sengketa | Dokumen palsu dan penipuan |

### 5.1 Peran dalam satu perusahaan

Satu perusahaan punya banyak pengguna lewat tabel MEMBERSHIP.

| Aksi | Admin Perusahaan | Procurement | Finance | Legal | Sustainability | Viewer |
| --- | --- | --- | --- | --- | --- | --- |
| Kelola anggota | Ya | Tidak | Tidak | Tidak | Tidak | Tidak |
| Buat request atau penawaran | Ya | Ya | Tidak | Tidak | Tidak | Tidak |
| Setujui pembayaran dan top-up | Ya | Tidak | Ya | Tidak | Tidak | Tidak |
| Ubah rekening pembayaran | Ya | Tidak | Ya | Tidak | Tidak | Tidak |
| Tanda tangan kontrak | Ya, bila penandatangan sah | Tidak | Tidak | Ya, bila penandatangan sah | Tidak | Tidak |
| Lihat dashboard dan laporan | Ya | Ya | Ya | Ya | Ya | Ya |

Pembayaran di atas batas nilai yang ditetapkan Admin Perusahaan membutuhkan dua persetujuan dari dua orang berbeda. Penandatangan sah dibuktikan surat penunjukan yang disetujui admin saat KYC. Bila Admin Perusahaan tunggal tidak dapat diakses, pemulihan lewat verifikasi ulang dokumen oleh Admin Platform.

### 5.2 Aturan peran perusahaan

- Server menentukan peran buyer atau seller dari hasil KYC. Pengguna tidak memilihnya di antarmuka.
- Satu perusahaan boleh memegang dua peran hanya bila lulus verifikasi untuk keduanya.
- Transaksi antara perusahaan dengan pemilik manfaat akhir yang sama diblokir. Pengecekan memakai data pemilik manfaat akhir dari KYC dan diulang tiap kali listing atau request dibuat.

### 5.3 Kontrol Admin Platform

- Aksi berdampak uang (pelepasan manual, refund manual, keputusan sengketa, ubah pengaturan fee) memakai prinsip empat mata: satu admin mengusulkan, admin lain menyetujui.
- Admin tidak dapat memutuskan sengketa pada perusahaan tempat ia pernah terdaftar atau memiliki kepentingan.
- Semua aksi admin masuk log audit dengan alasan wajib.

## 6. Cakupan MVP

1. Akun, anggota perusahaan, dan peran (KYC/AML).
2. Listing energi dan kredit karbon.
3. Pencarian dan filter.
4. Request dan penawaran terstruktur.
5. Kontrak digital dengan tanda tangan elektronik.
6. Escrow USD lewat mitra berlisensi.
7. Notifikasi email.
8. Dashboard status kontrak dan escrow.
9. Admin panel dengan kontrol empat mata.
10. Log audit.

Di luar MVP: laporan emisi (Fase 3), komentar (Fase 3), SSO, push notification, REST API ERP (Fase 3 atau lebih), prakiraan produksi (Fase 4).

## 7. Kebutuhan Fungsional

P0 berarti wajib agar satu transaksi berjalan dari awal sampai akhir. P1 menyusul.

| ID | Kebutuhan | Deskripsi | Prioritas | Fase |
| --- | --- | --- | --- | --- |
| FR-01 | Akun, anggota, peran | Registrasi email dan password, MFA, profil perusahaan, undang anggota, peran sesuai bagian 5 | P0 | 1 |
| FR-02 | KYC/AML | Unggah dokumen per negara, screening sanksi awal dan berkala, pemilik manfaat akhir, keputusan admin | P0 | 1 |
| FR-03 | Listing | Formulir energi dan karbon terpisah, dokumen pendukung, persetujuan admin | P0 | 1 |
| FR-04 | Pencarian | Filter kapasitas, lokasi, harga, durasi, jenis aset | P0 | 1 |
| FR-05 | Admin panel | Antrian KYC, moderasi listing, pengaturan fee, pelacakan sengketa, empat mata | P0 | 1 |
| FR-06 | Notifikasi email | Status KYC, request, penawaran, kontrak, pembayaran, pengingat top-up | P0 | 1 (KYC dan listing), 2 (sisanya) |
| FR-07 | Request dan penawaran | Request, penawaran terstruktur berversi, counter-offer maksimum 5 putaran, reservasi volume | P0 | 2 |
| FR-08 | Kontrak digital | PDF dari penawaran terakhir, tanda tangan dua pihak, checksum | P0 | 2 |
| FR-09 | Escrow | Deposit, top-up, pelepasan per tonggak, potong fee, refund, sengketa, verifikasi rekening | P0 | 2 |
| FR-10 | Dashboard status | Status kontrak, escrow, pembacaan meter | P0 | 2 |
| FR-11 | Verifikasi sertifikat | Cek nomor seri ke registri, status VALID, TRANSFERRED, RETIRED. Termasuk sertifikat atribut per periode kontrak energi | P0 | 1 (manual), 2 (transfer dan pensiun) |
| FR-12 | Log audit | Append-only untuk login, ubah kontrak, pembayaran, ubah rekening, aksi admin | P0 | 1 |
| FR-13 | Laporan emisi dan kepatuhan | Metodologi bagian 8.6, ekspor CSV, Excel, PDF | P1 | 3 |
| FR-14 | Komentar pada penawaran | Komentar teks, tidak mengikat | P1 | 3 |
| FR-15 | SSO, push, REST API ERP | Setelah ada permintaan pengguna | P1 | 3 atau lebih |
| FR-16 | Prakiraan produksi per proyek | Bagian 17 | P1 | 4 |

## 8. Fitur Inti dan Kriteria Penerimaan

### 8.1 Registrasi dan KYC (FR-01, FR-02)

Cerita: Sebagai manajer operasional data center, saya ingin mendaftar dan diverifikasi agar bisa mengakses marketplace.

| Negara | Dokumen perusahaan |
| --- | --- |
| Indonesia | NIB, NPWP, akta pendirian dan pengesahannya |
| Singapura | Profil perusahaan ACRA |
| Malaysia | Dokumen SSM |
| Vietnam | Sertifikat registrasi perusahaan |
| Laos | Sertifikat registrasi perusahaan |

Semua negara juga mengirim identitas direktur, pemilik manfaat akhir, surat penunjukan penandatangan, dan bukti rekening bank perusahaan. Daftar Vietnam dan Laos diverifikasi konsultan hukum lokal \[D-04\].

Kriteria penerimaan:

1. Form menerima email, password, dan dokumen sesuai tabel. Dokumen kedaluwarsa ditolak otomatis.
2. Status menjadi PENDING\_VERIFICATION dan email konfirmasi terkirim dalam 5 menit.
3. Screening sanksi berjalan otomatis lewat vendor KYC, saat pendaftaran dan ulang tiap 90 hari untuk perusahaan aktif.
4. Admin menyetujui atau menolak. Penolakan memuat alasan dan pengguna dapat mengirim ulang dokumen.
5. Median waktu keputusan 48 jam. Sembilan puluh persen selesai dalam 72 jam. Email menyebut estimasi ini saat konfirmasi.
6. Perusahaan yang lolos screening sanksi namun gagal pemeriksaan pemilik manfaat akhir tidak dapat transaksi sampai admin menyetujui ulang.

### 8.2 Listing (FR-03)

Listing energi: kapasitas terpasang (MW), volume kontrak tersedia (MW), tarif tetap (US$/kWh), durasi, tanggal mulai produksi, lokasi, izin proyek, foto. Eskalasi tarif tahunan: 0% pada MVP dan tarif tetap sepanjang kontrak.

Listing kredit karbon: jumlah (ton CO2e), vintage, standar registri (Verra atau Gold Standard), nomor seri, harga (US$/ton).

Kriteria penerimaan:

1. Semua field wajib terisi. Pesan error menyebut field yang salah.
2. Listing berstatus PENDING\_REVIEW sampai admin menyetujui. Penolakan memuat alasan dan listing kembali ke DRAFT.
3. Nomor seri sertifikat dicek ke registri sebelum ACTIVE. Nomor seri yang sama tidak boleh dipakai di dua listing.
4. Penjual mengedit atau menghapus listing hanya bila tidak ada request terbuka. Perubahan membuat versi baru. Kontrak yang sudah SIGNED tidak terpengaruh.
5. Volume tersedia berkurang saat penawaran diterima dan kembali bila penawaran batal, kedaluwarsa, atau kontrak EXPIRED\_UNSIGNED atau EXPIRED\_UNFUNDED.
6. Listing energi tanpa bukti kepemilikan meter atau data produksi untuk proyek berjalan ditandai “pra-operasi” dan tidak dapat meminta deposit sampai meter terhubung.

### 8.3 Request dan penawaran (FR-07)

Kriteria penerimaan:

1. Pembeli mengirim request berisi volume, durasi, dan harga maksimum. Sistem menyimpan snapshot harga dan versi listing.
2. Penjual menerima, menolak, atau mengirim counter-offer. Tiap counter-offer membuat versi OFFER baru. Maksimum 5 putaran, setelah itu penawaran ditutup (EXPIRED).
3. Penawaran kedaluwarsa 7 hari tanpa respons. Status EXPIRED.
4. Penawaran berstatus ACCEPTED hanya bila pihak yang menerima versi terakhir adalah pihak lawan dari pembuat versi itu. Persetujuan sepihak tidak cukup.
5. Volume ditahan sejak ACCEPTED sampai kontrak ACTIVE atau gugur. Dua pembeli tidak bisa menerima volume yang sama (reservasi dengan kunci).
6. Kontrak dibuat dari penawaran terakhir yang disetujui kedua pihak. Komentar bebas tidak mengikat.

### 8.4 Kontrak digital (FR-08)

Kriteria penerimaan:

1. PDF memuat kapasitas, harga, durasi, tonggak escrow, mekanisme top-up, klausul wanprestasi dan terminasi, klausul sengketa, dan hukum yang berlaku.
2. Template mengikuti yurisdiksi kontrak \[D-05\].
3. Kedua pihak menandatangani dalam 14 hari. Lewat batas, status EXPIRED\_UNSIGNED dan volume dilepas.
4. Penyedia tanda tangan sah di yurisdiksi kontrak, termasuk e-meterai di Indonesia bila berlaku \[D-05\].
5. Kontrak final tersimpan dengan checksum SHA-256.
6. Pembeli menyetor deposit T0 dalam 5 hari kerja sejak SIGNED. Lewat batas, status EXPIRED\_UNFUNDED dan volume dilepas.

### 8.5 Escrow (FR-09)

Lihat bagian 9.

### 8.6 Dashboard dan laporan (FR-10, FR-13)

Fase 2: status kontrak, status escrow, pembacaan meter per periode.

Fase 3: laporan emisi memakai GHG Protocol Scope 2 (market-based). Faktor emisi dan sumbernya dicantumkan di tiap laporan. Ekspor CSV, Excel, dan PDF untuk semua laporan. Laporan energi dan laporan kredit karbon dipisah. Format wajib regulator tiap negara dicatat sebelum Fase 3 dimulai.

## 9. Escrow dan Tonggak Pembayaran \[D-03\]

Satu definisi menggantikan semua definisi sebelumnya. Persentase di bawah berlabel usulan sampai D-03 disetujui.

| Produk | Tonggak | Syarat | Dana yang dilepas | Sumber data | Pemicu |
| --- | --- | --- | --- | --- | --- |
| Energi | T0 Deposit awal | Kontrak SIGNED. Pembeli menyetor tagihan perkiraan satu periode (volume kontrak × tarif) dalam 5 hari kerja | 0% | Kontrak | Otomatis. Lewat batas: EXPIRED\_UNFUNDED |
| Energi | T1 Top-up periode berikutnya | Pembeli menyetor tagihan periode berikutnya paling lambat 10 hari sebelum periode mulai | 0% | Kontrak | Pengingat otomatis. Gagal: lihat aturan gagal top-up |
| Energi | T2 Pelepasan produksi | Meter periode terverifikasi pihak ketiga | 90% dari (volume terverifikasi × tarif) dikurangi fee | Meter utilitas atau operator jaringan | Otomatis dalam 5 hari kerja |
| Energi | T3 Pelepasan sertifikat | Sertifikat atribut periode (I-REC) berpindah ke pembeli | 10% sisanya dikurangi fee | Registri | Otomatis dalam 5 hari kerja setelah konfirmasi. Batas pemindahan: 30 hari setelah periode |
| Energi | T4 Kelebihan deposit | Volume terverifikasi di bawah volume kontrak | Selisih dikembalikan ke pembeli atau dikreditkan ke periode berikutnya sesuai pilihan di kontrak | Meter | Otomatis |
| Karbon | K0 Deposit | Kontrak SIGNED. Pembeli menyetor 100% dalam 5 hari kerja | 0% | Kontrak | Lewat batas: EXPIRED\_UNFUNDED |
| Karbon | K1 Transfer dan pensiun | Penjual memulai transfer dalam 5 hari kerja sejak deposit HELD. Registri mengonfirmasi pemindahan dan pensiun atas nama pembeli | 100% dikurangi fee | Registri | Otomatis dalam 3 hari kerja setelah konfirmasi |

Aturan tambahan:

- Mata uang MVP: USD. Mitra escrow berlisensi dipilih di D-06. Penjual menerima USD di rekening yang sudah diverifikasi. Konversi ke mata uang lokal terjadi di bank penjual dan di luar tanggung jawab platform.
- Fee dipotong dari dana yang dilepas dan tampil terpisah di rincian pelepasan. Platform tidak menagih fee lewat jalur lain.
- Setiap pembayaran memakai idempotency key. Tombol bayar nonaktif setelah klik.
- Rekening penjual harus atas nama perusahaan yang lulus KYC. Perubahan rekening membutuhkan autentikasi ulang, persetujuan peran Finance atau Admin Perusahaan, email ke seluruh Admin Perusahaan, dan penahanan pelepasan 48 jam.
- Aturan gagal top-up: pengingat saat batas lewat. Tidak ada top-up dalam 5 hari kerja: eskalasi ke Admin Perusahaan pembeli dan penjual. Tidak ada top-up dalam 10 hari kerja: kontrak SUSPENDED dan penjual boleh menghentikan penyerahan. Tidak ada top-up dalam 30 hari: penjual boleh meminta TERMINATED.
- Wanprestasi volume: terverifikasi di bawah 95% kontrak pada periode tertentu memicu pemberitahuan. Tiga periode berturut-turut memberi pembeli hak terminasi. Pelepasan tetap proporsional volume terverifikasi.
- Refund terjadi bila kontrak TERMINATED sebelum penyerahan atau kredit gagal berpindah dalam 10 hari kerja.
- Data meter tidak tersedia lebih dari 5 hari kerja setelah periode: admin boleh melepas manual berdasarkan bukti kedua pihak, dengan persetujuan empat mata, maksimum 2 periode berturut-turut. Setelahnya kontrak masuk DISPUTED.
- Sengketa: pihak mana pun membuka dalam 10 hari kerja sejak pelepasan. Hanya porsi yang disengketakan tertahan. Admin Platform memberi rekomendasi berdasarkan bukti dalam 15 hari kerja. Keputusan akhir mengikuti klausul sengketa kontrak (negosiasi, mediasi, lalu arbitrase).

## 10. Alur Pengguna dan Kondisi Khusus

### UC-1: Beli energi surya

Prakondisi: akun pembeli terverifikasi dan ada listing yang cocok.

1. Pembeli membuka Cari Energi dan mengisi filter, misalnya 3 MW atau lebih, harga maksimal US$0,09/kWh, durasi 5 tahun atau lebih.
2. Sistem menampilkan listing yang cocok.
3. Pembeli memilih listing dan mengirim request, misalnya 4 MW.
4. Penjual menerima notifikasi, lalu menerima, menolak, atau mengirim counter-offer.
5. Setelah penawaran ACCEPTED oleh pihak lawan, sistem membuat kontrak PDF dan menahan volume.
6. Kedua pihak menandatangani secara elektronik dalam 14 hari.
7. Pembeli menyetor deposit T0 dalam 5 hari kerja.
8. Setelah deposit HELD, kontrak ACTIVE dan penyerahan produksi dimulai pada tanggal mulai di kontrak.
9. Tiap periode: pembeli top-up (T1), meter terverifikasi melepas 90% (T2), sertifikat atribut berpindah melepas 10% (T3).

Pascakondisi: kontrak ACTIVE, dana periode tertahan di escrow, pelepasan berjalan per periode, fee terpotong di tiap pelepasan.

Alur alternatif:

- A1: Penjual menolak. Pembeli menerima notifikasi dan kembali ke langkah 1.
- A2: Pembayaran gagal. Sistem menampilkan penyebab dan menawarkan ulang dengan idempotency key yang sama. Tidak ada penarikan ganda.
- A3: Penawaran kedaluwarsa. Volume dilepas dan kedua pihak menerima notifikasi.
- A4: Satu pihak tidak menandatangani dalam 14 hari. Status EXPIRED\_UNSIGNED.
- A5: Deposit tidak masuk dalam 5 hari kerja. Status EXPIRED\_UNFUNDED dan volume dilepas.
- A6: Sesi habis saat mengisi form. Sistem menyimpan draft dan memulihkannya setelah login.
- A7: Upload gagal atau terdeteksi malware. Sistem menolak file dan menjelaskan alasannya.
- A8: Top-up terlambat. Alur eskalasi bagian 9, kontrak dapat SUSPENDED.
- A9: Data meter tidak tersedia. Pelepasan manual empat mata bagian 9.

### UC-2: Jual kredit karbon

Prakondisi: akun penjual terverifikasi dan nomor seri kredit valid di registri.

1. Penjual membuat listing kredit karbon dan mengunggah dokumen.
2. Admin memverifikasi dokumen dan nomor seri, lalu menyetujui.
3. Pembeli mengirim request. Penjual menerima atau mengirim counter-offer.
4. Kedua pihak menandatangani kontrak.
5. Pembeli menyetor 100% ke escrow dalam 5 hari kerja (K0).
6. Penjual memulai transfer dalam 5 hari kerja. Sertifikat berpindah ke pembeli dan dipensiunkan atas nama pembeli di registri.
7. Setelah registri mengonfirmasi, dana dilepas ke penjual dikurangi fee dan LEDGER\_ENTRY dicatat.

Alur alternatif: A1 sertifikat tidak memenuhi standar, admin menolak dengan alasan. A2 pembeli membatalkan sebelum deposit, REQUEST menjadi CANCELLED. A3 penjual tidak memulai transfer dalam 5 hari kerja, admin mengirim peringatan, dan setelah 10 hari kerja dana REFUNDED.

### Kondisi tampilan

- Kosong: pencarian tanpa hasil menampilkan opsi simpan pencarian dan terima notifikasi bila listing baru cocok.
- Memuat: indikator di semua daftar dan grafik. Tombol aksi nonaktif selama proses.
- Error: pesan menyebut penyebab dan langkah berikutnya. Kode referensi tersedia untuk dukungan.

## 11. Status dan Transisi

| Entitas | Status | Transisi |
| --- | --- | --- |
| LISTING | DRAFT, PENDING\_REVIEW, ACTIVE, REJECTED, INACTIVE, SOLD\_OUT, EXPIRED | DRAFT ke PENDING\_REVIEW oleh penjual. PENDING\_REVIEW ke ACTIVE atau REJECTED oleh admin. REJECTED ke DRAFT oleh penjual. ACTIVE ke SOLD\_OUT bila volume tersedia nol. ACTIVE ke EXPIRED di tanggal kedaluwarsa yang diisi penjual |
| REQUEST | PENDING, COUNTERED, ACCEPTED, REJECTED, EXPIRED, CANCELLED | PENDING ke COUNTERED, ACCEPTED, atau REJECTED oleh penjual. Tanpa respons 7 hari ke EXPIRED. Pembeli membatalkan sebelum ACCEPTED |
| OFFER | PROPOSED, SUPERSEDED, ACCEPTED, REJECTED, EXPIRED | Versi baru membuat versi lama SUPERSEDED. Hanya pihak lawan yang dapat ACCEPTED. Putaran kelima tanpa kesepakatan ke EXPIRED |
| CONTRACT | DRAFT, PENDING\_SIGNATURE, SIGNED, ACTIVE, SUSPENDED, COMPLETED, TERMINATED, DISPUTED, EXPIRED\_UNSIGNED, EXPIRED\_UNFUNDED | PENDING\_SIGNATURE ke SIGNED bila kedua pihak menandatangani. SIGNED ke ACTIVE setelah deposit. Tanpa tanda tangan 14 hari ke EXPIRED\_UNSIGNED. Tanpa deposit 5 hari kerja ke EXPIRED\_UNFUNDED. ACTIVE ke SUSPENDED bila top-up gagal 10 hari kerja. ACTIVE ke COMPLETED di akhir durasi setelah semua pelepasan selesai. Pembeli atau penjual meminta TERMINATED sesuai klausul |
| ESCROW | PENDING\_DEPOSIT, HELD, PARTIALLY\_RELEASED, RELEASED, REFUNDED, DISPUTED | PENDING\_DEPOSIT ke HELD saat deposit masuk. HELD ke PARTIALLY\_RELEASED per pelepasan. RELEASED saat saldo nol setelah COMPLETED. Sengketa membuat porsi terkait DISPUTED |
| CERTIFICATE | VALID, TRANSFERRED, RETIRED, REJECTED | VALID ke TRANSFERRED saat berpindah ke pembeli. TRANSFERRED ke RETIRED bila klaim dipensiunkan di registri |
| DISPUTE | OPEN, UNDER\_REVIEW, RESOLVED, ESCALATED | OPEN oleh pihak yang keberatan. UNDER\_REVIEW oleh admin. RESOLVED bila kedua pihak menerima rekomendasi. ESCALATED ke mediasi atau arbitrase |

## 12. Model Data

| Entitas | Field utama | Relasi |
| --- | --- | --- |
| COMPANY | id, name, country, registrationNumber, kycStatus, companyRole | Punya banyak MEMBERSHIP, KYC\_DOCUMENT, LISTING, BANK\_ACCOUNT |
| USER | id, email, passwordHash, mfaEnabled | Punya banyak MEMBERSHIP |
| MEMBERSHIP | userId, companyId, role, approvalLimit | Menghubungkan USER dan COMPANY |
| KYC\_DOCUMENT | companyId, type, fileUrl, expiresAt, status | Milik COMPANY |
| BANK\_ACCOUNT | companyId, providerToken, verifiedAt, changedAt | Milik COMPANY. Nomor rekening disimpan mitra, platform hanya menyimpan token |
| LISTING | companyId, type, volumeTotal, volumeSisa, unit, price, priceUnit, durationYears, status, version, expiresAt | Punya CERTIFICATE. Menerima REQUEST |
| CERTIFICATE | listingId, contractId, period, type (I-REC atau KARBON), registry, serialNumber, status | Milik LISTING. Untuk energi, satu per periode kontrak |
| REQUEST | listingId, listingVersion, priceSnapshot, requestedVolume, unit, status | Punya banyak OFFER |
| OFFER | requestId, version, volume, price, durationYears, terms, status, createdBy | Menghasilkan satu CONTRACT |
| CONTRACT | offerId, templateVersion, pdfUrl, checksum, status, startDate, periodType | Punya CONTRACT\_PARTY, ESCROW, METER\_READING, DISPUTE |
| CONTRACT\_PARTY | contractId, companyId, partyRole, signedAt | Pihak penjual dan pembeli |
| ESCROW | contractId, provider, status, balance, currency | Punya banyak PAYMENT dan RELEASE |
| PAYMENT | escrowId, type (DEPOSIT atau TOPUP), period, amount, idempotencyKey, status, createdAt | Milik ESCROW |
| RELEASE | escrowId, period, milestone, grossAmount, feeAmount, netAmount, status, approvedBy | Milik ESCROW. Manual butuh dua approvedBy |
| LEDGER\_ENTRY | companyId, certificateId, delta, reason, createdAt | Mencatat perpindahan kredit |
| METER\_READING | contractId, period, mwh, source, verifiedBy | Milik CONTRACT |
| DISPUTE | contractId, openedBy, scope, amount, status, decision | Milik CONTRACT |
| NOTIFICATION | userId, channel, event, sentAt | Milik USER |
| AUDIT\_LOG | actorId, action, entity, entityId, reason, hashPrev, hash, createdAt | Append-only |

## 13. Kebutuhan Non-Fungsional

| Kategori | Kebutuhan |
| --- | --- |
| Performa | p95 halaman utama di bawah 2 detik pada 4G. p95 pencarian di bawah 1 detik. p95 checkout di bawah 5 detik di luar waktu gateway. p95 API di bawah 500 ms dari Singapura |
| Skalabilitas | 50 pengguna aktif dan 20 transaksi per hari pada tahun pertama. Uji beban pada 10 kali lipat angka itu sebelum go-live |
| Ketersediaan | 99,9% untuk kontrak dan escrow. 99,5% untuk fitur lain. Jendela perawatan diumumkan 48 jam sebelumnya |
| Backup | Harian dengan RPO 1 jam dan RTO 4 jam. Uji pemulihan tiap kuartal |
| Browser | Chrome, Edge, Safari, dan Firefox pada dua versi terbaru. Responsif di mobile |
| Aksesibilitas | WCAG 2.1 AA untuk alur inti: registrasi, listing, request, kontrak, pembayaran |
| Bahasa | Antarmuka dan kontrak dalam bahasa Inggris pada MVP. Bahasa Indonesia pada Fase 3 |
| Waktu dan angka | Waktu disimpan UTC dan tampil lokal. Tenggat menyebut zona waktu. Format angka mengikuti locale pengguna |
| Mata uang | Nilai disimpan sebagai amount (decimal) dan currency. MVP: USD. Konversi kurs Fase 3 |

## 14. Keamanan dan Skenario Kegagalan

### 14.1 Keamanan

- Enkripsi AES-256 saat disimpan dan TLS 1.3 saat transit.
- MFA wajib untuk semua pengguna. Tanda tangan, pembayaran, dan ubah rekening meminta autentikasi ulang.
- RBAC sesuai bagian 5. Server memeriksa peran di setiap permintaan.
- Sesi berakhir setelah 30 menit tanpa aktivitas. Draft form tersimpan.
- Upload: PDF, JPG, PNG, maksimum 10 MB, pindai antivirus, bucket privat, URL bertenggat.
- Webhook pembayaran diverifikasi dengan tanda tangan penyedia. Rate limit di semua endpoint publik.
- Log audit aplikasi di tabel append-only dengan hash berantai. CloudTrail hanya merekam infrastruktur.
- Kontrol empat mata untuk aksi admin berdampak uang (bagian 5.3).
- Prosedur akun diambil alih: kunci akun, batalkan sesi, bekukan pelepasan, verifikasi ulang identitas, tinjau log audit.
- Penilaian OWASP Top 10 dan uji penetrasi pihak luar sebelum go-live.

### 14.2 Skenario kegagalan

| Skenario | Penanganan |
| --- | --- |
| API pihak ketiga mati (gateway, KYC, e-signature, registri) | Antrian dengan retry backoff. Status menampilkan “menunggu”. Admin menerima peringatan. Tenggat kontrak dihentikan selama gangguan penyedia |
| Database hampir penuh | Alarm pada 70% dan 85% kapasitas. Prosedur penambahan kapasitas tertulis |
| Lonjakan trafik 10 kali lipat | Auto-scaling, antrian untuk tugas berat, uji beban |
| Klik ganda tombol bayar atau kirim | Idempotency key dan tombol nonaktif setelah klik |
| Dua pengguna mengubah data yang sama | Optimistic locking dengan nomor versi. Pengguna kedua melihat pesan konflik |
| Dua pembeli meminta volume yang sama | Reservasi volume dengan kunci. Pemenang pertama, pembeli kedua diberi sisa volume |
| Sesi habis saat mengisi form | Draft tersimpan dan pulih setelah login |
| Data korup | Checksum dokumen, backup harian, validasi skema saat tulis |
| Upload file berbahaya | Validasi tipe dan ukuran, antivirus, karantina |

## 15. Privasi dan Kepatuhan

### 15.1 Data pribadi yang dikumpulkan

| Data | Tujuan | Dasar | Masa simpan (usulan) | Lokasi |
| --- | --- | --- | --- | --- |
| Identitas direktur dan pemilik manfaat akhir | KYC/AML | Kewajiban hukum | 5 tahun setelah hubungan berakhir, diverifikasi hukum | Region AWS Singapura (usulan) |
| Email dan nama pengguna | Akun dan notifikasi | Perjanjian layanan | Selama akun aktif | Sama |
| Log aktivitas | Audit dan keamanan | Kepentingan sah dan kewajiban hukum | 5 tahun | Sama |
| Rekening bank | Pelepasan dana | Perjanjian layanan | Disimpan mitra escrow. Platform hanya menyimpan token | Mitra |

Pengguna dapat meminta ekspor dan penghapusan data. Data yang wajib disimpan hukum tetap disimpan dan pengguna diberi tahu. Region dan masa simpan diputuskan di \[D-07\]. Penanggung jawab data pribadi ditunjuk sebelum go-live. Dasar transfer lintas batas untuk data direktur tiga negara dikonfirmasi konsultan hukum.

### 15.2 Matriks regulasi per negara

| Negara | Area yang diperiksa konsultan hukum |
| --- | --- |
| Indonesia | UU PDP, ketenagalistrikan dan ekspor listrik, nilai ekonomi karbon, tanda tangan elektronik dan e-meterai, pajak atas pembayaran lintas negara |
| Singapura | PDPA, layanan pembayaran untuk escrow, pajak karbon dan kredit karbon yang diakui |
| Malaysia | PDPA, sektor energi |
| Vietnam | Perlindungan data pribadi, sektor listrik |
| Laos | Perlindungan data, sektor listrik |

Kerangka ASEAN Power Grid dan standar I-REC menjadi acuan teknis, bukan pengganti regulasi di atas. Opini tertulis per negara selesai sebelum sprint pertama \[D-01\].

## 16. Bisnis

### 16.1 Model pendapatan \[D-08\]

- Fee transaksi energi dari penjual, dipotong dari tiap pelepasan escrow. Usulan 1,0% sampai 2,0%.
- Fee kredit karbon sekali bayar dari penjual, dipotong saat K1.
- Selisih kurs dan langganan laporan kepatuhan pada Fase 3.
- Insentif awal penjual: diskon fee pada 10 listing pertama, angka di D-08.

Fee yang dipotong dari pelepasan membuat kontrak yang dibawa keluar platform kehilangan escrow dan laporan, sehingga insentif menjauh dari platform berkurang.

### 16.2 Alternatif yang dipakai pengguna sekarang

| Alternatif | Kelemahan | Alasan pindah (hipotesis, validasi di D-02) |
| --- | --- | --- |
| Broker atau konsultan energi | Biaya tinggi, tidak transparan | Harga dan listing terlihat langsung |
| Negosiasi bilateral lewat email | Lambat, tanpa verifikasi pihak lawan | KYC sudah selesai, kontrak standar |
| Pembelian sertifikat lewat registri atau bursa | Tidak terkait produksi proyek tertentu | Satu tempat untuk energi dan sertifikat |

Escrow dan tonggak pembayaran menjadi alasan utama penjual menerima pembeli baru tanpa risiko gagal bayar.

### 16.3 Biaya infrastruktur

Estimasi dihitung di Fase 1 dari kalkulator AWS dengan asumsi 50 pengguna aktif. Komponen: compute, RDS, S3, vendor KYC per verifikasi, e-signature per dokumen, fee mitra escrow. Hasil dilampirkan sebagai dasar fee.

### 16.4 Ekonomi unit (asumsi: faktor kapasitas surya 17%, tarif US$0,08/kWh)

| Skenario | Produksi per tahun | Nilai kontrak per tahun | Fee 1% | Fee 2% |
| --- | --- | --- | --- | --- |
| Satu kontrak 4 MW | 5.957 MWh | US$476.544 | US$4.765 | US$9.531 |
| Target 10 MW | 14.892 MWh | US$1.191.360 | US$11.914 | US$23.827 |

Pada target 10 MW, fee transaksi energi hanya sekitar US$12 ribu sampai US$24 ribu per tahun. Angka ini kemungkinan tidak menutup tim, vendor KYC, dan biaya mitra escrow. D-08 wajib menguji empat jalur: fee lebih tinggi, fee listing, fee karbon, dan langganan laporan. Bila tidak ada kombinasi yang menutup biaya, cakupan MVP dikecilkan atau target kapasitas dinaikkan.

## 17. Rilis dan Fase

| Fase | Cakupan | Keluaran | Usulan durasi | Syarat selesai |
| --- | --- | --- | --- | --- |
| 1 | FR-01 sampai FR-06 (notifikasi KYC dan listing), FR-11 (manual), FR-12 | Registrasi, KYC, listing, pencarian, admin, email, audit | 8 minggu | 5 penjual dan 3 pembeli terverifikasi di lingkungan pilot |
| 2 | FR-06 (sisanya), FR-07 sampai FR-11 | Penawaran, kontrak, escrow, top-up, dashboard, transfer sertifikat | 10 minggu | Satu transaksi penuh dari request sampai pelepasan T2 dan T3 dengan 1 pembeli dan 1 penjual |
| 3 | FR-13 sampai FR-15 | Laporan emisi, komentar, SSO, push, REST API ERP | 6 minggu | Laporan diterima 3 pembeli |
| 4 | FR-16 | Prakiraan produksi per proyek | 6 minggu | Syarat masuk: 6 bulan data meter. Akurasi diukur dengan nMAE terhadap kapasitas terpasang pada jam siang |
| 5 | Routing dan alokasi kuota transmisi | Hanya setelah operator jaringan menyediakan API kuota dan kontrak mendukung banyak penjual | Dinilai setelah Fase 4 | Keputusan terpisah |

Durasi adalah usulan dan divalidasi Lead Engineering sebelum kickoff. Tim, budget, dan tanggal go-live diisi di D-09.

### Definition of Ready (per FR sebelum masuk sprint)

1. Kriteria penerimaan tertulis dan punya tiga kasus uji (normal, gagal, batas).
2. Desain layar tersedia. Layar Panel KYC dan moderasi, form penawaran terstruktur, dan alur top-up dirancang sebelum Fase 1 dan 2 dimulai.
3. Dependensi eksternal punya pemilik dan status (D-xx terkait sudah disetujui).
4. Data contoh tersedia (listing, dokumen KYC, data meter) di lingkungan uji.
5. Urutan pengerjaan tidak menunggu FR lain yang belum selesai.

### Peta dependensi utama

FR-01 memblokir FR-02. FR-02 memblokir FR-03. FR-03 dan FR-11 memblokir FR-07. FR-07 memblokir FR-08. FR-08 memblokir FR-09. FR-09 bergantung pada D-06 (mitra escrow) dan sumber meter. FR-10 bergantung pada FR-09.

### Operasional

- Analytics: listing\_created, request\_sent, offer\_accepted, contract\_signed, escrow\_deposited, topup\_missed, escrow\_released.
- Monitoring: error rate, latensi p95, antrian gagal, saldo escrow, alarm kapasitas database, pelepasan lewat SLA.
- Dukungan: email dengan respons 1 hari kerja. Halaman status layanan.
- Rilis: feature flag, deployment blue-green, rollback otomatis bila error rate melewati ambang. Migrasi database punya skrip balik dan diuji di staging.

## 18. Risiko

| Risiko | Dampak | Mitigasi |
| --- | --- | --- |
| Regulasi lintas negara berubah atau melarang model escrow | Kontrak tertunda atau batal | Opini hukum per negara \[D-01\]. Mitra berlisensi \[D-06\]. Klausul renegosiasi |
| Struktur transaksi tidak diizinkan (D-10) | Produk perlu didesain ulang | Putuskan D-10 sebelum kickoff |
| Sertifikat ganda atau tidak diakui | Klaim ditolak auditor | FR-11, status RETIRED, larangan listing ganda |
| Penjual takut dana tertahan lama | Penjual menolak platform | Tabel tonggak, SLA pelepasan 5 hari kerja, metrik 10 |
| Pembeli gagal top-up | Penjual berhenti menyerahkan | Aturan gagal top-up dan hak terminasi |
| Penjual kurang setelah permintaan terikat | Marketplace tidak likuid | Metrik listing aktif. Insentif fee \[D-08\] |
| Kontrak dibawa keluar platform | Pendapatan nol | Fee dari pelepasan. Laporan dan escrow hanya di platform |
| Pendapatan tidak menutup biaya | Platform tidak berkelanjutan | Bagian 16.4 dan D-08 |
| Mitra escrow gagal atau mundur | Transaksi berhenti | Mitra cadangan di D-06 |
| Data meter tidak tersedia | Escrow tidak otomatis | Pelepasan manual empat mata, maksimum 2 periode |
| Skala teknis | Downtime | Uji beban 10 kali lipat |

## 19. Dependensi

- Mitra escrow berlisensi untuk USD \[D-06\].
- Layanan KYC/AML pihak ketiga, misalnya Onfido atau Jumio, yang mendukung dokumen Vietnam dan Laos.
- Penyedia tanda tangan elektronik yang sah di yurisdiksi kontrak, misalnya DocuSign atau penyedia lokal tersertifikasi \[D-05\]. HelloSign (Dropbox Sign) bukan open-source.
- Registri Verra, Gold Standard, dan penerbit I-REC. Fase 1 memakai cek manual admin. Integrasi API menyusul bila tersedia.
- Sumber data meter dari utilitas atau operator jaringan, diverifikasi pihak ketiga.
- Konsultan hukum untuk lima negara.
- Infrastruktur AWS (S3, RDS, compute).
- Penyedia data cuaca dan kuota transmisi hanya untuk Fase 4 dan 5.

## 20. Asumsi dan Validasi

| Asumsi | Cara validasi dalam 1 minggu | Akibat bila salah |
| --- | --- | --- |
| Pembeli mau membeli kontrak surya parsial | Wawancara 5 calon pembeli | Tidak ada permintaan |
| Pembeli menerima struktur produksi plus sertifikat tanpa pengiriman fisik dari platform | Wawancara 5 calon pembeli dan konsultan hukum | Struktur transaksi harus berubah (D-10) |
| Penjual mau menjalani KYC lengkap | Wawancara 5 penjual | Funnel registrasi runtuh |
| Data meter tersedia dari utilitas atau operator | Tanya 3 penjual dan 1 operator | Escrow tidak otomatis |
| Pembeli mau menyetor tagihan satu periode di muka | Wawancara 5 calon pembeli | Penjual menolak risiko gagal bayar |
| Mitra escrow mendukung USD untuk lima negara | Minta penawaran dari 3 mitra | Escrow tidak bisa diluncurkan |
| Pembeli menerima kontrak berbahasa Inggris | Tanya 5 calon pembeli | Biaya terjemahan dan hukum naik |
| Internet pengguna stabil | Cek lokasi 5 calon pengguna | Upload gagal dan sesi putus |
| Vendor KYC mendukung dokumen Vietnam dan Laos | Minta demo vendor | Verifikasi manual membengkak |
| I-REC dapat diterbitkan dan dipindahkan per periode untuk proyek target | Tanya penerbit I-REC dan 3 penjual | Tonggak T3 tidak bisa berjalan |

## 21. Keputusan Terbuka

| ID | Keputusan | Pemilik | Batas waktu |
| --- | --- | --- | --- |
| D-01 | Platform bukan pihak kontrak dan bukan penjual listrik. Opini hukum per negara tentang escrow dan kontrak lintas batas | Konsultan hukum | Sebelum kickoff |
| D-02 | Hasil wawancara pembeli: kewajiban karbon, tonase, produk pengurang, alasan pindah, target kredit karbon | Product Owner | 2 minggu sebelum kickoff |
| D-03 | Persetujuan final tabel tonggak escrow, termasuk persentase 90/10 dan aturan gagal top-up | Pemilik Bisnis dan Konsultan hukum | Sebelum Fase 2 |
| D-04 | Daftar dokumen KYC Vietnam dan Laos | Konsultan hukum lokal | Sebelum Fase 1 |
| D-05 | Template kontrak per yurisdiksi, hukum yang berlaku, penyedia tanda tangan, e-meterai | Konsultan hukum | Sebelum Fase 2 |
| D-06 | Mitra escrow utama dan cadangan | Pemilik Bisnis | Sebelum Fase 2 |
| D-07 | Region data dan masa simpan dokumen KYC | Lead Engineering dan Konsultan hukum | Sebelum Fase 1 |
| D-08 | Persentase fee, pihak penanggung, insentif awal, dan jalur pendapatan tambahan (bagian 16.4) | Pemilik Bisnis | Sebelum Fase 1 |
| D-09 | Tim, budget, dan tanggal go-live | Pemilik Bisnis dan Lead Engineering | Sebelum kickoff |
| D-10 | Struktur transaksi energi: kontrak berbasis produksi plus sertifikat (default), PPA fisik, atau PPA virtual | Pemilik Bisnis dan Konsultan hukum | Sebelum kickoff |

Batas waktu relatif diganti tanggal kalender setelah D-09 disetujui.

## 22. Kriteria Uji untuk Kebutuhan Kunci

| Kebutuhan | Jalur normal | Jalur gagal | Batas nilai |
| --- | --- | --- | --- |
| Reservasi volume (FR-07) | Request 4 MW dari listing 5 MW diterima, volume tersedia menjadi 1 MW | Pembeli kedua meminta 4 MW pada detik yang sama, ditolak dengan pesan sisa 1 MW | Request tepat 5 MW diterima. Request 5,001 MW ditolak |
| Deposit T0 (FR-09) | Deposit masuk pada hari kerja ke-3, kontrak ACTIVE | Tidak ada deposit sampai hari kerja ke-5 berakhir, status EXPIRED\_UNFUNDED dan volume kembali | Deposit tepat di akhir hari kerja ke-5 diterima. Deposit satu menit setelahnya ditolak |
| Pembayaran idempotent | Satu klik bayar menghasilkan satu PAYMENT | Klik ganda atau retry dengan key sama tidak membuat penarikan kedua | Dua key berbeda pada nilai sama tetap menghasilkan dua PAYMENT |
| Pelepasan T2 | Meter 100% volume kontrak melepas 90% nilai dikurangi fee dalam 5 hari kerja | Data meter tidak ada 5 hari kerja, pelepasan manual butuh dua admin berbeda | Meter 95% dan 94,99% menghasilkan pemberitahuan wanprestasi hanya pada yang kedua |
| Top-up (T1) | Top-up masuk 10 hari sebelum periode, tanpa pengingat | Tidak ada top-up 10 hari kerja setelah batas, kontrak SUSPENDED | Top-up tepat pada batas dihitung tepat waktu |
| Counter-offer (FR-07) | Putaran 1 sampai 5 menghasilkan versi baru | Putaran ke-6 ditolak dan penawaran EXPIRED | Penawaran diterima tepat hari ke-7 sah. Hari ke-8 EXPIRED |
| Nomor seri sertifikat (FR-11) | Nomor seri valid dan belum terpakai menjadikan listing ACTIVE | Nomor seri yang sama pada listing kedua ditolak | Nomor seri dengan spasi atau huruf kecil dinormalisasi lalu dicek |
| KYC (FR-02) | Dokumen lengkap dan valid dikeputusan dalam 48 jam | Dokumen kedaluwarsa ditolak otomatis dengan alasan | Keputusan pada jam ke-72 masih dalam batas. Setelahnya masuk antrian eskalasi |
| Ubah rekening | Perubahan dengan autentikasi ulang dan penahanan 48 jam | Perubahan tanpa autentikasi ulang ditolak | Pelepasan pada jam ke-47 tertahan, jam ke-49 berjalan |

## Lampiran: Pemetaan Celah v1.1 ke Perbaikan v1.2

| Celah pada v1.1 | Bagian yang memperbaiki |
| --- | --- |
| G-01 Top-up periode berikutnya tidak ada, deposit hanya satu periode | 9, 11, 12 |
| G-02 Tenggat deposit T0 tidak ada | 8.4, 9, 11 |
| G-03 Siapa mengirim listrik tidak jelas | 1.1, D-10 |
| G-04 Fee tanpa mekanisme pemungutan | 9, 16.1 |
| G-05 Fee 10 MW tidak menutup biaya | 16.4 |
| G-06 Sertifikat atribut energi per periode tidak punya tonggak | 9 (T3), 12 |
| G-07 Siapa memulai transfer karbon tidak jelas | 9 (K1), 10 |
| G-08 Pelepasan manual tanpa kontrol | 5.3, 9 |
| G-09 Counter-offer tanpa batas dan ACCEPTED ambigu | 8.3, 11 |
| G-10 Skema ESCROW satu-satu dengan PAYMENT tidak cocok dengan pelepasan bulanan | 12 |
| G-11 Ubah rekening penjual tidak diatur | 9, 12 |
| G-12 Notifikasi Fase 1 melebihi kebutuhan Fase 1 | 7 (FR-06), 17 |
| G-13 Layar KYC dan negosiasi belum dirancang | 17 (Definition of Ready) |
| G-14 Tidak ada kasus uji tertulis | 22 |
| G-15 Admin Perusahaan tunggal tidak punya pemulihan | 5.1 |
| G-16 Screening sanksi hanya sekali | 8.1 |
