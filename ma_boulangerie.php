<?php
session_start();
require_once 'db.php';
require_once 'navbar.php';

if (!isset($_SESSION['user']) || empty($_SESSION['bakery_admin'])) {
    header('Location: index.php');
    exit;
}

$bakery = $_SESSION['bakery_admin'];
$success = false;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_bakery_name'])) {
        $newName = trim($_POST['new_name']);
        if ($newName && $newName !== $bakery) {
            $stmt = $pdo->prepare("UPDATE products SET boulangerie = ? WHERE boulangerie = ?");
            $stmt->execute([$newName, $bakery]);

            $stmt = $pdo->prepare("UPDATE users SET boulangerie = ? WHERE boulangerie = ?");
            $stmt->execute([$newName, $bakery]);

            $stmt = $pdo->prepare("UPDATE boulangeries SET nom = ? WHERE nom = ?");
            $stmt->execute([$newName, $bakery]);

            $stmt = $pdo->prepare("UPDATE favoris_boulangeries SET bakery_name = ? WHERE bakery_name = ?");
            $stmt->execute([$newName, $bakery]);

            $_SESSION['bakery_admin'] = $newName;
            $bakery = $newName;
            $success = true;
        }
    }
    if (isset($_POST['update_recette_association']) && !empty($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $recette_id = !empty($_POST['recette_id']) ? intval($_POST['recette_id']) : null;

    
    // Supprimer l'ancienne association
    $stmt = $pdo->prepare("DELETE FROM recette_produit WHERE produit_id = ?");
    $stmt->execute([$product_id]);

    // Ajouter la nouvelle association si une recette est choisie
    if ($recette_id) {
        $stmt = $pdo->prepare("INSERT INTO recette_produit (produit_id, recette_id) VALUES (?, ?)");
        $stmt->execute([$product_id, $recette_id]);
    }
    $success = true;
}

    if (isset($_POST['update_image_url']) && isset($_FILES['image_url']) && $_FILES['image_url']['error'] === UPLOAD_ERR_OK) {
        // Supprimer l'ancienne image si elle existe
        if (!empty($image_url) && file_exists($image_url)) {
            unlink($image_url);
        }

        $newBanner = basename($_FILES['image_url']['name']);

        if (move_uploaded_file($_FILES['image_url']['tmp_name'], $newBanner)) {
            $stmt = $pdo->prepare("UPDATE boulangeries SET image_url = ? WHERE nom = ?");
            $stmt->execute([$newBanner, $bakery]);
            $success = true;
            $image_url = $newBanner;
        }
    }

    if (isset($_POST['update_product'])) {
        if (!empty($_FILES['image_file']['name'])) {
            $imageName = uniqid() . '_' . basename($_FILES['image_file']['name']);
            move_uploaded_file($_FILES['image_file']['tmp_name'], $imageName);
            $_POST['image_url'] = $imageName;
        }
        $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, poids = ?, description = ?, image_url = ? WHERE id = ? AND boulangerie = ?");
        $stmt->execute([
            $_POST['name'], $_POST['price'], $_POST['poids'], $_POST['description'], $_POST['image_url'], $_POST['id'], $bakery
        ]);
        $success = true;
    }

    if (isset($_POST['delete_product'])) {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ? AND boulangerie = ?");
        $stmt->execute([$_POST['id'], $bakery]);
        $success = true;
    }

    if (isset($_POST['update_recette'])) {
        if (!empty($_FILES['recette_image_file']['name'])) {
            $imageName = uniqid() . '_' . basename($_FILES['recette_image_file']['name']);
            move_uploaded_file($_FILES['recette_image_file']['tmp_name'], $imageName);
            $_POST['image_url'] = $imageName;
        }
        $stmt = $pdo->prepare("UPDATE recettes SET titre = ?, description = ?, image_url = ? WHERE id = ? AND boulangeries_id = (SELECT id FROM users WHERE boulangerie = ?)");
        $stmt->execute([
            $_POST['titre'], $_POST['description'], $_POST['image_url'], $_POST['id'], $bakery
        ]);
        $success = true;
    }

    if (isset($_POST['delete_recette'])) {
        $stmt = $pdo->prepare("DELETE FROM recettes WHERE id = ? AND boulangeries_id = (SELECT id FROM users WHERE boulangerie = ?)");
        $stmt->execute([$_POST['id'], $bakery]);
        $success = true;
    }

    if (isset($_POST['add_product'])) {
        $imageName = '';
        if (!empty($_FILES['image_file']['name'])) {
            $imageName = uniqid() . '_' . basename($_FILES['image_file']['name']);
            move_uploaded_file($_FILES['image_file']['tmp_name'], $imageName);
        }
        $stmt = $pdo->prepare("INSERT INTO products (name, price, poids, description, image_url, boulangerie) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['name'], $_POST['price'], $_POST['poids'], $_POST['description'], $imageName, $bakery
        ]);
        $success = true;
    }

    if (isset($_POST['add_recette'])) {
        $new_titre = $_POST['new_titre'] ?? '';
        $new_description = $_POST['new_description'] ?? '';
        $imagePath = '';

        if (!empty($_FILES['new_recette_image']['name'])) {
            $imageName = basename($_FILES['new_recette_image']['name']);
            if (move_uploaded_file($_FILES['new_recette_image']['tmp_name'], $imageName)) {
                $imagePath = $imageName;
            }
        }

        if (!empty($new_titre) && !empty($new_description)) {
            $stmt = $pdo->prepare("INSERT INTO recettes (titre, description, image_url, boulangeries_id) VALUES (?, ?, ?, (SELECT id FROM users WHERE boulangerie = ?))");
            $stmt->execute([
                $new_titre, $new_description, $imagePath, $bakery
            ]);
            $success = true;
        } else {
            echo "<div class='alert alert-danger text-center'>Le titre et la description sont obligatoires.</div>";
        }
    }
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE boulangerie = ?");
$stmt->execute([$bakery]);
$products = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT r.* FROM recettes r JOIN users u ON r.boulangeries_id = u.id WHERE u.boulangerie = ?");
$stmt->execute([$bakery]);
$recettes = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT image_url FROM boulangeries WHERE nom = ?");
$stmt->execute([$bakery]);
$image_url = $stmt->fetchColumn();
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
.produit-card {
  background-color: rgb(236, 212, 166);
  border: none;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}
