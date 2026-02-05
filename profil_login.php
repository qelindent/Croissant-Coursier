<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $userData = $stmt->fetch();

    if ($userData && password_verify($password, $userData['password'])) {
        // ✅ Stockage complet des infos dans la session
        $_SESSION['user'] = $userData['email'];
        $_SESSION['prenom'] = $userData['prenom'];
        $_SESSION['is_admin'] = ($userData['role'] === 'admin');
        $_SESSION['bakery_admin'] = $userData['boulangerie'] ?? null;

        // ✅ Redirection
        header("Location: index.php");
        exit;
    } else {
        // Erreur de connexion
        $_SESSION['login_error'] = "Email ou mot de passe incorrect.";
        header("Location: index.php");
        exit;
    }
}
?>
