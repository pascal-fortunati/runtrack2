<?php
require_once 'Manager.php';
$manager = new Manager(__DIR__);

$job = $_GET['job'] ?? null;
if (!$job) {
    echo "<p style='color:red;'>Aucun job spécifié.</p>";
    exit;
}

echo $manager->loadJob($job);
