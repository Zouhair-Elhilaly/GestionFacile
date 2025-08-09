<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../../vendor/autoload.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
    include("../error.php");
    exit();
}

require_once '../../include/config.php';
require_once "../functions/chiffre.php";

$_SESSION['insert_update_admin'] = [];


$nom    = strip_tags(trim($_POST['nom']));
$prenom = strip_tags(trim($_POST['prenom'] ?? ''));
$email  = strip_tags(trim($_POST['email'] ?? ''));
$phone  = trim($_POST['phone'] ?? '');
$id     = filter_input(INPUT_POST, 'id_num', FILTER_SANITIZE_NUMBER_INT);


if (!preg_match('/^[a-zA-ZÀ-ÿ\-\']+$/', $nom)) {
    $_SESSION['insert_update_admin'] = ['type' => 'error', 'msg' => 'Le nom est requis'];
    header("location:../view_admin.php");
    exit;
}

if (!preg_match('/^[a-zA-ZÀ-ÿ\-\' ]+$/', $prenom)) {
    $_SESSION['insert_update_admin'] = ['type' => 'error', 'msg' => 'Le prénom est requis'];
    header("location:../view_admin.php");
    exit;
}

if (!preg_match("/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/", $email)) {
    $_SESSION['insert_update_admin'] = ['type' => 'error', 'msg' => 'Email invalide'];
    header("location:../view_admin.php");
    exit;
}

if (!preg_match('/^(06|07|05)[0-9]{8}$/', $phone)) {
    $_SESSION['insert_update_admin'] = ['type' => 'error', 'msg' => 'Numéro de téléphone invalide'];
    header("location:../view_admin.php");
    exit;
}


$currentDataStmt = $conn1->prepare("SELECT email, image FROM admin WHERE id_admin = ?");
$currentDataStmt->bind_param('i', $id);
$currentDataStmt->execute();
$currentResult = $currentDataStmt->get_result();

if ($currentResult->num_rows === 0) {
    $_SESSION['insert_update_admin'] = ['type' => 'error', 'msg' => 'Administrateur introuvable'];
    header("location:../view_admin.php");
    exit;
}

$currentData   = $currentResult->fetch_assoc();
$oldEmail      = $currentData['email'];
$currentImage  = $currentData['image'];
$currentDataStmt->close();

// معالجة الصورة
$fileName = $currentImage; // الاحتفاظ بالصورة القديمة افتراضياً
// echo "hheh";
//     die();



if (isset($_FILES['image1']['name']) && $_FILES['image1']['error'] !== UPLOAD_ERR_NO_FILE ) {
    $targetDir = "../uploads/";
    if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true)) {
        $_SESSION['insert_update_admin'] = ['type' => 'error', 'msg' => 'Impossible de créer le dossier d\'upload.'];
        header("location:../view_admin.php");
        exit;
    }


    $fileTmpPath  = $_FILES['image1']['tmp_name'];
    $newFileName  = uniqid('admin_', true) . '.webp';
    $targetFile   = $targetDir . $newFileName;

    $mimeType = mime_content_type($fileTmpPath);
    $sourceImage = false;

    switch ($mimeType) {
        case 'image/jpeg': $sourceImage = imagecreatefromjpeg($fileTmpPath); break;
        case 'image/png':  $sourceImage = imagecreatefrompng($fileTmpPath);  break;
        case 'image/gif':  $sourceImage = imagecreatefromgif($fileTmpPath);  break;
        case 'image/webp': $sourceImage = imagecreatefromwebp($fileTmpPath); break;
        case 'image/bmp':  $sourceImage = imagecreatefrombmp($fileTmpPath);  break;
        default:
            $_SESSION['insert_update_admin'] = ['type' => 'error', 'msg' => 'Format d\'image non supporté'];
            header("location:../view_admin.php");
            exit;
    }

    if ($sourceImage && imagewebp($sourceImage, $targetFile, 80)) {
        imagedestroy($sourceImage);
        if (!empty($currentImage) && file_exists($targetDir . $currentImage)) {
            unlink($targetDir . $currentImage);
        }
        $fileName = $newFileName;
    } else {
        $_SESSION['insert_update_admin'] = ['type' => 'error', 'msg' => 'Erreur lors de la conversion en WebP'];
        header("location:../view_admin.php");
        exit;
    }
}

// التحقق من تغيير البريد الإلكتروني
$emailChanged = ($email !== $oldEmail);

if ($emailChanged) {
    // التأكد من عدم وجود البريد لمسؤول آخر
    $emailCheckStmt = $conn1->prepare("SELECT id_admin FROM admin WHERE email = ? AND id_admin != ?");
    $emailCheckStmt->bind_param('si', $email, $id);
    $emailCheckStmt->execute();
    if ($emailCheckStmt->get_result()->num_rows > 0) {
        $_SESSION['insert_update_admin'] = ['type' => 'error', 'msg' => 'Cet e-mail existe déjà pour un autre administrateur'];
        $emailCheckStmt->close();
        header("location:../view_admin.php");
        exit;
    }
    $emailCheckStmt->close();

    // إنشاء كلمة مرور جديدة وإرسالها
    function generateVerificationCode($length = 8) {
        return bin2hex(random_bytes($length / 2));
    }

    $passwordGenerateForEmployee = generateVerificationCode(8);
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $_SESSION['phpMailer']['email'];
        $mail->Password   = $_SESSION['phpMailer']['password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom($_SESSION['phpMailer']['email'], 'Conseil provincial Youssoufia');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Votre nouveau mot de passe';
        $mail->Body    = "<h1>Bonjour, $prenom $nom</h1><p>Votre nouveau mot de passe est : <b>$passwordGenerateForEmployee</b></p>";

        $mail->send();

        $password = password_hash( $passwordGenerateForEmployee , PASSWORD_DEFAULT);

        // تحديث مع البريد وكلمة المرور الجديدة
        $updateStmt = $conn1->prepare("UPDATE admin SET nom = ?, prenom = ?, telephone = ?, image = ?, email = ?, mot_de_passe = ? WHERE id_admin = ?");
        $updateStmt->bind_param("ssssssi", $nom, $prenom, $phone, $fileName, $email, $password, $id);

        $_SESSION['admin_email'] = $email;

        $_SESSION['email_send_avec_success_a_admin'] = true;

    } catch (Exception $e) {
        $_SESSION['insert_update_admin'] = ['type' => 'error', 'msg' => 'Erreur lors de l\'envoi de l\'email'];
        header("location:../view_admin.php");
        exit;
    }
} else {
    // تحديث بدون تغيير البريد الإلكتروني
    $updateStmt = $conn1->prepare("UPDATE admin SET nom = ?, prenom = ?, telephone = ?, image = ? WHERE id_admin = ?");
    $updateStmt->bind_param("ssssi", $nom, $prenom, $phone, $fileName, $id);
}

// تنفيذ التحديث
if ($updateStmt->execute()) {
    $_SESSION['insert_update_admin'] = ['type' => 'success', 'msg' => 'Administrateur modifié avec succès'];
} else {
    $_SESSION['insert_update_admin'] = ['type' => 'error', 'msg' => 'Erreur lors de la modification'];
}

$updateStmt->close();
$conn1->close();

header("Location:../view_admin.php?UpdateAdmin=true");
exit;
?>
