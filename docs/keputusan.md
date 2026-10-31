# Keputusan Produk dan Hukum (D-xx)

Satu baris per keputusan `[D-xx]` berdasarkan kutipan langsung dari PRD:

| Kode | Status | Kutipan Kalimat PRD | Keterangan / Tindak Lanjut |
| --- | --- | --- | --- |
| D-01 | Terbuka | "Platform tidak mengirim listrik, tidak menjadi pihak dalam kontrak, dan bukan bursa. Status ini perlu dikonfirmasi opini hukum [D-01]." | Menunggu konfirmasi opini hukum per negara. Tidak memblokir Fase 1. |
| D-02 | Terbuka | "Product Owner mewawancarai lima calon pembeli dan mencatat jenis kewajiban, tonase, dan produk yang menurunkannya [D-02]." | Target metrik pembeli dan kredit karbon belum final. Tidak memblokir Fase 1. |
| D-03 | Terbuka | "Satu definisi menggantikan semua definisi sebelumnya. Persentase di bawah berlabel usulan sampai D-03 disetujui [D-03]." | Escrow dan tonggak pembayaran adalah fitur Fase 2. |
| D-04 | Terbuka | "Daftar dokumen per negara disimpan di `config/greengrid.php`, bukan di kode. Vietnam dan Laos memakai daftar sementara di atas sampai D-04 selesai." | Menggunakan daftar sementara di `config/greengrid.php`. Terbuka untuk verifikasi hukum lokal. |
| D-05 | Terbuka | "Template mengikuti yurisdiksi kontrak [D-05] ... Penyedia tanda tangan sah di yurisdiksi kontrak, termasuk e-meterai di Indonesia bila berlaku [D-05]." | Template kontrak dan tanda tangan elektronik digital untuk Fase 2. |
| D-06 | Terbuka | "Mitra escrow berlisensi untuk USD [D-06] ... Mitra cadangan di D-06." | Pemilihan mitra escrow berlisensi untuk Fase 2. |
| D-07 | Terbuka | "Region dan masa simpan diputuskan di [D-07]." | Konfigurasi region data dan masa simpan dokumen KYC via config/env. |
| D-08 | Terbuka | "Pengaturan fee disimpan di tabel pengaturan dan tidak dipakai di Fase 1 [D-08]. Nilai awal kosong." | Tabel `settings` telah dibuat dengan nilai awal kosong. Persentase dan model fee final belum diputuskan. |
| D-09 | Terbuka | "Tim, budget, dan tanggal go-live diisi di D-09 ... Batas waktu relatif diganti tanggal kalender setelah D-09 disetujui." | Rencana operasional bisnis, tidak memblokir kode Fase 1. |
| D-10 | Terbuka | "Struktur transaksi energi: kontrak berbasis produksi plus sertifikat (default), PPA fisik, atau PPA virtual [D-10]." | Default MVP: kontrak produksi + sertifikat. Terbuka untuk PPA fisik/virtual lintas batas. |
