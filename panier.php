<?php
session_start();
require_once 'db.php';
require_once 'navbar.php';

$cart = $_SESSION['cart'] ?? [];

$total = 0;
$produits = [];

if (!empty($cart)) {
    $placeholders = implode(',', array_fill(0, count($cart), '?'));
    $ids = array_keys($cart);
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
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
    <h2>Votre panier</h2>
    <?php if (empty($produits)): ?>
        <p>Votre panier est vide.</p>
    <?php else: ?>
<div class="row row-cols-1 row-cols-md-3 g-4">
    <?php foreach ($produits as $p): 
        $qty = $cart[$p['id']];
        $subtotal = $qty * $p['price'];
        $total += $subtotal;
    ?>
        <div class="col">
            <div class="card h-100 shadow-sm">
                <img src="<?= htmlspecialchars($p['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($p['name']) ?>" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($p['name']) ?></h5>
                    <p class="card-text">
                        <?= number_format($p['price'], 2) ?> € x <?= $qty ?> = <strong><?= number_format($subtotal, 2) ?> €</strong>
                    </p>
                    <div class="btn-group" role="group">
                        <form method="POST" action="modifier_panier.php">
                            <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                            <input type="hidden" name="action" value="decrement">
                            <button class="btn btn-outline-secondary btn-sm">-</button>
                        </form>
                        <form method="POST" action="modifier_panier.php">
                            <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                            <input type="hidden" name="action" value="increment">
                            <button class="btn btn-outline-secondary btn-sm">+</button>
                        </form>
                        <form method="POST" action="modifier_panier.php">
                            <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                            <input type="hidden" name="action" value="remove">
                            <button class="btn btn-outline-danger btn-sm">❌</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
        <h4>Total : <?= number_format($total, 2) ?> €</h4>
        <a href="paiement.php" class="btn" style="background-color: rgb(226, 186, 111);">Payer</a>
    <?php endif; ?>
</div>

</main>
<?php require_once 'footer.php'; ?>
</body>
</html>