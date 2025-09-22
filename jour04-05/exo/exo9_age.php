<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['age'])) {
        $age = (int)$_POST['age'];
    } else {
        $age = 0;
    }

    if ($age >= 18) {
        echo "majeur";
    } else {
        echo "mineur";
    }
} else {
?>
    <form method="post">
        <label>Âge : <input type="number" name="age"></label>
        <button type="submit">Vérifier</button>
    </form>
<?php } ?>