<?php
session_start();
require_once 'pdo.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Domaine Commerce - Entrepreneur Step</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="commerce">
<header>
    <div class="logo">Entrepreneur Step</div>
    <nav>
        <a href="connexion.php">Connexion</a>
        <a href="inscription.php">S’inscrire</a>
        <a href="deconnexion.php">Déconnexion</a>
    </nav>
</header>

<div class="container">
    <h1>Commerce</h1>
    <p>Ici vos échanges deviennent des opportunités</p>

    <form method="POST" action="validation.php">
        <label for="titre">Titre du projet</label>
        <input type="text" id="titre" name="titre" placeholder="Lancez une boutique en ligne de bijoux " required>

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
