<?php
session_start();
require_once 'db.php';
require_once 'navbar.php';

// Récupération du nom de la boulangerie depuis l'URL
$boulangerie_nom = $_GET['nom'] ?? '';

// Traitement de l'ajout aux favoris
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['favoris_produit'])) {
    if (isset($_SESSION['user'])) {
        $email = $_SESSION['user'];
        $product_id = intval($_POST['favoris_produit']);
        $stmt = $pdo->prepare("INSERT IGNORE INTO favoris_produits (user_email, product_id) VALUES (?, ?)");
        $stmt->execute([$email, $product_id]);
    } else {
        echo "<div class='alert alert-warning text-center'>Veuillez vous connecter pour ajouter aux favoris.</div>";
    }
}

// Récupération des produits de la boulangerie
$stmt = $pdo->prepare("SELECT * FROM products WHERE boulangerie = ?");
$stmt->execute([$boulangerie_nom]);
$produits = $stmt->fetchAll();

// Récupération des détails de la boulangerie
$stmt = $pdo->prepare("
    SELECT bd.adresse, bd.horaires
    FROM boulangeries b
    LEFT JOIN boulangeries_details bd ON bd.boulangerie_id = b.id
    WHERE b.nom = ?
");
$stmt->execute([$boulangerie_nom]);
$boulangerie_details = $stmt->fetch();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Produits de <?= htmlspecialchars($boulangerie_nom) ?></title>
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

.btn-panier-favoris:hover {
  background-color: rgb(206, 166, 91);
  color: black;
}

.card {
  cursor: pointer;
}

.card:hover {
  transform: translateY(-5px);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
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

</head>
<main>
<div class="container py-4">
  <h2 class="mb-4 text-center">Produits de la boulangerie <?= htmlspecialchars($boulangerie_nom) ?></h2>
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
    <?php foreach ($produits as $p): ?>
      <div class="col">
        <div class="card h-100 shadow-sm">
          <div data-bs-toggle="modal" data-bs-target="#productModal<?= $p['id'] ?>" style="cursor: pointer;">
<img 
  src="<?= htmlspecialchars($p['image_url'] ?? 'https://via.placeholder.com/300x200') ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="<?= htmlspecialchars($p['name']) ?>" title="<?= htmlspecialchars($p['name']) ?>">
            <div class="card-body">
              <h5 class="card-title text-center"><?= htmlspecialchars($p['name']) ?></h5>
              <p class="card-text text-center"><strong>Poids :</strong> <?= htmlspecialchars($p['poids']) ?></p>
              <p class="card-text text-center"><strong>Prix :</strong> <?= number_format($p['price'], 2) ?> €</p>
              <p class="card-text text-center text-truncate"><strong>Description :</strong> <?= htmlspecialchars($p['description']) ?></p>
              <p class="card-text"><strong>Allergènes :</strong> <?= htmlspecialchars($p['allergenes'] ?? 'Aucun') ?></p>
            </div>
          </div>
          <div class="px-3 pb-3 text-center">
            <form method="POST" action="ajouter_au_panier.php" class="mb-2">
              <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
              <input type="number" name="quantite" value="1" min="1" class="form-control w-50 mx-auto mb-2" required>
              <button type="submit" class="btn btn-sm" style="background-color: rgb(226, 186, 111);">Ajouter au panier</button>
            </form>
            <form method="POST">
              <input type="hidden" name="favoris_produit" value="<?= $p['id'] ?>">
              <button type="submit" class="btn btn-sm" style="background-color: rgb(226, 186, 111);">❤️ Ajouter aux favoris</button>
            </form>
          </div>
        </div>
      </div>

<!-- Modal -->
<div class="modal fade" id="productModal<?= $p['id'] ?>" tabindex="-1" aria-labelledby="productModalLabel<?= $p['id'] ?>" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?= htmlspecialchars($p['name']) ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body text-center">
        <button class="btn btn-outline-secondary" onclick="navigateModal(<?= $p['id'] ?>, 'prev')">⬅</button>
        <button class="btn btn-outline-secondary" onclick="navigateModal(<?= $p['id'] ?>, 'next')">➡</button>
        <img src="<?= htmlspecialchars($p['image_url'] ?? 'https://via.placeholder.com/300x200') ?>"  class="img-fluid my-3 mx-auto d-block"  alt="<?= htmlspecialchars($p['name']) ?>"  title="<?= htmlspecialchars($p['name']) ?>"  style="height: 400px;">
        <p><strong>Poids :</strong> <?= htmlspecialchars($p['poids']) ?></p>
        <p><strong>Description :</strong><br><?= nl2br(htmlspecialchars($p['description'])) ?></p>
        <p><strong>Prix :</strong> <?= number_format($p['price'], 2) ?> €</p>
        <p class="card-text"><strong>Allergènes :</strong> <?= htmlspecialchars($p['allergenes'] ?? 'Aucun') ?></p>

        <!-- Formulaire d'ajout au panier -->
        <form method="POST" action="ajouter_au_panier.php" class="mb-3">
          <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
          <label class="form-label">Quantité :</label>
          <input type="number" name="quantite" value="1" min="1" class="form-control w-25 mx-auto mb-2" required>
          <button type="submit" class="btn btn-sm" style="background-color: rgb(226, 186, 111);">Ajouter au panier</button>
        </form>

        <!-- Formulaire d'ajout aux favoris -->
        <form method="POST" class="d-inline-block align-top">
          <input type="hidden" name="favoris_produit" value="<?= $p['id'] ?>">
          <button type="submit" class="btn btn-sm" style="background-color: rgb(226, 186, 111);">❤️ Ajouter aux favoris</button>
        </form>
  
      </div>
    </div>
  </div>
</div>
<?php endforeach; ?>
</div>
</div>

<?php if ($boulangerie_details): ?>
  <div class="text-center mb-4">
    <p><strong>Adresse :</strong> <?= htmlspecialchars($boulangerie_details['adresse']) ?></p>
    <p><strong>Horaires :</strong> <?= htmlspecialchars($boulangerie_details['horaires']) ?></p>
  </div>
<?php endif; ?>

</main>
<?php require_once 'footer.php'; ?>
</body>
</html>