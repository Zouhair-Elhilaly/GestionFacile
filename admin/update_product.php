<?php 
session_start();

include "../include/config.php";   // Assurez-vous que $conn1 est défini ici
include "functions/chiffre.php";

if(isset($_GET['token'])){
   if(decryptId($_GET['token']) != 'token'){
   header("location:error.php");
   exit();
   }
}else{
    header("location:error.php");
   exit();
};




if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {

    $id = decryptId($_GET['id']);  // suppose que cela renvoie un ID numérique

    if (!is_numeric($id)) {
       header("location:view_product.php?id=ID non valide");
       exit;
    }

    $id = (int) $id;

    $stmt = $conn1->prepare("SELECT * FROM produit WHERE id_produit = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $res = $result->fetch_assoc();

            // Stocker les données dans la session
            $_SESSION['nom_product'] = $res['nom_produit'];
            $_SESSION['category_id'] = $res['id_category'];
            $_SESSION['quantite_product'] = $res['stock'];
            $_SESSION['id_product'] = $id;
            $_SESSION['image_product'] = $res['image'];

            // Debug (à retirer en production)
            // echo $_SESSION['nom_product'] . "<br>";
            // echo $_SESSION['category_id'] . "<br>";
            // echo $_SESSION['quantite_product'] . "<br>";
            // echo $_SESSION['id_product'] . "<br>";
            // echo $_SESSION['image_product'] . "<br>";

            $_SESSION['product_data'] = 1;
            // Redirection possible
            header("Location: view_product.php");
            exit;
        } else {
            echo "Produit non trouvé.";
        }
    } else {
        echo "Erreur lors de l'exécution de la requête.";
    }

    $stmt->close();
}
?>
