<?php 



session_start();

//end insert image  
require_once '../include/conn_db.php';
include "functions/chiffre.php";

require_once 'header.php';

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $id = strip_tags(trim(decryptId($_GET['id']))) ;
    
    $sql = "SELECT * FROM employees WHERE id  = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i'  , $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows > 0){
       if($row = $result->fetch_assoc()){
           $_SESSION['nom_update'] = $row['nom'];
           $_SESSION['prenom_update'] = $row['prenom'];
           $_SESSION['email_update'] = $row['email'];
           $_SESSION['genre_update'] = $row['genre'];
           $_SESSION['telephone_update'] = $row['telephone'];
           $_SESSION['password_update'] = $row['mot_de_passe'];
           $_SESSION['nationalite_update'] = $row['nationalite'];
           $_SESSION['ville_update'] = $row['ville'];
           $_SESSION['age_update'] = $row['age'];
           $_SESSION['image_update'] = $row['image'];
?>
        <link rel="stylesheet" href="style.css">

        <style>
            .form_add_admin{
                display: block;
                top: 50%;
                left: 50%;
                transform: translate(-50% , -50%);
            }
            body{
                width: 100%;
                height: 1000px;
            }
        </style>

        <div class="form_add_admin">
            <form action="insert_update_employee.php" method="POST" enctype="multipart/form-data" >
                <h3>Update Employe</h3>
                <h3 id="close_form_add_admin" >X</h3>
                <input type="text" name="nom" placeholder="Ecrire le nom"  value="<?php echo $row['nom']?>"  >
                <input type="text" name="prenom" placeholder="Ecrire prenom" value="<?php echo $row['prenom']?>">
                <input type="email" name="email" id="" placeholder="Ecrire Email" value="<?php echo $row['email']?>">
                <input type="text" name="phone" id="" placeholder="Ex : 0101910087" value="<?php echo $row['telephone']?>">
                <input type="text" name="post1" id="" placeholder="Ecrire post employee" value="<?php echo $row['post']?>">
                <input type="text" name="age" id="" placeholder="Ecrire age employee" value="<?php echo $row['age']?>">
                <input type="text" name="ville" id="" placeholder="Ecrire ville employee" value="<?php echo $row['ville']?>">
                <input type="text" name="nationalite" placeholder="Ecrire nationalite  employee" value="<?php echo $row['nationalite']?>">
                <input type="text" name="genre" placeholder="Ecrire genre employee" value="<?php echo $row['genre']?>">
                <div class="label">
                    <label for="file">Choisir un image</label>
                    <input style="opacity:0; z-index: -1;" type="file" name="image" id="file" "> 
                </div>
                <input type="hidden" name="image_name" value="<?php echo $row['image'] ?>">
                <input type="hidden" name="id_num" value="<?php echo $row['id'] ?>">
                <input type="submit" name="submit" value="ADD">
            </form>
        </div>

'<?php 
        //    header("location: ../view_admin.php");

       }
    }



}else{
    echo "request not get";
}

require_once 'navbar.php';

?>