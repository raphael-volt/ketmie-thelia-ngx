<?php
include 'client/config_thelia.php';
try {
    $dsn = "mysql:host=" . THELIA_BD_HOST . "; dbname=" . THELIA_BD_NOM . ";";
    $pdo = new PDO($dsn, THELIA_BD_LOGIN, THELIA_BD_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->query("SET NAMES UTF8");
    die("PDO OK");
} catch (PDOException $e) {
    // header('HTTP/1.1 503 Service Temporarily Unavailable');
    // header('Status: 503 Service Temporarily Unavailable');
    
    die("Connexion à la base de données impossible<br/><pre>" . $e->getTraceAsString() . "</pre>");
}