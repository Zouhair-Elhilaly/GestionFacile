<?php
session_start();

require_once '../../include/config.php'; // Connexion à MySQL



if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
    include("../error.php");
    exit();
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['signature'])) {
    $fileTmp = $_FILES['signature']['tmp_name'];
    $mime = mime_content_type($fileTmp);

    // Vérifier si c'est une image
    if (!str_starts_with($mime, 'image/')) {
         $_SESSION['insert_signature']= [
            'type' => 'error',
            'msg' => 'Fichier non valide'
        ];
        header("location:../acceuil.php");
        exit;
    }

    // Charger l'image
    switch ($mime) {
        case 'image/jpeg':
            $src = imagecreatefromjpeg($fileTmp);
            $ext = '.jpg';
            break;
        case 'image/png':
            $src = imagecreatefrompng($fileTmp);
            $ext = '.png';
            break;
        case 'image/webp':
            $src = imagecreatefromwebp($fileTmp);
            $ext = '.webp';
            break;
        default:
            $_SESSION['insert_signature']= [
            'type' => 'error',
            'msg' => 'Format non supporté'
            ];
            header("location:../acceuil.php");
            exit;
    }



    if (!$src){
        $_SESSION['insert_signature']= [
            'type' => 'error',
            'msg' => 'Erreur lecture image'
        ];
        header("location:../acceuil.php");
        exit;
    }

    // Nouvelle taille 200x200
    $width = 200;
    $height = 200;
    $dst = imagecreatetruecolor($width, $height);

    // Taille originale
    [$srcWidth, $srcHeight] = getimagesize($fileTmp);

    // Redimensionner
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $srcWidth, $srcHeight);

    // Créer un nom unique
    $fileName = uniqid('signature_', true) . $ext;
    $folder = "../image/image_signature/";

    // Créer le dossier s'il n'existe pas
    if (!is_dir($folder)) {
        mkdir($folder, 0755, true);
    }

    // Sauvegarder l'image dans le dossier
    $filePath = $folder . $fileName;

    switch ($mime) {
        case 'image/jpeg':
            imagejpeg($dst, $filePath, 80);
            break;
        case 'image/png':
            imagepng($dst, $filePath, 8);
            break;
        case 'image/webp':
            imagewebp($dst, $filePath, 80);
            break;
    }

    $check = $conn1->prepare("SELECT * FROM config ");
     $check->execute();
     $resCHeck = $check->get_result();
      // Enregistrer juste le chemin dans la base
     if($resCHeck->num_rows > 0){
        $stmt = $conn1->prepare("UPDATE config SET image_signature = ?");
        $stmt->bind_param("s", $fileName);
     }else{
         $stmt = $conn1->prepare("INSERT INTO config(image_signature) VALUES(?)");
         $stmt->bind_param("s", $fileName);
     }
   
   
    if ($stmt->execute()) {
        $_SESSION['insert_signature']= [
            'type' => 'success',
            'msg' => 'Image enregistrée avec succès'
        ];
    } else {
       $_SESSION['insert_signature']= [
            'type' => 'error',
            'msg' => 'Erreur'
        ];
    }

    // Libérer mémoire
    imagedestroy($src);
    imagedestroy($dst);
    header("location:../acceuil.php");
    exit;
}
?>
