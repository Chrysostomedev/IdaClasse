<?php
/**
 * ============================================================
 * FICHIER : includes/header.php
 * RÔLE    : En-tête HTML + Navigation commune à toutes les pages
 * ============================================================
 *
 * 🎓 LEÇON : L'INCLUSION DE FICHIERS PHP
 *
 * require_once 'includes/header.php' → colle le contenu ici
 *
 * Avantage : on écrit le header UNE FOIS, utilisé partout.
 * Si on veut changer le logo → modifier UN seul fichier !
 *
 * C'est le principe DRY : Don't Repeat Yourself
 */

// Ce fichier peut recevoir ces variables depuis la page appelante :
// $pageTitre    (string) → Titre de la page
// $pageActive   (string) → Quelle page est active dans la nav
$pageTitre  = $pageTitre  ?? 'GestionSalles';
$pageActive = $pageActive ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitre) ?> — GestionSalles CI</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSS Principal -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/app.css">
</head>
<body>

    <!-- ===================================================
         SIDEBAR / NAVIGATION LATÉRALE
         =================================================== -->
    <aside class="sidebar" id="sidebar">

        <!-- Logo de l'app -->
        <div class="sidebar-logo">
            <div class="logo-icon">
                <i class="fas fa-school"></i>
            </div>
            <div class="logo-text">
                <span class="logo-name">GestionSalles</span>
                <span class="logo-sub">ESC Castaing</span>
            </div>
        </div>

        <!-- Infos utilisateur connecté -->
        <?php
        // Récupérer l'utilisateur depuis la session
        $user = utilisateurConnecte();
        if ($user) : ?>
            <div class="user-card">
                <div class="user-avatar">
                    <!-- Première lettre du prénom comme avatar -->
                    <?= strtoupper(substr($user['prenom'], 0, 1)) ?>
                </div>
                <div class="user-info">
                    <span class="user-name"><?= e($user['prenom'] . ' ' . $user['nom']) ?></span>
                    <span class="user-role role-<?= e($user['role']) ?>">
                        <?= e(ucfirst($user['role'])) ?>
                    </span>
                </div>
            </div>
        <?php endif; ?>

        <!-- Menu de navigation -->
        <nav class="sidebar-nav">
            <div class="nav-section-label">Navigation</div>
            <ul>
                <li>
                    <a href="<?= BASE_URL ?>pages/dashboard.php"
                       class="nav-link <?= $pageActive === 'dashboard' ? 'active' : '' ?>">
                        <span class="nav-icon"><i class="fas fa-chart-line"></i></span>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>pages/salles.php"
                       class="nav-link <?= $pageActive === 'salles' ? 'active' : '' ?>">
                        <span class="nav-icon"><i class="fas fa-door-open"></i></span>
                        <span>Salles</span>
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>pages/classes.php"
                       class="nav-link <?= $pageActive === 'classes' ? 'active' : '' ?>">
                        <span class="nav-icon"><i class="fas fa-book-open"></i></span>
                        <span>Classes</span>
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>pages/utilisateurs.php"
                       class="nav-link <?= $pageActive === 'utilisateurs' ? 'active' : '' ?>">
                        <span class="nav-icon"><i class="fas fa-users"></i></span>
                        <span>Etudiants</span>
                    </a>
                </li>
            </ul>

            <div class="nav-section-label">Compte</div>
            <ul>
                <li>
                    <a href="<?= BASE_URL ?>pages/profil.php"
                       class="nav-link <?= $pageActive === 'profil' ? 'active' : '' ?>">
                        <span class="nav-icon"><i class="fas fa-user-cog"></i></span>
                        <span>Mon profil</span>
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>logout.php" class="nav-link nav-link-danger">
                        <span class="nav-icon"><i class="fas fa-sign-out-alt"></i></span>
                        <span>Déconnexion</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Overlay pour mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- ===================================================
         ZONE DE CONTENU PRINCIPALE
         =================================================== -->
    <main class="main-content">

        <!-- Topbar -->
        <header class="topbar">
            <!-- Bouton burger (mobile) -->
            <button class="burger-btn" id="burgerBtn" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Titre de la page actuelle -->
            <h1 class="page-title"><?= e($pageTitre) ?></h1>

            <!-- Actions rapides à droite -->
            <div class="topbar-actions">
                <!-- Bouton mode sombre/clair -->
                <button class="theme-toggle" id="themeToggle" data-tooltip="Changer le thème">
                    <i class="fas fa-moon"></i>
                </button>
                
                <span class="topbar-date">
                    <i class="far fa-calendar"></i>
                    <?php
                    // Afficher la date en français
                    $jours = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
                    $mois = ['', 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 
                             'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
                    echo $jours[date('w')] . ' ' . date('d') . ' ' . $mois[date('n')] . ' ' . date('Y');
                    ?>
                </span>
            </div>
        </header>

        <!-- Le contenu de chaque page va ici (injection depuis les pages) -->
        <div class="page-content">