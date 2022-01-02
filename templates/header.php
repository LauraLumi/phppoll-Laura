
<?php
require 'config/config.php';
require 'config/common.php';
# Andmebaasiühendus aktiivse küsimuse leidmiseks (staatus = 1)
$msg = ''; // Kuvatav veateade
try {
    $connection = new PDO($dsn, USERNAME, PASSWORD, $options);
    $connection->exec('SET NAMES utf8');
    $sql = 'SELECT * FROM questions  WHERE status = 1';
    $statement = $connection->prepare($sql);
    $statement->execute();
    $question = $statement->fetch(PDO::FETCH_ASSOC);
    //show($question); # Testiks
    $id = $question['id_q']; // Aktiivse küsimuse id
} catch (PDOException $error) {
    $msg = 'Viga andmebaasist lugemisel: ' . ('<br /> SQL: <strong>' . $sql . '</strong><br />' . $error->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <title>Küsitlus</title>
</head>
<body>
    <nav class="navtop">
        <div>
            <h1>Hääletamise ja küsitluste tegemise lehekülg</h1>
            <a href="index.php"><i class="far fa-question-circle"></i>Päevaküsimus</a>
            <a href="poll_result2.php?id=<?php echo $question['id_q']; ?>"><i class="fas fa-chart-pie"></i>Päevatulemused</a>
            <a href="questions_view.php"><i class="fas fa-poll-h"></i>Küsitlused</a>
        </div>
    </nav>