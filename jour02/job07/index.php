<?php
$hauteur = 5; // Hauteur du triangle

for ($i = 1; $i <= $hauteur; $i++) {
    // Afficher les espaces pour aligner le triangle à gauche
    for ($j = 1; $j <= $hauteur - $i; $j++) {
        echo "&nbsp;"; // espace HTML
    }
    // Afficher les étoiles
    for ($k = 1; $k <= $i; $k++) {
        echo "* ";
    }
    echo "<br />"; // saut de ligne
}
