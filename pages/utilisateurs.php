<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/models.php';
verifierConnexion();

$modeleUser = new ModeleUtilisateur();
$utilisateurs = $modeleUser->trouverTous();
$stats = $modeleUser->statistiques();

$pageTitre  = 'Utilisateurs';
$pageActive = 'utilisateurs';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="stats-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:2rem">
    <div class="stat-card">
        <div class="stat-icon icon-bleu">👥</div>
        <div class="stat-body">
            <div class="stat-value"><?= $stats['total'] ?? 0 ?></div>
            <div class="stat-label">Total utilisateurs</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-rouge">🛡️</div>
        <div class="stat-body">
            <div class="stat-value"><?= $stats['admins'] ?? 0 ?></div>
            <div class="stat-label">Administrateurs</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-vert">👨‍🏫</div>
        <div class="stat-body">
            <div class="stat-value"><?= $stats['enseignants'] ?? 0 ?></div>
            <div class="stat-label">Enseignants</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-orange">🎓</div>
        <div class="stat-body">
            <div class="stat-value"><?= $stats['etudiants'] ?? 0 ?></div>
            <div class="stat-label">Étudiants</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">👥 Liste des utilisateurs</h3>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Utilisateur</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Inscrit le</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($utilisateurs)) : ?>
                    <tr>
                        <td colspan="5" style="text-align:center;padding:2rem;color:var(--gris-400)">
                            Aucun utilisateur trouvé.
                        </td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($utilisateurs as $i => $user) :
                        $roleBadges = [
                            'admin'      => 'badge-rouge',
                            'enseignant' => 'badge-vert',
                            'etudiant'   => 'badge-bleu',
                        ];
                        $roleBadge = $roleBadges[$user['role']] ?? 'badge-gris';
                        // Est-ce l'utilisateur connecté ?
                        $estMoi = $user['id'] == ($_SESSION['user_id'] ?? 0);
                    ?>
                        <tr <?= $estMoi ? 'style="background:rgba(0,165,80,0.04)"' : '' ?>>
                            <td style="color:var(--gris-400)"><?= $i + 1 ?></td>
                            <td>
                                <div style="display:flex;align-items:center;gap:0.8rem">
                                    <!-- Avatar initiales -->
                                    <div style="width:36px;height:36px;border-radius:50%;background:var(--vert);
                                                color:white;display:flex;align-items:center;justify-content:center;
                                                font-weight:700;font-size:0.9rem;flex-shrink:0">
                                        <?= strtoupper(substr($user['prenom'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div style="font-weight:600">
                                            <?= e($user['prenom'] . ' ' . $user['nom']) ?>
                                        </div>
                                        <?php if ($estMoi) : ?>
                                            <div style="font-size:0.75rem;color:var(--vert)">← Vous</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td style="color:var(--gris-600)"><?= e($user['email']) ?></td>
                            <td>
                                <span class="badge <?= $roleBadge ?>">
                                    <?= ucfirst(e($user['role'])) ?>
                                </span>
                            </td>
                            <td style="font-size:0.82rem;color:var(--gris-400)">
                                <?= dateFormat($user['created_at']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>