<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion Stock</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CSS SweetAlert2 -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<!-- JS SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style_index.css">
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <!-- Animated Neural Network Lines -->
<svg class="network">
    <line x1="0" y1="50" x2="200" y2="100" stroke-dasharray="10 5"/>
    <line x1="200" y1="100" x2="400" y2="50" stroke-dasharray="10 5"/>
    <line x1="400" y1="50" x2="600" y2="120" stroke-dasharray="10 5"/>
    <line x1="600" y1="120" x2="800" y2="60" stroke-dasharray="10 5"/>
</svg>

    <!-- Conteneur principal -->
    <div class="glass-card rounded-2xl p-4 w-full max-w-md slide-in">
        <!-- Logo et titre -->
        <div class="text-center mb-8" style="">
            <div class="inline-block  bg-gradient-to-r from-blue-500 to-purple-600 rounded-full mb-4" style="width: 100px;position:relative">
                <img style="width: 100% ; border-radius: 50% ; background-color:white" src="uploads/logo.png" alt="">
            </div>
            <h1 class="gestion-facil-title">
                Gestion Facile
            </h1>
            
            <p class="titleStyle text-sm">
                Connectez-vous à votre espace de gestion
            </p>
        </div>

        <!-- Message d'erreur global -->
        <?php if (isset($_SESSION['user']) && $_SESSION['user'] != ''): ?>
            <div class="error-message bg-red-50 border border-red-200 rounded-lg p-3 mb-6 flex items-center">
                <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                <span class="text-red-700 text-sm"><?= htmlspecialchars($_SESSION['user']) ?></span>
            </div>
        <?php endif; 
        
        // start alert reset password
        if(isset($_SESSION['reset_password'])){
            if($_SESSION['reset_password'] != ''){
                ?>
                    <script>
                    Swal.fire({
                        title: 'Notification',
                        text: '<?= $_SESSION['reset_password']['msg']?>',
                        icon: '<?= $_SESSION['reset_password']['type']?>',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK',
                        timer: 3000,
                        backdrop: 'rgba(0,0,0,0.4)'
                    })
                    </script>
                <?php
                unset($_SESSION['reset_password']);
            }
        }
        
        
        
        
        ?>

        <!-- Formulaire de connexion -->
        <form class="space-y-6" action="check/check.php"  method="POST">
            <!-- Champ Email -->
            <div class="input-group">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-envelope mr-2 text-blue-500"></i>
                    Adresse email
                </label>
                <div class="relative">
                    <i></i>
                    <input 
                        type="text" 
                        name="email" 
                        id="email" 
                        value="<?= isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '' ?>"
                        class="input-field w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                        placeholder="votre@email.com"
                        required
                    >
                </div>
                <?php if (isset($_SESSION['email_error'])): ?>
                    <div class="error-message flex items-center mt-2 text-red-600 text-sm">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <?= htmlspecialchars($_SESSION['email_error']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Champ Mot de passe -->
            <div class="input-group">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock mr-2 text-blue-500"></i>
                    Mot de passe
                </label>
                <div class="relative">
                    <i></i>
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        value="<?= isset($_SESSION['password']) ? htmlspecialchars($_SESSION['password']) : '' ?>"
                        class="input-field w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                        placeholder="••••••••"
                        required
                    >
                    <button 
                        type="button" 
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                        onclick="togglePassword()"
                    >
                        <i class="fas fa-eye" id="password-toggle"></i>
                    </button>
                </div>
                <?php if (isset($_SESSION['password_error'])): ?>
                    <div class="error-message flex items-center mt-2 text-red-600 text-sm">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <?= htmlspecialchars($_SESSION['password_error']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Options -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input 
                        id="remember" 
                        name="remember" 
                        type="checkbox" 
                        class="checkbox-custom"
                    >
                    <label for="remember" class="ml-3 text-sm text-gray-600 cursor-pointer">
                        Se souvenir de moi
                    </label>
                </div>
                <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors duration-300" onclick="openForgotPasswordModal()">
                    <i class="fas fa-question-circle mr-1"></i>
                    Mot de passe oublié ?
                </a>
            </div>

            <!-- Bouton de connexion -->
            <button 
                type="submit" 
                class="btn-primary w-full py-3 px-4 rounded-lg text-white font-medium text-sm flex items-center justify-center space-x-2 hover:scale-105 transition-all duration-300"
            >
                <i class="fas fa-sign-in-alt"></i>
                <span>Se connecter</span>
            </button>
        </form>

        <!-- Pied de page -->
        <div class="mt-8 text-center">
            <p class="titleStyle text-xs flex items-center justify-center">
                <i class="fas fa-shield-alt mr-2"></i>
                Connexion sécurisée SSL
            </p>
        </div>
    </div>










    <!-- Modal Mot de passe oublié - Version simplifiée -->
    <div id="forgotPasswordModal" class="modal-overlay">
        <div class="modal-content">
            <div class="text-center mb-6">
                <div class="inline-block p-3 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full mb-4">
                    <i class="fas fa-key text-2xl text-white"></i>
                </div>
                <h2 class="text-xl font-bold titleStyle mb-2">
                    Réinitialisation du mot de passe
                </h2>
                <p class="text-gray-600 text-sm mb-6">
                    Entrez votre adresse email pour recevoir un lien de réinitialisation
                </p>
                
                <form id="forgotPasswordForm" action="resend_code.php" method="POST" class="px-4">
                    <div class="mb-4">
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input 
                                type="email" 
                                id="reset_email" 
                                name="reset_email"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                                placeholder="votre@email.com"
                                required
                            >

                        </div>
                        <div id="emailError" class="hidden mt-2 text-red-600 text-sm text-left">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            <span></span>
                        </div>
                    </div>
                    <?php include "admin/functions/chiffre.php";?>
                    <input type="hidden" name="token" value="<?= encryptId("hello") ?>">
                    <button 
                        type="submit" 
                        class="w-full py-3 px-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-medium rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all duration-300"
                    >
                        <i class="fas fa-paper-plane mr-2"></i>
                        Envoyer le lien
                    </button>
                </form>
            </div>
            
            <div class="text-center mt-4">
                <button 
                    onclick="closeForgotPasswordModal()"
                    class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors duration-300"
                >
                    <i class="fas fa-arrow-left mr-1"></i>
                    Retour à la connexion
                </button>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('password-toggle');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Gestion du modal mot de passe oublié
        function openForgotPasswordModal() {
            document.getElementById('forgotPasswordModal').classList.add('active');
        }

        function closeForgotPasswordModal() {
            document.getElementById('forgotPasswordModal').classList.remove('active');
            document.getElementById('forgotPasswordForm').reset();
            document.getElementById('emailError').classList.add('hidden');
        }

        function submitForgotPassword(e) {
            // e.preventDefault();
            const email = document.getElementById('reset_email').value;
            const emailError = document.getElementById('emailError');
            
            // Validation simple
            if (!email.includes('@') || !email.includes('.')) {
                emailError.querySelector('span').textContent = "Veuillez entrer une adresse email valide";
                emailError.classList.remove('hidden');
                return;
            }
            
            // Ici vous ajouteriez votre logique d'envoi d'email
            // Pour l'exemple, on simule un envoi réussi
            emailError.classList.add('hidden');
            
            // Afficher un message de succès
            // alert("Un lien de réinitialisation a été envoyé à " + email);
            // closeForgotPasswordModal();
        }
        
        // Associer la fonction de soumission au formulaire
        document.getElementById('forgotPasswordForm').addEventListener('submit', submitForgotPassword);

        // Validation en temps réel
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');

            emailInput.addEventListener('input', function() {
                validateEmail(this);
            });

            passwordInput.addEventListener('input', function() {
                validatePassword(this);
            });
        });

        function validateEmail(input) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const isValid = emailRegex.test(input.value);
            
            if (input.value.length > 0) {
                if (isValid) {
                    input.classList.remove('border-red-300');
                    input.classList.add('border-green-300');
                } else {
                    input.classList.remove('border-green-300');
                    input.classList.add('border-red-300');
                }
            } else {
                input.classList.remove('border-red-300', 'border-green-300');
            }
        }

        function validatePassword(input) {
            const isValid = input.value.length >= 6;
            
            if (input.value.length > 0) {
                if (isValid) {
                    input.classList.remove('border-red-300');
                    input.classList.add('border-green-300');
                } else {
                    input.classList.remove('border-green-300');
                    input.classList.add('border-red-300');
                }
            } else {
                input.classList.remove('border-red-300', 'border-green-300');
            }
        }
    </script>

    <?php 
    // Nettoyage des sessions après affichage
    unset($_SESSION['password_error']); 
    unset($_SESSION['user']); 
    unset($_SESSION['email_error']); 
    unset($_SESSION['email']); 
    unset($_SESSION['password']); 
    ?>



    <style>
        body {
            background: 
                linear-gradient(rgba(106,17,203,0.6), rgba(37,117,252,0.6)),
                url('uploads/Gemini_bg1.png') center/cover no-repeat;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            /* background: linear-gradient(120deg, #4b6cb7, #182848); */
            overflow: hidden;
            font-family: 'Arial', sans-serif;
        }

        /* Animation background neural network */
        .network {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .network line {
            stroke: rgba(255,255,255,0.1);
            stroke-width: 2;
            animation: move 8s infinite linear;
        }

        @keyframes move {
            0% { stroke-dashoffset: 1000; }
            100% { stroke-dashoffset: 0; }
        }


                    .gestion-facil-title {
                        font-family: 'VotrePolice', sans-serif;
                        font-size: 2rem;
                        font-weight: bold;
                        background: linear-gradient(to right, #CD0BF4, #00A3FF);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        color: transparent; /* Fallback color for browsers that don't support the gradient */
                        }
                        .titleStyle{
                            font-family: 'VotrePolice', sans-serif;
                        background: linear-gradient(to right, #CD0BF4, #00A3FF);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        color: transparent; 
                        }


          @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .modal-content {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.8);
            background: white;
            border-radius: 12px;
            padding: 2rem;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        
        .modal-overlay.active .modal-content {
            transform: translate(-50%, -50%) scale(1);
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
        }
        
        .input-group {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            z-index: 10;
        }
        
        .input-field {
            padding-left: 45px;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
        }
        
        .slide-in {
            animation: slideIn 0.8s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .error-message {
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .checkbox-custom {
            appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid #d1d5db;
            border-radius: 4px;
            background-color: white;
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .checkbox-custom:checked {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
        }
        
        .checkbox-custom:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 12px;
            font-weight: bold;
        }


     </style>



</body>
</html>