<?php

session_start();
// require_once '../include/conn_db.php';

require_once '../include/config.php';

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Initialiser les variables et messages d'erreur
    $_SESSION['insert_category'] = '';

    // Valider et nettoyer les entrées
    $nom = strip_tags(trim($_POST['category_name'] ?? ''));
    $description = strip_tags(trim($_POST['description'] ?? ''));

   
    
    // Validation des champs
    if (empty($nom)) { 
        $_SESSION['insert_category'] = 'invalid nom category';
        header("location:view_category.php");
         exit; 
        };
    if (empty($description)) {
         $_SESSION['insert_category'] = 'invalid description category';
         header("location:view_category.php");
          exit;
      };
    
    
    // Vérifier si l'employee email  existe déjà et n'est pas modifier 
    $stmt = $conn1->prepare("SELECT id_category FROM category WHERE nom_category = ?");
    $stmt->bind_param('s', $nom);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows > 0) {
        $_SESSION['insert_category'] = 'category exist deja';
         header("location:view_category.php");
         exit;
    }
    

    // Traitement de l'image
    $fileName = null;
    if($_FILES['image']['name'] == ''){
         $_SESSION['insert_category'] = "Erreur lors de l'upload de l'image Category.";
            header("location:view_category.php");
            exit;
    }
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
              $_SESSION['insert_category'] = "Erreur lors de l'upload de l'image Category.";
            header("location:view_category.php");
            exit;
        }

        $targetDir = "image/image_category/";

        // Créer le dossier s'il n'existe pas
        if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true)) {
              $_SESSION['insert_category'] =  "Impossible de créer le dossier d'upload.";
            header("location:view_category.php");
            exit;
        }

        

        


        // Vérifier que c'est une vraie image
        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check === false) {
              $_SESSION['insert_category']  =  "Le fichier n'est pas une image valide.";
            header("location:view_category.php");
            exit;
        }

        // Générer un nom de fichier unique
        $fileName = uniqid('category_', true) . '.webp';
        $destination = $targetDir . $fileName;
        $fileTmpPath = $_FILES['image']['tmp_name'];

           

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
                             $_SESSION['insert_category']  = "❌ Erreur lors de la conversion en WebP.";
                }
            } else {
                             $_SESSION['insert_category']  =  "❌ Impossible d’ouvrir l’image.";
            }
        }
        // end webp image 

        // if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetDir)) {
        //       $_SESSION['insert_category']  = "Erreur lors de l'enregistrement de l'image.";
        //     header("location:view_category.php");
        //     exit;
        // }
    
  
// end isset file name
    



    // Insertion dans la BDD
    $stmt = $conn1->prepare("INSERT INTO category(nom_category,description,image) VALUES(?,?,?)");
    if ($stmt === false) {
        $_SESSION['insert_category']  = "Erreur préparation de la requête : " . $conn->error;
        header("location:view_category.php");
        exit;
    }

    $stmt->bind_param("sss", $nom, $description , $fileName);

    if ($stmt->execute()) {
        $_SESSION['insert_category']  = "Nouvel category ajouté avec succès";
    } else {
        $_SESSION['insert_category'] = "Erreur lors de l'ajout de category : " . $stmt->error;
    }

    $stmt->close();
    $conn1->close();

    // Redirection
    header("Location:view_category.php?Insert=true");
    exit;
}
