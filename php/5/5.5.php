<?php
    $color = "blue"; 
    $plus_color = "red";

    echo "<table border='1' cellpadding='5'>";

    echo "<tr>";
    echo "<td style='color: $plus_color;'>+</td>";
    for ($i = 1; $i <= 10; $i++) {
        echo "<td style='color: $color;'>$i</td>";
    }
    echo "</tr>";

    for ($i = 1; $i <= 10; $i++) {
        echo "<tr>";

        echo "<td style='color: $color;'>$i</td>";

        for ($j = 1; $j <= 10; $j++) {
            $sum = $i + $j;
            echo "<td>$sum</td>";
        }

        echo "</tr>";
    }

    echo "</table>";
?>