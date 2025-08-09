<?php 
define('SECURE_ACCESS', true);

include 'header.php';
$_SESSION['idEMploye']; //recupurer apres login












?>

<!-- satrt hidden card dans page commande  -->
<style>
    .show_counter_commande{
        display: none;
    }
</style>
<!-- end hidden card dans page commande  -->
 
<div class="content">

<div class="search-container">
        <i class="fa fa-search search-icon"></i>
        <input type="search" name="search" id="search" placeholder="Search commande ...">
  </div>  

 <!-- Page Commandes -->
                <div style="display:block" id="ordersPage" class="page">
                    <h3 style="margin-bottom: 2rem; color: var(--titleColor);">Mes Commandes</h3>
                    <div class="orders-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>N° Commande</th>
                                    <th>Date</th>
                                    <th>Produits</th>
                                    <th>Quantite</th>
                                    <th>Statut</th>
                                    <th colspan="2">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="ordersTableBody" >


                            <!-- start affichage commande -->
                             <?php 
                             
                             
                                $commande = $conn1->prepare("SELECT * FROM commande WHERE id = ? order by id_command desc");
                                $commande->bind_param('s', $_SESSION['idEMploye']);
                                $commande->execute();
                                $rowO = $commande->get_result();
                                
                                 while($rowC = $rowO->fetch_assoc()){
                                
                                    $idProduitDetailler = $conn1->prepare("SELECT * from détailler NATURAL JOIN commande WHERE id_command = ?");
                                    $idProduitDetailler->bind_param('i',$rowC['id_command']);
                                    $idProduitDetailler->execute();
                                    $resIdProduit = $idProduitDetailler->get_result()->fetch_assoc();

                                $PR = $conn1->prepare("SELECT * FROM produit WHERE id_produit = ?");
                                $PR->bind_param('s', $resIdProduit['id_produit']);
                                $PR->execute();
                                $rowP = $PR->get_result()->fetch_assoc();
                                $PR->close();

                                $idC = encryptId($rowC['id_command'])
                            
                             ?>

                                <tr  class="card_search" <?php if($rowC['status'] == 'Rejetée'){?> style="text-decoration: line-through" <?php }?>>
                                    <td ><?php echo $rowC['id_command']?></td>
                                    <td><?= $rowC['date']?></td>
                                    <td class="name" ><?= $rowP['nom_produit']?></td>
                                <?php 
                                $disabled = '';
                                 if($rowC['status'] == 'Confirmée' || $rowC['status'] == 'Rejetée'){
                                    $disabled = 'disabled';
                                 }
                                ?>
                                    <td><input class="input_commande" type="number" oninput="this.value = this.value.replace(/[^0-9]/g,'')" <?= $disabled  ?> value="<?= $resIdProduit['quantité']?>"> <input class="input_hidden" type="hidden" value="<?php echo $rowC['id_command'] ?>"></td>
                                    <input type="hidden" class="id_product_hidden" value="<?= $resIdProduit['id_produit'] ?>">
                                    
                                    <input type="hidden" id="token" value="'<?= $token?>'">
                                    
                                    <td <?php if($rowC['status'] == 'Rejetée' || $rowC['status'] == 'Confirmée' ){ ?>colspan="2" style="text-align: center;;" <?php }?>>
                                    <?php if($rowC['status'] == 'En attente'){?>
                                        
                                    <span class="status-badge status-pending">En attente</span>
                                    <?php }else if($rowC['status'] == 'Rejetée'){?>
                                                <span class="status-badge status-cancelled">Rejetée</span>
                                        <?php }else{?>
                                                <span class="status-badge status-confirmed">Confirmée</span>
                                        <?php }?>
                                        </td>

                                    <td <?php if($rowC['status'] == 'Rejetée' || $rowC['status'] == 'Confirmée' ){ ?> style="display:none"<?php }?>  >
                                        <a href="commande_employe.php?id=<?= $idC ?>&token=<?=$token?>" class="deleteCammande1">
                                    
                                            
                                            <button class="btn status-cancelled btn-outline" style="padding: 0.25rem 0.75rem; font-size: 0.8rem;">
                                                Annuler
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                                <?php 
                             }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

<script src="js/commande.js"></script>



<style>
    /* CSS spécifique pour le toggle - À ajouter dans votre CSS principal */

