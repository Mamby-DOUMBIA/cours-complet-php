# Projet 01 — Formulaire dynamique avec validation complète

> **Niveau :** Intermédiaire | **Durée :** 4-5 heures

Ce projet implémente un formulaire de contact complet avec validation côté serveur, affichage des erreurs, conservation des valeurs et envoi d'email.

## Fonctionnalités
- Champs : Nom, Email, Sujet (liste déroulante), Message, Pièce jointe (optionnelle)
- Validation complète côté serveur
- Conservation des valeurs après erreur
- Protection CSRF
- Envoi d'email avec PHPMailer (optionnel)

## Fichiers

### formulaire.php
```php
<?php
declare(strict_types=1);
session_start();

function tokenCSRF(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}

$erreurs = $donnees = [];
$succes  = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? ''))
        die("Requête invalide.");

    $nom     = trim($_POST['nom'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $sujet   = $_POST['sujet'] ?? '';
    $message = trim($_POST['message'] ?? '');
    $sujets_valides = ['question', 'bug', 'suggestion', 'autre'];

    if (strlen($nom) < 2)   $erreurs['nom'] = "Nom trop court (min. 2 caractères).";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erreurs['email'] = "Email invalide.";
    if (!in_array($sujet, $sujets_valides)) $erreurs['sujet'] = "Sujet invalide.";
    if (strlen($message) < 20) $erreurs['message'] = "Message trop court (min. 20 caractères).";

    if (empty($erreurs)) {
        // Ici : mail(), PHPMailer, ou enregistrement en BDD
        $succes = true;
        unset($_SESSION['csrf']);
    } else {
        $donnees = compact('nom', 'email', 'sujet', 'message');
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire de contact</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 40px auto; padding: 20px; }
        .champ { margin-bottom: 20px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input, select, textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        input.erreur, select.erreur, textarea.erreur { border-color: #e74c3c; }
        .msg-erreur { color: #e74c3c; font-size: 0.85em; margin-top: 4px; }
        button { background: #27ae60; color: white; padding: 12px 30px; border: none; border-radius: 4px; cursor: pointer; font-size: 1em; }
        .alerte-succes { background: #d5f5e3; border: 1px solid #27ae60; padding: 20px; border-radius: 5px; }
    </style>
</head>
<body>

<h1>Formulaire de contact</h1>

<?php if ($succes): ?>
    <div class="alerte-succes">
        <h2>✅ Message envoyé !</h2>
        <p>Merci pour votre message. Nous vous répondrons sous 48h.</p>
        <a href="formulaire.php">Envoyer un autre message</a>
    </div>
<?php else: ?>
    <form method="POST" action="formulaire.php" novalidate>
        <input type="hidden" name="csrf" value="<?= tokenCSRF() ?>">

        <div class="champ">
            <label for="nom">Nom complet *</label>
            <input type="text" id="nom" name="nom" class="<?= isset($erreurs['nom']) ? 'erreur' : '' ?>"
                   value="<?= htmlspecialchars($donnees['nom'] ?? '') ?>" required>
            <?php if (isset($erreurs['nom'])): ?>
                <p class="msg-erreur"><?= htmlspecialchars($erreurs['nom']) ?></p>
            <?php endif; ?>
        </div>

        <div class="champ">
            <label for="email">Adresse email *</label>
            <input type="email" id="email" name="email" class="<?= isset($erreurs['email']) ? 'erreur' : '' ?>"
                   value="<?= htmlspecialchars($donnees['email'] ?? '') ?>" required>
            <?php if (isset($erreurs['email'])): ?>
                <p class="msg-erreur"><?= htmlspecialchars($erreurs['email']) ?></p>
            <?php endif; ?>
        </div>

        <div class="champ">
            <label for="sujet">Sujet *</label>
            <select id="sujet" name="sujet" class="<?= isset($erreurs['sujet']) ? 'erreur' : '' ?>">
                <option value="">-- Choisissez un sujet --</option>
                <?php foreach (['question' => 'Question', 'bug' => 'Signaler un bug', 'suggestion' => 'Suggestion', 'autre' => 'Autre'] as $val => $lib): ?>
                    <option value="<?= $val ?>" <?= ($donnees['sujet'] ?? '') === $val ? 'selected' : '' ?>><?= $lib ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($erreurs['sujet'])): ?>
                <p class="msg-erreur"><?= htmlspecialchars($erreurs['sujet']) ?></p>
            <?php endif; ?>
        </div>

        <div class="champ">
            <label for="message">Message * (min. 20 caractères)</label>
            <textarea id="message" name="message" rows="6" class="<?= isset($erreurs['message']) ? 'erreur' : '' ?>"><?= htmlspecialchars($donnees['message'] ?? '') ?></textarea>
            <?php if (isset($erreurs['message'])): ?>
                <p class="msg-erreur"><?= htmlspecialchars($erreurs['message']) ?></p>
            <?php endif; ?>
        </div>

        <button type="submit">Envoyer le message</button>
    </form>
<?php endif; ?>

</body>
</html>
```
