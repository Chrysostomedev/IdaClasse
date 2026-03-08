<?php
/**
 * ============================================================
 * FICHIER : includes/config.php
 * RÔLE    : Configuration globale de l'application
 * ============================================================
 *
 * 💡 POUR LES DÉBUTANTS PHP :
 * 
 * Ce fichier est le "cerveau" de l'application. Il est inclus
 * par TOUS les autres fichiers PHP avec require_once.
 * 
 * ANALOGIE : C'est comme les "réglages généraux" d'un téléphone
 * que toutes les applications utilisent (langue, fuseau horaire, etc.)
 * 
 * Ce fichier contient :
 * - Les constantes (valeurs fixes)
 * - Le démarrage de la session
 * - Les fonctions utilitaires globales
 */

// ============================================================
// CONSTANTES DE L'APPLICATION
// ============================================================
/**
 * 💡 define() = créer une CONSTANTE
 * 
 * ANALOGIE : C'est comme graver quelque chose dans la pierre
 * Une fois définie, la valeur ne peut JAMAIS changer
 * 
 * Différence avec une variable :
 * - Variable : $nom = 'Jean'; puis $nom = 'Paul'; ✅ OK
 * - Constante : define('NOM', 'Jean'); puis NOM = 'Paul'; ❌ ERREUR
 * 
 * Convention : les constantes s'écrivent en MAJUSCULES
 */

// Nom de l'application (affiché dans le footer, etc.)
define('APP_NOM',      'GestionSalles CI');

// Version de l'application (pour le suivi des mises à jour)
define('APP_VERSION',  '1.0');

/**
 * 💡 date('Y') = récupérer l'année actuelle
 * 
 * ANALOGIE : C'est comme regarder un calendrier
 * 'Y' = année sur 4 chiffres (2025)
 * 'y' = année sur 2 chiffres (25)
 * 'm' = mois (01 à 12)
 * 'd' = jour (01 à 31)
 */
define('APP_ANNEE',    date('Y'));

/**
 * 💡 BASE_URL = l'URL de base de votre application
 * 
 * ANALOGIE : C'est comme l'adresse de votre maison
 * Toutes les pièces (pages) sont relatives à cette adresse
 * 
 * ⚠️ IMPORTANT : Adapter selon votre installation !
 * - En local : http://localhost/nom-du-dossier/
 * - En production : https://monsite.com/
 * 
 * Le / à la fin est OBLIGATOIRE !
 */
define('BASE_URL', 'http://localhost/salles/');

// ============================================================
// DÉMARRER LA SESSION PHP
// ============================================================
/**
 * 💡 LES SESSIONS PHP : Comprendre le concept
 * 
 * PROBLÈME : HTTP est "sans mémoire" (stateless)
 * Chaque fois que vous chargez une page, le serveur "oublie"
 * qui vous êtes. C'est comme si vous perdiez la mémoire
 * à chaque fois que vous changez de pièce !
 * 
 * SOLUTION : Les SESSIONS
 * PHP crée un "dossier" pour chaque visiteur et y stocke
 * des informations qui persistent entre les pages.
 * 
 * ANALOGIE : C'est comme un casier dans une salle de sport
 * - Vous arrivez → on vous donne une clé (cookie PHPSESSID)
 * - Vous mettez vos affaires dans le casier (données de session)
 * - Vous changez de vestiaire → vous gardez votre clé
 * - Vous récupérez vos affaires → elles sont toujours là !
 * 
 * FONCTIONNEMENT TECHNIQUE :
 * 1. session_start() crée un ID unique (ex: abc123def456)
 * 2. PHP envoie un cookie au navigateur avec cet ID
 * 3. Le navigateur renvoie ce cookie à chaque requête
 * 4. PHP retrouve le "dossier" correspondant à cet ID
 * 5. Les données dans $_SESSION sont disponibles
 * 
 * UTILISATION :
 * - Stocker : $_SESSION['user_id'] = 42;
 * - Lire : echo $_SESSION['user_id']; // 42
 * - Supprimer : unset($_SESSION['user_id']);
 * - Tout détruire : session_destroy();
 */

