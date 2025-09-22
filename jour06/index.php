<?php
// Gestion du style via cookie
if (isset($_COOKIE['style'])) {
    $style = $_COOKIE['style'];
} else {
    $style = 'style1';
}
// Mise à jour du style si le formulaire est soumis
if (isset($_POST['style'])) {
    $style = $_POST['style'];
    setcookie("style", $style, time() + 3600 * 24 * 30);
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire avec styles</title>
    <link id="theme" rel="stylesheet" href="<?php echo htmlspecialchars($style); ?>.css">
</head>

<body class="<?php echo $style; ?>">
    <form method="post">
        <label for="style">Choisissez votre style :</label>
        <select name="style" id="style" onchange="previewStyle(this.value); this.form.submit();">
            <option value="style1" <?php if ($style == "style1") echo "selected"; ?>>Style 1</option>
            <option value="style2" <?php if ($style == "style2") echo "selected"; ?>>Style 2</option>
            <option value="style3" <?php if ($style == "style3") echo "selected"; ?>>Style 3</option>
        </select>
    </form>

    <!-- Contenu de la page -->
    <h1 class="titre-style1">Tu es bien sur le style 1</h1>
    <h1 class="titre-style2">Tu es bien sur le style 2</h1>
    <h1 class="titre-style3">Tu es bien sur le style 3</h1>

    <script>
        function previewStyle(style) {
            document.getElementById("theme").href = style + ".css";
            document.body.className = style;
        }
    </script>
</body>

</html>