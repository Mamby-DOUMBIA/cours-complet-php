# Projet 02 — Système d'authentification complet

> **Niveau :** Intermédiaire → Avancé
> **Durée estimée :** 8 à 10 heures
> **Prérequis :** Modules 01 à 14

---

## Objectifs du projet

Construire un système d'authentification complet comprenant :
- Inscription avec validation
- Connexion / déconnexion
- Protection de pages par session
- Hachage sécurisé des mots de passe
- Token CSRF sur tous les formulaires

---

## Structure du projet

```
02-authentification/
├── index.php              ← Page d'accueil (protégée)
├── inscription.php        ← Formulaire d'inscription
├── connexion.php          ← Formulaire de connexion
├── deconnexion.php        ← Déconnexion
├── tableau-de-bord.php    ← Page protégée (nécessite connexion)
├── config/
│   └── database.php       ← Connexion PDO
├── includes/
│   ├── auth.php           ← Fonctions d'authentification
│   ├── header.php
│   └── footer.php
└── sql/
    └── creation.sql       ← Script SQL de création
```

---

## Script SQL (sql/creation.sql)

```sql
CREATE DATABASE IF NOT EXISTS auth_demo
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE auth_demo;

CREATE TABLE utilisateurs (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    prenom       VARCHAR(100) NOT NULL,
    nom          VARCHAR(100) NOT NULL,
    email        VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role         ENUM('admin', 'utilisateur') NOT NULL DEFAULT 'utilisateur',
    actif        TINYINT(1) NOT NULL DEFAULT 1,
    cree_le      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);
```

---

## config/database.php

```php
<?php
declare(strict_types=1);

function getConnexion(): PDO {
    static $pdo = null;

    if ($pdo === null) {
        $dsn = "mysql:host=localhost;dbname=auth_demo;charset=utf8mb4";
        $pdo = new PDO($dsn, 'root', '', [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }

    return $pdo;
}
```

---

## includes/auth.php

```php
<?php
declare(strict_types=1);

/**
 * Démarre une session sécurisée.
 */
function demarrerSession(): void {
    if (session_status() === PHP_SESSION_NONE) {
        ini_set('session.cookie_httponly', '1');
        ini_set('session.use_strict_mode', '1');
        session_start();
    }
}

/**
 * Génère un token CSRF.
 */
function tokenCSRF(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Vérifie le token CSRF.
 */
function verifierCSRF(string $token): bool {
    return isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Vérifie si un utilisateur est connecté.
 */
function estConnecte(): bool {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Redirige vers la connexion si non connecté.
 */
function exigerConnexion(): void {
    if (!estConnecte()) {
        header('Location: /connexion.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit();
    }
}

/**
 * Connecte un utilisateur (après vérification réussie).
 */
function connecter(array $utilisateur): void {
    session_regenerate_id(true);
    $_SESSION['user_id']    = $utilisateur['id'];
    $_SESSION['user_email'] = $utilisateur['email'];
    $_SESSION['user_prenom']= $utilisateur['prenom'];
    $_SESSION['user_role']  = $utilisateur['role'];
    $_SESSION['connecte_a'] = time();
}

/**
 * Déconnecte l'utilisateur.
 */
function deconnecter(): void {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}
```

---

## inscription.php

```php
<?php
declare(strict_types=1);

require_once 'config/database.php';
require_once 'includes/auth.php';

demarrerSession();

// Si déjà connecté, rediriger
if (estConnecte()) {
    header('Location: tableau-de-bord.php');
    exit();
}

$erreurs = [];
$succes  = false;
$val     = ['prenom' => '', 'nom' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Vérifier le CSRF
    if (!verifierCSRF($_POST['csrf_token'] ?? '')) {
        die("Requête invalide.");
    }

    // 2. Récupérer les données
    $prenom = trim($_POST['prenom'] ?? '');
    $nom    = trim($_POST['nom']    ?? '');
    $email  = trim($_POST['email']  ?? '');
    $mdp    = $_POST['mdp']         ?? '';
    $mdp2   = $_POST['mdp2']        ?? '';
    $val    = compact('prenom', 'nom', 'email');

    // 3. Valider
    if (strlen($prenom) < 2)
        $erreurs['prenom'] = "Le prénom doit faire au moins 2 caractères.";
    if (strlen($nom) < 2)
        $erreurs['nom'] = "Le nom doit faire au moins 2 caractères.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $erreurs['email'] = "Adresse email invalide.";
    if (strlen($mdp) < 8)
        $erreurs['mdp'] = "Le mot de passe doit faire au moins 8 caractères.";
    if ($mdp !== $mdp2)
        $erreurs['mdp2'] = "Les mots de passe ne correspondent pas.";

    // 4. Vérifier unicité de l'email
    if (empty($erreurs)) {
        $pdo  = getConnexion();
        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = :email");
        $stmt->execute([':email' => $email]);
        if ($stmt->fetch()) {
            $erreurs['email'] = "Cette adresse email est déjà utilisée.";
        }
    }

    // 5. Créer l'utilisateur
    if (empty($erreurs)) {
        $pdo  = getConnexion();
        $hash = password_hash($mdp, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $pdo->prepare("
            INSERT INTO utilisateurs (prenom, nom, email, mot_de_passe)
            VALUES (:prenom, :nom, :email, :mdp)
        ");
        $stmt->execute([
            ':prenom' => $prenom,
            ':nom'    => $nom,
            ':email'  => $email,
            ':mdp'    => $hash,
        ]);
        $succes = true;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 40px auto; padding: 20px; }
        .erreur { color: #e74c3c; font-size: 0.85em; }
        .succes { background: #d5f5e3; padding: 15px; border-radius: 5px; }
        input { width: 100%; padding: 8px; margin: 5px 0 15px; box-sizing: border-box; }
        button { background: #3498db; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>

<h1>Inscription</h1>

<?php if ($succes): ?>
    <div class="succes">
        <strong>✅ Compte créé avec succès !</strong><br>
        <a href="connexion.php">Se connecter maintenant</a>
    </div>

<?php else: ?>
    <form method="POST" action="inscription.php">
        <input type="hidden" name="csrf_token" value="<?= tokenCSRF() ?>">

        <label>Prénom</label>
        <input type="text" name="prenom" value="<?= htmlspecialchars($val['prenom']) ?>" required>
        <?php if (isset($erreurs['prenom'])): ?>
            <p class="erreur"><?= htmlspecialchars($erreurs['prenom']) ?></p>
        <?php endif; ?>

        <label>Nom</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($val['nom']) ?>" required>
        <?php if (isset($erreurs['nom'])): ?>
            <p class="erreur"><?= htmlspecialchars($erreurs['nom']) ?></p>
        <?php endif; ?>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($val['email']) ?>" required>
        <?php if (isset($erreurs['email'])): ?>
            <p class="erreur"><?= htmlspecialchars($erreurs['email']) ?></p>
        <?php endif; ?>

        <label>Mot de passe (min. 8 caractères)</label>
        <input type="password" name="mdp" required>
        <?php if (isset($erreurs['mdp'])): ?>
            <p class="erreur"><?= htmlspecialchars($erreurs['mdp']) ?></p>
        <?php endif; ?>

        <label>Confirmer le mot de passe</label>
        <input type="password" name="mdp2" required>
        <?php if (isset($erreurs['mdp2'])): ?>
            <p class="erreur"><?= htmlspecialchars($erreurs['mdp2']) ?></p>
        <?php endif; ?>

        <button type="submit">Créer mon compte</button>
    </form>

    <p>Déjà un compte ? <a href="connexion.php">Se connecter</a></p>
<?php endif; ?>

</body>
</html>
```

