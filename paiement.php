<?php
ob_start();
session_start();
require_once 'db.php';
require_once 'navbar.php';

$notConnected = !isset($_SESSION['user']);
$email = null;
$data = [];

if (!$notConnected) {
    $email = $_SESSION['user'];

    // Récupération des infos utilisateur
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $data = $stmt->fetch();
}

// Traitement du paiement uniquement si connecté
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$notConnected && !empty($_SESSION['cart'])) {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $pays = $_POST['pays'] ?? '';
    $region = $_POST['region'] ?? '';
    $ville = $_POST['ville'] ?? '';
    $rue = $_POST['rue'] ?? '';
    $carte = $_POST['carte'] ?? '';
    $expiration = $_POST['expiration'] ?? '';
    $ccv = $_POST['ccv'] ?? '';

    $cart = $_SESSION['cart'];
    $items = [];
    foreach ($cart as $product_id => $qty) {
        for ($i = 0; $i < $qty; $i++) {
            $items[] = $product_id;
        }
    }

    if (!empty($items)) {
        $stmt = $pdo->prepare("
            INSERT INTO orders (
                email, items, created_at,
                nom, prenom, rue, ville, region, pays,
                carte, expiration, ccv
            ) VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $email,
            implode(',', $items),
            $nom,
            $prenom,
            $rue,
            $ville,
            $region,
            $pays,
            $carte,
            $expiration,
            $ccv
        ]);

        unset($_SESSION['cart']);
        header("Location: success.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement</title>
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
<body class="bg-light">
<main>
<div class="container mt-5">
    <h2 class="mb-4">Paiement</h2>

    <?php if ($notConnected): ?>
        <div class="alert alert-warning text-center">
            Vous devez <strong>vous connecter</strong> ou <strong>vous inscrire</strong> pour procéder au paiement.<br>
            Cliquez sur l'icône 👤 en haut à droite pour vous connecter.
        </div>
    <?php else: ?>
        <form method="POST" action="paiement.php" class="row g-3">
            <div class="col-md-6">
                <label>Nom</label>
                <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($data['nom'] ?? '') ?>" required>
            </div>
            <div class="col-md-6">
                <label>Prénom</label>
                <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($data['prenom'] ?? '') ?>" required>
            </div>
            <div class="col-md-3">
                <label>Pays</label>
                <input type="text" name="pays" class="form-control" value="<?= htmlspecialchars($data['pays'] ?? '') ?>" required>
            </div>
            <div class="col-md-3">
                <label>Région</label>
                <input type="text" name="region" class="form-control" value="<?= htmlspecialchars($data['region'] ?? '') ?>" required>
            </div>
            <div class="col-md-3">
                <label>Ville</label>
                <input type="text" name="ville" class="form-control" value="<?= htmlspecialchars($data['ville'] ?? '') ?>" required>
            </div>
            <div class="col-md-3">
                <label>Rue</label>
                <input type="text" name="rue" class="form-control" value="<?= htmlspecialchars($data['rue'] ?? '') ?>" required>
            </div>
            <div class="col-md-6">
                <label>Carte bancaire</label>
                <input type="text" name="carte" class="form-control" value="<?= htmlspecialchars($data['carte'] ?? '') ?>" required>
            </div>
            <div class="col-md-3">
                <label>Date d'expiration</label>
                <input type="text" name="expiration" class="form-control" value="<?= htmlspecialchars($data['expiration'] ?? '') ?>" required>
            </div>
            <div class="col-md-3">
                <label>CCV</label>
                <input type="text" name="ccv" class="form-control" value="<?= htmlspecialchars($data['ccv'] ?? '') ?>" required>
            </div>
            <div class="col-12">
                <button type="submit" class="btn" style="background-color: rgb(226, 186, 111);">Valider le paiement</button>
            </div>
        </form>
    <?php endif; ?>
</div>

</main>
<?php require_once 'footer.php'; ?>
</body>
</html>
<?php ob_end_flush(); ?>