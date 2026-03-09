# Module 16 — Architecture MVC en PHP

> **Niveau :** Avancé
> **Durée estimée :** 5 à 6 heures
> **Prérequis :** Modules 01 à 15 (notamment POO — Module 08)

---

## Table des matières

1. [Qu'est-ce que l'architecture MVC ?](#1-quest-ce-que-larchitecture-mvc-)
2. [Structure d'un projet MVC](#2-structure-dun-projet-mvc)
3. [Le Modèle (Model)](#3-le-modèle-model)
4. [La Vue (View)](#4-la-vue-view)
5. [Le Contrôleur (Controller)](#5-le-contrôleur-controller)
6. [Le Routeur](#6-le-routeur)
7. [Le Front Controller](#7-le-front-controller)
8. [Exemple complet : Mini-blog MVC](#8-exemple-complet--mini-blog-mvc)
9. [Autoloading avec Composer](#9-autoloading-avec-composer)
10. [Bonnes pratiques MVC](#10-bonnes-pratiques-mvc)

---

## 1. Qu'est-ce que l'architecture MVC ?

MVC signifie **Model-View-Controller**. C'est un patron de conception qui **sépare** les responsabilités d'une application en trois couches distinctes.

### Avant MVC : le code spaghetti

```php
<?php
// ❌ Code non structuré (tout mélangé dans un seul fichier)
$pdo = new PDO("mysql:host=localhost;dbname=blog", "root", "");
$articles = $pdo->query("SELECT * FROM articles")->fetchAll();
?>
<html>
<head><title>Blog</title></head>
<body>
<?php foreach ($articles as $art): ?>
    <h2><?= $art['titre'] ?></h2>
    <p><?= $art['contenu'] ?></p>
<?php endforeach; ?>
</body>
</html>
```

**Problèmes :** logique BDD, HTML et logique métier tous mélangés → impossible à maintenir.

### Après MVC : séparation des responsabilités

```
┌─────────────────────────────────────────────────────────┐
│                    Architecture MVC                     │
│                                                         │
│  ┌────────────────┐     ┌────────────────┐              │
│  │   MODÈLE (M)   │     │    VUE (V)     │              │
│  │                │     │                │              │
│  │ • Données      │     │ • Affichage    │              │
│  │ • Base de      │     │ • HTML/CSS     │              │
│  │   données      │     │ • Templates    │              │
│  │ • Règles       │     │ • Aucune       │              │
│  │   métier       │     │   logique      │              │
│  └───────┬────────┘     └────────┬───────┘              │
│          │                       │                      │
│          └──────────┬────────────┘                      │
│                     │                                   │
│            ┌────────▼────────┐                          │
│            │ CONTRÔLEUR (C)  │                          │
│            │                 │                          │
│            │ • Reçoit la     │ ← Requête HTTP           │
│            │   requête       │                          │
│            │ • Appelle le    │                          │
│            │   Modèle        │                          │
│            │ • Passe données │                          │
│            │   à la Vue      │                          │
│            └─────────────────┘                          │
└─────────────────────────────────────────────────────────┘
```

### Les responsabilités de chaque couche

| Couche | Rôle | Contient |
|--------|------|----------|
| **Model** | Données et logique métier | Classes PHP, requêtes SQL |
| **View** | Présentation | HTML, CSS, peu de PHP |
| **Controller** | Coordination | Logique de flux, appels Model/View |

---

## 2. Structure d'un projet MVC

```
mon-projet-mvc/
│
├── public/                  ← Seul dossier accessible depuis le Web
│   ├── index.php            ← Front Controller (point d'entrée unique)
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── app.js
│
├── src/                     ← Code PHP de l'application (non accessible directement)
│   ├── Controllers/
│   │   ├── ArticleController.php
│   │   ├── UserController.php
│   │   └── HomeController.php
│   ├── Models/
│   │   ├── Article.php
│   │   └── User.php
│   └── Core/
│       ├── Router.php
│       ├── Controller.php   ← Contrôleur de base (classe parente)
│       └── Database.php
│
├── views/                   ← Templates HTML/PHP
│   ├── layouts/
│   │   ├── header.php
│   │   └── footer.php
│   ├── articles/
│   │   ├── liste.php
│   │   ├── detail.php
│   │   └── formulaire.php
│   └── home/
│       └── index.php
│
├── config/
│   └── config.php           ← Configuration de l'application
│
├── .htaccess                ← Redirige tout vers public/index.php
└── composer.json            ← Autoloading et dépendances
```

---

## 3. Le Modèle (Model)

```php
<?php
// src/Models/Article.php
namespace App\Models;

use App\Core\Database;
use PDO;

/**
 * Modèle Article : représente les articles du blog et gère les accès BDD.
 */
class Article {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    /**
     * Récupère tous les articles publiés, du plus récent au plus ancien.
     * @return array<int, array<string, mixed>>
     */
    public function findAllPublies(): array {
        $stmt = $this->pdo->prepare("
            SELECT a.id, a.titre, a.contenu, a.cree_le,
                   u.prenom, u.nom
            FROM   articles a
            JOIN   utilisateurs u ON a.auteur_id = u.id
            WHERE  a.publie = 1
            ORDER  BY a.cree_le DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Récupère un article par son ID.
     */
    public function findById(int $id): ?array {
        $stmt = $this->pdo->prepare("
            SELECT a.*, u.prenom, u.nom
            FROM   articles a
            JOIN   utilisateurs u ON a.auteur_id = u.id
            WHERE  a.id = :id AND a.publie = 1
        ");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result !== false ? $result : null;
    }

    /**
     * Crée un nouvel article.
     */
    public function create(string $titre, string $contenu, int $auteurId): int {
        $stmt = $this->pdo->prepare("
            INSERT INTO articles (titre, contenu, auteur_id, publie)
            VALUES (:titre, :contenu, :auteur_id, 0)
        ");
        $stmt->execute([
            ':titre'     => trim($titre),
            ':contenu'   => trim($contenu),
            ':auteur_id' => $auteurId,
        ]);
        return (int) $this->pdo->lastInsertId();
    }
}
?>
```

---

## 4. La Vue (View)

```php
<!-- views/articles/liste.php -->
<!-- La vue NE contient que de l'affichage. Pas de logique métier ! -->

<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<main class="container">
    <h1>Tous nos articles</h1>

    <?php if (empty($articles)): ?>
        <p class="message-vide">Aucun article publié pour le moment.</p>
    <?php else: ?>

        <div class="grille-articles">
            <?php foreach ($articles as $article): ?>
                <article class="carte-article">
                    <h2>
                        <a href="/article/<?= (int) $article['id'] ?>">
                            <?= htmlspecialchars($article['titre'], ENT_QUOTES, 'UTF-8') ?>
                        </a>
                    </h2>

                    <p class="meta-article">
                        Par <?= htmlspecialchars($article['prenom'] . ' ' . $article['nom']) ?>
                        — Le <?= date('d/m/Y', strtotime($article['cree_le'])) ?>
                    </p>

                    <p class="extrait">
                        <?= htmlspecialchars(mb_substr($article['contenu'], 0, 150)) ?>...
                    </p>

                    <a href="/article/<?= (int) $article['id'] ?>" class="btn">
                        Lire la suite →
                    </a>
                </article>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
```

```php
<!-- views/layouts/header.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titrePage ?? 'Mon Blog PHP') ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header class="site-header">
        <nav>
            <a href="/" class="logo">Mon Blog</a>
            <ul>
                <li><a href="/">Accueil</a></li>
                <li><a href="/articles">Articles</a></li>
                <li><a href="/contact">Contact</a></li>
            </ul>
        </nav>
    </header>
```

---

## 5. Le Contrôleur (Controller)

```php
<?php
// src/Controllers/ArticleController.php
namespace App\Controllers;

use App\Models\Article;
use App\Core\Controller;

/**
 * Contrôleur gérant les actions liées aux articles.
 */
class ArticleController extends Controller {
    private Article $articleModel;

    public function __construct() {
        $this->articleModel = new Article();
    }

    /**
     * Action : Affiche la liste de tous les articles.
     * Route : GET /articles
     */
    public function liste(): void {
        // 1. Demander les données au Modèle
        $articles = $this->articleModel->findAllPublies();

        // 2. Préparer les données pour la Vue
        $titrePage = "Liste des articles";

        // 3. Charger la Vue avec les données
        $this->render('articles/liste', compact('articles', 'titrePage'));
    }

    /**
     * Action : Affiche un article spécifique.
     * Route : GET /article/{id}
     */
    public function detail(int $id): void {
        $article = $this->articleModel->findById($id);

        // Vérifier que l'article existe
        if ($article === null) {
            $this->pageNonTrouvee();
            return;
        }

        $titrePage = htmlspecialchars($article['titre']);
        $this->render('articles/detail', compact('article', 'titrePage'));
    }

    /**
     * Action : Affiche le formulaire de création d'article.
     * Route : GET /article/creer
     */
    public function formulaireCreation(): void {
        // Vérifier que l'utilisateur est connecté
        if (!$this->estConnecte()) {
            $this->rediriger('/connexion');
            return;
        }

        $titrePage = "Créer un article";
        $this->render('articles/formulaire', compact('titrePage'));
    }

    /**
     * Action : Traite la soumission du formulaire de création.
     * Route : POST /article/creer
     */
    public function creer(): void {
        if (!$this->estConnecte()) {
            $this->rediriger('/connexion');
            return;
        }

        // Valider le token CSRF
        if (!$this->verifierCSRF($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Requête invalide.");
        }

        $titre   = trim($_POST['titre']   ?? '');
        $contenu = trim($_POST['contenu'] ?? '');
        $erreurs = [];

        if (strlen($titre) < 5) {
            $erreurs[] = "Le titre doit faire au moins 5 caractères.";
        }
        if (strlen($contenu) < 20) {
            $erreurs[] = "Le contenu doit faire au moins 20 caractères.";
        }

        if (!empty($erreurs)) {
            $titrePage = "Créer un article";
            $this->render('articles/formulaire', compact('titrePage', 'erreurs', 'titre', 'contenu'));
            return;
        }

        $id = $this->articleModel->create($titre, $contenu, $_SESSION['user_id']);
        $this->rediriger('/article/' . $id);
    }
}
?>
```

```php
<?php
// src/Core/Controller.php — Classe de base pour tous les contrôleurs
namespace App\Core;

abstract class Controller {
    /**
     * Charge et affiche une vue avec des variables.
     */
    protected function render(string $vue, array $donnees = []): void {
        // Extraire les variables dans le scope local
        extract($donnees);

        $cheminVue = __DIR__ . '/../../views/' . $vue . '.php';

        if (!file_exists($cheminVue)) {
            throw new \RuntimeException("Vue introuvable : {$cheminVue}");
        }

        require $cheminVue;
    }

    protected function rediriger(string $url): void {
        header('Location: ' . $url);
        exit();
    }

    protected function estConnecte(): bool {
        return isset($_SESSION['user_id']);
    }

    protected function verifierCSRF(string $token): bool {
        return isset($_SESSION['csrf_token'])
            && hash_equals($_SESSION['csrf_token'], $token);
    }

    protected function pageNonTrouvee(): void {
        http_response_code(404);
        $this->render('errors/404', ['titrePage' => 'Page non trouvée']);
    }
}
?>
```

---

## 6. Le Routeur

```php
<?php
// src/Core/Router.php
namespace App\Core;

/**
 * Routeur simple : associe des URLs à des actions de contrôleurs.
 */
class Router {
    private array $routes = [];

    /**
     * Enregistre une route GET.
     */
    public function get(string $pattern, string $controleur, string $action): void {
        $this->routes['GET'][] = compact('pattern', 'controleur', 'action');
    }

    /**
     * Enregistre une route POST.
     */
    public function post(string $pattern, string $controleur, string $action): void {
        $this->routes['POST'][] = compact('pattern', 'controleur', 'action');
    }

    /**
     * Traite la requête courante et exécute l'action correspondante.
     */
    public function dispatcher(): void {
        $methode = $_SERVER['REQUEST_METHOD'];
        $uri     = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes[$methode] ?? [] as $route) {
            // Convertir le pattern en regex
            // Ex: '/article/{id}' → '/article/([0-9]+)'
            $regex = preg_replace('/\{([a-z]+)\}/', '([^/]+)', $route['pattern']);
            $regex = '#^' . $regex . '$#';

            if (preg_match($regex, $uri, $matches)) {
                array_shift($matches);  // Supprimer le match complet

                // Instancier le contrôleur
                $classeControleur = 'App\\Controllers\\' . $route['controleur'];
                $controleur = new $classeControleur();

                // Appeler l'action avec les paramètres capturés
                call_user_func_array([$controleur, $route['action']], $matches);
                return;
            }
        }

        // Aucune route correspondante : 404
        http_response_code(404);
        require __DIR__ . '/../../views/errors/404.php';
    }
}
?>
```

---

## 7. Le Front Controller

```php
<?php
// public/index.php — Point d'entrée UNIQUE de l'application
declare(strict_types=1);

// Démarrer la session de façon sécurisée
ini_set('session.cookie_httponly', '1');
ini_set('session.use_strict_mode', '1');
session_start();

// Chargement de l'autoloader Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Chargement de la configuration
require_once __DIR__ . '/../config/config.php';

// Initialisation du routeur
$router = new App\Core\Router();

// ---- Déclaration des routes ----

// Pages publiques
$router->get('/',           'HomeController',    'index');
$router->get('/articles',   'ArticleController', 'liste');
$router->get('/article/{id}', 'ArticleController', 'detail');

// Authentification
$router->get('/connexion',  'AuthController',    'formulaireConnexion');
$router->post('/connexion', 'AuthController',    'connecter');
$router->get('/inscription','AuthController',    'formulaireInscription');
$router->post('/inscription','AuthController',   'inscrire');
$router->get('/deconnexion','AuthController',    'deconnecter');

// Articles (zone protégée)
$router->get('/article/creer',   'ArticleController', 'formulaireCreation');
$router->post('/article/creer',  'ArticleController', 'creer');
$router->get('/article/{id}/modifier', 'ArticleController', 'formulaireModification');
$router->post('/article/{id}/modifier','ArticleController', 'modifier');
$router->post('/article/{id}/supprimer','ArticleController','supprimer');

// Dispatcher la requête courante
$router->dispatcher();
?>
```

---

## 8. Exemple complet : Mini-blog MVC

```php
<?php
// config/config.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'mini_blog');
define('DB_USER', 'root');
define('DB_PASS', '');
define('APP_URL',  'http://localhost/mon-projet-mvc/public');
define('APP_ENV',  'development');  // 'production' en prod

// Affichage des erreurs selon l'environnement
if (APP_ENV === 'development') {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
}
?>
```

```apache
# .htaccess (à la racine du projet)
# Redirige toutes les requêtes vers public/index.php

RewriteEngine On

# Si la ressource demandée n'existe pas physiquement
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

# Redirige vers public/index.php
RewriteRule ^(.*)$ public/index.php [QSA,L]
```

```apache
# public/.htaccess
# Dans le dossier public, redirige vers index.php

RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]
```

---

## 9. Autoloading avec Composer

```json
// composer.json
{
    "name": "mon-projet/mini-blog",
    "description": "Mini blog en PHP avec architecture MVC",
    "require": {
        "php": ">=8.0"
    },
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    }
}
```

```bash
# Installer l'autoloader
composer install

# Recharger l'autoloader après ajout de nouvelles classes
composer dump-autoload
```

Avec cette configuration, PHP chargera automatiquement les classes :
- `App\Controllers\ArticleController` → `src/Controllers/ArticleController.php`
- `App\Models\Article` → `src/Models/Article.php`
- `App\Core\Router` → `src/Core/Router.php`

---

## 10. Bonnes pratiques MVC

| Principe | Description |
|----------|-------------|
| **Thin Controllers** | Les contrôleurs doivent être courts. La logique métier va dans les Models ou Services. |
| **Fat Models** | Les Models contiennent toute la logique de données. |
| **Views stupides** | Les vues ne contiennent que du PHP d'affichage (pas de SQL, pas de logique). |
| **DRY** | Réutilisez les layouts, les helpers, les traits. |
| **Un fichier = une classe** | Chaque classe PHP dans son propre fichier. |
| **Namespaces** | Organisez avec des espaces de noms PSR-4. |

---

## Résumé du module 16

| Couche | Responsabilité | Emplacement |
|--------|---------------|-------------|
| Model | Données + logique métier + BDD | `src/Models/` |
| View | Affichage HTML | `views/` |
| Controller | Coordination + flux | `src/Controllers/` |
| Router | URL → Controller | `src/Core/Router.php` |
| Front Controller | Point d'entrée unique | `public/index.php` |

---

**➡️ Module suivant : [Module 17 — Écosystème PHP](../17-ecosysteme/README.md)**

🔗 [Retour au sommaire principal](../../README.md)
