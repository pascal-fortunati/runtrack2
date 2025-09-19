<?php
require_once 'Manager.php';
$manager = new Manager(__DIR__);
$jobs = $manager->listJobs();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste des Jobs</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 0;
        }

        header {
            background: linear-gradient(90deg, #4a90e2, #007BFF);
            color: white;
            padding: 30px 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            margin: 0;
            font-size: 2rem;
            letter-spacing: 1px;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 30px 20px;
            gap: 20px;
        }

        .job-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
            width: 160px;
            height: 110px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: transform 0.25s, box-shadow 0.25s, color 0.25s;
        }

        .job-card:hover {
            transform: translateY(-8px) scale(1.05);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
            color: #007BFF;
        }

        #job-content {
            max-width: 850px;
            margin: 20px auto;
            padding: 25px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            display: none;
            position: relative;
            transition: all 0.3s ease;
        }

        #job-content h2 {
            margin-top: 0;
            font-size: 1.5rem;
            color: #007BFF;
        }

        #close-job {
            position: absolute;
            top: 15px;
            right: 20px;
            background: #f44336;
            color: white;
            border: none;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1rem;
            line-height: 1;
            text-align: center;
        }

        #close-job:hover {
            background: #d32f2f;
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
                <div class="job-card" data-job="<?php echo htmlspecialchars($job); ?>">
                    <?php echo ucfirst($job); ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center; width:100%;">Aucun job trouvé.</p>
        <?php endif; ?>
    </div>

    <div id="job-content">
        <button id="close-job">&times;</button>
        <div id="job-inner"></div>
    </div>

    <script>
        const jobCards = document.querySelectorAll('.job-card');
        const jobContent = document.getElementById('job-content');
        const jobInner = document.getElementById('job-inner');
        const closeBtn = document.getElementById('close-job');

        // Charger le job au clic
        jobCards.forEach(card => {
            card.addEventListener('click', () => {
                const jobName = card.getAttribute('data-job');

                fetch(`load.php?job=${encodeURIComponent(jobName)}`)
                    .then(resp => resp.text())
                    .then(html => {
                        jobInner.innerHTML = html;
                        jobContent.style.display = 'block';
                        jobContent.scrollIntoView({
                            behavior: 'smooth'
                        });
                        bindForms();
                    })
                    .catch(err => {
                        jobInner.innerHTML = '<p style="color:red;">Erreur lors du chargement du job.</p>';
                        jobContent.style.display = 'block';
                    });
            });
        });

        // rebinder les formulaires
        function bindForms() {
            jobInner.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', e => {
                    e.preventDefault();

                    const jobName = form.dataset.job; // Récupérer le nom du job depuis l'attribut data-job
                    const formData = new FormData(form);

                    fetch(`load.php?job=${encodeURIComponent(jobName)}`, {
                            method: form.method || 'POST',
                            body: formData
                        })
                        .then(resp => resp.text())
                        .then(html => {
                            jobInner.innerHTML = html;
                            bindForms(); // rebinder les nouveaux formulaires
                        })
                        .catch(err => {
                            jobInner.innerHTML = '<p style="color:red;">Erreur lors de l\'envoi du formulaire.</p>';
                            console.error(err);
                        });
                });
            });
        }

        closeBtn.addEventListener('click', () => {
            jobContent.style.display = 'none';
            jobInner.innerHTML = '';
        });
    </script>
</body>

</html>