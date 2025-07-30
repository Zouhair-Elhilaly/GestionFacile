
// start click dans button add admin
document.addEventListener("DOMContentLoaded", () => {
let btnAddAdmin = document.getElementById("btn_click_add");

let form_add_admin = document.querySelector(".form_add_admin");

let overlay = document.querySelector(".overlay");

btnAddAdmin.addEventListener('click' , () => {
  console.log('ckicked');
//   form_add_admin.style.display = 'block';
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

let close_form_add_admin = document.getElementById("close_form_add_admin_2");


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
          position: absolute;
          top: 10%;
          left: 10%;
          transform: translateX(-50%,-50%);
          overflow-x: scroll;
          overflow-y: scroll
          
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





// start designe alert
function showAlert(timeout = 5000) {
    const alertBox = document.getElementById('myAlert');
    const nextBtn = document.getElementById('nextBtn');
    let timer;

    // Afficher la boîte
    alertBox.style.display = 'block';

    // Masquer après un délai si l’utilisateur ne clique pas
    timer = setTimeout(() => {
        alertBox.style.display = 'none';
    }, timeout);

    // Si l’utilisateur clique, annuler le timer
    nextBtn.addEventListener('click', () => {
        clearTimeout(timer);
        alertBox.style.display = 'none';
    });
}

// Exemple : afficher après 1 seconde
setTimeout(() => showAlert(5000), 1000);

// <!-- end style alert  -->
});

