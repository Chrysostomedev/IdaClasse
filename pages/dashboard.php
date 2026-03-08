<?php
/**
 * ============================================================
 * FICHIER : pages/dashboard.php
 * RÔLE    : Tableau de bord principal de l'application
 * ============================================================
 *
 * 💡 POUR LES DÉBUTANTS PHP :
 * 
 * Ce fichier est comme la "page d'accueil" après connexion.
 * Il affiche un résumé de toutes les données importantes.
 * 
 * STRUCTURE D'UNE PAGE PHP :
 * 1. Code PHP en haut (logique, récupération de données)
 * 2. HTML au milieu (affichage)
 * 3. Code PHP dans le HTML (pour afficher les données dynamiques)
 * 
 * ANALOGIE : C'est comme une recette de cuisine
 * - En haut : préparer les ingrédients (données)
 * - Au milieu : assembler le plat (HTML)
 * - Dans le HTML : ajouter les ingrédients au bon moment (<?= ?>)
 */

// ============================================================
// ÉTAPE 1 : INCLURE LES FICHIERS NÉCESSAIRES
// ============================================================
/**
 * 💡 require_once = "importer" un autre fichier PHP
 * 
 * ANALOGIE : C'est comme copier-coller le contenu d'un fichier
 * dans celui-ci. On évite de répéter le même code partout !
 * 
 * __DIR__ = le dossier actuel (pages/)
 * ../ = remonter d'un niveau (vers la racine)
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/models.php';

// ============================================================
// ÉTAPE 2 : VÉRIFIER QUE L'UTILISATEUR EST CONNECTÉ
// ============================================================
/**
 * 💡 verifierConnexion() = fonction qui vérifie la session
 * 
 * ANALOGIE : C'est comme un videur de boîte de nuit
 * Si tu n'as pas de bracelet (session), tu ne rentres pas !
 * 
 * Si non connecté → redirection vers index.php (page de login)
 */
verifierConnexion();

// ============================================================
// ÉTAPE 3 : CRÉER LES "MODÈLES" (accès aux données)
// ============================================================
/**
 * 💡 Les MODÈLES sont des classes qui parlent à la base de données
 * 
 * ANALOGIE : C'est comme des "assistants" spécialisés
 * - ModeleSalle = assistant qui connaît tout sur les salles
 * - ModeleUtilisateur = assistant qui connaît tout sur les users
 * - ModeleClasse = assistant qui connaît tout sur les classes
 * 
 * new = créer une nouvelle instance (un nouvel objet)
 */
$modeleSalle = new ModeleSalle();
$modeleUser  = new ModeleUtilisateur();
$modeleClasse = new ModeleClasse();

// ============================================================
// ÉTAPE 4 : RÉCUPÉRER LES DONNÉES DE LA BASE DE DONNÉES
// ============================================================
/**
 * 💡 On appelle des méthodes sur nos modèles pour récupérer les données
 * 
 * ANALOGIE : C'est comme poser des questions à nos assistants
 * - "Donne-moi les statistiques des salles"
 * - "Donne-moi les 5 dernières salles ajoutées"
 * 
 * Le résultat est stocké dans des variables ($statsSalles, etc.)
 */

// Récupérer les statistiques (nombres, moyennes, etc.)
$statsSalles  = $modeleSalle->statistiques();
$statsUsers   = $modeleUser->statistiques();
$statsClasses = $modeleClasse->statistiques();

// Récupérer les 5 dernières salles ajoutées
$dernieresSalles = $modeleSalle->dernieresSalles(5);

// Récupérer toutes les classes
$toutesClasses = $modeleClasse->trouverTous();

// ============================================================
// ÉTAPE 5 : PRÉPARER LES VARIABLES POUR LE HEADER
// ============================================================
/**
 * 💡 Ces variables seront utilisées par header.php
 * 
 * $pageTitre = titre affiché dans l'onglet du navigateur
 * $pageActive = pour savoir quel lien mettre en surbrillance
 */
$pageTitre  = 'Dashboard';
$pageActive = 'dashboard';

// ============================================================
// ÉTAPE 6 : INCLURE LE HEADER (HTML + sidebar + topbar)
// ============================================================
/**
 * 💡 header.php contient tout le début du HTML
 * 
 * ANALOGIE : C'est comme le "cadre" de la page
 * On l'inclut une fois, utilisé partout !
 */
require_once __DIR__ . '/../includes/header.php';
?>

<!-- =====================================================
     À PARTIR D'ICI : C'EST DU HTML !
   
