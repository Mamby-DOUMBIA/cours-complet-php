# 📊 Tableaux récapitulatifs des fonctions PHP

---

## Fonctions de chaînes de caractères

| Fonction | Description | Exemple | Résultat |
|----------|-------------|---------|----------|
| `strlen($s)` | Longueur en octets | `strlen("Bonjour")` | `7` |
| `mb_strlen($s)` | Longueur Unicode | `mb_strlen("été")` | `3` |
| `strtolower($s)` | Minuscules | `strtolower("PHP")` | `"php"` |
| `strtoupper($s)` | Majuscules | `strtoupper("php")` | `"PHP"` |
| `ucfirst($s)` | 1ère lettre majuscule | `ucfirst("bonjour")` | `"Bonjour"` |
| `ucwords($s)` | 1ère lettre de chaque mot | `ucwords("bonjour le monde")` | `"Bonjour Le Monde"` |
| `trim($s)` | Supprime espaces début/fin | `trim("  texte  ")` | `"texte"` |
| `ltrim($s)` | Supprime espaces au début | `ltrim("  texte")` | `"texte"` |
| `rtrim($s)` | Supprime espaces à la fin | `rtrim("texte  ")` | `"texte"` |
| `substr($s, d, l)` | Sous-chaîne | `substr("Bonjour", 0, 3)` | `"Bon"` |
| `strpos($s, $c)` | Position (false si absent) | `strpos("PHP", "H")` | `1` |
| `strrpos($s, $c)` | Dernière position | `strrpos("PHP PHP", "PHP")` | `4` |
| `str_contains($s, $c)` | Contient ? (PHP 8+) | `str_contains("PHP", "H")` | `true` |
| `str_starts_with($s, $p)` | Commence par ? (PHP 8+) | `str_starts_with("PHP", "PH")` | `true` |
| `str_ends_with($s, $sfx)` | Finit par ? (PHP 8+) | `str_ends_with("PHP", "HP")` | `true` |
| `str_replace($f, $r, $s)` | Remplacement | `str_replace("e", "é", "texte")` | `"téxté"` |
| `str_ireplace($f, $r, $s)` | Remplacement insensible casse | `str_ireplace("PHP", "Python", "j'aime php")` | `"j'aime Python"` |
| `explode($sep, $s)` | Divise en tableau | `explode(",", "a,b,c")` | `["a","b","c"]` |
| `implode($sep, $arr)` | Joint un tableau | `implode("-", ["a","b"])` | `"a-b"` |
| `str_repeat($s, $n)` | Répète | `str_repeat("ab", 3)` | `"ababab"` |
| `str_pad($s, $n, $p, $t)` | Rembourrage | `str_pad("42", 5, "0", STR_PAD_LEFT)` | `"00042"` |
| `wordwrap($s, $w, $b)` | Coupe à N caractères | `wordwrap("long texte", 5, "\n")` | `"long\ntexte"` |
| `nl2br($s)` | \n → `<br>` | `nl2br("ligne1\nligne2")` | `"ligne1<br>\nligne2"` |
| `htmlspecialchars($s)` | Échappe HTML | `htmlspecialchars("<b>")` | `"&lt;b&gt;"` |
| `strip_tags($s)` | Supprime balises HTML | `strip_tags("<p>texte</p>")` | `"texte"` |
| `sprintf($fmt, ...)` | Formatage | `sprintf("%.2f€", 19.9)` | `"19.90€"` |
| `number_format($n, $d, $dp, $ts)` | Formatage nombre | `number_format(1234.5, 2, ',', ' ')` | `"1 234,50"` |
| `preg_match($pat, $s)` | Test regex | `preg_match('/^\d+$/', "123")` | `1` |
| `preg_replace($pat, $r, $s)` | Remplacement regex | `preg_replace('/\s+/', ' ', "a  b")` | `"a b"` |
| `urlencode($s)` | Encodage URL | `urlencode("PHP est génial")` | `"PHP+est+g%C3%A9nial"` |
| `base64_encode($s)` | Encodage Base64 | `base64_encode("PHP")` | `"UEHQ"` |
| `md5($s)` | Hash MD5 (32 hex) | `md5("test")` | `"098f6bcd..."` |
| `hash('sha256', $s)` | Hash SHA-256 | `hash('sha256', "test")` | `"9f86d081..."` |

---

## Fonctions mathématiques

