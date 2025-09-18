<?php
session_start();
require_once 'pdo.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$legalite = [
    'Artisanat' => [
        ['titre' => 'Guide de démarrage artisanal', 'lien' => 'https://www.lecoindesentrepreneurs.fr/creer-une-entreprise-artisanale', 'desc' => 'Comment créer une entreprise artisanale (Le coin des entrepreneurs.'],
        ['titre' => 'Blog d’inspiration artisanale', 'lien' => 'https://artisandart.fr/creer-son-atelier-dartisanat-dart-guide-complet-2025/ ', 'desc' => 'Créer son atelier d’artisanat d’art']
    ],
    'Audiovisuel' => [
         ['titre' => 'Beginner Filmmaker Playlist', 'lien' => 'https://www.youtube.com/playlist?list=PLXaPsavLQr11fU6ysUHegSiRTu6Xhuz7L', 'desc' => 'Tutoriels sur réalisation et montage pour débutants.'],
        ['titre' => 'Ressources son et image libres', 'lien' => 'https://www.pexels.com/fr-fr/videos/', 'desc' => 'Banque de vidéos libres de droit pour vos projets audiovisuels.']
    ],
    'Informatique' => [
        ['titre' => 'Cours PHP', 'lien' => 'https://www.php.net/manual/fr/tutorial.php', 'desc' => 'Apprenez à créer des applications web en PHP.'],
        ['titre' => 'Cours MySQL', 'lien' => 'https://dev.mysql.com/doc/', 'desc' => 'Guide officiel MySQL pour bases de données et requêtes SQL.']
    ],
    'Marketing' => [
        ['titre' => 'Guide du marketing digital', 'lien' => 'https://www.marketing-etudiant.fr', 'desc' => 'Bases pour promouvoir vos projets en ligne.'],
        ['titre' => 'Outils gratuits de planification', 'lien' => 'https://www.canva.com/fr_fr/', 'desc' => 'Gérez vos campagnes marketing facilement.']
    ],
    'Commerce' => [
        ['titre' => 'Bases du e-commerce', 'lien' => 'https://www.shopify.fr/blog', 'desc' => 'Comment vendre en ligne facilement.'],
        ['titre' => 'Conseils pour votre boutique', 'lien' => 'https://www.bpifrance-creation.fr/encyclopedie', 'desc' => 'Optimisez votre commerce local ou digital.']
    ]
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Légalité - Entrepreneur Step</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="logo">Entrepreneur Step</div>
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="deconnexion.php">Déconnexion</a>
    </nav>
</header>

<div class="container">
    <h1>Ressources utiles pour vos projets</h1>

    <?php foreach ($ressources as $domaine => $listeRessources): ?>
        <section class="domaine">
            <h2><?= htmlspecialchars($domaine) ?></h2>
            <?php foreach ($listeRessources as $res): ?>
                <div class="légalité">
                    <h3><?= htmlspecialchars($res['titre']) ?></h3>
                    <p><?= htmlspecialchars($res['desc']) ?></p>
                    <a href="<?= htmlspecialchars($res['lien']) ?>" target="_blank">Voir les légalités</a>
                </div>
            <?php endforeach; ?>
        </section>
    <?php endforeach; ?>
</div>
</body>
</html>
