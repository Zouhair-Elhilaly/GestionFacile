<?php
// fonction php mailler
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

session_start();
// require_once '../include/conn_db.php';

include "../include/config.php";




// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Initialiser les variables et messages d'erreur
    $_SESSION['insert_admin'] = '';

    // Valider et nettoyer les entrées
    $nom = strip_tags(trim($_POST['nom'] ?? ''));
    $prenom = strip_tags(trim($_POST['prenom'] ?? ''));
    $email = strip_tags(trim($_POST['email'] ?? ''));
    $phone = trim($_POST['phone'] ?? '');
    $password = '';
    
    // Validation des champs
    if (preg_match('/^[a-zA-ZÀ-ÿ\-\' ]+ $/', $nom)){
          $_SESSION['insert_admin'] = [
            'type' => 'error',
            'msg' => 'Le nom est requis'
         ];
        header("location:view_admin.php");
        exit;
    };

    if (!preg_match('/^[a-zA-ZÀ-ÿ\-\' ]+$/u', $prenom)){
         $_SESSION['insert_admin'] = [
            'type' => 'error',
            'msg' => 'Le prénom est requis'
         ];
        header("location:view_admin.php");
        exit;
    };

    if (!preg_match("/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/" , $email)){
          $_SESSION['insert_admin'] = [
            'type' => 'error',
            'msg' => 'Adresse e-mail invalide.'
         ];
          header("location:view_admin.php");
        exit;
    };

    if (!preg_match('/^(06|07|05)[0-9]{8}$/', $phone)){
         $_SESSION['insert_admin'] = [
            'type' => 'error',
            'msg' => 'invalid numero de telephone'
         ];
        header("location:view_admin.php");
        exit;
    };


    
   

    // if (strlen($password) < 8) {header("location:view_employee.php?error=Le mot de passe doit contenir au moins 8 caractères");exit;};
    if ($_FILES['image']['name'] == null ){
        $_SESSION['insert_admin'] = [
            'type' => 'error',
            'msg' => 'erreur dans image'
         ];
        header("location:view_admin.php");
        exit;
    };

    
    // Vérifier si l'admin existe déjà
    $stmt = $conn1->prepare("SELECT id_admin FROM admin WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows > 0) {
          $_SESSION['insert_admin'] = [
            'type' => 'error',
            'msg' => 'Un administrateur avec cette adresse e-mail existe déjà.'
         ];
        header("Location:view_admin.php");
        exit;
    }
    
    // Traitement de l'image
$fileName = null;

if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {

    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
          $_SESSION['insert_admin'] = [
            'type' => 'error',
            'msg' => 'Erreur lors de l\'upload de l\'image'
         ];
        header("location:view_admin.php");
        exit;
    }
    // start webp image 

            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $newFileName = 'admin_'.uniqid() . '.webp';
            $destination = $uploadDir . $newFileName;

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
                             $_SESSION['insert_admin'] = [
                                'type' => 'error',
                                'msg' => 'Erreur lors de la conversion en WebP.'
                            ];
                }
            } else {
                       $_SESSION['insert_admin'] = [
                                'type' => 'error',
                                'msg' => 'Impossible d’ouvrir l’image'
                            ];
            }
        }
        // end webp image 



    if (!move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
         $_SESSION['insert_admin'] = [
                'type' => 'error',
                 'msg' => 'Erreur lors de l\'enregistrement de l\'image.'
           ];
        header("location:view_admin.php");
        exit;
    }


// Hash du mot de passe
$passwordHash = password_hash($password, PASSWORD_BCRYPT);

// Insertion dans la BDD
$stmt = $conn1->prepare("INSERT INTO admin (nom, prenom, email, telephone, mot_de_passe, image) VALUES (?, ?, ?, ?, ?, ?)");
if ($stmt === false) {
     $_SESSION['insert_admin'] = [
                'type' => 'error',
                 'msg' => 'Erreur préparation de la requête'
           ];
    header("location:view_admin.php");
    exit;
}

$stmt->bind_param("ssssss", $nom, $prenom, $email, $phone, $passwordHash, $newFileName);

if ($stmt->execute()) {
    $_SESSION['insert_admin'] = [
                'type' => 'success',
                 'msg' => 'Nouvel administrateur ajouté avec succès'
           ];

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
        $mail->Username   = 'zouhairelhilaly00@gmail.com';
        $mail->Password   = 'dfqe rueb tsuj pfzt';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('zouhairelhilaly00@gmail.com', 'Conseil povincial Youssofiy');
        $mail->addAddress($email); // Utiliser l'email de l'employé

        $mail->isHTML(true);
        $mail->Subject = 'Votre mote de passe de vérification';
        $mail->Body    = "<h1>Bonjour, $prenom $nom</h1><p>Votre code de mon application est : <b>$passwordGenerateForEmployee</b></p>";
        $mail->AltBody = "Bonjour $prenom $nom,\nVotre code est : $passwordGenerateForEmployee";

        $mail->send();
        $_SESSION['email_send_avec_success_a_admin'] = true;
    } catch (Exception $e) {
        $_SESSION['email_send_avec_successa_admin'] = '';
    }


// end email


} else {
    $_SESSION['insert_admin'] = [
        'type' => 'error',
         'msg' => 'Erreur lors de l\'ajout de l\'administrateur'
    ];
}

$stmt->close();
$conn1->close();
}
    // Redirection
    header("Location:view_admin.php");
    exit;

