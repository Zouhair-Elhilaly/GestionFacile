<link rel="stylesheet" href="css/rapport.php">
<?php
// session_start();
// include "../include/config.php";
// include "../admin/functions/chiffre.php";
define('SECURE_ACCESS', true);
include "header.php";
include "navbar.php";





// delete_pdf
if(isset($_SESSION['delete_rapport'])){
    if(!empty($_SESSION['delete_rapport'])){
        $text = $_SESSION['delete_rapport']['msg'];
        $type = $_SESSION['delete_rapport']['type'];
        echo "<script>
        Swal.fire({
    icon: '$type',
    title: 'Opération réussie !',
    text: '$text',
    timer: 3000 
});</script>";
        unset($_SESSION['delete_rapport']);
    }
}
// end delete_pdf




// ajout_pdf
if(isset($_SESSION['ajout_pdf'])){
    if(!empty($_SESSION['ajout_pdf'])){
        $text = $_SESSION['ajout_pdf']['msg'];
        $type = $_SESSION['ajout_pdf']['type'];
        echo "<script>
        Swal.fire({
    icon: '$type',
    title: 'Opération réussie !',
    text: '$text',
    timer: 3000 
});</script>";
        unset($_SESSION['ajout_pdf']);
    }
}
// end ajout_pdf



?>

<div class="main_accueil" style="width: 100%">  
        <center><h2>Liste des documents par employé</h2></center>

 <!-- <div class="table-header"> -->
        <div class="navbar_rapport">
            <button class="add-btn" onclick="openModal()">
                <i>+</i> Ajouter un Document
            </button>
            <input type="search" name="search" id="search" placeholder="Search document">
        </div>
  <!-- </div> -->

<div class="container">
   
    
    <?php
    // Afficher les messages de succès ou d'erreur
    if (isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
        unset($_SESSION['success_message']);
    }
    if (isset($_SESSION['error_message'])) {
        echo '<div class="alert alert-error">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
        unset($_SESSION['error_message']);
    }
    ?>
    


   

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID Document</th>
                    <th>Nom Employé</th>
                    <th>Date Ajout</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="serviceTableBody">
                <?php 
                try {
                    // Requête pour récupérer les documents avec les informations des employés
                    $stmt = $conn1->prepare("
                        SELECT * from document NATURAL JOIN commande GROUP BY id_document
                    ");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {

                            $employe = $conn1->prepare("SELECT * FROM employé WHERE id = ? ");
                            $employe->bind_param('i',$row['id']);
                            $employe->execute();
                            $resemploye =$employe->get_result()->fetch_assoc();

                            $nom_document = $row['nom_document'];

                            $encrypted_id = encryptId($row['id_document']);
                            echo '<tr>';
                            echo '<td data-label="ID Document" class="document-id">' . htmlspecialchars($row['id_document'] ?? 'DOC' . $row['id']) . '</td>';
                            echo '<td  data-label="Nom Employé" class="employee-id service-name">' . htmlspecialchars($resemploye['nom'] . ' ' . $resemploye['prenom']) . '</td>';
                            // echo '<td data-label="Type Document">' . htmlspecialchars($row['type_document'] ?? 'N/A') . '</td>';
                            echo '<td data-label="Date Ajout">' . htmlspecialchars($row['date_ajout'] ?? date('Y-m-d')) . '</td>';
                            echo '<td data-label="Actions" class="actions">';
                            echo '<a href="view_document.php?nom_document='.$nom_document.'" class="btn btn-view">Voir Document</a>';
                            echo '<a href="delete/delete_rapport.php?id=' . $encrypted_id . '&token='.$token.'" class="btn btn-delete" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer ce document ?\')">Supprimer</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="5" style="text-align: center; padding: 20px;">Aucun document trouvé</td></tr>';
                    }
                } catch (Exception $e) {
                    echo '<tr><td colspan="5" style="text-align: center; padding: 20px; color: #e74c3c;">Erreur lors du chargement des documents</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal pour ajouter un document -->
<div class="modal-overlay" id="documentModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Ajouter un Document</h3>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>
        
        <form id="documentForm" action="insert/insert_document.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label" for="id_employe">Employé</label>
                <select id="id_employe" name="id_employe" class="form-select" required>
                    <option value="">Sélectionner un employé</option>
                    <?php 
                    try {
                        $stmt = $conn1->prepare("SELECT  distinct id, nom, prenom FROM employé natural join commande  ORDER BY nom, prenom ");
                        $stmt->execute();
                        $res = $stmt->get_result();
                        
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                echo '<option value="' . htmlspecialchars($row['id']) . '">' . 
                                     htmlspecialchars($row['nom'] . ' ' . $row['prenom']) . '</option>';
                            }
                        }
                    } catch (Exception $e) {
                        echo '<option value="">Erreur lors du chargement des employés</option>';
                    }
                    ?>
                </select>
            </div>
            

            
            <div class="form-group">
                <label class="form-label" for="document_file">Fichier Document</label>
                <div class="file-input-wrapper">
                    <input type="file" id="document_file" name="fichier_pdf" 
                           class="file-input" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" 
                           onchange="updateFileName(this)" required>
                    <label for="document_file" class="file-input-label" id="fileLabel">
                        📁 Cliquez pour sélectionner document
                    </label>
                </div>
            </div>

        

            
            <div class="form-actions">
                <button type="button" class="btn-cancel" onclick="closeModal()">Annuler</button>
                <button type="submit" class="btn-submit">Ajouter Document</button>
            </div>
        </form>
    </div>
