<?php
require('config/config.php');

try {
    $connection = new PDO('mysql:host='.HOST, USERNAME, PASSWORD, $options);
    $connection->exec('SET NAMES utf8'); // Täpitähtede jaoks
    $sql = file_get_contents('config/init.sql'); // SQL loeb sisu muutujasse
    $connection->exec($sql); // Andmebaasi tegemise jaoks

    echo '<p>Sussess!.</p>';
    echo '<a href="index.php">Avalehele</a>';

} catch (PDOException $error) {
    echo $error->getMessage(); // Veateade
}
?>