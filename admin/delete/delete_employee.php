<?php 


session_start();

require_once '../../include/config.php';
include '../functions/chiffre.php';


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
    $id = decryptId(strip_tags(trim($_GET['id']))) ;

    $stmt = $conn1->prepare("DELETE FROM employé WHERE id = ? ;");
    $stmt->bind_param('i' , $id);

    if($stmt->execute()){
        echo "1";
        if ($stmt->affected_rows > 0) {
     
            

            $_SESSION['delete_employee'] =[
                'type' => 'success',
                'msg' => 'Employé supprimé avec succès.'
            ] ;

            header("Location:../view_employee.php?");
            exit();
        } else {
             $_SESSION['delete_employee'] =[
                'type' => 'error',
                'msg' => 'Aucun enregistrement supprimé '
            ] ;
        }
    }else{
        $_SESSION['delete_employee'] =[
                'type' => 'error',
                'msg' => 'Erreur'
            ] ;

    }

}


header("location:../error.php");
exit();


?>