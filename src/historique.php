<?php
session_start();
require_once 'pdo.php';

// --- Vérification utilisateur ---
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// --- Récupérer tous les projets de l'utilisateur ---
$stmt = $pdo->prepare("SELECT * FROM historique_projets WHERE id_user = ? ORDER BY date_creation DESC");
$stmt->execute([$user_id]);
$projets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// --- Liste complète des services ---
$all_services = [
    'Definir le pitch du projet',
    'Faire une mini-etude de marche',
    'Ecrire un mini-business plan',
    'Voir les financements possibles',
    'Choisir un statut juridique',
    'Presentation finale'
];

// --- Fonction de normalisation pour comparer sans accents/majuscules ---
function normalize($str) {
    $str = trim($str);
    $str = mb_strtolower($str);
    $str = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
    $str = preg_replace('/[^a-z0-9 ]/', '', $str);
    return $str;
}
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
            $id_domaine = $projet['id_domaine'] ?? 1;

            // Récupérer les services déjà validés pour ce projet
            $stmt2 = $pdo->prepare("
                SELECT etape 
                FROM progression 
                WHERE id_user = ? AND id_projet = ?
            ");
            $stmt2->execute([$user_id, $id_projet]);
            $services_valides = $stmt2->fetchAll(PDO::FETCH_COLUMN, 0);

            // Normalisation pour comparaison
            $services_valides_norm = array_map('normalize', $services_valides);

            // Services à compléter
            $services_a_reprendre = [];
            foreach($all_services as $service) {
                if(!in_array(normalize($service), $services_valides_norm)) {
                    $services_a_reprendre[] = $service;
                }
            }
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
