let add_product_btn = document.querySelector("#add-product-btn");
// let update_btn = document.querySelector(".update-btn");


let form_add_product = document.querySelector(".form_add_product");


add_product_btn.addEventListener("click", () => {
  form_add_product.style.display = "block";
});

let close_btn_form_product = document.querySelector(".close_btn_form_product");


close_btn_form_product.addEventListener('click' , () =>{
    form_add_product.style.display = "none";
});



// fonction delete 
function show_delete(v){
    let res = confirm("are you  sure for delete ?");
    if(res == true){
        window.location.href = 'delete_product.php?id='+v;

    }
}



// close form

// close_btn_form_product_update.addEventListener('click' , () =>{
//     updateForm.style.display = 'none';
// })



// start form update

// let form_update_product = document.querySelector(".form_update_product");



// if ((form_update_product.style).display == 'block'){
//     let close_btn_form_product_update = document.querySelector(
//       ".close_btn_form_product_update"
//     );
//   close_btn_form_product_update.addEventListener("click", () => {
//     console.log("clicked");
//     form_update_product.style.display = "none";
//   });

// }else{
//     console.log("none");
// }
