
<?php 



session_start();



//end insert image  
// require_once '../include/conn_db.php';

require_once '../include/config.php';
require_once 'functions/chiffre.php';


if(isset($_GET['token'])){
   if(decryptId($_GET['token']) != 'token'){
   header("location:error.php");
   exit();
   }
}else{
    header("location:error.php");
   exit();
};




// require_once 'header.php';

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $id = decryptId($_GET['id']) ;
    $_SESSION['update_admin'] = ''; 
    $sql = "SELECT * FROM admin WHERE id_admin  = ?";
    $stmt = $conn1->prepare($sql);
    $stmt->bind_param('i'  , $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows > 0){
       if($row = $result->fetch_assoc()){
        $_SESSION['update_admin'] = 1;
           $_SESSION['nom_update'] = $row['nom'];
           $_SESSION['prenom_update'] = $row['prenom'];
           $_SESSION['email_update'] = $row['email'];
           $_SESSION['telephone_update'] = $row['telephone'];
           $_SESSION['password_update'] = $row['mot_de_passe'];
           $_SESSION['image_update'] = $row['image'];
            $_SESSION['id_admin_update'] = $row['id_admin'];
           
       header("location:view_admin.php");
       exit;
       }
    }



}else{
    echo "request not get";
}



?>

<!-- <script>

    close_form_add_admin.addEventListener('click' , ()=>{
        window.location = 'view_admin.php'
    });
</script> -->