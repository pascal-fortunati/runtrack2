<?php
// Configuration de la base de données
$host = "localhost";
$dbname = "jour09";
$username = "root";
$password = "";

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des données
    $etudiants = $pdo->query("SELECT * FROM etudiants")->fetchAll(PDO::FETCH_ASSOC);
    $etages = $pdo->query("SELECT * FROM etage")->fetchAll(PDO::FETCH_ASSOC);
    $salles = $pdo->query("SELECT * FROM salles")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Fonction pour afficher un tableau HTML avec Bootstrap
function afficherTableau($donnees, $titre)
{
    if (empty($donnees)) {
        echo "<h2 class='mt-5'>$titre</h2><p>Aucune donnée trouvée.</p>";
        return;
    }

    echo "<h2 class='mt-5'>$titre</h2>";
    echo "<div class='table-responsive'>";
    echo "<table class='table table-bordered table-striped table-hover'>";
    // En-têtes
    echo "<thead class='table-dark'><tr>";
    foreach (array_keys($donnees[0]) as $colonne) {
        echo "<th>" . htmlspecialchars($colonne) . "</th>";
    }
    echo "</tr></thead>";
    // Lignes
    echo "<tbody>";
    foreach ($donnees as $ligne) {
        echo "<tr>";
        foreach ($ligne as $valeur) {
            echo "<td>" . htmlspecialchars($valeur) . "</td>";
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
    <title>Base jour09</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container my-5">
        <h1 class="text-center mb-4">Données jour09</h1>

        <?php
        afficherTableau($etudiants, "Étudiants");
        afficherTableau($etages, "Étages");
        afficherTableau($salles, "Salles");
        ?>
    </div>

    <!-- Bootstrap JS (optionnel pour composants dynamiques) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>