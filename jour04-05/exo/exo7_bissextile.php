<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['annee'])) {
        $annee = (int)$_POST['annee'];
    } else {
        $annee = 0;
    }

    if (($annee % 4 == 0 && $annee % 100 != 0) || ($annee % 400 == 0)) {
        echo "$annee est bissextile";
    } else {
        echo "$annee n'est pas bissextile";
    }
} else {
?>
    <form method="post">
        <label>Année : <input type="number" name="annee"></label>
        <button type="submit">Vérifier</button>
    </form>
<?php } ?>