<?php
session_start();
include "../../include/config.php";
include "../functions/chiffre.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
   header("location:../error.php");
    exit();
}


// Vérifier si la requête est POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['service_message'] = [
        'msg' => 'Méthode non autorisée',
        'type' => 'error'
    ];
    header('Location: ../error.php');
    exit();
}

// Récupération et validation des données
$id_service = isset($_POST['id_service']) ? intval($_POST['id_service']) : 0;
$nom_service = isset($_POST['nom_service']) ? trim($_POST['nom_service']) : '';

// Validation de l'ID du service
if ($id_service <= 0) {
    $_SESSION['service_message'] = [
        'msg' => 'ID du service invalide',
        'type' => 'error'
    ];
    header('Location: ../view_service.php');
    exit();
}

// Validation du nom du service
if (empty($nom_service)) {
    $_SESSION['service_message'] = [
        'msg' => 'Le nom du service est obligatoire',
        'type' => 'error'
    ];
    header('Location: ../view_service.php?id=' . encryptId($id_service));
    exit();
}

// Vérifier la longueur du nom (max 100 caractères)
if (strlen($nom_service) > 100) {
    $_SESSION['service_message'] = [
        'msg' => 'Le nom du service ne peut pas dépasser 100 caractères',
        'type' => 'error'
    ];
    header('Location: ../view_service.php?id=' . encryptId($id_service));
    exit();
}

// Nettoyer le nom du service (enlever les caractères spéciaux dangereux)
$nom_service = htmlspecialchars($nom_service, ENT_QUOTES, 'UTF-8');

// Vérifier si le service existe
$check_stmt = $conn1->prepare("SELECT id_service FROM service WHERE id_service = ?");
$check_stmt->bind_param('i', $id_service);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows === 0) {
    $_SESSION['service_message'] = [
        'msg' => 'Service introuvable',
        'type' => 'error'
    ];
    header('Location: ../view_service.php');
    exit();
}

// Vérifier si un autre service avec le même nom existe déjà
$duplicate_stmt = $conn1->prepare("SELECT id_service FROM service WHERE nom_service = ? AND id_service != ?");
$duplicate_stmt->bind_param('si', $nom_service, $id_service);
$duplicate_stmt->execute();
$duplicate_result = $duplicate_stmt->get_result();

if ($duplicate_result->num_rows > 0) {
    $_SESSION['service_message'] = [
        'msg' => 'Un service avec ce nom existe déjà',
        'type' => 'error'
    ];
    header('Location: ../view_service.php?id=' . encryptId($id_service));
    exit();
}

// Mise à jour du service
$update_stmt = $conn1->prepare("UPDATE service SET nom_service = ? WHERE id_service = ?");
$update_stmt->bind_param('si', $nom_service, $id_service);

if ($update_stmt->execute()) {
    // Succès
    $_SESSION['service_message'] = [
        'msg' => 'Service modifié avec succès',
        'type' => 'success'
    ];
} else {
    // Erreur lors de la mise à jour
    $_SESSION['service_message'] = [
        'msg' => 'Erreur lors de la modification du service',
        'type' => 'error'
    ];
}

// Fermer les statements
$check_stmt->close();
$duplicate_stmt->close();
$update_stmt->close();

// Redirection vers la page de visualisation
header('Location: ../view_service.php');
exit();
?>