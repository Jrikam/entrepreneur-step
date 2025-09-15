<?php
session_start();
require_once 'pdo.php';

$error = "";

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Récupérer l'utilisateur
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['mot_de_passe'])) {
        $_SESSION['user_id'] = $user['id'];

        // Vérifier si l'utilisateur a déjà un projet
        $stmtProjet = $pdo->prepare("SELECT COUNT(*) FROM projets WHERE user_id = ?");
        $stmtProjet->execute([$user['id']]);
        $hasProjet = $stmtProjet->fetchColumn() > 0;

        // Déterminer la page de redirection
        if ($hasProjet) {
            header("Location: dashboard.php");
        } else {
            // Récupérer le domaine de l'utilisateur
            $domaine = strtolower($user['domaine']); // 'artisanat', 'commerce', etc.
            $formPage = "domaine_{$domaine}.php";
            header("Location: $formPage");
        }
        exit;
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Entrepreneur Step</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="connexion-container">
        <h2>Connexion</h2>
        <form method="post">
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Mot de passe" required><br>
            <button type="submit">Se connecter</button>
        </form>
        <?php if(!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <p>Pas encore inscrit ? <a href="inscription.php">Créez un compte</a></p>
    </div>
</body>
</html>
