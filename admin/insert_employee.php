<?php
// fonction php mailler
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

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
    $nationalite = mysqli_real_escape_string($conn, strip_tags(trim($_POST['nationalite'])));
    $genre = mysqli_real_escape_string($conn, strip_tags(trim($_POST['genre'])));
    $age = filter_input(INPUT_POST, 'age', FILTER_SANITIZE_NUMBER_INT);
    $phone = mysqli_real_escape_string($conn, strip_tags(trim($_POST['phone'])));
    $ville = filter_input(INPUT_POST, 'ville', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $post = trim($_POST['post1']);
    
    // Validation des champs
    if (empty($nom)) { header("location:view_employee.php?error=Le nom est requis"); exit; };
    if (empty($prenom)) { header("location:view_employee.php?error=Le prénom est requis"); exit; };
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { header("location:view_employee.php?error=Email invalide"); exit; };
    if ($_FILES['image']['name'] == null ) { header("location:view_employee.php?error=image Error"); exit; };
    if (!preg_match("/^[0-9]{10}$/", $phone)) { header("location:view_employee.php?error=Invalid_phone_number"); exit; };
    
    // Vérifier si l'employee existe déjà
    $stmt = $conn->prepare("SELECT id FROM employees WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows > 0) {
        $_SESSION['error_exist'] = "Un employee avec cet email existe déjà";
        header("Location:view_employee.php?email_exist");
        exit;
    }
    
    // Traitement de l'image
    $fileName = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = "Erreur lors de l'upload de l'image.";
            header("location:view_employee.php");
            exit;
        }

        $targetDir = "uploads_employees/";

        // Créer le dossier s'il n'existe pas
        if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true)) {
            $_SESSION['error'] = "Impossible de créer le dossier d'upload.";
            header("location:view_employee.php");
            exit;
        }

        $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $maxSize = 70 * 1024 * 1024; // 10 Mo

        // Validation du fichier
        if ($_FILES['image']['size'] > $maxSize) {
            $_SESSION['error'] = "L'image est trop grande (max 10 Mo)";
            header("location:view_employee.php?size=lot");
            exit;
        }

        if (!in_array($fileExt, $allowedTypes)) {
            $_SESSION['error'] = "Type d'image invalide. Seules les images JPG, JPEG, PNG et GIF sont autorisées.";
            header("location:view_employee.php");
            exit;
        }

        // Vérifier que c'est une vraie image
        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check === false) {
            $_SESSION['error'] = "Le fichier n'est pas une image valide.";
            header("location:view_employee.php");
            exit;
        }

        // Générer un nom de fichier unique
        $fileName = uniqid('employee_', true) . '.' . $fileExt;
        $targetFile = $targetDir . $fileName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $_SESSION['error'] = "Erreur lors de l'enregistrement de l'image.";
            header("location:view_employee.php");
            exit;
        }
    }

    // start send email
    function generateVerificationCode($length = 8) {
        return bin2hex(random_bytes($length / 2));
    }

    $passwordGenerateForEmployee = generateVerificationCode(8);
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'zouhairekjjsh@gmail.com';
        $mail->Password   = 'dnfqneln rnulneb ltsnunj pnflnzntnl';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('zouhairelhilaly00@gmail.com', 'Conseil povincial Youssofiy');
        $mail->addAddress($email); // Utiliser l'email de l'employé

        $mail->isHTML(true);
        $mail->Subject = 'Votre mote de passe de vérification';
        $mail->Body    = "<h1>Bonjour, $prenom $nom</h1><p>Votre code de mon application est : <b>$passwordGenerateForEmployee</b></p>";
        $mail->AltBody = "Bonjour $prenom $nom,\nVotre code est : $passwordGenerateForEmployee";

        $mail->send();
        $_SESSION['email_send_avec_success'] = true;
    } catch (Exception $e) {
        $_SESSION['email_send_avec_success'] = '';
    }

    // Hash du mot de passe
    $passwordHash = password_hash($passwordGenerateForEmployee, PASSWORD_BCRYPT);

    // Insertion dans la BDD
    $stmt = $conn->prepare("INSERT INTO employees (nom, prenom, email, genre, nationalite, ville, age, post, telephone, mot_de_passe, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        $_SESSION['error'] = "Erreur préparation de la requête : " . $conn->error;
        header("location:view_employee.php");
        exit;
    }

    $stmt->bind_param("ssssssissss", $nom, $prenom, $email, $genre, $nationalite, $ville, $age, $post, $phone, $passwordHash, $fileName);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Nouvel administrateur ajouté avec succès";
    } else {
        $_SESSION['error'] = "Erreur lors de l'ajout de l'administrateur : " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    // Redirection
    header("Location:view_employee.php?affiche=true");
    exit;
}
