# Module 15 — PHP Avancé

> **Niveau :** Avancé → Expert | **Durée :** 5 heures

---

## 1. Namespaces

```php
<?php
// Déclaration de namespace (doit être la première instruction du fichier)
namespace App\Services;

// Import d'autres namespaces
use App\Models\User;
use App\Repositories\UserRepository as UserRepo;
use PDO;

class UserService {
    public function __construct(private UserRepo $repo) {}
}
```

---

## 2. Autoloading PSR-4

```php
<?php
// Avec Composer (recommandé) — composer.json :
// "autoload": { "psr-4": { "App\\": "src/" } }

// Sans Composer (manuel)
spl_autoload_register(function(string $classe): void {
    $chemin = __DIR__ . '/src/' . str_replace('\\', '/', $classe) . '.php';
    if (file_exists($chemin)) {
        require $chemin;
    }
});
```

---

## 3. Traits avancés

```php
<?php
trait Singleton {
    private static ?self $instance = null;

    private function __construct() {}

    public static function getInstance(): static {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }
}

class Config { use Singleton; }
$config = Config::getInstance();
```

---

## 4. Générateurs (Generators)

```php
<?php
// Un générateur produit des valeurs à la demande (lazy evaluation)
// Économise énormément de mémoire pour les grands volumes

function nombresPairs(int $debut, int $fin): Generator {
    for ($i = $debut; $i <= $fin; $i += 2) {
        yield $i;  // Produit une valeur et suspend la fonction
    }
}

foreach (nombresPairs(0, 1000000) as $nombre) {
    echo $nombre . " ";  // Génère les nombres à la demande (pas en mémoire d'un coup)
}

// Générateur avec clés
function lireGrandFichier(string $chemin): Generator {
    $handle = fopen($chemin, 'r');
    $ligne  = 0;
    while (($contenu = fgets($handle)) !== false) {
        yield $ligne++ => trim($contenu);
    }
    fclose($handle);
}

foreach (lireGrandFichier('grand-fichier.txt') as $numero => $ligne) {
    echo $numero . ": " . $ligne . PHP_EOL;
}
```

---

## 5. Fonctionnalités PHP 8.x

```php
<?php
// Named arguments (PHP 8.0+)
function creerElement(string $tag, string $contenu, string $classe = '', string $id = ''): string {
    $attrs = $classe ? " class=\"$classe\"" : "";
    $attrs .= $id ? " id=\"$id\"" : "";
    return "<$tag$attrs>$contenu</$tag>";
}

echo creerElement(contenu: "Bonjour", tag: "h1", id: "titre");

// Match expression (PHP 8.0+)
$statut = 2;
$libelle = match($statut) {
    0       => "Brouillon",
    1       => "Publié",
    2, 3    => "En révision",
    default => "Inconnu",
};

// Nullsafe operator (PHP 8.0+)
$user = trouverUser(1);
$pays = $user?->getAdresse()?->getPays()?->getNom();  // null si l'un est null

// Fibers (PHP 8.1+) — Coroutines légères
$fiber = new Fiber(function(): void {
    $valeur = Fiber::suspend("Première suspension");
    echo "Valeur reçue : $valeur\n";
});

$resultat = $fiber->start();
echo $resultat . "\n";          // Première suspension
$fiber->resume("Bonjour");      // Valeur reçue : Bonjour

// Enums (PHP 8.1+)
enum Statut: string {
    case Brouillon = 'brouillon';
    case Publie    = 'publie';
    case Archive   = 'archive';

    public function libelle(): string {
        return match($this) {
            Statut::Brouillon => "Brouillon",
            Statut::Publie    => "Publié",
            Statut::Archive   => "Archivé",
        };
    }
}

$s = Statut::Publie;
echo $s->value;     // publie
echo $s->libelle(); // Publié
?>
```

---

## Résumé du module 15

| Fonctionnalité | Version PHP | Utilité |
|----------------|-------------|---------|
| Namespaces | 5.3+ | Organisation du code |
| Traits | 5.4+ | Réutilisation sans héritage |
| Générateurs | 5.5+ | Grands volumes, mémoire réduite |
| Match | 8.0+ | Switch amélioré et strict |
| Nullsafe `?->` | 8.0+ | Chaînage sécurisé |
| Enums | 8.1+ | Ensembles de valeurs fixes |
| Fibers | 8.1+ | Programmation asynchrone |

**➡️ [Module 16 — Architecture MVC](../16-architecture-mvc/README.md)**
