<?php

define('SECURE_ACCESS', true);

include "header.php";


if(isset($_SESSION['empty_employe']) && $_SESSION['empty_employe']): ?>

<script>
    Swal.fire({
        icon: '<?= $_SESSION['empty_employe']['type']?>',
        title: '<?= $_SESSION['empty_employe']['titre']?>',
        text: '<?= $_SESSION['empty_employe']['msg']?>',
        confirmButtonText: 'OK'
    });
</script>
<?php
unset($_SESSION['empty_employe']);
endif; 
?>


            <!-- Content -->
            <div class="content">

                    <div class="search-container">
                        <i class="fa fa-search search-icon"></i>
                        <input type="search" name="search" id="search" placeholder="Search category ...">

                    </div>   
  
              <h3 style="margin-bottom: 2rem; color: var(--gray-900);">Catégories Disponibles <span>category</span></h3> 
             

                                
              <div class="category-card" id="bodyCard">
                              <!-- <div class="categories-grid"> -->

     <?php 

                $stmt = $conn1->prepare("SELECT * FROM category ORDER BY id_category");

                $stmt->execute();
                $res = $stmt->get_result();
                $i = 1;
            
                    $counter = 0;
                if($res->num_rows >0){
                    
                    while($row = $res->fetch_assoc()){
                        $zero = 0;
                    $chiffrement = encryptId($row['id_category']);
                    $quntiteProduct = $conn1->prepare("SELECT COUNT(*) as res FROM produit WHERE id_category = ? and stock != ?");
                    $quntiteProduct->bind_param('ii' , $row['id_category'],$zero);
                    $quntiteProduct->execute();
                    $result = $quntiteProduct->get_result()->fetch_assoc();
                    
                    $id =  encryptId( $row['id_category']); 
                    

                    if($result['res'] > 0){
                         $counter = 1;
            echo "
                <a href='product_category.php?id=$id' style='text-decoration: none' class='card_search'>
                <div class='card_pies'>
                    <div class='category-image category-icon'>
                        <img src='../admin/protection_image/categorie_protection.php?img=$row[image]' alt='Category Image' class='category-img'>
                    </div>
                    <div class='text'>
                        <h3 class='name' >$row[nom_category]</h3>
                        <p class='category-description name'>$row[description]</p>
                    </div>

                    <div class='products-count'><span class='result_product'>$result[res]</span> products</div>
                </div>
                </a>
            ";}
        } }

    
            if( $counter = 0){
                echo "hello";
            }
            ?>

        
                    </div>
                </div>


  <script src="js/search.js"></script> 

<?php 




include "footer.php";

?>