# 🚀 Dokumentasi Resmi Developer: DFC Cirebon MVC

Selamat datang tim *developer*! Saat ini sistem manajemen web **DFC Cirebon** sedang bermigrasi besar-besaran dari gaya penulisan PHP Native (prosedural) lama menuju arsitektur modern **MVC (Model-View-Controller)** berbasis OOP (Object Oriented Programming), dengan balutan **Bootstrap 5** untuk UI (*User Interface*).

Halaman `Konsumen`, `Gudang`, dan `Owner` telah berhasil dimigrasikan. **Tugas tim saat ini adalah memigrasikan fitur di dalam folder lawas `outlet/` dan `mitra/` ke dalam struktur MVC yang baru.** 

Buku panduan (*playbook*) ini memuat Standar Operasional Prosedur (SOP) teknis untuk menulis kode di dalam proyek ini. Harap dibaca dengan teliti!

---

## 📁 1. Pemetaan Struktur Folder Utama

Segala sesuatu yang berkaitan dengan *backend* dan logika *website* sekarang hidup di dalam folder `app/`. Folder lawas di luar `app/` wajib ditinggalkan.

```text
📦 dfc cirebon (Root)
 ┣ 📂 app/
 ┃ ┣ 📂 config/      👉 File config.php (Simpan konstanta BASEURL, DB_HOST, DB_USER, dll)
 ┃ ┣ 📂 controllers/ 👉 [TUGAS ANDA] Buat file Controller di sini (Misal: Outlet.php)
 ┃ ┣ 📂 core/        👉 Mesin utama MVC (App.php, Controller.php, Database.php) JANGAN DIUBAH!
 ┃ ┣ 📂 models/      👉 [TUGAS ANDA] Buat file Model di sini (Misal: Outlet_model.php)
 ┃ ┗ 📂 views/       👉 [TUGAS ANDA] Buat struktur HTML/UI di sini (Gunakan Bootstrap 5)
 ┣ 📂 assets/        👉 TEMPAT SATU-SATUNYA untuk menaruh file statis (CSS, JS, Gambar)
 ┣ 📜 index.php      👉 Front Controller (Semua request masuk dari sini)
 ┗ 📜 .htaccess      👉 Mengatur perutean (routing) URL agar bersih (tanpa .php)
```

---

## 🌐 2. Konsep Routing (Perutean) URL MVC

Sistem kita menggunakan URL bersih (*Clean URL*). Rumus pemanggilannya adalah:
👉 `http://localhost/dfc%20cirebon/[Controller]/[Method]/[Parameter1]/[Parameter2]`

**Contoh Kasus:**
Jika Anda mengetikkan URL: `http://localhost/dfc%20cirebon/outlet/edit/5`
- `outlet` = Akan memanggil file `app/controllers/Outlet.php`
- `edit` = Akan memanggil fungsi `public function edit($id)` di dalam class Outlet.
- `5` = Adalah parameter data yang dikirimkan ke dalam `$id`.

*(Catatan: Jika metode tidak ditulis di URL, sistem otomatis memanggil fungsi `index()`)*.

---

## 💻 3. SOP & Standar Penulisan Kode (Step-by-Step)

Berikut adalah panduan teknis langkah demi langkah untuk memigrasikan satu fitur.

### LANGKAH A: Membuat Controller
File Controller bertugas menjadi jembatan lalu lintas. Dia akan mengambil data dari Model dan melemparnya ke View.

**Buat File:** `app/controllers/Outlet.php`
```php
<?php
// Nama Class WAJIB sama persis dengan nama file (huruf depan kapital)
class Outlet extends Controller {
    
    // Fungsi default jika URL hanya memanggil /outlet
    public function index() {
        // 1. Siapkan data dinamis
        $data['judul'] = 'Dashboard Outlet';
        
        // Memanggil fungsi dari Model
        $data['pesanan'] = $this->model('Outlet_model')->getAllPesanan();

        // 2. Render tampilan (Header, Isi, Footer)
        $this->view('templates/header', $data);
        $this->view('outlet/index', $data);
        $this->view('templates/footer');
    }

    // Fungsi untuk menerima pengiriman form (POST)
    public function simpan() {
        // Validasi dan panggil Model
        if ($this->model('Outlet_model')->tambahData($_POST) > 0) {
            // Gunakan BASEURL untuk redirect URL
            header('Location: ' . BASEURL . '/outlet');
            exit;
        }
    }
}
```

### LANGKAH B: Membuat Model & Kueri Database (PENTING!)
🚨 **ATURAN MUTLAK: DILARANG KERAS MENGGUNAKAN `koneksi.php` atau `mysqli_query`!** 🚨
Kita sekarang menggunakan kelas pembungkus (*wrapper*) PDO yang jauh lebih aman (anti SQL-Injection) dari `app/core/Database.php`.

