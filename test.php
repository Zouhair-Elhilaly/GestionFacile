<?php 

// Autoriser toutes les origines (attention : à restreindre en prod !)
header("Access-Control-Allow-Origin: *");

// Autoriser certains en-têtes (pour POST, PUT…)
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Autoriser certaines méthodes HTTP
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Indiquer qu’on renvoie du JSON
header("Content-Type: application/json; charset=UTF-8");


include "include/conn_db.php";


$result = file_get_contents('php://input');

$data = json_decode($result);

var_dump($data);


$stmt = $conn->prepare("SELECT * FROM produits");
$stmt->execute();

// $result = $stmt->get_result();

// $res = $result->fetch_all(PDO::FETCH_ASSOC);

// print_r(
//     json_encode($res)
// );

// $obj = create.object

// $data = json_encode($res);


// echo "<h3>".$data."</h3>";



?>


