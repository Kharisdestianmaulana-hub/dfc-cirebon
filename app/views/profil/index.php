    <style>
        /* CSS KHUSUS HALAMAN PROFIL */
        .hero-profil {
            background-color: #fd020f; 
            color: white;
            text-align: center;
            padding: 80px 20px 60px;
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 30px;
            margin-top: 0;
        }
        .hero-profil h1 {
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        .content-container {
            max-width: 800px;
            margin: -40px auto 40px;
            padding: 0 20px;
            position: relative;
            z-index: 10;
        }
        .card-profil {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .section-title {
            color: #fd020f; 
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 1.2rem;
            border-left: 5px solid #fd020f;
            padding-left: 10px;
        }
        .visi-misi {
            background-color: #fff5f5;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #ffe0e0;
        }
        
        /* Ikon SVG */
        .icon-box {
            display: flex;
            justify-content: space-around;
            text-align: center;
            margin-top: 20px;
            flex-wrap: wrap;
            gap: 20px;
        }
        .icon-item {
            flex: 1;
            min-width: 100px;
        }
        .icon-item svg {
            width: 50px;
            height: 50px;
            fill: #fd020f;
            margin-bottom: 10px;
        }
        .icon-item strong {
            display: block;
            color: #333;
        }
    </style>

    <section class="hero-profil">
        <h1 style="font-size: 2.5rem; margin-bottom: 10px;">Tentang DFC</h1>
        <p style="font-size: 1.1rem; opacity: 0.9;">Mengenal lebih dekat Dexpress Fried Chicken Cirebon</p>
    </section>

    <div class="content-container">
        
        <div class="card-profil">
            <h2 class="section-title">Cerita Kami</h2>
            <p style="line-height: 1.6; color: #555;">
                <?= nl2br($data['profil']['sejarah'] ?? ''); ?>
            </p>
        </div>

        <div class="card-profil">
            <h2 class="section-title">Visi & Misi</h2>
            <div class="visi-misi">
                <h3>Visi</h3>
                <p><?= nl2br($data['profil']['visi'] ?? ''); ?></p>
                
                <h3 style="margin-top:15px;">Misi</h3>
                <p style="color: #555;">
                    <?= nl2br($data['profil']['misi'] ?? ''); ?>
                </p>
            </div>
        </div>

        <div class="card-profil">
            <h2 class="section-title">Kenapa DFC?</h2>
            <div class="icon-box">
                <div class="icon-item">
                    <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M132.9 162.3c-27.2-13.6-43.5-42.8-40.8-73.2 2.1-23.3 15-44.2 34.6-56.1C189.9 -3.1 278.8-9.2 344 19.3c35.6 15.6 63.6 44.5 78.2 80.8 17.6 43.5 13.9 93.3-9.8 133.2l-37.4 63c15.7 6.4 29.5 16.6 40.1 29.8 28.5 35.6 24.3 87.7-9.3 118.2-12.7 11.5-29.2 17.8-46.3 17.8h-1.3c-23.7-.8-45.9-12.1-60.5-30.9-19.4-24.9-18.3-60.1 2.6-83.8 6.5-7.4 14.6-13.3 23.6-17.3l26.4-44.5c11.9-20 13.7-44.9 4.9-66.6-7.3-18.1-21.3-32.6-39.1-40.4-32.6-14.2-77.1-11.2-108.6 7.4-9.6 5.7-16 16.1-17.1 27.9-1.3 15.2 6.8 29.8 20.6 36.7l126.3 63.2c6.2 3.1 8.7 10.6 5.6 16.8-3.1 6.2-10.6 8.7-16.8 5.6L132.9 162.3z"/></svg>
                    <strong>Renyah & Gurih</strong>
                </div>
                <div class="icon-item">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58.55 0 1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41 0-.55-.23-1.06-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z"/></svg>
                    <strong>Harga Hemat</strong>
                </div>
                <div class="icon-item">
                    <svg viewBox="0 0 640 512" xmlns="http://www.w3.org/2000/svg"><path d="M434.7 64h-9.7c-30.8 0-58.4 16.4-73.4 42.8l-37.4 65.8-57.8-100.2C243.2 49.3 219.1 32 192 32H96C78.3 32 64 46.3 64 64v128c0 17.7 14.3 32 32 32h32c17.7 0 32-14.3 32-32V96h32l84.2 146.6c11.9 20.7 41.7 20.7 53.6 0l110.4-192c4.4-7.7 12.6-12.6 21.5-12.6h9.7c17.7 0 32-14.3 32-32s-14.3-32-32-32zM624.4 330.1l-128-80c-26.6-16.6-61.2-6.5-75.7 22.1l-18.5 36.4-18.4-36.4c-14.4-28.6-49.1-38.7-75.7-22.1l-128 80c-20 12.5-27.1 38.3-16.1 59.5l68.1 131.6c7.7 14.9 23 24.3 39.8 24.3h196.4c16.8 0 32.1-9.4 39.8-24.3l68.1-131.6c11-21.2 3.9-47-16.1-59.5z"/></svg>
                    <strong>Mitra UMKM</strong>
                </div>
            </div>
        </div>

    </div>
