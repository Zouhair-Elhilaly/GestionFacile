<?php 

if(isset($_POST['submit'])){
    $name = strip_tags($_POST['nom']);
    $prenom = strip_tags($_POST['prenom']);
    $email = filter_input(INPUT_POST , 'email' ,FILTER_VALIDATE_EMAIL);
    $phone = filter_input(INPUT_POST , 'phone' , FILTER_VALIDATE_INT);
    
}


?>