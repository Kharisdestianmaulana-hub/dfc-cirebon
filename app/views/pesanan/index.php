    <style>
        header { position: sticky; top: 0; z-index: 10000; }
        .outlet-bar {
            background: #fd020f; 
            padding: 15px; 
            text-align: center; 
            position: sticky; 
            top: 70px;
            z-index: 9999;
            box-shadow: 0 4px 10px rgba(253, 2, 15, 0.3);
        }
        @media (max-width: 768px) { .outlet-bar { top: 60px; } }
    </style>

    <div class="outlet-bar">
        <label style="color: white; font-weight: bold; margin-right: 10px;">Pilih Outlet Terdekat:</label>
        <select id="outlet-select-top" style="padding: 8px 15px; border-radius: 20px; border: none; font-weight: bold; max-width: 100%; outline: none; cursor: pointer;">
            <?php
            foreach($data['outlets'] as $o) {
                $no_hp = $o['no_hp'] ?? ''; 
                if(substr($no_hp, 0, 1) == '0') {
                    $no_hp = '62' . substr($no_hp, 1);
                }
                echo "<option value='".$o['username_admin']."' data-nomor='".$no_hp."'>".$o['nama']."</option>";
            }
            ?>
        </select>
    </div>

    <section class="section-menu" style="padding-top: 30px; padding-bottom: 120px;">
      <div class="container">
        <h1 class="section-title" style="font-size: 24px; text-align:center;">Menu DFC Dexpress Fried Chicken</h1>
        <p style="text-align:center; color:#777; font-size:14px; margin-bottom:15px;">
            Pusatnya ayam geprek dan fried chicken enak di Cirebon. 
            Melayani area Tuparev, Sumber, dan sekitarnya.
        </p>
        <p style="text-align:center; font-size:13px; color:#666; margin-bottom:20px; background: #fff3cd; padding: 10px; border-radius: 8px; display: inline-block; margin-left: auto; margin-right: auto;">
            <i class="fas fa-info-circle" style="color: #856404;"></i> Tulis request rasa (Original/Pedas) di kolom catatan per menu.
        </p>

        <div class="menu-grid">
            <?php foreach($data['menu'] as $row) {
                $gambar_db = $row['gambar'];
                $nama_file_saja = basename($gambar_db); 
                if (!empty($nama_file_saja)) { 
                    $img_src = BASEURL . "/assets/img/owner/" . $nama_file_saja; 
                } else { 
                    $img_src = "https://via.placeholder.com/300?text=No+Image"; 
                }
            ?>
            <div class="menu-card" 
                 data-id="<?= $row['id']; ?>"
                 data-name="<?= $row['nama']; ?>" 
                 data-price="<?= $row['harga']; ?>"
                 <?php
                 foreach($data['list_outlet_usernames'] as $usr) {
                     $stok = $row['stok_' . $usr] ?? 0;
                     $status = $data['status_toko'][$usr] ?? 'tutup';
                     echo " data-stok-$usr='$stok' data-status-$usr='$status' ";
                 }
                 ?>
            >
                <img src="<?= $img_src; ?>" class="menu-img" alt="<?= $row['nama']; ?>" onerror="this.src='https://via.placeholder.com/300?text=Error+Loading';">
                
                <h3><?= $row['nama']; ?></h3>
                <div class="menu-price">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></div>
                
                <p style="font-size: 0.9rem; color: #666; margin-top: 5px">
                    Sisa Stok: <strong class="display-stok">...</strong>
                </p>

                <div class="qty-control">
                    <button class="btn-qty minus">-</button>
                    <input type="number" class="qty-val" value="0" min="0" readonly>
                    <button class="btn-qty plus">+</button>
                </div>
                <input type="text" class="item-note" placeholder="Catatan: Pedas/Original..." style="display:none; width:100%; margin-top:10px; padding:8px; border:1px solid #ddd; border-radius:5px; font-size:12px;">
            </div>
            <?php } ?>
        </div>
      </div>
    </section>

    <!-- POST Route to Checkout Method -->
    <form id="form-checkout-data" action="<?= BASEURL; ?>/pesanan/checkout" method="POST" style="display:none;">
        <input type="hidden" name="cart_data" id="input-cart-data">
        <input type="hidden" name="outlet_name" id="input-outlet-name">
        <input type="hidden" name="outlet_phone" id="input-outlet-phone">
    </form>

    <div class="checkout-bar">
      <div class="container checkout-content" style="justify-content: flex-end;"> 
        <div class="checkout-right">
          <div id="shop-status" style="font-weight:bold; margin-right:15px;"></div>
          <div class="total-price">Total: Rp <span id="grand-total">0</span></div>
          
          <button id="btn-checkout" class="btn btn-primary" onclick="lanjutCheckout()" disabled>
            <i class="fas fa-shopping-cart"></i> Checkout
          </button>
        </div>
      </div>
    </div>
    
    <!-- Spacer agar bar checkout tidak menindih footer -->
    <div style="height: 120px;"></div>

    <script>
        const outletSelect = document.getElementById("outlet-select-top");
        
        function updateTampilan() {
            const outlet = outletSelect.value; 
            const cards = document.querySelectorAll(".menu-card");
            const statusLabel = document.getElementById("shop-status");
            const btnCheckout = document.getElementById("btn-checkout");
            
            let tokoTutup = false;
            let totalBelanja = 0;
            let adaBarang = false;

            cards.forEach(card => {
                const stok = parseInt(card.getAttribute('data-stok-' + outlet)) || 0;
                const status = card.getAttribute('data-status-' + outlet);
                const harga = parseInt(card.getAttribute('data-price'));
                
                if(status === 'tutup') tokoTutup = true;

                const displayStok = card.querySelector(".display-stok");
                const btnPlus = card.querySelector(".plus");
                const inputQty = card.querySelector(".qty-val");
                const inputNote = card.querySelector(".item-note");
                
                let qty = parseInt(inputQty.value);

                if(qty > 0) {
                    totalBelanja += (qty * harga);
                    adaBarang = true;
                    inputNote.style.display = "block"; 
                } else {
                    inputNote.style.display = "none";
                }

                displayStok.innerText = stok + " pcs";

                if (stok <= 0) {
                    displayStok.innerText = "Habis!";
                    displayStok.style.color = "red";
                    btnPlus.disabled = true;
                    btnPlus.style.background = "#ccc";
                    if(qty > 0) inputQty.value = 0; 
                } else {
                    displayStok.style.color = "#666";
                    btnPlus.disabled = false;
                    btnPlus.style.background = ""; 
                }

                if(qty >= stok) btnPlus.disabled = true;
            });

            document.getElementById("grand-total").innerText = new Intl.NumberFormat('id-ID').format(totalBelanja);

            if (tokoTutup) {
                statusLabel.innerText = "MAAF, OUTLET INI TUTUP";
                statusLabel.style.color = "red";
                btnCheckout.disabled = true;
                btnCheckout.style.background = "#555";
                btnCheckout.innerText = "Outlet Tutup";
            } else if (!adaBarang) {
                statusLabel.innerText = "";
                btnCheckout.disabled = true;
                btnCheckout.style.background = "#ccc"; 
                btnCheckout.innerText = "Pilih Menu Dulu";
            } else {
                statusLabel.innerText = "";
                btnCheckout.disabled = false;
                btnCheckout.style.background = "#fd020f"; 
                btnCheckout.innerHTML = '<i class="fas fa-shopping-cart"></i> Checkout';
            }
        }

        function lanjutCheckout() {
            const selectedOption = outletSelect.options[outletSelect.selectedIndex];
            const nomorWA = selectedOption.getAttribute('data-nomor');
            const namaOutlet = selectedOption.text;

            let keranjang = [];
            const cards = document.querySelectorAll(".menu-card");

            cards.forEach(card => {
                const qty = parseInt(card.querySelector(".qty-val").value);
                if (qty > 0) {
                    const namaMenu = card.getAttribute('data-name');
                    const harga = parseInt(card.getAttribute('data-price'));
                    const catatan = card.querySelector(".item-note").value;
                    
                    keranjang.push({
                        nama: namaMenu,
                        harga: harga,
                        qty: qty,
                        catatan: catatan
                    });
                }
            });

            if(keranjang.length === 0) return;

            document.getElementById('input-cart-data').value = JSON.stringify(keranjang);
            document.getElementById('input-outlet-name').value = namaOutlet;
            document.getElementById('input-outlet-phone').value = nomorWA;

            document.getElementById('form-checkout-data').submit();
        }
        
        document.addEventListener("click", function(e) {
            if(e.target.classList.contains("plus")) {
                const input = e.target.parentElement.querySelector(".qty-val");
                input.value = parseInt(input.value) + 1;
                updateTampilan();
            }
            if(e.target.classList.contains("minus")) {
                const input = e.target.parentElement.querySelector(".qty-val");
                if(parseInt(input.value) > 0) {
                    input.value = parseInt(input.value) - 1;
                    updateTampilan();
                }
            }
        });

        outletSelect.addEventListener("change", function() {
            document.querySelectorAll(".qty-val").forEach(el => el.value = 0);
            document.querySelectorAll(".item-note").forEach(el => el.value = "");
            updateTampilan();
        });

        document.addEventListener("DOMContentLoaded", updateTampilan);
    </script>
