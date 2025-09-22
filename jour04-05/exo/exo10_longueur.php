<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['mot'])) {
        $mot = trim($_POST['mot']);
    } else {
        $mot = '';
    }

    $longueur = 0;
    while (isset($mot[$longueur])) {
        $longueur++;
    }

    echo "$mot contient $longueur lettres.";
} else {
?>
    <form method="post">
        <label>Mot : <input type="text" name="mot"></label>
        <button type="submit">Compter</button>
    </form>
<?php } ?>