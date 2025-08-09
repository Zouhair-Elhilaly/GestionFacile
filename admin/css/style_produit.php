     <?php
if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === false) {
    header("HTTP/1.1 403 Forbidden");
    header("location:../error.php");
    exit();
}

// Sinon, afficher le CSS normalement
header("Content-Type: text/css");
?>
.navbar_product {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: var(--gradient); /* Couleur de fond claire */
  padding: 15px 20px;
  border-radius: 8px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  flex-wrap: wrap;
}

.add-product-btn {
  background: var(--addUser); /* Vert */
  color: white;
  border: none;
  padding: 10px 20px;
  font-size: 16px;
  border-radius: 5px;
  cursor: pointer;
  margin-bottom: 0 !important;
  transition: background-color 0.3s ease;
}

@media (max-width: 676px) {
  .add-product-btn {
    padding: 5px 10px;
  }
  .navbar_product {
    text-align: center;
    justify-content: center;
  }
}

.add-product-btn:hover {
  background-color: #218838; /* Vert foncé au hover */
}

.search-bar {
  display: flex;
  align-items: center;
  gap: 10px;
}

.search-bar input[type="text"] {
  padding: 8px 12px;
  font-size: 15px;
  border: 1px solid #ced4da;
  border-radius: 5px;
  max-width: 150px;
  transition: border-color 0.3s ease;
}

.search-bar input[type="text"]:focus {
  border-color: #80bdff;
  outline: none;
}

@media (max-width:412px){
  .search-bar input ,     .add-product-btn{
    width: 100% !important;
    /* max- */
  }

  .navbar_product{
    /* position: relative; */
    display: flex;
    flex-direction: column;
  }
  .search-bar input{
    margin: 2px auto;
    /* position: absolute; */
    /* max-width: 302px !important; */
  }
}

.search-bar button {
  background-color: #007bff; /* Bleu */
  color: white;
  border: none;
  padding: 8px 10px;
  font-size: 15px;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.search-bar button:hover {
  background-color: #0069d9; /* Bleu foncé au hover */
}

/* ******************************************************** */
.navbar_product {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.search-bar {
  display: flex;
  gap: 0.5rem;
}

.search-bar input {
  padding: 0.5rem;
  border: none;
  border-radius: 4px;
  width: 200px;
}

.search-bar button {
  padding: 0.5rem 1rem;
  background-color: #3498db;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.search-bar button:hover {
  background-color: #2980b9;
}

/* Contenu principal */
.container {
  padding: 2rem;
}
/* 
.add-product-btn {
  display: block;
  margin-bottom: 1.5rem;
  padding: 0.7rem 1.2rem;
  background-color: #27ae60;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1rem;
  transition: background-color 0.3s;
} */

.add-product-btn:hover {
  transform: translateY(-2px);
  background: var(--addUserHover);
  transition: var(--transition);
}




/* Animation pour le bouton */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.add-product-btn:active {
    animation: pulse 0.3s ease;
}

/* Grille de produits */

/* Carte de produit */
.product-card {
  background-color: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s, box-shadow 0.3s;
}

.product-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 5px 25px rgba(0, 0, 0, 0.4);
}

.product-image {
  width: 100%;
  margin: auto;
  height: 180px;
  object-fit: cover;
  border-bottom: 1px solid #eee;
}

.product-info {
  padding: 1rem;
}

.product-name {
  font-size: 1.1rem;
  margin-bottom: 0.5rem;
  color: #2c3e50;
}

.product-price {
  font-size: 1.2rem;
  font-weight: bold;
  color: #e74c3c;
  margin-bottom: 1rem;
}

.product-actions {
  display: flex;
  gap: 0.5rem;
}

.action-btn {
  padding: 0.5rem 0.8rem;
  border: none;
  border-radius: 4px;
  /* cursor: pointer; */
  font-size: 0.9rem;
  transition: opacity 0.3s;
  flex: 1;
  position: relative;
  text-align: center;
  text-decoration: none;
}

/* .action-btn a {
            color: white;
            /* width: 100%;
            height: 100%; */
/* position: absolute; *
            text-decoration: none;
        } */

.action-btn:hover {
  opacity: 0.9;
}

.update-btn {
  background-color: #3498db;
  color: white;
}

.delete-btn {
  background-color: #e74c3c;
  color: white;
}
.container_admin .product {
  width: 100%;
  margin-top: 4px;
  padding: 0;
}

/* start form add  */

#form,
.form_update_product {
  display: none;
  top: 50%;
  left: 50%;
  width: 60%;
  height: 80%;
  transform: translate(-50%, -50%);
  position: fixed;
  box-shadow: var(--shadowForm);
  padding: 20px;
  max-width: 500px;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  background-color:  var(--secondary-color) !important;
  color: var(--text-color) !important;
}

.form_update_product {
  display: none;
   background-color:  var(--secondary-color) !important;
  color: var(--text-color) !important;
}

/* #form {
   
    margin: 40px auto;
    padding: 30px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
} */

#form .form-group {
  margin-bottom: 10px;
}

