// start click dans button add admin

let btnAddAdmin = document.getElementById("btn_click_add");

let form_add_admin = document.querySelector(".form_add_admin");

let overlay = document.querySelector(".overlay");

btnAddAdmin.addEventListener('click' , () => {
    form_add_admin.style.cssText = `
    display: block;
    background-blend-mode: lighten;
    z-index: 1000;
    `;
    overlay.style.cssText = `
    display: block
    `;
    // document.body.style.backgroundColor = 'grey';
})



// start close form add admin 

let close_form_add_admin = document.getElementById("close_form_add_admin");


close_form_add_admin.addEventListener('click' , () => {
    form_add_admin.style.cssText = `
    display: none;
    `;
    overlay.style.cssText = `
    display: none;
    `;
});



// start devlop close btn 
let navbar = document.querySelector(".navbar");

let main_accueil = document.querySelector(".main_accueil");

function closeNavbar() {
  navbar.classList.toggle("close");

  if (navbar.classList.contains("close")) {
    main_accueil.style.cssText = `
          width: calc(100% - 0px);
          position: relative;
      `;
  } else {
    main_accueil.style.cssText = `
          width: calc(100% - 250px); /* largeur de la navbar */
          position: relative;
      `;
  }

  // Force recalcul pour résoudre le bug de scroll
  document.body.offsetHeight;
}



