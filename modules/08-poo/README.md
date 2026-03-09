# Module 08 — Programmation Orientée Objet (POO) en PHP

> **Niveau :** Intermédiaire → Avancé
> **Durée estimée :** 8 à 10 heures
> **Prérequis :** Modules 01 à 07

---

## Table des matières

1. [Introduction à la POO](#1-introduction-à-la-poo)
2. [Classes et objets](#2-classes-et-objets)
3. [Attributs (propriétés)](#3-attributs-propriétés)
4. [Méthodes](#4-méthodes)
5. [Encapsulation et modificateurs d'accès](#5-encapsulation-et-modificateurs-daccès)
6. [Le constructeur et le destructeur](#6-le-constructeur-et-le-destructeur)
7. [Héritage](#7-héritage)
8. [Méthodes et propriétés statiques](#8-méthodes-et-propriétés-statiques)
9. [Classes abstraites](#9-classes-abstraites)
10. [Interfaces](#10-interfaces)
11. [Traits](#11-traits)
12. [Polymorphisme](#12-polymorphisme)
13. [Espaces de noms (namespaces)](#13-espaces-de-noms-namespaces)
14. [Patterns courants en PHP](#14-patterns-courants-en-php)
15. [Exercices pratiques](#15-exercices-pratiques)
16. [Corrigés](#16-corrigés)

---

## 1. Introduction à la POO

### Programmation procédurale vs orientée objet

Jusqu'à présent, nous avons écrit du code **procédural** : des instructions exécutées les unes après les autres, avec des fonctions séparées.

La **Programmation Orientée Objet (POO)** est un paradigme différent : on organise le code autour d'**objets** qui regroupent **données** (attributs) et **comportements** (méthodes).

### Analogie : une voiture

```
APPROCHE PROCÉDURALE :
$marque        = "Toyota";
$vitesse       = 0;
$carburant     = 100;

function accelerer(&$vitesse, &$carburant, int $valeur) { ... }
function freiner(&$vitesse) { ... }
function fairePlein(&$carburant) { ... }

→ Les données et les fonctions sont SÉPARÉES.

---

APPROCHE POO :
class Voiture {
    public string $marque;
    private int $vitesse;
    private int $carburant;

    public function accelerer(int $valeur) { ... }
    public function freiner() { ... }
    public function fairePlein() { ... }
}

$maVoiture = new Voiture();  // Crée une instance

→ Les données et les comportements sont REGROUPÉS dans la classe.
```

### Avantages de la POO

| Avantage | Explication |
|----------|-------------|
| **Réutilisabilité** | Une classe peut être utilisée partout dans le projet |
| **Encapsulation** | Les données internes sont protégées |
| **Héritage** | Une classe peut "hériter" d'une autre |
| **Maintenabilité** | Code plus organisé, plus facile à modifier |
| **Modularité** | Chaque objet est une unité indépendante |

---

## 2. Classes et objets

### La classe : le plan de construction

Une **classe** est comme un **plan d'architecte** ou une **recette de cuisine**. Elle définit la structure, mais n'est pas l'objet lui-même.

Un **objet** est une **instance** d'une classe — c'est le bâtiment construit selon le plan, ou le gâteau cuit selon la recette.

```php
<?php
/**
 * Définition d'une classe simple.
 * 
 * Convention : le nom de la classe commence par une MAJUSCULE (PascalCase)
 * et le nom du fichier doit correspondre : Personne.php
 */
class Personne {
    // Attributs (propriétés)
    public string $prenom;
    public string $nom;
    public int    $age;

    // Méthode (comportement)
    public function direBonjour(): string {
        return "Bonjour, je suis " . $this->prenom . " " . $this->nom . " !";
    }

    public function estMajeur(): bool {
        return $this->age >= 18;
    }
}

// Créer des objets (instanciation)
// new NomDeLaClasse() crée une nouvelle instance
$alice = new Personne();
$alice->prenom = "Alice";
$alice->nom    = "Martin";
$alice->age    = 25;

$bob = new Personne();
$bob->prenom = "Bob";
$bob->nom    = "Dupont";
$bob->age    = 17;

// Utiliser les objets
echo $alice->direBonjour();  // Bonjour, je suis Alice Martin !
echo $bob->direBonjour();    // Bonjour, je suis Bob Dupont !

echo $alice->estMajeur() ? "Alice est majeure." : "Alice est mineure.";  // Majeure
echo $bob->estMajeur()   ? "Bob est majeur."    : "Bob est mineur.";     // Mineur

// $this fait référence à L'INSTANCE COURANTE de la classe
// Quand $alice->estMajeur() est appelé, $this représente $alice
// Quand $bob->estMajeur() est appelé, $this représente $bob
?>
```

---

## 3. Attributs (propriétés)

```php
<?php
class Produit {
    // Attributs avec types (PHP 7.4+)
    public string  $nom;
    public float   $prix;
    public int     $stock;
    public ?string $description = null;  // Nullable avec valeur par défaut

    // Propriété en lecture seule (PHP 8.1+)
    public readonly string $reference;  // Ne peut être assignée qu'une fois
}

$p = new Produit();
$p->nom  = "Cahier A4";
$p->prix = 2.99;

// Vérifier si un attribut existe
echo property_exists($p, 'nom');   // true
echo isset($p->prix);              // true
echo isset($p->description);       // false (null)
?>
```

---

## 4. Méthodes

```php
<?php
class Calculatrice {
    private array $historique = [];  // Historique des calculs

    public function additionner(float $a, float $b): float {
        $resultat = $a + $b;
        $this->enregistrer("$a + $b = $resultat");
        return $resultat;
    }

    public function soustraire(float $a, float $b): float {
        $resultat = $a - $b;
        $this->enregistrer("$a - $b = $resultat");
        return $resultat;
    }

    // Méthode privée — uniquement accessible depuis la classe
    private function enregistrer(string $operation): void {
        $this->historique[] = $operation;
    }

    public function afficherHistorique(): void {
        echo "=== Historique des calculs ===" . PHP_EOL;
        foreach ($this->historique as $i => $op) {
            echo ($i + 1) . ". " . $op . PHP_EOL;
        }
    }
}

$calc = new Calculatrice();
$calc->additionner(10, 5);    // 15
$calc->soustraire(20, 8);     // 12
$calc->afficherHistorique();
// 1. 10 + 5 = 15
// 2. 20 - 8 = 12
?>
```

---

## 5. Encapsulation et modificateurs d'accès

L'encapsulation consiste à **contrôler l'accès** aux attributs et méthodes d'une classe.

```php
<?php
class CompteBancaire {
    private string $titulaire;
    private float  $solde;
    private array  $transactions = [];

    public function __construct(string $titulaire, float $soldeInitial = 0.0) {
        $this->titulaire = $titulaire;
        $this->solde     = $soldeInitial;
    }

    // Getter : retourne le solde (lecture autorisée)
    public function getSolde(): float {
        return $this->solde;
    }

    // Getter pour le titulaire
    public function getTitulaire(): string {
        return $this->titulaire;
    }

    // Méthode contrôlée : déposer de l'argent avec validation
    public function deposer(float $montant): void {
        if ($montant <= 0) {
            throw new InvalidArgumentException("Le montant doit être positif.");
        }
        $this->solde += $montant;
        $this->transactions[] = "+{$montant}€";
    }

    // Méthode contrôlée : retirer de l'argent avec validation
    public function retirer(float $montant): void {
        if ($montant <= 0) {
            throw new InvalidArgumentException("Le montant doit être positif.");
        }
        if ($montant > $this->solde) {
            throw new RuntimeException("Solde insuffisant.");
        }
        $this->solde -= $montant;
        $this->transactions[] = "-{$montant}€";
    }

    public function afficherReleve(): void {
        echo "=== Relevé de compte de {$this->titulaire} ===" . PHP_EOL;
        foreach ($this->transactions as $t) {
            echo "  " . $t . PHP_EOL;
        }
        echo "  Solde actuel : " . number_format($this->solde, 2) . " €" . PHP_EOL;
    }
}

$compte = new CompteBancaire("Alice", 1000.0);
$compte->deposer(500.0);
$compte->retirer(200.0);
$compte->afficherReleve();

// ❌ On ne peut PAS accéder directement à $solde (private)
// echo $compte->solde;  // Fatal error: Cannot access private property

// ✅ On passe par le getter
echo $compte->getSolde();  // 1300.0
?>
```

**Les trois niveaux d'accès :**

| Modificateur | Accessible depuis | Utilisation recommandée |
|-------------|-------------------|------------------------|
| `public` | Partout | Méthodes et propriétés de l'interface publique |
| `protected` | La classe + ses enfants | Attributs utilisés par les sous-classes |
| `private` | La classe uniquement | Détails d'implémentation internes |

---

## 6. Le constructeur et le destructeur

```php
<?php
class Connexion {
    private string $hote;
    private string $base;
    private mixed  $connexion = null;

    /**
     * Le constructeur est appelé automatiquement lors de new Connexion(...)
     * Il initialise l'objet.
     */
    public function __construct(string $hote, string $base) {
        $this->hote = $hote;
        $this->base = $base;
        $this->ouvrir();
        echo "Connexion ouverte vers {$hote}/{$base}" . PHP_EOL;
    }

    /**
     * PHP 8+ : constructor property promotion
     * Déclare ET initialise les propriétés directement dans le constructeur
     */
    // Version raccourcie (PHP 8+) :
    // public function __construct(
    //     private string $hote,
    //     private string $base
    // ) { ... }

    private function ouvrir(): void {
        // Simulation d'ouverture de connexion
        $this->connexion = true;
    }

    private function fermer(): void {
        $this->connexion = null;
        echo "Connexion fermée." . PHP_EOL;
    }

    /**
     * Le destructeur est appelé automatiquement quand l'objet est détruit
     * (fin de script, unset(), ou lorsque plus aucune référence n'existe)
     */
    public function __destruct() {
        $this->fermer();
    }
}

// Utilisation
$conn = new Connexion("localhost", "mabase");  // Constructeur appelé → "Connexion ouverte"
// ... utilisation ...
unset($conn);  // Destructeur appelé → "Connexion fermée"
// En fin de script, le destructeur serait appelé automatiquement
?>
```

### Constructor Property Promotion (PHP 8+)

```php
<?php
// Version AVANT PHP 8 (verbeux)
class Utilisateur {
    public string $nom;
    public string $email;
    private int   $age;

    public function __construct(string $nom, string $email, int $age) {
        $this->nom   = $nom;
        $this->email = $email;
        $this->age   = $age;
    }
}

// Version PHP 8+ (concise — même résultat)
class Utilisateur {
    public function __construct(
        public string $nom,      // public + string + $nom = propriété + paramètre
        public string $email,
        private int   $age,
    ) {}  // Corps vide : PHP gère l'affectation automatiquement
}

$u = new Utilisateur("Alice", "alice@test.com", 25);
echo $u->nom;   // Alice
echo $u->email; // alice@test.com
?>
```

---

## 7. Héritage

L'héritage permet à une classe (**enfant**) de **réutiliser et étendre** le comportement d'une autre classe (**parent**).

```php
<?php
/**
 * Classe de base (parent)
 */
class Animal {
    protected string $nom;
    protected string $espece;

    public function __construct(string $nom, string $espece) {
        $this->nom    = $nom;
        $this->espece = $espece;
    }

    public function sePresenter(): string {
        return "Je suis {$this->nom}, un(e) {$this->espece}.";
    }

    public function dormir(): string {
        return "{$this->nom} dort... ZZzz";
    }

    // Méthode à surcharger dans les classes enfants
    public function faireDuBruit(): string {
        return "{$this->nom} fait un bruit générique.";
    }
}

/**
 * Classe enfant : Chien hérite de Animal
 * - Reçoit TOUS les attributs et méthodes d'Animal
 * - Peut en AJOUTER de nouveaux
 * - Peut en SURCHARGER (override) certains
 */
class Chien extends Animal {
    private string $race;

    public function __construct(string $nom, string $race) {
        // parent::__construct() appelle le constructeur du parent
        parent::__construct($nom, "Chien");
        $this->race = $race;
    }

    // Surcharge de la méthode faireDuBruit()
    public function faireDuBruit(): string {
        return "{$this->nom} aboie : Woof ! Woof !";
    }

    // Nouvelle méthode spécifique au Chien
    public function rapporterLaBalle(): string {
        return "{$this->nom} rapporte la balle ! 🎾";
    }

    public function getRace(): string {
        return $this->race;
    }
}

/**
 * Autre classe enfant
 */
class Chat extends Animal {
    private bool $estSauvage;

    public function __construct(string $nom, bool $estSauvage = false) {
        parent::__construct($nom, "Chat");
        $this->estSauvage = $estSauvage;
    }

    public function faireDuBruit(): string {
        return "{$this->nom} miaule : Miaou !";
    }

    public function ronronner(): string {
        return $this->estSauvage
            ? "{$this->nom} est sauvage, il ne ronronne pas."
            : "{$this->nom} ronronne doucement... prrr";
    }
}

// Utilisation
$rex   = new Chien("Rex", "Berger Allemand");
$minou = new Chat("Minou");

echo $rex->sePresenter() . PHP_EOL;     // Je suis Rex, un(e) Chien.  (hérité d'Animal)
echo $rex->dormir() . PHP_EOL;          // Rex dort... ZZzz  (hérité d'Animal)
echo $rex->faireDuBruit() . PHP_EOL;    // Rex aboie : Woof ! Woof !  (surchargé)
echo $rex->rapporterLaBalle() . PHP_EOL;// Rex rapporte la balle ! 🎾  (propre à Chien)

echo $minou->faireDuBruit() . PHP_EOL;  // Minou miaule : Miaou !  (surchargé)
echo $minou->ronronner() . PHP_EOL;     // Minou ronronne...  (propre à Chat)

// instanceof vérifie si un objet est d'un certain type
var_dump($rex instanceof Chien);   // true
var_dump($rex instanceof Animal);  // true (Chien hérite d'Animal)
var_dump($rex instanceof Chat);    // false
?>
```

### Le mot-clé final

```php
<?php
// final empêche l'héritage d'une classe ou la surcharge d'une méthode
final class Singleton {
    // Cette classe ne peut pas être étendue
}

class Base {
    final public function methodeImmuable(): string {
        return "Cette méthode ne peut pas être surchargée.";
    }
}
?>
```

---

## 8. Méthodes et propriétés statiques

Les membres statiques appartiennent à la **classe elle-même**, pas aux instances.

```php
<?php
class Compteur {
    // Propriété statique : partagée par TOUTES les instances
    private static int $nombreInstances = 0;
    private int        $id;

    public function __construct() {
        self::$nombreInstances++;         // self:: pour accéder au statique
        $this->id = self::$nombreInstances;
    }

    // Méthode statique : appelée sur la classe, pas sur une instance
    public static function getNombreInstances(): int {
        return self::$nombreInstances;
    }

    public function getId(): int {
        return $this->id;
    }
}

$c1 = new Compteur();
$c2 = new Compteur();
$c3 = new Compteur();

echo $c1->getId();  // 1
echo $c2->getId();  // 2
echo $c3->getId();  // 3

// Appel de méthode statique : NomClasse::methode()
echo Compteur::getNombreInstances();  // 3

// ❌ Pas besoin d'instance pour appeler une méthode statique
// $c = new Compteur();
// $c->getNombreInstances();  // Fonctionne mais déconseillé
?>
```

---

## 9. Classes abstraites

Une classe abstraite **ne peut pas être instanciée** directement. Elle sert de **modèle** pour les classes enfants.

```php
<?php
/**
 * Classe abstraite : définit un "contrat" que les sous-classes doivent respecter
 */
abstract class Forme {
    protected string $couleur;

    public function __construct(string $couleur = "noir") {
        $this->couleur = $couleur;
    }

    // Méthode abstraite : DOIT être implémentée dans chaque sous-classe
    abstract public function calculerSurface(): float;
    abstract public function calculerPerimetre(): float;

    // Méthode concrète : disponible dans toutes les sous-classes
    public function decrire(): string {
        return sprintf(
            "Je suis un(e) %s de couleur %s. Surface : %.2f. Périmètre : %.2f.",
            static::class,  // static::class = nom de la classe réelle (pas Forme)
            $this->couleur,
            $this->calculerSurface(),
            $this->calculerPerimetre()
        );
    }
}

class Cercle extends Forme {
    public function __construct(
        private float  $rayon,
        string $couleur = "rouge"
    ) {
        parent::__construct($couleur);
    }

    public function calculerSurface(): float {
        return M_PI * $this->rayon ** 2;  // π × r²
    }

    public function calculerPerimetre(): float {
        return 2 * M_PI * $this->rayon;  // 2πr
    }
}

class Rectangle extends Forme {
    public function __construct(
        private float $largeur,
        private float $hauteur,
        string $couleur = "bleu"
    ) {
        parent::__construct($couleur);
    }

    public function calculerSurface(): float {
        return $this->largeur * $this->hauteur;
    }

    public function calculerPerimetre(): float {
        return 2 * ($this->largeur + $this->hauteur);
    }
}

// ❌ On NE PEUT PAS instancier une classe abstraite
// $f = new Forme();  // Fatal error: Cannot instantiate abstract class

// ✅ On instancie les classes concrètes
$cercle    = new Cercle(5.0);
$rectangle = new Rectangle(4.0, 6.0);

echo $cercle->decrire() . PHP_EOL;
// Je suis un(e) Cercle de couleur rouge. Surface : 78.54. Périmètre : 31.42.

echo $rectangle->decrire() . PHP_EOL;
// Je suis un(e) Rectangle de couleur bleu. Surface : 24.00. Périmètre : 20.00.
?>
```

---

## 10. Interfaces

Une interface est un **contrat pur** : elle définit QUELLES méthodes une classe doit implémenter, mais pas COMMENT.

```php
<?php
/**
 * Interface : liste de méthodes que les classes DOIVENT implémenter
 * Toutes les méthodes d'une interface sont publiques par défaut
 */
interface Serialisable {
    public function toArray(): array;
    public function toJSON(): string;
}

interface Validable {
    public function valider(): bool;
    public function getErreurs(): array;
}

// Une classe peut implémenter PLUSIEURS interfaces
class Utilisateur implements Serialisable, Validable {
    private array $erreurs = [];

    public function __construct(
        private string $nom,
        private string $email,
        private int    $age
    ) {}

    // Implémentation obligatoire de Serialisable
    public function toArray(): array {
        return [
            'nom'   => $this->nom,
            'email' => $this->email,
            'age'   => $this->age,
        ];
    }

    public function toJSON(): string {
        return json_encode($this->toArray());
    }

    // Implémentation obligatoire de Validable
    public function valider(): bool {
        $this->erreurs = [];

        if (empty($this->nom)) {
            $this->erreurs[] = "Le nom est requis.";
        }
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->erreurs[] = "L'e-mail est invalide.";
        }
        if ($this->age < 0 || $this->age > 150) {
            $this->erreurs[] = "L'âge est invalide.";
        }

        return empty($this->erreurs);
    }

    public function getErreurs(): array {
        return $this->erreurs;
    }
}

$user = new Utilisateur("Alice", "alice@test.com", 25);

if ($user->valider()) {
    echo "Utilisateur valide !" . PHP_EOL;
    echo $user->toJSON() . PHP_EOL;
    // {"nom":"Alice","email":"alice@test.com","age":25}
} else {
    foreach ($user->getErreurs() as $erreur) {
        echo "Erreur : " . $erreur . PHP_EOL;
    }
}
?>
```

**Différences entre classe abstraite et interface :**

| | Classe abstraite | Interface |
|--|-----------------|-----------|
| Instanciation | Non | Non |
| Méthodes concrètes | Oui | Non (PHP < 8, PHP 8+ permet des méthodes par défaut) |
| Propriétés | Oui | Non |
| Héritage | Simple (1 seule) | Multiple (plusieurs) |
| But | Comportement partiel | Contrat pur |

---

## 11. Traits

Un trait est un **ensemble de méthodes réutilisables** qui peut être "injecté" dans des classes sans héritage.

```php
<?php
/**
 * Trait : méthodes réutilisables dans plusieurs classes non liées
 */
trait Horodatage {
    private \DateTime $creeLeDate;
    private \DateTime $modifieLeDate;

    public function initialiserHorodatage(): void {
        $this->creeLeDate   = new \DateTime();
        $this->modifieLeDate = new \DateTime();
    }

    public function marquerCommeModifie(): void {
        $this->modifieLeDate = new \DateTime();
    }

    public function getCreeLe(): string {
        return $this->creeLeDate->format('d/m/Y H:i:s');
    }

    public function getModifieLe(): string {
        return $this->modifieLeDate->format('d/m/Y H:i:s');
    }
}

trait Journalisation {
    private array $journalMessages = [];

    public function journaliser(string $message): void {
        $this->journalMessages[] = date('H:i:s') . " — " . $message;
    }

    public function afficherJournal(): void {
        foreach ($this->journalMessages as $msg) {
            echo $msg . PHP_EOL;
        }
    }
}

class Article {
    use Horodatage, Journalisation;  // Utilisation des deux traits

    public function __construct(
        private string $titre,
        private string $contenu
    ) {
        $this->initialiserHorodatage();
        $this->journaliser("Article créé : {$titre}");
    }

    public function modifier(string $nouveauContenu): void {
        $this->contenu = $nouveauContenu;
        $this->marquerCommeModifie();
        $this->journaliser("Article modifié");
    }
}

$article = new Article("Mon premier article", "Contenu initial...");
sleep(1);
$article->modifier("Contenu mis à jour !");

echo "Créé le : "    . $article->getCreeLe() . PHP_EOL;
echo "Modifié le : " . $article->getModifieLe() . PHP_EOL;
$article->afficherJournal();
?>
```

---

## 12. Polymorphisme

Le polymorphisme permet d'utiliser des objets de types différents de **façon uniforme** via une interface commune.

```php
<?php
interface Payable {
    public function calculerSalaire(): float;
    public function getType(): string;
}

class EmployeTempsPlein implements Payable {
    public function __construct(
        private string $nom,
        private float  $salaireMensuel
    ) {}

    public function calculerSalaire(): float {
        return $this->salaireMensuel;
    }

    public function getType(): string { return "Temps plein"; }
}

class EmployeTempsPartiel implements Payable {
    public function __construct(
        private string $nom,
        private float  $tauxHoraire,
        private float  $heuresTravaillees
    ) {}

    public function calculerSalaire(): float {
        return $this->tauxHoraire * $this->heuresTravaillees;
    }

    public function getType(): string { return "Temps partiel"; }
}

class Freelance implements Payable {
    public function __construct(
        private string $nom,
        private float  $montantFacture
    ) {}

    public function calculerSalaire(): float {
        return $this->montantFacture * 0.75;  // Après charges 25%
    }

    public function getType(): string { return "Freelance"; }
}

// POLYMORPHISME : on traite tous les types de la même façon
function genererBulletinPaie(array $employes): void {
    $totalMasse = 0.0;

    echo "=== BULLETIN DE PAIE ===" . PHP_EOL;
    foreach ($employes as $employe) {
        // Chaque objet est différent mais répond à la même interface Payable
        $salaire    = $employe->calculerSalaire();
        $totalMasse += $salaire;
        printf("%-15s (%s) : %8.2f €%s", 
            $employe->getType(), 
            get_class($employe),
            $salaire,
            PHP_EOL
        );
    }
    echo "Masse salariale totale : " . number_format($totalMasse, 2) . " €" . PHP_EOL;
}

$employes = [
    new EmployeTempsPlein("Alice",   3200.00),
    new EmployeTempsPartiel("Bob",   15.0, 80.0),
    new Freelance("Charlie",         5000.00),
    new EmployeTempsPlein("Diana",   2800.00),
];

genererBulletinPaie($employes);
?>
```

---

## 13. Espaces de noms (namespaces)

Les namespaces évitent les **conflits de noms** entre classes et organisent le code.

```php
<?php
// Fichier : src/Models/User.php
namespace App\Models;

class User {
    public function __construct(
        public int    $id,
        public string $email
    ) {}
}

// Fichier : src/Services/UserService.php
namespace App\Services;

use App\Models\User;  // On importe la classe

class UserService {
    public function creerUtilisateur(string $email): User {
        return new User(1, $email);  // Pas de préfixe namespace nécessaire
    }
}

// Fichier : index.php
use App\Services\UserService;
use App\Models\User as UserModel;  // Alias pour éviter les conflits

$service = new UserService();
$user    = $service->creerUtilisateur("alice@test.com");
echo $user->email;  // alice@test.com
?>
```

---

## 14. Patterns courants en PHP

### Pattern Singleton

```php
<?php
/**
 * Singleton : garantit qu'une classe n'a qu'UNE SEULE instance.
 * Utile pour : configuration, connexion BDD, cache...
 */
class Configuration {
    private static ?Configuration $instance = null;
    private array $parametres = [];

    // Constructeur privé : empêche new Configuration() depuis l'extérieur
    private function __construct() {
        // Chargement de la config...
        $this->parametres = ['env' => 'dev', 'debug' => true];
    }

    public static function getInstance(): static {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function get(string $cle): mixed {
        return $this->parametres[$cle] ?? null;
    }
}

$config1 = Configuration::getInstance();
$config2 = Configuration::getInstance();

var_dump($config1 === $config2);  // true — même instance !
echo $config1->get('env');        // dev
?>
```

---

## 15. Exercices pratiques

### Exercice 1 — Système de gestion de bibliothèque

Créez les classes suivantes :
- `Livre` : titre, auteur, ISBN, disponible (bool)
- `Membre` : nom, email, liste de livres empruntés
- `Bibliotheque` : gère les livres et les membres, permet d'emprunter et de rendre

### Exercice 2 — Formes géométriques

Créez une hiérarchie complète :
- Classe abstraite `Forme` avec `calculerSurface()` et `calculerPerimetre()`
- Classes `Cercle`, `Rectangle`, `Triangle`
- Fonction qui prend un tableau de Forme et affiche celle avec la plus grande surface

---

## 16. Corrigés

### Corrigé Exercice 1 — Bibliothèque

```php
<?php
declare(strict_types=1);

class Livre {
    public function __construct(
        private string $titre,
        private string $auteur,
        private string $isbn,
        private bool   $disponible = true
    ) {}

    public function getTitre(): string     { return $this->titre; }
    public function getISBN(): string      { return $this->isbn; }
    public function estDisponible(): bool  { return $this->disponible; }

    public function emprunter(): void {
        if (!$this->disponible) {
            throw new RuntimeException("Le livre \"{$this->titre}\" n'est pas disponible.");
        }
        $this->disponible = false;
    }

    public function retourner(): void {
        $this->disponible = true;
    }

    public function __toString(): string {
        return "\"{$this->titre}\" par {$this->auteur} (ISBN: {$this->isbn})";
    }
}

class Membre {
    private array $livresEmpruntes = [];

    public function __construct(
        private string $nom,
        private string $email
    ) {}

    public function getNom(): string        { return $this->nom; }
    public function getEmail(): string      { return $this->email; }
    public function getLivres(): array      { return $this->livresEmpruntes; }

    public function ajouterLivre(Livre $livre): void {
        $this->livresEmpruntes[$livre->getISBN()] = $livre;
    }

    public function retirerLivre(string $isbn): void {
        unset($this->livresEmpruntes[$isbn]);
    }
}

class Bibliotheque {
    private array $livres  = [];
    private array $membres = [];

    public function ajouterLivre(Livre $livre): void {
        $this->livres[$livre->getISBN()] = $livre;
    }

    public function inscrireMembre(Membre $membre): void {
        $this->membres[$membre->getEmail()] = $membre;
    }

    public function emprunter(string $emailMembre, string $isbn): void {
        $membre = $this->membres[$emailMembre]
            ?? throw new RuntimeException("Membre introuvable.");
        $livre  = $this->livres[$isbn]
            ?? throw new RuntimeException("Livre introuvable.");

        $livre->emprunter();
        $membre->ajouterLivre($livre);

        echo "✅ {$membre->getNom()} a emprunté : {$livre}" . PHP_EOL;
    }

    public function retourner(string $emailMembre, string $isbn): void {
        $membre = $this->membres[$emailMembre]
            ?? throw new RuntimeException("Membre introuvable.");
        $livre  = $this->livres[$isbn]
            ?? throw new RuntimeException("Livre introuvable.");

        $livre->retourner();
        $membre->retirerLivre($isbn);

        echo "📚 {$membre->getNom()} a rendu : {$livre}" . PHP_EOL;
    }
}

// Test
$biblio = new Bibliotheque();

$biblio->ajouterLivre(new Livre("Le Petit Prince",      "Antoine de Saint-Exupéry", "978-2-07-040850-4"));
$biblio->ajouterLivre(new Livre("L'Étranger",           "Albert Camus",             "978-2-07-036024-5"));

$biblio->inscrireMembre(new Membre("Alice Martin", "alice@test.com"));

$biblio->emprunter("alice@test.com", "978-2-07-040850-4");
$biblio->retourner("alice@test.com", "978-2-07-040850-4");
?>
```

---

## Résumé du module 08

| Concept | Définition | Mot-clé PHP |
|---------|------------|-------------|
| Classe | Modèle/plan | `class` |
| Objet | Instance d'une classe | `new` |
| Héritage | Réutiliser une classe | `extends` |
| Interface | Contrat de méthodes | `interface`, `implements` |
| Classe abstraite | Modèle partiel | `abstract` |
| Trait | Méthodes réutilisables | `trait`, `use` |
| Encapsulation | Contrôle d'accès | `public`, `private`, `protected` |
| `$this` | Instance courante | `$this->` |
| `parent::` | Appel méthode parente | `parent::methode()` |
| `static` | Membre de classe | `static::`, `self::` |

---

**➡️ Module suivant : [Module 09 — Erreurs et exceptions](../09-erreurs-exceptions/README.md)**

🔗 [Retour au sommaire principal](../../README.md)
