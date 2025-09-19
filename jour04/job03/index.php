<?php
// Vérification et comptage des arguments POST
if (isset($_POST['prenom']) || isset($_POST['nom'])) {
    echo "Le nombre d’arguments POST envoyés est : " . count($_POST);
} else {
    echo "Aucun argument POST n’a été envoyé.";
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
    <form method="POST" action="">
        <input type="text" name="prenom" placeholder="Prénom">
        <input type="text" name="nom" placeholder="Nom">
        <input type="submit" value="Envoyer">
    </form>
</body>

</html>