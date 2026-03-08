<?php
/**
 * ============================================================
 * FICHIER : pages/salles.php
 * RÔLE    : Liste de toutes les salles avec filtre par statut
 * ============================================================
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/models.php';
verifierConnexion();

$modeleSalle = new ModeleSalle();

// Lire le filtre depuis l'URL : salles.php?statut=disponible
// $_GET = données passées dans l'URL (?cle=valeur)
$filtreStatut = $_GET['statut'] ?? 'tous';

// Récupérer les salles selon le filtre
if ($filtreStatut !== 'tous' && in_array($filtreStatut, ['disponible', 'occupee', 'maintenance'])) {
    $salles = $modeleSalle->trouverParStatut($filtreStatut);
} else {
    $salles = $modeleSalle->trouverTous();
    $filtreStatut = 'tous';
}

$stats = $modeleSalle->statistiques();

$pageTitre  = 'Gestion des Salles';
$pageActive = 'salles';
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Filtres rapides -->
<div style="display:flex;gap:0.6rem;margin-bottom:2rem;flex-wrap:wrap">
    <?php
    $filtres = [
        'tous'        => ['label' => '🏢 Toutes (' . ($stats['total'] ?? 0) . ')',    'class' => 'badge-gris'],
        'disponible'  => ['label' => '✅ Disponibles (' . ($stats['disponibles'] ?? 0) . ')', 'class' => 'badge-vert'],
        'occupee'     => ['label' => '🔴 Occupées (' . ($stats['occupees'] ?? 0) . ')',   'class' => 'badge-rouge'],
        'maintenance' => ['label' => '🔧 Maintenance (' . ($stats['maintenance'] ?? 0) . ')', 'class' => 'badge-orange'],
    ];
    foreach ($filtres as $valeur => $info) :
        $isActif = $filtreStatut === $valeur;
    ?>
        <a href="?statut=<?= $valeur ?>"
           style="text-decoration:none;padding:0.5rem 1rem;border-radius:50px;font-size:0.88rem;font-weight:600;
                  <?= $isActif ? 'background:var(--noir);color:white;' : 'background:white;color:var(--gris-600);border:1.5px solid var(--gris-200);' ?>">
            <?= $info['label'] ?>
        </a>
    <?php endforeach; ?>
</div>

<!-- Grille des salles -->
<div class="salles-grid">
    <?php if (empty($salles)) : ?>
        <div style="grid-column:1/-1;text-align:center;padding:3rem;color:var(--gris-400)">
            <div style="font-size:3rem;margin-bottom:1rem">🚪</div>
            <p>Aucune salle trouvée pour ce filtre.</p>
        </div>
    <?php else : ?>
        <?php foreach ($salles as $salle) :
            $badges = [
                'disponible'  => ['class' => 'badge-vert',   'label' => '✅ Disponible'],
                'occupee'     => ['class' => 'badge-rouge',  'label' => '🔴 Occupée'],
                'maintenance' => ['class' => 'badge-orange', 'label' => '🔧 Maintenance'],
            ];
            $badge = $badges[$salle['statut']] ?? ['class' => 'badge-gris', 'label' => $salle['statut']];
        ?>
            <div class="salle-card">
                <div class="salle-card-header">
                    <span class="salle-nom"><?= e($salle['nom']) ?></span>
                    <span class="badge <?= $badge['class'] ?>"><?= $badge['label'] ?></span>
                </div>

                <div class="salle-details">
                    <div class="salle-detail">
                        <span>🏢</span>
                        <span><?= e($salle['batiment'] ?? 'Non défini') ?></span>
                    </div>
                    <div class="salle-detail">
                        <span>👥</span>
                        <span><?= $salle['capacite'] ?> places</span>
                    </div>
                    <?php if ($salle['equipements']) : ?>
                        <div class="salle-detail">
                            <span>🖥️</span>
                            <span style="font-size:0.82rem"><?= e($salle['equipements']) ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="salle-detail" style="margin-top:0.3rem;color:var(--gris-400);font-size:0.78rem">
                        <span>📅</span>
                        <span>Ajoutée le <?= dateFormat($salle['created_at']) ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>