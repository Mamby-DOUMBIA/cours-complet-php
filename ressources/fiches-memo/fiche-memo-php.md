# 📋 Fiche Mémo PHP — Référence rapide

> Gardez cette fiche à portée de main pendant votre développement !

---

## 🔤 Syntaxe de base

```php
<?php
// Commentaire une ligne
# Commentaire une ligne (style shell)
/* Commentaire multiligne */

echo "Affichage";           // Affiche sans retour ligne
echo "Texte" . PHP_EOL;     // Affiche avec retour ligne
print "Texte";              // Comme echo mais retourne 1

// Point-virgule OBLIGATOIRE à la fin de chaque instruction
$variable = "valeur";       // Affectation
$a = $b = $c = 0;           // Affectation multiple

declare(strict_types=1);    // À mettre EN TOUT PREMIER dans le fichier
```

---

## 📦 Variables et types

```php
// Types scalaires
$chaine  = "texte";       // string
$entier  = 42;            // int
$decimal = 3.14;          // float
$booleen = true;          // bool (true/false)
$rien    = null;          // null

// Vérification de type
gettype($var)             // Retourne le type sous forme de chaîne
is_string($var)           // bool
is_int($var) / is_integer($var)
is_float($var) / is_double($var)
is_bool($var)
is_null($var)
is_array($var)
is_object($var)
is_numeric($var)          // Vrai si la valeur est un nombre ou une chaîne numérique

// Conversion (cast)
(int) "42"                // 42
(float) "3.14abc"         // 3.14
(string) 42               // "42"
(bool) ""                 // false
(array) "hello"           // ["hello"]

// Débogage
var_dump($var);            // Type + valeur (détaillé)
print_r($var);             // Valeur lisible (tableaux, objets)
var_export($var, true);    // Représentation PHP valide
```

---

## ➕ Opérateurs essentiels

```php
// Arithmétiques
+ - * / %  **              // Puissance avec **
$a += 5;  $a -= 3;        // Affectation combinée
$a++;  $a--;  ++$a;  --$a;

// Comparaisons
==   !=   <   >   <=   >=  // Lâches (avec conversion de type)
===  !==                   // STRICTES (recommandées — type ET valeur)
<=>                        // Spaceship : retourne -1, 0, ou 1

// Logiques
&&   ||   !                // Préférez ces opérateurs
and  or   not              // Priorité différente — à éviter

// Chaînes
.                          // Concaténation
.=                         // Concaténation-affectation

// Null
??                         // Null coalescing : $a ?? "défaut"
??=                        // Null coalescing assignment (PHP 7.4+)
?->                        // Nullsafe : $obj?->method() (PHP 8+)
```

---

## 🔀 Structures de contrôle

```php
// Conditionnel
if ($condition) {
} elseif ($autre) {
} else {
}

// Ternaire
$val = $condition ? "vrai" : "faux";
$val = $variable ?? "défaut si null";  // Null coalescing

// Switch
switch ($variable) {
    case "valeur": /* code */ break;
    default: /* code */ break;
}

// Match (PHP 8+, strict)
$result = match($variable) {
    "a", "b" => "A ou B",
    "c"      => "C",
    default  => "Autre",
};

// Boucles
for ($i = 0; $i < 10; $i++) { }
while ($condition) { }
do { } while ($condition);       // Au moins une itération
foreach ($tableau as $val) { }
foreach ($tableau as $cle => $val) { }

// Contrôle de boucle
break;      // Sort de la boucle
break 2;    // Sort de 2 boucles imbriquées
continue;   // Passe à l'itération suivante
```

---

## 🔧 Fonctions

```php
// Déclaration
function nomFonction(type $param = "défaut"): typeRetour {
    return $valeur;
}

// Fonctions typées (PHP 7+)
function additionner(int $a, int $b): int {
    return $a + $b;
}

// Paramètre nullable
function traiter(?string $texte): string {
    return $texte ?? '';
}

// Paramètres variables (splat)
function somme(int ...$nombres): int {
    return array_sum($nombres);
}

// Closure
$fn = function($x) use ($var_externe) { return $x * $var_externe; };

// Fonction fléchée (PHP 7.4+) — capture auto les variables externes
$fn = fn($x) => $x * $multiplicateur;

// Appel de fonction
nomFonction(valeur);
nomFonction(param: valeur);  // Argument nommé (PHP 8+)
```

---

## 📚 Tableaux — Fonctions clés

