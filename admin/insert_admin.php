<?php

session_start();
require_once '../include/conn_db.php';

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Initialiser les variables et messages d'erreur
    $errors = [];
    $success = false;

    // Valider et nettoyer les entrées
    $nom = strip_tags(trim($_POST['nom'] ?? ''));
    $prenom = strip_tags(trim($_POST['prenom'] ?? ''));
    $email = strip_tags(trim($_POST['email'] ?? ''));
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validation des champs
    if (empty($nom)){header("location:view_admin.php?error=Le nom est requis");exit;};
    if (empty($prenom)){header("location:view_admin.php?error=Le prénom est requis");exit;};
    if (!preg_match("/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/" , $email)){header("location:view_admin.php?error=Email invalide");exit;};
    if (strlen($password) < 8) {header("location:view_employee.php?error=Le mot de passe doit contenir au moins 8 caractères");exit;};
    if ($_FILES['image']['name'] == null ){header("location:view_admin.php?error=image Error");exit;};

    
    // Vérifier si l'admin existe déjà
    $stmt = $conn->prepare("SELECT id FROM admin WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows > 0) {
        $_SESSION['error_exist'] = "Un administrateur avec cet email existe déjà";
        header("Location:view_admin.php");
        exit;
    }
    
    // Traitement de l'image
$fileName = null;

if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {

    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = "Erreur lors de l'upload de l'image.";
        header("location:view_admin.php");
        exit;
    }

    $targetDir = "uploads/";

    // Créer le dossier s'il n'existe pas
    if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true)) {
        $_SESSION['error'] = "Impossible de créer le dossier d'upload.";
        header("location:view_admin.php");
        exit;
    }

    $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    $maxSize = 30 * 1024 * 1024; // 10 Mo

    // Validation du fichier
    if ($_FILES['image']['size'] > $maxSize) {
        $_SESSION['error'] = "L'image est trop grande (max 10 Mo)";
        header("location:view_admin.php?size");
        exit;
    }

    if (!in_array($fileExt, $allowedTypes)) {
        $_SESSION['error'] = "Type d'image invalide. Seules les images JPG, JPEG, PNG et GIF sont autorisées.";
        header("location:view_admin.php");
        exit;
    }

    // Générer un nom de fichier unique
    $fileName = uniqid('admin_', true) . '.' . $fileExt;
    $targetFile = $targetDir . $fileName;

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        $_SESSION['error'] = "Erreur lors de l'enregistrement de l'image.";
        header("location:view_admin.php");
        exit;
    }
}

// Hash du mot de passe
$passwordHash = password_hash($password, PASSWORD_BCRYPT);

// Insertion dans la BDD
$stmt = $conn->prepare("INSERT INTO admin (nom, prenom, email, telephone, mot_de_passe, image) VALUES (?, ?, ?, ?, ?, ?)");
if ($stmt === false) {
    $_SESSION['error'] = "Erreur préparation de la requête : " . $conn->error;
    header("location:view_admin.php");
    exit;
}

$stmt->bind_param("ssssss", $nom, $prenom, $email, $phone, $passwordHash, $fileName);

if ($stmt->execute()) {
    $_SESSION['success'] = "Nouvel administrateur ajouté avec succès";
} else {
    $_SESSION['error'] = "Erreur lors de l'ajout de l'administrateur : " . $stmt->error;
}

$stmt->close();
$conn->close();

    // Redirection
    header("Location:view_admin.php");
    exit;
}