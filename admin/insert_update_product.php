<?php
session_start();
require_once '../include/config.php';
require_once 'functions/chiffre.php';

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  
    // Valider et nettoyer les entrées
    $id = decryptId($_POST['productId']);
    $productImage = decryptId(trim($_POST['productImage']));
    $nom = strip_tags(trim($_POST['productName'] ?? ''));
    $quantite = filter_input(INPUT_POST, 'productQuantite', FILTER_SANITIZE_NUMBER_INT);
    $category_ID = filter_input(INPUT_POST, 'productCategory', FILTER_SANITIZE_NUMBER_INT);

    // header("location:view_product.php?id=$id");
    // exit;

    // Vérification ID
    if (!is_numeric($id) || $id <= 0) {
        $_SESSION['error_update_product'] = "ID produit invalide.";
        header("location:view_product.php?$id");
        exit;
    }

    // Mise à jour du nom du produit
    if (!empty($nom)) {
        $updateName = $conn1->prepare("UPDATE produit SET nom_produit = ? WHERE id_produit = ?");
        $updateName->bind_param('si', $nom, $id);
        if (!$updateName->execute()) {
            $_SESSION['error_update_product'] = 'Erreur lors de la mise à jour du nom.';
            header("location:view_product.php");
            exit;
        }
    }

    // Mise à jour de la quantité
    if (!empty($quantite)) {
        $update_qnt = $conn1->prepare("UPDATE produit SET stock = ? WHERE id_produit = ?");
        $update_qnt->bind_param('ii', $quantite, $id);
        if (!$update_qnt->execute()) {
            $_SESSION['error_update_product'] = 'Erreur lors de la mise à jour de la quantité.';
            header("location:view_product.php");
            exit;
        }
    }

    // Mise à jour de la catégorie
    if (!empty($category_ID)) {
        $updateCategory = $conn1->prepare("UPDATE produit SET id_category = ? WHERE id_produit = ?");
        $updateCategory->bind_param('ii', $category_ID, $id);
        if (!$updateCategory->execute()) {
            $_SESSION['error_update_product'] = 'Erreur lors de la mise à jour de la catégorie.';
            header("location:view_product.php");
            exit;
        }
    }

    // Gestion de l'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error_update_product'] = "Erreur lors de l'upload de l'image.";
            header("location:view_product.php");
            exit;
        }

        $targetDir = "image/image_produit/";
        if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true)) {
            $_SESSION['error_update_product'] = "Impossible de créer le dossier d'upload.";
            header("location:view_product.php");
            exit;
        }

       

        $fileTmpPath = $_FILES['image']['tmp_name'];
            $newFileName = 'produit_'.uniqid() . '.webp';
            $destination =  $targetDir . $newFileName;

            // 🌟 Détecter le type MIME pour supporter toutes les extensions
            $mimeType = mime_content_type($fileTmpPath);

            switch ($mimeType) {
                case 'image/jpeg':
                    $sourceImage = imagecreatefromjpeg($fileTmpPath);
                    break;
                case 'image/png':
                    $sourceImage = imagecreatefrompng($fileTmpPath);
                    break;
                case 'image/gif':
                    $sourceImage = imagecreatefromgif($fileTmpPath);
                    break;
                case 'image/webp':
                    $sourceImage = imagecreatefromwebp($fileTmpPath);
                    break;
                case 'image/bmp':
                    $sourceImage = imagecreatefrombmp($fileTmpPath);
                    break;
                // default:
                //     die("❌ Ce format n’est pas supporté : $mimeType");
            }

            

            if ($sourceImage !== false) {
                if (imagewebp($sourceImage, $destination, 80)) {
                    imagedestroy($sourceImage);
                    
                } else {
                            $_SESSION['error_update_product']  = "❌ Erreur lors de la conversion en WebP.";
                }
            } else {
                      $_SESSION['error_update_product']  =  "❌ Impossible d’ouvrir l’image.";
            }
        
        // end webp image 

        // Mise à jour du chemin de l'image
        $updateImage = $conn1->prepare("UPDATE produit SET image = ? WHERE id_produit = ?");
        $updateImage->bind_param('si', $newFileName, $id);
        if (!$updateImage->execute()) {
            $_SESSION['error_update_product'] = "Erreur lors de la mise à jour de l'image.";
            header("location:view_product.php");
            exit;
        }
    } //end image

    $_SESSION['error_update_product'] = 'Mise à jour réussie.';
    header("location:view_product.php");
    exit;
}
?>
