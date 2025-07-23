<?php
session_start();

// Vérifier si l'utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Vérifier si l'utilisateur est admin
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// Rediriger vers login si pas connecté
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

// Rediriger vers login si pas admin
function requireAdmin() {
    if (!isAdmin()) {
        header("Location: dashboard.php");
        exit();
    }
}

// Déconnexion
function logout() {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Récupérer les infos de l'utilisateur connecté
function getCurrentUser() {
    if (isLoggedIn()) {
        return [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'email' => $_SESSION['user_email'],
            'role' => $_SESSION['user_role']
        ];
    }
    return null;
}

// Créer une session utilisateur
function createUserSession($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];
}
?>