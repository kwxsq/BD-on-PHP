<!DOCTYPE html>
<html>
<head>
    <title>Просмотр записей notebook_br11</title>
</head>
<body>
    <?php
    $dbFile = 'C:/Users/rotov/Desktop/DB Browser for SQLite/7lab.db';
    $teamNumber = 11;
    $tableName = "notebook_br" . $teamNumber;
    ?>
    <h2>Записи в таблице notebook_br<?php echo $teamNumber; ?></h2>

    <?php
    try {
        $pdo = new PDO("sqlite:$dbFile");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM $tableName";
        $stmt = $pdo->query($sql);

        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Имя</th><th>Город</th><th>Адрес</th><th>Дата рождения</th><th>Email</th></tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['city'] . "</td>";
            echo "<td>" . $row['address'] . "</td>";
            echo "<td>" . $row['birthday'] . "</td>";
            echo "<td>" . $row['mail'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";

    } catch(PDOException $e) {
        echo "Ошибка при выводе записей: " . $e->getMessage();
    }
    ?>
</body>
</html>