#form label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
  color: #333;
}

#form .form-control {
  width: 100%;
  padding: 12px 15px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 16px;
  transition: border-color 0.3s ease;
}

#form .form-control:focus {
  border-color: #4a90e2;
  outline: none;
  box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.2);
}

#form select.form-control {
  appearance: none;
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 10px center;
  background-size: 15px;
}

.submit-btn {
  width: 100%;
  padding: 12px;
  background: var(--gradient) ;
  color: white;
  border: none;
  border-radius: 6px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.submit-btn:hover {
  background: var(--gradientRotate);
}

/* Style pour les options du select */
#productCategory option {
  padding: 10px;
}

/* Responsive design */
@media (max-width: 600px) {
  #form {
    margin: 20px 15px;
    padding: 20px;

  }
 
}

.close_btn_form_product,
.close_btn_form_product_update {
  font-weight: bold;
  top: 1%;
  right: 1%;
  position: absolute;
  font-size: 20px;
  cursor: pointer;
}

.Image_design {
  background-color: white;
  color:black !important;
  border-radius: 5px;
  box-shadow: 0px 0 1px black;
  cursor: pointer;
  text-align: center;
  width: 100%;
  padding: 2% 0;

}

/* start form update product */
/* Formulaire de mise à jour produit centré */
.form_update_product {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 100%;
  max-width: 500px;
  height: 50%;
  background: #ffffff;
  border-radius: 12px;
  box-shadow: var(--shadowProduct);
  padding: 30px;
  z-index: 9999;
  overflow-y: auto;
  animation: fadeInScale 0.3s ease-out;
}




/* Animation d'apparition */
@keyframes fadeInScale {
  from {
    opacity: 0;
    transform: translate(-50%, -50%) scale(0.9);
  }
  to {
    opacity: 1;
    transform: translate(-50%, -50%) scale(1);
  }
}

/* Overlay de fond */
/* .form_update_product::before {
  content: "";
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(255, 252, 252, 0.6);
  z-index: -1;
  backdrop-filter: blur(3px);
} */

/* Bouton de fermeture */
.close_btn_form_product_update {
  position: absolute;
  top: 1px;
  right: 1px;
  font-size: 28px;
  font-weight: bold;
  color: var(--text-color);
  cursor: pointer;
  transition: all 0.3s ease;
  width: 30px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: transparent;
  border: none;
}

.close_btn_form_product_update:hover {
  color: #333;
  background: #f8f9fa;
  transform: rotate(90deg);
}

/* Titre du formulaire (optionnel) */
.form_update_product h2 {
  margin: 0 0 25px 0;
  color: #333;
  font-size: 24px;
  font-weight: 600;
  text-align: center;
  border-bottom: 2px solid #007bff;
  padding-bottom: 10px;
}

/* Groupes de champs */
.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
  color: #555;
  font-size: 14px;
}

/* Champs de saisie */
.form-control_update {
  width: 100%;
  padding: 12px 16px;
  border: 2px solid #e1e5e9;
  border-radius: 8px;
  font-size: 14px;
  transition: all 0.3s ease;
  box-sizing: border-box;
  background: #fafafa;
}

.form-control_update:focus {
  outline: none;
  border-color: #007bff;
  background: #ffffff;
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
}

/* Select customisé */
.form-control_update select {
  cursor: pointer;
  appearance: none;
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
  background-position: right 12px center;
  background-repeat: no-repeat;
  background-size: 16px;
  padding-right: 40px;
}

/* Label personnalisé pour l'image */
.Image_design_update {
  display: inline-block;
  width: 100%;
  padding: 12px 16px;
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border: 2px dashed #dee2e6;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  text-align: center;
  font-weight: 500;
  color: #6c757d;
  position: relative;
  overflow: hidden;
}

.Image_design_update:hover {
  border-color: #007bff;
  background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
  color: #007bff;
  transform: translateY(-2px);
}

.Image_design_update::before {
  content: "📁";
  margin-right: 8px;
  font-size: 16px;
}

/* Bouton de soumission */
.submit-btn-update {
  width: 100%;
  padding: 14px 20px;
  background: var(--gradient);
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  margin-top: 10px;
  position: relative;
  overflow: hidden;
}

.submit-btn-update:hover {
  background: var(--gradientRotate);
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3);
}

.submit-btn-update:active {
  transform: translateY(0);
}

