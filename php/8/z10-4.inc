<?php
try {
    $db = new PDO('sqlite:D:/7_semak/БД/php/8/sample.db');
} catch (PDOException $e) {
    echo "Ошибка подключения: " . $e->getMessage();
    exit();
}
?>
