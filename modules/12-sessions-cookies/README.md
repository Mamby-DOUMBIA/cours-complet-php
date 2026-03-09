# Module 12 — Sessions et cookies

> **Niveau :** Intermédiaire | **Durée :** 3 heures

---

## 1. Les sessions

Une session permet de **mémoriser des données** entre plusieurs pages pour un même visiteur.

```php
<?php
// Démarrer la session (AVANT tout output HTML)
session_start();

// Stocker des données en session
$_SESSION['user_id']   = 1;
$_SESSION['prenom']    = 'Alice';
$_SESSION['connecte']  = true;

// Lire des données de session
if (isset($_SESSION['connecte']) && $_SESSION['connecte'] === true) {
    echo "Bonjour, " . htmlspecialchars($_SESSION['prenom']) . " !";
}

// Supprimer une variable de session
unset($_SESSION['cle']);

// Détruire toute la session (déconnexion)
$_SESSION = [];
session_destroy();
?>
```

### Système de connexion simple

```php
<?php
// connexion.php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $mdp   = $_POST['mdp'] ?? '';

    // Récupérer l'utilisateur depuis la BDD
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email AND actif = 1");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($mdp, $user['mot_de_passe'])) {
        // Connexion réussie
        session_regenerate_id(true);  // Sécurité : nouvel ID de session
        $_SESSION['user_id']    = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role']  = $user['role'];
        header('Location: /tableau-de-bord.php');
        exit();
    } else {
        $erreur = "Email ou mot de passe incorrect.";
    }
}
?>
```

---

## 2. Les cookies

Un cookie est un **petit fichier texte** stocké dans le navigateur du visiteur.

```php
<?php
// Créer un cookie (expire dans 30 jours)
setcookie(
    'theme',           // Nom
    'sombre',          // Valeur
    [
        'expires'  => time() + (30 * 24 * 3600),  // 30 jours
        'path'     => '/',          // Accessible sur tout le site
        'secure'   => true,         // HTTPS uniquement (production)
        'httponly' => true,          // Inaccessible au JavaScript
        'samesite' => 'Strict',     // Protection CSRF
    ]
);

// Lire un cookie
$theme = $_COOKIE['theme'] ?? 'clair';  // Valeur par défaut si absent
echo "Thème actuel : " . htmlspecialchars($theme);

// Supprimer un cookie (mettre une date d'expiration dans le passé)
setcookie('theme', '', time() - 3600, '/');

// Cookie "se souvenir de moi"
function creerTokenSouvenirMoi(int $userId, PDO $pdo): string {
    $token = bin2hex(random_bytes(32));
    $hash  = hash('sha256', $token);

    $stmt = $pdo->prepare("INSERT INTO tokens_session (user_id, token_hash, expire_le) VALUES (?, ?, ?)");
    $stmt->execute([$userId, $hash, date('Y-m-d H:i:s', time() + 30*24*3600)]);

    // Stocker le token en clair dans le cookie (le hash est en BDD)
    setcookie('souvenir', $token, [
        'expires'  => time() + 30*24*3600,
        'httponly' => true,
        'secure'   => true,
        'samesite' => 'Strict',
    ]);

    return $token;
}
?>
```

---

## 3. Sessions vs Cookies

| Aspect | Session | Cookie |
|--------|---------|--------|
| Stockage | Serveur | Navigateur client |
| Durée de vie | Jusqu'à fermeture navigateur (par défaut) | Définie manuellement |
| Sécurité | Plus sûr (données côté serveur) | Moins sûr (côté client) |
| Taille | Illimitée | Max 4 Ko |
| Utilisation | Connexion, panier, données temp. | Préférences, "se souvenir" |

---

## Résumé du module 12

- `session_start()` avant tout output
- `session_regenerate_id(true)` après connexion
- Cookies : utiliser `httponly`, `secure`, `samesite`
- Détruire la session à la déconnexion : `$_SESSION = []; session_destroy();`
- Ne jamais stocker de données sensibles dans les cookies

**➡️ [Module 13 — Bases de données](../13-bases-de-donnees/README.md)**
