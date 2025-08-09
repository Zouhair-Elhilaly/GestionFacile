document.addEventListener("DOMContentLoaded", function () {
  // ===== SYSTÈME DE RECHERCHE SIMPLE =====

  const searchInput = document.getElementById("search");
  const tableBody = document.getElementById("serviceTableBody");

  searchInput.addEventListener("input", function () {
    const searchTerm = this.value.toLowerCase().trim();
    const rows = tableBody.querySelectorAll("tr");

    rows.forEach((row) => {
      const serviceName = row.querySelector(".service-name");
      if (serviceName) {
        console.log("hello");
        const text = serviceName.textContent.toLowerCase();
        if (text.includes(searchTerm) || searchTerm === "") {
          row.style.display = "";
        } else {
          row.style.display = "none";
        }
      }
    });
  });
  // end search
});

// Sélection des éléments
let closeBtn = document.querySelector(".close-modale");
let modal = document.querySelector(".category-modal");
// let addCategoryBtn = document.getElementById("btn_click_add_category"); // Modifié pour utiliser getElementById

// Fermer la modal quand on clique sur ×
closeBtn.addEventListener("click", () => {
  window.location = "view_category.php"; // Redirige vers la page de visualisation des catégories
});

// start display form update category
let modifier_category_form = document.querySelector(".modifier_category_form");

let close_btn_form_category_update = document.querySelector(
  ".close_btn_form_category_update"
);

close_btn_form_category_update.addEventListener("click", () => {
  console.log("clicked x");
  window.location = "view_category.php";
});


