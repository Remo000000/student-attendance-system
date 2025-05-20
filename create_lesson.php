<!DOCTYPE html>
<html lang="kz">
<head>
    <meta charset="UTF-8">
    <title>Сабақ құру</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 20px;
        }
        form {
            background-color: #ffe6f0;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            margin: 0 auto;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #cc0066;
            color: white;
            font-weight: bold;
            border: none;
        }
    </style>
</head>
<body>

<h2 style="text-align:center;">Жаңа сабақ құру</h2>

<form action="create_lesson.php" method="post">
    <label>Сабақтың атауы:</label>
    <input type="text" name="name" required>

    <label>Аудитория:</label>
    <input type="text" name="auditorium" required>

    <label>Күні:</label>
    <input type="date" name="lesson_date" required>

    <button type="submit">Сабақ құру</button>
</form>

</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Подключение к БД
    $conn = new mysqli('localhost', 'root', '', 'attendance_system');

    if ($conn->connect_error) {
        die("Қате: " . $conn->connect_error);
    }

    // Получаем данные из формы
    $name = $conn->real_escape_string($_POST['name']);
    $auditorium = $conn->real_escape_string($_POST['auditorium']);
    $lesson_date = $conn->real_escape_string($_POST['lesson_date']);

    // Добавляем урок
    $sql = "INSERT INTO lessons (name, auditorium, lesson_date, created_at)
            VALUES ('$name', '$auditorium', '$lesson_date', NOW())";

    if ($conn->query($sql) === TRUE) {
        $lesson_id = $conn->insert_id; // Берем ID только что созданного урока

        // Подключаем библиотеку QR
        include 'php-qrcode-master/lib/full/qrlib.php';

        // Генерируем QR
        $path = 'images/';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $path . $lesson_id . '.png';
        $data = "LessonID:" . $lesson_id;

        QRcode::png($data, $file, QR_ECLEVEL_L, 5);

        // Показать сообщение об успехе и QR-код
        echo "<div style='background-color: #ffe6f0; padding: 10px; border-radius: 8px; color: #cc0066; font-weight: bold; margin: 20px; text-align: center;'>
            Сабақ сәтті құрылды! Сабақ ID: $lesson_id
            </div>";

        echo "<div style='text-align: center;'>
                <h3>QR-код:</h3>
                <img src='$file' alt='QR Code' style='border:1px solid #ccc; padding:10px; border-radius:8px;'>
              </div>";

    } else {
        echo "Қате: " . $conn->error;
    }

    $conn->close();
}
?>