<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

session_start();
require_once '../../include/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
    include("../error.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nom = strip_tags(trim($_POST['nom'] ?? ''));
    $prenom = strip_tags(trim($_POST['prenom'] ?? ''));
    $email = strip_tags(trim($_POST['email'] ?? ''));
    $phone = mysqli_real_escape_string($conn1, strip_tags(trim($_POST['phone'] ?? '')));
    $nationalite = mysqli_real_escape_string($conn1, strip_tags(trim($_POST['nationalite'] ?? '')));
    $genre = mysqli_real_escape_string($conn1, strip_tags(trim($_POST['genre'] ?? '')));
    $CN = mysqli_real_escape_string($conn1, strip_tags(trim($_POST['CNIE'] ?? '')));
    $ville = filter_input(INPUT_POST, 'ville', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $post = trim($_POST['post']);
    $image_name = trim($_POST['image_name']);
    $id = filter_input(INPUT_POST , 'id_num' , FILTER_SANITIZE_NUMBER_INT);

    // Validation de base
    if (empty($nom)) { $_SESSION['insert_employee'] = ['type'=>'error','msg'=>'Le nom est requis']; header("location:../view_employee.php"); exit; }
    if (empty($prenom)) { $_SESSION['insert_employee'] = ['type'=>'error','msg'=>'Le prénom est requis']; header("location:../view_employee.php"); exit; }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $_SESSION['insert_employee'] = ['type'=>'error','msg'=>'Email invalide']; header("location:../view_employee.php"); exit; }
    if (!preg_match("/^(06|07|05)[0-9]{8}$/", $phone)) { $_SESSION['insert_employee'] = ['type'=>'error','msg'=>'Numéro de téléphone invalide']; header("location:../view_employee.php"); exit; }

    // Récupérer l'ancien email de cet employé
    $stmt = $conn1->prepare("SELECT email FROM employé WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if($res->num_rows == 0){ header("../location:view_employee.php?error=Employé introuvable"); exit; }
    $oldData = $res->fetch_assoc();
    $oldEmail = $oldData['email'];
    $stmt->close();

    $nouveauMotDePasse = null;

    // Si l'email a changé
    if ($email !== $oldEmail) {
        // Vérifier si email existe déjà pour un autre employé
        $stmt = $conn1->prepare("SELECT id FROM employé WHERE email = ? AND id != ?");
        $stmt->bind_param('si', $email, $id);
        $stmt->execute();
        $check = $stmt->get_result();
        if($check->num_rows > 0){
            $_SESSION['insert_employee'] = ['type'=>'error','msg'=>'Cet email appartient déjà à un autre employé'];
            header("location:../view_employee.php"); exit;
        }
        $stmt->close();

        // Générer un nouveau mot de passe
        function generateVerificationCode($length = 8) {
            return bin2hex(random_bytes($length / 2));
        }
        $nouveauMotDePasse = generateVerificationCode(8);

        // Envoyer l'email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $_SESSION['phpMailer']['email'];
            $mail->Password   = $_SESSION['phpMailer']['password'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom($_SESSION['phpMailer']['email'], 'Conseil provincial Youssofia');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Votre nouveau mot de passe';
            $mail->Body    = "<h1>Bonjour, $prenom $nom</h1><p>Votre nouveau mot de passe est : <b>$nouveauMotDePasse</b></p>";
            $mail->AltBody = "Bonjour $prenom $nom,\nVotre nouveau mot de passe est : $nouveauMotDePasse";

            $mail->send();
            $_SESSION['email_send_avec_success'] = true;
        } catch (Exception $e) {
            $_SESSION['insert_employee'] = ['type'=>'error','msg'=>'Erreur lors de l\'envoi de l\'email'];
            header("../location:view_employee.php"); exit;
        }

        // Mettre à jour le mot de passe hashé
        $passwordHash = password_hash($nouveauMotDePasse, PASSWORD_BCRYPT);
        $stmt = $conn1->prepare("UPDATE employé SET mot_de_passe_em = ? WHERE id = ?");
        $stmt->bind_param('si', $passwordHash, $id);
        $stmt->execute();
        $stmt->close();
    }

    // === Gestion image ===
    $fileName = $image_name;
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['insert_employee'] = ['type'=>'error','msg'=>'Erreur upload image']; header("location:../view_employee.php"); exit;
        }
        $targetDir = "../uploads_employees/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
        $fileName = uniqid('employé_', true) . '.webp';
        $targetFile = $targetDir . $fileName;
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $check = getimagesize($fileTmpPath);
        if ($check === false) { $_SESSION['insert_employee'] = ['type'=>'error','msg'=>"Fichier n'est pas image valide"]; header("location:../view_employee.php"); exit; }
        $mimeType = mime_content_type($fileTmpPath);
        switch ($mimeType) {
            case 'image/jpeg': $sourceImage = imagecreatefromjpeg($fileTmpPath); break;
            case 'image/png': $sourceImage = imagecreatefrompng($fileTmpPath); break;
            case 'image/gif': $sourceImage = imagecreatefromgif($fileTmpPath); break;
            case 'image/webp': $sourceImage = imagecreatefromwebp($fileTmpPath); break;
            case 'image/bmp': $sourceImage = imagecreatefrombmp($fileTmpPath); break;
        }
        if ($sourceImage !== false) {
            imagewebp($sourceImage, $targetFile, 80);
            imagedestroy($sourceImage);
        }
    }

    // === UPDATE final ===
    $stmt = $conn1->prepare("UPDATE employé SET nom=?, prenom=?, genre=?, nationalite=?, ville=?, CNIE=?, id_post=?, Telephone=?, image=?, email=? WHERE id=?");
    $stmt->bind_param("ssssssisssi", $nom, $prenom, $genre, $nationalite, $ville, $CN, $post, $phone, $fileName, $email, $id);

    if ($stmt->execute()) {
        $_SESSION['insert_employee'] = ['type' => 'success','msg' => 'Employé modifié avec succès'];
    } else {
        $_SESSION['insert_employee'] = ['type' => 'error','msg' => 'Erreur lors de la modification'];
    }

    $stmt->close();
    $conn1->close();

    header("Location:../view_employee.php?Update=true");
    exit;
}
?>
