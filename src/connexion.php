<?php
session_start();
require_once 'pdo.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $mot_de_passe_base = $user['mot_de_passe'];

        // Vérification MD5 (ancienne version)
        if ($mot_de_passe_base === md5($password)) {
            // Rehash avec password_hash pour sécuriser
            $nouveau_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmtUpdate = $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE id = ?");
            $stmtUpdate->execute([$nouveau_hash, $user['id']]);

            $_SESSION['user_id'] = $user['id'];

        } elseif (password_verify($password, $mot_de_passe_base)) {
            $_SESSION['user_id'] = $user['id'];
        } else {
            $error = "Email ou mot de passe incorrect.";
        }

        if (empty($error)) {
            if (!empty($user['role']) && $user['role'] === 'admin') {
                header("Location: admin.php");
                exit;
            }

            $stmtProjet = $pdo->prepare("SELECT COUNT(*) FROM projets WHERE user_id = ?");
            $stmtProjet->execute([$user['id']]);
            $hasProjet = $stmtProjet->fetchColumn() > 0;

            if ($hasProjet) {
                header("Location: dashboard.php");
            } else {
                $domaine = strtolower($user['domaine'] ?? 'artisanat');
                $formPage = "domaine_{$domaine}.php";
                header("Location: $formPage");
            }
            exit;
        }
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
