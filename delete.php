<?php
if($_SERVER['REQUEST_METHOD']  == $_GET){
    $Id_user = $_GET['id'];

    $sql = "DELETE * FROM users WHERE id = '$Id_user'";
    $result = mysqli_query($conn, $sql);

}
?>