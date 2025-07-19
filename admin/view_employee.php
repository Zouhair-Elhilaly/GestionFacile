<?php  
include "../include/conn_db.php";
include "functions/chiffre.php";

session_start();

require_once 'header.php';
// start affiche email send avec successfully
if (isset($_SESSION['email_send_avec_success'])) {
    if ($_SESSION['email_send_avec_success'] === true) {
        echo "<script>alert('Email envoyé avec succès');</script>";
    } else {
        echo "<script>alert('Échec de l\'envoi de l\'email');</script>";
    }
    // Supprime la session pour éviter de réafficher le message
    unset($_SESSION['email_send_avec_success']);
}
// end affiche email send avec successfully


// start affiche delete employe

if (isset($_SESSION['delete_employee'])) {
    if ($_SESSION['delete_employee'] === 1) {
        echo "<script>alert('Employee Delted successfully ');</script>";
    } else {
        echo "<script>alert('Échec suprimme employee');</script>";
    }
    // Supprime la session pour éviter de réafficher le message
    unset($_SESSION['delete_employee']);
}

// end affiche delete employe


?>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="style.employe.css">

<!-- recubur navbar -->
<?php   require_once 'navbar.php' ?>



<!-- start design sapace employee -->
 

<div  class="main_accueil">  
    <button id="btn_click_add" class="add_admin">
       <a href="#">Add Employee</a>
    </button>

<!-- start affichage admin exist dans ma base de donnee -->
<div class="table-modern">
        <table>
            <thead>
                <tr>
                    <th>ID</th><th>image</th><th>nom</th><th>Telephone</th><th>email</th><th>genre</th><th>Nationalite</th><th>age</th><th>Post</th><th colspan="2">Modification</th>
                </tr>
            </thead>
            <tbody>
<?php 

$stmt = $conn->prepare("SELECT * FROM employees ORDER BY id");

$stmt->execute();
$res = $stmt->get_result();
$i = 1;
if($res->num_rows >0){
    while($row = $res->fetch_assoc()){
        $key = bin2hex(openssl_random_pseudo_bytes(32)); 
       echo " <tr>
                    <td>$i</td>
                    <td><img style='width: 120px ; height: 120px ' src='uploads_employees/$row[image]' alt='img'></td>
                    <td>$row[nom] $row[prenom]</td>
                    <td>$row[telephone]</td>
                    <td>$row[email]</td>
                    <td>$row[genre]</td>
                    <td>$row[nationalite]</td>
                    <td>$row[age]</td>
                    <td>$row[post]</td>
                    <td><a href='update_employee.php?id=".encryptId($row['id']) ."'>Update</a></td>
                    <td ><a href='delete_employee.php?id=$row[id]'>Delete</a></td>
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
    <form action="insert_employee.php" method="POST" enctype="multipart/form-data" >
        <h3>Add new employee</h3>
        <h3 id="close_form_add_admin" >X</h3>
        <input type="text" name="nom" placeholder="Ecrire le nom employee"    >
        <input type="text" name="prenom" placeholder="Ecrire prenom employee">
        <input type="text" name="nationalite" placeholder="Ecrire nationalite  employee">
        <input type="text" name="genre" placeholder="Ecrire genre employee">
        <input type="email" name="email" id="" placeholder="Ecrire Email employee">
        <input type="text" name="post1" id="" placeholder="Ecrire post employee">
        <input type="text" name="age" id="" placeholder="Ecrire age employee">
        <input type="text" name="ville" id="" placeholder="Ecrire ville employee">
        <input type="text" name="phone" id="" placeholder="Ex : 0101910087">
        <!-- <input type="password" name="password" id="" placeholder="Ecrire Password employee"> -->
        <div class="label">
            <label for="file">Choisir un image</label>
            <input style="opacity:0; z-index: -1;" type="file" name="image" id="file" />
        </div>
        <input type="submit" name="submit" value="ADD">
    </form>
</div>

</div>



<script src="main_add.js"></script>
