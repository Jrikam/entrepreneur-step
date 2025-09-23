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
        <?php foreach($projets as $projet): ?>
            <div class="projet">
                <h3><?= htmlspecialchars($projet['nom_projet']) ?></h3>
                <p><?= nl2br(htmlspecialchars($projet['description'])) ?></p>
                <span>Date : <?= $projet['date_creation'] ?></span><br>

                <?php
                // Récupérer les services validés pour ce projet et cet utilisateur
                $stmt2 = $pdo->prepare("
    SELECT id_domaine, etape AS service_nom
    FROM progression
    WHERE id_user = ? AND id_projet = ?
    ORDER BY id_domaine ASC
");
$stmt2->execute([$user_id, $projet['id_projet']]);
$services = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if($services): ?>
    <h4>Services :</h4>
    <ul>
        <?php foreach($services as $service): ?>
            <li>
                <?= htmlspecialchars($service['service_nom']) ?> - 
                <a class="services-link" href="service.php?domaine=<?= $service['id_domaine'] ?>&projet=<?= $projet['id_projet'] ?>&etape=<?= rawurlencode($service['service_nom']) ?>">Reprendre</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <!-- Commencer le premier service par défaut -->
    <a class="services-link" href="service.php?domaine=1&projet=<?= $projet['id_projet'] ?>&etape=<?= rawurlencode('Définir le pitch du projet') ?>">Commencer le service</a>
<?php endif; ?>

                <a href="supprimer_projet.php?id=<?= $projet['id_projet'] ?>" onclick="return confirm('Supprimer ce projet ?')">Supprimer</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>
</body>
</html>