.recette-card {
  background-color: rgb(226, 190, 123);
  border: none;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}
</style>
<title>Ma boulangerie</title>
<link rel="icon" type="image/x-icon" href="favicon.ico">
<div class="container py-4">
    <h2 class="mb-4 text-center">Gestion de ma boulangerie : <?= htmlspecialchars($bakery) ?></h2>

    <?php if ($success): ?>
        <div class="alert alert-success">Modifications enregistrées.</div>
        <script>
          setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) alert.remove();
          }, 7000);
        </script>
    <?php endif; ?>

<h4 class="text-center">Bannière</h4>
<div class="d-flex justify-content-center mb-4">
  <form method="POST" enctype="multipart/form-data" class="w-75">
    <div class="text-center mt-3 mb-4">
      <img src="<?= htmlspecialchars($image_url) ?>" alt="Bannière actuelle" class="img-fluid" style="max-height: 400px;">
    </div>
    <div class="d-flex flex-wrap align-items-center justify-content-center gap-3">
      <input type="file" name="image_url" accept="image/*" class="form-control w-50">
      <button type="submit" name="update_image_url" class="btn btn-warning" style="background-color: rgb(226, 186, 111);">Changer l'image</button>
    </div>
  </form>
</div>

<h4 class="text-center">Nom</h4>
<div class="d-flex justify-content-center mb-4">
  <form method="POST" class="w-75">
    <div class="d-flex flex-wrap align-items-center justify-content-center gap-3">
      <input type="text" name="new_name" class="form-control w-50" placeholder="Nouveau nom de la boulangerie" required>
      <button type="submit" name="update_bakery_name" class="btn" style="background-color: rgb(226, 186, 111);">Renommer</button>
    </div>
  </form>
</div>

    <h4 class="text-center">Produits</h4>
<div class="text-center mb-4">
  <button class="btn btn-outline-warning" data-bs-toggle="collapse" data-bs-target="#formAjoutProduit">
  ➕ Ajouter un produit
</button>
</div>
<div class="collapse mb-5" id="formAjoutProduit">
  <form method="POST" enctype="multipart/form-data" class="card card-body produit-card">
    <h5 class="text-center">Nouveau produit</h5>
    <div class="mb-2">
      <label>Nom :</label>
      <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-2">
      <label>Prix :</label>
      <input type="number" step="0.01" name="price" class="form-control" required>
    </div>
    <div class="mb-2">
      <label>Poids :</label>
      <input type="text" name="poids" class="form-control">
    </div>
    <div class="mb-2">
     <label>Allergènes :</label>
     <textarea name="allergenes" class="form-control" rows="2" placeholder="Ex : gluten, lait, œuf..."></textarea>
    </div>
    <div class="mb-2">
      <label>Description :</label>
      <textarea name="description" class="form-control"></textarea>
    </div>
    <div class="mb-2">
      <label>Image :</label>
      <input type="file" name="image_file" class="form-control">
    </div>
    <div class="text-center">
      <button type="submit" name="add_product" class="btn btn-success">Ajouter</button>
    </div>
  </form>
