<?php
if (!isset($_COOKIE['nbvisites'])) {
    $nbvisites = 0;
} else {
    $nbvisites = $_COOKIE['nbvisites'];
}

if (isset($_POST['reset'])) {
    $nbvisites = 0;
} else {
    $nbvisites++;
}

setcookie("nbvisites", $nbvisites, time() + 3600); // valable 1h
?>
<!DOCTYPE html>
<html>

<body>
    <p>Nombre de visites (cookie) : <?= $nbvisites; ?></p>
    <form method="post">
        <button type="submit" name="reset">Reset</button>
    </form>
</body>

</html>