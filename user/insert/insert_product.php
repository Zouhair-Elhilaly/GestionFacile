<?php 
session_start();


require_once '../../include/config.php';
require_once '../../admin/functions/chiffre.php';

if($_SERVER['REQUEST_METHOD'] == 'GET'){

    $_SESSION['insert_commande'] = '';



if(isset($_GET['page']) and $_GET['page'] == 'produit'){
// $C = decryptId($_GET['id']);
$d = filter_input(INPUT_GET , 'id' , FILTER_SANITIZE_NUMBER_INT);
}else{
    //    $C = filter_input(INPUT_GET , 'id' , FILTER_SANITIZE_NUMBER_INT);
    //     $d = filter_input(INPUT_GET , 'id' , FILTER_SANITIZE_NUMBER_INT);
        $d = decryptId($_GET['id']);

    }



$V =  decryptId($_GET['email']);


$email = encryptId($V);



$stmt = $conn1->prepare("SELECT id FROM employé WHERE email = ?");
$stmt->bind_param('s', $V);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
if($res){

    // start deminue 
    $resultStock = $conn1->prepare("SELECT stock as resStock FROM produit where id_produit = ?");
    $resultStock->bind_param('i',$d);
    $resultStock->execute();
    $stock = $resultStock->get_result()->fetch_assoc();
    if( $stock['resStock'] == 0){
        $_SESSION['insert_commande'] = [
            'type' => 'erorr',
            'msg' => 'La quantité de produit est vide'
        ];
    }else{

    $produitStock = $conn1->prepare("UPDATE produit SET stock = stock - 1  WHERE id_produit = ?");
    $produitStock->bind_param('i',$d);
    $produitStock->execute();

    // end deninu





    $id_employe = $res['id'];
    $stmt = $conn1->prepare("INSERT INTO commande (id) VALUES (?)");
    $stmt->bind_param('i', $id_employe,);
    
    
    if($stmt->execute()){
      $stmt2 = $conn1->prepare("SELECT MAX(id_command) AS res FROM commande");
       $stmt2->execute();
       $res2 = $stmt2->get_result()->fetch_assoc();

        // echo $res2['res'].decryptId($C);
        // die();

        $details = $conn1->prepare("INSERT INTO détailler (id_command,id_produit) VALUES (?,?)");
        $details->bind_param('ii', $res2['res'],$d);
        $details->execute();
        

        $_SESSION['insert_commande'] = [
            'type' => 'success',
            'msg' => 'Produit ajouté avec succès.'
        ];
    }else{
        $_SESSION['insert_commande'] = [
            'type' => 'error',
            'msg' => 'Erreur lors de l\'ajout du produit'
        ];
    }


//}//else{
    // $_SESSION['insert_commande'] = [
    //         'type' => 'error',
    //         'msg' => 'Erreur lors de l\'ajout du produit'
    //     ];
}
    $stmt->close(); 
    $conn1->close();
    $details->close();
}

}  //end stock==0


if(!empty($_GET['page']) ){
    if($_GET['page'] == 'produit'){
     header("location:../all_product.php");
    }

}else if(isset($_GET['product_category'] ) and $_GET['product_category'] == 1){
    $_SESSION['id_category'] = $_GET['idCAtegory'];
header( "location:../product_category.php");

}else{
    header("location:../home.php");

}
    exit;



?>