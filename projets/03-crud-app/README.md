# Projet 03 — Application CRUD complète

> **Niveau :** Intermédiaire | **Durée :** 6-8 heures

Application de gestion de contacts (CRUD) : Créer, Lire, Mettre à jour, Supprimer.

## Base de données

```sql
CREATE DATABASE crud_contacts CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE crud_contacts;

CREATE TABLE contacts (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom        VARCHAR(100) NOT NULL,
    prenom     VARCHAR(100) NOT NULL,
    email      VARCHAR(255) NOT NULL UNIQUE,
    telephone  VARCHAR(20),
    entreprise VARCHAR(200),
    cree_le    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO contacts (nom, prenom, email, telephone, entreprise) VALUES
('Martin',  'Alice',   'alice@test.com',   '06 12 34 56 78', 'TechCorp'),
('Dupont',  'Bob',     'bob@test.com',     '07 98 76 54 32', 'StartupXYZ'),
('Bernard', 'Charlie', 'charlie@test.com', NULL,             NULL);
```

## index.php — Liste des contacts

```php
<?php
declare(strict_types=1);
$pdo = new PDO("mysql:host=localhost;dbname=crud_contacts;charset=utf8mb4", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$recherche = trim($_GET['q'] ?? '');
if ($recherche) {
    $stmt = $pdo->prepare("SELECT * FROM contacts WHERE nom LIKE :q OR prenom LIKE :q OR email LIKE :q ORDER BY nom");
    $stmt->execute([':q' => "%$recherche%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM contacts ORDER BY nom, prenom");
}
$contacts = $stmt->fetchAll();
$msgSucces = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des contacts</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 30px auto; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #3498db; color: white; }
        tr:hover { background: #f5f5f5; }
        .btn { padding: 6px 12px; border-radius: 4px; text-decoration: none; color: white; border: none; cursor: pointer; }
        .btn-modifier { background: #f39c12; }
        .btn-supprimer { background: #e74c3c; }
        .btn-ajouter { background: #27ae60; display: inline-block; margin-bottom: 20px; }
        .succes { background: #d5f5e3; padding: 10px 15px; border-radius: 4px; margin-bottom: 20px; }
    </style>
</head>
<body>
<h1>📋 Gestion des contacts</h1>

<?php if ($msgSucces): ?>
    <div class="succes"><?= htmlspecialchars($msgSucces) ?></div>
<?php endif; ?>

<a href="formulaire.php" class="btn btn-ajouter">+ Ajouter un contact</a>

<form method="GET" style="margin-bottom: 15px;">
    <input type="search" name="q" value="<?= htmlspecialchars($recherche) ?>" placeholder="Rechercher..." style="padding: 8px; width: 250px;">
    <button type="submit" class="btn" style="background:#3498db; padding:8px 15px;">Rechercher</button>
    <?php if ($recherche): ?> <a href="index.php">Tout afficher</a> <?php endif; ?>
</form>

<table>
    <thead>
        <tr>
            <th>Nom</th><th>Prénom</th><th>Email</th><th>Téléphone</th><th>Entreprise</th><th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($contacts)): ?>
            <tr><td colspan="6" style="text-align:center;color:#999;">Aucun contact trouvé</td></tr>
        <?php else: ?>
            <?php foreach ($contacts as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['nom']) ?></td>
                    <td><?= htmlspecialchars($c['prenom']) ?></td>
                    <td><?= htmlspecialchars($c['email']) ?></td>
                    <td><?= htmlspecialchars($c['telephone'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($c['entreprise'] ?? '—') ?></td>
                    <td>
                        <a href="formulaire.php?id=<?= $c['id'] ?>" class="btn btn-modifier">Modifier</a>
                        <form method="POST" action="supprimer.php" style="display:inline;" onsubmit="return confirm('Supprimer ce contact ?')">
                            <input type="hidden" name="id" value="<?= $c['id'] ?>">
                            <button type="submit" class="btn btn-supprimer">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
<p><small><?= count($contacts) ?> contact(s) affiché(s)</small></p>
</body>
</html>
```

## Ce que vous apprendrez

- Architecture CRUD simple et fonctionnelle
- Recherche avec `LIKE` en SQL
- Confirmation avant suppression (JavaScript)
- Paramètres GET pour messages de succès
- Gestion des valeurs NULL en SQL et PHP
