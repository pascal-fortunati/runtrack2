<?php
function bonjour($jour)
{
    if ($jour === true) {
        echo "Bonjour";
    } else {
        echo "Bonsoir";
    }
}
?>
<p><?php bonjour(true); ?> la fonction est true</p>
<p><?php bonjour(false); ?> la fonction est false</p>