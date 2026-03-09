# Module 13 — Bases de données avec PHP

> **Niveau :** Intermédiaire → Avancé
> **Durée estimée :** 6 à 8 heures
> **Prérequis :** Modules 01 à 12

---

## Table des matières

1. [Introduction aux bases de données](#1-introduction-aux-bases-de-données)
2. [Créer une base de données MySQL](#2-créer-une-base-de-données-mysql)
3. [Connexion avec PDO](#3-connexion-avec-pdo)
4. [Les requêtes préparées](#4-les-requêtes-préparées)
5. [CRUD complet avec PDO](#5-crud-complet-avec-pdo)
6. [Gestion des erreurs PDO](#6-gestion-des-erreurs-pdo)
7. [La classe MySQLi (alternative)](#7-la-classe-mysqli-alternative)
8. [Classe d'accès aux données (DAO Pattern)](#8-classe-daccès-aux-données-dao-pattern)
9. [Transactions](#9-transactions)
10. [Exercices pratiques](#10-exercices-pratiques)
11. [Corrigés](#11-corrigés)

---

## 1. Introduction aux bases de données

### Pourquoi une base de données ?

Jusqu'à présent, nos données disparaissaient à chaque requête. Une base de données permet de **persister** (sauvegarder) les données.

```
Sans base de données :
┌─────────────────┐     Requête      ┌─────────────────┐
│   Navigateur    │ ──────────────→  │    PHP Script   │
│                 │ ←────────────── │  (données en    │
│                 │     HTML         │  mémoire temp.) │
└─────────────────┘                  └─────────────────┘
                                     ↑ Les données disparaissent !

Avec base de données :
┌─────────────────┐     Requête      ┌─────────────────┐
│   Navigateur    │ ──────────────→  │   PHP Script    │
│                 │ ←────────────── │                 │
│                 │     HTML         └────────┬────────┘
└─────────────────┘                           │ SQL
                                    ┌─────────▼────────┐
                                    │   Base MySQL     │
                                    │  (données        │
                                    │   persistantes)  │
                                    └──────────────────┘
```

### Les bases du SQL

SQL (**Structured Query Language**) est le langage utilisé pour interagir avec une base de données.

```sql
-- Créer une table
CREATE TABLE utilisateurs (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    nom      VARCHAR(100) NOT NULL,
    email    VARCHAR(255) UNIQUE NOT NULL,
    age      INT,
    cree_le  DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Insérer des données
INSERT INTO utilisateurs (nom, email, age) VALUES ('Alice', 'alice@test.com', 25);

-- Lire des données
SELECT * FROM utilisateurs;
SELECT nom, email FROM utilisateurs WHERE age >= 18;

-- Mettre à jour
UPDATE utilisateurs SET age = 26 WHERE nom = 'Alice';

-- Supprimer
DELETE FROM utilisateurs WHERE nom = 'Alice';
```

---

## 2. Créer une base de données MySQL

### Via phpMyAdmin (graphique)

1. Ouvrez `http://localhost/phpmyadmin`
2. Cliquez "Nouvelle base de données"
3. Entrez le nom : `cours_php`
4. Choisissez l'interclassement : `utf8mb4_unicode_ci`
5. Cliquez "Créer"

### Via SQL

```sql
-- Créer la base de données
CREATE DATABASE cours_php
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- Sélectionner la base
USE cours_php;

-- Créer la table utilisateurs
CREATE TABLE utilisateurs (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom         VARCHAR(100) NOT NULL,
    prenom      VARCHAR(100) NOT NULL,
    email       VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role        ENUM('admin', 'utilisateur') NOT NULL DEFAULT 'utilisateur',
    actif       TINYINT(1) NOT NULL DEFAULT 1,
    cree_le     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modifie_le  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Créer la table articles
CREATE TABLE articles (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titre       VARCHAR(255) NOT NULL,
    contenu     TEXT NOT NULL,
    auteur_id   INT UNSIGNED NOT NULL,
    publie      TINYINT(1) NOT NULL DEFAULT 0,
    cree_le     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (auteur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Insérer des données de test
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES
    ('Martin',   'Alice',   'alice@test.com',   '$2y$10$...', 'admin'),
    ('Dupont',   'Bob',     'bob@test.com',     '$2y$10$...', 'utilisateur'),
    ('Bernard',  'Charlie', 'charlie@test.com', '$2y$10$...', 'utilisateur');
```

---

## 3. Connexion avec PDO

PDO (**PHP Data Objects**) est l'extension PHP recommandée pour interagir avec les bases de données. Elle est :

- **Compatible** avec de nombreux SGBD (MySQL, PostgreSQL, SQLite, etc.)
- **Sécurisée** grâce aux requêtes préparées
- **Orientée objet**

### Connexion de base

```php
<?php
declare(strict_types=1);

/**
 * Connexion à une base de données avec PDO
 */

// Paramètres de connexion
$hote    = "localhost";
$port    = "3306";
$base    = "cours_php";
$charset = "utf8mb4";
$user    = "root";       // À remplacer en production !
$mdp     = "";           // À remplacer en production !

// DSN : Data Source Name — chaîne qui identifie la source de données
$dsn = "mysql:host={$hote};port={$port};dbname={$base};charset={$charset}";

// Options PDO recommandées
$options = [
    // Lance une exception PHP si une erreur SQL survient
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    // Retourne les résultats sous forme de tableau associatif par défaut
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // Désactive l'émulation des requêtes préparées (meilleure sécurité)
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $mdp, $options);
    echo "Connexion réussie !";

} catch (PDOException $e) {
    // En production, ne JAMAIS afficher le message d'erreur brut
    // Il peut contenir des informations sensibles (identifiants, structure BDD)
    echo "Erreur de connexion. Veuillez réessayer.";

    // En développement, vous pouvez logger l'erreur
    error_log("PDO Error: " . $e->getMessage());

    exit(1);  // Arrêter le script
}
?>
```

### Classe de connexion réutilisable

```php
<?php
/**
 * Fichier : config/Database.php
 * 
 * Classe Singleton pour gérer la connexion PDO.
 * Garantit une seule connexion par requête.
 */
class Database {
    private static ?PDO $instance = null;

    // Empêche l'instanciation directe
    private function __construct() {}
    private function __clone() {}

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $config = [
                'host'    => $_ENV['DB_HOST']    ?? 'localhost',
                'port'    => $_ENV['DB_PORT']    ?? '3306',
                'dbname'  => $_ENV['DB_NAME']    ?? 'cours_php',
                'charset' => 'utf8mb4',
                'user'    => $_ENV['DB_USER']    ?? 'root',
                'password'=> $_ENV['DB_PASSWORD'] ?? '',
            ];

            $dsn = sprintf(
                "mysql:host=%s;port=%s;dbname=%s;charset=%s",
                $config['host'],
                $config['port'],
                $config['dbname'],
                $config['charset']
            );

            self::$instance = new PDO(
                $dsn,
                $config['user'],
                $config['password'],
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        }

        return self::$instance;
    }
}

// Utilisation
$pdo = Database::getInstance();
?>
```

---

## 4. Les requêtes préparées

### Pourquoi les requêtes préparées ?

Imaginez qu'un utilisateur entre `alice' OR '1'='1` dans un champ de connexion.

**Sans requête préparée (DANGEREUX) :**
```php
// ❌ INJECTION SQL : ne JAMAIS faire ça !
$email = $_POST['email'];  // L'utilisateur entre : alice' OR '1'='1
$sql   = "SELECT * FROM utilisateurs WHERE email = '$email'";
// La requête devient :
// SELECT * FROM utilisateurs WHERE email = 'alice' OR '1'='1'
// Résultat : RETOURNE TOUS LES UTILISATEURS !
```

**Avec requête préparée (SÉCURISÉ) :**
```php
// ✅ Les données sont séparées du code SQL
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
$stmt->execute([$email]);  // PHP transmet la valeur séparément
// MySQL reçoit la requête ET les données séparément
// L'injection SQL est IMPOSSIBLE
```

### Syntaxe des requêtes préparées

```php
<?php
// Méthode 1 : Paramètres positionnels (?)
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE age > ? AND actif = ?");
$stmt->execute([18, 1]);  // Les ? sont remplacés dans l'ordre

// Méthode 2 : Paramètres nommés (:nom) — plus lisible
$stmt = $pdo->prepare("
    SELECT * FROM utilisateurs
    WHERE age > :age_min
    AND   actif = :actif
");
$stmt->execute([
    ':age_min' => 18,
    ':actif'   => 1,
]);
// Ou sans les deux-points :
$stmt->execute(['age_min' => 18, 'actif' => 1]);

// Récupérer les résultats
$utilisateurs = $stmt->fetchAll();   // Tous les résultats (tableau de tableaux)
$utilisateur  = $stmt->fetch();      // Un seul résultat
$colonne      = $stmt->fetchColumn(); // Une seule valeur (1ère colonne)
$nombre       = $stmt->rowCount();   // Nombre de lignes affectées
?>
```

---

## 5. CRUD complet avec PDO

CRUD signifie : **C**reate, **R**ead, **U**pdate, **D**elete.

```php
<?php
declare(strict_types=1);

$pdo = Database::getInstance();  // Utilise notre classe de connexion

// =========================================================
// C — CREATE (INSERT)
// =========================================================
function creerUtilisateur(PDO $pdo, array $donnees): int {
    $sql = "
        INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe)
        VALUES (:nom, :prenom, :email, :mot_de_passe)
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nom'          => $donnees['nom'],
        ':prenom'       => $donnees['prenom'],
        ':email'        => $donnees['email'],
        ':mot_de_passe' => password_hash($donnees['mot_de_passe'], PASSWORD_BCRYPT),
    ]);

    // Retourne l'ID de la ligne insérée
    return (int) $pdo->lastInsertId();
}

// Utilisation
$id = creerUtilisateur($pdo, [
    'nom'          => 'Durand',
    'prenom'       => 'Diana',
    'email'        => 'diana@test.com',
    'mot_de_passe' => 'MonMDP_Securise123!',
]);
echo "Utilisateur créé avec l'ID : " . $id . PHP_EOL;

// =========================================================
// R — READ (SELECT)
// =========================================================

// Lire TOUS les utilisateurs
function lireTousLesUtilisateurs(PDO $pdo): array {
    $stmt = $pdo->query("SELECT id, nom, prenom, email, role, cree_le FROM utilisateurs");
    return $stmt->fetchAll();
}

// Lire UN utilisateur par ID
function lireUtilisateurParId(PDO $pdo, int $id): ?array {
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $resultat = $stmt->fetch();
    return $resultat !== false ? $resultat : null;
}

// Rechercher des utilisateurs
function rechercherUtilisateurs(PDO $pdo, string $terme): array {
    $termeSQL = "%" . $terme . "%";  // % = wildcard SQL
    $stmt = $pdo->prepare("
        SELECT id, nom, prenom, email
        FROM   utilisateurs
        WHERE  nom LIKE :terme
           OR  prenom LIKE :terme
           OR  email LIKE :terme
        ORDER BY nom, prenom
    ");
    $stmt->execute([':terme' => $termeSQL]);
    return $stmt->fetchAll();
}

// Utilisation
$utilisateurs = lireTousLesUtilisateurs($pdo);
foreach ($utilisateurs as $u) {
    echo "{$u['prenom']} {$u['nom']} — {$u['email']}" . PHP_EOL;
}

$user = lireUtilisateurParId($pdo, 1);
echo $user ? "Trouvé : {$user['prenom']}" : "Non trouvé";

// =========================================================
// U — UPDATE (UPDATE)
// =========================================================
function mettreAJourUtilisateur(PDO $pdo, int $id, array $donnees): bool {
    $sql = "
        UPDATE utilisateurs
        SET    nom    = :nom,
               prenom = :prenom,
               email  = :email
        WHERE  id     = :id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nom'    => $donnees['nom'],
        ':prenom' => $donnees['prenom'],
        ':email'  => $donnees['email'],
        ':id'     => $id,
    ]);

    return $stmt->rowCount() > 0;  // true si au moins 1 ligne modifiée
}

$succes = mettreAJourUtilisateur($pdo, 1, [
    'nom'    => 'Martin',
    'prenom' => 'Alice Marie',
    'email'  => 'alice.marie@test.com',
]);
echo $succes ? "Mise à jour réussie" : "Aucune modification";

// =========================================================
// D — DELETE (DELETE)
// =========================================================
function supprimerUtilisateur(PDO $pdo, int $id): bool {
    $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->rowCount() > 0;
}

$supprime = supprimerUtilisateur($pdo, 4);
echo $supprime ? "Suppression réussie" : "Utilisateur non trouvé";

// =========================================================
// Requêtes avec COUNT, SUM, GROUP BY
// =========================================================
function statistiquesUtilisateurs(PDO $pdo): array {
    $stmt = $pdo->query("
        SELECT
            COUNT(*)                    AS total,
            SUM(CASE WHEN actif = 1 THEN 1 ELSE 0 END) AS actifs,
            SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) AS admins,
            AVG(age)                    AS age_moyen
        FROM utilisateurs
    ");
    return $stmt->fetch();
}
?>
```

---

## 6. Gestion des erreurs PDO

```php
<?php
// Mode ERRMODE_EXCEPTION (recommandé) :
// PDO lance une PDOException si une erreur SQL survient

try {
    $pdo = Database::getInstance();

    // Exemple : tentative d'insérer un email déjà existant (UNIQUE)
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (email, nom, prenom, mot_de_passe) VALUES (?, ?, ?, ?)");
    $stmt->execute(['alice@test.com', 'Autre', 'Alice', 'mdp']);

} catch (PDOException $e) {
    // Codes d'erreur MySQL courants :
    $code = $e->getCode();

    switch ($code) {
        case 23000:  // SQLSTATE : Integrity constraint violation (doublons, FK)
            echo "Erreur : Cette adresse email est déjà utilisée.";
            break;
        case '42S02':  // Table non trouvée
            echo "Erreur technique : table introuvable.";
            error_log($e->getMessage());
            break;
        default:
            echo "Une erreur est survenue. Veuillez réessayer.";
            error_log("PDO Error [{$code}]: " . $e->getMessage());
    }
}
?>
```

---

## 7. La classe MySQLi (alternative)

MySQLi est une alternative à PDO, spécifique à MySQL.

```php
<?php
// Connexion MySQLi (orientée objet)
$mysqli = new mysqli("localhost", "root", "", "cours_php");

if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8mb4");

// Requête préparée avec MySQLi
$stmt = $mysqli->prepare("SELECT nom, email FROM utilisateurs WHERE id = ?");
$stmt->bind_param("i", $id);  // "i" = integer, "s" = string, "d" = double

$id = 1;
$stmt->execute();
$stmt->bind_result($nom, $email);
$stmt->fetch();

echo "$nom — $email";
$stmt->close();
$mysqli->close();
?>
```

> 💡 **PDO vs MySQLi** : PDO est généralement préféré car il supporte plusieurs SGBD (pas seulement MySQL) et a une API plus moderne. Utilisez PDO sauf contrainte spécifique.

---

## 8. Classe d'accès aux données (DAO Pattern)

```php
<?php
/**
 * Fichier : src/Models/Utilisateur.php
 * 
 * Classe modèle représentant un utilisateur
 */
class Utilisateur {
    public function __construct(
        private int    $id,
        private string $nom,
        private string $prenom,
        private string $email,
        private string $role = 'utilisateur'
    ) {}

    public function getId(): int      { return $this->id; }
    public function getNom(): string  { return $this->nom; }
    public function getPrenom(): string { return $this->prenom; }
    public function getEmail(): string { return $this->email; }
    public function getRole(): string  { return $this->role; }

    public function getNomComplet(): string {
        return $this->prenom . ' ' . $this->nom;
    }
}

/**
 * Fichier : src/Repositories/UtilisateurRepository.php
 * 
 * DAO (Data Access Object) : gère toutes les opérations BDD pour Utilisateur
 */
class UtilisateurRepository {
    public function __construct(private PDO $pdo) {}

    /**
     * Récupère tous les utilisateurs.
     * @return Utilisateur[]
     */
    public function findAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM utilisateurs ORDER BY nom");
        $rows = $stmt->fetchAll();

        return array_map([$this, 'hydrate'], $rows);
    }

    /**
     * Trouve un utilisateur par son ID.
     */
    public function findById(int $id): ?Utilisateur {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row ? $this->hydrate($row) : null;
    }

    /**
     * Trouve un utilisateur par son email.
     */
    public function findByEmail(string $email): ?Utilisateur {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch();

        return $row ? $this->hydrate($row) : null;
    }

    /**
     * Sauvegarde (crée ou met à jour) un utilisateur.
     */
    public function save(Utilisateur $utilisateur): Utilisateur {
        if ($utilisateur->getId() === 0) {
            return $this->insert($utilisateur);
        }
        return $this->update($utilisateur);
    }

    private function insert(Utilisateur $utilisateur): Utilisateur {
        $stmt = $this->pdo->prepare("
            INSERT INTO utilisateurs (nom, prenom, email, role)
            VALUES (:nom, :prenom, :email, :role)
        ");
        $stmt->execute([
            ':nom'    => $utilisateur->getNom(),
            ':prenom' => $utilisateur->getPrenom(),
            ':email'  => $utilisateur->getEmail(),
            ':role'   => $utilisateur->getRole(),
        ]);
        return $this->findById((int) $this->pdo->lastInsertId());
    }

    private function update(Utilisateur $utilisateur): Utilisateur {
        $stmt = $this->pdo->prepare("
            UPDATE utilisateurs SET nom = :nom, prenom = :prenom, email = :email
            WHERE id = :id
        ");
        $stmt->execute([
            ':nom'    => $utilisateur->getNom(),
            ':prenom' => $utilisateur->getPrenom(),
            ':email'  => $utilisateur->getEmail(),
            ':id'     => $utilisateur->getId(),
        ]);
        return $utilisateur;
    }

    /**
     * Convertit un tableau (ligne BDD) en objet Utilisateur.
     */
    private function hydrate(array $row): Utilisateur {
        return new Utilisateur(
            (int) $row['id'],
            $row['nom'],
            $row['prenom'],
            $row['email'],
            $row['role']
        );
    }
}
?>
```

---

## 9. Transactions

Une transaction garantit que plusieurs opérations s'exécutent **toutes ensemble ou pas du tout** (principe ACID).

```php
<?php
/**
 * Exemple : Transfert bancaire entre deux comptes.
 * 
 * Les deux opérations doivent réussir ENSEMBLE.
 * Si l'une échoue, l'autre doit être annulée.
 */
function transfererArgent(PDO $pdo, int $compteSource, int $compteDestination, float $montant): void {
    // Démarrer la transaction
    $pdo->beginTransaction();

    try {
        // Opération 1 : Débiter le compte source
        $stmt1 = $pdo->prepare("UPDATE comptes SET solde = solde - :montant WHERE id = :id AND solde >= :montant");
        $stmt1->execute([':montant' => $montant, ':id' => $compteSource]);

        if ($stmt1->rowCount() === 0) {
            throw new RuntimeException("Solde insuffisant ou compte introuvable.");
        }

        // Opération 2 : Créditer le compte destination
        $stmt2 = $pdo->prepare("UPDATE comptes SET solde = solde + :montant WHERE id = :id");
        $stmt2->execute([':montant' => $montant, ':id' => $compteDestination]);

        if ($stmt2->rowCount() === 0) {
            throw new RuntimeException("Compte destination introuvable.");
        }

        // Les deux opérations ont réussi : on valide
        $pdo->commit();
        echo "Transfert de {$montant}€ effectué avec succès." . PHP_EOL;

    } catch (\Exception $e) {
        // Une erreur s'est produite : on annule TOUT
        $pdo->rollBack();
        echo "Transfert annulé : " . $e->getMessage() . PHP_EOL;
    }
}
?>
```

---

## 10. Exercices pratiques

### Exercice 1 — CRUD Complet

Créez un script PHP avec les fonctions CRUD pour une table `produits` (id, nom, prix, categorie, stock).

### Exercice 2 — Système de recherche

Créez une page PHP avec un formulaire de recherche qui interroge la base de données et affiche les résultats dans un tableau HTML.

---

## 11. Corrigés

### Corrigé Exercice 1

```php
<?php
declare(strict_types=1);

class ProduitRepository {
    public function __construct(private PDO $pdo) {}

    public function findAll(string $ordre = 'nom'): array {
        $colonnesAutorisees = ['nom', 'prix', 'stock', 'categorie'];
        $ordre = in_array($ordre, $colonnesAutorisees) ? $ordre : 'nom';

        $stmt = $this->pdo->query("SELECT * FROM produits ORDER BY {$ordre}");
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM produits WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function create(array $data): int {
        $stmt = $this->pdo->prepare("
            INSERT INTO produits (nom, prix, categorie, stock)
            VALUES (:nom, :prix, :categorie, :stock)
        ");
        $stmt->execute([
            ':nom'       => $data['nom'],
            ':prix'      => $data['prix'],
            ':categorie' => $data['categorie'],
            ':stock'     => $data['stock'] ?? 0,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->pdo->prepare("
            UPDATE produits SET nom = :nom, prix = :prix, categorie = :categorie, stock = :stock
            WHERE id = :id
        ");
        $stmt->execute([
            ':nom'       => $data['nom'],
            ':prix'      => $data['prix'],
            ':categorie' => $data['categorie'],
            ':stock'     => $data['stock'],
            ':id'        => $id,
        ]);
        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM produits WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }
}
?>
```

---

## Résumé du module 13

| Concept | Points clés |
|---------|-------------|
| PDO | Extension PHP pour BDD, orientée objet, multi-SGBD |
| DSN | `mysql:host=...;dbname=...;charset=utf8mb4` |
| Requête préparée | `prepare()` + `execute()` — Obligatoire contre les injections SQL |
| `?` ou `:nom` | Deux syntaxes pour les paramètres préparés |
| `fetchAll()` | Retourne tous les résultats |
| `fetch()` | Retourne un seul résultat |
| Transaction | `beginTransaction()`, `commit()`, `rollBack()` |
| DAO | Séparer la logique BDD dans une classe dédiée |

---

**➡️ Module suivant : [Module 14 — Sécurité Web](../14-securite/README.md)**

🔗 [Retour au sommaire principal](../../README.md)
