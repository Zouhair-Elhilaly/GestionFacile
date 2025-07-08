<?php

session_start();
require_once '../../include/conn_db.php';

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
    if (empty($nom)){header("location:../view_admin.php?error=Le nom est requis");exit;};
    if (empty($prenom)){header("location:../view_admin.php?error=Le prénom est requis");exit;};
    if (!preg_match("/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/" , $email)){header("location:../view_admin.php?error=Email invalide");exit;};
    if (strlen($password) < 8) {header("location:../view_admin.php?error=Le mot de passe doit contenir au moins 8 caractères");exit;};
    
    // Vérifier si l'admin existe déjà
    $stmt = $conn->prepare("SELECT id FROM admin WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows > 0) {
        $_SESSION['error'] = "Un administrateur avec cet email existe déjà";
        header("Location: ../view_admin.php");
        exit;
    }
    
    // Traitement de l'image
    $fileName = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        
        // Créer le dossier s'il n'existe pas
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        
        $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $maxSize = 10 * 1024 * 1024; // 5 Mo
        
        // Validation du fichier
        if ($_FILES['image']['size'] > $maxSize) {
            header("location: L'image est trop grande (max 10 Mo)") ;
            exit;
        }
        
        if (!in_array($fileExt, $allowedTypes)) {
            header("location: Seules les images JPG, JPEG, PNG et GIF sont autorisées") ;
            exit;
        }
        
        if (empty($errors)) {
            // Générer un nom de fichier unique
            $fileName = uniqid('admin_', true) . '.' . $fileExt;
            $targetFile = $targetDir . $fileName;

            
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                header("location: Erreur lors de l'enregistrement de l'image") ;
            exit; 
            }
        }
    }
    
    // Si aucune erreur, procéder à l'insertion
    if (empty($errors)) {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        
        $stmt = $conn->prepare("INSERT INTO admin (nom, prenom, email, telephone, mot_de_passe, image) 
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $nom, $prenom, $email, $phone, $passwordHash, $fileName);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Nouvel administrateur ajouté avec succès";
            $success = true;
        } else {
            $_SESSION['error'] = "Erreur lors de l'ajout de l'administrateur: " . $conn->error;
        }
        
        $stmt->close();
    } else {
        $_SESSION['errors'] = $errors;
    }
    
    $conn->close();
    
    // Redirection
    header("Location: ../view_admin.php");
    exit;
}