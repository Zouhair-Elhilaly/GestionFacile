<?php 


session_start();

require_once '../include/conn_db.php';

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $id = $_GET['id'] ;

    $stmt = $conn->prepare("DELETE FROM employees WHERE id = ? ;");
    $stmt->bind_param('s' , $id);

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