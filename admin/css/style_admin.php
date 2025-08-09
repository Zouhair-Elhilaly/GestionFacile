<?php 

if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === false) {
    header("HTTP/1.1 403 Forbidden");
    header("location:../error.php");
    exit();
}

// Sinon, afficher le CSS normalement
header("Content-Type: text/css");
?>



@media (max-width: 730px){
    .navbar_employe > div > .search > input , .navbar_employe > button {
        width: 100%;
    }
}/* designe table et btn  */


 .navbar_employe{
    padding: 10px !important;
    width: 90% !important;
 }
    .table-modern{
        width: 100% !important;
        overflow-x: scroll;
    }

     .table-modern table thead tr th:hover{
      background-color: var(--text-color) !important;
     }
/* start designe btn */
.action-btn {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 500;
    font-size: 14px;
    transition: all 0.3s ease;
    text-align: center;
}
table{
    overflow-x: scroll;
    width: auto;
}


.main_accueil{
    padding: 20px !important;
    width: 80%
}

.container_admin{
  /* margin-top: 0 !important; */
    padding:0;
    width: 100%
}

.update-btn {
    background-color: var(--modifyBtn); /* Vert */
    color: var(--text-color);

}

.navbar div a{
  color: var(--text-color)
}

.update-btn:hover {
    background-color: var(--modifyHover);
    transform: translateY(-1px);
}

.delete-btn {
    background-color:  var(--deleteBtn); /* Rouge */
    color: var(--text-color);
    margin-left: 5px;
}

.delete-btn:hover {
    background-color: var(--deleteHover);
    transform: translateY(-1px);
}


/* start designe form ajoute admin */
.ajoute_admin_form , .update_admin_form {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    backdrop-filter: blur(10px) !important; 
    /* transform: translate(-50%, -50%); */
    /* background-color: rgba(0, 0, 0, 0.5); */
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}



.ajoute_admin_form form , .update_admin_form form {
    position: relative;
    background-color: var(--secondary-color);
    padding: 2rem;
    border-radius: 10px;
    width: 90%;
    max-width: 500px;
    box-shadow: var(--shadowForm);
}

.ajoute_admin_form h3 ,#form_update_admin h3, .update_admin_form h3 {
    text-align: center;
    margin-bottom: 1.5rem;
    color: var(--titleColor);

}

#close_form_add_admin , .close_form_update_admin{
    position: absolute;
    top: 10px;
    right: 10px;
    cursor: pointer;
    font-size: 1.5rem;
    color: var(--text-light);
}

 input[type="text"],
 input[type="email"],
 input[type="password"] {
    width: 100%;
    padding: 12px;
    margin-bottom: 1rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
    box-sizing: border-box;
}

.label {
    display: block;
    margin-bottom: 1rem;
    text-align: center;
    padding: 12px;
    background-color: black;
    border-radius: 5px;
    color: white;
    cursor: pointer;
    border: 1px dashed #999;
}



input[type="submit"] {
    width: 100%;
    padding: 12px;
    background: var(--gradient);
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s;
}

 input[type="submit"]:hover {
    background: var(--gradientRotate);
}

/* Responsive adjustments */
@media (max-width: 600px) {
    form {
        padding: 1.5rem;
        width: 95%;
    }
    
     input[type="text"],
     input[type="email"],
     input[type="password"] {
        padding: 10px;
    }
}
/* end style form ajout admin */








/* start designe table */


.table-responsive {
  width: 100%;
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
}

@media (max-width:768px){
    th{
        color: white
    }
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
        display: flex;
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
  padding:7px;
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

  color: white;
  /* color: var(--text-color) !important; */
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
    color: var(--text-color) !important
  }

  /* td:before {
    content: attr(data-label);
    position: absolute;
    left: 10px;
    width: 45%;
    padding-right: 10px;
    font-weight: bold;
    text-align: left;
  } */

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



/* end designe table  */




/* strat desine navbar */

.navbar_admin {
  margin-top: 1px !important;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px;
    background: var(--gradient);
    color: white;
    gap: 1rem;
    border-radius: 20px;
    margin-top: -70px;
    width: 100%;
    box-sizing: border-box;
}
@media (max-width:984px){
  .navbar_admin {
    margin-top: -50px;
  }
}

@media (max-width:735px){
  .navbar_admin button , .navbar_admin div {
   width: 100% !important
  }
}

.add_admin {
    padding: 7px 10px;
    background-color: #3498db;
    color: white;
    border: none;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease;
    width: 100%;
    max-width: 300px;
}

.add_admin:hover {
    background-color: #2980b9;
}

.search-bar {
    width: 100%;
    max-width: 500px;
}

#search {
    width: 100%;
    padding: 8px;
    border: 1px solid #bdc3c7;
    border-radius: 5px;
    font-size: 1rem;
    box-sizing: border-box;
}

#search:focus {
    outline: none;
    padding: 10px;
    border-color: #3498db;
    box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
}

/* Version desktop */
@media (min-width: 768px) {
    .navbar_admin {
        flex-direction: row;
        justify-content: space-between;
        padding: 10px;
    }

    .add_admin {
        width: auto;
        margin-right: auto;
    }

    .search-bar {
        width: 50%;
        margin-left: auto;
    }
}

/* Version tablette */
@media (min-width: 576px) and (max-width: 767px) {
    .navbar_admin {
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
    }

    .add_admin {
        width: 45%;
        margin-right: 5%;
    }

    .search-bar {
        width: 50%;
    }
}

/* end desine navbar */