</div>
    <?php if ($products): ?>
        <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center">
            <?php foreach ($products as $p): ?>
                <div class="col">
                    <div class="card h-100 produit-card">
                        <img src="<?= htmlspecialchars($p['image_url'] ?? 'https://via.placeholder.com/300x200') ?>" class="card-img-top" alt="Produit" style="max-height: 180px; object-fit: cover;">
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <div class="mb-2">
                                    <label>Nom :</label>
                                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($p['name']) ?>" required>
                                </div>
                                <div class="mb-2">
                                    <label>Prix :</label>
                                    <input type="number" step="0.01" name="price" class="form-control" value="<?= $p['price'] ?>" required>
                                </div>
                                <div class="mb-2">
                                    <label>Poids :</label>
                                    <input type="text" name="poids" class="form-control" value="<?= htmlspecialchars($p['poids']) ?>">
                                </div>
                                <div class="mb-2">
                                    <label>Description :</label>
                                    <textarea name="description" class="form-control"><?= htmlspecialchars($p['description']) ?></textarea>
                                </div>
                                <div class="mb-2">
                                    <label>Changer l'image :</label>
                                    <input type="file" name="image_file" class="form-control">
                                </div>
                                <input type="hidden" name="image_url" value="<?= htmlspecialchars($p['image_url']) ?>">
                                <div class="d-flex justify-content-between">
                                    <button type="submit" name="update_product" class="btn btn-success">Modifier</button>
                                    <button type="submit" name="delete_product" class="btn btn-danger" onclick="return confirm('Supprimer ce produit ?')">Supprimer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Aucun produit trouvé.</p>
    <?php endif; ?>

    <h4 class="text-center">Recettes</h4>
    <div class="text-center mb-4">
  <button class="btn btn-outline-warning" data-bs-toggle="collapse" data-bs-target="#formAjoutRecette">
  ➕ Ajouter une recette
</button>

</div>
<div class="collapse mb-5" id="formAjoutRecette">
  <form method="POST" enctype="multipart/form-data" class="card card-body recette-card">
    <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
    <h5 class="text-center">Nouvelle recette</h5>
    <div class="mb-2">
      <label>Titre :</label>
      <input type="text" name="new_titre" class="form-control" placeholder="Titre de la recette" required>
    </div>
    <div class="mb-2">
      <label>Description :</label>
      <textarea name="new_description" class="form-control" rows="3" placeholder="Description" required></textarea>
    </div>
    <div class="mb-2">
      <label>Image :</label>
      <input type="file" name="new_recette_image" class="form-control" accept="image/*">
    </div>
    <div class="mb-2">
  <label>Associer un produit :</label>
  <select name="new_product_id" class="form-select">
    <option value="">-- Aucun produit associé --</option>
    <?php
    $stmt = $pdo->prepare("SELECT id, name FROM products WHERE boulangerie = ?");
    $stmt->execute([$bakery]);
    $produits_options = $stmt->fetchAll();
    foreach ($produits_options as $produit) {
        echo "<option value='{$produit['id']}'>{$produit['name']}</option>";
    }
    ?>
  </select>
</div>
    <div class="text-center">
      <button type="submit" name="add_recette" class="btn btn-success">Ajouter</button>
    </div>
  </form>
</div>
    <?php if ($recettes): ?>
        <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center">
            <?php foreach ($recettes as $r): ?>
                <div class="col">
                    <div class="card h-100 recette-card">
                      <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                        <img src="<?= htmlspecialchars($r['image_url']) ?>" class="card-img-top" style="max-height: 180px; object-fit: cover;">
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                <div class="mb-2">
                                    <label>Titre :</label>
                                    <input type="text" name="titre" class="form-control" value="<?= htmlspecialchars($r['titre']) ?>" required>
                                </div>
                                <div class="mb-2">
                                    <label>Description :</label>
                                    <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($r['description']) ?></textarea>
                                </div>
                                <div class="mb-2">
                                    <label>Changer l'image :</label>
                                    <input type="file" name="recette_image_file" class="form-control">
                                </div>
                                <input type="hidden" name="image_url" value="<?= htmlspecialchars($r['image_url']) ?>">
                                <div class="mb-2">
  <label>Associer un produit :</label>
  <select name="new_product_id" class="form-select">
    <option value="">-- Aucun produit associé --</option>
    <?php
    $stmt = $pdo->prepare("SELECT id, name FROM products WHERE boulangerie = ?");
    $stmt->execute([$bakery]);
    $produits_options = $stmt->fetchAll();
    foreach ($produits_options as $produit) {
        echo "<option value='{$produit['id']}'>{$produit['name']}</option>";
    }
    ?>
  </select>
</div>
                                <div class="d-flex justify-content-between">
                                    <button type="submit" name="update_recette" class="btn btn-success">Modifier</button>
                                    <button type="submit" name="delete_recette" class="btn btn-danger" onclick="return confirm('Supprimer cette recette ?')">Supprimer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Aucune recette enregistrée.</p>
    <?php endif; ?>
</div>

</main>

<?php require_once 'footer.php'; ?>

</body>
</html>