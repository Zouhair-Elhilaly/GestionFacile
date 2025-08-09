<?php

// session_start();
// if (!$_SESSION['id_admin']) {
//     header('location:../error.php');
//     die();
// }



include "../../include/config.php";
$img = strip_tags($_GET['img']);

$stmt = $conn1->prepare("SELECT * from produit WHERE image like ?");
$stmt ->bind_param('s' , $img);
$stmt ->execute();

$res = $stmt->get_result();
if($res->num_rows <= 0){
header("location:../error.php");
exit();
}


$imagePath = '../image/image_produit/' . basename($_GET['img']);
if (!file_exists($imagePath)) {
    header('location:../error.php');
    die();
}


header('Content-Type: image/webp');
readfile($imagePath);
?>