
<?php include_once "header.php" ;
session_start();

require_once "../include/conn_db.php";

require_once "../include/config.php";  //configuration db


if(isset($_SESSION['error_exist'])){
    unset($_SESSION['error_exist']);
    echo "<script>alert('admin deja exist')</script>";
}

if(isset($_SESSION['success'])){
    unset($_SESSION['success']);
    echo "<script>alert('Inserted Image successfully')</script>";
}


// start affichage delete admin
if(isset($_SESSION['delete_admin'])){
    unset($_SESSION['delete_admin']);
    echo "<script>alert('Deleted successfully')</script>";
}


// check for delete

if(isset($_SESSION['nom_update'])){
    echo "<script> document.querySelector('.close_form_add_admin').style.display = 'block'  </script>" ;
}



// start affichage size image et trop
if(isset($_SESSION['trop_image_admin'])){
    unset($_SESSION['trop_image_admin']);
    echo "<script>alert('L'image est trop grande (max 10 Mo)')</script>";
}
?>

<link rel="stylesheet" href="style.css">


<?php include_once "navbar.php"
?>


<div  class="main_accueil">  
    <button id="btn_click_add" class="add_admin">
       <a href="#"> add admin</a>
    </button>

<!-- start affichage admin exist dans ma base de donnee -->
<div class="table-modern">
        <table>
            <thead>
                <tr>
                    <th>ID</th><th>image</th><th>nom</th><th>service</th><th>email</th><th colspan="2">Modification</th>
                </tr>
            </thead>
            <tbody>
<?php 

$stmt = $conn->prepare("SELECT * FROM admin ORDER BY id");

$stmt->execute();
$res = $stmt->get_result();
$i = 1;
if($res->num_rows >0){
    while($row = $res->fetch_assoc()){
        $key = bin2hex(openssl_random_pseudo_bytes(32)); 
       echo " <tr>
                    <td>$i</td>
                    <td><img style='width: 120px ; height: 120px ' src='uploads/$row[image]' alt='img'></td>
                    <td>$row[nom] $row[prenom]</td>
                    <td>$row[telephone]</td>
                    <td>$row[email]</td>
                    <td><a href='update_admin.php?id=$row[id]'>Update</a></td>
                    <td ><a href='delete_admin.php?id=$row[id]'>Delete</a></td>
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
<div class="form_add_admin">
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
        <input type="submit" name="submit" value="ADD">
    </form>
</div>

</div>


<?php if(isset($_SESSION['nom'])){ echo $_SESSION['nom'];}?>

<script src="main_add.js"></script>



<?php include_once "footer.php" ?>