```php
// Création
$arr  = [1, 2, 3];
$asso = ['cle' => 'valeur'];
$arr[] = 4;                    // Ajouter à la fin

// Informations
count($arr)                    // Nombre d'éléments
array_key_exists('cle', $asso) // Vérifie l'existence d'une clé
in_array($val, $arr, true)     // Cherche une valeur (strict)
array_search($val, $arr)       // Retourne la clé de la valeur

// Modification
array_push($arr, $val)         // Ajouter à la fin
array_pop($arr)                // Retirer le dernier
array_unshift($arr, $val)      // Ajouter au début
array_shift($arr)              // Retirer le premier
unset($arr[$cle])              // Supprimer un élément

// Tri
sort($arr)                     // Croissant (réindexe)
rsort($arr)                    // Décroissant
asort($arr)                    // Par valeur (conserve clés)
ksort($arr)                    // Par clé
usort($arr, fn($a,$b) => $a-$b); // Tri personnalisé

// Transformation
array_map(callable, $arr)      // Applique une fonction à chaque élément
array_filter($arr, callable)   // Filtre selon une condition
array_reduce($arr, callable, $init) // Réduit à une valeur
array_merge($a, $b)            // Fusion de tableaux
array_slice($arr, 0, 3)        // Extraction de sous-tableau
array_unique($arr)             // Supprime les doublons
array_flip($arr)               // Échange clés et valeurs
array_column($arr, 'cle')      // Extrait une colonne
array_combine($cles, $valeurs) // Crée un tableau associatif
array_reverse($arr)            // Inverse l'ordre

// Informations agrégées
array_sum($arr)                // Somme
array_keys($arr)               // Toutes les clés
array_values($arr)             // Toutes les valeurs (réindexe)
```

---

## 📝 Chaînes — Fonctions clés

```php
strlen($s)                     // Longueur en octets
mb_strlen($s)                  // Longueur en caractères UTF-8
strtolower($s) / strtoupper($s)
ucfirst($s) / ucwords($s)
trim($s) / ltrim($s) / rtrim($s)
substr($s, debut, longueur)    // Extraction
strpos($s, $cherche)           // Position (false si absent)
str_contains($s, $cherche)     // PHP 8+ — true/false
str_starts_with($s, $prefix)   // PHP 8+
str_ends_with($s, $suffix)     // PHP 8+
str_replace($cherche, $remplace, $s)
str_ireplace(...)              // Insensible à la casse
preg_match('/pattern/', $s)   // Regex — vrai/faux
preg_replace('/pattern/', $remplace, $s)
preg_split('/pattern/', $s)
explode(',', $s)               // Divise en tableau
implode(',', $arr)             // Joint un tableau
sprintf("Prix : %.2f €", 19.9) // Formatage
number_format(1234.56, 2, ',', ' ')
htmlspecialchars($s, ENT_QUOTES, 'UTF-8')  // Sécurité HTML !!!
strip_tags($s)                 // Supprime les balises HTML
nl2br($s)                      // Convertit \n en <br>
wordwrap($s, 75, "\n", true)   // Coupe le texte
str_pad($s, 10, '0', STR_PAD_LEFT)
str_repeat('*', 10)
md5($s) / sha1($s)             // Hachage (pas pour les MDP !)
base64_encode($s) / base64_decode($s)
urlencode($s) / urldecode($s)
```

---

## 🕐 Date et heure

```php
date("d/m/Y")                  // 15/01/2025
date("H:i:s")                  // 14:30:45
date("Y-m-d H:i:s")            // 2025-01-15 14:30:45
date("N")                      // Jour semaine : 1=Lun, 7=Dim
date("l")                      // Nom jour en anglais
date("F")                      // Nom mois en anglais
time()                         // Timestamp Unix (secondes)
strtotime("2025-12-25")        // Convertit une date en timestamp
strtotime("+7 days")           // Dans 7 jours
strtotime("next Monday")       // Prochain lundi
mktime(H, i, s, m, d, Y)      // Crée un timestamp

// Classe DateTime (recommandée)
$date = new DateTime();
$date = new DateTime('2025-01-15 14:30:00');
$date->format('d/m/Y H:i')
$date->modify('+1 month')
$diff = $date1->diff($date2);  // DateInterval
```

---

## 🗄️ Base de données — PDO

```php
// Connexion
$pdo = new PDO(
    "mysql:host=localhost;dbname=mabase;charset=utf8mb4",
    "user", "mdp",
    [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]
);

// Requête simple (sans paramètre variable)
$stmt = $pdo->query("SELECT * FROM articles");
$articles = $stmt->fetchAll();

// Requête préparée — OBLIGATOIRE pour les données utilisateur
$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = :id");
$stmt->execute([':id' => $id]);
$article = $stmt->fetch();          // Un seul résultat
$articles = $stmt->fetchAll();      // Tous les résultats
$valeur = $stmt->fetchColumn();     // Une seule valeur
$count = $stmt->rowCount();         // Lignes affectées

// INSERT
$stmt = $pdo->prepare("INSERT INTO articles (titre, contenu) VALUES (:titre, :contenu)");
$stmt->execute([':titre' => $titre, ':contenu' => $contenu]);
$id = $pdo->lastInsertId();         // ID de la ligne insérée

// UPDATE
$stmt = $pdo->prepare("UPDATE articles SET titre = :titre WHERE id = :id");
$stmt->execute([':titre' => $titre, ':id' => $id]);

// DELETE
$stmt = $pdo->prepare("DELETE FROM articles WHERE id = :id");
$stmt->execute([':id' => $id]);

// Transaction
$pdo->beginTransaction();
try {
    /* requêtes */
    $pdo->commit();
} catch (PDOException $e) {
    $pdo->rollBack();
    throw $e;
}
```

