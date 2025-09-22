<?php
if (isset($_POST['prenom']) || isset($_POST['nom'])) {
    echo "<table border='1'>
            <tr>
                <th>Argument</th>
                <th>Valeur</th>
            </tr>";
    foreach ($_POST as $key => $value) {
        echo "<tr>
                <td>" . htmlspecialchars($key) . "</td>
                <td>" . htmlspecialchars($value) . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "Aucun argument GET n’a été envoyé.";
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Job 02</h1>
    <form method="POST" action="" data-job="job02">
        <input type="text" name="prenom" placeholder="Prénom">
        <input type="text" name="nom" placeholder="Nom">
        <input type="submit" value="Envoyer">
    </form>
</body>

</html>