<!-- ===================================================
     SECTION 1 : CARTES STATISTIQUES
     
     💡 Ces cartes affichent les chiffres clés en un coup d'œil
     ===================================================== -->
<div class="stats-grid">

    <!-- CARTE 1 : Total des salles -->
    <div class="stat-card">
        <div class="stat-icon icon-success">
            <i class="fas fa-door-open"></i>
        </div>
        <div class="stat-body">
            <?php
            /**
             * 💡 <?= ?> = raccourci pour afficher une variable
             * C'est équivalent à <?php echo ?>
             * 
             * ANALOGIE : C'est comme un "trou" dans le HTML
             * où on insère une valeur dynamique
             * 
             * ?? 0 = si la valeur n'existe pas, afficher 0
             */
            ?>
            <div class="stat-value"><?= $statsSalles['total'] ?? 0 ?></div>
            <div class="stat-label">Salles au total</div>
            <div class="stat-change change-up">
                <i class="fas fa-check-circle"></i>
                <?= $statsSalles['disponibles'] ?? 0 ?> disponibles
            </div>
        </div>
    </div>

    <!-- CARTE 2 : Total des classes -->
    <div class="stat-card">
        <div class="stat-icon icon-primary">
            <i class="fas fa-book-open"></i>
        </div>
        <div class="stat-body">
            <div class="stat-value"><?= $statsClasses['total'] ?? 0 ?></div>
            <div class="stat-label">Classes</div>
            <div class="stat-change change-up">
                <i class="fas fa-users"></i>
                <?= $statsClasses['effectif_total'] ?? 0 ?> élèves
            </div>
        </div>
    </div>

    <!-- CARTE 3 : Total des utilisateurs -->
    <div class="stat-card">
        <div class="stat-icon icon-warning">
            <i class="fas fa-user-friends"></i>
        </div>
        <div class="stat-body">
            <div class="stat-value"><?= $statsUsers['total'] ?? 0 ?></div>
            <div class="stat-label">Utilisateurs</div>
            <div class="stat-change">
                <i class="fas fa-graduation-cap"></i>
                <?= $statsUsers['etudiants'] ?? 0 ?> étudiants
            </div>
        </div>
    </div>

    <!-- CARTE 4 : Capacité totale -->
    <div class="stat-card">
        <div class="stat-icon icon-info">
            <i class="fas fa-chair"></i>
        </div>
        <div class="stat-body">
            <div class="stat-value">
                <?php
                /**
                 * 💡 number_format() = formater un nombre avec des espaces
                 * Exemple : 1000 devient "1 000"
                 */
                echo number_format($statsSalles['capacite_totale'] ?? 0, 0, ',', ' ');
                ?>
            </div>
            <div class="stat-label">Places disponibles</div>
            <div class="stat-change">
                <i class="fas fa-calculator"></i>
                Ø <?= round($statsSalles['capacite_moyenne'] ?? 0) ?> par salle
            </div>
        </div>
    </div>

</div><!-- /.stats-grid -->


<!-- ===================================================
     SECTION 2 : GRILLE DE CARTES (2 colonnes)
     ===================================================== -->
