.cookie</script>

Quand un visiteur lit ce commentaire, son cookie de session est volé !
L'attaquant peut maintenant usurper son identité.
```

### Protection contre XSS

```php
<?php
// =========================================================
// RÈGLE FONDAMENTALE : Échapper TOUTES les données
// avant de les afficher dans du HTML
// =========================================================

// ❌ VULNÉRABLE
$nom = $_GET['nom'];
echo "<p>Bonjour " . $nom . " !</p>";
// Si $nom = "<script>alert('xss')</script>", le script s'exécute !

// ✅ SÉCURISÉ avec htmlspecialchars()
$nom = $_GET['nom'] ?? '';
echo "<p>Bonjour " . htmlspecialchars($nom, ENT_QUOTES, 'UTF-8') . " !</p>";
// htmlspecialchars convertit : < → &lt;  > → &gt;  " → &quot;  ' → &#039;
// Le navigateur affiche le texte brut, sans exécuter le script.

// Créer une fonction utilitaire
function e(string $valeur): string {
    return htmlspecialchars($valeur, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

// Utilisation simplifiée
echo "<p>Bonjour " . e($nom) . " !</p>";
echo "<input value='" . e($valeur) . "'>";

// ❌ Ne jamais insérer des données non filtrées dans du JavaScript
echo "<script>var nom = '" . $nom . "';</script>";  // DANGEREUX !

// ✅ Passer les données via json_encode()
echo "<script>var nom = " . json_encode($nom) . ";</script>";  // Sécurisé
?>
```

### Content Security Policy (CSP)

```php
<?php
// Un en-tête CSP indique au navigateur quels scripts sont autorisés
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline'");
// Résultat : les scripts externes ou inline non autorisés sont BLOQUÉS
// Même si un attaquant injecte du HTML, le navigateur refusera d'exécuter le JS
?>
```

---

## 4. CSRF — Cross-Site Request Forgery

Le CSRF force un utilisateur authentifié à exécuter des actions à son insu.

### Scénario d'attaque CSRF

```
1. Alice est connectée sur sa banque (elle a un cookie de session valide).

2. Alice visite un site malveillant qui contient :
   <img src="http://ma-banque.com/transfert?vers=attaquant&montant=1000">

3. Le navigateur d'Alice envoie automatiquement la requête à la banque
   AVEC son cookie de session.

4. La banque exécute le transfert car la session d'Alice est valide !
```

### Protection avec token CSRF

```php
<?php
session_start();

/**
 * Génère un token CSRF unique et le stocke en session.
 */
function genererTokenCSRF(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Vérifie que le token CSRF soumis est valide.
 */
function verifierTokenCSRF(string $tokenSoumis): bool {
    if (empty($_SESSION['csrf_token'])) {
        return false;
    }
    // hash_equals évite les attaques par timing (timing attacks)
    $valide = hash_equals($_SESSION['csrf_token'], $tokenSoumis);

    // Renouveler le token après utilisation (one-time token)
    unset($_SESSION['csrf_token']);

    return $valide;
}

// Dans le formulaire HTML :
$token = genererTokenCSRF();
echo '<form method="POST" action="traitement.php">';
echo '<input type="hidden" name="csrf_token" value="' . $token . '">';
echo '<button type="submit">Supprimer mon compte</button>';
echo '</form>';

// Dans traitement.php :
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tokenSoumis = $_POST['csrf_token'] ?? '';

    if (!verifierTokenCSRF($tokenSoumis)) {
        http_response_code(403);
        die("Requête invalide — token CSRF manquant ou expiré.");
    }
    // Traitement sécurisé de la requête POST...
}
?>
```

---

## 5. Sécurité des mots de passe

### Ne JAMAIS stocker un mot de passe en clair

```php
<?php
// ❌ CATASTROPHIQUE — stocker en clair
$mdp_en_clair = "MonMotDePasse123";
// INSERT INTO users SET mot_de_passe = 'MonMotDePasse123'
// Si la BDD est compromise, TOUS les mots de passe sont exposés !

// ❌ INSUFFISANT — MD5 ou SHA1 (algorithmes cassés)
$mdp_md5 = md5("MonMotDePasse123");
// Ces algorithmes sont trop rapides, vulnérables aux rainbow tables

// ✅ CORRECT — password_hash() avec PASSWORD_BCRYPT ou PASSWORD_ARGON2ID
$hash = password_hash("MonMotDePasse123", PASSWORD_BCRYPT, ['cost' => 12]);
// Résultat : $2y$12$abcdefghijklmnopqrstuuVoIci1HashBcryptSécurisé
// Le hash est différent à CHAQUE appel (salt aléatoire intégré)

// Vérification lors de la connexion
$mdp_saisi = "MonMotDePasse123";  // Mot de passe saisi par l'utilisateur

// password_verify() compare le mot de passe saisi avec le hash stocké
if (password_verify($mdp_saisi, $hash)) {
    echo "✅ Mot de passe correct — connexion autorisée";
} else {
    echo "❌ Mot de passe incorrect";
}

// Mettre à jour le hash si nécessaire (algorithme amélioré)
if (password_needs_rehash($hash, PASSWORD_BCRYPT, ['cost' => 12])) {
    $nouveauHash = password_hash($mdp_saisi, PASSWORD_BCRYPT, ['cost' => 12]);
    // Mettre à jour le hash en base de données
}
?>
```

### Politique de mots de passe

```php
<?php
declare(strict_types=1);

/**
 * Valide la robustesse d'un mot de passe.
 */
function validerMotDePasse(string $mdp): array {
    $erreurs = [];

    if (strlen($mdp) < 12) {
        $erreurs[] = "Minimum 12 caractères requis.";
    }
    if (!preg_match('/[A-Z]/', $mdp)) {
        $erreurs[] = "Au moins une lettre majuscule requise.";
    }
    if (!preg_match('/[a-z]/', $mdp)) {
        $erreurs[] = "Au moins une lettre minuscule requise.";
    }
    if (!preg_match('/[0-9]/', $mdp)) {
        $erreurs[] = "Au moins un chiffre requis.";
    }
    if (!preg_match('/[\W_]/', $mdp)) {
        $erreurs[] = "Au moins un caractère spécial requis (!@#\$%...).";
    }

    // Vérifier les mots de passe courants
    $mdpCourants = ['password', '123456', 'azerty', 'qwerty', 'motdepasse'];
    if (in_array(strtolower($mdp), $mdpCourants)) {
        $erreurs[] = "Ce mot de passe est trop courant et facilement devinable.";
    }

    return $erreurs;
}
?>
```

---

## 6. Validation et assainissement des entrées

**Principe fondamental : Ne faites JAMAIS confiance aux données extérieures.**

```php
<?php
declare(strict_types=1);

/**
 * Classe de validation complète.
 */
class Validateur {
    private array $erreurs = [];

    public function requis(string $champ, mixed $valeur): static {
        if ($valeur === null || $valeur === '' || $valeur === []) {
            $this->erreurs[$champ][] = "Le champ '{$champ}' est obligatoire.";
        }
        return $this;
    }

    public function email(string $champ, string $valeur): static {
        if (!filter_var($valeur, FILTER_VALIDATE_EMAIL)) {
            $this->erreurs[$champ][] = "L'adresse email '{$valeur}' n'est pas valide.";
        }
        return $this;
    }

    public function entier(string $champ, mixed $valeur, int $min = PHP_INT_MIN, int $max = PHP_INT_MAX): static {
        if (!filter_var($valeur, FILTER_VALIDATE_INT, ['options' => ['min_range' => $min, 'max_range' => $max]])) {
            $this->erreurs[$champ][] = "'{$champ}' doit être un entier entre {$min} et {$max}.";
        }
        return $this;
    }

    public function longueur(string $champ, string $valeur, int $min = 0, int $max = PHP_INT_MAX): static {
        $len = mb_strlen($valeur);
        if ($len < $min || $len > $max) {
            $this->erreurs[$champ][] = "'{$champ}' doit faire entre {$min} et {$max} caractères.";
        }
        return $this;
    }

    public function url(string $champ, string $valeur): static {
        if (!filter_var($valeur, FILTER_VALIDATE_URL)) {
            $this->erreurs[$champ][] = "L'URL '{$valeur}' n'est pas valide.";
        }
        return $this;
    }

    public function estValide(): bool {
        return empty($this->erreurs);
    }

    public function getErreurs(): array {
        return $this->erreurs;
    }
}

// Utilisation
$donnees = [
    'nom'   => $_POST['nom']   ?? '',
    'email' => $_POST['email'] ?? '',
    'age'   => $_POST['age']   ?? '',
];

$v = new Validateur();
$v->requis('nom',   $donnees['nom'])  ->longueur('nom',   $donnees['nom'],   2, 100)
  ->requis('email', $donnees['email'])->email('email',    $donnees['email'])
  ->requis('age',   $donnees['age'])  ->entier('age',     $donnees['age'],   1, 120);

if ($v->estValide()) {
    // Traitement des données validées
    $nomNettoye   = trim(htmlspecialchars($donnees['nom'], ENT_QUOTES, 'UTF-8'));
    $emailNettoye = filter_var($donnees['email'], FILTER_SANITIZE_EMAIL);
    $ageNettoye   = (int) $donnees['age'];
    echo "Données valides !";
} else {
    foreach ($v->getErreurs() as $champ => $erreurs) {
        foreach ($erreurs as $erreur) {
            echo "❌ {$erreur}<br>";
        }
    }
}
?>
```

---

## 7. Sécurité des sessions

```php
<?php
/**
 * Configuration sécurisée des sessions PHP.
 * À mettre au début de chaque script utilisant les sessions.
 */
function demarrerSessionSecurisee(): void {
    // Configurer les paramètres AVANT session_start()
    ini_set('session.cookie_httponly', '1');    // Cookie inaccessible au JS
    ini_set('session.cookie_secure', '1');      // Cookie HTTPS uniquement (prod)
    ini_set('session.cookie_samesite', 'Strict'); // Protection CSRF
    ini_set('session.use_strict_mode', '1');    // Rejette les IDs de session inconnus
    ini_set('session.gc_maxlifetime', '1800');  // Expiration après 30 min d'inactivité

    session_start();

    // Régénérer l'ID de session périodiquement (prévient le session fixation)
    if (!isset($_SESSION['derniere_regeneration'])) {
        session_regenerate_id(true);
        $_SESSION['derniere_regeneration'] = time();
    } elseif (time() - $_SESSION['derniere_regeneration'] > 300) {
        // Régénérer l'ID toutes les 5 minutes
        session_regenerate_id(true);
        $_SESSION['derniere_regeneration'] = time();
    }
}

/**
 * Connecte un utilisateur de façon sécurisée.
 */
function connecterUtilisateur(array $utilisateur): void {
    // Régénérer l'ID après connexion (prévient le session fixation)
    session_regenerate_id(true);

    $_SESSION['user_id']    = $utilisateur['id'];
    $_SESSION['user_email'] = $utilisateur['email'];
    $_SESSION['user_role']  = $utilisateur['role'];
    $_SESSION['connecte_le'] = time();
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'] ?? '';
}

/**
 * Vérifie qu'un utilisateur est connecté et que la session est valide.
 */
function verifierSession(): bool {
    if (!isset($_SESSION['user_id'])) {
        return false;
    }

    // Vérifier que l'IP et le User-Agent n'ont pas changé (anti-hijacking)
    $agentValide = ($_SESSION['user_agent'] === ($_SERVER['HTTP_USER_AGENT'] ?? ''));
    $ipValide    = ($_SESSION['ip_address'] === ($_SERVER['REMOTE_ADDR'] ?? ''));

    if (!$agentValide || !$ipValide) {
        deconnecterUtilisateur();
        return false;
    }

    // Vérifier l'inactivité
    $inactivite = time() - ($_SESSION['connecte_le'] ?? 0);
    if ($inactivite > 1800) {  // 30 minutes
        deconnecterUtilisateur();
        return false;
    }

    $_SESSION['connecte_le'] = time();  // Renouveler le timer
    return true;
}

/**
 * Déconnecte l'utilisateur proprement.
 */
function deconnecterUtilisateur(): void {
    $_SESSION = [];  // Vider toutes les données de session
    session_destroy();  // Détruire la session

    // Supprimer le cookie de session
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
}
?>
```

---

## 8. En-têtes HTTP de sécurité

```php
<?php
/**
 * Configurer les en-têtes HTTP de sécurité.
 * À appeler au tout début de chaque page.
 */
function configurerEntetesSecurite(): void {
    // Empêche le navigateur de "deviner" le type MIME
    header('X-Content-Type-Options: nosniff');

    // Empêche l'intégration dans une iframe (anti-clickjacking)
    header('X-Frame-Options: DENY');

    // Active le filtre XSS intégré des navigateurs (ancien)
    header('X-XSS-Protection: 1; mode=block');

    // Force HTTPS pendant 1 an (production uniquement)
    // header('Strict-Transport-Security: max-age=31536000; includeSubDomains');

    // Politique de sécurité du contenu
    header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline'; img-src 'self' data:;");

    // Contrôle les informations envoyées dans le header Referer
    header('Referrer-Policy: strict-origin-when-cross-origin');

    // Désactive certaines fonctionnalités du navigateur
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
}

configurerEntetesSecurite();
?>
```

---

## 9. Upload de fichiers sécurisé

```php
<?php
declare(strict_types=1);

/**
 * Gère l'upload de fichiers de façon sécurisée.
 */
function traiterUpload(array $fichier, string $repertoireDestination): string {
    // 1. Vérifier les erreurs d'upload
    if ($fichier['error'] !== UPLOAD_ERR_OK) {
        $messages = [
            UPLOAD_ERR_INI_SIZE   => "Fichier trop volumineux (limite PHP).",
            UPLOAD_ERR_FORM_SIZE  => "Fichier trop volumineux (limite formulaire).",
            UPLOAD_ERR_PARTIAL    => "Upload incomplet.",
            UPLOAD_ERR_NO_FILE    => "Aucun fichier envoyé.",
            UPLOAD_ERR_NO_TMP_DIR => "Répertoire temporaire manquant.",
            UPLOAD_ERR_CANT_WRITE => "Impossible d'écrire sur le disque.",
        ];
        throw new RuntimeException($messages[$fichier['error']] ?? "Erreur d'upload inconnue.");
    }

    // 2. Vérifier que c'est bien un vrai upload (pas une injection de chemin)
    if (!is_uploaded_file($fichier['tmp_name'])) {
        throw new RuntimeException("Tentative d'upload non autorisée détectée.");
    }

    // 3. Valider la taille (max 5 Mo)
    $tailleMax = 5 * 1024 * 1024;  // 5 Mo en octets
    if ($fichier['size'] > $tailleMax) {
        throw new RuntimeException("Fichier trop volumineux. Maximum 5 Mo.");
    }

    // 4. Valider le type MIME réel (pas juste l'extension)
    $typesAutorises = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $typeMime = $finfo->file($fichier['tmp_name']);

    if (!in_array($typeMime, $typesAutorises, true)) {
        throw new RuntimeException("Type de fichier non autorisé : {$typeMime}");
    }

    // 5. Valider l'extension (double vérification)
    $extensionsAutorisees = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $extension = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $extensionsAutorisees, true)) {
        throw new RuntimeException("Extension non autorisée : .{$extension}");
    }

    // 6. Générer un nom de fichier sécurisé (jamais utiliser le nom original !)
    // Le nom original pourrait être : "../../../etc/passwd.php"
    $nomSecurise = bin2hex(random_bytes(16)) . '.' . $extension;
    $destination = rtrim($repertoireDestination, '/') . '/' . $nomSecurise;

    // 7. Déplacer le fichier dans le répertoire de destination
    if (!move_uploaded_file($fichier['tmp_name'], $destination)) {
        throw new RuntimeException("Impossible de déplacer le fichier uploadé.");
    }

    return $nomSecurise;
}

