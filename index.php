<?php
require 'templates/header.php';

$msg = ''; // Veateate jaoks

# Leiab aktiivse küsimuse (staatus = 1)
try {
    $connection = new PDO($dsn, USERNAME, PASSWORD, $options);
    $connection->exec('SET NAMES utf8');
    $sql = 'SELECT * FROM questions  WHERE status = 1';
    $statement = $connection->prepare($sql);
    $statement->execute();
    $question = $statement->fetch(PDO::FETCH_ASSOC);
    $id = $question['id_q']; // Aktiivse küsimuse id
} catch (PDOException $error) {
    $msg = 'Viga andmebaasist lugemisel: ' . ('<br /> SQL: <strong>' . $sql . '</strong><br />' . $error->getMessage());
}

# Leiab vastanute arvu
try {
    $connection = new PDO($dsn, USERNAME, PASSWORD, $options);
    $connection->exec('SET NAMES utf8');
    $sql = 'SELECT COUNT(*) as total FROM answers WHERE id_q =' . $id;
    $statement = $connection->prepare($sql);
    $statement->execute();
    $answers = $statement->fetch(PDO::FETCH_ASSOC);
    $total = $answers['total'];
} catch (PDOException $error) {
    if (empty($total)) {
        $msg = 'Hetkel ei ole ühtegi aktiivset küsimust';
    } else {
        $msg = 'Viga andmebaasist lugemisel: ' . ('<br /> SQL: <strong>' . $sql . '</strong><br />' . $error->getMessage());
    }
}


if ($msg != '') { // Kui on teade siis kuvatakse 
?>
    <div class="delete">
        <h3 class=""><?php echo $msg ?></h3>
    </div>
<?php

} else { // Pole veateadet, vastuse valikuga suunatakse tulemuste lehele
?>

    <div class="content poll-vote">
        <h2><?php echo ($question['question']); ?></h2>

        <input type="radio" id="answer_1" name="answer_1" value="1" onclick="location.href='poll_result.php?id=<?php echo $question['id_q']; ?>&answer=1'">
        <label for="answer_1"> <?php echo ($question['answer_1']); ?> </label><br>
        <input type="radio" id="answer_2" name="answer_2" value="2" onclick="location.href='poll_result.php?id=<?php echo $question['id_q']; ?>&answer=2'">
        <label for="answer_2"> <?php echo ($question['answer_2']); ?> </label><br>
        <!-- Kolm varianti -->
        <?php if (strlen(trim($question['answer_3'])) != 0) {
        ?>
            <input type="radio" id="answer_3" name="answer_3" value="3" onclick="location.href='poll_result.php?id=<?php echo $question['id_q']; ?>&answer=3'">
            <label for="answer_3"> <?php echo ($question['answer_3']); ?> </label>
        <?php
            }
        ?>
        <form action="">
        <p class=""> Küsitluses on osalenud <?php echo $total ?> inimest.</p>
        <div>
            <a href="poll_result2.php?id=<?php echo $question['id_q']; ?>"> Tulemused </a>
        </div>
        </form>
    </div>

<?php
}
?>

<?php require 'templates/footer.php'; ?>