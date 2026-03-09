# Module 02 — Installation et environnement de développement

> **Niveau :** Débutant absolu
> **Durée estimée :** 1 à 2 heures
> **Prérequis :** Module 01 — Introduction à PHP

---

## Table des matières

1. [Comprendre ce dont vous avez besoin](#1-comprendre-ce-dont-vous-avez-besoin)
2. [Installation de XAMPP (recommandé)](#2-installation-de-xampp-recommandé)
3. [Installation de WAMP (Windows)](#3-installation-de-wamp-windows)
4. [Installation de MAMP (Mac)](#4-installation-de-mamp-mac)
5. [Installation manuelle de PHP](#5-installation-manuelle-de-php)
6. [Vérifier l'installation de PHP](#6-vérifier-linstallation-de-php)
7. [Choisir et configurer un éditeur de code](#7-choisir-et-configurer-un-éditeur-de-code)
8. [Créer votre premier projet](#8-créer-votre-premier-projet)
9. [Comprendre la configuration PHP](#9-comprendre-la-configuration-php)
10. [Exercices pratiques](#10-exercices-pratiques)
11. [Corrigés](#11-corrigés)

---

## 1. Comprendre ce dont vous avez besoin

### La pile technologique (AMP Stack)

Pour faire tourner du PHP sur votre machine, vous avez besoin de trois composants :

```
┌─────────────────────────────────────────────────┐
│              La pile AMP (LAMP/WAMP/MAMP/XAMPP) │
│                                                  │
│  ┌──────────────────┐                           │
│  │  Serveur Web     │  ← Apache ou Nginx        │
│  │  (Apache)        │    Reçoit les requêtes HTTP│
│  └──────────────────┘                           │
│           ↕                                      │
│  ┌──────────────────┐                           │
│  │  Moteur PHP      │  ← Interprète le PHP      │
│  │                  │    Génère le HTML          │
│  └──────────────────┘                           │
│           ↕                                      │
│  ┌──────────────────┐                           │
│  │  Base de données │  ← MySQL ou MariaDB       │
│  │  (MySQL)         │    Stocke les données      │
│  └──────────────────┘                           │
│                                                  │
│  + phpMyAdmin (interface graphique pour MySQL)   │
└─────────────────────────────────────────────────┘
```

| Lettre | Signification | Rôle |
|--------|---------------|------|
| **A** | Apache | Serveur Web |
| **M** | MySQL | Base de données |
| **P** | PHP | Langage de programmation |

Les solutions **XAMPP**, **WAMP** et **MAMP** installent ces trois composants **en un seul clic**, ce qui est idéal pour débuter.

---

## 2. Installation de XAMPP (recommandé)

XAMPP est la solution **la plus populaire** et fonctionne sur **Windows, Mac et Linux**.

### Étape 1 — Télécharger XAMPP

1. Rendez-vous sur le site officiel : 🔗 [https://www.apachefriends.org/fr/index.html](https://www.apachefriends.org/fr/index.html)
2. Cliquez sur le bouton de téléchargement correspondant à votre système d'exploitation
3. Choisissez la version avec **PHP 8.x** (la plus récente disponible)

### Étape 2 — Installer XAMPP

**Sur Windows :**
1. Double-cliquez sur le fichier `.exe` téléchargé
2. Si Windows Defender vous avertit, cliquez "Plus d'informations" → "Exécuter quand même"
3. Cliquez "Next" jusqu'à la fin
4. **Chemin d'installation recommandé :** `C:\xampp` (ne changez pas ce chemin !)
5. Laissez toutes les cases cochées (Apache, PHP, MySQL, phpMyAdmin)
6. Cliquez "Install"

**Sur Linux :**
```bash
# Rendre le fichier exécutable
chmod +x xampp-linux-x64-8.x.x-installer.run

# Lancer l'installation (avec les droits admin)
sudo ./xampp-linux-x64-8.x.x-installer.run
```

**Sur Mac :**
1. Ouvrez le fichier `.dmg` téléchargé
2. Glissez XAMPP dans le dossier Applications
3. Lancez XAMPP depuis le dossier Applications

### Étape 3 — Démarrer XAMPP

1. Ouvrez le **XAMPP Control Panel**
2. Cliquez "Start" à côté de **Apache**
3. Cliquez "Start" à côté de **MySQL**

Les deux services doivent passer au **vert** :

```
Module    PID(s)    Port(s)    Actions
Apache    1234      80, 443    Stop | Admin | Config | Logs
MySQL     5678      3306       Stop | Admin | Config | Logs
```

### Étape 4 — Tester l'installation

Ouvrez votre navigateur et tapez :
```
http://localhost
```

Vous devriez voir la **page d'accueil XAMPP** avec le tableau de bord. Si c'est le cas, tout fonctionne !

### Le dossier htdocs — Votre espace de travail

Avec XAMPP, tous vos fichiers PHP doivent être placés dans :

```
Windows : C:\xampp\htdocs\
Linux   : /opt/lampp/htdocs/
Mac     : /Applications/XAMPP/htdocs/
```

> ⚠️ **Important** : Tout fichier PHP que vous souhaitez exécuter **doit être** dans ce dossier (ou un sous-dossier). C'est le **répertoire racine** du serveur Web.

**Exemple :**
- Vous créez : `C:\xampp\htdocs\mon-site\index.php`
- Vous accédez dans le navigateur : `http://localhost/mon-site/index.php`

---

## 3. Installation de WAMP (Windows)

WAMP est une alternative populaire à XAMPP sur Windows.

### Téléchargement et installation

1. Téléchargez sur : 🔗 [https://www.wampserver.com/](https://www.wampserver.com/)
2. Choisissez la version 64 bits si votre Windows est en 64 bits (ce qui est le cas pour la grande majorité des ordinateurs modernes)
3. Installez et lancez WampServer
4. L'icône dans la barre des tâches doit passer au **vert** (services démarrés)

**Dossier de travail WAMP :**
```
C:\wamp64\www\
```

---

## 4. Installation de MAMP (Mac)

MAMP est la solution recommandée sur Mac.

1. Téléchargez sur : 🔗 [https://www.mamp.info/en/](https://www.mamp.info/en/)
2. Installez et ouvrez MAMP
3. Cliquez "Start Servers"
4. Cliquez "Open WebStart page" pour vérifier

**Dossier de travail MAMP :**
```
/Applications/MAMP/htdocs/
```

**Port par défaut MAMP :** `http://localhost:8888`

---

## 5. Installation manuelle de PHP

Pour les développeurs qui souhaitent plus de contrôle, PHP peut être installé séparément.

### Sur Linux (Ubuntu/Debian)

```bash
# Mettre à jour les paquets
sudo apt update

# Installer PHP et les extensions courantes
sudo apt install php php-cli php-mysql php-curl php-mbstring php-zip

# Vérifier l'installation
php --version
# Résultat attendu : PHP 8.x.x (cli) ...

# Installer Apache
sudo apt install apache2

# Démarrer Apache
sudo systemctl start apache2
sudo systemctl enable apache2  # Démarrage automatique au boot
```

### Sur Windows (sans XAMPP)

1. Téléchargez PHP sur 🔗 [https://windows.php.net/download/](https://windows.php.net/download/)
2. Choisissez la version "Thread Safe" (TS) en 64 bits
3. Décompressez dans `C:\php`
4. Ajoutez `C:\php` à la variable d'environnement `PATH`

---

## 6. Vérifier l'installation de PHP

### Via le navigateur

Créez un fichier `info.php` dans votre dossier htdocs :

```php
<?php
/**
 * Fichier : info.php
 * 
 * phpinfo() affiche toutes les informations sur votre installation PHP.
 * C'est un outil de diagnostic très utile.
 * 
 * ATTENTION : Ne laissez JAMAIS ce fichier sur un serveur de production !
 * Il révèle des informations sensibles sur votre serveur.
 */

phpinfo();
?>
```

Ouvrez `http://localhost/info.php` dans votre navigateur.

Vous devriez voir une **longue page colorée** avec toutes les informations PHP :
- La version de PHP installée
- Les extensions activées
- La configuration (php.ini)

> ⚠️ **Sécurité** : Supprimez ce fichier `info.php` une fois votre vérification terminée. Ne le laissez **jamais** sur un serveur en production car il expose des informations sensibles sur votre configuration.

### Via le terminal/ligne de commande

```bash
# Affiche la version de PHP
php --version
# Exemple de résultat :
# PHP 8.2.0 (cli) (built: Nov 26 2022 07:55:33) (NTS)

# Lance un serveur de développement intégré (PHP 5.4+)
# Utile pour tester rapidement sans Apache
php -S localhost:8000

# Puis ouvrez : http://localhost:8000
```

---

## 7. Choisir et configurer un éditeur de code

### Visual Studio Code (VS Code) — Recommandé

VS Code est gratuit, puissant et très populaire dans la communauté PHP.

🔗 [Télécharger VS Code](https://code.visualstudio.com/)

**Extensions PHP recommandées pour VS Code :**

| Extension | Description | Lien |
|-----------|-------------|------|
| PHP Intelephense | Autocomplétion intelligente | VS Code Marketplace |
| PHP Debug | Débogage avec Xdebug | VS Code Marketplace |
| PHP Formatter | Formater automatiquement le code | VS Code Marketplace |
| GitLens | Gestion Git avancée | VS Code Marketplace |

**Pour installer une extension :**
1. Ouvrez VS Code
2. Cliquez sur l'icône Extensions (ou `Ctrl+Shift+X`)
3. Recherchez le nom de l'extension
4. Cliquez "Install"

### PhpStorm — L'IDE professionnel

PhpStorm est l'IDE (Environnement de Développement Intégré) le plus puissant pour PHP. Il est **payant** mais propose une période d'essai de 30 jours.

🔗 [PhpStorm](https://www.jetbrains.com/phpstorm/)

> 💡 **Conseil** : Pour débuter, VS Code est amplement suffisant. PhpStorm est recommandé pour les projets professionnels de grande envergure.

### Configuration VS Code pour PHP

Dans VS Code, ouvrez les paramètres (`Ctrl+,`) et ajoutez :

```json
{
    "editor.tabSize": 4,
    "editor.detectIndentation": false,
    "files.eol": "\n",
    "php.validate.enable": true
}
```

---

## 8. Créer votre premier projet

### Structure recommandée pour débuter

```
C:\xampp\htdocs\mon-premier-projet\
├── index.php          ← Page principale
├── css/
│   └── style.css      ← Styles CSS
├── js/
│   └── script.js      ← JavaScript (si besoin)
└── images/
    └── logo.png       ← Images
```

### Fichier index.php de démarrage

```php
<?php
/**
 * Fichier  : index.php
 * Projet   : Mon premier projet PHP
 * 
 * C'est la page d'accueil de votre site.
 * Le fichier index.php est automatiquement affiché quand on
 * accède à un dossier sans spécifier de fichier.
 * Ex: http://localhost/mon-premier-projet/ affiche index.php
 */

// Déclaration strict des types (bonne pratique PHP 7+)
declare(strict_types=1);

// On définit quelques variables
$titre_site = "Mon Premier Site PHP";
$message    = "Bienvenue dans le monde de PHP !";
$date_jour  = date("d/m/Y");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titre_site; ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            background: #f5f5f5;
            color: #333;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 { color: #2c3e50; }
        .info { background: #e8f4f8; padding: 15px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo $titre_site; ?></h1>

        <p><?php echo $message; ?></p>

        <div class="info">
            <p><strong>Date du jour :</strong> <?php echo $date_jour; ?></p>
            <p><strong>Version PHP :</strong> <?php echo phpversion(); ?></p>
            <p><strong>Serveur Web :</strong> <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Inconnu'; ?></p>
        </div>

        <p><em>Votre installation PHP fonctionne correctement ! 🎉</em></p>
    </div>
</body>
</html>
```

Ouvrez `http://localhost/mon-premier-projet/` pour voir le résultat.

---

## 9. Comprendre la configuration PHP

### Le fichier php.ini

PHP est configuré via un fichier appelé **`php.ini`**. Ce fichier contient des centaines de paramètres qui contrôlent le comportement de PHP.

**Localiser votre php.ini :**

```php
<?php
// Ce code affiche le chemin du fichier php.ini utilisé
echo phpini_loaded_file();

// Ou plus simplement avec phpinfo() :
phpinfo();
// Cherchez "Loaded Configuration File" dans la page
?>
```

Ou en ligne de commande :
```bash
php --ini
```

### Paramètres importants dans php.ini

```ini
; Afficher les erreurs (ACTIVEZ sur le serveur de développement)
; DÉSACTIVEZ absolument sur le serveur de production !
display_errors = On

; Niveau d'erreurs à rapporter
error_reporting = E_ALL

; Taille maximale des fichiers uploadés
upload_max_filesize = 10M

; Taille maximale des données POST (formulaires)
post_max_size = 10M

; Durée maximale d'exécution d'un script (en secondes)
max_execution_time = 30

; Mémoire maximale qu'un script peut utiliser
memory_limit = 128M

; Fuseau horaire par défaut
date.timezone = "Europe/Paris"
```

### Modifier php.ini

1. Trouvez votre `php.ini` (via `phpinfo()` ou `php --ini`)
2. Ouvrez-le dans un éditeur de texte **en tant qu'administrateur**
3. Modifiez les valeurs souhaitées
4. **Redémarrez Apache** pour que les changements prennent effet (via le XAMPP Control Panel)

> ⚠️ **Prudence** : Modifiez php.ini avec précaution. Une erreur de syntaxe peut empêcher PHP de fonctionner.

---

## 10. Exercices pratiques

### Exercice 1 — Vérifier l'installation

Créez un fichier `verification.php` qui affiche :
- La version de PHP
- Le nom du système d'exploitation du serveur
- Le chemin du fichier php.ini
- La date et heure actuelles

*Indices : `phpversion()`, `PHP_OS`, `php_ini_loaded_file()`, `date()`*

### Exercice 2 — Page d'accueil personnalisée

Créez un projet avec la structure recommandée et une page `index.php` qui affiche votre prénom, votre passion et le jour de la semaine.

---

## 11. Corrigés

### Corrigé Exercice 1

```php
<?php
/**
 * verification.php — Vérifie et affiche les infos de l'installation PHP
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérification PHP</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Vérification de l'installation PHP</h1>

    <table>
        <tr>
            <th>Information</th>
            <th>Valeur</th>
        </tr>
        <tr>
            <td>Version de PHP</td>
            <td><?php echo phpversion(); ?></td>
        </tr>
        <tr>
            <td>Système d'exploitation</td>
            <td><?php echo PHP_OS; ?></td>
        </tr>
        <tr>
            <td>Chemin de php.ini</td>
            <td><?php echo php_ini_loaded_file() ?: "Non trouvé"; ?></td>
        </tr>
        <tr>
            <td>Date et heure actuelles</td>
            <td><?php echo date("d/m/Y H:i:s"); ?></td>
        </tr>
    </table>
</body>
</html>
```

---

## Résumé du module 02

| Outil | Système | Dossier de travail |
|-------|---------|-------------------|
| XAMPP | Windows / Linux / Mac | `htdocs/` |
| WAMP | Windows | `www/` |
| MAMP | Mac | `htdocs/` |

**Ce qu'il faut retenir :**

1. PHP nécessite un serveur Web (Apache) pour fonctionner
2. XAMPP est la solution la plus simple pour débuter
3. Placez vos fichiers dans `htdocs/` et accédez-y via `http://localhost/`
4. VS Code avec l'extension PHP Intelephense est l'environnement recommandé
5. Configurez `display_errors = On` pendant le développement

---

**➡️ Module suivant : [Module 03 — Bases du langage PHP](../03-bases-langage/README.md)**

🔗 [Retour au sommaire principal](../../README.md)
