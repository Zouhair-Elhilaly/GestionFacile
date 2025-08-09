<?php
session_start();
include "../../include/config.php";
include "../functions/chiffre.php";

header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
   header("location:../error.php");
    exit();
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    
    if ($action == 'delete') {
        $encrypted_id = $_POST['id_post'];
        $id_post = decryptId($encrypted_id);
        
        // Validation de l'ID
        if (!$id_post || $id_post <= 0) {
            echo json_encode([
                'success' => false,
                'message' => 'ID du poste invalide.'
            ]);
            exit();
        }
        
        // Vérifier si le poste existe
        $check_stmt = $conn1->prepare("SELECT nom_post FROM post WHERE id_post = ?");
        $check_stmt->bind_param('i', $id_post);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows == 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Poste introuvable.'
            ]);
            exit();
        }
        
        // Vérifier s'il y a des employés associés à ce poste (si vous avez une table employés)
        // Décommentez cette partie si vous avez une table employés avec une clé étrangère vers post
        /*
        $check_employees = $conn1->prepare("SELECT COUNT(*) as count FROM employe WHERE id_post = ?");
        $check_employees->bind_param('i', $id_post);
        $check_employees->execute();
        $employee_result = $check_employees->get_result();
        $employee_count = $employee_result->fetch_assoc()['count'];
        
        if ($employee_count > 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Impossible de supprimer ce poste car il est associé à des employés.'
            ]);
            exit();
        }
        */
        
        // Supprimer le poste
        $delete_stmt = $conn1->prepare("DELETE FROM post WHERE id_post = ?");
        $delete_stmt->bind_param('i', $id_post);
        
        if ($delete_stmt->execute()) {
            if ($delete_stmt->affected_rows > 0) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Poste supprimé avec succès.'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Aucun poste supprimé.'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Erreur lors de la suppression du poste.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Action non valide.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée.'
    ]);
}
?>