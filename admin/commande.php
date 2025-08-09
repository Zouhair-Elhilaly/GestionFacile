<?php 
// session_start();
// include "../include/config.php";
define('SECURE_ACCESS', true);
include "header.php";
include "navbar.php";
// session_start();

// start image signature
if(isset($_SESSION['error_image_signature'])){
  $msg = $_SESSION['error_image_signature']['msg'];
  $type =  $_SESSION['error_image_signature']['type'];
echo "<script>
   Swal.fire({
    icon: '$type',
    title: 'Notification !',
    text: '$msg',
    timer: 3000 
});</script>";
unset($_SESSION['error_image_signature']);
}
// end image signature

?>
<link rel="stylesheet" href="css/commande_employe.php">
<div class="commande_employe bg-light">

<div class="container my-5">
  <?php ?>
    <div class="header_commande">
        <h2  class="mb-4 text-center">📋 Tableau des Commandes</h2>
    <a  id="btn-generer" href="#generer_pdf">
        <button>👁️ Aperçu PDF</button>
    </a>
</div>
  <!-- <div > -->


 

    <table class="table-responsive">
      <thead >
        <tr>
          <th>ID Commande</th>
          <th>ID Employé</th>
          <th>Produit</th>
          <th>Quantité</th>
          <th>Statut</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>

      
 <?php 
        $utilisee1= 0;
        $commande = $conn1->prepare("SELECT * FROM commande  where utilisee = ? ORDER BY id_command");
        $commande->bind_param('i',$utilisee1);
        $commande->execute();
        $res = $commande->get_result();

        // Créer un tableau pour grouper les commandes confirmées par employé
        $commandes_confirmees = [];

     
        if($res->num_rows > 0){
         
          while($row = $res->fetch_assoc()){

            $idProduitConn = $conn1->prepare("SELECT * FROM détailler WHERE id_command = ? ");
            $idProduitConn->bind_param('i',$row['id_command']);
            $idProduitConn->execute();
            $idProduit = $idProduitConn->get_result()->fetch_assoc();

            $produit = $conn1->prepare("SELECT nom_produit as nomP FROM produit WHERE id_produit = ? ");
            $produit->bind_param('i',$idProduit['id_produit']);
            $produit->execute();
            $nomP = $produit->get_result()->fetch_assoc();

             $qunatiteProduit = $conn1->prepare("SELECT * FROM détailler where id_command = ?");
              $qunatiteProduit->bind_param('i',$row['id_command']);
              $qunatiteProduit->execute();
              $quantiteProduit1 = $qunatiteProduit->get_result()->fetch_assoc();
              $quantiteProduit = $quantiteProduit1['quantité'];
              $qunatiteProduit->close();



            if ($row['status'] == 'Confirmée') {
              $commandes_confirmees[$row['id']][] = [
                'id_command' => $row['id_command'],
                'produit' => $nomP['nomP'],
                'quantite' => $quantiteProduit
              ];
            }
             
        ?>
        <tr>
          <td id="id_command"><?= $row['id_command'] ?></td>
          <td id="id_employe"><?= $row['id'] ?></td>
          <td id="produit"><?= $nomP['nomP'] ?></td>

        
          <td id="quantite"><?= $quantiteProduit ? $quantiteProduit : 0 ?></td>
          <td id="statu">
            <?php if ($row['status'] == 'Confirmée'): ?>
              <span class="badge btn-success">Confirmée</span>
            <?php elseif ($row['status'] == 'Rejetée'): ?>
              <span  class="badge rejetBtn">Rejetée</span>
            <?php else: ?>
              <span class="badge modify-btn bg-warning text-dark">En attente</span>
            <?php endif; ?>
          </td>
          <td id="modification">
            <?php if ($row['status'] == 'En attente'): ?>
              <form method="post" action="insert_update/update_commande.php" class="d-flex justify-content-center gap-2">
                <input type="hidden" name="id_commande" value="<?= $row['id_command'] ?>">
                <input type="hidden" name="id_employe" value="<?= $row['id']?>">
                <button name="action" value="1" class="btn btn-sm btn-success">✅</button>
                <button name="action" value="0" class="btn btn-sm btn-danger">❌</button>
              </form>
            <?php elseif($row['status'] == 'Confirmée' ): ?>
              Confirmée
            <?php elseif($row['status'] == 'Rejetée'):  ?>
              Rejetée
            <?php endif;?>
          </td>
        </tr>
        <?php }}else{
                   echo '<tr><td colspan="6"><center><div style="color: red; text-align: center; font-weight: bold;">aucune commande </div></center></td></tr>';
                  // hidden buntton  generer pdf
                   echo "<style>
                   #btn-generer{
                   display:none;
                   }
                   </style>";
                   
        }?>
      </tbody>
    </table>

  </div>

  <!-- Boutons pour générer les PDF des commandes confirmées par employé -->
  <?php if (!empty($commandes_confirmees)): ?>
    <div id="generer_pdf"   class="mt-5 generation_commande">
      <h4>Générer les documents PDF</h4>
      <?php foreach ($commandes_confirmees as $id_emp => $cmds): ?>
        <form method="post" action="insert/generer_pdf_commande.php" target="_blank" class="mb-2">
          <input type="hidden" name="id_employe" value="<?= $id_emp ?>">
          <button type="submit" class="btn btn-primary btn-sm">Générer PDF pour Employé N°<?= $id_emp ?></button>
        </form>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

</div>
<script>
  let notification_commande = document.querySelector("#notification_commande");
  notification_commande.style.display = 'none';
</script>
<?php include "footer.php" ;?>