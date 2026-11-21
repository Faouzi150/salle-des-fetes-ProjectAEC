cat << 'EOF' > index.php
<?php
require_once __DIR__ . '/data/salles.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accueil — Palais des Fêtes</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header>
    <div class="conteneur nav-barre">
      <a href="index.php" class="logo">Palais des Fêtes</a>
      <nav aria-label="Navigation principale">
        <ul>
          <li><a href="index.php" class="actif">Accueil</a></li>
          <li><a href="catalogue.php">Nos Salles</a></li>
          <li><a href="reservation.php">Réserver</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main class="conteneur">
    <section class="hero">
      <h1>Des espaces d'exception pour vos événements inoubliables</h1>
      <p>Mariages, réceptions familiales, galas ou séminaires : découvrez nos salles modulables et nos formules clé en main.</p>
      <a href="catalogue.php" class="btn btn-primaire">Explorer le catalogue</a>
    </section>

    <section aria-labelledby="titre-apercu">
      <h2 id="titre-apercu" class="section-titre">Nos espaces à la une</h2>
      <div class="grille-cartes">
        <?php foreach ($salles as $salle): ?>
          <article class="carte">
            <div>
              <span class="badge"><?= htmlspecialchars($salle['categorie'], ENT_QUOTES, 'UTF-8') ?></span>
              <h3><?= htmlspecialchars($salle['nom'], ENT_QUOTES, 'UTF-8') ?></h3>
              <p class="meta">
                Capacité : <?= (int) $salle['capacite'] ?> personnes •
                <?= (int) $salle['superficie'] ?> m² •
                <?= number_format((int) $salle['tarif'], 0, ',', ' ') ?> € / jour
              </p>
              <p><?= htmlspecialchars($salle['resume'], ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <a href="detail.php?id=<?= (int) $salle['id'] ?>" class="btn btn-secondaire">Voir la fiche</a>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
  </main>

  <footer>
    <div class="conteneur">
      <p>&copy; 2026 Palais des Fêtes — ProjetAEC. Tous droits réservés.</p>
    </div>
  </footer>
</body>
</html>
EOF