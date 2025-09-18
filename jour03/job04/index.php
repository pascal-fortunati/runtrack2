<?php
// Déclaration de la chaîne
$str = "Dans l'espace, personne ne vous entend crier";

// Compter le nombre de caractères sans strlen()
$length = 0;
for ($i = 0; isset($str[$i]); $i++) {
    $length++;
}

// Affichage du résultat
echo "La chaîne contient $length caractères.";
