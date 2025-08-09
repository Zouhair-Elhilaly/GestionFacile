
<?php 
//  session_start();
define('SECURE_ACCESS', true);
include "header.php";
include "navbar.php";

// include "functions/chiffre.php";

// insert_product 
if(isset($_SESSION['insert_product'])){
    if(!empty($_SESSION['insert_product'])){
        $text = $_SESSION['insert_product']['msg'];
        $type = $_SESSION['insert_product']['type'];
        echo "<script>
        Swal.fire({
    icon: '$type',
    title: 'Opération réussie !',
    text: '$text',
    timer: 3000 
});</script>";
        unset($_SESSION['insert_product']);
    }
}
// end insert_pdf

?>

<link rel="stylesheet" href="css/style_produit.php">

<div class="product">
 

    <!-- Contenu principal -->
  
        <div class="navbar_product">
            <button id="add-product-btn" class="add-product-btn">+ Ajouter un produit</button>
            <div class="search-bar">
                <input id="search" type="search" placeholder="Rechercher un produit...">
            </div>
        </div>
        
        <div class="products-grid">
            <!-- start affichage product -->
             <div class="container produit">
                <h2 style="color: var(--titleColor); padding: 10px 0;" class="text-center mb-4">Liste des produits</h2>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover ">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Nom</th>
                                <th>Category</th>
                                <th>Stock</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="serviceTableBody">

                    <?php 
                    $stmt = $conn1->prepare("SELECT * FROM produit ORDER BY id_produit");
                    if($stmt->execute()){
                        $result = $stmt->get_result();
                        foreach($result as $row) {
                        $category = $conn1->prepare("SELECT * FROM category WHERE id_category = ?");
                        $category->bind_param("i", $row['id_category']);
                            $category->execute();
                            $cat_result = $category->get_result()->fetch_assoc();
                            
                    ?>
                    


            
                            <tr>
                                <td data-label="ID"><?= $row['id_produit'] ?></td>
                                <td data-label="Image">
                                    <img src="protection_image/image_produit.php?img=<?= $row['image']?>" alt="Produit 1" class="table-img">
                                </td>
                                <td class='service-name' data-label="Nom"><?= $row['nom_produit']?></td>
                                <td data-label="Categorie"><?php echo  $cat_result['nom_category']?></td>
                                <td data-label="Stock"><?= $row['stock']?></td>
                                <td data-label="Actions" class="action-btns btn_tableau">

                                        <a href="update_product.php?id=<?= encryptId($row['id_produit'])?>&token=<?= $token?>" style="display: block;" class="btn btn-sm btn-warning action-btn modify-btn " ><i class="fas fa-edit"></i>Modifier</a>
                                    
                                        <a href="view_product.php?id=<?= encryptId($row['id_produit'])?>" style="display: block;" class="btn btn-sm btn-danger action-btn delete-btn" ><i class="fas fa-trash"></i>Supprimer</a> 
                                
                                </td>
                            </tr>

                    <?php 
                        $category->close();
                        }
                        $stmt->close();
                       
                    
                    } else {
                        echo "<p>Erreur lors de la récupération des produits</p>";
                    }
                    ?>  
                            
            
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>


<!-- start popup ************************************************************************* -->

   

        <?php 

        //  satrt affiche popup delete category 
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
            $id = (int) decryptId($_GET['id']);
            // check id il exist dans commande 


                $checkProduitExsistEnDetails = $conn1->prepare("SELECT * FROM détailler WHERE id_produit = ?");
                $checkProduitExsistEnDetails->bind_param("i",$id);
                $checkProduitExsistEnDetails->execute();
                $resultCheckProduitExsistEnDetails =$checkProduitExsistEnDetails->get_result();

           
            $msg = 'Cette action est irréversible !';
                if($resultCheckProduitExsistEnDetails->num_rows > 0){
                     $msg = 'Attention! Cela supprimera aussi les commandes liées';
                }
                $checkProduitExsistEnDetails->close();
            ?>
            <script>
                Swal.fire({
                    title: 'Confirmez la suppression',
                    text: "<?php echo $msg ?>",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Oui, supprimer !',
                    cancelButtonText: 'Annuler',
                    backdrop: 'rgba(0,0,0,0.7)',
                    customClass: {
                        popup: 'animated fadeIn faster' // Animation (optionnelle)
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'delete/delete_product.php?id=<?= urlencode($_GET['id']) ?>&token=<?= $token ?>';
                    } else {
                        window.location.href = 'view_product.php';
                    }
                });
            </script>
            
        <!--  satrt affiche popup delete category  -->

            <?php
            exit();
        }
        
