<?php
$treug = [];
$kvd = [];

for ($n = 1; $n <= 30; $n++) {
    $treug[] = $n * ($n + 1) / 2;
    $kvd[] = $n * $n;
}

echo "<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }
    td {
        width: 30px;
        height: 30px;
        text-align: center;
        font-size: 10px;
        border: 1px solid black;
    }
    .blue {
        background-color: blue;
        color: white;
    }
    .green {
        background-color: green;
        color: white;
    }
    .red {
        background-color: red;
        color: white;
    }
    .white {
        background-color: white;
        color: black;
    }
</style>";

echo "<table>";
for ($i = 1; $i <= 30; $i++) {
    echo "<tr>";
    for ($j = 1; $j <= 30; $j++) {
        $value = $i * $j;
        $class = "white";

        if (in_array($value, $kvd) && in_array($value, $treug)) {
            $class = "red";
        } elseif (in_array($value, $kvd)) {
            $class = "blue";
        } elseif (in_array($value, $treug)) {
            $class = "green";
        }

        echo "<td class='$class'>$value</td>";
    }
    echo "</tr>";
}
echo "</table>";

echo "<br><strong>Треугольные числа:</strong><br>";
echo implode(", ", $treug);
?>
