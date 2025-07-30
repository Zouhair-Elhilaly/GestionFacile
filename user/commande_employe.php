<?php 
include 'header.php';
$_SESSION['idEMploye']; //recupurer apres login




// check update stock commande 

//         echo "<script>
//         Swal.fire({
//     icon: '$type',
//     title: 'Opération réussie !',
//     text: '$msg',
//     timer: 3000 
// });</script>";
//         unset($_SESSION['insert_commande']);

// end check update stock commande










?>

<!-- satrt hidden card dans page commande  -->
<style>
    .show_counter_commande{
        display: none;
    }
</style>
<!-- end hidden card dans page commande  -->

<div class="content">


 <!-- Page Commandes -->
                <div style="display:block" id="ordersPage" class="page">
                    <h3 style="margin-bottom: 2rem; color: var(--gray-900);">Mes Commandes</h3>
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
                            <tbody id="ordersTableBody">


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

                                <tr <?php if($rowC['status'] == 'Rejetée'){?> style="text-decoration: line-through" <?php }?>>
                                    <td><?php echo $rowC['id_command']?></td>
                                    <td><?= $rowC['date']?></td>
                                    <td><?= $rowP['nom_produit']?></td>
                                    <td><input class="input_commande" type="number" oninput="this.value = this.value.replace(/[^0-9]/g,'')" value="<?= $resIdProduit['quantité']?>"> <input class="input_hidden" type="hidden" value="<?php echo $rowC['id_command'] ?>"></td>
                                    <input type="hidden" class="id_product_hidden" value="<?= $resIdProduit['id_produit'] ?>">
                                    
                                        <td <?php if($rowC['status'] == 'Rejetée'){ ?>colspan="2" style="text-align: center;;" <?php }?>>
                                    <?php if($rowC['status'] == 'En attente'){?>
                                        
                                    <span class="status-badge status-pending">En attente</span>
                                    <?php }else if($rowC['status'] == 'Rejetée'){?>
                                                <span class="status-badge status-cancelled">Rejetée</span>
                                        <?php }else{?>
                                                <span class="status-badge status-confirmed">Confirmée</span>
                                        <?php }?>
                                        </td>

                                    <td <?php if($rowC['status'] == 'Rejetée'){ ?> style="display:none"<?php }?>  >
                                        <a href="commande_employe.php?id=<?= $idC ?>" class="deleteCammande1">
                                    
                                            
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