<?php
require 'templates/header.php';
require 'pagination.php';

$msg = ''; // Kuvatav veateade

if (!isset($_GET['id'])) { // URL-is ühegi küsimuse id-d ei ole
} else {  // URL-is on olemas id, seega kasutaja tahab küsimuse staatust muuta
    $id = $_GET['id'];

    # Andmebaasiühendus, kas sellise id-ga küsimus on andmebaasis olemas
    try {
        $connection = new PDO($dsn, USERNAME, PASSWORD, $options);
        $connection->exec('SET NAMES utf8');
        $sql = 'SELECT COUNT(*) AS total FROM questions WHERE id_q =' . $id;
        $statement = $connection->prepare($sql);
        $statement->execute();
        $total = $statement->fetch(PDO::FETCH_ASSOC);
        $total = $total['total'];
        // show($total); # Testiks
    } catch (PDOException $error) {
        $msg = 'Viga andmebaasist lugemisel!' . ('<br /> SQL: <strong>' . $sql . '</strong><br />' . $error->getMessage());
    }

    if ($total == 1) { // URL-is oleva id-ga küsimus on andmebaasis
        if (isset($_GET['status']) && $_GET['status'] != '') { // URL-is on staatus ja selle väärtus olemas

            if ($_GET['status'] == 0) { // Kasutaja tahab "aktiivse" staatuse muuta "ootel"
                # Andmebaasiühendus, staatuse muutmine tabelis
                try {
                    $connection = new PDO($dsn, USERNAME, PASSWORD, $options);
                    $connection->exec('SET NAMES utf8');
                    $sql = 'UPDATE questions SET status = 0 WHERE id_q =' . $id;
                    $statement = $connection->prepare($sql);
                    $statement->execute();
                    $msg = 'Küsimuse ' . $id . ' staatus muudetud "ootel"!';
                } catch (PDOException $error) {
                    $msg = 'Viga andmebaasi kirjutamisel!' . ('<br /> SQL: <strong>' . $sql . '</strong><br />' . $error->getMessage());
                }
            } else if ($_GET['status'] == 1) { // Kasutaja tahab "ootel" staatuse muuta "aktiivne"
                # Andmebaasiühendus, staatuse muutmine tabelis
                try {
                    $connection = new PDO($dsn, USERNAME, PASSWORD, $options);
                    $connection->exec('SET NAMES utf8');
                    $sql = 'UPDATE questions SET status = 0 WHERE status = 1; UPDATE questions SET status = 1 WHERE id_q =' . $id;
                    $statement = $connection->prepare($sql);
                    $statement->execute();
                    $msg = 'Küsimuse ' . $id . ' staatus muudetud "aktiivne"!';
                } catch (PDOException $error) {
                    $msg = 'Viga andmebaasi kirjutamisel!' . ('<br /> SQL: <strong>' . $sql . '</strong><br />' . $error->getMessage());
                }
            } else { // URL-il staatuse väärtus midagi muud kui 0 või 1
                $msg = 'URL-is staatus vigane!';
            }
        } else {
            $msg = 'URL-is puudub staatus!';
        }
    } else { 
        $msg = 'URL-is oleva id-ga kuulutust ei ole andmebaasis!';
    }
}

# Andmebaasiühendus, loetakse tabelist kõik küsimused
try {
    $connection = new PDO($dsn, USERNAME, PASSWORD, $options);
    $connection->exec('SET NAMES utf8');
    $sql = 'SELECT * FROM questions ORDER BY id_q DESC LIMIT ' . $start . ', ' . MAXPERPAGE; // Järjestab viimasena lisatud algusesse
    $statement = $connection->prepare($sql);
    $statement->execute();
    $questions = $statement->fetchAll();
} catch (PDOException $error) {
    $msg = 'Viga andmebaasist lugemisel!' . ('<br /> SQL: <strong>' . $sql . '</strong><br />' . $error->getMessage());
}
?>


