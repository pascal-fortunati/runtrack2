<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nombre'])) {
        $nombre = (float)$_POST['nombre'];
    } else {
        $nombre = 0;
    }

    $carre = $nombre * $nombre;
    echo "Le carré de $nombre est $carre";
} else {
?>
    <form method="post">
        <label>Nombre : <input type="number" name="nombre"></label>
        <button type="submit">Calculer</button>
    </form>
<?php } ?>