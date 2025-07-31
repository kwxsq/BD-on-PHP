<?php
date_default_timezone_set('Asia/Novosibirsk');

$brigadeNumber = 11;
$fileName = "notebook_br{$brigadeNumber}.txt";

if (file_exists($fileName)) {
    $file_array = file($fileName);
} else {
    die("Файл $fileName не найден.");
}

echo '<table border="1" cellpadding="10">';

foreach ($file_array as $line) {
    $line = rtrim($line, "| \n");

    $line = str_replace('|', '</td><td>', $line);

    $line = preg_replace(
        '/([^\s]+@[^\s]+)/',
        '<a href="mailto:$1">$1</a>',
        $line
    );

    echo "<tr><td>{$line}</td></tr>";
}

echo '</table>';

$lastModified = date("d-m-Y H:i:s", filemtime($fileName));
echo "<p>Последняя модификация файла: $lastModified</p>";
?>
