<?php
require_once 'delete.php';

if($result){
    ?> <script>
        alert("User deleted");
    </script>
    <?php }else{
        ?>
        <script>
            alert("user note deleted")
        </script>
        <?php
    }
?>