| Fonction | Description | Exemple | Résultat |
|----------|-------------|---------|----------|
| `abs($n)` | Valeur absolue | `abs(-5)` | `5` |
| `ceil($n)` | Arrondi supérieur | `ceil(4.1)` | `5` |
| `floor($n)` | Arrondi inférieur | `floor(4.9)` | `4` |
| `round($n, $d)` | Arrondi normal | `round(4.567, 2)` | `4.57` |
| `max($a, $b, ...)` | Valeur maximale | `max(3, 7, 2)` | `7` |
| `min($a, $b, ...)` | Valeur minimale | `min(3, 7, 2)` | `2` |
| `pow($base, $exp)` | Puissance | `pow(2, 8)` | `256` |
| `sqrt($n)` | Racine carrée | `sqrt(16)` | `4.0` |
| `rand($min, $max)` | Nombre aléatoire | `rand(1, 100)` | `42` (aléatoire) |
| `mt_rand($min, $max)` | Nombre aléatoire (meilleur) | `mt_rand(1, 6)` | `3` (aléatoire) |
| `intdiv($a, $b)` | Division entière | `intdiv(7, 2)` | `3` |
| `fmod($a, $b)` | Modulo float | `fmod(7.5, 2.5)` | `0.0` |
| `pi()` | Valeur de π | `pi()` | `3.14159...` |
| `log($n)` | Logarithme naturel | `log(M_E)` | `1.0` |
| `log($n, $base)` | Logarithme base N | `log(100, 10)` | `2.0` |
| `sin($rad)` | Sinus | `sin(M_PI / 2)` | `1.0` |
| `cos($rad)` | Cosinus | `cos(0)` | `1.0` |

---

## Fonctions de tableaux

| Fonction | Description | Exemple | Résultat |
|----------|-------------|---------|----------|
| `count($arr)` | Nombre d'éléments | `count([1,2,3])` | `3` |
| `array_push($arr, $v)` | Ajouter à la fin | `array_push($a, 4)` | Modifie `$a` |
| `array_pop($arr)` | Retirer le dernier | `array_pop([1,2,3])` | `3` |
| `array_unshift($arr, $v)` | Ajouter au début | `array_unshift($a, 0)` | Modifie `$a` |
| `array_shift($arr)` | Retirer le premier | `array_shift([1,2,3])` | `1` |
| `array_merge($a, $b)` | Fusionner | `array_merge([1,2],[3,4])` | `[1,2,3,4]` |
| `array_slice($arr, $d, $l)` | Extraire sous-tableau | `array_slice([1,2,3,4], 1, 2)` | `[2,3]` |
| `array_splice($arr, $d, $l, $rep)` | Modifier + retirer | — | Modifie `$arr` |
| `array_unique($arr)` | Supprimer doublons | `array_unique([1,1,2,3])` | `[1,2,3]` |
| `array_flip($arr)` | Inverser clés/valeurs | `array_flip(['a'=>1])` | `[1=>'a']` |
| `array_reverse($arr)` | Inverser l'ordre | `array_reverse([1,2,3])` | `[3,2,1]` |
| `array_keys($arr)` | Retourner les clés | `array_keys(['a'=>1,'b'=>2])` | `['a','b']` |
| `array_values($arr)` | Retourner les valeurs | `array_values(['a'=>1,'b'=>2])` | `[1,2]` |
| `sort($arr)` | Tri croissant | `sort([3,1,2])` | Modifie → `[1,2,3]` |
| `rsort($arr)` | Tri décroissant | `rsort([3,1,2])` | Modifie → `[3,2,1]` |
| `asort($arr)` | Tri valeur, garde clés | — | Modifie `$arr` |
| `ksort($arr)` | Tri par clés | — | Modifie `$arr` |
| `usort($arr, $fn)` | Tri personnalisé | `usort($a, fn($a,$b) => $a-$b)` | Modifie `$arr` |
| `array_map($fn, $arr)` | Transformer éléments | `array_map(fn($n)=>$n*2, [1,2,3])` | `[2,4,6]` |
| `array_filter($arr, $fn)` | Filtrer éléments | `array_filter([1,2,3], fn($n)=>$n>1)` | `[2,3]` |
| `array_reduce($arr, $fn, $init)` | Réduire à une valeur | `array_reduce([1,2,3], fn($c,$i)=>$c+$i, 0)` | `6` |
| `array_sum($arr)` | Somme | `array_sum([1,2,3])` | `6` |
| `array_product($arr)` | Produit | `array_product([1,2,3,4])` | `24` |
| `array_search($val, $arr)` | Chercher une valeur | `array_search(2, [1,2,3])` | `1` |
| `in_array($val, $arr, $strict)` | Existe dans tableau ? | `in_array(2, [1,2,3], true)` | `true` |
| `array_key_exists($cle, $arr)` | Clé existe ? | `array_key_exists('a', ['a'=>1])` | `true` |
| `array_column($arr, $col)` | Extraire une colonne | `array_column($users, 'nom')` | `['Alice','Bob']` |
| `array_combine($k, $v)` | Créer depuis clés+valeurs | `array_combine(['a','b'],[1,2])` | `['a'=>1,'b'=>2]` |
| `array_chunk($arr, $n)` | Diviser en groupes | `array_chunk([1,2,3,4], 2)` | `[[1,2],[3,4]]` |
| `array_fill($start, $n, $val)` | Remplir | `array_fill(0, 3, 'x')` | `['x','x','x']` |
| `compact('a','b')` | Créer tableau depuis vars | — | `['a'=>$a,'b'=>$b]` |
| `extract($arr)` | Créer vars depuis tableau | `extract(['a'=>1])` | `$a = 1` |