<div class="cards-grid">

    <!-- ===============================================
         CARTE : Dernières salles ajoutées
         =============================================== -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-door-open"></i>
                Dernières salles
            </h3>
            <a href="<?= BASE_URL ?>pages/salles.php"
               style="font-size:0.9rem;color:var(--primary);text-decoration:none;font-weight:600;">
                Voir tout <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Salle</th>
                        <th>Capacité</th>
                        <th>Bâtiment</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    /**
                     * 💡 foreach = BOUCLE pour parcourir un tableau
                     * 
                     * ANALOGIE : C'est comme lire une liste d'invités
                     * un par un et faire quelque chose pour chacun
                     * 
                     * $dernieresSalles = le tableau (la liste)
                     * as $salle = chaque élément (chaque invité)
                     * 
                     * À chaque tour de boucle, $salle contient
                     * les données d'une salle différente
                     */
                    foreach ($dernieresSalles as $salle) :
                        /**
                         * 💡 Déterminer la couleur du badge selon le statut
                         * 
                         * On crée un tableau associatif (clé => valeur)
                         * pour mapper chaque statut à une classe CSS
                         */
                        $badgeClasses = [
                            'disponible'  => 'badge-success',
                            'occupee'     => 'badge-danger',
                            'maintenance' => 'badge-warning',
                        ];
                        
                        /**
                         * 💡 Récupérer la classe CSS correspondante
                         * 
                         * $salle['statut'] = la clé (ex: 'disponible')
                         * $badgeClasses[...] = la valeur (ex: 'badge-success')
                         * ?? 'badge-gray' = valeur par défaut si statut inconnu
                         */
                        $badgeClass = $badgeClasses[$salle['statut']] ?? 'badge-gray';
                    ?>
                        <tr>
                            <td>
                                <strong><?= e($salle['nom']) ?></strong>
                            </td>
                            <td>
                                <i class="fas fa-users" style="color:var(--text-secondary);margin-right:0.5rem"></i>
                                <?= $salle['capacite'] ?> places
                            </td>
                            <td><?= e($salle['batiment'] ?? '—') ?></td>
                            <td>
                                <span class="badge <?= $badgeClass ?>">
                                    <?= e($salle['statut']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php
                    /**
                     * 💡 endforeach = fin de la boucle
                     * 
                     * On peut aussi écrire : } au lieu de endforeach;
                     * Mais endforeach est plus lisible dans le HTML
                     */
                    endforeach;
                    ?>
                </tbody>
            </table>
        </div>
    </div><!-- /.card salles -->


    <!-- ===============================================
         CARTE : État des salles (graphique)
         =============================================== -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-chart-pie"></i>
                État des salles
            </h3>
        </div>
        <div class="card-body">

            <?php
            /**
             * 💡 Calculer les pourcentages pour la barre de progression
             * 
             * ANALOGIE : C'est comme calculer des parts de gâteau
             * Si on a 10 salles et 3 sont disponibles = 30%
             * 
             * max(1, ...) = éviter la division par zéro
             * Si total = 0, on utilise 1 pour ne pas crasher
             */
            $total = max(1, $statsSalles['total']);

            /**
             * 💡 round() = arrondir un nombre
             * Exemple : 33.333 devient 33
             */
            $pctDispo  = round(($statsSalles['disponibles']  ?? 0) / $total * 100);
            $pctOccup  = round(($statsSalles['occupees']     ?? 0) / $total * 100);
            $pctMaint  = round(($statsSalles['maintenance']  ?? 0) / $total * 100);
            ?>

            <!-- Barre de progression composite -->
            <div style="height:20px;background:var(--bg-secondary);border-radius:10px;overflow:hidden;display:flex;margin-bottom:2rem">
                <!-- 
                    💡 Chaque div représente une portion de la barre
                    width:<?= $pctDispo ?>% = largeur dynamique en %
                -->
                <div style="width:<?= $pctDispo ?>%;background:var(--success);transition:width 1s ease"></div>
                <div style="width:<?= $pctOccup ?>%;background:var(--danger);transition:width 1s ease"></div>
                <div style="width:<?= $pctMaint ?>%;background:var(--warning);transition:width 1s ease"></div>
            </div>

            <!-- Légende -->
            <div style="display:flex;flex-direction:column;gap:1rem">

                <div style="display:flex;align-items:center;justify-content:space-between">
                    <div style="display:flex;align-items:center;gap:0.75rem">
                        <div style="width:14px;height:14px;border-radius:50%;background:var(--success)"></div>
                        <span style="font-size:0.95rem">Disponibles</span>
                    </div>
                    <div>
                        <strong><?= $statsSalles['disponibles'] ?? 0 ?></strong>
                        <span style="color:var(--text-secondary);font-size:0.85rem"> (<?= $pctDispo ?>%)</span>
                    </div>
                </div>

                <div style="display:flex;align-items:center;justify-content:space-between">
                    <div style="display:flex;align-items:center;gap:0.75rem">
                        <div style="width:14px;height:14px;border-radius:50%;background:var(--danger)"></div>
                        <span style="font-size:0.95rem">Occupées</span>
                    </div>
                    <div>
                        <strong><?= $statsSalles['occupees'] ?? 0 ?></strong>
                        <span style="color:var(--text-secondary);font-size:0.85rem"> (<?= $pctOccup ?>%)</span>
                    </div>
                </div>

                <div style="display:flex;align-items:center;justify-content:space-between">
                    <div style="display:flex;align-items:center;gap:0.75rem">
                        <div style="width:14px;height:14px;border-radius:50%;background:var(--warning)"></div>
                        <span style="font-size:0.95rem">En maintenance</span>
                    </div>
                    <div>
                        <strong><?= $statsSalles['maintenance'] ?? 0 ?></strong>
                        <span style="color:var(--text-secondary);font-size:0.85rem"> (<?= $pctMaint ?>%)</span>
                    </div>
                </div>

            </div>

            <!-- Message de bienvenue personnalisé -->
            <?php
            /**
             * 💡 Récupérer l'utilisateur connecté depuis la session
             * 
             * utilisateurConnecte() = fonction définie dans config.php
             * Elle retourne les infos de l'utilisateur ou null
             */
            $userActuel = utilisateurConnecte();
            ?>
            <div style="margin-top:2rem;padding:1.25rem;background:rgba(99,102,241,0.1);border-radius:var(--radius);
                        border-left:4px solid var(--primary);">
                <p style="font-size:0.95rem;color:var(--text-primary);display:flex;align-items:center;gap:0.5rem">
                    <i class="fas fa-hand-wave" style="color:var(--primary)"></i>
                    Bonjour <strong><?= e($userActuel['prenom'] ?? 'Utilisateur') ?></strong> !
                    Vous êtes connecté en tant que <strong><?= e($userActuel['role'] ?? '') ?></strong>.
                </p>
                <p style="font-size:0.85rem;color:var(--text-secondary);margin-top:0.5rem">
                    <i class="far fa-clock"></i>
                    Dernière connexion : <?= date('d/m/Y à H:i') ?>
                </p>
            </div>
        </div>
    </div><!-- /.card état salles -->

</div><!-- /.cards-grid -->


<!-- ===================================================
     SECTION 3 : TABLEAU DES CLASSES
     ===================================================== -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-book-open"></i>
            Classes de l'établissement
        </h3>
        <a href="<?= BASE_URL ?>pages/classes.php"
           style="font-size:0.9rem;color:var(--primary);text-decoration:none;font-weight:600;">
            Voir tout <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Classe</th>
                    <th>Niveau</th>
                    <th>Filière</th>
                    <th>Effectif</th>
                    <th>Salle assignée</th>
                </tr>
            </thead>
            <tbody>
                <?php
                /**
                 * 💡 array_slice() = découper un tableau
                 * 
                 * ANALOGIE : C'est comme prendre seulement
                 * les 6 premières parts d'un gâteau
                 * 
                 * Paramètres :
                 * - $toutesClasses = le tableau complet
                 * - 0 = commencer à l'index 0 (le début)
                 * - 6 = prendre 6 éléments
                 */
                $classesAffichees = array_slice($toutesClasses, 0, 6);

                /**
                 * 💡 empty() = vérifier si un tableau est vide
                 * 
                 * ANALOGIE : C'est comme vérifier si un panier
                 * est vide avant d'essayer d'en sortir quelque chose
                 */
                if (empty($classesAffichees)) : ?>
                    <tr>
                        <td colspan="6" style="text-align:center;padding:3rem;color:var(--text-secondary)">
                            <i class="fas fa-inbox" style="font-size:3rem;margin-bottom:1rem;display:block;opacity:0.3"></i>
                            Aucune classe trouvée. Ajoutez des données via phpMyAdmin.
                        </td>
                    </tr>
                <?php else :
                    /**
                     * 💡 foreach avec $index
                     * 
                     * On peut récupérer l'index (position) en plus de la valeur
                     * $index commence à 0, donc on ajoute 1 pour afficher 1, 2, 3...
                     */
                    foreach ($classesAffichees as $index => $classe) : ?>
                        <tr>
                            <td style="color:var(--text-secondary)"><?= $index + 1 ?></td>
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
                                <?php
                                /**
                                 * 💡 if/else = condition
                                 * 
                                 * ANALOGIE : C'est comme un aiguillage de train
                                 * Si la salle existe, on affiche son nom
                                 * Sinon, on affiche "Non assignée"
                                 */
                                if ($classe['salle_nom']) : ?>
                                    <span class="badge <?= $classe['salle_statut'] === 'disponible' ? 'badge-success' : 'badge-warning' ?>">
                                        <i class="fas fa-door-open"></i>
                                        <?= e($classe['salle_nom']) ?>
                                    </span>
                                <?php else : ?>
                                    <span style="color:var(--text-secondary);font-size:0.9rem">
                                        <i class="fas fa-minus-circle"></i>
                                        Non assignée
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach;
                endif; ?>
            </tbody>
        </table>
    </div>
</div><!-- /.card classes -->

<?php
/**
 * ============================================================
 * ÉTAPE 7 : INCLURE LE FOOTER
 * ============================================================
 * 
 * 💡 footer.php ferme toutes les balises HTML ouvertes
 * et inclut les scripts JavaScript
 */
require_once __DIR__ . '/../includes/footer.php';
?>
