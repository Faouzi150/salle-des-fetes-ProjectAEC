<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function utilisateurConnecte(): ?array
{
    if (!empty($_SESSION['utilisateur']) && is_array($_SESSION['utilisateur'])) {
        return $_SESSION['utilisateur'];
    }
    return null;
}

function exigerConnexion(array $rolesAutorises = ['admin', 'gestionnaire']): array
{
    $utilisateur = utilisateurConnecte();
    if ($utilisateur === null) {
        header('Location: login.php');
        exit;
    }

    if (!in_array($utilisateur['role'] ?? '', $rolesAutorises, true)) {
        http_response_code(403);
        echo '<h1>Accès refusé</h1><p>Votre rôle ne permet pas d\'effectuer cette action.</p>';
        exit;
    }

    return $utilisateur;
}

function estAdmin(?array $utilisateur): bool
{
    return ($utilisateur['role'] ?? '') === 'admin';
}
