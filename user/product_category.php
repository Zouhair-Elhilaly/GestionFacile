<?php include "header.php";
include "../include/conn_db.php";
include "../admin/functions/chiffre.php";

?>
<div class="content">
      <div class="search-container">
        <i class="fa fa-search search-icon"></i>
        <input type="search" name="search" id="search" placeholder="Search category ...">
  </div>  

<div class="category-card" >
    
    <!-- start affichage card d'un category  -->
            <?php 

            
        if($_SERVER['REQUEST_METHOD'] == 'GET'){

            $idCategory = filter_var(decryptId($_GET['id']) , FILTER_SANITIZE_NUMBER_INT);
                $stmt = $conn->prepare("SELECT * FROM produits  where category_id = ?");
                $stmt->bind_param('i',$idCategory);
                $stmt->execute();
                $res = $stmt->get_result();
                $i = 1;
            

                if($res->num_rows >0){
                    while($row = $res->fetch_assoc()){
                    $quntiteProduct = $conn->prepare("SELECT quantite as qnt FROM quantite_produits WHERE id_produit = ?");
                    $quntiteProduct->bind_param('i' , $row['id']);
                    $quntiteProduct->execute();
                    $result = $quntiteProduct->get_result()->fetch_assoc();
            echo "
                <div class='card_pies'>
                    <div class='category-image category-icon'>
                        <img src='../admin/uploads_produits/$row[image]' alt='Category Image' class='category-img'>
                    </div>
                    <div class='text'>
                        <h3>$row[nom]</h3>
                        
                    </div>
                    <div class='products-count'><span class='result_product'>$result[qnt]</span> products</div>
                    <a href='#' class='btn btn-success' >Ajouter</a>
                </div>
            ";}}else{
                echo "<b class='danger'>Aucun product</b>";
            }

                    } //end server request

            ?>
     <!-- start affichage card d'un category  -->


</div> <!-- end category card -->


</div> <!-- end content --> 
<?php include "footer.php" ?>