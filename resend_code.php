<?php 




// fonction php mailler
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';



if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
   header("location:error.php");
   exit();
}


session_start();
require_once 'include/config.php';
require_once "admin/functions/chiffre.php";


function generateVerificationCode($length = 8) {
        return bin2hex(random_bytes($length / 2));
    }


if($_SERVER['REQUEST_METHOD'] == 'POST'){
  
  $email = filter_var($_POST['reset_email'] , FILTER_SANITIZE_EMAIL);

  if(!empty($email)){


    // requpure email and pass phpmailler
    $phpmailler = $conn1->prepare("SELECT * FROM config  WHERE email IS NOT NULL");
    $phpmailler->execute();
    $resMailler = $phpmailler->get_result();
    if($resMailler->num_rows > 0){
      $row = $resMailler->fetch_assoc();
       
      $key = "MaCleSecrete123456";
      $passwordApp = openssl_decrypt($row['mot_de_passe_app'], "AES-128-ECB", $key); ;
      $emailApp = $row['email'];

    }else{
      $_SESSION['reset_password'] = [
        'type' => 'error',
        'msg' => 'empty email and password pour resend password'
      ];
      header("location:index.php");
      exit();
    }




    $stmt = $conn1->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->bind_param('s',$email);
    $stmt->execute();
    $resAdmin = $stmt->get_result();

    if($resAdmin->num_rows > 0){
        $row1 = $resAdmin->fetch_assoc();

        $prenom = $row1['prenom'];
        $nom = $row1['nom'];
        $id = $row1['id_admin'];

        
    $passwordGenerate1 = generateVerificationCode(8);
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = "$emailApp";
        $mail->Password   = "$passwordApp";
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom($emailApp, 'Conseil povincial Youssoufia');
        $mail->addAddress($email); // Utiliser l'email de l'employé

        $mail->isHTML(true);
        $mail->Subject = 'Votre  mote de passe de vérification';
        $mail->Body    = "<h1>Bonjour, $prenom $nom</h1><p>Votre nouveau code pour mon application est : <b>$passwordGenerate1</b></p>";
        $mail->AltBody = "Bonjour $prenom $nom,\nVotre code est : $passwordGenerate1";

        $mail->send();

          $password = encryptId($passwordGenerate1);
        
       
         $sql = $conn1->prepare("UPDATE admin SET mot_de_passe = ? WHERE id_admin = ?");
        $sql->bind_param('si', $password,$id);
        $sql->execute();
        $_SESSION['reset_password'] = [
        'type' => 'success',
        'msg' => 'Mot de passe réinitialisé avec succès'
      ];

    } catch (Exception $e) {
        $_SESSION['reset_password'] = [
        'type' => 'error',
        'msg' => 'Le mot de passe n’a pas été réinitialisé'
      ];
    }
    header("location:index.php");
    exit();
    }


    // start employe email 

    $stmt1 = $conn1->prepare("SELECT * FROM employé WHERE email = ?");
    $stmt1->bind_param('s',$email);
    $stmt1->execute();
    $resEmploye = $stmt1->get_result();
    if($resEmploye->num_rows > 0){
      $row2 = $resEmploye->fetch_assoc();

       $prenom = $row2['prenom'];
        $nom = $row2['nom'];
        $id = $row2['id'];

         

    $passwordGenerate = generateVerificationCode(8);
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = "$emailApp";
        $mail->Password   = "$passwordApp";
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom($emailApp, 'Conseil povincial Youssoufia');
        $mail->addAddress($email); // Utiliser l'email de l'employé

        $mail->isHTML(true);
        $mail->Subject = 'Votre  mote de passe de vérification';
        $mail->Body    = "<h1>Bonjour, $prenom $nom</h1><p>Votre nouveau code pour mon application est : <b>$passwordGenerate</b></p>";
        $mail->AltBody = "Bonjour $prenom $nom,\nVotre code est : $passwordGenerate";

        $mail->send();

          $password = password_hash($passwordGenerate , PASSWORD_BCRYPT );
        
       
         $sql = $conn1->prepare("UPDATE employé SET mot_de_passe_em = ? WHERE id = ?");
        $sql->bind_param('si', $password,$id);
        $sql->execute();
        $_SESSION['reset_password'] = [
        'type' => 'success',
        'msg' => 'Mot de passe réinitialisé avec succès'
      ];
    } catch (Exception $e) {
        $_SESSION['reset_password'] = [
        'type' => 'error',
        'msg' => 'Le mot de passe n’a pas été réinitialisé'
      ];
    }
    header("location:index.php");
    exit();



    }
  
  
  }
}
$_SESSION['reset_password'] = [
        'type' => 'error',
        'msg' => 'invalid email'
      ];
 header("location:index.php");
    exit();

?>