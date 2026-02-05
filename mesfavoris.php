<?php
ob_start();
session_start();
require_once 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$user = $_SESSION['user'];

// Suppression d'un favori
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove_favori'])) {
        $stmt = $pdo->prepare("DELETE FROM favoris_boulangeries WHERE user_email = ? AND bakery_name = ?");
        $stmt->execute([$user, $_POST['remove_favori']]);
        header("Location: mesfavoris.php");
        exit;
    }
    if (isset($_POST['remove_produit_favori'])) {
        $stmt = $pdo->prepare("DELETE FROM favoris_produits WHERE user_email = ? AND product_id = ?");
        $stmt->execute([$user, intval($_POST['remove_produit_favori'])]);
        header("Location: mesfavoris.php");
        exit;
    }
    if (isset($_POST['remove_recette_favori'])) {
        $stmt = $pdo->prepare("DELETE FROM favoris_recettes WHERE user_email = ? AND recette_id = ?");
        $stmt->execute([$user, intval($_POST['remove_recette_favori'])]);
        header("Location: mesfavoris.php");
        exit;
    }
}

require_once 'navbar.php';

// Récupération des favoris
$stmt = $pdo->prepare("SELECT b.nom, b.image_url FROM favoris_boulangeries f JOIN boulangeries b ON f.bakery_name = b.nom WHERE f.user_email = ?");
$stmt->execute([$user]);
$favoris_boulangeries = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT p.* FROM favoris_produits f JOIN products p ON f.product_id = p.id WHERE f.user_email = ?");
$stmt->execute([$user]);
$favoris_produits = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT r.* FROM favoris_recettes f JOIN recettes r ON f.recette_id = r.id WHERE f.user_email = ?");
$stmt->execute([$user]);
$favoris_recettes = $stmt->fetchAll();
?>
<title>Mes favoris</title>
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
.truncate-3-lines {
  display: -webkit-box;
  -webkit-line-clamp: 3; /* nombre de lignes */
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
}
.description-trim {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
}
.card.recette-card {
  background-color:rgb(226, 190, 123); 
  border: none;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}
</style>

<div class="container py-4">
    <h2 class="mb-4 text-center">Mes favoris</h2>

    <!-- Boulangeries -->
    <h4>Boulangeries</h4>
    <?php if (empty($favoris_boulangeries)): ?>
        <p class="text-muted">Aucune boulangerie en favoris.</p>
    <?php else: ?>
        <div class="row g-4 mb-5">
            <?php foreach ($favoris_boulangeries as $b): ?>
                <div class="col-md-4">
                    <div class="card position-relative h-100">
                        <form method="POST" class="position-absolute top-0 end-0 m-2">
                            <input type="hidden" name="remove_favori" value="<?= htmlspecialchars($b['nom']) ?>">
                            <button type="submit" class="btn btn-sm btn-danger" title="Retirer des favoris">❌</button>
                        </form>
                        <a href="boulangerie.php?nom=<?= urlencode($b['nom']) ?>" class="text-decoration-none">
                            <img src="<?= htmlspecialchars($b['image_url']) ?>" class="card-img-top" alt="Boulangerie" style="height: 200px; object-fit: cover;">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= htmlspecialchars($b['nom']) ?></h5>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Produits -->
    <h4>Produits</h4>
    <?php if (empty($favoris_produits)): ?>
        <p class="text-muted">Aucun produit en favoris.</p>
    <?php else: ?>
        <div class="row g-4 mb-5">
            <?php foreach ($favoris_produits as $p): ?>
                <div class="col-md-4">
                    <div class="card h-100 position-relative">
                        <form method="POST" class="position-absolute top-0 end-0 m-2">
                            <input type="hidden" name="remove_produit_favori" value="<?= $p['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger" title="Retirer des favoris">❌</button>
                        </form>
                        <img src="<?= htmlspecialchars($p['image_url']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?= htmlspecialchars($p['name']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($p['description']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<!-- Recettes -->
<h4>Recettes</h4>
<?php if (empty($favoris_recettes)): ?>
    <p class="text-muted">Aucune recette en favoris.</p>
<?php else: ?>
    <div class="row g-4 mb-5">
        <?php foreach ($favoris_recettes as $r): ?>
            <div class="col-md-4">
                <div class="card h-100 position-relative" data-bs-toggle="modal" data-bs-target="#recetteModal<?= $r['id'] ?>" style="cursor: pointer;">
                    <form method="POST" class="position-absolute top-0 end-0 m-2">
                        <input type="hidden" name="remove_recette_favori" value="<?= $r['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger" title="Retirer des favoris" onclick="event.stopPropagation()">❌</button>
                    </form>
                    <img src="<?= htmlspecialchars($r['image_url']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Recette">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?= htmlspecialchars($r['titre']) ?></h5>
                        <p class="card-text text-truncate" style="max-height: 4.5em; overflow: hidden;"><?= htmlspecialchars($r['description']) ?></p>
                    </div>
                </div>
            </div>

            <!-- Modal de la recette -->
            <div class="modal fade" id="recetteModal<?= $r['id'] ?>" tabindex="-1" aria-labelledby="recetteModalLabel<?= $r['id'] ?>" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><?= htmlspecialchars($r['titre']) ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            <img src="<?= htmlspecialchars($r['image_url']) ?>" class="img-fluid mb-3" alt="Image recette">
                            <p><?= nl2br(htmlspecialchars($r['description'])) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
</div>

</main>

<?php require_once 'footer.php'; ?>

</body>
</html>

<?php ob_end_flush(); ?>
