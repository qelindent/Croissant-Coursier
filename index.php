<?php
session_start();
require_once 'db.php';
require_once 'navbar.php';

$welcome_message = $_SESSION['welcome'] ?? null;
unset($_SESSION['welcome']); // On l'efface après affichage

function masquerCarte($numero) {
    // Ne garde que les 4 derniers chiffres
    $visible = substr($numero, -4);
    return '**** **** **** ' . $visible;
}


$login_error = '';
$user = $_SESSION['user'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Connexion
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $login_error = "Adresse email invalide";
        } else {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $userData = $stmt->fetch();

            if ($userData && password_verify($password, $userData['password'])) {
                // ✅ Stocker les infos utilisateur en session
                $_SESSION['user'] = $userData['email'];
                $_SESSION['is_admin'] = ($userData['role'] === 'admin');
                $_SESSION['bakery_admin'] = $userData['boulangerie'] ?? null;

                // Charger le panier
                $stmt = $pdo->prepare("SELECT product_name FROM cart_items WHERE user_email = ?");
                $stmt->execute([$userData['email']]);
                $_SESSION['cart'] = array_column($stmt->fetchAll(), 'product_name');

                // Charger les favoris
                $stmt = $pdo->prepare("SELECT bakery_name FROM favorites WHERE user_email = ?");
                $stmt->execute([$userData['email']]);
                $_SESSION['favorites'] = array_column($stmt->fetchAll(), 'bakery_name');

                header("Location: index.php");
                exit;
            } else {
                $login_error = "Identifiants invalides";
            }
        }
    }

    // Inscription
    if (isset($_POST['register'])) {
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $login_error = "Adresse email invalide";
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
            $stmt->execute([$email, $password]);

            $_SESSION['user'] = $email;
            $_SESSION['is_admin'] = false;
            $_SESSION['cart'] = [];
            $_SESSION['favorites'] = [];

            header("Location: index.php");
            exit;
        }
    }

    // Déconnexion
    if (isset($_POST['logout'])) {
        session_destroy();
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
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
<script>
  // Disparition du message après 7 secondes (7000 ms)
  setTimeout(function() {
    const alert = document.getElementById('welcome-alert');
    if (alert) {
      alert.style.transition = 'opacity 1s ease';
      alert.style.opacity = 0;

      // Ensuite on le retire du DOM après animation
      setTimeout(() => alert.remove(), 500);
    }
  }, 7000);
</script>
<main>
<?php if ($welcome_message): ?>
  <div id="welcome-alert" class="alert alert-success text-center">
    <?= htmlspecialchars($welcome_message) ?>
  </div>
<?php endif; ?>
<body class="bg-light">
<div class="position-relative text-center">
    <img src="accueil.jpg" class="img-fluid w-100" alt="Boulangerie" style="width: 100%; max-height: 600px; object-fit: cover; min-height: 400px;">
<div class="position-absolute top-50 start-50 translate-middle text-white bg-dark bg-opacity-50 p-4 rounded welcome-text">
  <h1>Bienvenue sur Croissant Coursier !</h1>
  <p>Commandez dès maintenant dans votre boulangerie préférée.</p>
  <a href="boulangeries.php" class="nav-highlight mt-3">🛍️ Commander</a>
</div>
</div>

<div class="container text-center mt-5">
  <h2 class="mb-4">Pourquoi commander sur Croissant Coursier ?</h2>
  <div class="row">
    <div class="col-md-4">
      <img src="front1.jpg" class="img-fluid rounded mb-2" alt="Image 1">
      <h2>Rapide</h2>
      <p>Choisissez un artisan parmis notre liste de boulangeries et faite vous livrer dans l'immédiat ! 
      </p>
    </div>
    <div class="col-md-4">
      <img src="front2.jpg" class="img-fluid rounded mb-2" alt="Image 2">
      <h2>N'importe où</h2>
      <p>N'importe où, n'importe quand 24/7 non stop en livraison ou retrait </p>
    </div>
    <div class="col-md-4">
      <img src="front3.jpg" class="img-fluid rounded mb-2" alt="Image 3">
      <h2>Rejoignez nous !</h2>
      <p>Lancez votre propre webshop sur notre site et suivez l'exemple de plus de 1000 boulangers, pâtissiers et chocolatiers. Digitalisez votre business aujourd'hui!</p>
    </div>
  </div>
</div>

</main>

<?php require_once 'footer.php'; ?>

</body>
</html>