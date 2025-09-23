<?php
function calcule($a, $operation, $b)
{
    if ($operation == "+") {
        return $a + $b;
    } elseif ($operation == "-") {
        return $a - $b;
    } elseif ($operation == "*") {
        return $a * $b;
    } elseif ($operation == "/") {
        if ($b != 0) {
            return $a / $b;
        } else {
            return "Erreur : division par zéro";
        }
    } elseif ($operation == "%") {
        return $a % $b;
    } else {
        return "Opération inconnue";
    }
}
echo calcule(10, "+", 5);
echo "<br>";
echo calcule(10, "-", 5);
echo "<br>";
echo calcule(10, "*", 5);
echo "<br>";
echo calcule(10, "/", 5);
echo "<br>";
echo calcule(10, "/", 0);
echo "<br>";
echo calcule(10, "%", 3);
