<?php
# Andmebaasiühendus tabelis olevate küsimuste arvu kokkulugemiseks
try {
    $connection = new PDO($dsn, USERNAME, PASSWORD, $options);
    $connection->exec('SET NAMES utf8');
    $sql = 'SELECT COUNT(*) AS total FROM questions';
    $statement = $connection->prepare($sql);
    $statement->execute();
    $total = $statement->fetch(PDO::FETCH_ASSOC);
    $total = $total['total'];
} catch (PDOException $error) {
    $msg = 'Viga andmebaasist lugemisel: <br /> ' . ($error->getMessage());
}

// Tuvasta URL-is lehekülje number
if($total > 0) {  // Tabelis on kirjeid
    if(isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }
} else {
    $page = 1;
}

$totalRows = $total; // Kirjeid kokku  MAXPERPAGE; // Mitu kirjet lehele (default = 8 -> config.php failis)
$pageCount = (int)ceil($totalRows / MAXPERPAGE); // Mitu lehekülge kokku
if(empty($page) || $page < 1 || $page > $pageCount) {
    $page = 1;
}

$nextStart = $page * MAXPERPAGE;
$start = $nextStart - MAXPERPAGE;

//Sirvimise loogika jaoks. Millised nupud joonistatakse
if($pageCount < MAX_BUTTONS) {
    $btnStart = 1;
    $btnEnd = $pageCount;
} else { 
    // Millise numbriga algavad nupud
    $btnStart = $page - (int)(MAX_BUTTONS / 2);
    if($btnStart < 2) {
        $btnStart = 1;
    }
    // Millise numbriga nupud lõpevad
    $btnEnd = $btnStart	+ MAX_BUTTONS - 1;
    if($btnEnd > $pageCount) {
        $btnEnd = $pageCount;
    }
    // Start nupp tehakse viimaste lehtede puhul ringi
    if($btnEnd > MAX_BUTTONS) {
        $btnStart = $btnEnd - MAX_BUTTONS + 1;
    }
}

?>
