<?php
// session_start();
// include "../include/config.php";
// include "functions/chiffre.php";
define('SECURE_ACCESS', true);
require_once 'header.php';

// Gestion des messages de session
if(isset($_SESSION['service_message'])){
    if(!empty($_SESSION['service_message'])){
        $text = $_SESSION['service_message']['msg'];
        $type = $_SESSION['service_message']['type'];
        echo "<script>
        Swal.fire({
            icon: '$type',
            title: 'Notification',
            text: '$text',
            timer: 3000 
        });
        </script>";
        unset($_SESSION['service_message']);
    }
}
?>

<link rel="stylesheet" href="css/style_admin.php">
<link rel="stylesheet" href="css/style_search.php">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php require_once 'navbar.php' ?>

<div class="main_accueil">
    <!-- Header avec bouton d'ajout et recherche -->
    <div class="navbar_employe">
        <button id="btn_ajoute_service" class="add_admin">
            Ajouter un nouveau service
        </button>
        <div class="content">
            <div class="search-container">
                <i class="fa fa-search search-icon"></i>
                <input type="search" name="search" id="search" placeholder="Rechercher un service...">
            </div>
        </div>
    </div>

    <!-- Tableau des services -->
    <!-- <div class="table-modern"> -->
        <table id="serviceTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom du Service</th>
                    <th >Actions</th>
                </tr>
            </thead>
            <tbody id="serviceTableBody">
                <?php 
                $stmt = $conn1->prepare("SELECT * FROM service ORDER BY id_service");
                $stmt->execute();
                $res = $stmt->get_result();
                $i = 1;
                
                if($res->num_rows > 0){
                    while($row = $res->fetch_assoc()){
                        $idService = encryptId($row['id_service']);
                        echo "<tr>
                                <td id='id' >$i</td>
                                <td id='name' class='service-name'>{$row['nom_service']}</td>
                                <td id='modification' >
                                    <a class='action-btn update-btn' href='view_service.php?id={$idService}'>
                                       Modifier
                                    </a>
                               
                                    <a class='action-btn delete-btn' href='#'
                                       onclick=\"confirmDelete('{$idService}', '{$row['nom_service']}')\">
                                       Supprimer
                                    </a>
                                </td>
                              </tr>";
                        $i++;
                    }
                } else {
                    echo "<tr>
                            <td colspan='4' style='text-align: center; padding: 40px; color: #666;'>
                                Aucun service trouvé
                                <br><small>Commencez par ajouter votre premier service</small>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    





    <!-- Overlay pour les formulaires -->
    <div class="overlay"></div>

    <!-- Formulaire d'ajout de service -->
    <div class="ajoute_employe_form display_none" id="addServiceForm">
        <form id="formAddService" method="POST" action="insert/insert_service.php">
            <h3>Ajouter un nouveau service</h3>
            <h3 id="close_form_add" class="close-btn">X</h3>
            
            <input type="text" name="nom_service" id="nom_service_add" 
                   placeholder="Nom du service" required maxlength="100">
            
            <input type="submit" value="Ajouter le service">
        </form>
    </div>

    <?php 
    if(isset($_GET['id'])){
        $idServ = decryptId(strip_tags(trim($_GET['id'])));
        $stmt2 = $conn1->prepare("SELECT * FROM service WHERE id_service = ?");
        $stmt2->bind_param('i',$idServ);
        $stmt2->execute();
        $res = $stmt2->get_result();
        if($res->num_rows > 0){
            $row = $res->fetch_assoc();

?>
            <!-- Formulaire de modification de service -->
    <div class="ajoute_employe_form2" id="editServiceForm">
        <form id="formEditService" method="POST" action="insert_update/update_service.php">
            <h3>Modifier le service</h3>
            <h3 id="close_form_edit" class="close-btn">X</h3>
            
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id_service" id="edit_service_id" value="<?= $row['id_service']?>">
            
            <input type="text" name="nom_service" id="nom_service_edit" 
                   placeholder="Nom du service" required maxlength="100" value="<?= $row['nom_service']?>">
            
            <input type="submit" value="Modifier le service">
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










<script>
    document.addEventListener('DOMContentLoaded', () => {


        // ===== SYSTÈME DE RECHERCHE SIMPLE =====

    const searchInput = document.getElementById('search');
    const tableBody = document.getElementById('serviceTableBody');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        const rows = tableBody.querySelectorAll('tr');
        
        rows.forEach(row => {
            const serviceName = row.querySelector('.service-name');
            if (serviceName) {
                const text = serviceName.textContent.toLowerCase();
                if (text.includes(searchTerm) || searchTerm === '') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    });
// end search 





    // Sélectionnez les formulaires et les boutons de fermeture
    const addForm = document.getElementById('addServiceForm');
    const editForm = document.getElementById('editServiceForm');
    const overlay = document.querySelector('.overlay');

    const closeAddBtn = document.getElementById('close_form_add');
    const closeEditBtn = document.getElementById('close_form_edit');

    // Fonction pour masquer le formulaire et l'overlay
    function hideForms() {
        addForm.classList.add('display_none');
        editForm.classList.add('display_none');
        overlay.classList.add('display_none');
    }

    let btn_ajoute_service = document.querySelector("#btn_ajoute_service");

    btn_ajoute_service.addEventListener('click' , () => {
        showForms();
    })

    function showForms() {
        addForm.classList.remove('display_none');
        // editForm.classList.remove('display_none');
        overlay.classList.remove('display_none');
    }


    // Ajoutez un écouteur d'événement pour le bouton de fermeture du formulaire d'ajout
    if (closeAddBtn) {
        closeAddBtn.addEventListener('click', (e)=>{
            e.preventDefault();

             addForm.classList.add('display_none');

            setTimeout(() => {
            window.location.href = 'http://localhost/projet_stage/admin/view_service.php';
        }, 300);
        });
    }

    // Ajoutez un écouteur d'événement pour le bouton de fermeture du formulaire de modification
    if (closeEditBtn) {
        closeEditBtn.addEventListener('click', hideForms);
    }



    
});


editForm.classList.remove('display_none');

 // Ajoutez un écouteur d'événement pour le bouton de fermeture du formulaire d'ajout
    if (closeAddBtn) {
        closeAddBtn.addEventListener('click', (e)=>{
            e.preventDefault();

             editForm.classList.add('display_none');

            setTimeout(() => {
            window.location.href = 'http://localhost/projet_stage/admin/view_service.php';
        }, 300);
        });
    }


function confirmDelete(id, nom) {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: `Voulez-vous vraiment supprimer le service "${nom}" ?`,
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
            formData.append('id_service', id);
            
            // Envoie la requête AJAX pour la suppression
            fetch('delete/delete_service.php', {
                method: 'POST',
                body: formData // Utilisation de FormData au lieu de body string
            })
            .then(response => {
                // Vérifier si la réponse est OK
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        'Supprimé!',
                        'Le service a été supprimé.',
                        'success'
                    ).then(() => {
                        window.location.reload(); // Rafraîchit la page
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
                console.error('Erreur:', error); // Log l'erreur pour debug
                Swal.fire(
                    'Erreur!',
                    'Une erreur est survenue lors de la suppression.',
                    'error'
                );
            });
        }
    });
}




// // ===== SYSTÈME DE RECHERCHE SIMPLE =====
// document.addEventListener('DOMContentLoaded', function() {
//     const searchInput = document.getElementById('search');
//     const tableBody = document.getElementById('serviceTableBody');
    
//     searchInput.addEventListener('input', function() {
//         const searchTerm = this.value.toLowerCase().trim();
//         const rows = tableBody.querySelectorAll('tr');
        
//         rows.forEach(row => {
//             const serviceName = row.querySelector('.service-name');
//             if (serviceName) {
//                 const text = serviceName.textContent.toLowerCase();
//                 if (text.includes(searchTerm) || searchTerm === '') {
//                     row.style.display = '';
//                 } else {
//                     row.style.display = 'none';
//                 }
//             }
//         });
//     });
// });
</script>







<style>
    /* Style pour l'overlay */
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Arrière-plan semi-transparent */
    z-index: 999; /* Assure que l'overlay est au-dessus du reste */
    /* display: block; Masqué par défaut */
}

/* Style pour les formulaires d'ajout et de modification */
.ajoute_employe_form , .ajoute_employe_form2{
    position: fixed; /* Position fixe par rapport à la fenêtre */
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%); /* Centre parfaitement l'élément */
    max-width: 600px; /* Largeur maximale */
    width: 90%; /* Prend 90% de la largeur disponible sur les petits écrans */
    background-color: var(--secondary-color);
    padding: 20px;
    border-radius: 8px;
    /* display: block; */
    box-shadow: var(--shadowForm);
    z-index: 1000; /* Assure que le formulaire est au-dessus de l'overlay */
    box-sizing: border-box; /* Inclut le padding dans la largeur et la hauteur */
}