// Utilisation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    try {
        $nom = traiterUpload($_FILES['photo'], '/var/www/uploads/');
        echo "Photo uploadée avec succès : " . $nom;
    } catch (RuntimeException $e) {
        echo "Erreur : " . htmlspecialchars($e->getMessage());
    }
}
?>
```

---

## 10. Checklist de sécurité

Avant de mettre en production, vérifiez chaque point :

### 🔐 Base de données
- [ ] Toutes les requêtes utilisent des requêtes préparées (PDO)
- [ ] Les identifiants BDD ne sont pas dans le code source (utiliser `.env`)
- [ ] L'utilisateur MySQL a des privilèges minimaux (pas `root`)
- [ ] La base de données n'est pas accessible depuis Internet

### 🔒 Mots de passe
- [ ] `password_hash()` avec `PASSWORD_BCRYPT` ou `PASSWORD_ARGON2ID`
- [ ] Jamais de MD5, SHA1, ou stockage en clair
- [ ] Politique de mots de passe robuste (min 12 cars, majuscule, chiffre, spécial)

### 🛡️ Entrées utilisateur
- [ ] Toutes les entrées sont validées côté serveur
- [ ] `htmlspecialchars()` sur toutes les sorties HTML
- [ ] Token CSRF sur tous les formulaires POST

### 🔑 Sessions
- [ ] `session_regenerate_id(true)` après connexion
- [ ] Cookies de session : `httponly`, `secure`, `samesite=Strict`
- [ ] Timeout d'inactivité implémenté

### 📁 Fichiers
- [ ] Validation du type MIME réel (pas seulement l'extension)
- [ ] Noms de fichiers générés aléatoirement
- [ ] Répertoire d'upload hors de la racine Web (ou protégé)

### ⚙️ Configuration serveur
- [ ] `display_errors = Off` en production
- [ ] `error_reporting = E_ALL` avec log dans un fichier
- [ ] En-têtes HTTP de sécurité configurés
- [ ] HTTPS activé (certificat SSL/TLS)
- [ ] Version PHP à jour

### 🔍 Code
- [ ] Aucun chemin absolu ou identifiant dans les messages d'erreur publics
- [ ] Principe du moindre privilège respecté
- [ ] Pas de `eval()`, `exec()`, `system()` avec des données utilisateur

---

## Résumé du module 14

| Menace | Solution PHP |
|--------|-------------|
| Injection SQL | Requêtes préparées PDO obligatoires |
| XSS | `htmlspecialchars()` sur toutes les sorties |
| CSRF | Token CSRF dans chaque formulaire POST |
| Mots de passe faibles | `password_hash()` + `password_verify()` |
| Sessions vulnérables | `session_regenerate_id()` + cookies sécurisés |
| Upload malveillant | Valider type MIME + noms aléatoires |

---

**➡️ Module suivant : [Module 16 — Architecture MVC](../16-architecture-mvc/README.md)**

🔗 [Retour au sommaire principal](../../README.md)
