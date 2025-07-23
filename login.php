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
    <style>
        .login {
            background-color: #722F37;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Geist', sans-serif;
        }
        .login-container {
            background: rgba(138, 107, 107, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 2rem;
            width: 100%;
            max-width: 500px;
            backdrop-filter: blur(10px);
            color: white;
            box-shadow: 6px 6px 20px rgba(0, 0, 0, 0.6);
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header .logo {
            height: 80px;
            margin-bottom: 1rem;
        }
        .form-group {
            margin-bottom: 1.2rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            box-sizing: border-box;
        }
        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        .btn-primary {
            width: 100%;
            padding: 1rem;
            background-color: #8a6b6b;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            margin-bottom: 1rem;
        }
        .btn-primary:hover {
            background-color: rgba(138, 107, 107, 0.8);
        }
        .login-links {
            text-align: center;
        }
        .login-links a {
            color: white;
            text-decoration: underline;
        }
        .message {
            padding: 0.8rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: none;
        }
        .message.success {
            background: rgba(0, 255, 0, 0.2);
            border: 1px solid rgba(0, 255, 0, 0.5);
        }
        .message.error {
            background: rgba(255, 0, 0, 0.2);
            border: 1px solid rgba(255, 0, 0, 0.5);
        }
    </style>
</head>
<body class="login">
    <div class="login-container">
        <div class="login-header">
            <img src="assets/img/logo-large.png" alt="MyCave Logo" class="logo">
            <h1>Bienvenue dans MyCave</h1>
            <p>Connectez-vous pour gérer votre cave à vin</p>
        </div>

        <div id="message" class="message"></div>

        <form id="loginForm" class="login-form">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="votre@email.com" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Votre mot de passe" required>
            </div>

            <button type="submit" class="btn-primary">Se connecter</button>

            <div class="login-links">
                <p>Pas encore de compte ? <a href="#" onclick="showRegister()">Créer un compte</a></p>
            </div>
        </form>

        <form id="registerForm" class="register-form" style="display: none;">
            <div class="form-group">
                <label for="reg-name">Nom complet</label>
                <input type="text" id="reg-name" name="name" placeholder="Votre nom" required>
            </div>

            <div class="form-group">
                <label for="reg-email">Email</label>
                <input type="email" id="reg-email" name="email" placeholder="votre@email.com" required>
            </div>

            <div class="form-group">
                <label for="reg-password">Mot de passe</label>
                <input type="password" id="reg-password" name="password" placeholder="Minimum 6 caractères" required minlength="6">
            </div>

            <button type="submit" class="btn-primary">Créer un compte</button>

            <div class="login-links">
                <p>Déjà un compte ? <a href="#" onclick="showLogin()">Se connecter</a></p>
            </div>
        </form>

        <div style="margin-top: 2rem; padding: 1rem; background: rgba(0,0,0,0.3); border-radius: 8px; font-size: 0.9rem;">
            <strong>Comptes de test :</strong><br>
            • <strong>Utilisateur :</strong> didier@mycave.com / password<br>
            • <strong>Admin :</strong> admin@mycave.com / password
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
                showMessage('Erreur de connexion au serveur', 'error');
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
                showMessage('Erreur de connexion au serveur', 'error');
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
    </script>
</body>
</html>