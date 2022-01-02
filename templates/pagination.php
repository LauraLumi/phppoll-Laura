
<nav class="pagination" role="navigation" aria-label="pagination">
    <div class="pagination">
        <a href="?page=1" class="pagination-previous is-disabled" <?php echo ($page == 1) ? 'disabled' : null; ?>>Esimene</a>
        <a href="?page=<?php echo (($page - 1) == 0) ? "1" : ($page - 1); ?>" class="pagination-previous is-disabled" <?php echo ($page == 1) ? 'disabled' : null; ?>>Eelmine</a>

        <a href="?page=<?php echo (($page + 1) > $pageCount) ? $pageCount : $page + 1; ?>" class="pagination-next" <?php echo ($page >= $pageCount) ? 'disabled' : null; ?>>Järgmine</a>
        <a href="?page=<?php echo $pageCount; ?>" class="pagination-next" <?php echo ($page >= $pageCount) ? 'disabled' : null; ?>>Viimane</a>
        <ul class="pagination-list">
            <?php
            for ($i = $btnStart; $i <= $btnEnd; $i++) {
            ?>
                <li>
                    <a href="?page=<?php echo $i; ?>" class="pagination-link <?php echo ($page == $i) ? 'is-current' : null; ?>" aria-label="Page <?php echo $i; ?>" aria-current="page"><?php echo $i; ?></a>
                </li>
            <?php
            }
            ?>
        </ul>
    </div>
</nav>

<div class="content home">
    <p class="m-2">Lehel <?php echo $page; ?>/<?php echo $pageCount;
        echo ($pageCount == 1 ? ' leht' : ' lehte') ?> (lehel <?php echo count($questions);
            echo (count($questions) == 1 ? ' kirje' : ' kirjet') ?> ja kirjeid kokku <?php echo $totalRows; ?>)</p>
</div>