<?php
session_start();
require_once 'pdo.php';
require_once __DIR__ . '/vendor/autoload.php'; // Assure-toi d'avoir installé mPDF via Composer

use Mpdf\Mpdf;

if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupération du parcours / projets de l'utilisateur
$stmt = $pdo->prepare("SELECT etape, statut, date_mise_a_jour FROM progression WHERE id_user = ? ORDER BY id_progression ASC");
$stmt->execute([$user_id]);
$progressions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Génération du HTML pour le PDF
$html = '<h1>Mon parcours Entrepreneur Step</h1>';
$html .= '<table border="1" cellpadding="8" cellspacing="0" width="100%">';
$html .= '<tr><th>Étape</th><th>Statut</th><th>Date mise à jour</th></tr>';

foreach ($progressions as $p) {
    $html .= '<tr>';
    $html .= '<td>' . htmlspecialchars($p['etape']) . '</td>';
    $html .= '<td>' . htmlspecialchars($p['statut']) . '</td>';
    $html .= '<td>' . htmlspecialchars($p['date_mise_a_jour']) . '</td>';
    $html .= '</tr>';
}

$html .= '</table>';

// Instancie mPDF et génère le PDF
$mpdf = new Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output('parcours.pdf', 'D'); // D = download automatique
exit();
