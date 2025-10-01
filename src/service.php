<?php
session_start();
require_once 'pdo.php';

// --- Vérification utilisateur ---
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit();
}
$user_id = $_SESSION['user_id'];

// --- Projet courant ---
$id_projet = isset($_GET['projet']) ? intval($_GET['projet']) : null;
if (!$id_projet) die("Erreur : projet non spécifié.");

$stmt = $pdo->prepare("SELECT * FROM historique_projets WHERE id_projet=? AND id_user=?");
$stmt->execute([$id_projet, $user_id]);
$projet = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$projet) die("Erreur : projet introuvable.");

// --- Domaine courant ---
$id_domaine = isset($_GET['domaine']) ? intval($_GET['domaine']) : null;
if (!$id_domaine) die("Erreur : domaine non spécifié.");

$stmt = $pdo->prepare("SELECT * FROM domaines WHERE id=?");
$stmt->execute([$id_domaine]);
$domaine = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$domaine) die("Erreur : domaine introuvable.");

// --- Étape / service ---
$etape_courante = $_GET['etape'] ?? null;
if (!$etape_courante) die("Erreur : étape non spécifiée.");
// --- Mapping pour correspondre aux noms du dashboard ---



// --- Liste des services ---
$services = [
    ['nom' => 'Definir le pitch du projet', 'desc' => 'Un pitch : 1–2 phrases pour expliquer simplement ton projet.', 'lien' => 'https://bpifrance-creation.fr/boiteaoutils/guide-methodologique-du-pitch'],
    ['nom' => 'Faire une mini-etude de marche', 'desc' => 'Observe la concurrence et note les tendances et opportunités.', 'lien' => 'https://bpifrance-creation.fr/encyclopedie/faire-une-etude-de-marche'],
    ['nom' => 'Ecrire un mini-business plan', 'desc' => 'Structure ton projet (objectifs, cible, besoins financiers).', 'lien' => 'https://bpifrance-creation.fr/encyclopedie/business-plan'],
    ['nom' => 'Voir les financements possibles', 'desc' => 'Repère aides, subventions, prêts, crowdfunding adaptés à ton projet.', 'lien' => 'https://bpifrance.fr'],
    ['nom' => 'Choisir un statut juridique', 'desc' => 'Compare rapidement les statuts (auto-entrepreneur, SAS, SARL, association...).', 'lien' => 'https://www.l-expert-comptable.com/a/534993-tableau-comparatif-des-statuts-juridiques-quel-statut-choisir.html'],
    ['nom' => 'Presentation finale', 'desc' => 'Rassemble les points clés pour présenter ton projet (résumé, slides).', 'lien' => 'https://asana.com/fr/resources/executive-summary-examples'],
];


// --- Trouver le service actuel (comparaison insensible aux accents, majuscules et espaces) ---
$service = null;
foreach ($services as $s) {
    if (trim(mb_strtolower($s['nom'])) === trim(mb_strtolower($etape_courante))) {
        $service = $s;
        break;
    }
}
if (!$service) die("Service non trouvé.");

// --- Traitement formulaire ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action_text = trim($_POST['action_text'] ?? '');
    $statut_choisi = $_POST['statut_choice'] ?? 'en cours';

    $stmt = $pdo->prepare("
        INSERT INTO progression (id_user, id_projet, id_domaine, etape, statut, action_text, date_mise_a_jour)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE
            statut = VALUES(statut),
            action_text = VALUES(action_text),
            date_mise_a_jour = NOW()
    ");
    $stmt->execute([$user_id, $id_projet, $id_domaine, $etape_courante, $statut_choisi, $action_text]);

    header("Location: dashboard.php?projet=$id_projet");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($service['nom']) ?> - <?= htmlspecialchars($projet['nom_projet']) ?></title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="logo">Entrepreneur Step</div>
    <nav>
        <a href="dashboard.php?projet=<?= $id_projet ?>">Dashboard</a>
        <a href="historique.php">Historique</a>
        <a href="deconnexion.php">Déconnexion</a>
    </nav>
</header>

<div class="container">
    <h1><?= htmlspecialchars($service['nom']) ?></h1>
    <p><?= htmlspecialchars($service['desc']) ?>
        <a href="<?= htmlspecialchars($service['lien']) ?>" target="_blank">En savoir plus ↗</a>
    </p>

    <form method="post">
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

        <button type="submit">Valider ce service</button>
    </form>
</div>
</body>
</html>