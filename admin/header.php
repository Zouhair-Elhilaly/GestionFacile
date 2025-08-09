
<?php 

if (!defined('SECURE_ACCESS')) {
    // Si on tente d’accéder directement au fichier sans passer par include
    header('location:error.php');
    exit();
}



if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "../include/config.php";
if (!function_exists('encryptId')) {
    
    include "functions/chiffre.php";
    }


    // Requête de suppression
$stmtDelete = $conn1->prepare("DELETE FROM commande
WHERE TRIM(LOWER(status)) = (TRIM('Confirmée') or  TRIM('Rejetée'))
  AND date_rejet IS NOT NULL
  AND DATE_ADD(date_rejet, INTERVAL 2 MINUTE) <= NOW();
");

$stmtDelete->execute();

// $stmtDelete->close();

$token = encryptId("token");

// start requpure email et password
$mail = $conn1->prepare("SELECT * FROM config");
$mail->execute();
$res = $mail->get_result();
if($res->num_rows > 0){
    $key = "MaCleSecrete123456"; 
    $row = $res->fetch_assoc();
    $_SESSION['phpMailer'] = [
        'email' => $row['email'],
        'password' => openssl_decrypt($row['mot_de_passe_app'], "AES-128-ECB", $key),
    ];
 $d = $_SESSION['phpMailer']['password'];
}

$mail->close();
// start requpure email et password





// requpure admin session
if(isset($_SESSION['admin_email'])){
    if(!empty($_SESSION['admin_email'])){
    $em = $conn1->prepare("SELECT * from admin WHERE email = ? ");
    $em->bind_param('s',$_SESSION['admin_email']);
    $em->execute();
    $resEmail = $em->get_result();
    if($resEmail->num_rows >0){
        $rowEmail = $resEmail->fetch_assoc();
        $_SESSION['id_admin'] = $rowEmail['id_admin'];
    }else{
      header("location:error.php");
      exit();
    }
}else{
    header("location:error.php");
    exit();
}
}else{
    header("location:error.php");
      exit();
}
// end requpure admin
?>






<!-- *************************** -->
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Système de Gestion du Stock</title>
         <!-- CSS SweetAlert2 -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
  

        <!-- JS SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
          <!-- JS jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <!-- Select2 CSS -->
            <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
            <!-- Select2 JS -->
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
:root {
             --deleteBtn: #dc3545;
            --modifyHover: #e0a800;
            --deleteHover : #c82333;
            --modifyBtn : #ffc107;
            --bgColor : linear-gradient(#A559D4 ,#A559D4);
            --trHover: #472EA3;
            --titleColor : #9B59D4;
            --primary-color: #2563eb;
            --primary-dark: #1e40af;
            --secondary-color: #f8fafc;
            --text-color: #1f2937;
            --text-light: #6b7280;
            --text-lightHover: #67686aff;
            --bg-logo: #b4b6baff;
            --input-light : #f8fafc;
            --shadowForm : 1px 1px 1px black;
            --border-color: #e5e7eb;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadowProduct: .3px .7px 1px white;
            --addUser:  linear-gradient(135deg, #6e8efb, #a777e3);
            --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradientRotate: linear-gradient(135deg,#764ba2  0%, #667eea 100%);
             --transition: .3s linear;
             --addUserHover: linear-gradient(135deg,#a777e3,#6e8efb);
            }

            .popudMode{
                background-color: var(--secondary-color);
                color: var(--text-color);
            }

        @media (max-width:600px){
            body{
                font-size: 12px;
            }
        }

        [data-theme="dark"] {
            --bg-logo: #ffffffff;
            --input-light : #6b7280;
            --titleColor : #9B59D4;
            --gradientRotate: linear-gradient(135deg,#764ba2  0%, #667eea 100%);
            --transition: .3s linear;
            --addUserHover: linear-gradient(135deg,#a777e3,#6e8efb);
            --addUser:  linear-gradient(135deg, #6e8efb, #a777e3);
            --deleteHover : #c82333;
            --deleteBtn: #dc3545;
            --modifyHover: #e0a800;
            --modifyBtn : #ffc107;
            --shadowProduct: .3px .7px 1px white;
            --primary-color: #3b82f6;
            --primary-dark: #2563eb;
            --secondary-color: #1f2937;
            --text-color: #f9fafb;
            --text-light: #d1d5db;
            --text-lightHover: #dbdfe8ff;
            --border-color: #374151;
             --shadowForm : 1px 1px 1px white;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
            --gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --bgColor : linear-gradient(#A559D4,#A559D4);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--secondary-color);
            color: var(--text-color);
            transition: all 0.3s ease;
            overflow-x: hidden;
        }


        /* btn */
  
    #scrollTopBtn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 50px;
    height: 50px;
    background: var(--gradient); /* Vert */
    color: white;
    border: none;
    border-radius: 50%;
    font-size: 24px;
    cursor: pointer;
    display: none; /* Caché par défaut */
    justify-content: center;
    align-items: center;
    box-shadow: 0 4px 6px rgba(0,0,0,0.3);
    transition: 0.3s;
    z-index: 30000
}
#scrollTopBtn:hover {
    background-color: var(--gradientRotate);
    transform: scale(1.1);
}

  td{
        color: var(--text-color) !important
    }

.modify-btn {
  background-color:  #e0a53fff;
   padding: 3px 5px;
 border-radius: 4px;
 color: var(--text-color) !important;
}

.rejetBtn{
    padding: 3px 5px;
 border-radius: 4px;
 color: var(--text-color) !important;
    background-color:  #ef4444
}

.rejetBtn:hover{
    background-color:  #fd0000ff
}
.modify-btn:hover {
  background-color:  #9e6709ff;
}

.btn-success{
    padding: 3px 5px;
  background: #22be8cff;
  border-radius: 4px;
}

.btn-success:hover {
  background: #059669;
}

.action-btn {
  padding: 6px 10px;
  margin: 0 3px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
  transition: background-color 0.3s;
}


.delete-btn {
  background-color: var(--deleteBtn);
  color: white;
}

.delete-btn:hover {
  background-color:  var(--deleteHover);
}



        /* end btn */

        /* Header Styles */
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
            z-index: 1000;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
        }

        [data-theme="dark"] header {
            background: rgba(31, 41, 55, 0.95);
        }

        .logo_and_close {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo_admin {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor:pointer;
        }

        input::placeholder{
            color: var(--text-light) !important;
        }
        input{
            
            background-color: var(--input-light) !important;
        }
        .stat-label{
            overflow:auto;
             -ms-overflow-style: none;    /* IE 10+ */
             scrollbar-width: none;
        }
        textarea::placeholder{
            color: var(--text-light) !important;
        }
        select,textarea{
            color: var(--text-light) !important;
            background-color: var(--input-light) !important;
        }

        .logo_admin img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            background-color: var(--bg-logo);
        }

        .title_dashboard {
            font-size: 1.5rem;
            font-weight: 700;
            /* background: var(--gradient); */
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-transform: capitalize;
                            font-family: 'VotrePolice', sans-serif;
                        background: linear-gradient(to right, #CD0BF4, #00A3FF);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        color: transparent; 
        }

        .close_tag {
            display: none;
        }

        #closebtn {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-color);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        #closebtn:hover {
            background: var(--border-color);
            transform: rotate(90deg);
        }

        .left_admin_header {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .mode {
            background: var(--gradient);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: var(--shadow);
        }

        @media (max-width: 392px){
            .mode{
                padding: 3px 7px !important;
                font-size: 12px  !important;
            }

        }

        .mode:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        #notification {
            position: relative;
            padding: 0.5rem;
            cursor: pointer;
            color: var(--text-light);
            transition: all 0.3s ease;
            list-style: none;
        }

        #notification:hover {
            color: var(--primary-color);
        }

        #notification::before {
            content: '\f0f3';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            font-size: 1.2rem;
        }

        .menu_notification {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            list-style: none;
            z-index: 1001;
        }

        [data-theme="dark"] .menu_notification {
            background: var(--secondary-color);
            border-color: var(--border-color);
        }

        #notification:hover .menu_notification {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .menu_notification li {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border-color);
            cursor: pointer;
            transition: all 0.3s ease;
            display: block;
            text-decoration: none;
        }

        .menu_notification li:last-child {
            border-bottom: none;
        }

        .menu_notification li:hover {
            background: var(--border-color);
        }

        .left_admin_header > img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-color);
            cursor: pointer;
            transition: all 0.3s ease;
            background: var(--gradient);
        }

        .left_admin_header > img:hover {
            transform: scale(1.1);
            box-shadow: var(--shadow-lg);
        }

        /* Navbar Styles */
        .navbar {
            position: fixed;
            left: 0;
            top: 70px;
            width: 280px;
            height: calc(100vh - 70px);
            background: white;
            border-right: 1px solid var(--border-color);
            padding: 2rem 0;
            overflow-y: auto;
            transition: transform 0.3s ease;
            z-index: 999;
            box-shadow: var(--shadow);
        }

        [data-theme="dark"] .navbar {
            background: var(--secondary-color);
            border-right-color: var(--border-color);
        }

        .navbar > div {
            margin-bottom: 0.5rem;
        }

        .navbar a {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 2rem;
            text-decoration: none;
            color: var(--text-color);
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            text-transform: capitalize;
        }

        .navbar a::before {
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            font-size: 1.1rem;
            width: 20px;
        }

        .accueil_btn::before { content: '\f015'; }
        .produit_btn::before { content: '\f1b2'; }
        .category_btn::before { content: '\f07b'; }
        .admin_btn::before { content: '\f007'; }
        .employee_btn::before { content: '\f0c0'; }
        .Reservation_btn::before { content: '\f073'; }
        .Rapport_btn::before { content: '\f1c1'; }
        .deconnection_btn::before { content: '\f2f5'; }
        .profile_btn::before { content: '\f007'; }



        

        .navbar a:hover {
            color: var(--text-color) !important;
            background: rgba(37, 99, 235, 0.1);
            transform: translateX(10px);
        }

        .navbar a::after {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--primary-color);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .navbar a:hover::after {
            transform: scaleY(1);
        }

        /* Main Content */
        .container_admin {
            margin-left: 280px;
            margin-top: 70px;
            padding: 2rem;
            min-height: calc(100vh - 70px);
            transition: margin-left 0.3s ease;
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            header {
                padding: 0 1rem;
            }

            .close_tag {
                display: block;
            }

            .title_dashboard {
                font-size: 1.2rem;
            }

            .left_admin_header {
                gap: 1rem;
            }

            .navbar {
                transform: translateX(-100%);
                width: 280px;
            }

            .navbar.active {
                transform: translateX(0);
            }

            .container_admin {
                margin-left: 0;
                padding: 1rem;
            }

            .mode {
                padding: 0.4rem 0.8rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            header {
                padding: 0 0.5rem;
            }

            .title_dashboard {
                font-size: 1rem;
            }

            .left_admin_header {
                gap: 0.5rem;
            }

            .left_admin_header > img {
                width: 35px;
                height: 35px;
            }

            .logo_admin img {
                width: 35px;
                height: 35px;
            }

            .navbar {
                width: 100%;
            }

            .container_admin {
                padding: 0.5rem;
            }
        }

        /* Overlay for mobile */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 998;
        }

        .overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Animation classes */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Custom scrollbar */
        .navbar::-webkit-scrollbar {
            width: 6px;
        }

        .navbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .navbar::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 3px;
        }

        .navbar::-webkit-scrollbar-thumb:hover {
            background: var(--text-light);
        }
    </style>



