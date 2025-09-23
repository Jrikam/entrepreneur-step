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
        [
            'titre' => 'Créer légalement son entreprise artisanale',
            'lien' => 'https://entreprendre.service-public.fr/vosdroits/F32887',
            'desc' => 'Découvrez les démarches administratives nécessaires pour déclarer et légaliser une activité artisanale en France.',
        ],
        [
            'titre' => 'Formalités légales pour l’artisanat',
            'lien' => 'https://www.artisanat.fr/nous-connaitre/vous-accompagner/creation-entreprise',
            'desc' => 'Informez-vous sur les obligations légales liées à l’exercice d’une activité artisanale, y compris les qualifications requises et les démarches à suivre.',
        ],
    ],
    'Audiovisuel' => [
        [
            'titre' => 'Droits d’auteur et audiovisuel',
            'lien' => 'https://entreprendre.service-public.fr/vosdroits/F22667',
            'desc' => 'Comprenez la législation française relative aux œuvres audiovisuelles, y compris les droits des auteurs et les obligations des producteurs.',
        ],
        [
            'titre' => 'Vidéos libres de droits',
            'lien' => 'https://entreprendre.service-public.fr/vosdroits/F22388',
            'desc' => 'Accédez à des ressources de vidéos libres de droits pour vos projets audiovisuels, en respectant les normes légales en vigueur.',
        ],
    ],
    'Informatique' => [
        [
            'titre' => 'Protection des données personnelles (RGPD)',
            'lien' => 'https://www.cnil.fr/fr/reglement-europeen-protection-donnees',
            'desc' => 'Apprenez les principes du Règlement Général sur la Protection des Données (RGPD) et comment les appliquer dans vos projets numériques.',
        ],
        [
            'titre' => 'Sécurité des données personnelles',
            'lien' => 'https://www.cnil.fr/fr/guide-de-la-securite-des-donnees-personnelles',
            'desc' => 'Conseils et bonnes pratiques pour protéger légalement les données personnelles dans vos projets numériques.',
        ],
    ],
    'Marketing' => [
        [
            'titre' => 'Réglementation publicitaire en ligne',
            'lien' => 'https://entreprendre.service-public.fr/vosdroits/F31228',
            'desc' => 'Découvrez les règles encadrant la publicité en ligne en France, y compris la transparence et la non-tromperie.',
        ],
        [
            'titre' => 'Protection des consommateurs et influenceurs',
            'lien' => 'https://www.economie.gouv.fr/guide-bonne-conduite-influenceurs-createurs-contenu',
            'desc' => 'Comprenez les obligations légales pour la communication marketing et les contenus sponsorisés.',
        ],
    ],
    'Commerce' => [
        [
            'titre' => 'Créer légalement sa société',
            'lien' => 'https://entreprendre.service-public.fr/vosdroits/F23455',
            'desc' => 'Suivez les étapes légales pour créer une société en France, du choix du statut juridique à l’immatriculation.',
        ],
        [
            'titre' => 'Fiscalité et obligations légales',
            'lien' => 'https://entreprendre.service-public.fr/vosdroits/F23571',
            'desc' => 'Informez-vous sur les obligations fiscales et légales liées à l’exploitation d’un commerce en France.',
        ],
    ],
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

    <?php foreach ($legalite as $domaine => $listeRessources): ?>
    <section class="domaine">
        <h2><?= htmlspecialchars($domaine) ?></h2>
        <?php foreach ($listeRessources as $res): ?>
            <div class="legalite">
                <h3><?= htmlspecialchars($res['titre']) ?></h3>
                <p><?= htmlspecialchars($res['desc']) ?></p>
                <a href="<?= htmlspecialchars($res['lien']) ?>" target="_blank">Lien ↗</a>
            </div>
        <?php endforeach; ?>
    </section>
<?php endforeach; ?>
</div>
</body>
</html>
