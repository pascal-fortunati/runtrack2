<?php
for ($i = 0; $i <= 100; $i++) {
    // Remplacer 42 par "La Plateforme_"
    if ($i == 42) {
        echo "La Plateforme_<br />";
        continue;
    }

    // Nombres entre 0 et 20 : italique
    if ($i >= 0 && $i <= 20) {
        echo "<i>$i</i><br />";
    }
    // Nombres entre 25 et 50 : souligné
    elseif ($i >= 25 && $i <= 50) {
        echo "<u>$i</u><br />";
    }
    // Tous les autres nombres
    else {
        echo "$i<br />";
    }
}
