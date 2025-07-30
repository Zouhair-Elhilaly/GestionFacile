<?php
// fonction php mailler
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

session_start();
// require_once '../include/conn_db.php';
require_once '../include/config.php';

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Initialiser les variables et messages d'erreur
    $_SESSION['insert_employee'] = '';

    // Valider et nettoyer les entrées
    $nom = strip_tags(trim($_POST['nom'] ?? ''));
    $prenom = strip_tags(trim($_POST['prenom'] ?? ''));
    $email = strip_tags(trim($_POST['email'] ?? ''));
    // $phone = trim(string: $_POST['phone'] ?? '');
    $nationalite = mysqli_real_escape_string($conn1, strip_tags(trim($_POST['nationalite'])));
    $genre = mysqli_real_escape_string($conn1, strip_tags(trim($_POST['genre'])));
    // $age = filter_input(INPUT_POST, /: 'age', FILTER_SANITIZE_NUMBER_INT);
    $phone = mysqli_real_escape_string($conn1, strip_tags(trim($_POST['phone'])));
    $ville = filter_input(INPUT_POST, 'ville', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $post =  mysqli_real_escape_string($conn1, strip_tags(trim($_POST['post'])));
    $CN =  mysqli_real_escape_string($conn1, strip_tags(trim($_POST['CN'])));
    // Validation des champs
    if (empty($nom)) { 
        $_SESSION['insert_employee'] = "Le nom est requis";
      header("location:view_employee.php");
       exit; 
    };

    if (empty($prenom)) { 
        $_SESSION['insert_employee']  = "Le prénom est requis";
        header("location:view_employee.php"); 
        exit; };

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { header("location:view_employee.php?error=Email invalide"); exit; };
    if ($_FILES['image']['name'] == null ) {
        $_SESSION['insert_employee'] = "Image Erreur";
         header("location:view_employee.php");
          exit; };
    if (!preg_match("/^(06|07|05)[0-9]{8}$/", $phone)) { header("location:view_employee.php?error=Invalid_phone_number"); exit; };
    
    // Vérifier si l'employee existe déjà
    $stmt = $conn1->prepare("SELECT id FROM employé WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows > 0) {
        $_SESSION['insert_employee'] = "Un employé avec cet email existe déjà";
        header("Location:view_employee.php?email_exist");
        exit;
    }
    
    // Traitement de l'image
    $fileName = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['insert_employee'] = "Erreur lors de l'upload de l'image.";
            header("location:view_employee.php");
            exit;
        }

        $targetDir = "uploads_employees/";


         // Vérifier que c'est une vraie image
        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check === false) {
           $_SESSION['insert_employee'] = "Le fichier n'est pas une image valide.";
            header("location:view_employee.php");
            exit;
        }


        // Créer le dossier s'il n'existe pas
        if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true)) {
            $_SESSION['insert_employee'] = "Impossible de créer le dossier d'upload.";
            header("location:view_employee.php");
            exit;
        }
        // Générer un nom de fichier unique
        $fileName = uniqid('employee_', true) . '.webp';
        $targetFile = $targetDir . $fileName;

        $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        // $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        // $maxSize = 70 * 1024 * 1024; // 10 Mo

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
                    die("❌ Ce format n’est pas supporté : $mimeType");
            }

            if ($sourceImage !== false) {
                if (imagewebp($sourceImage, $targetFile, 80)) {
                    imagedestroy($sourceImage);
                    echo "✅ L’image a été convertie en WebP : $targetFile";
                } else {
                             $_SESSION['insert_employee'] = "❌ Erreur lors de la conversion en WebP.";
                }
            } else {
                      $_SESSION['insert_employee'] =  "❌ Impossible d’ouvrir l’image.";
            }
        }
        // end webp image 

        
       

        

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
             $_SESSION['insert_employee'] = "Erreur lors de l'enregistrement de l'image.";
            header("location:view_employee.php");
            exit;
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
        $_SESSION['email_send_avec_success'] = true;
    } catch (Exception $e) {
        $_SESSION['email_send_avec_success'] = '';
        header("location:view_employee.php");
        exit;
    }

    // Hash du mot de passe
    $passwordHash = password_hash($passwordGenerateForEmployee, PASSWORD_BCRYPT);

    // Insertion dans la BDD
    $stmt = $conn1->prepare("INSERT INTO employé (nom, prenom, email, genre, nationalite, ville, CNIE, id_post, Telephone, mot_de_passe_em, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
         $_SESSION['insert_employee'] = "Erreur préparation de la requête : " . $conn->error;
        header("location:view_employee.php");
        exit;
    }

    $stmt->bind_param("sssssssisss", $nom,$prenom, $email, $genre, $nationalite, $ville, $CN, $post, $phone, $passwordHash, $fileName);

    if ($stmt->execute()) {
         $_SESSION['insert_employee'] = "Nouvel administrateur ajouté avec succès";
    } else {
         $_SESSION['insert_employee'] = "Erreur lors de l'ajout de l'administrateur : " . $stmt->error;
    }

    $stmt->close();
    $conn1->close();
}
    // Redirection
    header("Location:view_employee.php?affiche=true");
    exit;

