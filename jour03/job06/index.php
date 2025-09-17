<?php
// Déclaration de la chaîne
$str = "Les choses que l'on possède finissent par nous posséder.";

// Parcours de la chaîne à l'envers et affichage
for ($i = strlen($str) - 1; $i >= 0; $i--) {
    echo $str[$i];
}
