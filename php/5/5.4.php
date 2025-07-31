<?php
    $a = isset($_GET['a']) ? (int)$_GET['a'] : null;
    $b = isset($_GET['b']) ? (int)$_GET['b'] : null;
    $c = isset($_GET['c']) ? (int)$_GET['c'] : null;

    if ($a !== null && $b !== null && $c !== null) {
        $correct_result = $a * $b;

        if ($c == $correct_result) {
            echo "Результат произведения верный: $a * $b = $c<br>";
        } else {
            echo "Результат произведения неверный!<br>";
            echo "Введенное произведение: $a * $b = $c<br>";
            echo "Правильное произведение: $a * $b = $correct_result<br>";
        }
    } else {
        echo "Пожалуйста, введите все значения для переменных a, b и c через адресную строку.";
    }
?>