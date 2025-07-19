<?php
session_start();
require_once '../include/conn_db.php';
require_once 'functions/chiffre.php';

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Vérification CSRF
    // if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    //     $_SESSION['error_update_product'] = "Tentative de soumission non autorisée.";
    //     header("location:view_product.php");
    //     exit;
    // }
    // unset($_SESSION['csrf_token']); // détruire le token

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
        $updateName = $conn->prepare("UPDATE produits SET nom = ? WHERE id = ?");
        $updateName->bind_param('si', $nom, $id);
        if (!$updateName->execute()) {
            $_SESSION['error_update_product'] = 'Erreur lors de la mise à jour du nom.';
            header("location:view_product.php");
            exit;
        }
    }

    // Mise à jour de la quantité
    if (!empty($quantite)) {
        $update_qnt = $conn->prepare("UPDATE quantite_produits SET quantite = ? WHERE id_produit = ?");
        $update_qnt->bind_param('ii', $quantite, $id);
        if (!$update_qnt->execute()) {
            $_SESSION['error_update_product'] = 'Erreur lors de la mise à jour de la quantité.';
            header("location:view_product.php");
            exit;
        }
    }

    // Mise à jour de la catégorie
    if (!empty($category_ID)) {
        $updateCategory = $conn->prepare("UPDATE produits SET category_id = ? WHERE id = ?");
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

        $targetDir = "uploads_produits/";
        if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true)) {
            $_SESSION['error_update_product'] = "Impossible de créer le dossier d'upload.";
            header("location:view_product.php");
            exit;
        }

        $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'avif'];
        $maxSize = 30 * 1024 * 1024; // 30 Mo

        if ($_FILES['image']['size'] > $maxSize) {
            $_SESSION['error_update_product'] = "L'image est trop grande (max 30 Mo)";
            header("location:view_product.php");
            exit;
        }

        if (!in_array($fileExt, $allowedTypes)) {
            $_SESSION['error_update_product'] = "Type d'image invalide.";
            header("location:view_product.php");
            exit;
        }

        $fileName = uniqid('produit_', true) . '.' . $fileExt;
        $targetFile = $targetDir . $fileName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $_SESSION['error_update_product'] = "Erreur lors de l'enregistrement de l'image.";
            header("location:view_product.php");
            exit;
        }

        // Mise à jour du chemin de l'image
        $updateImage = $conn->prepare("UPDATE produits SET image = ? WHERE id = ?");
        $updateImage->bind_param('si', $fileName, $id);
        if (!$updateImage->execute()) {
            $_SESSION['error_update_product'] = "Erreur lors de la mise à jour de l'image.";
            header("location:view_product.php");
            exit;
        }
    }

    $_SESSION['error_update_product'] = 'Mise à jour réussie.';
    header("location:view_product.php");
    exit;
}
?>
