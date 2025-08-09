<?php
session_start();
include "../../include/config.php";


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
   header("location:../error.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    
    if ($action == 'add') {
        $nom_post = strip_tags(trim($_POST['nom_post']));
        $id_service = intval($_POST['id_service']);
        
        // Validation
        if (empty($nom_post)) {
            $_SESSION['post_message'] = [
                'msg' => 'Le nom du poste est requis.',
                'type' => 'error'
            ];
            header('Location:../view_post.php');
            exit();
        }
        
        if ($id_service <= 0) {
            $_SESSION['post_message'] = [
                'msg' => 'Veuillez sélectionner un service valide.',
                'type' => 'error'
            ];
            header('Location:../view_post.php');
            exit();
        }
        
        // Vérifier si le service existe
        $check_service = $conn1->prepare("SELECT id_service FROM service WHERE id_service = ?");
        $check_service->bind_param('i', $id_service);
        $check_service->execute();
        $service_result = $check_service->get_result();
        
        if ($service_result->num_rows == 0) {
            $_SESSION['post_message'] = [
                'msg' => 'Le service sélectionné n\'existe pas.',
                'type' => 'error'
            ];
            header('Location:../view_post.php');
            exit();
        }
        
        // Vérifier si le poste existe déjà dans ce service
        $check_post = $conn1->prepare("SELECT id_post FROM post WHERE nom_post = ? AND id_service = ?");
        $check_post->bind_param('si', $nom_post, $id_service);
        $check_post->execute();
        $post_result = $check_post->get_result();
        
        if ($post_result->num_rows > 0) {
            $_SESSION['post_message'] = [
                'msg' => 'Ce poste existe déjà dans ce service.',
                'type' => 'error'
            ];
            header('Location:../view_post.php');
            exit();
        }
        
        // Insérer le nouveau poste
        $stmt = $conn1->prepare("INSERT INTO post (nom_post, id_service) VALUES (?, ?)");
        $stmt->bind_param('si', $nom_post, $id_service);
        
        if ($stmt->execute()) {
            $_SESSION['post_message'] = [
                'msg' => 'Poste ajouté avec succès.',
                'type' => 'success'
            ];
        } else {
            $_SESSION['post_message'] = [
                'msg' => 'Erreur lors de l\'ajout du poste.',
                'type' => 'error'
            ];
        }
        
        header('Location:../view_post.php');
        exit();
    }
} else {
    header('Location:../error.php');
    exit();
}
?>