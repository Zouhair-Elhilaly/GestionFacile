<?php 
session_start();

include "header.php";
include "navbar.php";
include "../include/conn_db.php";
include "functions/chiffre.php";
?>

<link rel="stylesheet" href="style_product.css">
<div class="product">
    <!-- start style css search -->
<style>
    .navbar_product {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #f8f9fa; /* Couleur de fond claire */
    padding: 15px 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    flex-wrap: wrap;
}

.add-product-btn {
    background-color: #28a745; /* Vert */
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

@media (max-width: 676px){
    .add-product-btn{
        padding: 5px 10px;
    }
     .navbar_product{
        text-align: center;
        justify-content: center;
     }
}

.add-product-btn:hover {
    background-color: #218838; /* Vert foncé au hover */
}

.search-bar {
    display: flex;
    align-items: center;
    gap: 10px;
   
}

.search-bar input[type="text"] {
    padding: 8px 12px;
    font-size: 15px;
    border: 1px solid #ced4da;
    border-radius: 5px;
    max-width: 150px;
    transition: border-color 0.3s ease;
}

.search-bar input[type="text"]:focus {
    border-color: #80bdff;
    outline: none;
}

.search-bar button {
    background-color: #007bff; /* Bleu */
    color: white;
    border: none;
    padding: 8px 10px;
    font-size: 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.search-bar button:hover {
    background-color: #0069d9; /* Bleu foncé au hover */
}

</style>
<!-- end style css search -->


    <!-- Contenu principal -->
    <div class="container">
        <div class="navbar_product">
            <button id="add-product-btn" class="add-product-btn">+ Ajouter un produit</button>
            <div class="search-bar">
                <input type="text" placeholder="Rechercher un produit...">
                <button>Rechercher</button>
            </div>
        </div>

        <div class="products-grid">
            <!-- start affichage product -->
            <?php 
            $stmt = $conn->prepare("SELECT * FROM produits ORDER BY id");
            if($stmt->execute()){
                $result = $stmt->get_result();
                foreach($result as $row) {
                    $quantite = 0; // Initialisation par défaut
                    
                    $qnt_stmt = $conn->prepare("SELECT quantite FROM quantite_produits WHERE id_produit = ? LIMIT 1");
                    $qnt_stmt->bind_param('i', $row['id']);
                    if($qnt_stmt->execute()){
                        $res_qnt = $qnt_stmt->get_result();
                        $qnt_data = $res_qnt->fetch_assoc();
                        if($qnt_data && isset($qnt_data['quantite'])) {
                            $quantite = (int)$qnt_data['quantite'];
                        }
                    }
                    $qnt_stmt->close();
            ?>
                <!-- Produit -->
                <div class="product-card">
                    <img src="uploads_produits/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['nom']) ?>" class="product-image">
                    <div class="product-info">
                        <h3 class="product-name"><?= htmlspecialchars($row['nom']) ?></h3>
                        <p class="product-price">Quantité: <?= $quantite ?></p>
                        <div class="product-actions">
                            <a class="action-btn update-btn update-btn-product" 
                               href="update_product.php?id=<?= encryptId($row['id']) ?>" >Modifier</a>                            
                        <div class="action-btn delete-btn" onclick="show_delete('<?= encryptId($row['id']) ; ?>')" >Supprimer</div>
                        </div>
                    </div>
                </div>
            <?php 
                }
                $stmt->close();
            } else {
                echo "<p>Erreur lors de la récupération des produits</p>";
            }
            ?>  
        </div>
    </div>

    <!-- start form ajouter product ************************************ -->

    <form action="insert_product.php" class="form_add_product" method="POST" enctype="multipart/form-data" id="form">
        <div class="close_btn_form_product">&times</div>
        <div class="form-group">
            <label for="productName">Nom du produit</label>
            <input type="text" id="productName" name="productName" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="productQuantite">Quantité</label>
            <input type="number" id="productQuantite" name="productQuantite" class="form-control" value="1">
        </div>

        <div class="form-group">
            <label for="productCategory">Catégorie</label>
            <select id="productCategory" name="productCategory" class="form-control" required>
                <option value="" disabled selected>Sélectionnez une catégorie</option>
                <?php 
                $sql = $conn->prepare("SELECT * FROM category ORDER BY id");
                $sql->execute();
                $res = $sql->get_result();
                while($cat = $res->fetch_assoc()) {
                ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']); ?></option>
                <?php }; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="productImage" class="Image_design">Choisir une image</label>
            <input type="file" id="productImage" name="image" class="form-control" style="display: none" required>
        </div>

        <button type="submit" class="submit-btn">Ajouter le produit</button>
    </form>

    <!-- end ajouter product *************************************************** -->










    
    <!-- start form update product -->

 <?php 

// start 
      if(isset($_SESSION['error_update_product'])){
        if($_SESSION['error_update_product'] != ''){
            $var = $_SESSION['error_update_product'];
            unset($_SESSION['error_update_product']);
            echo "<script>alert('".$var."')</script>";
        }
        // end 
      }
 
     if(isset($_SESSION['product_data'])){
        if($_SESSION['product_data'] == 1){
            unset($_SESSION['product_data'] ); 
     ?>

    <form action="insert_update_product.php" class="form_update_product" method="POST" enctype="multipart/form-data" style="display: block"  >
        <div class="close_btn_form_product_update"  ><b class="close_btn_form_product_update">&times</b></div>


       
           
      
        <input type="hidden" id="updateProductId" name="productId" value="<?= encryptId($_SESSION['id_product']); ?>">
         <input type="hidden" id="update-ProductId" name="productImage" value="<?= encryptId($_SESSION['image_product']);?>">

        <div class="form-group">
            <label for="updateProductName">Nom du produit</label>
            <input type="text" id="updateProductName" name="productName" value="<?= $_SESSION['nom_product'] ;?>" class="form-control_update" required>
        </div>

        <div class="form-group">
            <label for="updateProductQuantite">Quantité</label>
            <input required type="number" id="updateProductQuantite" value="<?=$_SESSION['quantite_product']?>" name="productQuantite" class="form-control_update" required>
        </div>

        <div class="form-group">
            <label for="updateProductCategory">Catégorie</label>
            <select id="updateProductCategory" name="productCategory" class="form-control_update" required>
                <!-- <option value="" disabled selected>Sélectionnez une catégorie</option> -->
                <?php 
                $sql = $conn->prepare("SELECT * from category ORDER BY id");
                $sql->execute();
                $res = $sql->get_result();
                while($cat = $res->fetch_assoc()) {
                    $selected = '';
                    if($cat['id'] == $_SESSION['category_id']){
                        $selected = 'selected';
                    }
                ?>
                    <option value="<?= $cat['id'] ?>" <?= $selected ?>> <?=  htmlspecialchars($cat['name']); ?></option>
                <?php }; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="updateProductImage" class="Image_design_update">Choisir une image</label>
            <input type="file" id="updateProductImage" name="image" class="form-control_update" style="display: none">
        </div>

        <button type="submit" class="submit-btn-update">Mettre à jour le produit</button>
        
    </form>

  
  
  <!-- start script close update form  -->
  <script>
        let form_update_product = document.querySelector(".form_update_product");



if ((form_update_product.style).display == 'block'){
    let close_btn_form_product_update = document.querySelector(
      ".close_btn_form_product_update"
    );
  close_btn_form_product_update.addEventListener("click", () => {
    console.log("clicked");
    form_update_product.style.display = "none";
  });

}else{
    console.log("none");
}
    </script>

    <?php       
             }
             }
                
          ?>
    <!-- end update product -->



    
</div>

<script src="product.js"></script>
