<?php

session_start();
require_once '../../include/config.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
   header("location:../error.php");
    exit();
}


// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    // Valider et nettoyer les entrées
    $nom = strip_tags(trim($_POST['nom_service'] ?? ''));

     $nom = filter_var($nom , FILTER_SANITIZE_STRING);
   
    
    // Validation des champs
    if (empty($nom)){
        $_SESSION['service_message'] =[
            'type' => 'error',
             'msg' =>  'Le nom est requis'
        ];
        header("location:../view_service.php");
        exit;
    };

  
    
    // Vérifier si l'admin existe déjà
    $stmt = $conn1->prepare("SELECT * FROM service WHERE nom_service = ?");
    $stmt->bind_param('s', $nom);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows > 0) {
         $_SESSION['service_message'] =[
            'type' => 'error',
             'msg' =>  'Le service  existe déjà'
        ];
        header("Location:../view_product.php");
        exit;
    }
    
  

// Insertion dans la BDD
$stmt = $conn1->prepare("INSERT INTO service (nom_service) VALUES (?)");
if ($stmt === false) {
    $_SESSION['service_message'] =[
            'type' => 'error',
             'msg' =>  'Erreur préparation'
        ];
    header("location:../view_product.php");
    exit;
}

$stmt->bind_param("s", $nom);

if ($stmt->execute()) {
     $_SESSION['service_message'] =[
            'type' => 'success',
             'msg' =>  'Nouvel service ajouté avec succès'
        ];

} else {
     $_SESSION['service_message'] =[
            'type' => 'error',
             'msg' =>  'Erreur lors de l\'ajout de service'
        ];
}

$stmt->close();
$conn1->close();

    // Redirection
    header("Location:../view_service.php");
    exit;
}