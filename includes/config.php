<?php
ob_start();
session_start();

$dsn = "mysql:dbname=connexion;host=localhost";
$username = 'root';
$password = '';
try {
    $connection = new PDO($dsn, $username, $password);
    $connection->SetAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo "ERREUR - " . $e->getMessage();
}