---

## 🔐 Sécurité — Aide-mémoire

```php
// Mots de passe
password_hash($mdp, PASSWORD_BCRYPT, ['cost' => 12])
password_verify($mdp_saisi, $hash_stocke)

// XSS — TOUJOURS avant affichage HTML
htmlspecialchars($val, ENT_QUOTES, 'UTF-8')

// Validation
filter_var($email, FILTER_VALIDATE_EMAIL)
filter_var($url, FILTER_VALIDATE_URL)
filter_var($entier, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])

// Injection SQL → REQUÊTES PRÉPARÉES (voir PDO ci-dessus)

// CSRF
$_SESSION['csrf'] = bin2hex(random_bytes(32));
hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')

// Sessions sécurisées
session_regenerate_id(true);      // Après connexion
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_secure', '1');   // HTTPS
```

---

## 📁 Fichiers

```php
file_get_contents($chemin)        // Lire tout
file_put_contents($chemin, $data) // Écrire (crée ou écrase)
file_put_contents($chemin, $data, FILE_APPEND | LOCK_EX)  // Ajouter
file_exists($chemin)              // Existe ?
is_file($chemin) / is_dir($chemin)
filesize($chemin)                 // Taille en octets
basename($chemin)                 // Nom du fichier
dirname($chemin)                  // Répertoire parent
pathinfo($chemin, PATHINFO_EXTENSION)
copy($src, $dest)
rename($old, $new)
unlink($chemin)                   // Supprimer fichier
mkdir($dir, 0755, true)           // Créer dossier (récursif)
glob('*.php')                     // Lister les fichiers
scandir($dossier)                 // Contenu d'un dossier
```

---

## 🌐 HTTP — Superglobales

```php
$_GET['param']                    // Paramètres URL
$_POST['champ']                   // Données formulaire POST
$_SESSION['cle']                  // Données de session
$_COOKIE['nom']                   // Cookies
$_FILES['champ']                  // Fichiers uploadés
$_SERVER['REQUEST_METHOD']        // GET, POST, PUT, DELETE
$_SERVER['REQUEST_URI']           // L'URL demandée
$_SERVER['HTTP_HOST']             // Le nom d'hôte
$_SERVER['REMOTE_ADDR']           // IP du visiteur
$_SERVER['PHP_SELF']              // Fichier courant
$_SERVER['HTTP_USER_AGENT']       // Navigateur

// Redirection HTTP
header('Location: /page.php');
exit();

// Codes HTTP
http_response_code(200);  // OK
http_response_code(301);  // Moved Permanently
http_response_code(302);  // Found (redirection temp.)
http_response_code(400);  // Bad Request
http_response_code(401);  // Unauthorized
http_response_code(403);  // Forbidden
http_response_code(404);  // Not Found
http_response_code(500);  // Internal Server Error
```

---

## ⚡ Constantes utiles

```php
PHP_EOL         // Fin de ligne selon l'OS
PHP_INT_MAX     // Plus grand entier
PHP_INT_MIN     // Plus petit entier
PHP_FLOAT_MAX   // Plus grand float
PHP_VERSION     // Version de PHP
PHP_OS          // Système d'exploitation
PHP_SAPI        // Interface (cli, apache2handler, etc.)
M_PI            // π = 3.14159...
M_E             // e = 2.71828...
PHP_INT_SIZE    // Taille des entiers (4 ou 8 octets)
DIRECTORY_SEPARATOR  // / sur Linux, \ sur Windows
PATH_SEPARATOR       // : sur Linux, ; sur Windows
__FILE__        // Chemin absolu du fichier courant
__DIR__         // Répertoire du fichier courant
__LINE__        // Numéro de ligne courant
__FUNCTION__    // Nom de la fonction courante
__CLASS__       // Nom de la classe courante
__METHOD__      // Nom de la méthode courante (Classe::methode)
```

---

## 🎯 Erreurs et exceptions

```php
// Lever une exception
throw new InvalidArgumentException("Message d'erreur");
throw new RuntimeException("Erreur", $code);

// Capturer
try {
    // Code risqué
} catch (InvalidArgumentException $e) {
    // Gestion spécifique
} catch (Exception $e) {
    // Gestion générique
} finally {
    // Toujours exécuté
}

// Propriétés d'une exception
$e->getMessage()    // Message
$e->getCode()       // Code numérique
$e->getFile()       // Fichier où l'exception a été levée
$e->getLine()       // Ligne
$e->getTrace()      // Tableau de la pile d'appels
$e->getTraceAsString() // Pile en chaîne
$e->getPrevious()   // Exception précédente (enchaînement)
```

---

*🔗 Documentation complète : [php.net/manual/fr](https://www.php.net/manual/fr/)*
