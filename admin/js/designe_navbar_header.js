document.addEventListener("DOMContentLoaded", function () {
  const menuToggle = document.getElementById("menuToggle");
  const closeSidebar = document.getElementById("closeSidebar");
  const sidebar = document.getElementById("sidebar");
  const mainContent = document.getElementById("mainContent");
  const overlay = document.getElementById("overlay");

  // Ouvrir la sidebar
  menuToggle.addEventListener("click", function () {
    sidebar.classList.add("show");
    overlay.classList.add("show");
    document.body.style.overflow = "hidden";
  });

  // Fermer la sidebar
  function closeSidebarFunc() {
    sidebar.classList.remove("show");
    overlay.classList.remove("show");
    document.body.style.overflow = "auto";
  }

  closeSidebar.addEventListener("click", closeSidebarFunc);
  overlay.addEventListener("click", closeSidebarFunc);

  // Mode sombre/clair
  const modeToggle = document.querySelector(".mode-toggle");
  modeToggle.addEventListener("click", function () {
    document.body.classList.toggle("dark-mode");

    if (document.body.classList.contains("dark-mode")) {
      modeToggle.innerHTML = '<i class="fas fa-sun"></i>';
    } else {
      modeToggle.innerHTML = '<i class="fas fa-moon"></i>';
    }
  });

  // Fermer la sidebar si on clique sur un lien
  const navLinks = document.querySelectorAll(".sidebar-menu a");
  navLinks.forEach((link) => {
    link.addEventListener("click", closeSidebarFunc);
  });
});
