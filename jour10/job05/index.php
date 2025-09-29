<?php
// Configuration DB
$host = "localhost";
$dbname = "jour09";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // requête pour le Job 05
    $donnees = $pdo->query("SELECT * FROM etudiants WHERE TIMESTAMPDIFF(YEAR, naissance, CURDATE()) < 18")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

// Fonction tableau Bootstrap
function tableau($donnees, $titre)
{
    if (empty($donnees)) {
        echo "<h2 class='mt-5'>$titre</h2><p>Aucune donnée trouvée.</p>";
        return;
    }

    echo "<h2 class='mt-5'>$titre</h2>";
    echo "<div class='table-responsive'>";
    echo "<table class='table table-bordered table-striped table-hover'>";
    echo "<thead class='table-dark'><tr>";
    foreach (array_keys($donnees[0]) as $col) {
        echo "<th>" . htmlspecialchars($col) . "</th>";
    }
    echo "</tr></thead><tbody>";
    foreach ($donnees as $ligne) {
        echo "<tr>";
        foreach ($ligne as $val) {
            echo "<td>" . htmlspecialchars($val) . "</td>";
        }
        echo "</tr>";
    }
    echo "</tbody></table></div>";
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Job 01</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container my-5">
        <h1 class="text-center mb-4">Job 05</h1>
        <?php tableau($donnees, "Résultat"); ?>
    </div>
</body>

</html>