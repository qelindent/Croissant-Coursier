<?php
session_start();
require_once 'db.php';
require_once 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');
    // Ici vous pouvez envoyer un email ou sauvegarder dans une base de données
    $confirmation = "Merci $nom, votre message a bien été envoyé.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contactez-nous</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
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
</head>
<body>
<main>
<div class="container mt-5">
    <h2>Contactez-nous</h2>
    <?php if (!empty($confirmation)): ?>
        <div class="alert alert-success"><?= $confirmation ?></div>
    <?php endif; ?>
    <form method="POST" class="row g-3">
        <div class="col-md-6">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" name="nom" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="col-12">
            <label for="message" class="form-label">Message</label>
            <textarea name="message" rows="5" class="form-control" required></textarea>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </div>
    </form>
</div>

</main>

<?php require_once 'footer.php'; ?>

</body>
</html>