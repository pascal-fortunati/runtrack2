<?php
session_start();

/**
 * Classe pour gérer le jeu de Puissance 4
 */
class JeuPuissance4
{
    private $cle_session = 'puissance4_hackeur';
    private $lignes = 6;
    private $colonnes = 7;

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
            'grille' => array_fill(0, $this->lignes, array_fill(0, $this->colonnes, '')),
            'joueur_actuel' => 'ROUGE',
            'gagnant' => null,
            'partie_terminee' => false,
            'jetons_gagnants' => [],
            'dernier_jeton' => null,
            'scores' => $_SESSION[$this->cle_session]['scores'] ?? ['ROUGE' => 0, 'JAUNE' => 0, 'nuls' => 0]
        ];
    }

    /**
     * Joue un jeton dans une colonne donnée
     */
    public function jouerJeton($colonne)
    {
        $jeu = &$_SESSION[$this->cle_session];

        // Vérifier si le jeu est terminé ou la colonne invalide
        if ($jeu['partie_terminee'] || $colonne < 0 || $colonne >= $this->colonnes) {
            return false;
        }

        // Trouver la première case libre dans cette colonne (de bas en haut)
        $ligne_libre = -1;
        for ($ligne = $this->lignes - 1; $ligne >= 0; $ligne--) {
            if (empty($jeu['grille'][$ligne][$colonne])) {
                $ligne_libre = $ligne;
                break;
            }
        }

        // Si la colonne est pleine
        if ($ligne_libre === -1) {
            return false;
        }

        // Placer le jeton du joueur actuel
        $jeu['grille'][$ligne_libre][$colonne] = $jeu['joueur_actuel'];
        $jeu['dernier_jeton'] = [$ligne_libre, $colonne];

        // Vérifier si ce coup gagne la partie
        $resultat = $this->verifierVictoire($ligne_libre, $colonne);

        if ($resultat['gagnant']) {
            // Il y a un gagnant
            $jeu['gagnant'] = $resultat['gagnant'];
            $jeu['jetons_gagnants'] = $resultat['jetons_gagnants'];
            $jeu['partie_terminee'] = true;
            $jeu['scores'][$resultat['gagnant']]++;
        } elseif ($this->estGrillePleine()) {
            // Match nul (grille pleine)
            $jeu['partie_terminee'] = true;
            $jeu['scores']['nuls']++;
        } else {
            // Changer de joueur
            $jeu['joueur_actuel'] = $jeu['joueur_actuel'] === 'ROUGE' ? 'JAUNE' : 'ROUGE';
        }

        return true;
    }

    /**
     * Vérifie si un coup à la position donnée gagne la partie
     */
    private function verifierVictoire($ligne, $colonne)
    {
        $jeu = $_SESSION[$this->cle_session];
        $grille = $jeu['grille'];
        $joueur = $grille[$ligne][$colonne];

        // Directions pour vérifier : horizontal, vertical, diagonale \, diagonale /
        $directions = [
            [0, 1],   // horizontal
            [1, 0],   // vertical  
            [1, 1],   // diagonale \
            [1, -1]   // diagonale /
        ];

        // Vérifier chaque direction
        foreach ($directions as $direction) {
            $jetons_alignes = [[$ligne, $colonne]]; // Le jeton qu'on vient de jouer

            // Vérifier dans un sens
            $this->chercherJetonsDirection($grille, $ligne, $colonne, $direction[0], $direction[1], $joueur, $jetons_alignes);

            // Vérifier dans l'autre sens
            $this->chercherJetonsDirection($grille, $ligne, $colonne, -$direction[0], -$direction[1], $joueur, $jetons_alignes);

            // Si on a 4 jetons ou plus alignés
            if (count($jetons_alignes) >= 4) {
                return [
                    'gagnant' => $joueur,
                    'jetons_gagnants' => $jetons_alignes
                ];
            }
        }

        return [
            'gagnant' => null,
            'jetons_gagnants' => []
        ];
    }

    /**
     * Cherche les jetons alignés dans une direction donnée
     */
    private function chercherJetonsDirection($grille, $ligne_depart, $colonne_depart, $delta_ligne, $delta_colonne, $joueur, &$jetons_alignes)
    {
        $ligne_actuelle = $ligne_depart + $delta_ligne;
        $colonne_actuelle = $colonne_depart + $delta_colonne;

        // Continuer tant qu'on reste dans la grille et qu'on trouve le même joueur
        while (
            $ligne_actuelle >= 0 && $ligne_actuelle < $this->lignes &&
            $colonne_actuelle >= 0 && $colonne_actuelle < $this->colonnes &&
            $grille[$ligne_actuelle][$colonne_actuelle] === $joueur
        ) {

            $jetons_alignes[] = [$ligne_actuelle, $colonne_actuelle];
            $ligne_actuelle += $delta_ligne;
            $colonne_actuelle += $delta_colonne;
        }
    }

    /**
     * Vérifie si la grille est complètement pleine
     */
    private function estGrillePleine()
    {
        $jeu = $_SESSION[$this->cle_session];

        // Vérifier la ligne du haut seulement
        for ($colonne = 0; $colonne < $this->colonnes; $colonne++) {
            if (empty($jeu['grille'][0][$colonne])) {
                return false;
            }
        }
        return true;
    }

    /**
     * Vérifie si une case fait partie des jetons gagnants
     */
    public function estJetonGagnant($ligne, $colonne)
    {
        $jeu = $_SESSION[$this->cle_session];

        foreach ($jeu['jetons_gagnants'] as $position) {
            if ($position[0] === $ligne && $position[1] === $colonne) {
                return true;
            }
        }
        return false;
    }

    /**
     * Vérifie si une case est le dernier jeton joué
     */
    public function estDernierJeton($ligne, $colonne)
    {
        $jeu = $_SESSION[$this->cle_session];

        if (!$jeu['dernier_jeton']) return false;

        return $jeu['dernier_jeton'][0] === $ligne && $jeu['dernier_jeton'][1] === $colonne;
    }

    /**
     * Vérifie si une colonne peut recevoir un jeton
     */
    public function colonneJouable($colonne)
    {
        $jeu = $_SESSION[$this->cle_session];

        // Vérifier si la case du haut de cette colonne est libre
        return empty($jeu['grille'][0][$colonne]) && !$jeu['partie_terminee'];
    }
}

