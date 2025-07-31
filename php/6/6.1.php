<?php
$treug = [];
for ($n = 1; $n <= 10; $n++) {
    $treug[] = $n * ($n + 1) / 2;
}
echo "Треугольные числа: " . implode("  ", $treug) . "<br><br>";

$kvd = [];
for ($n = 1; $n <= 10; $n++) {
    $kvd[] = $n * $n;
}
echo "Квадраты чисел: " . implode("  ", $kvd) . "<br><br>";

$rez = array_merge($treug, $kvd);
echo "Объединенный массив: " . implode("  ", $rez) . "<br><br>";

sort($rez);
echo "Отсортированный массив: " . implode("  ", $rez) . "<br><br>";

array_shift($rez);
echo "После удаления первого элемента: " . implode("  ", $rez) . "<br><br>";

$rez1 = array_unique($rez);
echo "Массив без повторяющихся элементов: " . implode("  ", $rez1) . "<br>";
?>