<!-- Kuvab teadet-->
<?php
if ($msg != '') {
?>
    <div class="content home">
        <h2 class=""><?php echo $msg ?></h1>
    </div>
<?php
}
?>
<!-- Tabel -->
<div class="content home">
    <div class="">
        <div class="">
            <h2>Küsitlused</h2>
            <button class="create-poll" onclick="location.href='question_create_new.php'">Lisa uus küsimus</button>
            <p>Siin on küsitluste haldamise lehekülg ja nimekiri küsitlustest, mida saab aktiveerida päevaküsimuseks.</p>
            <table class="">
                <thead class="">
                    <tr>
                        <th>Jrk nr</th>
                        <th>Küsimuse ID</th>
                        <th>Küsimus</th>
                        <th>Vastus 1</th>
                        <th>Vastus 2</th>
                        <th>Vastus 3</th>
                        <th>Loodud</th>
                        <th>Staatus</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $jrk = $start + 1; ?>
                    <?php foreach ($questions as $row) : ?>
                        <tr>
                            <td> <?php echo $jrk ?> </td>
                            <td> <?php echo $row['id_q']; ?> </td>
                            <td> <?php echo escape($row['question']); ?> </td>
                            <td> <?php echo escape($row['answer_1']); ?> </td>
                            <td> <?php echo escape($row['answer_2']); ?> </td>
                            <td> <?php echo escape($row['answer_3']); ?> </td>
                            <td> <?php echo escape($row['created']); ?> </td>
                            <!-- Vastavalt küsimuse staatusele kuvatakse tabelis erinev teade "radiobutton" olemasoleva staatuse muutmiseks -->
                            <?php if ($row['status'] == 0) { ?>
                                <td class="actions">

                                    <label for="status_0"> Ootel / Tee aktiivseks</label>
                                    <input id="status_0" type="radio" name="status" onclick="location.href='questions_view.php?id=<?php echo $row['id_q']; ?>&status=1'"><br>

                                    <a href="poll_result.php?id=<?php echo $row['id_q']; ?>" class="view"> <i class="fas fa-eye fa-xs"></i> </a>
                                    <a href="question_delete.php?id=<?php echo $row['id_q']; ?>" onclick="return confirm('Kas soovid küsimust: <?php echo $row['question']; ?>  kustutada?')" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                                </td>
                            <?php } elseif ($row['status'] == 1) { ?>
                                <td class="actions">

                                    <label for="status_1"> Aktiivne / pane ootele </label>
                                    <input id="status_1" type="radio" name="status" checked onclick="location.href='questions_view.php?id=<?php echo $row['id_q']; ?>&status=0'"><br>

                                    <a href="poll_result.php?id=<?php echo $row['id_q']; ?>" class="view"> <i class="fas fa-eye fa-xs"></i> </a>
                                    <a href="question_delete.php?id=<?php echo $row['id_q']; ?>" onclick="return confirm('Kas soovid küsimust: <?php echo $row['question']; ?>  kustutada?')" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                                </td>
                            <?php } else { ?>
                                <td class="actions">

                                    <label for="status_3" > Staatus vigane / pane ootele </label>
                                    <input id="status_3" type="radio" name="status" onclick="location.href='questions_view.php?id=<?php echo $row['id_q']; ?>&status=0'"><br>

                                    <a href="poll_result.php?id=<?php echo $row['id_q']; ?>" class="view"> <i class="fas fa-eye fa-xs"></i> </a>
                                    <a href="question_delete.php?id=<?php echo $row['id_q']; ?>" onclick="return confirm('Kas soovid küsimust: <?php echo $row['question']; ?> kustutada?')" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                                </td>
                            <?php
                            } ?>
                        </tr>
                    <?php
                        $jrk++;
                    endforeach;
                    ?>
                </tbody>
            </table>
            <?php require 'templates/pagination.php'; ?>
            
        </div>
    </div>
</div>

<?php require 'templates/footer.php'; ?>