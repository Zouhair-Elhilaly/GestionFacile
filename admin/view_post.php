<?php
// session_start();
// include "../include/config.php";
// include "functions/chiffre.php";
define('SECURE_ACCESS', true);
require_once 'header.php';

// Gestion des messages de session
if(isset($_SESSION['post_message'])){
    if(!empty($_SESSION['post_message'])){
        $text = $_SESSION['post_message']['msg'];
        $type = $_SESSION['post_message']['type'];
        echo "<script>
        Swal.fire({
            icon: '$type',
            title: 'Notification',
            text: '$text',
            timer: 3000 
        });
        </script>";
        unset($_SESSION['post_message']);
    }
}
?>

<link rel="stylesheet" href="css/style_admin.php">
<link rel="stylesheet" href="css/style_search.php
">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php require_once 'navbar.php' ?>

<div class="main_accueil">
    <!-- Header avec bouton d'ajout et recherche -->
    <div class="navbar_employe">
        <button id="btn_ajoute_post" class="add_admin">
            Ajouter un nouveau poste
        </button>
        <div class="content">
            <div class="search-container">
                <i class="fa fa-search search-icon"></i>
                <input type="search" name="search" id="search" placeholder="Rechercher un poste...">
            </div>
        </div>
    </div>

    <!-- Tableau des postes -->
        <table   id="postTable" class="table-responsive">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom du Poste</th>
                    <th>Service</th>
                    <th class="action_modification">Actions</th>
                </tr>
            </thead>
            <tbody id="postTableBody">
                <?php 
                $stmt = $conn1->prepare("
                    SELECT p.*, s.nom_service 
                    FROM post p 
                    LEFT JOIN service s ON p.id_service = s.id_service 
                    ORDER BY p.id_post
                ");
                $stmt->execute();
                $res = $stmt->get_result();
                $i = 1;
                
                if($res->num_rows > 0){
                    while($row = $res->fetch_assoc()){
                        $idPost = encryptId($row['id_post']);
                        echo "<tr>
                                <td id='id' >$i</td>
                                <td id='name' class='post-name'>{$row['nom_post']}</td>
                                <td id='service'name class='service-name'>{$row['nom_service']}</td>
                                <td id='modification' class='action_modification' >
                                    <a class='action-btn update-btn' href='view_post.php?id={$idPost}'>
                                       Modifier
                                    </a>
                                
                                    <a class='action-btn delete-btn' href='#'
                                       onclick=\"confirmDelete('{$idPost}', '{$row['nom_post']}')\">
                                       Supprimer
                                    </a>
                                </td>
                              </tr>";
                        $i++;
                    }
                } else {
                    echo "<tr>
                            <td colspan='5' style='text-align: center; padding: 40px; color: #666;'>
                                Aucun poste trouvé
                                <br><small>Commencez par ajouter votre premier poste</small>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
 

    <!-- Overlay pour les formulaires -->
    <div class="overlay"></div>

    <!-- Formulaire d'ajout de poste -->
    <div class="ajoute_employe_form display_none" id="addPostForm">
        <form id="formAddPost" method="POST" action="insert/insert_post.php">
            <h3>Ajouter un nouveau poste</h3>
            <h3 id="close_form_add" class="close-btn">X</h3>
            
            <input type="hidden" name="action" value="add">
            
            <input type="text" name="nom_post" id="nom_post_add" 
                   placeholder="Nom du poste" required maxlength="100">
            
            <select name="id_service" id="id_service_add" required>
                <option value="">Sélectionner un service</option>
                <?php
                $stmt_services = $conn1->prepare("SELECT * FROM service ORDER BY nom_service");
                $stmt_services->execute();
                $services = $stmt_services->get_result();
                while($service = $services->fetch_assoc()){
                    echo "<option value='{$service['id_service']}'>{$service['nom_service']}</option>";
                }
                ?>
            </select>
            
            <input type="submit" value="Ajouter le poste">
        </form>
    </div>

    <?php 
    if(isset($_GET['id'])){
        $idPost = decryptId(strip_tags(trim($_GET['id'])));
        $stmt2 = $conn1->prepare("SELECT * FROM post WHERE id_post = ?");
        $stmt2->bind_param('i',$idPost);
        $stmt2->execute();
        $res = $stmt2->get_result();
        if($res->num_rows > 0){
            $row = $res->fetch_assoc();
?>
            <!-- Formulaire de modification de poste -->
    <div class="ajoute_employe_form2" id="editPostForm">
        <form id="formEditPost" method="POST" action="insert_update/update_post.php">
            <h3>Modifier le poste</h3>
            <h3 id="close_form_edit" class="close-btn">X</h3>
            
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id_post" id="edit_post_id" value="<?= $row['id_post']?>">
            
            <input type="text" name="nom_post" id="nom_post_edit" 
                   placeholder="Nom du poste" required maxlength="100" value="<?= $row['nom_post']?>">
            
            <select name="id_service" id="id_service_edit" required>
                <option value="">Sélectionner un service</option>
                <?php
                $stmt_services = $conn1->prepare("SELECT * FROM service ORDER BY nom_service");
                $stmt_services->execute();
                $services = $stmt_services->get_result();
                while($service = $services->fetch_assoc()){
                    $selected = ($service['id_service'] == $row['id_service']) ? 'selected' : '';
                    echo "<option value='{$service['id_service']}' $selected>{$service['nom_service']}</option>";
                }
                ?>
            </select>
            
            <input type="submit" value="Modifier le poste">
        </form>
    </div>

<?php
        }else{
             echo "<script>
        Swal.fire({
        icon: 'error',
        title: 'Notification',
        text: 'Erreur',
        timer: 3000 
    });</script>";
        }
    }
    ?>
    
</div>

<?php require_once 'footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    // Sélectionnez les formulaires et les boutons de fermeture
    const addForm = document.getElementById('addPostForm');
    const editForm = document.getElementById('editPostForm');
    const overlay = document.querySelector('.overlay');

    const closeAddBtn = document.getElementById('close_form_add');
    const closeEditBtn = document.getElementById('close_form_edit');

    // Fonction pour masquer le formulaire et l'overlay
    function hideForms() {
        addForm.classList.add('display_none');
        editForm.classList.add('display_none');
        overlay.classList.add('display_none');
    }

    let btn_ajoute_post = document.querySelector("#btn_ajoute_post");

    btn_ajoute_post.addEventListener('click' , () => {
        showForms();
    })

    function showForms() {
        addForm.classList.remove('display_none');
        overlay.classList.remove('display_none');
    }

    // Ajoutez un écouteur d'événement pour le bouton de fermeture du formulaire d'ajout
    if (closeAddBtn) {
        closeAddBtn.addEventListener('click', (e)=>{
            e.preventDefault();
            addForm.classList.add('display_none');
            setTimeout(() => {
                window.location.href = 'http://localhost/projet_stage/admin/view_post.php';
            }, 300);
        });
    }

    // Ajoutez un écouteur d'événement pour le bouton de fermeture du formulaire de modification
    if (closeEditBtn) {
        closeEditBtn.addEventListener('click', hideForms);
    }
});

