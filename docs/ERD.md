# Entity Relationship Diagram (ERD)
# Aplikasi DFC Cirebon (Dexpress Fried Chicken)

**Versi Dokumen:** 3.0
**Tanggal:** 11 Oktober 2026
**Berkas Diagram:** `docs/diagram/erd-dfc-cirebon.drawio`

---

## Daftar Isi

1. [Pendahuluan](#1-pendahuluan)
2. [Ringkasan Entitas](#2-ringkasan-entitas)
3. [Rincian Struktur Tabel](#3-rincian-struktur-tabel)
4. [Hubungan Antar Tabel](#4-hubungan-antar-tabel)
5. [Normalisasi Struktur Stok](#5-normalisasi-struktur-stok)
6. [Diagram Physical](#6-diagram-physical)
7. [Kamus Data Ringkas](#7-kamus-data-ringkas)

---

## 1. Pendahuluan

Dokumen ini menjelaskan struktur basis data aplikasi DFC Cirebon
secara rinci. Diagram disusun memakai kaidah Entity Relationship
Diagram dengan notasi Chen yang dimodifikasi untuk menampilkan
jenis kunci dan jenis hubungan antar tabel.

Basis data bernama `dfc_db` dan berisi 26 tabel, terdiri atas 17 tabel
aplikasi dan 9 tabel teknis bawaan Laravel. Struktur ini sudah disamakan
dengan basis data asli DFC Cirebon, termasuk kolom dan tabel yang
sebelumnya belum dimigrasikan.

### 1.1 Cara Membaca Diagram

| Simbol | Arti |
|--------|------|
| PK | Primary Key, kunci utama yang unik untuk setiap baris |
| FK | Foreign Key, kunci penghubung ke tabel lain |
| UQ | Unique, nilai tidak boleh berulang |
| IDX | Indeks, mempercepat pencarian |
| Garis penuh | Relasi fisik yang ditegakkan oleh basis data |
| Garis putus-putus | Relasi logis berbasis kode, tidak ditegakkan basis data |
| 1 : 1 | Satu baris berpasangan dengan tepat satu baris lain |
| 1 : N | Satu baris berpasangan dengan banyak baris lain |

### 1.2 Konvensi Penamaan

1. Nama tabel memakai huruf kecil dan garis bawah, dalam bentuk tunggal.
2. Setiap tabel memiliki kolom `id` sebagai kunci utama.
3. Nama tabel yang dihubungkan dijadikan awalan pada kunci tamu, contoh `menu_id`.
4. Setiap tabel aplikasi memiliki kolom `created_at` dan `updated_at` sebagai penanda waktu.

---

## 2. Ringkasan Entitas

Entitas dikelompokkan menjadi lima kelompok fungsional.

### 2.1 Kelompok Akun dan Hak Akses

| Entitas | Fungsi | Jumlah Baris Terduga |
|---------|--------|---------------------|
| admin | Akun karyawan yang mengelola outlet | Sedikit, sebanding jumlah outlet |
| admin_gudang | Akun admin gudang pusat | Satu baris |
| akun_mitra | Akun login portal mitra | Sebanding jumlah mitra |

Tiga entitas akun dipisah menjadi tabel tersendiri agar setiap peran
memiliki jalur autentikasi yang terisolasi. Pemisahan ini juga
memudahkan pemberian hak akses yang berbeda tanpa saling memengaruhi.

### 2.2 Kelompok Produk dan Persediaan

| Entitas | Fungsi | Sifat Perubahan |
|---------|--------|-----------------|
| menu | Daftar produk beserta harga | Jarang berubah |
| stok_outlet | Jumlah tiap produk di tiap outlet | Sering berubah |
| stok_gudang | Persediaan bahan baku gudang pusat | Sering berubah |
| riwayat_stok | Jejak setiap perubahan stok gudang | Hanya bertambah |

### 2.3 Kelompok Transaksi

| Entitas | Fungsi | Sifat Perubahan |
|---------|--------|-----------------|
| transaksi_pesanan | Pesanan yang dibuat pelanggan | Berubah status |
| chat_pesanan | Percakapan pesanan antara konsumen dan outlet | Hanya bertambah |
| penjualan_harian | Rincian penjualan per menu | Hanya bertambah |
| laporan_keuangan | Rekapitulasi keuangan harian | Satu baris per hari per outlet |

### 2.4 Kelompok Operasional

| Entitas | Fungsi | Sifat Perubahan |
|---------|--------|-----------------|
| lokasi | Data pusat, outlet, mitra, dan cabang | Jarang berubah |
| pengaturan_toko | Status buka tutup dan kontak outlet | Berubah harian |
| request_stok | Permintaan penambahan stok | Berubah status |
| log_aktivitas | Catatan aktivitas pengguna | Hanya bertambah |

### 2.5 Kelompok Konten dan Kemitraan

| Entitas | Fungsi | Sifat Perubahan |
|---------|--------|-----------------|
| pengajuan_mitra | Pengajuan kerja sama dari pengunjung | Hanya bertambah |
| profil_perusahaan | Sejarah, visi, dan misi | Jarang berubah |

---

## 3. Rincian Struktur Tabel

### 3.1 Tabel `lokasi`

Menyimpan seluruh titik usaha, baik pusat, outlet, mitra, maupun cabang.

| Kolom | Tipe | Kunci | Keterangan |
|-------|------|-------|-----------|
| id | BIGINT | PK | Penomoran otomatis |
| nama | VARCHAR(150) | | Nama lokasi |
| alamat | TEXT | | Alamat lengkap |
| map_query | TEXT | | Kata kunci pencarian pada peta |
| kategori | ENUM | | Pusat, Outlet, Mitra, atau Cabang |
| latitude | VARCHAR(50) | | Koordinat lintang untuk peta |
| longitude | VARCHAR(50) | | Koordinat bujur untuk peta |
| telepon | VARCHAR(30) | | Nomor telepon lokasi |
| username_admin | VARCHAR(100) | FK | Menghubungkan ke akun karyawan |
| mitra_username | VARCHAR(50) | | Nama pengguna mitra pemilik lokasi |
| qris_image | VARCHAR(255) | | Nama berkas gambar QRIS lokasi |
| created_at | TIMESTAMP | | Waktu baris dibuat |
| updated_at | TIMESTAMP | | Waktu baris diperbarui |

Kolom `map_query`, `mitra_username`, dan `qris_image` disamakan dengan
basis data asli agar tidak ada data yang hilang saat pemindahan.

### 3.2 Tabel `menu`

Menyimpan daftar produk yang dijual.

| Kolom | Tipe | Kunci | Keterangan |
|-------|------|-------|-----------|
| id | BIGINT | PK | Penomoran otomatis |
| nama | VARCHAR(150) | | Nama produk |
| harga | INT | | Harga satuan dalam rupiah |
| gambar | VARCHAR(255) | | Nama berkas gambar |
| kategori | VARCHAR(50) | | Kelompok produk |
| pemilik_menu | VARCHAR(50) | | Pemilik menu, bawaan pusat |
| created_at | TIMESTAMP | | Waktu baris dibuat |
| updated_at | TIMESTAMP | | Waktu baris diperbarui |

### 3.3 Tabel `admin`

Akun karyawan yang mengelola outlet.

| Kolom | Tipe | Kunci | Keterangan |
|-------|------|-------|-----------|
| id | BIGINT | PK | Penomoran otomatis |
| username | VARCHAR(100) | UQ | Nama pengguna untuk login |
| password | VARCHAR(255) | | Kata sandi ter-hash |
| nama_outlet | VARCHAR(100) | | Nama tampilan outlet |
| kode_outlet | VARCHAR(50) | | Kode outlet yang dikelola |
| nomor_wa | VARCHAR(20) | | Nomor WhatsApp outlet |
| created_at | TIMESTAMP | | Waktu baris dibuat |
| updated_at | TIMESTAMP | | Waktu baris diperbarui |

### 3.4 Tabel `admin_gudang`

Akun admin gudang pusat, hanya berisi satu baris.

| Kolom | Tipe | Kunci | Keterangan |
|-------|------|-------|-----------|
| id | BIGINT | PK | Penomoran otomatis |
| username | VARCHAR(100) | UQ | Nama pengguna untuk login |
| password | VARCHAR(255) | | Kata sandi ter-hash |
| nama_lengkap | VARCHAR(100) | | Nama lengkap admin gudang |
| created_at | TIMESTAMP | | Waktu baris dibuat |
| updated_at | TIMESTAMP | | Waktu baris diperbarui |

### 3.5 Tabel `stok_outlet`

Menyimpan jumlah tiap produk di tiap outlet. Tabel ini merupakan hasil
normalisasi dari struktur kolom dinamis pada versi native.

| Kolom | Tipe | Kunci | Keterangan |
|-------|------|-------|-----------|
| id | BIGINT | PK | Penomoran otomatis |
| menu_id | BIGINT | FK | Merujuk ke tabel menu |
| kode_outlet | VARCHAR(100) | FK | Kode outlet, merujuk ke username admin |
| stok | INT | | Jumlah persediaan |
| created_at | TIMESTAMP | | Waktu baris dibuat |
| updated_at | TIMESTAMP | | Waktu baris diperbarui |

Batasan unik gabungan pada `menu_id` dan `kode_outlet` menjamin bahwa
satu produk hanya memiliki satu baris stok pada satu outlet.

### 3.6 Tabel `pengaturan_toko`

Menyimpan pengaturan operasional tiap outlet.

| Kolom | Tipe | Kunci | Keterangan |
|-------|------|-------|-----------|
| id | BIGINT | PK | Penomoran otomatis |
| kode_outlet | VARCHAR(100) | FK | Kode outlet |
| nama_outlet | VARCHAR(150) | | Nama tampilan outlet |
| status | ENUM | | buka atau tutup |
| no_hp | VARCHAR(30) | | Nomor WhatsApp outlet |
| created_at | TIMESTAMP | | Waktu baris dibuat |
| updated_at | TIMESTAMP | | Waktu baris diperbarui |

### 3.7 Tabel `transaksi_pesanan`

Menyimpan pesanan yang dibuat pelanggan.

| Kolom | Tipe | Kunci | Keterangan |
|-------|------|-------|-----------|
| id | BIGINT | PK | Penomoran otomatis |
| order_id | VARCHAR(50) | UQ | Nomor pesanan unik |
| nama_pemesan | VARCHAR(150) | | Nama pelanggan |
| no_hp | VARCHAR(30) | IDX | Nomor telepon untuk pelacakan |
| kode_outlet | VARCHAR(100) | FK | Outlet yang melayani |
| total_bayar | DECIMAL(15,2) | | Jumlah yang harus dibayar |
| metode_bayar | VARCHAR(20) | | Cara pembayaran |
| detail_pesanan | LONGTEXT | | Rincian barang dalam format JSON |
| status_pesanan | VARCHAR(50) | IDX | Status terkini pesanan |
| waktu_pesan | TIMESTAMP | | Waktu pesanan dibuat |
| created_at | TIMESTAMP | | Waktu baris dibuat |
| updated_at | TIMESTAMP | | Waktu baris diperbarui |

### 3.8 Tabel `penjualan_harian`

Menyimpan rincian penjualan per menu pada tiap hari.

| Kolom | Tipe | Kunci | Keterangan |
|-------|------|-------|-----------|
| id | BIGINT | PK | Penomoran otomatis |
| tanggal | DATE | IDX | Tanggal penjualan |
| waktu | TIME | | Jam penjualan |
| kode_outlet | VARCHAR(100) | FK, IDX | Outlet penjual |
| nama_menu | VARCHAR(150) | | Nama produk terjual |
| qty | INT | | Jumlah terjual |
| catatan | VARCHAR(255) | | Catatan tambahan |
| harga_satuan | DECIMAL(15,2) | | Harga per satuan |
| subtotal | DECIMAL(15,2) | | Hasil kali harga dan jumlah |
| created_at | TIMESTAMP | | Waktu baris dibuat |
| updated_at | TIMESTAMP | | Waktu baris diperbarui |

### 3.9 Tabel `laporan_keuangan`

Menyimpan rekapitulasi keuangan harian tiap outlet.

| Kolom | Tipe | Kunci | Keterangan |
|-------|------|-------|-----------|
| id | BIGINT | PK | Penomoran otomatis |
| tanggal | DATE | IDX | Tanggal laporan |
| kode_outlet | VARCHAR(100) | FK | Outlet pelapor |
| omzet_kotor | DECIMAL(15,2) | | Pendapatan sebelum pengeluaran |
| pengeluaran | DECIMAL(15,2) | | Biaya operasional |
| omzet_bersih | DECIMAL(15,2) | | Selisih pendapatan dan biaya |
| catatan | TEXT | | Keterangan tambahan |
| waktu_input | TIME | | Jam laporan dibuat |
| created_at | TIMESTAMP | | Waktu baris dibuat |
| updated_at | TIMESTAMP | | Waktu baris diperbarui |

Batasan unik gabungan pada `tanggal` dan `kode_outlet` menjamin satu
outlet hanya memiliki satu laporan pada satu tanggal.

### 3.10 Tabel `stok_gudang`

Menyimpan persediaan bahan baku gudang pusat.

| Kolom | Tipe | Kunci | Keterangan |
|-------|------|-------|-----------|
| id | BIGINT | PK | Penomoran otomatis |
| mitra_username | VARCHAR(100) | | Mitra pemilik baris, kosong bila milik pusat |
| kategori | VARCHAR(50) | | Kelompok bahan baku |
| nama_barang | VARCHAR(150) | | Nama bahan baku |
| stok | INT | | Jumlah persediaan |
| satuan | VARCHAR(30) | | Satuan ukur, misalnya kg |
| created_at | TIMESTAMP | | Waktu baris dibuat |
| updated_at | TIMESTAMP | | Waktu baris diperbarui |

### 3.11 Tabel `riwayat_stok`

Mencatat setiap perubahan stok gudang agar dapat ditelusuri.

| Kolom | Tipe | Kunci | Keterangan |
|-------|------|-------|-----------|
| id | BIGINT | PK | Penomoran otomatis |
| mitra_username | VARCHAR(100) | | Mitra terkait perubahan, bila ada |
| nama_barang | VARCHAR(150) | FK | Bahan baku yang berubah |
| stok_lama | INT | | Jumlah sebelum perubahan |
| stok_baru | INT | | Jumlah setelah perubahan |
| selisih | INT | | Beda antara stok baru dan lama |
| admin | VARCHAR(100) | | Pelaku perubahan |
| waktu | TIMESTAMP | | Waktu perubahan terjadi |
| created_at | TIMESTAMP | | Waktu baris dibuat |
| updated_at | TIMESTAMP | | Waktu baris diperbarui |

### 3.12 Tabel `request_stok`

Menyimpan permintaan penambahan stok dari outlet ke gudang pusat.

| Kolom | Tipe | Kunci | Keterangan |
|-------|------|-------|-----------|
| id | BIGINT | PK | Penomoran otomatis |
| nama_outlet | VARCHAR(150) | FK | Outlet pemohon |
| nama_barang | VARCHAR(150) | FK | Bahan baku yang diminta |
| jumlah | INT | | Banyaknya permintaan |
| satuan | VARCHAR(30) | | Satuan ukur |
| status | VARCHAR(30) | | Pending, Disetujui, atau Ditolak |
| alasan_tolak | VARCHAR(255) | | Alasan bila permintaan ditolak |
| waktu_request | TIMESTAMP | | Waktu permintaan dikirim |
| created_at | TIMESTAMP | | Waktu baris dibuat |
| updated_at | TIMESTAMP | | Waktu baris diperbarui |

### 3.13 Tabel `log_aktivitas`

Mencatat aktivitas penting pengguna untuk keperluan audit.

| Kolom | Tipe | Kunci | Keterangan |
|-------|------|-------|-----------|
| id | BIGINT | PK | Penomoran otomatis |
| waktu | DATETIME | | Saat aktivitas terjadi |
| pelaku | VARCHAR(100) | | Pengguna yang beraktivitas |
| aksi | VARCHAR(100) | | Jenis aktivitas |
| detail | TEXT | | Keterangan rinci |
| created_at | TIMESTAMP | | Waktu baris dibuat |
| updated_at | TIMESTAMP | | Waktu baris diperbarui |

### 3.14 Tabel `pengajuan_mitra`

Menyimpan pengajuan kerja sama yang dikirim melalui halaman kemitraan.

| Kolom | Tipe | Kunci | Keterangan |
|-------|------|-------|-----------|
| id | BIGINT | PK | Penomoran otomatis |
| nama | VARCHAR(150) | | Nama pengaju |
| whatsapp | VARCHAR(30) | | Nomor WhatsApp pengaju |
| lokasi_usaha | VARCHAR(255) | | Rencana lokasi usaha |
| pesan | TEXT | | Pesan tambahan |
| status | VARCHAR(20) | | Pending atau Approved |
| tanggal | TIMESTAMP | | Waktu pengajuan masuk |
| created_at | TIMESTAMP | | Waktu baris dibuat |
| updated_at | TIMESTAMP | | Waktu baris diperbarui |

### 3.15 Tabel `akun_mitra`

Menyimpan akun login portal mitra.

| Kolom | Tipe | Kunci | Keterangan |
|-------|------|-------|-----------|
| id | BIGINT | PK | Penomoran otomatis |
| nama_mitra | VARCHAR(150) | | Nama mitra |
| username | VARCHAR(100) | UQ | Nama pengguna untuk login |
| password | VARCHAR(255) | | Kata sandi ter-hash |
| created_at | TIMESTAMP | | Waktu baris dibuat |
| updated_at | TIMESTAMP | | Waktu baris diperbarui |

### 3.16 Tabel `profil_perusahaan`

Menyimpan konten profil perusahaan, hanya berisi satu baris.

| Kolom | Tipe | Kunci | Keterangan |
|-------|------|-------|-----------|
| id | BIGINT | PK | Penomoran otomatis |
| sejarah | TEXT | | Riwayat berdirinya usaha |
| visi | TEXT | | Visi perusahaan |
| misi | TEXT | | Misi perusahaan, dipisah baris baru |
| created_at | TIMESTAMP | | Waktu baris dibuat |
| updated_at | TIMESTAMP | | Waktu baris diperbarui |

### 3.17 Tabel `chat_pesanan`

Menyimpan percakapan pesanan antara konsumen dan outlet. Tabel ini ada
pada basis data asli DFC Cirebon namun belum dimigrasikan sebelumnya.

| Kolom | Tipe | Kunci | Keterangan |
|-------|------|-------|-----------|
| id | BIGINT | PK | Penomoran otomatis |
| order_id | VARCHAR(50) | IDX | Nomor pesanan yang dipercakapkan |
| pengirim | ENUM | | konsumen, outlet, atau sistem |
| pesan | TEXT | | Isi pesan, boleh memuat HTML terbatas |
| is_read | BOOLEAN | | Penanda pesan sudah dibaca |
| waktu | TIMESTAMP | | Waktu pesan dikirim |
| created_at | TIMESTAMP | | Waktu baris dibuat |
| updated_at | TIMESTAMP | | Waktu baris diperbarui |

Kolom `order_id` terhubung secara logis ke `transaksi_pesanan.order_id`,
bukan lewat kunci tamu fisik, karena percakapan dirujuk memakai nomor
pesanan yang sudah unik. Isi kolom `pesan` disaring di lapisan model
sebelum ditampilkan agar hanya memuat penanda HTML yang aman.

---

## 4. Hubungan Antar Tabel

### 4.1 Daftar Hubungan

| No | Tabel Asal | Tabel Tujuan | Kardinalitas | Kolom Penghubung | Jenis |
|----|-----------|--------------|--------------|------------------|-------|
| 1 | menu | stok_outlet | 1 : N | menu.id ke stok_outlet.menu_id | Fisik |
| 2 | lokasi | admin | 1 : 1 | lokasi.username_admin ke admin.username | Fisik |
| 3 | lokasi | pengaturan_toko | 1 : 1 | lokasi.username_admin ke pengaturan_toko.kode_outlet | Logis |
| 4 | pengaturan_toko | request_stok | 1 : N | pengaturan_toko.nama_outlet ke request_stok.nama_outlet | Logis |
| 5 | stok_gudang | riwayat_stok | 1 : N | stok_gudang.nama_barang ke riwayat_stok.nama_barang | Logis |
| 6 | stok_gudang | request_stok | 1 : N | stok_gudang.nama_barang ke request_stok.nama_barang | Logis |
| 7 | pengaturan_toko | penjualan_harian | 1 : N | pengaturan_toko.kode_outlet ke penjualan_harian.kode_outlet | Logis |
| 8 | pengaturan_toko | laporan_keuangan | 1 : N | pengaturan_toko.kode_outlet ke laporan_keuangan.kode_outlet | Logis |
| 9 | pengaturan_toko | transaksi_pesanan | 1 : N | pengaturan_toko.kode_outlet ke transaksi_pesanan.kode_outlet | Logis |
| 10 | pengajuan_mitra | akun_mitra | 1 : 1 | Alur pendaftaran, bukan kolom | Proses |
| 11 | transaksi_pesanan | chat_pesanan | 1 : N | transaksi_pesanan.order_id ke chat_pesanan.order_id | Logis |

### 4.2 Penjelasan Hubungan Utama

**Hubungan 1: Menu ke Stok Outlet**

Satu jenis produk tersedia di banyak outlet, sehingga satu baris pada
tabel `menu` berpasangan dengan banyak baris pada tabel `stok_outlet`.
Hubungan ini ditegakkan sebagai kunci tamu fisik sehingga integritas
datanya terjamin oleh basis data.

**Hubungan 2: Lokasi ke Akun Karyawan**

Setiap outlet memiliki tepat satu akun karyawan. Penghubungnya adalah
kolom `username_admin` pada `lokasi` yang merujuk ke kolom `username`
pada `admin`. Karena penghubungnya bukan kunci utama, hubungan ini
digolongkan sebagai satu ke satu secara logis.

**Hubungan 4 hingga 9: Rantai Outlet**

Tabel `pengaturan_toko` menjadi pusat rujukan bagi seluruh data
operasional outlet. Kolom `kode_outlet` dipakai berulang pada tabel
`request_stok`, `penjualan_harian`, `laporan_keuangan`, dan
`transaksi_pesanan`. Pola ini menjaga satu penamaan kode outlet yang
konsisten di seluruh sistem.

**Hubungan 10: Pengajuan Mitra ke Akun Mitra**

Hubungan ini bersifat alur kerja, bukan relasi basis data. Ketika
owner menyetujui sebuah pengajuan, owner membuat akun mitra baru
secara manual melalui panel. Tidak ada kolom penghubung karena
kedua tabel berdiri sendiri.

**Hubungan 11: Pesanan ke Percakapan**

Satu pesanan dapat memiliki banyak pesan percakapan, sehingga satu
baris pada `transaksi_pesanan` berpasangan dengan banyak baris pada
`chat_pesanan`. Penghubungnya adalah kolom `order_id` yang unik pada
`transaksi_pesanan` dan berulang pada `chat_pesanan`. Hubungan ini
digolongkan logis karena tidak ditegakkan kunci tamu fisik.

---

## 5. Normalisasi Struktur Stok

### 5.1 Struktur Versi Native

Pada aplikasi PHP native, stok tiap outlet disimpan pada satu tabel
dengan pola penamaan kolom dinamis sebagai berikut.

```
Tabel: stok
Kolom: id | nama_menu | stok_sutawinangun | stok_sumber | stok_<outlet_baru>
```

Setiap kali outlet baru dibuka, sistem menjalankan perintah
`ALTER TABLE` untuk menambah kolom baru. Pola ini menimbulkan
empat persoalan:

1. Struktur tabel berubah saat aplikasi berjalan sehingga sulit diprediksi.
2. Jumlah kolom tidak terbatas dan terus bertambah.
3. Query penyaringan stok memerlukan penyusunan nama kolom secara dinamis.
4. Tidak ada mekanisme kunci tamu untuk menjaga keutuhan data.

### 5.2 Struktur Versi Laravel

Struktur tersebut dinormalisasi menjadi tabel tersendiri.

```
Tabel: stok_outlet
Kolom: id | menu_id | kode_outlet | stok | created_at | updated_at
Batasan: UNIQUE (menu_id, kode_outlet)
```

### 5.3 Perbandingan Hasil

| Aspek | Versi Native | Versi Laravel |
|-------|--------------|---------------|
| Penambahan outlet | Mengubah struktur tabel | Cukup menambah baris data |
| Jumlah kolom | Bertambah terus | Tetap |
| Query stok per outlet | Nama kolom dinamis | `where('kode_outlet', $kode)` |
| Keutuhan data | Tidak terjamin | Dijamin batasan unik dan kunci tamu |
| Relasi ke menu | Tidak ada | Relasi Eloquent yang jelas |

### 5.4 Pemeriksaan Bentuk Normal

| Bentuk | Syarat | Status |
|--------|--------|--------|
| 1NF | Tidak ada kolom bernilai banyak dalam satu sel | Terpenuhi, kecuali `detail_pesanan` yang menyimpan JSON secara sengaja untuk keperluan arsip struk |
| 2NF | Tidak ada ketergantungan sebagian pada kunci gabungan | Terpenuhi pada seluruh tabel |
| 3NF | Tidak ada ketergantungan transitif | Terpenuhi pada seluruh tabel |

Kolom `detail_pesanan` pada tabel `transaksi_pesanan` menyimpan
rincian barang dalam bentuk JSON. Keputusan ini diambil secara sadar
agar struk pesanan tetap utuh sesuai kondisi saat transaksi terjadi,
meskipun harga menu berubah di kemudian hari.

---

## 6. Diagram Physical

Berkas diagram lengkap tersedia pada `docs/diagram/erd-dfc-cirebon.drawio`.
Diagram menampilkan seluruh 17 tabel aplikasi beserta kolom kuncinya
dan garis penghubung antar tabel.

### 6.1 Susunan Diagram

Diagram disusun dalam lima kelompok warna:

| Warna | Kelompok | Tabel |
|-------|----------|-------|
| Biru | Akun dan hak akses | lokasi, admin, admin_gudang, pengaturan_toko |
| Ungu | Produk dan kemitraan | menu, stok_outlet, pengajuan_mitra, akun_mitra |
| Hijau | Transaksi | transaksi_pesanan, chat_pesanan |
| Biru muda | Laporan | penjualan_harian, laporan_keuangan |
| Merah | Gudang | stok_gudang, riwayat_stok |
| Kuning tua | Operasional | request_stok |
| Abu-abu | Konten dan log | log_aktivitas, profil_perusahaan |

### 6.2 Cara Membuka Diagram

1. Buka situs `https://app.diagrams.net`.
2. Pilih menu File lalu Open From dan Device.
3. Pilih berkas `erd-dfc-cirebon.drawio`.
4. Diagram akan tampil utuh dan dapat disunting lebih lanjut.

---

## 7. Kamus Data Ringkas

### 7.1 Kolom yang Sering Dipakai

| Nama Kolom | Tipe | Muncul Pada Tabel | Keterangan |
|-----------|------|-------------------|-----------|
| kode_outlet | VARCHAR(100) | stok_outlet, pengaturan_toko, penjualan_harian, laporan_keuangan, transaksi_pesanan | Penanda outlet |
| nama_barang | VARCHAR(150) | stok_gudang, riwayat_stok, request_stok | Nama bahan baku |
| nama_outlet | VARCHAR(150) | request_stok, pengaturan_toko | Nama tampilan outlet |
| order_id | VARCHAR(50) | transaksi_pesanan, chat_pesanan | Nomor pesanan |
| pengirim | ENUM | chat_pesanan | Peran pengirim pesan |
| pesan | TEXT | chat_pesanan, pengajuan_mitra | Isi pesan |
| username | VARCHAR(100) | admin, admin_gudang, akun_mitra | Nama pengguna login |
| status | VARCHAR(30) | pengaturan_toko, request_stok, pengajuan_mitra | Penanda keadaan |
| tanggal | DATE | penjualan_harian, laporan_keuangan | Tanggal pencatatan |
| waktu | TIME atau DATETIME | penjualan_harian, log_aktivitas, riwayat_stok | Waktu pencatatan |

### 7.2 Nilai Enum dan Rentang Tetap

| Kolom | Nilai yang Diizinkan | Sumber di Kode |
|-------|---------------------|----------------|
| lokasi.kategori | Pusat, Outlet, Mitra, Cabang | Migrasi |
| pengaturan_toko.status | buka, tutup | Migrasi |
| request_stok.status | Pending, Disetujui, Ditolak | Migrasi |
| transaksi_pesanan.status_pesanan | Menunggu Konfirmasi, Diproses, Selesai, Dibatalkan | TransaksiPesanan::STATUS |
| chat_pesanan.pengirim | konsumen, outlet, sistem | Migrasi |
| Batas stok kritis | 10 | config/dfc.php |
| Ukuran gambar maksimal | 4096 KB | config/dfc.php |

### 7.3 Indeks

| Tabel | Kolom | Jenis | Alasan |
|-------|-------|-------|--------|
| transaksi_pesanan | status_pesanan | Indeks | Sering disaring menurut status |
| transaksi_pesanan | no_hp | Indeks | Dipakai untuk pelacakan pesanan |
| chat_pesanan | order_id | Indeks | Dipakai untuk memuat percakapan per pesanan |
| penjualan_harian | tanggal | Indeks | Sering disaring menurut rentang tanggal |
| penjualan_harian | kode_outlet | Indeks | Sering disaring menurut outlet |
| laporan_keuangan | tanggal | Indeks | Dipakai untuk penyusunan laporan |
| stok_outlet | menu_id, kode_outlet | Unik | Mencegah duplikasi stok |

---

Dokumen ini disusun sebagai pelengkap dokumen SRS dan menjadi rujukan
utama dalam pembuatan migrasi basis data serta model Eloquent pada
aplikasi Laravel.
