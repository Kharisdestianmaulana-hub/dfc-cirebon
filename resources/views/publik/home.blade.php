@extends('layouts.publik')

@section('konten')
    <section id="home" class="hero">
      <div class="container hero-content">
        <img src="{{ asset('assets/img/Logodexpress (2).jpg') }}" alt="Logo Dexpress" class="hero-logo" />
        <h1>Nikmati Kerenyahan Dexpress Fried Chicken</h1>
        <p>Fried Chicken dengan bumbu rahasia yang meresap hingga ke tulang. Gurih, Renyah, dan Menguntungkan.</p>
        <h3 id="hero-greeting" class="greeting-style">
            Halo! Mau makan apa hari ini?
        </h3>
        <a href="{{ route('menu.index') }}" class="btn btn-primary">Lihat Menu</a>
      </div>
    </section>

    <section id="menu" class="section-menu">
      <div class="container">
        <h2 class="section-title">Varian Menu Andalan</h2>
        <div class="menu-grid">
          <div class="menu-card">
            <img src="{{ asset('assets/img/Sayap.png') }}" alt="Sayap Ayam" class="menu-img" />
            <h3>Original Crispy</h3>
            <p>Ayam goreng original dengan kulit super renyah.</p>
          </div>
          <div class="menu-card">
            <img src="{{ asset('assets/img/Sayap.png') }}" alt="Sayap Ayam" class="menu-img" />
            <h3>Crispy Spicy</h3>
            <p>Ayam goreng pedas dengan kulit super renyah.</p>
          </div>
          <div class="menu-card">
            <img src="{{ asset('assets/img/Geprek.webp') }}" alt="Geprek" class="menu-img" />
            <h3>Spicy Geprek</h3>
            <p>Ayam crispy dibalut sambal geprek pedas nampol.</p>
          </div>
        </div>
        <div class="menu-action">
          <a href="{{ route('menu.index') }}" class="btn btn-outline">Lihat Seluruh Menu</a>
        </div>
      </div>
    </section>

    <section id="kemitraan" class="section-kemitraan">
      <div class="container">
        <h2 class="section-title white-text">Peluang Kemitraan</h2>
        <div class="slideshow-container">
          <div class="slides-wrapper">
            <img src="{{ asset('assets/img/gerobak1.jpg') }}" class="slide-img" alt="Gerobak Dexpress 1" />
            <img src="{{ asset('assets/img/gerobak2.jpg') }}" class="slide-img" alt="Gerobak Dexpress 2" />
            <img src="{{ asset('assets/img/gerobak3.jpg') }}" class="slide-img" alt="Gerobak Dexpress 3" />
            <img src="{{ asset('assets/img/gerobak4.jpg') }}" class="slide-img" alt="Gerobak Dexpress 4" />
          </div>
          <div class="slide-indicators">
            <span class="dot active"></span>
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
          </div>
        </div>
        <div class="kemitraan-content">
          <p>Bergabunglah menjadi mitra sukses Dexpress. Kami menawarkan paket usaha terjangkau dengan sistem manajemen yang mudah dan dukungan penuh dari pusat. Lihat contoh outlet mitra kami di atas!</p>
          <ul class="kemitraan-list">
            <li>Modal Terjangkau</li>
            <li>Bahan Baku Terjamin</li>
            <li>Training Karyawan</li>
            <li>Tanpa Royalty Fee</li>
          </ul>
        </div>
      </div>
    </section>

    <section id="faq" class="section-faq">
      <div class="container">
        <h2 class="section-title">Pertanyaan Sering Diajukan</h2>
        <div class="faq-wrapper">
          <details class="faq-item">
            <summary>Berapa modal awal untuk kemitraan Dexpress?</summary>
            <div class="faq-answer">
              <p>Modal kemitraan sangat terjangkau, disesuaikan dengan paket yang Anda pilih (Paket Hemat atau Paket Lengkap). Silakan klik tombol pendaftaran untuk mendapatkan proposal penawaran lengkap.</p>
            </div>
          </details>
          <details class="faq-item">
            <summary>Apakah dikenakan biaya royalti (Royalty Fee)?</summary>
            <div class="faq-answer">
              <p>Tidak ada! Keuntungan penjualan 100% menjadi milik mitra. Kami tidak memungut biaya royalti bulanan ataupun tahunan.</p>
            </div>
          </details>
          <details class="faq-item">
            <summary>Apakah bahan baku wajib beli dari pusat?</summary>
            <div class="faq-answer">
              <p>Demi menjaga kualitas rasa dan standar Dexpress, bahan baku utama seperti tepung bumbu rahasia dan marinasi ayam wajib dipasok dari pusat. Bahan pendukung lain bisa dibeli di pasar lokal.</p>
            </div>
          </details>
          <details class="faq-item">
            <summary>Apakah disediakan pelatihan (training)?</summary>
            <div class="faq-answer">
              <p>Ya, kami memberikan pelatihan lengkap cara menggoreng, penyajian, hingga manajemen stok kepada mitra atau karyawan sampai mahir.</p>
            </div>
          </details>
        </div>
      </div>
    </section>
@include('layouts.publik_footer')
@endsection
