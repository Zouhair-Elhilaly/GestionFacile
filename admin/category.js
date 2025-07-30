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

// end display form update category

function display() {
  modal.style.display = "block";
}
