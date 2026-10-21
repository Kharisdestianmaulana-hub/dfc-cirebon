-- =====================================================================
-- REKONSTRUKSI SKEMA DATABASE dfc_db (VERSI NATIVE, SEBAGAI REFERENSI)
-- Dibuat dengan membaca seluruh query pada folder app/, outlet/, mitra/.
-- File ini BUKAN pengganti migration Laravel, hanya dokumentasi kiblat.
-- =====================================================================

CREATE DATABASE IF NOT EXISTS dfc_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE dfc_db;

-- ---------------------------------------------------------------------
-- 1. admin : akun karyawan outlet (dibuat saat aktivasi outlet oleh owner)
--    Kolom stok_<username> pada tabel menu ditambahkan dinamis per outlet.
-- ---------------------------------------------------------------------
CREATE TABLE admin (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    username  VARCHAR(100) NOT NULL UNIQUE,
    password  VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- 2. admin_gudang : akun tunggal admin gudang pusat (id = 1)
--    Password disimpan plain text pada sistem native lama.
-- ---------------------------------------------------------------------
CREATE TABLE admin_gudang (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    username  VARCHAR(100) NOT NULL,
    password  VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO admin_gudang (id, username, password) VALUES (1, 'gudang', 'gudang123');

-- ---------------------------------------------------------------------
-- 3. lokasi : data outlet/kemitraan/pusat beserta koordinat peta
-- ---------------------------------------------------------------------
CREATE TABLE lokasi (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    nama          VARCHAR(150) NOT NULL,
    alamat        TEXT NULL,
    kategori      ENUM('Pusat', 'Outlet', 'Mitra', 'Cabang') NOT NULL DEFAULT 'Outlet',
    latitude      VARCHAR(50) NULL,
    longitude     VARCHAR(50) NULL,
    telepon       VARCHAR(30) NULL,
    username_admin VARCHAR(100) NULL,
    created_at    TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- 4. menu : daftar menu jual beserta gambar
--    CATATAN: sistem native menambahkan kolom stok_<username_admin>
--    setiap kali outlet diaktifkan (stok per outlet disimpan di tabel menu).
--    Pada Laravel, stok per outlet dinormalisasi ke tabel stok_outlet.
-- ---------------------------------------------------------------------
CREATE TABLE menu (
    id     INT AUTO_INCREMENT PRIMARY KEY,
    nama   VARCHAR(150) NOT NULL,
    harga  INT NOT NULL DEFAULT 0,
    gambar VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- 5. pengaturan_toko : status buka/tutup tiap outlet (kode_outlet = username admin)
-- ---------------------------------------------------------------------
CREATE TABLE pengaturan_toko (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    kode_outlet VARCHAR(100) NOT NULL UNIQUE,
    nama_outlet VARCHAR(150) NULL,
    status      ENUM('buka', 'tutup') NOT NULL DEFAULT 'tutup',
    updated_at  TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- 6. transaksi_pesanan : pesanan konsumen dari website
--    detail_pesanan berisi JSON array item {nama, harga, qty, catatan}
-- ---------------------------------------------------------------------
CREATE TABLE transaksi_pesanan (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    order_id       VARCHAR(50) NOT NULL UNIQUE,
    nama_pemesan   VARCHAR(150) NOT NULL,
    no_hp          VARCHAR(30) NOT NULL,
    kode_outlet    VARCHAR(100) NULL,
    total_bayar    DECIMAL(15,2) NOT NULL DEFAULT 0,
    metode_bayar   VARCHAR(20) NOT NULL DEFAULT 'Cash',
    detail_pesanan LONGTEXT NULL,
    status_pesanan VARCHAR(50) NOT NULL DEFAULT 'Menunggu Konfirmasi',
    waktu_pesan    TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_status (status_pesanan),
    INDEX idx_hp (no_hp)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- 7. penjualan_harian : catatan penjualan offline per outlet
-- ---------------------------------------------------------------------
CREATE TABLE penjualan_harian (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    tanggal      DATE NOT NULL,
    waktu        TIME NOT NULL,
    kode_outlet  VARCHAR(100) NOT NULL,
    nama_menu    VARCHAR(150) NOT NULL,
    qty          INT NOT NULL DEFAULT 1,
    catatan      VARCHAR(255) NULL,
    harga_satuan DECIMAL(15,2) NOT NULL DEFAULT 0,
    subtotal     DECIMAL(15,2) NOT NULL DEFAULT 0,
    INDEX idx_tanggal (tanggal),
    INDEX idx_outlet (kode_outlet)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- 8. laporan_keuangan : laporan omzet harian per outlet
-- ---------------------------------------------------------------------
CREATE TABLE laporan_keuangan (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    tanggal      DATE NOT NULL,
    kode_outlet  VARCHAR(100) NOT NULL,
    omzet_kotor  DECIMAL(15,2) NOT NULL DEFAULT 0,
    pengeluaran  DECIMAL(15,2) NOT NULL DEFAULT 0,
    omzet_bersih DECIMAL(15,2) NOT NULL DEFAULT 0,
    catatan      TEXT NULL,
    waktu_input  TIME NULL,
    UNIQUE KEY uniq_laporan (tanggal, kode_outlet),
    INDEX idx_tanggal (tanggal)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- 9. stok_gudang : master stok gudang pusat
-- ---------------------------------------------------------------------
CREATE TABLE stok_gudang (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nama_barang VARCHAR(150) NOT NULL,
    stok        INT NOT NULL DEFAULT 0,
    satuan      VARCHAR(30) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- 10. riwayat_stok : audit trail perubahan stok gudang
-- ---------------------------------------------------------------------
CREATE TABLE riwayat_stok (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nama_barang VARCHAR(150) NOT NULL,
    stok_lama   INT NOT NULL DEFAULT 0,
    stok_baru   INT NOT NULL DEFAULT 0,
    selisih     INT NOT NULL DEFAULT 0,
    admin       VARCHAR(100) NULL,
    waktu       TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- 11. request_stok : permintaan restock dari outlet ke gudang pusat
-- ---------------------------------------------------------------------
CREATE TABLE request_stok (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    nama_outlet    VARCHAR(150) NOT NULL,
    nama_barang    VARCHAR(150) NOT NULL,
    jumlah         INT NOT NULL DEFAULT 1,
    satuan         VARCHAR(30) NULL,
    status         VARCHAR(30) NOT NULL DEFAULT 'Pending',
    alasan_tolak   VARCHAR(255) NULL,
    waktu_request  TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- 12. log_aktivitas : jejak aktivitas karyawan outlet
-- ---------------------------------------------------------------------
CREATE TABLE log_aktivitas (
    id     INT AUTO_INCREMENT PRIMARY KEY,
    waktu  DATETIME NULL,
    pelaku VARCHAR(100) NULL,
    aksi   VARCHAR(100) NULL,
    detail TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- 13. pengajuan_mitra : pengajuan kemitraan dari halaman publik
-- ---------------------------------------------------------------------
CREATE TABLE pengajuan_mitra (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    nama         VARCHAR(150) NOT NULL,
    whatsapp     VARCHAR(30) NOT NULL,
    lokasi_usaha VARCHAR(255) NULL,
    pesan        TEXT NULL,
    tanggal      TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- 14. akun_mitra : akun login portal mitra (dibuat owner)
-- ---------------------------------------------------------------------
CREATE TABLE akun_mitra (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nama_mitra VARCHAR(150) NOT NULL,
    username   VARCHAR(100) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- 15. profil_perusahaan : konten halaman "Tentang Kami" (id = 1)
-- ---------------------------------------------------------------------
CREATE TABLE profil_perusahaan (
    id      INT AUTO_INCREMENT PRIMARY KEY,
    sejarah TEXT NULL,
    visi    TEXT NULL,
    misi    TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO profil_perusahaan (id, sejarah, visi, misi) VALUES
(1,
 'Dexpress Fried Chicken (DFC) adalah UMKM kuliner asli Cirebon yang berdiri sejak tahun 2020. Berawal dari gerobak kecil, kini DFC telah berkembang menjadi jaringan outlet dan mitra yang tersebar di wilayah Cirebon dan sekitarnya.',
 'Menjadi brand Fried Chicken lokal nomor 1 di Cirebon yang dikenal karena kualitas rasa, pelayanan, dan peluang kemitraannya.',
 'Menyajikan produk ayam goreng yang Halal, Higienis, dan Hemat. Memberikan peluang usaha yang menguntungkan bagi mitra tanpa biaya royalti.');