/* Effet de ripple sur le bouton */
.submit-btn-update::before {
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.3);
  transition: all 0.3s ease;
  transform: translate(-50%, -50%);
}

.submit-btn-update:active::before {
  width: 300px;
  height: 300px;
}

/* Responsive design */
@media (max-width: 768px) {
  .form_update_product {
    width: 95%;
    padding: 20px;
    max-height: 95vh;
  }

  .form-group {
    margin-bottom: 15px;
  }

  .form-control_update {
    padding: 10px 12px;
    font-size: 16px; /* Évite le zoom sur iOS */
  }

  .submit-btn-update {
    padding: 12px 16px;
    font-size: 14px;
  }
}

@media (max-width: 480px) {
  .form_update_product {
    width: 98%;
    padding: 15px;
    border-radius: 8px;
  }

  .close_btn_form_product_update {
    top: 10px;
    right: 15px;
    font-size: 24px;
  }
}

/* Amélioration du scrollbar pour webkit */
.form_update_product::-webkit-scrollbar {
  width: 6px;
}

.form_update_product::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}

.form_update_product::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}

.form_update_product::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}

/* États de validation */
.form-control_update:invalid {
  border-color: #dc3545;
}

.form-control_update:valid {
  border-color: #28a745;
}

/* Animation de fermeture */
.form_update_product.closing {
  animation: fadeOutScale 0.3s ease-in forwards;
}

@keyframes fadeOutScale {
  from {
    opacity: 1;
    transform: translate(-50%, -50%) scale(1);
  }
  to {
    opacity: 0;
    transform: translate(-50%, -50%) scale(0.9);
  }
}
/* ******************************************END ******************* */

/* ********* produit ******************* */
* {
  box-sizing: border-box;
  font-family: Arial, sans-serif;
}

/* .produit {
  max-width: 1200px;
  margin: 20px auto;
  padding: 20px;
  background: #fff;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
} */

.produit h2 {
  text-align: center;
  color: #333;
  margin-bottom: 20px;
}

.table-responsive {
  width: 100%;
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
}


table{
        width: 100% !important;
    }

     .container{
      padding:0
    }


/* start designe table */
@media (max-width:766px){
    table{
        width: 100% !important;
    }
    .container{
      padding:0
    }
    body{
      font-size: 14px !important;
    }
}

th,
td {
  padding: 12px 15px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

th {
 color: var( --secondary-color);
    background-color: var(--text-color);
  font-weight: bold;
}

.form_add_product{
  z-index: 999999;
  max-height: 400px !important;
  background-color: var(--secondary-color) !important;
  color: var(--text-color) !important;
}



label{
  color: var(--text-color) !important;
}

#label_image_update label{
  color: black !important;
}

tr:hover {
  background-color: var(--text-light);
}

.table-img {
  width: 50px;
  height: 50px;
  object-fit: cover;
  border-radius: 4px;
}

.action-btn {
  padding: 6px 10px;
  margin: 0 3px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
  transition: background-color 0.3s;
}

.modify-btn {
  background-color: var(--modifyBtn);
  color: #000;
  margin: 2px;
}

.modify-btn:hover {
  background-color: var(--modifyHover);
}

.delete-btn {
  background-color: var(--deleteBtn);
  color: white;
  margin: 2px;
}

.delete-btn:hover {
  background-color:  var(--deleteHover);
}

/* Style pour les écrans mobiles */
@media screen and (max-width: 768px) {
  table,
  thead,
  tbody,
  th,
  td,
  tr {
    display: block;
  }

  thead tr {
    position: absolute;
    top: -9999px;
    left: -9999px;
  }


  tr {
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 10px;
   
  }

  td {
    border: none;
    border-bottom: 1px solid #eee;
    position: relative;
    padding-left: 50%;
    text-align: right;
  }

  td:before {
    content: attr(data-label);
    position: absolute;
    left: 10px;
    width: 45%;
    padding-right: 10px;
    font-weight: bold;
    text-align: left;
  }

  .action-btns {
    display: flex;
    gap: 4px;
    /* justify-content: flex-end; */
  }



  .btn_tableau{
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
  }

  .action-btns > a{
    margin: 5px;
  }

  .action-btn {
    margin: 0 5px;
    padding: 8px 12px;
  }

  .form_add_product{
    width:90% !important;
    margin: auto;
    z-index: 100;
    width: 100% !important;
   margin-left: 10px !important
  }
}

/* Style pour les très petits écrans */
@media screen and (max-width: 480px) {
  td {
    padding-left: 40%;
  }

  td:before {
    width: 35%;
  }

  .action-btns {
    flex-direction: column;
  }

  .action-btn {
    margin: 3px 0;
    width: 100%;
  }
}
/* ************* end produit ***************** */
