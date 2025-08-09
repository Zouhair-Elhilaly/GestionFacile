<?php
define('SECURE_ACCESS', true);
require_once 'header.php'; ?>

<link rel="stylesheet" href="css/style_category.php">

<!-- Navigation bar -->
<?php include 'navbar.php' ?>

<div class="navbar_category">
    <button onclick="display()" id="btn_click_add_category" class="add_category">
       Ajouter Category
    </button>
    <div class="search-container">
        <i class="fa fa-search search-icon"></i>
        <input type="search" id="search" name="category_search" placeholder="Search">
    </div>
</div>

<!-- Display existing categories from database -->
<div class="category-card">
    <div style="overflow-x:auto;">
        <table class="table-responsive">
            <thead>
                <tr>
                    <th>ID</th>
                    <th id="image_space">Image</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Quantité</th>
                    <th colspan="2">Modification</th>
                </tr>
            </thead>
            <tbody id="serviceTableBody">
                <?php 
                $stmt = $conn1->prepare("SELECT * FROM category ORDER BY id_category");
                $stmt->execute();
                $res = $stmt->get_result();
                $stmt->close();
                $i = 1;

                if($res->num_rows > 0){
                    while($row = $res->fetch_assoc()){
                        $idCH = encryptId($row['id_category']);
                        $quntiteProduct = $conn1->prepare("SELECT COUNT(*) as res FROM produit WHERE id_category = ?");
                        $quntiteProduct->bind_param('i', $row['id_category']);
                        $quntiteProduct->execute();
                        $result = $quntiteProduct->get_result()->fetch_assoc();
                        $quntiteProduct->close();
                        
                        echo "
                        <tr>
                            <td id='id'>$i</td>
                            <td class='table-img'><img src='protection_image/categorie_protection.php?img={$row['image']}' class='category-img'></td>
                            <td id='name' class='service-name'>{$row['nom_category']}</td>
                            <td id='description'>{$row['description']}</td>
                            <td id='quantite' class='products-count'>{$result['res']}</td>
                            <td id='update'><a href='update_category.php?id=$idCH&token=$token' class='modify-btn'>Modifier</a></td>
                            <td id='delete'><a href='view_category.php?id=$idCH' class='delete-btn'>Supprimer</a></td>
                        </tr>";
                        $i++;
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Category Modal -->
<div class="modal category-modal" id="categoryModal" style="display: none;">
    <div class="modal-content">
        <span class="close-modale">&times;</span>
        <h3 id="modalTitle">Nouvelle Catégorie</h3>
        <form action='insert/insert_category.php' method="POST" id="categoryForm" enctype="multipart/form-data">
            <input type="hidden" id="categoryId" name="category_id">
            <div class="form-group">
                <label for="categoryName">Nom de catégorie</label>
                <input placeholder="Nom de catégorie" type="text" id="categoryName" name="category_name">
            </div>
            <div class="form-group">
                <label for="categoryDescription">Description</label>
                <textarea id="categoryDescription" placeholder="Entrez la description de la catégorie" name="description"></textarea>
            </div>
            <div class="label">
                <label for="file">Choisir un image</label>
                <input style="opacity:0; z-index: -1;" type="file" name="image" id="file" />
            </div>
            <button type="submit" class="btn-save">Save</button>
        </form>
    </div>
</div>

<!-- Scripts et Popups -->
<?php
// Variables pour contrôler l'affichage des popups
$showDeleteConfirmation = false;
$showDeleteSuccess = false;
$showInsertResult = false;

// Gestion popup de suppression
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $showDeleteConfirmation = true;
    $deleteId = $_GET['id'];
}

// Gestion popup de succès de suppression
if(isset($_SESSION['delete_category']) && $_SESSION['delete_category'] != ''){
    $showDeleteSuccess = true;
    $deleteMessage = $_SESSION['delete_category'];
    unset($_SESSION['delete_category']);
}

// Gestion popup d'insertion
if(isset($_SESSION['insert_category']) && $_SESSION['insert_category'] != ''){
    $showInsertResult = true;
    $insertMessage = $_SESSION['insert_category'];
    unset($_SESSION['insert_category']);
}
?>

<!-- Scripts JavaScript -->
<script>
// Fonction pour afficher le modal
function display() {
    console.log("Display function called");
    const categoryModal = document.querySelector("#categoryModal");
    if (categoryModal) {
        categoryModal.style.display = "block";
    } else {
        console.error("Modal not found");
    }
}

// Attendre que le DOM soit chargé
document.addEventListener("DOMContentLoaded", function () {
    console.log("DOM loaded");
    
    // Système de recherche
    const searchInput = document.getElementById("search");
    const tableBody = document.getElementById("serviceTableBody");
    
    if (searchInput && tableBody) {
        searchInput.addEventListener("input", function () {
            const searchTerm = this.value.toLowerCase().trim();
            const rows = tableBody.querySelectorAll("tr");
            
            rows.forEach((row) => {
                const serviceName = row.querySelector(".service-name");
                if (serviceName) {
                    const text = serviceName.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) || searchTerm === "" ? "" : "none";
                }
            });
        });
    }
    
    // Fermer la modal
    const closeBtn = document.querySelector(".close-modale");
    if (closeBtn) {
        closeBtn.addEventListener("click", () => {
            window.location = "view_category.php";
        });
    }
});

// Popups avec conditions PHP
<?php if ($showDeleteSuccess): ?>
Swal.fire({
    title: '<?= $deleteMessage['titre'] ?>',
    text: '<?= $deleteMessage['msg'] ?>',
    icon: '<?= $deleteMessage['type'] ?>',
    confirmButtonColor: '#3085d6',
    confirmButtonText: 'OK',
    timer: 3000,
    backdrop: 'rgba(0,0,0,0.4)'
}).then(() => {
    window.location.href = 'view_category.php';
});
<?php endif; ?>

<?php if ($showDeleteConfirmation): ?>
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
        popup: 'animated fadeIn faster'
    }
}).then((result) => {
    if (result.isConfirmed) {
        window.location.href = 'delete/delete_category.php?id=<?= urlencode($deleteId) ?>&token=<?= $token ?>';
    } else {
        window.location.href = 'view_category.php';
    }
});
<?php endif; ?>

<?php if ($showInsertResult): ?>
Swal.fire({
    title: '<?= $insertMessage['titre'] ?>',
    text: '<?= $insertMessage['msg'] ?>',
    icon: '<?= $insertMessage['type'] ?>',
    confirmButtonColor: '#3085d6',
    confirmButtonText: 'OK',
    timer: 3000,
    backdrop: 'rgba(0,0,0,0.4)'
});
<?php endif; ?>
</script>

<!-- Inclure le script externe après les scripts inline -->
<script src="js/category.js"></script>

<?php include "footer.php"; ?>