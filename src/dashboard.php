<?php
session_start();
require_once 'pdo.php';

// Vérification utilisateur
if (!isset($_SESSION['user_id'])) {
    die("Erreur : utilisateur non connecté.");
}
$user_id = $_SESSION['user_id'];

// --- Récupération des projets de l'utilisateur ---
$stmt = $pdo->prepare("SELECT * FROM historique_projets WHERE id_user = ? ORDER BY date_creation DESC");
$stmt->execute([$user_id]);
$projets = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$projets) {
    die("Erreur : aucun projet trouvé pour cet utilisateur.");
}

// --- Détermination du projet courant ---
$id_projet = isset($_GET['projet']) ? intval($_GET['projet']) : $projets[0]['id_projet'];

// Vérification que le projet existe
$projet_courant = null;
foreach ($projets as $p) {
    if ($p['id_projet'] == $id_projet) {
        $projet_courant = $p;
        break;
    }
}
if (!$projet_courant) die("Erreur : projet introuvable.");

// --- Récupération des domaines ---
$stmt = $pdo->query("SELECT * FROM domaines");
$domaines = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (!$domaines) die("Erreur : aucun domaine trouvé.");

// --- Étapes ---
$etapes = [
    ['id'=>1,'nom'=>'Definir le pitch du projet','desc'=>'Ecrivez votre idee de projet.'],
    ['id'=>2,'nom'=>'Faire une mini-etude de marche','desc'=>'Regardez le marche et notez vos observations.'],
    ['id'=>3,'nom'=>'Ecrire un mini-business plan','desc'=>'Structurer votre projet en quelques lignes.'],
    ['id'=>4,'nom'=>'Voir les financements possibles','desc'=>'Consultez les options pour financer votre projet.'],
    ['id'=>5,'nom'=>'Choisir un statut juridique','desc'=>'Selectionnez le statut adapte a votre projet.'],
    ['id'=>6,'nom'=>'Presentation finale','desc'=>'Felicitations ! Vous avez prepare toutes les bases de votre projet.']
];
$pointsParEtape = 20;

// --- Réinitialisation globale ---
if (isset($_POST['reset'])) {
    $stmt = $pdo->prepare("DELETE FROM progression WHERE id_user = ? AND id_projet = ?");
    $stmt->execute([$user_id, $id_projet]);
    header("Location: dashboard.php?projet=$id_projet");
    exit();
}

// --- Réinitialisation d'une étape spécifique ---
if (isset($_POST['reset_etape'])) {
    $id_domaine = intval($_POST['domaine']);
    $etape_nom = $_POST['etape_nom'];
    $stmt = $pdo->prepare("
        DELETE FROM progression 
        WHERE id_user = ? AND id_domaine = ? AND id_projet = ? AND etape = ?
    ");
    $stmt->execute([$user_id, $id_domaine, $id_projet, $etape_nom]);
    header("Location: dashboard.php?projet=$id_projet");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Dashboard - <?= htmlspecialchars($projet_courant['nom_projet']) ?></title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="logo">Entrepreneur Step</div>
    <nav>
        <a href="deconnexion.php">Se déconnecter</a>
        <a href="historique.php">Historique</a>
    </nav>
</header>

<div class="container">
    <form method="GET" action="dashboard.php" style="margin-bottom:20px;">
    <label for="projet">Changer de projet :</label>
    <select name="projet" id="projet" onchange="this.form.submit()">
        <?php foreach($projets as $p): ?>
            <option value="<?= $p['id_projet'] ?>" 
                <?= ($p['id_projet'] == $id_projet) ? 'selected' : '' ?>>
                <?= htmlspecialchars($p['nom_projet']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>
    <h1>Dashboard pour le projet : <?= htmlspecialchars($projet_courant['nom_projet']) ?></h1>
    <p>
    <a href="export_pdf.php?projet=<?= $id_projet ?>" target="_blank" style="display:inline-block; margin-bottom:20px;">
        Exporter en PDF
    </a>
</p>


    <!-- Bouton de réinitialisation globale -->
    <form method="POST" style="margin-bottom:20px;">
        <button type="submit" name="reset" style="background:red; color:#fff; padding:10px; border:none; border-radius:5px;">
            Réinitialiser toutes les étapes
        </button>
    </form>

    <?php foreach ($domaines as $domaine): 
        $stmt2 = $pdo->prepare("SELECT etape FROM progression WHERE id_user=? AND id_projet=? AND id_domaine=?");
        $stmt2->execute([$user_id, $id_projet, $domaine['id']]);
        $valides = $stmt2->fetchAll(PDO::FETCH_COLUMN);
        $progress = min(count($valides) * $pointsParEtape, 100);
    ?>
        <h3><?= htmlspecialchars($domaine['nom']) ?> (<?= $progress ?>%)</h3>
        <div class="progress-container" style="width:80%; margin-bottom:10px; background:#ddd; border-radius:20px; height:25px;">
            <div class="progress-bar" style="width:<?= $progress ?>%; background:#4caf50; height:100%; border-radius:20px; text-align:center; color:#fff; line-height:25px;">
                <?= $progress ?>%
            </div>
        </div>

        <ul>
            <?php foreach ($etapes as $etapeItem): ?>
                <li>
                    <?= htmlspecialchars($etapeItem['nom']) ?> -
                    <?php if (in_array($etapeItem['nom'], $valides)): ?>
                        <span style="color:green;">✔ Validée</span>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="reset_etape" value="1">
                            <input type="hidden" name="domaine" value="<?= $domaine['id'] ?>">
                            <input type="hidden" name="etape_nom" value="<?= htmlspecialchars($etapeItem['nom']) ?>">
                            <button type="submit" style="background:orange; color:#fff; padding:5px 10px; border:none; border-radius:5px;">
                                Réinitialiser cette étape
                            </button>
                        </form>
                    <?php else: ?>
                        <a href="service.php?projet=<?= $id_projet ?>&domaine=<?= $domaine['id'] ?>&etape=<?= urlencode($etapeItem['nom']) ?>">
                            <button>Aller au service</button>
                        </a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>
</div>
</body>
</html>