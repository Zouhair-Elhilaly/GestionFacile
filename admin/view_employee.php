<link rel="stylesheet" href="css/style.employe.php">
<?php  
// session_start();

// include "../include/config.php";
define('SECURE_ACCESS', true);
// include "functions/chiffre.php";
require_once 'header.php';

// // start affiche email send avec successfully
// if (isset($_SESSION['email_send_avec_success'])) {
//     if ($_SESSION['email_send_avec_success'] === true) {
//         ? >
//         <div id="myAlert" style="
//   background: #4de118ff;
//   color: #1a3a15ff;
//   padding: 10px; 
//   border-radius: 5px;
//   max-width: 400px;
//   text-align: center;
//   position: fixed;
//   top: 30px;
//   left: 50%;
//   transform: translateX(-50%);
//   display: none;
// ">
//     Email envoyé avec succès!
//   <br><br>
//   <button id="nextBtn">Suivant</button>
// </div>
// <?php
//     } else {
//          echo "<script>alert('Échec de l\'envoi de l\'email');</script>";
//     }
//     // Supprime la session pour éviter de réafficher le message
//     unset($_SESSION['email_send_avec_success']);
// }
// // end affiche email send avec successfully

// start affiche insert employe
if(isset($_SESSION['insert_employee'])){
    
if(!empty($_SESSION['insert_employee'])){
        $text = $_SESSION['insert_employee']['msg'];
        $type = $_SESSION['insert_employee']['type'];
        echo "<script>
        Swal.fire({
    icon: '$type',
    title: 'Notification',
    text: '$text',
    timer: 3000 
});</script>";
        unset($_SESSION['insert_employee']);
    }
}
// end insert employe

// start affiche delete employe
if(isset($_SESSION['delete_employee'])){
    if(!empty($_SESSION['delete_employee'])){
        $text = $_SESSION['delete_employee']['msg'];
        $type = $_SESSION['delete_employee']['type'];
        echo "<script>
        Swal.fire({
    icon: '$type',
    title: 'Notification',
    text: '$text',
    timer: 3000 
});</script>";
        unset($_SESSION['delete_employee']);
    }
}
// end delete employe
?>

<link rel="stylesheet" href="css/style_admin.php">
<link rel="stylesheet" href="css/style_search.php">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
<!-- recubur navbar -->

<?php require_once 'navbar.php' ?>

