<!DOCTYPE html>
<html>
<head>
    <title>Редактирование таблицы notebook_br11</title>
</head>
<body>
    <?php 
    $dbFile = 'C:/Users/rotov/Desktop/DB Browser for SQLite/7lab.db'; 
    $teamNumber = 11;
    $tableName = "notebook_br" . $teamNumber;
    
    try {
        $pdo = new PDO("sqlite:$dbFile");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (isset($_POST['id']) && isset($_POST['field_name'])) {
            $id = $_POST['id'];
            $fieldName = $_POST['field_name'];
            $fieldValue = $_POST['field_value'];

            $sql = "UPDATE $tableName SET $fieldName = :value WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['value' => $fieldValue, 'id' => $id]);

            echo "Запись обновлена. <a href='z09-3.php'>Посмотреть таблицу</a>";
        }

        $sql = "SELECT * FROM $tableName";
        $stmt = $pdo->query($sql);

        echo "<form method='post'>";
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Имя</th><th>Город</th><th>Адрес</th><th>Дата рождения</th><th>Email</th><th>Выбор</th></tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['city'] . "</td>";
            echo "<td>" . $row['address'] . "</td>";
            echo "<td>" . $row['birthday'] . "</td>";
            echo "<td>" . $row['mail'] . "</td>";
            echo "<td><input type='radio' name='id' value='" . $row['id'] . "'></td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<input type='submit' value='Редактировать'>";
        echo "</form>";

        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $sql = "SELECT * FROM $tableName WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            $a_row = $stmt->fetch(PDO::FETCH_ASSOC);

            echo "<h2>Редактирование записи</h2>";
            echo "<form method='post'>";
            echo "<input type='hidden' name='id' value='$id'>";

            echo "<label for='field_name'>Поле:</label>";
            echo "<select name='field_name' id='field_name'>";
            echo "<option value='name'>Имя</option>";
            echo "<option value='city'>Город</option>";
            echo "<option value='address'>Адрес</option>";
            echo "<option value='birthday'>Дата рождения</option>";
            echo "<option value='mail'>Email</option>";
            echo "</select><br><br>";

            echo "Имя: " . htmlspecialchars($a_row['name']) . "<br>";
            echo "Город: " . htmlspecialchars($a_row['city']) . "<br>";
            echo "Адрес: " . htmlspecialchars($a_row['address']) . "<br>";
            echo "Дата рождения: " . htmlspecialchars($a_row['birthday']) . "<br>";
            echo "Email: " . htmlspecialchars($a_row['mail']) . "<br><br>";

            echo "<label for='field_value'>Новое значение:</label>";
            if (isset($_POST['field_name'])) {
                $fieldName = $_POST['field_name'];
                echo "<input type='text' name='field_value' id='field_value' value='". htmlspecialchars($a_row[$fieldName]) ."'><br><br>";
            } else {
                echo "<input type='text' name='field_value' id='field_value'><br><br>";
            }

            echo "<input type='submit' value='Заменить'>";
            echo "</form>";
        }

    } catch(PDOException $e) {
        echo "Ошибка: " . $e->getMessage();
    }
    ?>
</body>
</html>