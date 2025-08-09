        // Variables globales
        const email = document.getElementById("employeeEmail");
        const password = document.getElementById("employeePassword");
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        // Fonction de validation de l'email en temps réel
        function checkEmail() {
            const emailValue = email.value.trim();
            if (emailValue === '') {
                email.style.border = "2px solid #ddd";
                return;
            }
            
            if (emailRegex.test(emailValue)) {
                email.style.border = "2px solid green";
            } else {
                email.style.border = "2px solid red";
            }
        }

        // Fonction principale de validation et soumission
        function validateData() {
            const emailValue = email.value.trim();
            const passwordValue = password.value.trim();

            // Validation de l'email
            if (!emailRegex.test(emailValue)) {
                Swal.fire({
                    icon: "error",
                    title: "Erreur !",
                    text: "Veuillez saisir une adresse email valide",
                    timer: 3000,
                    showConfirmButton: false
                });
                return;
            }

            // Validation du mot de passe
            if (passwordValue.length < 4) {
                Swal.fire({
                    icon: "error",
                    title: "Erreur !",
                    text: "Le mot de passe doit contenir au moins 6 caractères",
                    timer: 3000,
                    showConfirmButton: false
                });
                return;
            }

            // Désactiver le bouton pendant la requête
            const submitBtn = document.querySelector('.btn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement...';

            // Envoi des données
            fetch("insert/insert_email_app.php", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    email: emailValue,
                    password: passwordValue
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Réponse serveur:', data);
                
                if (data.status === 'success') {
                    Swal.fire({
                        icon: "success",
                        title: "Succès !",
                        text: "Email et mot de passe enregistrés avec succès !",
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        // Optionnel: redirection ou actualisation
                        // window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Erreur !",
                        text: data.message || "Une erreur est survenue",
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                Swal.fire({
                    icon: "error",
                    title: "Erreur de connexion !",
                    text: "Impossible de contacter le serveur",
                    timer: 3000,
                    showConfirmButton: false
                });
            })
            .finally(() => {
                // Réactiver le bouton
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-key"></i> Ajouter Email Provincial';
            });
        }

        // Validation en temps réel du mot de passe
        password.addEventListener('input', function() {
            const passwordValue = this.value.trim();
            if (passwordValue.length >= 6) {
                this.style.border = "2px solid green";
            } else if (passwordValue.length > 0) {
                this.style.border = "2px solid red";
            } else {
                this.style.border = "2px solid #ddd";
            }
        });

        // Soumission du formulaire avec Enter
        document.getElementById('emailForm').addEventListener('submit', function(e) {
            e.preventDefault();
            validateData();
        });
