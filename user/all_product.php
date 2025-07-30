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
<!-- <input type="hidden" name="" id="email_employe" value="<?= encryptId($email) ?>"> -->
<div class="category-card" >
    
    <!-- start affichage card d'un category  -->
            <?php 

                $em = encryptId($email);
                $quantiteActuelle = 0;
                $stmt = $conn1->prepare("SELECT * FROM produit   where stock != ? ORDER BY rand() limit 5");
                $stmt->bind_param('i',$quantiteActuelle);
                $stmt->execute();
                $res = $stmt->get_result();
                $i = 1;
            

                if($res->num_rows >0){
                    while($row = $res->fetch_assoc()){
                        // $idP = encryptId($row['id_produit']);
                        $idP = $row['id_produit'];

                        echo "
                <div class='card_pies card_pies_origin'>
                    <div class='category-image category-icon'>
                        <img src='../admin/image/image_produit/$row[image]' alt='Category Image' class='category-img'>
                    </div>
                    <div class='text'>
                        <h3>$row[nom_produit]</h3>
                        
                    </div>
                    <div class='products-count'><span class='result_product'>$row[stock]</span> products</div>
                    <a href='insert/insert_product.php?id=$idP&email=$em&page=produit' class='btn btn-success' >Ajouter</a>
                </div>
            ";
        }
    }else{
                echo "<b class='danger'>Aucun product</b>";
            }

                    

            ?>
     <!-- start affichage card d'un category  -->


</div> <!-- end category card -->
<?php
$stmt = $conn1->prepare("SELECT COUNT(*) AS counter FROM produit ;");
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
if($res){
    if($res['counter'] > 4){
        echo '
        <button class="btn btn-primary voir_plus">Voir plus...</button>
        ';
    }
}
?>
</div> <!-- end content --> 
<?php include "footer.php" ?>