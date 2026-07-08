    <div class="page-header">
      <div class="container">
        <img src="<?= BASEURL; ?>/assets/img/Logodexpress (2).jpg" alt="Logo Dexpress" class="hero-logo" />
        <h1>Daftar Menu & Harga</h1>
        <p>Harga bersahabat, Rasa pejabat!</p>
      </div>
    </div>

    <section class="section-menu">
      <div class="container">
        <h2 class="category-title">Semua Menu Andalan</h2>
        
        <div class="menu-grid">
          <?php foreach( $data['menu'] as $menu ) : ?>
          <?php
              // LOGIKA GAMBAR
              $gambar_db = $menu['gambar'];
              $nama_file_saja = basename($gambar_db); 

              if (!empty($nama_file_saja)) { 
                  $img_src = BASEURL . "/assets/img/owner/" . $nama_file_saja; 
              } 
              else { 
                  $img_src = "https://via.placeholder.com/300?text=No+Image"; 
              }
          ?>
          
          <div class="menu-card">
            <img src="<?= $img_src; ?>" alt="<?= $menu['nama']; ?>" class="menu-img" onerror="this.src='https://via.placeholder.com/300?text=Error+Loading';"/>
            <h3><?= $menu['nama']; ?></h3>
            <p>Menu favorit Dexpress yang renyah dan gurih.</p>
            <div class="menu-price">Rp <?= number_format($menu['harga'], 0, ',', '.'); ?></div>
          </div>
          
          <?php endforeach; ?>
        </div>
      </div>
    </section>
