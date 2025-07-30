<link rel="stylesheet" href="style_admin.css">
<!-- <link rel="stylesheet" href="style.css"> -->
<?php 



session_start();

//end insert image  
require_once '../include/conn_db.php';

require_once '../include/config.php';
require_once 'functions/chiffre.php';


require_once 'header.php';

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $id = decryptId($_GET['id']) ;
    
    $sql = "SELECT * FROM admin WHERE id_admin  = ?";
    $stmt = $conn1->prepare($sql);
    $stmt->bind_param('i'  , $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows > 0){
       if($row = $result->fetch_assoc()){
           $_SESSION['nom_update'] = $row['nom'];
           $_SESSION['prenom_update'] = $row['prenom'];
           $_SESSION['email_update'] = $row['email'];
           $_SESSION['telephone_update'] = $row['telephone'];
           $_SESSION['password_update'] = $row['mot_de_passe'];
           $_SESSION['image_update'] = $row['image'];

           
?>
 

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

        <div class="ajoute_admin_form" style="display: flex">
            <form action="insert_update_admin.php" method="POST" enctype="multipart/form-data" >
                <h3>Modifier l’administrateu </h3>
                <h3 id="close_form_add_admin" >X</h3>
                <input type="text" name="nom" placeholder="Ecrire le nom"  value="<?php echo $row['nom']?>"  >
                <input type="text" name="prenom" placeholder="Ecrire prenom" value="<?php echo $row['prenom']?>">
                <input type="email" name="email" id="" placeholder="Ecrire Email" value="<?php echo $row['email']?>">
                <input type="text" name="phone" id="" placeholder="Ex : 0101910087" value="<?php echo $row['telephone']?>">
                <!-- <input type="password" name="password" id="" placeholder="Ecrire Password" value="<?php echo $row['mot_de_passe']?>"> -->
                <div class="label">
                    <label for="file">Choisir un image</label>
                    <input style="opacity:0; z-index: -1;" type="file" name="image" id="file" "> 
                </div>
                 <input type="hidden" name="image_name" value="<?php echo $row['image'] ?>">
                <input type="hidden" name="id_num" value="<?php echo $row['id_admin'] ?>">
                <input type="submit" name="submit" value="Modifier" style="background-color: green">
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

<script>

    close_form_add_admin.addEventListener('click' , ()=>{
        window.location = 'view_admin.php'
    });
</script>