cat << 'EOF' > detail.php
<?php
require_once __DIR__ . '/data/salles.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 1;
$salle = $salles[$id] ?? reset($salles);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($salle['nom'], ENT_QUOTES, 'UTF-8') ?> — Palais des Fêtes</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header>
    <div class="conteneur nav-barre">
      <a href="index.php" class="logo">Palais des Fêtes</a>
      <nav aria-label="Navigation principale">
        <ul>
          <li><a href="index.php">Accueil</a></li>
          <li><a href="catalogue.php" class="actif">Nos Salles</a></li>
          <li><a href="reservation.php">Réserver</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main class="conteneur">
    <p style="margin-top: 1.5rem;"><a href="catalogue.php">&larr; Retour au catalogue</a></p>

    <article class="carte" style="margin-top: 1rem;">
      <div>
        <span class="badge"><?= htmlspecialchars($salle['categorie'], ENT_QUOTES, 'UTF-8') ?></span>
        <h1 class="section-titre" style="margin-top: 0.5rem;"><?= htmlspecialchars($salle['nom'], ENT_QUOTES, 'UTF-8') ?></h1>
        <p class="meta">
          Capacité maximale : <?= (int) $salle['capacite'] ?> personnes •
          Superficie : <?= (int) $salle['superficie'] ?> m² •
          Tarif : <?= number_format((int) $salle['tarif'], 0, ',', ' ') ?> € / jour
        </p>
        <p style="margin-top: 1rem;"><?= htmlspecialchars($salle['description'], ENT_QUOTES, 'UTF-8') ?></p>
      </div>

      <section aria-labelledby="titre-formules">
        <h2 id="titre-formules" class="section-titre" style="font-size: 1.2rem;">Formules disponibles en option</h2>
        <div class="grille-cartes">
          <?php foreach ($salle['formules'] as $formule): ?>
            <div class="carte">
              <h3><?= htmlspecialchars($formule['nom'], ENT_QUOTES, 'UTF-8') ?></h3>
              <p class="meta">Forfait : <?= (int) $formule['tarif'] ?> €</p>
              <p><?= htmlspecialchars($formule['desc'], ENT_QUOTES, 'UTF-8') ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </section>

      <div>
        <a href="reservation.php?salle=<?= (int) $salle['id'] ?>" class="btn btn-primaire">Réserver cette salle</a>
      </div>
    </article>
  </main>

  <footer>
    <div class="conteneur">
      <p>&copy; 2026 Palais des Fêtes — ProjetAEC. Tous droits réservés.</p>
    </div>
  </footer>
</body>
</html>