---

## connexion.php

```php
<?php
declare(strict_types=1);

require_once 'config/database.php';
require_once 'includes/auth.php';

demarrerSession();

if (estConnecte()) {
    header('Location: tableau-de-bord.php');
    exit();
}

$erreur  = '';
$email   = '';
$redirect = htmlspecialchars($_GET['redirect'] ?? 'tableau-de-bord.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifierCSRF($_POST['csrf_token'] ?? '')) {
        die("Requête invalide.");
    }

    $email = trim($_POST['email'] ?? '');
    $mdp   = $_POST['mdp'] ?? '';

    if (empty($email) || empty($mdp)) {
        $erreur = "Veuillez remplir tous les champs.";
    } else {
        $pdo  = getConnexion();
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email AND actif = 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($mdp, $user['mot_de_passe'])) {
            connecter($user);

            // Mettre à jour le hash si nécessaire
            if (password_needs_rehash($user['mot_de_passe'], PASSWORD_BCRYPT, ['cost' => 12])) {
                $nouvelleHash = password_hash($mdp, PASSWORD_BCRYPT, ['cost' => 12]);
                $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE id = ?")
                    ->execute([$nouvelleHash, $user['id']]);
            }

            header('Location: ' . $redirect);
            exit();
        } else {
            // Message d'erreur générique (ne pas révéler si email ou mdp incorrect)
            $erreur = "Email ou mot de passe incorrect.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><title>Connexion</title></head>
<body>
<h1>Connexion</h1>
<?php if ($erreur): ?>
    <p style="color:red"><?= htmlspecialchars($erreur) ?></p>
<?php endif; ?>
<form method="POST" action="connexion.php">
    <input type="hidden" name="csrf_token" value="<?= tokenCSRF() ?>">
    <label>Email : <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required></label><br>
    <label>Mot de passe : <input type="password" name="mdp" required></label><br>
    <button type="submit">Se connecter</button>
</form>
<p><a href="inscription.php">Créer un compte</a></p>
</body>
</html>
```

---

## tableau-de-bord.php

```php
<?php
declare(strict_types=1);
require_once 'includes/auth.php';
demarrerSession();
exigerConnexion();  // Redirige vers connexion si non connecté
?>
<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><title>Tableau de bord</title></head>
<body>
<h1>Tableau de bord</h1>
<p>Bonjour, <strong><?= htmlspecialchars($_SESSION['user_prenom']) ?></strong> !</p>
<p>Vous êtes connecté(e) en tant que : <?= htmlspecialchars($_SESSION['user_role']) ?></p>
<p><a href="deconnexion.php">Se déconnecter</a></p>
</body>
</html>
```

---

## deconnexion.php

```php
<?php
require_once 'includes/auth.php';
demarrerSession();
deconnecter();
header('Location: connexion.php');
exit();
```

---

## Points clés du projet

| Aspect | Implémentation |
|--------|---------------|
| Mots de passe | `password_hash()` + `password_verify()` |
| Protection CSRF | Token dans chaque formulaire POST |
| Session fixation | `session_regenerate_id(true)` après connexion |
| Cookies sécurisés | `httponly` + `samesite=Strict` |
| Validation | Côté serveur, toujours |
| Messages d'erreur | Génériques (ne pas révéler email vs mdp) |
