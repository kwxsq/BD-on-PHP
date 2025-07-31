<?php
require_once 'db.php';
require_once 'functions.php';

$db = require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['student_id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $phone = $_POST['phone'];

    try {
        $stmt = $db->prepare("UPDATE Студенты SET ФИО = ?, возраст = ?, телефон = ? WHERE id = ?");
        $stmt->execute([$name, $age, $phone, $id]);

        header("Location: ?action=view&table=Студенты");
        exit();
    } catch (PDOException $e) {
        die("<p class='error'>Ошибка: {$e->getMessage()}</p>");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['student_id'])) {
    $id = $_GET['student_id'];
    $stmt = $db->prepare("SELECT ФИО, возраст, телефон FROM Студенты WHERE id = ?");
    $stmt->execute([$id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($student);
    exit();
}

$students = $db->query("SELECT id, ФИО FROM Студенты")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать Студента</title>
    <link rel="stylesheet" href="style.css">
    <script>
        async function fetchStudentData(studentId) {
            if (!studentId) return;

            const response = await fetch(`edit_students.php?student_id=${studentId}`);
            const data = await response.json();

            document.getElementById('name').value = data.ФИО || '';
            document.getElementById('age').value = data.возраст || '';
            document.getElementById('phone').value = data.телефон || '';
        }
    </script>
</head>
<body>
<div class="container">
    <h1>Редактировать студента</h1>
    <form method="post">
        <label for="student_id">Выберите студента:</label>
        <select id="student_id" name="student_id" required onchange="fetchStudentData(this.value)">
            <option value="">Выберите студента</option>
            <?php foreach ($students as $student): ?>
                <option value="<?php echo $student['id']; ?>"><?php echo $student['ФИО']; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="name">ФИО:</label>
        <input type="text" id="name" name="name" required>

        <label for="age">Возраст:</label>
        <input type="number" id="age" name="age" min="16" max="100" required>

        <label for="phone">Телефон:</label>
        <input type="tel" id="phone" name="phone" pattern="^[0-9\s\-\+\(\)]*$" placeholder="Пример: +1 (234) 567-8901" required>

        <button type="submit">Сохранить</button>
    </form>

    <a href="index.php?action=view&table=Студенты">Вернуться к таблице</a>

</div>
</body>
</html>
