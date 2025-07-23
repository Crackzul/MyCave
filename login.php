<?php
require_once 'includes/session.php';

// Si déjà connecté, rediriger vers le dashboard
if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - MyCave</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <img src="assets/img/logo-large.png" alt="MyCave Logo" class="logo">
                <h1>Bienvenue dans MyCave</h1>
                <p>Connectez-vous pour gérer votre cave à vin</p>
            </div>

            <form id="loginForm" class="login-form">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn-primary">Se connecter</button>

                <div class="login-links">
                    <p>Pas encore de compte ? <a href="#" onclick="showRegister()">Créer un compte</a></p>
                </div>
            </form>

            <form id="registerForm" class="register-form" style="display: none;">
                <div class="form-group">
                    <label for="reg-name">Nom complet</label>
                    <input type="text" id="reg-name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="reg-email">Email</label>
                    <input type="email" id="reg-email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="reg-password">Mot de passe</label>
                    <input type="password" id="reg-password" name="password" required minlength="6">
                </div>

                <button type="submit" class="btn-primary">Créer un compte</button>

                <div class="login-links">
                    <p>Déjà un compte ? <a href="#" onclick="showLogin()">Se connecter</a></p>
                </div>
            </form>

            <div id="message" class="message"></div>
        </div>
    </div>

    <script>
        // Gestion du formulaire de connexion
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            formData.append('action', 'login');
            
            try {
                const response = await fetch('api/auth.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showMessage('Connexion réussie ! Redirection...', 'success');
                    setTimeout(() => {
                        window.location.href = 'dashboard.php';
                    }, 1000);
                } else {
                    showMessage(data.error || 'Erreur de connexion', 'error');
                }
            } catch (error) {
                showMessage('Erreur de connexion', 'error');
            }
        });

        // Gestion du formulaire d'inscription
        document.getElementById('registerForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            formData.append('action', 'register');
            
            try {
                const response = await fetch('api/auth.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showMessage('Inscription réussie ! Redirection...', 'success');
                    setTimeout(() => {
                        window.location.href = 'dashboard.php';
                    }, 1000);
                } else {
                    showMessage(data.error || 'Erreur d\'inscription', 'error');
                }
            } catch (error) {
                showMessage('Erreur d\'inscription', 'error');
            }
        });

        function showLogin() {
            document.getElementById('loginForm').style.display = 'block';
            document.getElementById('registerForm').style.display = 'none';
            document.querySelector('.login-header h1').textContent = 'Bienvenue dans MyCave';
            document.querySelector('.login-header p').textContent = 'Connectez-vous pour gérer votre cave à vin';
        }

        function showRegister() {
            document.getElementById('loginForm').style.display = 'none';
            document.getElementById('registerForm').style.display = 'block';
            document.querySelector('.login-header h1').textContent = 'Créer un compte';
            document.querySelector('.login-header p').textContent = 'Rejoignez MyCave pour gérer votre cave à vin';
        }

        function showMessage(message, type) {
            const messageDiv = document.getElementById('message');
            messageDiv.textContent = message;
            messageDiv.className = `message ${type}`;
            messageDiv.style.display = 'block';
            
            setTimeout(() => {
                messageDiv.style.display = 'none';
            }, 5000);
        }

        // Comptes de test disponibles
        console.log('Comptes de test disponibles :');
        console.log('Admin: admin@mycave.com / password');
        console.log('User: didier@mycave.com / password');
    </script>
</body>
</html>