<?php
// Clé secrète (à garder confidentielle)
define('SECRET_KEY', 'MaCléSuperSecrète123!');
define('SECRET_IV', 'MonIVSecret456!'); // IV = vecteur d'initialisation
define('METHOD', 'AES-256-CBC'); // Algorithme de chiffrement

// Fonction pour chiffrer l'ID
function encryptId($id) {
    $key = hash('sha256', SECRET_KEY);
    $iv = substr(hash('sha256', SECRET_IV), 0, 16);
    $output = openssl_encrypt($id, METHOD, $key, 0, $iv);
    return base64_encode($output); // Encodage pour URL
}

// Fonction pour déchiffrer l'ID
function decryptId($encryptedId) {
    $key = hash('sha256', SECRET_KEY);
    $iv = substr(hash('sha256', SECRET_IV), 0, 16);
    $encryptedId = base64_decode($encryptedId);
    $output = openssl_decrypt($encryptedId, METHOD, $key, 0, $iv);
    return $output;
}
?>
