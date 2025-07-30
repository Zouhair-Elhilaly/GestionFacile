
let ajoute_admin_form = document.querySelector(".ajoute_admin_form");
let btn_click_add_admin = document.getElementById("btn_click_add_admin");


btn_click_add_admin.addEventListener('click' , () => {
    // ajoute_admin_form.classList.remove("dislay_none");
    ajoute_admin_form.style.display = 'flex';
});
close_form_add_admin.addEventListener('click' , ()=>{
    // ajoute_admin_form.classList.add("dislay_none");
        ajoute_admin_form.style.display = "none";

})


