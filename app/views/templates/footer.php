    </main>
    <footer>
      <div class="container footer-content">
        <div class="footer-left">
          <h3 style="margin:0; font-size: 24px;">DFC</h3>
          <p style="margin:5px 0; font-size: 14px; opacity: 0.8;">
            Renyah, Gurih, dan Menguntungkan.
          </p>
        </div>

        <div class="footer-right">
          <a href="<?= BASEURL; ?>/">Beranda</a>
          <a href="<?= BASEURL; ?>/menu">Menu</a>
          <a href="<?= BASEURL; ?>/lokasi">Lokasi</a>
        </div>
      </div>

      <hr style="border:0; border-top:1px solid rgba(255,255,255,0.1); margin: 20px 0;">

      <div class="container copyright-box" style="text-align: center;">
        <p style="margin-bottom: 10px;">
          © <span id="autoYear"></span> <b>Dexpress Fried Chicken</b>. All Rights Reserved.
        </p>
      </div>
    </footer>

    <button class="chatbot-toggler" onclick="toggleChat()">
        <span class="material-symbols-outlined">chat</span>
        <span class="material-symbols-outlined close-icon">close</span>
    </button>

    <div class="chatbot-container" id="chatbot">
        <header class="chat-header">
            <div class="header-info">
                <img src="<?= BASEURL; ?>/assets/img/Logodexpress (2).jpg" alt="Logo" onerror="this.src='https://via.placeholder.com/40';">
                <div>
                    <h4>CS Dexpress 🤖</h4>
                    <p>Online (Bot)</p>
                </div>
            </div>
            <button class="close-btn" onclick="toggleChat()">
                <span class="material-symbols-outlined">close</span>
            </button>
        </header>

        <ul class="chat-box" id="chatBox">
            <li class="chat incoming">
                <span class="material-symbols-outlined icon-bot">smart_toy</span>
                <p>Halo! Selamat datang di <b>Dexpress Fried Chicken</b> 🍗.<br>Ada yang bisa saya bantu?</p>
            </li>
        </ul>

        <div class="chat-input">
            <div class="quick-options">
                <button onclick="botReply('menu')">🍗 Info Menu</button>
                <button onclick="botReply('lokasi')">📍 Lokasi Outlet</button>
                <button onclick="botReply('mitra')">🤝 Gabung Mitra</button>
                <button onclick="botReply('admin')" style="background: #25D366; color: white; border-color:#25D366;">💬 Chat Admin WA</button>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <button id="scrollTopBtn" title="Kembali ke atas">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
        </svg>
    </button>

    <script>
        // --- 1. VARIABEL CHATBOT ---
        const chatbotContainer = document.querySelector(".chatbot-container");
        const chatBox = document.getElementById("chatBox");
        const body = document.body;

        // --- 2. FUNGSI CHATBOT ---
        function toggleChat() {
            body.classList.toggle("show-chatbot");
        }

        function addMessage(text, type) {
            const li = document.createElement("li");
            li.classList.add("chat", type);
            
            let content = '';
            if(type === 'incoming') {
                content = `<span class="material-symbols-outlined icon-bot">smart_toy</span><p>${text}</p>`;
            } else {
                content = `<p>${text}</p>`;
            }
            
            li.innerHTML = content;
            chatBox.appendChild(li);
            chatBox.scrollTop = chatBox.scrollHeight; 
        }

        function botReply(option) {
            let userText = "";
            if(option === 'menu') userText = "Info Menu dong?";
            if(option === 'lokasi') userText = "Outlet dimana aja?";
            if(option === 'mitra') userText = "Cara jadi Mitra?";
            if(option === 'admin') userText = "Chat Admin WhatsApp";
            
            addMessage(userText, 'outgoing');

            setTimeout(() => {
                let botText = "";

                if (option === 'menu') {
                    botText = "Kami punya berbagai varian:<br>🍗 <b>Original</b><br>🌶️ <b>Spicy</b><br>🔨 <b>Geprek</b><br><br>Cek harga lengkap di halaman <a href='<?= BASEURL; ?>/menu' style='color:blue; text-decoration:underline;'>Menu</a>.";
                } 
                else if (option === 'lokasi') {
                    botText = "Outlet DFC tersebar di:<br>📍 <b>Sutawinangun</b><br>📍 <b>Gegesik</b><br>📍 <b>Tuparev</b><br><br>Cek peta di menu <a href='<?= BASEURL; ?>/lokasi' style='color:blue; text-decoration:underline;'>Lokasi</a>.";
                } 
                else if (option === 'mitra') {
                    botText = "Mau punya usaha Fried Chicken? 🤝<br>Modal terjangkau & Tanpa Royalti Fee!<br><br>Silakan isi <b>Formulir Pendaftaran</b> di halaman Portal Mitra <a href='<?= BASEURL; ?>/kemitraan' style='color:blue; text-decoration:underline;'>Di sini</a>.";
                } 
                else if (option === 'admin') {
                    botText = "Siap! Saya alihkan ke WhatsApp Admin sekarang... 🚀";
                    setTimeout(() => {
                        window.open("https://wa.me/6281222453572?text=Halo%20Admin%20DFC,%20saya%20mau%20tanya...", "_blank");
                    }, 1000);
                }

                addMessage(botText, 'incoming');
            }, 600);
        }
        
        // --- 3. LOGIKA PRELOADER ---
        const preloader = document.getElementById("preloader");
        if (sessionStorage.getItem("sudah_mampir") === "ya") {
            preloader.style.display = "none";
        } else {
            window.addEventListener("load", function() {
                setTimeout(() => {
                    preloader.style.display = "none";
                    sessionStorage.setItem("sudah_mampir", "ya");
                }, 1500); 
            });
        }
        
        function updateHeroGreeting() {
            const hour = new Date().getHours();
            const element = document.getElementById("hero-greeting");
            
            let sapaan = "Halo";
            let tanya = "Mau makan apa hari ini?";

            if (hour >= 4 && hour < 10) {
                sapaan = "Selamat Pagi ☀️";
                tanya = "Sarapan ayam hangat enak nih!";
            } else if (hour >= 10 && hour < 15) {
                sapaan = "Selamat Siang 🌤️";
                tanya = "Udah jam makan siang, laper kan?";
            } else if (hour >= 15 && hour < 19) {
                sapaan = "Selamat Sore 🌥️";
                tanya = "Ngemil ayam krispi yuk!";
            } else {
                sapaan = "Selamat Malam 🌙";
                tanya = "Lapar tengah malam? DFC solusinya.";
            }

            if(element) {
                element.innerHTML = `<span style="color: #fd020f;">${sapaan}.</span><br><span style="font-weight:400; font-size: 0.9em; color: #333;">${tanya}</span>`;
            }
        }
        updateHeroGreeting();

        document.getElementById("autoYear").textContent = new Date().getFullYear();
    </script>
    <script src="<?= BASEURL; ?>/assets/js/script.js"></script>
</body>
</html>
