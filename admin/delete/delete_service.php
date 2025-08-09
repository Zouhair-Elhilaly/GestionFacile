<?php
require_once '../../include/config.php'; // Inclure votre connexion à la DB
include '../functions/chiffre.php'; // Ajouter l'inclusion des fonctions de chiffrement

// Définir l'en-tête JSON
header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
   header("location:../error.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'delete' && isset($_POST['id_service'])) {
        $id_service_encrypted = $_POST['id_service'];
        
        // Déchiffrer l'ID
        $id_service = decryptId($id_service_encrypted);
        
        // Vérifier que l'ID est valide
        if (!$id_service || !is_numeric($id_service)) {
            echo json_encode([
                'success' => false,
                'message' => 'ID de service invalide'
            ]);
            exit;
        }
        
        try {
            // Utilisation de mysqli (selon votre code principal)
            $deleteServ = $conn1->prepare("DELETE FROM service WHERE id_service = ?");
            $deleteServ->bind_param('i', $id_service);
            
            if ($deleteServ->execute()) {
                if ($deleteServ->affected_rows > 0) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Service supprimé avec succès'
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Aucun service trouvé avec cet ID'
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Erreur lors de l\'exécution de la requête'
                ]);
            }
            
            $deleteServ->close();
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ]);
        }
        exit;
    }
}

// Si la requête n'est pas valide
echo json_encode([
    'success' => false,
    'message' => 'Requête invalide'
]);
?>