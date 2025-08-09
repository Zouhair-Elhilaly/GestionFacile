
<?php 

if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === false) {
    header("HTTP/1.1 403 Forbidden");
    header("location:../error.php");
    exit();
}

// Sinon, afficher le CSS normalement
header("Content-Type: text/css");
?>


.commande_employe {
  width: 75%;
  height: 100%;
  margin: 6px auto;
}
h2,h4{
  color: var(--titleColor)
}
/* satrt designe tableau */
/* Style général pour le conteneur produit /
.container.produit {
  padding: 2rem;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
  margin: 2rem auto;
  max-width: 1200px;
}

.container.produit h2 {
  color: #2c3e50;
  font-weight: 600;
  position: relative;
  padding-bottom: 0.5rem;
}

{


.container.produit h2::after {
  content: "";
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 80px;
  height: 3px;
  background: linear-gradient(to right, #3498db, #9b59b6);
}

/ Style pour le tableau /
.table-responsive {
  overflow-x: auto;
  margin-top: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
}

.table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.95rem;
}

.table th {
  background-color: #2c3e50;
  color: white;
  font-weight: 500;
  padding: 1rem;
  text-align: left;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-size: 0.85rem;
}

.table td {
  padding: 0.8rem 1rem;
  border-bottom: 1px solid #eee;
  vertical-align: middle;
  color: #555;
}

.table tr:last-child td {
  border-bottom: none;
}

/* Effet de survol pour les lignes */
/* .table-hover tbody tr:hover {
  background-color: rgba(52, 152, 219, 0.1);
  transition: background-color 0.2s ease;
} */

/* Style pour les boutons d'action */
.btn-action {
  border: none;
  padding: 0.5rem 0.8rem;
  border-radius: 4px;
  cursor: pointer;
  font-size: 0.8rem;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.3rem;
}

.btn-accept {
  background-color: #2ecc71;
  color: white;
}

.btn-accept:hover {
  background-color: #27ae60;
}

.btn-delete {
  background-color: #e74c3c;
  color: white;
}

.btn-delete:hover {
  background-color: #c0392b;
}

/* Icônes pour les boutons */
.btn-action i {
  font-size: 0.9rem;
}

/* Style responsive */
@media (max-width: 768px) {
  .container.produit {
    padding: 1rem;
    margin: 1rem;
  }

  .table th,
  .table td {
    padding: 0.6rem;
    font-size: 0.8rem;
  }

  .btn-action {
    padding: 0.3rem 0.5rem;
    font-size: 0.7rem;
  }
}

/* Animation pour les lignes */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* .table tbody tr {
  animation: fadeIn 0.3s ease forwards;
}

.table tbody tr:nth-child(1) {
  animation-delay: 0.1s;
}
.table tbody tr:nth-child(2) {
  animation-delay: 0.2s;
}
.table tbody tr:nth-child(3) {
  animation-delay: 0.3s;
} */
Continuez selon le nombre de lignes attendues

/* end designe tableau  */

/* style generation_commande */
.generation_commande {
  margin-top: 20px;
  background-color: #f8f9fa;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  margin-bottom: 30px;
}

.generation_commande h4 {
  /* color: #2c3e50; */
  margin-bottom: 15px;
  font-size: 1.25rem;
  font-weight: 600;
  border-bottom: 2px solid #e9ecef;
  padding-bottom: 10px;
}


@media (max-width: 768px){
  td:after{
    left:0;
    position: absolute;
    left: 0;
    content: "0"
  }
  #id_command::after{
    content: 'Id'
  }
  #id_employe::after{
    content: 'Nom Employé'
  }
  #produit::after{
    content: 'Produit'
  }
  #quantite::after{
    content: 'Quantité'
  }
   #statu::after{
    content: 'Statu'
  }
  #modification::after{
    content: 'Modification'
  }
  
}

.generation_commande form {
  margin-bottom: 10px;
}

.generation_commande .btn {
  background: var(--gradient);
  border: none;
  color: var(--text-color);
  border-radius: 4px;
  padding: 8px 15px;
  font-size: 0.9rem;
  transition: all 0.3s ease;
  box-shadow: var(--shadow);
  display: inline-flex;
  align-items: center;
}

.generation_commande .btn:hover {
  background-color: #2980b9;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.generation_commande .btn:active {
  transform: translateY(0);
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Optionnel: ajouter une icône PDF */
.generation_commande .btn::before {
  content: "📄";
  margin-right: 8px;
  font-size: 1rem;
}

/* start header commande */
.header_commande {
    display: flex;
    justify-content: space-around;
    padding: 10px 0;
    flex-wrap: wrap;
}

.header_commande  a{
   position: relative;
}
.header_commande  a button{
   background-color: rgb(158, 155, 155); /* Vert */
    color: rgb(72, 63, 63);
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
}
/* end header commande */



/* table */


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
