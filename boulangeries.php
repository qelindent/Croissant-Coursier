<?php
session_start();
require_once 'db.php';
require_once 'navbar.php';

$message = '';

// Ajout aux favoris
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mes_favoris'])) {
    if (isset($_SESSION['user'])) {
        $email = $_SESSION['user'];
        $boulangerie_nom = $_POST['mes_favoris'];
        $stmt = $pdo->prepare("INSERT IGNORE INTO favoris_boulangeries (user_email, bakery_name) VALUES (?, ?)");
        $stmt->execute([$email, $boulangerie_nom]);
    } else {
        $message = "Veuillez vous connecter ou vous inscrire pour ajouter une boulangerie à vos favoris.";
    }
}

// Récupération des boulangeries
$stmt = $pdo->query("
    SELECT b.*, bd.adresse, bd.horaires
    FROM boulangeries b
    LEFT JOIN boulangeries_details bd ON b.id = bd.boulangerie_id
");
$boulangeries = $stmt->fetchAll();

?>
<title>Boulangeries</title>
<link rel="icon" type="image/x-icon" href="favicon.ico">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Fredoka&display=swap" rel="stylesheet">
<style>

    html, body {
      height: 100%;
      margin: 0;
    }

    main {
      flex: 1;
    }

    body {
      font-family: 'Fredoka', sans-serif;
      font-weight: bold;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    
  .btn-panier-favoris {
  background-color: rgb(226, 186, 111);
  color: black;
  border: none;
}

.btn-panier-favoris:hover {
  background-color: rgb(206, 166, 91);
  color: black;
}

  .footer-link {
    color: black;
    text-decoration: none;
    transition: color 0.3s ease;
  }

  .footer-link:hover {
    color: rgb(138, 98, 11);
  }


</style>
<main>
<div class="container py-4">
  <h2 class="mb-4">Nos Boulangeries</h2>

  <?php if (!empty($message)): ?>
    <div class="alert alert-warning text-center"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <div class="row g-4">
    <?php foreach ($boulangeries as $b): ?>
      <div class="col-12">
        <div class="card shadow-sm">
          <a href="boulangerie.php?nom=<?= urlencode($b['nom']) ?>" class="text-decoration-none text-dark">
          <img src="<?= htmlspecialchars($b['image_url']) ?>"class="card-img-top" alt="<?= htmlspecialchars($b['nom']) ?>"title="<?= htmlspecialchars($b['nom']) ?>"style="width: 100%; max-height: 500px; object-fit: cover;">
          <div class="card-body d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><?= htmlspecialchars($b['nom']) ?></h5>
            <p class="mb-1"><strong>Adresse :</strong> <?= htmlspecialchars($b['adresse'] ?? 'Non renseignée') ?></p>
<p class="mb-1"><strong>Horaires :</strong> <?= htmlspecialchars($b['horaires'] ?? 'Non renseigné') ?></p>
            <form method="POST" class="mb-0">
              <input type="hidden" name="mes_favoris" value="<?= htmlspecialchars($b['nom']) ?>">
              <button type="submit" class="btn btn-panier-favoris btn-sm">❤️ Ajouter aux favoris</button>
            </form>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

</main>
<?php require_once 'footer.php'; ?>
</body>
</html>