<!-- start design sapace employee -->
<div class="main_accueil"> 
    <div class="navbar_employe">
        <button id="btn_ajoute_employe" class="add_admin">
            Ajouter un nouvel employé
        </button>
        <div class="content">
            <div class="search-container">
                <i class="fa fa-search search-icon"></i>
                <input type="search" name="search" id="search" placeholder="Rechercher un employé...">
            </div> 
        </div> 
        
        <!-- Compteur de résultats -->
        <div class="search-results" id="searchResults" style="margin-top: 10px; color: #666; font-size: 14px;">
            <span id="resultCount"></span>
        </div>
    </div>

    <!-- start affichage admin exist dans ma base de donnee -->
    <div class="table-modern">
        <table id="employeeTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Nom</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                    <th>Genre</th>
                    <th>Nationalité</th>
                    <th>CNIE</th>
                    <th>Poste</th>
                    <th colspan="2">Modification</th>
                </tr>
            </thead>
            <tbody id="employeeTableBody">
                <?php 

                

                $stmt = $conn1->prepare("SELECT * FROM employé ORDER BY id");
                $stmt->execute();
                $res = $stmt->get_result();
                $i = 1;
                if($res->num_rows > 0){
                    while($row = $res->fetch_assoc()){
                       $idEm = encryptId($row['id']);
                       $postName = $conn1->prepare("SELECT * FROM post WHERE id_post = ? ");
                        $postName->bind_param('i',$row['id_post']);
                        $postName->execute();
                        $resP = $postName->get_result()->fetch_assoc();

                       echo " <tr>
                                <td id='id'>$i</td>
                                <td id='image'><img style='width: 120px ; height: 120px ' src='protection_image/protection_employe.php?img=$row[image]' alt='img'></td>
                                <td id='nom'>$row[nom] $row[prenom]</td>
                                <td id='telephone'>$row[Telephone]</td>
                                <td id='email'>$row[email]</td>
                                <td id='genre'>$row[genre]</td>
                                <td id='nationalite'>$row[nationalite]</td>
                                <td id='cnie'>$row[CNIE]</td>
                                <td id='post'>$resP[nom_post]</td>
                                <td id='modification'>
                                <a class='action-btn update-btn' href='update_employee.php?id=".encryptId($row['id']) ."&token=$token'>Modifier</a>
                                <a class='action-btn delete-btn' href='#' onclick=\"confirmDelete('$idEm','$token')\">Supprimer</a>
                                </td>
                            </tr>";
                        $i++;
                    } 
                }
                ?>
            </tbody>
        </table>
        
        <!-- Message quand aucun résultat -->
        <div id="noResults" style="display: none; text-align: center; padding: 40px; color: #666;">
            <i class="fa fa-search" style="font-size: 48px; margin-bottom: 20px; opacity: 0.3;"></i>
            <h3>Aucun employé trouvé</h3>
            <p>Essayez de modifier vos critères de recherche</p>
        </div>
    </div>

    <div class="overlay"></div>   <!-- background overlay -->

    <!-- start ajouter employer -->
    <div class="ajoute_employe_form display_none">
        <form action="insert/insert_employee.php" method="POST" enctype="multipart/form-data">
            <h3>Ajouter un employé</h3>
            <h3 id="close_form_employe">X</h3>

            <input type="text" name="nom" placeholder="Ecrire le nom" required>
            <input type="text" name="prenom" placeholder="Ecrire prenom" required>
            <input type="email" name="email" placeholder="Ecrire Email" required>
            <input type="text" name="nationalite" placeholder="Ecrire nationalite employee" required>
            <input type="text" name="genre" placeholder="Ecrire genre employee" required>
            <input type="text" name="ville" placeholder="Ecrire ville employee" required>
            <input type="text" name="CN" placeholder="Ecrire carte national employee" required>
            <input type="text" name="phone" placeholder="Ex : 0101910087" required>

            <!-- start affichage les post -->
            <select name="post" required>
              <option value="" style="opacity: 0.3" disabled selected>choisir post</option>
            <?php 
            $stmt = $conn1->prepare("SELECT * FROM post ORDER BY id_post");
            $stmt->execute();
            $res = $stmt->get_result();
            while($row = $res->fetch_assoc()) {
           ?>
                <option value="<?= $row['id_post'] ?>"><?= $row['nom_post'] ?></option>
           
              <?php 
             }
            ?>
             </select>
            <!-- end affichage les post -->

            <div class="label">
                <label for="file">Choisir un image</label>
                <input style="opacity:0; z-index: -1; display: none;" type="file" name="image" id="file" accept="image/*" />
            </div>
            <input type="submit" name="submit" value="Ajouter">
        </form>
    </div>
    <!-- end ajouter employer -->

    <!-- start modify form -->
    <?php
    if(isset($_SESSION['update_employe']) && $_SESSION['update_employe'] != ''){
    ?>
        <div class="update_employe_form">
            <form action="insert_update/insert_update_employee.php" method="POST" enctype="multipart/form-data">
                <h3>Modifier Employe</h3>
                <h3 id="close_form_update_employe" class="form_modifier_employe_btn">X</h3>
                
                <input type="text" name="nom" placeholder="Ecrire le nom" 
                       value="<?php echo isset($_SESSION['nom_update']) ? htmlspecialchars($_SESSION['nom_update']) : ''; ?>" required>
                
                <input type="text" name="prenom" placeholder="Ecrire prenom" 
                       value="<?php echo isset($_SESSION['prenom_update']) ? htmlspecialchars($_SESSION['prenom_update']) : ''; ?>" required>
                
                <input type="email" name="email" placeholder="Ecrire Email" 
                       value="<?php echo isset($_SESSION['email_update']) ? htmlspecialchars($_SESSION['email_update']) : ''; ?>" required>
                
                <input type="text" name="phone" placeholder="Ex : 0101910087" 
                       value="<?php echo isset($_SESSION['telephone_update']) ? htmlspecialchars($_SESSION['telephone_update']) : ''; ?>" required>

                <!-- start affichage les post -->
                <select name="post" required>
                <?php 
                $stmt = $conn1->prepare("SELECT * FROM post ORDER BY id_post");
                $stmt->execute();
                $res = $stmt->get_result();
                while($rows = $res->fetch_assoc()) {
                    $selected = '';
                    if(isset($_SESSION['post_update']) && $rows['id_post'] == $_SESSION['post_update']){
                        $selected = 'selected';
                    }
                ?>
                    <option value="<?= $rows['id_post'] ?>" <?= $selected ?>><?= htmlspecialchars($rows['nom_post']) ?></option>
                <?php 
                }
                ?>
                </select>
                <!-- end affichage les post -->

                <input type="text" name="CNIE" placeholder="Ecrire CNIE employee" 
                       value="<?php echo isset($_SESSION['CNIE_update']) ? htmlspecialchars($_SESSION['CNIE_update']) : ''; ?>" required>
                
                <input type="text" name="ville" placeholder="Ecrire ville employee" 
                       value="<?php echo isset($_SESSION['ville_update']) ? htmlspecialchars($_SESSION['ville_update']) : ''; ?>" required>
                
                <input type="text" name="nationalite" placeholder="Ecrire nationalite employee" 
                       value="<?php echo isset($_SESSION['nationalite_update']) ? htmlspecialchars($_SESSION['nationalite_update']) : ''; ?>" required>
                
                <input type="text" name="genre" placeholder="Ecrire genre employee" 
                       value="<?php echo isset($_SESSION['genre_update']) ? htmlspecialchars($_SESSION['genre_update']) : ''; ?>" required>
                
                <div class="label">
                    <label for="file_update">Choisir un image</label>
                    <input style="opacity:0; z-index: -1; display: none;" type="file" name="image" id="file_update" accept="image/*"> 
                </div>
                
                <input type="hidden" name="image_name" 
                       value="<?php echo isset($_SESSION['image_update']) ? htmlspecialchars($_SESSION['image_update']) : ''; ?>">
                
                <input type="hidden" name="id_num" 
                       value="<?php echo isset($_SESSION['id_update']) ? htmlspecialchars($_SESSION['id_update']) : ''; ?>">
                
                <input type="submit" name="submit" value="Modifier">
            </form>
        </div>
        
        <script>
        // Afficher automatiquement le formulaire de modification
        document.addEventListener('DOMContentLoaded', function() {
            const updateForm = document.querySelector('.update_employe_form');
            const overlay = document.querySelector('.overlay');
            
            if (updateForm) {
                updateForm.style.display = 'block';
                overlay.style.display = 'block';
            }
        });
        </script>
        
    <?php
        // Nettoyer les sessions après affichage
        unset($_SESSION['update_employe']);
        unset($_SESSION['nom_update']);
        unset($_SESSION['prenom_update']);
        unset($_SESSION['email_update']);
        unset($_SESSION['genre_update']);
        unset($_SESSION['telephone_update']); 
        unset($_SESSION['nationalite_update']);
        unset($_SESSION['ville_update']);
        unset($_SESSION['image_update']);
        unset($_SESSION['id_update']);
        unset($_SESSION['CNIE_update']);
        unset($_SESSION['post_update']);
    }
    ?>
    <!-- end modify form -->

