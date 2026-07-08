    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

    <style>
        /* --- VARIABLE WARNA --- */
        :root { --primary: #e60023; --dark: #1a1a1a; --gray: #f4f6f8; --border: #ddd; }
        
        /* Layout Peta */
        .dfc-locator-container { display: flex; height: 75vh; min-height: 500px; max-width: 1200px; margin: 20px auto; background: white; box-shadow: 0 0 20px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden; width: 100%; }
        .dfc-sidebar { width: 350px; overflow-y: auto; border-right: 1px solid var(--border); display: flex; flex-direction: column; background: #fff; flex-shrink: 0; text-align: left; }
        .search-box { padding: 15px; border-bottom: 1px solid var(--border); position: sticky; top: 0; background: white; z-index: 1000; }
        .search-box input { width: 100%; padding: 12px; border: 2px solid #eee; border-radius: 8px; font-size: 14px; box-sizing: border-box; }
        .location-item { padding: 20px; border-bottom: 1px solid #eee; cursor: pointer; transition: 0.2s; text-align: left; }
        .location-item:hover { background: #fff5f5; }
        .location-item.active { background: #ffebeb; border-left: 5px solid var(--primary); }
        .tag { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; margin-bottom: 8px; text-transform: uppercase; }
        .tag-Pusat { background: #1a1a1a; color: white; }
        .tag-Outlet { background: var(--primary); color: white; }
        .tag-Mitra { background: #ff9800; color: white; }
        .tag-Cabang { background: #2196f3; color: white; }
        .loc-name { font-weight: bold; font-size: 16px; margin-bottom: 5px; color: var(--dark); }
        .loc-addr { font-size: 13px; color: #666; line-height: 1.4; }
        .btn-maps { display: inline-block; margin-top: 10px; text-decoration: none; color: var(--primary); font-size: 12px; font-weight: bold; border: 1px solid var(--primary); padding: 5px 10px; border-radius: 5px; }
        .btn-maps:hover { background: var(--primary); color: white; }
        .dfc-map-wrapper { flex: 1; position: relative; z-index: 1; background: #eee; width: 100%; height: 100%; }
        #map { width: 100%; height: 100%; position: absolute; top:0; left:0; bottom:0; right:0; }

        /* --- RESPONSIF HP (LAYOUT & MENU) --- */
        @media (max-width: 768px) { 
            .dfc-locator-container { flex-direction: column-reverse; height: 85vh; margin: 0; border-radius: 0; width: 100%; } 
            .dfc-map-wrapper { height: 45%; max-height: 45%; flex: none; position: relative; } 
            .dfc-sidebar { width: 100%; height: 55%; flex: none; border-right: none; border-top: 4px solid #fff; }
            .page-header h1 { font-size: 20px; }
            .hamburger { position: relative; z-index: 9999; }
        }
    </style>

    <div class="page-header" style="background: #f9f9f9; padding: 20px 0; text-align: center;">
      <div class="container">
        <h1 style="margin:0; color:#333;">Lokasi Outlet</h1>
        <p style="margin:5px 0; color:#666;">Temukan outlet Dexpress terdekat di peta</p>
      </div>
    </div>

    <div class="dfc-locator-container container">
        <div class="dfc-sidebar">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Cari nama daerah..." onkeyup="filterList()">
            </div>
            <div id="locationList"></div>
        </div>
        <div class="dfc-map-wrapper">
            <div id="map"></div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const locations = <?= $data['lokasi_json']; ?>;
        const map = L.map('map').setView([-6.732023, 108.552316], 13);
        
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '© OpenStreetMap' }).addTo(map);

        const listContainer = document.getElementById('locationList');
        const markers = []; 

        function renderLocations(data) {
            listContainer.innerHTML = "";
            markers.forEach(m => map.removeLayer(m));
            markers.length = 0;

            if(data.length === 0) {
                listContainer.innerHTML = "<div style='padding:20px; text-align:center; color:#666;'>Belum ada data lokasi.</div>";
                return;
            }

            data.forEach((loc, index) => {
                const marker = L.marker([loc.lat, loc.lng]).addTo(map);
                marker.bindPopup(`<b>${loc.name}</b><br>${loc.address}`);
                markers.push(marker);

                const item = document.createElement('div');
                item.className = 'location-item';
                let badgeClass = `tag-${loc.category}`;
                
                const gmapsLink = `https://www.google.com/maps/dir/?api=1&destination=${loc.lat},${loc.lng}`;

                item.innerHTML = `
                    <span class="tag ${badgeClass}">${loc.category}</span>
                    <div class="loc-name">${loc.name}</div>
                    <div class="loc-addr">${loc.address}</div>
                    <a href="${gmapsLink}" target="_blank" class="btn-maps">
                        <i class="fa-solid fa-diamond-turn-right"></i> Rute
                    </a>
                `;

                item.addEventListener('click', function() {
                    document.querySelectorAll('.location-item').forEach(el => el.classList.remove('active'));
                    this.classList.add('active');
                    
                    map.flyTo([loc.lat, loc.lng], 16, { duration: 1.5 });
                    marker.openPopup();
                    
                    if(window.innerWidth <= 768) {
                        document.querySelector('.locator-container').scrollIntoView({ behavior: 'smooth' });
                    }
                });
                listContainer.appendChild(item);
            });
        }

        function filterList() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            const filtered = locations.filter(loc => loc.name.toLowerCase().includes(query) || loc.address.toLowerCase().includes(query));
            renderLocations(filtered);
        }

        window.addEventListener('resize', function() { setTimeout(function(){ map.invalidateSize(); }, 400); });
        renderLocations(locations);
    </script>
