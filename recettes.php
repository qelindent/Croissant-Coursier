<?php
session_start();
require_once 'db.php';
require_once 'navbar.php';

// Ajout d'une recette aux favoris
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['favoris_recette'])) {
    if (isset($_SESSION['user'])) {
        $email = $_SESSION['user'];
        $recette_id = intval($_POST['favoris_recette']);
        $stmt = $pdo->prepare("INSERT IGNORE INTO favoris_recettes (user_email, recette_id) VALUES (?, ?)");
        $stmt->execute([$email, $recette_id]);
    } else {
        echo "<script>alert('Veuillez vous connecter ou vous inscrire pour ajouter une recette aux favoris.');</script>";
    }
}

$stmt = $pdo->query("SELECT * FROM recettes");
$recettes = $stmt->fetchAll();
foreach ($recettes as &$r) {
    $stmt = $pdo->prepare("
        SELECT u.boulangerie
        FROM users u
        WHERE u.id = ?
    ");
    $stmt->execute([$r['boulangeries_id']]);
    $r['boulangerie_nom'] = $stmt->fetchColumn() ?: 'Boulangerie inconnue';
}
unset($r);
?>

<body>
<main>
<title>Recettes</title>
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
    .btn-panier-favoris {
      background-color: rgb(226, 186, 111);
      color: black;
      border: none;
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
</style>
<script>
function openRecetteModal(id) {
    const modal = new bootstrap.Modal(document.getElementById('recetteModal' + id));
    modal.show();
}
function navigateRecetteModal(currentId, direction) {
    const ids = <?= json_encode(array_column($recettes, 'id')) ?>;
    const currentIndex = ids.indexOf(parseInt(currentId));
    let newIndex = direction === 'prev' ? currentIndex - 1 : currentIndex + 1;
    if (newIndex < 0) newIndex = ids.length - 1;
    if (newIndex >= ids.length) newIndex = 0;
    const newId = ids[newIndex];
    const currentModal = bootstrap.Modal.getInstance(document.getElementById('recetteModal' + currentId));
    currentModal.hide();
    const newModal = new bootstrap.Modal(document.getElementById('recetteModal' + newId));
    newModal.show();
}
</script>

<div class="container py-4">
  <h2 class="mb-4">Nos Recettes</h2>
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
    <?php foreach ($recettes as $r): ?>
      <?php
        // Récupérer les produits associés à la recette
        $stmt = $pdo->prepare("
            SELECT p.*
            FROM recette_produit rp
            JOIN products p ON rp.produit_id = p.id
            WHERE rp.recette_id = ?
        ");
        $stmt->execute([$r['id']]);
        $produits_associes = $stmt->fetchAll();
      ?>
      <div class="col">
        <div class="card h-100 shadow-sm">
          <div onclick="openRecetteModal(<?= $r['id'] ?>)" style="cursor: pointer;">
            <img src="<?= htmlspecialchars($r['image_url'] ?? 'https://via.placeholder.com/300x200') ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Recette">
            <div class="card-body">
              <h5 class="card-title text-center"><?= htmlspecialchars($r['titre']) ?></h5>
              <p><strong>Boulangerie :</strong> <?= htmlspecialchars($r['boulangerie_nom']) ?></p>
              <p class="card-text text-truncate" style="max-height: 4.5em; overflow: hidden;">
                <?= nl2br(htmlspecialchars($r['description'])) ?>
              </p>
            </div>
          </div>
          <div class="px-3 pb-3 text-center">
            <form method="POST" class="mt-2">
              <input type="hidden" name="favoris_recette" value="<?= $r['id'] ?>">
              <button type="submit" class="btn btn-panier-favoris btn-sm">❤️ Ajouter aux favoris</button>
            </form>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="recetteModal<?= $r['id'] ?>" tabindex="-1" aria-labelledby="recetteModalLabel<?= $r['id'] ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="recetteModalLabel<?= $r['id'] ?>"><?= htmlspecialchars($r['titre']) ?></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
              <div class="d-flex justify-content-between">
                <button class="btn btn-outline-secondary" onclick="navigateRecetteModal(<?= $r['id'] ?>, 'prev')">⬅</button>
                <button class="btn btn-outline-secondary" onclick="navigateRecetteModal(<?= $r['id'] ?>, 'next')">➡</button>
              </div>

              <img src="<?= htmlspecialchars($r['image_url'] ?? 'https://via.placeholder.com/600x400') ?>" class="img-fluid my-3 mx-auto d-block"  alt="Recette" style="height: 400px;">
                <?php if (!empty($produits_associes)): ?>
                <hr>
                <h5 class="text-center mt-4">🥖 Produit qui irait bien avec cette recette :</h5>
                <?php foreach ($produits_associes as $produit): ?>
                  <div class="card mb3 mx-auto" style="width: 18rem;">
                    <img src="<?= htmlspecialchars($produit['image_url']) ?>" class="card-img-top" style="height: 180px; object-fit: cover;" alt="<?= htmlspecialchars($produit['name']) ?>">
                    <div class="card-body text-center">
                      <h6 class="card-title"><?= htmlspecialchars($produit['name']) ?></h6>
                      <p class="card-text"><?= number_format($produit['price'], 2) ?> €</p>
                      <form method="POST" action="ajouter_au_panier.php">
                        <input type="hidden" name="product_id" value="<?= $produit['id'] ?>">
                        <input type="hidden" name="quantite" value="1">
                        <button type="submit" class="btn btn-sm btn-panier-favoris">Ajouter au panier</button>
                      </form>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
              <p><strong>Description :</strong><br><?= nl2br(htmlspecialchars($r['description'])) ?></p>


              <form method="POST" class="text-center mt-2">
                <input type="hidden" name="favoris_recette" value="<?= $r['id'] ?>">
                <button type="submit" class="btn btn-panier-favoris btn-sm">❤️ Ajouter aux favoris</button>
              </form>
            </div>
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
