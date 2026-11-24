<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/auth.php';

if (utilisateurConnecte() !== null) {
    header('Location: admin.php');
    exit;
}

$email = '';
$erreur = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $motDePasse = $_POST['mot_de_passe'] ?? '';

    if ($email === '' || $motDePasse === '') {
        $erreur = 'Veuillez renseigner votre adresse e-mail et votre mot de passe.';
    } else {
        $stmt = $pdo->prepare('SELECT id, nom, email, mot_de_passe, role FROM utilisateurs WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $utilisateur = $stmt->fetch();

        if ($utilisateur && password_verify($motDePasse, $utilisateur['mot_de_passe'])) {
            session_regenerate_id(true);
            $_SESSION['utilisateur'] = [
                'id'    => (int) $utilisateur['id'],
                'nom'   => $utilisateur['nom'],
                'email' => $utilisateur['email'],
                'role'  => $utilisateur['role'],
            ];
            header('Location: admin.php');
            exit;
        }

        $erreur = 'Identifiants incorrects.';
    }
}

$pageTitre = 'Connexion espace gestion';
$pageActive = 'login';
require_once __DIR__ . '/includes/header.php';
?>
  <main class="conteneur">
    <h1 class="section-titre">Connexion à l'espace gestion</h1>

    <?php if ($erreur !== null): ?>
      <section class="carte" style="margin-bottom: 1.5rem; border-left: 4px solid #b91c1c;">
        <div>
          <p><?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?></p>
        </div>
      </section>
    <?php endif; ?>

    <form action="login.php" method="post" class="formulaire-bloc">
      <div class="champ">
        <label for="email">Adresse e-mail *</label>
        <input
          type="email"
          id="email"
          name="email"
          value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
          required
        >
      </div>

      <div class="champ">
        <label for="mot_de_passe">Mot de passe *</label>
        <input
          type="password"
          id="mot_de_passe"
          name="mot_de_passe"
          required
        >
      </div>

      <button type="submit" class="btn btn-primaire">Se connecter</button>
    </form>
  </main>
<?php
require_once __DIR__ . '/includes/footer.php';
?>
