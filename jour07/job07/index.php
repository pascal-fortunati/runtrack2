<?php
// Fonction pour séparer une chaîne en mots
function splitMots($str)
{
    $mots = [];
    $mot = "";
    $i = 0;
    while (isset($str[$i])) {
        if ($str[$i] == " ") {
            if ($mot != "") {
                $mots[] = $mot;
                $mot = "";
            }
        } else {
            $mot .= $str[$i];
        }
        $i++;
    }
    if ($mot != "") {
        $mots[] = $mot;
    }
    return $mots;
}

// Fonction gras
function gras($str)
{
    // Récupérer les mots
    $mots = splitMots($str);
    // Parcourir les mots
    $result = "";
    $i = 0;
    while (isset($mots[$i])) {
        $mot = $mots[$i];
        // Vérifier si le premier caractère est une majuscule
        if (isset($mot[0]) && $mot[0] >= 'A' && $mot[0] <= 'Z') {
            // Mettre le mot en gras
            $result .= "<b>" . $mot . "</b>";
        } else {
            // Garder le mot tel quel
            $result .= $mot;
        }
        // Ajouter un espace après chaque mot
        $result .= " ";
        // Incrémenter l'index
        $i++;
    }
    return $result;
}

// Fonction césar
function cesar($str, $decalage = 2)
{
    $result = "";
    $i = 0;
    while (isset($str[$i])) {
        $char = $str[$i];
        // lettres minuscules
        if ($char >= 'a' && $char <= 'z') {
            $pos = ord($char) - ord('a');
            $pos = ($pos + $decalage) % 26;
            $result .= chr($pos + ord('a'));
        }
        // lettres majuscules
        elseif ($char >= 'A' && $char <= 'Z') {
            $pos = ord($char) - ord('A');
            $pos = ($pos + $decalage) % 26;
            $result .= chr($pos + ord('A'));
        } else {
            $result .= $char;
        }
        $i++;
    }
    return $result;
}

// Fonction plateforme
function plateforme($str)
{
    $mots = splitMots($str);
    $result = "";
    $i = 0;
    while (isset($mots[$i])) {
        $mot = $mots[$i];
        $len = 0;
        while (isset($mot[$len])) {
            $len++;
        }
        if ($len >= 2 && $mot[$len - 2] == "m" && $mot[$len - 1] == "e") {
            $result .= $mot . "_";
        } else {
            $result .= $mot;
        }
        $result .= " ";
        $i++;
    }
    return $result;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Jobs PHP</title>
</head>

<body>
    <h2>Job 07 - Formulaire</h2>
    <form method="post">
        <input type="text" name="str" placeholder="Entrez une phrase">
        <select name="fonction">
            <option value="gras">Gras</option>
            <option value="cesar">César</option>
            <option value="plateforme">Plateforme</option>
        </select>
        <button type="submit">Valider</button>
    </form>

    <?php
    if (isset($_POST["str"]) && isset($_POST["fonction"])) {
        $str = $_POST["str"];
        $fonction = $_POST["fonction"];

        if ($fonction == "gras") {
            echo gras($str);
        } elseif ($fonction == "cesar") {
            echo cesar($str);
        } elseif ($fonction == "plateforme") {
            echo plateforme($str);
        }
    }
    ?>
</body>

</html>