<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'])) {
        $email = strtolower(trim($_POST['email']));
    } else {
        $email = '';
    }

    echo "Email en minuscules : $email";
} else {
?>
    <form method="post">
        <label>Email : <input type="email" name="email"></label>
        <button type="submit">Envoyer</button>
    </form>
<?php } ?>