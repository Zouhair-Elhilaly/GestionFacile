<?php 
include "../include/config.php";
include "header.php";
include "navbar.php";
session_start();

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

 <div class="table-header">
        <h2>Gestion des Documents</h2>
        <p>Liste des documents par employé</p>
    </div>

<style>
    .container {
        max-width: 1200px;
        margin: 0 auto;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .table-header {

        padding: 20px;
        text-align: center;
    }

    .table-header h2 {
        font-size: 24px;
        margin-bottom: 5px;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 600px;
    }

    thead {
        background: #34495e;
        color: white;
    }

    th, td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 1px;
    }

    tbody tr {
        transition: background-color 0.3s ease;
    }

    tbody tr:hover {
        background-color: #f8f9fa;
    }

    tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tbody tr:nth-child(even):hover {
        background-color: #e8f4f8;
    }

    .btn {
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        margin: 0 3px;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .btn-view {
        background: #3498db;
        color: white;
    }

    .btn-view:hover {
        background: #2980b9;
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(52, 152, 219, 0.3);
    }

    .btn-delete {
        background: #e74c3c;
        color: white;
    }

    .btn-delete:hover {
        background: #c0392b;
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(231, 76, 60, 0.3);
    }

    .actions {
        white-space: nowrap;
    }

    .document-id {
        font-weight: 600;
        color: #2c3e50;
    }

    .employee-id {
        color: #7f8c8d;
        font-style: italic;
    }

    .add-btn {
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 20px;
        transition: all 0.3s ease;
        box-shadow: 0 3px 10px rgba(39, 174, 96, 0.3);
    }

    .add-btn:hover {
        background: linear-gradient(135deg, #229954, #27ae60);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(39, 174, 96, 0.4);
    }

    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        backdrop-filter: blur(5px);
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        background: white;
        padding: 30px;
        border-radius: 15px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        transform: scale(0.7);
        opacity: 0;
        transition: all 0.3s ease;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-overlay.active .modal-content {
        transform: scale(1);
        opacity: 1;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #ecf0f1;
    }

    .modal-title {
        font-size: 24px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }

    .close-btn {
        background: none;
        border: none;
        font-size: 28px;
        cursor: pointer;
        color: #95a5a6;
        transition: color 0.3s ease;
        padding: 0;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .close-btn:hover {
        color: #e74c3c;
        background: #fdeaea;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #2c3e50;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-select {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #ecf0f1;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #fafafa;
    }

    .form-select:focus {
        outline: none;
        border-color: #3498db;
        background: white;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    }

    .file-input-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
        width: 100%;
    }

    .file-input {
        position: absolute;
        left: -9999px;
    }

    .file-input-label {
        display: block;
        padding: 12px 15px;
        border: 2px dashed #bdc3c7;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fafafa;
        color: #7f8c8d;
    }

    .file-input-label:hover {
        border-color: #3498db;
        background: #f8f9fa;
        color: #3498db;
    }

    .file-input-label.has-file {
        border-color: #27ae60;
        background: #f1f8e9;
        color: #27ae60;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 25px;
        justify-content: flex-end;
    }

    .btn-submit {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        background: linear-gradient(135deg, #2980b9, #21618c);
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(52, 152, 219, 0.3);
    }

    .btn-cancel {
        background: #95a5a6;
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background: #7f8c8d;
        transform: translateY(-1px);
    }

    .alert {
        padding: 15px;
        margin: 20px;
        border-radius: 8px;
        font-weight: 500;
    }

    .alert-success {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }

    .alert-error {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .container {
            margin: 0;
            border-radius: 0;
        }

        .table-wrapper {
            padding: 0;
        }

        table, thead, tbody, th, td, tr {
            display: block;
        }

        thead tr {
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        tbody tr {
            border: 2px solid #e3e3e3;
            margin-bottom: 15px;
            padding: 15px;
            border-radius: 12px;
            background: white;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            position: relative;
        }

        td {
            border: none;
            position: relative;
            padding: 12px 12px 12px 40%;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
            min-height: 50px;
            display: flex;
            align-items: center;
        }

        td:before {
            content: attr(data-label);
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 35%;
            padding-right: 10px;
            white-space: nowrap;
            font-weight: 700;
            color: #2c3e50;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .actions {
            padding-left: 12px !important;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: flex-start;
        }

        .modal-content {
            width: 95%;
            padding: 20px;
            margin: 10px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-submit, .btn-cancel {
            width: 100%;
            margin: 0;
        }
    }
</style>

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
    
    <button class="add-btn" onclick="openModal()">
        <i>+</i> Ajouter un Document
    </button>
    
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
            <tbody>
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

                            $encrypted_id = function_exists('encryptId') ? encryptId($row['id']) : $row['id'];
                            echo '<tr>';
                            echo '<td data-label="ID Document" class="document-id">' . htmlspecialchars($row['id_document'] ?? 'DOC' . $row['id']) . '</td>';
                            echo '<td data-label="Nom Employé" class="employee-id">' . htmlspecialchars($resemploye['nom'] . ' ' . $resemploye['prenom']) . '</td>';
                            // echo '<td data-label="Type Document">' . htmlspecialchars($row['type_document'] ?? 'N/A') . '</td>';
                            // echo '<td data-label="Date Ajout">' . htmlspecialchars($row['date_creation'] ?? date('Y-m-d')) . '</td>';
                            echo '<td data-label="Actions" class="actions">';
                            echo '<a href="view_document.php?nom_document='.$nom_document.'" class="btn btn-view">Voir Document</a>';
                            echo '<a href="delete_document.php?id=' . $encrypted_id . '" class="btn btn-delete" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer ce document ?\')">Supprimer</a>';
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
        
        <form id="documentForm" action="insert_document.php" method="POST" enctype="multipart/form-data">
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
</script>

</div>

<?php include "footer.php"; ?>