</div>

<!-- Scripts JavaScript -->
<script src="js/employe.js"></script>

<script>
// ===== SYSTÈME DE RECHERCHE COMPLET =====

class EmployeeSearch {
    constructor() {
        this.searchInput = document.getElementById('search');
        this.tableBody = document.getElementById('employeeTableBody');
        this.resultCount = document.getElementById('resultCount');
        this.noResults = document.getElementById('noResults');
        this.table = document.querySelector('.table-modern table');
        this.allRowsData = []; // Cache des données
        
        this.init();
    }
    
    init() {
        // Sauvegarder les données initiales
        this.cacheTableData();
        
        // Compter les résultats initiaux
        this.updateResultCount();
        
        // Événements de recherche
        this.searchInput.addEventListener('input', (e) => {
            this.handleSearch(e.target.value);
        });
        
        // Recherche en temps réel (optionnel)
        this.searchInput.addEventListener('keyup', (e) => {
            if (e.key === 'Enter') {
                this.handleSearch(e.target.value);
            }
        });
        
        // Effacer la recherche
        this.searchInput.addEventListener('focus', () => {
            if (this.searchInput.value === '') {
                this.showAllRows();
            }
        });
    }
    
    // Sauvegarder les données du tableau
    cacheTableData() {
        const rows = this.tableBody.querySelectorAll('tr');
        this.allRowsData = Array.from(rows).map(row => ({
            element: row,
            text: this.getRowSearchText(row)
        }));
    }
    
