<?php 

session_start();
include "../include/conn_db.php";
include "functions/chiffre.php";

if($_SERVER['REQUEST_METHOD'] == 'GET'){

 if(isset($_GET['id'])){

           $id = filter_var(decryptId($_GET['id']) , FILTER_SANITIZE_NUMBER_INT);

            $stmt1 = $conn->prepare("SELECT * FROM produits WHERE id = ? ");
            $stmt1->bind_param('i', $id);
            if($stmt1->execute()){
                $res = $stmt1->get_result()->fetch_assoc();
                // $stmt->close();
                $quantiteProduct = $conn->prepare("SELECT * FROM quantite_produits WHERE id_produit = ? ");
                $quantiteProduct->bind_param('i', $id);
                $quantiteProduct->execute();
                $result = $quantiteProduct->get_result()->fetch_assoc();
          
          $_SESSION['nom_product']  = $res['nom'];
          $_SESSION['category_id'] = $res['category_id'];
          $_SESSION['quantite_product'] =  $result['quantite'];
          $_SESSION['id_product'] = $id;
          $_SESSION['image_product'] = $res['image'];

          // echo $_SESSION['nom_product']."<br>";
          // echo $_SESSION['category_id']."<br>";
          // echo $_SESSION['quantite_product']."<br>";
          // echo $_SESSION['id_product']."<br>";
          // echo  $_SESSION['image_product']."<br>";
        
          $stmt1->close();
          $quantiteProduct->close();

          // echo  $_SESSION['id_product'] ;

        $_SESSION['product_data'] = 1;
        header("location: view_product.php");
          
          
  }}}