<div class="content">
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <i class="ph-bold ph-list"></i>
    </button>

    <div class="header-title">
        <h2><?= htmlspecialchars($data['page_title']); ?></h2>
        <p>Route MVC sudah disiapkan dengan URL bersih tanpa ekstensi file.</p>
    </div>

    <div class="placeholder-card">
        <h3 style="margin-top:0;">Tahap migrasi berikutnya</h3>
        <p>
            Halaman ini masih menunggu dipindahkan dari PHP native lama ke pola Controller, Model, dan View.
            Untuk sementara, data dan logika lamanya belum dimasukkan ke route MVC ini supaya proses rombaknya
            tetap rapi dan bertahap.
        </p>
        <p>
            Kalau perlu membuka fitur lama saat masa transisi, halaman native-nya masih tersedia.
        </p>
        <a href="<?= $data['legacy_url']; ?>" class="btn-legacy">
            <i class="ph ph-arrow-square-out"></i> Buka Versi Lama
        </a>
    </div>
</div>
