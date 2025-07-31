<?php

$dbFile = 'C:/Users/rotov/Desktop/DB Browser for SQLite/7lab.db';
$tableName = 'notebook_br11';

try {
    $pdo = new PDO("sqlite:$dbFile");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("DROP TABLE IF EXISTS $tableName");

    $sql = "CREATE TABLE $tableName (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        city TEXT,
        address TEXT,
        birthday DATE,
        mail TEXT
    )";

    $pdo->exec($sql);

    echo "Таблица $tableName успешно создана в базе данных $dbFile.";

} catch(PDOException $e) {
    echo "Нельзя создать таблицу $tableName: " . $e->getMessage();
}
?>