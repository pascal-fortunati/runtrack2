<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['conditions'])) {
        echo "✅ Conditions acceptées";
    } else {
        echo "❌ Tu dois accepter.";
    }
} else {
?>
    <form method="post">
        <label>
            <input type="checkbox" name="conditions"> J'accepte les conditions
        </label><br>
        <button type="submit">Valider</button>
    </form>
<?php } ?>