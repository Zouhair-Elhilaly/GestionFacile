<?php

session_start();
include '../admin/functions/chiffre.php';
include "../include/config.php";

if(isset($_GET['token'])){
    if(decryptId($_GET['token']) != 'hello'){
        header("location:../error.php");
        exit();
    }
}else{
      header("location:../error.php");
        exit();
}

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $id = decryptId($_GET['id']);
    $id = filter_var($id , FILTER_SANITIZE_NUMBER_INT);
    if(isset($id)){
        $stmt = $conn1->prepare("SELECT * from employé WHERE id = ?");
         $stmt->bind_param('i' , $id);
         $stmt->execute();
         $res = $stmt->get_result();
         if($res->num_rows > 0){
            session_unset();
            session_destroy();
            header("location:../index.php");
            exit;
         }else{
            $_SESSION['empty_employe'] = [
                'type' => 'error',
                'titre' => 'Erreur de déconnexion',
                'msg' => 'Une erreur est survenue lors de la déconnexion. Veuillez réessayer.'
            ];
         }
    }
}


$stmt->close();
header("location:home.php");
exit();

?>