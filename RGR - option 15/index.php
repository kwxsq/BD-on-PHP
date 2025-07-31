<?php
require_once 'db.php';
require_once 'functions.php';

$db = require 'db.php';

$action = $_GET['action'] ?? 'home';
$table = $_GET['table'] ?? '';
$validTables = ['Курсы', 'Преподаватели', 'Студенты', 'Записи_на_курсы'];

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Центр изучения иностранных языков</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <header>
        <h1>Центр изучения иностранных языков</h1>
        <nav><ul>
                <li><a href="?action=home" <?php if ($action === 'home') echo 'class="active"'; ?>>Главная</a></li>
                <?php foreach ($validTables as $validTable): ?>
                    <li><a href="?action=view&table=<?php echo $validTable; ?>" <?php if ($action === 'view' && $table === $validTable) echo 'class="active"'; ?>><?php echo $validTable; ?></a></li>
                <?php endforeach; ?>
                <li><a href="?action=queries" <?php if ($action === 'queries') echo 'class="active"'; ?>>Запросы</a></li>
            </ul></nav>
    </header>

    <main>
        <?php
        if ($action === 'view' && in_array($table, $validTables)) {
            $records = $db->query("SELECT * FROM $table")->fetchAll(PDO::FETCH_ASSOC);
            echo "<h1>Таблица: $table</h1>";
            renderTable($records);
            echo "<h2>Добавить новую запись в таблицу $table</h2>";
            echo "<form method='post' action='?action=add&table=$table'>";

            if ($table === 'Записи_на_курсы') {
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
                $columns = getColumns($table);
                foreach ($columns as $col) {
                    echo "<label>$col:</label>";
                    if ($col === 'возраст') {
                        echo "<input type='number' name='$col' min='16' max='100' required>";
                    } elseif ($col === 'телефон') {
                        echo "<input type='tel' name='$col' pattern='^[0-9\s\-\+\(\)]*$' placeholder='Пример: +1 (234) 567-8901' required>";
                    } else {
                        echo "<input type='text' name='$col' required>";
                    }
                }
            } elseif ($table === 'Преподаватели') {
                $columns = getColumns($table);
                foreach ($columns as $col) {
                    echo "<label>$col:</label>";
                    if ($col === 'опыт_работы') {
                        echo "<input type='number' name='$col' min='0' required>";
                    } else {
                        echo "<input type='text' name='$col' required>";
                     }
                }
            }

            echo "<input type='submit' value='Добавить запись'>";
            echo "</form>";

            echo "<a href='?'>Вернуться на главную</a>";

        } elseif ($action === 'add' && in_array($table, $validTables)) {
            try {
                $columns = getColumns($table);
                $values = array_map(fn($col) => $_POST[$col], $columns);

                if ($table === 'Записи_на_курсы') {
                    $студент_id = $_POST['студент_id'];
                    $курс_id = $_POST['курс_id'];
                    $преподаватель_id = $_POST['преподаватель_id'];
                    $дата_начала = $_POST['дата_начала'];
                
                    $checkDuplicate = $db->prepare("SELECT COUNT(*) FROM Записи_на_курсы WHERE студент_id = ? AND курс_id = ? AND преподаватель_id = ? AND дата_начала = ?");
                    $checkDuplicate->execute([$студент_id, $курс_id, $преподаватель_id, $дата_начала]);
                    $exists = $checkDuplicate->fetchColumn();
                
                    if ($exists) {
                        echo "<p class='error'>Ошибка: Эта запись уже существует.</p>";
                        echo "<meta http-equiv='refresh' content='3;url=?action=view&table=Записи_на_курсы'>";
                        exit();
                    }
                
                    $courseLanguage = $db->query("SELECT язык FROM Курсы WHERE id = $курс_id")->fetchColumn();
                
                    $teacherQuery = $db->prepare("SELECT COUNT(*) FROM Преподаватели WHERE id = ? AND язык = ?");
                    $teacherQuery->execute([$преподаватель_id, $courseLanguage]);
                    $languageMatch = $teacherQuery->fetchColumn();
                
                    if (!$languageMatch) {
                        echo "<p class='error'>Ошибка: Преподаватель не владеет языком выбранного курса или указанный язык не совпадает с его специализацией.</p>";
                        echo "<meta http-equiv='refresh' content='3;url=?action=view&table=Записи_на_курсы'>";
                        exit();
                    }
                } else if ($table === 'Преподаватели') {
                    $fio = $_POST['ФИО'];
                    $language = $_POST['язык'];
                    $existingTeacher = checkTeacherExists($fio, $language, $db);
                    if ($existingTeacher) {
                        echo "<p class='error'>Ошибка: Преподаватель с таким ФИО и языком уже существует.</p>";
                        echo "<meta http-equiv='refresh' content='3;url=?action=view&table=Преподаватели'>";
                        exit();
                    }
                } elseif ($table === 'Курсы') {
                    $название = $_POST['название'];
                    $язык = $_POST['язык'];
                    $стоимость = $_POST['стоимость'];

                    $query = $db->prepare("SELECT COUNT(*) FROM Курсы WHERE название = ? AND язык = ? AND стоимость = ?");
                    $query->execute([$название, $язык, $стоимость]);
                    $exists = $query->fetchColumn();

                    if ($exists) {
                        echo "<p class='error'>Ошибка: Курс с таким названием, языком и стоимостью уже существует.</p>";
                        echo "<meta http-equiv='refresh' content='3;url=?action=view&table=Курсы'>";
                        exit();
                    }
                }

                $columnsList = implode(", ", $columns);
                $placeholders = implode(", ", array_fill(0, count($columns), "?"));

                $stmt = $db->prepare("INSERT INTO $table ($columnsList) VALUES ($placeholders)");
                $stmt->execute($values);

                header("Location: ?action=view&table=$table");
                exit();

            } catch (PDOException $e) {
                echo "<p class='error'>Ошибка при добавлении записи: " . $e->getMessage() . "</p>";
                echo "<a href='?action=view&table=$table'>Вернуться к таблице</a>";
            }
        } elseif ($action === 'queries') {
            include('queries.php');
        } else {
            echo "<h2>Добро пожаловать!</h2>";
            echo "<p>Выберите таблицу для просмотра или редактирования данных, или перейдите к разделу запросов.</p>";
        }
        ?>
        <?php
        if ($table === 'Студенты') {
        echo "<a href='edit_students.php'>Редактировать студента</a>";
        }
        ?>

    </main>
</div>
</body>
</html>
