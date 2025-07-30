<?php session_start();
include "../include/config.php";
include "../admin/functions/chiffre.php";
// require_once '../include/config.php';
// require_once '../admin/functions/chiffre.php';

if(isset($_SESSION['email_employe'] )){
    if($_SESSION['email_employe'] != '' and filter_var($_SESSION['email_employe'] , FILTER_VALIDATE_EMAIL) == true){
        
        $email = $_SESSION['email_employe'] ;


       
        $stmt = $conn1->prepare("SELECT * FROM employé WHERE email = ?");
         $stmt->bind_param('s',$email);
         $stmt->execute();
         $row = $stmt->get_result()->fetch_assoc();
         $_SESSION['idEMploye'] =  $row['id'];
         

    }
}
        $status = 'En attente';

         $nbC = $conn1->prepare("SELECT COUNT(*) as nbCommande FROM commande WHERE id = ? and status = ?");
         $nbC->bind_param('is',$_SESSION['idEMploye'], $status);
         $nbC->execute();
         $counter = $nbC->get_result()->fetch_assoc();


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
    <div class="dashboard-container">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">RP</div>
                <div class="logo-text">ReservaPro</div>
            </div>
            <div class="nav-menu">
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
                        <span class="nav-text">Mon Profil</span>
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
                    <div class="user-avatar"><?= strtoupper($row['nom'][0]).strtoupper($row['prenom'][0]) ?></div>
                </div>
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
