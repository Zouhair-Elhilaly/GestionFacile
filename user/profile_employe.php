<?php 
define('SECURE_ACCESS', true);
include 'header.php';

?>

<div class="content">

<?php 

$employe = $conn1->prepare("SELECT * from employé WHERE id = ? ");
$employe->bind_param('i',$_SESSION['idEMploye']);
$employe->execute();
$res = $employe->get_result()->fetch_assoc();

$post = $conn1->prepare("SELECT * from post WHERE id_post = ? ");
$post->bind_param('i',$res['id_post']);
$post->execute();
$resPost = $post->get_result()->fetch_assoc();

?>

<style>
    label{
        color: var(--titleColor) !important
    }
</style>
      <!-- Page Profil -->
                <div >
                    <h3 style="margin-bottom: 2rem; color: var(--titleColor);">Mon Profile</h3>
                    <div class="profile-form">
                        <center class="center_img"><img id="image_profile" src="../admin/protection_image/protection_employe.php?img=<?= $res['image'] ?>" alt=""></center>
                        <form>
                            <div class="form-group">
                                <label class="form-label">Nom complet</label>
                                <input style="color: var(--gray-700) ; font-weight: bold;" disabled type="text" class="form-control " value="<?= $res['prenom'].' '.$res['nom']?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label ">Email</label>
                                <input style="color: var(--gray-700) ; font-weight: bold;" disabled type="email" class="form-control " value="<?= $res['email'] ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Poste</label>
                                <input style="color: var(--gray-700) ; font-weight: bold;" disabled type="text" class="form-control " value="<?= $resPost['nom_post'] ?>" >
                            </div>
                            <div class="form-group">
                                <label class="form-label">Téléphone</label>
                                <input style="color: var(--gray-700) ; font-weight: bold;" disabled type="tel" class="form-control " value="<?= $res['Telephone'] ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Ville</label>
                                <input style="color: var(--gray-700) ; font-weight: bold;" disabled type="tel" class="form-control " value="<?= $res['ville'] ?>">
                            </div>
                        </form>
                    </div>
                </div>
            
      










<style>

    .nav-link1{
          background: var(--titleColor);
          color: var(--white);
    }
</style>
<?php include "footer.php" ?>