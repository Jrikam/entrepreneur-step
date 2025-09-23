<?php
session_start();
require_once 'pdo.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit();
}

if (isset($_GET['id'])) {
    $user_id = $_SESSION['user_id'];
    $id_projet = intval($_GET['id']);

    // Supprimer uniquement si le projet appartient à l'utilisateur
    $stmt = $pdo->prepare("DELETE FROM historique_projets WHERE id_projet = ? AND id_user = ?");
    $stmt->execute([$id_projet, $user_id]);
}

header('Location: historique.php');
exit();
?>
