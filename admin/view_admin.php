<link rel="stylesheet" href="css/style.php">
<link rel="stylesheet" href="css/style_admin.php">
<?php

// session_start();

define('SECURE_ACCESS', true);

include_once "header.php" ;

?>

<?php // require_once "../include/conn_db.php";

// require_once "functions/chiffre.php";
// require_once "../include/config.php";  //configuration db

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
    <div class="navbar_admin">
        <button id="btn_click_add_admin" class="add_admin">
        Ajouter un administrateur
        </button>
        <div class="search-bar">
                    <input id="search" type="search" placeholder="Rechercher administrateur...">
        </div>
    </div>

<!-- start affichage admin exist dans ma base de donnee -->
<div class="table-modern">
        <table id="adminTableBody">
            <thead>
                <tr>
                    <th>ID</th><th>Image</th><th>Nom</th><th>Créé par</th><th>Telephone</th><th>Email</th><th colspan="2">Modification</th>
                </tr>
            </thead>
            <tbody>
<?php 

$stmt = $conn1->prepare("SELECT * FROM admin ORDER BY id_admin");

$stmt->execute();
$res2 = $stmt->get_result();
$i = 1;
if($res2->num_rows >0){
    while($row = $res2->fetch_assoc()){
        $nomC = '';
        $prenomC = '';
        $id_admin = encryptId($row['id_admin']);


        if($row['adm_id_admin'] != null){

        $adminCrier = $conn1->prepare("SELECT * FROM admin WHERE id_admin  = ?");
        $adminCrier->bind_param("i", $row['adm_id_admin']);
         $adminCrier->execute();
         $resCrier = $adminCrier->get_result();
         if ($resCrier->num_rows > 0) {
                $res = $resCrier->fetch_assoc();
                $nomC = $res['nom'];
                $prenomC = $res['prenom'];
            }
        }

       echo " <tr>
                    <td class='id'>$i</td>
                    <td class='image'><img style='width: 120px ; height: 120px ' src='protection_image/image_proxy.php?img=$row[image]' alt='img'></td>
                    <td class='admin-name Nom' >$row[nom] $row[prenom]</td>
                    <td style='min-height: 30px' class='nom creation'> $nomC $prenomC</td>
                    <td class='telephone'>$row[telephone]</td>
                    <td class='email'>$row[email]</td>
                    <td class='Modifier'><a  class='action-btn update-btn' href='update_admin.php?id=$id_admin&token=$token'>Modifier</a></td>
                    <td class='Supprimer' ><a  class='action-btn delete-btn' href='delete/delete_admin.php?id=$id_admin&token=$token'>Supprimer</a></td>
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
    <form action="insert/insert_admin.php" method="POST" enctype="multipart/form-data" >
        <h3> Ajouter un administrateur</h3>
        <h3 id="close_form_add_admin" >X</h3>
        <input type="text" name="nom" placeholder="Ecrire le nom"    >
        <input type="text" name="prenom" placeholder="Ecrire prenom">
        <input type="email" name="email" id="" placeholder="Ecrire Email">
        <input type="text" name="phone" id="" placeholder="Ex : 0101910087">
        <!-- <input type="password" name="password" id="" placeholder="Ecrire Password"> -->
        <!-- <input type="text" name="parent_admin_id" placeholder="Ecrire par ..."    > -->
        <div class="label">
            <label for="file">Choisir un image</label>
            <input style="opacity:0; z-index: -1;" type="file" name="image" id="file" />
        </div>
        <input type="submit" name="submit" value="Ajouter">
    </form>
</div>

</div>
<!-- start form update admin  -->
<?php 

if(isset($_SESSION['update_admin'] )){
    if($_SESSION['update_admin']  != ''){
        ?>
           <style>
            #form_update_admin{
                 background-color: var(--secondary-color);
                position:fixed;
                padding: 20px;
                box-shadow: var(--shadowForm);
                border-radius: 10px;
                display: block;
                top: 50%;
                /* z-index:1000; */
                left: 50%;
                transform: translate(-50% , -50%);
            }
            body{
                width: 100%;
                height: 100%;
            }
            .update_admin_form1{
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                backdrop-filter: blur(10px) !important; 
                /* transform: translate(-50%, -50%); */
                /* background-color: rgba(0, 0, 0, 0.5); */
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 1000;
            }
        </style>


        <div class="update_admin_form1">
    <form action="insert_update/insert_update_admin.php" method="POST" enctype="multipart/form-data" id="form_update_admin">
        <h3>Modifier l'administrateur</h3>
        <h3 class="close_form_update_admin">X</h3>
        <input type="text" name="nom" placeholder="Ecrire le nom" value="<?php echo $_SESSION['nom_update']?>">
        <input type="text" name="prenom" placeholder="Ecrire prenom" value="<?php echo $_SESSION['prenom_update']?>">
        <input type="email" name="email" placeholder="Ecrire Email" value="<?php echo $_SESSION['email_update']?>">
        <input type="text" name="phone" placeholder="Ex : 0101910087" value="<?php echo $_SESSION['telephone_update']?>">
        
        <div style="display:flex;flex-direction:column;justify-content:center;align-items:center;text-align: center;" class="label">
            <label for="file">Choisir une image</label>
            <input style="opacity:0; z-index: 1; position:absolute; width:100%" type="file" name="image1" id="file" onchange="console.log('Fichier sélectionné:', this.files[0])" >
        </div>
        
        <input type="hidden" name="image_name" value="<?php echo $_SESSION['image_update'] ?>">
        <input type="hidden" name="id_num" value="<?php echo $_SESSION['id_admin_update'] ?>">
        <input type="submit" name="submit" value="Modifier" style="background-color: green">
    </form>
    </div>
  <?php  
unset( $_SESSION['update_admin']) ;
unset($_SESSION['nom_update']);  
unset($_SESSION['prenom_update']);
unset($_SESSION['email_update']); 
unset($_SESSION['telephone_update']); 
unset($_SESSION['image_update']); 
unset($_SESSION['id_admin_update']);  
}
}





?>




<?php if(isset($_SESSION['nom'])){ echo $_SESSION['nom'];}?>


<style>
    td{
        color: var(--text-color)
    }

    
@media (max-width:768px) {
    .main_accueil {
    padding: 0 !important;
    width: 100%;
}
}
    
</style>

<!-- <script src="admin.js"></script> -->
<script>
// start search 

 document.addEventListener('DOMContentLoaded', () => {


        // ===== SYSTÈME DE RECHERCHE SIMPLE =====
console.log("hello")
    const searchInput = document.getElementById('search');
    const tableBody = document.getElementById('adminTableBody');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        const rows = tableBody.querySelectorAll('tr');
        
        rows.forEach(row => {
            const serviceName = row.querySelector('.admin-name');
            if (serviceName) {
                const text = serviceName.textContent.toLowerCase();
                if (text.includes(searchTerm) || searchTerm === '') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    });
// end search 
 });


// end search 


    
// strat controll form ajouter admin
    let ajoute_admin_form = document.querySelector(".ajoute_admin_form");
let btn_click_add_admin = document.getElementById("btn_click_add_admin");


btn_click_add_admin.addEventListener('click' , () => {
    // ajoute_admin_form.classList.remove("dislay_none");
    ajoute_admin_form.style.display = 'flex';
});

close_form_add_admin.addEventListener('click' , ()=>{
    // ajoute_admin_form.classList.add("dislay_none");
        ajoute_admin_form.style.display = "none";

})


// end style close form



    // /start button hide modify form
let close_form_update_admin = document.querySelector(".close_form_update_admin");

close_form_update_admin.addEventListener('click' , ()=>{
    //   let ajoute_admin_form = document.querySelector(".ajoute_admin_form ");
      let form_update_admin = document.querySelector("#form_update_admin");
        form_update_admin.style.display = "none";
        let update_admin_form1 = document.querySelector(".update_admin_form1");
        update_admin_form1.style.display = 'none';
        // aj/oute_admin_form.style.display = "none";
    }); 
</script>


<?php include_once "footer.php" ?>


