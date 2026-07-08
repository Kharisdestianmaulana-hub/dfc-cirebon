# DFC Cirebon - Sistem Manajemen Web

Selamat datang di repositori proyek DFC Cirebon! Sistem ini sedang dalam masa transisi (migrasi) dari gaya penulisan PHP Native tradisional ke arsitektur **MVC (Model-View-Controller)** berbasis OOP (Object Oriented Programming), serta menggunakan **Bootstrap 5** untuk UI yang modern dan responsif.

Dokumen ini ditulis khusus sebagai **Panduan Migrasi** bagi tim *developer* yang bertugas merombak sisa halaman sistem (yaitu **Outlet** dan **Mitra**).

---

## 🏗️ Struktur Folder Baru (Wajib Dipahami)

Sistem yang baru mengikuti pola kerangka kerja (*framework*) MVC buatan sendiri. Berikut strukturnya:

- `app/` 👉 Inti dari aplikasi MVC.
  - `config/` 👉 Konfigurasi database (`config.php`).
  - `controllers/` 👉 Tempat menaruh logika bisnis (Misal: `Outlet.php`, `Mitra.php`).
  - `core/` 👉 Penggerak utama MVC (`App.php`, `Controller.php`, `Database.php`).
  - `models/` 👉 Tempat menaruh kueri database/SQL (Misal: `Outlet_model.php`).
  - `views/` 👉 Tempat menaruh tampilan UI (HTML/PHP). Pisahkan ke dalam subfolder (Misal: `views/outlet/`).
- `assets/` 👉 Satu-satunya wadah untuk file CSS, JS, dan Gambar.
  - `css/` 👉 File `.css`.
  - `js/` 👉 File `.js`.
  - `img/` 👉 Seluruh gambar.
- `index.php` 👉 *Front Controller* (Pintu masuk utama seluruh aplikasi).

---

## 🚀 Panduan Migrasi untuk Tim (Tugas: Outlet & Mitra)

Halaman Konsumen, Gudang, dan Owner **SUDAH** dirombak ke MVC. 
Tugas tim saat ini adalah memindahkan fungsi yang ada di dalam folder lawas `outlet/` dan `mitra/` ke dalam struktur `app/`.

### Aturan Main (SOP Migrasi):

1. **JANGAN GUNAKAN `koneksi.php` LAGI!**
   - File `koneksi.php` gaya lama harus ditinggalkan.
   - Gunakan *wrapper* PDO yang sudah kami siapkan di `app/core/Database.php`. 
   - Cara memanggilnya ada di dalam Model. Contoh: `$this->db->query('SELECT * FROM tabel');`

2. **Pola URL (Routing) Telah Berubah**
   - URL lama: `http://localhost/dfc%20cirebon/outlet/index.php`
   - URL MVC baru: `http://localhost/dfc%20cirebon/outlet`
   - URL MVC memanggil Controller `Outlet.php` dan *method* default `index()`.

3. **Cara Kerja Controller & Model**
   - Buat Controller `app/controllers/Outlet.php`.
   - Buat Model `app/models/Outlet_model.php`.
   - Controller bertugas mengecek *Session* (login) dan melempar/menerima data ke Model.
   - Panggil view dari controller dengan: `$this->view('outlet/index', $data);`

4. **Wajib Menggunakan Bootstrap 5**
   - Rombak *file* HTML lawas (yang pakai tabel biasa/CSS native) menjadi *Grid*, *Card*, dan *Table* milik Bootstrap 5.
   - Buatkan `app/views/outlet/templates/header.php` yang memuat tag `<head>`, `<link>` Bootstrap CDN, dan Navbar.

5. **Rapikan Aset (Gambar/CSS)**
   - Jika halaman Outlet/Mitra memiliki gambar atau file CSS khusus, **JANGAN** taruh di dalam folder views!
   - Pindahkan aset tersebut ke dalam folder `/assets/css/` atau `/assets/img/`.
   - Panggil aset di HTML menggunakan konstanta `BASEURL`. Contoh:
     `<img src="<?= BASEURL; ?>/assets/img/logo.png">`

6. **Hapus File Lama Jika Sudah Selesai**
   - Jika Controller `Outlet.php` sudah berfungsi sempurna, **HAPUS** seluruh folder fisik lawas `outlet/` di direktori terluar (root) agar sistem Apache tidak bingung membedakan antara folder fisik dan *routing* MVC (Menghindari Error 403 Forbidden).

---

## 💡 Contoh Alur Kerja (Workflow) Migrasi

Misal Anda ingin memigrasikan halaman Login Outlet:
1. Pindahkan form HTML lama ke `app/views/outlet/login.php`.
2. Hias form tersebut pakai kelas Bootstrap (contoh: `card`, `form-control`, `btn btn-primary`).
3. Buat *method* `login()` di `app/controllers/Outlet.php` untuk menerima `$_POST['username']`.
4. Buat fungsi `cekLogin()` di `app/models/Outlet_model.php` untuk verifikasi *database*.
5. Arahkan *action* form-nya ke `<?= BASEURL; ?>/outlet/login`.

Selamat bekerja untuk tim *developer*! Mari jadikan kode DFC Cirebon lebih bersih, moduler, dan terstruktur! 🚀
