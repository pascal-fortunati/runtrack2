<?php
// Déclaration de l'encodage UTF-8 pour le navigateur
header('Content-Type: text/html; charset=utf-8');

// Déclaration de la chaîne
$str = "Les choses que l'on possède finissent par nous posséder.";

// Transformer la chaîne en tableau de caractères UTF-8
$chars = preg_split('//u', $str, -1, PREG_SPLIT_NO_EMPTY);

// Parcours à l'envers et affichage
for ($i = count($chars) - 1; $i >= 0; $i--) {
    echo $chars[$i];
}
