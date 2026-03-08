
<?php
/**
 * ============================================================
 * FICHIER : logout.php
 * RÔLE    : Déconnexion — Détruire la session
 * ============================================================
 *
 * 🎓 LEÇON : Déconnexion propre en PHP
 *
 * Pour déconnecter quelqu'un, il faut :
 *   1. Démarrer la session (pour y accéder)
 *   2. Vider le tableau $_SESSION
 *   3. Supprimer le cookie de session
 *   4. Détruire la session côté serveur
 *   5. Rediriger vers le login
 */

session_start();

// 1. Vider toutes les variables de session
$_SESSION = [];

// 2. Supprimer le cookie PHPSESSID du navigateur
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), // 'PHPSESSID'
        '',             // Valeur vide
        time() - 3600, // Expiration dans le passé → le navigateur le supprime
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

// 3. Détruire la session côté serveur
session_destroy();

// 4. Rediriger vers la page de login
header('Location: index.php');
exit;