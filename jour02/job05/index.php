<?php
// Fonction pour vérifier si un nombre est premier
function estPremier($n)
{
    if ($n < 2) return false;
    for ($i = 2; $i <= sqrt($n); $i++) {
        if ($n % $i == 0) {
            return false;
        }
    }
    return true;
}

// Boucle pour afficher tous les nombres premiers jusqu'à 1000
for ($i = 2; $i <= 1000; $i++) {
    if (estPremier($i)) {
        echo "$i<br />";
    }
}
