<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/models.php';
verifierConnexion();

$modeleClasse = new ModeleClasse();
$classes = $modeleClasse->trouverTous();
$stats   = $modeleClasse->statistiques();

$pageTitre  = 'Classes';
$pageActive = 'classes';
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Stats rapides classes -->
<div class="stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:2rem">
    <div class="stat-card">
        <div class="stat-icon icon-primary">
            <i class="fas fa-book-open"></i>
        </div>
        <div class="stat-body">
            <div class="stat-value"><?= $stats['total'] ?? 0 ?></div>
            <div class="stat-label">Classes au total</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-success">
            <i class="fas fa-user-graduate"></i>
        </div>
        <div class="stat-body">
            <div class="stat-value"><?= number_format($stats['effectif_total'] ?? 0) ?></div>
            <div class="stat-label">Élèves inscrits</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-warning">
            <i class="fas fa-chart-bar"></i>
        </div>
        <div class="stat-body">
            <div class="stat-value"><?= round($stats['effectif_moyen'] ?? 0) ?></div>
            <div class="stat-label">Moyenne par classe</div>
        </div>
    </div>
</div>

<!-- Tableau des classes -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-book-open"></i>
            Liste des classes
        </h3>
        <span style="font-size:0.85rem;color:var(--text-secondary)">
            <i class="fas fa-list"></i>
            <?= count($classes) ?> classes trouvées
        </span>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Niveau</th>
                    <th>Filière</th>
                    <th>Effectif</th>
                    <th>Salle</th>
                    <th>Date création</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($classes)) : ?>
                    <tr>
                        <td colspan="7" style="text-align:center;padding:3rem;color:var(--text-secondary)">
                            <i class="fas fa-inbox" style="font-size:3rem;margin-bottom:1rem;display:block;opacity:0.3"></i>
                            Aucune classe trouvée. Insérez des données via phpMyAdmin.
                        </td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($classes as $i => $classe) : ?>
                        <tr>
                            <td style="color:var(--text-secondary)"><?= $i + 1 ?></td>
                            <td><strong><?= e($classe['nom']) ?></strong></td>
                            <td><?= e($classe['niveau'] ?? '—') ?></td>
                            <td><?= e($classe['filiere'] ?? '—') ?></td>
                            <td>
                                <span class="badge badge-info">
                                    <i class="fas fa-users"></i>
                                    <?= $classe['effectif'] ?? 0 ?> élèves
                                </span>
                            </td>
                            <td>
                                <?php if ($classe['salle_nom']) : ?>
                                    <span class="badge <?= $classe['salle_statut'] === 'disponible' ? 'badge-success' : 'badge-warning' ?>">
                                        <i class="fas fa-door-open"></i>
                                        <?= e($classe['salle_nom']) ?>
                                    </span>
                                <?php else : ?>
                                    <span style="color:var(--text-secondary)">
                                        <i class="fas fa-minus-circle"></i>
                                        Non assignée
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td style="font-size:0.85rem;color:var(--text-secondary)">
                                <i class="far fa-calendar"></i>
                                <?= dateFormat($classe['created_at']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>