# Module 11 — Formulaires HTML et PHP

> **Niveau :** Intermédiaire | **Durée :** 4 heures

---

## 1. Traiter un formulaire POST

```php
<?php
// contact.php
declare(strict_types=1);

$erreurs  = [];
$succes   = false;
$donnees  = ['nom' => '', 'email' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer et nettoyer les données
    $nom     = trim($_POST['nom']     ?? '');
    $email   = trim($_POST['email']   ?? '');
    $message = trim($_POST['message'] ?? '');

    // Valider
    if (strlen($nom) < 2) {
        $erreurs['nom'] = "Le nom doit faire au moins 2 caractères.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs['email'] = "Adresse email invalide.";
    }
    if (strlen($message) < 10) {
        $erreurs['message'] = "Le message doit faire au moins 10 caractères.";
    }

    if (empty($erreurs)) {
        // Traitement (envoi email, enregistrement BDD, etc.)
        $succes = true;
    } else {
        // Conserver les valeurs pour les ré-afficher
        $donnees = compact('nom', 'email', 'message');
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire de contact</title>
</head>
<body>

<?php if ($succes): ?>
    <p style="color:green">✅ Message envoyé avec succès !</p>
<?php else: ?>
    <form method="POST" action="contact.php" novalidate>
        <div>
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom"
                   value="<?= htmlspecialchars($donnees['nom']) ?>">
            <?php if (isset($erreurs['nom'])): ?>
                <span style="color:red"><?= htmlspecialchars($erreurs['nom']) ?></span>
            <?php endif; ?>
        </div>
        <div>
            <label for="email">Email :</label>
            <input type="email" id="email" name="email"
                   value="<?= htmlspecialchars($donnees['email']) ?>">
            <?php if (isset($erreurs['email'])): ?>
                <span style="color:red"><?= htmlspecialchars($erreurs['email']) ?></span>
            <?php endif; ?>
        </div>
        <div>
            <label for="message">Message :</label>
            <textarea id="message" name="message"><?= htmlspecialchars($donnees['message']) ?></textarea>
            <?php if (isset($erreurs['message'])): ?>
                <span style="color:red"><?= htmlspecialchars($erreurs['message']) ?></span>
            <?php endif; ?>
        </div>
        <button type="submit">Envoyer</button>
    </form>
<?php endif; ?>

</body>
</html>
```

---

## 2. Formulaire GET (recherche)

```php
<?php
// recherche.php
$terme    = trim($_GET['q'] ?? '');
$resultats = [];

if (!empty($terme)) {
    // Simuler une recherche
    $articles = ['PHP débutant', 'PHP avancé', 'JavaScript moderne', 'Laravel'];
    $resultats = array_filter($articles, fn($a) => stripos($a, $terme) !== false);
}
?>
<form method="GET" action="recherche.php">
    <input type="search" name="q" value="<?= htmlspecialchars($terme) ?>" placeholder="Rechercher...">
    <button type="submit">Chercher</button>
</form>

<?php if (!empty($terme)): ?>
    <p><?= count($resultats) ?> résultat(s) pour "<?= htmlspecialchars($terme) ?>"</p>
    <?php foreach ($resultats as $r): ?>
        <p><?= htmlspecialchars($r) ?></p>
    <?php endforeach; ?>
<?php endif; ?>
```

---

## 3. Types de champs de formulaire

```php
<?php
// Récupération de différents types de champs
$texte      = $_POST['texte']      ?? '';           // text, email, password, etc.
$nombre     = (int) ($_POST['age'] ?? 0);            // number
$case       = isset($_POST['accepte']);              // checkbox (bool)
$choix      = $_POST['couleur']    ?? '';             // radio, select
$multiples  = $_POST['langages']   ?? [];             // select multiple, checkboxes[]
$fichier    = $_FILES['photo']     ?? null;           // file

// Sécurisation de chaque type
$texte_safe = htmlspecialchars(trim($texte), ENT_QUOTES, 'UTF-8');
$age_safe   = max(0, min(150, $nombre));  // Contraindre dans un intervalle valide

// Filtres PHP intégrés
$email_valide = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$url_valide   = filter_input(INPUT_POST, 'url',   FILTER_VALIDATE_URL);
$int_propre   = filter_input(INPUT_POST, 'age',   FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 0, 'max_range' => 120]
]);
?>
```

---

## Résumé du module 11

- Toujours valider côté serveur (le JavaScript peut être contourné)
- Utiliser `$_POST` pour les formulaires sensibles, `$_GET` pour les recherches/filtres
- Afficher les valeurs précédentes après erreur (UX)
- Toujours `htmlspecialchars()` avant affichage dans le HTML
- Utiliser `filter_var()` et `filter_input()` pour les validations courantes

**➡️ [Module 12 — Sessions et cookies](../12-sessions-cookies/README.md)**
