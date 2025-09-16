<?php
$hauteur = 5; // Hauteur du triangle

for ($i = 1; $i <= $hauteur; $i++) {
    // Afficher les espaces pour centrer le triangle
    for ($j = 1; $j <= $hauteur - $i; $j++) {
        echo "&nbsp;&nbsp;"; // deux espaces HTML pour mieux centrer
    }
    // Afficher les étoiles
    for ($k = 1; $k <= (2 * $i - 1); $k++) {
        echo "*";
    }
    echo "<br />";
}
