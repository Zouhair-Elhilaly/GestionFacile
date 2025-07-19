<?php

session_start();
require_once '../include/conn_db.php';

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Initialiser les variables et messages d'erreur
    $errors = [];
    $success = false;

    // Valider et nettoyer les entrées
    $nom = strip_tags(trim($_POST['productName'] ?? ''));
    $quantite = filter_input(INPUT_POST ,  'productQuantite' , FILTER_SANITIZE_NUMBER_INT);
    $categoryName = filter_input(INPUT_POST , 'productCategory' , FILTER_SANITIZE_NUMBER_INT);
    
    // Validation des champs
    if (empty($nom)){header("location:view_product.php?error=Le nom est requis");exit;};
    if (empty($quantite)){header("location:view_product.php?error=La quantite est requis");exit;};

    
    // Vérifier si l'admin existe déjà
    $stmt = $conn->prepare("SELECT id FROM produits WHERE nom = ?");
    $stmt->bind_param('s', $nom);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows > 0) {
        $_SESSION['error_exist'] = "Le produit  existe déjà";
        header("Location:view_category.php");
        exit;
    }
    
    // Traitement de l'image
$fileName = null;

if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {

    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = "Erreur lors de l'upload de l'image.";
        header("location:view_product.php");
        exit;
    }

    $targetDir = "uploads_produits/";

    // Créer le dossier s'il n'existe pas
    if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true)) {
        $_SESSION['error'] = "Impossible de créer le dossier d'upload.";
        header("location:view_product.php");
        exit;
    }

    $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif' , 'avif'];
    $maxSize = 30 * 1024 * 1024; // 10 Mo

    // Validation du fichier
    if ($_FILES['image']['size'] > $maxSize) {
        $_SESSION['error'] = "L'image est trop grande (max 10 Mo)";
        header("location:view_product.php?size");
        exit;
    }

    if (!in_array($fileExt, $allowedTypes)) {
        $_SESSION['error'] = "Type d'image invalide. Seules les images JPG, JPEG, PNG et GIF sont autorisées.";
        header("location:view_product.php");
        exit;
    }

    // Générer un nom de fichier unique
    $fileName = uniqid('produit_', true) . '.' . $fileExt;
    $targetFile = $targetDir . $fileName;

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        $_SESSION['error'] = "Erreur lors de l'enregistrement de l'image.";
        header("location:view_product.php");
        exit;
    }
}


// Insertion dans la BDD
$stmt = $conn->prepare("INSERT INTO produits (nom, category_id, image) VALUES (?, ?, ?)");
if ($stmt === false) {
    $_SESSION['error'] = "Erreur préparation de la requête : " . $conn->error;
    header("location:view_product.php");
    exit;
}

$stmt->bind_param("sis", $nom,$categoryName , $fileName);

if ($stmt->execute()) {
    $_SESSION['success'] = "Nouvel produit ajouté avec succès";

    $readId = $conn->prepare("SELECT id FROM produits WHERE nom = ?");
    $readId->bind_param('s',$nom);
    $readId->execute();
    $res = $readId->get_result();

    $row = $res->fetch_assoc();



    $stmt = $conn->prepare("INSERT INTO quantite_produits (id_produit ,  quantite) VALUES (?, ?)");
    $stmt->bind_param('ii',$row['id'] , $quantite);
    if($stmt->execute()){
        header("location:view_product.php?execute=success");
    }
 
} else {
    $_SESSION['error'] = "Erreur lors de l'ajout de roduit : " . $stmt->error;
}

$stmt->close();
$conn->close();

    // Redirection
    header("Location:view_product.php");
    exit;
}