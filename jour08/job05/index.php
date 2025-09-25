<?php
session_start();

// Reset prénoms
if (isset($_POST['change_names'])) {
    $_SESSION['joueurs'] = ['✖️' => null, '⭕' => null];
}

// Initialisation des joueurs
if (!isset($_SESSION['joueurs'])) {
    $_SESSION['joueurs'] = ['✖️' => null, '⭕' => null];
}

// Initialisation du jeu
if (!isset($_SESSION['jeu'])) {
    $_SESSION['jeu'] = [
        'grille' => array_fill(0, 3, array_fill(0, 3, '')),
        'tour' => '✖️',
        'gagnant' => null
    ];
}

$jeu = &$_SESSION['jeu'];
$joueurs = &$_SESSION['joueurs'];

// Vérification du gagnant
function verifGagnant($grille)
{
    $lignes = []; // Lignes et colonnes
    for ($i = 0; $i < 3; $i++) {
        $lignes[] = $grille[$i];
        $lignes[] = [$grille[0][$i], $grille[1][$i], $grille[2][$i]];
    }
    $lignes[] = [$grille[0][0], $grille[1][1], $grille[2][2]];
    $lignes[] = [$grille[0][2], $grille[1][1], $grille[2][0]];

    foreach ($lignes as $ligne) {
        if ($ligne[0] && $ligne[0] === $ligne[1] && $ligne[1] === $ligne[2]) {
            return $ligne[0];
        }
    }
    return null;
}

// Sauvegarde des prénoms
if (isset($_POST['set_names'])) {
    $joueurs['✖️'] = trim($_POST['joueurX']);
    $joueurs['⭕'] = trim($_POST['joueurO']);
}

// Coup joué
if (isset($_POST['cell'])) {
    [$x, $y] = explode(',', $_POST['cell']);
    if (!$jeu['grille'][$x][$y] && !$jeu['gagnant']) {
        $jeu['grille'][$x][$y] = $jeu['tour'];
        $jeu['gagnant'] = verifGagnant($jeu['grille']);
        $jeu['tour'] = ($jeu['tour'] === '✖️') ? '⭕' : '✖️';
    }
}

// Auto-play (si temps écoulé)
if (isset($_POST['auto_play'])) {
    $casesLibres = [];
    foreach ($jeu['grille'] as $i => $ligne) {
        foreach ($ligne as $j => $case) {
            if ($case === '') {
                $casesLibres[] = [$i, $j];
            }
        }
    }
    if ($casesLibres && !$jeu['gagnant']) {
        [$x, $y] = $casesLibres[array_rand($casesLibres)];
        $jeu['grille'][$x][$y] = $jeu['tour'];
        $jeu['gagnant'] = verifGagnant($jeu['grille']);
        $jeu['tour'] = ($jeu['tour'] === '✖️') ? '⭕' : '✖️';
    }
}

// Rejouer une partie
if (isset($_POST['reset'])) {
    $jeu = [
        'grille' => array_fill(0, 3, array_fill(0, 3, '')),
        'tour' => '✖️',
        'gagnant' => null
    ];
    $message = null;
}

// Fin de partie
$message = null;
$isGameOver = false;
if ($jeu['gagnant'] || !in_array('', array_merge(...$jeu['grille']))) {
    $isGameOver = true;
    $nomGagnant = $jeu['gagnant'] ? $joueurs[$jeu['gagnant']] : null;
    $message = $nomGagnant ? "$nomGagnant a gagné !" : "Match nul !";
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
            background: #f4f4f9;
        }

        table {
            margin: auto;
            border-collapse: collapse;
        }

        td {
            width: 100px;
            height: 100px;
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
        }

        button:hover {
            background: #d0d0d0;
        }

        .message {
            margin: 20px;
            font-size: 1.2em;
        }

        .victory-message {
            margin: 20px;
            font-size: 1.5em;
            color: #2e8b57;
            font-weight: bold;
            animation: victoryBlink 1s ease-in-out infinite alternate;
        }

        @keyframes victoryBlink {
            from {
                opacity: 1;
            }

            to {
                opacity: 0.5;
            }
        }

        .reset {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 1em;
            border-radius: 6px;
            cursor: pointer;
        }

        .flash {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #333;
            color: #fff;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 1em;
        }
    </style>
</head>

<body>
    <h1>Jeu du Morpion</h1>

    <?php if (!$joueurs['✖️'] || !$joueurs['⭕']): ?>
        <!-- Formulaire prénoms -->
        <form method="post">
            <p>
                Joueur ✖️ : <input type="text" name="joueurX" required>
                <br><br>
                Joueur ⭕ : <input type="text" name="joueurO" required>
            </p>
            <button type="submit" name="set_names">Commencer la partie</button>
        </form>
    <?php else: ?>
        <?php if (!empty($message)): ?>
            <p class="<?= $jeu['gagnant'] ? 'victory-message' : 'message' ?>"><?= $message ?></p>
            <form method="post">
                <button type="submit" name="reset" class="reset">Rejouer une partie</button>
            </form>
            <form method="post" style="margin-top:10px;">
                <button type="submit" name="change_names">Changer les prénoms</button>
            </form>

            <?php if ($jeu['gagnant']): ?>
                <script>
                    // Effet sonore de victoire
                    let victorySound = new Audio("https://actions.google.com/sounds/v1/alarms/bugle_tune.ogg");
                    victorySound.volume = 0.7;
                    victorySound.play().catch(e => console.log("Erreur audio:", e));
                </script>
            <?php endif; ?>

        <?php else: ?>
            <p class="message">Tour de <strong><?= $joueurs[$jeu['tour']] ?></strong> (<?= $jeu['tour'] ?>)</p>

            <form method="post" id="jeuForm">
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
            </form>

            <!-- Flash message et timer -->
            <div id="flash" class="flash" style="display:none;"></div>

            <form method="post" id="autoPlayForm" style="display:none;">
                <input type="hidden" name="auto_play" value="1">
            </form>

            <form method="post" style="margin-top:10px;">
                <button type="submit" name="change_names">Changer les prénoms</button>
            </form>

            <script>
                let timeLeft = 10;
                let flash = document.getElementById("flash");
                let timer = setInterval(() => {
                    flash.style.display = "block";
                    flash.textContent = "<?= $joueurs[$jeu['tour']] ?> doit jouer ! Temps restant : " + timeLeft + "s";

                    if (timeLeft === 3) {
                        flash.style.background = "red";
                        let beep = new Audio("https://actions.google.com/sounds/v1/cartoon/clang_and_wobble.ogg");
                        beep.play();
                    }

                    timeLeft--;

                    if (timeLeft < 0) {
                        clearInterval(timer);
                        document.getElementById("autoPlayForm").submit();
                    }
                }, 1000);
            </script>
        <?php endif; ?>
    <?php endif; ?>
</body>

</html>