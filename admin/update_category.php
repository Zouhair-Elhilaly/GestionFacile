<?php
// ✅ تأكد من بدء الجلسة مرة واحدة فقط
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ✅ قم بإدراج الملفات مرة واحدة فقط
include_once "../include/config.php";
require_once "functions/chiffre.php";

// ✅ تحقق من شروطك قبل أي مخرجات (قبل HTML أو include للـ header)
if (!isset($_GET['token']) || decryptId($_GET['token']) != 'token') {
    header("Location: error.php");
    exit();
}

?>



<?php

define('SECURE_ACCESS', true);
include 'header.php';


// Initialiser les variables
$categoryData = null;
$errorMessage = '';




if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])){
    try {
        $id = decryptId($_GET['id']);
        
        $sql = "SELECT * FROM category WHERE id_category = ?";
        $stmt = $conn1->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if($result->num_rows > 0){
            $categoryData = $result->fetch_assoc();
        } else {
            $errorMessage = "Catégorie non trouvée";
        }
        $stmt->close();
    } catch (Exception $e) {
        $errorMessage = "Erreur lors de la récupération des données";
    }
} else {
    $errorMessage = "ID de catégorie manquant";
}

// Inclure la navbar après avoir récupéré les données
require_once 'navbar.php';
?>

<link rel="stylesheet" href="css/style.php">
<link rel="stylesheet" href="css/style_category.php">

<style>
/* Styles spécifiques à la page de modification */
.update-category-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.category-modal {
    position: relative;
    z-index: 1001;
    width: 90%;
    max-width: 500px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background-color: var(--secondary-color);
    color: var(--text-color);
    border-radius: 20px;
    width: 100%;
    max-width: 500px;
    padding: 2rem;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--border-color);
    transition: all 0.3s ease;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: var(--text-color);
    font-weight: 500;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    background-color: var(--input-light);
    color: var(--text-color);
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-group textarea {
    resize: vertical;
    min-height: 100px;
}

.label {
    margin-bottom: 1.5rem;
}

.label label {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    color: white;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.label label:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.btn-save {
    width: 100%;
    padding: 0.75rem;
    background: var(--gradient);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.close-modale {
    position: absolute;
    top: 1rem;
    right: 1rem;
    font-size: 1.5rem;
    color: var(--text-light);
    cursor: pointer;
    width: 30px;
    height: 30px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.close-modale:hover {
    background-color: var(--border-color);
    color: var(--text-color);
}

.error-message {
    text-align: center;
    padding: 2rem;
    background-color: var(--secondary-color);
    color: var(--text-color);
    border-radius: 8px;
    border: 1px solid #ef4444;
}

@media (max-width: 717px) {
    .modal-content {
        margin: 1rem;
        padding: 1.5rem;
    }
    
    .category-modal {
        width: 95%;
    }
}

/* S'assurer que les styles de thème sont appliqués */
.navbar > div > a {
    color: var(--text-color) !important;
}

.navbar.active {
    color: var(--primary-color);
}
</style>

<div class="update-category-container">
    <?php if ($categoryData): ?>
        <!-- Category Modal -->
        <div class="modal category-modal modifier_category_form" id="categoryModal">
            <div class="modal-content">
                <span class="close-modale close_btn_form_category_update">&times;</span>
                <h3 id="modalTitle">Modifier Catégorie</h3>
                <form action='insert_update/insert_update_category.php' method="POST" id="categoryForm" enctype="multipart/form-data">
                    <input type="hidden" id="categoryId" name="category_id" value="<?php echo htmlspecialchars($categoryData['id_category']); ?>">
                    
                    <div class="form-group">
                        <label for="categoryName">Nom de catégorie</label>
                        <input type="text" 
                               placeholder="Nom de catégorie" 
                               id="categoryName" 
                               name="category_name" 
                               value="<?php echo htmlspecialchars($categoryData['nom_category']); ?>" 
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="categoryDescription">Description</label>
                        <textarea placeholder="Entrez la description de la catégorie" 
                                id="categoryDescription" 
                                name="category_description"><?php echo htmlspecialchars($categoryData['description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div style="display: flex;justify-content:center;flex-direction:column; align-items:center" class="label">
                        <label style="text-align:center" for="file">Choisir une image</label>
                        <input style="opacity:0; position:absolute; z-index: -1;" 
                               type="file" 
                               name="image" 
                               id="file" 
                               accept="image/*" />
                    </div>
                    
                    <input type="hidden" name="image_name" value="<?php echo htmlspecialchars($categoryData['image']); ?>">
                    
                    <button type="submit" class="btn-save">Mettre à jour</button>
                </form>
            </div>
        </div>
    <?php else: ?>
        <div class="modal category-modal">
            <div class="modal-content">
                <div class="error-message">
                    <h3>Erreur</h3>
                    <p><?php echo htmlspecialchars($errorMessage); ?></p>
                    <br>
                    <button onclick="window.location.href='view_category.php'" class="btn-save">
                        Retour à la liste
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
// Attendre que le DOM soit chargé
document.addEventListener("DOMContentLoaded", function() {
    console.log("Update category page loaded");
    
    // S'assurer que le thème est appliqué
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.body.setAttribute('data-theme', savedTheme);
    
    // Gestion de la fermeture du modal
    const closeBtn = document.querySelector(".close_btn_form_category_update");
    if (closeBtn) {
        closeBtn.addEventListener("click", function() {
            window.location.href = "view_category.php";
        });
    }
    
    // Gestion du clic en dehors du modal
    const container = document.querySelector(".update-category-container");
    if (container) {
        container.addEventListener("click", function(e) {
            if (e.target === container) {
                window.location.href = "view_category.php";
            }
        });
    }
    
    // Gestion de l'upload de fichier
    const fileInput = document.getElementById("file");
    const fileLabel = document.querySelector("label[for='file']");
    
    if (fileInput && fileLabel) {
        fileInput.addEventListener("change", function() {
            if (this.files && this.files[0]) {
                const fileName = this.files[0].name;
                fileLabel.textContent = `Image sélectionnée: ${fileName}`;
            } else {
                fileLabel.textContent = "Choisir une image";
            }
        });
    }
    
    // Validation du formulaire
    const form = document.getElementById("categoryForm");
    if (form) {
        form.addEventListener("submit", function(e) {
            const categoryName = document.getElementById("categoryName").value.trim();
            
            if (!categoryName) {
                e.preventDefault();
                Swal.fire({
                    title: 'Erreur',
                    text: 'Le nom de la catégorie est requis',
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
                return false;
            }
        });
    }
});

// Fonction pour fermer le modal (appelée par le bouton X)
function closeModal() {
    window.location.href = "view_category.php";
}

// Gestion des touches du clavier
document.addEventListener("keydown", function(e) {
    if (e.key === "Escape") {
        window.location.href = "view_category.php";
    }
});
</script>

<!-- Inclure les scripts externes après le DOM -->
<script src="js/category.js"></script>

<?php include "footer.php"; ?>