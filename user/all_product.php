<?php
define('SECURE_ACCESS', true);
include "header.php";


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
    timer: 3000 ,
    customClass:{
    popup: localStorage.getItem('mode') === 'dark' ? 'swal2-dark' : ''
    }
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
<div class="category-card" id="bodyCard" >
    
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
                <div class='card_pies card_pies_origin card_search'>
                    <div class='category-image category-icon'>
                        <img src='../admin/protection_image/image_produit.php?img=$row[image]' alt='Category Image' class='category-img'>
                    </div>
                    <div class='text'>
                        <h3 class='name' >$row[nom_produit]</h3>
                        
                    </div>
                    <div class='products-count'><span class='result_product'>$row[stock]</span> produits</div>
                    <a href='insert/insert_product.php?token=$token&id=$idP&email=$em&page=produit' class='btn btn-success' >Ajouter</a>
                </div>
            ";
        }
    }else{
                echo "<b class='danger'>Aucun product</b>";
            }

                    

            ?>
     <!-- start affichage card d'un category  -->
<input type="hidden" name="" id="token" value="<?= $token ?>">

</div> <!-- end category card -->
<?php
$stmt = $conn1->prepare("SELECT COUNT(*) AS counter FROM produit ;");
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
if($res){
    if($res['counter'] > 4){
        echo '
        <button style="background-color: var(--titleColor);  color: var(--white)" class="btn  voir_plus">Voir plus...</button>
        ';
    }
}
?>
</div> <!-- end content --> 

<!-- / ===== SYSTÈME DE RECHERCHE SIMPLE ===== -->
<script>
document.addEventListener("DOMContentLoaded", () => {

  const searchInput = document.getElementById("search");
  const tableBody = document.getElementById("bodyCard");


  const noResultMsg = document.createElement("div");
  noResultMsg.textContent = "Aucun résultat trouvé";
  noResultMsg.style.cssText = "display:none; text-align:center; margin-top:20px; font-weight:bold; color:red;";
  tableBody.append( noResultMsg);

  
  searchInput.addEventListener("input", function () {
    const searchTerm = this.value.toLowerCase().trim();
    const rows = tableBody.querySelectorAll(".card_search");

    let found = false;

    rows.forEach((row) => {
      const serviceName = row.querySelector(".name");

      if (serviceName) {
        const text = serviceName.textContent.toLowerCase();
        if (text.includes(searchTerm) || searchTerm === "") {
          row.style.display = "";
          found = true;
        } else {
          row.style.display = "none";
        }
      }
    });

    
    noResultMsg.style.display = found ? "none" : "block";
  });

  
  rows.forEach((row) => {
    row.addEventListener('mouseover', () => {
      row.style.transition = "transform 0.3s ease, box-shadow 0.3s ease";
      row.style.transform = 'translateY(-10px)';
      row.style.boxShadow = "0 10px 20px rgba(0,0,0,0.2)";
    });

    row.addEventListener('mouseout', () => {
      row.style.transform = 'translateY(0)';
      row.style.boxShadow = "0 4px 10px rgba(0,0,0,0.1)";
    });
  });
});
</script>

<!-- end search -->
<?php include "footer.php" ?>