<link rel="stylesheet" href="style.employe.css">
<?php 



session_start();

//end insert image  
require_once '../include/conn_db.php';
require_once '../include/config.php';

include "functions/chiffre.php";

require_once 'header.php';

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $id = strip_tags(trim(decryptId($_GET['id']))) ;
    
    $sql = "SELECT * FROM employé WHERE id  = ?";
    $stmt = $conn1->prepare($sql);
    $stmt->bind_param('i'  , $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows > 0){
       if($row = $result->fetch_assoc()){
           $_SESSION['nom_update'] = $row['nom'];
           $_SESSION['prenom_update'] = $row['prenom'];
           $_SESSION['email_update'] = $row['email'];
           $_SESSION['genre_update'] = $row['genre'];
           $_SESSION['telephone_update'] = $row['Telephone'];
           $_SESSION['password_update'] = $row['mot_de_passe_em'];
           $_SESSION['nationalite_update'] = $row['nationalite'];
           $_SESSION['ville_update'] = $row['ville'];

           $_SESSION['image_update'] = $row['image'];
?>
        <link rel="stylesheet" href="style.css">

        <!-- <style>
            .form_add_admin  {
                display: block;
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50% , -50%);
                /* width: 400px;
                height: 400px; */
            }
            body{
                width: 100%;
                height: 100vh;
            }
            .form_add_admin input{
                margin: 1px auto
            }
        </style> -->

        <div class="ajoute_employe_form">
            <form action="insert_update_employee.php" method="POST" enctype="multipart/form-data" >
                <h3>Modifier Employe</h3>
                <h3 id="close_form_add_admin" class="form_modifier_employe_btn" >X</h3>
                <input type="text" name="nom" placeholder="Ecrire le nom"  value="<?php echo $row['nom']?>"  >
                <input type="text" name="prenom" placeholder="Ecrire prenom" value="<?php echo $row['prenom']?>">
                <input type="email" name="email" id="" placeholder="Ecrire Email" value="<?php echo $row['email']?>">
                <input type="text" name="phone" id="" placeholder="Ex : 0101910087" value="<?php echo $row['Telephone']?>">


                        
        <!-- start affichage les post -->
                <select name="post" id="">
                <?php 
                $stmt = $conn1->prepare("SELECT * FROM post ORDER BY id_post");
                $stmt->execute();
                $res = $stmt->get_result();
                $selected = '';
                while($rows = $res->fetch_assoc()) {
                    if($rows['id_post'] == $row['id_post']){
                        $selected = 'selected';}
            ?>
        
                    <option value="<?= $rows['id_post'] ?>" <?= $selected ?> ><?= $rows['nom_post'] ?></option>
            
                <?php 
                }
                ?>
                </select>
        <!-- end affichage les post -->

                <input type="text" name="CNIE" id="" placeholder="Ecrire CNIE employee" value="<?php echo $row['CNIE']?>">
                <input type="text" name="ville" id="" placeholder="Ecrire ville employee" value="<?php echo $row['ville']?>">
                <input type="text" name="nationalite" placeholder="Ecrire nationalite  employee" value="<?php echo $row['nationalite']?>">
                <input type="text" name="genre" placeholder="Ecrire genre employee" value="<?php echo $row['genre']?>">
                <div class="label">
                    <label for="file">Choisir un image</label>
                    <input style="opacity:0; z-index: -1;" type="file" name="image" id="file" "> 
                </div>
                <input type="text" name="image_name" value="<?php echo $row['image'] ?>">
                <input type="hidden" name="id_num" value="<?php echo $row['id'] ?>">
                <input type="submit" name="submit" value="Modifier">
            </form>
        </div>
<?php 
        //    header("location: ../view_admin.php");

       }
    }



}else{
    echo "request not get";
}

require_once 'navbar.php';

?>


<script>
    let form_modifier_employe_btn = document.querySelector(".form_modifier_employe_btn");

    form_modifier_employe_btn.onclick = () =>{
        window.location = 'view_employee.php';
    }
</script>

