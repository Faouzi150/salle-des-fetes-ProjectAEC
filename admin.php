cd /Applications/MAMP/htdocs/ProjectAEC
cat << 'EOF' > admin.php
<?php
require_once __DIR__ . '/config/db.php';

$statutsAutorises = [
    'en_attente' => 'En attente',
    'confirmee'  => 'Confirmée',
    'annulee'    => 'Annulée',
];

$messageSucces = null;
$messageErreur = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['supprimer_id'])) {
        $idSuppression = (int) $_POST['supprimer_id'];

        if ($idSuppression > 0) {
            $stmtDelete = $pdo->prepare('DELETE FROM reservations WHERE id = :id');
            $stmtDelete->execute([':id' => $idSuppression]);

            if ($stmtDelete->rowCount() > 0) {
                $messageSucces = 'La réservation #' . $idSuppression . ' a bien été supprimée.';
            } else {
                $messageErreur = 'Aucune réservation correspondante trouvée.';
            }
        } else {
            $messageErreur = 'Identifiant de réservation invalide.';
        }
    } elseif (isset($_POST['statut_id'], $_POST['nouveau_statut'])) {
        $idReservation = (int) $_POST['statut_id'];
        $nouveauStatut = trim($_POST['nouveau_statut']);

        if ($idReservation > 0 && isset($statutsAutorises[$nouveauStatut])) {
            $stmtUpdate = $pdo->prepare('UPDATE reservations SET statut = :statut WHERE id = :id');
            $stmtUpdate->execute([
                ':statut' => $nouveauStatut,
                ':id'     => $idReservation,
            ]);

            if ($stmtUpdate->rowCount() > 0) {
                $messageSucces = 'Statut de la réservation #' . $idReservation . ' mis à jour (« ' . $statutsAutorises[$nouveauStatut] . ' »).';
            } else {
                $messageSucces = 'Statut inchangé pour la réservation #' . $idReservation . '.';
            }
        } else {
            $messageErreur = 'Statut ou identifiant de réservation invalide.';
        }
    }
}

$sql = 'SELECT
    r.id,
    r.salle_id,
    s.nom AS salle_nom,
    r.formule_nom,
    r.date_evenement,
    r.nb_invites,
    r.nom_client,
    r.email_client,
    r.telephone_client,
    r.commentaire,
    r.montant_estime,
    r.statut,
    r.date_creation
FROM reservations r
INNER JOIN salles s ON s.id = r.salle_id
ORDER BY r.date_evenement ASC, r.id DESC';

$reservations = $pdo->query($sql)->fetchAll();

