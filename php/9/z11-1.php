<?php
$brigadeNumber = 11;
$fileName = "notebook_br{$brigadeNumber}.txt";
$dbPath = 'D:/7_semak/БД/php/8/sample.db';

if (file_exists($fileName)) {
    echo "Файл существует<br>";
} else {
    $fileHandle = fopen($fileName, 'w');
    if ($fileHandle) {
        fclose($fileHandle);
        echo "Файл был создан<br>";
    } else {
        die("Не удалось создать файл");
    }
}

try {
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $fileHandle = fopen($fileName, 'w');
    if (!$fileHandle) {
        die("Не удалось открыть файл для записи");
    }

    $emailPattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

    $query = $pdo->query("SELECT * FROM notebook_br{$brigadeNumber}");
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $line = '';
        foreach ($row as $column => $value) {
            $value = preg_replace(
                '/(\d{4})-(\d{2})-(\d{2})/',
                '$3-$2-$1',
                $value
            );
            
            if ($column == 'email' && !preg_match($emailPattern, $value)) {
                continue;
            }

            $line .= $value . " | ";
        }

        $line = rtrim($line, " | ") . "\n";
        fwrite($fileHandle, $line);
    }

    fclose($fileHandle);

    $fileHandle = fopen($fileName, 'r');
    if (!$fileHandle) {
        die("Не удалось открыть файл для чтения");
    }

    while (($line = fgets($fileHandle)) !== false) {
        echo htmlspecialchars($line) . "<br>";
    }

    fclose($fileHandle);
} catch (PDOException $e) {
    echo "Ошибка подключения к БД: " . $e->getMessage();
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage();
}
?>
