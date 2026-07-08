<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?></title>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/owner-style.css">
    <style>
        .bento-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }
        .bento-card { background: white; padding: 25px; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #f0f0f0; display: flex; flex-direction: column; justify-content: space-between; position: relative; overflow: hidden; min-height: 150px;}
        .bento-card:not(.card-omzet) .card-info h4 { margin: 0 0 10px 0; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; color: #8898aa; }
        .bento-card:not(.card-omzet) .angka { font-size: 32px; font-weight: 800; margin-bottom: 5px; line-height: 1; color: #2d3436; }
        .bento-card:not(.card-omzet) .card-info small { font-size: 12px; display: flex; align-items: center; gap: 5px; font-weight: 600; color: #b2bec3; }
        .card-icon { position: absolute; top: 20px; right: 20px; width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
        .icon-yellow { background: #fff3e0; color: #f57c00; }
        .icon-blue { background: #e3f2fd; color: #1565c0; }
        .icon-purple { background: #f3e5f5; color: #6a1b9a; }
        .icon-red { background: #ffebee; color: #c62828; }
        .label-kritis { color: #d32f2f !important; }
        .card-omzet { grid-column: span 2; }
        .card-omzet .angka { font-size: 42px; font-weight: 800; margin-bottom: 5px; line-height: 1; }
        .card-omzet .card-info h4 { margin: 0 0 10px 0; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; }
        .card-omzet .card-info small { font-size: 12px; display: flex; align-items: center; gap: 5px; font-weight: 600; }
        .omzet-putih { background: white; border: 1px solid #f0f0f0; }
        .omzet-putih .card-info h4 { color: #8898aa; }
        .omzet-putih .angka { color: #2d3436; }
        .omzet-putih .card-info small { color: #b2bec3; }
        .omzet-putih .card-icon { background: #f4f7fc; color: #aab8c5; }
        .omzet-biru { background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%); color: white; border: none;}
        .omzet-hijau { background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%); color: white; border: none;}
        .omzet-oranye { background: linear-gradient(135deg, #f57c00 0%, #e65100 100%); color: white; border: none;}
        .omzet-merah { background: linear-gradient(135deg, #ff0000 0%, #cc0000 100%); color: white; border: none;}
        .omzet-biru .card-info h4, .omzet-biru .card-info small, .omzet-biru .angka,
        .omzet-hijau .card-info h4, .omzet-hijau .card-info small, .omzet-hijau .angka,
        .omzet-oranye .card-info h4, .omzet-oranye .card-info small, .omzet-oranye .angka,
        .omzet-merah .card-info h4, .omzet-merah .card-info small, .omzet-merah .angka { color: white; }
        .omzet-biru .card-icon, .omzet-hijau .card-icon, .omzet-oranye .card-icon, .omzet-merah .card-icon { background: rgba(255,255,255,0.2); color: white; }
        .layout-bawah { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
        .box-chart { grid-column: span 2; }
        .box-wrapper { background: white; padding: 25px; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #f0f0f0; overflow-x: auto; }
        .box-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #f9f9f9; padding-bottom: 15px;}
        .box-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: #2d3436; display: flex; align-items: center; gap: 10px;}
        table { width: 100%; border-collapse: collapse; min-width: 300px; }
        thead th { text-align: left; padding: 12px; color: #8898aa; font-size: 11px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #eee; }
        tbody td { padding: 12px; border-bottom: 1px solid #f9f9f9; vertical-align: middle; color: #444; font-size: 13px; }
        .badge { padding: 5px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block;}
        .badge-blue { background: #e3f2fd; color: #1565c0; }
        .badge-orange { background: #fff3e0; color: #e65100; }
        .top-menu-list { list-style: none; padding: 0; margin: 0; }
        .top-menu-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f9f9f9; }
        .top-menu-rank { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; color: white; background: #ddd; margin-right: 12px; }
        .rank-1 { background: #FFD700; } .rank-2 { background: #C0C0C0; } .rank-3 { background: #CD7F32; }
        .top-menu-name { flex-grow: 1; font-weight: 600; font-size: 13px; color: #333; }
        .top-menu-qty { font-weight: 800; color: #ff0000; font-size: 14px; }
        .timeline { position: relative; padding-left: 15px; margin-top: 10px; }
        .timeline::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 2px; background: #eee; }
        .timeline-item { position: relative; padding-bottom: 20px; padding-left: 20px; }
        .timeline-icon { position: absolute; left: -26px; top: 0; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; z-index: 2; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.1);}
        .timeline-time { font-size: 11px; color: #8898aa; font-weight: bold; margin-bottom: 5px; display:flex; align-items:center; gap:5px;}
        .timeline-content { font-size: 12px; color: #444; background: #fafafa; padding: 12px; border-radius: 8px; border: 1px solid #f0f0f0; line-height: 1.4;}
        .timeline-outlet { font-weight: 800; color: #2d3436; margin-bottom: 3px; display: block; font-size: 13px;}
        .placeholder-card { background:white; border:1px solid #f0f0f0; border-radius:16px; padding:30px; max-width:760px; box-shadow:0 4px 15px rgba(0,0,0,0.03); }
        .placeholder-card p { color:#636e72; line-height:1.7; }
        .btn-legacy { display:inline-flex; align-items:center; gap:8px; background:#2d3436; color:white; text-decoration:none; padding:12px 16px; border-radius:8px; font-weight:700; margin-top:12px; }
        /* Native owner UI compatibility. Keep the old visual language; MVC only moves logic. */
        .card, .owner-card { background:white; padding:25px; border-radius:12px; box-shadow:0 5px 20px rgba(0,0,0,0.02); border:1px solid #f0f0f0; overflow-x:auto; }
        .owner-actions { display:flex; gap:10px; flex-wrap:wrap; align-items:center; margin-bottom:20px; }
        .btn, .owner-btn { padding:8px 16px; border-radius:6px; font-size:13px; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:5px; cursor:pointer; border:none; transition:0.2s; font-family:'Segoe UI', sans-serif; }
        .btn-add, .owner-btn-primary { background:#ff0000; color:white; box-shadow:0 4px 10px rgba(255,0,0,0.2); margin-bottom:20px; padding:12px 20px; }
        .btn-add:hover, .owner-btn-primary:hover { background:#d60000; }
        .btn-edit, .owner-btn-edit { background:#fff3cd; color:#856404; border:1px solid #ffeeba; }
        .btn-del, .btn-danger, .owner-btn-danger { background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; }
        .btn-muted, .owner-btn-muted { background:#eef2f7; color:#445; }
        .img-thumb { width:50px; height:50px; border-radius:8px; object-fit:cover; background:#f0f0f0; border:1px solid #eee; }
        .owner-form { max-width:640px; margin:0 auto; }
        .owner-form label { display:block; margin-bottom:8px; font-weight:600; color:#555; font-size:14px; }
        .owner-form input[type="text"], .owner-form input[type="password"], .owner-form input[type="number"], .owner-form input[type="file"], .owner-form select, .owner-form textarea { width:100%; padding:12px; border:1px solid #ddd; border-radius:8px; margin-bottom:20px; box-sizing:border-box; font-size:14px; font-family:'Segoe UI', sans-serif; }
        .owner-form input:focus, .owner-form select:focus, .owner-form textarea:focus { outline:none; border-color:#ff0000; box-shadow:0 0 0 3px rgba(255,0,0,0.08); }
        .owner-form-row { display:flex; gap:15px; }
        .owner-form-row > div { flex:1; min-width:0; }
        .owner-two-col { display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap:20px; }
        .current-image { width:120px; height:120px; object-fit:cover; border-radius:10px; border:1px solid #ddd; display:block; margin-bottom:12px; }
        .alert-owner { background:#e8f5e9; color:#1b5e20; border:1px solid #c8e6c9; border-radius:8px; padding:12px 15px; font-size:14px; margin-bottom:18px; font-weight:600; }
        .alert-error { background:#ffebee; color:#b71c1c; border-color:#ffcdd2; }
        .badge-pusat, .bg-pusat { background:#333; color:white; }
        .badge-outlet, .bg-outlet { background:#ff0000; color:white; }
        .badge-mitra, .bg-mitra { background:#ff9800; color:white; }
        .badge-cabang, .bg-cabang { background:#2196f3; color:white; }
        .bg-pending { background:#fff3e0; color:#e65100; }
        .bg-approved { background:#e8f5e9; color:#2e7d32; }
        .bg-rejected { background:#ffebee; color:#c62828; }
        .native-filter { background:white; padding:20px; border-radius:12px; box-shadow:0 5px 20px rgba(0,0,0,0.02); border:1px solid #f0f0f0; margin-bottom:25px; }
        .native-filter form { display:grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap:12px; align-items:end; }
        .native-filter label { display:block; font-size:13px; font-weight:700; color:#555; margin-bottom:6px; }
        .native-filter input, .native-filter select { width:100%; padding:12px; border:1px solid #ddd; border-radius:8px; box-sizing:border-box; font-size:14px; font-family:'Segoe UI', sans-serif; background:white; }
        .native-filter button { width:100%; justify-content:center; margin:0; height:43px; }
        .native-table { width:100%; border-collapse:collapse; min-width:800px; }
        .native-table thead th { text-align:left; padding:18px 25px; background:#fafafa; color:#8898aa; font-size:11px; font-weight:800; text-transform:uppercase; border-bottom:1px solid #eee; white-space:nowrap; }
        .native-table tbody td { padding:15px 25px; border-bottom:1px solid #f9f9f9; font-size:14px; vertical-align:middle; color:#444; }
        .native-empty { text-align:center; color:#999; padding:35px !important; font-size:16px; }
        .native-total-row td { border-top:1px solid #eee; background:#fff; font-size:18px; font-weight:800; }
        .native-panel { background:white; border-radius:12px; box-shadow:0 5px 20px rgba(0,0,0,0.02); border:1px solid #f0f0f0; overflow:hidden; }
        .native-panel-title { text-align:center; background:#f4f7fc; border-bottom:1px solid #eee; margin:0; padding:22px; font-size:22px; color:#2d3436; }
        .native-panel-body { padding:30px; }
        .native-form label { display:block; margin-bottom:8px; font-weight:700; color:#636e72; font-size:14px; }
        .native-form input, .native-form textarea, .native-form select { width:100%; padding:12px; border:1px solid #ddd; border-radius:8px; margin-bottom:20px; box-sizing:border-box; font-family:'Segoe UI', sans-serif; font-size:14px; }
        .native-form textarea { min-height:110px; resize:vertical; }
        .native-info { background:#e3f2fd; border-left:4px solid #2196f3; color:#333; border-radius:8px; padding:15px 18px; margin-bottom:20px; font-size:14px; }
        .native-status { display:inline-flex; align-items:center; gap:6px; border-radius:7px; padding:7px 13px; font-size:11px; font-weight:800; text-transform:uppercase; }
        .status-aman, .status-disetujui { background:#e8f5e9; color:#2e7d32; }
        .status-kritis, .status-ditolak { background:#ffebee; color:#c62828; }
        .status-pending { background:#fff3e0; color:#e65100; }
        .btn-wa { background:#25d366; color:white; }
        .btn-wa:hover { background:#1ebe57; }
        .log-time { font-family:'Consolas', monospace; font-weight:800; line-height:1.2; }
        .log-date { color:#999; font-size:11px; font-weight:500; display:block; }
        .log-dot { width:6px; height:6px; display:inline-block; border-radius:50%; margin-right:7px; background:#2196f3; }
        .log-dot.orange { background:#ff9800; }
        .log-action { display:inline-block; padding:7px 12px; border-radius:6px; font-size:11px; font-weight:800; text-transform:uppercase; line-height:1.1; }
        .log-login { background:#e3f2fd; color:#1565c0; }
        .log-stok { background:#fff3e0; color:#e65100; }
        .log-status { background:#ffebee; color:#c62828; }
        @media (max-width: 1200px) { .bento-grid { grid-template-columns: repeat(2, 1fr); } .card-omzet { grid-column: span 2; } .layout-bawah { grid-template-columns: repeat(2, 1fr); } .box-chart { grid-column: span 2; } }
        @media (max-width: 768px) { .bento-grid, .layout-bawah, .owner-two-col, .native-filter form { grid-template-columns: 1fr; } .card-omzet, .box-chart { grid-column: span 1; } .owner-form-row { flex-direction:column; gap:0; } }
    </style>
</head>
<body>
    <div class="overlay" onclick="toggleSidebar()"></div>
