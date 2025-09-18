<?php
// Déclaration de l'encodage UTF-8 pour le navigateur
header('Content-Type: text/html; charset=utf-8');

// Déclaration de la chaîne
$str = "Les choses que l'on possède finissent par nous posséder.";

// Transformer la chaîne en tableau de caractères UTF-8
$chars = preg_split('//u', $str, -1, PREG_SPLIT_NO_EMPTY);

// Trouver le dernier index sans utiliser count()
$lastIndex = 0;
while (isset($chars[$lastIndex])) {
    $lastIndex++;
}
$lastIndex--; // dernier index réel

// Parcours à l'envers et affichage
for ($i = $lastIndex; $i >= 0; $i--) {
    echo $chars[$i];
}
