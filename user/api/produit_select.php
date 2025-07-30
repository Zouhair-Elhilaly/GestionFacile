<?php 
require_once '../../include/config.php';
header('Content-Type: application/json');

// Vérifie si la méthode est GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // Vérifie si 'select=1' est bien présent dans l'URL
    $select = filter_input(INPUT_GET, 'select', FILTER_VALIDATE_INT);

    if ($select === 1) {
        $quantiteActuelle = 0;
        // Prépare la requête SQL avec tri aléatoire correct
        $stmt = $conn1->prepare("SELECT * FROM produit where stock != ? ORDER BY RAND()");
        $stmt->bind_param('i',$quantiteActuelle);
        $stmt->execute();

        $result = $stmt->get_result();
        $produits = $result->fetch_all(MYSQLI_ASSOC); // retourne un tableau associatif lisible

        echo json_encode([
            'status' => 'success',
            'data' => $produits
        ]);

    } else {
        // Valeur "select" incorrecte
        echo json_encode([
            'status' => 'error',
            'message' => 'Paramètre "select" invalide.'
        ]);
    }
} else {
    // Méthode non autorisée
    echo json_encode([
        'status' => 'error',
        'message' => 'Méthode non autorisée. Utilisez GET.'
    ]);
}
?>
