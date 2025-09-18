<?php
// Déclaration de la chaîne
$str = "Certaines choses changent, et d'autres ne changeront jamais.";

// Trouver la longueur sans strlen()
$len = 0;
while (isset($str[$len])) {
    $len++;
}

// Vérification que la chaîne contient au moins 2 caractères
if ($len > 1) {
    // Stocker le premier caractère
    $firstChar = $str[0];

    // Parcours de 0 à longueur-2
    for ($i = 0; $i < $len - 1; $i++) {
        $str[$i] = $str[$i + 1]; // Décaler chaque caractère
    }

    // Mettre le premier caractère à la fin
    $str[$len - 1] = $firstChar;
}

// Affichage du résultat
echo $str;
