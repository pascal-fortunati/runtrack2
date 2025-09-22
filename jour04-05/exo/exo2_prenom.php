<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['prenom'])) {
        $prenom = htmlspecialchars($_POST['prenom']);
    } else {
        $prenom = '';
    }

    echo "Bonjour $prenom";
} else {
?>
    <form method="post">
        <label>Prénom : <input type="text" name="prenom"></label>
        <button type="submit">Envoyer</button>
    </form>
<?php } ?>