</div>

<script>



document.addEventListener("DOMContentLoaded", function () {
  // ===== SYSTÈME DE RECHERCHE SIMPLE =====

  const searchInput = document.getElementById("search");
  const tableBody = document.getElementById("serviceTableBody");

  searchInput.addEventListener("input", function () {
    const searchTerm = this.value.toLowerCase().trim();
    const rows = tableBody.querySelectorAll("tr");

    rows.forEach((row) => {
      const serviceName = row.querySelector(".service-name");
      if (serviceName) {
        console.log("hello");
        const text = serviceName.textContent.toLowerCase();
        if (text.includes(searchTerm) || searchTerm === "") {
          row.style.display = "";
        } else {
          row.style.display = "none";
        }
      }
    });
  });
  // end search
});







    // start jquery EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27
        $(document).ready(function() {
                    $('#id_employe').select2({
                        placeholder: "Sélectionner un employé...",
                        allowClear: true,
                        width : '100%',
                        language: {
                        noResults: function() {
                            return "Aucun employé trouvé"; // ← Ton message personnalisé
                        }
                    }

                    });
    
                    });
    // end jquery search
    function openModal() {
        document.getElementById('documentModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('documentModal').classList.remove('active');
        document.body.style.overflow = 'auto';
        document.getElementById('documentForm').reset();
        updateFileLabel();
    }

    function updateFileName(input) {
        updateFileLabel();
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const label = document.getElementById('fileLabel');
            
            // Vérifier la taille du fichier (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                alert('Le fichier est trop volumineux. Taille maximum: 5MB');
                input.value = '';
                updateFileLabel();
                return;
            }
            
            label.textContent = '📄 ' + file.name;
            label.classList.add('has-file');
        }
    }

    function updateFileLabel() {
        const label = document.getElementById('fileLabel');
        const input = document.getElementById('document_file');
        
        if (!input.files || !input.files[0]) {
            label.textContent = '📁 Cliquez pour sélectionner un fichier';
            label.classList.remove('has-file');
        }
    }

    // Fermer le modal en cliquant sur l'overlay
    document.getElementById('documentModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Fermer le modal avec la touche Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('documentModal');
            if (modal.classList.contains('active')) {
                closeModal();
            }
        }
    });

    // Validation du formulaire
    document.getElementById('documentForm').addEventListener('submit', function(e) {
        const employe = document.getElementById('id_employe').value;
        const typeDoc = document.getElementById('type_document').value;
        const fichier = document.getElementById('document_file').files[0];
        
        if (!employe || !typeDoc || !fichier) {
            e.preventDefault();
            alert('Veuillez remplir tous les champs obligatoires.');
            return false;
        }
        
        // Afficher un indicateur de chargement
        const submitBtn = this.querySelector('.btn-submit');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Ajout en cours...';
        submitBtn.disabled = true;
        
        // Restaurer le bouton en cas d'erreur
        setTimeout(() => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }, 5000);
    });






 document.addEventListener("DOMContentLoaded", function () {   
  // ===== SYSTÈME DE RECHERCHE SIMPLE =====

  const searchInput = document.getElementById("search");
  const tableBody = document.getElementById("serviceTableBody");

  searchInput.addEventListener("input", function () {
    const searchTerm = this.value.toLowerCase().trim();
    const rows = tableBody.querySelectorAll("tr");
   
    
    rows.forEach((row) => {
      const serviceName = row.querySelector(".service-name");
      if (serviceName) {
         console.log("hello");
        const text = serviceName.textContent.toLowerCase();
        if (text.includes(searchTerm) || searchTerm === "") {
          row.style.display = "";
        } else {
          row.style.display = "none";
        }
      }
    });
  });
});
  // end search



</script>

</div>

<?php include "footer.php"; ?>