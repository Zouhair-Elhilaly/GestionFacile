// start click dans button add admin

  let btn_ajoute_employe = document.getElementById("btn_ajoute_employe");

  let form_add_employe = document.querySelector(".ajoute_employe_form");

  let overlay = document.querySelector(".overlay");

  btn_ajoute_employe.addEventListener("click", () => {
    console.log("ckicked");
    if(form_add_employe.classList.contains('display_none')){
        form_add_employe.classList.remove("display_none");
    }else{
        form_add_employe.classList.add("display_none");
    }
    
    // overlay.style.cssText = `
    // display: block
    // `;
    // document.body.style.backgroundColor = 'grey';
  });

  // start close form add admin

  let close_form_employe = document.getElementById("close_form_employe");

  close_form_employe.addEventListener("click", () => {
       
    form_add_employe.classList.add("display_none");
    // overlay.style.cssText = `
    // display: none;
    // `;
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

//   // start designe alert
//   function showAlert(timeout = 5000) {
//     const alertBox = document.getElementById("myAlert");
//     const nextBtn = document.getElementById("nextBtn");
//     let timer;

//     // Afficher la boîte
//     alertBox.style.display = "block";

//     // Masquer après un délai si l’utilisateur ne clique pas
//     timer = setTimeout(() => {
//       alertBox.style.display = "none";
//     }, timeout);

//     // Si l’utilisateur clique, annuler le timer
//     nextBtn.addEventListener("click", () => {
//       clearTimeout(timer);
//       alertBox.style.display = "none";
//     });
//   }

//   // Exemple : afficher après 1 seconde
//   setTimeout(() => showAlert(5000), 1000);

//   // <!-- end style alert  -->


let close_form_update_employe = document.querySelector(
  "#close_form_update_employe"
);

let update_employe_form = document.querySelector(".update_employe_form");

close_form_update_employe.addEventListener('click' , ()=>{
  update_employe_form.style.display = 'none';
  console.log("helloooo")
})