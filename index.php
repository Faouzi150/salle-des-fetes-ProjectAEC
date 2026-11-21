cat << 'EOF' > index.php
<?php
require_once __DIR__ . '/data/salles.php';

$pageTitre = 'Accueil';
$pageActive = 'accueil';
require_once __DIR__ . '/includes/header.php';
?>
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
<?php
require_once __DIR__ . '/includes/footer.php';
?>
EOF