/* S'assurer que le toggle est toujours visible et fonctionnel */
.toggle {
    position: absolute;
    right: -10px;
    top: 20px;
    display: none; /* Caché par défaut sur desktop */
    justify-content: center;
    align-items: center;
    width: 24px;
    height: 24px;
    text-align: center;
    border-radius: 50%;
    cursor: pointer;
    color: white;
    background: linear-gradient(to right, #cf4aea, #50a0ce);
    font-family: 'VotrePolice', sans-serif;
    font-weight: bold;
    font-size: 12px;
    z-index: 9999;
    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    transition: all 0.3s ease;
    user-select: none;
}

.toggle:hover {
    transform: scale(1.1);
    box-shadow: 0 3px 8px rgba(0,0,0,0.4);
}

/* Afficher le toggle sur mobile */
@media (max-width: 768px) {
    .toggle {
        display: flex !important;
        right: -12px;
    }
}

/* États de la sidebar plus spécifiques */
.sidebar {
    width: 280px;
    background: var(--white);
    border-right: 1px solid var(--gray-200);
    box-shadow: var(--shadow);
    position: fixed;
    height: 100vh;
    overflow-y: auto;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1000;
    left: 0;
}

/* Desktop collapsed */
.sidebar.collapsed {
    width: 80px !important;
}

/* Mobile states */
@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
    }
    
    .sidebar.mobile-open {
        transform: translateX(0);
    }
    
    .sidebar.collapsed2 {
        transform: translateX(-100%);
        width: 280px; /* Garder la largeur normale */
    }
    
    /* Éviter les conflits de classes sur mobile */
    .sidebar.collapsed {
        width: 280px !important;
    }
}

/* Main content adjustments */
.main-content {
    flex: 1;
    margin-left: 280px;
    transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.sidebar.collapsed + .main-content {
    margin-left: 80px;
}

@media (max-width: 768px) {
    .main-content {
        margin-left: 0 !important;
    }
}

/* S'assurer que le contenu de la page ne soit pas affecté */
.content {
    padding: 2rem;
    position: relative;
    z-index: 1;
}

/* Styles spécifiques pour la page des commandes */
.orders-table {
    background: var(--white);
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: var(--shadow);
    margin-top: 1rem;
}
</style>

<script>
    let nav_text = document.querySelector(".nav-text");
    let menu_toggle = document.querySelector(".menu-toggle");
    menu_toggle.addEventListener('click' , ()=>{
        if(sidebar.classList.contains('mobile-open')){
            sidebar.classList.remove('mobile-open');
             sidebar.classList.add('collapsed2');
             nav_text.style.display = 'block !important';
             nav_text.style.color = 'white';
        }else{
            sidebar.classList.add('mobile-open');
             sidebar.classList.remove('collapsed2');
             nav_text.style.display = 'block !important';
             nav_text.style.color = 'white';
        }
    })
    </script>



<!-- start search -->
<!-- <script>
    //  search strat
document.addEventListener("DOMContentLoaded", () => {
  // ===== SYSTÈME DE RECHERCHE SIMPLE =====
  console.log("hello");
  const searchInput = document.getElementById("search");
  const tableBody = document.getElementById("ordersTableBody");

  searchInput.addEventListener("input", function () {
    const searchTerm = this.value.toLowerCase().trim();
    const rows = tableBody.querySelectorAll(".card_search");

    rows.forEach((row) => {
      const serviceName = row.querySelector(".name");
      if (serviceName) {
        const text = serviceName.textContent.toLowerCase();
        if (text.includes(searchTerm) || searchTerm === "") {
          row.style.display = "";
        } else {
          row.style.display = "none";
        }
      }
    });
  });
  // end search
});

</script>
 -->

<!-- <script>
    
// Toggle sidebar function améliorée
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const toggle = document.querySelector(".toggle");
    
    if (window.innerWidth <= 768) {
        // Mode mobile
        if (sidebar.classList.contains("mobile-open")) {
            sidebar.classList.remove("mobile-open");
            sidebar.classList.add("collapsed2");
            toggle.textContent = '>';
        } else {
            sidebar.classList.add("mobile-open");
            sidebar.classList.remove("collapsed2", "collapsed");
            toggle.textContent = '<';
        }
    } else {
        // Mode desktop
        if (sidebar.classList.contains("collapsed")) {
            sidebar.classList.remove("collapsed");
            toggle.textContent = '<';
        } else {
            sidebar.classList.add("collapsed");
            toggle.textContent = '>';
        }
        // Supprimer les classes mobiles en mode desktop
        sidebar.classList.remove("collapsed2", "mobile-open");
    }
}

// Toggle navbar amélioré (remplace votre code actuel)
// let toggle = document.querySelector(".toggle");

