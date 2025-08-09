<?php 

session_start();
require_once '../../include/config.php';
require_once '../functions/chiffre.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
    include("../error.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = decryptId($_POST['productId']);
    $productImage = decryptId(trim($_POST['productImage']));
    $nom = strip_tags(trim($_POST['productName'] ?? ''));
    $quantite = filter_input(INPUT_POST, 'productQuantite', FILTER_SANITIZE_NUMBER_INT);
    $category_ID = filter_input(INPUT_POST, 'productCategory', FILTER_SANITIZE_NUMBER_INT);

    if (!is_numeric($id) || $id <= 0) {
        $_SESSION['insert_product'] = [
            'type' => 'error',
            'msg' => "ID produit invalide.",
            'title' => "Erreur !"
        ];
        header("location:../view_product.php");
        exit;
    }

    if (!empty($nom)) {
        $updateName = $conn1->prepare("UPDATE produit SET nom_produit = ? WHERE id_produit = ?");
        $updateName->bind_param('si', $nom, $id);
        if (!$updateName->execute()) {
            $_SESSION['insert_product'] = [
                'type' => 'error',
                'msg' => 'Erreur lors de la mise à jour du nom.',
                'title' => 'Erreur !'
            ];
            header("location:../view_product.php");
            exit;
        }
    }

    if (!empty($quantite)) {
        $update_qnt = $conn1->prepare("UPDATE produit SET stock = ? WHERE id_produit = ?");
        $update_qnt->bind_param('ii', $quantite, $id);
        if (!$update_qnt->execute()) {
            $_SESSION['insert_product'] = [
                'type' => 'error',
                'msg' => 'Erreur lors de la mise à jour de la quantité.',
                'title' => 'Erreur !'
            ];
            header("location:../view_product.php");
            exit;
        }
    }

    if (!empty($category_ID)) {
        $updateCategory = $conn1->prepare("UPDATE produit SET id_category = ? WHERE id_produit = ?");
        $updateCategory->bind_param('ii', $category_ID, $id);
        if (!$updateCategory->execute()) {
            $_SESSION['insert_product'] = [
                'type' => 'error',
                'msg' => 'Erreur lors de la mise à jour de la catégorie.',
                'title' => 'Erreur !'
            ];
            header("location:../view_product.php");
            exit;
        }
    }

    // Traitement image
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['insert_product'] = [
                'type' => 'error',
                'msg' => "Erreur lors de l'upload de l'image.",
                'title' => 'Erreur !'
            ];
            header("location:../view_product.php");
            exit;
        }

        $targetDir = "../image/image_produit/";
        if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true)) {
            $_SESSION['insert_product'] = [
                'type' => 'error',
                'msg' => "Impossible de créer le dossier d'upload.",
                'title' => 'Erreur !'
            ];
            header("location:../view_product.php");
            exit;
        }

        $fileTmpPath = $_FILES['image']['tmp_name'];
        $newFileName = 'produit_' . uniqid() . '.webp';
        $destination = $targetDir . $newFileName;

        $mimeType = mime_content_type($fileTmpPath);

        switch ($mimeType) {
            case 'image/jpeg': $sourceImage = imagecreatefromjpeg($fileTmpPath); break;
            case 'image/png':  $sourceImage = imagecreatefrompng($fileTmpPath); break;
            case 'image/gif':  $sourceImage = imagecreatefromgif($fileTmpPath); break;
            case 'image/webp': $sourceImage = imagecreatefromwebp($fileTmpPath); break;
            case 'image/bmp':  $sourceImage = imagecreatefrombmp($fileTmpPath); break;
            default:
                $_SESSION['insert_product'] = [
                    'type' => 'error',
                    'msg' => "Format d'image non supporté : $mimeType",
                    'title' => 'Erreur !'
                ];
                header("location:../view_product.php");
                exit;
        }

        if ($sourceImage !== false) {
            if (imagewebp($sourceImage, $destination, 80)) {
                imagedestroy($sourceImage);
            } else {
                $_SESSION['insert_product'] = [
                    'type' => 'error',
                    'msg' => "Erreur lors de la conversion en WebP.",
                    'title' => 'Erreur !'
                ];
                header("location:../view_product.php");
                exit;
            }
        } else {
            $_SESSION['insert_product'] = [
                'type' => 'error',
                'msg' => "Impossible d’ouvrir l’image.",
                'title' => 'Erreur !'
            ];
            header("location:../view_product.php");
            exit;
        }

        $updateImage = $conn1->prepare("UPDATE produit SET image = ? WHERE id_produit = ?");
        $updateImage->bind_param('si', $newFileName, $id);
        if (!$updateImage->execute()) {
            $_SESSION['insert_product'] = [
                'type' => 'error',
                'msg' => "Erreur lors de la mise à jour de l'image.",
                'title' => 'Erreur !'
            ];
            header("location:../view_product.php");
            exit;
        }
    }

    // Succès général
    $_SESSION['insert_product'] = [
        'type' => 'success',
        'msg' => "Produit mis à jour avec succès.",
        'title' => "Opération réussie !"
    ];
    header("location:../view_product.php");
    exit;
}

?>