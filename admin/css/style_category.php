
<?php 

if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === false) {
    header("HTTP/1.1 403 Forbidden");
    header("location:../error.php");
    exit();
}

// Sinon, afficher le CSS normalement
header("Content-Type: text/css");
?>

  /* Mise en page */
  .dashboard {
    font-family: Arial, sans-serif;
    padding: 20px;
  }

  .categories-section {
    width: 100%;
    
  }
/* 
  .navbar_category{
    display: flex;
    text-align: center;
    align-items: center;
    background: var(--bgColor) ;
    justify-content: space-between;
    box-shadow: 1px 1px 3px rgb(202, 203, 205);
  } */

  .navbar_category{
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: var(--gradient); /* Couleur de fond claire */
  padding: 15px 20px;
  border-radius: 8px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  flex-wrap: wrap;
  margin-bottom: 10px;
}

  /* / Boutons / */
 .category-actions a , button {
    padding: 6px 10px;
    margin: 0 auto;
    cursor: pointer;
    border: none;
    border-radius: 4px;
    background: var(--bgColor);
    width: 80%;
    font-size: 0.9em;
    margin: 10px;
    text-align: center;
    /* width: 100px; */
    display: block;
    text-decoration: none;
  }

  #btn_click_add_category {
    width: 20%;
    padding: 10px;
    min-width: 200px !important;
     background: var(--addUser) ;
     color:#fff !important;
     border-radius: 10px;
     font-weight: bold;
     border: none;
  }

  #btn_click_add_category:hover {
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

#btn_click_add_category:active {
    animation: pulse 0.3s ease;
}



.search-container {
    position: relative;
    display: flex;
    align-items: center;
}

.search-icon {
    position: absolute;
    left: 15px;
    color: #a0a0a0;
    font-size: 1rem;
}

@media (max-width:336px){
  /* #search {
    padding: 7px 10px !important;
  }  */

  .search-icon {
    left: 5px !important;
  }

  #btn_click_add_category{
    width: 100% !important;
  }
}

h3{
  text-align: center;
  color: var(--titleColor);
 
}

#search {
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    font-size: 0.95rem;
    width: 250px;
    transition: all 0.3s ease;
    background-color: #f9f9f9;
    color: #333;
}

#search:focus {
    outline: none;
    border-color: #6e8efb;
    box-shadow: 0 0 0 3px rgba(110, 142, 251, 0.2);
    background-color: #fff;
}




  .btn-edit-category {
    background-color: #2196F3;
    color: #fff;
  }

  .btn-delete-category {
    background-color: #f44336;
    color: #fff;
  }

  .btn-view-products {
    background-color: #ff9800;
    color: #fff;
  } */

  /* Modal */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: var(--text-color);
}

.modal-content {
    position: fixed;
    z-index: 1001;
    left: 50%;
    top: 50%;
    width: 400px;
    background:var(--secondary-color);
    padding: 20px;
    box-shadow:  var(--shadowForm);
    border-radius: 5px;
    transform: translate(-50%, -50%);
}

  .close-modale {
    float: right;
    font-size: 24px;
    cursor: pointer;
  }

  .form-group {
    margin-bottom: 15px;
  }

  .form-group label {
    display: block;
    margin-bottom: 5px;
    margin-left: 30px !important;

  }

  textarea {
  resize: vertical; /* Autorise seulement vertical */
}

  .form-group input, 
  .form-group textarea {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
  }

  .btn-save {
    /*background-color: #4CAF50 !important;*/
    width:90%;
    margin: auto;
    color: #fff;
    background: var(--gradient) !important;
  }


  /* start card */
  .category-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 15px;
    /* margin-bottom: 20px; */
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    width: 100%;
    /* display: flex;
    justify-content: flex-start;
    flex-wrap: wrap;
     */
     
   
}

.category-header {
    display: flex;
    align-items: center;
    gap: 15px;
    /* margin-bottom: 10px; */
    flex-direction: column;
    max-width: 400px;
    min-width: 120px;
    box-shadow: 1px 1px 10px rgb(200, 191, 191);
    /* margin: 10px; */


   
}


.category-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    background-size: 90%;
}

/* .category-actions {
    /* margin-left: auto; /
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
} */

table tbody td  a {
    padding: 5px ;
    border-radius: 4px;
    text-decoration: none;
    font-size: 14px;
}


  /* navbar category */

/* 



 /* sart designe label foorm */
 .label label{
    cursor: pointer;
    /* border: 1px solid black; */
    padding: 5px 10px;
    margin: 4px;
    background-color: #333;
    color:white;
    width: 100%;
    border: 1px dashed var(--text-color);
    display: flex;
    justify-content: center;
    align-items: center;
}


/* ovelay */
.overlay {
    display: none; /* cacher par défaut */
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5); /* couleur semi-transparente */
    z-index: 999; /* au-dessus de tout */
  }



  /* start designe table (******************************************************** */
  
.table-responsive {
  width: 100%;
  overflow-x: auto;
   border-collapse: collapse;
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
  padding: 7px;
  text-align: left;
  color: var(--text-color);
  border-bottom: 1px solid #ddd;
}

@media (max-width: 888px){
  table{
    width: 100% !important
  }
  .container_admin{
    padding: 10px;
  }
}
th {
 color: var( --secondary-color);
    background-color: var(--text-color);
  font-weight: bold;
}





tr:hover {
  background-color: var(--text-light);
}

.table-img {
  /* width: 70px; */
  height: 70px;
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
}

.modify-btn:hover {
  background-color: var(--modifyHover);
}

.delete-btn {
  background-color: var(--deleteBtn);
  color: white;
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

  td::after{
    position: absolute;
    left: 0;
    top:0;
    content: "0";
  }
  .table-img{
    right:0;
  }
  .table-img::after{
    content: "Image";
  }
  #id::after{
    content : "Id"
  }
   #name::after{
    content : "Nom"
  }
   #description::after{
    content : "Description"
  }
   #quantite::after{
    content : "Quantité"
  }
   #update::after{
    content : "Modifier"
  }
   #delete::after{
    content : "Supprimer"
  }

  #image_space{
    height: 70px;

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
