<?php

session_start();
require_once '../../include/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
   header("../error.php");
    exit();
}

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Initialiser la session pour les messages
    $_SESSION['insert_category'] = '';

    // Valider et nettoyer les entrées
    $category_id = filter_input(INPUT_POST ,'category_id' ,FILTER_SANITIZE_NUMBER_INT);
    $nom = strip_tags(trim($_POST['category_name'] ?? ''));
    $image_name = filter_input(INPUT_POST , 'image_name' ,  FILTER_SANITIZE_STRING);
    $description = strip_tags(trim($_POST['category_description'] ?? ''));

    // Validation des champs
    if (empty($nom)) { 
        $_SESSION['insert_category'] = [
            'type' => 'error',
            'msg' => 'Nom de catégorie invalide.',
            'titre' => 'Erreur !'
        ];
        header("location:../view_category.php");
        exit; 
    }

    if (empty($description)){
        $_SESSION['insert_category'] = [
            'type' => 'error',
            'msg' => 'Description de catégorie invalide.',
            'titre' => 'Erreur !'
        ];
        header("location:../view_category.php");
        exit;
    }

    // Vérifier si la catégorie existe
    $stmt = $conn1->prepare("SELECT id_category FROM category WHERE id_category = ?");
    $stmt->bind_param('i', $category_id);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows > 0) {

        // Traitement de l'image
        $fileName = null;
        if($_FILES['image']['name'] !=  ''){
            
            if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                    $_SESSION['insert_category'] = [
                        'type' => 'error',
                        'msg' => "Erreur lors de l'upload de l'image de catégorie.",
                        'titre' => 'Erreur !'
                    ];
                    header("location:../view_category.php");
                    exit;
                }

                $targetDir = "../image/image_category/";

                // Créer le dossier s'il n'existe pas
                if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true)) {
                    $_SESSION['insert_category'] = [
                        'type' => 'error',
                        'msg' => "Impossible de créer le dossier d'upload.",
                        'titre' => 'Erreur !'
                    ];
                    header("location:../view_category.php");
                    exit;
                }

                // Vérifier que c'est une vraie image
                $check = getimagesize($_FILES['image']['tmp_name']);
                if ($check === false) {
                    $_SESSION['insert_category'] = [
                        'type' => 'error',
                        'msg' => "Le fichier n'est pas une image valide.",
                        'titre' => 'Erreur !'
                    ];
                    header("location:../view_category.php");
                    exit;
                }

                // Générer un nom de fichier unique
                $fileName = uniqid('category_', true) . '.webp';
                $destination = $targetDir . $fileName;
                $fileTmpPath = $_FILES['image']['tmp_name'];

                // Détecter le type MIME pour supporter toutes les extensions
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
                }

                if ($sourceImage !== false) {
                    if (imagewebp($sourceImage, $destination, 80)) {
                        imagedestroy($sourceImage);
                    } else {
                        $_SESSION['insert_category'] = [
                            'type' => 'error',
                            'msg' => "Erreur lors de la conversion en WebP.",
                            'titre' => 'Erreur !'
                        ];
                    }
                } else {
                    $_SESSION['insert_category'] = [
                        'type' => 'error',
                        'msg' => "Impossible d’ouvrir l’image.",
                        'titre' => 'Erreur !'
                    ];
                }
            }
        } else {
            // Si aucune image n'est fournie, on garde l'image existante
            $fileName = $image_name; 
        }
    
        // Mise à jour dans la BDD
        $stmt = $conn1->prepare("UPDATE category SET nom_category = ? ,description = ? ,image = ? WHERE id_category = ?");
        if ($stmt === false) {
            $_SESSION['insert_category'] = [
                'type' => 'error',
                'msg' => "Erreur lors de la préparation de la requête : " . $conn1->error,
                'titre' => 'Erreur !'
            ];
            header("location:../view_category.php");
            exit;
        }

        $stmt->bind_param("sssi", $nom, $description , $fileName , $category_id);

        if ($stmt->execute()) {
            $_SESSION['insert_category'] = [
                'type' => 'success',
                'msg' => "Catégorie modifiée avec succès.",
                'titre' => 'Succès !'
            ];
        } else {
            $_SESSION['insert_category'] = [
                'type' => 'error',
                'msg' => "Erreur lors de la modification de la catégorie.",
                'titre' => 'Erreur !'
            ];
        }

        $stmt->close();
        $conn1->close();

        // Redirection
        header("Location:../view_category.php");
        exit;
    }

    $_SESSION['insert_category'] = [
        'type' => 'error',
        'msg' => "Catégorie introuvable ou non modifiée.",
        'titre' => 'Erreur !'
    ];
    header("Location:../view_category.php?Insert=false");
    exit;
}
