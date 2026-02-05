<?php
session_start();
require_once 'db.php';
require_once 'navbar.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mentions légales</title>
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

<main class="container mt-5">
  <h2>Mentions légales</h2>
  <p><strong>Nom de la société :</strong> Croissant Coursier</p>
  <p><strong>Adresse :</strong> 123 Rue du Pain, 75000 Paris, France</p>
  <p><strong>Téléphone :</strong> 03 89 65 67 89</p>
  <p><strong>Email :</strong> contact@croissant-coursier.fr</p>
  <p><strong>Responsable de la publication :</strong> Mohammed Bannour</p>
  <p><strong>Hébergeur :</strong> Laragon - Serveur local</p>
</main>

<?php require_once 'footer.php'; ?>

</body>
</html>