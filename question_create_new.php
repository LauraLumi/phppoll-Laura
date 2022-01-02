<?php
require 'templates/header.php'; 
# Tagasiside ja veateated
$msg = ''; // Kuvatav veateade

if (isset($_POST['submit'])) { // Kas Submit nuppu on vajutatud
    //require 'config/config.php';
    //require 'config/common.php';

    # Vormilt saadud andmete kontroll
    if (strlen(trim($_POST['question'])) <= 3) {
        $msg = 'Viga! Küsimus on tühi või liiga lühike.';
    } elseif (substr($_POST['question'], -1) != '?') {
        $msg = 'Viga! Küsimus peab lõppema ? -ga.';
    } elseif (strlen(trim($_POST['answer_1'])) == 0) {
        $msg = 'Viga! Vastus 1 peab olema täidetud.';
    } elseif (strlen(trim($_POST['answer_2'])) == 0) {
        $msg = 'Viga! Vastus 2 peab olema täidetud.';
    } else { // Kõik korras - võetakse andmed vormilt
        # Andmebaasiüühendus, tabelisse lisatakse uus küsimus
        try {
            $connection = new PDO($dsn, USERNAME, PASSWORD, $options);
            $connection->exec('SET NAMES utf8'); // SQL lause, et täpitähed oleks korrektselt

            $new_question = array(
                'question' => $_POST['question'],
                'answer_1'  => $_POST['answer_1'],
                'answer_2'  => $_POST['answer_2'],
                'answer_3'  => $_POST['answer_3']
            );

            $sql = sprintf(
                'INSERT INTO %s (%s) VALUES (%s)',
                'questions',
                implode(', ', array_keys($new_question)),
                ':' . implode(', :', array_keys($new_question))
            );
            //show($sql); # Testiks
            //show($new_question);
            $statement = $connection->prepare($sql);
            $statement->execute($new_question);
            $msg = 'Uus küsimus edukalt salvestatud!';
        } catch (PDOException $error) {
            $msg = 'Viga andmebaasist kirjutamisel: ' . ('<br /> SQL: <strong>' . $sql . '</strong><br />' . $error->getMessage());
        }
    }
}
?>



<div class="content update">
    <?php if ($msg): ?>
        <p><?=$msg?></p>
    <?php endif; ?>
	<h2>Loo küsitlus</h2>
    <form action="" method="post">
        <label for="question">Küsimus</label>
        <input type="text" name="question" id="question" value="<?php echo (isset($_POST['submit'])) ? $_POST['question'] : null; ?>" placeholder="Pealkiri" required> 
        <label for="answer_1">Vastus 1</label>
        <input type="text" name="answer_1" id="answer_1" value="<?php echo (isset($_POST['submit'])) ? $_POST['answer_1'] : null; ?>" placeholder="Esimene vastusevariant" required>
        <label for="answer_2">Vastus 2</label>
        <input type="text" name="answer_2" id="answer_2" value="<?php echo (isset($_POST['submit'])) ? $_POST['answer_2'] : null; ?>" placeholder="Teine vastusevariant" required>
        <label for="answer_3">Vastus 3</label>
        <input type="text" name="answer_3" id="answer_3" value="<?php echo (isset($_POST['submit'])) ? $_POST['answer_3'] : null; ?>" placeholder="Kolmas vastusevariant - pole kohustuslik">
        <input type="submit" name="submit" value="Loo küsitlus">
    </form>

</div>

<?php require 'templates/footer.php'; ?>