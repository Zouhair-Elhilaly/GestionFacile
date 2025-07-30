<?php 


session_start();

require_once '../include/config.php';
include 'functions/chiffre.php';

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $id = decryptId(strip_tags(trim($_GET['id']))) ;

    $stmt = $conn1->prepare("DELETE FROM employé WHERE id = ? ;");
    $stmt->bind_param('i' , $id);

    if($stmt->execute()){
        echo "1";
        if ($stmt->affected_rows > 0) {
            echo "Suppression réussie";
            

            $_SESSION['delete_employee'] = 1;

            header("Location:view_employee.php?");
            exit();
        } else {
            echo "Aucun enregistrement supprimé (ID non trouvé)";
        }
    }else{
        echo "not success";
    }

}


?>