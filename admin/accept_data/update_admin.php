<?php 



session_start();

require_once '../../include/conn_db.php';

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $id = $_GET['id'] ;
    
    $sql = "SELECT * FROM admin WHERE id  = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i'  , $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows > 0){
       if($row = $result->fetch_assoc()){
           $_SESSION['nom_update'] = $row['nom'];
           $_SESSION['prenom_update'] = $row['prenom'];
           $_SESSION['email_update'] = $row['email'];
           $_SESSION['telephone_update'] = $row['telephone'];
           $_SESSION['image_update'] = $row['image'];

           header("location: ../view_admin.php");

       }
    }



}else{
    echo "request not get";
}
?>