</head>
<body data-theme="light">

<button id="scrollTopBtn" title="Aller en haut">
    ⬆
</button>


    <div class="overlay" id="overlay"></div>
    
    <header id="header" class="fade-in">
        <div class="logo_and_close">
            <div class="logo_admin">
                <!-- <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='50' fill='%23667eea'/%3E%3Ctext x='50' y='60' text-anchor='middle' fill='white' font-size='40' font-family='Arial'%3ES%3C/text%3E%3C/svg%3E" alt="Logo"> -->
                 <img src="../uploads/logo.png" alt="">
                <h3 class="title_dashboard">Dashboard</h3>

            </div>
            <div class="close_tag">
                <button id="closebtn" onclick="toggleNavbar()">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
        <div class="left_admin_header">
            <button class="mode" onclick="toggleTheme()">
                <i class="fas fa-moon" id="theme-icon"></i>
                <span id="theme-text">Mode Sombre</span>
            </button>
            <ul>
                <li id="notification">
                    <span class="notification-badge" style="position: absolute; display:none; top: 0; right: 0; background: #ef4444; color: white; border-radius: 50%; width: 8px; height: 8px;"></span>
                    <ul class="menu_notification">

                        <?php 
                        $counter = 0;
                        $aucun2 = 1;
                        $aucun1 = 3;
                        $atente = 'En attente';
                        $cmd = $conn1->prepare("SELECT COUNT(*) as counterCmd from commande WHERE status like ?");
                        $cmd->bind_param('s', $atente);
                        $cmd->execute();
                        $resCmd = $cmd->get_result();
                        if($resCmd->num_rows > 0){
                            $rowCommandeHeader = $resCmd->fetch_assoc();
                            $counterCmd =  $rowCommandeHeader['counterCmd'];
                            if($counterCmd > 0){
                                $counter = 1;
                            ?>
                            
                            <li id="notification_commande" onclick="commande()"><i class='fas fa-info-circle'></i><?php 
                           

                            if ( $counterCmd == 1) {
                                echo " Nouvelle commande reçue";
                            } else if($counterCmd > 1) {
                                echo " $counterCmd nouvelles commandes reçues";
                            }
                            ?></li>
                      <?php  }
                        }{
                            $aucun1 = 0;
                        }

                        $pstock = 0;
                        $produitStockCheck = $conn1->prepare("SELECT COUNT(*) as quantiteEmpty FROM produit where stock = ?");
                        $produitStockCheck->bind_param('i',$pstock );
                        $produitStockCheck->execute();
                        $resPrEmpty =  $produitStockCheck->get_result();
                        if($resPrEmpty->num_rows > 0){
                           $resultStock = $resPrEmpty->fetch_assoc();
                           $quantiteEmpty = $resultStock['quantiteEmpty'];
                           if($quantiteEmpty > 0){
                             $counter = 1;
                            ?>
                            <li id="produitNotification" onclick="produit()" ><i class="fas fa-exclamation-triangle"></i> Stock faible: <?= $quantiteEmpty ?> Produit </li>
                            <?php
                           }
                    }
                        $produitStockCheck->close();

                        if($counter == 1){
                            ?>
                            <style>
                                .notification-badge{
                                    display:block !important;
                                }
                            </style>
                            <?php
                        }
                        ?>
                        
                        
                        
                    </ul>
                </li>
            </ul>
            <?php  
            // requpure_image 
            $img = $conn1->prepare("SELECT * FROM admin WHERE id_admin = ?");
            $img->bind_param('i' ,$_SESSION['id_admin']);
            $img->execute();
            $resImage = $img->get_result();
            if( $resImage->num_rows > 0){
                $rowImg = $resImage->fetch_assoc();
                $image =  $rowImg['image'];
            }

            $img->close();

            if(isset($image)){
                echo "<img src='protection_image/image_proxy.php?img=$image' alt='Profile'>";
            }else{
            ?>
            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='50' fill='%23764ba2'/%3E%3Ctext x='50' y='60' text-anchor='middle' fill='white' font-size='30' font-family='Arial'%3EAD%3C/text%3E%3C/svg%3E" alt="Profile">
            <?php
            }
            ?>
        </div>
    </header>
