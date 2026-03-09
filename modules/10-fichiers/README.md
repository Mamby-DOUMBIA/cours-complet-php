# Module 10 — Manipulation de fichiers

> **Niveau :** Intermédiaire
> **Durée estimée :** 3 heures

---

## 1. Lire des fichiers

```php
<?php
// Méthode simple : lire tout le contenu d'un coup
$contenu = file_get_contents('data.txt');
echo $contenu;

// Lire en tableau de lignes
$lignes = file('data.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lignes as $numero => $ligne) {
    echo ($numero + 1) . ": " . $ligne . PHP_EOL;
}

// Lire avec fopen pour les gros fichiers (efficace en mémoire)
$handle = fopen('gros-fichier.txt', 'r');
if ($handle) {
    while (($ligne = fgets($handle)) !== false) {
        // Traiter ligne par ligne
        echo trim($ligne) . PHP_EOL;
    }
    fclose($handle);
}

// Lire un fichier CSV
$handle = fopen('donnees.csv', 'r');
$entetes = fgetcsv($handle);  // Première ligne = en-têtes
while (($ligne = fgetcsv($handle)) !== false) {
    $donnees = array_combine($entetes, $ligne);
    print_r($donnees);
}
fclose($handle);
?>
```

---

## 2. Écrire des fichiers

```php
<?php
// Écriture simple (crée ou écrase)
file_put_contents('sortie.txt', "Contenu du fichier\n");

// Ajouter au contenu existant (ne pas écraser)
file_put_contents('sortie.txt', "Nouvelle ligne\n", FILE_APPEND | LOCK_EX);

// Écriture avec fopen pour plus de contrôle
$handle = fopen('journal.txt', 'a');  // 'a' = append
fwrite($handle, date('[Y-m-d H:i:s]') . " Événement enregistré\n");
fclose($handle);

// Écrire un CSV
$handle = fopen('export.csv', 'w');
fputcsv($handle, ['Nom', 'Email', 'Age']);  // En-têtes
$donnees = [
    ['Alice', 'alice@test.com', 25],
    ['Bob', 'bob@test.com', 30],
];
foreach ($donnees as $ligne) {
    fputcsv($handle, $ligne);
}
fclose($handle);

// Écrire du JSON
$config = ['debug' => true, 'version' => '1.0', 'db' => 'cours_php'];
file_put_contents('config.json', json_encode($config, JSON_PRETTY_PRINT));

// Lire du JSON
$config = json_decode(file_get_contents('config.json'), true);
echo $config['version'];  // 1.0
?>
```

---

## 3. Gestion des fichiers et dossiers

```php
<?php
// Vérifications
echo file_exists('fichier.txt') ? "Existe" : "N'existe pas";
echo is_file('fichier.txt') ? "C'est un fichier" : "Pas un fichier";
echo is_dir('dossier/') ? "C'est un dossier" : "Pas un dossier";
echo is_readable('fichier.txt') ? "Lisible" : "Non lisible";
echo is_writable('fichier.txt') ? "Modifiable" : "Non modifiable";

// Informations sur un fichier
echo filesize('fichier.txt') . " octets";
echo filemtime('fichier.txt');  // Timestamp de modification
echo pathinfo('chemin/vers/fichier.php', PATHINFO_EXTENSION);  // php
echo pathinfo('chemin/vers/fichier.php', PATHINFO_FILENAME);   // fichier
echo pathinfo('chemin/vers/fichier.php', PATHINFO_DIRNAME);    // chemin/vers

// Opérations
copy('source.txt', 'destination.txt');     // Copier
rename('ancien.txt', 'nouveau.txt');        // Renommer/déplacer
unlink('fichier.txt');                      // Supprimer un fichier

// Dossiers
mkdir('nouveau-dossier', 0755, true);       // Créer (true = récursif)
rmdir('dossier-vide');                      // Supprimer (doit être vide)
$fichiers = glob('*.txt');                  // Lister les fichiers *.txt
$fichiers = scandir('dossier/');            // Tous les fichiers d'un dossier

// Lister récursivement
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator('src/', RecursiveDirectoryIterator::SKIP_DOTS)
);
foreach ($iterator as $fichier) {
    echo $fichier->getPathname() . PHP_EOL;
}
?>
```

---

## 4. Upload de fichiers sécurisé

```php
<?php
// Formulaire HTML requis :
// <form method="POST" enctype="multipart/form-data">
//   <input type="file" name="photo">
// </form>

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $fichier = $_FILES['photo'];

    // Vérifications de sécurité
    if ($fichier['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException("Erreur d'upload : " . $fichier['error']);
    }

    // Valider le type MIME réel (pas l'extension)
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $typeMime = $finfo->file($fichier['tmp_name']);
    $typesAutorises = ['image/jpeg', 'image/png', 'image/gif'];

    if (!in_array($typeMime, $typesAutorises)) {
        throw new RuntimeException("Type de fichier non autorisé.");
    }

    // Taille maximale : 5 Mo
    if ($fichier['size'] > 5 * 1024 * 1024) {
        throw new RuntimeException("Fichier trop volumineux.");
    }

    // Nom aléatoire sécurisé (jamais utiliser le nom original !)
    $extension = match($typeMime) {
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
    };
    $nomFichier = bin2hex(random_bytes(16)) . '.' . $extension;
    $destination = '/uploads/' . $nomFichier;

    if (!move_uploaded_file($fichier['tmp_name'], $destination)) {
        throw new RuntimeException("Impossible de déplacer le fichier.");
    }

    echo "Fichier uploadé : " . $nomFichier;
}
?>
```

---

## Résumé du module 10

| Opération | Fonction |
|-----------|---------|
| Lire tout | `file_get_contents()` |
| Écrire tout | `file_put_contents()` |
| Lire ligne à ligne | `fopen()` + `fgets()` + `fclose()` |
| CSV | `fgetcsv()` / `fputcsv()` |
| Vérifier existence | `file_exists()`, `is_file()` |
| Supprimer | `unlink()` |
| Upload | `$_FILES` + `move_uploaded_file()` |

**➡️ [Module 11 — Formulaires](../11-formulaires/README.md)**
