@extends('layouts.publik')

@section('konten')
    <div class="page-header">
      <div class="container">
        <img src="{{ asset('assets/img/Logodexpress (2).jpg') }}" alt="Logo Dexpress" class="hero-logo" />
        <h1>Daftar Menu &amp; Harga</h1>
        <p>Harga bersahabat, Rasa pejabat!</p>
      </div>
    </div>

    <section class="section-menu">
      <div class="container">
        <h2 class="category-title">Semua Menu Andalan</h2>

        <div class="menu-grid">
          @forelse ($menu as $item)
          <div class="menu-card">
            <img src="{{ $item->url_gambar ?? 'https://via.placeholder.com/300?text=No+Image' }}"
                 alt="{{ $item->nama }}"
                 class="menu-img"
                 onerror="this.src='https://via.placeholder.com/300?text=Error+Loading';"/>
            <h3>{{ $item->nama }}</h3>
            <p>Menu favorit Dexpress yang renyah dan gurih.</p>
            <div class="menu-price">{{ $item->harga_rupiah }}</div>
          </div>
          @empty
          <div class="menu-card">
            <h3>Belum ada menu</h3>
            <p>Menu akan segera ditambahkan oleh pengelola.</p>
          </div>
          @endforelse
        </div>
      </div>
    </section>
@include('layouts.publik_footer')
@endsection
