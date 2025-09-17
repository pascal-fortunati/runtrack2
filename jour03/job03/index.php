<?php
// Déclaration de la chaîne
$str = "I'm sorry Dave I'm afraid I can't do that";

// Définition des voyelles (majuscules et minuscules)
$vowels = "aeiouAEIOU";

// Parcours de la chaîne
for ($i = 0; $i < strlen($str); $i++) {
    if (strpos($vowels, $str[$i]) !== false) {
        echo $str[$i];
    }
}
