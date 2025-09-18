<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === 'John' && $password === 'Rambo') {
        echo "C’est pas ma guerre";
    } else {
        echo "Votre pire cauchemar";
    }
}
?>

<form method="POST" action="">
    <input type="text" name="username" placeholder="Nom d'utilisateur">
    <input type="password" name="password" placeholder="Mot de passe">
    <input type="submit" value="Se connecter">
</form>