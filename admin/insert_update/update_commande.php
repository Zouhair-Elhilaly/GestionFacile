<?php 



session_start();

//end insert image  
require_once '../../include/config.php';

// require_once 'header.php';

include '../functions/chiffre.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
   header("location:../error.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $idEmploye = filter_input(INPUT_POST , 'id_employe' , FILTER_SANITIZE_NUMBER_INT);

    $idCommande = filter_input(INPUT_POST , 'id_commande' , FILTER_SANITIZE_NUMBER_INT);

    //   if action = 1 accept else rejet
    $action = filter_input(INPUT_POST , 'action' , FILTER_SANITIZE_NUMBER_INT);

    $now = date('Y-m-d H:i:s');
    $update = $conn1->prepare("UPDATE commande SET status = ? , date_rejet = ? WHERE id_command = ?");
   

    $statusString = 'En atente';

    if($action == 1){
          $statusString = 'Confirmée';
        $update->bind_param('ssi',$statusString ,$now ,$idCommande);
    }else{
        $statusString = 'Rejetée';
        $update->bind_param('ssi',$statusString ,$now ,$idCommande);
    }

    $update->execute();

    header("location:../commande.php?status=1");
    exit;
}
?>