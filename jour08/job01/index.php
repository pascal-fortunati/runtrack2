<?php
session_start();

if (!isset($_SESSION['nbvisites'])) {
    $_SESSION['nbvisites'] = 1;
}

if (isset($_POST['reset'])) {
    $_SESSION['nbvisites'] = 0;
} else {
    $_SESSION['nbvisites']++;
}
?>
<!DOCTYPE html>
<html>

<body>
    <p>Nombre de visites : <?= $_SESSION['nbvisites']; ?></p>
    <form method="post">
        <button type="submit" name="reset">Reset</button>
    </form>
</body>

</html>