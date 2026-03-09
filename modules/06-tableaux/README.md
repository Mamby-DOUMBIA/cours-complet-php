# Module 06 — Tableaux en PHP

> **Niveau :** Débutant → Intermédiaire
> **Durée estimée :** 4 heures
> **Prérequis :** Modules 01 à 05

---

## 1. Introduction aux tableaux

Un tableau en PHP peut stocker plusieurs valeurs sous un seul nom de variable.

```php
<?php
// Tableau indexé (clés numériques automatiques)
$fruits = ["pomme", "banane", "cerise"];
echo $fruits[0];  // pomme
echo $fruits[2];  // cerise

// Tableau associatif (clés définies manuellement)
$personne = [
    "prenom" => "Alice",
    "age"    => 25,
    "ville"  => "Paris",
];
echo $personne["prenom"];  // Alice

// Tableau multidimensionnel
$etudiants = [
    ["nom" => "Alice", "note" => 18],
    ["nom" => "Bob",   "note" => 14],
];
echo $etudiants[0]["nom"];   // Alice
echo $etudiants[1]["note"];  // 14
?>
```

---

## 2. Créer et modifier des tableaux

```php
<?php
// Créer un tableau vide et ajouter des éléments
$liste = [];
$liste[] = "premier";   // Ajoute à la fin
$liste[] = "deuxième";
$liste[] = "troisième";
print_r($liste);  // ["premier", "deuxième", "troisième"]

// Ajouter avec une clé spécifique
$config = [];
$config["debug"] = true;
$config["version"] = "1.0";

// Modifier un élément
$fruits = ["pomme", "banane", "cerise"];
$fruits[1] = "fraise";  // Remplace "banane" par "fraise"

// Supprimer un élément
unset($fruits[0]);       // Supprime "pomme"
print_r($fruits);        // [1 => "fraise", 2 => "cerise"]

// Réindexer après suppression
$fruits = array_values($fruits);  // [0 => "fraise", 1 => "cerise"]
?>
```

---

## 3. Fonctions de tableaux essentielles

```php
<?php
$nombres = [5, 3, 8, 1, 9, 2, 7, 4, 6];

// Tri
sort($nombres);           // Tri croissant (modifie le tableau)
rsort($nombres);          // Tri décroissant
$trie = $nombres;
asort($trie);             // Tri par valeur (conserve les clés)
ksort($trie);             // Tri par clé

// Recherche
echo in_array(5, $nombres);                  // true
echo array_search(5, $nombres);             // index de 5

// Manipulation
$a = [1, 2, 3];
$b = [4, 5, 6];
$fusion = array_merge($a, $b);              // [1,2,3,4,5,6]
$tranche = array_slice($a, 0, 2);           // [1,2]
$unique = array_unique([1,1,2,3,3]);        // [1,2,3]
$inverse = array_reverse($a);              // [3,2,1]
$compte = count($a);                        // 3

// Extraction
$cles = array_keys($personne);              // ["prenom","age","ville"]
$vals = array_values($personne);            // ["Alice",25,"Paris"]

// Transformation fonctionnelle
$carres  = array_map(fn($n) => $n**2, [1,2,3,4]);    // [1,4,9,16]
$pairs   = array_filter([1,2,3,4,5], fn($n) => $n%2===0);  // [2,4]
$somme   = array_reduce([1,2,3,4,5], fn($acc,$n) => $acc+$n, 0);  // 15

// Stack (pile)
array_push($a, 4);   // Ajoute à la fin
$dernier = array_pop($a);   // Retire le dernier

// Queue (file)
array_unshift($a, 0);  // Ajoute au début
$premier = array_shift($a); // Retire le premier
?>
```

---

## 4. Exercices pratiques

### Exercice 1 — Gestion d'une liste de courses
Créez un tableau `$courses` et implémentez : ajouter un article, supprimer par nom, afficher la liste triée.

### Exercice 2 — Statistiques de notes
Avec le tableau `$notes = [14, 17, 8, 12, 19, 11, 16]`, calculez : moyenne, note min, note max, nombre d'élèves au-dessus de la moyenne.

---

## 5. Corrigés

### Corrigé Exercice 2
```php
<?php
$notes = [14, 17, 8, 12, 19, 11, 16];

$moyenne = array_sum($notes) / count($notes);
$min     = min($notes);
$max     = max($notes);
$audessus = count(array_filter($notes, fn($n) => $n > $moyenne));

echo "Moyenne : " . round($moyenne, 2) . "/20" . PHP_EOL;
echo "Min : $min/20, Max : $max/20" . PHP_EOL;
echo "Au-dessus de la moyenne : $audessus étudiant(s)" . PHP_EOL;
?>
```

---

## Résumé du module 06

| Opération | Fonction PHP |
|-----------|-------------|
| Ajouter à la fin | `$arr[] = val` ou `array_push()` |
| Supprimer | `unset($arr[$i])` |
| Trier croissant | `sort()` |
| Chercher | `in_array()`, `array_search()` |
| Fusionner | `array_merge()` |
| Transformer | `array_map()`, `array_filter()`, `array_reduce()` |

**➡️ [Module 07 — Chaînes de caractères](../07-chaines-caracteres/README.md)**
