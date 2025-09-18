<?php
// Déclaration de la chaîne
$str = "On n’est pas le meilleur quand on le croit mais quand on le sait";

// Initialisation du dictionnaire pour compter voyelles et consonnes
$dic = [
    "voyelles" => 0,
    "consonnes" => 0
];

// Définition des voyelles (majuscules et minuscules)
$vowels = "aeiouyAEIOUY";

// Parcours de la chaîne sans strlen()
for ($i = 0; isset($str[$i]); $i++) {
    $char = $str[$i];
    if (ctype_alpha($char)) { // Vérifie que c'est une lettre
        if (strpos($vowels, $char) !== false) {
            $dic["voyelles"]++;
        } else {
            $dic["consonnes"]++;
        }
    }
}

// Affichage des résultats dans un tableau HTML
echo "<table border='1'>";
echo "<thead><tr><th>Voyelles</th><th>Consonnes</th></tr></thead>";
echo "<tbody>";
echo "<tr><td>{$dic['voyelles']}</td><td>{$dic['consonnes']}</td></tr>";
echo "</tbody>";
echo "</table>";
