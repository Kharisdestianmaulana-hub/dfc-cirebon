# Software Requirements Specification (SRS)
# Aplikasi DFC Cirebon (Dexpress Fried Chicken)

**Versi Dokumen:** 3.0
**Versi Perangkat Lunak:** Laravel 13.35.0
**Tanggal:** 11 Oktober 2026
**Disusun oleh:** Muhammad Arif Triyana (NIM 20251020011)
**Institusi:** Universitas Catur Insan Cendekia
**Pemilik Sistem:** DFC Cirebon (Dexpress Fried Chicken)

---

## Daftar Isi

1. [Pendahuluan](#1-pendahuluan)
2. [Deskripsi Umum](#2-deskripsi-umum)
3. [Kebutuhan Fungsional](#3-kebutuhan-fungsional)
4. [Kebutuhan Non-Fungsional](#4-kebutuhan-non-fungsional)
5. [Perancangan Basis Data](#5-perancangan-basis-data)
6. [Perancangan Arsitektur](#6-perancangan-arsitektur)
7. [Perancangan Antarmuka](#7-perancangan-antarmuka)
8. [Matriks Ketertelusuran](#8-matriks-ketertelusuran)
9. [Lampiran Diagram](#9-lampiran-diagram)
10. [Hasil Verifikasi](#10-hasil-verifikasi)

---

## 1. Pendahuluan

### 1.1 Tujuan Dokumen

Dokumen ini menjelaskan kebutuhan perangkat lunak Aplikasi DFC Cirebon
secara lengkap dan terukur. Dokumen ditujukan bagi pengembang, penguji,
dan pihak akademik yang menilai kesesuaian implementasi dengan rancangan.

Dokumen ini merupakan hasil migrasi dari aplikasi berbasis PHP native
(dengan pola MVC manual) ke kerangka kerja Laravel 13. Seluruh kebutuhan
yang tercatat di sini berasal dari pemetaan fitur aplikasi native, dengan
penambahan penyesuaian struktur basis data yang telah dinormalisasi.

### 1.2 Ruang Lingkup

Aplikasi DFC Cirebon adalah sistem informasi manajemen restoran ayam
goreng yang melayani lima kelompok pengguna:

1. **Pelanggan**, memesan makanan secara daring tanpa akun.
2. **Owner**, pemilik usaha yang memantau dan mengendalikan seluruh operasional.
3. **Admin Gudang**, mengelola persediaan bahan baku di gudang pusat.
4. **Karyawan Outlet**, mengelola penjualan, stok, dan pesanan di outlet.
5. **Mitra**, memantau ketersediaan stok secara waktu nyata.

Selain itu, pelanggan dan karyawan outlet dapat bertukar pesan mengenai
satu pesanan melalui fitur chat pesanan.

### 1.3 Definisi dan Singkatan

| Istilah | Penjelasan |
|---------|-----------|
| DFC | Dexpress Fried Chicken, nama usaha yang dikelola aplikasi |
| Outlet | Gerai penjualan yang menjual produk langsung ke pelanggan |
| Owner | Pemilik usaha dengan hak akses penuh atas seluruh data |
| Gudang Pusat | Tempat penyimpanan bahan baku utama |
| Mitra | Pihak ketiga yang bekerja sama dan dapat memantau stok |
| QRIS | Quick Response Code Indonesian Standard, standar pembayaran kode QR |
| Request Stok | Permohonan penambahan persediaan dari outlet ke gudang pusat |
| Chat Pesanan | Percakapan dua arah antara konsumen dan outlet mengenai satu pesanan |
| MVC | Model, View, Controller, pola arsitektur perangkat lunak |
| SRS | Software Requirements Specification, dokumen spesifikasi kebutuhan |

### 1.4 Referensi

1. Aplikasi PHP native DFC Cirebon (referensi migrasi, folder `app/`, `outlet/`, `mitra/`).
2. Dokumentasi resmi Laravel 13 pada `https://laravel.com/docs`.
3. Dokumentasi Eloquent ORM pada `https://laravel.com/docs/eloquent`.
4. Standar IEEE 830 untuk penulisan spesifikasi kebutuhan perangkat lunak.

---

## 2. Deskripsi Umum

### 2.1 Perspektif Produk

Aplikasi ini merupakan sistem terpadu yang menggabungkan tiga peran
sekaligus: kanal penjualan bagi pelanggan, alat operasional harian bagi
outlet dan gudang, serta alat pemantauan dan pengendalian bagi owner dan
mitra. Seluruh antarmuka dijalankan melalui satu basis kode Laravel dengan
pemisahan wilayah akses berdasarkan middleware.

### 2.2 Karakteristik Pengguna

| Pengguna | Tingkat Keahlian | Frekuensi Pemakaian | Kebutuhan Utama |
|----------|------------------|---------------------|-----------------|
| Pelanggan | Umum, awam teknologi | Sesaat saat memesan | Proses pesan yang cepat dan jelas |
| Owner | Menengah | Harian | Ringkasan kondisi usaha dan kendali penuh |
| Admin Gudang | Menengah | Harian | Pencatatan stok yang akurat |
| Karyawan Outlet | Menengah | Sepanjang jam operasional | Kemudahan input penjualan |
| Mitra | Awam hingga menengah | Berkala | Informasi stok yang mutakhir |

### 2.3 Batasan Sistem

1. Aplikasi berjalan pada peramban web; tidak tersedia aplikasi bergerak khusus.
2. Pembayaran QRIS menampilkan kode statis; verifikasi pembayaran dilakukan manual oleh outlet.
3. Pelanggan tidak perlu membuat akun; pemesanan cukup dengan nama dan nomor telepon.
4. Resolusi peta dan panggilan layanan peta bergantung pada koneksi internet.
5. Unggahan gambar menu dibatasi 4096 KB dan format jpg, jpeg, png, atau webp.

### 2.4 Asumsi dan Ketergantungan

1. Peladen web telah terpasang PHP 8.4 atau lebih baru beserta Composer.
2. Basis data MySQL telah tersedia dan dapat diakses.
3. Aplikasi mengandalkan jaringan CDN untuk pustaka antarmuka.
4. Nomor WhatsApp admin bersifat tetap dan diatur pada berkas konfigurasi.

---

## 3. Kebutuhan Fungsional

Kode kebutuhan memakai pola `KF-<modul>-<nomor>` untuk memudahkan
penelusuran terhadap implementasi.

### 3.1 Modul Publik

| Kode | Kebutuhan | Aktor | Implementasi |
|------|-----------|-------|--------------|
| KF-PUB-01 | Sistem menampilkan halaman beranda berisi ringkasan usaha, produk unggulan, dan ajakan memesan | Pelanggan | `HomeController@index` |
| KF-PUB-02 | Sistem menampilkan daftar menu lengkap beserta harga dan gambar | Pelanggan | `MenuController@index` |
| KF-PUB-03 | Sistem menampilkan daftar dan peta lokasi outlet | Pelanggan | `LokasiController@index` |
| KF-PUB-04 | Sistem menampilkan profil perusahaan meliputi sejarah, visi, dan misi | Pelanggan | `ProfilController@index` |
| KF-PUB-05 | Sistem menampilkan informasi kemitraan dan formulir pengajuan | Pelanggan | `KemitraanController@index` |
| KF-PUB-06 | Sistem menyimpan pengajuan kemitraan yang dikirim pengunjung | Pelanggan | `KemitraanController@kirim` |

### 3.2 Modul Pemesanan

| Kode | Kebutuhan | Aktor | Implementasi |
|------|-----------|-------|--------------|
| KF-PES-01 | Sistem menampilkan katalog menu yang dapat dipilih beserta keranjang | Pelanggan | `PesananController@index` |
| KF-PES-02 | Sistem menolak proses pemesanan apabila keranjang kosong | Pelanggan | `PesananController@index` |
| KF-PES-03 | Sistem menampilkan formulir checkout berisi data pemesan dan pilihan outlet | Pelanggan | `PesananController@checkout` |
| KF-PES-04 | Sistem memvalidasi kelengkapan data pemesan sebelum menyimpan pesanan | Pelanggan | `PesananController@prosesCheckout` |
| KF-PES-05 | Sistem menyimpan pesanan beserta rincian barang dalam format JSON | Pelanggan | `PesananController@prosesCheckout` |
| KF-PES-06 | Sistem mengurangi stok outlet secara otomatis saat pesanan dibuat | Sistem | `PesananController@prosesCheckout` |
| KF-PES-07 | Sistem menghasilkan nomor pesanan unik sebagai identitas transaksi | Sistem | `PesananController@prosesCheckout` |
| KF-PES-08 | Sistem menampilkan halaman pembayaran QRIS berisi kode dan nominal | Pelanggan | `PesananController@qris` |
| KF-PES-09 | Sistem menampilkan struk pesanan yang dapat dicetak | Pelanggan | `PesananController@struk` |

### 3.3 Modul Pelacakan

| Kode | Kebutuhan | Aktor | Implementasi |
|------|-----------|-------|--------------|
| KF-TRK-01 | Sistem melacak pesanan berdasarkan nomor pesanan atau nomor telepon | Pelanggan | `TrackingController@riwayat` |
| KF-TRK-02 | Sistem menampilkan rincian satu pesanan beserta riwayat statusnya | Pelanggan | `TrackingController@detail` |
| KF-TRK-03 | Sistem menampilkan percakapan pesanan dan menerima pesan dari konsumen | Pelanggan | `TrackingController@detail`, `TrackingController@kirimChat` |

### 3.4 Modul Owner

| Kode | Kebutuhan | Aktor | Implementasi |
|------|-----------|-------|--------------|
| KF-OWN-01 | Sistem mengautentikasi owner memakai kredensial pada berkas konfigurasi | Owner | `OwnerController@prosesLogin` |
| KF-OWN-02 | Sistem menampilkan dasbor berisi ringkasan penjualan, stok, dan aktivitas terbaru | Owner | `OwnerController@dashboard` |
| KF-OWN-03 | Sistem menampilkan daftar menu beserta kemampuan tambah, ubah, dan hapus | Owner | `OwnerController@menuIndex` |
| KF-OWN-04 | Sistem menyimpan menu baru beserta unggahan gambarnya | Owner | `OwnerController@menuSimpan` |
| KF-OWN-05 | Sistem menghapus menu beserta berkas gambarnya | Owner | `OwnerController@menuHapus` |
| KF-OWN-06 | Sistem mengelola data lokasi meliputi pusat, outlet, mitra, dan cabang | Owner | `OwnerController@lokasiIndex` |
| KF-OWN-07 | Sistem menampilkan dan mengubah akun karyawan tiap outlet | Owner | `OwnerController@outletKaryawan` |
| KF-OWN-08 | Sistem mengaktifkan outlet baru beserta akun dan stok awalnya | Owner | `OwnerController@outletAktifkan` |
| KF-OWN-09 | Sistem menampilkan dan mengubah data stok gudang | Owner | `OwnerController@stokGudang` |
| KF-OWN-10 | Sistem menampilkan seluruh permintaan stok dari outlet | Owner | `OwnerController@requestStok` |
| KF-OWN-11 | Sistem menyetujui permintaan stok dari outlet | Owner | `OwnerController@requestStokApprove` |
| KF-OWN-12 | Sistem menolak permintaan stok beserta alasannya | Owner | `OwnerController@requestStokTolak` |
| KF-OWN-13 | Sistem menampilkan laporan keuangan seluruh outlet | Owner | `OwnerController@laporanFull` |
| KF-OWN-14 | Sistem menampilkan riwayat penjualan seluruh outlet | Owner | `OwnerController@riwayatPenjualan` |
| KF-OWN-15 | Sistem menampilkan log aktivitas seluruh pengguna | Owner | `OwnerController@logAktivitas` |
| KF-OWN-16 | Sistem menampilkan dan mengubah profil perusahaan serta kredensial gudang | Owner | `OwnerController@profil` |
| KF-OWN-17 | Sistem menampilkan dan menghapus data pengajuan kemitraan | Owner | `OwnerController@kemitraan` |
| KF-OWN-18 | Sistem mengelola akun mitra beserta kata sandinya | Owner | `OwnerController@akunMitra` |

### 3.5 Modul Outlet

| Kode | Kebutuhan | Aktor | Implementasi |
|------|-----------|-------|--------------|
| KF-OUT-01 | Sistem mengautentikasi karyawan outlet memakai akun yang dibuat owner | Karyawan Outlet | `OutletController@prosesLogin` |
| KF-OUT-02 | Sistem menampilkan dasbor outlet berisi stok dan penjualan hari ini | Karyawan Outlet | `OutletController@index` |
| KF-OUT-03 | Sistem mencatat penjualan harian beserta rincian menunya | Karyawan Outlet | `OutletController@simpanPenjualan` |
| KF-OUT-04 | Sistem memperbarui jumlah stok tiap menu di outlet | Karyawan Outlet | `OutletController@updateStok` |
| KF-OUT-05 | Sistem mengubah status toko antara buka dan tutup | Karyawan Outlet | `OutletController@ubahStatus` |
| KF-OUT-06 | Sistem menampilkan pesanan masuk dari pelanggan | Karyawan Outlet | `OutletController@pesananMasuk` |
| KF-OUT-07 | Sistem memperbarui status pesanan pelanggan | Karyawan Outlet | `OutletController@updateStatusPesanan` |
| KF-OUT-08 | Sistem menyimpan laporan keuangan harian outlet | Karyawan Outlet | `OutletController@simpanLaporan` |
| KF-OUT-09 | Sistem mengirim permintaan penambahan stok ke gudang pusat | Karyawan Outlet | `OutletController@kirimRequest` |
| KF-OUT-10 | Sistem menampilkan riwayat permintaan stok beserta statusnya | Karyawan Outlet | `OutletController@riwayatRequest` |
| KF-OUT-11 | Sistem menampilkan percakapan tiap pesanan dan menerima balasan ke konsumen | Karyawan Outlet | `OutletController@pesananMasuk`, `OutletController@kirimChat` |

### 3.6 Modul Gudang

| Kode | Kebutuhan | Aktor | Implementasi |
|------|-----------|-------|--------------|
| KF-GDG-01 | Sistem mengautentikasi admin gudang pusat | Admin Gudang | `GudangController@prosesLogin` |
| KF-GDG-02 | Sistem menampilkan kondisi stok gudang pusat | Admin Gudang | `GudangController@index` |
| KF-GDG-03 | Sistem memperbarui jumlah stok bahan baku | Admin Gudang | `GudangController@update` |
| KF-GDG-04 | Sistem mencatat setiap perubahan stok ke riwayat stok | Sistem | `GudangController@update` |
| KF-GDG-05 | Sistem menampilkan riwayat perubahan stok | Admin Gudang | `GudangController@riwayat` |
| KF-GDG-06 | Sistem menandai bahan baku yang stoknya melewati batas kritis | Sistem | `StokGudang::BATAS_KRITIS` |

### 3.7 Modul Mitra

| Kode | Kebutuhan | Aktor | Implementasi |
|------|-----------|-------|--------------|
| KF-MTR-01 | Sistem mengautentikasi mitra memakai akun yang dibuat owner | Mitra | `MitraController@prosesLogin` |
| KF-MTR-02 | Sistem menampilkan kondisi stok gudang secara waktu nyata | Mitra | `MitraController@index` |
| KF-MTR-03 | Sistem menyediakan titik akhir data stok untuk pembaruan berkala | Mitra | `MitraController@cekStok` |

---

## 4. Kebutuhan Non-Fungsional

| Kode | Kategori | Kebutuhan |
|------|----------|-----------|
| KNF-01 | Keamanan | Kata sandi disimpan dalam bentuk hash memakai bcrypt |
| KNF-02 | Keamanan | Setiap permintaan pengubah data dilindungi token CSRF |
| KNF-03 | Keamanan | Setiap wilayah akses dijaga middleware tersendiri sesuai perannya |
| KNF-04 | Keamanan | Berkas konfigurasi kredensial tidak disertakan ke repositori |
| KNF-05 | Kinerja | Halaman publik tampil dalam waktu kurang dari tiga detik pada koneksi standar |
| KNF-06 | Kinerja | Query basis data memakai indeks pada kolom yang sering disaring |
| KNF-07 | Kegunaan | Antarmuka responsif dan nyaman dipakai pada layar ponsel maupun komputer |
| KNF-08 | Kegunaan | Setiap aksi penting memberi umpan balik berupa pesan notifikasi |
| KNF-09 | Pemeliharaan | Seluruh kode berorientasi objek dengan pemisahan tanggung jawab yang jelas |
| KNF-10 | Pemeliharaan | Penamaan variabel, komentar, dan dokumentasi memakai bahasa Indonesia |
| KNF-11 | Portabilitas | Aplikasi dapat berjalan pada lingkungan Linux maupun Windows |
| KNF-12 | Reliabilitas | Operasi pengubah stok dijalankan dalam transaksi basis data |
| KNF-13 | Keterlacakan | Setiap perubahan stok dan aktivitas penting tercatat di log |

---

## 5. Perancangan Basis Data

### 5.1 Daftar Tabel

Sistem memiliki 26 tabel. Tujuh belas tabel merupakan tabel aplikasi,
sedangkan sembilan tabel sisanya adalah tabel bawaan Laravel untuk
keperluan teknis (users, password_reset_tokens, sessions, cache,
cache_locks, jobs, job_batches, failed_jobs, migrations).

| No | Nama Tabel | Fungsi | Kunci Utama | Kunci Unik |
|----|-----------|--------|-------------|-----------|
| 1 | lokasi | Menyimpan data pusat, outlet, mitra, dan cabang | id | - |
| 2 | menu | Menyimpan daftar produk beserta harga | id | - |
| 3 | admin | Akun karyawan outlet | id | username |
| 4 | admin_gudang | Akun admin gudang pusat | id | username |
| 5 | stok_outlet | Jumlah stok tiap menu di tiap outlet | id | menu_id, kode_outlet |
| 6 | pengaturan_toko | Status buka tutup dan kontak tiap outlet | id | kode_outlet |
| 7 | transaksi_pesanan | Data pesanan pelanggan | id | order_id |
| 8 | penjualan_harian | Rincian penjualan harian outlet | id | - |
| 9 | laporan_keuangan | Rekapitulasi keuangan harian tiap outlet | id | tanggal, kode_outlet |
| 10 | stok_gudang | Persediaan bahan baku gudang pusat | id | - |
| 11 | riwayat_stok | Jejak perubahan stok gudang | id | - |
| 12 | request_stok | Permintaan penambahan stok dari outlet | id | - |
| 13 | log_aktivitas | Catatan aktivitas pengguna | id | - |
| 14 | pengajuan_mitra | Pengajuan kerja sama dari pengunjung | id | - |
| 15 | akun_mitra | Akun login portal mitra | id | username |
| 16 | profil_perusahaan | Sejarah, visi, dan misi perusahaan | id | - |
| 17 | chat_pesanan | Percakapan pesanan konsumen dan outlet | id | - |

### 5.2 Normalisasi Struktur Stok

Pada aplikasi native, stok tiap outlet disimpan sebagai kolom dinamis
pada satu tabel dengan penamaan `stok_<username>`. Pola ini menimbulkan
tiga kelemahan: struktur tabel berubah setiap kali outlet baru dibuka,
sulit disaring dengan query yang rapi, dan tidak mendukung relasi antar
tabel.

Pada versi Laravel, struktur tersebut dinormalisasi menjadi tabel
`stok_outlet` dengan kolom `menu_id`, `kode_outlet`, dan `stok`, serta
batasan unik pada pasangan `menu_id` dan `kode_outlet`. Hasilnya:

1. Penambahan outlet baru tidak mengubah struktur tabel.
2. Pencarian dan penyaringan stok menjadi query biasa tanpa nama kolom dinamis.
3. Hubungan antara menu dan outlet terbentuk sebagai relasi yang jelas.

### 5.3 Relasi Antar Tabel

| Relasi | Tipe | Keterangan |
|--------|------|-----------|
| menu ke stok_outlet | Satu ke banyak | Satu menu memiliki stok di banyak outlet |
| lokasi ke admin | Satu ke satu | Satu outlet memiliki satu akun karyawan |
| lokasi ke pengaturan_toko | Satu ke satu | Satu outlet memiliki satu pengaturan toko |
| pengaturan_toko ke request_stok | Satu ke banyak | Satu outlet dapat mengirim banyak permintaan |
| stok_gudang ke riwayat_stok | Satu ke banyak | Satu bahan baku memiliki banyak catatan perubahan |
| stok_gudang ke request_stok | Satu ke banyak | Satu bahan baku dapat diminta berkali-kali |
| pengajuan_mitra ke akun_mitra | Satu ke satu | Pengajuan yang disetujui menjadi akun mitra |
| transaksi_pesanan ke chat_pesanan | Satu ke banyak | Satu pesanan memiliki banyak pesan percakapan |

---

## 6. Perancangan Arsitektur

### 6.1 Pola Arsitektur

Aplikasi menerapkan pola MVC yang disediakan Laravel, dengan tambahan
lapisan layanan untuk logika yang tidak layak diletakkan di controller.

Alur permintaan berjalan sebagai berikut:

1. Peramban mengirim permintaan HTTP.
2. Router memetakan alamat ke route bernama yang sesuai.
3. Middleware memeriksa hak akses dan keabsahan token CSRF.
4. Controller memanggil lapisan layanan atau model untuk mengolah data.
5. Model berkomunikasi dengan basis data melalui Eloquent ORM.
6. Controller mengirim data ke view Blade untuk dirender.
7. View yang telah dirender dikirim kembali ke peramban.

### 6.2 Pembagian Wilayah Akses

| Wilayah | Middleware | Guard | Jumlah Route |
|---------|-----------|-------|--------------|
| Publik | - | - | 18 |
| Gudang | jaga.gudang | gudang | 6 |
| Outlet | jaga.outlet | outlet | 14 |
| Mitra | jaga.mitra | mitra | 5 |
| Owner | jaga.owner | penanda sesi | 35 |

Total terdapat 78 route bernama.

### 6.3 Struktur Direktori

```
laravel-dfc/
├── app/
│   ├── Http/
│   │   ├── Controllers/      11 controller
│   │   └── Middleware/       4 middleware penjaga
│   ├── Models/               17 model Eloquent
│   └── Support/              kelas bantuan
├── config/
│   ├── auth.php              4 guard, 4 provider
│   └── dfc.php               konfigurasi khusus aplikasi
├── database/
│   ├── migrations/           21 migrasi
│   ├── seeders/              seeder data asli DFC Cirebon
│   └── schema_reference_native.sql
├── docs/
│   ├── SRS.md
│   ├── ERD.md
│   ├── diagram/              5 berkas draw.io
│   └── screenshot/           tangkapan layar dan logo
├── resources/views/
│   ├── layouts/              5 layout induk
│   ├── publik/               11 tampilan
│   ├── gudang/               3 tampilan
│   ├── outlet/               5 tampilan
│   ├── mitra/                2 tampilan
│   └── owner/                16 tampilan
└── routes/web.php            78 route bernama
```

---

## 7. Perancangan Antarmuka

### 7.1 Prinsip Perancangan

1. Tampilan versi Laravel dipertahankan setia pada versi native agar pengguna yang telah terbiasa tidak perlu menyesuaikan diri.
2. Setiap wilayah akses memakai warna aksen yang berbeda agar pengguna tidak keliru mengenali posisinya.
3. Seluruh ikon memakai pustaka vektor, bukan emoji, agar tampilan konsisten di semua peramban.
4. Setiap aksi yang mengubah data menampilkan dialog konfirmasi terlebih dahulu.

### 7.2 Palet Warna per Wilayah

| Wilayah | Warna Utama | Kode | Penggunaan |
|---------|-------------|------|-----------|
| Publik | Biru | #2563eb | Tombol utama, tautan, aksen |
| Gudang | Merah | #dc2626 | Penanda stok kritis |
| Outlet | Hijau | #059669 | Status buka dan penjualan |
| Mitra | Ungu | #7c3aed | Kartu informasi stok |
| Owner | Kuning tua | #d97706 | Menu samping dan aksen panel |

### 7.3 Daftar Halaman

| No | Wilayah | Halaman | Berkas Blade |
|----|---------|---------|--------------|
| 1 | Publik | Beranda | publik/home.blade.php |
| 2 | Publik | Daftar menu | publik/menu.blade.php |
| 3 | Publik | Lokasi dan peta | publik/lokasi.blade.php |
| 4 | Publik | Profil perusahaan | publik/profil.blade.php |
| 5 | Publik | Kemitraan | publik/kemitraan.blade.php |
| 6 | Publik | Katalog pesan | publik/pesanan/index.blade.php |
| 7 | Publik | Checkout | publik/pesanan/checkout.blade.php |
| 8 | Publik | Pembayaran QRIS | publik/pesanan/qris.blade.php |
| 9 | Publik | Struk pesanan | publik/pesanan/struk.blade.php |
| 10 | Publik | Riwayat pelacakan | publik/tracking/riwayat.blade.php |
| 11 | Publik | Rincian pelacakan | publik/tracking/detail.blade.php |
| 12 | Gudang | Login gudang | gudang/login.blade.php |
| 13 | Gudang | Dasbor stok gudang | gudang/index.blade.php |
| 14 | Gudang | Riwayat stok | gudang/riwayat.blade.php |
| 15 | Outlet | Login outlet | outlet/login.blade.php |
| 16 | Outlet | Dasbor outlet | outlet/index.blade.php |
| 17 | Outlet | Pesanan masuk | outlet/pesanan_masuk.blade.php |
| 18 | Outlet | Laporan keuangan | outlet/laporan.blade.php |
| 19 | Outlet | Riwayat request | outlet/riwayat_request.blade.php |
| 20 | Mitra | Login mitra | mitra/login.blade.php |
| 21 | Mitra | Portal stok mitra | mitra/index.blade.php |
| 22 | Owner | Login owner | owner/login.blade.php |
| 23 | Owner | Dasbor | owner/dashboard.blade.php |
| 24 | Owner | Kelola menu | owner/menu/index.blade.php |
| 25 | Owner | Form menu | owner/menu/form.blade.php |
| 26 | Owner | Kelola lokasi | owner/lokasi/index.blade.php |
| 27 | Owner | Form lokasi | owner/lokasi/form.blade.php |
| 28 | Owner | Akun karyawan outlet | owner/outlet_karyawan.blade.php |
| 29 | Owner | Stok gudang | owner/stok_gudang.blade.php |
| 30 | Owner | Request stok | owner/request_stok.blade.php |
| 31 | Owner | Atur gudang | owner/atur_gudang.blade.php |
| 32 | Owner | Laporan keuangan | owner/laporan.blade.php |
| 33 | Owner | Riwayat penjualan | owner/riwayat_penjualan.blade.php |
| 34 | Owner | Log aktivitas | owner/log_aktivitas.blade.php |
| 35 | Owner | Profil perusahaan | owner/profil.blade.php |
| 36 | Owner | Kemitraan | owner/kemitraan.blade.php |
| 37 | Owner | Akun mitra | owner/akun_mitra.blade.php |

Antarmuka chat pesanan tidak berdiri sebagai halaman tersendiri, melainkan
menyatu di dalam halaman rincian pelacakan untuk konsumen dan halaman
pesanan masuk untuk outlet.

---

## 8. Matriks Ketertelusuran

Matriks ini menunjukkan bahwa setiap kebutuhan fungsional memiliki
implementasi pada controller dan tampilan yang sesuai.

| Kode | Controller | Tampilan |
|------|-----------|----------|
| KF-PUB-01 | HomeController@index | publik/home |
| KF-PUB-02 | MenuController@index | publik/menu |
| KF-PUB-03 | LokasiController@index | publik/lokasi |
| KF-PUB-04 | ProfilController@index | publik/profil |
| KF-PUB-05 | KemitraanController@index | publik/kemitraan |
| KF-PUB-06 | KemitraanController@kirim | - |
| KF-PES-01 | PesananController@index | publik/pesanan/index |
| KF-PES-03 | PesananController@checkout | publik/pesanan/checkout |
| KF-PES-04 | PesananController@prosesCheckout | - |
| KF-PES-08 | PesananController@qris | publik/pesanan/qris |
| KF-PES-09 | PesananController@struk | publik/pesanan/struk |
| KF-TRK-01 | TrackingController@riwayat | publik/tracking/riwayat |
| KF-TRK-02 | TrackingController@detail | publik/tracking/detail |
| KF-TRK-03 | TrackingController@kirimChat | publik/tracking/detail |
| KF-OWN-02 | OwnerController@dashboard | owner/dashboard |
| KF-OWN-03 | OwnerController@menuIndex | owner/menu/index |
| KF-OWN-06 | OwnerController@lokasiIndex | owner/lokasi/index |
| KF-OWN-07 | OwnerController@outletKaryawan | owner/outlet_karyawan |
| KF-OWN-09 | OwnerController@stokGudang | owner/stok_gudang |
| KF-OWN-10 | OwnerController@requestStok | owner/request_stok |
| KF-OWN-13 | OwnerController@laporanFull | owner/laporan |
| KF-OWN-14 | OwnerController@riwayatPenjualan | owner/riwayat_penjualan |
| KF-OWN-15 | OwnerController@logAktivitas | owner/log_aktivitas |
| KF-OWN-16 | OwnerController@profil | owner/profil |
| KF-OWN-17 | OwnerController@kemitraan | owner/kemitraan |
| KF-OWN-18 | OwnerController@akunMitra | owner/akun_mitra |
| KF-OUT-02 | OutletController@index | outlet/index |
| KF-OUT-06 | OutletController@pesananMasuk | outlet/pesanan_masuk |
| KF-OUT-08 | OutletController@simpanLaporan | outlet/laporan |
| KF-OUT-10 | OutletController@riwayatRequest | outlet/riwayat_request |
| KF-OUT-11 | OutletController@kirimChat | outlet/pesanan_masuk |
| KF-GDG-02 | GudangController@index | gudang/index |
| KF-GDG-05 | GudangController@riwayat | gudang/riwayat |
| KF-MTR-02 | MitraController@index | mitra/index |

---

## 9. Lampiran Diagram

Seluruh diagram berikut dibuat dengan draw.io dan tersimpan pada
direktori `docs/diagram/`. Berkas dapat dibuka melalui aplikasi
draw.io atau situs `https://app.diagrams.net`.

| No | Nama Diagram | Berkas | Kegunaan |
|----|--------------|--------|----------|
| 1 | ERD DFC Cirebon | erd-dfc-cirebon.drawio | Hubungan antar tabel basis data |
| 2 | Use Case Diagram | usecase-dfc-cirebon.drawio | Interaksi aktor dengan sistem |
| 3 | Diagram Arsitektur | arsitektur-laravel.drawio | Susunan lapisan aplikasi Laravel |
| 4 | Flowchart Alur Pemesanan | flowchart-alur-pesanan.drawio | Urutan proses pemesanan pelanggan |
| 5 | Flowchart Request Stok | flowchart-request-stok.drawio | Alur pengajuan dan persetujuan stok |

Penjelasan rinci ERD beserta rujukan tabelnya tersedia pada dokumen
`docs/ERD.md`.

---

## 10. Hasil Verifikasi

Seluruh kebutuhan fungsional telah diuji melalui empat skrip pengujian
otomatis yang disertakan bersama kode sumber. Pengujian dijalankan
terhadap basis data MySQL yang sudah berisi data asli DFC Cirebon.

| Skrip | Cakupan | Hasil |
|-------|---------|-------|
| uji_paritas.php | 33 route GET pada kelima wilayah | 33 lolos, 0 gagal |
| uji_login.php | 25 pemeriksaan autentikasi keempat wilayah | 25 lolos, 0 gagal |
| uji_fungsional.php | 15 pemeriksaan alur tulis data | 15 lolos, 0 gagal |
| uji_chat.php | 17 pemeriksaan fitur chat pesanan | 17 lolos, 0 gagal |

Total 90 pemeriksaan lolos tanpa kegagalan.

Pemeriksaan tambahan:

1. Seluruh 74 berkas PHP lolos pemeriksaan sintaks.
2. Seluruh 42 berkas Blade lolos pemeriksaan sintaks.
3. 21 migrasi berjalan tanpa kesalahan dan pengisian data asli berhasil.
4. Perlindungan CSRF terbukti aktif pada kondisi operasi normal.
5. Relasi antar tabel terverifikasi melalui pengujian model.
6. Jumlah baris seluruh tabel aplikasi cocok dengan basis data asli
   DFC Cirebon, tanpa sisa data pengujian.

---

Dokumen ini disusun sebagai bagian dari pemenuhan tugas Ujian Tengah
Semester dan Ujian Akhir Semester. Seluruh isi dokumen mengikuti kaidah
penulisan bahasa Indonesia yang baku dan menggunakan penomoran bertingkat.
