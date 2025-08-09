<?php 


if (!defined('SECURE_ACCESS')) {
    // Si on tente d’accéder directement au fichier sans passer par include
    header('location:../error.php');
    exit();
}

session_start();
include "../include/config.php";
include "../admin/functions/chiffre.php";

$token = encryptId("hello");

//     // Requête de suppression
// $sql = "DELETE FROM commande
//         WHERE status = 'Rejetée'
//         AND date_rejet IS NOT NULL
//         AND DATE_ADD(date_rejet, INTERVAL 2 MINUTE) <= NOW()";



// $stmtDelete = $conn1->prepare($sql);

// $stmtDelete->execute();

// $stmtDelete->close();



if(isset($_SESSION['email_employe'] )){
    if($_SESSION['email_employe'] != '' and filter_var($_SESSION['email_employe'] , FILTER_VALIDATE_EMAIL) == true){
        
        $email = $_SESSION['email_employe'] ;


       
        $stmt = $conn1->prepare("SELECT * FROM employé WHERE email = ?");
         $stmt->bind_param('s',$email);
         $stmt->execute();
         $row = $stmt->get_result()->fetch_assoc();
         $_SESSION['idEMploye'] =  $row['id'];
         

    }
}else{
    header("location:../error.php");
    exit();
}
        $status = 'En attente';

         $nbC = $conn1->prepare("SELECT COUNT(*) as nbCommande FROM commande WHERE id = ? and status = ?");
         $nbC->bind_param('is',$_SESSION['idEMploye'], $status);
         $nbC->execute();
         $counter = $nbC->get_result()->fetch_assoc();

if(!isset($_SESSION['employee_priority'])){
   header("/projet_stage/index.php");
   exit();
}
?> 
 <input type="hidden" name="" id="email_employe" value="<?= encryptId($email) ?>">

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Réservation Produits</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/commande.css">
            <!-- CSS SweetAlert2 -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

        <!-- JS SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body data-theme="light">
    
<button id="scrollTopBtn" title="Aller en haut">
    ⬆
</button>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <img style="background-color: white;" class="logo" src="../uploads/logo.png" alt="img">
                <div class="titleStyle">GestionFacile</div>
            </div>
            <div class="nav-menu">
                <div class="toggle"></div>
                <div class="nav-item Accueil_product">
                    <a class="nav-link " href="home.php">
                        <span class="nav-icon">🏠</span>
                        <span class="nav-text">Accueil 
                        </span>
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link" href="all_product.php">
                        <span class="nav-icon">🛒</span>
                        <span class="nav-text">Réserver Produits</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link" href="commande_employe.php" >
                        <span class="nav-icon">📋</span>
                        <span class="nav-text">Mes Commandes</span>
                    </a>
                </div>
                            

                <div class="nav-item">
                    <a class="nav-link nav-link1" href="profile_employe.php?id=<?= encryptId($row['id']) ?>">
                        <span class="nav-icon">👤</span>
                        <span class="nav-text">Mon Profile</span>
                    </a>
                </div>
   <!-- <a href="#" class="deconnection_btn" onclick="logout()">Déconnexion</a>              -->
                <div class="nav-item">
                    <a class="nav-link" onclick="logout('<?= encryptId($_SESSION['idEMploye'])?>', '<?= $token ?>')" href="#?id=<?= encryptId($row['id']) ?>">
                        <!-- <span class="nav-icon">👤</span> -->
                         <i style="margin-right: 4px !important" class="fas fa-sign-in-alt"></i>
                        <span style="margin-left: 4px !important" class="nav-text">Déconnexion</span>
                    </a>
                </div>
                
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="header-left">
                    <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
                    <h1 class="page-title" id="pageTitle">Accueil </h1>
                </div>
                <div class="header-right">
                    <a class="show_counter_commande" href="commande_employe.php" style="position: relative"><div  class="user-avatar">🛒<span class="quantite_commande" ><?php echo  $counter['nbCommande'] ?></span></div></a>
                    <button class="theme-toggle" onclick="toggleTheme()">🌙</button>
                    <div class="user-avatar" style="position:relative"> <img class="user-avatar"   style="position: absolute ; width: 100% ; border-radius: 50%; cursor:pointer" title="Profile" src="../admin/protection_image/protection_employe.php?img=<?= $row['image']?>" alt=""></div>
                </div>
                <style>
                    .user-avatar:hover{
                        transform: scale(1,1)
                    }
                </style>
            </header>

<?php  $nbC->close(); ?>

<!-- <script>
    document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner tous les liens de navigation
    const navLinks = document.querySelectorAll('.nav-link');
    
    // Ajouter un écouteur d'événement à chaque lien
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Retirer la classe 'active' de tous les liens
            navLinks.forEach(l => l.classList.remove('active'));
            
            // Ajouter la classe 'active' au lien cliqué
            this.classList.add('active');
        });
    });
});
</script> -->
