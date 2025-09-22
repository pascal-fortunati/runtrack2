<?php
// Vérification des identifiants
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Vérifie si les champs existent
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Comparaison avec les valeurs attendues
        if ($username === 'John' && $password === 'Rambo') {
            echo "C’est pas ma guerre";
        } else {
            echo "Votre pire cauchemar";
        }
    } else {
        echo "Veuillez remplir tous les champs.";
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
    <h1>Job 05</h1>
    <form method="POST" action="" data-job="job05">
        <input type="text" name="username" placeholder="Nom d'utilisateur">
        <input type="password" name="password" placeholder="Mot de passe">
        <input type="submit" value="Se connecter">
    </form>
</body>

</html>