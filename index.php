<?php
// Dossier de base
$baseDir = __DIR__;

// Dossiers commençant par "job"
$jobs = array_filter(scandir($baseDir), function ($item) use ($baseDir) {
    return is_dir($baseDir . '/' . $item) && preg_match('/^job\d+$/', $item);
});

// Trier les dossiers par ordre alphabétique
sort($jobs);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste des Jobs</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #007BFF;
            color: white;
            padding: 20px;
            text-align: center;
        }

        h1 {
            margin: 0;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
            gap: 20px;
        }

        .job-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 150px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #333;
            font-weight: bold;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .job-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            color: #007BFF;
        }
    </style>
</head>

<body>
    <header>
        <h1>Liste des Jobs</h1>
    </header>
    <div class="container">
        <?php if (!empty($jobs)): ?>
            <?php foreach ($jobs as $job): ?>
                <a class="job-card" href="<?php echo $job; ?>/index.php">
                    <?php echo ucfirst($job); ?>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center; width:100%;">Aucun job trouvé.</p>
        <?php endif; ?>
    </div>
</body>

</html>