<?php
session_start();
require_once '../../include/config.php';



if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
    include("../error.php");
    exit();
}



// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $_SESSION['insert_product'] = []; // Réinitialiser la session

    // Valider et nettoyer les entrées
    $nom = strip_tags(trim($_POST['productName'] ?? ''));
    $stock = filter_input(INPUT_POST, 'productQuantite', FILTER_SANITIZE_NUMBER_INT);
    $categoryId = filter_input(INPUT_POST, 'productCategory', FILTER_SANITIZE_NUMBER_INT);

    // Validation des champs
    if (empty($nom)) {
        $_SESSION['insert_product'] = [
            'type' => 'error',
            'titre' => 'Erreur !',
            'msg' => 'Le nom du produit est requis'
        ];
        header("location:../view_product.php");
        exit;
    }

    if (empty($stock)) {
        $_SESSION['insert_product'] = [
            'type' => 'error',
            'titre' => 'Erreur !',
            'msg' => 'La quantité est requise'
        ];
        header("location:../view_product.php");
        exit;
    }

    // Vérifier si le produit existe déjà
    $stmt = $conn1->prepare("SELECT id_produit FROM produit WHERE nom_produit = ?");
    $stmt->bind_param('s', $nom);
    $stmt->execute();

    if ($stmt->get_result()->num_rows > 0) {
        $_SESSION['insert_product'] = [
            'type' => 'error',
            'titre' => 'Erreur !',
            'msg' => "Le produit existe déjà"
        ];
        header("Location:../view_product.php");
        exit;
    }

    // Traitement de l'image
    $fileName = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {

        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['insert_product'] = [
                'type' => 'error',
                'titre' => 'Erreur !',
                'msg' => "Erreur lors de l'upload de l'image"
            ];
            header("location:../view_product.php");
            exit;
        }

        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];

        $uploadDir = '../image/image_produit/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $newFileName = 'produit_' . uniqid() . '.webp';
        $destination = $uploadDir . $newFileName;

        // Détecter le type MIME
        $mimeType = mime_content_type($fileTmpPath);

        // Vérifier extension
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $extensions_convertibles = ['jpg', 'jpeg', 'png', 'bmp', 'gif'];
        if (!in_array($extension, $extensions_convertibles)) {
            $_SESSION['insert_product'] = [
                'type' => 'error',
                'titre' => 'Erreur !',
                'msg' => "Type d'image invalide"
            ];
            header("location: ../view_product.php");
            exit;
        }

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
            default:
                $sourceImage = false;
        }

        if ($sourceImage !== false) {
            if (imagewebp($sourceImage, $destination, 80)) {
                imagedestroy($sourceImage);
            } else {
                $_SESSION['insert_product'] = [
                    'type' => 'error',
                    'titre' => 'Erreur !',
                    'msg' => "Erreur lors de la conversion en WebP"
                ];
            }
        } else {
            $_SESSION['insert_product'] = [
                'type' => 'error',
                'titre' => 'Erreur !',
                'msg' => "Impossible d’ouvrir l’image"
            ];
        }
    }

    if ($fileName === null) {
        $_SESSION['insert_product'] = [
            'type' => 'error',
            'titre' => 'Erreur !',
            'msg' => 'L’image du produit est requise'
        ];
        header("location:../view_product.php");
        exit;
    }

    // Insertion dans la BDD
    $stmt = $conn1->prepare("INSERT INTO produit (nom_produit, id_category, stock, image) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        $_SESSION['insert_product'] = [
            'type' => 'error',
            'titre' => 'Erreur !',
            'msg' => "Erreur préparation de la requête : " . $conn1->error
        ];
        header("location:../view_product.php");
        exit;
    }

    $stmt->bind_param("siss", $nom, $categoryId, $stock, $newFileName);

    if ($stmt->execute()) {
        $_SESSION['insert_product'] = [
            'type' => 'success',
            'titre' => 'Opération réussie !',
            'msg' => "Nouveau produit ajouté avec succès"
        ];
    } else {
        $_SESSION['insert_product'] = [
            'type' => 'error',
            'titre' => 'Erreur !',
            'msg' => "Erreur lors de l'ajout du produit : " . $stmt->error
        ];
    }

    $stmt->close();
    $conn1->close();

    // Redirection
    header("Location:../view_product.php");
    exit;
}
