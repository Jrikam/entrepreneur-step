<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Accueil - Entrepreneur Step</title>

    <!-- Import Google Fonts pour une belle écriture -->
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Barre jaune */
        header {
            width: 100%;
            background-color: #FFD700; /* jaune */
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            box-sizing: border-box;
        }

        /* Logo image */
        header img.logo {
            height: 40px;
        }

        /* Nom du projet avec une belle police */
        header .nom-projet {
            font-family: 'Pacifico', cursive;
            font-size: 28px;
            color: #333;
            margin-left: 10px;
        }

        /* Regrouper logo + texte */
        .gauche {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Menu hamburger */
        header .menu {
            font-size: 26px;
            cursor: pointer;
        }

        /* Pitch / slogan */
        .pitch {
            margin: 15px 20px;
            font-size: 18px;
            text-align: center;
            color: #333;
        }

        /* Domaines */
        .domaines {
            display: flex;
            flex-direction: column;
            gap: 15px;
            width: 80%;
            max-width: 400px;
            margin-top: 20px;
        }

        .domaine {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            font-size: 18px;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: background-color 0.3s;
            text-decoration: none;
            color: #000;
        }

        .domaine:hover {
            background-color: #f0f0f0;
        }

        .gauche {
    display: flex;
    align-items: center;
    gap: 15px;
}

/* Groupe des liens Connexion / S'inscrire */
.gauche .auth {
    margin-left: 30px; /* espace entre nom projet et liens */
}

.gauche .auth a {
    margin-left: 10px;
    text-decoration: none;
    color: #000;
    font-weight: bold;
}

    </style>
</head>
<body>

    <!-- Header avec logo + nom projet + menu -->
    <header>
        <div class="gauche">
            <img src ="image/logo.png"alt="Logo" class="logo">
            <div class="nom-projet">Entrepreneur Step</div>
            <div class="auth">
            <a href="connexion.php">Connexion</a>
            <a href="inscription.php">S'inscrire</a>
        </div>
        </div>
        <div class="menu">☰</div>
    </header>

    <!-- Pitch -->
    <div class="pitch"> Entrepreneur Step c'est une application qui vous guide pas à pas sur la création de l'application de vos reves. Chaque idée,chaque étape vous guide vers sa réalisation </div>

    <!-- Domaines -->
    <div class="domaines">
        <a href="domaine_informatique.php" class="domaine">Informatique</a>
        <a href="domaine_commerce.php" class="domaine">Commerce</a>
        <a href="domaine_artisanat.php" class="domaine">Artisanat</a>
        <a href="domaine_marketing.php" class="domaine">Marketing</a>
        <a href="domaine_audiovisuel.php" class="domaine">Audiovisuel</a>
    </div>

</body>
</html>
