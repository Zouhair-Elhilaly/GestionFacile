

<?php  

session_start();
include "../include/config.php";
require_once 'header.php';
include 'functions/chiffre.php';  

 

 // start check insertion situation
if(isset($_SESSION['insert_category'] )){
  if($_SESSION['insert_category']  != ''){
    $var = $_SESSION['insert_category'] ;
      echo "<script>alert('$var')</script>";
    unset($_SESSION['insert_category'] );
  }
}



 
 // start check delete category
// if(isset($_SESSION['delete_category'] )){
//   if($_SESSION['delete_category']  != ''){
//     $var = $_SESSION['delete_category'] ;
//       echo "<script>alert('$var')</script>";
//     unset($_SESSION['delete_category'] );
//   }
// }

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


    <button onclick="display()" id="btn_click_add_category" class="add_category">
       Ajouter Category
     </button>
     <div id="search">
        <input type="search" id="search_view_category" name="category_search" placeholder="Search">
     </div>
   </div>


   <!-- Display existing categories from database -->
   <div class="category-card" >
      <div style="overflow-x:auto;">
  <table style="width:100%; border-collapse: collapse;">
    <thead>
      <tr style="background-color: #f2f2f2;">
        <th style="padding: 8px; text-align: left; border: 1px solid #ddd;">ID</th>
        <th style="padding: 8px; text-align: left; border: 1px solid #ddd;">Image</th>
        <th style="padding: 8px; text-align: left; border: 1px solid #ddd;">Nom</th>
        <th style="padding: 8px; text-align: left; border: 1px solid #ddd;">Description</th>
        <th style="padding: 8px; text-align: left; border: 1px solid #ddd;">Quantité</th>
        <th style="padding: 8px; text-align: left; border: 1px solid #ddd;" colspan="3">Modification</th>
      </tr>
    </thead>
    <tbody>
    <?php 
    
    $stmt = $conn1->prepare("SELECT * FROM category ORDER BY id_category");

    $stmt->execute();
    $res = $stmt->get_result();
    $i = 1;
   

    if($res->num_rows >0){
        while($row = $res->fetch_assoc()){
          $idCH = encryptId($row['id_category']);
          $quntiteProduct = $conn1->prepare("SELECT COUNT(*) as res FROM produit WHERE id_category = ?");
          $quntiteProduct->bind_param('i' , $row['id_category']);
          $quntiteProduct->execute();
          $result = $quntiteProduct->get_result()->fetch_assoc();
          echo "
     
    
      <tr>
        <td style='padding: 8px; border: 1px solid #ddd;'>$i</td>
        <td style='padding: 8px; border: 1px solid #ddd;' class='category-image'> <img src='image/image_category/$row[image]' class='category-img'></td>
        <td style='padding: 8px; border: 1px solid #ddd;'>$row[nom_category]</td>
        <td style='padding: 8px; border: 1px solid #ddd;'>$row[description]</td>
        <td style='padding: 8px; border: 1px solid #ddd;' class='products-count'>$result[res]</td>
        <td style='padding: 8px; border: 1px solid #ddd;'><a href='#' class='btn-edit-category' >View produits</a></td>
        <td style='padding: 8px; border: 1px solid #ddd;'><a href='update_category.php?id=$idCH' class='btn-view-products' >Modifier</a></td>
        <td style='padding: 8px; border: 1px solid #ddd;'><a href='view_category.php?id=$idCH' class='btn-delete-category' >Supprimer</a></td>
      </tr>
     
";}}

?>
      </tr>
    </tbody>
  </table>
</div>

<!-- end style tableau  -->
       
 <!-- Category Modal -->
   <div class="modal category-modal" id="categoryModal" style="display: none;">
     <div class="modal-content">
       <span class="close-modale">&times;</span>
       <h3 id="modalTitle">New Category</h3>
       <form action='insert_category.php' method="POST" id="categoryForm" enctype="multipart/form-data">
         <input type="hidden" id="categoryId" name="category_id">
         <div class="form-group">
           <label for="categoryName">Category Name</label>
           <input type="text" id="categoryName" name="category_name" >
         </div>
         <div class="form-group">
           <label for="categoryDescription">Description</label>
           <textarea id="categoryDescription" name="description"></textarea>
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
 
  // start affiche delted succcessfully

if(isset($_SESSION['delete_category'] )){
  if($_SESSION['delete_category'] != ''){
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
//  satrt affiche popup delete category 

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
    
<!--  satrt affiche popup delete category  -->

    <?php
    exit();
}


 
 
 ?>
 <!-- end popup ************************************************************************* -->

 



<!-- <script src="main_add.js"></script> -->



   <script src="category.js"></script>