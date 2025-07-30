<?php

session_start();
require_once '../include/config.php';

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

   $_SESSION['insert_product'] = ''; // Réinitialiser l'erreur

    // Valider et nettoyer les entrées
    $nom = strip_tags(trim($_POST['productName'] ?? ''));
    $stock = filter_input(INPUT_POST ,  'productQuantite' , FILTER_SANITIZE_NUMBER_INT);
    $categoryId = filter_input(INPUT_POST , 'productCategory' , FILTER_SANITIZE_NUMBER_INT);
    
    // Validation des champs
    if (empty($nom)){
        $_SESSION['insert_product'] = 'Le nom est requis';
        header("location:view_product.php");
        exit;
    };

    if (empty($stock)){
        $_SESSION['insert_product'] = 'La quantite est requis';
        header("location:view_product.php");
        exit;
    };

    
    // Vérifier si l'admin existe déjà
    $stmt = $conn1->prepare("SELECT id_produit FROM produit WHERE nom_produit = ?");
    $stmt->bind_param('s', $nom);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows > 0) {
        $_SESSION['insert_product'] = "Le produit  existe déjà";
        header("Location:view_product.php");
        exit;
    }
    
    // Traitement de l'image
$fileName = null;

if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {

    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['insert_product']= "Erreur lors de l'upload de l'image.";
        header("location:view_product.php");
        exit;
    }

           $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];

            $uploadDir = 'image/image_produit/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $newFileName = 'produit_'.uniqid() . '.webp';
            $destination = $uploadDir . $newFileName;

            // 🌟 Détecter le type MIME pour supporter toutes les extensions
            $mimeType = mime_content_type($fileTmpPath);

                
                // Nom du fichier uploadé
                $fichier = $_FILES['image']['name']; 

                // Extraire l'extension en minuscule
                $extension = strtolower(pathinfo($fichier, PATHINFO_EXTENSION));

                // Liste des extensions compatibles avec conversion WebP
                $extensions_convertibles = ['jpg', 'jpeg', 'png', 'bmp', 'gif'];

                if (!in_array($extension, $extensions_convertibles)) {
                    $_SESSION['insert_product'] = 'invalid type Image';
                     header("location: view_product.php");
                     exit;
                    // Exemple de suite : move_uploaded_file() + conversion
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
                // default:
                //     die("❌ Ce format n’est pas supporté : $mimeType");
            }

            

            if ($sourceImage !== false) {
                if (imagewebp($sourceImage, $destination, 80)) {
                    imagedestroy($sourceImage);
                    echo "✅ L’image a été convertie en WebP : $destination";
                } else {
                             $_SESSION['insert_admin'] = "❌ Erreur lors de la conversion en WebP.";
                }
            } else {
                      $_SESSION['insert_admin'] =  "❌ Impossible d’ouvrir l’image.";
            }
        }
        // end webp image 

        if($fileName === null){
            $_SESSION['insert_product'] = 'Image est requis';
            header("location:view_product.php");
            exit;
        }

// Insertion dans la BDD
$stmt = $conn1->prepare("INSERT INTO produit (nom_produit, id_category,stock, image) VALUES (?, ?,?, ?)");
if ($stmt === false) {
    $_SESSION['insert_product'] = "Erreur préparation de la requête : " . $conn->error;
    header("location:view_product.php");
    exit;
}

$stmt->bind_param("siss", $nom,$categoryId,$stock , $newFileName);

if ($stmt->execute()) {
    $_SESSION['insert_product'] = "Nouvel produit ajouté avec succès";

} else {
   $_SESSION['insert_product'] = "Erreur lors de l'ajout de produit : " . $stmt->error;
}

$stmt->close();
$conn1->close();

    // Redirection
    header("Location:view_product.php");
    exit;
}