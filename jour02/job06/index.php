<?php
$hauteur = 5; // Hauteur du triangle

for ($i = 1; $i <= $hauteur; $i++) {
    // Afficher les espaces pour centrer le triangle
    for ($j = 1; $j <= $hauteur - $i; $j++) {
        echo "&nbsp;&nbsp;";
    }

    // Afficher les étoiles pour cette ligne
    for ($k = 1; $k <= $i; $k++) {
        echo "*";
        if ($k < $i) echo " ";
    }

    echo "<br />";
}
