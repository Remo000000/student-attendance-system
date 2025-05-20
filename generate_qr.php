<?php
// Подключение к базе данных
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "attendance_system"; // замени на своё имя базы

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("<div style='background-color: #ffcccc; padding: 10px; border-radius: 5px;'>Ошибка подключения: " . $conn->connect_error . "</div>");
}

// Проверяем, передан ли ID урока
if (!isset($_GET['lesson_id'])) {
    die("<div style='background-color: #ffcccc; padding: 10px; border-radius: 5px;'>Ошибка: ID урока не указан.</div>");
}

$lesson_id = intval($_GET['lesson_id']);

// Проверяем, существует ли урок в базе
$sql = "SELECT * FROM lessons WHERE id = $lesson_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    // Урока нет — создаем его
    $create_lesson_sql = "INSERT INTO lessons (id, name, created_at) VALUES ($lesson_id, 'Урок $lesson_id', NOW())";
    if ($conn->query($create_lesson_sql) === TRUE) {
        echo "<div style='background-color: #ffe6f0; padding: 10px; border-radius: 8px; color: #cc0066; font-weight: bold; margin-bottom: 20px;'>
        Новый урок с ID $lesson_id успешно создан!
        </div>";
    } else {
        die("<div style='background-color: #ffcccc; padding: 10px; border-radius: 5px;'>Ошибка при создании урока: " . $conn->error . "</div>");
    }
} else {
    echo "<div style='background-color: #e6f7ff; padding: 10px; border-radius: 8px; color: #0073e6; font-weight: bold; margin-bottom: 20px;'>
    Урок с ID $lesson_id уже существует.
    </div>";
}

// Подключаем библиотеку для создания QR
include 'php-qrcode-master/lib/full/qrlib.php'; // укажи правильный путь

// Текст для QR-кода
$data = "LessonID:" . $lesson_id;

// Путь для сохранения QR
$path = 'images/';
if (!file_exists($path)) {
    mkdir($path, 0777, true);
}

$file = $path . $lesson_id . '.png';

// Генерация QR-кода
QRcode::png($data, $file, QR_ECLEVEL_L, 5);

// Вывод QR-кода на экран
echo "<h3 style='color: #333;'>QR-код для урока:</h3>";
echo "<img src='$file' alt='QR Code' style='border: 1px solid #ccc; padding: 10px; border-radius: 8px;'>";
?>