// Initialisation du jeu de Puissance 4
$puissance4 = new JeuPuissance4();

// Traitement des actions du joueur
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'jouer':
                // Jouer un jeton dans une colonne
                if (isset($_POST['colonne'])) {
                    $puissance4->jouerJeton((int)$_POST['colonne']);
                }
                break;
            case 'reinitialiser':
                // Recommencer une nouvelle partie
                $puissance4->reinitialiserPartie();
                break;
        }
    }

    // Redirection pour éviter le double envoi
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Récupérer l'état actuel du jeu
$jeu = $puissance4->obtenirJeu();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puissance 4</title>
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

        /* Effet de fond matrice animé */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                linear-gradient(0deg, transparent 24%, rgba(0, 255, 65, 0.03) 25%, rgba(0, 255, 65, 0.03) 26%, transparent 27%, transparent 74%, rgba(0, 255, 65, 0.03) 75%, rgba(0, 255, 65, 0.03) 76%, transparent 77%, transparent),
                linear-gradient(90deg, transparent 24%, rgba(0, 255, 65, 0.03) 25%, rgba(0, 255, 65, 0.03) 26%, transparent 27%, transparent 74%, rgba(0, 255, 65, 0.03) 75%, rgba(0, 255, 65, 0.03) 76%, transparent 77%, transparent);
            background-size: 30px 30px;
            z-index: -1;
            animation: grille-hackeur 15s linear infinite;
        }

        @keyframes grille-hackeur {
            0% {
                transform: translate(0, 0);
            }

            100% {
                transform: translate(30px, 30px);
            }
        }

        /* Conteneur principal du jeu */
        .conteneur-jeu {
            background: rgba(0, 0, 0, 0.9);
            border: 2px solid #00ff41;
            border-radius: 10px;
            padding: 2rem;
            box-shadow:
                0 0 30px rgba(0, 255, 65, 0.4),
                inset 0 0 30px rgba(0, 255, 65, 0.1);
            max-width: 600px;
            width: 95%;
            position: relative;
        }

        /* Effet de scan vertical */
        .conteneur-jeu::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, #00ff41, #40ff80, #00ff41, transparent);
            animation: scan-vertical 3s linear infinite;
        }

        @keyframes scan-vertical {
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
            text-shadow: 0 0 15px #00ff41;
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
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .jeton-joueur {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 2px solid #00ff41;
            display: inline-block;
            position: relative;
        }

        .jeton-rouge {
            background: linear-gradient(45deg, #ff0040, #cc0033);
            box-shadow: 0 0 10px #ff0040;
        }

        .jeton-jaune {
            background: linear-gradient(45deg, #ffff00, #cccc00);
            box-shadow: 0 0 10px #ffff00;
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
            min-width: 80px;
        }

        .valeur-score {
            display: block;
            font-size: 1.5rem;
            font-weight: bold;
            color: #00ff41;
            text-shadow: 0 0 5px #00ff41;
        }

        /* Grille de jeu Puissance 4 */
        .grille-puissance4 {
            background: rgba(0, 50, 100, 0.3);
            border: 3px solid #00ff41;
            border-radius: 10px;
            padding: 15px;
            margin: 2rem 0;
            box-shadow: 0 0 20px rgba(0, 255, 65, 0.3);
        }

        /* Boutons pour jouer dans chaque colonne */
        .boutons-colonnes {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            margin-bottom: 10px;
        }

        .bouton-colonne {
            background: rgba(0, 255, 65, 0.1);
            border: 1px solid #00ff41;
            color: #00ff41;
            padding: 10px 5px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Courier New', monospace;
            text-transform: uppercase;
        }

        .bouton-colonne:hover:not(:disabled) {
            background: rgba(0, 255, 65, 0.3);
            box-shadow: 0 0 15px rgba(0, 255, 65, 0.5);
            text-shadow: 0 0 10px #00ff41;
        }

        .bouton-colonne:disabled {
            background: rgba(255, 0, 64, 0.1);
            border-color: #ff0040;
            color: #ff0040;
            cursor: not-allowed;
        }

        /* Grille des jetons */
        .grille-jetons {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            grid-template-rows: repeat(6, 1fr);
            gap: 8px;
            background: rgba(0, 0, 0, 0.5);
            padding: 10px;
            border-radius: 5px;
        }

        /* Cases de la grille (emplacements pour jetons) */
        .case-jeton {
            aspect-ratio: 1;
            border: 2px solid #333;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.8);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Jetons dans les cases - SANS animation par défaut */
        .jeton {
            width: 90%;
            height: 90%;
            border-radius: 50%;
            border: 2px solid #00ff41;
            position: relative;
            /* Animation désactivée par défaut */
        }

        /* Couleurs des jetons */
        .jeton.rouge {
            background: linear-gradient(45deg, #ff0040, #cc0033);
            box-shadow: 0 0 15px #ff0040;
        }

        .jeton.jaune {
            background: linear-gradient(45deg, #ffff00, #cccc00);
            box-shadow: 0 0 15px #ffff00;
        }

        /* Jetons gagnants avec effet spécial */
        .jeton.gagnant {
            animation: pulsation-victoire 1s infinite;
            border-width: 3px;
            z-index: 10;
        }

        /* Dernier jeton joué */
        .jeton.dernier {
            border-color: #40ff80;
            border-width: 3px;
            animation: surbrillance-dernier 2s ease-out;
        }

        @keyframes chute-jeton {
            0% {
                transform: translateY(-300px) scale(0.8);
                opacity: 0.8;
            }

            60% {
                transform: translateY(10px) scale(1.1);
                opacity: 1;
            }

            80% {
                transform: translateY(-5px) scale(1.05);
            }

            100% {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
        }

        @keyframes pulsation-victoire {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 0 15px currentColor;
            }

            50% {
                transform: scale(1.2);
                box-shadow: 0 0 25px currentColor, 0 0 35px currentColor;
            }
        }

        @keyframes surbrillance-dernier {
            0% {
                border-color: #40ff80;
            }

            50% {
                border-color: #80ff80;
            }

            100% {
                border-color: #40ff80;
            }
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
            animation: clignotement-victoire 1s infinite;
        }

        .message-nul {
            color: #ffff00;
            text-shadow: 0 0 10px #ffff00;
        }

        @keyframes clignotement-victoire {

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
            border-radius: 5px;
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
        @media (max-width: 768px) {
            .conteneur-jeu {
                padding: 1rem;
            }

            h1 {
                font-size: 2rem;
            }

            .grille-jetons {
                gap: 4px;
            }

            .informations-partie {
                flex-direction: column;
                text-align: center;
            }

            .scores {
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="conteneur-jeu">
        <h1>PUISSANCE 4</h1>

        <div class="informations-partie">
            <?php if (!$jeu['partie_terminee']): ?>
                <div class="joueur-actuel">
                    JOUEUR ACTUEL : <?= $jeu['joueur_actuel'] ?>
                    <span class="jeton-joueur <?= strtolower($jeu['joueur_actuel'] === 'ROUGE' ? 'jeton-rouge' : 'jeton-jaune') ?>"></span>
                </div>
            <?php endif; ?>

            <div class="scores">
                <div class="element-score">
                    <span class="valeur-score"><?= $jeu['scores']['ROUGE'] ?></span>
                    <div>ROUGE</div>
                </div>
                <div class="element-score">
                    <span class="valeur-score"><?= $jeu['scores']['nuls'] ?></span>
                    <div>NULS</div>
                </div>
                <div class="element-score">
                    <span class="valeur-score"><?= $jeu['scores']['JAUNE'] ?></span>
                    <div>JAUNE</div>
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
                            === GRILLE SATURÉE - ÉGALITÉ ===
                        </div>
                    <?php endif; ?>
                </div>

                <div class="grille-puissance4">
                    <!-- Boutons pour jouer dans chaque colonne -->
                    <?php if (!$jeu['partie_terminee']): ?>
                        <div class="boutons-colonnes">
                            <?php for ($colonne = 0; $colonne < 7; $colonne++): ?>
                                <button
                                    type="button"
                                    class="bouton-colonne"
                                    <?= !$puissance4->colonneJouable($colonne) ? 'disabled' : '' ?>
                                    onclick="jouerCoupAjax(<?= $colonne ?>)">
                                    <?= !$puissance4->colonneJouable($colonne) ? 'PLEINE' : 'COL ' . ($colonne + 1) ?>
                                </button>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Grille des jetons -->
                    <div class="grille-jetons">
                        <?php for ($ligne = 0; $ligne < 6; $ligne++): ?>
                            <?php for ($colonne = 0; $colonne < 7; $colonne++): ?>
                                <div class="case-jeton">
                                    <?php if (!empty($jeu['grille'][$ligne][$colonne])): ?>
                                        <div class="jeton <?= strtolower($jeu['grille'][$ligne][$colonne]) ?> 
                                    <?= $puissance4->estJetonGagnant($ligne, $colonne) ? 'gagnant' : '' ?>
                                    <?= $puissance4->estDernierJeton($ligne, $colonne) ? 'dernier' : '' ?>">
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endfor; ?>
                        <?php endfor; ?>
                    </div>
                </div>

                <div class="controles">
                    <button onclick="nouvellePartie()" class="bouton bouton-reinitialiser">
                        🔄 NOUVELLE PARTIE
                    </button>
                </div>
        </div>

        <script>
            // Variables globales pour le jeu
            let partie_en_cours = <?= $jeu['partie_terminee'] ? 'false' : 'true' ?>;
            let joueur_actuel = '<?= $jeu['joueur_actuel'] ?>';
            let grille = <?= json_encode($jeu['grille']) ?>;
            let scores = <?= json_encode($jeu['scores']) ?>;

            // Jouer un coup en pur JavaScript sans rechargement
            function jouerCoupAjax(colonne) {
                if (!partie_en_cours) return;

                // Vérifier si la colonne est jouable
                if (grille[0][colonne] !== '') return;

                // Désactiver temporairement tous les boutons
                document.querySelectorAll('.bouton-colonne').forEach(btn => btn.disabled = true);

                // Animation du bouton cliqué
                const bouton = document.querySelector(`[onclick*="jouerCoupAjax(${colonne})"]`);
                bouton.style.transform = 'scale(0.9)';
                bouton.style.boxShadow = '0 0 25px #00ff41';

                // Trouver la ligne libre dans cette colonne
                let ligne_libre = -1;
                for (let ligne = 5; ligne >= 0; ligne--) {
                    if (grille[ligne][colonne] === '') {
                        ligne_libre = ligne;
                        break;
                    }
                }

                if (ligne_libre === -1) {
                    // Colonne pleine - réactiver les boutons
                    remettreBoutonsActifs();
                    return;
                }

                // Mettre à jour la grille locale
                grille[ligne_libre][colonne] = joueur_actuel;

                // Créer et animer le jeton qui tombe
                const case_index = ligne_libre * 7 + colonne;
                const case_element = document.querySelectorAll('.case-jeton')[case_index];

                const nouveau_jeton = document.createElement('div');
                nouveau_jeton.className = `jeton ${joueur_actuel.toLowerCase()} dernier`;

                // Calculer la hauteur de départ pour que le jeton démarre au-dessus de la grille
                const hauteur_case = case_element.offsetHeight || 60;
                nouveau_jeton.style.transform = `translateY(-${(ligne_libre + 2) * hauteur_case}px)`;
                nouveau_jeton.style.transition = 'transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275)';

                case_element.appendChild(nouveau_jeton);

                // Déclencher l'animation de chute après un court délai
                setTimeout(() => {
                    nouveau_jeton.style.transform = 'translateY(0)';
                }, 100);

                // Après l'animation, vérifier la victoire et changer de joueur
                setTimeout(() => {
                    // Enlever la classe dernier des autres jetons
                    document.querySelectorAll('.jeton.dernier').forEach(j => {
                        if (j !== nouveau_jeton) j.classList.remove('dernier');
                    });

                    // Vérifier victoire
                    const resultat = verifierVictoire(ligne_libre, colonne);

                    if (resultat.gagnant) {
                        // Victoire !
                        partie_en_cours = false;
                        scores[resultat.gagnant]++;

                        console.log(`Victoire de ${resultat.gagnant}! Positions gagnantes:`, resultat.positions_gagnantes);

                        // Marquer TOUS les jetons gagnants avec un délai pour l'effet
                        resultat.positions_gagnantes.forEach((pos, index) => {
                            setTimeout(() => {
                                const case_index = pos[0] * 7 + pos[1];
                                const case_gagnante = document.querySelectorAll('.case-jeton')[case_index];
                                const jeton_gagnant = case_gagnante.querySelector('.jeton');
                                if (jeton_gagnant) {
                                    jeton_gagnant.classList.add('gagnant');
                                    console.log(`Jeton gagnant ajouté à position [${pos[0]}, ${pos[1]}]`);
                                }
                            }, index * 100); // Effet cascade
                        });

                        // Afficher message de victoire
                        setTimeout(() => {
                            document.querySelector('.statut-partie').innerHTML =
                                `<div class="message-gagnant">>>> JOUEUR ${resultat.gagnant} A GAGNÉ ! <<<</div>`;
                        }, 500);

                    } else if (estGrillePleine()) {
                        // Match nul
                        partie_en_cours = false;
                        scores.nuls++;
                        document.querySelector('.statut-partie').innerHTML =
                            `<div class="message-nul">=== GRILLE SATURÉE - ÉGALITÉ ===</div>`;
                    } else {
                        // Changer de joueur
                        joueur_actuel = joueur_actuel === 'ROUGE' ? 'JAUNE' : 'ROUGE';

                        // Mettre à jour l'affichage du joueur actuel
                        const info_joueur = document.querySelector('.joueur-actuel');
                        if (info_joueur) {
                            const jeton_class = joueur_actuel === 'ROUGE' ? 'jeton-rouge' : 'jeton-jaune';
                            info_joueur.innerHTML = `JOUEUR ACTUEL : ${joueur_actuel} <span class="jeton-joueur ${jeton_class}"></span>`;
                        }
                    }

                    // Mettre à jour les scores
                    mettreAJourScores();

                    // Envoyer au serveur en arrière-plan (sans attendre)
                    envoyerCoupServeur(colonne);

                    // Réactiver les boutons si la partie continue
                    if (partie_en_cours) {
                        remettreBoutonsActifs();
                    }

                }, 700);
            }

            // Vérifier victoire côté client
            function verifierVictoire(ligne, colonne) {
                const joueur = grille[ligne][colonne];
                const directions = [
                    [0, 1], // horizontal
                    [1, 0], // vertical  
                    [1, 1], // diagonale \
                    [1, -1] // diagonale /
                ];

                for (let direction of directions) {
                    let positions = [
                        [ligne, colonne]
                    ]; // Commencer avec le jeton qu'on vient de jouer

                    // Vérifier dans le sens positif
                    chercherDirection(ligne, colonne, direction[0], direction[1], joueur, positions);

                    // Vérifier dans le sens négatif
                    chercherDirection(ligne, colonne, -direction[0], -direction[1], joueur, positions);

                    // Si on a 4 jetons ou plus alignés
                    if (positions.length >= 4) {
                        console.log(`Victoire trouvée! ${positions.length} jetons alignés:`, positions);
                        return {
                            gagnant: joueur,
                            positions_gagnantes: positions
                        };
                    }
                }

                return {
                    gagnant: null,
                    positions_gagnantes: []
                };
            }

            function chercherDirection(ligne, colonne, deltaL, deltaC, joueur, positions) {
                let l = ligne + deltaL;
                let c = colonne + deltaC;

                // Continuer tant qu'on est dans la grille et qu'on trouve le même joueur
                while (l >= 0 && l < 6 && c >= 0 && c < 7 && grille[l][c] === joueur) {
                    positions.push([l, c]);
                    l += deltaL;
                    c += deltaC;
                }
            }

            function estGrillePleine() {
                for (let c = 0; c < 7; c++) {
                    if (grille[0][c] === '') return false;
                }
                return true;
            }

            function remettreBoutonsActifs() {
                document.querySelectorAll('.bouton-colonne').forEach((btn, index) => {
                    if (grille[0][index] === '' && partie_en_cours) {
                        btn.disabled = false;
                        btn.textContent = `COL ${index + 1}`;
                    } else {
                        btn.disabled = true;
                        btn.textContent = 'PLEINE';
                    }
                });
            }

            function mettreAJourScores() {
                document.querySelectorAll('.element-score .valeur-score').forEach((score, index) => {
                    if (index === 0) score.textContent = scores.ROUGE;
                    else if (index === 1) score.textContent = scores.nuls;
                    else if (index === 2) score.textContent = scores.JAUNE;
                });
            }

            function envoyerCoupServeur(colonne) {
                const formData = new FormData();
                formData.append('action', 'jouer');
                formData.append('colonne', colonne);

                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                }).catch(() => {}); // Ignorer les erreurs réseau
            }

            // Initialisation
            document.addEventListener('DOMContentLoaded', function() {
                // Effet de frappe de terminal pour le titre
                const titre = document.querySelector('h1');
                const texte_original = titre.textContent;
                titre.textContent = '';

                let index = 0;
                const vitesse_frappe = 80;

                function taper() {
                    if (index < texte_original.length) {
                        titre.textContent += texte_original.charAt(index);
                        index++;
                        setTimeout(taper, vitesse_frappe);
                    }
                }

                taper();

                // Désactiver les animations CSS sur les jetons existants
                document.querySelectorAll('.jeton').forEach(jeton => {
                    jeton.style.animation = 'none';
                });
            });

            // Fonction pour nouvelle partie (recharger la page)
            function nouvellePartie() {
                const formData = new FormData();
                formData.append('action', 'reinitialiser');

                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                }).then(() => {
                    window.location.reload();
                });
            }
        </script>
</body>

</html>