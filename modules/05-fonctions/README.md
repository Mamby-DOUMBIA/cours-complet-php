# Module 05 — Fonctions en PHP

> **Niveau :** Débutant → Intermédiaire
> **Durée estimée :** 4 à 5 heures
> **Prérequis :** Modules 01 à 04

---

## Table des matières

1. [Qu'est-ce qu'une fonction ?](#1-quest-ce-quune-fonction-)
2. [Définir et appeler une fonction](#2-définir-et-appeler-une-fonction)
3. [Les paramètres](#3-les-paramètres)
4. [Les valeurs de retour](#4-les-valeurs-de-retour)
5. [Le typage des fonctions (PHP 7+)](#5-le-typage-des-fonctions-php-7)
6. [Portée des variables (scope)](#6-portée-des-variables-scope)
7. [Fonctions récursives](#7-fonctions-récursives)
8. [Fonctions anonymes (closures)](#8-fonctions-anonymes-closures)
9. [Fonctions fléchées (arrow functions)](#9-fonctions-fléchées-arrow-functions)
10. [Fonctions de rappel (callbacks)](#10-fonctions-de-rappel-callbacks)
11. [Fonctions intégrées utiles](#11-fonctions-intégrées-utiles)
12. [Erreurs fréquentes](#12-erreurs-fréquentes)
13. [Bonnes pratiques](#13-bonnes-pratiques)
14. [Exercices pratiques](#14-exercices-pratiques)
15. [Corrigés](#15-corrigés)

---

## 1. Qu'est-ce qu'une fonction ?

### Analogie simple

Imaginez une **machine à café** : vous appuyez sur un bouton (vous l'appelez), vous pouvez choisir le type de café (paramètre), et elle vous donne un café (valeur de retour). Vous n'avez pas besoin de savoir comment la machine fonctionne en interne.

Une fonction, c'est pareil : c'est un **bloc de code nommé**, qu'on peut réutiliser plusieurs fois, qui peut recevoir des données (paramètres) et en retourner (valeur de retour).

### Pourquoi utiliser des fonctions ?

**Principe DRY : Don't Repeat Yourself (Ne vous répétez pas)**

```php
<?php
// ❌ SANS fonctions : code répétitif (à éviter)
$prix1 = 50.0;
$tva1  = $prix1 * 0.20;
$ttc1  = $prix1 + $tva1;
echo number_format($ttc1, 2) . " €";

$prix2 = 120.0;
$tva2  = $prix2 * 0.20;
$ttc2  = $prix2 + $tva2;
echo number_format($ttc2, 2) . " €";

// ✅ AVEC une fonction : réutilisable
function calculerPrixTTC(float $prixHT, float $tauxTVA = 0.20): string {
    $ttc = $prixHT * (1 + $tauxTVA);
    return number_format($ttc, 2) . " €";
}

echo calculerPrixTTC(50.0);    // "60.00 €"
echo calculerPrixTTC(120.0);   // "144.00 €"
echo calculerPrixTTC(200.0, 0.10);  // "220.00 €" (TVA 10%)
?>
```

---

## 2. Définir et appeler une fonction

### Syntaxe

```php
<?php
// Définition d'une fonction
function nomDeLaFonction() {
    // Code à exécuter
    echo "Bonjour depuis la fonction !";
}

// Appel de la fonction
nomDeLaFonction();  // Affiche : Bonjour depuis la fonction !

// On peut l'appeler plusieurs fois
nomDeLaFonction();
nomDeLaFonction();
?>
```

### Convention de nommage

```php
<?php
// ✅ Convention camelCase (recommandée pour les fonctions PHP)
function calculerTotal() { ... }
function afficherMessage() { ... }
function validerEmail() { ... }

// ✅ Convention snake_case (aussi acceptée)
function calculer_total() { ... }
function afficher_message() { ... }

// ❌ À éviter
function CalculerTotal() { ... }  // PascalCase (réservé aux classes)
function ct() { ... }              // Trop court, pas parlant
function calculateTheTotalPriceOfAllProducts() { ... }  // Trop long
?>
```

### Fonctions en PHP : flexibilité de position

```php
<?php
// En PHP, une fonction peut être appelée AVANT d'être définie
// (contrairement à JavaScript avec les function expressions)

direBonjour();  // ← Appel AVANT la définition (fonctionne !)

function direBonjour() {
    echo "Bonjour !";
}
?>
```

---

## 3. Les paramètres

Les paramètres permettent de **passer des données** à une fonction.

### Paramètres simples

```php
<?php
// Définir une fonction avec des paramètres
function direBonjour(string $prenom) {
    echo "Bonjour, " . $prenom . " !";
}

// Appel avec un argument
direBonjour("Alice");   // Bonjour, Alice !
direBonjour("Bob");     // Bonjour, Bob !
```

### Valeurs par défaut

```php
<?php
// Un paramètre avec une valeur par défaut est OPTIONNEL à l'appel
function saluer(string $prenom, string $salutation = "Bonjour") {
    echo $salutation . ", " . $prenom . " !<br>";
}

saluer("Alice");              // Bonjour, Alice !   (valeur par défaut)
saluer("Bob", "Bonsoir");     // Bonsoir, Bob !     (valeur fournie)
saluer("Charlie", "Salut");   // Salut, Charlie !

// ✅ Règle importante : les paramètres avec valeurs par défaut
// doivent TOUJOURS être placés EN DERNIER
function creerArticle(string $titre, string $contenu, string $statut = "brouillon") {
    // $statut a une valeur par défaut, donc optionnel
}
?>
```

### Passage par référence

Par défaut, PHP passe les arguments **par valeur** (une copie). Avec `&`, on passe par **référence** (la variable originale).

```php
<?php
// Passage par VALEUR (défaut) — la variable originale n'est PAS modifiée
function doublerValeur(int $nombre) {
    $nombre *= 2;  // On modifie la COPIE, pas l'original
}

$x = 5;
doublerValeur($x);
echo $x;  // Affiche : 5 (non modifié)

// Passage par RÉFÉRENCE avec & — la variable originale EST modifiée
function doublerParReference(int &$nombre) {
    $nombre *= 2;  // On modifie l'ORIGINAL
}

$y = 5;
doublerParReference($y);
echo $y;  // Affiche : 10 (modifié !)

// Cas d'usage réel : la fonction sort() de PHP trie un tableau en place
$fruits = ["cerise", "banane", "abricot"];
sort($fruits);  // sort() modifie $fruits directement (passage par référence interne)
print_r($fruits);  // ["abricot", "banane", "cerise"]
?>
```

### Nombre variable de paramètres

```php
<?php
// PHP 5.6+ : opérateur splat "..."
function somme(int ...$nombres): int {
    $total = 0;
    foreach ($nombres as $nombre) {
        $total += $nombre;
    }
    return $total;
}

echo somme(1, 2, 3);           // 6
echo somme(10, 20, 30, 40);    // 100
echo somme(5);                  // 5

// Arguments nommés (PHP 8.0+)
function creerBalise(string $tag, string $contenu, string $classe = "") {
    $attr = $classe ? " class=\"$classe\"" : "";
    return "<$tag$attr>$contenu</$tag>";
}

// Appel classique
echo creerBalise("p", "Bonjour", "intro");

// Appel avec arguments nommés — l'ordre n'a plus d'importance
echo creerBalise(contenu: "Bonjour", tag: "p", classe: "intro");
echo creerBalise(tag: "h1", contenu: "Titre");  // classe omise
?>
```

---

## 4. Les valeurs de retour

Une fonction peut **retourner** une valeur avec l'instruction `return`.

```php
<?php
// Sans return : la fonction fait quelque chose mais ne retourne rien
function afficherMessage(string $msg): void {
    echo $msg;
    // Pas de return (ou return; sans valeur)
}

// Avec return : la fonction calcule et retourne un résultat
function additionner(int $a, int $b): int {
    return $a + $b;  // La fonction s'arrête ici et retourne la somme
}

$resultat = additionner(5, 3);
echo $resultat;  // 8

// On peut aussi utiliser directement le retour
echo additionner(10, 20);  // 30

// ⚠️ Après return, le reste de la fonction n'est PAS exécuté
function test(): int {
    echo "Avant return<br>";
    return 42;
    echo "Après return<br>";  // ← Cette ligne n'est JAMAIS exécutée !
}

$val = test();  // Affiche "Avant return"
echo $val;      // Affiche 42

// Retourner plusieurs valeurs avec un tableau
function divisionAvecReste(int $a, int $b): array {
    return [
        'quotient' => intdiv($a, $b),
        'reste'    => $a % $b,
    ];
}

$resultat = divisionAvecReste(17, 5);
echo "17 ÷ 5 = " . $resultat['quotient'] . " reste " . $resultat['reste'];
// 17 ÷ 5 = 3 reste 2

// Destructuration du tableau retourné (PHP 7.1+)
['quotient' => $q, 'reste' => $r] = divisionAvecReste(22, 7);
echo "Quotient : $q, Reste : $r";
?>
```

---

## 5. Le typage des fonctions (PHP 7+)

PHP 7 a introduit le **typage strict** des fonctions, une excellente pratique professionnelle.

```php
<?php
declare(strict_types=1);  // Active le mode strict

// Types disponibles pour les paramètres et retours :
// int, float, string, bool, array, object, null
// callable, iterable, mixed (PHP 8), never (PHP 8.1)
// Classe ou interface spécifique

function calculerIMC(float $poids, float $taille): float {
    // IMC = poids (kg) / taille² (m)
    return $poids / ($taille ** 2);
}

$imc = calculerIMC(70.0, 1.75);
echo round($imc, 1);  // Affiche : 22.9

// Type de retour nullable avec ? devant le type
function chercherUtilisateur(int $id): ?array {
    // Retourne soit un tableau, soit null
    $utilisateurs = [
        1 => ["nom" => "Alice", "email" => "alice@test.com"],
        2 => ["nom" => "Bob",   "email" => "bob@test.com"],
    ];

    return $utilisateurs[$id] ?? null;  // null si non trouvé
}

$user = chercherUtilisateur(1);
if ($user !== null) {
    echo "Utilisateur trouvé : " . $user['nom'];
} else {
    echo "Utilisateur introuvable";
}

// Union types (PHP 8.0) : plusieurs types possibles
function traiter(int|string $valeur): string {
    if (is_int($valeur)) {
        return "Entier : " . $valeur;
    }
    return "Chaîne : " . $valeur;
}
?>
```

---

## 6. Portée des variables (scope)

```php
<?php
$global = "Je suis global";

function test() {
    // ❌ $global n'est PAS accessible ici
    // echo $global;  // Notice: Undefined variable

    $local = "Je suis local";
    echo $local;  // ✅ Fonctionne
}

test();
// echo $local;  // ❌ $local n'existe pas ici

// ✅ Solution 1 : Passer la variable en paramètre (RECOMMANDÉ)
function afficherMessage(string $message): void {
    echo $message;
}
afficherMessage($global);

// ✅ Solution 2 : Mot-clé global (DÉCONSEILLÉ, à éviter)
function mauvaisExemple(): void {
    global $global;  // On "importe" la variable globale
    echo $global;    // Fonctionne, mais c'est une mauvaise pratique
}

// Variables statiques : conservent leur valeur entre les appels
function compteur(): int {
    static $compte = 0;  // Initialisé UNE SEULE FOIS au premier appel
    $compte++;
    return $compte;
}

echo compteur();  // 1
echo compteur();  // 2
echo compteur();  // 3  ← la valeur est conservée !
?>
```

---

## 7. Fonctions récursives

Une fonction **récursive** s'appelle elle-même.

```php
<?php
/**
 * Calcule la factorielle d'un nombre.
 * Factorielle de 5 (noté 5!) = 5 × 4 × 3 × 2 × 1 = 120
 */
function factorielle(int $n): int {
    // CAS DE BASE : condition d'arrêt (obligatoire !)
    if ($n <= 1) {
        return 1;
    }
    // APPEL RÉCURSIF : la fonction s'appelle elle-même avec n-1
    return $n * factorielle($n - 1);
}

// Trace d'exécution pour factorielle(4) :
// factorielle(4) = 4 × factorielle(3)
//                = 4 × 3 × factorielle(2)
//                = 4 × 3 × 2 × factorielle(1)
//                = 4 × 3 × 2 × 1
//                = 24

echo factorielle(5);   // 120
echo factorielle(10);  // 3628800

// ⚠️ ATTENTION : toujours avoir un cas de base, sinon boucle infinie !
?>
```

---

## 8. Fonctions anonymes (closures)

Une fonction anonyme est une fonction **sans nom**, qu'on peut stocker dans une variable ou passer en argument.

```php
<?php
// Syntaxe : $variable = function(paramètres) { ... };

// Exemple 1 : Fonction anonyme simple
$saluer = function(string $prenom): string {
    return "Bonjour, " . $prenom . " !";
};

echo $saluer("Alice");  // Bonjour, Alice !
echo $saluer("Bob");    // Bonjour, Bob !

// Exemple 2 : Closure avec use (accès aux variables externes)
$taxe = 0.20;

$calculerTTC = function(float $prixHT) use ($taxe): float {
    // use($taxe) permet d'utiliser la variable $taxe définie hors de la closure
    return $prixHT * (1 + $taxe);
};

echo $calculerTTC(100.0);  // 120.0

// Exemple 3 : use par référence
$compteur = 0;
$incrementer = function() use (&$compteur): void {
    $compteur++;  // Modifie la variable externe grâce à &
};

$incrementer();
$incrementer();
echo $compteur;  // 2

// Exemple 4 : Passer une closure comme argument
$nombres = [5, 2, 8, 1, 9, 3];

// usort() utilise une closure pour définir comment trier
usort($nombres, function(int $a, int $b): int {
    return $a - $b;  // Tri croissant
});
print_r($nombres);  // [1, 2, 3, 5, 8, 9]
?>
```

---

## 9. Fonctions fléchées (arrow functions)

Les fonctions fléchées (PHP 7.4+) sont une **syntaxe plus concise** pour les closures.

```php
<?php
// Syntaxe : fn(paramètres) => expression

// Closure classique
$doubler = function(int $n): int {
    return $n * 2;
};

// Équivalent en fonction fléchée
$doubler = fn(int $n): int => $n * 2;

// Différence importante : les fonctions fléchées capturent
// automatiquement les variables du scope parent (pas besoin de use)
$multiplicateur = 3;

$tripler = fn(int $n): int => $n * $multiplicateur;  // $multiplicateur capturé auto
echo $tripler(5);  // 15

// Utilisation avec array_map()
$nombres = [1, 2, 3, 4, 5];

// array_map() applique une fonction à chaque élément
$carres = array_map(fn(int $n): int => $n ** 2, $nombres);
print_r($carres);  // [1, 4, 9, 16, 25]

// Utilisation avec array_filter()
$pairs = array_filter($nombres, fn(int $n): bool => $n % 2 === 0);
print_r($pairs);  // [2, 4]
?>
```

---

## 10. Fonctions de rappel (callbacks)

Un callback est une **fonction passée en argument** à une autre fonction.

```php
<?php
// array_map() : applique une fonction à chaque élément d'un tableau
$noms = ["alice", "bob", "charlie"];
$noms_majuscules = array_map('strtoupper', $noms);  // Nom de fonction comme chaîne
print_r($noms_majuscules);  // ["ALICE", "BOB", "CHARLIE"]

// array_filter() : filtre un tableau selon une condition
$nombres = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
$impairs = array_filter($nombres, fn($n) => $n % 2 !== 0);
print_r($impairs);  // [1, 3, 5, 7, 9]

// array_reduce() : réduit un tableau à une valeur unique
$total = array_reduce($nombres, function($carry, $item) {
    return $carry + $item;  // Additionne tous les éléments
}, 0);  // 0 est la valeur de départ ($carry initial)
echo $total;  // 55

// usort() avec callback personnalisé
$etudiants = [
    ["nom" => "Charlie", "note" => 12],
    ["nom" => "Alice",   "note" => 18],
    ["nom" => "Bob",     "note" => 15],
];

usort($etudiants, fn($a, $b) => $b['note'] - $a['note']);  // Tri décroissant par note
foreach ($etudiants as $e) {
    echo $e['nom'] . " : " . $e['note'] . "<br>";
}
// Alice : 18, Bob : 15, Charlie : 12
?>
```

---

## 11. Fonctions intégrées utiles

PHP dispose de **plus de 1000 fonctions intégrées**. Voici les plus courantes :

```php
<?php
// === MATHÉMATIQUES ===
echo abs(-5);           // 5    — valeur absolue
echo max(3, 7, 2);      // 7    — valeur maximale
echo min(3, 7, 2);      // 2    — valeur minimale
echo pow(2, 8);         // 256  — 2 puissance 8
echo sqrt(16);          // 4    — racine carrée
echo round(3.7);        // 4    — arrondi
echo ceil(3.2);         // 4    — arrondi vers le haut
echo floor(3.9);        // 3    — arrondi vers le bas
echo rand(1, 100);      // Nombre aléatoire entre 1 et 100

// === CHAÎNES ===
echo strlen("Bonjour");           // 7   — longueur
echo strtoupper("bonjour");       // BONJOUR
echo strtolower("BONJOUR");       // bonjour
echo ucfirst("bonjour");          // Bonjour
echo trim("  bonjour  ");         // "bonjour" (retire espaces)
echo str_replace("e", "é", "Bonjour le monde");  // Remplacement
echo strpos("Bonjour", "jour");    // 3 (position de "jour")
echo substr("Bonjour", 0, 3);     // "Bon" (extrait 3 chars depuis index 0)
echo str_repeat("ab", 3);         // "ababab"
$tab = explode(",", "a,b,c,d");   // Divise en tableau : ["a","b","c","d"]
$str = implode("-", ["a","b","c"]); // Joint un tableau : "a-b-c"

// === TABLEAUX ===
$arr = [3, 1, 4, 1, 5, 9, 2, 6];
echo count($arr);             // 8 — nombre d'éléments
sort($arr);                   // Tri croissant (modifie $arr)
$inverse = array_reverse($arr); // Tableau inversé
$unique = array_unique([1,2,2,3,3,3]);  // [1,2,3] — supprime doublons
echo array_sum($arr);         // Somme de tous les éléments
echo in_array(5, $arr);       // true si 5 est dans le tableau

// === DATE ET HEURE ===
echo date("Y");          // Année sur 4 chiffres : 2025
echo date("m");          // Mois : 01 à 12
echo date("d");          // Jour : 01 à 31
echo date("H:i:s");      // Heure : 14:30:45
echo time();             // Timestamp Unix (secondes depuis 01/01/1970)
echo date("d/m/Y", strtotime("2025-12-25"));  // Convertit une date

// === DIVERS ===
var_dump($var);           // Type et valeur (debug)
print_r($arr);            // Affichage lisible d'un tableau (debug)
isset($var);              // true si la variable existe et n'est pas null
empty($var);              // true si la variable est "vide" (0, "", [], null, false)
is_numeric("42");         // true si la valeur est numérique
htmlspecialchars("<script>alert('xss')</script>");  // Sécurise le HTML
?>
```

---

## 12. Erreurs fréquentes

### ❌ Oublier return

```php
<?php
// ❌ La fonction calcule mais n'RETOURNE PAS le résultat
function additionner(int $a, int $b): int {
    $somme = $a + $b;
    // OUBLI : return manquant !
}

$resultat = additionner(3, 5);
echo $resultat;  // Affiche : rien (ou Warning selon configuration)

// ✅ Correct
function additionner(int $a, int $b): int {
    return $a + $b;  // return bien présent
}
?>
```

---

## 13. Bonnes pratiques

### Règle de responsabilité unique

```php
<?php
// ❌ Fonction qui fait trop de choses
function traiterUtilisateur(array $data): void {
    // Valide, sauvegarde en BDD, envoie un email, et affiche le résultat
    // ... trop de responsabilités !
}

// ✅ Une fonction = une responsabilité
function validerUtilisateur(array $data): bool { ... }
function sauvegarderUtilisateur(array $data): int { ... }
function envoyerEmailBienvenue(string $email): void { ... }
function afficherSucces(string $message): void { ... }
?>
```

---

## 14. Exercices pratiques

### Exercice 1 — Calculatrice complète

Créez des fonctions `additionner()`, `soustraire()`, `multiplier()`, `diviser()` typées avec gestion de la division par zéro.

### Exercice 2 — Validation de mot de passe

Créez une fonction `validerMotDePasse(string $mdp): array` qui retourne un tableau avec les erreurs éventuelles (longueur min 8 chars, au moins une majuscule, au moins un chiffre).

### Exercice 3 — Formatage de texte

Créez une fonction `formaterPrix(float $prix, string $devise = "EUR"): string` qui retourne le prix formaté comme "59,90 €" ou "59.90 $".

---

## 15. Corrigés

### Corrigé Exercice 2 — Validation de mot de passe

```php
<?php
declare(strict_types=1);

/**
 * Valide un mot de passe selon plusieurs règles.
 * 
 * @param string $mdp Le mot de passe à valider
 * @return array Tableau vide si valide, sinon tableau des erreurs
 */
function validerMotDePasse(string $mdp): array {
    $erreurs = [];

    // Règle 1 : longueur minimale
    if (strlen($mdp) < 8) {
        $erreurs[] = "Le mot de passe doit contenir au moins 8 caractères.";
    }

    // Règle 2 : au moins une lettre majuscule
    if (!preg_match('/[A-Z]/', $mdp)) {
        $erreurs[] = "Le mot de passe doit contenir au moins une lettre majuscule.";
    }

    // Règle 3 : au moins un chiffre
    if (!preg_match('/[0-9]/', $mdp)) {
        $erreurs[] = "Le mot de passe doit contenir au moins un chiffre.";
    }

    // Règle 4 : au moins un caractère spécial
    if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $mdp)) {
        $erreurs[] = "Le mot de passe doit contenir au moins un caractère spécial.";
    }

    return $erreurs;
}

// Test
$mots_de_passe = ["abc", "password", "Password1", "Password1!"];

foreach ($mots_de_passe as $mdp) {
    $erreurs = validerMotDePasse($mdp);
    echo "Test : \"$mdp\"<br>";
    if (empty($erreurs)) {
        echo "✅ Mot de passe valide !<br>";
    } else {
        foreach ($erreurs as $erreur) {
            echo "❌ " . $erreur . "<br>";
        }
    }
    echo "<br>";
}
?>
```

---

## Résumé du module 05

| Concept | Syntaxe | Points clés |
|---------|---------|-------------|
| Fonction simple | `function nom() { ... }` | Réutilisation du code |
| Paramètre | `function f($param)` | Valeur passée à la fonction |
| Valeur par défaut | `function f($p = "défaut")` | Paramètre optionnel |
| Retour | `return $valeur;` | Arrête la fonction et retourne |
| Typage | `function f(int $a): string` | Avec `strict_types=1` |
| Closure | `$fn = function() { ... }` | Fonction stockée dans variable |
| Arrow function | `fn($x) => $x * 2` | Closure compacte (PHP 7.4+) |
| Callback | `array_map('strtoupper', $arr)` | Fonction passée en argument |

---

**➡️ Module suivant : [Module 06 — Tableaux](../06-tableaux/README.md)**

🔗 [Retour au sommaire principal](../../README.md)
