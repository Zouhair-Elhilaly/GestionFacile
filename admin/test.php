<?php 

include "../include/conn_db.php";

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $stmt = $conn->prepare("SELECT name from category WHERE id = ?");

 echo    $categoryName = filter_input(INPUT_POST , 'productCategory' , FILTER_SANITIZE_STRING);

    $stmt = $conn->prepare("SELECT name from category WHERE id = ?");
    $stmt->bind_param("i", $categoryName);
    $stmt->execute();
    $res = $stmt->get_result();

    $row = $res->fetch_assoc();
    echo $row['name'];
}

?>