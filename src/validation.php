<?php
session_start();
require_once 'pdo.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$pointsParEtape = 10;

// Vérifie si une étape est soumise
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['etape_id'])) {
    $etapeId = intval($_POST['etape_id']);

    // Initialisation des étapes validées et des points si ce n'est pas déjà fait
    if (!isset($_SESSION['etapes_valides'])) {
        $_SESSION['etapes_valides'] = [];
    }
    if (!isset($_SESSION['points'])) {
        $_SESSION['points'] = 0;
    }

    // Si l'étape n'est pas déjà validée
    if (!in_array($etapeId, $_SESSION['etapes_valides'])) {
        $_SESSION['etapes_valides'][] = $etapeId;
        $_SESSION['points'] += $pointsParEtape;

        // Stocker la validation dans la base de données
        $stmt = $pdo->prepare("INSERT INTO progression (user_id, etape_id, date_valide) VALUES (?, ?, NOW())");
        $stmt->execute([$user_id, $etapeId]);
    }

    // Redirection vers le dashboard après validation
    header("Location: dashboard.php");
    exit();
} else {
    // Si on accède directement à validation.php sans POST
    header("Location: dashboard.php");
    exit();
}
