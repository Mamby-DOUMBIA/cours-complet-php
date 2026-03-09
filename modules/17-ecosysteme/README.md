# Module 17 — Écosystème PHP

> **Niveau :** Avancé → Expert
> **Durée estimée :** 6 à 8 heures
> **Prérequis :** Modules 01 à 16

---

## Table des matières

1. [Composer — Le gestionnaire de dépendances](#1-composer--le-gestionnaire-de-dépendances)
2. [Laravel — Le framework moderne](#2-laravel--le-framework-moderne)
3. [Symfony — Le framework robuste](#3-symfony--le-framework-robuste)
4. [PHPUnit — Les tests automatisés](#4-phpunit--les-tests-automatisés)
5. [Outils complémentaires](#5-outils-complémentaires)

---

## 1. Composer — Le gestionnaire de dépendances

🔗 [getcomposer.org](https://getcomposer.org/)

Composer est l'outil indispensable de tout développeur PHP moderne. Il permet de :
- **Installer des bibliothèques** tierces en une commande
- **Gérer les versions** des dépendances
- **Autocharger** les classes automatiquement (PSR-4)

### Installation

```bash
# Linux / Mac
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Vérification
composer --version
# Composer version 2.x.x
```

### Commandes essentielles

```bash
# Initialiser un projet
composer init

# Installer une dépendance
composer require vendor/package
composer require guzzlehttp/guzzle           # Client HTTP
composer require vlucas/phpdotenv            # Variables d'environnement
composer require monolog/monolog             # Journalisation

# Installer une dépendance de développement uniquement
composer require --dev phpunit/phpunit
composer require --dev squizlabs/php_codesniffer

# Installer toutes les dépendances (depuis composer.json)
composer install

# Mettre à jour les dépendances
composer update

# Recharger l'autoloader
composer dump-autoload

# Afficher les dépendances installées
composer show
```

### Structure de composer.json

```json
{
    "name": "mon-entreprise/mon-projet",
    "description": "Description du projet",
    "type": "project",
    "require": {
        "php": ">=8.1",
        "guzzlehttp/guzzle": "^7.0",
        "vlucas/phpdotenv": "^5.0",
        "monolog/monolog": "^3.0"
    },
    "require-dev": {
        "phpunit/phpunit": "^10.0",
        "friendsofphp/php-cs-fixer": "^3.0"
    },
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        },
        "files": [
            "src/helpers.php"
        ]
    },
    "autoload-dev": {
        "psr-4": {
            "Tests\\": "tests/"
        }
    },
    "scripts": {
        "test": "vendor/bin/phpunit",
        "cs-fix": "vendor/bin/php-cs-fixer fix src/"
    }
}
```

### Utiliser une bibliothèque Composer : Exemple avec Guzzle

```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

// Guzzle — Client HTTP pour appeler des APIs externes
$client = new Client([
    'base_uri' => 'https://jsonplaceholder.typicode.com',
    'timeout'  => 5.0,
]);

try {
    // GET request
    $response = $client->request('GET', '/posts/1');
    $body     = json_decode($response->getBody()->getContents(), true);

    echo "Titre : " . $body['title'] . PHP_EOL;

    // POST request
    $response = $client->request('POST', '/posts', [
        'json' => [
            'title'  => 'Mon article',
            'body'   => 'Contenu de l\'article',
            'userId' => 1,
        ]
    ]);

    $article = json_decode($response->getBody()->getContents(), true);
    echo "Article créé avec l'ID : " . $article['id'] . PHP_EOL;

} catch (RequestException $e) {
    echo "Erreur HTTP : " . $e->getMessage() . PHP_EOL;
}
?>
```

### Utiliser phpdotenv pour les variables d'environnement

```bash
# .env (à la racine du projet — NE PAS committer dans Git !)
DB_HOST=localhost
DB_NAME=ma_base
DB_USER=root
DB_PASS=mon_mot_de_passe_secret
APP_ENV=development
APP_DEBUG=true
MAIL_FROM=noreply@monsite.com
```

```php
<?php
// index.php
require_once 'vendor/autoload.php';

use Dotenv\Dotenv;

// Charger les variables d'environnement depuis .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Accéder aux variables
$dbHost = $_ENV['DB_HOST'];
$dbName = $_ENV['DB_NAME'];
$appEnv = $_ENV['APP_ENV'];

echo "Environnement : " . $appEnv . PHP_EOL;
?>
```

```
# .gitignore — OBLIGATOIRE pour ne pas exposer .env
.env
vendor/
```

---

## 2. Laravel — Le framework moderne

🔗 [laravel.com/docs](https://laravel.com/docs)

Laravel est le framework PHP le plus populaire. Il fournit tout ce dont une application Web a besoin.

### Installation d'un projet Laravel

```bash
# Prérequis : PHP 8.1+, Composer, Node.js

# Créer un nouveau projet Laravel
composer create-project laravel/laravel mon-blog

# Ou avec l'installeur Laravel (plus rapide)
composer global require laravel/installer
laravel new mon-blog

# Démarrer le serveur de développement
cd mon-blog
php artisan serve
# Application accessible sur http://localhost:8000
```

### Structure d'un projet Laravel

```
mon-blog/
├── app/
│   ├── Http/
│   │   ├── Controllers/      ← Contrôleurs
│   │   └── Middleware/       ← Middlewares
│   ├── Models/               ← Modèles Eloquent
│   └── Providers/
├── config/                   ← Fichiers de configuration
├── database/
│   ├── migrations/           ← Migrations de base de données
│   └── seeders/              ← Données de test
├── resources/
│   └── views/                ← Templates Blade
├── routes/
│   ├── web.php               ← Routes Web
│   └── api.php               ← Routes API
├── storage/                  ← Logs, cache, fichiers uploadés
├── tests/                    ← Tests automatisés
├── .env                      ← Variables d'environnement
└── artisan                   ← Outil en ligne de commande Laravel
```

### Concepts clés de Laravel

**Routes (routes/web.php) :**
```php
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;

// Route simple
Route::get('/', function () {
    return view('welcome');
});

// Routes resource (CRUD complet en une ligne)
Route::resource('articles', ArticleController::class);
// Génère automatiquement :
// GET    /articles           → index()
// GET    /articles/create    → create()
// POST   /articles           → store()
// GET    /articles/{id}      → show()
// GET    /articles/{id}/edit → edit()
// PUT    /articles/{id}      → update()
// DELETE /articles/{id}      → destroy()

// Routes protégées (authentification requise)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::resource('articles', ArticleController::class)->except(['index', 'show']);
});
```

**Modèle Eloquent (app/Models/Article.php) :**
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model {
    // Champs pouvant être remplis en masse
    protected $fillable = ['titre', 'contenu', 'user_id', 'publie'];

    // Relation : un article appartient à un utilisateur
    public function auteur() {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scope : filtre les articles publiés
    public function scopePublies($query) {
        return $query->where('publie', true);
    }
}

// Utilisation d'Eloquent :
$articles = Article::publies()->latest()->paginate(10);
$article  = Article::findOrFail(1);
$article  = Article::where('titre', 'like', '%PHP%')->first();

// Créer
$article  = Article::create(['titre' => 'Mon article', 'contenu' => '...']);

// Mettre à jour
$article->update(['titre' => 'Nouveau titre']);

// Supprimer
$article->delete();
?>
```

**Contrôleur (app/Http/Controllers/ArticleController.php) :**
```php
<?php
namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller {
    public function index() {
        $articles = Article::publies()->with('auteur')->latest()->paginate(10);
        return view('articles.index', compact('articles'));
    }

    public function show(Article $article) {
        // Laravel résout automatiquement $article depuis l'ID en URL (Route Model Binding)
        return view('articles.show', compact('article'));
    }

    public function store(Request $request) {
        // Validation intégrée de Laravel
        $donnees = $request->validate([
            'titre'   => 'required|min:5|max:255',
            'contenu' => 'required|min:20',
        ]);

        $article = auth()->user()->articles()->create($donnees);
        return redirect()->route('articles.show', $article)->with('success', 'Article créé !');
    }
}
?>
```

**Template Blade (resources/views/articles/index.blade.php) :**
```html
@extends('layouts.app')

@section('title', 'Tous les articles')

@section('content')
    <h1>Nos articles</h1>

    @forelse($articles as $article)
        <article>
            <h2><a href="{{ route('articles.show', $article) }}">{{ $article->titre }}</a></h2>
            <p>Par {{ $article->auteur->name }} — {{ $article->created_at->diffForHumans() }}</p>
            <p>{{ Str::limit($article->contenu, 150) }}</p>
        </article>
    @empty
        <p>Aucun article pour le moment.</p>
    @endforelse

    {{ $articles->links() }}  {{-- Pagination automatique --}}
@endsection
```

**Migrations (database/migrations/..._create_articles_table.php) :**
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('contenu');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('publie')->default(false);
            $table->timestamps();  // created_at + updated_at automatiques
        });
    }

    public function down(): void {
        Schema::dropIfExists('articles');
    }
};
```

```bash
# Exécuter les migrations (créer les tables)
php artisan migrate

# Revenir en arrière
php artisan migrate:rollback

# Régénérer toutes les tables
php artisan migrate:fresh --seed
```

### Artisan — L'outil en ligne de commande

```bash
php artisan make:model Article -mcr      # Model + Migration + Controller resource
php artisan make:controller ArticleController --resource
php artisan make:migration create_articles_table
php artisan make:middleware CheckAdmin
php artisan make:request StoreArticleRequest
php artisan make:seeder ArticleSeeder
php artisan route:list                   # Liste toutes les routes
php artisan tinker                       # REPL interactif Laravel
```

---

## 3. Symfony — Le framework robuste

🔗 [symfony.com/doc](https://symfony.com/doc/current/index.html)

Symfony est un framework très mature, modulaire, utilisé pour des applications d'entreprise. C'est aussi la base de nombreux frameworks (Laravel utilise des composants Symfony !).

### Installation

```bash
# Avec l'outil Symfony CLI
symfony new mon-projet --webapp

# Ou avec Composer
composer create-project symfony/skeleton mon-projet
composer require webapp  # Installe tous les composants Web

# Démarrer le serveur
symfony serve
# Ou
php -S localhost:8000 -t public/
```

### Architecture Symfony

```php
<?php
// src/Controller/ArticleController.php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArticleController extends AbstractController {

    // Route définie via attribut PHP 8
    #[Route('/articles', name: 'article_liste', methods: ['GET'])]
    public function liste(ArticleRepository $repo): Response {
        $articles = $repo->findBy(['publie' => true], ['creeLe' => 'DESC']);

        // render() retourne une Response avec le template Twig rendu
        return $this->render('article/liste.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/article/{id}', name: 'article_detail', requirements: ['id' => '\d+'])]
    public function detail(Article $article): Response {  // Injection automatique via ID
        return $this->render('article/detail.html.twig', [
            'article' => $article,
        ]);
    }
}
?>
```

**Template Twig :**
```twig
{# templates/article/liste.html.twig #}
{% extends 'base.html.twig' %}

{% block title %}Nos articles{% endblock %}

{% block body %}
    <h1>Tous les articles</h1>

    {% for article in articles %}
        <article>
            <h2>
                <a href="{{ path('article_detail', {id: article.id}) }}">
                    {{ article.titre }}
                </a>
            </h2>
            <p>{{ article.contenu|slice(0, 150) }}...</p>
        </article>
    {% else %}
        <p>Aucun article.</p>
    {% endfor %}
{% endblock %}
```

---

## 4. PHPUnit — Les tests automatisés

🔗 [phpunit.de](https://phpunit.de/)

Les tests automatisés garantissent que votre code fonctionne correctement, même après des modifications.

### Installation

```bash
composer require --dev phpunit/phpunit
```

### Écrire des tests

```php
<?php
// tests/Unit/CalculatriceTest.php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\Calculatrice;

/**
 * Tests unitaires pour la classe Calculatrice.
 * 
 * Convention de nommage :
 * - Classe de test : NomDeLaClasse + Test
 * - Méthode de test : test + DescriptionDuComportement
 */
class CalculatriceTest extends TestCase {
    private Calculatrice $calc;

    // setUp() s'exécute avant CHAQUE test
    protected function setUp(): void {
        $this->calc = new Calculatrice();
    }

    public function testAdditionnerDeuxEntiers(): void {
        $resultat = $this->calc->additionner(5, 3);
        $this->assertEquals(8, $resultat);
    }

    public function testAdditionnerNombresNegatifs(): void {
        $resultat = $this->calc->additionner(-5, -3);
        $this->assertEquals(-8, $resultat);
    }

    public function testDivisionParZeroLeveUneException(): void {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Division par zéro impossible");

        $this->calc->diviser(10, 0);
    }

    /**
     * Test avec plusieurs jeux de données (@dataProvider)
     *
     * @dataProvider fournisseurDonneesSomme
     */
    public function testAdditionnerAvecPlusieursValeurs(int $a, int $b, int $attendu): void {
        $this->assertEquals($attendu, $this->calc->additionner($a, $b));
    }

    public static function fournisseurDonneesSomme(): array {
        return [
            'positifs'  => [2,   3,   5],
            'negatifs'  => [-2, -3,  -5],
            'mixtes'    => [-2,  5,   3],
            'avec zero' => [0,   7,   7],
        ];
    }
}
?>
```

### Assertions courantes PHPUnit

```php
<?php
// Égalité
$this->assertEquals(8, $resultat);        // == avec conversion
$this->assertSame(8, $resultat);           // === sans conversion (recommandé)
$this->assertNotEquals(9, $resultat);

// Types
$this->assertIsInt($resultat);
$this->assertIsString($texte);
$this->assertIsArray($tableau);
$this->assertNull($valeur);
$this->assertNotNull($valeur);

// Booléens
$this->assertTrue($condition);
$this->assertFalse($condition);

// Tableaux
$this->assertCount(3, $tableau);           // 3 éléments
$this->assertContains('PHP', $tableau);    // Contient 'PHP'
$this->assertArrayHasKey('nom', $tableau); // Clé 'nom' présente

// Chaînes
$this->assertStringContainsString('PHP', $texte);
$this->assertStringStartsWith('Bonjour', $texte);
$this->assertMatchesRegularExpression('/^\d+$/', $texte);

// Exceptions
$this->expectException(\RuntimeException::class);
?>
```

### Exécuter les tests

```bash
# Tous les tests
vendor/bin/phpunit

# Avec rapport de couverture de code
vendor/bin/phpunit --coverage-html coverage/

# Un fichier spécifique
vendor/bin/phpunit tests/Unit/CalculatriceTest.php

# Un test spécifique
vendor/bin/phpunit --filter testAdditionnerDeuxEntiers
```

**phpunit.xml :**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true">
    <testsuites>
        <testsuite name="Unit Tests">
            <directory>tests/Unit</directory>
        </testsuite>
        <testsuite name="Integration Tests">
            <directory>tests/Integration</directory>
        </testsuite>
    </testsuites>
    <source>
        <include>
            <directory>src</directory>
        </include>
    </source>
</phpunit>
```

---

## 5. Outils complémentaires

| Outil | Description | Lien |
|-------|-------------|------|
| **PHP-CS-Fixer** | Formateur de code selon les standards PSR | [github.com/PHP-CS-Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer) |
| **PHPStan** | Analyse statique du code (détecte les bugs avant l'exécution) | [phpstan.org](https://phpstan.org/) |
| **Xdebug** | Débogueur PHP | [xdebug.org](https://xdebug.org/) |
| **Rector** | Migration et modernisation automatique du code PHP | [getrector.com](https://getrector.com/) |
| **Deployer** | Déploiement automatisé | [deployer.org](https://deployer.org/) |

### PHPStan — Analyse statique

```bash
composer require --dev phpstan/phpstan
vendor/bin/phpstan analyse src --level 8
```

```php
<?php
// Exemple de code que PHPStan peut détecter :
function traiter(?string $texte): string {
    return strtoupper($texte);  // ← PHPStan alerte : $texte peut être null !
}

// Version corrigée :
function traiter(?string $texte): string {
    return strtoupper($texte ?? '');  // ✅ Gestion du null
}
?>
```

---

## Résumé du module 17

| Outil | Rôle | Commande de base |
|-------|------|-----------------|
| **Composer** | Gestionnaire de dépendances | `composer require` |
| **Laravel** | Framework Web complet | `php artisan serve` |
| **Symfony** | Framework modulaire entreprise | `symfony serve` |
| **PHPUnit** | Tests automatisés | `vendor/bin/phpunit` |
| **PHPStan** | Analyse statique | `vendor/bin/phpstan analyse` |

---

**➡️ Module suivant : [Module 18 — Performance et optimisation](../18-performance/README.md)**

🔗 [Retour au sommaire principal](../../README.md)
