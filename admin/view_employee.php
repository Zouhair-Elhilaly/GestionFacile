<link rel="stylesheet" href="style.employe.css">
<?php  
session_start();

include "../include/config.php";

include "functions/chiffre.php";
require_once 'header.php';


// start affiche email send avec successfully
if (isset($_SESSION['email_send_avec_success'])) {
    if ($_SESSION['email_send_avec_success'] === true) {
        ?>
        <div id="myAlert" style="
  background: #4de118ff;
  color: #1a3a15ff;
  padding: 10px; 
  border-radius: 5px;
  max-width: 400px;
  text-align: center;
  position: fixed;
  top: 30px;
  left: 50%;
  transform: translateX(-50%);
  display: none;
">

    Email envoyé avec succès!
  <br><br>
  <button id="nextBtn">Suivant</button>
</div>


<?php
    } else {
        echo "<script>alert('Échec de l\'envoi de l\'email');</script>";
    }
    // Supprime la session pour éviter de réafficher le message
    unset($_SESSION['email_send_avec_success']);
}
// end affiche email send avec successfully



if (isset($_SESSION['insert_employee'])) {
    if ($_SESSION['insert_employee'] != '') {
        $msg = $_SESSION['insert_employee'];
        echo "<script>alert('$msg');</script>";
    }
    // Supprime la session pour éviter de réafficher le message
    unset($_SESSION['insert_employee']);
}

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


<!-- <link rel="stylesheet" href="employe.css"> -->
<link rel="stylesheet" href="style_admin.css">
<link rel="stylesheet" href="style_search.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
<!-- recubur navbar -->


<?php   require_once 'navbar.php' ?>



<!-- start design sapace employee -->
 

<div  class="main_accueil"> 
    <div class="navbar_employe">
        <button id="btn_ajoute_employe" class="add_admin">
            Ajouter un nouvel employé
        </button>
        <div class="content">
            <div class="search-container">
                <i class="fa fa-search search-icon"></i>
                <input type="search" name="search" id="search" placeholder="Search category ...">
            </div> 
        </div> 
    </div>


    <!-- <script src="main_add.js"></script> /ajouter admin from -->

    <!-- start affichage admin exist dans ma base de donnee -->
<div class="table-modern">
        <table>
            <thead>
                <tr>
                    <th>ID</th><th>image</th><th>nom</th><th>Telephone</th><th>email</th><th>genre</th><th>Nationalite</th><th>CNIE</th><th>Post</th><th colspan="2">Modification</th>
                </tr>
            </thead>
            <tbody>
                


<?php 

$stmt = $conn1->prepare("SELECT * FROM employé ORDER BY id");

$stmt->execute();
$res = $stmt->get_result();
$i = 1;
if($res->num_rows >0){
    while($row = $res->fetch_assoc()){
       $idEm = encryptId($row['id']);
       echo " <tr>
                    <td>$i</td>
                    <td><img style='width: 120px ; height: 120px ' src='uploads_employees/$row[image]' alt='img'></td>
                    <td>$row[nom] $row[prenom]</td>
                    <td>$row[Telephone]</td>
                    <td>$row[email]</td>
                    <td>$row[genre]</td>
                    <td>$row[nationalite]</td>
                    <td>$row[CNIE]</td>
                    <td>$row[id_post]</td>
                    <td><a  class='action-btn update-btn' href='update_employee.php?id=".encryptId($row['id']) ."'>Update</a></td>
                    ";?>
                    <td ><a class='action-btn delete-btn' href='#'
                    onclick="confirmDelete('<?= $idEm ?>')"
                    >Delete</a></td>
                </tr>
                <?php
                $i++;
    } 
}



?>

<!-- alert confirm delete -->
<script>
// confirm delete employe
function confirmDelete(id) {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Voulez-vous vraiment supprimer cet employé ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "delete_employee.php?id=" + id;
        }
    });
    
}
// end confirm delete
</script>

   
              
            </tbody>
        </table>
    </div>


    <div class="overlay"></div>   <!-- background overlay -->

<!-- start ajouter employer -->

<div class="ajoute_employe_form display_none">
    <form action="insert_employee.php" method="POST" enctype="multipart/form-data" >
        <h3> Ajouter un administrateur</h3>
        <h3 id="close_form_employe" >X</h3>

        <input type="text" name="nom" placeholder="Ecrire le nom"    >
        <input type="text" name="prenom" placeholder="Ecrire prenom">
        <input type="email" name="email" id="" placeholder="Ecrire Email">
        <input type="text" name="nationalite" placeholder="Ecrire nationalite  employee">
        <input type="text" name="genre" placeholder="Ecrire genre employee">
        <input type="text" name="ville" id="" placeholder="Ecrire ville employee">
        <input type="text" name="CN" id="" placeholder="Ecrire carte national employee">        <!-- <input type="password" name="password" id="" placeholder="Ecrire Password"> -->
        <input type="text" name="phone" id="" placeholder="Ex : 0101910087">

<!-- start affichage les post -->
        <select name="post" id="">
          <option value="" style="opacity: 0.3" disabled selected >choisir post</option>
        <?php 
        $stmt = $conn1->prepare("SELECT * FROM post ORDER BY id_post");
        $stmt->execute();
        $res = $stmt->get_result();
        while($row = $res->fetch_assoc()) {
       ?>
            <option value="<?= $row['id_post'] ?>"><?= $row['nom_post'] ?></option>
       
          <?php 
         }
        ?>
         </select>
<!-- end affichage les post -->

        <div class="label">
            <label for="file">Choisir un image</label>
            <input style="opacity:0; z-index: -1; display: none;" type="file" name="image" id="file" />
        </div>
        <input type="submit" name="submit" value="Ajouter">
    </form>
</div>
<!-- end ajouter employer -->



    
    <!-- start from ajoute employee  -->
<!-- <div class="form_add_admin form_add_admin_2">
    <form action="insert_employee.php" method="POST" enctype="multipart/form-data" >
        <h3>Ajouter un nouvelle employé</h3>
        <h3 id="close_form_add_admin_2" >X</h3>
        <input type="text" name="nom" placeholder="Ecrire le nom employee"    >
        <input type="text" name="prenom" placeholder="Ecrire prenom employee">
        <input type="text" name="nationalite" placeholder="Ecrire nationalite  employee">
        <input type="text" name="genre" placeholder="Ecrire genre employee">
        <input type="email" name="email" id="" placeholder="Ecrire Email employee">
        

        <select name="" id="">
            <option value="" selected>ajouter post</option>
        < ?php $post = $conn1->prepare("SELECT * from post order by id");
        $post->execute();
        $resPost = $post->get_result();
        while($res = $resPost->fetch_assoc()){
       ?>
            <option value="< ?php $res['id_post']?>">< ?php echo $res['nom']?></option>
        < ?php  }
        ?>
        <option value="r">send</option>
        </select>


        <!-- <input type="text" name="post1" id="" placeholder="Ecrire post employee"> -->
        <!-- <input type="text" name="age" id="" placeholder="Ecrire age employee">
        <input type="text" name="ville" id="" placeholder="Ecrire ville employee">
        <input type="text" name="phone" id="" placeholder="Ex : 0101910087">
        <!-- <input type="password" name="password" id="" placeholder="Ecrire Password employee"> -->
        <!-- < div class="label">
            < label for="file">Choisir un image</label>
            <input style="display:none;" type="file" name="image" id="file" />
        </div>
        <input type="submit" name="submit" value="ADD"> -->
    <!-- </form> --> 
<!-- </div> --> 

</div>


<script src="employe.js"></script>


<!-- <script src="main_add.js"></script> -->
