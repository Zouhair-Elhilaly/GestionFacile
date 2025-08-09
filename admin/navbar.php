<?php 
// include "functions/chiffre.php";
if (!defined('SECURE_ACCESS')) {
    // Si on tente d’accéder directement au fichier sans passer par include
    header('location:error.php');
    exit();
}
?>

<!-- start navbar -->
<!-- <div class="container_admin">
<div class="navbar">
    <div class="accueil">
        <a href="acceuil.php" class="accueil_btn">Accueil</a>  -background image = icon -
    </div>
    <div class="produit">
        <a href="view_product.php" class="produit_btn">Produit</a>  <!--background image = icon --
    </div>
    <div class="produit">
        <a href="view_post.php" class="produit_btn">Post</a>  <!--background image = icon --
    </div>
    <div class="produit">
        <a href="view_category.php" class="produit_btn">Category</a>  <!--background image = icon --
    </div>
    <div class="category">
        <a href="view_service.php" class="category_btn">Service</a>  <!--background image = icon --
    </div>
    <div  class="admin">
    <a id="admin_click" href="view_admin.php" class="admin_btn">Admin</a>    </div>
    <div class="employee">
        <a href="view_employee.php" class="employee_btn">employes</a>  <!--background image = icon --
    </div>
    <div class="Reservation">
        <a href="commande.php" class="Reservation_btn">Reservation</a>  <!--background image = icon-
    </div>
    <div class="Rapport">
        <a href="view_rapport.php" class="Rapport_btn">Rapport</a>  <!--background image = icon --
    </div>
    <div class="deconnection">
        <a href="#" class="deconnection_btn">deconnection</a>  <!--background image = icon --
    </div>
    <div class="parametre">
        <a href="#" class="parametre_btn">parametre</a>  <!--background image = icon --
    </div>
</div>
 -->



<!-- **************************************************  -->
 
    <div class="navbar" id="navbar">
        <div class="accueil">
            <a href="acceuil.php" class="accueil_btn">Accueil</a>
        </div>
        <div class="produit">
            <a href="view_product.php" class="produit_btn">Produits</a>
        </div>
        <div class="produit">
            <a href="view_post.php" class="produit_btn">Posts</a>
        </div>
        <div class="produit">
            <a href="view_category.php" class="produit_btn">Catégories</a>
        </div>
        <div class="category">
            <a href="view_service.php" class="category_btn">Services</a>
        </div>
        <div class="admin">
            <a id="admin_click" href="view_admin.php" class="admin_btn">Administrateurs</a>
        </div>
        <div class="employee">
            <a href="view_employee.php" class="employee_btn">Employés</a>
        </div>
        <div class="Reservation">
            <a href="commande.php" class="Reservation_btn">Réservations</a>
        </div>
        <div class="Rapport">
            <a href="view_rapport.php" class="Rapport_btn">Rapports</a>
        </div>

        <div class="parametre">
            <a href="profile.php" class="profile_btn">Profile</a>
        </div>
        <div class="deconnection">
            <a href="#" class="deconnection_btn" onclick="logout('<?= encryptId($_SESSION['id_admin']) ?>','<?= $token ?>')">Déconnexion</a>
        </div>
    </div>

    <div class="container_admin">
        <style>
            .navbar > div > a {
                color: var(--text-color)
            }
        </style>