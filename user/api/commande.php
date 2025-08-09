<?php 

require_once '../../include/config.php';
header('Content-Type: application/json');

include "../../admin/functions/chiffre.php";

if(isset($_GET['token'])){
    if(decryptId($_GET['token']) != 'hello'){
        header("location:../../error.php");
        exit();
    }
}else{
      header("location:../../error.php");
        exit();
}

if (isset($_GET['quantite']) && isset($_GET['id']) && $_GET['id_product_hidden']) {
    $quantite = filter_input(INPUT_GET, 'quantite', FILTER_SANITIZE_NUMBER_INT);
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    // $id_product_hidden = filter_input(INPUT_GET, 'id_product_hidden', FILTER_SANITIZE_NUMBER_INT);

    $status = 'Rejetée';
    // Vérifier si la commande existe
    $stmt = $conn1->prepare("SELECT * FROM commande WHERE id_command = ? and status != ? ");
    $stmt->bind_param('is', $id, $status);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {

        // Récupérer les détails de la commande
        $idProduit = $conn1->prepare("select * from détailler WHERE id_command = ? ");
        $idProduit->bind_param('i', $id);
        $idProduit->execute();
        $resIdProduitDetailler = $idProduit->get_result()->fetch_assoc();
        
        // Quantité actuelle dans la table détailler
        $quantiteActuelle = $resIdProduitDetailler['quantité'];
        $idProduitActuel = $resIdProduitDetailler['id_produit'];

        // Récupérer le stock actuel du produit
        $stockExist = $conn1->prepare("SELECT stock as stock FROM produit WHERE id_produit = ?");
        $stockExist->bind_param('i', $idProduitActuel);
        $stockExist->execute();
        $restockExist = $stockExist->get_result()->fetch_assoc();
        $stockProduit = $restockExist['stock'];

        // Calculer la différence entre nouvelle quantité et ancienne
        $differenceQuantite = $quantite - $quantiteActuelle;

        if ($differenceQuantite > 0) {
            // L'utilisateur veut augmenter la quantité
            // Vérifier si le stock suffit pour cette augmentation
            if ($differenceQuantite > $stockProduit) {
                echo json_encode([
                    'type' => 'warning',
                    'success' => false,
                    'message' => 'Stock insuffisant. Stock disponible: ' . $stockProduit
                ]);
                exit;
            }
            
            // Diminuer le stock du produit
            $produitStock = $conn1->prepare("UPDATE produit SET stock = stock - ? WHERE id_produit = ?");
            $produitStock->bind_param('ii', $differenceQuantite, $idProduitActuel);
            $produitStock->execute();
            
        } else if ($differenceQuantite < 0) {
            // L'utilisateur veut diminuer la quantité
            // Remettre la différence dans le stock
            $quantiteARestituer = abs($differenceQuantite);
            $produitStock = $conn1->prepare("UPDATE produit SET stock = stock + ? WHERE id_produit = ?");
            $produitStock->bind_param('ii', $quantiteARestituer, $idProduitActuel);
            $produitStock->execute();
        }
        // Si $differenceQuantite == 0, pas de changement nécessaire dans le stock

        // Mettre à jour la quantité dans détailler
        $update = $conn1->prepare("UPDATE détailler SET quantité = ? WHERE id_command = ?");
        $update->bind_param('ii', $quantite, $id);
        
        if ($update->execute()) {
            echo json_encode([
                 'type' => 'success',
                'success' => true,
                'message' => 'Quantité mise à jour avec succès'
            ]);
        } else {
            echo json_encode([
                 'type' => 'error',
                'success' => false,
                'message' => 'Erreur lors de la mise à jour'
            ]);
        }
        $update->close();
        
    } else {
        echo json_encode([
            'type' => 'error',
            'success' => false,
            'message' => 'Commande introuvable'
        ]);
    }

    $stmt->close();
    $conn1->close();
} else {
    echo json_encode([
        'type' => 'error',
        'success' => false,
        'message' => 'Paramètres manquants'
    ]);
}

exit;
?>