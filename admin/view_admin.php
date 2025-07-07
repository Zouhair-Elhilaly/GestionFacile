
<?php include_once "header.php" ?>

<link rel="stylesheet" href="style.css">


<?php include_once "navbar.php" ?>


<div  class="main_accueil">  
    <button id="btn_click_add" class="add_admin">
       <a href="#"> add admin</a>
    </button>
    <div class="table_admin">
        <table>
            <thead>
                <tr>
                    <th>ID</th><th>image</th><th>nom</th><th>service</th><th>email</th><th colspan="2">Modification</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>7</td>
                    <td><img src="" alt="img"></td>
                    <td>amine</td>
                    <td>info</td>
                    <td>email</td>
                    <td ><a href="#">update</a></td>
                    <td><a href="#">delete</a></td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- start from add admin  -->
<div class="form_add_admin">
    <form action="" method="POST">
        <h3>Add new admin</h3>
        <h3 id="close_form_add_admin" >X</h3>
        <input type="text" name="nom" placeholder="Ecrire le nom">
        <input type="text" name="prenom" placeholder="Ecrire prenom">
        <input type="email" name="email" id="" placeholder="Ecrire Email">
        <input type="text" name="phone" id="" placeholder="Ex : 0101910087">
        <input type="password" name="password" id="" placeholder="Ecrire Password">
        <div class="label">
            <label for="file">Choisir un image</label>
            <input style="display: none" type="file" name="image" id="file" />
        </div>
        <input type="submit" name="submit" value="ADD">
    </form>
</div>

</div>



<script src="main_add.js"></script>



<?php include_once "footer.php" ?>