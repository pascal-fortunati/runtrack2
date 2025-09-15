<?php
$estVrai = true;
$nombreEntier = 32;
$chaine = "Bonjour";
$nombreFlottant = 8.1406;

// Tableau
$variables = [
    ["type" => gettype($estVrai), "nom" => "\$estVrai", "valeur" => $estVrai ? "true" : "false"],
    ["type" => gettype($nombreEntier), "nom" => "\$nombreEntier", "valeur" => $nombreEntier],
    ["type" => gettype($chaine), "nom" => "\$chaine", "valeur" => $chaine],
    ["type" => gettype($nombreFlottant), "nom" => "\$nombreFlottant", "valeur" => $nombreFlottant]
];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Tableau des variables</title>
    <style>
        table {
            border-collapse: collapse;
            width: 50%;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #ddd;
        }
    </style>
</head>

<body>
    <h1>Variables en PHP</h1>
    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Nom</th>
                <th>Valeur</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($variables as $var): ?>
                <tr>
                    <td><?= htmlspecialchars($var["type"]) ?></td>
                    <td><?= htmlspecialchars($var["nom"]) ?></td>
                    <td><?= htmlspecialchars($var["valeur"]) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>