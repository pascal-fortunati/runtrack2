<?php
session_start();

/**
 * Classe pour gérer le jeu de morpion
 */
class JeuMorpion
{
    private $cle_session = 'morpion_moderne';

    public function __construct()
    {
        // Initialise une nouvelle partie si aucune session existe
        if (!isset($_SESSION[$this->cle_session])) {
            $this->reinitialiserPartie();
        }
    }

    /**
     * Récupère l'état actuel du jeu
     */
    public function obtenirJeu()
    {
        return $_SESSION[$this->cle_session];
    }

    /**
     * Remet à zéro la partie en cours
     */
    public function reinitialiserPartie()
    {
        $_SESSION[$this->cle_session] = [
            'grille' => array_fill(0, 3, array_fill(0, 3, '')),
            'joueur_actuel' => 'X',
            'gagnant' => null,
            'partie_terminee' => false,
            'ligne_gagnante' => null,
            'scores' => $_SESSION[$this->cle_session]['scores'] ?? ['X' => 0, 'O' => 0, 'nuls' => 0]
        ];
    }

    /**
     * Joue un coup sur la grille
     */
    public function jouerCoup($ligne, $colonne)
    {
        $jeu = &$_SESSION[$this->cle_session];

        // Vérifier si le coup est valide
        if ($jeu['partie_terminee'] || !empty($jeu['grille'][$ligne][$colonne])) {
            return false;
        }

        // Placer le symbole du joueur actuel
        $jeu['grille'][$ligne][$colonne] = $jeu['joueur_actuel'];

        // Vérifier l'état du jeu après le coup
        $resultat = $this->verifierEtatJeu();

        if ($resultat['gagnant']) {
            // Il y a un gagnant
            $jeu['gagnant'] = $resultat['gagnant'];
            $jeu['ligne_gagnante'] = $resultat['ligne_gagnante'];
            $jeu['partie_terminee'] = true;
            $jeu['scores'][$resultat['gagnant']]++;
        } elseif ($resultat['est_nul']) {
            // Match nul
            $jeu['partie_terminee'] = true;
            $jeu['scores']['nuls']++;
        } else {
            // Changer de joueur
            $jeu['joueur_actuel'] = $jeu['joueur_actuel'] === 'X' ? 'O' : 'X';
        }

        return true;
    }

    /**
     * Vérifie si la partie est terminée et qui a gagné
     */
    private function verifierEtatJeu()
    {
        $jeu = $_SESSION[$this->cle_session];
        $grille = $jeu['grille'];

        // Toutes les lignes possibles pour gagner au morpion
        $lignes_possibles = [
            // Lignes horizontales
            [[0, 0], [0, 1], [0, 2]],
            [[1, 0], [1, 1], [1, 2]],
            [[2, 0], [2, 1], [2, 2]],
            // Lignes verticales
            [[0, 0], [1, 0], [2, 0]],
            [[0, 1], [1, 1], [2, 1]],
            [[0, 2], [1, 2], [2, 2]],
            // Diagonales
            [[0, 0], [1, 1], [2, 2]],
            [[0, 2], [1, 1], [2, 0]]
        ];

        // Vérifier chaque ligne possible
        foreach ($lignes_possibles as $ligne) {
            $valeurs = array_map(function ($position) use ($grille) {
                return $grille[$position[0]][$position[1]];
            }, $ligne);

            // Si les trois cases ont le même symbole et ne sont pas vides
            if ($valeurs[0] && $valeurs[0] === $valeurs[1] && $valeurs[1] === $valeurs[2]) {
                return [
                    'gagnant' => $valeurs[0],
                    'ligne_gagnante' => $ligne,
                    'est_nul' => false
                ];
            }
        }

        // Vérifier si la grille est pleine (match nul)
        $grille_pleine = true;
        foreach ($grille as $ligne_grille) {
            foreach ($ligne_grille as $case) {
                if (empty($case)) {
                    $grille_pleine = false;
                    break 2;
                }
            }
        }

        return [
            'gagnant' => null,
            'ligne_gagnante' => null,
            'est_nul' => $grille_pleine
        ];
    }

    /**
     * Vérifie si une case fait partie de la ligne gagnante
     */
    public function estCaseGagnante($ligne, $colonne)
    {
        $jeu = $_SESSION[$this->cle_session];
        if (!$jeu['ligne_gagnante']) return false;

        foreach ($jeu['ligne_gagnante'] as $position) {
            if ($position[0] === $ligne && $position[1] === $colonne) {
                return true;
            }
        }
        return false;
    }
}

// Initialisation du jeu de morpion
$morpion = new JeuMorpion();

