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

  
    // Valider et nettoyer les entrées
    $nom = strip_tags(trim($_POST['nom'] ?? ''));
    $prenom = strip_tags(trim($_POST['prenom'] ?? ''));
    $email = strip_tags(trim($_POST['email'] ?? ''));
    $phone = trim($_POST['phone'] ?? '');
    $nationalite = mysqli_real_escape_string($conn1, strip_tags(trim($_POST['nationalite'])));
    $genre = mysqli_real_escape_string($conn1, strip_tags(trim($_POST['genre'])));
    $CN = mysqli_real_escape_string($conn1, strip_tags(trim($_POST['CNIE'])));
    $phone = mysqli_real_escape_string($conn1, strip_tags(trim($_POST['phone'])));
    $ville = filter_input(INPUT_POST, 'ville', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $post = trim($_POST['post']);
    $image_name = trim($_POST['image_name']);
    $id = filter_input(INPUT_POST , 'id_num' , FILTER_SANITIZE_NUMBER_INT);
 
    // Validation des champs
    if (empty($nom)) {
     header("location:view_employee.php?error=Le nom est requis"); exit; };
    if (empty($prenom)) {
         header("location:view_employee.php?error=Le prénom est requis"); exit; };
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { 
        header("location:view_employee.php?error=Email invalide"); exit; };
    // if ($_FILES['image']['name'] == null ) { header("location:view_employee.php?error=image Error"); exit; };
    if (!preg_match("/^(06|07|05)[0-9]{8}$/", $phone)) { 
         $_SESSION['insert_employee'] = "Invalid_phone_number";
        header("location:view_employee.php?error=Invalid_phone_number"); exit; };
    
    // Vérifier si l'employee email  existe déjà et n'est pas modifier 
    $stmt = $conn1->prepare("SELECT id FROM employé WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows > 0) {
        $email_exist = true;
    }else{
        // start update password 
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
    }

    // Hash du mot de passe
    $passwordHash = password_hash($passwordGenerateForEmployee, PASSWORD_BCRYPT);


        // end update password
        $sql = $conn1->prepare("INSERT INTO employé(email , mot_de_passe_em) VALUES(?,?)");
        $sql->bind_param('ss', $email,$passwordHash);
        $sql->execute();
        $email_exist = false;

    }
    

    // if(isset($_FILES['image']['name'])){
    // Traitement de l'image
    $fileName = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['insert_employee'] = "Erreur lors de l'upload de l'image.";
            header("location:view_employee.php");
            exit;
        }

        $targetDir = "uploads_employees/";

        // Créer le dossier s'il n'existe pas
        if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true)) {
            $_SESSION['insert_employee'] = "Impossible de créer le dossier d'upload.";
            header("location:view_employee.php");
            exit;
        }

         // Générer un nom de fichier unique
        $fileName = uniqid('employé_', true) . '.webp';
        $targetFile = $targetDir . $fileName;

        $fileTmpPath = $_FILES['image']['tmp_name'];


        // Vérifier que c'est une vraie image
        $check = getimagesize($fileTmpPath);
        if ($check === false) {
             $_SESSION['insert_employee'] = "Le fichier n'est pas une image valide.";
            header("location:view_employee.php");
            exit;
        }

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
                if (imagewebp($sourceImage, $targetFile, 80)) {
                    imagedestroy($sourceImage);
                    echo "✅ L’image a été convertie en WebP : $targetFile";
                } else {
                             $_SESSION['insert_employee'] = "❌ Erreur lors de la conversion en WebP.";
                }
            } else {
                      $_SESSION['insert_employee'] =  "❌ Impossible d’ouvrir l’image.";
            }
        
        // end webp image 


       

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $_SESSION['insert_employee'] = "Erreur lors de l'enregistrement de l'image.";
            header("location:view_employee.php");
            exit;
        }
    
  
// end isset file name
    }else{
    $fileName = trim($_POST['image_name']);

}



    // Insertion dans la BDD
    $stmt = $conn1->prepare("UPDATE  employé SET nom = ? , prenom = ?, genre = ?, nationalite = ?, ville = ?, CNIE = ?, id_post = ?, Telephone = ?, image = ? WHERE id = ?");
    if ($stmt === false) {
        $_SESSION['insert_employee'] = "Erreur préparation de la requête : " . $conn->error;
        header("location:view_employee.php");
        exit;
    }

    $stmt->bind_param("ssssssissi", $nom, $prenom,  $genre, $nationalite, $ville, $CN, $post, $phone,  $fileName, $id);

    if ($stmt->execute()) {
        $_SESSION['insert_employee']  = "administrateur modifier avec succès";
    } else {
        $_SESSION['insert_employee']  = "Erreur lors de l'ajout de l'administrateur : " . $stmt->error;
    }

    $stmt->close();
    $conn1->close();

    // Redirection
    header("Location:view_employee.php?Update=true");
    exit;
}
