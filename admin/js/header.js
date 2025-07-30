document.addEventListener("DOMContentLoaded", function () {
  // Toggle navbar on mobile
  const closeBtn = document.getElementById("closebtn");
  const navbar = document.querySelector(".navbar");

  closeBtn.addEventListener("click", function () {
    navbar.classList.toggle("active");
  });

  // Toggle notification menu
  const notification = document.getElementById("notification");
  const menuNotification = document.querySelector(".menu_notification");

  notification.addEventListener("click", function (e) {
    e.stopPropagation();
    menuNotification.style.display =
      menuNotification.style.display === "block" ? "none" : "block";
  });

  // Close notification menu when clicking elsewhere
  document.addEventListener("click", function () {
    menuNotification.style.display = "none";
  });

  // Toggle dark mode
  const modeBtn = document.querySelector(".mode");

  modeBtn.addEventListener("click", function () {
    document.body.classList.toggle("dark-mode");
    if (document.body.classList.contains("dark-mode")) {
      modeBtn.textContent = "Dark";
    } else {
      modeBtn.textContent = "Light";
    }
  });

  // Responsive navbar
  function handleResponsive() {
    if (window.innerWidth <= 768) {
      navbar.classList.remove("collapsed");
      navbar.style.left = "-100%";
    } else {
      navbar.style.left = "0";
    }
  }

  window.addEventListener("resize", handleResponsive);
  handleResponsive();

  // Add active class to current page
  const currentPage = window.location.pathname.split("/").pop();
  const navLinks = document.querySelectorAll(".navbar a");

  navLinks.forEach((link) => {
    if (link.getAttribute("href") === currentPage) {
      link.classList.add("active");
    }
  });

  // Collapse/expand sidebar
  const collapseBtn = document.createElement("button");
  collapseBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
  collapseBtn.className = "collapse-btn";
  collapseBtn.style.position = "absolute";
  collapseBtn.style.right = "-15px";
  collapseBtn.style.top = "20px";
  collapseBtn.style.background = "#2c3e50";
  collapseBtn.style.color = "white";
  collapseBtn.style.border = "none";
  collapseBtn.style.borderRadius = "50%";
  collapseBtn.style.width = "30px";
  collapseBtn.style.height = "30px";
  collapseBtn.style.cursor = "pointer";
  collapseBtn.style.zIndex = "100";
  collapseBtn.style.boxShadow = "0 2px 5px rgba(0,0,0,0.2)";

  navbar.appendChild(collapseBtn);

  collapseBtn.addEventListener("click", function () {
    navbar.classList.toggle("collapsed");
    if (navbar.classList.contains("collapsed")) {
      this.innerHTML = '<i class="fas fa-chevron-right"></i>';
    } else {
      this.innerHTML = '<i class="fas fa-chevron-left"></i>';
    }
  });

  // Show SweetAlert on logout
  const logoutBtn = document.querySelector(".deconnection_btn");
  if (logoutBtn) {
    logoutBtn.addEventListener("click", function (e) {
      e.preventDefault();
      Swal.fire({
        title: "Êtes-vous sûr?",
        text: "Vous voulez vraiment vous déconnecter?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Oui, déconnecter!",
        cancelButtonText: "Annuler",
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = "logout.php"; // Change to your logout URL
        }
      });
    });
  }
});



  document
    .getElementById("btn-generer")
    .addEventListener("click", function (e) {
      e.preventDefault(); // empêche le comportement par défaut du lien
      document.querySelector("#generer_pdf").scrollIntoView({
        behavior: "smooth",
      });
    });






