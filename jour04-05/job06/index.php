<?php
if (isset($_POST['nombre'])) {
    $nombre = $_POST['nombre'];

    if (is_numeric($nombre)) {
        if ($nombre % 2 == 0) {
            echo "Nombre pair";
        } else {
            echo "Nombre impair";
        }
    } else {
        echo "Veuillez entrer un nombre valide.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Job 06</h1>
    <form method="POST" action="" data-job="job06">
        <input type="text" name="nombre" placeholder="Entrez un nombre">
        <input type="submit" value="Vérifier">
    </form>
</body>

</html>