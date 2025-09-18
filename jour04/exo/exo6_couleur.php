<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['couleur'])) {
        $couleur = htmlspecialchars($_POST['couleur']);
    } else {
        $couleur = '#000000';
    }

    echo "<p style='color:$couleur'>Ce texte est en couleur choisie.</p><br/>";
} else {
?>
    <form method="post">
        <label>Choisir une couleur : <input type="color" name="couleur"></label>
        <button type="submit">Afficher</button>
    </form>
<?php } ?>