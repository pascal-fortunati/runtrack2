<?php
if (isset($_POST['connexion']) && !empty($_POST['prenom'])) {
    setcookie("prenom", htmlspecialchars($_POST['prenom']), time() + 3600);
    $_COOKIE['prenom'] = htmlspecialchars($_POST['prenom']);
}

if (isset($_POST['deco'])) {
    setcookie("prenom", "", time() - 3600);
    unset($_COOKIE['prenom']);
}
?>
<!DOCTYPE html>
<html>

<body>
    <?php if (isset($_COOKIE['prenom'])): ?>
        <p>Bonjour <?= $_COOKIE['prenom']; ?> !</p>
        <form method="post">
            <button type="submit" name="deco">Déconnexion</button>
        </form>
    <?php else: ?>
        <form method="post">
            <input type="text" name="prenom" placeholder="Votre prénom">
            <button type="submit" name="connexion">Connexion</button>
        </form>
    <?php endif; ?>
</body>

</html>