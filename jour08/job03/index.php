<?php
session_start();

if (!isset($_SESSION['prenoms'])) {
    $_SESSION['prenoms'] = [];
}

if (isset($_POST['prenom']) && !empty($_POST['prenom'])) {
    $_SESSION['prenoms'][] = htmlspecialchars($_POST['prenom']);
}

if (isset($_POST['reset'])) {
    $_SESSION['prenoms'] = [];
}
?>
<!DOCTYPE html>
<html>

<body>
    <form method="post">
        <input type="text" name="prenom" placeholder="Entrez un prénom">
        <button type="submit">Ajouter</button>
    </form>
    <form method="post">
        <button type="submit" name="reset">Reset</button>
    </form>
    <h3>Liste des prénoms :</h3>
    <ul>
        <?php foreach ($_SESSION['prenoms'] as $p): ?>
            <li><?= $p; ?></li>
        <?php endforeach; ?>
    </ul>
</body>

</html>