function confirmDelete(id, nom) {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: `Voulez-vous vraiment supprimer le poste "${nom}" ?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            // Création du FormData pour envoyer les données
            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('id_post', id);
            
            // Envoie la requête AJAX pour la suppression
            fetch('delete/delete_post.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        'Supprimé!',
                        'Le poste a été supprimé.',
                        'success'
                    ).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire(
                        'Erreur!',
                        data.message || 'La suppression a échoué.',
                        'error'
                    );
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                Swal.fire(
                    'Erreur!',
                    'Une erreur est survenue lors de la suppression.',
                    'error'
                );
            });
        }
    });
}

// ===== SYSTÈME DE RECHERCHE SIMPLE =====
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const tableBody = document.getElementById('postTableBody');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        const rows = tableBody.querySelectorAll('tr');
        
        rows.forEach(row => {
            const postName = row.querySelector('.post-name');
            const serviceName = row.querySelector('.service-name');
            
            if (postName && serviceName) {
                const postText = postName.textContent.toLowerCase();
                const serviceText = serviceName.textContent.toLowerCase();
                
                if (postText.includes(searchTerm) || serviceText.includes(searchTerm) || searchTerm === '') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    });
});

</script>

<style>


@media (max-width: 768px){
  table,
  thead,
  tbody,
  th,
  td,
  tr {
    display: block;
  }

  body{
    width: 100%;
    display:flex;
    justify-content:center;
    align-items: center
  }
  td::after{
    position: absolute;
    left: 0;
    top:0;
    content: "0";
  }
  .table-img{
    right:0;
  }

  #id::after{
    content : "Id"
  }
   #name::after{
    content : "Nom"
  }
   #service::after{
    content : "Service"
  }
   #modification::after{
    content : "Modification"
  }
  

 
  
  thead tr {
    position: absolute;
    top: -9999px;
    left: -9999px;
  }




  




  tr {
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 10px;
   
  }

  td {
    border: none;
    border-bottom: 1px solid #eee;
    position: relative;
    padding-left: 50%;
    text-align: right;
  }

  td:before {
    content: attr(data-label);
    position: absolute;
    left: 10px;
    width: 45%;
    padding-right: 10px;
    font-weight: bold;
    text-align: left;
  }

  
}


h3{
     color: var(--text-color) !important;
}
    /* Style pour l'overlay */
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 999;
}


/* Style pour les formulaires d'ajout et de modification */
.ajoute_employe_form , .ajoute_employe_form2{
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    max-width: 400px;
    width: 90%;
    height: 50%;
    background-color: var(--secondary-color);
    padding: 20px;
    border-radius: 8px;
    box-shadow: var(--shadowForm) ;
    z-index: 1000;
    box-sizing: border-box;
    
}

.ajoute_employe_form h3 , .ajoute_employe_form2 h3{
    margin-top: 0;
    color: #333;
    text-align: center;
}

.ajoute_employe_form .close-btn , .ajoute_employe_form2 .close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 1.5rem;
    font-weight: bold;
    cursor: pointer;
    color: #888;
}

.ajoute_employe_form .close-btn:hover , .ajoute_employe_form2 .close-btn:hover{
    color: #333;
}

.ajoute_employe_form input[type="text"],
.ajoute_employe_form input[type="submit"],
.ajoute_employe_form select,
.ajoute_employe_form2 input[type="text"],
.ajoute_employe_form2 input[type="submit"],
.ajoute_employe_form2 select {
    width: 100%;
    padding: 10px;
    margin-top: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 1rem;
}

.ajoute_employe_form select,
.ajoute_employe_form2 select {
    cursor: pointer;
    background-color: white;
}

.ajoute_employe_form input[type="submit"],
.ajoute_employe_form2 input[type="submit"] {
    background: var(--gradient);
    color: var(--text-color)
    border: none;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.ajoute_employe_form input[type="submit"]:hover ,
.ajoute_employe_form2 input[type="submit"]:hover{
    background-color: #0056b3;
}

/* Utilitaires pour masquer/afficher les éléments */
.display_none {
    display: none;
}

/* Style pour la colonne service */
.service-name {
    font-weight: 500;
    color: var(--text-color);
}

/* .action_modification{
    width: 50%
}  */

</style>