@media (width: 820px ){
    .main_accueil{
       width: 70%;

    }
    .navbar_employe{
        justify-content: center;
    }
     .navbar_employe .add_admin ,  .navbar_employe #search{
        width: 100%;
     }
}

.ajoute_employe_form h3 , .ajoute_employe_form2 h3{
    margin-top: 0;
    color: var(--titleColor);
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
.ajoute_employe_form2 input[type="text"],
.ajoute_employe_form2 input[type="submit"] {
    width: 100%;
    padding: 10px;
    margin-top: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 1rem;
}

.ajoute_employe_form input[type="submit"],
.ajoute_employe_form2 input[type="submit"] {
    background-color: #5b2687ff;
    color: white;
    border: none;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.ajoute_employe_form input[type="submit"]:hover ,

.ajoute_employe_form2 input[type="submit"]:hover{
    background-color: #5317c2ff;
}

table{
    width: 90% !important;
    margin: auto;
}

.navbar_employe{
     width: 90% !important;
    margin: 10px auto;
}


@media (max-width: 802px){
    table{
    width: 100% !important;
    margin: auto;
}

}

@media (max-width: 800px){
    .navbar_employe{
    display: flex;
    justify-content: center !important;
    }
}

@media (max-width: 768px){
    td::after{
        position: absolute;
        content: '0';
        left: 0;

    }

    #id::after{
        content: "Id"
    }
    #name::after{
        content: "Nom"
    }
    #modification::after{
        content: "Modification"
    }
}

/* Utilitaires pour masquer/afficher les éléments */
.display_none {
    display: none;
}
</style>


<?php require_once 'footer.php'; ?>

