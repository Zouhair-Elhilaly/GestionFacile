<?php
// Inclure le fichier de configuration de la base de données
include '../../include/config.php';

// Démarrer la session
session_start();

// Headers pour JSON
header('Content-Type: application/json; charset=utf-8');

// Fonction pour envoyer une réponse JSON et arrêter l'exécution
function sendJsonResponse($data) {
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}




if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
   header("location:../error.php");
    exit();
}


try {
    // Vérifier la méthode de requête
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        sendJsonResponse([
            'status' => 'error',
            'message' => 'Méthode non autorisée'
        ]);
    }

    // Lire les données JSON envoyées
    $input = json_decode(file_get_contents('php://input'), true);

    // Validation des données reçues
    if (!$input || !isset($input['email']) || !isset($input['password'])) {
        sendJsonResponse([
            'status' => 'error',
            'message' => 'Données manquantes'
        ]);
    }

    $email = trim($input['email']);
    $password = trim($input['password']);

    // Validation côté serveur
    if (empty($email) || empty($password)) {
        sendJsonResponse([
            'status' => 'error',
            'message' => 'Email et mot de passe requis'
        ]);
    }

    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendJsonResponse([
            'status' => 'error',
            'message' => 'Format d\'email invalide'
        ]);
    }

    // Validation de la longueur du mot de passe
    if (strlen($password) < 6) {
        sendJsonResponse([
            'status' => 'error',
            'message' => 'Le mot de passe doit contenir au moins 6 caractères'
        ]);
    }

    // Hachage du mot de passe
    $key = "MaCleSecrete123456"; // مفتاح سري ثابت
    $passwordHash = openssl_encrypt($password, "AES-128-ECB", $key);

    // Hachage du mot de passe
    // $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Vérifier si la table config existe et a des données
    $checkQuery = "SELECT id, email, mot_de_passe_app FROM config LIMIT 1";
    $checkResult = $conn1->query($checkQuery);

    if (!$checkResult) {
        throw new Exception("Erreur lors de la vérification de la configuration: " . $conn1->error);
    }

    if ($checkResult->num_rows > 0) {
        // Mettre à jour la configuration existante
        $updateQuery = "UPDATE config SET email = ?, mot_de_passe_app = ? WHERE id = (SELECT id FROM (SELECT id FROM config LIMIT 1) AS temp)";
        $updateStmt = $conn1->prepare($updateQuery);
        
        if (!$updateStmt) {
            throw new Exception("Erreur de préparation de la requête UPDATE: " . $conn1->error);
        }

        $updateStmt->bind_param("ss", $email, $passwordHash);
        
        if (!$updateStmt->execute()) {
            throw new Exception("Erreur lors de la mise à jour: " . $updateStmt->error);
        }

        $updateStmt->close();
        
        sendJsonResponse([
            'status' => 'success',
            'message' => 'Configuration mise à jour avec succès',
            'action' => 'updated'
        ]);

    } else {
        // Insérer une nouvelle configuration
        $insertQuery = "INSERT INTO config (email, mot_de_passe_app) VALUES (?, ?)";
        $insertStmt = $conn1->prepare($insertQuery);
        
        if (!$insertStmt) {
            throw new Exception("Erreur de préparation de la requête INSERT: " . $conn1->error);
        }

        $insertStmt->bind_param("ss", $email, $passwordHash);
        
        if (!$insertStmt->execute()) {
            throw new Exception("Erreur lors de l'insertion: " . $insertStmt->error);
        }

        $insertStmt->close();
        
        sendJsonResponse([
            'status' => 'success',
            'message' => 'Configuration ajoutée avec succès',
            'action' => 'inserted'
        ]);
    }

} catch (Exception $e) {
    // Log l'erreur (optionnel)
    error_log("Erreur dans insert_email_app.php: " . $e->getMessage());
    
    sendJsonResponse([
        'status' => 'error',
        'message' => 'Erreur serveur: ' . $e->getMessage()
    ]);
}

// Fermer la connexion
if (isset($conn1)) {
    $conn1->close();
}
?>