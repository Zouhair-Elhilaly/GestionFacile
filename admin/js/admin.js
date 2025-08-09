
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



 document.addEventListener('DOMContentLoaded', () => {


        // ===== SYSTÈME DE RECHERCHE SIMPLE =====
console.log("hello")
    const searchInput = document.getElementById('search');
    const tableBody = document.getElementById('adminTableBody');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        const rows = tableBody.querySelectorAll('tr');
        
        rows.forEach(row => {
            const serviceName = row.querySelector('.admin-name');
            if (serviceName) {
                const text = serviceName.textContent.toLowerCase();
                if (text.includes(searchTerm) || searchTerm === '') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    });
// end search 
 });


// /start button hide modify form
let close_form_update_admin = document.querySelector(".close_form_update_admin");

close_form_update_admin.addEventListener('click' , ()=>{
      let ajoute_admin_form = document.querySelector(".ajoute_admin_form ");
      let form_update_admin = document.querySelector("#form_update_admin");
        form_update_admin.style.display = "none";
        // aj/oute_admin_form.style.display = "none";
    }); 


