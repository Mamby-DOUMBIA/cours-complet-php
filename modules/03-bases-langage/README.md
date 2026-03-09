# Module 03 — Bases du langage PHP

> **Niveau :** Débutant
> **Durée estimée :** 4 à 5 heures
> **Prérequis :** Modules 01 et 02

---

## Table des matières

1. [Syntaxe PHP — Les règles fondamentales](#1-syntaxe-php--les-règles-fondamentales)
2. [Les variables](#2-les-variables)
3. [Les types de données](#3-les-types-de-données)
4. [Les constantes](#4-les-constantes)
5. [Les opérateurs](#5-les-opérateurs)
6. [Priorité des opérateurs](#6-priorité-des-opérateurs)
7. [Conversion de types (Casting)](#7-conversion-de-types-casting)
8. [Erreurs fréquentes des débutants](#8-erreurs-fréquentes-des-débutants)
9. [Bonnes pratiques professionnelles](#9-bonnes-pratiques-professionnelles)
10. [Exercices pratiques](#10-exercices-pratiques)
11. [Corrigés](#11-corrigés)

---

## 1. Syntaxe PHP — Les règles fondamentales

### La structure d'un fichier PHP

```php
<?php
// Le code PHP commence après cette balise

// Instructions PHP...

// Si le fichier est un mélange PHP/HTML,
// on peut fermer la balise PHP :
?>

<!-- HTML ici -->

<?php
// Et rouvrir PHP plus tard
?>
```

### Les règles incontournables

**Règle 1 : Chaque instruction se termine par un point-virgule `;`**

```php
<?php
echo "Bonjour";        // ✅ Correct — avec point-virgule
$nom = "Alice";        // ✅ Correct
$age = 25              // ❌ ERREUR — manque le point-virgule
?>
```

**Règle 2 : PHP est sensible à la casse pour les variables**

```php
<?php
$Nom   = "Alice";
$nom   = "Bob";
$NOM   = "Charlie";

// Ce sont TROIS variables différentes !
echo $Nom;   // Affiche : Alice
echo $nom;   // Affiche : Bob
echo $NOM;   // Affiche : Charlie
?>
```

**Règle 3 : PHP n'est PAS sensible à la casse pour les fonctions et mots-clés**

```php
<?php
// Ces trois lignes font exactement la même chose :
echo "test";
ECHO "test";
Echo "test";

// Idem pour les fonctions native :
$texte = strtoupper("bonjour");  // Fonctionne
$texte = STRTOUPPER("bonjour");  // Fonctionne aussi
?>
```

> 💡 **Bonne pratique** : Même si PHP accepte `ECHO` ou `Echo`, utilisez **toujours les minuscules** pour les fonctions et mots-clés. C'est la convention standard.

**Règle 4 : Les espaces sont ignorés (pour la plupart)**

```php
<?php
// Ces deux lignes sont équivalentes pour PHP :
$resultat=2+3;
$resultat = 2 + 3;

// Mais la deuxième est beaucoup plus lisible !
?>
```

### Les guillemets — Simple vs Double

Cette distinction est **très importante** en PHP :

```php
<?php
$prenom = "Alice";

// Guillemets DOUBLES : les variables sont interprétées
echo "Bonjour $prenom !";        // Affiche : Bonjour Alice !
echo "Bonjour {$prenom} !";      // Affiche : Bonjour Alice ! (avec accolades)

// Guillemets SIMPLES : les variables NE sont PAS interprétées
echo 'Bonjour $prenom !';        // Affiche : Bonjour $prenom !

// Guillemets doubles : caractères d'échappement
echo "Ligne 1\nLigne 2";         // \n = saut de ligne
echo "Tabulation\tici";           // \t = tabulation
echo "Il a dit \"Bonjour\"";     // \" = guillemet dans guillemet
?>
```

**Caractères d'échappement dans les guillemets doubles :**

| Séquence | Signification |
|----------|---------------|
| `\n` | Saut de ligne (newline) |
| `\t` | Tabulation |
| `\\` | Antislash littéral |
| `\"` | Guillemet double littéral |
| `\$` | Dollar littéral (évite l'interprétation) |

---

## 2. Les variables

### Qu'est-ce qu'une variable ?

Une variable est comme une **boîte avec une étiquette** : vous lui donnez un nom (l'étiquette) et vous y rangez une valeur (le contenu de la boîte). À tout moment, vous pouvez changer le contenu de la boîte.

```
     $age
  ┌────────┐
  │   25   │  ← La valeur stockée (peut changer)
  └────────┘
  ↑ L'étiquette (le nom de la variable)
```

### Déclaration et affectation

En PHP, une variable est déclarée avec le symbole `$` suivi de son nom :

```php
<?php
// Déclaration et affectation en une seule opération
$prenom = "Alice";         // Variable de type chaîne de caractères (string)
$age    = 25;              // Variable de type entier (integer)
$taille = 1.68;            // Variable de type décimal (float)
$estMajeur = true;         // Variable de type booléen (boolean)

// Affectation ultérieure — on peut changer la valeur
$prenom = "Bob";           // $prenom vaut maintenant "Bob"
$age    = $age + 1;        // $age vaut maintenant 26

// On peut aussi affecter la valeur d'une variable à une autre
$prenom2 = $prenom;        // $prenom2 vaut "Bob"
?>
```

### Règles de nommage des variables

```php
<?php
// ✅ Noms valides
$nom        = "Alice";         // Lettres minuscules
$Nom        = "Alice";         // Commence par une majuscule
$_nom       = "Alice";         // Commence par un underscore
$nom2       = "Alice";         // Contient un chiffre (pas en première position)
$nomComplet = "Alice Martin";  // camelCase (recommandé)
$nom_complet = "Alice Martin"; // snake_case (aussi accepté)

// ❌ Noms invalides
$2nom    = "Alice";   // ERREUR : ne peut pas commencer par un chiffre
$nom-complet = "Alice"; // ERREUR : tiret interdit (confondu avec soustraction)
$nom complet = "Alice"; // ERREUR : espace interdit
?>
```

**Conventions de nommage :**

| Convention | Exemple | Utilisation recommandée |
|------------|---------|------------------------|
| camelCase | `$nomComplet` | Variables et fonctions |
| snake_case | `$nom_complet` | Variables (alternative) |
| PascalCase | `NomClasse` | Noms de classes uniquement |
| SCREAMING_SNAKE | `MAX_TAILLE` | Constantes |

### Variables prédéfinies (Superglobales)

PHP possède des **variables spéciales** disponibles partout dans votre code :

```php
<?php
// $_SERVER : informations sur le serveur et la requête HTTP
echo $_SERVER['PHP_SELF'];      // Chemin du fichier actuel
echo $_SERVER['HTTP_HOST'];     // Nom d'hôte (ex: localhost)
echo $_SERVER['REQUEST_METHOD']; // GET ou POST
echo $_SERVER['REMOTE_ADDR'];   // Adresse IP du visiteur

// $_GET : données envoyées via l'URL
// URL : http://exemple.com/page.php?nom=Alice&age=25
echo $_GET['nom'];    // Affiche : Alice
echo $_GET['age'];    // Affiche : 25

// $_POST : données envoyées via un formulaire (méthode POST)
echo $_POST['email'];    // Contenu du champ "email" du formulaire

// $_SESSION : données de session (voir Module 12)
// $_COOKIE : cookies (voir Module 12)
// $_FILES : fichiers uploadés (voir Module 10)

// Variables d'environnement utiles
echo PHP_EOL;    // Fin de ligne selon l'OS (\n sur Linux, \r\n sur Windows)
echo PHP_INT_MAX; // Plus grand entier supporté
echo PHP_FLOAT_EPSILON; // Plus petite différence float distinguable
?>
```

### Portée des variables (Scope)

La **portée** d'une variable désigne l'endroit où elle est accessible.

```php
<?php
$message = "Je suis global";  // Variable globale

function direBonjour() {
    // ❌ ERREUR : $message n'est PAS accessible ici directement !
    // echo $message;  // Notice: Undefined variable

    // ✅ Pour accéder à une variable globale dans une fonction :
    global $message;
    echo $message;  // Affiche : Je suis global
}

function autreExemple() {
    $locale = "Je suis locale";  // Variable locale à cette fonction
    echo $locale;  // ✅ Fonctionne
}

autreExemple();
// echo $locale;  // ❌ ERREUR : $locale n'existe plus hors de la fonction
?>
```

> ⚠️ **Bonne pratique** : Évitez d'utiliser `global` dans les fonctions. C'est généralement signe d'une mauvaise conception. Préférez passer les variables en **paramètres** (voir Module 05).

---

## 3. Les types de données

PHP est un langage à **typage dynamique** : vous n'avez pas besoin de déclarer le type d'une variable, PHP le détermine automatiquement selon la valeur assignée.

### Les 8 types primitifs PHP

```
Types scalaires (valeur unique) :
├── boolean  : true / false
├── integer  : 42, -10, 0
├── float    : 3.14, -0.5
└── string   : "Bonjour"

Types composés :
├── array    : [1, 2, 3] ou ["clé" => "valeur"]
└── object   : instance d'une classe

Types spéciaux :
├── null     : absence de valeur
└── resource : référence à une ressource externe (fichier, BDD)
```

### 3.1 — Les booléens (boolean)

Un booléen ne peut avoir que **deux valeurs** : `true` (vrai) ou `false` (faux).

```php
<?php
// Déclaration de booléens
$estActif     = true;
$estSupprime  = false;
$estMajeur    = true;

// PHP est insensible à la casse pour true/false
$test1 = TRUE;   // identique à true
$test2 = False;  // identique à false

// Affichage d'un booléen avec echo
echo $estActif;    // Affiche : 1 (PHP convertit true en "1")
echo $estSupprime; // Affiche : (rien — PHP convertit false en "")

// Affichage plus explicite
var_dump($estActif);   // Affiche : bool(true)
var_dump($estSupprime); // Affiche : bool(false)

// Valeurs "fausses" (falsy) en PHP :
// false, 0, 0.0, "", "0", [], null
// TOUT LE RESTE est considéré comme vrai (truthy)
if (0) {
    echo "0 est vrai";  // Ne s'affiche PAS
}
if ("") {
    echo "chaîne vide est vraie";  // Ne s'affiche PAS
}
if ("0") {
    echo "'0' est vrai";  // Ne s'affiche PAS non plus !
}
if ("00") {
    echo "'00' est vrai";  // S'AFFICHE (la chaîne n'est pas "0")
}
?>
```

### 3.2 — Les entiers (integer / int)

Un entier est un nombre **sans partie décimale**.

```php
<?php
// Différentes façons de déclarer un entier
$decimal     = 42;          // Notation décimale (base 10) — la plus courante
$negatif     = -15;         // Entier négatif
$octal       = 0755;        // Notation octale (commence par 0) = 493 en décimal
$hexadecimal = 0xFF;        // Notation hexadécimale (commence par 0x) = 255
$binaire     = 0b11001100;  // Notation binaire (commence par 0b) — PHP 5.4+
$gros_nombre = 1_000_000;   // Séparateur de milliers — PHP 7.4+

// Affichage
echo $decimal;       // Affiche : 42
echo $hexadecimal;   // Affiche : 255 (converti en décimal pour l'affichage)
echo $gros_nombre;   // Affiche : 1000000

// Limites des entiers
echo PHP_INT_MAX;    // Affiche la valeur maximale (9223372036854775807 en 64 bits)
echo PHP_INT_MIN;    // Affiche la valeur minimale (-9223372036854775808)
echo PHP_INT_SIZE;   // Affiche la taille en octets (8 en 64 bits)

// Vérifier si une variable est un entier
var_dump(is_int(42));       // bool(true)
var_dump(is_int("42"));     // bool(false) — "42" est une chaîne !
var_dump(is_integer(42));   // bool(true) — alias de is_int
?>
```

### 3.3 — Les flottants (float / double)

Un flottant est un nombre avec une **partie décimale** (virgule flottante).

```php
<?php
$prix           = 19.99;       // Notation décimale
$taux_tva       = 0.20;        // Vingt pourcent
$notation_sci   = 1.5e3;       // Notation scientifique : 1500.0
$notation_sci2  = 1.5E-3;      // Notation scientifique : 0.0015

// Attention aux problèmes de précision des flottants !
// C'est une limitation mathématique, pas un bug PHP
$a = 0.1 + 0.2;
echo $a;           // Affiche : 0.3 (mais...)
var_dump($a == 0.3); // bool(false) !!! C'est faux !

// Explication : en binaire, 0.1 et 0.2 ne peuvent pas être représentés
// exactement, ce qui crée des imprécisions infimes.

// ✅ Solution : utiliser une comparaison avec epsilon (tolérance)
$epsilon = 0.00001;
if (abs($a - 0.3) < $epsilon) {
    echo "Les valeurs sont égales (à epsilon près)";
}

// Pour les calculs financiers, utilisez la bibliothèque BCMath
$resultat = bcadd("0.1", "0.2", 2);  // Résultat : "0.30" (exact)
echo $resultat;

// Fonctions utiles pour les flottants
echo ceil(4.2);    // Arrondit vers le haut  : 5
echo floor(4.9);   // Arrondit vers le bas   : 4
echo round(4.5);   // Arrondit normalement   : 5
echo round(4.567, 2); // Arrondit à 2 décimales : 4.57
echo number_format(1234567.891, 2, ',', ' ');  // Affiche : 1 234 567,89
?>
```

### 3.4 — Les chaînes de caractères (string)

Une chaîne de caractères est une **suite de caractères** (texte).

```php
<?php
// Déclaration de chaînes
$simple     = 'Bonjour';              // Guillemets simples
$double     = "Bonjour";              // Guillemets doubles
$avec_var   = "Bonjour $simple";      // Interpolation de variable
$multilignes = "Première ligne\nDeuxième ligne"; // Avec saut de ligne

// HEREDOC : chaîne multiligne avec interpolation
$prenom = "Alice";
$heredoc = <<<EOT
    Bonjour $prenom,
    Ceci est un texte
    sur plusieurs lignes.
EOT;

// NOWDOC : comme heredoc mais SANS interpolation (comme guillemets simples)
$nowdoc = <<<'EOT'
    Bonjour $prenom,  (le $prenom ne sera PAS remplacé)
    Texte sans interpolation.
EOT;

echo $heredoc;
echo $nowdoc;

// Longueur d'une chaîne
$texte = "Bonjour le monde !";
echo strlen($texte);           // Affiche : 19 (nombre d'octets)
echo mb_strlen($texte);        // Affiche : 19 (nombre de caractères Unicode)

// Accès à un caractère par son index (commence à 0)
echo $texte[0];    // Affiche : B (premier caractère)
echo $texte[7];    // Affiche : l
echo $texte[-1];   // Affiche : ! (dernier caractère)

// Concaténation avec l'opérateur point "."
$prenom  = "Alice";
$message = "Bonjour " . $prenom . " !";  // "Bonjour Alice !"
echo $message;
?>
```

### 3.5 — Null

`null` représente l'**absence de valeur**.

```php
<?php
// Une variable est null si :
$a = null;                     // 1. On l'assigne explicitement à null
$b;                            // 2. Elle est déclarée sans valeur (Notice en PHP 8)
// unset($variable) la rend null

// Vérifier si une valeur est null
var_dump(is_null($a));         // bool(true)
var_dump($a === null);         // bool(true) — comparaison stricte recommandée

// isset() retourne false si la variable est null ou n'existe pas
echo isset($a) ? "définie" : "non définie";  // Affiche : non définie
echo isset($b) ? "définie" : "non définie";  // Affiche : non définie
?>
```

### La fonction var_dump()

`var_dump()` est votre meilleure amie pour déboguer : elle affiche le **type ET la valeur** d'une variable.

```php
<?php
$prenom  = "Alice";
$age     = 25;
$taille  = 1.68;
$actif   = true;
$rien    = null;

var_dump($prenom);   // string(5) "Alice"
var_dump($age);      // int(25)
var_dump($taille);   // float(1.68)
var_dump($actif);    // bool(true)
var_dump($rien);     // NULL

// Afficher plusieurs variables en même temps
var_dump($prenom, $age, $actif);

// print_r() — affichage moins verbeux, utile pour les tableaux
print_r(["Alice", 25, true]);
// Array ( [0] => Alice [1] => 25 [2] => 1 )
?>
```

---

## 4. Les constantes

Une constante est comme une variable, mais sa valeur **ne peut pas changer** une fois définie.

```php
<?php
// Définir une constante avec define()
define('TVA', 0.20);            // Taux de TVA
define('NOM_SITE', 'MonSite');   // Nom du site
define('MAX_CONNEXIONS', 100);   // Valeur maximale de connexions

// Utilisation (sans le signe $)
echo TVA;             // Affiche : 0.2
echo NOM_SITE;        // Affiche : MonSite
echo MAX_CONNEXIONS;  // Affiche : 100

// ❌ On ne peut pas modifier une constante
// TVA = 0.10;  // ERREUR fatale !

// PHP 5.3+ : définir une constante avec const (dans le corps principal ou une classe)
const VERSION_APP = "2.1.0";
echo VERSION_APP;  // Affiche : 2.1.0

// Vérifier si une constante existe
if (defined('TVA')) {
    echo "La constante TVA est définie";
}

// Constantes prédéfinies de PHP
echo PHP_VERSION;         // Version de PHP (ex: "8.2.0")
echo PHP_OS;              // OS du serveur (ex: "Linux")
echo PHP_MAJOR_VERSION;   // Numéro de version majeur (ex: 8)
echo PHP_EOL;             // Fin de ligne selon l'OS
echo PHP_INT_MAX;         // Plus grand entier

// Constantes magiques (valeur change selon le contexte)
echo __FILE__;    // Chemin absolu du fichier actuel
echo __LINE__;    // Numéro de ligne actuel
echo __DIR__;     // Répertoire du fichier actuel
echo __FUNCTION__; // Nom de la fonction actuelle
echo __CLASS__;    // Nom de la classe actuelle
?>
```

**Quand utiliser une constante plutôt qu'une variable ?**

- Valeurs qui **ne changent jamais** : taux de TVA, nom du site, clé API
- **Configuration** de l'application : chemin de la base de données, etc.
- **Messages** fixes : textes d'erreur standards

---

## 5. Les opérateurs

### 5.1 — Opérateurs arithmétiques

```php
<?php
$a = 10;
$b = 3;

echo $a + $b;    // Addition       : 13
echo $a - $b;    // Soustraction   : 7
echo $a * $b;    // Multiplication : 30
echo $a / $b;    // Division       : 3.333...
echo $a % $b;    // Modulo (reste) : 1  (10 = 3×3 + 1)
echo $a ** $b;   // Puissance      : 1000 (10³)

// Division entière (PHP 7+)
echo intdiv(10, 3);  // 3 (ignore la partie décimale)

// Opérateurs d'incrémentation / décrémentation
$compteur = 5;
$compteur++;    // Post-incrémentation : $compteur vaut 6
++$compteur;    // Pré-incrémentation  : $compteur vaut 7
$compteur--;    // Post-décrémentation : $compteur vaut 6
--$compteur;    // Pré-décrémentation  : $compteur vaut 5

// Différence entre pré et post :
$x = 5;
echo $x++;   // Affiche 5, PUIS incrémente à 6
echo $x;     // Affiche 6

$y = 5;
echo ++$y;   // Incrémente à 6, PUIS affiche 6
echo $y;     // Affiche 6
?>
```

### 5.2 — Opérateurs d'affectation

```php
<?php
$a = 10;

// Affectation simple
$a = 10;       // $a = 10

// Affectations combinées
$a += 5;       // équivalent à $a = $a + 5  → $a = 15
$a -= 3;       // équivalent à $a = $a - 3  → $a = 12
$a *= 2;       // équivalent à $a = $a * 2  → $a = 24
$a /= 4;       // équivalent à $a = $a / 4  → $a = 6
$a %= 4;       // équivalent à $a = $a % 4  → $a = 2
$a **= 3;      // équivalent à $a = $a ** 3 → $a = 8

// Affectation pour chaînes
$texte  = "Bonjour";
$texte .= " Alice";   // équivalent à $texte = $texte . " Alice"
echo $texte;           // Affiche : Bonjour Alice

// Opérateur de coalescence null (PHP 7+)
$valeur = null;
$resultat = $valeur ?? "Valeur par défaut";
echo $resultat;  // Affiche : Valeur par défaut

// Équivalent à :
$resultat = isset($valeur) ? $valeur : "Valeur par défaut";
?>
```

### 5.3 — Opérateurs de comparaison

C'est une des parties les plus **importantes** à bien comprendre en PHP.

```php
<?php
$a = 5;
$b = 10;
$c = "5";    // Chaîne de caractères

// == Égalité simple (avec conversion de type)
var_dump($a == 5);      // bool(true)
var_dump($a == "5");    // bool(true) !! PHP convertit "5" en entier 5

// === Égalité stricte (type ET valeur)
var_dump($a === 5);     // bool(true)  — même type (int) et même valeur
var_dump($a === "5");   // bool(false) — types différents (int vs string)

// != Inégalité simple
var_dump($a != $b);     // bool(true)
var_dump($a != "5");    // bool(false) !! (car $a == "5" avec conversion)

// !== Inégalité stricte
var_dump($a !== "5");   // bool(true) — types différents

// Comparaisons numériques
var_dump($a < $b);      // bool(true)  — 5 est inférieur à 10
var_dump($a > $b);      // bool(false) — 5 n'est pas supérieur à 10
var_dump($a <= 5);      // bool(true)  — 5 est inférieur ou égal à 5
var_dump($a >= 10);     // bool(false) — 5 n'est pas supérieur ou égal à 10

// Opérateur spaceship <=> (PHP 7+)
// Retourne -1, 0, ou 1 selon si gauche < = > droite
echo (5 <=> 10);   // -1  (5 est inférieur à 10)
echo (10 <=> 10);  //  0  (égaux)
echo (15 <=> 10);  //  1  (15 est supérieur à 10)
// Très utile pour les fonctions de tri
?>
```

> ⚠️ **Règle d'or** : Utilisez **presque toujours** `===` et `!==` (comparaison **stricte**) plutôt que `==` et `!=`. La comparaison lâche (`==`) peut entraîner des comportements inattendus et des bugs difficiles à trouver.

### 5.4 — Opérateurs logiques

```php
<?php
$age       = 25;
$aPaie     = true;
$estBanni  = false;

// && (ET) : true si LES DEUX conditions sont vraies
if ($age >= 18 && $aPaie) {
    echo "Accès autorisé";  // S'affiche
}

// || (OU) : true si AU MOINS UNE condition est vraie
if ($age < 18 || $estBanni) {
    echo "Accès refusé";    // Ne s'affiche pas
}

// ! (NON) : inverse la valeur booléenne
if (!$estBanni) {
    echo "Pas banni";  // S'affiche
}

// Opérateurs alternatifs (moins utilisés, priorité différente)
// and, or, not
// Évitez-les en général, préférez &&, ||, !

// Exemple pratique
function peutAcceder($age, $aCompte, $estBanni) {
    return ($age >= 18) && $aCompte && !$estBanni;
}

var_dump(peutAcceder(25, true, false));  // bool(true)
var_dump(peutAcceder(16, true, false));  // bool(false) — mineur
var_dump(peutAcceder(25, true, true));   // bool(false) — banni
?>
```

### 5.5 — Opérateurs de chaînes

```php
<?php
// Concaténation avec le point "."
$prenom = "Alice";
$nom    = "Martin";
$complet = $prenom . " " . $nom;  // "Alice Martin"

// Concaténation-affectation
$message = "Bonjour";
$message .= " le monde";  // "Bonjour le monde"

// Répétition de chaîne
$separateur = str_repeat("-", 30);  // "------------------------------"
echo $separateur;
?>
```

---

## 6. Priorité des opérateurs

Comme en mathématiques, certains opérateurs ont une **priorité plus élevée** que d'autres.

```php
<?php
// Sans parenthèses : multiplication avant addition
echo 2 + 3 * 4;    // 14 (pas 20) — multiplication d'abord

// Avec parenthèses : les parenthèses ont la priorité absolue
echo (2 + 3) * 4;  // 20

// Exemple plus complexe
$a = true;
$b = false;
$c = true;

// && a une priorité plus élevée que ||
echo var_export($a || $b && $c, true);  // true (b && c = false, a || false = true)
echo var_export(($a || $b) && $c, true); // true ((true) && true = true)

// ✅ Bonne pratique : utilisez TOUJOURS des parenthèses pour clarifier
// N'essayez pas de mémoriser toutes les priorités !
$resultat = ($prix * $quantite) + ($livraison * $nb_colis);
?>
```

---

## 7. Conversion de types (Casting)

PHP peut convertir des valeurs d'un type à un autre, soit automatiquement (**transtypage implicite**), soit manuellement (**cast explicite**).

```php
<?php
// Cast explicite : (type) valeur
$nombre    = 42;
$texte     = "123.45 euros";
$decimal   = 3.99;
$booleen   = true;

// Vers entier (int)
$a = (int) $texte;     // 123  (PHP prend les chiffres en début de chaîne)
$b = (int) $decimal;   // 3    (la partie décimale est tronquée, pas arrondie)
$c = (int) $booleen;   // 1    (true → 1, false → 0)
$d = (int) "abc";      // 0    (chaîne sans chiffres → 0)

// Vers float
$e = (float) "3.14abc"; // 3.14

// Vers chaîne (string)
$f = (string) 42;      // "42"
$g = (string) true;    // "1"
$h = (string) false;   // ""
$i = (string) null;    // ""
$j = (string) 3.14;    // "3.14"

// Vers booléen (bool)
$k = (bool) 1;         // true
$l = (bool) 0;         // false
$m = (bool) "";        // false
$n = (bool) "bonjour"; // true
$o = (bool) null;      // false
$p = (bool) [];        // false (tableau vide)

// Fonctions de conversion (alternative au cast)
$q = intval("42abc");      // 42
$r = floatval("3.14abc");  // 3.14
$s = strval(42);           // "42"
$t = boolval("1");         // true

// settype() modifie la variable en place
$variable = "42";
settype($variable, "integer");
var_dump($variable);  // int(42)
?>
```

---

## 8. Erreurs fréquentes des débutants

### ❌ Erreur 1 : Utiliser `==` au lieu de `===`

```php
<?php
$id = 0;

// ❌ Dangereux !
if ($id == false) {
    echo "Problème : 0 == false est vrai !";  // S'affiche !
}

// ✅ Sûr
if ($id === false) {
    echo "Ceci ne s'affiche pas";
}
if ($id === 0) {
    echo "Correct : $id vaut bien 0";  // S'affiche
}
?>
```

### ❌ Erreur 2 : Confondre `=` (affectation) et `==` (comparaison)

```php
<?php
$note = 15;

// ❌ ERREUR LOGIQUE : on affecte 20 à $note au lieu de comparer !
if ($note = 20) {  // Cette condition est TOUJOURS vraie !
    echo "Note = 20 (mais on vient d'affecter 20 à $note !)";
}
echo $note;  // Affiche 20 (la valeur a été modifiée !)

// ✅ Correct : comparaison
$note = 15;
if ($note == 20) {
    echo "La note est 20";  // Ne s'affiche pas
}
echo $note;  // Affiche 15 (non modifié)
?>
```

### ❌ Erreur 3 : Diviser par zéro

```php
<?php
$diviseur = 0;

// ❌ Sans vérification : erreur fatale ou warning
$resultat = 10 / $diviseur;  // Warning: Division by zero

// ✅ Avec vérification
if ($diviseur !== 0) {
    $resultat = 10 / $diviseur;
    echo $resultat;
} else {
    echo "Erreur : division par zéro impossible";
}
?>
```

---

## 9. Bonnes pratiques professionnelles

### Déclarez vos types avec PHP 8

```php
<?php
declare(strict_types=1);  // ← À mettre TOUT EN HAUT de chaque fichier PHP

// Avec strict_types, PHP ne fera plus de conversion implicite
// et lèvera une erreur si le mauvais type est passé à une fonction typée

function additionner(int $a, int $b): int {
    return $a + $b;
}

echo additionner(5, 3);       // ✅ 8
// echo additionner(5, "3");  // ❌ TypeError avec strict_types=1
?>
```

### Nommez vos variables de façon parlante

```php
<?php
// ❌ Mauvais : que font ces variables ?
$a = 1.99;
$b = 3;
$c = $a * $b;

// ✅ Bon : le code se lit comme du texte
$prix_unitaire   = 1.99;
$quantite        = 3;
$prix_total      = $prix_unitaire * $quantite;
?>
```

---

## 10. Exercices pratiques

### Exercice 1 — Calcul de prix TTC

Créez un script `calcul-prix.php` qui :
- Déclare un prix hors taxe de 49.90€
- Déclare un taux de TVA de 20%
- Calcule le prix TTC
- Affiche le résultat formaté avec 2 décimales

### Exercice 2 — Vérificateur de types

Créez un script `types.php` qui déclare une variable de chaque type et affiche pour chacune : la valeur, le type (avec `gettype()`), et le résultat de `var_dump()`.

### Exercice 3 — Comparaisons

Créez un script `comparaisons.php` et testez les comparaisons entre `0`, `""`, `"0"`, `false`, `null`. Affichez le résultat de chaque comparaison avec `==` et `===`.

---

## 11. Corrigés

### Corrigé Exercice 1

```php
<?php
declare(strict_types=1);

/**
 * Calcul de prix TTC
 * Démontre l'utilisation des variables, constantes et opérateurs
 */

// Définir le taux de TVA comme constante (ne change jamais)
const TAUX_TVA = 0.20;  // 20%

// Prix hors taxe
$prix_ht = 49.90;

// Calcul du montant de la TVA
$montant_tva = $prix_ht * TAUX_TVA;

// Calcul du prix TTC
$prix_ttc = $prix_ht + $montant_tva;
// Ou plus directement : $prix_ttc = $prix_ht * (1 + TAUX_TVA);

// Affichage formaté
echo "=== Détail du prix ===" . PHP_EOL;
echo "Prix HT  : " . number_format($prix_ht, 2, ',', ' ') . " €" . PHP_EOL;
echo "TVA 20%  : " . number_format($montant_tva, 2, ',', ' ') . " €" . PHP_EOL;
echo "Prix TTC : " . number_format($prix_ttc, 2, ',', ' ') . " €" . PHP_EOL;
?>
```

**Résultat :**
```
=== Détail du prix ===
Prix HT  : 49,90 €
TVA 20%  : 9,98 €
Prix TTC : 59,88 €
```

### Corrigé Exercice 2

```php
<?php
/**
 * Démonstration des types de données PHP
 */

$variables = [
    'Chaîne'   => "Bonjour le monde",
    'Entier'   => 42,
    'Flottant' => 3.14,
    'Booléen'  => true,
    'Null'     => null,
];

echo "<table border='1' cellpadding='8'>";
echo "<tr><th>Nom</th><th>Valeur</th><th>Type (gettype)</th></tr>";

foreach ($variables as $nom => $valeur) {
    $valeur_affichee = var_export($valeur, true);  // true = retourner au lieu d'afficher
    $type = gettype($valeur);

    echo "<tr>";
    echo "<td>" . htmlspecialchars($nom) . "</td>";
    echo "<td>" . htmlspecialchars($valeur_affichee) . "</td>";
    echo "<td>" . $type . "</td>";
    echo "</tr>";
}

echo "</table>";
?>
```

---

## Résumé du module 03

| Concept | Points clés |
|---------|------------|
| Variables | `$nom`, sensibles à la casse, pas de type déclaré |
| Types | boolean, integer, float, string, array, object, null |
| Constantes | `define()` ou `const`, pas de `$`, ne changent pas |
| `==` vs `===` | Préférez TOUJOURS `===` (comparaison stricte) |
| Casting | `(int)`, `(float)`, `(string)`, `(bool)` |
| `var_dump()` | Votre outil de débogage principal |

---

**➡️ Module suivant : [Module 04 — Structures de contrôle](../04-structures-controle/README.md)**

🔗 [Retour au sommaire principal](../../README.md)
