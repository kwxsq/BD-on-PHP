<?php
$name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : "Тестируемый";

$answers = [
    isset($_POST['q1']) ? (int)$_POST['q1'] : 0,
    isset($_POST['q2']) ? (int)$_POST['q2'] : 0,
    isset($_POST['q3']) ? (int)$_POST['q3'] : 0,
];

$correct_answers = [
    3,
    3,
    3
];

$correct_count = 0;
for ($i = 0; $i < count($correct_answers); $i++) {
    if ($answers[$i] == $correct_answers[$i]) {
        $correct_count++;
    }
}

$score = '';
switch ($correct_count) {
    case 3:
        $score = "Отлично! Все ответы правильные!";
        break;
    case 2:
        $score = "Хорошо, вы ответили правильно на 2 из 3 вопросов.";
        break;
    case 1:
        $score = "Вы ответили правильно только на 1 вопрос. Попробуйте еще раз!";
        break;
    default:
        $score = "К сожалению, вы не дали ни одного правильного ответа.";
        break;
}

echo "<h2>Результаты теста:</h2>";
echo "Имя: $name<br>";
echo "Правильные ответы: $correct_count из 3<br>";
echo "<strong>$score</strong>";
echo "<br><br><a href='5a.html'>Пройти тест заново</a>";
?>
