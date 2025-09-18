<?php
session_start();
require_once 'pdo.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit();
}

$user_id   = $_SESSION['user_id'];
$domain_id = isset($_GET['domaine']) ? intval($_GET['domaine']) : 1;

// Tous les services
$services = [
    ['nom' => 'Définir le pitch du projet', 'desc' => 'Un pitch : 1–2 phrases pour expliquer simplement ton projet.', 'lien' => 'https://bpifrance-creation.fr/boiteaoutils/guide-methodologique-du-pitch'],
    ['nom' => 'Mini-étude de marché', 'desc' => 'Observe la concurrence et note les tendances et opportunités.', 'lien' => 'https://bpifrance-creation.fr/encyclopedie/faire-une-etude-de-marche'],
    ['nom' => 'Business plan simplifié', 'desc' => 'Structure ton projet (objectifs, cible, besoins financiers).', 'lien' => 'https://bpifrance-creation.fr/encyclopedie/business-plan'],
    ['nom' => 'Financement possible', 'desc' => 'Repère aides, subventions, prêts, crowdfunding adaptés à ton projet.', 'lien' => 'https://bpifrance.fr'],
    ['nom' => 'Choisir un statut juridique', 'desc' => 'Compare rapidement les statuts (auto-entrepreneur, SAS, SARL, association...).', 'lien' => 'https://www.l-expert-comptable.com/a/534993-tableau-comparatif-des-statuts-juridiques-quel-statut-choisir.html'],
    ['nom' => 'Présentation finale', 'desc' => 'Rassemble les points clés pour présenter ton projet (résumé, slides).', 'lien' => 'https://asana.com/fr/resources/executive-summary-examples'],
];

// Sélectionne uniquement le service choisi
$service = $services[$domain_id - 1] ?? null;
if (!$service) {
    die('Service non trouvé.');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($service['nom']) ?> - Entrepreneur Step</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="logo">Entrepreneur Step</div>
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="ressources.php">Ressources</a>
        <a href="deconnexion.php">Déconnexion</a>
    </nav>
</header>

<div class="container">
    <h1><?= htmlspecialchars($service['nom']) ?></h1>
    <p><?= htmlspecialchars($service['desc']) ?>
        <a href="<?= htmlspecialchars($service['lien']) ?>" target="_blank">En savoir plus ↗</a>
    </p>

    <form method="post" action="service.php?domaine=<?= $domain_id ?>">
        <?php if ($service['nom'] === 'Choisir un statut juridique'): ?>
            <label>Choisissez un statut :</label>
            <select name="statut_choice" required>
                <option value="">-- Choisir --</option>
                <option value="auto-entrepreneur">Auto-entrepreneur / Micro-entreprise</option>
                <option value="entreprise-individuelle">Entreprise individuelle</option>
                <option value="eurl-sarl">EURL / SARL</option>
                <option value="sas-sasu">SAS / SASU</option>
                <option value="association">Association</option>
                <option value="autre">Autre</option>
            </select>
        <?php else: ?>
            <label>Ton action / note :</label>
            <textarea name="action_text" placeholder="Écris ici ton action ou ton idée…"></textarea>
        <?php endif; ?>
        <input type="hidden" name="service_nom" value="<?= htmlspecialchars($service['nom']) ?>">
        <button type="submit">Valider ce service</button>
    </form>
</div>
</body>
</html>
