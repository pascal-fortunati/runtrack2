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

<form method="POST" action="">
    <input type="text" name="username" placeholder="Nom d'utilisateur">
    <input type="password" name="password" placeholder="Mot de passe">
    <input type="submit" value="Se connecter">
</form>