<?php
session_start();
require_once 'pdo.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Vérifie que le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['titre']) && !empty($_POST['description'])) {
    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);

    // Insère le projet dans la table historique_projets
    $stmt = $pdo->prepare("INSERT INTO historique_projets (id_user, nom_projet, description) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $titre, $description]);
}

// Redirige vers l'historique pour voir le projet enregistré
header('Location: historique.php');
exit();
?>
