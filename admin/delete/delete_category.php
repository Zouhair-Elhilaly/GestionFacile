<?php 


session_start();

require_once '../../include/config.php';

include "../functions/chiffre.php";

if(isset($_GET['token'])){
   if(decryptId($_GET['token']) != 'token'){
   header("location:../error.php");
   exit();
   }
}else{
    header("location:../error.php");
   exit();
};


if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $id = (int)decryptId($_GET['id']) ;

    $stmt = $conn1->prepare("CALL supprimer_categorie(?)");
    $stmt->bind_param('i' , $id);

    if($stmt->execute()){
       
            $_SESSION['delete_category'] = [
                'titre'=> 'Succès !',
                'type' => 'success',
                 'msg' => 'Suppression réussie'
            ];

        }
    }else{
        $_SESSION['delete_category'] = [
                'titre' => 'Erreur !',
                'type' => 'error',
                 'msg' => 'Echec de la suppression'
            ];
        
    }

 header("Location:../view_category.php");
            exit();

?>