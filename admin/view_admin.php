
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="style_admin.css">

<?php

session_start();

include_once "header.php" ;

require_once "../include/conn_db.php";

require_once "functions/chiffre.php";
require_once "../include/config.php";  //configuration db

// if(isset($_SESSION['insert_admin'])){
//    if($_SESSION['insert_admin'] != ''){
//     $msg = $_SESSION['insert_admin'];
//     echo "<script>alert('$msg');</script>";
//     unset($_SESSION['insert_admin']);
//    } 
// }


// insert admin
if(isset($_SESSION['insert_admin'])){
    if(!empty($_SESSION['insert_admin'])){
        $text = $_SESSION['insert_admin']['msg'];
        $type = $_SESSION['insert_admin']['type'];
        echo "<script>
        Swal.fire({
    icon: '$type',
    title: 'Opération réussie !',
    text: '$text',
    timer: 3000 
});</script>";
        unset($_SESSION['insert_admin']);
    }
}
// end insert admin







// insert update
if(isset($_SESSION['insert_update_admin'])){
    if(!empty($_SESSION['insert_update_admin'])){
        $text = $_SESSION['insert_update_admin']['msg'];
        $type = $_SESSION['insert_update_admin']['type'];
        echo "<script>
        Swal.fire({
    icon: '$type',
    title: 'Opération réussie !',
    text: '$text',
    timer: 3000 
});</script>";
        unset($_SESSION['insert_update_admin']);
    }
}
// end insert update


if(isset($_SESSION['error_exist'])){
    unset($_SESSION['error_exist']);
    echo "<script>alert('admin deja exist')</script>";
}




// delete admin
if(isset($_SESSION['delete_admin'])){
        echo "<script>
        Swal.fire({
    icon: 'success',
    title: 'Opération réussie !',
    text: ' L’administrateur a été supprimé avec succès. ',
    timer: 3000 
});</script>";
        unset($_SESSION['delete_admin']);
    
}
// end delete admin



// check for delete

if(isset($_SESSION['nom_update'])){
    echo "<script> document.querySelector('.close_form_add_admin').style.display = 'block'  </script>" ;
}



// // start affichage size image et trop
// if(isset($_SESSION['trop_image_admin'])){
//     unset($_SESSION['trop_image_admin']);
//     echo "<script>alert('L'image est trop grande (max 10 Mo)')</script>";
// }
?>


<?php include_once "navbar.php"
?>


<div  class="main_accueil">  
    <button id="btn_click_add_admin" class="add_admin">
      Ajouter un administrateur
    </button>

<!-- start affichage admin exist dans ma base de donnee -->
<div class="table-modern">
        <table>
            <thead>
                <tr>
                    <th>ID</th><th>Image</th><th>Nom</th><th>Créé par</th><th>Telephone</th><th>Email</th><th colspan="2">Modification</th>
                </tr>
            </thead>
            <tbody>
<?php 

$stmt = $conn1->prepare("SELECT * FROM admin ORDER BY id_admin");

$stmt->execute();
$res = $stmt->get_result();
$i = 1;
if($res->num_rows >0){
    while($row = $res->fetch_assoc()){
        $id_admin = encryptId($row['id_admin']);
       echo " <tr>
                    <td>$i</td>
                    <td><img style='width: 120px ; height: 120px ' src='uploads/$row[image]' alt='img'></td>
                    <td>$row[nom] $row[prenom]</td>
                    <td>$row[adm_id_admin]</td>
                    <td>$row[telephone]</td>
                    <td>$row[email]</td>
                    <td><a  class='action-btn update-btn' href='update_admin.php?id=$id_admin'>Update</a></td>
                    <td ><a  class='action-btn delete-btn' href='delete_admin.php?id=$id_admin'>Delete</a></td>
                </tr>
                ";
                $i++;
    }
}




?>



   
              
            </tbody>
        </table>
    </div>
    <div class="overlay"></div>   /<!-- background overlay

    <!-- start from add admin  -->
 

<div class="ajoute_admin_form display_none">
    <form action="insert_admin.php" method="POST" enctype="multipart/form-data" >
        <h3> Ajouter un administrateur</h3>
        <h3 id="close_form_add_admin" >X</h3>
        <input type="text" name="nom" placeholder="Ecrire le nom"    >
        <input type="text" name="prenom" placeholder="Ecrire prenom">
        <input type="email" name="email" id="" placeholder="Ecrire Email">
        <input type="text" name="phone" id="" placeholder="Ex : 0101910087">
        <!-- <input type="password" name="password" id="" placeholder="Ecrire Password"> -->
        <input type="text" name="parent_admin_id" placeholder="Ecrire par ..."    >
        <div class="label">
            <label for="file">Choisir un image</label>
            <input style="opacity:0; z-index: -1;" type="file" name="image" id="file" />
        </div>
        <input type="submit" name="submit" value="Ajouter">
    </form>
</div>

</div>


<?php if(isset($_SESSION['nom'])){ echo $_SESSION['nom'];}?>



<?php include_once "footer.php" ?>
<script src="admin.js"></script>