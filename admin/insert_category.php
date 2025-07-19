<?php

session_start();
require_once '../include/conn_db.php';

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Initialiser les variables et messages d'erreur
    $errors = [];
    $success = false;

    // Valider et nettoyer les entrées
    $nom = strip_tags(trim($_POST['category_name'] ?? ''));
    $description = filter_input(INPUT_POST , 'category_description' , FILTER_SANITIZE_STRING);

   
    
    // Validation des champs
    if (empty($nom)) { 
        $_SESSION['error_category'] = 'invalid nom category';
        header("location:view_category.php");
         exit; 
        };
    if (empty($description)) {
         $_SESSION['error_category'] = 'invalid description category';
         header("location:view_category.php");
          exit;
      };
    
    
    // Vérifier si l'employee email  existe déjà et n'est pas modifier 
    $stmt = $conn->prepare("SELECT id FROM category WHERE name = ?");
    $stmt->bind_param('s', $nom);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows > 0) {
        $_SESSION['error_category'] = 'category exist deja';
         header("location:view_category.php");
    }
    

    // Traitement de l'image
    $fileName = null;
    if($_FILES['image']['name'] == ''){
         $_SESSION['error_category'] = "Erreur lors de l'upload de l'image Category.";
            header("location:view_category.php");
    }
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
              $_SESSION['error_category'] = "Erreur lors de l'upload de l'image Category.";
            header("location:view_category.php");
            exit;
        }

        $targetDir = "uploads_category/";

        // Créer le dossier s'il n'existe pas
        if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true)) {
              $_SESSION['error_category'] =  "Impossible de créer le dossier d'upload.";
            header("location:view_category.php");
            exit;
        }

        $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif' , 'avif'];
        $maxSize = 70 * 1024 * 1024; // 10 Mo

        // Validation du fichier
        if ($_FILES['image']['size'] > $maxSize) {
              $_SESSION['error_category'] = "L'image est trop grande (max 10 Mo)";
              header("location:view_category.php?size=lot");
            exit;
        }

        if (!in_array($fileExt, $allowedTypes)) {
              $_SESSION['error_category'] =  "Type d'image invalide. Seules les images JPG, JPEG, PNG et GIF sont autorisées.";
            header("location:view_category.php");
            exit;
        }

        // Vérifier que c'est une vraie image
        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check === false) {
              $_SESSION['error_category'] =  "Le fichier n'est pas une image valide.";
            header("location:view_category.php");
            exit;
        }

        // Générer un nom de fichier unique
        $fileName = uniqid('category_', true) . '.' . $fileExt;
        $targetFile = $targetDir . $fileName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
              $_SESSION['error_category'] = "Erreur lors de l'enregistrement de l'image.";
            header("location:view_category.php");
            exit;
        }
    
  
// end isset file name
    }



    // Insertion dans la BDD
    $stmt = $conn->prepare("INSERT INTO category(name,description,image) VALUES(?,?,?)");
    if ($stmt === false) {
        $_SESSION['error_category'] = "Erreur préparation de la requête : " . $conn->error;
        header("location:view_category.php");
        exit;
    }

    $stmt->bind_param("sss", $nom, $description , $fileName);

    if ($stmt->execute()) {
        $_SESSION['success_category'] = "Nouvel administrateur ajouté avec succès";
    } else {
        $_SESSION['error_category'] = "Erreur lors de l'ajout de l'administrateur : " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    // Redirection
    header("Location:view_category.php?Insert=true");
    exit;
}
