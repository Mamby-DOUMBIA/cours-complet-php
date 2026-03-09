# Module 04 — Structures de contrôle

> **Niveau :** Débutant
> **Durée estimée :** 4 à 5 heures
> **Prérequis :** Module 03 — Bases du langage PHP

---

## Table des matières

1. [Qu'est-ce qu'une structure de contrôle ?](#1-quest-ce-quune-structure-de-contrôle-)
2. [La condition if / else / elseif](#2-la-condition-if--else--elseif)
3. [L'opérateur ternaire](#3-lopérateur-ternaire)
4. [La structure switch](#4-la-structure-switch)
5. [La structure match (PHP 8)](#5-la-structure-match-php-8)
6. [La boucle while](#6-la-boucle-while)
7. [La boucle do...while](#7-la-boucle-dowhile)
8. [La boucle for](#8-la-boucle-for)
9. [La boucle foreach](#9-la-boucle-foreach)
10. [break et continue](#10-break-et-continue)
11. [Structures imbriquées](#11-structures-imbriquées)
12. [Erreurs fréquentes](#12-erreurs-fréquentes)
13. [Bonnes pratiques](#13-bonnes-pratiques)
14. [Exercices pratiques](#14-exercices-pratiques)
15. [Corrigés](#15-corrigés)

---

## 1. Qu'est-ce qu'une structure de contrôle ?

Une structure de contrôle permet de **décider** quelles instructions exécuter en fonction de conditions, et de **répéter** des instructions plusieurs fois.

Sans structures de contrôle, un programme ne peut qu'exécuter les instructions dans l'ordre, sans aucune logique conditionnelle.

```
Exemple de vie quotidienne :
S'IL FAIT FROID, ALORS prends un manteau, SINON prends des lunettes de soleil.
↑ if                  ↑ then (bloc)           ↑ else (bloc alternatif)
```

Il existe deux grandes familles :
- **Structures conditionnelles** : exécutent du code selon une condition (`if`, `switch`, `match`)
- **Structures itératives (boucles)** : répètent du code (`while`, `for`, `foreach`)

---

## 2. La condition if / else / elseif

### Syntaxe de base

```php
<?php
// Structure if simple
if (condition) {
    // Code exécuté si la condition est vraie
}

// Structure if / else
if (condition) {
    // Code exécuté si la condition est vraie
} else {
    // Code exécuté si la condition est fausse
}

// Structure if / elseif / else
if (condition1) {
    // Code si condition1 est vraie
} elseif (condition2) {
    // Code si condition1 est fausse ET condition2 est vraie
} elseif (condition3) {
    // Code si condition1 et condition2 sont fausses ET condition3 est vraie
} else {
    // Code si TOUTES les conditions précédentes sont fausses
}
?>
```

### Exemples simples

```php
<?php
$age = 20;

// Exemple 1 : if simple
if ($age >= 18) {
    echo "Vous êtes majeur.<br>";
}

// Exemple 2 : if / else
if ($age >= 18) {
    echo "Accès autorisé (majeur).<br>";
} else {
    echo "Accès refusé (mineur).<br>";
}

// Exemple 3 : if / elseif / else — Système de notes
$note = 14;

if ($note >= 16) {
    echo "Très bien 🌟<br>";
} elseif ($note >= 14) {
    echo "Bien 👍<br>";
} elseif ($note >= 12) {
    echo "Assez bien<br>";
} elseif ($note >= 10) {
    echo "Passable<br>";
} else {
    echo "Insuffisant ❌<br>";
}
// Affiche : Bien 👍
?>
```

### Exemples avancés

```php
<?php
declare(strict_types=1);

// Exemple 4 : Conditions multiples
$age              = 22;
$possede_billet   = true;
$est_banni        = false;

if ($age >= 18 && $possede_billet && !$est_banni) {
    echo "Entrée autorisée au concert.<br>";
} elseif (!$possede_billet) {
    echo "Vous n'avez pas de billet.<br>";
} elseif ($age < 18) {
    echo "Réservé aux majeurs.<br>";
} elseif ($est_banni) {
    echo "Vous avez été banni.<br>";
}

// Exemple 5 : Condition imbriquée
$heure = (int) date("H");  // Heure actuelle (0-23)

if ($heure < 12) {
    echo "Bonjour ! (matin)<br>";
} elseif ($heure < 18) {
    if ($heure < 14) {
        echo "Bon après-midi ! (début d'après-midi)<br>";
    } else {
        echo "Bon après-midi ! (fin d'après-midi)<br>";
    }
} else {
    echo "Bonsoir !<br>";
}

// Exemple 6 : Vérification de formulaire
$email  = "alice@exemple.com";
$mdp    = "MonMotDePasse123";

// Validation simple
if (empty($email)) {
    echo "L'e-mail est requis.<br>";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "L'e-mail n'est pas valide.<br>";
} elseif (strlen($mdp) < 8) {
    echo "Le mot de passe doit faire au moins 8 caractères.<br>";
} else {
    echo "Formulaire valide ! ✅<br>";
}
?>
```

### Analyse ligne par ligne : Vérification de formulaire

```php
<?php
$email = "alice@exemple.com";  // Données à valider

// Condition 1 : le champ est-il vide ?
// empty() retourne true si la variable est vide ("", 0, null, false, [])
if (empty($email)) {
    echo "L'e-mail est requis.";

// Condition 2 : l'email a-t-il un format valide ?
// filter_var() avec FILTER_VALIDATE_EMAIL retourne false si l'email est invalide
// Le ! inverse le résultat : "si PAS valide, alors..."
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Format d'e-mail invalide.";

// Si toutes les conditions précédentes sont fausses,
// c'est que l'email est valide
} else {
    echo "E-mail valide : " . $email;
}
?>
```

---

## 3. L'opérateur ternaire

L'opérateur ternaire est une version **condensée** du if/else, écrite sur une ligne.

```php
<?php
// Syntaxe :
// $variable = condition ? valeur_si_vrai : valeur_si_faux;

$age = 20;

// Version if/else classique :
if ($age >= 18) {
    $statut = "majeur";
} else {
    $statut = "mineur";
}

// Version ternaire équivalente (plus concise) :
$statut = ($age >= 18) ? "majeur" : "mineur";

echo $statut;  // Affiche : majeur

// Utilisation directe dans echo
echo "Vous êtes " . (($age >= 18) ? "majeur" : "mineur") . ".<br>";

// Opérateur Elvis ?: (raccourci pour valeur par défaut)
$prenom = $_GET['prenom'] ?? "";  // Ou : isset($_GET['prenom']) ? $_GET['prenom'] : ""
$nom_affiche = $prenom ?: "Visiteur";  // Si $prenom est faux/vide, utilise "Visiteur"
echo "Bonjour, " . $nom_affiche . "!";

// ❌ Évitez les ternaires imbriqués (illisibles)
$note = 15;
$mention = $note >= 16 ? "Très bien" : ($note >= 14 ? "Bien" : ($note >= 12 ? "Assez bien" : "Passable"));
// ↑ Très difficile à lire ! Préférez un if/elseif dans ce cas.
?>
```

> 💡 **Bonne pratique** : Utilisez l'opérateur ternaire uniquement pour des conditions **simples** et **lisibles**. Pour les logiques complexes, préférez `if/elseif/else`.

---

## 4. La structure switch

`switch` permet de **comparer une variable à plusieurs valeurs** de façon claire et organisée.

```php
<?php
$jour = "lundi";

switch ($jour) {
    case "lundi":
        echo "C'est le début de la semaine !";
        break;  // ← IMPORTANT : arrête l'exécution du switch

    case "mardi":
    case "mercredi":  // Plusieurs cases pour la même action
    case "jeudi":
        echo "C'est le milieu de la semaine.";
        break;

    case "vendredi":
        echo "C'est bientôt le week-end ! 🎉";
        break;

    case "samedi":
    case "dimanche":
        echo "C'est le week-end ! 😊";
        break;

    default:  // ← Exécuté si aucun case ne correspond
        echo "Jour inconnu : " . $jour;
        break;
}
?>
```

### Exemple avancé : Menu de navigation

```php
<?php
$page = $_GET['page'] ?? 'accueil';

switch ($page) {
    case 'accueil':
        $titre   = "Bienvenue sur notre site";
        $contenu = "Ceci est la page d'accueil.";
        break;

    case 'apropos':
        $titre   = "À propos de nous";
        $contenu = "Découvrez notre histoire.";
        break;

    case 'contact':
        $titre   = "Nous contacter";
        $contenu = "Formulaire de contact.";
        break;

    case 'blog':
        $titre   = "Notre blog";
        $contenu = "Tous nos articles.";
        break;

    default:
        $titre   = "Page introuvable (404)";
        $contenu = "La page demandée n'existe pas.";
        http_response_code(404);  // Envoie un code HTTP 404
        break;
}

echo "<h1>" . htmlspecialchars($titre) . "</h1>";
echo "<p>" . htmlspecialchars($contenu) . "</p>";
?>
```

> ⚠️ **ATTENTION : Le `break` est crucial dans switch !**
> Sans `break`, PHP continue d'exécuter TOUS les cases suivants (comportement appelé "fall-through"). Ceci est rarement voulu.

```php
<?php
$couleur = "rouge";

switch ($couleur) {
    case "rouge":
        echo "Rouge<br>";
        // PAS DE BREAK ici !
    case "vert":
        echo "Vert<br>";
        // PAS DE BREAK ici non plus !
    case "bleu":
        echo "Bleu<br>";
        break;
}
// Affiche :
// Rouge
// Vert   ← aussi ! (fall-through)
// Bleu   ← aussi ! (fall-through)
?>
```

---

## 5. La structure match (PHP 8)

`match` est une version **plus moderne et plus stricte** de `switch`, introduite en PHP 8.

```php
<?php
$note = 15;

// Avec switch (PHP 7 et antérieur)
switch ($note) {
    case 16: case 17: case 18: case 19: case 20:
        $mention = "Très bien";
        break;
    // ...
}

// Avec match (PHP 8+) — plus concis et strict
$mention = match(true) {
    $note >= 16 => "Très bien",
    $note >= 14 => "Bien",
    $note >= 12 => "Assez bien",
    $note >= 10 => "Passable",
    default     => "Insuffisant",
};

echo $mention;  // Affiche : Bien

// match avec comparaison stricte (===)
$valeur = "1";  // C'est une CHAÎNE, pas un entier

// switch comparerait "1" == 1 (true avec ==)
// match compare "1" === 1 (false avec ===, donc pas de match)
$resultat = match($valeur) {
    1       => "C'est le chiffre 1",
    "1"     => "C'est la chaîne '1'",
    default => "Autre valeur",
};
echo $resultat;  // Affiche : C'est la chaîne '1'
?>
```

**Différences entre switch et match :**

| Caractéristique | switch | match |
|-----------------|--------|-------|
| Comparaison | Lâche (`==`) | Stricte (`===`) |
| `break` nécessaire | Oui | Non |
| Retourne une valeur | Non | Oui |
| Fall-through | Oui (si pas de break) | Non |
| PHP minimum | Toutes versions | PHP 8.0+ |

---

## 6. La boucle while

`while` exécute un bloc de code **tant qu'une condition est vraie**.

```php
<?php
// Syntaxe :
while (condition) {
    // Code répété tant que la condition est vraie
}

// Exemple 1 : Compter de 1 à 10
$compteur = 1;  // Initialisation avant la boucle

while ($compteur <= 10) {
    echo $compteur . " ";  // Affiche le nombre
    $compteur++;            // Incrémentation — NE PAS OUBLIER !
}
// Affiche : 1 2 3 4 5 6 7 8 9 10

// Exemple 2 : Tant que l'utilisateur n'a pas de crédit
$credit = 100;
$achat  = 0;

while ($credit > 0) {
    $achat++;
    $credit -= 15;  // Chaque achat coûte 15€
    echo "Achat #" . $achat . " — Crédit restant : " . max(0, $credit) . "€<br>";
}
// Simule des achats jusqu'à épuisement du crédit

// ⚠️ Attention à la BOUCLE INFINIE !
// $i = 0;
// while ($i < 10) {
//     echo $i;
//     // OUBLI : $i++ manquant → boucle infinie, le script plante !
// }
?>
```

---

## 7. La boucle do...while

Similaire à `while`, mais la condition est évaluée **APRÈS** la première exécution. Le bloc s'exécute donc **au moins une fois**.

```php
<?php
// Syntaxe :
do {
    // Code exécuté au moins une fois
} while (condition);

// Exemple 1 : Compter de 1 à 5
$i = 1;
do {
    echo $i . " ";
    $i++;
} while ($i <= 5);
// Affiche : 1 2 3 4 5

// Exemple 2 : Cas où do/while est utile
// Même si la condition est fausse DÈS LE DÉBUT, le bloc s'exécute une fois
$tentatives = 10;  // Déjà au max

do {
    echo "Menu affiché au moins une fois !<br>";
    $tentatives++;
} while ($tentatives < 3);
// Affiche "Menu affiché..." une fois, même si $tentatives > 3 dès le départ

// Comparaison while vs do/while
$nombre = 100;

// while : ne s'exécute PAS car 100 >= 10 est faux dès le départ
while ($nombre < 10) {
    echo "while : " . $nombre . "<br>";  // Ne s'affiche jamais
}

// do/while : s'exécute UNE FOIS même si la condition est fausse
do {
    echo "do/while : " . $nombre . "<br>";  // S'affiche une fois !
} while ($nombre < 10);
?>
```

---

## 8. La boucle for

`for` est utilisée quand on connaît **à l'avance** le nombre d'itérations.

```php
<?php
// Syntaxe :
// for (initialisation; condition; incrément) { ... }

// Exemple 1 : Compter de 1 à 10
for ($i = 1; $i <= 10; $i++) {
    echo $i . " ";
}
// Affiche : 1 2 3 4 5 6 7 8 9 10

// Exemple 2 : Compter à rebours
for ($i = 10; $i >= 1; $i--) {
    echo $i . " ";
}
echo "Décollage ! 🚀";
// Affiche : 10 9 8 7 6 5 4 3 2 1 Décollage !

// Exemple 3 : Table de multiplication du 7
echo "<h3>Table de multiplication du 7</h3>";
for ($i = 1; $i <= 10; $i++) {
    $resultat = 7 * $i;
    echo "7 × " . $i . " = " . $resultat . "<br>";
}

// Exemple 4 : Générer une liste HTML
echo "<ul>";
for ($i = 1; $i <= 5; $i++) {
    echo "<li>Élément numéro " . $i . "</li>";
}
echo "</ul>";

// Exemple 5 : Incrément personnalisé (compter de 2 en 2)
echo "Nombres pairs de 2 à 20 : ";
for ($i = 2; $i <= 20; $i += 2) {
    echo $i . " ";
}
// Affiche : 2 4 6 8 10 12 14 16 18 20

// Exemple 6 : Plusieurs variables dans le for
for ($i = 0, $j = 10; $i <= 10; $i++, $j--) {
    echo "i=" . $i . ", j=" . $j . "<br>";
}
?>
```

### Analyse détaillée de la boucle for

```
for ($i = 1; $i <= 10; $i++) { ... }
      ↑           ↑         ↑
      │           │         └── Incrément : exécuté APRÈS chaque itération
      │           └──────────── Condition : vérifiée AVANT chaque itération
      └──────────────────────── Initialisation : exécutée UNE SEULE FOIS au départ
```

**Ordre d'exécution :**
1. Initialisation : `$i = 1` (une seule fois)
2. Vérification : `$i <= 10` → si faux, on sort de la boucle
3. Exécution du bloc
4. Incrément : `$i++`
5. Retour à l'étape 2

---

## 9. La boucle foreach

`foreach` est spécialement conçue pour **parcourir des tableaux**.

```php
<?php
// Syntaxe 1 : valeurs uniquement
$fruits = ["pomme", "banane", "cerise", "orange"];

foreach ($fruits as $fruit) {
    echo $fruit . "<br>";
}
// Affiche :
// pomme
// banane
// cerise
// orange

// Syntaxe 2 : clés ET valeurs
foreach ($fruits as $index => $fruit) {
    echo $index . " : " . $fruit . "<br>";
}
// Affiche :
// 0 : pomme
// 1 : banane
// 2 : cerise
// 3 : orange

// Tableau associatif
$personne = [
    "prenom" => "Alice",
    "age"    => 25,
    "ville"  => "Paris",
];

foreach ($personne as $cle => $valeur) {
    echo $cle . " : " . $valeur . "<br>";
}
// Affiche :
// prenom : Alice
// age : 25
// ville : Paris

// Tableau multidimensionnel
$etudiants = [
    ["nom" => "Alice",   "note" => 16],
    ["nom" => "Bob",     "note" => 12],
    ["nom" => "Charlie", "note" => 18],
];

echo "<table border='1'>";
echo "<tr><th>Étudiant</th><th>Note</th><th>Mention</th></tr>";

foreach ($etudiants as $etudiant) {
    $mention = ($etudiant["note"] >= 14) ? "Bien" : "Passable";
    echo "<tr>";
    echo "<td>" . $etudiant["nom"] . "</td>";
    echo "<td>" . $etudiant["note"] . "/20</td>";
    echo "<td>" . $mention . "</td>";
    echo "</tr>";
}

echo "</table>";
?>
```

---

## 10. break et continue

### break — Sortir d'une boucle

```php
<?php
// break sort immédiatement de la boucle
for ($i = 1; $i <= 10; $i++) {
    if ($i === 5) {
        break;  // On sort de la boucle quand i vaut 5
    }
    echo $i . " ";
}
// Affiche : 1 2 3 4
// (5 n'est PAS affiché car break est exécuté avant echo)

// break avec un paramètre : sort de N boucles imbriquées
for ($i = 0; $i < 3; $i++) {
    for ($j = 0; $j < 3; $j++) {
        if ($i === 1 && $j === 1) {
            break 2;  // Sort des DEUX boucles imbriquées
        }
        echo "($i,$j) ";
    }
}
// Affiche : (0,0) (0,1) (0,2) (1,0)  ← s'arrête avant (1,1)
?>
```

### continue — Passer à l'itération suivante

```php
<?php
// continue saute le reste du bloc et passe à l'itération suivante
for ($i = 1; $i <= 10; $i++) {
    if ($i % 2 === 0) {
        continue;  // Saute les nombres pairs
    }
    echo $i . " ";
}
// Affiche : 1 3 5 7 9 (seulement les impairs)

// Exemple pratique : filtrer des données
$produits = [
    ["nom" => "Stylo",     "stock" => 15],
    ["nom" => "Cahier",    "stock" => 0],
    ["nom" => "Règle",     "stock" => 8],
    ["nom" => "Compas",    "stock" => 0],
    ["nom" => "Calculette","stock" => 3],
];

echo "Produits en stock :<br>";
foreach ($produits as $produit) {
    if ($produit["stock"] === 0) {
        continue;  // Ignore les produits en rupture de stock
    }
    echo "- " . $produit["nom"] . " (stock : " . $produit["stock"] . ")<br>";
}
// Affiche uniquement Stylo, Règle, Calculette
?>
```

---

## 11. Structures imbriquées

On peut combiner toutes les structures vues précédemment :

```php
<?php
/**
 * Exemple : Génération d'un tableau de multiplication HTML complet
 */
echo "<h2>Tables de multiplication (1 à 10)</h2>";
echo "<table border='1' cellpadding='8'>";

// En-tête du tableau
echo "<tr><th>×</th>";
for ($col = 1; $col <= 10; $col++) {
    echo "<th>" . $col . "</th>";
}
echo "</tr>";

// Corps du tableau
for ($ligne = 1; $ligne <= 10; $ligne++) {
    echo "<tr>";
    echo "<th>" . $ligne . "</th>";  // Étiquette de la ligne

    for ($col = 1; $col <= 10; $col++) {
        $produit = $ligne * $col;

        // Mise en évidence des multiples de 5
        $style = ($produit % 5 === 0) ? " style='background:#ffe;'" : "";
        echo "<td" . $style . ">" . $produit . "</td>";
    }

    echo "</tr>";
}

echo "</table>";
?>
```

---

## 12. Erreurs fréquentes

### ❌ Oublier le break dans switch

```php
<?php
$couleur = "rouge";

// ❌ ERREUR : pas de break — fall-through non voulu
switch ($couleur) {
    case "rouge":
        echo "Rouge";
        // PAS DE BREAK → tombe dans "vert" !
    case "vert":
        echo "Vert";
        break;
    case "bleu":
        echo "Bleu";
        break;
}
// Affiche : RougeVert (non voulu !)

// ✅ Correct
switch ($couleur) {
    case "rouge":
        echo "Rouge";
        break;  // ← break bien présent
    case "vert":
        echo "Vert";
        break;
}
?>
```

### ❌ Boucle infinie

```php
<?php
// ❌ ERREUR : oubli d'incrémenter le compteur
$i = 0;
while ($i < 10) {
    echo $i;
    // OUBLI : $i++ manquant → boucle infinie !
}

// ✅ Correct
$i = 0;
while ($i < 10) {
    echo $i;
    $i++;  // Indispensable !
}
?>
```

---

## 13. Bonnes pratiques

### Préférez foreach à for pour les tableaux

```php
<?php
$noms = ["Alice", "Bob", "Charlie"];

// ❌ Moins lisible avec for
for ($i = 0; $i < count($noms); $i++) {
    echo $noms[$i] . "<br>";
}

// ✅ Plus lisible avec foreach
foreach ($noms as $nom) {
    echo $nom . "<br>";
}
?>
```

### Évitez les conditions trop complexes

```php
<?php
// ❌ Difficile à lire
if ($a > 0 && $b > 0 && ($c === true || $d !== null) && strlen($e) > 5) { ... }

// ✅ Décomposez en variables explicites
$valeurs_positives = $a > 0 && $b > 0;
$condition_speciale = $c === true || $d !== null;
$texte_suffisant = strlen($e) > 5;

if ($valeurs_positives && $condition_speciale && $texte_suffisant) { ... }
?>
```

---

## 14. Exercices pratiques

### Exercice 1 — FizzBuzz classique

Affichez les nombres de 1 à 100 avec les règles suivantes :
- Si le nombre est divisible par 3, affichez "Fizz"
- Si le nombre est divisible par 5, affichez "Buzz"
- Si le nombre est divisible par 3 ET 5, affichez "FizzBuzz"
- Sinon, affichez le nombre

### Exercice 2 — Calculatrice simple

Créez un script `calculatrice.php` avec des variables `$nombre1`, `$nombre2` et `$operation` ("+", "-", "*", "/"). Utilisez `switch` ou `match` pour calculer et afficher le résultat.

### Exercice 3 — Tableau HTML dynamique

Générez un tableau HTML qui affiche les 12 mois de l'année avec leur numéro, leur nom, et le nombre de jours.

---

## 15. Corrigés

### Corrigé Exercice 1 — FizzBuzz

```php
<?php
for ($i = 1; $i <= 100; $i++) {

    // On vérifie d'abord la condition la plus restrictive (divisible par 15)
    if ($i % 15 === 0) {
        echo "FizzBuzz";
    } elseif ($i % 3 === 0) {
        echo "Fizz";
    } elseif ($i % 5 === 0) {
        echo "Buzz";
    } else {
        echo $i;
    }

    echo " ";  // Espace entre les résultats
}
?>
```

### Corrigé Exercice 2 — Calculatrice

```php
<?php
declare(strict_types=1);

$nombre1   = 15;
$nombre2   = 4;
$operation = "/";

$resultat = match($operation) {
    "+"     => $nombre1 + $nombre2,
    "-"     => $nombre1 - $nombre2,
    "*"     => $nombre1 * $nombre2,
    "/"     => ($nombre2 !== 0) ? $nombre1 / $nombre2 : "Division impossible",
    default => "Opération inconnue : " . $operation,
};

echo $nombre1 . " " . $operation . " " . $nombre2 . " = " . $resultat;
// Affiche : 15 / 4 = 3.75
?>
```

### Corrigé Exercice 3 — Tableau des mois

```php
<?php
$mois = [
    1  => ["Janvier",   31],
    2  => ["Février",   28],  // Simplifié (sans années bissextiles)
    3  => ["Mars",      31],
    4  => ["Avril",     30],
    5  => ["Mai",       31],
    6  => ["Juin",      30],
    7  => ["Juillet",   31],
    8  => ["Août",      31],
    9  => ["Septembre", 30],
    10 => ["Octobre",   31],
    11 => ["Novembre",  30],
    12 => ["Décembre",  31],
];

echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Numéro</th><th>Mois</th><th>Jours</th></tr>";

foreach ($mois as $numero => [$nom, $jours]) {
    $style = ($jours === 31) ? " style='background:#e8f4e8;'" : "";
    echo "<tr" . $style . ">";
    echo "<td>" . $numero . "</td>";
    echo "<td>" . $nom . "</td>";
    echo "<td>" . $jours . "</td>";
    echo "</tr>";
}

echo "</table>";
?>
```

---

## Résumé du module 04

| Structure | Utilisation | Points clés |
|-----------|-------------|-------------|
| `if/elseif/else` | Conditions générales | Condition évaluée avec `===` |
| `switch` | Comparer une valeur à plusieurs cas | Ne pas oublier `break` |
| `match` (PHP 8) | Comme switch mais strict et retourne une valeur | Comparaison `===` automatique |
| `while` | Boucle tant que condition vraie | Gérer l'incrémentation ! |
| `do/while` | Au moins une exécution garantie | Condition vérifiée après |
| `for` | Nombre d'itérations connu | `init ; condition ; incrément` |
| `foreach` | Parcourir un tableau | `as $cle => $valeur` |
| `break` | Sortir d'une boucle | `break 2` sort de 2 boucles |
| `continue` | Passer à l'itération suivante | Utile pour filtrer |

---

**➡️ Module suivant : [Module 05 — Fonctions](../05-fonctions/README.md)**

🔗 [Retour au sommaire principal](../../README.md)
