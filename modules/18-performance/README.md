# Module 18 — Performance et optimisation PHP

> **Niveau :** Expert
> **Durée estimée :** 4 à 5 heures
> **Prérequis :** Modules 01 à 17

---

## Table des matières

1. [Profiling — Identifier les goulots d'étranglement](#1-profiling--identifier-les-goulots-détranglement)
2. [Optimisation du code PHP](#2-optimisation-du-code-php)
3. [OPcache — Cache de bytecode](#3-opcache--cache-de-bytecode)
4. [Mise en cache applicative](#4-mise-en-cache-applicative)
5. [Optimisation des requêtes SQL](#5-optimisation-des-requêtes-sql)
6. [Gestion de la mémoire](#6-gestion-de-la-mémoire)
7. [Optimisation des assets](#7-optimisation-des-assets)
8. [Checklist de performance](#8-checklist-de-performance)

---

## 1. Profiling — Identifier les goulots d'étranglement

**Règle fondamentale : Mesurez avant d'optimiser.**

```php
<?php
/**
 * Micro-benchmark simple pour mesurer le temps d'exécution.
 */
function mesurer(string $nom, callable $fn, int $iterations = 1000): void {
    $debut = hrtime(true);  // High-resolution time en nanosecondes

    for ($i = 0; $i < $iterations; $i++) {
        $fn();
    }

    $fin    = hrtime(true);
    $totalNs = $fin - $debut;
    $moyenneMs = ($totalNs / $iterations) / 1_000_000;

    printf("%-30s %d itérations — Moyenne : %.4f ms%s",
        $nom, $iterations, $moyenneMs, PHP_EOL);
}

// Comparer deux approches
mesurer('strlen()', fn() => strlen(str_repeat('a', 1000)));
mesurer('mb_strlen()', fn() => mb_strlen(str_repeat('a', 1000)));
// strlen() est généralement plus rapide pour les chaînes ASCII pures

// Mémoire utilisée
echo memory_get_usage(true) / 1024 . " Ko utilisés" . PHP_EOL;
echo memory_get_peak_usage(true) / 1024 . " Ko pic mémoire" . PHP_EOL;
?>
```

### Xdebug + profiling

```bash
# Activer le profiling dans php.ini
xdebug.mode=profile
xdebug.output_dir=/tmp/xdebug
xdebug.profiler_output_name=cachegrind.out.%p

# Analyser avec KCacheGrind (Linux) ou WinCacheGrind (Windows)
```

---

## 2. Optimisation du code PHP

### Boucles et tableaux

```php
<?php
// ❌ Lent : count() appelé à CHAQUE itération
for ($i = 0; $i < count($tableau); $i++) {
    // count() parcourt le tableau à chaque tour !
}

// ✅ Rapide : count() appelé UNE SEULE FOIS
$longueur = count($tableau);
for ($i = 0; $i < $longueur; $i++) {
    // ...
}

// ✅ Encore plus simple avec foreach (recommandé pour les tableaux)
foreach ($tableau as $element) {
    // ...
}

// ❌ Lent : concaténation dans une boucle (crée de nouveaux objets string)
$html = '';
for ($i = 0; $i < 1000; $i++) {
    $html .= '<li>' . $i . '</li>';
}

// ✅ Rapide : accumuler dans un tableau puis implode()
$items = [];
for ($i = 0; $i < 1000; $i++) {
    $items[] = '<li>' . $i . '</li>';
}
$html = implode('', $items);

// ✅ Ou utiliser le buffering de sortie
ob_start();
for ($i = 0; $i < 1000; $i++) {
    echo '<li>' . $i . '</li>';
}
$html = ob_get_clean();
?>
```

### Utiliser les fonctions natives PHP

```php
<?php
// Les fonctions natives PHP sont implémentées en C → beaucoup plus rapides

// ❌ Boucle PHP manuelle
$somme = 0;
foreach ($nombres as $n) {
    $somme += $n;
}

// ✅ Fonction native (implémentée en C, beaucoup plus rapide)
$somme = array_sum($nombres);

// ❌ Chercher un élément manuellement
$trouve = false;
foreach ($tableau as $val) {
    if ($val === 'PHP') { $trouve = true; break; }
}

// ✅ Fonction native
$trouve = in_array('PHP', $tableau, true);

// Pour chercher par clé, isset() >> array_key_exists() >> in_array()
// isset() est le plus rapide car il n'appelle pas de fonction
$cle = 'nom';
if (isset($tableau[$cle])) { /* le plus rapide */ }
?>
```

### Éviter les requêtes N+1

```php
<?php
// ❌ Problème N+1 : 1 requête pour les articles + N requêtes pour les auteurs
$articles = $pdo->query("SELECT id, titre, auteur_id FROM articles")->fetchAll();
foreach ($articles as $article) {
    // Une requête supplémentaire pour CHAQUE article !
    $auteur = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?")->execute([$article['auteur_id']]);
}
// Si 100 articles → 101 requêtes SQL !

// ✅ Solution : JOIN (une seule requête)
$articles = $pdo->query("
    SELECT a.id, a.titre, u.prenom, u.nom
    FROM articles a
    JOIN utilisateurs u ON a.auteur_id = u.id
")->fetchAll();
// 1 seule requête, peu importe le nombre d'articles !

// ✅ Ou charger les auteurs en une seule requête IN()
$ids_auteurs = array_unique(array_column($articles, 'auteur_id'));
$placeholders = implode(',', array_fill(0, count($ids_auteurs), '?'));
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id IN ($placeholders)");
$stmt->execute($ids_auteurs);
$auteurs = array_column($stmt->fetchAll(), null, 'id');  // Indexer par id
?>
```

---

## 3. OPcache — Cache de bytecode

PHP doit normalement compiler chaque script à chaque requête. **OPcache** stocke le bytecode compilé en mémoire pour éviter cette recompilation.

```ini
; php.ini — Configuration OPcache recommandée pour la production
opcache.enable=1
opcache.enable_cli=0
opcache.memory_consumption=128          ; 128 Mo de mémoire pour le cache
opcache.interned_strings_buffer=16      ; 16 Mo pour les chaînes internées
opcache.max_accelerated_files=10000     ; Nombre max de fichiers en cache
opcache.revalidate_freq=60              ; Vérifier les modifications toutes les 60s
opcache.validate_timestamps=1           ; En dev: 1, en prod: 0 (désactiver la vérif)
opcache.fast_shutdown=1                 ; Libération mémoire plus rapide
opcache.jit=1255                        ; PHP 8 : activer le JIT
opcache.jit_buffer_size=128M
```

```php
<?php
// Vérifier l'état d'OPcache
$status = opcache_get_status();
echo "OPcache actif : " . ($status['opcache_enabled'] ? 'Oui' : 'Non') . PHP_EOL;
echo "Mémoire utilisée : " . round($status['memory_usage']['used_memory'] / 1024 / 1024, 2) . " Mo" . PHP_EOL;
echo "Fichiers en cache : " . $status['opcache_statistics']['num_cached_scripts'] . PHP_EOL;

// En production : invalider le cache après un déploiement
opcache_reset();
?>
```

---

## 4. Mise en cache applicative

### Cache avec APCu (mémoire partagée)

```php
<?php
// APCu stocke des données en mémoire RAM (très rapide)
// Idéal pour : résultats de requêtes coûteuses, configuration, compteurs

function obtenirArticlesPopulaires(PDO $pdo): array {
    $cleCache = 'articles_populaires';

    // 1. Vérifier si le résultat est déjà en cache
    if (apcu_exists($cleCache)) {
        return apcu_fetch($cleCache);
    }

    // 2. Non trouvé : exécuter la requête (plus lente)
    $articles = $pdo->query("
        SELECT titre, vues FROM articles
        ORDER BY vues DESC LIMIT 10
    ")->fetchAll();

    // 3. Stocker en cache pour 5 minutes (300 secondes)
    apcu_store($cleCache, $articles, 300);

    return $articles;
}
?>
```

### Cache avec Redis

```php
<?php
// Redis est un serveur de cache haute performance
// Utilisé dans les applications à fort trafic

// Installation : composer require predis/predis

use Predis\Client;

class CacheRedis {
    private Client $redis;

    public function __construct() {
        $this->redis = new Client([
            'scheme' => 'tcp',
            'host'   => '127.0.0.1',
            'port'   => 6379,
        ]);
    }

    public function get(string $cle): mixed {
        $valeur = $this->redis->get($cle);
        return $valeur !== null ? unserialize($valeur) : null;
    }

    public function set(string $cle, mixed $valeur, int $ttl = 3600): void {
        $this->redis->setex($cle, $ttl, serialize($valeur));
    }

    public function delete(string $cle): void {
        $this->redis->del($cle);
    }

    public function remember(string $cle, int $ttl, callable $callback): mixed {
        $valeur = $this->get($cle);
        if ($valeur !== null) {
            return $valeur;
        }
        $valeur = $callback();
        $this->set($cle, $valeur, $ttl);
        return $valeur;
    }
}

// Utilisation
$cache    = new CacheRedis();
$articles = $cache->remember('articles_home', 600, function() use ($pdo) {
    return $pdo->query("SELECT * FROM articles WHERE publie=1 LIMIT 10")->fetchAll();
});
?>
```

### Cache de fichiers simple

```php
<?php
/**
 * Cache de fichiers simple — pas de dépendance externe.
 */
class CacheFichiers {
    public function __construct(private string $repertoire = '/tmp/cache') {
        if (!is_dir($this->repertoire)) {
            mkdir($this->repertoire, 0755, true);
        }
    }

    private function cheminFichier(string $cle): string {
        return $this->repertoire . '/' . md5($cle) . '.cache';
    }

    public function get(string $cle): mixed {
        $fichier = $this->cheminFichier($cle);
        if (!file_exists($fichier)) {
            return null;
        }

        $donnees = unserialize(file_get_contents($fichier));

        // Vérifier l'expiration
        if ($donnees['expiration'] < time()) {
            unlink($fichier);  // Supprimer le fichier expiré
            return null;
        }

        return $donnees['valeur'];
    }

    public function set(string $cle, mixed $valeur, int $ttl = 3600): void {
        $donnees = ['valeur' => $valeur, 'expiration' => time() + $ttl];
        file_put_contents($this->cheminFichier($cle), serialize($donnees), LOCK_EX);
    }
}
?>
```

---

## 5. Optimisation des requêtes SQL

```sql
-- Toujours ajouter des INDEX sur les colonnes utilisées dans WHERE, JOIN, ORDER BY

-- Avant : requête lente sans index
EXPLAIN SELECT * FROM articles WHERE auteur_id = 5;
-- Type: ALL (parcourt toute la table !)

-- Créer un index
CREATE INDEX idx_articles_auteur ON articles(auteur_id);
CREATE INDEX idx_articles_publie_date ON articles(publie, cree_le DESC);

-- Après : requête rapide avec index
EXPLAIN SELECT * FROM articles WHERE auteur_id = 5;
-- Type: ref (utilise l'index)

-- Éviter SELECT * — sélectionner uniquement les colonnes nécessaires
-- ❌ Lent
SELECT * FROM articles WHERE publie = 1;

-- ✅ Rapide
SELECT id, titre, cree_le FROM articles WHERE publie = 1;

-- Utiliser LIMIT pour paginer
SELECT id, titre FROM articles ORDER BY cree_le DESC LIMIT 10 OFFSET 0;

-- Pagination par curseur (plus efficace pour les grandes tables)
SELECT id, titre FROM articles WHERE id < :last_id ORDER BY id DESC LIMIT 10;
```

```php
<?php
// Lazy loading : charger les données uniquement si nécessaire
class ArticleService {
    private ?array $articlesCache = null;

    public function getArticles(): array {
        // Ne charge depuis la BDD qu'au premier appel
        if ($this->articlesCache === null) {
            $this->articlesCache = $this->pdo->query("SELECT * FROM articles")->fetchAll();
        }
        return $this->articlesCache;
    }
}
?>
```

---

## 6. Gestion de la mémoire

```php
<?php
// ❌ Charger des millions de lignes en mémoire
$lignes = $pdo->query("SELECT * FROM logs")->fetchAll();  // Peut crasher !

// ✅ Utiliser un générateur pour traiter ligne par ligne
function lireLignes(PDO $pdo): Generator {
    $stmt = $pdo->query("SELECT * FROM logs");
    while ($ligne = $stmt->fetch()) {
        yield $ligne;  // Retourne une ligne à la fois (1 ligne en mémoire)
    }
}

foreach (lireLignes($pdo) as $ligne) {
    traiterLigne($ligne);  // Mémoire constante, peu importe le nombre de lignes
}

// Libérer la mémoire explicitement
unset($grandTableau);
gc_collect_cycles();  // Forcer le ramasse-miettes
?>
```

---

## 7. Optimisation des assets

```php
<?php
// Compresser la sortie HTML avec gzip
ob_start("ob_gzhandler");

// Ou configurer dans .htaccess / nginx :
// AddOutputFilterByType DEFLATE text/html text/css application/javascript

// Minifier le HTML en sortie
function minifierHTML(string $html): string {
    // Supprimer les commentaires HTML
    $html = preg_replace('/<!--(?!\[if).*?-->/s', '', $html);
    // Supprimer les espaces multiples
    $html = preg_replace('/\s+/', ' ', $html);
    return trim($html);
}
?>
```

---

## 8. Checklist de performance

### 🔧 PHP et serveur
- [ ] OPcache activé et bien configuré
- [ ] PHP 8.x avec JIT activé (CPU intensif)
- [ ] `php.ini` : `realpath_cache_size` augmenté
- [ ] Serveur Web : Nginx (plus rapide qu'Apache pour le statique)
- [ ] PHP-FPM en mode pool optimisé

### 🗄️ Base de données
- [ ] Index sur toutes les colonnes de `WHERE`, `JOIN`, `ORDER BY`
- [ ] `EXPLAIN` utilisé pour analyser les requêtes lentes
- [ ] `SELECT *` remplacé par les colonnes nécessaires
- [ ] Pas de problème N+1 (utiliser `JOIN` ou eager loading)
- [ ] Connection pooling (PgBouncer, ProxySQL)

### 💾 Cache
- [ ] OPcache pour le bytecode PHP
- [ ] APCu ou Redis pour les données applicatives
- [ ] Cache HTTP (en-têtes `Cache-Control`, `ETag`)
- [ ] CDN pour les assets statiques

### 💻 Code PHP
- [ ] `count()` hors des boucles `for`
- [ ] Fonctions natives PHP préférées aux boucles manuelles
- [ ] Générateurs pour les gros volumes de données
- [ ] Traitement asynchrone pour les tâches longues (files de messages)

---

## Résumé du module 18

| Technique | Impact | Difficulté |
|-----------|--------|------------|
| OPcache | Très élevé | Faible (config php.ini) |
| Index SQL | Très élevé | Faible (ALTER TABLE) |
| Éviter N+1 | Élevé | Moyenne |
| Redis/APCu | Élevé | Moyenne |
| JIT PHP 8 | Moyen-élevé (CPU) | Faible (config) |
| Générateurs | Moyen (mémoire) | Faible |

---

🔗 [Retour au sommaire principal](../../README.md)
