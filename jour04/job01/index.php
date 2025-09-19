<?php
// Vérification et affichage des arguments GET
if (isset($_GET['prenom']) || isset($_GET['nom'])) {
    echo "Le nombre d’arguments GET envoyés est : " . count($_GET);
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
    <form method="GET" action="">
        <input type="text" name="prenom" placeholder="Prénom">
        <input type="text" name="nom" placeholder="Nom">
        <input type="submit" value="Envoyer">
    </form>
</body>

</html>