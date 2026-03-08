<?php
/**
 * ============================================================
 * FICHIER : index.php (page d'accueil = Login/Inscription)
 * RÔLE    : Formulaire d'authentification avec slider animé
 * ============================================================
 *
 * 🎓 LEÇON : Le flux d'authentification
 *
 *   1. Afficher le formulaire (GET ou première visite)
 *   2. Utilisateur soumet (POST)
 *   3. PHP traite : valide → session → dashboard
 *                  invalide → réaffiche avec erreur
 */

// Démarrer la config (sessions, connexion BD, etc.)
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/models.php';

// Si déjà connecté, rediriger directement vers le dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'pages/dashboard.php');
    exit;
}

// ============================================================
// VARIABLES D'ÉTAT — Gérer les messages à afficher
// ============================================================
$erreurLogin      = '';
$erreurInscription = '';
$succesInscription = '';
$panneauActif     = 'login'; // Quel panneau afficher par défaut

// ============================================================
// TRAITEMENT DU FORMULAIRE (si méthode POST)
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';

    // --------------------------------------------------------
    // CAS 1 : CONNEXION
    // --------------------------------------------------------
    if ($action === 'login') {
        $email      = trim($_POST['email']      ?? '');
        $motDePasse = trim($_POST['mot_de_passe'] ?? '');

        if (empty($email) || empty($motDePasse)) {
            $erreurLogin = 'Veuillez remplir tous les champs.';
            $panneauActif = 'login';

        } else {
            // Tenter la connexion via notre modèle
            $modeleUser = new ModeleUtilisateur();
            $user = $modeleUser->connecter($email, $motDePasse);

            if ($user) {
                // ✅ Connexion réussie !
                // Stocker l'utilisateur dans la session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user']    = $user;  // Tout le tableau user (sans mdp)

                // Rediriger vers le dashboard
                header('Location: ' . BASE_URL . 'pages/dashboard.php');
                exit;

            } else {
                // ❌ Email ou mot de passe incorrect
                $erreurLogin = 'Email ou mot de passe incorrect. Réessayez.';
                $panneauActif = 'login';
            }
        }

    // --------------------------------------------------------
    // CAS 2 : INSCRIPTION
    // --------------------------------------------------------
    } elseif ($action === 'inscription') {
        $panneauActif = 'inscription'; // Garder ce panneau visible

        $nom        = trim($_POST['nom']        ?? '');
        $prenom     = trim($_POST['prenom']      ?? '');
        $email      = trim($_POST['email']       ?? '');
        $motDePasse = trim($_POST['mot_de_passe'] ?? '');
        $confirmation = trim($_POST['confirmation'] ?? '');

        // Validation basique
        if (empty($nom) || empty($prenom) || empty($email) || empty($motDePasse)) {
            $erreurInscription = 'Tous les champs sont obligatoires.';

        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreurInscription = 'Adresse email invalide.';

        } elseif (strlen($motDePasse) < 6) {
            $erreurInscription = 'Le mot de passe doit contenir au moins 6 caractères.';

        } elseif ($motDePasse !== $confirmation) {
            $erreurInscription = 'Les mots de passe ne correspondent pas.';

        } else {
            $modeleUser = new ModeleUtilisateur();

            // Vérifier si l'email est déjà utilisé
            if ($modeleUser->emailExiste($email)) {
                $erreurInscription = 'Cet email est déjà utilisé. Connectez-vous !';

            } else {
                // ✅ Créer le compte
                $nouvelId = $modeleUser->creer([
                    'nom'          => strtoupper($nom),
                    'prenom'       => ucfirst(strtolower($prenom)),
                    'email'        => $email,
                    'mot_de_passe' => $motDePasse, // Sera hashé dans le modèle
                    'role'         => 'etudiant',
                ]);

                if ($nouvelId > 0) {
                    $succesInscription = "Compte créé avec succès ! Connectez-vous maintenant 🎉";
                    $panneauActif = 'login'; // Basculer vers login après inscription
                } else {
                    $erreurInscription = 'Erreur lors de la création du compte. Réessayez.';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — GestionSalles CI</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- CSS Principal -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/app.css">
</head>
<body>

<!-- =====================================================
     PAGE D'AUTHENTIFICATION
     ===================================================== -->
<div class="auth-page">

    <!-- ===================================================
         PANNEAU GAUCHE — Décoratif / Branding
         =================================================== -->
    <div class="auth-visual">
        <div class="auth-visual-content">
            <div class="auth-visual-icon">
                <i class="fas fa-school"></i>
            </div>
            <h2>GestionSalles CI</h2>
            <p>
                La solution moderne pour gérer les salles de classes,
                les niveaux et les affectations dans votre établissement.
            </p>

            <div class="auth-features">
                <div class="auth-feature">
                    <i class="fas fa-check-circle"></i>
                    <span>Suivi en temps réel des salles</span>
                </div>
                <div class="auth-feature">
                    <i class="fas fa-chart-bar"></i>
                    <span>Dashboard intuitif</span>
                </div>
                <div class="auth-feature">
                    <i class="fas fa-shield-alt"></i>
                    <span>Accès sécurisé par rôle</span>
                </div>
                <div class="auth-feature">
                    <i class="fas fa-flag"></i>
                    <span>Fait pour la Côte d'Ivoire</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================================================
         PANNEAU DROIT — Formulaires Login + Inscription
         =================================================== -->
    <div class="auth-forms">
        <div class="auth-slider">

            <!-- TABS de navigation -->
            <div class="auth-tabs">
                <button
                    class="auth-tab <?= $panneauActif === 'login' ? 'active' : '' ?>"
                    onclick="basculerVers('login')"
                    id="tab-login"
                >
                    <i class="fas fa-sign-in-alt"></i> Connexion
                </button>
                <button
                    class="auth-tab <?= $panneauActif === 'inscription' ? 'active' : '' ?>"
                    onclick="basculerVers('inscription')"
                    id="tab-inscription"
                >
                    <i class="fas fa-user-plus"></i> Inscription
                </button>
            </div>

            <!--
                SLIDER CONTAINER
                show-register = classe CSS pour glisser vers inscription
                On l'ajoute/retire avec JavaScript
            -->
            <div class="auth-panels <?= $panneauActif === 'inscription' ? 'show-register' : '' ?>"
                 id="authPanels">

                <!-- ==========================================
                     PANNEAU 1 : CONNEXION
                     ========================================== -->
                <div class="auth-panel">
                    <div class="auth-form">
                        <h2 class="form-title">Bon retour ! <i class="fas fa-hand-wave"></i></h2>
                        <p class="form-subtitle">Connecte-toi à ton espace de gestion</p>

                        <!-- Alerte : succès d'inscription -->
                        <?php if (!empty($succesInscription)) : ?>
                            <div class="alert-auth succes visible">
                                <i class="fas fa-check-circle"></i> <?= e($succesInscription) ?>
                            </div>
                        <?php endif; ?>

                        <!-- Alerte : erreur de connexion -->
                        <div class="alert-auth erreur <?= !empty($erreurLogin) ? 'visible' : '' ?>"
                             id="erreurLogin">
                            <i class="fas fa-exclamation-circle"></i> <?= e($erreurLogin) ?>
                        </div>

                        <!--
                            FORMULAIRE DE CONNEXION
                            method="post" → envoyer au serveur
                            action="" → traité par ce même fichier PHP
                        -->
                        <form method="post" action="">
                            <!-- Champ caché : indique à PHP quelle action traiter -->
                            <input type="hidden" name="action" value="login">

                            <div class="form-group">
                                <label for="login-email">Adresse email</label>
                                <input
                                    type="email"
                                    id="login-email"
                                    name="email"
                                    placeholder="ton@email.com"
                                    value="<?= isset($_POST['email']) && isset($_POST['action']) && $_POST['action'] === 'login' ? e($_POST['email']) : '' ?>"
                                    required
                                    autocomplete="email"
                                >
                            </div>

                            <div class="form-group">
                                <label for="login-mdp">Mot de passe</label>
                                <input
                                    type="password"
                                    id="login-mdp"
                                    name="mot_de_passe"
                                    placeholder="••••••••"
                                    required
                                    autocomplete="current-password"
                                >
                            </div>

                            <button type="submit" class="btn-auth">
                                Se connecter →
                            </button>
                        </form>

                        <!-- Compte de test -->
                        <p style="text-align:center;margin-top:1.2rem;font-size:0.82rem;color:var(--gris-400);">
                            Test : <code>admin@ecole.ci</code> / <code>password</code>
                        </p>
                    </div>
                </div><!-- /.auth-panel (login) -->

                <!-- ==========================================
                     PANNEAU 2 : INSCRIPTION
                     ========================================== -->
                <div class="auth-panel">
                    <div class="auth-form">
                        <h2 class="form-title">Créer un compte <i class="fas fa-rocket"></i></h2>
                        <p class="form-subtitle">Rejoins la plateforme GestionSalles</p>

                        <!-- Alerte : erreur d'inscription -->
                        <div class="alert-auth erreur <?= !empty($erreurInscription) ? 'visible' : '' ?>"
                             id="erreurInscription">
                            <i class="fas fa-exclamation-circle"></i> <?= e($erreurInscription) ?>
                        </div>

                        <form method="post" action="">
                            <input type="hidden" name="action" value="inscription">

                            <!-- Nom et Prénom côte à côte -->
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="reg-nom">Nom *</label>
                                    <input
                                        type="text"
                                        id="reg-nom"
                                        name="nom"
                                        placeholder="KOUASSI"
                                        value="<?= isset($_POST['action']) && $_POST['action'] === 'inscription' ? e($_POST['nom'] ?? '') : '' ?>"
                                        required
                                    >
                                </div>
                                <div class="form-group">
                                    <label for="reg-prenom">Prénom *</label>
                                    <input
                                        type="text"
                                        id="reg-prenom"
                                        name="prenom"
                                        placeholder="Jean"
                                        value="<?= isset($_POST['action']) && $_POST['action'] === 'inscription' ? e($_POST['prenom'] ?? '') : '' ?>"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="reg-email">Email *</label>
                                <input
                                    type="email"
                                    id="reg-email"
                                    name="email"
                                    placeholder="jean@email.com"
                                    required
                                >
                            </div>

                            <div class="form-group">
                                <label for="reg-mdp">Mot de passe *</label>
                                <input
                                    type="password"
                                    id="reg-mdp"
                                    name="mot_de_passe"
                                    placeholder="Minimum 6 caractères"
                                    required
                                    minlength="6"
                                >
                            </div>

                            <div class="form-group">
                                <label for="reg-confirm">Confirmer le mot de passe *</label>
                                <input
                                    type="password"
                                    id="reg-confirm"
                                    name="confirmation"
                                    placeholder="Répète ton mot de passe"
                                    required
                                >
                            </div>

                            <button type="submit" class="btn-auth">
                                Créer mon compte →
                            </button>
                        </form>
                    </div>
                </div><!-- /.auth-panel (inscription) -->

            </div><!-- /.auth-panels -->
        </div><!-- /.auth-slider -->
    </div><!-- /.auth-forms -->

</div><!-- /.auth-page -->

<!-- JavaScript -->
<script src="<?= BASE_URL ?>assets/js/app.js"></script>

</body>
</html>