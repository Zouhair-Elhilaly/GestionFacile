<!-- <link rel="stylesheet" href="style.employe.css"> -->
<?php 



session_start();

//end insert image  
// require_once '../include/conn_db.php';
require_once '../include/config.php';

include "functions/chiffre.php";

// require_once 'header.php';


if(isset($_GET['token'])){
   if(decryptId($_GET['token']) != 'token'){
   header("location:error.php");
   exit();
   }
}else{
    header("location:error.php");
   exit();
};




if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $id = strip_tags(trim(decryptId($_GET['id']))) ;
    
    $sql = "SELECT * FROM employé WHERE id  = ?";
    $stmt = $conn1->prepare($sql);
    $stmt->bind_param('i'  , $id);
    $stmt->execute();

    $_SESSION['update_employe'] = '';

    $result = $stmt->get_result();

    if($result->num_rows > 0){
       if($row = $result->fetch_assoc()){
        $_SESSION['update_employe'] = '1881';

          $_SESSION['nom_update'] = $row['nom'];
           $_SESSION['prenom_update'] = $row['prenom'];
           $_SESSION['email_update'] = $row['email'];
           $_SESSION['CNIE_update']  =$row['CNIE'];
           $_SESSION['genre_update'] = $row['genre'];
           $_SESSION['telephone_update'] = $row['Telephone'];
        //    $_SESSION['password_update'] = $row['mot_de_passe_em'];
           $_SESSION['nationalite_update'] = $row['nationalite'];
           $_SESSION['ville_update'] = $row['ville'];
           $_SESSION['id_update'] = $row['id'];
           $_SESSION['image_update'] = $row['image'];
           $_SESSION['post_update'] = $row['id_post'];
?>
        <!-- <link rel="stylesheet" href="style.css"> -->

     

<?php 
           header("location:view_employee.php");
           exit;

       }
    }else{
    
    }



}else{
    echo "request not get";
}

// require_once 'navbar.php';

?>


<!-- <script>
    let form_modifier_employe_btn = document.querySelector(".form_modifier_employe_btn");

    form_modifier_employe_btn.onclick = () =>{
        window.location = 'view_employee.php';
    }
</script> -->

