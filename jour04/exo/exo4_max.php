<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['a'])) {
        $a = (float)$_POST['a'];
    } else {
        $a = 0;
    }

    if (isset($_POST['b'])) {
        $b = (float)$_POST['b'];
    } else {
        $b = 0;
    }

    if ($a > $b) {
        $max = $a;
    } else {
        $max = $b;
    }

    echo "Le plus grand est $max";
} else {
?>
    <form method="post">
        <label>Nombre 1 : <input type="number" name="a"></label><br>
        <label>Nombre 2 : <input type="number" name="b"></label><br>
        <button type="submit">Comparer</button>
    </form>
<?php } ?>