<?php
require_once __DIR__ . '/config/db.php';

$recherche = trim($_GET['q'] ?? '');
$categorie = trim($_GET['categorie'] ?? '');
$tri = $_GET['tri'] ?? 'id_asc';

$orderBy = match ($tri) {
    'tarif_asc' => 'tarif ASC',
    'tarif_desc' => 'tarif DESC',
    'capacite_desc' => 'capacite DESC',
    default => 'id ASC',
};

$conditions = [];
$params = [];

if ($recherche !== '') {
    $conditions[] = '(nom LIKE :motif1 OR categorie LIKE :motif2 OR resume LIKE :motif3 OR description LIKE :motif4)';
    $motif = '%' . $recherche . '%';
    $params[':motif1'] = $motif;
    $params[':motif2'] = $motif;
    $params[':motif3'] = $motif;
    $params[':motif4'] = $motif;
}

if ($categorie !== '') {
    $conditions[] = 'categorie = :categorie';
    $params[':categorie'] = $categorie;
}

$sql = 'SELECT id, nom, categorie, capacite, superficie, tarif, resume FROM salles';
if (!empty($conditions)) {
    $sql .= ' WHERE ' . implode(' AND ', $conditions);
}
$sql .= ' ORDER BY ' . $orderBy;

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$salles = $stmt->fetchAll();

$stmtCategories = $pdo->query('SELECT DISTINCT categorie FROM salles ORDER BY categorie ASC');
$categories = $stmtCategories->fetchAll(PDO::FETCH_COLUMN);

$pageTitre = 'Catalogue des salles';
$pageActive = 'catalogue';
require_once __DIR__ . '/includes/header.php';
?>
  <main class="conteneur">
    <h1 class="section-titre">Catalogue de nos salles</h1>

    <form action="catalogue.php" method="get" class="formulaire-bloc" style="margin-bottom: 2rem;">
      <div class="champ">
        <label for="q">Rechercher par mot-clé</label>
        <input
          type="text"
          id="q"
          name="q"
          placeholder="Ex. Royal, jardin, mariage..."
          value="<?= htmlspecialchars($recherche, ENT_QUOTES, 'UTF-8') ?>"
        >
      </div>

      <div class="champ">
        <label for="categorie">Catégorie</label>
        <select id="categorie" name="categorie">
          <option value="">Toutes les catégories</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') ?>" <?= $categorie === $cat ? 'selected' : '' ?>>
              <?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="champ">
        <label for="tri">Trier par</label>
        <select id="tri" name="tri">
          <option value="id_asc" <?= $tri === 'id_asc' ? 'selected' : '' ?>>Ordre par défaut</option>
          <option value="tarif_asc" <?= $tri === 'tarif_asc' ? 'selected' : '' ?>>Tarif croissant</option>
          <option value="tarif_desc" <?= $tri === 'tarif_desc' ? 'selected' : '' ?>>Tarif décroissant</option>
          <option value="capacite_desc" <?= $tri === 'capacite_desc' ? 'selected' : '' ?>>Capacité (plus grande)</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primaire">Filtrer</button>
      <?php if ($recherche !== '' || $categorie !== '' || $tri !== 'id_asc'): ?>
        <a href="catalogue.php" class="btn">Réinitialiser</a>
      <?php endif; ?>
    </form>

    <?php if (empty($salles)): ?>
      <section class="carte">
        <div>
          <h3>Aucune salle trouvée</h3>
          <p>Aucune salle ne correspond à votre recherche « <?= htmlspecialchars($recherche, ENT_QUOTES, 'UTF-8') ?> ».</p>
        </div>
      </section>
    <?php else: ?>
      <div class="grille-cartes">
        <?php foreach ($salles as $salle): ?>
          <article class="carte">
            <div>
              <p class="meta">
                <?= htmlspecialchars($salle['categorie'], ENT_QUOTES, 'UTF-8') ?> •
                <?= (int) $salle['capacite'] ?> pers. max •
                <?= (int) $salle['superficie'] ?> m²
              </p>
              <h3><?= htmlspecialchars($salle['nom'], ENT_QUOTES, 'UTF-8') ?></h3>
              <p><?= htmlspecialchars($salle['resume'], ENT_QUOTES, 'UTF-8') ?></p>
              <p><strong><?= number_format((int) $salle['tarif'], 0, ',', ' ') ?> €</strong></p>
              <a href="detail.php?id=<?= (int) $salle['id'] ?>" class="btn btn-primaire">Voir la salle</a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </main>
<?php
require_once __DIR__ . '/includes/footer.php';
?>
