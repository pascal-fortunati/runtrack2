<?php
function occurrences($str, $char)
{
    $count = 0;
    $i = 0;
    while (isset($str[$i])) {
        if ($str[$i] == $char) {
            $count++;
        }
        $i++;
    }
    return $count;
}
?>
<p>Le caractère 'a' apparaît <?php echo occurrences("anaconda", "a"); ?> fois dans le mot "anaconda".</p>
<p>Le caractère 'o' apparaît <?php echo occurrences("anaconda", "o"); ?> fois dans le mot "anaconda".</p>
<p>Le caractère 'n' apparaît <?php echo occurrences("anaconda", "n"); ?> fois dans le mot "anaconda".</p>
<p>Le caractère 'c' apparaît <?php echo occurrences("anaconda", "c"); ?> fois dans le mot "anaconda".</p>
<p>Le caractère 'x' apparaît <?php echo occurrences("anaconda", "x"); ?> fois dans le mot "anaconda".</p>