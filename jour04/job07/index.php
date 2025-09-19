<?php
if (isset($_POST['largeur'], $_POST['hauteur'])) {
    $largeur = (int)$_POST['largeur'];
    $hauteur = (int)$_POST['hauteur'];

    if ($largeur > 1 && $hauteur > 0) {
        $output = '';
        // Calcul du toit
        $toitHauteur = ceil($largeur / 2);
        for ($i = 0; $i < $toitHauteur; $i++) {
            $nbEtoiles = min(1 + 2 * $i, $largeur);
            $nbEspaces = floor(($largeur - $nbEtoiles) / 2);
            $output .= str_repeat(' ', $nbEspaces) . str_repeat('*', $nbEtoiles) . "\n";
        }

        // Corps de la maison
        for ($i = 0; $i < $hauteur; $i++) {
            $output .= '*' . str_repeat(' ', $largeur - 2) . '*' . "\n";
        }

        // Sol de la maison
        $output .= str_repeat('*', $largeur) . "\n";

        // Affichage
        echo "<pre>$output</pre>";
    } else {
        echo "Largeur et hauteur doivent être des nombres positifs (largeur > 1).";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Job 07</h1>
    <form method="POST" action="" data-job="job07">
        <input type="text" name="largeur" placeholder="Largeur">
        <input type="text" name="hauteur" placeholder="Hauteur">
        <input type="submit" value="Dessiner la maison">
    </form>
</body>

</html>