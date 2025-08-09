<?php 

include "admin/functions/chiffre.php";

include "include/config.php";


$stmt = $conn1->prepare("SELECT * FROM config");
$stmt->execute();

$res = $stmt->get_result()->fetch_assoc();

$r = $res['mot_de_passe_app'];
$d = decryptId("MXRnOEhuRnF6cDRHZHpYTnNRbW4xQT09");

$hash = password_hash('12345tr' , PASSWORD_DEFAULT);

echo $hash;
// echo $res['mot_de_passe_app'];
// echo "hhf"
?>