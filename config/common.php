<?php
/** Muudab HTML märgid turvaliseks */
function escape($html) {
    return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Näitab massiivi inimlikul kujul */
function show($array) {
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}
