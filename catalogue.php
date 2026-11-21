cat << 'EOF' > catalogue.php
<?php
require_once __DIR__ . '/data/salles.php';

$recherche = trim($_GET['recherche'] ?? '');
$type = trim($_GET['type'] ?? '');
$tri = $_GET['tri'] ?? 'nom_asc';

$sallesFiltrees = array_filter($salles, static function (array $salle) use ($recherche, $type): bool {
    if ($type !== '' && strcasecmp($salle['categorie'], $type) !== 0) {
        return false;
    }

    if ($recherche !== '') {
        $texte = mb_strtolower($salle['nom'] . ' ' . $salle['categorie'] . ' ' . $salle['resume'] . ' ' . $salle['description'], 'UTF-8');
        $motif = mb_strtolower($recherche, 'UTF-8');
        if (mb_strpos($texte, $motif) === false) {
            return false;
        }
    }

    return true;
});

usort($sallesFiltrees, static function (array $a, array $b) use ($tri): int {
    return match ($tri) {
        'tarif_asc' => $a['tarif'] <=> $b['tarif'],
        'capacite_desc' => $b['capacite'] <=> $a['capacite'],
        default => strcmp($a['nom'], $b['nom']),
    };
});

$pageTitre = 'Nos Salles';
$pageActive = 'catalogue';
require_once __DIR__ . '/includes/header.php';
?>
  <main class="conteneur">
    <h1 class="section-titre">Catalogue de nos salles de réception</h1>

    <section aria-label="Recherche et filtres" class="formulaire-bloc" style="max-width: 100%; margin-bottom: 2rem;">
      <form action="catalogue.php" method="get" class="grille-cartes">
        <div class="champ">
          <label for="recherche">Mot-clé</label>
          <input
            type="text"
            id="recherche"
            name="recherche"
            value="<?= htmlspecialchars($recherche, ENT_QUOTES, 'UTF-8') ?>"
            placeholder="Ex. : Royal, terrasse, scène..."
          >
        </div>

        <div class="champ">
          <label for="type">Type d'événement</label>
          <select id="type" name="type">
            <option value="">Tous les types</option>
            <option value="Mariage &amp; Gala" <?= $type === 'Mariage & Gala' ? 'selected' : '' ?>>Mariage &amp; Gala</option>
            <option value="Réception" <?= $type === 'Réception' ? 'selected' : '' ?>>Réception</option>
            <option value="Séminaire" <?= $type === 'Séminaire' ? 'selected' : '' ?>>Séminaire</option>
          </select>
        </div>

        <div class="champ">
          <label for="tri">Trier par</label>
          <select id="tri" name="tri">
            <option value="nom_asc" <?= $tri === 'nom_asc' ? 'selected' : '' ?>>Nom (A → Z)</option>
            <option value="tarif_asc" <?= $tri === 'tarif_asc' ? 'selected' : '' ?>>Tarif croissant</option>
            <option value="capacite_desc" <?= $tri === 'capacite_desc' ? 'selected' : '' ?>>Capacité décroissante</option>
          </select>
        </div>

        <div class="champ" style="justify-content: flex-end;">
          <button type="submit" class="btn btn-primaire">Filtrer</button>
        </div>
      </form>
    </section>

    <section aria-label="Liste des salles" class="grille-cartes">
      <?php if (empty($sallesFiltrees)): ?>
        <article class="carte">
          <div>
            <h3>Aucune salle trouvée</h3>
            <p>Aucun espace ne correspond à vos critères actuels. Réinitialisez les filtres pour afficher toutes les salles.</p>
          </div>
          <a href="catalogue.php" class="btn btn-secondaire">Voir toutes les salles</a>
        </article>
      <?php else: ?>
        <?php foreach ($sallesFiltrees as $salle): ?>
          <article class="carte">
            <div>
              <span class="badge"><?= htmlspecialchars($salle['categorie'], ENT_QUOTES, 'UTF-8') ?></span>
              <h3><?= htmlspecialchars($salle['nom'], ENT_QUOTES, 'UTF-8') ?></h3>
              <p class="meta">
                <?= (int) $salle['capacite'] ?> invités •
                <?= (int) $salle['superficie'] ?> m² •
                <?= number_format((int) $salle['tarif'], 0, ',', ' ') ?> € / jour
              </p>
              <p><?= htmlspecialchars($salle['resume'], ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <a href="detail.php?id=<?= (int) $salle['id'] ?>" class="btn btn-secondaire">Consulter la fiche</a>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </section>
  </main>
<?php
require_once __DIR__ . '/includes/footer.php';
?>
EOF