/**
 * 💡 session_status() = vérifier l'état de la session
 * 
 * Retourne :
 * - PHP_SESSION_DISABLED = sessions désactivées
 * - PHP_SESSION_NONE = pas de session active
 * - PHP_SESSION_ACTIVE = session déjà démarrée
 * 
 * On vérifie avant de démarrer pour éviter une erreur
 * si session_start() est appelé deux fois
 */
if (session_status() === PHP_SESSION_NONE) {
    /**
     * 💡 session_start() = démarrer ou reprendre la session
     * 
     * ANALOGIE : C'est comme ouvrir votre casier avec votre clé
     * Si c'est la première fois, on crée le casier
     * Sinon, on ouvre le casier existant
     */
    session_start();
}

// ============================================================
// INCLURE LA CLASSE DATABASE
// ============================================================
/**
 * 💡 require_once = inclure un fichier PHP
 * 
 * ANALOGIE : C'est comme copier-coller le contenu d'un fichier
 * 
 * Différence entre les 4 méthodes d'inclusion :
 * 
 * 1. include      = inclure, continuer si erreur (⚠️ risqué)
 * 2. require      = inclure, STOP si erreur (✅ mieux)
 * 3. include_once = inclure UNE SEULE FOIS
 * 4. require_once = inclure UNE SEULE FOIS, STOP si erreur (✅ le meilleur)
 * 
 * __DIR__ = le dossier du fichier actuel (includes/)
 * Donc __DIR__ . '/Database.php' = includes/Database.php
 */
require_once __DIR__ . '/Database.php';

// ============================================================
// FONCTIONS UTILITAIRES GLOBALES
// ============================================================
/**
 * 💡 Les FONCTIONS : Comprendre le concept
 * 
 * ANALOGIE : Une fonction est comme une machine
 * - Vous mettez quelque chose dedans (paramètres)
 * - Elle fait un traitement
 * - Elle vous rend un résultat (return)
 * 
 * Exemple concret : une machine à café
 * - Paramètre : type de café (expresso, cappuccino)
 * - Traitement : moudre, chauffer, mélanger
 * - Résultat : une tasse de café
 * 
 * SYNTAXE :
 * function nomDeLaFonction($parametre1, $parametre2) {
 *     // Code à exécuter
 *     return $resultat;
 * }
 * 
 * APPEL :
 * $monCafe = nomDeLaFonction('expresso', 'sucre');
 */

/**
 * Vérifie si l'utilisateur est connecté
 * Redirige vers la page de login si non connecté
 * 
 * 💡 void = cette fonction ne retourne rien (return;)
 * 
 * ANALOGIE : C'est comme un videur de boîte de nuit
 * Si tu n'as pas de bracelet (session), tu ne rentres pas !
 */
function verifierConnexion(): void
{
    /**
     * 💡 isset() = vérifier si une variable existe
     * 
     * ANALOGIE : C'est comme vérifier si une clé existe
     * dans un trousseau avant d'essayer de l'utiliser
     * 
     * Retourne :
     * - true si la variable existe (même si elle vaut null)
     * - false si la variable n'existe pas
     */
    if (!isset($_SESSION['user_id'])) {
        /**
         * 💡 header('Location: ...') = rediriger vers une autre page
         * 
         * ANALOGIE : C'est comme un panneau "Déviation"
         * qui vous envoie sur une autre route
         * 
         * ⚠️ IMPORTANT : Toujours mettre exit; après !
         * Sinon le code continue à s'exécuter
         */
        header('Location: ' . BASE_URL . 'index.php');
        exit; // STOP l'exécution du script
    }
}

/**
 * Retourne l'utilisateur connecté depuis la session
 * 
 * 💡 ?array = peut retourner un tableau OU null
 * 
 * ANALOGIE : C'est comme demander "Qui est connecté ?"
 * - Si quelqu'un est connecté → retourne ses infos
 * - Sinon → retourne null (personne)
 * 
 * @return array|null Les données de l'utilisateur ou null
 */
