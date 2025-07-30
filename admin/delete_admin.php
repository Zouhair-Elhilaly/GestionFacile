<?php 


session_start();

require_once '../include/config.php';
require_once 'functions/chiffre.php';

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $id = decryptId($_GET['id']) ;

    $stmt = $conn1->prepare("DELETE FROM admin WHERE id_admin = ? ;");
    $stmt->bind_param('s' , $id);

    if($stmt->execute()){
        echo "1";
        if ($stmt->affected_rows > 0) {
            echo "Suppression réussie";
            

            $_SESSION['delete_admin'] = 1;

            header("Location:view_admin.php?");
            exit();
        } else {
            echo "Aucun enregistrement supprimé (ID non trouvé)";
        }
    }else{
        echo "not success";
    }

}


?>