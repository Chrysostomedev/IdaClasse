<?php
/**
 * ============================================================
 * FICHIER : includes/Database.php
 * RÔLE    : Classe de connexion à MySQL (Singleton Pattern)
 * ============================================================
 *
 * 🎓 LEÇON POO #2 — L'HÉRITAGE (Inheritance)
 *
 * L'héritage, c'est comme en biologie :
 *   - Un ENFANT hérite des traits de ses PARENTS
 *   - Il peut avoir ses propres caractéristiques en plus
 *
 * Exemple concret :
 *   class Animal { public function respirer() {...} }
 *   class Chien extends Animal { public function aboyer() {...} }
 *
 *   $rex = new Chien();
 *   $rex->respirer(); // hérité de Animal ✓
 *   $rex->aboyer();   // propre à Chien ✓
 *
 * Dans notre app :
 *   Database → classe de base (connexion)
 *   ModeleSalle, ModeleUser → héritent de Database (ont la connexion)
 *   Chaque modèle ajoute ses propres méthodes (getSalles, getUser...)
 *
 * 🎓 LEÇON POO #3 — LE POLYMORPHISME (Endomorphisme en pratique)
 *
 * "Poly" = plusieurs, "morph" = forme
 * Même méthode, comportements différents selon la classe !
 *
 * class Forme { public function aire() { return 0; } }
 * class Cercle extends Forme { public function aire() { return π*r²; } }
 * class Carré extends Forme  { public function aire() { return c*c; } }
 *
 * $formes = [new Cercle(5), new Carré(4)];
 * foreach ($formes as $f) {
 *     echo $f->aire(); // Chaque forme calcule SA façon d'être !
 * }
 */

class Database
{
    // =========================================================
    // PATTERN SINGLETON — Une seule connexion pour toute l'app
    // =========================================================
    /**
     * 🎓 LEÇON : Le SINGLETON Pattern
     *
     * Problème : si on fait "new Database()" à chaque fichier PHP,
     * on crée 10, 20 connexions à MySQL → surcharge du serveur !
     *
     * Solution Singleton : UNE SEULE instance partagée.
     * Comme l'électricité : un seul compteur pour tout l'immeuble.
     *
     * 'static' = appartient à la CLASSE, pas à une instance
     */
    private static ?PDO $instance = null;

    // Identifiants de connexion
    private static string $host     = 'localhost';
    private static string $dbname   = 'salles';
    private static string $username = 'root';
    private static string $password = '';

    /**
     * Le constructeur est PRIVÉ → impossible de faire "new Database()"
     * depuis l'extérieur. On FORCE l'utilisation de getInstance()
     */
    private function __construct() {}

    /**
     * Méthode statique = on l'appelle sur la classe, pas sur une instance
     * Database::getInstance() → pas besoin de "new Database()" avant
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            // Première fois : on crée la connexion
            try {
                $dsn = sprintf(
                    'mysql:host=%s;dbname=%s;charset=utf8mb4',
                    self::$host,
                    self::$dbname
                );

                self::$instance = new PDO($dsn, self::$username, self::$password, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);

            } catch (PDOException $e) {
                // Stocker dans session pour afficher proprement
                $_SESSION['erreur_bd'] = true;
                die('
                <div style="font:16px sans-serif;max-width:600px;margin:50px auto;
                            padding:30px;background:#fee;border-radius:12px;color:#c00;">
                    <h2>⚠️ Base de données inaccessible</h2>
                    <p>Assurez-vous que :</p>
                    <ul>
                        <li>XAMPP ou WAMP est lancé</li>
                        <li>MySQL (MariaDB) est démarré</li>
                        <li>La base <strong>masterclass_php</strong> existe</li>
                    </ul>
                    <code style="font-size:13px;color:#666;">' . e($e->getMessage()) . '</code>
                </div>');
            }
        }

        // Les fois suivantes : retourner la même connexion déjà créée
        return self::$instance;
    }
}


/**
 * ============================================================
 * CLASSE ABSTRAITE : Modele
 * ============================================================
 *
 * 🎓 LEÇON : Classe ABSTRAITE
 *
 * Une classe abstraite est un "modèle de classe" qu'on ne peut
 * pas instancier directement. Elle définit une structure commune
 * que toutes les classes enfants DOIVENT respecter.
 *
 * C'est comme un moule : tu ne peux pas porter le moule,
 * mais tu peux créer des objets avec.
 *
 * abstract class Forme {
 *     abstract public function aire(): float; // OBLIGATOIRE dans enfants
 *     public function afficher() { echo $this->aire(); } // PARTAGÉE
 * }
 */
abstract class Modele
{
    // La connexion PDO, accessible par toutes les classes enfants
    protected PDO $pdo;

    /**
     * Le constructeur de la classe parente
     * Toutes les classes enfants héritent de cette connexion
     */
    public function __construct()
    {
        // On récupère l'instance unique de la connexion
        $this->pdo = Database::getInstance();
    }

    /**
     * Méthode abstraite : chaque modèle enfant DOIT l'implémenter
     * C'est du POLYMORPHISME : même nom, comportement différent
     */
    abstract public function trouverTous(): array;

    /**
     * Méthode partagée par tous les modèles
     * Compter les lignes d'une table
     */
    public function compter(string $table): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM {$table}");
        $result = $stmt->fetch();
        return (int) $result['total'];
    }
}