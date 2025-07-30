<?php 


session_start();

require_once '../include/config.php';

include "functions/chiffre.php";

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $id = (int)decryptId($_GET['id']) ;

    $stmt = $conn1->prepare("DELETE FROM category WHERE id_category = ? ;");
    $stmt->bind_param('s' , $id);

    if($stmt->execute()){
        echo "1";
        if ($stmt->affected_rows > 0) {
            
            $_SESSION['delete_category'] = "Suppression réussie";

            header("Location:view_category.php");
            exit();
        } else {
            $_SESSION['delete_category'] = "Aucun enregistrement supprimé (ID non trouvé)";
            header("Location:view_category.php");
            exit();
        }
    }else{
        $_SESSION['delete_category'] = "Echec de la suppression";
         header("Location:view_category.php");
            exit();
    }

}


?>