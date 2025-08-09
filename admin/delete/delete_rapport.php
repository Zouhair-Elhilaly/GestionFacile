<?php 
session_start();
include '../../include/config.php';
include '../../admin/functions/chiffre.php';


if(isset($_GET['token'])){
   if(decryptId($_GET['token']) != 'token'){
   header("location:../error.php");
   exit();
   }
}else{
   header("location:../error.php");
   exit();
};



if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $id = decryptId(trim($_GET['id']));
    $id = filter_var($id , FILTER_SANITIZE_NUMBER_INT);
    if(!empty($id)){
        $select = $conn1->prepare("SELECT id_document as idDoc FROM commande WHERE id_document = ?");
        $select->bind_param("i", $id);
        $select->execute();
        $res = $select->get_result();
        if($res->num_rows > 0){
            $row = $res->fetch_assoc();
            $null = NULL;
            $idDoc = $row['idDoc'];
            $delete = $conn1->prepare("UPDATE commande SET id_document = ? WHERE id_document = ?");
            $delete->bind_param("is", $null,$idDoc);
            $delete->execute();
            if($delete){
                 $_SESSION['delete_rapport'] = [
                        'type' => 'success',
                        'msg' => 'rapport suprimer avec succés',
                    ];
                    header("location:../view_rapport.php");
                     exit;
            }else{
                 $_SESSION['delete_rapport'] = [
                        'type' => 'warning',
                        'msg' => 'echec de suppression',
                    ];

                    header("location:../view_rapport.php");
                     exit;
            }

        }else{
             $_SESSION['delete_rapport'] = [
                        'type' => 'error',
                        'msg' => 'rapport non trouvé',
                    ];

             header("location:../view_rapport.php");
                     exit;
        }
    }else{
        $_SESSION['delete_rapport'] = [
            'type' => 'error',
             'msg' => 'invalide rapport'
        ];
    }
    header("location:../error.php");
    exit;
}

header("location:../error.php");
    exit;


?>