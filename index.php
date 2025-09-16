<?php
$baseDir = __DIR__; // le dossier courant

// Récupérer tous les dossiers commençant par "job"
$jobs = array_filter(scandir($baseDir), function ($item) use ($baseDir) {
    return is_dir($baseDir . '/' . $item) && preg_match('/^job\d+$/', $item);
});

// Trier les dossiers pour un affichage ordonné
sort($jobs);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste des jobs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        a {
            display: block;
            margin: 5px 0;
            text-decoration: none;
            color: #007BFF;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <h1>Liste des jobs</h1>
    <?php if (!empty($jobs)): ?>
        <?php foreach ($jobs as $job): ?>
            <a href="<?php echo $job; ?>/index.php"><?php echo ucfirst($job); ?></a>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun job trouvé.</p>
    <?php endif; ?>
</body>

</html>