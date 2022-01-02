<?php
require 'templates/header.php';

$msg = ''; // Kuvatav veateade

if (isset($_GET['id']) && $_GET['id'] != '') { // Kas URL-il on küsimuse id olemas
    # Andmebaasiühendus, küsimuse kustutamine
    try {
        $connection = new PDO($dsn, USERNAME, PASSWORD, $options);
        $id = $_GET['id']; // URL realt id
        $sql = 'DELETE FROM questions WHERE id_q = :id';
        $statement = $connection->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute();

        $msg = 'Küsimus nr: ' . $id . ' edukalt kustutatud!';
    } catch (PDOException $error) {
        $msg = 'Viga andmebaasi kirjutamisel: <br />' . ($error->getMessage());
    }
} else { 
    $msg = 'URL-is küsimuse id puudu';
}
?>
<div class="content delete">
    <div>
        <h2><?php echo $msg; ?></h2>
        <div class="content home">
            <button class="create-poll" onclick="location.href='questions_view.php'">Tagasi</button>
        </div>
    </div>
</div>

<?php require 'templates/footer.php'; ?>