    // Extraire le texte recherchable d'une ligne
    getRowSearchText(row) {
        const cells = row.querySelectorAll('td');
        let searchText = '';
        
        // Exclure la première cellule (ID) et les deux dernières (actions)
        for (let i = 1; i < cells.length - 2; i++) {
            const cell = cells[i];
            // Ignorer les images
            if (!cell.querySelector('img')) {
                searchText += ' ' + cell.textContent.toLowerCase();
            }
        }
        
        return searchText.trim();
    }
    
    // Fonction principale de recherche
    handleSearch(searchTerm) {
        const term = searchTerm.toLowerCase().trim();
        
        if (term === '') {
            this.showAllRows();
            return;
        }
        
        let visibleCount = 0;
        
        this.allRowsData.forEach(rowData => {
            const isMatch = this.isMatchingSearch(rowData.text, term);
            
            if (isMatch) {
                rowData.element.style.display = '';
                this.highlightSearchTerm(rowData.element, term);
                visibleCount++;
            } else {
                rowData.element.style.display = 'none';
            }
        });
        
        this.updateResultCount(visibleCount, term);
        this.toggleNoResultsMessage(visibleCount === 0);
    }
    
    // Vérifier si une ligne correspond à la recherche
    isMatchingSearch(rowText, searchTerm) {
        // Recherche simple : contient le terme
        if (rowText.includes(searchTerm)) {
            return true;
        }
        
        // Recherche avancée : mots séparés
        const searchWords = searchTerm.split(' ').filter(word => word.length > 0);
        return searchWords.every(word => rowText.includes(word));
    }
    
    // Surligner les termes trouvés (optionnel)
    highlightSearchTerm(row, searchTerm) {
        // Cette fonction peut être étendue pour surligner les résultats
        // Pour l'instant, on ajoute juste une classe CSS
        row.classList.add('search-highlight');
        
        // Supprimer le surlignage après un délai
        setTimeout(() => {
            row.classList.remove('search-highlight');
        }, 100);
    }
    
    // Afficher toutes les lignes
    showAllRows() {
        this.allRowsData.forEach(rowData => {
            rowData.element.style.display = '';
        });
        this.updateResultCount();
        this.toggleNoResultsMessage(false);
    }
    
    // Mettre à jour le compteur de résultats
    updateResultCount(count = null, searchTerm = '') {
        if (count === null) {
            count = this.allRowsData.length;
        }
        
        if (searchTerm) {
            this.resultCount.innerHTML = `
                <strong>${count}</strong> résultat${count !== 1 ? 's' : ''} 
                pour "<em>${searchTerm}</em>"
            `;
        } else {
            this.resultCount.innerHTML = `
                <strong>${count}</strong> employé${count !== 1 ? 's' : ''} au total
            `;
        }
    }
    
    // Afficher/masquer le message "Aucun résultat"
    toggleNoResultsMessage(show) {
        this.noResults.style.display = show ? 'block' : 'none';
        this.table.style.display = show ? 'none' : 'table';
    }
    
    // Fonction publique pour réinitialiser la recherche
    clearSearch() {
        this.searchInput.value = '';
        this.showAllRows();
    }
    
    // Fonction publique pour rafraîchir les données
    refresh() {
        this.cacheTableData();
        this.updateResultCount();
    }
}

