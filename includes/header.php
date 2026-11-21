cat << 'EOF' > includes/header.php
<?php
$pageTitre = $pageTitre ?? 'Palais des Fêtes';
$pageActive = $pageActive ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitre, ENT_QUOTES, 'UTF-8') ?> — Palais des Fêtes</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header>
    <div class="conteneur nav-barre">
      <a href="index.php" class="logo">Palais des Fêtes</a>
      <nav aria-label="Navigation principale">
        <ul>
          <li><a href="index.php"<?= $pageActive === 'accueil' ? ' class="actif"' : '' ?>>Accueil</a></li>
          <li><a href="catalogue.php"<?= $pageActive === 'catalogue' ? ' class="actif"' : '' ?>>Nos Salles</a></li>
          <li><a href="reservation.php"<?= $pageActive === 'reservation' ? ' class="actif"' : '' ?>>Réserver</a></li>
        </ul>
      </nav>
    </div>
  </header>