toggle.addEventListener('click', () => {
    const sidebar = document.getElementById("sidebar");
    
    if (window.innerWidth <= 768) {
        // Mode mobile
        if (sidebar.classList.contains("mobile-open")) {
            sidebar.classList.remove("mobile-open");
            sidebar.classList.add("collapsed2");
            toggle.textContent = '>';
        } else {
            sidebar.classList.add("mobile-open");
            sidebar.classList.remove("collapsed2", "collapsed");
            toggle.textContent = '<';
        }
    } else {
        // Mode desktop
        sidebar.classList.toggle("collapsed");
        sidebar.classList.remove("collapsed2", "mobile-open");
        
        if (sidebar.classList.contains("collapsed")) {
            toggle.textContent = '>';
        } else {
            toggle.textContent = '<';
        }
    }
});

// Gestion du redimensionnement de la fenêtre
window.addEventListener('resize', () => {
    const sidebar = document.getElementById("sidebar");
    const toggle = document.querySelector(".toggle");
    
    if (window.innerWidth > 768) {
        // Passage en mode desktop
        sidebar.classList.remove("mobile-open", "collapsed2");
        if (sidebar.classList.contains("collapsed")) {
            toggle.textContent = '>';
        } else {
            toggle.textContent = '<';
        }
    } else {
        // Passage en mode mobile
        sidebar.classList.remove("collapsed");
        if (!sidebar.classList.contains("mobile-open")) {
            sidebar.classList.add("collapsed2");
            toggle.textContent = '>';
        } else {
            toggle.textContent = '<';
        }
    }
});

// Initialisation au chargement de la page
window.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById("sidebar");
    const toggle = document.querySelector(".toggle");
    
    // État initial basé sur la taille d'écran
    if (window.innerWidth <= 768) {
        sidebar.classList.add("collapsed2");
        toggle.textContent = '>';
    } else {
        toggle.textContent = '<';
    }
})
</script> -->


<script>
// Attendre que la page soit complètement chargée
document.addEventListener("DOMContentLoaded", function() {
    
    // ===== RÉINITIALISER LE TOGGLE SIDEBAR =====
    // Réinitialiser le toggle au cas où il y aurait des conflits
    setTimeout(() => {
        if (typeof initSidebarToggle === 'function') {
            initSidebarToggle();
        }
    }, 200);

    // ===== SYSTÈME DE RECHERCHE =====
    console.log("Page commande chargée");
    const searchInput = document.getElementById("search");
    const tableBody = document.getElementById("ordersTableBody");

    if (searchInput && tableBody) {
        searchInput.addEventListener("input", function () {
            const searchTerm = this.value.toLowerCase().trim();
            const rows = tableBody.querySelectorAll(".card_search");

            rows.forEach((row) => {
                const serviceName = row.querySelector(".name");
                if (serviceName) {
                    const text = serviceName.textContent.toLowerCase();
                    if (text.includes(searchTerm) || searchTerm === "") {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                }
            });
        });
    }

    // ===== GESTION DES INPUTS COMMANDE =====
    const commandeInputs = document.querySelectorAll('.input_commande');
    commandeInputs.forEach(input => {
        input.addEventListener('input', function() {
            // Votre logique pour les inputs de commande
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });
});

// Fonction spécifique pour cette page si nécessaire
function handleCommandeActions() {
    // Logique spécifique aux commandes
    console.log("Actions commandes initialisées");
}

// Appeler les fonctions spécifiques après le chargement
window.addEventListener('load', function() {
    handleCommandeActions();
});
</script>


<?php




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
                        // popup: 'animated fadeIn faster' // Animation (optionnelle)
                        popup: localStorage.getItem('mode') === 'dark' ? 'swal2-dark' : ''
                    }


                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'api/delete_commande.php?id=<?= urlencode($_GET['id']) ?>';
                    } else {
                        window.location.href = 'commande_employe.php';
                    }
                });
            </script>
            
        <!--  satrt affiche popup delete category  -->

            <?php
            exit();
        }
        

    
 // start affiche delted succcessfully

if(isset($_SESSION['delete'] )){
  if($_SESSION['delete'] != ''){
    ?>
     <script>
        Swal.fire({
            title: '<?= ($_SESSION['delete']['type'] == 'success' ) ? 'Succès' : 'Erreur'; ?>',
            text: '<?= $_SESSION['delete']['message']; ?>',
            icon: '<?= $_SESSION['delete']['type'] ?>',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK',
            backdrop: 'rgba(0,0,0,0.4)',
            customClass:{
                popup :localStorage.getItem('mode') === 'dark' ? 'swal2-dark' : ''
            }
        }).then(() => {
            // Optionnel : Recharger la page pour actualiser les données
            window.location.href = 'commande_employe.php';
        });
    </script>
<?php
    unset($_SESSION['delete'] );
  }
}
?>
<!-- //  end affiche popup delete category  -->






<?php include "footer.php" ?>