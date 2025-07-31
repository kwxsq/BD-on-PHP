<?php
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

function getColumns($table) {
    switch ($table) {
        case 'Курсы':
            return ['название', 'язык', 'стоимость'];
        case 'Преподаватели':
            return ['ФИО', 'специализация', 'язык', 'опыт_работы'];
        case 'Студенты':
            return ['ФИО', 'возраст', 'телефон'];
        case 'Записи_на_курсы':
            return ['студент_id', 'курс_id', 'преподаватель_id', 'дата_начала'];
        default:
            return [];
    }
}

function checkTeacherExists($fio, $language, $db) {
    $query = $db->prepare("SELECT id FROM Преподаватели WHERE ФИО = ? AND язык = ?");
    $query->execute([$fio, $language]);
    return $query->fetch(PDO::FETCH_ASSOC);
}
?>