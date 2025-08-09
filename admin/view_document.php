<?php
// Code minimal pour afficher PDF en lecture seule

if (!isset($_GET['nom_document'])) {
    die('Document non spécifié');
}

$nom_document =$_GET['nom_document'];
$pdf_path = 'pdf/'.$nom_document; // . $nom_document;

if (!file_exists($pdf_path) || pathinfo($pdf_path, PATHINFO_EXTENSION) !== 'pdf') {
    die('Fichier introuvable ou invalide');
}

// En-têtes pour lecture dans le navigateur uniquement
header('Content-Type: application/pdf');
header('Content-Disposition: inline');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');

// Afficher le PDF
readfile($pdf_path);
exit;
?>