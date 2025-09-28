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

// Récupérer **toutes les étapes avec leur statut réel** pour ce projet
$stmt = $pdo->prepare("
    SELECT etape, statut, date_mise_a_jour, action_text
    FROM progression
    WHERE id_user = ?
    ORDER BY id_progression ASC
");
$stmt->execute([$user_id]);
$progressions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Génération du HTML pour le PDF
$html = '<h1>Mon parcours Entrepreneur Step</h1>';
$html .= '<table border="1" cellpadding="8" cellspacing="0" width="100%">';
$html .= '<tr><th>Étape</th><th>Statut</th><th>Date mise à jour</th><th>Contenu</th></tr>';

foreach ($progressions as $p) {
    $status_display = ($p['statut'] === 'termine') ? '✔ Validé' 
                    : ($p['statut'] === 'en cours' ? '⏳ En cours' : '❌ Bloqué');

    $html .= '<tr>';
    $html .= '<td>' . htmlspecialchars($p['etape']) . '</td>';
    $html .= '<td>' . $status_display . '</td>';
    $html .= '<td>' . htmlspecialchars($p['date_mise_a_jour']) . '</td>';
    $html .= '<td>' . nl2br(htmlspecialchars($p['action_text'])) . '</td>';
    $html .= '</tr>';
}

$html .= '</table>';

// Génération du PDF
$mpdf = new Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output('parcours.pdf', 'D'); // téléchargement automatique
exit();
?>
