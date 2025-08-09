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
    $id = filter_var(((int)decryptId($_GET['id'])), FILTER_SANITIZE_NUMBER_INT) ;

   $checkProduitExsistEnDetails = $conn1->prepare("SELECT * FROM détailler WHERE id_produit = ?");
   $checkProduitExsistEnDetails->bind_param("i",$id);
    $checkProduitExsistEnDetails->execute();
    $resultCheckProduitExsistEnDetails =$checkProduitExsistEnDetails->get_result();


    if(  $resultCheckProduitExsistEnDetails->num_rows > 0){
        $resultIdCmd = $resultCheckProduitExsistEnDetails->fetch_assoc();

        $deleteAllCommande = $conn1->prepare("DELETE FROM commande WHERE id_command = ?");
        $deleteAllCommande->bind_param('i',$resultIdCmd['id_command']);
        $deleteAllCommande->execute();


    }
 
    $stmt = $conn1->prepare("DELETE FROM produit WHERE id_produit = ? ;");
    $stmt->bind_param('i' , $id);

    if($stmt->execute()){
        if ($stmt->affected_rows > 0) {
            
            $_SESSION['delete_produit'] = [
                'titre' => 'Succès !',
                'type' => 'success',
                'msg' => 'La suppression a été effectuée.'
            ];

            

            header("Location:../view_product.php");
            exit();
        } else {
             $_SESSION['delete_produit'] = [
                'titre' => 'Erreur !',
                'type' => 'warning',
                'msg' => 'Aucun enregistrement supprimé (ID non trouvé)'
            ];
           
        }
    }
    }else{
        $_SESSION['delete_produit'] = [
                'titre' => 'Erreur !',
                'msg' => 'Aucun enregistrement supprimé'
            ];

    }


 header("Location:../error.php");
 exit();

?>