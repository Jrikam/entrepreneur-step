<?php
session_start();
require_once 'pdo.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$points = $_SESSION['points'] ?? 0;
$etapesValides = $_SESSION['etapes_valides'] ?? [];

// Définition des étapes
$etapes = [
    ['id' => 1, 'nom' => 'Définir le pitch du projet', 'desc' => 'Écrivez votre idée de projet.'],
    ['id' => 2, 'nom' => 'Faire une mini-étude de marché', 'desc' => 'Regardez le marché et notez vos observations.'],
    ['id' => 3, 'nom' => 'Écrire un mini-business plan', 'desc' => 'Structurer votre projet en quelques lignes.'],
    ['id' => 4, 'nom' => 'Voir les financements possibles', 'desc' => 'Consultez les options pour financer votre projet.'],
    ['id' => 5, 'nom' => 'Choisir un statut juridique', 'desc' => 'Sélectionnez le statut adapté à votre projet.'],
    ['id' => 6, 'nom' => 'Présentation finale', 'desc' => 'Félicitations ! Vous avez préparé toutes les bases de votre projet.']
];

// Liens des pages de domaine
$domainesFormulaires = [
    1 => 'domaine_artisanat.php',
    2 => 'domaine_audiovisuel.php',
    3 => 'domaine_informatique.php',
    4 => 'domaine_marketing.php',
    5 => 'domaine_commerce.php'
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Entrepreneur Step</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="logo">Entrepreneur Step</div>
    <nav>
        <a href="deconnexion.php">Se déconnecter</a>
    </nav>
</header>

<div class="container">
    <h1>Bienvenue sur ton dashboard</h1>
    <?php
// Calcul de la progression en % selon les étapes validées
$totalEtapes = 6; // Nombre total d'étapes
$etapesValides = $_SESSION['etapes_valides'] ?? [];
$progress = round((count($etapesValides) / $totalEtapes) * 100);
?>
<div class="progress-container" style="width:80%; margin:20px auto; background:#ddd; border-radius:20px; height:25px;">
    <div class="progress-bar" style="height:100%; border-radius:20px; background:#4caf50; width:<?= $progress ?>%; text-align:center; color:#fff; line-height:25px;">
        <?= $progress ?>%
    </div>
</div>

    <p>Points actuels : <?= $points ?></p>

    <h2>Étapes du projet</h2>
    <?php foreach ($etapes as $etape): 
        $formLink = $domainesFormulaires[$etape['id']] ?? '#';
    ?>
        <div class="etape">
            <h3><?= htmlspecialchars($etape['nom']) ?></h3>
            <p><?= htmlspecialchars($etape['desc']) ?></p>

            <a href="<?= $formLink ?>"><button>Aller à l’action</button></a>

            <?php if (in_array($etape['id'], $etapesValides)): ?>
                <span class="ok">✔ Validée</span>
            <?php else: ?>
                <form method="POST" action="validation.php" style="display:inline;">
                    <input type="hidden" name="etape_id" value="<?= $etape['id'] ?>">
                    <button type="submit">Valider</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <div style="margin-top:20px;">
        <button onclick="window.location.href='service.php'">Voir les services</button>
    </div>
</div>
</body>
</html>