// <!-- end popup ************************************************************************* -->

         
 
  // start affiche delted succcessfully

if(isset($_SESSION['delete_produit'] )){
  if($_SESSION['delete_produit'] != ''){

    $type = $_SESSION['delete_produit']['type'];
    $msg = $_SESSION['delete_produit']['msg'];
    $title = $_SESSION['delete_produit']['titre'];

    ?>
     <script>
        Swal.fire({
            title: '<?php echo  $title?>',
            text: '<?php echo $msg ?>',
            icon: '<?php echo $type?>',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK',
            backdrop: 'rgba(0,0,0,0.4)'
        }).then(() => {
            // Optionnel : Recharger la page pour actualiser les données
            window.location.href = 'view_product.php';
        });
    </script>
<?php
    unset($_SESSION['delete_produit'] );
  }
}
?>
<!-- //  end affiche popup delete category  -->





    <!-- start form ajouter product ************************************ -->

    <form action="insert/insert_product.php"  class="form_add_product" method="POST" enctype="multipart/form-data" id="form" style="border-radius: 10px">
        <div class="close_btn_form_product">&times</div>
        <div class="form-group">
            <label for="productName">Nom du produit</label>
            <input type="text" id="productName" name="productName" placeholder="Saisir le nom du produit" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="productQuantite">Quantité</label>
            <input type="number" id="productQuantite" name="productQuantite" placeholder="Saisir la quantité" class="form-control" value="1">
        </div>

        <div class="form-group">
            <label for="productCategory">Catégorie</label>
            <select id="productCategory" name="productCategory" class="form-control" required>
                <option value="" disabled selected>Sélectionnez une catégorie</option>
                <?php 
                $sql = $conn1->prepare("SELECT * FROM category ORDER BY id_category");
                $sql->execute();
                $res = $sql->get_result();
                while($cat = $res->fetch_assoc()) {
                ?>
                    <option value="<?= $cat['id_category'] ?>"><?= htmlspecialchars($cat['nom_category']); ?></option>
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
 
     if(isset($_SESSION['product_data'])){ //requpure data en update 
        if($_SESSION['product_data'] == 1){
            unset($_SESSION['product_data'] ); 
     ?>

    <form action="insert_update/insert_update_product.php" class="form_update_product" method="POST" enctype="multipart/form-data" style="display: block"  >
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
                $sql = $conn1->prepare("SELECT * from category ORDER BY id_category");
                $sql->execute();
                $res = $sql->get_result();
                while($cat = $res->fetch_assoc()) {
                    $selected = '';
                    if($cat['id'] == $_SESSION['category_id']){
                        $selected = 'selected';
                    }
                ?>
                    <option value="<?= $cat['id_category'] ?>" <?= $selected ?>> <?=  htmlspecialchars($cat['nom_category']); ?></option>
                <?php }; ?>
            </select>
        </div>

        <div id="label_image_update" class="form-group">
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
            $conn1->close();    
          ?>
    <!-- end update product -->



    
</div>
<script>
    // notification hidden
let produitNotification = document.getElementById("produitNotification");
produitNotification.style.display = 'none';


</script>
<script src="js/product.js"></script>


<?php include "footer.php"?>