$pageTitre = 'Gestion des réservations';
$pageActive = 'admin';
require_once __DIR__ . '/includes/header.php';
?>
  <main class="conteneur">
    <h1 class="section-titre">Réservations enregistrées</h1>

    <?php if ($messageSucces !== null): ?>
      <section class="carte" style="margin-bottom: 1.5rem; border-left: 4px solid #15803d;">
        <div>
          <p><?= htmlspecialchars($messageSucces, ENT_QUOTES, 'UTF-8') ?></p>
        </div>
      </section>
    <?php endif; ?>

    <?php if ($messageErreur !== null): ?>
      <section class="carte" style="margin-bottom: 1.5rem; border-left: 4px solid #b91c1c;">
        <div>
          <p><?= htmlspecialchars($messageErreur, ENT_QUOTES, 'UTF-8') ?></p>
        </div>
      </section>
    <?php endif; ?>

    <?php if (empty($reservations)): ?>
      <section class="carte">
        <div>
          <h3>Aucune réservation pour le moment</h3>
          <p>Les demandes envoyées depuis le formulaire de réservation apparaîtront ici.</p>
          <a href="reservation.php" class="btn btn-primaire">Aller au formulaire</a>
        </div>
      </section>
    <?php else: ?>
      <p class="meta" style="margin-bottom: 1rem;">Total : <?= count($reservations) ?> réservation(s)</p>

      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr>
              <th style="text-align: left; padding: 0.6rem; border-bottom: 2px solid #ccc;">#</th>
              <th style="text-align: left; padding: 0.6rem; border-bottom: 2px solid #ccc;">Date</th>
              <th style="text-align: left; padding: 0.6rem; border-bottom: 2px solid #ccc;">Salle</th>
              <th style="text-align: left; padding: 0.6rem; border-bottom: 2px solid #ccc;">Formule</th>
              <th style="text-align: left; padding: 0.6rem; border-bottom: 2px solid #ccc;">Client</th>
              <th style="text-align: left; padding: 0.6rem; border-bottom: 2px solid #ccc;">Invités</th>
              <th style="text-align: left; padding: 0.6rem; border-bottom: 2px solid #ccc;">Estimation</th>
              <th style="text-align: left; padding: 0.6rem; border-bottom: 2px solid #ccc;">Statut</th>
              <th style="text-align: left; padding: 0.6rem; border-bottom: 2px solid #ccc;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reservations as $res): ?>
              <?php $statutActuel = $res['statut'] ?? 'en_attente'; ?>
              <tr>
                <td style="padding: 0.6rem; border-bottom: 1px solid #e5e7eb;"><?= (int) $res['id'] ?></td>
                <td style="padding: 0.6rem; border-bottom: 1px solid #e5e7eb;"><?= htmlspecialchars($res['date_evenement'], ENT_QUOTES, 'UTF-8') ?></td>
                <td style="padding: 0.6rem; border-bottom: 1px solid #e5e7eb;"><?= htmlspecialchars($res['salle_nom'], ENT_QUOTES, 'UTF-8') ?></td>
                <td style="padding: 0.6rem; border-bottom: 1px solid #e5e7eb;"><?= htmlspecialchars($res['formule_nom'] ?? 'Aucune', ENT_QUOTES, 'UTF-8') ?></td>
                <td style="padding: 0.6rem; border-bottom: 1px solid #e5e7eb;">
                  <strong><?= htmlspecialchars($res['nom_client'], ENT_QUOTES, 'UTF-8') ?></strong><br>
                  <span class="meta"><?= htmlspecialchars($res['email_client'], ENT_QUOTES, 'UTF-8') ?> • <?= htmlspecialchars($res['telephone_client'], ENT_QUOTES, 'UTF-8') ?></span>
                </td>
                <td style="padding: 0.6rem; border-bottom: 1px solid #e5e7eb;"><?= (int) $res['nb_invites'] ?></td>
                <td style="padding: 0.6rem; border-bottom: 1px solid #e5e7eb;"><strong><?= number_format((int) $res['montant_estime'], 0, ',', ' ') ?> €</strong></td>
                <td style="padding: 0.6rem; border-bottom: 1px solid #e5e7eb;">
                  <form action="admin.php" method="post" style="display: flex; gap: 0.4rem; align-items: center;">
                    <input type="hidden" name="statut_id" value="<?= (int) $res['id'] ?>">
                    <select name="nouveau_statut">
                      <?php foreach ($statutsAutorises as $cleStatut => $libelleStatut): ?>
                        <option value="<?= htmlspecialchars($cleStatut, ENT_QUOTES, 'UTF-8') ?>" <?= $statutActuel === $cleStatut ? 'selected' : '' ?>>
                          <?= htmlspecialchars($libelleStatut, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn">Enregistrer</button>
                  </form>
                </td>
                <td style="padding: 0.6rem; border-bottom: 1px solid #e5e7eb;">
                  <form action="admin.php" method="post" onsubmit="return confirm('Supprimer cette réservation ?');">
                    <input type="hidden" name="supprimer_id" value="<?= (int) $res['id'] ?>">
                    <button type="submit" class="btn">Supprimer</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </main>
<?php
require_once __DIR__ . '/includes/footer.php';
?>
EOF
/Applications/MAMP/bin/php/php8.5.2/bin/php -l admin.php
