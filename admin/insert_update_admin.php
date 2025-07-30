<?php

// fonction php mailler
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';


session_start();
require_once '../include/conn_db.php';

require_once '../include/config.php';

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Initialiser les variables et messages d'erreur
   $_SESSION['insert_update_admin'] = [];
    // Valider et nettoyer les entrées
   
    // Valider et nettoyer les entrées
   $nom =  strip_tags(trim($_POST['nom']));
   $prenom = strip_tags(trim($_POST['prenom'] ?? ''));
   $email = strip_tags(trim($_POST['email'] ?? ''));
   $phone = trim($_POST['phone'] ?? '');
    $id = filter_input(INPUT_POST , 'id_num' , FILTER_SANITIZE_NUMBER_INT);
    
    // Validation des champs
    // Validation des champs
    if (!preg_match('/^[a-zA-ZÀ-ÿ\-\']+$/', $nom)){
         $_SESSION['insert_update_admin'] = [
            'type' => 'error',
            'msg' => 'Le nom est requis'
         ];

         header("location:view_admin.php");
         exit;
    };

    if (!preg_match('/^[a-zA-ZÀ-ÿ\-\' ]+$/', $prenom)){
          $_SESSION['insert_update_admin'] = [
            'type' => 'error',
            'msg' => 'Le prénom est requis'
         ];
        header("location:view_admin.php");
        exit;
    };

    if (!preg_match("/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/" , $email)){
          $_SESSION['insert_update_admin'] = [
            'type' => 'error',
            'msg' => 'Email invalide'
         ];
          header("location:view_admin.php");
        exit;
    };

     if (!preg_match('/^(06|07|05)+[0-9]{8}$/', $phone)){

          $_SESSION['insert_update_admin'] = [
            'type' => 'error',
            'msg' => 'invalid numero de telephone'
         ];
        header("location:view_admin.php");
        exit;
    };

    // Vérifier si l'admin email  existe déjà et n'est pas modifier 
    $stmt = $conn1->prepare("SELECT id_admin FROM admin WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows > 0) {
        $email_exist = true;
    }else{
        $sql = $conn1->prepare("UPDATE admin SET email = ? WHERE id_admin = ?");
        $sql->bind_param('si', $email,$id);
        $sql->execute();
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


    }
    

    // Traitement de l'image
    $fileName = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['insert_update_admin'] = [
            'type' => 'error',
            'msg' => 'Erreur lors de l\'upload de l\'image.'
         ];
            header("location:view_admin.php");
            exit;
        }

        $targetDir = "uploads/";

        // Créer le dossier s'il n'existe pas
        if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true)) {
            $_SESSION['insert_update_admin'] = [
            'type' => 'error',
            'msg' => 'Impossible de créer le dossier d\'upload.'
         ];
            header("location:view_admin.php");
            exit;
        }

        $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
         $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        
        // Générer un nom de fichier unique
        $fileName = uniqid('admin_', true) . '.webp';
        $targetFile = $targetDir . $fileName;

            $sourceImage = false;
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
                
            }


          if ($sourceImage !== false) {
                if (imagewebp($sourceImage, $targetFile, 80)) {
                    imagedestroy($sourceImage);
                } else {
                   $_SESSION['insert_update_admin'] = [
                          'type' => 'error',
                                'msg' => ' Erreur lors de la conversion en WebP. '
                            ];
                }
            }else{
                
                $_SESSION['insert_update_admin'] = [
                                'type' => 'error',
                                'msg' => 'Impossible d’ouvrir l’image.'
                            ];
           header("location:view_admin.php");
            exit;
            }
        
        // end webp image 


        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $_SESSION['insert_update_admin'] = [
                                'type' => 'error',
                                'msg' => 'Erreur lors de l\'enregistrement de l\'image.'
                            ];
            header("location:view_admin.php");
            exit;
        }
    
  
// end isset file name
    }else{
    $fileName = trim($_POST['image_name']);

}



    // Insertion dans la BDD
    $stmt = $conn1->prepare("UPDATE  admin SET nom = ? , prenom = ?, telephone = ?, image = ? WHERE id_admin = ?");
    if ($stmt === false) {
         $_SESSION['insert_update_admin'] = [
                'type' => 'error',
                  'msg' => 'Erreur préparation de la requête'
         ];
        header("location:view_admin.php");
        exit;
    }

    $stmt->bind_param("ssssi", $nom, $prenom,  $phone ,$fileName, $id);

    if ($stmt->execute()) {
         $_SESSION['insert_update_admin'] = [
                'type' => 'success',
                  'msg' => 'Administrateur modifier avec succès'
         ];
    } else {
        $_SESSION['insert_update_admin'] = [
                'type' => 'error',
                  'msg' => 'Erreur lors de l\'ajout de l\'administrateur '
         ];
    }

    $stmt->close();
    $conn1->close();
}
    // Redirection
    header("Location:view_admin.php?UpdateAdmin=true");
    exit;

