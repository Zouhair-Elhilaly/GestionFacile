<?php 


session_start();

require_once '../include/conn_db.php';

include "functions/chiffre.php";

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $id = (int)decryptId($_GET['id']) ;

    $stmt = $conn->prepare("DELETE FROM category WHERE id = ? ;");
    $stmt->bind_param('s' , $id);

    if($stmt->execute()){
        echo "1";
        if ($stmt->affected_rows > 0) {
            echo "Suppression réussie";
            

            $_SESSION['delete_category'] = 1;

            header("Location:view_category.php");
            exit();
        } else {
            echo "Aucun enregistrement supprimé (ID non trouvé)";
        }
    }else{
        echo "not success";
    }

}


?>