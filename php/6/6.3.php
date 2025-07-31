<?php
$cust = [
    "cnum" => 2001,
    "cname" => "Hoffman",
    "city" => "London",
    "snum" => 1001,
    "rating" => 100
];

echo "<strong>Исходный массив:</strong><br>";
foreach ($cust as $key => $value) {
    echo "$key: $value<br>";
}

asort($cust);
echo "<br><strong>Массив, отсортированный по значениям:</strong><br>";
foreach ($cust as $key => $value) {
    echo "$key: $value<br>";
}

ksort($cust);
echo "<br><strong>Массив, отсортированный по ключам:</strong><br>";
foreach ($cust as $key => $value) {
    echo "$key: $value<br>";
}

sort($cust);
echo "<br><strong>Массив после использования sort():</strong><br>";
foreach ($cust as $key => $value) {
    echo "$key: $value<br>";
}
?>
