# Module 09 — Gestion des erreurs et exceptions

> **Niveau :** Intermédiaire
> **Durée estimée :** 3 heures

---

## 1. Les types d'erreurs PHP

PHP distingue plusieurs niveaux d'erreurs :

| Niveau | Constante | Description |
|--------|-----------|-------------|
| Fatal | `E_ERROR` | Erreur fatale — script arrêté |
| Avertissement | `E_WARNING` | Erreur non fatale — script continue |
| Notice | `E_NOTICE` | Problème mineur (variable non définie) |
| Dépréciation | `E_DEPRECATED` | Fonctionnalité obsolète |
| Toutes | `E_ALL` | Tous les niveaux |

```php
<?php
// Configurer la gestion des erreurs (DÉVELOPPEMENT)
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Configurer pour la PRODUCTION
// ini_set('display_errors', '0');
// ini_set('log_errors', '1');
// ini_set('error_log', '/var/log/php_errors.log');
// error_reporting(E_ALL);  // Loguer tout, mais ne rien afficher
?>
```

---

## 2. Exceptions en PHP

Une exception est un objet qui représente une **erreur ou une situation exceptionnelle**.

```php
<?php
// Lancer une exception
function diviser(float $a, float $b): float {
    if ($b === 0.0) {
        throw new InvalidArgumentException("Division par zéro impossible.");
    }
    return $a / $b;
}

// Capturer une exception avec try/catch
try {
    $resultat = diviser(10, 0);
    echo $resultat;  // Cette ligne ne s'exécute PAS si exception
} catch (InvalidArgumentException $e) {
    echo "Erreur de paramètre : " . $e->getMessage();
} catch (RuntimeException $e) {
    echo "Erreur d'exécution : " . $e->getMessage();
} catch (Exception $e) {
    echo "Erreur générique : " . $e->getMessage();
} finally {
    // Ce bloc s'exécute TOUJOURS (exception ou pas)
    echo "Nettoyage effectué." . PHP_EOL;
}
?>
```

---

## 3. Hiérarchie des exceptions

```
Throwable (interface)
├── Error (erreurs PHP internes)
│   ├── TypeError
│   ├── ValueError
│   └── ArithmeticError
└── Exception (exceptions applicatives)
    ├── RuntimeException
    │   ├── InvalidArgumentException  ← Mauvais paramètre
    │   ├── OutOfRangeException
    │   └── OverflowException
    ├── LogicException
    │   ├── BadMethodCallException
    │   └── InvalidArgumentException
    └── PDOException                  ← Erreurs base de données
```

---

## 4. Exceptions personnalisées

```php
<?php
declare(strict_types=1);

// Créer des exceptions métier spécifiques
class UtilisateurNotFoundException extends RuntimeException {
    public function __construct(int $id) {
        parent::__construct("Utilisateur introuvable avec l'ID : {$id}", 404);
    }
}

class EmailDejaPrisException extends RuntimeException {
    public function __construct(string $email) {
        parent::__construct("L'email '{$email}' est déjà utilisé.", 409);
    }
}

class ValidationException extends RuntimeException {
    private array $erreurs;

    public function __construct(array $erreurs) {
        parent::__construct("Erreur de validation.", 422);
        $this->erreurs = $erreurs;
    }

    public function getErreurs(): array {
        return $this->erreurs;
    }
}

// Utilisation
function inscrireUtilisateur(array $donnees): void {
    $erreurs = [];

    if (empty($donnees['email'])) {
        $erreurs[] = "L'email est requis.";
    }
    if (!empty($erreurs)) {
        throw new ValidationException($erreurs);
    }

    // Vérifier si email pris...
    if (true) {  // Simulé
        throw new EmailDejaPrisException($donnees['email']);
    }
}

try {
    inscrireUtilisateur(['email' => '']);
} catch (ValidationException $e) {
    echo "Validation échouée :" . PHP_EOL;
    foreach ($e->getErreurs() as $err) {
        echo "  - " . $err . PHP_EOL;
    }
} catch (EmailDejaPrisException $e) {
    echo "Email déjà utilisé : " . $e->getMessage() . PHP_EOL;
}
?>
```

---

## 5. Gestionnaire d'erreurs global

```php
<?php
// Convertir les erreurs PHP en exceptions
set_error_handler(function(int $errno, string $errstr, string $errfile, int $errline): bool {
    // Ne pas gérer les erreurs supprimées avec @
    if (!(error_reporting() & $errno)) {
        return false;
    }
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

// Gestionnaire d'exceptions non capturées
set_exception_handler(function(Throwable $e): void {
    error_log(sprintf(
        "[%s] %s dans %s:%d\nTrace:\n%s",
        date('Y-m-d H:i:s'),
        $e->getMessage(),
        $e->getFile(),
        $e->getLine(),
        $e->getTraceAsString()
    ));

    // En production : afficher une page d'erreur générique
    http_response_code(500);
    echo "<h1>Une erreur est survenue</h1><p>Veuillez réessayer ultérieurement.</p>";
});
?>
```

---

## Résumé du module 09

| Concept | Utilisation |
|---------|-------------|
| `throw` | Lance une exception |
| `try/catch` | Capture et gère une exception |
| `finally` | Exécuté quoi qu'il arrive |
| `Exception` | Classe de base des exceptions |
| Exceptions personnalisées | `class MonException extends RuntimeException` |

**➡️ [Module 10 — Fichiers](../10-fichiers/README.md)**