// ===== GESTION DES FORMULAIRES =====
document.addEventListener('DOMContentLoaded', function() {
    // Créer l'instance de recherche
    window.employeeSearch = new EmployeeSearch();
    
    // Gestion du formulaire d'ajout
    const btnAjouteEmploye = document.getElementById('btn_ajoute_employe');
    const ajouteEmployeForm = document.querySelector('.ajoute_employe_form');
    const closeFormEmploye = document.getElementById('close_form_employe');
    const overlay = document.querySelector('.overlay');
    
    // Gestion du formulaire de modification
    const updateEmployeForm = document.querySelector('.update_employe_form');
    const closeFormUpdateEmploye = document.getElementById('close_form_update_employe');
    
    // Ouvrir le formulaire d'ajout
    if (btnAjouteEmploye) {
        btnAjouteEmploye.addEventListener('click', function() {
            ajouteEmployeForm.classList.remove('display_none');
            overlay.style.display = 'block';
        });
    }
    
    // Fermer le formulaire d'ajout
    if (closeFormEmploye) {
        closeFormEmploye.addEventListener('click', function() {
            ajouteEmployeForm.classList.add('display_none');
            overlay.style.display = 'none';
        });
    }
    
    // Fermer le formulaire de modification
    if (closeFormUpdateEmploye) {
        closeFormUpdateEmploye.addEventListener('click', function() {
            updateEmployeForm.style.display = 'none';
            overlay.style.display = 'none';
        });
    }
    
    // Fermer les formulaires en cliquant sur l'overlay
    if (overlay) {
        overlay.addEventListener('click', function() {
            if (ajouteEmployeForm) ajouteEmployeForm.classList.add('display_none');
            if (updateEmployeForm) updateEmployeForm.style.display = 'none';
            overlay.style.display = 'none';
        });
    }
    
    // Fonction globale pour réinitialiser la recherche
    window.clearEmployeeSearch = function() {
        window.employeeSearch.clearSearch();
    };
});

// ===== FONCTION DE SUPPRESSION =====
function confirmDelete(id,token) {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Voulez-vous vraiment supprimer cet employé ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "delete/delete_employee.php?id="+id+"&token="+token;
        }
    });
}

// ===== AMÉLIORATION : Recherche par colonne spécifique =====
function searchByColumn(columnIndex, searchTerm) {
    const rows = document.querySelectorAll('#employeeTableBody tr');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const cell = row.querySelectorAll('td')[columnIndex];
        const cellText = cell ? cell.textContent.toLowerCase() : '';
        
        if (cellText.includes(searchTerm.toLowerCase())) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    return visibleCount;
}

// ===== AMÉLIORATION : Tri des colonnes =====
function sortTable(columnIndex) {
    const table = document.getElementById('employeeTable');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    rows.sort((a, b) => {
        const aVal = a.querySelectorAll('td')[columnIndex].textContent.trim();
        const bVal = b.querySelectorAll('td')[columnIndex].textContent.trim();
        
        return aVal.localeCompare(bVal);
    });
    
    // Réinsérer les lignes triées
    rows.forEach(row => tbody.appendChild(row));
    
    // Rafraîchir la recherche
    if (window.employeeSearch) {
        window.employeeSearch.refresh();
    }
}
</script>

<!-- CSS pour les améliorations visuelles -->
<style>
/* Style pour le surlignage des résultats */
.search-highlight {
    background-color: #fff3cd !important;
    transition: background-color 0.3s ease;
}

/* Style pour le compteur de résultats */
.search-results {
    font-style: italic;
}

.action-btn{
    margin: 4px;
    width: 100px;
}

/* Style pour le message "Aucun résultat" */
#noResults {
    width: 100%;
    background-color: var(--secandary-color);
    border-radius: 8px;
    left: 50%;
    transform: translate(-50%);
    position: absolute;
    margin: 20px auto !important;
}

/* Amélioration du champ de recherche */
.search-container {
    position: relative;
}

.search-container input {
    transition: all 0.3s ease;
}

.search-container input:focus {
    padding: 0;
    scale: 1;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25) !important;
}
@media (max-width:706px){
    .navbar_employe{
        align-items: center;
        justify-content:center;
        /* position:relative */
    }

}

#resultCount{
    margin: 3px;
    color: var(--text-color) !important
}
/* Style pour les formulaires */
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 999;
    display: none;
}

.update_employe_form form{
    position: relative;
    background-color: var(--secondary-color);
    border-radius: 10px;
    width: 80%;
    text-align: center;
    padding: 10px 0;
    /* z-index: */
    max-width: 500px;
    box-shadow: var(--shadowForm);
}

.update_employe_form {
    position: fixed;
    top: 0;
    left: 0;
     backdrop-filter: blur(10px); /* applique le flou à l'arrière-plan */
    /* transform: translate(-50%,-50%); */
    width: 100%;
    height: 100%;
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}
td{
    color: var(--text-color)
}
</style>

<?php include "footer.php"?>