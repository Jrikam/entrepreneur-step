<?php
session_start();
require_once 'pdo.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Domaine Informatique - Entrepreneur Step</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="informatique">
<header>
    <div class="logo">Entrepreneur Step</div>
    <nav>
        <a href="connexion.php">Connexion</a>
        <a href="inscription.php">S’inscrire</a>
        <a href="deconnexion.php">Déconnexion</a>
    </nav>
</header>

<div class="container">
    <h1>Informatique</h1>
    <p>Ici vos idéés prennent vie à travers le code </p>

    <form method="POST" action="validation.php">
        <label for="titre">Titre du projet</label>
        <input type="text" id="titre" name="titre" placeholder="Ex: Création d'application de gestion de taches " required>

        <label for="description">Description</label>
        <textarea id="description" name="description" placeholder="Décrivez votre projet ici..." required></textarea>

        <button type="submit">Valider l’étape</button>
    </form>

    <div class="liens">
        <a href="ressources.php">📚 Ressources</a>
        <a href="#">⚖️ Légalité</a>
    </div>
</div>
</body>
</html>
