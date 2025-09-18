<?php
session_start();
require_once 'pdo.php';

// Vérifie que l'utilisateur est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: connexion.php");
    exit();
}

// Récupère tous les utilisateurs
$users = $pdo->query("SELECT id, nom, email, role FROM utilisateurs")->fetchAll(PDO::FETCH_ASSOC);

// Récupère la progression de tous les utilisateurs
$progressions = $pdo->query("
    SELECT p.id_user, u.nom, p.etape, p.statut, p.date_mise_a_jour 
    FROM progression p
    JOIN utilisateurs u ON p.id_user = u.id
    ORDER BY u.nom, p.id_progression
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard - Entrepreneur Step</title>
<link rel="stylesheet" href="style.css">
<style>
table { border-collapse: collapse; width:100%; margin-top:20px; }
th, td { border:1px solid #ccc; padding:8px; text-align:left; }
th { background:#eee; }
</style>
</head>
<body>
<header>
<h1>Dashboard Admin</h1>
<nav>
<a href="deconnexion.php">Déconnexion</a>
</nav>
</header>

<h2>Liste des utilisateurs</h2>
<table>
<tr>
<th>ID</th>
<th>Nom</th>
<th>Email</th>
<th>Role</th>
</tr>
<?php foreach($users as $u): ?>
<tr>
<td><?= htmlspecialchars($u['id']) ?></td>
<td><?= htmlspecialchars($u['nom']) ?></td>
<td><?= htmlspecialchars($u['email']) ?></td>
<td><?= htmlspecialchars($u['role']) ?></td>
</tr>
<?php endforeach; ?>
</table>

<h2>Suivi des progressions</h2>
<table>
<tr>
<th>Utilisateur</th>
<th>Étape</th>
<th>Statut</th>
<th>Dernière mise à jour</th>
</tr>
<?php foreach($progressions as $p): ?>
<tr>
<td><?= htmlspecialchars($p['nom']) ?></td>
<td><?= htmlspecialchars($p['etape']) ?></td>
<td><?= htmlspecialchars($p['statut']) ?></td>
<td><?= htmlspecialchars($p['date_mise_a_jour']) ?></td>
</tr>
<?php endforeach; ?>
</table>

</body>
</html>

INSERT INTO utilisateurs (nom, email, mot_de_passe, role) 
VALUES ('Admin', 'admin76000@gmail.com', MD5('MotDePasse123'), 'admin');