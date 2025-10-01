<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
require_once 'pdo.php';
use Mpdf\Mpdf;

if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$id_projet = isset($_GET['projet']) ? intval($_GET['projet']) : null;
if (!$id_projet) die("Erreur : projet non spécifié.");

// Récupération du projet
$stmt = $pdo->prepare("SELECT * FROM historique_projets WHERE id_projet=? AND id_user=?");
$stmt->execute([$id_projet, $user_id]);
$projet = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$projet) die("Erreur : projet introuvable.");

// Récupération des progressions
$stmt = $pdo->prepare("
    SELECT * FROM progression 
    WHERE id_projet=? AND id_user=?
    ORDER BY id_domaine ASC, id_progression ASC
");
$stmt->execute([$id_projet, $user_id]);
$progressions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Génération du HTML pour le PDF
$html = '<h1>Mon parcours Entrepreneur Step</h1>';
$html .= '<h2>Projet : ' . htmlspecialchars($projet['nom_projet']) . '</h2>';

$currentDomaine = null;
foreach ($progressions as $p) {
    // Affichage du domaine
    if ($currentDomaine !== $p['id_domaine']) {
        $stmtD = $pdo->prepare("SELECT nom FROM domaines WHERE id=?");
        $stmtD->execute([$p['id_domaine']]);
        $domaine = $stmtD->fetch(PDO::FETCH_ASSOC);
        $html .= '<h3>' . htmlspecialchars($domaine['nom']) . '</h3>';
        $currentDomaine = $p['id_domaine'];
    }

    $etape = htmlspecialchars($p['etape']);
    $contenu = htmlspecialchars($p['action_text']);

    // Détermination du statut affiché
    if ($p['statut'] === 'termine' || (!empty($p['action_text']) && $p['statut'] === 'en cours')) {
        $statutAffiche = '✔ Validée';
    } elseif ($p['statut'] === 'en cours') {
        $statutAffiche = '⏳ En cours';
    } else {
        $statutAffiche = '❌ Bloqué';
    }

    $html .= '<p><strong>' . $etape . '</strong> - ' . $statutAffiche . '</p>';
    if (!empty($contenu)) {
        $html .= '<p style="margin-left:20px;">' . nl2br($contenu) . '</p>';
    }
}

// Génération du PDF
$mpdf = new Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output('parcours.pdf', 'D'); // téléchargement automatique
exit();
