<?php
//See poll_result.php on selle jaoks kui kasutaja on vastanud küsimusele ja kontrollib IP-d et uuesti ei saaks vastata
require 'templates/header.php';

$msg = ''; // Kuvatav veateade

$ip_error = false; // Abimuutuja IP kontrolliks

// Vastaja IP-aadress muutujasse $ip
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
// echo $ip; # Testiks

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
        
        if (isset($_GET['answer']) && $_GET['answer'] != '') { // URL-is on vastuse nr ja see ei ole tühi, seega siseneb vastaja
            $answer = $_GET['answer'];
            // show($id);
            // show($answer);

                    # Andmebaasiühendus, IP aadressi kontrolliks
         try {
            $connection = new PDO($dsn, USERNAME, PASSWORD, $options);
            $connection->exec('SET NAMES utf8');
            $sql = 'SELECT IP FROM answers WHERE id_q =' . $id;
            $statement = $connection->prepare($sql);
            $statement->execute();
            $answers = $statement->fetchAll();
            // show($answers);
        } catch (PDOException $error) {
            $msg = 'Viga andmebaasist lugemisel: ' . ('<br /> SQL: <strong>' . $sql . '</strong><br />' . $error->getMessage());
        }

        for ($i = 0; $i < count($answers); $i++) { // Kontroll, kas vastaja IP aadress on juba selle küsimuse vastuste juures olemas
            if ($answers[$i]['IP'] == $ip) {
                $ip_error = true;
            }
        }
        if ($ip_error == true) { // Vastaja IP aadress leiti tabelist selle küsimuse juurest
            $msg = 'Sellelt IP aadressilt on juba vastatud. Vastata saab vaid üks kord!';
        } else {
                # Andmebaasiühendus. Kas sellel küsimusel on kolmas vastusevariant olemas
                try {
                    $connection = new PDO($dsn, USERNAME, PASSWORD, $options);
                    $connection->exec('SET NAMES utf8');
                    $sql = 'SELECT answer_3 FROM questions WHERE id_q =' . $id;
                    $statement = $connection->prepare($sql);
                    $statement->execute();
                    $answer_3_Ok = $statement->fetch(PDO::FETCH_ASSOC);
                    $answer_3_Ok = $answer_3_Ok['answer_3'];
                    // show($answer_3_Ok); # Testiks
                } catch (PDOException $error) {
                    $msg = 'Viga andmebaasist lugemisel: <br /> ' . ($error->getMessage());
                }

                if ($answer == 3 && $answer_3_Ok == '') { // URL-is vastuse id = 3 kuid küsimusel vaid 2 vastusevarianti
                    $msg = 'Selline vastusevariant nagu URL-is märgitud ei ole võimalik!';
                } else {

                    if ($answer == 1 || $answer == 2 || $answer == 3) { // Küsimuse nr URL-is vastab reeglitele
                        # Andmebaasiühendus, valitud vastuse kirjutamine tabelisse
                        try {
                            $connection = new PDO($dsn, USERNAME, PASSWORD, $options);
                            $connection->exec('SET NAMES utf8'); // SQL lause, et täpitähed oleks korrektselt
                            $sql = 'INSERT INTO answers (id_q, answer, IP) VALUES (' . $id . ', ' . $answer . ', "' . $ip . '")';
                            // show($sql); # Testiks
                            $statement = $connection->prepare($sql);
                            $statement->execute();
                            $msg = 'Vastus edukalt salvestatud!';
                        } catch (PDOException $error) {
                            $msg = 'Viga andmebaasi kirjutamisel: ' . ('<br /> SQL: <strong>' . $sql . '</strong><br />' . $error->getMessage());
                        }
                    } else { // URL-is vastuse id ei vasta reeglitele
                        $msg = 'URL-is vastuse id vale';
                    }
                }
            }
        }

        # Andmebaasiühendus küsimuse ja selle vastuste leidmiseks
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

# Kuvatakse teade, kas vastuse salvestamine õnnestus või veateade
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