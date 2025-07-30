<?php 
session_start();

require_once '../../include/config.php';
require_once '../../admin/functions/chiffre.php';


if (isset($_GET['id'])) {
    $id = filter_var((decryptId($_GET['id'])) , FILTER_SANITIZE_NUMBER_INT);

    // Vérifier si la commande existe
    $stmt = $conn1->prepare("SELECT * FROM commande WHERE id_command = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        // Mettre à jour la quantité
        $delete = $conn1->prepare("DELETE FROM commande WHERE id_command = ?");
        $delete ->bind_param('i',  $id);
        if ($delete ->execute()) {
           $_SESSION['delete'] = [
            'type' => 'success',
            'message' => 'La commande a été supprimée avec succès'
           ];
        } else {

                $_SESSION['delete'] = [
                    'type' => 'warning',
                    'message' => 'Erreur lors de la suppression'
                ];
        }
        $delete->close();
    } else {
         $_SESSION['delete'] = [
            'type' => 'warning',
            'message' => 'Commande introuvable'
        ];

    }

    $stmt->close();
    $conn1->close();
} else {
                    $_SESSION['delete'] = [
                    'type' => 'warning',
                    'message' => 'Paramètres manquants'
                    ];
}
header("location:../commande_employe.php");
exit;
?>
