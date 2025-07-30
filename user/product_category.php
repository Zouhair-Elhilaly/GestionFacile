<?php include "header.php";


// check ajouter commande 
if(isset($_SESSION['insert_commande'])){
    if($_SESSION['insert_commande'] != ''){
        $type = $_SESSION['insert_commande']['type'];
        $msg = $_SESSION['insert_commande']['msg'];
        echo "<script>
        Swal.fire({
    icon: '$type',
    title: 'Opération réussie !',
    text: '$msg',
    timer: 3000 
});</script>";
        unset($_SESSION['insert_commande']);
    }
}
// end check ajouter commande 

?>
<div class="content">
      <div class="search-container">
        <i class="fa fa-search search-icon"></i>
        <input type="search" name="search" id="search" placeholder="Search category ...">
  </div>  

<div class="category-card" >
    
    <!-- start affichage card d'un category  -->
            <?php 

        if($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_SESSION['id_category'])){
            if(isset($_SESSION['id_category'])){
                if($_SESSION['id_category'] != ''){
                $idCategory = $_SESSION['id_category'];           
            } 
            unset($_SESSION['id_category']);  
            }
                if(isset($_GET['id'])){
                    $idCategory = filter_var(decryptId($_GET['id']) , FILTER_SANITIZE_NUMBER_INT);
                }
                $stmt = $conn1->prepare("SELECT * FROM produit  where id_category = ?");
                $stmt->bind_param('i',$idCategory);
                $stmt->execute();
                $res = $stmt->get_result();
                $i = 1;
            

                if($res->num_rows >0){
                    while($row = $res->fetch_assoc()){
                        $idP = encryptId($row['id_produit']);
                        $email = encryptId($_SESSION['email_employe']) ;
            echo "
                <div class='card_pies card_pies_origin'>
                    <div class='category-image category-icon'>
                        <img src='../admin/image/image_produit/$row[image]' alt='Category Image' class='category-img'>
                    </div>
                    <div class='text'>
                        <h3>$row[nom_produit]</h3>
                        
                    </div>
                    <div class='products-count'><span class='result_product'>$row[stock]</span> products</div>
                    <a href='insert/insert_product.php?id=$idP&email=$email&product_category=1&idCAtegory=$idCategory' class='btn btn-success' >Ajouter</a>
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
<script>
    let Accueil_product = document.querySelector(".Accueil_product");

    Accueil_product.classList.add('active');
</script>