
     <?php
if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === false) {
    header("HTTP/1.1 403 Forbidden");
    header("location:../error.php");
    exit();
}

// Sinon, afficher le CSS normalement
header("Content-Type: text/css");
?>

   

.table-modern{
  width: 90% !important;
  overflow-x: auto !important; /* Active le scroll horizontal si nécessaire */
}

table {
  width: 100%;
  border-collapse: collapse;
  min-width: 600px; /* Force le scroll si l’écran est trop petit */
}

th, td {
  /* border: 1px solid #ddd; */
  padding: 8px;
  text-align: left;
}



@media screen and (max-width: 600px) {
  th, td {
    font-size: 14px; /* Réduit la taille sur mobile */
    padding: 6px;
  }
 
  
}


@media (max-width:768px){
  td:after{
    left:0;
    position: absolute;
    content: '0';

  }
   #id::after{
    content : "Id"
  }
  #image::after{
    content : "Image"
  }
  #nom::after{
    content : "Nom"
  }
  #telephone::after{
    content : "Téléphone"
  }
  #email::after{
    content : "Email"
  }
  #genre::after{
    content : "Genre"
  }
  #nationalite::after{
    content : "Nationalité"
  }
  #cnie::after{
    content : "CNIE"
  }
  #post::after{
    content : "Poste"
  }
   #modification::after{
    content : "Modification"
  }
  .table-modern{
    width: 100%;
  }
  .main_accueil{
    width: 100% !important
  }
}



@media (max-width:1224px){
  #employeeTable{
    width: fit-content;
    overflow: scroll;
  }
}
/* start designe form ajoute admin */
.ajoute_employe_form {
  position: fixed;
  padding: 10px;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  /* transform: translate(-50%, -50%); */
  backdrop-filter: blur(10px); 
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.update_employe_form  form{
  position: fixed;
  top:50%;
  left: 50%;
  /* max-width: 600px; */
  padding: 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  z-index:9999;
  /* height: 500px !important; */
  background-color: var(--secondary-color);
  border-radius: 10px;
  box-shadow: var(--shadowForm);
  transform: translate(-50% , -50%);;
}

.ajoute_employe_form form  {
  position: relative;
  background-color: var(--secondary-color);
  border-radius: 10px;
  width: 80%;
  text-align: center;
  padding: 10px 0;
  max-width: 500px;
  box-shadow: var(--shadowForm);
}

.ajoute_employe_form h3 ,.update_employe_form h3 {
  text-align: center;
  margin-bottom: 1.5rem;
  color: var(--titleColor);
}

#close_form_employe ,#close_form_update_employe{
  position: absolute;
  top: 10px;
  right: 10px;
  cursor: pointer;
  font-size: 1.5rem;
  color: var(--text-light);
}

.ajoute_employe_form input[type="text"],
.ajoute_employe_form input[type="email"],
.ajoute_employe_form input[type="password"],
.update_employe_form input[type="text"],
.update_employe_form input[type="email"],
.update_employe_form input[type="password"],
select {
  width: 80%;
  margin: 4px auto;
  padding: 5px 10px;
  /* margin-bottom: 1rem; */
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 1rem;
  box-sizing: border-box;
}

.label {
  display: block;
  margin-bottom: 1rem;

  text-align: center;
  width: 80%;
  padding: 5px 10px;
  margin:  4px auto;
  background-color: #f0f0f0;
  border-radius: 5px;
  cursor: pointer;
  border: 1px dashed #999;
}


/* .label:hover {
  background-color: #e0e0e0;
} */

.ajoute_employe_form input[type="submit"] , .update_employe_form input[type="submit"]{
  width: 80%;
  padding: 5px 10px;
  background-color: #4caf50;
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.3s;
}

/* .ajoute_employe_form input[type="submit"]:hover {
  background-color: #45a049;
} */

/* Responsive adjustments */
@media (max-width: 600px) {
  .ajoute_employe_form form , .update_employe_form form{
    padding: 1.5rem;
    width: 95%;
  }
   

  .ajoute_employe_form input[type="text"],
  .ajoute_employe_form input[type="email"],
  .ajoute_employe_form input[type="password"],
  .update_employe_form input[type="text"],
  .update_employe_form input[type="email"],
  .update_employe_forminput[type="password"] {
    padding: 10px;
  }
}
/* end style form ajout employe */

.display_none {
  display: none;
}