**Buat File:** `app/models/Outlet_model.php`
```php
<?php
class Outlet_model {
    private $table = 'nama_tabel_anda'; // Ubah sesuai tabel db
    private $db;

    public function __construct() {
        // Inisialisasi koneksi database modern
        $this->db = new Database; 
    }

    // --- CONTOH SELECT DATA BANYAK ---
    public function getAllPesanan() {
        $this->db->query('SELECT * FROM ' . $this->table . ' ORDER BY id DESC');
        return $this->db->resultSet(); // Gunakan resultSet() untuk data banyak (array)
    }

    // --- CONTOH SELECT 1 DATA ---
    public function getPesananById($id) {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind('id', $id); // Gunakan bind() untuk mencegah SQL Injection!
        return $this->db->single(); // Gunakan single() untuk 1 baris data
    }

    // --- CONTOH INSERT DATA (Bisa diadopsi untuk Update/Delete) ---
    public function tambahData($data) {
        $query = "INSERT INTO " . $this->table . " (nama, harga) VALUES (:nama, :harga)";
        $this->db->query($query);
        
        // Ikat data dari form
        $this->db->bind('nama', $data['nama']);
        $this->db->bind('harga', $data['harga']);

        $this->db->execute();
        
        // Kembalikan jumlah baris yang berhasil terpengaruh (1 = sukses)
        return $this->db->rowCount(); 
    }
}
```

### LANGKAH C: Membuat View (Wajib Bootstrap 5)
Di dalam folder `app/views/`, pecah kode HTML Anda menjadi bagian-bagian kecil. Gunakan *Grid System* dan *Card* Bootstrap 5.

**Contoh:** `app/views/outlet/index.php`
```php
<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <!-- Panggil variabel PHP dari Controller menggunakan $data -->
            <h2 class="text-danger fw-bold"><i class="fas fa-store"></i> <?= $data['judul']; ?></h2>
        </div>
    </div>
    
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Looping data array dari Database -->
                    <?php foreach( $data['pesanan'] as $psn ) : ?>
                    <tr>
                        <td><?= $psn['id']; ?></td>
                        <td>
                            <!-- Contoh Badge Bootstrap -->
                            <span class="badge bg-success"><?= $psn['status']; ?></span>
                        </td>
                        <td>
                            <a href="<?= BASEURL; ?>/outlet/detail/<?= $psn['id']; ?>" class="btn btn-sm btn-primary">Detail</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
```

---

## 🎨 4. Penanganan File Aset (CSS, JS, & Gambar)

Jika fitur Anda butuh gambar atau CSS khusus, **JANGAN** letakkan di dalam folder `app/views`. Semua file yang bersifat visual statis **wajib disetor ke dalam folder `assets/` di root proyek**.

Aturan pemanggilan di dalam file HTML/PHP Anda:
- Pemanggilan Gambar: `<img src="<?= BASEURL; ?>/assets/img/logo.jpg">`
- Pemanggilan CSS Tambahan: `<link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/style-tambahan.css">`
- Menyimpan Gambar Upload: Simpan file hasil upload dari PHP `move_uploaded_file()` ke path `dirname(__DIR__, 2) . '/assets/img/nama_file.jpg'`.

---

## 🔒 5. Pengelolaan Session (Login)

Jangan panggil `session_start()` berulang-ulang di setiap baris secara manual seperti di PHP Native. Pemeriksaan sesi (Login) cukup dilakukan di dalam `Controller`.

Contoh penjagaan pintu di *Controller*:
```php
public function index() {
    if (!isset($_SESSION['isOutletLoggedIn'])) {
        header("Location: " . BASEURL . "/outlet/login");
        exit;
    }
    // Jika sudah login, lanjut jalankan kode di bawahnya...
}
```

---

## 🧹 6. Tahap Akhir: Pembersihan (PENTING)

1. Pastikan Anda sudah menguji URL MVC Anda (contoh: `/outlet` atau `/mitra`).
2. Jika logika lama sudah berhasil diubah 100% ke MVC, **HAPUS KESELURUHAN FOLDER FISIK LAMA** `outlet/` dan `mitra/` di *root directory*. 
3. *Mengapa harus dihapus?* Jika ada folder fisik `/outlet` yang tersisa di luar, Web Server Apache akan mengalami kebingungan (*Conflict*) dengan rute MVC kita, sehingga justru akan memunculkan tulisan `Error 403 Access Forbidden`.

> **Selamat Merombak Kode!** Disiplinlah terhadap pemisahan Model, View, dan Controller agar proyek kita panjang umur, mudah dikelola, dan sedap dipandang mata. 🚀
