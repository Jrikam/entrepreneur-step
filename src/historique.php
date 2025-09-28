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

// Liste complète des services possibles
$all_services = [
    'Définir le pitch du projet',
    'Mini-étude de marché',
    'Business plan simplifié',
    'Financement possible',
    'Choisir un statut juridique',
    'Présentation finale'
];
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
        ul { padding-left:20px; }
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
        <?php foreach($projets as $projet): 
            $id_projet = $projet['id_projet'];
            $id_domaine = $projet['id_domaine'] ?? 1; // Domaine par défaut si non défini

            // Récupérer les services validés pour ce projet
            $stmt2 = $pdo->prepare("
                SELECT etape
                FROM progression
                WHERE id_user = ? AND id_projet = ?
                ORDER BY etape ASC
            ");
            $stmt2->execute([$user_id, $id_projet]);
            $services_valides = $stmt2->fetchAll(PDO::FETCH_COLUMN, 0); // tableau des étapes déjà validées

            // Services à compléter
            $services_a_reprendre = array_diff($all_services, $services_valides);
        ?>
        <div class="projet">
            <h3><?= htmlspecialchars($projet['nom_projet']) ?></h3>
            <p><?= nl2br(htmlspecialchars($projet['description'])) ?></p>
            <span>Date : <?= $projet['date_creation'] ?></span><br>

            <?php if($services_a_reprendre): ?>
                <h4>Services à compléter :</h4>
                <ul>
                    <?php foreach($services_a_reprendre as $service): ?>
                        <li>
                            <?= htmlspecialchars($service) ?> - 
                            <a class="services-link" href="service.php?domaine=<?= $id_domaine ?>&projet=<?= $id_projet ?>&etape=<?= rawurlencode($service) ?>">Reprendre</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Toutes les étapes sont validées pour ce projet.</p>
            <?php endif; ?>

            <a href="supprimer_projet.php?id=<?= $id_projet ?>" onclick="return confirm('Supprimer ce projet ?')">Supprimer</a>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>
</body>
</html>
