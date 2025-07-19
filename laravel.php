<?php 

// Autoriser toutes les origines (attention : à restreindre en prod !)
header("Access-Control-Allow-Origin: *");

// Autoriser certains en-têtes (pour POST, PUT…)
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Autoriser certaines méthodes HTTP
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Indiquer qu’on renvoie du JSON
header("Content-Type: application/json; charset=UTF-8");


$db = new mysqli("localhost" , "root" , "" , "stock");


if(isset($_GET['search'])){
if($_GET['search'] != ''){
    $v = trim($_GET['search']);

$stmt = $db->prepare("SELECT * from produits where nom = ?");
$stmt->bind_param('s' , $v);
$stmt->execute();
$res = $stmt->get_result()->fetch_all();

print_r(
    json_encode($res)
);

}}

?>