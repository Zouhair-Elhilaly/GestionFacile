<?php 

include "../include/config.php";
include "../admin/functions/chiffre.php";
//   $stmt = $conn11->prepare("SELECT name from category WHERE id = ?");

//  echo    $categoryName = filter_input(INPUT_POST , 'productCategory' , FILTER_SANITIZE_STRING);
$d = 36;
    $stmt = $conn1->prepare("SELECT * from admin WHERE id_admin = ?");
    $stmt->bind_param("i", $d);
    $stmt->execute();
    $res = $stmt->get_result();

    $row = $res->fetch_assoc();
    // echo $row['mot_de_passe'];
    $p =decryptId($row['mot_de_passe']);
    echo $p;
//    var_dump(password_verify($p, $row['id_admin']))

?>