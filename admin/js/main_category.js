// Sélection des éléments
let closeBtn = document.querySelector(".close-modal");
let modal = document.querySelector(".category-modal");
let overlay = document.querySelector(".overlay");
let addCategoryBtn = document.getElementById("btn_click_add_category"); // Modifié pour utiliser getElementById

// Fermer la modal quand on clique sur ×
closeBtn.addEventListener("click", () => {
  modal.style.display = "none";
});

// Ouvrir la modal quand on clique sur "Add Category"
addCategoryBtn.addEventListener("click", (e) => {
  e.preventDefault(); // Empêche le comportement par défaut du lien
  modal.style.display = "block";
   modal.style.cssText = ` 
   z-index: 99;
   `;
   overlay.style.cssText = `
    display: block
    `;
});

// Optionnel: Fermer la modal si on clique en dehors
window.addEventListener("click", (e) => {
  if (e.target === modal) {
    modal.style.display = "none";
  }
});
