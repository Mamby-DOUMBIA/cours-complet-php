<?php
/**
 * Fichier : exemple-01-hello-world.php
 * Module  : 01 - Introduction à PHP
 * Auteur  : Cours PHP Complet
 * 
 * Description :
 * Ce fichier illustre les bases absolues de PHP :
 *   - La balise d'ouverture PHP
 *   - L'instruction echo
 *   - Les commentaires
 *   - Le point-virgule obligatoire
 */

// =============================================================
// EXEMPLE 1 : Le classique "Hello World"
// =============================================================

echo "Bonjour, le monde !";
echo "<br>"; // <br> est du HTML pour "retour à la ligne"

// =============================================================
// EXEMPLE 2 : Afficher plusieurs informations
// =============================================================

// Variables simples (on les verra en détail dans le Module 03)
$prenom    = "Alice";
$age       = 25;
$ville     = "Paris";

// Affichage avec echo
echo "Prénom : " . $prenom . "<br>";
echo "Âge    : " . $age . " ans<br>";
echo "Ville  : " . $ville . "<br>";

// =============================================================
// EXEMPLE 3 : Utiliser la date du jour
// =============================================================

// La fonction date() retourne la date/heure actuelle selon un format
// "d" = jour, "m" = mois, "Y" = année à 4 chiffres
// "H" = heures (24h), "i" = minutes, "s" = secondes
$date_du_jour    = date("d/m/Y");
$heure_actuelle  = date("H:i:s");

echo "<br>--- Informations temporelles ---<br>";
echo "Date    : " . $date_du_jour . "<br>";
echo "Heure   : " . $heure_actuelle . "<br>";

// =============================================================
// EXEMPLE 4 : Mélanger PHP et HTML
// =============================================================
?>

<!-- À partir d'ici, c'est du HTML pur -->
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Exemple PHP de base</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .boite { background: #f0f8ff; border: 1px solid #4CAF50; padding: 15px; border-radius: 5px; }
    </style>
</head>
<body>

    <h2>Ma première page PHP dynamique</h2>

    <div class="boite">
        <p><strong>Prénom :</strong> <?php echo $prenom; ?></p>
        <p><strong>Âge    :</strong> <?php echo $age; ?> ans</p>
        <p><strong>Ville  :</strong> <?php echo $ville; ?></p>
        <p><strong>Date   :</strong> <?php echo $date_du_jour; ?></p>
    </div>

    <p>
        <!-- PHP peut aussi être utilisé dans les attributs HTML -->
        <em>Page générée le <?php echo date("d/m/Y à H:i"); ?></em>
    </p>

</body>
</html>
