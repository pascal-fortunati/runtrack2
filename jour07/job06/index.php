<?php
function leetSpeak($str)
{
    $map = [
        'A' => '4',
        'a' => '4',
        'B' => '8',
        'b' => '8',
        'E' => '3',
        'e' => '3',
        'G' => '6',
        'g' => '6',
        'L' => '1',
        'l' => '1',
        'S' => '5',
        's' => '5',
        'T' => '7',
        't' => '7'
    ];

    $result = "";
    $i = 0;
    while (isset($str[$i])) {
        if (isset($map[$str[$i]])) {
            $result .= $map[$str[$i]];
        } else {
            $result .= $str[$i];
        }
        $i++;
    }
    return $result;
}
?>
<p><?php echo leetSpeak("Hello LaPlateforme!"); ?></p>