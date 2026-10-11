<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $judul ?? 'Gudang Pusat DFC' }}</title>

    {{-- Bootstrap 5 CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Ikon Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            background-color: #f8f9fa; /* Latar abu terang */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Tema Merah DFC */
        .navbar-dfc {
            background-color: #e60023; /* Merah DFC */
        }

        .btn-dfc {
            background-color: #e60023;
            color: white;
            border: none;
        }
        .btn-dfc:hover {
            background-color: #cc001d;
            color: white;
        }
    </style>
</head>
<body>

    {{-- Navbar (Hanya tampil jika admin gudang sudah login) --}}
    @auth('gudang')
    <nav class="navbar navbar-expand-lg navbar-dark navbar-dfc shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('gudang.index') }}">
                <i class="fas fa-boxes me-2"></i>GUDANG PUSAT
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('gudang.index') ? 'active fw-bold' : '' }}" href="{{ route('gudang.index') }}">Stok Barang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('gudang.riwayat') ? 'active fw-bold' : '' }}" href="{{ route('gudang.riwayat') }}">Riwayat</a>
                    </li>
                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                        <a class="btn btn-light btn-sm text-danger fw-bold rounded-pill px-3" href="{{ route('gudang.logout') }}" onclick="return confirm('Yakin ingin keluar?');">
                            <i class="fas fa-sign-out-alt me-1"></i> Keluar
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @endauth

    @yield('konten')

    {{-- Bootstrap 5 JS Bundle beserta Popper --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
