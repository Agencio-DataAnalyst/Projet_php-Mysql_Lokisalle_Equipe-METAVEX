<?php
// Connexion à la BDD
$pdo = new PDO('mysql:host=localhost;dbname=lokisalle', 'root', '', array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
));

// Session
session_start();

// Chemin (à adapter selon ton dossier)
define("RACINE_SITE", "/lokisalle/");

// Inclusion des fonctions
require_once("functions.inc.php");
?>