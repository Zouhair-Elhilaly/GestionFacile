<?php 



session_start();

//end insert image  
require_once '../include/conn_db.php';

require_once 'header.php';

include 'functions/chiffre.php';

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $id =  decryptId( $_GET['id']) ;
    
    $sql = "SELECT * FROM category WHERE id  = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i'  , $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows > 0){
       if($row = $result->fetch_assoc()){
        //    $_SESSION['nom_update'] = $row['nom'];
        //    $_SESSION['prenom_update'] = $row['prenom'];
        //    $_SESSION['email_update'] = $row['email'];
        //    $_SESSION['telephone_update'] = $row['telephone'];
        //    $_SESSION['password_update'] = $row['mot_de_passe'];
        //    $_SESSION['image_update'] = $row['image'];

           
?>
       

        <style>
            .form_add_admin{
                display: block;
                top: 50%;
                left: 50%;
                transform: translate(-50% , -50%);
            }
            body{
                width: 100%;
                height: 1000px;
            }
        </style>
         <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="style.employe.css">
        <link rel="stylesheet" href="style_category.css">

        

'<?php 
        //    header("location: ../view_admin.php");

       }
    }



}else{
    echo "request not get";
}

require_once 'navbar.php';

?>


 <!-- Category Modal -->
        <div class="modal category-modal" id="categoryModal" style="display: block;">
            <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h3 id="modalTitle">Update Category</h3>
            <form action='insert_update_category.php' method="POST" id="categoryForm" enctype="multipart/form-data">
                <input type="hidden" id="categoryId" name="category_id" value="<?php echo $row['id']?>">
                <div class="form-group">
                <label for="categoryName">Category Name</label>
                <input type="text" id="categoryName" name="category_name"  value="<?php echo $row['name']?>">
                </div>
                <div class="form-group">
                <label for="categoryDescription">Description</label>
                <textarea  id="categoryDescription" name="category_description"   ><?= htmlspecialchars($row['description'] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8') ?></textarea>
                </div>
                <div class="label">
                    <label for="file">Choisir un image</label>
                    <input style="opacity:0; z-index: -1;" type="file" name="image" id="file" />
                </div>
                <input type="hidden" name="image_name" value="<?php echo $row['image']?>">
                <button type="submit" class="btn-save">Save</button>
            </form>
            </div>
        </div>


        <style>
            .category-modal{
                background-color: green;
                 width: 100%;
                 display:flex;
                 justify-content: center;
                 align-items: center;
            }
            .modal-content{
                background-color: bisque;
                width: fit-content;
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50% , -50%)
            }
        </style>