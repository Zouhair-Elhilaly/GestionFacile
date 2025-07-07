<?php

if($_SERVER['REQUEST_METHOD']  == $GET['id']){
    $id = $_GET['id'];
    $sql = "SELECT * FROM table WHERE id = '$id'";
    
}


?>