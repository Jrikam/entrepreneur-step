<?php
// pdo.php : connexion PDO à la base MySQL

$host = 'db';              // Nom du service MySQL dans Docker
$db   = 'entrepreneur_db'; // Nom de ta base
$user = 'entrepreneur_user';
$pass = 'jade';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Erreurs en exception
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Récupération en tableau associatif
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Sécurité
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // En cas d'erreur, affichage et arrêt du script
    die('Erreur de connexion à la base : ' . $e->getMessage());
}
