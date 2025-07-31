<?php
require_once 'db.php';
require_once 'functions.php';

$db = require 'db.php';

function renderQueryResult($result, ...$headers)
{
    if ($result) {
        echo "<table border='1'>";
        echo "<thead><tr>";
        foreach ($headers as $header) {
            echo "<th>$header</th>";
        }
        echo "</tr></thead>";
        echo "<tbody>";
        foreach ($result as $row) {
            echo "<tr>";
            foreach ($row as $col) {
                echo "<td>$col</td>";
            }
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>Нет данных для отображения.</p>";
    }
}

function getLanguages($db)
{
    $sql = "SELECT DISTINCT язык FROM Курсы";
    $stmt = $db->query($sql);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

echo "<h2>Запросы</h2>";

echo "<form method='post'>";
echo "<h3>Запрос 1: Студенты и курсы</h3>";
echo "<p>Вывести имена студентов и названия курсов, на которые они записаны, вместе с датой начала курса.</p>";
echo "<button type='submit' name='query' value='query1'>Выполнить</button>";

echo "<h3>Запрос 2: Преподаватели и курсы на английском языке</h3>";
echo "<p>Вывести имена преподавателей, которые ведут курсы на английском языке, и названия этих курсов.</p>";
echo "<button type='submit' name='query' value='query2'>Выполнить</button>";

echo "<h3>Запрос 3: Студенты по выбранному языку</h3>";
echo "<label for='language'>Выберите язык:</label>";
echo "<select id='language' name='language'>";
foreach (getLanguages($db) as $language) {
    echo "<option value='$language'>$language</option>";
}
echo "</select>";
echo "<button type='submit' name='query' value='query3'>Выполнить</button>";

echo "<h3>Запрос 4: Студенты записанные на курс до определенной даты</h3>";
echo "<label for='start_date'>Выберите дату начала курса:</label>";
echo "<input type='date' id='start_date' name='start_date'>";
echo "<button type='submit' name='query' value='query4'>Выполнить</button>";
echo "</form>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['query'])) {
    $query = $_POST['query'];

    switch ($query) {
        case 'query1':
            $sql = "
                SELECT 
                    Студенты.ФИО AS Имя_студента, 
                    Курсы.название AS Название_курса, 
                    Записи_на_курсы.дата_начала AS Дата_начала
                FROM 
                    Студенты
                JOIN 
                    Записи_на_курсы ON Студенты.id = Записи_на_курсы.студент_id
                JOIN 
                    Курсы ON Курсы.id = Записи_на_курсы.курс_id";
            $stmt = $db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            renderQueryResult($result, "Имя студента", "Название курса", "Дата начала");
            break;

        case 'query2':
            $sql = "
                SELECT DISTINCT 
                    Преподаватели.ФИО AS Имя_преподавателя, 
                    Курсы.название AS Название_курса
                FROM 
                    Преподаватели
                JOIN 
                    Курсы ON Преподаватели.язык = Курсы.язык
                WHERE 
                    Курсы.язык = 'Английский'";
            $stmt = $db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            renderQueryResult($result, "Имя преподавателя", "Название курса");
            break;

            case 'query3':
                if (!empty($_POST['language'])) {
                    $language = trim($_POST['language']);
                    $sql = "
                        SELECT 
                            Студенты.ФИО AS Имя_студента, 
                            Курсы.название AS Название_курса, 
                            Преподаватели.ФИО AS Имя_преподавателя
                        FROM 
                            Студенты
                        JOIN 
                            Записи_на_курсы ON Студенты.id = Записи_на_курсы.студент_id
                        JOIN 
                            Курсы ON Курсы.id = Записи_на_курсы.курс_id
                        JOIN 
                            Преподаватели ON Преподаватели.id = Записи_на_курсы.преподаватель_id
                        WHERE 
                            Курсы.язык = :language;
                    ";
                    $stmt = $db->prepare($sql);
                    $stmt->execute([':language' => $language]);
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if ($result) {
                        renderQueryResult($result, "Имя студента", "Название курса", "Имя преподавателя");
                    } else {
                        echo "<p>Нет студентов, изучающих курсы на языке: $language.</p>";
                    }
                } else {
                    echo "<p>Выберите язык для выполнения запроса.</p>";
                }
                break;            

        case 'query4':
            if (!empty($_POST['start_date'])) {
                $startDate = trim($_POST['start_date']);
                $sql = "
                    SELECT 
                        Студенты.ФИО AS Имя_студента, 
                        Курсы.название AS Название_курса, 
                        Записи_на_курсы.дата_начала AS Дата_начала
                    FROM 
                        Студенты
                    JOIN 
                        Записи_на_курсы ON Студенты.id = Записи_на_курсы.студент_id
                    JOIN 
                        Курсы ON Курсы.id = Записи_на_курсы.курс_id
                    WHERE 
                        Записи_на_курсы.дата_начала <= :start_date";
                $stmt = $db->prepare($sql);
                $stmt->execute([':start_date' => $startDate]);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($result) {
                    renderQueryResult($result, "Имя студента", "Название курса", "Дата начала");
                } else {
                    echo "<p>Нет студентов, записанных на курсы до даты: $startDate.</p>";
                }
            } else {
                echo "<p>Введите корректную дату для выполнения запроса.</p>";
            }
            break;

        default:
            echo "<p>Неизвестный запрос.</p>";
    }
}

echo "<a href='?'>Вернуться на главную</a>";
?>
