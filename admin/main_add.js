// start click dans button add admin

let btnAddAdmin = document.getElementById("btn_click_add");

let form_add_admin = document.querySelector(".form_add_admin");


btnAddAdmin.addEventListener('click' , () => {
    form_add_admin.style.cssText = `
    display: block;
    `;
    // document.body.style.backgroundColor = 'grey';
})



// start close form add admin 

let close_form_add_admin = document.getElementById("close_form_add_admin");


close_form_add_admin.addEventListener('click' , () => {
    form_add_admin.style.cssText = `
    display: none;
    `;
});



// start devlop close btn 
let navbar = document.querySelector(".navbar");

let main_accueil = document.querySelector(".main_accueil");


function closeNavbar(){
  navbar.classList.toggle("close");
  if(navbar.classList.contains("close")){
    console.log("before contain");
    main_accueil.style.cssText = `
    transition: transform 6s linear;
    transform-origin: left; 
    transform: scaleX(1); 
    width: 500px;
    position: fixed;
    left: 10%;
    `;
    
  }else{
    navbar.classList.remove("close");
    main_accueil.style.cssText = `
    width : 80%;
    position: relative;
    `;
  }
}