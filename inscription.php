<?php
ob_start();
session_start();
require_once 'db.php';
require_once 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $pays = $_POST['pays'];
    $region = $_POST['region'];
    $ville = $_POST['ville'];
    $rue = $_POST['rue'];
    $carte = $_POST['carte'];
    $expiration = $_POST['expiration'];
    $ccv = $_POST['ccv'];

    // Insertion de l'utilisateur
    $stmt = $pdo->prepare("
        INSERT INTO users (
            email, password, nom, prenom, pays, region, ville, rue, carte, expiration, ccv
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $email, $password, $nom, $prenom, $pays, $region, $ville, $rue, $carte, $expiration, $ccv
    ]);

    // Récupération des données utilisateur fraîchement enregistrées
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $userData = $stmt->fetch();

    // Connexion immédiate
    $_SESSION['user'] = $email;
    $_SESSION['prenom'] = $userData['prenom'];
    $_SESSION['is_admin'] = ($userData['role'] ?? '') === 'admin';
    $_SESSION['bakery_admin'] = $userData['boulangerie'] ?? null;

    // Message de bienvenue
    $_SESSION['welcome'] = "Bienvenue " . $userData['prenom'] . ", votre compte a été créé avec succès !";

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
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
    <h2>Inscription</h2>
    <form method="POST" class="row g-3">
        <div class="col-md-6">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label>Mot de passe</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label>Nom</label>
            <input type="text" name="nom" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label>Prénom</label>
            <input type="text" name="prenom" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label>Pays</label>
            <input type="text" name="pays" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label>Région</label>
            <input type="text" name="region" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label>Ville</label>
            <input type="text" name="ville" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label>Rue</label>
            <input type="text" name="rue" class="form-control" required>
        </div>
        <div class="col-12">
            <button type="submit" class="btn" style="background-color: rgb(226, 186, 111);">Créer le compte</button>
        </div>
    </form>
</div>

</main>

<?php require_once 'footer.php'; ?>
<?php ob_end_flush(); ?>

</body>
</html>