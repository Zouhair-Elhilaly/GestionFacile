<?php 
// session_start();
define('SECURE_ACCESS', true);
include 'header.php';
include 'navbar.php';
?>

<div class="content">

<?php 

$employe = $conn1->prepare("SELECT * from admin WHERE id_admin = ? ");
$employe->bind_param('i',$_SESSION['id_admin']);
$employe->execute();
$res = $employe->get_result()->fetch_assoc();

?>

      <!-- Page Profil -->
                <div >
                    <center><h3 style="margin-bottom: 2rem; color: var(--titleColor);">Mon Profile</h3></center>
                    
                    <div class="profile-form">
                        <center class="center_img"><img id="image_profile" src="protection_image/image_proxy.php?img=<?=$res['image']?>" alt=""></center>
                        <form>
                            <div class="form-group">
                                <label class="form-label">Nom complete</label>
                                <input style="color: var(--gray-700) ; font-weight: bold;" disabled type="text" class="form-control " value="<?= $res['prenom'].' '.$res['nom']?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label ">Email</label>
                                <input style="color: var(--gray-700) ; font-weight: bold;" disabled type="email" class="form-control " value="<?= $res['email'] ?>">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Téléphone</label>
                                <input style="color: var(--gray-700) ; font-weight: bold;" disabled type="tel" class="form-control " value="<?= $res['telephone'] ?>">
                            </div>
                        </form>
                    </div>
                </div>
            
      










<style>

    .nav-link1{
          background: var(--primary-color);
          color: var(--white);
    }



    /* Profile Form */
.profile-form {
  background: var(--secondary-color);
  border-radius: 1rem;
  /* padding: 2rem; */
  padding: 8px;
  box-shadow: var(--shadowForm);
}

#image_profile {
  width: 120px;
  height: 120px;
  background-size: cover;
  border-radius: 50%;
  box-shadow: var(--shadow);
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
  color: var(--titleColor);
}

input{
    border: 1px solid var(--text-light) !important
}



.form-control {
  width: 100%;
  padding: 0.75rem;
  border: 2px solid var(--gray-200);
  border-radius: 0.5rem;
  font-size: 1rem;
  transition: all 0.2s ease;
}

.form-control:focus {
  outline: none;
  border-color: var(--primary-color);
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
</style>
<?php include "footer.php" ?>