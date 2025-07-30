<?php
include "header.php";
?>


            <!-- Content -->
            <div class="content">
                    <div class="search-container">
                        <i class="fa fa-search search-icon"></i>
                        <input type="search" name="search" id="search" placeholder="Search category ...">
                    </div>   
  
              <h3 style="margin-bottom: 2rem; color: var(--gray-900);">Catégories Disponibles <span>category</span></h3> 
             



                                
              <div class="category-card" >
                              <!-- <div class="categories-grid"> -->

     <?php 

                $stmt = $conn1->prepare("SELECT * FROM category ORDER BY id_category");

                $stmt->execute();
                $res = $stmt->get_result();
                $i = 1;
            

                if($res->num_rows >0){
                    while($row = $res->fetch_assoc()){
                    $chiffrement = encryptId($row['id_category']);
                    $quntiteProduct = $conn1->prepare("SELECT COUNT(*) as res FROM produit WHERE id_category = ?");
                    $quntiteProduct->bind_param('i' , $row['id_category']);
                    $quntiteProduct->execute();
                    $result = $quntiteProduct->get_result()->fetch_assoc();
                    
                    $id =  encryptId( $row['id_category']); 
                    

                    if($result['res'] > 0){
            echo "
                <a href='product_category.php?id=$id' style='text-decoration: none'>
                <div class='card_pies'>
                    <div class='category-image category-icon'>
                        <img src='../admin/image/image_category/$row[image]' alt='Category Image' class='category-img'>
                    </div>
                    <div class='text'>
                        <h3>$row[nom_category]</h3>
                        <p class='category-description'>$row[description]</p>
                    </div>

                    <div class='products-count'><span class='result_product'>$result[res]</span> products</div>
                </div>
                </a>
            ";}
        } }

            ?>

        
                    </div>
                </div>


               
          


<?php 

include "footer.php";

?>