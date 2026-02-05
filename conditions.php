<?php
session_start();
require_once 'db.php';
require_once 'navbar.php';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mentions légales</title>
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
<body>
<div class="container mt-5">
    <h2>Conditions générales d'utilisation</h2>

    <p>Bienvenue sur Croissant Coursier. En accédant à ce site web, vous acceptez pleinement et sans réserve les présentes Conditions Générales d’Utilisation (CGU). Si vous n'acceptez pas tout ou partie des conditions ci-dessous, veuillez ne pas utiliser notre service.</p>

    <h4>1. Objet du service</h4>
    <p>Le site permet aux utilisateurs de consulter des produits proposés par diverses boulangeries locales et de passer commande en ligne. Les utilisateurs s'engagent à ne pas utiliser ce service à des fins frauduleuses ou illégales.</p>

    <h4>2. Commandes et paiements</h4>
    <p>Les commandes effectuées sur Croissant Coursier sont fermes et définitives une fois le paiement validé. Les produits sont livrés sous 24 à 48 heures, selon les disponibilités et les zones de livraison.</p>
    <p>Aucun remboursement ne pourra être exigé une fois la commande confirmée, sauf en cas d'erreur manifeste ou d'indisponibilité totale des produits commandés.</p>

    <h4>3. Responsabilité</h4>
    <p>Nous mettons tout en œuvre pour assurer le bon fonctionnement du site, mais ne pouvons garantir l'absence de bugs ou d'interruptions. La responsabilité de Croissant Coursier ne saurait être engagée en cas de dommage direct ou indirect lié à l'utilisation du site.</p>

    <h4>4. Données personnelles</h4>
    <p>Les données personnelles collectées (nom, adresse, coordonnées bancaires, etc.) sont traitées dans le strict respect du Règlement Général sur la Protection des Données (RGPD). Ces données ne sont jamais vendues à des tiers et sont utilisées uniquement pour assurer le bon déroulement des commandes.</p>

    <h4>5. Propriété intellectuelle</h4>
    <p>Le contenu du site (textes, images, logos, éléments graphiques, etc.) est protégé par le droit d’auteur. Toute reproduction ou utilisation sans autorisation préalable est interdite.</p>

    <h4>6. Modification des CGU</h4>
    <p>Les présentes conditions peuvent être modifiées à tout moment. Les utilisateurs seront informés des mises à jour par une notification sur le site.</p>

    <p>Dernière mise à jour : Juin 2025</p>
</div>

</main>

<?php require_once 'footer.php'; ?>

</body>
</html>