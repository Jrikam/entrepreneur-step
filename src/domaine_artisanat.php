<?php
session_start();
require_once 'pdo.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Domaine Artisanat - Entrepreneur Step</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="artisanat">
<header>
    <div class="logo">Entrepreneur Step</div>
    <nav>
        <a href="connexion.php">Connexion</a>
        <a href="inscription.php">S’inscrire</a>
        <a href="deconnexion.php">Déconnexion</a>
    </nav>
</header>

<div class="container">
    <h1>Artisanat</h1>
    <p> Ici, on façonne vos envies et vos objets de vie.</p>

    <form method="POST" action="validation.php">
        <label for="titre">Titre du projet</label>
        <input type="text" id="titre" name="titre" placeholder="Ex: Création de bijoux artisanaux" required>

        <label for="description">Description</label>
        <textarea id="description" name="description" placeholder="Décrivez votre projet ici..." required></textarea>

        <button type="submit">Valider l’étape</button>
    </form>

    <div class="liens">
        <a href="ressources.php">📚 Ressources</a>
        <a href="#">⚖️ Légalité</a>
        <a href="historique.php"> Enregistrer dans l'historique</a>

    </div>
</div>
</body>
</html>
