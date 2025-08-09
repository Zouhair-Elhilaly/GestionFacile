<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';
session_start();
require_once '../../include/config.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
   header("location:../error.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Initialiser la session pour message SweetAlert2
    $_SESSION['insert_employee'] = ['msg' => '', 'type' => 'info'];

    // Valider et nettoyer les entrées
    $nom = strip_tags(trim($_POST['nom'] ?? ''));
    $prenom = strip_tags(trim($_POST['prenom'] ?? ''));
    $email = strip_tags(trim($_POST['email'] ?? ''));
    $nationalite = mysqli_real_escape_string($conn1, strip_tags(trim($_POST['nationalite'])));
    $genre = mysqli_real_escape_string($conn1, strip_tags(trim($_POST['genre'])));
    $phone = mysqli_real_escape_string($conn1, strip_tags(trim($_POST['phone'])));
    $ville = filter_input(INPUT_POST, 'ville', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $post =  mysqli_real_escape_string($conn1, strip_tags(trim($_POST['post'])));
    $CN =  mysqli_real_escape_string($conn1, strip_tags(trim($_POST['CN'])));

    // Validation des champs
    if (empty($nom)) { 
        $_SESSION['insert_employee'] = ['msg' => "Le nom est requis", 'type' => 'error'];
        header("location:../view_employee.php");
        exit; 
    }

    if (empty($prenom)) { 
        $_SESSION['insert_employee']  = ['msg' => "Le prénom est requis", 'type' => 'error'];
        header("location:../view_employee.php"); 
        exit; 
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { 
        $_SESSION['insert_employee']  = ['msg' => "Email invalide", 'type' => 'error'];
        header("location:../view_employee.php"); 
        exit; 
    }

    if ($_FILES['image']['name'] == null) {
        $_SESSION['insert_employee'] = ['msg' => "Image obligatoire", 'type' => 'error'];
        header("location:../view_employee.php");
        exit; 
    }

    if (!preg_match("/^(06|07|05)[0-9]{8}$/", $phone)) { 
        $_SESSION['insert_employee']  = ['msg' => "Numéro de téléphone invalide", 'type' => 'error'];
        header("location:../view_employee.php"); 
        exit; 
    }
    
    // Vérifier si l'employee existe déjà
    $stmt = $conn1->prepare("SELECT id FROM employé WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows > 0) {
        $_SESSION['insert_employee'] = ['msg' => "Un employé avec cet email existe déjà", 'type' => 'warning'];
        header("Location:../view_employee.php");
        exit;
    }
    
    // Traitement de l'image
    $fileName = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['insert_employee'] = ['msg' => "Erreur lors de l'upload de l'image.", 'type' => 'error'];
            header("location:../view_employee.php");
            exit;
        }

        $targetDir = "../uploads_employees/";

        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check === false) {
           $_SESSION['insert_employee'] = ['msg' => "Le fichier n'est pas une image valide.", 'type' => 'error'];
            header("location:../view_employee.php");
            exit;
        }

        if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true)) {
            $_SESSION['insert_employee'] = ['msg' => "Impossible de créer le dossier d'upload.", 'type' => 'error'];
            header("location:../view_employee.php");
            exit;
        }

        $fileName = uniqid('employee_', true) . '.webp';
        $targetFile = $targetDir . $fileName;

        $fileTmpPath = $_FILES['image']['tmp_name'];
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
            default:
                $_SESSION['insert_employee'] = ['msg' => "❌ Format d'image non supporté : $mimeType", 'type' => 'error'];
                header("location:../view_employee.php");
                exit;
        }

        if ($sourceImage !== false) {
            if (imagewebp($sourceImage, $targetFile, 80)) {
                imagedestroy($sourceImage);
            } else {
                $_SESSION['insert_employee'] = ['msg' => "❌ Erreur lors de la conversion en WebP.", 'type' => 'error'];
            }
        } else {
            $_SESSION['insert_employee'] = ['msg' => "❌ Impossible d’ouvrir l’image.", 'type' => 'error'];
        }
        
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $_SESSION['insert_employee'] = ['msg' => "Erreur lors de l'enregistrement de l'image.", 'type' => 'error'];
            header("location:../view_employee.php");
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
        $mail->Username   = "{$_SESSION['phpMailer']['email']}";
        $mail->Password   = "{$_SESSION['phpMailer']['password']}";
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom("{$_SESSION['phpMailer']['email']}", 'Conseil Provincial Youssoufia');
        $mail->addAddress($email); 

        $mail->isHTML(true);
        $mail->Subject = 'Votre mot de passe de vérification';
        $mail->Body    = "<h1>Bonjour, $prenom $nom</h1><p>Votre code de connexion est : <b>$passwordGenerateForEmployee</b></p>";
        $mail->AltBody = "Bonjour $prenom $nom,\nVotre code est : $passwordGenerateForEmployee";

        $mail->send();
        $_SESSION['email_send_avec_success'] = true;
    } catch (Exception $e) {
        $_SESSION['insert_employee'] = ['msg' => "Erreur lors de l'envoi de l'email : ".$mail->ErrorInfo, 'type' => 'error'];
        header("location:../view_employee.php");
        exit;
    }

    // Hash du mot de passe
    $passwordHash = password_hash($passwordGenerateForEmployee, PASSWORD_BCRYPT);

    // Insertion dans la BDD
    $stmt = $conn1->prepare("INSERT INTO employé (nom, prenom, email, genre, nationalite, ville, CNIE, id_post, Telephone, mot_de_passe_em, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        $_SESSION['insert_employee'] = ['msg' => "Erreur préparation de la requête : " . $conn1->error, 'type' => 'error'];
        header("location:../view_employee.php");
        exit;
    }

    $stmt->bind_param("sssssssisss", $nom,$prenom, $email, $genre, $nationalite, $ville, $CN, $post, $phone, $passwordHash, $fileName);

    if ($stmt->execute()) {
        $_SESSION['insert_employee'] = ['msg' => "Nouvel employé ajouté avec succès", 'type' => 'success'];
    } else {
        $_SESSION['insert_employee'] = ['msg' => "Erreur lors de l'ajout : " . $stmt->error, 'type' => 'error'];
    }

    $stmt->close();
    $conn1->close();

    header("Location:../view_employee.php?affiche=true");
    exit;
}
?>

