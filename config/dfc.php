<?php

// =====================================================================
// Konfigurasi khusus aplikasi DFC Cirebon.
// Memusatkan nilai yang pada sistem native tersebar sebagai konstanta.
// =====================================================================

return [

    // -----------------------------------------------------------------
    // Kredensial owner. Pada sistem native nilai ini tertulis langsung
    // di dalam model Owner_model. Di sini dipindah ke konfigurasi.
    // -----------------------------------------------------------------
    'owner_username' => env('DFC_OWNER_USERNAME', 'bigboss'),
    'owner_password' => env('DFC_OWNER_PASSWORD', 'dfcjayaterus'),

    // -----------------------------------------------------------------
    // Nomor WhatsApp admin pusat untuk tautan pesan cepat konsumen
    // dan halaman pelacakan pesanan.
    // -----------------------------------------------------------------
    'wa_admin' => env('DFC_WA_ADMIN', '6281222453572'),

    // -----------------------------------------------------------------
    // Batas stok dianggap kritis, dipakai pada badge dashboard owner.
    // -----------------------------------------------------------------
    'batas_stok_kritis' => 10,

    // -----------------------------------------------------------------
    // Ukuran maksimal unggahan gambar menu dalam kilobyte.
    // -----------------------------------------------------------------
    'maks_gambar_kb' => 4096,
];
