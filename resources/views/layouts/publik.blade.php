<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('judul', $judul ?? 'DFC Cirebon')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Ikon dan font -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <!-- Gaya utama aplikasi -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v=4">

    <style>
      /* --- GAYA CHATBOT --- */
      .chatbot-toggler { position: fixed; bottom: 30px; right: 30px; outline: none; border: none; height: 60px; width: 60px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #fd020f; color: white; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 4px 10px rgba(0,0,0,0.2); z-index: 9999; }
      .chatbot-toggler span { position: absolute; }
      .chatbot-toggler span.close-icon { opacity: 0; }
      .show-chatbot .chatbot-toggler span:first-child { opacity: 0; }
      .show-chatbot .chatbot-toggler span.close-icon { opacity: 1; }
      .chatbot-toggler:hover { transform: scale(1.1); }

      .chatbot-container { position: fixed; bottom: 140px; right: 30px; width: 350px; background: #fff; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); overflow: hidden; opacity: 0; pointer-events: none; transform: scale(0.5); transform-origin: bottom right; transition: all 0.3s ease; z-index: 9998; font-family: 'Poppins', sans-serif; }
      body.show-chatbot .chatbot-container { opacity: 1; pointer-events: auto; transform: scale(1); }

      .chat-header { background: #fd020f; padding: 15px; display: flex; align-items: center; justify-content: space-between; color: #fff; }
      .header-info { display: flex; align-items: center; gap: 10px; }
      .header-info img { height: 35px; width: 35px; border-radius: 50%; background: #fff; object-fit: cover; }
      .header-info h4 { margin: 0; font-size: 16px; font-weight: 600; }
      .header-info p { margin: 0; font-size: 12px; opacity: 0.9; }
      .close-btn { background: none; border: none; color: #fff; cursor: pointer; }

      .chat-box { padding: 15px; height: 300px; overflow-y: auto; display: flex; flex-direction: column; gap: 15px; background: #f9f9f9; list-style: none; margin: 0; }
      .chat { display: flex; list-style: none; align-items: flex-end; }
      .chat p { padding: 10px 15px; border-radius: 10px 10px 10px 0; max-width: 85%; font-size: 14px; line-height: 1.4; background: #e9e9e9; color: #333; margin: 0; }
      .chat.outgoing { justify-content: flex-end; }
      .chat.outgoing p { background: #fd020f; color: #fff; border-radius: 10px 10px 0 10px; }
      .chat.incoming span.icon-bot { margin-right: 10px; font-size: 28px; color: #fd020f; background: white; border-radius: 50%; padding: 2px; }

      .chat-input { padding: 15px; border-top: 1px solid #ddd; background: #fff; }
      .quick-options { display: flex; flex-wrap: wrap; gap: 8px; justify-content: center; }
      .quick-options button { background: #fff; border: 1px solid #fd020f; color: #fd020f; padding: 8px 12px; border-radius: 20px; font-size: 12px; cursor: pointer; transition: 0.2s; flex: 1 1 40%; }
      .quick-options button:hover { background: #fd020f; color: white; }

      @media (max-width: 400px) { .chatbot-container { width: 90%; right: 5%; bottom: 140px; } }

      .greeting-style { font-size: 1.5rem; font-weight: 700; margin: 20px 0; opacity: 0; animation: fadeInUp 1s forwards; animation-delay: 0.5s; }
      @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

      footer { background: #111; color: white; padding: 40px 0 20px; margin-top: auto !important; width: 100%; }
      .footer-content { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; max-width: 1000px; margin: 0 auto; padding: 0 20px; }
      .footer-right { display: flex; gap: 20px; }
      .footer-right a { color: white; text-decoration: none; font-size: 14px; transition: 0.3s; }
      .footer-right a:hover { color: #fd020f; text-decoration: underline; }
      .copyright-box p { text-align: center; font-size: 13px; color: #777; margin: 0; }

      @media (max-width: 768px) {
          .footer-content { flex-direction: column; text-align: center; gap: 30px; }
          .footer-left { width: 100%; }
          .footer-right { justify-content: center; flex-wrap: wrap; width: 100%; gap: 15px; }
          .footer-right a { margin: 0 5px; }
      }
    </style>
    @stack('gaya')
</head>
<body style="min-height: 100vh; display: flex; flex-direction: column; margin: 0;">
    <script>window.BASEURL = "{{ url('/') }}";</script>
    <div id="preloader">
      <div class="loader-content">
        <img src="{{ asset('assets/img/Logodexpress (2).jpg') }}" alt="Memuat..." class="loader-logo" />
        <div class="spinner"></div>
      </div>
    </div>

    <header>
      <div class="container nav-container">
        <div class="logo">DFC</div>
        <div class="hamburger">
          <div class="line"></div>
          <div class="line"></div>
          <div class="line"></div>
        </div>
        <nav>
          <ul class="nav-links">
            {{-- Tandai menu aktif sesuai halaman yang sedang dibuka --}}
            <li><a href="{{ route('beranda') }}" class="{{ request()->routeIs('beranda') ? 'active' : '' }}">Beranda</a></li>
            <li><a href="{{ route('menu.index') }}" class="{{ request()->routeIs('menu.*') ? 'active' : '' }}">Menu</a></li>
            <li><a href="{{ route('pesanan.index') }}" class="{{ request()->routeIs('pesanan.*') ? 'active' : '' }}">Pesanan</a></li>
            <li><a href="{{ route('tracking.riwayat') }}" class="{{ request()->routeIs('tracking.*') ? 'active' : '' }}">Riwayat</a></li>
            <li><a href="{{ route('lokasi.index') }}" class="{{ request()->routeIs('lokasi.*') ? 'active' : '' }}">Lokasi</a></li>
            <li><a href="{{ route('profil.index') }}" class="{{ request()->routeIs('profil.*') ? 'active' : '' }}">Profil</a></li>
          </ul>
        </nav>
      </div>
    </header>
    <main class="main-content" style="flex: 1; display: flex; flex-direction: column;">

    @if (session('sukses'))
        <script>document.addEventListener('DOMContentLoaded', function () { alert(@json(session('sukses'))); });</script>
    @endif

    @yield('konten')
