<?php
require_once 'db.php';
require_once 'navbar.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $new_password = $_POST['new_password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Adresse email invalide.";
    } elseif (strlen($new_password) < 6) {
        $message = "Le mot de passe doit contenir au moins 6 caractères.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
            $update->execute([$hashed, $email]);
            $message = "✅ Mot de passe mis à jour avec succès.";
        } else {
            $message = "❌ Aucun compte trouvé avec cette adresse.";
        }
    }
}
?>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Fredoka&display=swap" rel="stylesheet">
  <style>
    html, body {
      height: 100%;
      margin: 0;
    }

    body {
      font-family: 'Fredoka', sans-serif;
      font-weight: bold;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    main {
      flex: 1;
    }
  </style>
  <main>
<body>
<div class="container mt-5 text-center">
    <h2 class="mb-4">Réinitialiser votre mot de passe</h2>

    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" class="w-50 mx-auto">
        <div class="mb-3">
            <label class="form-label">Adresse email</label>
            <input type="email" name="email" class="form-control" required placeholder="Votre adresse email">
        </div>
        <div class="mb-3">
            <label class="form-label">Nouveau mot de passe</label>
            <input type="password" name="new_password" class="form-control" required placeholder="Nouveau mot de passe">
        </div>
        <button type="submit" class="btn" style="background-color: rgb(226, 186, 111);">Réinitialiser le mot de passe</button>
    </form>
</div>

</main>

<?php require_once 'footer.php'; ?>

</body>
</html>