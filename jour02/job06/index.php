<?php
// Définir les dimensions du rectangle
$largeur = 20;
$hauteur = 10;

// Boucle pour chaque ligne (hauteur)
for ($i = 0; $i < $hauteur; $i++) {
    // Boucle pour chaque colonne (largeur)
    for ($j = 0; $j < $largeur; $j++) {
        echo "*"; // caractère du rectangle
    }
    echo "<br />"; // retour à la ligne après chaque ligne
}
