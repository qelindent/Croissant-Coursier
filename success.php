<?php
session_start();
require_once 'navbar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Paiement réussi</title>
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
<main>
<body class="bg-light">
  <div class="container py-5 text-center">
    <h2>Paiement effectué avec succès !</h2>
    <p>Merci pour votre commande.</p>
    <a href="index.php" class="btn" style="background-color: rgb(226, 186, 111);">Retour à l'accueil</a>
  </div>
</body>
</html>

</main>

<?php require_once 'footer.php'; ?>

</body>
</html>