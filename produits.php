<?php
session_start();
require_once 'db.php';
require_once 'navbar.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['favoris_produit'])) {
    if (isset($_SESSION['user'])) {
        $email = $_SESSION['user'];
        $product_id = intval($_POST['favoris_produit']);
        $stmt = $pdo->prepare("INSERT IGNORE INTO favoris_produits (user_email, product_id) VALUES (?, ?)");
        $stmt->execute([$email, $product_id]);
    } else {
        $message = "Veuillez vous connecter ou vous inscrire pour ajouter des produits aux favoris.";
    }
}

$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();
?>
<title>Produits</title>
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
function openModal(id) {
    const modal = new bootstrap.Modal(document.getElementById('productModal' + id));
    modal.show();
}
function navigateModal(currentId, direction) {
    const ids = <?= json_encode(array_column($products, 'id')) ?>;
    const currentIndex = ids.indexOf(parseInt(currentId));
    let newIndex = direction === 'prev' ? currentIndex - 1 : currentIndex + 1;
    if (newIndex < 0) newIndex = ids.length - 1;
    if (newIndex >= ids.length) newIndex = 0;
    const newId = ids[newIndex];
    const currentModal = bootstrap.Modal.getInstance(document.getElementById('productModal' + currentId));
    currentModal.hide();
    const newModal = new bootstrap.Modal(document.getElementById('productModal' + newId));
    newModal.show();
}
</script>

<div class="container py-4">
  <h2 class="mb-4">Tous les produits</h2>
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
    <?php foreach ($products as $p): ?>
      <div class="col">
        <div class="card h-100 shadow-sm">
          <div onclick="openModal(<?= $p['id'] ?>)" style="cursor: pointer;">
<img src="<?= htmlspecialchars($p['image_url'] ?? 'https://via.placeholder.com/300x200') ?>" class="card-img-top" alt="<?= htmlspecialchars($p['name']) ?>" title="<?= htmlspecialchars($p['name']) ?>" style="height: 200px; object-fit: cover;">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($p['name']) ?></h5>
              <p class="card-text"><strong>Boulangerie :</strong> <?= htmlspecialchars($p['boulangerie']) ?></p>
              <p class="card-text"><strong>Poids :</strong> <?= htmlspecialchars($p['poids']) ?></p>
              <p class="card-text"><strong>Prix :</strong> <?= number_format($p['price'], 2) ?> €</p>
              <p class="card-text text-truncate"><strong>Description :</strong> <?= htmlspecialchars($p['description']) ?></p>
              <p class="card-text"><strong>Allergènes :</strong> <?= htmlspecialchars($p['allergenes'] ?? 'Aucun') ?></p>
            </div>
          </div>
          <div class="px-3 pb-3 text-center">
            <form method="POST" action="ajouter_au_panier.php">
              <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
              <label class="form-label">Quantité :</label>
              <input type="number" name="quantite" value="1" min="1" class="form-control w-50 mb-2 mx-auto" required>
              <button type="submit" class="btn btn-panier-favoris btn-sm">Ajouter au panier</button>
            </form>
            <form method="POST" class="mt-2">
              <input type="hidden" name="favoris_produit" value="<?= $p['id'] ?>">
              <button type="submit" class="btn btn-panier-favoris btn-sm">❤️ Ajouter aux favoris</button>
            </form>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="productModal<?= $p['id'] ?>" tabindex="-1" aria-labelledby="productModalLabel<?= $p['id'] ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="productModalLabel<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
              <div class="d-flex justify-content-between">
                <button class="btn btn-outline-secondary" onclick="navigateModal(<?= $p['id'] ?>, 'prev')">⬅</button>
                <button class="btn btn-outline-secondary" onclick="navigateModal(<?= $p['id'] ?>, 'next')">➡</button>
              </div>
              <img src="<?= htmlspecialchars($p['image_url'] ?? 'https://via.placeholder.com/600x400') ?>"class="img-fluid my-3 mx-auto d-block"style="height: 400px;"alt="Produit">
              <p><strong>Boulangerie :</strong> <?= htmlspecialchars($p['boulangerie']) ?></p>
              <p><strong>Poids :</strong> <?= htmlspecialchars($p['poids']) ?></p>
              <p><strong>Prix :</strong> <?= number_format($p['price'], 2) ?> €</p>
              <p><strong>Description :</strong><br><?= nl2br(htmlspecialchars($p['description'])) ?></p>
              <p class="card-text"><strong>Allergènes :</strong> <?= htmlspecialchars($p['allergenes'] ?? 'Aucun') ?></p>
              <form method="POST" action="ajouter_au_panier.php" class="mt-3">
                <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                <label class="mb-2 text-center d-block">Quantité :</label>
                <input type="number" name="quantite" value="1" min="1" class="form-control w-25 mb-2 mx-auto" required>
                <button type="submit" class="btn btn-panier-favoris btn-sm">Ajouter au panier</button>
              </form>
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

</main>
<?php require_once 'footer.php'; ?>
</body>
</html>