// Traitement des actions du joueur
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'jouer':
                // Jouer un coup
                if (isset($_POST['ligne']) && isset($_POST['colonne'])) {
                    $morpion->jouerCoup((int)$_POST['ligne'], (int)$_POST['colonne']);
                }
                break;
            case 'reinitialiser':
                // Recommencer une nouvelle partie
                $morpion->reinitialiserPartie();
                break;
        }
    }

    // Redirection pour éviter le double envoi
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Récupérer l'état actuel du jeu
$jeu = $morpion->obtenirJeu();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Morpion</title>
    <style>
        /* Reset CSS complet */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Style principal avec thème hackeur */
        body {
            font-family: 'Courier New', 'Monaco', monospace;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #0d1421 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #00ff41;
            overflow-x: hidden;
        }

        /* Effet de fond matrice */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(0, 255, 65, 0.05) 1px, transparent 1px),
                radial-gradient(circle at 80% 20%, rgba(0, 255, 65, 0.05) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: -1;
            animation: matrice 20s linear infinite;
        }

        @keyframes matrice {
            0% {
                transform: translateY(0);
            }

            100% {
                transform: translateY(-50px);
            }
        }

        /* Conteneur principal du jeu */
        .conteneur-jeu {
            background: rgba(0, 0, 0, 0.9);
            border: 2px solid #00ff41;
            border-radius: 10px;
            padding: 2rem;
            box-shadow:
                0 0 20px rgba(0, 255, 65, 0.3),
                inset 0 0 20px rgba(0, 255, 65, 0.1);
            max-width: 500px;
            width: 90%;
            position: relative;
        }

        /* Effet de scan ligne */
        .conteneur-jeu::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, #00ff41, transparent);
            animation: scan 2s linear infinite;
        }

        @keyframes scan {
            0% {
                top: 0;
                opacity: 1;
            }

            100% {
                top: 100%;
                opacity: 0;
            }
        }

        /* Titre principal */
        h1 {
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 2.5rem;
            font-weight: bold;
            text-shadow: 0 0 10px #00ff41;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        /* Informations de la partie */
        .informations-partie {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1rem;
            background: rgba(0, 255, 65, 0.1);
            border: 1px solid #00ff41;
            border-radius: 5px;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .joueur-actuel {
            font-size: 1.2rem;
            font-weight: bold;
            text-transform: uppercase;
        }

        .symbole-joueur {
            display: inline-block;
            width: 30px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            border: 1px solid #00ff41;
            margin-left: 0.5rem;
            font-weight: bold;
            background: rgba(0, 255, 65, 0.2);
        }

        /* Tableau des scores */
        .scores {
            display: flex;
            gap: 1rem;
            font-size: 0.9rem;
        }

        .element-score {
            text-align: center;
            border: 1px solid #00ff41;
            padding: 0.5rem;
            background: rgba(0, 255, 65, 0.05);
        }

        .valeur-score {
            display: block;
            font-size: 1.5rem;
            font-weight: bold;
            color: #00ff41;
            text-shadow: 0 0 5px #00ff41;
        }

        /* Grille de jeu */
        .grille-morpion {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 5px;
            margin: 2rem 0;
            padding: 15px;
            background: rgba(0, 0, 0, 0.5);
            border: 2px solid #00ff41;
            border-radius: 5px;
        }

        /* Cases de la grille */
        .case {
            aspect-ratio: 1;
            background: rgba(0, 0, 0, 0.8);
            border: 1px solid #00ff41;
            font-size: 3rem;
            font-weight: bold;
            color: #00ff41;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            font-family: 'Courier New', monospace;
        }

        .case:hover:not(:disabled) {
            background: rgba(0, 255, 65, 0.1);
            box-shadow: 0 0 15px rgba(0, 255, 65, 0.5);
            text-shadow: 0 0 10px #00ff41;
        }

        .case:disabled {
            cursor: not-allowed;
        }

        /* Cases gagnantes avec effet spécial */
        .case.gagnante {
            background: rgba(0, 255, 65, 0.3);
            animation: pulsation-hackeur 1s infinite;
            box-shadow: 0 0 20px #00ff41;
        }

        /* Couleurs différentes pour X et O */
        .case.x {
            color: #ff0040;
            text-shadow: 0 0 10px #ff0040;
        }

        .case.o {
            color: #0080ff;
            text-shadow: 0 0 10px #0080ff;
        }

        @keyframes pulsation-hackeur {

            0%,
            100% {
                box-shadow: 0 0 20px #00ff41;
                border-color: #00ff41;
            }

            50% {
                box-shadow: 0 0 30px #00ff41, 0 0 40px #00ff41;
                border-color: #40ff80;
            }
        }

        @keyframes apparition {
            from {
                opacity: 0;
                transform: scale(0.3) rotate(180deg);
            }

            to {
                opacity: 1;
                transform: scale(1) rotate(0deg);
            }
        }

        .case:not(:empty) {
            animation: apparition 0.3s ease-out;
        }

        /* Statut de la partie */
        .statut-partie {
            text-align: center;
            margin: 1.5rem 0;
            font-size: 1.3rem;
            font-weight: bold;
            min-height: 2rem;
            text-transform: uppercase;
        }

        .message-gagnant {
            color: #00ff41;
            text-shadow: 0 0 15px #00ff41;
            animation: clignotement-hackeur 1s infinite;
        }

        .message-nul {
            color: #ffff00;
            text-shadow: 0 0 10px #ffff00;
        }

        @keyframes clignotement-hackeur {

            0%,
            50% {
                opacity: 1;
            }

            51%,
            100% {
                opacity: 0.7;
            }
        }

        /* Contrôles de la partie */
        .controles {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 1.5rem;
        }

        .bouton {
            padding: 12px 24px;
            border: 2px solid #00ff41;
            background: rgba(0, 0, 0, 0.8);
            color: #00ff41;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-family: 'Courier New', monospace;
        }

        .bouton-reinitialiser {
            border-color: #ff0040;
            color: #ff0040;
        }

        .bouton:hover {
            background: rgba(0, 255, 65, 0.1);
            box-shadow: 0 0 15px rgba(0, 255, 65, 0.3);
            text-shadow: 0 0 8px currentColor;
        }

        .bouton-reinitialiser:hover {
            background: rgba(255, 0, 64, 0.1);
            box-shadow: 0 0 15px rgba(255, 0, 64, 0.3);
        }

        .bouton:active {
            transform: scale(0.95);
        }

        /* Responsive pour mobile */
        @media (max-width: 480px) {
            .conteneur-jeu {
                padding: 1rem;
            }

            h1 {
                font-size: 2rem;
            }

            .case {
                font-size: 2rem;
            }

            .informations-partie {
                flex-direction: column;
                text-align: center;
            }
        }

        /* Effet de terminal */
        .conteneur-jeu {
            font-family: 'Courier New', monospace;
        }
    </style>
</head>

<body>
    <div class="conteneur-jeu">
        <h1>MORPION</h1>

        <div class="informations-partie">
            <?php if (!$jeu['partie_terminee']): ?>
                <div class="joueur-actuel">
                    JOUEUR ACTUEL :
                    <span class="symbole-joueur <?= strtolower($jeu['joueur_actuel']) ?>">
                        <?= $jeu['joueur_actuel'] ?>
                    </span>
                </div>
            <?php endif; ?>

            <div class="scores">
                <div class="element-score">
                    <span class="valeur-score"><?= $jeu['scores']['X'] ?></span>
                    <div>VICTOIRES X</div>
                </div>
                <div class="element-score">
                    <span class="valeur-score"><?= $jeu['scores']['nuls'] ?></span>
                    <div>MATCHS NULS</div>
                </div>
                <div class="element-score">
                    <span class="valeur-score"><?= $jeu['scores']['O'] ?></span>
                    <div>VICTOIRES O</div>
                </div>
            </div>
        </div>

        <div class="statut-partie">
            <?php if ($jeu['gagnant']): ?>
                <div class="message-gagnant">
                    >>> JOUEUR <?= $jeu['gagnant'] ?> A GAGNÉ ! <<<
                        </div>
                    <?php elseif ($jeu['partie_terminee']): ?>
                        <div class="message-nul">
                            === ÉGALITÉ DÉTECTÉE ===
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Formulaire pour jouer -->
                <form method="post" class="formulaire-jeu">
                    <div class="grille-morpion">
                        <?php for ($ligne = 0; $ligne < 3; $ligne++): ?>
                            <?php for ($colonne = 0; $colonne < 3; $colonne++): ?>
                                <button
                                    type="submit"
                                    name="action"
                                    value="jouer"
                                    class="case <?= $jeu['grille'][$ligne][$colonne] ? strtolower($jeu['grille'][$ligne][$colonne]) : '' ?> <?= $morpion->estCaseGagnante($ligne, $colonne) ? 'gagnante' : '' ?>"
                                    <?= $jeu['grille'][$ligne][$colonne] || $jeu['partie_terminee'] ? 'disabled' : '' ?>
                                    onclick="this.form.ligne.value=<?= $ligne ?>; this.form.colonne.value=<?= $colonne ?>;">
                                    <?= $jeu['grille'][$ligne][$colonne] ?: '' ?>
                                </button>
                            <?php endfor; ?>
                        <?php endfor; ?>
                    </div>

                    <!-- Champs cachés pour transmettre les coordonnées -->
                    <input type="hidden" name="ligne" value="">
                    <input type="hidden" name="colonne" value="">
                </form>

                <div class="controles">
                    <form method="post" style="display: inline;">
                        <button type="submit" name="action" value="reinitialiser" class="bouton bouton-reinitialiser">
                            🔄 NOUVELLE PARTIE
                        </button>
                    </form>
                </div>
        </div>
</body>

</html>