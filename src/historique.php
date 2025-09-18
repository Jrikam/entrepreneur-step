<?php
session_start();
require_once 'pdo.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupère tous les projets de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM historique_projets WHERE id_user = ? ORDER BY date_creation DESC");
$stmt->execute([$user_id]);
$projets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des projets - Entrepreneur Step</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .projet { border:1px solid #ddd; padding:10px; margin-bottom:10px; border-radius:6px; background:#f9f9f9; }
        .projet h3 { margin:0; }
        .projet p { margin:5px 0; }
        .services-link { margin-top:6px; display:inline-block; }
    </style>
</head>
<body>
<header>
    <div class="logo">Entrepreneur Step</div>
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="deconnexion.php">Déconnexion</a>
    </nav>
</header>

<div class="container">
    <h1>Historique de tes projets</h1>

    <?php if(empty($projets)): ?>
        <p>Tu n’as encore enregistré aucun projet.</p>
    <?php else: ?>
        <?php foreach($projets as $projet): ?>
            <div class="projet">
                <h3><?= htmlspecialchars($projet['nom_projet']) ?></h3>
                <p><?= nl2br(htmlspecialchars($projet['description'])) ?></p>
                <span>Date : <?= $projet['date_creation'] ?></span><br>
                <a class="services-link" href="service.php?domaine=<?= $projet['id_projet'] ?>">Reprendre les services</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>
