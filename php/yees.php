<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Центр изучения иностранных языков</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f9;
        }
        h1, h2, h3 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #0078D7;
            color: white;
        }
        input[type="text"], input[type="submit"], input[type="date"], select, input[type="number"], input[type="tel"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #0078D7;
            color: white;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #005a9e;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #0078D7;
        }
        a:hover {
            text-decoration: underline;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .error {
            color: red;
        }
        select option {
            padding: 10px;
        }
        .success {
            color: green;
            font-size: 18px;
        }
    </style>
</head>
<body>
<div class="container">
<?php
// Подключение или создание базы данных
$path = 'D:/7_semak/BD/RGR/language.db';
$db = new PDO("sqlite:$path");

// Создание таблиц, если они не существуют
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

// Обработка действий пользователя
$action = $_GET['action'] ?? 'home';
$table = $_GET['table'] ?? '';
$validTables = ['Курсы', 'Преподаватели', 'Студенты', 'Записи_на_курсы'];

// Функция для отображения таблиц
function renderTable($records) {
    echo "<table>";
    if (!empty($records)) {
        echo "<tr>";
        foreach (array_keys($records[0]) as $col) {
            echo "<th>$col</th>";
        }
        echo "</tr>";
        foreach ($records as $row) {
            echo "<tr>";
            foreach ($row as $col) {
                echo "<td>$col</td>";
            }
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='100%'>Нет данных для отображения</td></tr>";
    }
    echo "</table>";
}

// Функция для получения списка колонок таблицы
function getColumns($table) {
    switch ($table) {
        case 'Курсы':
            return ['название', 'язык', 'стоимость'];
        case 'Преподаватели':
            return ['ФИО', 'специализация', 'язык', 'опыт_работы'];  // Изменено: добавлен 'язык'
        case 'Студенты':
            return ['ФИО', 'возраст', 'телефон'];
        case 'Записи_на_курсы':
            return ['студент_id', 'курс_id', 'преподаватель_id', 'дата_начала'];
        default:
            return [];
    }
}

// Функция для проверки существования преподавателя с таким ФИО и языком
function checkTeacherExists($fio, $language, $db) {
    $query = $db->prepare("SELECT id FROM Преподаватели WHERE ФИО = ? AND язык = ?");
    $query->execute([$fio, $language]);
    return $query->fetch(PDO::FETCH_ASSOC);
}

if ($action === 'view' && in_array($table, $validTables)) {
    // Просмотр данных таблицы
    $records = $db->query("SELECT * FROM $table")->fetchAll(PDO::FETCH_ASSOC);

    echo "<h1>Таблица: $table</h1>";
    renderTable($records);

    // Форма для добавления записи
    echo "<h2>Добавить новую запись в таблицу $table</h2>";
    echo "<form method='post' action='?action=add&table=$table' name='addForm'>";

    if ($table === 'Записи_на_курсы') {
        // Дополнительная форма для записи на курс
        echo "<label>Выберите студента:</label>";
        echo "<select name='студент_id' required>";
        $students = $db->query("SELECT id, ФИО FROM Студенты")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($students as $student) {
            echo "<option value='{$student['id']}'>{$student['ФИО']} (ID: {$student['id']})</option>";
        }
        echo "</select>";

        echo "<label>Выберите курс:</label>";
        echo "<select id='курс_id' name='курс_id' required>";
        echo "<option value=''>Выберите курс</option>";
        $courses = $db->query("SELECT id, название, язык FROM Курсы")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($courses as $course) {
            echo "<option value='{$course['id']}' data-language='{$course['язык']}'>{$course['название']} ({$course['язык']})</option>";
        }
        echo "</select>";

        echo "<label>Выберите преподавателя:</label>";
        echo "<select id='преподаватель_id' name='преподаватель_id' required>";
        echo "<option value=''>Выберите преподавателя</option>";
        $teachers = $db->query("SELECT id, ФИО, специализация, язык FROM Преподаватели")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($teachers as $teacher) {
            echo "<option value='{$teacher['id']}' data-language='{$teacher['язык']}'>{$teacher['ФИО']} ({$teacher['язык']}) (ID: {$teacher['id']})</option>";
        }
        echo "</select>";

        echo "<label>Дата начала курса:</label>";
        echo "<input type='date' name='дата_начала' required>";
    } elseif ($table === 'Курсы') {
        // Форма для курсов
        $columns = getColumns($table);
        foreach ($columns as $col) {
            echo "<label>$col:</label>";
            if ($col === 'стоимость') {
                echo "<input type='number' name='$col' min='0' step='any' required>";
            } else {
                echo "<input type='text' name='$col' required>";
            }
        }
    } elseif ($table === 'Студенты') {
        // Специфичная форма для студентов
        $columns = getColumns($table);
        foreach ($columns as $col) {
            echo "<label>$col:</label>";
            if ($col === 'возраст') {
                // Ограничение на возраст (от 16 до 100 лет)
                echo "<input type='number' name='$col' min='16' max='100' required>";
            } elseif ($col === 'телефон') {
                // Поле для телефона
                echo "<input type='tel' name='$col' pattern='^[0-9\s\-\+\(\)]*$' placeholder='Пример: +1 (234) 567-8901' required>";
            } else {
                echo "<input type='text' name='$col' required>";
            }
        }
    } elseif ($table === 'Преподаватели') {
        // Форма для преподавателей
        $columns = getColumns($table);
        foreach ($columns as $col) {
            echo "<label>$col:</label>";
            if ($col === 'опыт_работы') {
                echo "<input type='number' name='$col' min='0' required>";
            } else {
                echo "<input type='text' name='$col' required>";
            }
        }
    } else {
        $columns = getColumns($table);
        foreach ($columns as $col) {
            echo "<label>$col:</label>";
            if ($col === 'стоимость') {
                echo "<input type='number' name='$col' min='0' step='any' required>";
            } else {
                echo "<input type='text' name='$col' required>";
            }
        }
    }

    echo "<input type='submit' value='Добавить запись'>";
    echo "</form>";

    // Кнопка для возврата на главную страницу
    echo "<a href='?'>Вернуться на главную</a>";
} elseif ($action === 'add' && in_array($table, $validTables)) {
    // Добавление записи в таблицу
    $columns = getColumns($table);
    $values = array_map(fn($col) => $_POST[$col], $columns);

    // Проверка на валидность данных
    if ($table === 'Записи_на_курсы') {
        // Проверка соответствия языка курса и преподавателя
        $courseId = $_POST['курс_id'];
        $teacherId = $_POST['преподаватель_id'];
        $courseLanguage = $db->query("SELECT язык FROM Курсы WHERE id = $courseId")->fetchColumn();
        $teacherLanguage = $db->query("SELECT язык FROM Преподаватели WHERE id = $teacherId")->fetchColumn();

        if ($courseLanguage !== $teacherLanguage) {
            echo "<p class='error'>Ошибка: Преподаватель не владеет языком выбранного курса.</p>";
            echo "<meta http-equiv='refresh' content='3;url=?action=view&table=Записи_на_курсы'>";
            exit();
        } else {
            // Если языки совпадают, добавляем запись
            $columnsList = implode(", ", $columns);
            $placeholders = implode(", ", array_fill(0, count($columns), "?"));
            $stmt = $db->prepare("INSERT INTO $table ($columnsList) VALUES ($placeholders)");
            $stmt->execute($values);
        
            // Перенаправление на страницу с таблицей
            header("Location: ?action=view&table=$table");
            exit();
        }
    } else {
        // Проверка существования преподавателя с таким ФИО и языком
        if ($table === 'Преподаватели') {
            $fio = $_POST['ФИО'];
            $language = $_POST['язык'];

            $existingTeacher = checkTeacherExists($fio, $language, $db);

            if ($existingTeacher) {
                // Если преподаватель уже существует
                echo "<p class='error'>Ошибка: Преподаватель с таким ФИО и языком уже существует.</p>";
                echo "<meta http-equiv='refresh' content='3;url=?action=view&table=Преподаватели'>";
                exit();
            }
        }

        $columnsList = implode(", ", $columns);
        $placeholders = implode(", ", array_fill(0, count($columns), "?"));

        $stmt = $db->prepare("INSERT INTO $table ($columnsList) VALUES ($placeholders)");
        $stmt->execute($values);

        // Перенаправление на страницу с таблицей
        header("Location: ?action=view&table=$table");
        exit();
    }
} else {
    // Главная страница
    echo "<h1>Центр изучения иностранных языков</h1>";
    echo "<h2>Выберите таблицу для работы</h2>";
    echo "<ul>";
    foreach ($validTables as $validTable) {
        echo "<li><a href='?action=view&table=$validTable'>$validTable</a></li>";
    }
    echo "</ul>";
    echo "<h2>Выполнить запросы</h2>";
    echo "<a href='?action=queries'>Запросы</a>";
}
?>

</div>
</body>
</html>