function utilisateurConnecte(): ?array
{
    /**
     * 💡 ?? = opérateur de coalescence nulle
     * 
     * ANALOGIE : C'est comme dire "Donne-moi A, sinon B"
     * 
     * $_SESSION['user'] ?? null signifie :
     * - Si $_SESSION['user'] existe → le retourner
     * - Sinon → retourner null
     * 
     * C'est un raccourci pour :
     * isset($_SESSION['user']) ? $_SESSION['user'] : null
     */
    return $_SESSION['user'] ?? null;
}

/**
 * Échappe le HTML pour affichage sécurisé
 * 
 * 💡 SÉCURITÉ : TOUJOURS utiliser cette fonction pour afficher
 * des données venant de la base de données ou de l'utilisateur !
 * 
 * POURQUOI ? Pour éviter les attaques XSS (Cross-Site Scripting)
 * 
 * ANALOGIE : C'est comme désinfecter de la nourriture
 * avant de la manger. On neutralise les "microbes" (code malveillant)
 * 
 * EXEMPLE D'ATTAQUE :
 * Un utilisateur entre : <script>alert('Hack!')</script>
 * Sans échappement : le script s'exécute ! 💀
 * Avec échappement : affiché comme du texte ✅
 * 
 * @param string $texte Le texte à échapper
 * @return string Le texte sécurisé
 */
function e(string $texte): string
{
    /**
     * 💡 htmlspecialchars() = convertir les caractères spéciaux
     * 
     * Conversions :
     * < devient &lt;
     * > devient &gt;
     * " devient &quot;
     * ' devient &#039;
     * & devient &amp;
     * 
     * Paramètres :
     * - ENT_QUOTES = échapper aussi les guillemets simples
     * - 'UTF-8' = encodage (pour les accents français)
     */
    return htmlspecialchars($texte, ENT_QUOTES, 'UTF-8');
}

/**
 * Formater une date MySQL en date française
 * 
 * 💡 MySQL stocke les dates au format : 2025-01-15 14:30:00
 * On veut afficher : 15 janvier 2025
 * 
 * ANALOGIE : C'est comme traduire une date anglaise en français
 * 
 * @param string $date Date au format MySQL
 * @return string Date formatée en français
 * 
 * Exemple :
 * dateFormat('2025-01-15 14:30:00') → "15 janvier 2025"
 */
function dateFormat(string $date): string
{
    /**
     * 💡 Tableau associatif des mois en français
     * 
     * ANALOGIE : C'est comme un dictionnaire
     * Clé (numéro) → Valeur (nom du mois)
     * 
     * $mois[1] = 'janvier'
     * $mois[12] = 'décembre'
     */
    $mois = [
        1  => 'janvier',   2  => 'février',   3  => 'mars',
        4  => 'avril',     5  => 'mai',       6  => 'juin',
        7  => 'juillet',   8  => 'août',      9  => 'septembre',
        10 => 'octobre',   11 => 'novembre',  12 => 'décembre'
    ];

    /**
     * 💡 strtotime() = convertir une date en timestamp
     * 
     * ANALOGIE : C'est comme convertir une date en "secondes
     * depuis le 1er janvier 1970" (format universel)
     * 
     * Exemple :
     * strtotime('2025-01-15') → 1736899200
     */
    $timestamp = strtotime($date);
    
    /**
     * 💡 date() = formater un timestamp
     * 
     * Codes de format :
     * 'd' = jour sur 2 chiffres (01 à 31)
     * 'm' = mois sur 2 chiffres (01 à 12)
     * 'Y' = année sur 4 chiffres (2025)
     * 'H' = heure sur 2 chiffres (00 à 23)
     * 'i' = minutes sur 2 chiffres (00 à 59)
     * 
     * (int) = convertir en entier (enlever le 0 devant)
     * Exemple : (int)'05' → 5
     */
    return date('d', $timestamp) . ' ' . $mois[(int)date('m', $timestamp)] . ' ' . date('Y', $timestamp);
}
