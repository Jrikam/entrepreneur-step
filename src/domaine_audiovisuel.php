<?php
session_start();
require_once 'pdo.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enregistrer_historique.php'])) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: connexion.php');
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $titre = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($titre && $description) {
        $stmt = $pdo->prepare("
            INSERT INTO historique_projets (id_user, nom_projet, description)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$user_id, $titre, $description]);
    }

    header('Location: historique.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Domaine Audiovisuel - Entrepreneur Step</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="audiovisuel">
<header>
    <div class="logo">Entrepreneur Step</div>
    <nav>
        <a href="connexion.php">Connexion</a>
        <a href="inscription.php">S’inscrire</a>
        <a href="deconnexion.php">Déconnexion</a>
    </nav>
</header>

<div class="container">
    <h1>Audiovisuel</h1>
    <p>Racontez vos histoires en images et en sons.</p>

    <form method="POST" action="enregistrer_historique.php">
    <label for="titre">Titre du projet</label>
    <input type="text" id="titre" name="titre" placeholder="Ex: Création de court métrages" required>

    <label for="description">Description</label>
    <textarea id="description" name="description" placeholder="Décrivez votre projet ici..." required></textarea>

    <button type="submit">Enregistrer dans l'historique</button>
</form>

    
    <div class="liens">
        <a href="ressources.php">📚 Ressources</a>
        <a href="legalite.php">⚖️ Légalité</a>
        <a href="historique.php">Historique</a>
    </div>
</div>
</body>
</html>
