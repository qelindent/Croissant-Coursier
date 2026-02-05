<?php
session_start();
require_once 'db.php';
require_once 'navbar.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$user = $_SESSION['user'];
$stmt = $pdo->prepare("
    SELECT o.*, u.nom, u.prenom, u.pays, u.region, u.ville, u.rue
    FROM orders o
    JOIN users u ON o.email = u.email
    WHERE o.email = ?
    ORDER BY o.created_at DESC
");
$stmt->execute([$user]);
$commandes = $stmt->fetchAll();
?>
<title>Historique de commandes</title>
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
<main>
<div class="container">
  <h2>Historique de commandes</h2>
  <?php if (empty($commandes)): ?>
    <p>Vous n'avez pas encore passé de commande.</p>
  <?php else: ?>
    <ul class="list-group">
      <?php foreach ($commandes as $commande): ?>
        <li class="list-group-item">
          <strong>Date :</strong> <?= $commande['created_at'] ?><br>
          <strong>Nom :</strong> <?= htmlspecialchars($commande['prenom']) ?> <?= htmlspecialchars($commande['nom']) ?><br>

          <?php
          $product_ids = explode(',', $commande['items']);
          $quantites = array_count_values($product_ids);

          $placeholders = implode(',', array_fill(0, count($quantites), '?'));
          $stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id IN ($placeholders)");
          $stmt->execute(array_keys($quantites));
          $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

          $articles = [];
          $total = 0;

          foreach ($produits as $produit) {
              $id = $produit['id'];
              $qty = $quantites[$id] ?? 0;
              $subtotal = $qty * $produit['price'];
              $total += $subtotal;
              $articles[] = "{$qty}× {$produit['name']}";
          }
          ?>

          <strong>Articles :</strong> <?= htmlspecialchars(implode(', ', $articles)) ?><br>
          <strong>Total :</strong> <?= number_format($total, 2) ?> €<br>

          <strong>Adresse :</strong><br>
          <?= htmlspecialchars($commande['rue'] ?? '') ?><br>
          <?= htmlspecialchars($commande['ville'] ?? '') ?><br>
          <?= htmlspecialchars($commande['region'] ?? '') ?><br>
          <?= htmlspecialchars($commande['pays'] ?? '') ?><br>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>

</main>

<?php require_once 'footer.php'; ?>

</body>
</html>