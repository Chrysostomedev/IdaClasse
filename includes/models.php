<?php
/**
 * ============================================================
 * FICHIER : includes/models.php
 * RÔLE    : Modèles de données (Salle, Utilisateur, Classe)
 * ============================================================
 *
 * 🎓 LEÇON POO — L'HÉRITAGE EN ACTION
 *
 * Tous nos modèles héritent de la classe Modele (dans Database.php)
 * Donc tous ont accès à $this->pdo sans se reconnecter !
 *
 * Architecture :
 *   Modele (classe abstraite)
 *      ├── ModeleSalle      → gère la table "salles"
 *      ├── ModeleUtilisateur → gère la table "utilisateurs"
 *      └── ModeleClasse     → gère la table "classes"
 */

// On inclut le fichier qui contient la classe Modele
require_once __DIR__ . '/Database.php';

// ============================================================
// MODÈLE : Salle
// ============================================================
/**
 * 'extends Modele' = ModeleSalle hérite de Modele
 * → Elle hérite de $this->pdo et de la méthode compter()
 * → Elle DOIT implémenter trouverTous() (méthode abstraite)
 */
class ModeleSalle extends Modele
{
    // Nom de la table en base de données
    private string $table = 'salles';

    /**
     * Récupère TOUTES les salles
     * Implémente la méthode abstraite de la classe parent
     *
     * @return array → tableau de toutes les salles
     */
    public function trouverTous(): array
    {
        // ORDER BY = trier par nom (A → Z)
        $stmt = $this->pdo->query("SELECT * FROM {$this->table} ORDER BY nom ASC");
        return $stmt->fetchAll(); // fetchAll() = tous les résultats
    }

    /**
     * Récupère les salles filtrées par statut
     *
     * @param string $statut → 'disponible', 'occupee', 'maintenance'
     */
    public function trouverParStatut(string $statut): array
    {
        // Requête préparée avec paramètre :statut
        $stmt = $this->pdo->prepare(
            "SELECT * FROM {$this->table} WHERE statut = :statut ORDER BY nom"
        );
        $stmt->execute([':statut' => $statut]);
        return $stmt->fetchAll();
    }

    /**
     * Récupère les statistiques des salles pour le dashboard
     *
     * @return array → ['total' => 10, 'disponibles' => 7, 'occupees' => 2, ...]
     */
    public function statistiques(): array
    {
        $sql = "SELECT
                    COUNT(*) as total,
                    SUM(CASE WHEN statut = 'disponible' THEN 1 ELSE 0 END) as disponibles,
                    SUM(CASE WHEN statut = 'occupee'    THEN 1 ELSE 0 END) as occupees,
                    SUM(CASE WHEN statut = 'maintenance' THEN 1 ELSE 0 END) as maintenance,
                    SUM(capacite) as capacite_totale,
                    AVG(capacite) as capacite_moyenne
                FROM {$this->table}";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetch(); // fetch() = UN seul résultat
    }

    /**
     * Récupère les 5 dernières salles ajoutées
     */
    public function dernieresSalles(int $limite = 5): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :limite"
        );
        // bindParam = lier un paramètre avec son TYPE (important pour LIMIT !)
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

// ============================================================
// MODÈLE : Utilisateur
// ============================================================
class ModeleUtilisateur extends Modele
{
    private string $table = 'utilisateurs';

    /**
     * Récupère tous les utilisateurs (sans les mots de passe !)
     *
     * 🎓 BONNE PRATIQUE : Ne JAMAIS sélectionner mot_de_passe
     *    sauf quand c'est absolument nécessaire (connexion)
     */
    public function trouverTous(): array
    {
        $stmt = $this->pdo->query(
            "SELECT id, nom, prenom, email, role, created_at
             FROM {$this->table}
             ORDER BY nom, prenom"
        );
        return $stmt->fetchAll();
    }

