<?php 


session_start();

require_once '../include/config.php';

include "functions/chiffre.php";

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $id = filter_var(((int)decryptId($_GET['id'])), FILTER_SANITIZE_NUMBER_INT) ;


 
    $stmt = $conn1->prepare("DELETE FROM produit WHERE id_produit = ? ;");
    $stmt->bind_param('s' , $id);

    if($stmt->execute()){
        if ($stmt->affected_rows > 0) {
            echo "Suppression réussie";
            

            $_SESSION['delete_produit'] = 1;

            

            header("Location:view_product.php");
            exit();
        } else {
            echo "Aucun enregistrement supprimé (ID non trouvé)";
        }
    }
    }else{
        echo "not success";
    }




?>