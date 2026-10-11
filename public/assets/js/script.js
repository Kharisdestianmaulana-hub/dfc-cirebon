document.addEventListener("DOMContentLoaded", function () {

  // --- 1. CEK STATUS TOKO (BANNER ATAS) ---
  let dataStatusToko = {}; 

  function cekStatusBerkala() {
      const baseUrl = window.BASEURL || '';
      fetch(baseUrl + '/pesanan/status-outlet')
      .then(response => {
          if (!response.ok) return null;
          return response.json();
      })
      .then(data => {
          if(!data) return;

          dataStatusToko = data; 

          const banner = document.getElementById('shop-status-banner');
          const text = document.getElementById('shop-status-text');

          if (banner && text) {
              banner.style.display = "block";

              let statusHTML = "";
              for (let kode in dataStatusToko) {
                  let info = dataStatusToko[kode];
                  let icon = (info.status === 'buka') ? "🟢" : "🔴";

                  let namaPendek = info.nama_outlet.replace("Outlet ", "").replace("Dexpress ", ""); 
                  statusHTML += `${namaPendek}: ${icon} &nbsp;&nbsp; `;
              }

              banner.style.backgroundColor = "#333"; 
              text.innerHTML = "STATUS: &nbsp; " + statusHTML;
          }
      })
      .catch(err => console.log('Silent error check status')); 
  }

  cekStatusBerkala();
  setInterval(cekStatusBerkala, 3000); 

  // --- 2. FORM MITRA (Jika ada di halaman lain) ---
  const form = document.getElementById("mitraForm");
  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      const nama = document.getElementById("nama").value;
      const whatsapp = document.getElementById("whatsapp").value;
      const lokasi = document.getElementById("lokasi").value;

      if (nama && whatsapp && lokasi) {
        alert(`Terima kasih, ${nama}! Data pengajuan kemitraan Anda untuk lokasi ${lokasi} telah kami terima. Tim Dexpress akan segera menghubungi nomor ${whatsapp}.`);
        form.reset();
      } else {
        alert("Mohon lengkapi semua data formulir.");
      }
    });
  }

  // --- 3. SMOOTH SCROLL LINK ---
  const links = document.querySelectorAll('a[href^="#"]');
  links.forEach((link) => {
    link.addEventListener("click", function (e) {
      const targetId = this.getAttribute("href");
      const targetElement = document.querySelector(targetId);
      if (targetElement) {
        e.preventDefault();
        targetElement.scrollIntoView({ behavior: "smooth" });

        const navLinks = document.querySelector(".nav-links");
        const hamburger = document.querySelector(".hamburger");
        if (navLinks && navLinks.classList.contains("nav-active")) {
          navLinks.classList.remove("nav-active");
          hamburger.classList.remove("toggle");
        }
      }
    });
  });

  // --- 4. SLIDER GAMBAR ---
  const slideWrapper = document.querySelector(".slides-wrapper");
  const slides = document.querySelectorAll(".slide-img");
  const dots = document.querySelectorAll(".dot");
  if (slideWrapper && slides.length > 0) {
    let slideIndex = 0;
    const totalSlides = slides.length;
    function updateSlide() {
      slideWrapper.style.transform = `translateX(-${slideIndex * 100}%)`;
      dots.forEach((dot) => dot.classList.remove("active"));
      if (dots[slideIndex]) dots[slideIndex].classList.add("active");
    }
    function nextSlide() {
      slideIndex++;
      if (slideIndex >= totalSlides) slideIndex = 0;
      updateSlide();
    }
    setInterval(nextSlide, 3000);
    dots.forEach((dot, index) => {
      dot.addEventListener("click", () => {
        slideIndex = index;
        updateSlide();
      });
    });
  }

  // --- 5. HAMBURGER MENU (MOBILE) ---
  const hamburger = document.querySelector(".hamburger");
  const navLinks = document.querySelector(".nav-links");
  if (hamburger && navLinks) {
    hamburger.addEventListener("click", () => {
      navLinks.classList.toggle("nav-active");
      hamburger.classList.toggle("toggle");
    });
  }

  // --- 6. TOMBOL SCROLL TO TOP ---
  const scrollTopBtn = document.getElementById("scrollTopBtn");
  if (scrollTopBtn) {
    window.onscroll = function () {
      if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) scrollTopBtn.style.display = "block";
      else scrollTopBtn.style.display = "none";
    };
    scrollTopBtn.addEventListener("click", () => window.scrollTo({ top: 0, behavior: "smooth" }));
  }

  // --- 7. PRELOADER ---
  window.addEventListener("load", function () {
    const preloader = document.getElementById("preloader");
    if (preloader) {
      preloader.style.opacity = "0";
      setTimeout(() => { preloader.style.display = "none"; }, 1000);
    }
  });

});
