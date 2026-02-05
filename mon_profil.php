<?php
session_start();
require_once 'db.php';
require_once 'navbar.php';
function masquerCarte($numero) {
    // Ne garde que les 4 derniers chiffres si le numéro est assez long
    $numero = trim($numero);
    if (strlen($numero) >= 4) {
        $visible = substr($numero, -4);
        return '**** **** **** ' . $visible;
    } else {
        return '****';
    }
}

$email = $_SESSION['user'];
$success = false;

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $pays = $_POST['pays'] ?? '';
    $region = $_POST['region'] ?? '';
    $ville = $_POST['ville'] ?? '';
    $rue = $_POST['rue'] ?? '';
    $carte = $_POST['carte'] ?? '';
    $ccv = $_POST['ccv'] ?? '';
    $expiration = $_POST['expiration'] ?? '';

    $stmt = $pdo->prepare("UPDATE users SET nom = ?, prenom = ?, pays = ?, region = ?, ville = ?, rue = ?, carte = ?, ccv = ?, expiration = ? WHERE email = ?");
    $stmt->execute([$nom, $prenom, $pays, $region, $ville, $rue, $carte, $ccv, $expiration, $email]);

    $success = true;
}

// Récupération des données actualisées
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$data = $stmt->fetch();
?>
<title>Mon profil</title>
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
    <body>
    <main>
<div class="container mt-5">
    <h2>Mon profil</h2>

    <?php if ($success): ?>
        <div class="alert alert-success">Profil mis à jour avec succès.</div>
    <?php endif; ?>

    <form method="POST" class="row g-3">
        <div class="col-md-6">
            <label>Nom</label>
            <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($data['nom'] ?? '') ?>" required>
        </div>
        <div class="col-md-6">
            <label>Prénom</label>
            <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($data['prenom'] ?? '') ?>" required>
        </div>
        <div class="col-3">
            <label>Pays</label>
            <input type="text" name="pays" class="form-control" value="<?= htmlspecialchars($data['pays'] ?? '') ?>" required>
        </div>
        <div class="col-3">
            <label>Région</label>
            <input type="text" name="region" class="form-control" value="<?= htmlspecialchars($data['region'] ?? '') ?>" required>
        </div>
        <div class="col-3">
            <label>Ville</label>
            <input type="text" name="ville" class="form-control" value="<?= htmlspecialchars($data['ville'] ?? '') ?>" required>
        </div>
        <div class="col-6">
            <label>Rue</label>
            <input type="text" name="rue" class="form-control" value="<?= htmlspecialchars($data['rue'] ?? '') ?>" required>
        </div>
        <div class="col-md-4">
            <label>Carte bancaire</label>
            <input type="text" name="carte" class="form-control" value="<?= htmlspecialchars(masquerCarte($data['carte'] ?? '')) ?>" required>
        </div>
        <div class="col-md-2">
            <label>Date d'expiration</label>
            <input type="text" name="expiration" class="form-control" value="<?= htmlspecialchars($data['expiration'] ?? '') ?>" required>
        </div>
        <div class="col-md-2">
            <label>CCV</label>
            <input type="text" name="ccv" class="form-control" value="<?= htmlspecialchars($data['ccv'] ?? '') ?>" required>
        </div>
        <div class="col-12">
            <button type="submit" class="btn" style="background-color: rgb(226, 186, 111);">Mettre à jour</button>
        </div>
    </form>
</div>


</main>
<?php require_once 'footer.php'; ?>
</body>
</html>