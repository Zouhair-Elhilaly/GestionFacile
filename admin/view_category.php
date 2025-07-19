

<?php  
include "../include/conn_db.php";
session_start();
require_once 'header.php';
 include 'functions/chiffre.php';  

 

// // start check insertion situation
// if(isset($_SESSION['error_category'])){
//   if(gettype($_SESSION['error_category']) == 'string'){
//     $var = $_SESSION['error_category'];
//       echo $var  ;
//     unset($_SESSION['error_category']);
//   }
// }
// // end check insertion situation
// ?>

<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="style.employe.css">
<link rel="stylesheet" href="style_category.css">

<!-- Navigation bar -->
<?php require_once 'navbar.php' ?>
<div class="overlay"></div> <!-- background overlay -->

<!-- Main employee space design -->
<div class="main_accueil">  



   <div class="navbar_category">
  <?php  // start check insertion situation
if(isset($_SESSION['error_category'])){
  if(gettype($_SESSION['error_category']) == 'string'){
    $var = $_SESSION['error_category'];
      echo "<h2>".$var."</h2>";
    unset($_SESSION['error_category']);
  }
}
   ?> 
    <button onclick="display()" id="btn_click_add_category" class="add_category">
       <a  href="#">Add Category</a>
     </button>
     <div id="search">
        <input type="search" id="search_view_category" name="category_search" placeholder="Search">
     </div>
   </div>

<!-- 4ir exemple_fetch_search -->
   <script src="../exemple_fetch_search.js"></script>

   <!-- Display existing categories from database -->
   <div class="category-card" >
    <?php 
    
    $stmt = $conn->prepare("SELECT * FROM category ORDER BY id");

    $stmt->execute();
    $res = $stmt->get_result();
    $i = 1;
   

    if($res->num_rows >0){
        while($row = $res->fetch_assoc()){
          $chiffrement = encryptId($row['id']);
          $quntiteProduct = $conn->prepare("SELECT COUNT(*) as res FROM produits WHERE category_id = ?");
          $quntiteProduct->bind_param('i' , $row['id']);
          $quntiteProduct->execute();
          $result = $quntiteProduct->get_result()->fetch_assoc();
          echo "
     
      <div class='category-header'>
          <div class='category-image'>
              <img src='uploads_category/$row[image]' alt='Category Image' class='category-img'>
          </div>
          <div class='text'>
              <h3>$row[name]</h3>
              <p class='category-description'>$row[description]</p>
          </div>
          <div class='category-actions'>
              <a class='btn-view-products' href='#'>View Products</a>
              <a class='btn-edit-category' href='update_category.php?id=$chiffrement'>Edit</a>
              <a class='btn-delete-category' href='view_category.php?id=$chiffrement''>Delete</a>
          </div>
          <div class='products-count'><span class='result_product'>$result[res]</span> products</div>
      </div>
";}}

?>
       
 <!-- Category Modal -->
   <div class="modal category-modal" id="categoryModal" style="display: none;">
     <div class="modal-content">
       <span class="close-modal">&times;</span>
       <h3 id="modalTitle">New Category</h3>
       <form action='insert_category.php' method="POST" id="categoryForm" enctype="multipart/form-data">
         <input type="hidden" id="categoryId" name="category_id">
         <div class="form-group">
           <label for="categoryName">Category Name</label>
           <input type="text" id="categoryName" name="category_name" >
         </div>
         <div class="form-group">
           <label for="categoryDescription">Description</label>
           <textarea id="categoryDescription" name="category_description"></textarea>
         </div>
         <div class="label">
            <label for="file">Choisir un image</label>
            <input style="opacity:0; z-index: -1;" type="file" name="image" id="file" />
        </div>
         <button type="submit" class="btn-save">Save</button>
       </form>
     </div>
   </div>

  
</div>
 </div>





 <!-- start popup ************************************************************************* -->

 <!-- CSS SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- JS SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


 <?php 
 
 

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = (int) decryptId($_GET['id']);
    ?>
    <script>
        Swal.fire({
            title: 'Confirmez la suppression',
            text: "Cette action est irréversible !",
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
                window.location.href = 'delete_category.php?id=<?= urlencode($_GET['id']) ?>';
            } else {
                window.location.href = 'view_category.php';
            }
        });
    </script>
    <?php
    exit();
}



// start affiche deeted succcessfully

if(isset($_SESSION['delete_category'] )){
  if($_SESSION['delete_category'] == 1){
    ?>

     <script>
        Swal.fire({
            title: 'Succès !',
            text: 'La suppression a été effectuée.',
            icon: 'success',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK',
            backdrop: 'rgba(0,0,0,0.4)'
        }).then(() => {
            // Optionnel : Recharger la page pour actualiser les données
            window.location.href = 'view_category.php';
        });
    </script>
<?php
    unset($_SESSION['delete_category'] );
  }
}
 
 
 ?>


 <!-- end popup ************************************************************************* -->

 



<script src="main_add.js"></script>


   <script >
    // Sélection des éléments
let closeBtn = document.querySelector(".close-modal");
let modal = document.querySelector(".category-modal");
let addCategoryBtn = document.getElementById("btn_click_add_category"); // Modifié pour utiliser getElementById

// Fermer la modal quand on clique sur ×
closeBtn.addEventListener("click", () => {
  modal.style.display = "none";
});

// Ouvrir la modal quand on clique sur "Add Category"
addCategoryBtn.addEventListener("click", (e) => {
  e.preventDefault(); // Empêche le comportement par défaut du lien
  modal.style.display = "block";
   modal.style.cssText = ` 
   z-index: 99;
   `;
});

// Optionnel: Fermer la modal si on clique en dehors
window.addEventListener("click", (e) => {
  if (e.target === modal) {
    modal.style.display = "none";
  }
});

   </script>