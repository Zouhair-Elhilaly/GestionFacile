<?php 
define('SECURE_ACCESS', true);
 include_once "header.php"; ?>
<!-- <link rel="stylesheet" href="style.css"> -->
<link rel="stylesheet" href="css/acceuil.php">

<!-- < ?php include_once "../include/config.php";  -->

<?php include_once "navbar.php"; 




// start insert_signature

if(isset($_SESSION['insert_signature'])){
    if(!empty($_SESSION['insert_signature'])){
        $text = $_SESSION['insert_signature']['msg'];
        $type = $_SESSION['insert_signature']['type'];
        echo "<script>
        Swal.fire({
            icon: '$type',
            title: 'Notification',
            text: '$text',
            timer: 3000 
        });</script>";
        
        unset($_SESSION['insert_signature']);
    }
}
// end insert_signature


// start affiche erreur de logout

if(isset($_SESSION['empty_admin'])){
    if(!empty($_SESSION['empty_admin'])){
        $text = $_SESSION['empty_admin']['msg'];
        $type = $_SESSION['empty_admin']['type'];
        echo "<script>
        Swal.fire({
            icon: '$type',
            title: 'Notification',
            text: '$text',
            timer: 3000 
        });</script>";
        
        unset($_SESSION['empty_admin']);
    }
}
// end affiche erreur de logout

?>

<div class="main_accueil">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <div class="dashboard-container">
        <!-- Header -->
        <div class="header">
            <h1><i class="fas fa-crown"></i> Tableau de Bord Administrateur</h1>
            <p>Gérez votre système de manière efficace et intuitive</p>
        </div>

        <!-- Cards Grid -->
        <div class="cards-grid">
            <!-- Card 1: Statistiques des produits par catégorie -->
            <div class="card">
                <div class="card-header">
                    <div class="card-icon stats">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="card-title">Statistiques des Catégories</div>
                </div>

                <?php
                // Requête pour les catégories
               $produit = $conn1->prepare("SELECT count(*) AS counter FROM category");
               $produit->execute();
               $totalProduits = $produit->get_result()->fetch_assoc();


                ?>

                <div class="stats-container">
                    <div class="stat-item">
                        <div class="stat-number"><?= $totalProduits['counter'] ?></div>
                        <div class="stat-label">Total Catégories</div>
                    </div>

                    <?php

                    $category = $conn1->prepare("SELECT * FROM category ORDER BY id_category");
                    $category->execute();
                    $categories = $category->get_result();


                     while ($row = $categories->fetch_assoc()): 
                     $sumProductCategory = $conn1->prepare("SELECT COUNT(id_category) AS id_C from produit WHERE id_category = ?");
                     $sumProductCategory->bind_param('i',$row['id_category']);
                     $sumProductCategory->execute();
                     $catCounter = $sumProductCategory->get_result()->fetch_assoc();
                     ?>
                     
                        <div class="stat-item">
                            <div class="stat-number"><?= $catCounter['id_C']?></div>
                            <div class="stat-label"><?= htmlspecialchars($row['nom_category']) ?></div>
                        </div>
                    <?php endwhile ; ?>
                </div>
            </div>





            <!-- start card des produit -->
            <div class="card">
                <div class="card-header">
                    <div class="card-icon stats">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="card-title">Statistiques des Produits</div>
                </div>

                <?php
                // Requête pour les catégories
               $produit = $conn1->prepare("SELECT SUM(stock) AS counter FROM produit");
               $produit->execute();
               $totalProduits = $produit->get_result()->fetch_assoc();


                ?>

                <div class="stats-container">
                    <div class="stat-item">
                        <div class="stat-number"><?= $totalProduits['counter']? $totalProduits['counter']:0 ?></div>
                        <div class="stat-label">Total Produits</div>
                    </div>

                  
                   
                     

      
                </div>
            </div>

            <!-- end card des produit -->

            <?php 
            
            $administrator = $conn1->prepare("SELECT * from admin WHERE id_admin = ? and adm_id_admin IS NULL");
            $administrator->bind_param('i',$_SESSION['id_admin']);
            $administrator->execute();
            $resA = $administrator->get_result();
            $administrator->close();
            if($resA->num_rows > 0){

            
            ?>
            <!-- Card 2: Email Provincial -->
            <div class="card">
                <div class="card-header">
                    <div class="card-icon employee">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="card-title">Génération email Povincial</div>
                </div>

                <form id="emailForm">
                    <div class="form-group">
                        <label for="employeeEmail">Email Povincial</label>
                        <input onmouseleave="checkEmail()"  type="text" name="email_provincial" id="employeeEmail" class="form-control" placeholder="exemple@province.gov.ma">
                        <label for="employeeEmail">Mot de Passe application</label>
                        <input type="password" name="password_povincial"  class="form-control" placeholder="........." id="employeePassword">
                    </div>
                    <button onclick="validateData()" name="generate_email" class="btn">
                        <i class="fas fa-key"></i>
                        Ajouter email Povincial
                    </button>
                     </form>
                    <script src="js/acceuil.js"></script>
                <?php
                if (isset($_POST['generate_email'])) {
                    $email = $_POST['email_provincial'];
                    $code = strtoupper(uniqid("PRV-"));
                    echo "<div class='success-message'><strong>Code généré avec succès !</strong><br>Code: <strong>$code</strong><br>Envoyé à : $email</div>";
                }
                ?>
            </div>
            <?php }?>

            <!-- Card 3: Upload signature -->
            <div class="card">
                <div class="card-header">
                    <div class="card-icon signature">
                        <i class="fas fa-signature"></i>
                    </div>
                    <div class="card-title">Signature Administrateur</div>
                </div>

                <form method="post" action="insert/insert_signature.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label  class="label-upload " for="dg">Choisir une image</label>
                        <input style="display:none" id="dg"  type="file" name="signature" accept="image/*" required >
                    </div>
                    <button type="submit" name="upload_signature" class="btn">
                        <i class="fas fa-save"></i>
                        Sauvegarder Signature
                    </button>
                </form>

                <?php
                if (isset($_POST['upload_signature']) && isset($_FILES['signature'])) {
                    $file = $_FILES['signature'];
                    if ($file['error'] === 0 && $file['size'] <= 5 * 1024 * 1024) {
                        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                        if (in_array(strtolower($ext), ['png', 'jpg', 'jpeg'])) {
                            $dest = "uploads/signature_admin." . $ext;
                            move_uploaded_file($file['tmp_name'], $dest);
                            echo "<div class='success-message'>Signature sauvegardée avec succès !</div>";
                            echo "<div><img src='$dest' alt='Signature' style='max-width: 200px; margin-top: 10px;'></div>";
                        } else {
                            echo "<div class='error-message'>Format non supporté.</div>";
                        }
                    } else {
                        echo "<div class='error-message'>Erreur de fichier ou taille trop grande.</div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>



<?php




include_once "footer.php"; ?>
