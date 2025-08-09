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

<div class="category-card" id="bodyCard">
    
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
                $stockEmpty = 0;
                $stmt = $conn1->prepare("SELECT * FROM produit  where id_category = ? and stock != ?");
                $stmt->bind_param('ii',$idCategory,$stockEmpty);
                $stmt->execute();
                $res = $stmt->get_result();
                $i = 1;
            

                if($res->num_rows >0){
                    while($row = $res->fetch_assoc()){
                        $idP = encryptId($row['id_produit']);
                        $email = encryptId($_SESSION['email_employe']) ;
            echo "
                <div class='card_pies card_pies_origin card_search'>
                    <div class='category-image category-icon'>
                        <img src='../admin/protection_image/image_produit.php?img=$row[image]' alt='Category Image' class='category-img'>
                    </div>
                    <div class='text'>
                        <h3 class='name'>$row[nom_produit]</h3>
                        
                    </div>
                    <div class='products-count'><span class='result_product'>$row[stock]</span> products</div>
                    <a href='insert/insert_product.php?id=$idP&email=$email&product_category=1&idCAtegory=$idCategory&token=$token' class='btn btn-success' >Ajouter</a>
                </div>
            ";}}else{
                echo "<b class='danger'>Aucun product</b>";
            }

                    } //end server request

            ?>
     <!-- start affichage card d'un category  -->
               

</div> <!-- end category card -->


</div> <!-- end content --> 
<script src="js/search.php" ></script>
<script>
    let Accueil_product = document.querySelector(".Accueil_product");

    Accueil_product.classList.add('active');



    document.addEventListener("DOMContentLoaded", () => {
  // ===== SYSTÈME DE RECHERCHE SIMPLE =====
  console.log("hello");
  const searchInput = document.getElementById("search");
  const tableBody = document.getElementById("bodyCard");

      const noResultMsg = document.createElement("div");
    noResultMsg.textContent = "Aucun résultat trouvé";
    noResultMsg.style.cssText =
      "display:none; text-align:center; margin-top:20px; font-weight:bold; color:red;";
    tableBody.append(noResultMsg)

  searchInput.addEventListener("input", function () {
    const searchTerm = this.value.toLowerCase().trim();
    const rows = tableBody.querySelectorAll(".card_search");

    let  found = false;
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

    noResultMsg.style.display = found ? 'none' : 'block' ;
  });
  // end search
});

</script>
<?php include "footer.php" ?>
