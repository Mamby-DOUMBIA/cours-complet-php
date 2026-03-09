# Module 01 — Introduction à PHP

> **Niveau :** Débutant absolu
> **Durée estimée :** 2 à 3 heures
> **Prérequis :** Aucun

---

## Table des matières

1. [Qu'est-ce que PHP ?](#1-quest-ce-que-php-)
2. [Histoire et évolution de PHP](#2-histoire-et-évolution-de-php)
3. [Rôle de PHP dans le développement Web](#3-rôle-de-php-dans-le-développement-web)
4. [PHP côté serveur — Comment ça fonctionne ?](#4-php-côté-serveur--comment-ça-fonctionne-)
5. [Le cycle HTTP expliqué simplement](#5-le-cycle-http-expliqué-simplement)
6. [Votre premier programme PHP](#6-votre-premier-programme-php)
7. [Erreurs fréquentes des débutants](#7-erreurs-fréquentes-des-débutants)
8. [Bonnes pratiques dès le départ](#8-bonnes-pratiques-dès-le-départ)
9. [Exercices pratiques](#9-exercices-pratiques)
10. [Corrigés](#10-corrigés)

---

## 1. Qu'est-ce que PHP ?

### Explication simple

Imaginez un site Web comme un restaurant. Quand vous entrez (visitez le site), un **serveur** (le serveur informatique) vous apporte un menu personnalisé selon votre commande. PHP est le **chef cuisinier** — il prépare ce menu à la demande, en utilisant des ingrédients (données d'une base de données, fichiers, etc.) et en personnalisant le résultat pour chaque visiteur.

En termes simples : **PHP est un langage de programmation qui s'exécute sur un serveur Web et qui génère des pages HTML dynamiques.**

### Définition technique

PHP (acronyme récursif de **PHP: Hypertext Preprocessor**) est un langage de script **open source** côté serveur (*server-side scripting language*), principalement utilisé pour le développement Web dynamique. Il est intégré directement dans le HTML et traité par un serveur Web avant d'être envoyé au navigateur du client.

**Caractéristiques principales :**

| Caractéristique | Description |
|-----------------|-------------|
| Type | Langage de script interprété |
| Paradigme | Multi-paradigme (procédural, orienté objet, fonctionnel) |
| Typage | Faiblement typé, dynamique |
| Exécution | Côté serveur uniquement |
| Extension de fichier | `.php` |
| Open source | Oui, entièrement gratuit |

🔗 [Documentation officielle PHP](https://www.php.net/manual/fr/)

---

## 2. Histoire et évolution de PHP

### Les grandes étapes de PHP

Comprendre l'histoire de PHP permet de comprendre **pourquoi** le langage est conçu comme il l'est.

#### 1994 — La naissance (PHP/FI)

PHP a été créé en **1994** par **Rasmus Lerdorf**, un développeur danois-canadien. À l'origine, il s't'appelait pas "PHP" mais **"Personal Home Page Tools"** (outils pour page personnelle). Rasmus voulait simplement suivre les visites sur son CV en ligne.

Il s'agissait d'un ensemble de scripts CGI (*Common Gateway Interface*) écrits en C, sans aucune ambition de créer un nouveau langage.

> 💡 **Anecdote** : Rasmus a lui-même déclaré qu'il n'avait "jamais eu l'intention de créer un langage de programmation". PHP est né d'un besoin pratique, ce qui explique son approche très pragmatique.

#### 1997 — PHP 3 : le vrai décollage

En 1997, **Andi Gutmans** et **Zeev Suraski** ont entièrement réécrit PHP. Cette version — **PHP 3** — a introduit :

- Une syntaxe beaucoup plus cohérente
- Le support des bases de données
- L'extensibilité du langage

PHP commence à être utilisé sur de vrais sites Web commerciaux.

#### 2000 — PHP 4 et le moteur Zend

PHP 4 a introduit le **moteur Zend** (créé par Zeev et Andi — d'où le nom "Ze-nd"). Ce moteur a considérablement amélioré les performances de PHP.

#### 2004 — PHP 5 : la révolution orientée objet

PHP 5 est une **version charnière** dans l'histoire du langage. Elle introduit :

- Un vrai modèle de **programmation orientée objet** (POO)
- Les **exceptions** (gestion des erreurs avancée)
- **PDO** (PHP Data Objects) pour les bases de données
- Les interfaces, les classes abstraites

C'est à partir de PHP 5 que PHP est devenu un vrai langage professionnel.

#### 2015 — PHP 7 : la révolution des performances

PHP 7 a apporté des améliorations majeures :

- **Performances doublées** par rapport à PHP 5
- Introduction du **typage des paramètres et des retours de fonctions**
- Gestion d'erreurs améliorée
- **Opérateur null coalescing** (`??`)

#### 2020 — PHP 8 : modernisation

PHP 8 est la version actuelle majeure et apporte :

- **JIT Compiler** (Just-In-Time) pour encore plus de performances
- **Named arguments** (arguments nommés)
- **Match expression** (alternative plus puissante au switch)
- **Nullsafe operator** (`?->`)
- **Fibers** (PHP 8.1) pour la programmation asynchrone

#### Tableau récapitulatif

| Version | Année | Points clés |
|---------|-------|-------------|
| PHP/FI | 1994 | Scripts CGI basiques |
| PHP 3 | 1997 | Réécriture complète, bases de données |
| PHP 4 | 2000 | Moteur Zend, meilleures performances |
| PHP 5 | 2004 | POO complète, PDO, exceptions |
| PHP 7 | 2015 | x2 performances, typage strict |
| PHP 8 | 2020 | JIT, match, named args |
| PHP 8.3 | 2023 | Améliorations de type, nouvelles fonctions |

---

## 3. Rôle de PHP dans le développement Web

### La distinction Frontend / Backend

Pour comprendre PHP, il faut d'abord comprendre comment un site Web est construit. Il y a deux "mondes" distincts :

```
┌─────────────────────────────────────────────────────────┐
│                    NAVIGATEUR (Client)                   │
│                                                         │
│  ┌─────────┐    ┌─────────┐    ┌──────────────────┐    │
│  │  HTML   │    │   CSS   │    │   JavaScript     │    │
│  │ Structure│   │  Style  │    │  Interactivité   │    │
│  └─────────┘    └─────────┘    └──────────────────┘    │
│                                                         │
│            👁️  FRONTEND (ce que l'utilisateur voit)     │
└─────────────────────────────────────────────────────────┘
                          ↕ HTTP
┌─────────────────────────────────────────────────────────┐
│                    SERVEUR WEB                          │
│                                                         │
│  ┌─────────┐    ┌─────────────┐    ┌────────────────┐  │
│  │   PHP   │    │   MySQL     │    │  Système de    │  │
│  │ Logique │    │  Base de    │    │  fichiers      │  │
│  │ métier  │    │  données    │    │                │  │
│  └─────────┘    └─────────────┘    └────────────────┘  │
│                                                         │
│        ⚙️  BACKEND (ce qui tourne côté serveur)         │
└─────────────────────────────────────────────────────────┘
```

### PHP est un langage Backend

PHP s'exécute **uniquement sur le serveur**. Le navigateur du visiteur ne voit jamais le code PHP — il reçoit uniquement le HTML généré par PHP.

**Comparaison concrète :**

| Technologie | Où s'exécute-t-elle ? | Ce que l'utilisateur voit |
|-------------|----------------------|--------------------------|
| HTML | Navigateur | Le code HTML lui-même |
| CSS | Navigateur | Les styles appliqués |
| JavaScript | Navigateur | Le comportement de la page |
| **PHP** | **Serveur** | **Le HTML généré par PHP** |

### Ce que PHP peut faire

PHP peut :

1. **Générer du HTML dynamiquement** : afficher le prénom de l'utilisateur connecté, la date du jour, etc.
2. **Lire et écrire dans une base de données** : récupérer des articles, enregistrer un formulaire
3. **Gérer les sessions** : "se souvenir" qu'un utilisateur est connecté
4. **Envoyer des e-mails** : notifications, confirmations
5. **Manipuler des fichiers** : lire, créer, modifier, supprimer des fichiers
6. **Traiter des formulaires** : valider et enregistrer les données saisies
7. **Créer des API** : fournir des données à des applications mobiles ou JavaScript

### PHP dans le monde réel

PHP est **le langage côté serveur le plus utilisé au monde** :

- **WordPress** (plus de 43% des sites Web mondiaux) est écrit en PHP
- **Wikipedia** utilise PHP (MediaWiki)
- **Facebook** a été créé en PHP (ils utilisent maintenant HHVM/Hack)
- **Drupal**, **Joomla**, **Magento** : tous en PHP

🔗 [Statistiques d'utilisation W3Techs](https://w3techs.com/technologies/details/pl-php)

---

## 4. PHP côté serveur — Comment ça fonctionne ?

### Le principe fondamental

Quand vous visitez un site PHP, voici ce qui se passe **en coulisses** :

```
1. Vous tapez "www.exemple.com/bonjour.php" dans votre navigateur
              ↓
2. Votre navigateur envoie une REQUÊTE HTTP au serveur
              ↓
3. Le serveur Web (Apache/Nginx) reçoit la requête
              ↓
4. Il voit que le fichier demandé est un .php
              ↓
5. Il transmet le fichier au moteur PHP (Zend Engine)
              ↓
6. PHP exécute le code, interroge la BDD si nécessaire
              ↓
7. PHP produit du HTML pur (le code PHP disparaît)
              ↓
8. Le serveur envoie ce HTML au navigateur
              ↓
9. Votre navigateur affiche la page HTML
```

### Illustration avec du code

Voici un fichier PHP **avant** exécution (ce que le développeur écrit) :

```php
<!DOCTYPE html>
<html>
<body>

<?php
    // Ce code PHP s'exécute sur le serveur
    $prenom = "Alice";
    $heure_actuelle = date("H:i");
    echo "<h1>Bonjour, " . $prenom . " !</h1>";
    echo "<p>Il est actuellement " . $heure_actuelle . "</p>";
?>

</body>
</html>
```

Et voici ce que **le navigateur reçoit** (PHP a disparu, il ne reste que du HTML) :

```html
<!DOCTYPE html>
<html>
<body>

<h1>Bonjour, Alice !</h1>
<p>Il est actuellement 14:35</p>

</body>
</html>
```

> ⚠️ **Point crucial** : Le visiteur ne voit **jamais** votre code PHP. Il ne reçoit que le HTML final. C'est une différence fondamentale avec JavaScript, dont le code source est visible dans le navigateur.

---

## 5. Le cycle HTTP expliqué simplement

### HTTP — Hypertext Transfer Protocol

HTTP est le **protocole de communication** entre votre navigateur et le serveur Web. C'est comme les règles d'une conversation téléphonique : qui parle en premier, comment on formule les demandes, etc.

### La Requête HTTP (Request)

Quand votre navigateur veut une page, il envoie une **requête HTTP**. Voici ce qu'elle contient (simplifié) :

```
GET /index.php HTTP/1.1
Host: www.exemple.com
User-Agent: Mozilla/5.0 (Windows NT 10.0)
Accept: text/html
```

**Décryptage :**
- `GET` : méthode HTTP (je veux récupérer quelque chose)
- `/index.php` : l'URL demandée
- `Host` : le nom de domaine
- `User-Agent` : quel navigateur fait la demande

### La Réponse HTTP (Response)

Le serveur répond avec :

```
HTTP/1.1 200 OK
Content-Type: text/html; charset=UTF-8

<!DOCTYPE html>
<html>
...le HTML généré par PHP...
</html>
```

**Les codes de statut HTTP importants :**

| Code | Signification | Exemple |
|------|---------------|---------|
| 200 | OK — Tout va bien | Page trouvée et renvoyée |
| 301 | Redirection permanente | L'URL a changé |
| 404 | Page non trouvée | La page demandée n'existe pas |
| 500 | Erreur serveur | PHP a planté |
| 403 | Accès interdit | Pas les droits pour accéder |

### Les méthodes HTTP et PHP

PHP interagit avec HTTP via deux méthodes principales :

**GET** — Demande d'information :
```
URL: www.exemple.com/recherche.php?q=php&langue=fr
```
PHP récupère les paramètres via `$_GET` :
```php
<?php
$query = $_GET['q'];       // Contient "php"
$langue = $_GET['langue'];  // Contient "fr"
?>
```

**POST** — Envoi de données (formulaires) :
- Les données ne sont pas visibles dans l'URL
- Utilisé pour les formulaires de connexion, d'inscription, etc.
- PHP récupère les données via `$_POST`

---

## 6. Votre premier programme PHP

### Prérequis

Avant d'écrire du PHP, vous devez avoir installé un serveur local. Consultez le **Module 02** pour l'installation. Pour l'instant, voici la structure de base.

### La balise PHP

Tout code PHP doit être entre les **balises d'ouverture et de fermeture** :

```php
<?php
    // Votre code PHP ici
?>
```

> 💡 Si votre fichier contient **uniquement** du PHP (pas de mélange HTML/PHP), il est recommandé de **ne pas mettre** la balise de fermeture `?>`. Cela évite des problèmes d'espaces blancs indésirables.

### Hello World en PHP

Créez un fichier nommé `bonjour.php` :

```php
<?php
// Mon premier programme PHP
// La fonction echo affiche du texte

echo "Bonjour, le monde !";
?>
```

**Analyse ligne par ligne :**

```php
<?php
```
↑ Indique au serveur que le code PHP commence ici.

```php
// Mon premier programme PHP
```
↑ Un commentaire. Les commentaires commencent par `//`. Ils ne s'affichent pas, ils servent à expliquer le code.

```php
echo "Bonjour, le monde !";
```
↑ `echo` est une instruction qui **affiche** du texte. Le texte est entre guillemets. La ligne se termine par un `;` (point-virgule) — **obligatoire en PHP** !

```php
?>
```
↑ Indique que le code PHP se termine.

### Mélanger PHP et HTML

C'est une des grandes forces de PHP : s'intégrer dans le HTML :

```php
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma première page PHP</title>
</head>
<body>

    <h1>Bienvenue sur mon site</h1>

    <?php
        // PHP calcule la date actuelle
        $date_actuelle = date("d/m/Y");  // Format : jour/mois/année
        $heure_actuelle = date("H:i");    // Format : heure:minutes

        echo "<p>Nous sommes le " . $date_actuelle . "</p>";
        echo "<p>Il est " . $heure_actuelle . "</p>";
    ?>

    <p>Merci de votre visite !</p>

</body>
</html>
```

**Résultat affiché dans le navigateur :**
```
Bienvenue sur mon site
Nous sommes le 15/01/2025
Il est 14:30
Merci de votre visite !
```

### Les commentaires PHP

PHP supporte trois types de commentaires :

```php
<?php

// Commentaire sur une seule ligne — style C++

# Commentaire sur une seule ligne — style shell Unix

/*
   Commentaire
   sur plusieurs
   lignes
*/

/**
 * Commentaire de documentation (PHPDoc)
 * Utilisé pour documenter les fonctions et les classes
 *
 * @param string $nom Le nom de la personne
 * @return string Le message de bienvenue
 */
function direBonjour($nom) {
    return "Bonjour, " . $nom . " !";
}

?>
```

> 💡 **Bonne pratique** : Commentez votre code pour expliquer le **pourquoi**, pas le **quoi**. Par exemple, préférez `// On vérifie que l'utilisateur est majeur` plutôt que `// On compare $age à 18`.

---

## 7. Erreurs fréquentes des débutants

### ❌ Erreur 1 : Oublier le point-virgule

```php
<?php
// ❌ ERREUR : manque le point-virgule
echo "Bonjour"

// ✅ CORRECT
echo "Bonjour";
?>
```

**Résultat de l'erreur :**
```
Parse error: syntax error, unexpected end of file
```

### ❌ Erreur 2 : Confondre guillemets simples et doubles

```php
<?php
$prenom = "Alice";

// ❌ Les guillemets simples n'interprètent PAS les variables
echo 'Bonjour $prenom';   // Affiche : Bonjour $prenom

// ✅ Les guillemets doubles interprètent les variables
echo "Bonjour $prenom";   // Affiche : Bonjour Alice
?>
```

> 💡 **Règle à retenir** : Utilisez les guillemets **doubles** (`"`) pour les chaînes qui contiennent des variables. Utilisez les guillemets **simples** (`'`) pour les chaînes fixes sans variables.

### ❌ Erreur 3 : Oublier `<?php`

```php
// ❌ ERREUR : le serveur va afficher ce texte brut, pas l'exécuter
echo "Bonjour";

// ✅ CORRECT
<?php
echo "Bonjour";
?>
```

### ❌ Erreur 4 : Confondre `=` et `==`

```php
<?php
$age = 18;  // ← '=' est une AFFECTATION (on donne une valeur)

if ($age == 18) {  // ← '==' est une COMPARAISON (on teste si égal)
    echo "Vous avez 18 ans";
}

// ❌ ERREUR CLASSIQUE : utiliser = dans une condition
if ($age = 20) {  // Ceci assigne 20 à $age et est TOUJOURS vrai !
    echo "Ceci s'affiche toujours !";
}
?>
```

### ❌ Erreur 5 : Le fichier n'est pas sur un serveur

PHP ne fonctionne pas en ouvrant directement un fichier `.php` dans votre navigateur. Vous devez passer par un serveur local (XAMPP, WAMP, etc.).

```
❌ MAUVAISE façon d'ouvrir : 
   file:///C:/Users/Alice/Desktop/bonjour.php

✅ BONNE façon d'ouvrir :
   http://localhost/bonjour.php
```

---

## 8. Bonnes pratiques dès le départ

### Nommage des fichiers

```
✅ Bons noms de fichiers PHP :
   index.php
   contact.php
   afficher-article.php
   traitement-formulaire.php

❌ Mauvais noms :
   MonFichierPhP.PHP         (majuscules, mauvaise extension)
   mon fichier.php           (espace dans le nom)
   fichier spécial!.php      (caractères spéciaux)
```

### Structure d'un projet PHP organisé

```
mon-projet/
├── index.php              ← Point d'entrée principal
├── config/
│   └── database.php       ← Configuration base de données
├── includes/
│   ├── header.php         ← En-tête réutilisable
│   └── footer.php         ← Pied de page réutilisable
├── pages/
│   ├── accueil.php
│   └── contact.php
├── css/
│   └── style.css
└── js/
    └── script.js
```

### Indentation et lisibilité

```php
<?php

// ❌ Code illisible sans indentation
function calculerTotal($prix,$tva){$total=$prix*$tva;return $total;}

// ✅ Code lisible avec indentation correcte
function calculerTotal($prix, $tva) {
    $total = $prix * $tva;
    return $total;
}

?>
```

> 💡 **Recommandation** : Utilisez 4 espaces (ou une tabulation équivalente à 4 espaces) pour indenter votre code PHP. C'est la convention la plus répandue dans la communauté PHP (PSR-2).

---

## 9. Exercices pratiques

### Exercice 1 — Votre première page PHP

**Consigne :** Créez un fichier `exercice1.php` qui affiche les informations suivantes :
- Votre prénom
- La date du jour (utilisez la fonction `date()`)
- Un message de bienvenue personnalisé

**Indice :** La fonction `date("d/m/Y")` retourne la date du jour au format `15/01/2025`.

---

### Exercice 2 — Page HTML avec PHP

**Consigne :** Créez une page HTML complète (`exercice2.php`) avec :
- Un titre `<h1>` qui affiche "Bienvenue [votre prénom]"
- Un paragraphe qui affiche le jour de la semaine (indice : `date("l")` en anglais, `date("N")` retourne le numéro du jour)
- Un paragraphe avec l'heure actuelle

---

### Exercice 3 — Commentaires et documentation

**Consigne :** Reprenez l'exercice 2 et ajoutez des commentaires explicatifs sur **chaque ligne** du code PHP.

---

## 10. Corrigés

### Corrigé Exercice 1

```php
<?php
// Exercice 1 — Ma première page PHP

// On stocke le prénom dans une variable
$prenom = "Alice";  // ← Remplacez "Alice" par votre prénom

// On récupère la date du jour
// date("d/m/Y") retourne la date au format : 15/01/2025
$date = date("d/m/Y");

// On affiche les informations
echo "Prénom : " . $prenom . "<br>";      // <br> crée un saut de ligne en HTML
echo "Date : " . $date . "<br>";
echo "Bienvenue, " . $prenom . " ! Nous sommes le " . $date . ".";
?>
```

**Résultat attendu :**
```
Prénom : Alice
Date : 15/01/2025
Bienvenue, Alice ! Nous sommes le 15/01/2025.
```

---

### Corrigé Exercice 2

```php
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma page PHP</title>
</head>
<body>

<?php
    // On définit le prénom
    $prenom = "Alice";

    // On récupère les informations de date et heure
    // date("N") retourne le numéro du jour : 1=Lundi, 7=Dimanche
    $numero_jour = date("N");

    // On crée un tableau associatif pour traduire les numéros en noms
    $jours = [
        1 => "Lundi",
        2 => "Mardi",
        3 => "Mercredi",
        4 => "Jeudi",
        5 => "Vendredi",
        6 => "Samedi",
        7 => "Dimanche"
    ];

    // On récupère le nom du jour
    $nom_jour = $jours[$numero_jour];

    // On récupère l'heure
    $heure = date("H:i");
?>

    <h1>Bienvenue <?php echo $prenom; ?></h1>
    <p>Nous sommes <?php echo $nom_jour; ?>.</p>
    <p>Il est <?php echo $heure; ?>.</p>

</body>
</html>
```

---

### Corrigé Exercice 3

```php
<?php
/*
 * Exercice 3 — Page PHP documentée
 * Auteur : Alice
 * Date : 15/01/2025
 * Description : Affiche une page de bienvenue avec date et heure
 */

// Définition du prénom de l'utilisateur
$prenom = "Alice";

// Récupération du numéro du jour de la semaine
// date("N") retourne un entier entre 1 (Lundi) et 7 (Dimanche)
$numero_jour = date("N");

// Tableau de traduction numéro → nom du jour en français
$jours = [
    1 => "Lundi",   // Premier jour de la semaine
    2 => "Mardi",
    3 => "Mercredi",
    4 => "Jeudi",
    5 => "Vendredi",
    6 => "Samedi",
    7 => "Dimanche" // Septième et dernier jour
];

// Récupération du nom du jour correspondant au numéro
$nom_jour = $jours[$numero_jour];

// Récupération de l'heure actuelle au format HH:MM
$heure = date("H:i");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma page documentée</title>
</head>
<body>
    <!-- Affichage du titre avec le prénom -->
    <h1>Bienvenue <?php echo $prenom; ?></h1>

    <!-- Affichage du jour de la semaine -->
    <p>Nous sommes <?php echo $nom_jour; ?>.</p>

    <!-- Affichage de l'heure courante -->
    <p>Il est <?php echo $heure; ?>.</p>
</body>
</html>
```

---

## Résumé du module 01

| Concept | Ce qu'il faut retenir |
|---------|----------------------|
| PHP | Langage serveur, génère du HTML dynamique |
| Exécution | Sur le serveur, jamais visible par l'utilisateur |
| Balises | `<?php ... ?>` délimitent le code PHP |
| `echo` | Affiche du contenu |
| `;` | Obligatoire à la fin de chaque instruction |
| Commentaires | `//` une ligne, `/* */` plusieurs lignes |
| HTTP | Protocole de communication navigateur ↔ serveur |

---

**➡️ Module suivant : [Module 02 — Installation et environnement de développement](../02-installation/README.md)**

🔗 [Retour au sommaire principal](../../README.md)
