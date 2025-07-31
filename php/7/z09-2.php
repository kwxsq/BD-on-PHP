<!DOCTYPE html>
<html>
<head>
    <title>Заполнение таблицы notebook_br11</title>
</head>
<body>
    <?php
    $dbFile = 'C:/Users/rotov/Desktop/DB Browser for SQLite/7lab.db';
    $teamNumber = 11;
    $tableName = "notebook_br" . $teamNumber;
    ?>
    <h2>Добавление записи в notebook_br<?php echo $teamNumber; ?></h2>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['name']) && !empty($_POST['mail'])) {
        try {
            $pdo = new PDO("sqlite:$dbFile");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $name = $_POST['name'];
            $city = $_POST['city'];
            $address = $_POST['address'];
            $birthday = $_POST['birthday'];
            $mail = $_POST['mail'];

            $sql = "INSERT INTO $tableName (name, city, address, birthday, mail) 
                    VALUES (:name, :city, :address, :birthday, :mail)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['name' => $name, 'city' => $city, 
                            'address' => $address, 'birthday' => $birthday, 'mail' => $mail]);

            echo "Новая запись добавлена";
        } catch(PDOException $e) {
            echo "Ошибка при добавлении записи: " . $e->getMessage();
        }
    }
    ?>

    <form method="post">
        <label for="name">Имя:</label><br>
        <input type="text" name="name" id="name" required><br><br>

        <label for="city">Город:</label><br>
        <input type="text" name="city" id="city"><br><br>

        <label for="address">Адрес:</label><br>
        <input type="text" name="address" id="address"><br><br>

        <label for="birthday">Дата рождения:</label><br>
        <input type="date" name="birthday" id="birthday"><br><br>

        <label for="mail">Email:</label><br>
        <input type="email" name="mail" id="mail" required><br><br>

        <input type="submit" value="Добавить запись">
    </form>
</body>
</html>