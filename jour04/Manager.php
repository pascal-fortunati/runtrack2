<?php
class Manager
{
    // Répertoire de base contenant les jobs
    private string $baseDir;

    // Initialise le gestionnaire avec le répertoire de base
    public function __construct(string $baseDir)
    {
        $this->baseDir = rtrim($baseDir, DIRECTORY_SEPARATOR);
    }

    // Liste les dossiers de jobs disponibles
    public function listJobs(): array
    {
        $jobs = array_filter(scandir($this->baseDir), function ($item) {
            return is_dir($this->baseDir . DIRECTORY_SEPARATOR . $item)
                && preg_match('/^job\d+$/', $item);
        });
        sort($jobs);
        return $jobs;
    }

    // Retourne le chemin du fichier index.php d'un job donné
    public function getJobFile(string $job): ?string
    {
        if (!preg_match('/^job\d+$/', $job)) return null;
        $path = $this->baseDir . DIRECTORY_SEPARATOR . $job . DIRECTORY_SEPARATOR . 'index.php';
        return file_exists($path) ? $path : null;
    }

    // Charge et retourne le contenu HTML d'un job donné
    public function loadJob(string $job): string
    {
        $file = $this->getJobFile($job);
        if ($file) {
            ob_start();
            include $file;
            return ob_get_clean();
        }
        return "<p style='color:red;'>Le job '$job' est introuvable.</p>";
    }
}