---

## Fonctions de date et heure

| Code `date()` | Description | Exemple |
|---------------|-------------|---------|
| `Y` | Année sur 4 chiffres | `2025` |
| `y` | Année sur 2 chiffres | `25` |
| `m` | Mois (01-12) | `01` |
| `n` | Mois sans zéro (1-12) | `1` |
| `M` | Mois abrégé | `Jan` |
| `F` | Mois complet | `January` |
| `d` | Jour (01-31) | `05` |
| `j` | Jour sans zéro (1-31) | `5` |
| `D` | Jour abrégé | `Mon` |
| `l` | Jour complet | `Monday` |
| `N` | Numéro du jour 1=Lun,7=Dim | `1` |
| `w` | Numéro du jour 0=Dim,6=Sam | `0` |
| `H` | Heure 24h (00-23) | `14` |
| `G` | Heure 24h sans zéro | `14` |
| `h` | Heure 12h (01-12) | `02` |
| `i` | Minutes (00-59) | `30` |
| `s` | Secondes (00-59) | `45` |
| `A` | AM ou PM | `PM` |
| `a` | am ou pm | `pm` |
| `U` | Timestamp Unix | `1705320000` |
| `t` | Nombre de jours dans le mois | `31` |
| `L` | Année bissextile ? (1 ou 0) | `0` |
| `W` | Numéro de semaine ISO | `03` |
| `z` | Jour de l'année (0-365) | `14` |

---

## Fonctions de fichiers

| Fonction | Description |
|----------|-------------|
| `file_get_contents($p)` | Lire tout le fichier |
| `file_put_contents($p, $d)` | Écrire (crée/écrase) |
| `file_put_contents($p, $d, FILE_APPEND)` | Ajouter au fichier |
| `file($p, FILE_IGNORE_NEW_LINES)` | Lire en tableau de lignes |
| `fopen($p, $mode)` | Ouvrir un fichier |
| `fclose($handle)` | Fermer un fichier |
| `fgets($handle)` | Lire une ligne |
| `fread($handle, $len)` | Lire N octets |
| `fwrite($handle, $data)` | Écrire |
| `feof($handle)` | Fin de fichier atteinte ? |
| `file_exists($p)` | Le fichier/dossier existe ? |
| `is_file($p)` | C'est un fichier ? |
| `is_dir($p)` | C'est un dossier ? |
| `is_readable($p)` | Lisible ? |
| `is_writable($p)` | Modifiable ? |
| `filesize($p)` | Taille en octets |
| `filemtime($p)` | Date de modification (timestamp) |
| `pathinfo($p, $option)` | Informations sur le chemin |
| `basename($p)` | Nom du fichier |
| `dirname($p)` | Répertoire parent |
| `realpath($p)` | Chemin absolu réel |
| `copy($src, $dest)` | Copier |
| `rename($old, $new)` | Renommer/déplacer |
| `unlink($p)` | Supprimer un fichier |
| `mkdir($p, $mode, $rec)` | Créer un dossier |
| `rmdir($p)` | Supprimer un dossier vide |
| `glob($pattern)` | Lister fichiers correspondants |
| `scandir($dir)` | Lister le contenu d'un dossier |

---

## Fonctions de sécurité

| Fonction | Description | Utilisation |
|----------|-------------|-------------|
| `password_hash($mdp, $algo)` | Hacher un mot de passe | `password_hash($mdp, PASSWORD_BCRYPT)` |
| `password_verify($mdp, $hash)` | Vérifier un mot de passe | `password_verify($saisie, $hashBDD)` |
| `password_needs_rehash($hash, $algo)` | Le hash doit être mis à jour ? | Lors de chaque connexion |
| `htmlspecialchars($s, $flags, $enc)` | Échapper pour HTML | `htmlspecialchars($val, ENT_QUOTES, 'UTF-8')` |
| `htmlentities($s)` | Convertir toutes entités HTML | Moins recommandé qu'htmlspecialchars |
| `strip_tags($s)` | Supprimer balises HTML | — |
| `filter_var($val, $filter)` | Valider/nettoyer | `filter_var($email, FILTER_VALIDATE_EMAIL)` |
| `filter_input($type, $name, $filter)` | Valider depuis superglobale | `filter_input(INPUT_POST, 'email', ...)` |
| `random_bytes($n)` | Octets aléatoires cryptographiques | `bin2hex(random_bytes(32))` = token |
| `random_int($min, $max)` | Entier aléatoire cryptographique | Meilleur que rand() |
| `hash_equals($a, $b)` | Comparaison sécurisée (timing) | Pour tokens CSRF |
| `hash($algo, $data)` | Hachage général | `hash('sha256', $data)` |

---

*🔗 Documentation complète : [php.net/manual/fr/](https://www.php.net/manual/fr/)*
