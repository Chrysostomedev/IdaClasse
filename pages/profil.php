<?php
require_once __DIR__ . '/../includes/config.php';
verifierConnexion();

$user = utilisateurConnecte();
$pageTitre  = 'Mon Profil';
$pageActive = 'profil';
require_once __DIR__ . '/../includes/header.php';
?>

<div style="max-width:600px">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">⚙️ Mon profil</h3>
        </div>
        <div class="card-body">

            <!-- Avatar grand -->
            <div style="display:flex;align-items:center;gap:1.5rem;margin-bottom:2rem">
                <div style="width:80px;height:80px;border-radius:50%;background:var(--vert);
                            display:flex;align-items:center;justify-content:center;
                            color:white;font-size:2rem;font-weight:800;">
                    <?= strtoupper(substr($user['prenom'] ?? 'U', 0, 1)) ?>
                </div>
                <div>
                    <h2 style="font-size:1.4rem;font-weight:700">
                        <?= e(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? '')) ?>
                    </h2>
                    <span class="badge badge-<?= $user['role'] === 'admin' ? 'rouge' : ($user['role'] === 'enseignant' ? 'vert' : 'bleu') ?>">
                        <?= ucfirst(e($user['role'] ?? '')) ?>
                    </span>
                </div>
            </div>

            <!-- Détails du profil -->
            <div style="display:flex;flex-direction:column;gap:1.2rem">

                <div style="display:grid;grid-template-columns:140px 1fr;gap:1rem;padding:1rem;
                            background:var(--gris-100);border-radius:10px">
                    <span style="color:var(--gris-400);font-size:0.85rem;font-weight:600">ID</span>
                    <span>#<?= $user['id'] ?? '—' ?></span>
                </div>

                <div style="display:grid;grid-template-columns:140px 1fr;gap:1rem;padding:1rem;
                            background:var(--gris-100);border-radius:10px">
                    <span style="color:var(--gris-400);font-size:0.85rem;font-weight:600">Nom complet</span>
                    <span><?= e(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? '')) ?></span>
                </div>

                <div style="display:grid;grid-template-columns:140px 1fr;gap:1rem;padding:1rem;
                            background:var(--gris-100);border-radius:10px">
                    <span style="color:var(--gris-400);font-size:0.85rem;font-weight:600">Email</span>
                    <span><?= e($user['email'] ?? '—') ?></span>
                </div>

                <div style="display:grid;grid-template-columns:140px 1fr;gap:1rem;padding:1rem;
                            background:var(--gris-100);border-radius:10px">
                    <span style="color:var(--gris-400);font-size:0.85rem;font-weight:600">Rôle</span>
                    <span><?= ucfirst(e($user['role'] ?? '—')) ?></span>
                </div>

            </div>

            <!-- Note POO -->
            <div style="margin-top:2rem;padding:1.2rem;background:var(--vert-bg);border-radius:10px;
                        border-left:3px solid var(--vert)">
                <p style="font-size:0.85rem;color:var(--vert-dark);font-weight:600;margin-bottom:0.3rem">
                    💡 Note pédagogique
                </p>
                <p style="font-size:0.83rem;color:var(--vert-dark)">
                    Ces données viennent de <code>$_SESSION['user']</code> — un tableau PHP stocké
                    côté serveur, créé lors de votre connexion. Pas de requête BD sur cette page !
                </p>
            </div>

            <!-- Bouton déconnexion -->
            <div style="margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid var(--gris-200)">
                <a href="<?= BASE_URL ?>logout.php"
                   style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.7rem 1.4rem;
                          background:rgba(230,57,70,0.1);color:var(--rouge);border-radius:10px;
                          text-decoration:none;font-weight:600;font-size:0.9rem;">
                    🚪 Se déconnecter
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>