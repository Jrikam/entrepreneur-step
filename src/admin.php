<?php
session_start();
require_once 'pdo.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

// Vérifie rôle admin
$stmt = $pdo->prepare("SELECT role FROM utilisateurs WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || $user['role'] !== 'admin') {
    header("Location: connexion.php");
    exit();
}

// Tous les utilisateurs
$stmt = $pdo->prepare("SELECT id, nom, email, role FROM utilisateurs");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Suivi complet des progressions avec projet et domaine
$stmt = $pdo->prepare("
    SELECT p.id_user, u.nom AS nom_user, p.etape, p.statut, p.action_text, p.date_mise_a_jour,
           hp.nom_projet, d.nom AS nom_domaine
    FROM progression p
    JOIN utilisateurs u ON p.id_user = u.id
    JOIN historique_projets hp ON p.id_projet = hp.id_projet
    JOIN domaines d ON p.id_domaine = d.id
    ORDER BY u.nom, hp.id_projet, d.id, p.etape
");
$stmt->execute();
$progressions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fonction pour afficher le statut
function afficherStatut($statut, $action_text) {
    return (!empty($action_text) && $statut === 'en cours') ? '✔ Validée' : htmlspecialchars($statut);
}

// Fonction pour formater la date
function formatDate($date) {
    return date('d/m/Y H:i', strtotime($date));
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard - Entrepreneur Step</title>
<link rel="stylesheet" href="style.css">
<style>
body { font-family: Arial, sans-serif; margin: 20px; }
header { display: flex; justify-content: space-between; align-items: center; }
h1 { margin: 0; }
table { border-collapse: collapse; width:100%; margin-top:20px; }
th, td { border:1px solid #ccc; padding:8px; text-align:left; }
th { background:#eee; }
tr:nth-child(even) { background: #f9f9f9; }
tr:hover { background: #f1f1f1; }
nav a { text-decoration: none; color: #007BFF; }
nav a:hover { text-decoration: underline; }
</style>
</head>
<body>
<header>
<h1>Dashboard Admin</h1>
<nav><a href="deconnexion.php">Déconnexion</a></nav>
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

<h2>Suivi des progressions par projet et domaine</h2>
<table>
<tr>
<th>Utilisateur</th>
<th>Projet</th>
<th>Domaine</th>
<th>Étape</th>
<th>Statut</th>
<th>Dernière mise à jour</th>
</tr>
<?php foreach($progressions as $p): ?>
<tr>
<td><?= htmlspecialchars($p['nom_user']) ?></td>
<td><?= htmlspecialchars($p['nom_projet']) ?></td>
<td><?= htmlspecialchars($p['nom_domaine']) ?></td>
<td><?= htmlspecialchars($p['etape']) ?></td>
<td><?= afficherStatut($p['statut'], $p['action_text']) ?></td>
<td><?= formatDate($p['date_mise_a_jour']) ?></td>
</tr>
<?php endforeach; ?>
</table>

</body>
</html>
