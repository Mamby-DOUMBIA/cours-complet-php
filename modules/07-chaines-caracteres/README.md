# Module 07 — Chaînes de caractères

> **Niveau :** Débutant → Intermédiaire
> **Durée estimée :** 3 heures

---

## 1. Créer et afficher des chaînes

```php
<?php
// Guillemets doubles : variables interprétées
$prenom = "Alice";
echo "Bonjour $prenom !";       // Bonjour Alice !
echo "Bonjour {$prenom} !";     // Bonjour Alice !

// Guillemets simples : variables NON interprétées
echo 'Bonjour $prenom !';       // Bonjour $prenom !

// Heredoc (multiligne avec interpolation)
$texte = <<<EOT
Bonjour $prenom,
Bienvenue dans ce cours PHP !
EOT;

// Nowdoc (multiligne SANS interpolation)
$texte2 = <<<'EOT'
Bonjour $prenom,
(le $prenom n'est pas remplacé)
EOT;
?>
```

---

## 2. Fonctions de chaînes essentielles

```php
<?php
$texte = "  Bonjour le monde PHP !  ";

// Longueur
echo strlen($texte);                         // 26 (octets)
echo mb_strlen($texte);                      // 26 (caractères UTF-8)

// Nettoyage
echo trim($texte);                           // "Bonjour le monde PHP !"
echo ltrim($texte);                          // "Bonjour le monde PHP !  "
echo rtrim($texte);                          // "  Bonjour le monde PHP !"

// Casse
echo strtoupper("bonjour");                  // BONJOUR
echo strtolower("BONJOUR");                  // bonjour
echo ucfirst("bonjour le monde");            // Bonjour le monde
echo ucwords("bonjour le monde");            // Bonjour Le Monde

// Recherche
echo strpos("Bonjour PHP", "PHP");           // 8 (position)
echo strrpos("PHP est PHP", "PHP");          // 8 (dernière occurrence)
echo str_contains("PHP est génial", "génial"); // true (PHP 8+)
echo str_starts_with("Bonjour", "Bon");      // true (PHP 8+)
echo str_ends_with("Bonjour", "jour");       // true (PHP 8+)

// Extraction
echo substr("Bonjour le monde", 0, 7);       // Bonjour
echo substr("Bonjour le monde", -5);         // monde

// Remplacement
echo str_replace("PHP", "Python", "J'aime PHP");  // J'aime Python
echo str_ireplace("php", "PHP", "j'aime php");     // j'aime PHP (insensible casse)

// Division / jonction
$tableau = explode(",", "pomme,banane,cerise");  // ["pomme","banane","cerise"]
$chaine  = implode(" - ", $tableau);              // "pomme - banane - cerise"

// Répétition et rembourrage
echo str_repeat("*", 10);                    // **********
echo str_pad("42", 5, "0", STR_PAD_LEFT);   // 00042

// Formatage
echo sprintf("Prix : %.2f €", 19.9);         // Prix : 19.90 €
echo number_format(1234567.89, 2, ',', ' '); // 1 234 567,89
?>
```

---

## 3. Expressions régulières (Regex)

```php
<?php
// preg_match() — Teste si une regex correspond
$email = "alice@exemple.com";
if (preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
    echo "Email valide !";
}

// preg_replace() — Remplace selon un pattern
$texte = "Téléphone : 06-12-34-56-78";
$propre = preg_replace('/[^0-9]/', '', $texte);  // Garde uniquement les chiffres
echo $propre;  // 0612345678

// preg_split() — Divise selon un pattern
$mots = preg_split('/\s+/', "  Bonjour   le   monde  ");
$mots = array_filter($mots);  // Supprimer les éléments vides
print_r($mots);  // ["Bonjour", "le", "monde"]

// preg_match_all() — Trouve toutes les correspondances
$html  = '<a href="http://php.net">PHP</a> et <a href="http://laravel.com">Laravel</a>';
preg_match_all('/href="([^"]+)"/', $html, $matches);
print_r($matches[1]);  // ["http://php.net", "http://laravel.com"]
?>
```

---

## 4. Sécurité et encodage

```php
<?php
// TOUJOURS protéger les données avant affichage HTML
$commentaire = '<script>alert("xss")</script>';
echo htmlspecialchars($commentaire, ENT_QUOTES, 'UTF-8');
// Affiche : &lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;
// Le navigateur affiche du texte, pas du code !

// Encodage URL
$url = "https://exemple.com/recherche?q=PHP débutant";
echo urlencode("PHP débutant");    // PHP+d%C3%A9butant
echo rawurlencode("PHP débutant"); // PHP%20d%C3%A9butant (RFC 3986)

// Hachage
echo md5("mot_de_passe");    // ❌ Obsolète pour les mots de passe
echo sha1("texte");           // ❌ Obsolète pour les mots de passe
echo hash('sha256', "texte"); // ✅ Meilleur pour hachage général
// Pour les mots de passe : password_hash() (voir Module 14)

// Base64
$encode = base64_encode("Données binaires ou texte");
$decode = base64_decode($encode);
?>
```

---

## 5. Exercices pratiques

### Exercice 1 — Validateur de données
Créez des fonctions pour valider un email, un numéro de téléphone français (0X XX XX XX XX), et un code postal (5 chiffres).

### Exercice 2 — Formateur de texte
Créez une fonction `formaterParagraphe(string $texte): string` qui : supprime les espaces multiples, capitalise la première lettre, ajoute un point final si absent, et tronque à 200 caractères maximum.

---

## 6. Corrigés

### Corrigé Exercice 1

```php
<?php
function validerEmail(string $email): bool {
    return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validerTelephoneFR(string $tel): bool {
    $tel = preg_replace('/[\s\-\.]/', '', $tel);  // Supprimer séparateurs
    return (bool) preg_match('/^0[1-9][0-9]{8}$/', $tel);
}

function validerCodePostal(string $cp): bool {
    return (bool) preg_match('/^[0-9]{5}$/', $cp);
}

// Tests
var_dump(validerEmail("alice@test.com"));      // true
var_dump(validerEmail("alice@@test.com"));     // false
var_dump(validerTelephoneFR("06 12 34 56 78")); // true
var_dump(validerCodePostal("75001"));           // true
var_dump(validerCodePostal("7501"));            // false
?>
```

---

## Résumé du module 07

| Besoin | Fonction |
|--------|---------|
| Longueur | `strlen()` / `mb_strlen()` |
| Majuscule/minuscule | `strtoupper()` / `strtolower()` |
| Chercher | `strpos()`, `str_contains()` |
| Extraire | `substr()` |
| Remplacer | `str_replace()`, `preg_replace()` |
| Diviser | `explode()`, `preg_split()` |
| Joindre | `implode()` |
| Sécuriser | `htmlspecialchars()` |

**➡️ [Module 08 — POO](../08-poo/README.md)**
