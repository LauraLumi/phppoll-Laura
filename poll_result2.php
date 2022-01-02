<?php
//See poll_result2.php on selle jaoks kui kasutaja tahab vaadata lihtsalt tulemusi (ilma ip kontrollita)
require 'templates/header.php';

$msg = ''; // Kuvatav veateade

if (isset($_GET['id']) && $_GET['id'] != '') { // URL-is on küsimuse id ja see ei ole tühi
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
        $msg = 'Viga andmebaasist lugemisel: ' . ('<br /> SQL: <strong>' . $sql . '</strong><br />' . $error->getMessage());
    }

    if ($total == 1) { // URL-is oleva id-ga küsimus on andmebaasis
        try {
            $connection = new PDO($dsn, USERNAME, PASSWORD, $options);
            $connection->exec('SET NAMES utf8');
            $sql = 'SELECT q.id_q, q.question, q.answer_1, q.answer_2, q.answer_3, a.answer FROM questions AS q LEFT JOIN answers AS a USING (id_q) WHERE q.id_q =' . $id;
            $statement = $connection->prepare($sql);
            $statement->execute();
            $result = $statement->fetchAll();
            // show($result); # Testiks
        } catch (PDOException $error) {
            $msg = 'Viga andmebaasist lugemisel: ' . ('<br /> SQL: <strong>' . $sql . '</strong><br />' . $error->getMessage());
        }
    } else {
        $msg = 'URL-is oleva id-ga kuulutust ei ole andmebaasis';
    }
} else {
    $msg = 'URL-is küsimuse id puudu';
}
# Kuvatakse teade
if ($msg != '') {
    ?>
        <div class = "content delete">
            <h2><?php echo $msg ?></h2>
            <div class="yesno">
            <a onclick="location.href='index.php'">Avalehele</a>
            </div>
        </div>
    <?php
}

if ($msg == '') { // Kui ühtegi veateadet ei ole, siis loeb kokku vastanute koguarvu ja kõikide vastusevariantide arvud ning kuvatakse tulemused
    $total = 0;
    $answer_1 = 0;
    $answer_2 = 0;
    $answer_3 = 0;

    for ($i = 0; $i < count($result); $i++) {
        if ($result[$i]['answer'] == 1) {
            $answer_1++;
            $total++;
        }
        if ($result[$i]['answer'] == 2) {
            $answer_2++;
            $total++;
        }
        if ($result[$i]['answer'] == 3) {
            $answer_3++;
            $total++;
        }
    }
    # Arvutame kui suur on iga vastusevariandi osakaal vastuste koguhulgast (%). Kui vastanuid pole (0-ga jagamine) siis tulemuseks 0-d
    $rel_answer_1 = ($total != 0) ? round($answer_1 / $total * 100) : 0;
    $rel_answer_2 = ($total != 0) ? round($answer_2 / $total * 100) : 0;
    $rel_answer_3 = ($total != 0) ? round($answer_3 / $total * 100) : 0;
?>

    <div class="content poll-result">
        <h2><?php echo ($result[0]['question']); ?></h2>
        <p> Küsitluses on osalenud <?php echo $total ?> inimest.</p>
        <div class="wrapper">
            <div class="poll-question">
                <p><?php echo ($result[0]['answer_1']); ?> <?php echo ($rel_answer_1); ?> % (<?php echo $answer_1;
                        echo ($answer_1 == 1) ? ' vastaja' : ' vastajat' ?>)</p>
                <div class="result-bar" style= "width:<?=@round(($answer_1/$total)*100)?>%">
                    <?=@round(($answer_1/$total)*100)?>%
                </div>
                <p><?php echo ($result[0]['answer_2']); ?> <?php echo ($rel_answer_2); ?> % (<?php echo $answer_2;
                        echo ($answer_2 == 1) ? ' vastaja' : ' vastajat' ?>) </p>
                <div class="result-bar" style= "width:<?=@round(($answer_2/$total)*100)?>%">
                    <?=@round(($answer_2/$total)*100)?>%
                </div>
                <?php if (strlen(trim($result[0]['answer_3'])) > 0) {
                ?>
                <p><?php echo ($result[0]['answer_3']); ?> <?php echo ($rel_answer_2); ?> % (<?php echo $answer_3;
                        echo ($answer_2 == 1) ? ' vastaja' : ' vastajat' ?>) </p>
                <div class="result-bar" style= "width:<?=@round(($answer_3/$total)*100)?>%">
                    <?=@round(($answer_3/$total)*100)?>%
                </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
<?php
}
?>

<?php require 'templates/footer.php'; ?>