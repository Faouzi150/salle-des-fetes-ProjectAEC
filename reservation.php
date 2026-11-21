cat << 'EOF' > reservation.php
<?php
require_once __DIR__ . '/data/salles.php';

$sallePreselectionnee = isset($_GET['salle']) ? (int) $_GET['salle'] : (isset($_GET['id']) ? (int) $_GET['id'] : 0);

$erreurs = [];
$confirmation = null;

$valeurs = [
    'id_salle' => $sallePreselectionnee,
    'formule' => '',
    'date_evenement' => '',
    'nb_invites' => '',
    'nom_client' => '',
    'email_client' => '',
    'telephone_client' => '',
    'commentaire' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valeurs['id_salle'] = (int) ($_POST['id_salle'] ?? 0);
    $valeurs['formule'] = trim($_POST['formule'] ?? '');
    $valeurs['date_evenement'] = trim($_POST['date_evenement'] ?? '');
    $valeurs['nb_invites'] = (int) ($_POST['nb_invites'] ?? 0);
    $valeurs['nom_client'] = trim($_POST['nom_client'] ?? '');
    $valeurs['email_client'] = trim($_POST['email_client'] ?? '');
    $valeurs['telephone_client'] = trim($_POST['telephone_client'] ?? '');
    $valeurs['commentaire'] = trim($_POST['commentaire'] ?? '');

    if (!isset($salles[$valeurs['id_salle']])) {
        $erreurs[] = 'Veuillez sélectionner une salle valide.';
    }

    if ($valeurs['date_evenement'] === '' || DateTime::createFromFormat('Y-m-d', $valeurs['date_evenement']) === false) {
        $erreurs[] = 'Veuillez saisir une date d\'événement valide.';
    }

    if ($valeurs['nb_invites'] < 10 || $valeurs['nb_invites'] > 500) {
        $erreurs[] = 'Le nombre d\'invités doit être compris entre 10 et 500.';
    } elseif (isset($salles[$valeurs['id_salle']]) && $valeurs['nb_invites'] > $salles[$valeurs['id_salle']]['capacite']) {
        $erreurs[] = 'Le nombre d\'invités dépasse la capacité maximale de la salle sélectionnée (' . $salles[$valeurs['id_salle']]['capacite'] . ' personnes).';
    }

    if (mb_strlen($valeurs['nom_client'], 'UTF-8') < 2) {
        $erreurs[] = 'Veuillez saisir votre nom et prénom.';
    }

    if (!filter_var($valeurs['email_client'], FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = 'Veuillez saisir une adresse e-mail valide.';
    }

    if (mb_strlen($valeurs['telephone_client'], 'UTF-8') < 6) {
        $erreurs[] = 'Veuillez saisir un numéro de téléphone valide.';
    }

    if (empty($erreurs)) {
        $salleChoisie = $salles[$valeurs['id_salle']];
        $montantEstime = $salleChoisie['tarif'];

        foreach ($salleChoisie['formules'] as $formuleItem) {
            if ($formuleItem['nom'] === $valeurs['formule']) {
                $montantEstime += $formuleItem['tarif'];
                break;
            }
        }

        $confirmation = [
            'salle' => $salleChoisie['nom'],
            'formule' => $valeurs['formule'] !== '' ? $valeurs['formule'] : 'Aucune',
            'date_evenement' => $valeurs['date_evenement'],
            'nb_invites' => $valeurs['nb_invites'],
            'nom_client' => $valeurs['nom_client'],
            'email_client' => $valeurs['email_client'],
            'telephone_client' => $valeurs['telephone_client'],
            'commentaire' => $valeurs['commentaire'],
            'montant_estime' => $montantEstime,
        ];
    }
}

$salleActive = isset($salles[$valeurs['id_salle']]) ? $salles[$valeurs['id_salle']] : null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Demande de réservation — Palais des Fêtes</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header>
    <div class="conteneur nav-barre">
      <a href="index.php" class="logo">Palais des Fêtes</a>
      <nav aria-label="Navigation principale">
        <ul>
          <li><a href="index.php">Accueil</a></li>
          <li><a href="catalogue.php">Nos Salles</a></li>
          <li><a href="reservation.php" class="actif">Réserver</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main class="conteneur">
    <h1 class="section-titre">Demander une réservation</h1>

    <?php if (!empty($erreurs)): ?>
      <section aria-label="Erreurs de saisie" class="carte" style="margin-bottom: 1.5rem; border-left: 4px solid #b91c1c;">
        <div>
          <h3>Veuillez corriger les points suivants :</h3>
          <ul>
            <?php foreach ($erreurs as $erreur): ?>
              <li><?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </section>
    <?php endif; ?>

    <?php if ($confirmation !== null): ?>
      <section aria-label="Confirmation de demande" class="carte" style="margin-bottom: 1.5rem; border-left: 4px solid #15803d;">
        <div>
          <h3>Demande enregistrée pour <?= htmlspecialchars($confirmation['nom_client'], ENT_QUOTES, 'UTF-8') ?></h3>
          <p class="meta">
            Salle : <?= htmlspecialchars($confirmation['salle'], ENT_QUOTES, 'UTF-8') ?> •
            Date : <?= htmlspecialchars($confirmation['date_evenement'], ENT_QUOTES, 'UTF-8') ?> •
            Invités : <?= (int) $confirmation['nb_invites'] ?>
          </p>
          <p>Formule : <?= htmlspecialchars($confirmation['formule'], ENT_QUOTES, 'UTF-8') ?></p>
          <p>Contact : <?= htmlspecialchars($confirmation['email_client'], ENT_QUOTES, 'UTF-8') ?> / <?= htmlspecialchars($confirmation['telephone_client'], ENT_QUOTES, 'UTF-8') ?></p>
          <?php if ($confirmation['commentaire'] !== ''): ?>
            <p>Précisions : <?= htmlspecialchars($confirmation['commentaire'], ENT_QUOTES, 'UTF-8') ?></p>
          <?php endif; ?>
          <p><strong>Estimation indicative : <?= number_format((int) $confirmation['montant_estime'], 0, ',', ' ') ?> €</strong></p>
        </div>
      </section>
    <?php endif; ?>

    <form action="reservation.php" method="post" class="formulaire-bloc">
      <div class="champ">
        <label for="id_salle">Salle souhaitée *</label>
        <select id="id_salle" name="id_salle" required>
          <option value="">-- Choisissez une salle --</option>
          <?php foreach ($salles as $salle): ?>
            <option value="<?= (int) $salle['id'] ?>" <?= (int) $valeurs['id_salle'] === (int) $salle['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($salle['nom'], ENT_QUOTES, 'UTF-8') ?>
              (<?= (int) $salle['capacite'] ?> pers. max — <?= number_format((int) $salle['tarif'], 0, ',', ' ') ?> €)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="champ">
        <label for="formule">Formule complémentaire (optionnel)</label>
        <select id="formule" name="formule">
          <option value="">Aucune formule</option>
          <?php if ($salleActive !== null): ?>
            <?php foreach ($salleActive['formules'] as $formule): ?>
              <option value="<?= htmlspecialchars($formule['nom'], ENT_QUOTES, 'UTF-8') ?>" <?= $valeurs['formule'] === $formule['nom'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($formule['nom'], ENT_QUOTES, 'UTF-8') ?> (+ <?= (int) $formule['tarif'] ?> €)
              </option>
            <?php endforeach; ?>
          <?php else: ?>
            <option value="Pack Son &amp; Lumière" <?= $valeurs['formule'] === 'Pack Son & Lumière' ? 'selected' : '' ?>>Pack Son &amp; Lumière (+ 350 €)</option>
            <option value="Décoration Florale" <?= $valeurs['formule'] === 'Décoration Florale' ? 'selected' : '' ?>>Décoration Florale (+ 480 €)</option>
          <?php endif; ?>
        </select>
      </div>

      <div class="champ">
        <label for="date_evenement">Date de l'événement *</label>
        <input
          type="date"
          id="date_evenement"
          name="date_evenement"
          value="<?= htmlspecialchars($valeurs['date_evenement'], ENT_QUOTES, 'UTF-8') ?>"
          required
        >
      </div>

      <div class="champ">
        <label for="nb_invites">Nombre d'invités estimé *</label>
        <input
          type="number"
          id="nb_invites"
          name="nb_invites"
          min="10"
          max="500"
          value="<?= htmlspecialchars((string) $valeurs['nb_invites'], ENT_QUOTES, 'UTF-8') ?>"
          required
        >
      </div>

      <div class="champ">
        <label for="nom_client">Nom et prénom *</label>
        <input
          type="text"
          id="nom_client"
          name="nom_client"
          value="<?= htmlspecialchars($valeurs['nom_client'], ENT_QUOTES, 'UTF-8') ?>"
          required
        >
      </div>

      <div class="champ">
        <label for="email_client">Adresse e-mail *</label>
        <input
          type="email"
          id="email_client"
          name="email_client"
          value="<?= htmlspecialchars($valeurs['email_client'], ENT_QUOTES, 'UTF-8') ?>"
          required
        >
      </div>

      <div class="champ">
        <label for="telephone_client">Téléphone *</label>
        <input
          type="tel"
          id="telephone_client"
          name="telephone_client"
          value="<?= htmlspecialchars($valeurs['telephone_client'], ENT_QUOTES, 'UTF-8') ?>"
          required
        >
      </div>

      <div class="champ">
        <label for="commentaire">Précisions ou besoins particuliers</label>
        <textarea id="commentaire" name="commentaire" rows="4"><?= htmlspecialchars($valeurs['commentaire'], ENT_QUOTES, 'UTF-8') ?></textarea>
      </div>

      <button type="submit" class="btn btn-primaire">Envoyer la demande</button>
    </form>
  </main>

  <footer>
    <div class="conteneur">
      <p>&copy; 2026 Palais des Fêtes — ProjetAEC. Tous droits réservés.</p>
    </div>
  </footer>
</body>
</html>
EOF