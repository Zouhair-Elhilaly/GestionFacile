<?php 

require_once "../../include/config.php";
require_once '../../vendor/tecnickcom/tcpdf/tcpdf.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
   header("location:../error.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id_employe'];
    $utilisee = 0;
     $statusRejet = 'Rejetée';
    $stmt = $conn1->prepare("SELECT * FROM commande WHERE id = ? and utilisee = ? and status != ?");
    $stmt->bind_param('iis', $id, $utilisee,$statusRejet);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        // Démarrage du contenu HTML du tableau
        $html = '
<style>
  table {
    border-collapse: collapse;
    width: 100%;
    font-size: 12pt;
  }
  th, td {
    border: 1px solid #555;
    padding: 8px;
    text-align: center;
  }
  th {
    background-color: #f2f2f2;
  }
  tfoot td {
    font-weight: bold;
    background-color: #eee;
  }
</style>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Produit</th>
      <th>Category</th>
      <th>Quantité</th>
    </tr>
  </thead>
  <tbody>';

        while ($row = $result->fetch_assoc()) {
            // Récupérer les détails de la commande depuis la table détailler
            $idProduitDetailler = $conn1->prepare("SELECT * from détailler WHERE id_command = ?");
            $idProduitDetailler->bind_param('i', $row['id_command']);
            $idProduitDetailler->execute();
            $resIdProduit = $idProduitDetailler->get_result()->fetch_assoc();

            // Récupérer les informations du produit
            $produit = $conn1->prepare("SELECT * FROM produit WHERE id_produit = ?");
            $produit->bind_param('i', $resIdProduit['id_produit']);
            $produit->execute();
            $produit_result = $produit->get_result()->fetch_assoc();

            // Récupérer le nom de la catégorie
            $categoryName = $conn1->prepare("SELECT * FROM category WHERE id_category = ?");
            $categoryName->bind_param('i', $produit_result['id_category']);
            $categoryName->execute();
            $category_result = $categoryName->get_result()->fetch_assoc();

            $html .= '<tr>
                <td>' . $row['id_command'] . '</td>
                <td>' . htmlspecialchars($produit_result['nom_produit']) . '</td>
                <td>' . $category_result['nom_category'] . '</td>
                <td>' . $resIdProduit['quantité'] . '</td>
            </tr>';
        }

        // Calculer la somme des quantités depuis la table détailler
        $sum = $conn1->prepare("SELECT SUM(d.quantité) AS quantite FROM détailler d 
                               INNER JOIN commande c ON d.id_command = c.id_command 
                               WHERE c.id = ? AND c.utilisee = ? AND status != ?");
        $sum->bind_param('iis', $_POST['id_employe'], $utilisee,$statusRejet);
        $sum->execute();
        $vv = $sum->get_result();
        $sumProduit = $vv->fetch_assoc();
        $var = $sumProduit['quantite'];

        $html .= '
  </tbody>
  <tfoot>
    <tr>
      <td colspan="2">Total produits</td>
      <td colspan="2">' . $var . '</td>
    </tr>
  </tfoot>
</table>';

        // Création PDF
        $pdf = new TCPDF();
        $pdf->AddPage();

        // Adresse en haut à gauche
        $pdf->SetFont('helvetica', '', 10);
        $adresse = "Conseil Provincial de Youssoufia\nAdresse : Avenue Mohammed V\n Youssoufia, Maroc";
        $pdf->SetXY(10, 10); // X=10mm, Y=10mm
        $pdf->MultiCell(90, 10, $adresse, 0, 'L', false);

        // titre des commande
        $pdf->SetFont('helvetica', '', 10);
        $adresse = "Documentation des commandes";
        $pdf->SetXY(50, 30); // X=10mm, Y=10mm
        $pdf->MultiCell(90, 10, $adresse, 0, 'C', false);

        // Date en haut à droite
        $date_now = date('d/m/Y');
        $pdf->SetXY(140, 10); // droite
        $pdf->MultiCell(50, 10, "Date : $date_now", 0, 'R', false);

        // Affichage tableau au centre
        $pdf->Ln(30); // saut de 30mm
        $pdf->writeHTML($html, true, false, true, false, '');

        // Signature administrateur en bas à gauche
        $pdf->SetXY(10, 250); // position bas gauche
        $pdf->MultiCell(70, 10, "Signature Administrateur", 0, 'L', false);


        // requpure image en db
        $req = $conn1->prepare("SELECT * FROM config where image_signature IS NOT NULL ");
        $req->execute();
        $res = $req->get_result();
        if($res->num_rows > 0){
          $row = $res->fetch_assoc();
          $imageSignature =  $row['image_signature'];
        }else{
          $_SESSION['error_image_signature']=[
            'type' => 'error',
            'msg' => 'image signature non trouvé',
          ];
          header("location:../commande.php");
          exit;
        }

        $pdf->Image('../image/image_signature/'.$imageSignature, 10, 255, 40, 20, '', '', '', false, 300);


        // Signature employé en bas à droite
        $pdf->SetXY(130, 250);
        $pdf->MultiCell(70, 10, "Signature Employé", 0, 'R', false);
        // $pdf->Image('', 160, 255, 40, 20, '', '', '', false, 300);

        // Affichage PDF
        $pdf->Output('commande_' . $id.'_'.time().'_'.uniqid().'.pdf', 'D');

        // CORRECTION 1: Mise à jour du statut AVANT de changer utilisee
        $statusConfirmed = 'Confirmée';
        $statusEnattent = 'En attente';
        $updateStatus1 = $conn1->prepare("UPDATE commande SET status = ? WHERE id = ? AND utilisee = ? AND status = ?");
        $updateStatus1->bind_param('siis', $statusConfirmed, $id, $utilisee, $statusEnattent);
        $updateStatus1->execute();
        $updateStatus1->close();

        // CORRECTION 2: Mise à jour de utilisee APRÈS avoir mis à jour le statut
        $VraiUtilisee = 1;
        $update = $conn1->prepare("UPDATE commande SET utilisee = ? WHERE id = ?");
        $update->bind_param('ii', $VraiUtilisee, $id);
        $update->execute();
        $update->close();
    }
}

header("location:../commande.php");
exit;
?>