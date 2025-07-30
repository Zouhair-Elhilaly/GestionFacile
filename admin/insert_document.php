<?php 
include "../include/config.php"; 
session_start(); 

// Crée le dossier s'il n'existe pas
if (!is_dir('pdf')) {
    mkdir('pdf', 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["fichier_pdf"])) {
    $file = $_FILES["fichier_pdf"];
    $id_employe = filter_input(INPUT_POST, 'id_employe', FILTER_SANITIZE_NUMBER_INT);
    
    // Vérifie si c'est un PDF
    if ($file["type"] === "application/pdf") {
        $nom_fichier = basename( $file["name"]);
        $chemin = "pdf/". $nom_fichier;
        
        if (move_uploaded_file($file["tmp_name"], $chemin)) {
            
            // CORRECTION 1: Commencer une transaction pour garantir la cohérence
            $conn1->begin_transaction();
            
            try {
                // Enregistre en base
                $stmt = $conn1->prepare("INSERT INTO document (nom_document) VALUES (?)");
                $stmt->bind_param("s", $nom_fichier);
                $stmt->execute();
                
                // CORRECTION 2: Utiliser last_insert_id() au lieu de SELECT
                $id_document_insere = $conn1->insert_id;
                
                // CORRECTION 3: Mettre à jour les commandes de cet employé qui sont confirmées et utilisées mais sans document
                $status = 'Confirmée';
                $utilisee = 1;



                $requete = "
                        UPDATE commande 
                        JOIN document ON document.id_document = ?
                        SET commande.id_document = ?
                        WHERE 
                            commande.id = ? 
                            AND commande.status = ? 
                            AND commande.utilisee = ? 
                            AND commande.id_document IS NULL 
                            AND document.nom_document = ?
                    ";

                
                // $update = $conn1->prepare("UPDATE commande SET id_document = ? WHERE id = ? AND status = ? AND utilisee = ? AND id_document IS NULL");
                
                $update = $conn1->prepare($requete);
                $update->bind_param('iiisis', $id_document_insere,$id_document_insere, $id_employe, $status, $utilisee,$nom_fichier);
                $update->execute();
                
                // CORRECTION 4: Vérifier si des lignes ont été affectées
                $lignes_affectees = $update->affected_rows;
                
                if ($lignes_affectees > 0) {
                    // Valider la transaction
                    $conn1->commit();
                    
                    $_SESSION['ajout_pdf'] = [
                        'type' => 'success',
                        'msg' => "Fichier PDF téléchargé avec succès. $lignes_affectees commande(s) mise(s) à jour."
                    ];
                } else {
                    // Annuler la transaction si aucune commande n'a été mise à jour
                    $conn1->rollback();
                    
                    $_SESSION['ajout_pdf'] = [
                        'type' => 'warning',
                        'msg' => "PDF téléchargé mais aucune commande correspondante trouvée pour cet employé."
                    ];
                }
                
                $stmt->close();
                $update->close();
                
            } catch (Exception $e) {
                // Annuler la transaction en cas d'erreur
                $conn1->rollback();
                
                $_SESSION['ajout_pdf'] = [
                    'type' => 'error',
                    'msg' => "Erreur lors de l'enregistrement : " . $e->getMessage()
                ];
            }
            
        } else {
            $_SESSION['ajout_pdf'] = [
                'type' => 'error',
                'msg' => "Erreur lors du téléchargement"
            ];
        }
    } else {
        $_SESSION['ajout_pdf'] = [
            'type' => 'error',
            'msg' => "Veuillez sélectionner un fichier PDF."
        ];
    }
}

header("location:view_rapport.php");
exit;
?>