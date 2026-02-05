<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($pdo)) {
    require_once 'db.php';
}

$stmt = $pdo->query("SELECT logo_url FROM site_config LIMIT 1");
$logo = $stmt->fetchColumn();

$cart_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $qty) {
        $cart_count += $qty;
    }
}

$user = $_SESSION['user'] ?? null;
$prenom = null;
if ($user) {
    $stmt = $pdo->prepare("SELECT prenom FROM users WHERE email = ?");
    $stmt->execute([$user]);
    $prenom = $stmt->fetchColumn();
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<style>
.high-contrast {
  filter: contrast(150%) brightness(120%);
  background-color: black !important;
  color: yellow !important;
}
.large-text * {
  font-size: 1.2em !important;
}
.daltonism-mode img {
  filter: grayscale(100%) contrast(120%);
}
  body {
    padding-top: 0px;
  }
  .navbar {
  background-color: rgb(226, 214, 190) !important;
}
.nav-highlight {
  background-color:rgb(226, 186, 111) !important;
  border-radius: 8px;
  padding: 6px 16px !important;
  margin-right: 10px;
  font-weight: bold;
  font-size: 1.5rem;
  text-decoration: none !important;
  color: #000 !important;
  display: inline-block;
}
  .link {
    color: rgb(138, 98, 11);
    text-decoration: none;
    transition: color 0.3s ease;
  }

  .link:hover {
    color: rgb(226, 186, 111);
  }
</style>
<script>
function toggleHighContrast() {
  document.body.classList.toggle('high-contrast');
}

function toggleLargeText() {
  document.body.classList.toggle('large-text');
}

function toggleDaltonisme() {
  document.body.classList.toggle('daltonism-mode');
}

function resetAccessibility() {
  document.body.classList.remove('high-contrast', 'large-text', 'daltonism-mode');
}
</script>
<nav class="navbar navbar-expand-lg shadow sticky-top mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">
      <img src="<?= htmlspecialchars($logo) ?>" alt="Logo" style="max-height: 120px; width: auto;">
    </a>

    <!-- Bouton hamburger pour mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
      aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Contenu de la navbar -->
    <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
      <ul class="navbar-nav align-items-center">

<!-- Accessibilité -->
<li class="nav-item dropdown">
  <div class="dropdown">
    <button class="nav-highlight dropdown-toggle border-0" type="button" data-bs-toggle="dropdown">
      Accessibilité
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
      <li><a class="dropdown-item" href="#" onclick="toggleHighContrast()">Contraste élevé</a></li>
      <li><a class="dropdown-item" href="#" onclick="toggleLargeText()">Texte agrandi</a></li>
      <li><a class="dropdown-item" href="#" onclick="toggleDaltonisme()">Mode daltonien</a></li>
      <li><a class="dropdown-item" href="#" onclick="resetAccessibility()">Réinitialiser</a></li>
    </ul>
  </div>
</li>

        <!-- Boulangeries -->
        <li class="nav-item">
          <a class="nav-item nav-highlight" href="boulangeries.php">Boulangeries</a>
        </li>

        <!-- Produits -->
        <li class="nav-item">
          <a class="nav-item nav-highlight" href="produits.php">Produits</a>
        </li>

        <!-- Recettes -->
        <li class="nav-item">
          <a class="nav-item nav-highlight" href="recettes.php">Recettes</a>
        </li>

        <!-- Panier -->
<li class="nav-item mx-2 position-relative">
  <a href="panier.php" class="d-inline-block position-relative">
    <img src="icon2.png" alt="Panier" style="height: 50px;">
    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
      <?= $cart_count ?>
    </span>
  </a>
</li>

        <!-- Profil -->
        <li class="nav-item">
          <?php if (!$user): ?>
            <div class="dropdown">
              <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
               <img src="icon1.png" alt="Panier" style="height: 50px;">
              </button>
              <div class="dropdown-menu dropdown-menu-end p-3" style="min-width: 300px;">
                <form method="POST" action="profil_login.php">
                  <div class="mb-2">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                  </div>
                  <div class="mb-2">
                    <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
                  </div>
                  <button type="submit" class="btn btn-sm w-100" style="background-color: rgb(226, 186, 111);">Se connecter</button>
                </form>
                <div class="mt-2 text-center">
                  <a href="reset_password.php" class="mx-2 link">Mot de passe oublié</a> | <a href="inscription.php" class="mx-2 link">S'inscrire</a>
                </div>
              </div>
            </div>
          <?php else: ?>
            <div class="dropdown">
              <button class="nav-highlight dropdown-toggle border-0" type="button" data-bs-toggle="dropdown">
                👤 <?= htmlspecialchars($prenom ?? $user) ?>
                </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="historique.php">🧾 Historique de commandes</a></li>
                <li><a class="dropdown-item" href="mon_profil.php">👤 Mon profil</a></li>
                <?php if (!empty($_SESSION['is_admin']) && !empty($_SESSION['bakery_admin'])): ?>
                  <li><a class="dropdown-item" href="ma_boulangerie.php">🏪 Ma boulangerie</a></li>
                <?php endif; ?>
                <li><a class="dropdown-item" href="mesfavoris.php">🥐 Mes favoris</a></li>
                <li><a class="dropdown-item text-danger" href="logout.php">🚪 Se déconnecter</a></li>
              </ul>
            </div>
          <?php endif; ?>
        </li>
      </ul>
    </div>
  </div>
</nav>