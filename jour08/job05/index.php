<?php
session_start();

// Initialisation de la partie
if (!isset($_SESSION['jeu'])) {
    $_SESSION['jeu'] = [
        'grille' => array_fill(0, 3, array_fill(0, 3, '')),
        'tour' => 'X',
        'gagnant' => null
    ];
}

$jeu = &$_SESSION['jeu'];

function verifGagnant($grille)
{
    $lignes = [];

    // Lignes et colonnes
    for ($i = 0; $i < 3; $i++) {
        $lignes[] = $grille[$i]; // lignes
        $lignes[] = [$grille[0][$i], $grille[1][$i], $grille[2][$i]]; // colonnes
    }
    // Diagonales
    $lignes[] = [$grille[0][0], $grille[1][1], $grille[2][2]];
    $lignes[] = [$grille[0][2], $grille[1][1], $grille[2][0]];

    foreach ($lignes as $ligne) {
        if ($ligne[0] && $ligne[0] === $ligne[1] && $ligne[1] === $ligne[2]) {
            return $ligne[0];
        }
    }
    return null;
}

// Gestion du clic sur une cellule
if (isset($_POST['cell'])) {
    [$x, $y] = explode(',', $_POST['cell']);
    if (!$jeu['grille'][$x][$y] && !$jeu['gagnant']) {
        $jeu['grille'][$x][$y] = $jeu['tour'];
        $jeu['gagnant'] = verifGagnant($jeu['grille']);
        $jeu['tour'] = ($jeu['tour'] === 'X') ? 'O' : 'X';
    }
}

// Message de fin de partie
if (isset($_POST['reset']) || ($jeu['gagnant'] || !in_array('', array_merge(...$jeu['grille'])))) {
    $message = $jeu['gagnant'] ? "{$jeu['gagnant']} a gagné !" : "Match nul !";
    $jeu = [
        'grille' => array_fill(0, 3, array_fill(0, 3, '')),
        'tour' => 'X',
        'gagnant' => null
    ];
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Morpion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
            background: #f4f4f9;
        }

        table {
            margin: auto;
            border-collapse: collapse;
        }

        td {
            width: 80px;
            height: 80px;
            text-align: center;
            font-size: 2em;
            border: 2px solid #333;
        }

        button {
            width: 100%;
            height: 100%;
            font-size: 1.5em;
            border: none;
            cursor: pointer;
            background: #e0e0e0;
            transition: background 0.2s;
        }

        button:hover {
            background: #d0d0d0;
        }

        .message {
            margin: 20px;
            font-size: 1.2em;
            color: #333;
        }

        .reset {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 1em;
            border-radius: 6px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <h1>Jeu du Morpion</h1>

    <?php if (!empty($message)): ?>
        <p class="message"><?= $message ?></p>
    <?php else: ?>
        <p class="message">Tour du joueur : <strong><?= $jeu['tour'] ?></strong></p>
    <?php endif; ?>

    <form method="post">
        <table>
            <?php for ($i = 0; $i < 3; $i++): ?>
                <tr>
                    <?php for ($j = 0; $j < 3; $j++): ?>
                        <td>
                            <?php if ($jeu['grille'][$i][$j]): ?>
                                <?= $jeu['grille'][$i][$j] ?>
                            <?php else: ?>
                                <button type="submit" name="cell" value="<?= "$i,$j" ?>">-</button>
                            <?php endif; ?>
                        </td>
                    <?php endfor; ?>
                </tr>
            <?php endfor; ?>
        </table>
        <button type="submit" name="reset" class="reset">Réinitialiser la partie</button>
    </form>
</body>

</html>