    /**
     * Trouver un utilisateur par son email (pour la connexion)
     *
     * @param string $email
     * @return array|false → les données user, ou false si pas trouvé
     */
    public function trouverParEmail(string $email): array|false
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1"
        );
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(); // false si aucun résultat
    }

    /**
     * Vérifier si un email existe déjà (pour l'inscription)
     */
    public function emailExiste(string $email): bool
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) FROM {$this->table} WHERE email = :email"
        );
        $stmt->execute([':email' => $email]);
        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Créer un nouvel utilisateur
     *
     * 🎓 LEÇON : password_hash() — Ne JAMAIS stocker les mots de passe en clair !
     *
     *   password_hash('MonMotDePasse', PASSWORD_BCRYPT)
     *   → '$2y$10$abc123...' (une chaîne hashée, irréversible)
     *
     *   password_verify('MonMotDePasse', '$2y$10$abc123...')
     *   → true (PHP vérifie sans "déhasher")
     *
     * C'est comme un cadenas : tu peux fermer (hasher) mais pas ouvrir (retrouver le mot de passe)
     */
    public function creer(array $donnees): int
    {
        // Hasher le mot de passe AVANT de l'insérer
        $motDePasseHash = password_hash($donnees['mot_de_passe'], PASSWORD_BCRYPT);

        $sql = "INSERT INTO {$this->table} (nom, prenom, email, mot_de_passe, role)
                VALUES (:nom, :prenom, :email, :mot_de_passe, :role)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nom'           => $donnees['nom'],
            ':prenom'        => $donnees['prenom'],
            ':email'         => $donnees['email'],
            ':mot_de_passe'  => $motDePasseHash,
            ':role'          => $donnees['role'] ?? 'etudiant',
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Connecter un utilisateur
     * Vérifie email + mot de passe, retourne les données user ou false
     */
    public function connecter(string $email, string $motDePasse): array|false
    {
        // 1. Trouver l'utilisateur par email
        $user = $this->trouverParEmail($email);

        if (!$user) {
            return false; // Email inconnu
        }

        // 2. Vérifier le mot de passe
        // password_verify compare le mot de passe en clair avec le hash stocké
        if (!password_verify($motDePasse, $user['mot_de_passe'])) {
            return false; // Mauvais mot de passe
        }

        // 3. Retourner les données SANS le mot de passe
        unset($user['mot_de_passe']); // Supprimer le hash du tableau
        return $user;
    }

    /**
     * Statistiques pour le dashboard
     */
    public function statistiques(): array
    {
        $sql = "SELECT
                    COUNT(*) as total,
                    SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) as admins,
                    SUM(CASE WHEN role = 'enseignant' THEN 1 ELSE 0 END) as enseignants,
                    SUM(CASE WHEN role = 'etudiant' THEN 1 ELSE 0 END) as etudiants
                FROM {$this->table}";

        return $this->pdo->query($sql)->fetch();
    }
}

// ============================================================
// MODÈLE : Classe scolaire
// ============================================================
class ModeleClasse extends Modele
{
    private string $table = 'classes';

    /**
     * Toutes les classes avec le nom de leur salle (JOIN SQL)
     *
     * 🎓 LEÇON : Le JOIN SQL
     *
     * JOIN permet de combiner des données de plusieurs tables.
     * Ici : classes + salles → une seule requête !
     *
     * LEFT JOIN → garde toutes les classes, même sans salle assignée
     */
    public function trouverTous(): array
    {
        $sql = "SELECT
                    c.*,
                    s.nom AS salle_nom,
                    s.statut AS salle_statut
                FROM {$this->table} c
                LEFT JOIN salles s ON c.salle_id = s.id
                ORDER BY c.niveau, c.nom";

        return $this->pdo->query($sql)->fetchAll();
    }

    /**
     * Statistiques des classes
     */
    public function statistiques(): array
    {
        $sql = "SELECT
                    COUNT(*) as total,
                    SUM(effectif) as effectif_total,
                    AVG(effectif) as effectif_moyen,
                    MAX(effectif) as effectif_max
                FROM {$this->table}";

        return $this->pdo->query($sql)->fetch();
    }
}