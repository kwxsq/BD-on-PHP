<?php
$path = 'D:/7_semak/BD/RGR/language.db';
try {
    $db = new PDO("sqlite:$path");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->exec("CREATE TABLE IF NOT EXISTS Курсы (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        название TEXT NOT NULL,
        язык TEXT NOT NULL,
        стоимость REAL
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS Преподаватели (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        ФИО TEXT NOT NULL,
        специализация TEXT NOT NULL,
        язык TEXT NOT NULL,
        опыт_работы INTEGER CHECK(опыт_работы >= 0)
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS Студенты (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        ФИО TEXT NOT NULL,
        возраст INTEGER CHECK(возраст >= 16 AND возраст <= 100),
        телефон TEXT NOT NULL
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS Записи_на_курсы (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        студент_id INTEGER NOT NULL,
        курс_id INTEGER NOT NULL,
        преподаватель_id INTEGER NOT NULL,
        дата_начала TEXT NOT NULL,
        FOREIGN KEY(студент_id) REFERENCES Студенты(id),
        FOREIGN KEY(курс_id) REFERENCES Курсы(id),
        FOREIGN KEY(преподаватель_id) REFERENCES Преподаватели(id)
    )");

    return $db;
} catch (PDOException $e) {
    die("Ошибка базы данных: " . $e->getMessage());
}
?>