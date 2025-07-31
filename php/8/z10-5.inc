<?php
function vid_structure($table) {
    global $db;
    echo "<h4>Структура таблицы $table:</h4>";
    $stmt = $db->query("PRAGMA table_info($table)");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($columns) > 0) {
        echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Constraints</th></tr>";

        foreach ($columns as $column) {
            $constraints = [];
            $name = $column['name'];
            $type = $column['type'];

            preg_match('/(\w+)(\((\d+)\))?/', $type, $matches);

            $type_without_length = $matches[1] ?? $type;

            $length = isset($matches[3]) ? $matches[3] : '';

            $type_with_length = $length ? "$type_without_length($length)" : $type_without_length;

            if ($column['pk'] == 1) {
                $constraints[] = 'PRIMARY KEY';
            }
            if ($column['notnull'] == 1) {
                $constraints[] = 'NOT NULL';
            }
            if ($column['dflt_value'] !== null) {
                $constraints[] = "DEFAULT '{$column['dflt_value']}'";
            }

            $constraints_str = implode(' ', $constraints);

            echo "<tr><td>$name</td><td>$type_with_length</td><td>$constraints_str</td></tr>";
        }

        echo "</table>";
    } else {
        echo "<p>Таблица $table не существует или пуста.</p>";
    }
}


function vid_content($table) {
    global $db;
    $rus_name = [
        "id" => ["Идентификатор"],    
        "name" => ["Имя"],    
        "age" => ["Возраст"],    
        "product_name" => ["Наименование продукта"],    
        "price" => ["Цена"],    
        "title" => ["Заголовок"],    
        "description" => ["Описание"],
        "cnum" => ["Номер клиента"],
        "cname" => ["Имя клиента"],
        "city" => ["Город"],
        "rating" => ["Рейтинг"],
        "snum" => ["Номер продавца"]
    ];


    $stmt = $db->query("SELECT * FROM $table");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h4>Содержимое таблицы $table</h4>";
    if (count($rows) > 0) {
        echo "<table border='1'>";
        echo "<tr>";
        foreach ($rows[0] as $col => $val) {
            $col_name = isset($rus_name[$col]) ? $rus_name[$col][0] : $col;
            echo "<th>$col_name<br>$col</th>";
        }
        echo "</tr>";

        foreach ($rows as $row) {
            echo "<tr>";
            foreach ($row as $col => $val) {
                echo "<td>$val</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Таблица $table пуста.</p>";
    }

    echo "<br><a href='z10-1.htm'>Возврат к выбору таблицы</a>";
}


if (isset($_GET['structure'])) {
    vid_structure($_GET['table']);
}

if (isset($_GET['content'])) {
    vid_content($_GET['table']);
}
?>