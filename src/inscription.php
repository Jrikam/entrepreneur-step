<?php
session_start();
require_once 'pdo.php';

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // On vérifie que tous les champs existent
    $pseudo = isset($_POST['pseudo']) ? trim($_POST['pseudo']) : '';
    $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($pseudo && $nom && $email && $password) {
        try {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO utilisateurs (pseudo, nom, email, mot_de_passe) VALUES (?, ?, ?, ?)");
            $stmt->execute([$pseudo, $nom, $email, $password_hash]);

            $_SESSION['user_id'] = $pdo->lastInsertId();
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $erreur = "Erreur : " . $e->getMessage();
        }
    } else {
        $erreur = "Veuillez remplir tous les champs.";
    }
}
?>

<h2>Inscription</h2>

<?php if($erreur) echo "<p style='color:red;'>$erreur</p>"; ?>

<form method="post">
    <input type="text" name="pseudo" placeholder="Pseudo" required><br>
    <input type="text" name="nom" placeholder="Nom" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Mot de passe" required><br>
    <button type="submit">S'inscrire</button>
</form>
 <link rel="stylesheet" href="style.css">