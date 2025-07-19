<?php 


session_start();

require_once '../include/conn_db.php';

include "functions/chiffre.php";

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $id = (int)decryptId($_GET['id']) ;


    // delete quantite stock produits
        $delte_quantite = $conn->prepare(query: "DELETE FROM quantite_produits  WHERE id_produit = ? ;");
        $delte_quantite ->bind_param('i' , $id);
      if(  $delte_quantite->execute()){
        $delte_quantite->close();

    $stmt = $conn->prepare(query: "DELETE FROM produits WHERE id = ? ;");
    $stmt->bind_param('s' , $id);

    if($stmt->execute()){
        if ($stmt->affected_rows > 0) {
            echo "Suppression réussie";
            

            $_SESSION['delete_category'] = 1;

            

            header("Location:view_product.php");
            exit();
        } else {
            echo "Aucun enregistrement supprimé (ID non trouvé)";
        }
    }
    }else{
        echo "not success";
    }

}


?>